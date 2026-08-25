<?php

namespace App\View\Composers;

use App\Models\Blog;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UserSidebarComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();
        $uid = $user?->id;

        $sidebarKyc = $user?->kycVerification;

        $sidebarCounts = Cache::remember("sidebar.counts.{$uid}", 300, function () use ($uid) {
            $campaigns = Campaign::where('user_id', $uid)
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(campaign_state = 'active') as active,
                    SUM(campaign_state = 'inactive') as inactive,
                    SUM(campaign_state = 'pending') as pending,
                    SUM(campaign_state = 'paused') as paused,
                    SUM(campaign_state = 'rejected') as rejected,
                    SUM(campaign_state = 'expired') as expired
                ")
                ->first();

            $blogs = Blog::where('author_id', $uid)
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(status = 'approved') as published,
                    SUM(status = 'draft') as draft,
                    SUM(status = 'pending') as pending
                ")
                ->first();

            return [
                'all'      => $campaigns->total ?? 0,
                'active'   => $campaigns->active ?? 0,
                'inactive' => $campaigns->inactive ?? 0,
                'pending'  => $campaigns->pending ?? 0,
                'paused'   => $campaigns->paused ?? 0,
                'rejected' => $campaigns->rejected ?? 0,
                'expired'  => $campaigns->expired ?? 0,
                'blogTotal'     => $blogs->total ?? 0,
                'blogPublished' => $blogs->published ?? 0,
                'blogDraft'     => $blogs->draft ?? 0,
                'blogPending'   => $blogs->pending ?? 0,
            ];
        });

        $sidebarAll      = $sidebarCounts['all'];
        $sidebarActive   = $sidebarCounts['active'];
        $sidebarInactive = $sidebarCounts['inactive'];
        $sidebarPending  = $sidebarCounts['pending'];
        $sidebarPaused   = $sidebarCounts['paused'];
        $sidebarRejected = $sidebarCounts['rejected'];
        $sidebarExpired  = $sidebarCounts['expired'];
        $sidebarBlogTotal     = $sidebarCounts['blogTotal'];
        $sidebarBlogPublished = $sidebarCounts['blogPublished'];
        $sidebarBlogDraft     = $sidebarCounts['blogDraft'];
        $sidebarBlogPending   = $sidebarCounts['blogPending'];

        $view->with(compact(
            'sidebarKyc', 'sidebarAll', 'sidebarActive', 'sidebarInactive',
            'sidebarPending', 'sidebarPaused', 'sidebarRejected', 'sidebarExpired',
            'sidebarBlogTotal', 'sidebarBlogPublished', 'sidebarBlogDraft', 'sidebarBlogPending'
        ));
    }
}
