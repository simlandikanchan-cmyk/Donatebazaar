<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Str;

$migrationsPath = __DIR__ . '/database/migrations';
$modelsPath = __DIR__ . '/app/Models';

$report = ['tables' => [], 'models' => []];
$tables = [];

// ========== READ MIGRATIONS ==========
$migrationFiles = glob($migrationsPath . '/*.php');

foreach ($migrationFiles as $file) {
    $content = file_get_contents($file);
    
    // Find all Schema::create/table operations
    preg_match_all('/Schema::(create|table)\([\'"`]([^\'"`]+)[\'"`]\s*,\s*function\s*\([^)]+\)\s*\{/', $content, $schemaMatches, PREG_SET_ORDER);
    
    foreach ($schemaMatches as $match) {
        $tableName = $match[2];
        $startPos = strpos($content, $match[0]) + strlen($match[0]);
        
        // Find matching closing brace
        $braceCount = 1;
        $pos = $startPos;
        while ($braceCount > 0 && $pos < strlen($content)) {
            if ($content[$pos] === '{') $braceCount++;
            elseif ($content[$pos] === '}') $braceCount--;
            $pos++;
        }
        
        $body = substr($content, $startPos, $pos - $startPos - 1);
        
        if (!isset($tables[$tableName])) {
            $tables[$tableName] = [
                'name' => $tableName,
                'columns' => [],
                'primaryKey' => null,
                'foreignKeys' => [],
                'indexes' => [],
                'isPivot' => false
            ];
        }
        
        // Split by $table->
        $parts = explode('$table->', $body);
        
        foreach ($parts as $part) {
            if (empty($part)) continue;
            
            // Extract method name
            preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $part, $m);
            if (!$m) continue;
            $method = $m[1];
            
            // Skip non-column methods
            if (in_array($method, ['foreign', 'references', 'on', 'onDelete', 'onUpdate', 'index', 'unique', 'primary', 'engine', 'charset', 'collation', 'dropForeign', 'dropIndex', 'dropColumn', 'renameColumn', 'changeColumn', 'drop', 'dropIfExists', 'default', 'dropUnique', 'dropIndex', 'dropForeign', 'foreignIdFor'])) {
                continue;
            }
            
            // Extract first argument (column name) if any
            preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*\s*\(\s*([^)]*)\)/', $part, $argMatch);
            $argsStr = isset($argMatch[1]) ? $argMatch[1] : '';
            
            // Extract column name from first arg
            preg_match('/[\'"`]?([^\'"`,)]+)[\'"`]?/', $argsStr, $nameMatch);
            $rawArg = isset($nameMatch[1]) ? $nameMatch[1] : '';
            
            // Determine column name
            if (in_array($method, ['id', 'bigIncrements', 'increments', 'uuid', 'foreignUlid'])) {
                $colName = 'id';
            } elseif ($method === 'foreignId' && !empty($rawArg)) {
                $colName = $rawArg;
            } elseif ($method === 'timestamps') {
                $colName = null; // creates created_at, updated_at
            } elseif ($method === 'softDeletes') {
                $colName = 'deleted_at';
            } elseif ($method === 'rememberToken') {
                $colName = 'remember_token';
            } elseif ($method === 'uuidMorphs' && !empty($rawArg)) {
                $colName = null; // creates {name}_id and {name}_type
            } elseif ($method === 'morphs' && !empty($rawArg)) {
                $colName = null;
            } elseif ($method === 'nullableMorphs' && !empty($rawArg)) {
                $colName = null;
            } elseif (!empty($rawArg)) {
                $colName = $rawArg;
            } else {
                continue; // Can't determine column name
            }
            
            // Determine type
            $baseType = $method;
            if (in_array($method, ['string', 'char', 'varchar'])) {
                preg_match('/,\s*(\d+)/', $argsStr, $lenMatch);
                $baseType = $method . '(' . ($lenMatch[1] ?? 255) . ')';
            } elseif ($method === 'enum') {
                $baseType = 'enum';
            } elseif ($method === 'decimal') {
                preg_match_all('/(\d+)/', $argsStr, $decMatch);
                $baseType = 'decimal(' . implode(',', $decMatch[0]) . ')';
            } elseif (in_array($method, ['bigIncrements', 'increments', 'unsignedBigInteger', 'unsignedInteger', 'unsignedTinyInteger', 'unsignedSmallInteger'])) {
                $baseType = $method;
            } elseif ($method === 'foreignId') {
                $baseType = 'foreignId';
            }
            
            $nullable = str_contains($part, '->nullable');
            $unsigned = str_contains($part, '->unsigned');
            $unique = str_contains($part, '->unique');
            
            $default = null;
            if (preg_match('/->default\(([^)]+)\)/', $part, $defMatch)) {
                $default = trim($defMatch[1], " '\"`");
            }
            
            // For timestamps/softDeletes/uuidMorphs, add columns directly
            if ($method === 'timestamps') {
                $t1 = ['name' => 'created_at', 'type' => 'timestamp', 'nullable' => false, 'unsigned' => false, 'unique' => false, 'default' => null];
                $t2 = ['name' => 'updated_at', 'type' => 'timestamp', 'nullable' => false, 'unsigned' => false, 'unique' => false, 'default' => null];
                foreach ([$t1, $t2] as $t) {
                    $exists = false;
                    foreach ($tables[$tableName]['columns'] as $existing) {
                        if ($existing['name'] === $t['name']) { $exists = true; break; }
                    }
                    if (!$exists) $tables[$tableName]['columns'][] = $t;
                }
                continue;
            } elseif ($method === 'softDeletes') {
                $colInfo = ['name' => 'deleted_at', 'type' => 'timestamp', 'nullable' => true, 'unsigned' => false, 'unique' => false, 'default' => null];
                $exists = false;
                foreach ($tables[$tableName]['columns'] as $existing) {
                    if ($existing['name'] === 'deleted_at') { $exists = true; break; }
                }
                if (!$exists) $tables[$tableName]['columns'][] = $colInfo;
                continue;
            } elseif ($method === 'uuidMorphs' && !empty($rawArg)) {
                $colInfo1 = ['name' => $rawArg . '_id', 'type' => 'uuid', 'nullable' => false, 'unsigned' => false, 'unique' => false, 'default' => null];
                $colInfo2 = ['name' => $rawArg . '_type', 'type' => 'string', 'nullable' => false, 'unsigned' => false, 'unique' => false, 'default' => null];
                foreach ([$colInfo1, $colInfo2] as $t) {
                    $exists = false;
                    foreach ($tables[$tableName]['columns'] as $existing) {
                        if ($existing['name'] === $t['name']) { $exists = true; break; }
                    }
                    if (!$exists) $tables[$tableName]['columns'][] = $t;
                }
                continue;
            }
            
            if ($colName === null) continue;
            
            // Check primary key
            if (str_contains($part, '->primary') || in_array($method, ['id', 'bigIncrements', 'increments'])) {
                $tables[$tableName]['primaryKey'] = $colName;
            }
            
            // Add column if not exists
            $exists = false;
            foreach ($tables[$tableName]['columns'] as $existing) {
                if ($existing['name'] === $colName) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $tables[$tableName]['columns'][] = [
                    'name' => $colName,
                    'type' => $baseType,
                    'nullable' => $nullable,
                    'unsigned' => $unsigned,
                    'unique' => $unique,
                    'default' => $default
                ];
            }
        }
        
        // Extract foreign keys - traditional style (handles multi-line chains)
        preg_match_all('/\$table->foreign\([\'"`]?([^\'"`)]+)[\'"`]?\)\s*->references\([\'"`]?([^\'"`)]+)[\'"`]?\)\s*->on\([\'"`]?([^\'"`)]+)[\'"`]?\)\s*(?:->onDelete\([\'"`]?([^\'"`)]+)[\'"`]?\))?\s*(?:->onUpdate\([\'"`]?([^\'"`)]+)[\'"`]?\))?/', $body, $fkMatches, PREG_SET_ORDER);
        
        foreach ($fkMatches as $fk) {
            $onDelete = isset($fk[4]) ? trim($fk[4], " '\"`") : null;
            $onUpdate = isset($fk[5]) ? trim($fk[5], " '\"`") : null;
            $tables[$tableName]['foreignKeys'][] = [
                'from' => $fk[1],
                'to' => $fk[2],
                'to_table' => $fk[3],
                'onDelete' => $onDelete,
                'onUpdate' => $onUpdate
            ];
        }
        
        // Extract foreignId with constrained (handles multi-line chains, includes onDelete/onUpdate)
        preg_match_all('/\$table->foreignId\([\'"`]?([^\'"`)]+)[\'"`]?\)\s*(?:->constrained\([\'"`]?([^\'"`)]+)[\'"`]?\))?\s*(?:->cascadeOnDelete\(\)|\->onDelete\([\'"`]?(?:cascade|restrict|set\s+null|no\s+action)[\'"`]?\))?\s*(?:->restrictOnDelete\(\))?\s*(?:->nullOnDelete\(\))?\s*(?:->noActionOnDelete\(\))?\s*(?:->cascadeOnUpdate\(\))?\s*(?:->restrictOnUpdate\(\))?\s*(?:->noActionOnUpdate\(\))?/', $body, $constrainedMatches, PREG_SET_ORDER);
        
        foreach ($constrainedMatches as $cm) {
            $fromCol = $cm[1];
            $toTable = $cm[2] ?? null;
            
            if (!$toTable) {
                $toTable = preg_replace('/_id$/', '', $fromCol);
                $toTable = Str::plural($toTable);
            }
            
            $onDelete = null;
            if (preg_match('/->(cascadeOnDelete|restrictOnDelete|nullOnDelete|noActionOnDelete|onDelete)\s*\([\'"`]?(?:cascade|restrict|set\s+null|no\s+action)?[\'"`]?\)/', $body, $odMatch)) {
                if ($odMatch[1] === 'onDelete') {
                    preg_match('/->onDelete\([\'"`]?(cascade|restrict|set\s+null|no\s+action)[\'"`]?\)/', $body, $odMatch2);
                    $onDelete = isset($odMatch2[1]) ? $odMatch2[1] : null;
                } else {
                    $onDelete = $odMatch[1];
                }
            }
            
            // Check if already exists
            $exists = false;
            foreach ($tables[$tableName]['foreignKeys'] as $existing) {
                if ($existing['from'] === $fromCol) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $tables[$tableName]['foreignKeys'][] = [
                    'from' => $fromCol,
                    'to' => 'id',
                    'to_table' => $toTable,
                    'onDelete' => $onDelete,
                    'onUpdate' => null
                ];
            }
        }
        
        // Extract indexes
        preg_match_all('/\$table->(?:index|unique)\(\s*\[([^\]]+)\]\s*,\s*[\'"`]?([^\'"`)]+)[\'"`]?\s*\)/', $body, $idxMatches, PREG_SET_ORDER);
        
        foreach ($idxMatches as $idx) {
            $columns = array_map('trim', explode(',', $idx[1]));
            $columns = array_map(function($c) { return trim($c, " '\"`"); }, $columns);
            $tables[$tableName]['indexes'][] = [
                'name' => $idx[2] ?? implode('_', $columns) . '_index',
                'columns' => $columns
            ];
        }
    }
}

// Identify pivot tables
foreach ($tables as $tableName => &$table) {
    $fkCount = count($table['foreignKeys']);
    $hasId = false;
    foreach ($table['columns'] as $col) {
        if ($col['name'] === 'id') $hasId = true;
    }
    if ($fkCount === 2 && !$hasId && str_contains($tableName, '_')) {
        $table['isPivot'] = true;
    }
}

$report['tables'] = array_values($tables);

// ========== READ MODELS ==========
$modelFiles = glob($modelsPath . '/*.php');

foreach ($modelFiles as $file) {
    $content = file_get_contents($file);
    $className = basename($file, '.php');
    
    $modelInfo = [
        'class' => $className,
        'table' => null,
        'primaryKey' => null,
        'fillable' => [],
        'guarded' => [],
        'relationships' => []
    ];
    
    // Extract $table
    if (preg_match('/protected\s+\$table\s*=\s*[\'"`]([^\'"`]+)[\'"`]/', $content, $m)) {
        $modelInfo['table'] = $m[1];
    } else {
        $modelInfo['table'] = Str::snake(Str::plural($className));
    }
    
    // Extract $primaryKey
    if (preg_match('/protected\s+\$primaryKey\s*=\s*[\'"`]([^\'"`]+)[\'"`]/', $content, $m)) {
        $modelInfo['primaryKey'] = $m[1];
    }
    
    // Extract $fillable
    if (preg_match('/protected\s+\$fillable\s*=\s*\[([^\]]+)\]/s', $content, $m)) {
        preg_match_all('/[\'"`]([^\'"`]+)[\'"`]/', $m[1], $fields);
        $modelInfo['fillable'] = $fields[1];
    }
    
    // Extract $guarded
    if (preg_match('/protected\s+\$guarded\s*=\s*\[([^\]]+)\]/s', $content, $m)) {
        $guardedStr = trim($m[1]);
        if ($guardedStr === '*' || $guardedStr === '') {
            $modelInfo['guarded'] = ['*'];
        } else {
            preg_match_all('/[\'"`]([^\'"`]+)[\'"`]/', $m[1], $fields);
            $modelInfo['guarded'] = $fields[1];
        }
    }
    
    // Extract relationships - only methods that actually return a relationship
    preg_match_all('/public\s+function\s+(\w+)\s*\([^)]*\)\s*(?::[^{]+)?\s*\{([^}]+)\}/', $content, $relMatches, PREG_SET_ORDER);
    
    foreach ($relMatches as $rel) {
        $methodName = $rel[1];
        $body = $rel[2];
        
        // Only include if body contains a known relationship call
        if (!preg_match('/return\s+\$this->(hasOne|hasMany|belongsTo|belongsToMany|morphOne|morphMany|morphTo|morphToMany|hasManyThrough|hasOneThrough|morphTo|morphedByMany)\(/', $body)) {
            continue;
        }
        
        $relInfo = [
            'name' => $methodName,
            'type' => null,
            'related' => null,
            'foreignKey' => null,
            'localKey' => null,
            'pivot' => null,
            'otherKey' => null
        ];
        
        if (preg_match('/return\s+\$this->([a-zA-Z_][a-zA-Z0-9_]*)\(([^)]+)\)/', $body, $typeMatch)) {
            $relType = $typeMatch[1];
            $relInfo['type'] = $relType;
            $args = $typeMatch[2];
            $argParts = array_map('trim', explode(',', $args));
            
            if (in_array($relType, ['hasOne', 'hasMany'])) {
                $relInfo['related'] = trim($argParts[0] ?? '', " '\"`");
                $relInfo['foreignKey'] = isset($argParts[1]) ? trim($argParts[1], " '\"`") : null;
                $relInfo['localKey'] = isset($argParts[2]) ? trim($argParts[2], " '\"`") : null;
            } elseif ($relType === 'belongsTo') {
                $relInfo['related'] = trim($argParts[0] ?? '', " '\"`");
                $relInfo['foreignKey'] = isset($argParts[1]) ? trim($argParts[1], " '\"`") : null;
                $relInfo['ownerKey'] = isset($argParts[2]) ? trim($argParts[2], " '\"`") : null;
            } elseif (in_array($relType, ['belongsToMany', 'morphToMany'])) {
                $relInfo['related'] = trim($argParts[0] ?? '', " '\"`");
                $relInfo['pivot'] = isset($argParts[1]) ? trim($argParts[1], " '\"`") : null;
                $relInfo['foreignKey'] = isset($argParts[2]) ? trim($argParts[2], " '\"`") : null;
                $relInfo['otherKey'] = isset($argParts[3]) ? trim($argParts[3], " '\"`") : null;
            } elseif (in_array($relType, ['hasManyThrough', 'hasOneThrough'])) {
                $relInfo['related'] = trim($argParts[0] ?? '', " '\"`");
                $relInfo['through'] = isset($argParts[1]) ? trim($argParts[1], " '\"`") : null;
                $relInfo['firstKey'] = isset($argParts[2]) ? trim($argParts[2], " '\"`") : null;
                $relInfo['secondKey'] = isset($argParts[3]) ? trim($argParts[3], " '\"`") : null;
            } elseif (in_array($relType, ['morphOne', 'morphMany'])) {
                $relInfo['related'] = trim($argParts[0] ?? '', " '\"`");
                $morphArgs = explode(',', $argParts[1] ?? '');
                $relInfo['morphable'] = isset($morphArgs[0]) ? trim($morphArgs[0], " '\"`") : null;
            }
        }
        
        $modelInfo['relationships'][] = $relInfo;
    }
    
    $report['models'][] = $modelInfo;
}

// Clean up foreign keys
foreach ($report['tables'] as &$table) {
    foreach ($table['foreignKeys'] as &$fk) {
        if (isset($fk['to_table'])) {
            $toTable = $fk['to_table'];
            unset($fk['to_table']);
            if ($fk['to'] === 'id' || empty($fk['to'])) {
                $fk['to'] = 'id';
            }
            $fk['to_table'] = $toTable;
        }
    }
    
    // Remove duplicate foreign keys and indexes
    $table['foreignKeys'] = array_values(array_unique($table['foreignKeys'], SORT_REGULAR));
    $table['indexes'] = array_values(array_unique($table['indexes'], SORT_REGULAR));
}

// Output
$json = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__ . '/schema_report.json', $json);
echo "Schema report generated: schema_report.json\n";
echo "Tables found: " . count($report['tables']) . "\n";
echo "Models found: " . count($report['models']) . "\n";
