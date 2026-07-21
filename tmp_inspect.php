<?php

$c = \App\Models\Campaign::where('slug', 'help-campaign')->first();

if (! $c) {
    echo "CAMPAIGN NOT FOUND\n";
    exit;
}

echo "Campaign: " . $c->title . " (id=" . $c->id . ") state=" . $c->campaign_state . "\n";
echo "product_reservations table exists: " . (\Schema::hasTable('product_reservations') ? 'YES' : 'NO') . "\n";

$ps = \App\Models\CampaignProduct::where('campaign_id', $c->id)->get();
echo "Product rows: " . $ps->count() . "\n";

foreach ($ps as $p) {
    echo "  #" . $p->id
        . " name=" . $p->name
        . " approval=" . $p->approval_status
        . " active=" . ($p->is_active ? '1' : '0')
        . " rem=" . $p->remaining_quantity
        . " source=" . $p->source . "\n";
}
