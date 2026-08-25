<?php

namespace App\Services;

use App\Models\Event;
use App\Models\VolunteerAssignment;

class VolunteerAssignmentService
{
    /**
     * Determine whether an active assignment for the given volunteer would
     * overlap with the proposed period on a given event.
     *
     * Time-aware check: when two assignments fall on the same single date,
     * event start_time/end_time are compared so same-day back-to-back events
     * (e.g. 9-11am and 2-4pm) are allowed.
     */
    public function hasTimeConflict(int $volunteerId, string $start, string $end, Event $event): bool
    {
        $overlapping = VolunteerAssignment::with('event')
            ->where('volunteer_id', $volunteerId)
            ->where('status', 'active')
            ->where(function ($q) use ($start, $end) {
                $q->where(function ($q) {
                    $q->whereNull('start_date')->whereNull('end_date');
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $start);
                });
            })
            ->get();

        return $overlapping->contains(function ($assignment) use ($start, $end, $event) {
            $aStart = optional($assignment->start_date)?->format('Y-m-d');
            $aEnd = optional($assignment->end_date)?->format('Y-m-d');

            // Multi-day range or different dates → date overlap is conclusive
            if ($aStart !== $aEnd || $start !== $end || $aStart !== $start) {
                return true;
            }

            // Same single date — check time overlap if both events have times
            if (! $assignment->event || ! $assignment->event->start_time || ! $assignment->event->end_time) {
                return true; // assume full-day
            }
            if (! $event->start_time || ! $event->end_time) {
                return true; // assume full-day
            }

            // No overlap if one ends before the other starts (strict <)
            return ! ($assignment->event->end_time <= $event->start_time
                   || $event->end_time <= $assignment->event->start_time);
        });
    }
}