<?php
$d = json_decode(file_get_contents('schema_report.json'), true);

echo "=== DATABASE SCHEMA REPORT ===\n\n";

echo "TABLES (" . count($d['tables']) . "):\n";
echo str_repeat("-", 120) . "\n";
printf("%-35s %6s %6s %6s %-15s %-30s\n", "TABLE", "COLS", "FKS", "IDX", "PRIMARY KEY", "PIVOT");
echo str_repeat("-", 120) . "\n";

foreach ($d['tables'] as $t) {
    printf("%-35s %6d %6d %6s %-15s %-30s\n",
        $t['name'],
        count($t['columns']),
        count($t['foreignKeys']),
        count($t['indexes']),
        $t['primaryKey'] ?? '(none)',
        $t['isPivot'] ? 'YES' : 'no'
    );
}

echo "\n\nMODELS (" . count($d['models']) . "):\n";
echo str_repeat("-", 120) . "\n";
printf("%-30s %-30s %-40s %-30s\n", "CLASS", "TABLE", "PRIMARY KEY", "FILLABLE COUNT");
echo str_repeat("-", 120) . "\n";

foreach ($d['models'] as $m) {
    printf("%-30s %-30s %-40s %-30s\n",
        $m['class'],
        $m['table'],
        $m['primaryKey'] ?? '(none)',
        count($m['fillable'])
    );
}

echo "\n\n=== KEY TABLE DETAILS ===\n\n";

// Show details for a few key tables
$keyTables = ['users', 'campaigns', 'donations', 'organizations', 'events', 'blogs', 'wallets', 'wallet_transactions', 'campaign_settlements', 'settlement_items'];
foreach ($keyTables as $tn) {
    foreach ($d['tables'] as $t) {
        if ($t['name'] === $tn) {
            echo "--- $tn ---\n";
            echo "Columns (" . count($t['columns']) . "):\n";
            foreach ($t['columns'] as $c) {
                $flags = [];
                if ($c['nullable']) $flags[] = 'NULL';
                if ($c['unsigned']) $flags[] = 'UNSIGNED';
                if ($c['unique']) $flags[] = 'UNIQUE';
                if ($c['default']) $flags[] = 'DEFAULT ' . $c['default'];
                echo "  " . $c['name'] . " " . $c['type'] . " " . implode(' ', $flags) . "\n";
            }
            echo "Primary Key: " . ($t['primaryKey'] ?? 'none') . "\n";
            echo "Foreign Keys:\n";
            foreach ($t['foreignKeys'] as $fk) {
                echo "  " . $fk['from'] . " -> " . $fk['to'] . " (" . $fk['to_table'] . ")";
                if ($fk['onDelete']) echo " ON DELETE " . $fk['onDelete'];
                if ($fk['onUpdate']) echo " ON UPDATE " . $fk['onUpdate'];
                echo "\n";
            }
            echo "Indexes:\n";
            foreach ($t['indexes'] as $idx) {
                echo "  " . $idx['name'] . " (" . implode(', ', $idx['columns']) . ")\n";
            }
            echo "\n";
            break;
        }
    }
}

echo "\n=== ALL MODEL RELATIONSHIPS ===\n\n";
foreach ($d['models'] as $m) {
    if (empty($m['relationships'])) continue;
    echo "-- " . $m['class'] . " (" . $m['table'] . ") --\n";
    foreach ($m['relationships'] as $r) {
        echo "  " . $r['name'] . ": " . $r['type'] . "(" . $r['related'] . ")";
        if ($r['foreignKey']) echo " FK=" . $r['foreignKey'];
        if ($r['localKey']) echo " LK=" . $r['localKey'];
        if ($r['pivot']) echo " PIVOT=" . $r['pivot'];
        if ($r['otherKey']) echo " OK=" . $r['otherKey'];
        echo "\n";
    }
    echo "\n";
}

echo "\nFull JSON report saved to: schema_report.json\n";
?>
