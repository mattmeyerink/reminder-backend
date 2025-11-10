<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Datetime;
use \DateInterval;

class Reminder extends Model
{
    use HasFactory;

    const FREQUENCY_INDEX = 0;
    const DAY_OF_WEEK_INDEX = 1;
    const DAY_OF_MONTH_INDEX = 2;
    const HOUR_INDEX = 3;
    const MINUTE_INDEX = 4;

    const DAILY_FREQUENCY = 'daily';
    const WEEKLY_FREQUENCY = 'weekly';

    const SECONDS_PER_DAY = 86400;
    const SECONDS_PER_WEEK = 604800;

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

        $frequency = $scheduleSplit[self::FREQUENCY_INDEX];

        if ($frequency == self::DAILY_FREQUENCY) {
            return $this->isDailyReminderInRange($start, $end);
        }

        if ($frequency == self::WEEKLY_FREQUENCY) {
            return $this->isWeeklyReminderInRange($start, $end);
        }

        return false;
    }

    private function isDailyReminderInRange(int $start, int $end) {
        // If the interval is greater than 24 hours all daily reminders
        // are in range.
        if ($end - $start >= self::SECONDS_PER_DAY) {
            return true;
        }

        $scheduleSplit = explode(' ', $this->schedule);

        $hour = $scheduleSplit[self::HOUR_INDEX];
        $minute = $scheduleSplit[self::MINUTE_INDEX];

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
        if ($end - $start >= self::SECONDS_PER_WEEK) {
            return true;
        }

        $scheduleSplit = explode(' ', $this->schedule);

        $dayOfWeek = $scheduleSplit[self::DAY_OF_WEEK_INDEX];
        $hour = $scheduleSplit[self::HOUR_INDEX];
        $minute = $scheduleSplit[self::MINUTE_INDEX];

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
