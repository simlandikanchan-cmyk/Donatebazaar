<?php
require 'vendor/autoload.php';
$fs = new \Illuminate\Filesystem\Filesystem();
$compiler = new \Illuminate\View\Compilers\BladeCompiler($fs, sys_get_temp_dir().'/blade_cache');
$src = file_get_contents('resources/views/public/show.blade.php');
try {
    $compiler->compile($src);
    echo "BLADE_OK\n";
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
