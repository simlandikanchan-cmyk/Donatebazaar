<?php
$d = json_decode(file_get_contents('schema_report.json'), true);
$names = ['blog_tag', 'role_permission', 'post_tag', 'category_products', 'campaign_products', 'coupon_redemptions', 'volunteer_assignments', 'celebrity_campaign'];
foreach ($names as $name) {
    foreach ($d['tables'] as $t) {
        if ($t['name'] === $name) {
            echo "=== $name ===\n";
            echo "Columns: " . count($t['columns']) . "\n";
            echo "FKs: " . count($t['foreignKeys']) . "\n";
            echo "Has id: " . (in_array('id', array_column($t['columns'], 'name')) ? 'yes' : 'no') . "\n";
            echo json_encode($t, JSON_PRETTY_PRINT);
            echo "\n\n";
            break;
        }
    }
}
