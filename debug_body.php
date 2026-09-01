<?php
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
echo "BODY:\n";
echo $body;
echo "\n\n";

preg_match_all('/\$table->([a-zA-Z_][a-zA-Z0-9_]*)\(([^)]*(?:\([^)]*\)[^)]*)*)\)(.*?)(?=\$table->|\Z)/', $body, $colMatches, PREG_SET_ORDER);
echo "Matches: " . count($colMatches[0]) . "\n";
foreach ($colMatches[0] as $m) {
    echo "MATCH: $m\n";
}
