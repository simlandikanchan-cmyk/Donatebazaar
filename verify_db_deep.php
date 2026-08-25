<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check all FK constraints with full detail
echo "=== ALL FOREIGN KEY CONSTRAINTS ===" . PHP_EOL;
$fks = DB::select("
    SELECT 
        kcu.TABLE_NAME,
        kcu.COLUMN_NAME,
        kcu.REFERENCED_TABLE_NAME,
        kcu.REFERENCED_COLUMN_NAME,
        rc.UPDATE_RULE,
        rc.DELETE_RULE,
        kcu.CONSTRAINT_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
    JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
        ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
        AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
    WHERE kcu.TABLE_SCHEMA = DATABASE()
    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
    ORDER BY kcu.TABLE_NAME, kcu.ORDINAL_POSITION
");
foreach ($fks as $fk) {
    echo "{$fk->TABLE_NAME}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}.{$fk->REFERENCED_COLUMN_NAME} [ON DELETE {$fk->DELETE_RULE}] ({$fk->CONSTRAINT_NAME})" . PHP_EOL;
}

// Check cascade deletes specifically
echo PHP_EOL . "=== CASCADE DELETE FKS (FINANCIAL/CRITICAL) ===" . PHP_EOL;
$cascadeFks = DB::select("
    SELECT 
        kcu.TABLE_NAME,
        kcu.COLUMN_NAME,
        kcu.REFERENCED_TABLE_NAME,
        rc.DELETE_RULE
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
    JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
        ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
        AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
    WHERE kcu.TABLE_SCHEMA = DATABASE()
    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
    AND rc.DELETE_RULE = 'CASCADE'
    ORDER BY kcu.TABLE_NAME
");
if (count($cascadeFks) > 0) {
    foreach ($cascadeFks as $fk) {
        echo "CASCADE: {$fk->TABLE_NAME}.{$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME} (ON DELETE CASCADE)" . PHP_EOL;
    }
} else {
    echo "No CASCADE deletes found." . PHP_EOL;
}

// Check for soft deletes columns
echo PHP_EOL . "=== SOFT DELETE COLUMNS ===" . PHP_EOL;
$softDeletes = DB::select("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME = 'deleted_at' ORDER BY TABLE_NAME");
foreach ($softDeletes as $s) {
    echo "{$s->TABLE_NAME}.deleted_at" . PHP_EOL;
}

// Check wallet_transaction_references table
echo PHP_EOL . "=== wallet_transaction_references ===" . PHP_EOL;
$exists = Schema::hasTable('wallet_transaction_references');
echo "Table exists: " . ($exists ? "YES" : "NO") . PHP_EOL;
if ($exists) {
    $cols = DB::select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'wallet_transaction_references' ORDER BY ORDINAL_POSITION");
    foreach ($cols as $c) {
        echo "{$c->COLUMN_NAME} | type={$c->DATA_TYPE} | nullable={$c->IS_NULLABLE} | default={$c->COLUMN_DEFAULT}" . PHP_EOL;
    }
    $idx = DB::select("SELECT INDEX_NAME, COLUMN_NAME, NON_UNIQUE FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'wallet_transaction_references' ORDER BY INDEX_NAME, SEQ_IN_INDEX");
    foreach ($idx as $i) {
        echo "INDEX: {$i->INDEX_NAME} on {$i->COLUMN_NAME} (unique=" . (!$i->NON_UNIQUE ? 'YES' : 'NO') . ")" . PHP_EOL;
    }
}

// Check migration log vs actual
echo PHP_EOL . "=== MIGRATIONS IN DB LOG ===" . PHP_EOL;
$migrations = DB::select("SELECT migration, batch FROM migrations ORDER BY migration");
foreach ($migrations as $m) {
    echo $m->migration . " (batch {$m->batch})" . PHP_EOL;
}

// Check the 2026_08_12 migrations in log
echo PHP_EOL . "=== 2026_08_12 MIGRATIONS IN LOG ===" . PHP_EOL;
$newMigrations = DB::select("SELECT migration, batch FROM migrations WHERE migration LIKE '2026_08_12%' ORDER BY migration");
if (count($newMigrations) > 0) {
    foreach ($newMigrations as $m) {
        echo "IN LOG: {$m->migration} (batch {$m->batch})" . PHP_EOL;
    }
} else {
    echo "NONE of the 2026_08_12 migrations are in the DB migration log." . PHP_EOL;
    echo "But their schema changes ARE present in the DB — this indicates direct DB modification outside Artisan." . PHP_EOL;
}

// Check for any tables with CASCADE that shouldn't have it
echo PHP_EOL . "=== ALL CASCADED TABLES (potential data loss risk) ===" . PHP_EOL;
$allCascade = DB::select("
    SELECT DISTINCT kcu.TABLE_NAME, kcu.REFERENCED_TABLE_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
    JOIN INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
        ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
        AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
    WHERE kcu.TABLE_SCHEMA = DATABASE()
    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
    AND rc.DELETE_RULE = 'CASCADE'
    ORDER BY kcu.TABLE_NAME
");
foreach ($allCascade as $c) {
    echo "{$c->TABLE_NAME} cascades on delete of {$c->REFERENCED_TABLE_NAME}" . PHP_EOL;
}

// Check decimal types on all financial tables
echo PHP_EOL . "=== MONETARY COLUMN TYPES ===" . PHP_EOL;
$moneyCols = DB::select("SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, NUMERIC_PRECISION, NUMERIC_SCALE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN ('wallets','wallet_transactions','campaign_settlements','settlement_items','payout_attempts','donations','refunds','donation_payments') AND DATA_TYPE = 'decimal' ORDER BY TABLE_NAME, ORDINAL_POSITION");
foreach ($moneyCols as $c) {
    echo "{$c->TABLE_NAME}.{$c->COLUMN_NAME} = decimal({$c->NUMERIC_PRECISION},{$c->NUMERIC_SCALE})" . PHP_EOL;
}
