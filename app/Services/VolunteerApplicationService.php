<?php

namespace App\Services;

use App\Models\VolunteerApplication;
use App\Mail\VolunteerApplicationStatusChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
