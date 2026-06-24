<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class MigrateBlogMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run using: php artisan migrate:blog-metrics
     */
    protected $signature = 'migrate:blog-metrics';

    /**
     * The console command description.
     */
    protected $description = 'Migrate blog metrics (views, likes, comments) from blogs table to blog_metrics table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info(' Starting blog metrics migration...');

        try {
            DB::beginTransaction();

            DB::table('blogs')
                ->select('id', 'views_count', 'likes_count', 'comments_count')
                ->orderBy('id')
                ->chunk(500, function ($blogs) {

                    $data = [];

                    foreach ($blogs as $blog) {
                        $data[] = [
                            'blog_id' => $blog->id,
                            'views_count' => $blog->views_count ?? 0,
                            'likes_count' => $blog->likes_count ?? 0,
                            'comments_count' => $blog->comments_count ?? 0,
                        ];
                    }

                    // Use upsert to avoid duplicate issues
                    DB::table('blog_metrics')->upsert(
                        $data,
                        ['blog_id'], // unique key
                        ['views_count', 'likes_count', 'comments_count']
                    );

                    $this->info('Processed chunk of ' . count($blogs) . ' blogs');
                });

            DB::commit();

            $this->info(' Blog metrics migrated successfully!');
            return Command::SUCCESS;

        } catch (Throwable $e) {
            DB::rollBack();

            $this->error('Migration failed!');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}