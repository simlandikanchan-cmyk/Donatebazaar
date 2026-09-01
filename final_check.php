<?php
$d = json_decode(file_get_contents('schema_report.json'), true);

echo "Total tables: " . count($d['tables']) . "\n";
echo "Total models: " . count($d['models']) . "\n\n";

$noPk = [];
foreach ($d['tables'] as $t) {
    if (!$t['primaryKey']) $noPk[] = $t['name'];
}
echo "Tables without primaryKey detected: " . count($noPk) . "\n";
echo implode(', ', $noPk) . "\n\n";

$pivotTables = [];
foreach ($d['tables'] as $t) {
    if ($t['isPivot']) $pivotTables[] = $t['name'];
}
echo "Pivot tables: " . count($pivotTables) . "\n";
echo implode(', ', $pivotTables) . "\n\n";

echo "=== USERS ===";
echo json_encode($d['tables'][array_search('users', array_column($d['tables'], 'name'))], JSON_PRETTY_PRINT);
