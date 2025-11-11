<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Reminder;

class ReminderScheduleRule implements ValidationRule
{
    const MAX_VALID_HOUR = 23;
    const MAX_VALID_MINUTE = 59;
    const MAX_VALID_DAY_OF_WEEK = 7;
    const MAX_VALID_DAY_OF_MONTH = 31;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // frequency dayOfWeek dayOfMonth hour minute
        $cronArray = explode(' ', $value);

        if (count($cronArray) != 5) {
            $fail('Invalid cron string passed.');
        }

        $frequency = $cronArray[Reminder::FREQUENCY_INDEX];
        $dayOfWeek = $cronArray[Reminder::DAY_OF_WEEK_INDEX];
        $dayOfMonth = $cronArray[Reminder::DAY_OF_MONTH_INDEX];
        $hour = $cronArray[Reminder::HOUR_INDEX];
        $minute = $cronArray[Reminder::MINUTE_INDEX];

        if ($frequency != Reminder::DAILY_FREQUENCY and $frequency != Reminder::WEEKLY_FREQUENCY and $frequency != Reminder::MONTHLY_FREQUENCY) {
            $fail('Invalid frequency passed.');
        }

        if (is_numeric($hour) == FALSE or intval($hour) < 0 or intval($hour) > self::MAX_VALID_HOUR) {
            $fail('You must specify a valid hour in military time 0-23');
        }

        if (is_numeric($minute) == FALSE or $minute < 0 or $minute > self::MAX_VALID_MINUTE) {
            $fail('You must specify a valid minute in military time 0-59');
        }

        if ($frequency == Reminder::WEEKLY_FREQUENCY and (is_numeric($dayOfWeek) == FALSE or $dayOfWeek < 1 or $dayOfWeek > self::MAX_VALID_DAY_OF_WEEK)) {
            $fail('You must specify a valid day of the week for the weekly frequency');
        }

        if ($frequency == Reminder::MONTHLY_FREQUENCY and (is_numeric($dayOfMonth) == FALSE or $dayOfMonth < 1 or $dayOfMonth > self::MAX_VALID_DAY_OF_MONTH)) {
            $fail('You must specifiy a valid day of the month for the monthly frequency');
        }
    }
}
