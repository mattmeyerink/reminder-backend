<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Datetime;
use \DateInterval;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'schedule'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isReminderInRange(int $start, int $end) {
        $scheduleSplit = explode(' ', $this->schedule);

        $frequency = $scheduleSplit[0];
        $dayOfWeek = $scheduleSplit[1];
        $dayOfMonth = $scheduleSplit[2];
        $hour = $scheduleSplit[3];
        $minute = $scheduleSplit[4];

        $startDateTime = new DateTime();
        $startDateTime->setTimestamp($start);

        $endDateTime = new DateTime();
        $endDateTime->setTimestamp($end);

        if ($frequency == "daily") {
            // If the interval is greater than 24 hours all daily reminders
            // are valid.
            if ($end - $start >= 86400) {
                return true;
            }
            
            // Create a version of the reminder on the start date and see if it meets the range
            $currentDateTime = new DateTime();
            $currentDateTime->setTimestamp($start);
            $currentDateTime->setTime($hour, $minute);

            if ($currentDateTime >= $startDateTime and $currentDateTime <= $endDateTime) {
                return true;
            }

            // Add one day and see if it meets the range
            // This covers the case where the start end dates go past midnight UTC
            $currentDateTime->add(DateInterval::createFromDateString('1 day'));

            return $currentDateTime >= $startDateTime and $currentDateTime <= $endDateTime;
        }


        if ($frequency == "weekly") {
            return false;
        }

        return false;

        // If frequency is DAILY
        // See if creating a date time object on the start date, but with the reminder time is after the start date.
        // Then check if it is still before the end date.

        // If the frequency is WEEKLY
        // start at the start date time object.
        // Move to the next day that matches the day of the week.
        // Determine if we are still inside of the end.
    }
}
