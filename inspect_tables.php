<?php
$d = json_decode(file_get_contents('schema_report.json'), true);
echo "USERS TABLE:\n";
echo json_encode($d['tables'][0], JSON_PRETTY_PRINT);
echo "\n\nCAMPAIGNS TABLE:\n";
foreach ($d['tables'] as $t) {
    if ($t['name'] === 'campaigns') {
        echo json_encode($t, JSON_PRETTY_PRINT);
        break;
    }
}
echo "\n\nDONATIONS TABLE:\n";
foreach ($d['tables'] as $t) {
    if ($t['name'] === 'donations') {
        echo json_encode($t, JSON_PRETTY_PRINT);
        break;
    }
}
