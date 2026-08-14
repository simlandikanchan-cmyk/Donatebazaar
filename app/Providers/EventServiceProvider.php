<?php

namespace App\Providers;

use App\Events\SettlementAutoApproved;
use App\Events\SettlementCancelled;
use App\Events\SettlementFailed;
use App\Events\SettlementManualReviewRequired;
use App\Events\SettlementPaid;
use App\Events\SettlementProcessingStarted;
use App\Events\SettlementRejected;
use App\Events\SettlementRequested;
use App\Events\SettlementRetryScheduled;
use App\Listeners\AutoProcessAutoApprovedSettlement;
use App\Listeners\ProcessFailedJob;
use App\Listeners\SendSettlementAutoApprovedNotification;
use App\Listeners\SendSettlementCancelledNotification;
use App\Listeners\SendSettlementFailedNotification;
use App\Listeners\SendSettlementManualReviewRequiredNotification;
use App\Listeners\SendSettlementPaidNotification;
use App\Listeners\SendSettlementProcessingStartedNotification;
use App\Listeners\SendSettlementRejectedNotification;
use App\Listeners\SendSettlementRequestedNotification;
use App\Listeners\SendSettlementRetryScheduledNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\JobFailed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SettlementRequested::class => [
            SendSettlementRequestedNotification::class,
        ],
        SettlementAutoApproved::class => [
            SendSettlementAutoApprovedNotification::class,
            AutoProcessAutoApprovedSettlement::class,
        ],
        SettlementManualReviewRequired::class => [
            SendSettlementManualReviewRequiredNotification::class,
        ],
        SettlementRejected::class => [
            SendSettlementRejectedNotification::class,
        ],
        SettlementProcessingStarted::class => [
            SendSettlementProcessingStartedNotification::class,
        ],
        SettlementPaid::class => [
            SendSettlementPaidNotification::class,
        ],
        SettlementFailed::class => [
            SendSettlementFailedNotification::class,
        ],
        SettlementRetryScheduled::class => [
            SendSettlementRetryScheduledNotification::class,
        ],
        SettlementCancelled::class => [
            SendSettlementCancelledNotification::class,
        ],
    ];

    public function boot(): void
    {
        \Illuminate\Support\Facades\Queue::failing(function (JobFailed $event) {
            ProcessFailedJob::dispatch($event);
        });
    }
}
