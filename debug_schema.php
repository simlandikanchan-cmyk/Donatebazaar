<?php
$migrationsPath = __DIR__ . '/database/migrations';
$files = glob($migrationsPath . '/*.php');

$tables = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    preg_match_all('/Schema::(create|table)\([\'"`]([^\'"`]+)[\'"`]\s*,\s*function\s*\([^)]+\)\s*\{/', $content, $schemaMatches, PREG_SET_ORDER);
    
    foreach ($schemaMatches as $match) {
        $tableName = $match[2];
        $startPos = strpos($content, $match[0]) + strlen($match[0]);
        
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
                'foreignKeys' => [],
                'indexes' => []
            ];
        }
        
        // Extract columns
        preg_match_all('/\$table->([a-zA-Z_][a-zA-Z0-9_]*)\(([^)]*(?:\([^)]*\)[^)]*)*)\)(.*?)(?=\$table->|\Z)/', $body, $colMatches, PREG_SET_ORDER);
        
        foreach ($colMatches as $col) {
            $method = $col[1];
            $args = $col[2];
            $modifiers = $col[3] ?? '';
            
            if (in_array($method, ['foreign', 'references', 'on', 'onDelete', 'onUpdate', 'index', 'unique', 'primary', 'engine', 'charset', 'collation', 'dropForeign', 'dropIndex', 'dropColumn', 'renameColumn', 'changeColumn', 'drop', 'dropIfExists'])) {
                continue;
            }
            
            preg_match('/[\'"`]?([^\'"`,]+)[\'"`]?/', $args, $nameMatch);
            $colName = $nameMatch[1] ?? '';
            if (empty($colName)) continue;
            
            $exists = false;
            foreach ($tables[$tableName]['columns'] as $existing) {
                if ($existing['name'] === $colName) { $exists = true; break; }
            }
            if (!$exists) {
                $tables[$tableName]['columns'][] = ['name' => $colName, 'method' => $method];
            }
        }
        
        // Extract foreign keys
        preg_match_all('/\$table->foreign\([\'"`]?([^\'"`)]+)[\'"`]?\)->references\([\'"`]?([^\'"`)]+)[\'"`]?\)->on\([\'"`]?([^\'"`)]+)[\'"`]?\)(?:->onDelete\([\'"`]?([^\'"`)]+)[\'"`]?)?(?:->onUpdate\([\'"`]?([^\'"`)]+)[\'"`]?)?/', $body, $fkMatches, PREG_SET_ORDER);
        foreach ($fkMatches as $fk) {
            $tables[$tableName]['foreignKeys'][] = ['from' => $fk[1], 'to' => $fk[2], 'to_table' => $fk[3]];
        }
        
        // Extract indexes
        preg_match_all('/\$table->(?:index|unique)\(\s*\[([^\]]+)\]\s*,\s*[\'"`]?([^\'"`)]+)[\'"`]?\s*\)/', $body, $idxMatches, PREG_SET_ORDER);
        foreach ($idxMatches as $idx) {
            $columns = array_map('trim', explode(',', $idx[1]));
            $tables[$tableName]['indexes'][] = ['name' => $idx[2], 'columns' => $columns];
        }
    }
}

echo "Tables found: " . count($tables) . "\n";
foreach ($tables as $name => $info) {
    echo "- $name (" . count($info['columns']) . " cols, " . count($info['foreignKeys']) . " fks, " . count($info['indexes']) . " idxs)\n";
}
?>
