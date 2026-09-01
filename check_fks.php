<?php
$d = json_decode(file_get_contents('schema_report.json'), true);
foreach ($d['tables'] as $t) {
    if (in_array($t['name'], ['blogs', 'campaigns', 'users', 'donations', 'events'])) {
        echo "=== " . $t['name'] . " ===\n";
        echo "FKs:\n";
        foreach ($t['foreignKeys'] as $fk) {
            echo "  " . $fk['from'] . " -> " . $fk['to'] . " (" . $fk['to_table'] . ")";
            if ($fk['onDelete']) echo " ON DELETE " . $fk['onDelete'];
            echo "\n";
        }
        echo "\n";
    }
}
