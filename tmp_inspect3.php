<?php

echo "DB: " . config('database.connections.mysql.database') . "\n";

$rows = DB::table('migrations')->where('migration', 'like', '%product_reservation%')->get();
foreach ($rows as $r) {
    echo $r->migration . " batch=" . $r->batch . "\n";
}

echo "--- all migrations count: " . DB::table('migrations')->count() . "\n";
echo "table exists check: " . (\Schema::hasTable('product_reservations') ? 'YES' : 'NO') . "\n";
