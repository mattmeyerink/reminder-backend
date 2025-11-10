<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ReminderScheduleRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // frequency dayOfWeek dayOfMonth hour minute
        $cronArray = explode(" ", $value);

        if (count($cronArray) != 5) {
            $fail('Invalid cron string passed.');
        }

        $frequency = $cronArray[0];
        $dayOfWeek = $cronArray[1];
        $dayOfMonth = $cronArray[2];
        $hour = $cronArray[3];
        $minute = $cronArray[4];

        if ($frequency != 'daily' and $frequency != 'weekly') {
            $fail('Invalid frequency passed.');
        }

        if (is_numeric($hour) == FALSE or intval($hour) < 0 or intval($hour) > 23) {
            $fail('You must specify a valid hour in military time 0-23');
        }

        if (is_numeric($minute) == FALSE or $minute < 0 or $minute > 59) {
            $fail('You must specify a valid minute in military time 0-59');
        }

        if ($frequency == 'weekly' and (is_numeric($dayOfWeek) == FALSE or $dayOfWeek < 1 or $dayOfWeek > 7)) {
            $fail('You must specify a valid day of the week for the weekly frequency');
        }
    }
}
