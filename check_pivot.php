<?php
$d = json_decode(file_get_contents('schema_report.json'), true);
foreach ($d['tables'] as $t) {
    $fkCount = count($t['foreignKeys']);
    $hasId = false;
    foreach ($t['columns'] as $c) {
        if ($c['name'] === 'id') $hasId = true;
    }
    if ($fkCount === 2 && !$hasId) {
        echo $t['name'] . " (2 FKs, no id)\n";
    }
}
