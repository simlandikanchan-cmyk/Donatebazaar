<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use DB;

class GenerateUUIDs extends Command
{
    protected $signature = 'db:generate-uuids';

    public function handle()
    {
        DB::table('campaigns')->whereNull('uuid')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('campaigns')
                    ->where('id', $row->id)
                    ->update(['uuid' => Str::uuid()]);
            }
        });

        $this->info('UUIDs generated successfully!');
    }
}