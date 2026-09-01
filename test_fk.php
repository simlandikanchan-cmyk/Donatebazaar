<?php
$body = "
            \$table->foreignId('author_id')
                ->constrained('users')
                ->onDelete('cascade');
";

preg_match_all('/\$table->foreignId\([\'"`]?([^\'"`)]+)[\'"`]?\)(?:->constrained\([\'"`]?([^\'"`)]+)[\'"`]?\))?(?:->cascadeOnDelete\(\))?(?:->restrictOnDelete\(\))?(?:->nullOnDelete\(\))?(?:->noActionOnDelete\(\))?(?:->cascadeOnUpdate\(\))?(?:->restrictOnUpdate\(\))?(?:->noActionOnUpdate\(\))?/', $body, $matches, PREG_SET_ORDER);

var_dump($matches);
