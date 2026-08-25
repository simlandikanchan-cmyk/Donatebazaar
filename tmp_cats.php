<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = App\Models\Category::query()->withCount('campaigns')->get(['id','name','slug','icon','color']);
foreach ($rows as $r) {
    echo $r->id.' | '.($r->name).' | slug:'.$r->slug.' | icon:'.($r->icon ?? 'NULL').' | color:'.($r->color ?? 'NULL').' | campaigns:'.$r->campaigns_count.PHP_EOL;
}
