<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Event;
use App\Models\JobPost;

class SitemapController extends Controller
{
    public function index()
    {
        $static = [
            ['loc' => '/',                               'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => '/about',                          'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/how-it-works',                   'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/contact',                        'priority' => '0.6', 'changefreq' => 'yearly'],
            ['loc' => '/partnership',                    'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => '/disaster-relief',                'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => '/all-campaigns',                  'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => '/blog',                           'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => '/events',                         'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => '/career',                         'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => '/gift-cards',                     'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => '/gift-cards/redeem',              'priority' => '0.4', 'changefreq' => 'monthly'],
            ['loc' => '/volunteer/apply',                'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        $campaigns = Campaign::whereIn('campaign_state', [
            Campaign::STATE_ACTIVE,
            Campaign::STATE_COMPLETED,
            Campaign::STATE_EXPIRED,
        ])
            ->whereNotNull('slug')
            ->with('category:id,slug')
            ->get()
            ->map(fn ($c) => [
                'loc' => '/campaigns/'.($c->category->slug ?? 'general').'/'.$c->slug,
                'priority' => $c->campaign_state === Campaign::STATE_ACTIVE ? '0.8' : '0.5',
                'changefreq' => $c->campaign_state === Campaign::STATE_ACTIVE ? 'daily' : 'weekly',
                'lastmod' => $c->updated_at,
            ]);

        $categories = Category::where('is_active', true)
            ->get()
            ->map(fn ($c) => [
                'loc' => '/category/'.$c->slug,
                'priority' => '0.6',
                'changefreq' => 'weekly',
            ]);

        $blogs = Blog::public()
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($b) => [
                'loc' => '/blog/'.$b->slug,
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $b->updated_at,
            ]);

        $events = Event::whereIn('status', [Event::STATUS_ACTIVE, Event::STATUS_COMPLETED])
            ->get()
            ->map(fn ($e) => [
                'loc' => '/events/'.$e->id,
                'priority' => '0.6',
                'changefreq' => 'weekly',
                'lastmod' => $e->updated_at,
            ]);

        $jobs = JobPost::active()
            ->get()
            ->map(fn ($j) => [
                'loc' => '/career/'.$j->slug,
                'priority' => '0.5',
                'changefreq' => 'weekly',
                'lastmod' => $j->updated_at,
            ]);

        $urls = collect()
            ->concat($static)
            ->concat($campaigns)
            ->concat($categories)
            ->concat($blogs)
            ->concat($events)
            ->concat($jobs);

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
