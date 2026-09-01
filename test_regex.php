<?php
$content = file_get_contents('database/migrations/0001_01_01_000000_create_users_table.php');
preg_match_all('/Schema::(create|table)\([\'"`]([^\'"`]+)[\'"`]\s*,\s*function\s*\([^)]+\)\s*\{/', $content, $matches);
var_dump($matches);
