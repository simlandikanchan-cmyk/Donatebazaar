<?php
// Test simpler column extraction
$content = file_get_contents('database/migrations/0001_01_01_000000_create_users_table.php');
preg_match('/Schema::create\([\'"`]([^\'"`]+)[\'"`]\s*,\s*function\s*\([^)]+\)\s*\{/', $content, $match);
$startPos = strpos($content, $match[0]) + strlen($match[0]);

$braceCount = 1;
$pos = $startPos;
while ($braceCount > 0 && $pos < strlen($content)) {
    if ($content[$pos] === '{') $braceCount++;
    elseif ($content[$pos] === '}') $braceCount--;
    $pos++;
}

$body = substr($content, $startPos, $pos - $startPos - 1);

// Split by $table->
$parts = explode('$table->', $body);
$columns = [];
foreach ($parts as $part) {
    if (empty($part)) continue;
    
    // Extract method name
    preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $part, $m);
    if (!$m) continue;
    $method = $m[1];
    
    // Skip non-column methods
    if (in_array($method, ['foreign', 'references', 'on', 'onDelete', 'onUpdate', 'index', 'unique', 'primary', 'engine', 'charset', 'collation', 'dropForeign', 'dropIndex', 'dropColumn', 'renameColumn', 'changeColumn', 'drop', 'dropIfExists', 'default'])) {
        continue;
    }
    
    // Extract first argument (column name)
    preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s*\(\s*[\'"`]?([^\'"`,)]+)[\'"`]?/', $part, $argMatch);
    if (!$argMatch) continue;
    $colName = $argMatch[2];
    
    // Extract modifiers until next $table->
    $modifiersStr = substr($part, strpos($part, $argMatch[0]) + strlen($argMatch[0]));
    $modifiersStr = preg_replace('/->\w+\([^)]*\)/', '', $modifiersStr);
    $modifiersStr = preg_replace('/->\w+/', '', $modifiersStr);
    
    $nullable = str_contains($part, '->nullable');
    $unsigned = str_contains($part, '->unsigned');
    $unique = str_contains($part, '->unique');
    
    $default = null;
    if (preg_match('/->default\(([^)]+)\)/', $part, $defMatch)) {
        $default = trim($defMatch[1], " '\"`");
    }
    
    $columns[] = [
        'name' => $colName,
        'method' => $method,
        'nullable' => $nullable,
        'unsigned' => $unsigned,
        'unique' => $unique,
        'default' => $default
    ];
}

echo json_encode($columns, JSON_PRETTY_PRINT);
