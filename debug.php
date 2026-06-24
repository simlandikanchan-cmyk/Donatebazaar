<?php
$c = \App\Models\Campaign::where('slug', 'new-campaign')->first();
echo "Campaign: " . ($c->title ?? 'NOT FOUND') . PHP_EOL;

$p = $c->products()->with('categoryProduct')->first();
echo "Product: " . ($p->name ?? 'NONE') . PHP_EOL;
echo "Product image: " . ($p->image ?? 'NULL') . PHP_EOL;
echo "CategoryProduct ID: " . ($p->categoryProduct ? $p->categoryProduct->id : 'NULL') . PHP_EOL;
echo "CategoryProduct image: " . ($p->categoryProduct->image ?? 'NULL') . PHP_EOL;
echo "CategoryProduct image_url: " . ($p->categoryProduct->image_url ?? 'NULL') . PHP_EOL;