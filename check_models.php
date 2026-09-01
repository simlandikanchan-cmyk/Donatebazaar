<?php
$d = json_decode(file_get_contents('schema_report.json'), true);
foreach ($d['models'] as $m) {
    if (in_array($m['class'], ['User', 'Campaign', 'Donation', 'Organization', 'Event', 'Blog', 'Volunteer', 'Celebrity', 'JobPost', 'Coupon', 'ProductReservation', 'GiftCard', 'Refund', 'PayoutAccount', 'CampaignSettlement', 'SettlementItem', 'Wallet', 'WalletTransaction', 'KycVerification', 'FundraiserLevel'])) {
        echo "=== " . $m['class'] . " ===\n";
        echo "Table: " . $m['table'] . "\n";
        echo "Fillable: " . implode(', ', $m['fillable']) . "\n";
        echo "Relationships:\n";
        foreach ($m['relationships'] as $r) {
            echo "  - " . $r['name'] . ": " . $r['type'] . "(" . $r['related'] . ")";
            if ($r['foreignKey']) echo " FK=" . $r['foreignKey'];
            if ($r['pivot']) echo " PIVOT=" . $r['pivot'];
            echo "\n";
        }
        echo "\n";
    }
}
