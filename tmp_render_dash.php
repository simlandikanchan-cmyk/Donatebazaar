<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::create('/user/dashboard', 'GET'));

use App\Http\Controllers\DashboardController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$userId = (int)($argv[1] ?? 30);
$user = User::find($userId);
Auth::login($user);

$req = Request::create('/user/dashboard', 'GET');
$req->setUserResolver(function () use ($user) {
    return $user;
});
app()->instance('request', $req);

$html = app(DashboardController::class)->index($req)->render();
file_put_contents(__DIR__.'/storage/dash_render.html', $html);
echo 'ok '.strlen($html)."\n";
