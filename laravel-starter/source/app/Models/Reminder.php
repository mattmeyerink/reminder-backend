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

        if ($frequency == "daily") {
            return $this->isDailyReminderInRange($start, $end);
        }

        if ($frequency == "weekly") {
            return $this->isWeeklyReminderInRange($start, $end);
        }

        return false;
    }

    private function isDailyReminderInRange(int $start, int $end) {
        // If the interval is greater than 24 hours all daily reminders
        // are in range.
        if ($end - $start >= 86400) {
            return true;
        }

        $scheduleSplit = explode(' ', $this->schedule);

        $hour = $scheduleSplit[3];
        $minute = $scheduleSplit[4];

        $startDateTime = new DateTime();
        $startDateTime->setTimestamp($start);

        $endDateTime = new DateTime();
        $endDateTime->setTimestamp($end);
        
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

    private function isWeeklyReminderInRange(int $start, int $end) {
        // If the interval is greater than 1 week all weekly reminders are in range.
        if ($end - $start >= 604800) {
            return true;
        }

        $scheduleSplit = explode(' ', $this->schedule);

        $dayOfWeek = $scheduleSplit[1];
        $hour = $scheduleSplit[3];
        $minute = $scheduleSplit[4];

        $startDateTime = new DateTime();
        $startDateTime->setTimestamp($start);

        $endDateTime = new DateTime();
        $endDateTime->setTimestamp($end);

        $currentDateTime = new DateTime();
        $currentDateTime->setTimestamp($start);
        $currentDateTime->setTime($hour, $minute);

        while ($currentDateTime->format('N') != $dayOfWeek) {
            $currentDateTime->add(DateInterval::createFromDateString('1 day'));
        }

        return $currentDateTime >= $startDateTime and $currentDateTime <= $endDateTime;
    }
}
