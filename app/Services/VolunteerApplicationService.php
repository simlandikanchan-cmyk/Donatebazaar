<?php

namespace App\Services;

use App\Mail\VolunteerApplicationStatusChanged;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VolunteerApplicationService
{
    public function processStatusChange(VolunteerApplication $application, string $status): void
    {
        DB::transaction(function () use ($application, $status) {
            $application->update(['status' => $status]);

            if ($status === 'approved') {
                $application->volunteer->update(['is_verified' => true]);
            }
        });

        try {
            Mail::to($application->volunteer->user->email)->send(
                new VolunteerApplicationStatusChanged($application)
            );
        } catch (\Throwable $e) {
            Log::warning('Volunteer status change email failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
