<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Reminder;

class ReminderTest extends TestCase
{
    public function test_weekly_reminder_in_greater_than_one_week_range(): void
    {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "weekly 1 * 1 30";

        // 11/10/25 - 12:00
        $start = 1762776058;

        // 11/20/25 - 12:00
        $end = 1763640058;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertTrue($isReminderInRange);
    }

    public function test_weekly_reminder_before_the_provided_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "weekly 1 * 1 30";

        // Monday 11/10/25 - 12:00
        $start = 1762776058;

        // Saturday 11/25/25 - 12:00
        $end = 1763208058;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertFalse($isReminderInRange);
    }

    public function test_weekly_reminder_after_the_provided_range() {
        $reminder = new Reminder(); 
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "weekly 7 * 1 30";

        // Monday 11/10/25 - 12:00
        $start = 1762776058;

        // Saturday 11/25/25 - 12:00
        $end = 1763208058;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);
        
        $this->assertFalse($isReminderInRange);
    }

    public function test_weekly_reminder_within_the_provided_range() {
        $reminder = new Reminder(); 
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "weekly 3 * 1 30";

        // Monday 11/10/25 - 12:00
        $start = 1762776058;

        // Saturday 11/25/25 - 12:00
        $end = 1763208058;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);
        
        $this->assertTrue($isReminderInRange);
    }

    public function test_daily_reminder_in_greater_than_one_day_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 1 30";

        // 11/10/25 - 12:00
        $start = 1762776058;

        // 11/20/25 - 12:00
        $end = 1763640058;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertTrue($isReminderInRange);
    }

    public function test_daily_reminder_outside_overnight_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 22 00";

        // 11/10/25 - 23:00
        $start = 1762815658;

        // 11/11/25 - 11:00
        $end = 1762858858;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertFalse($isReminderInRange);
    }

    public function test_daily_reminder_inside_overnight_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 8 00";

        // 11/10/25 - 23:00
        $start = 1762815658;

        // 11/11/25 - 11:00
        $end = 1762858858;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertTrue($isReminderInRange);
    }

    public function test_daily_reminder_before_daytime_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 8 00";

        // 11/11/25 - 11:00
        $start = 1762858858;

        // 11/11/25 - 15:00
        $end = 1762873258;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertFalse($isReminderInRange);
    }

    public function test_daily_reminder_after_daytime_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 16 00";

        // 11/11/25 - 11:00
        $start = 1762858858;

        // 11/11/25 - 15:00
        $end = 1762873258;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertFalse($isReminderInRange);
    }

    public function test_daily_reminder_inside_daytime_range() {
        $reminder = new Reminder();
        
        $reminder->message = "Visit Hagrid";
        $reminder->schedule = "daily 1 * 12 00";

        // 11/11/25 - 11:00
        $start = 1762858858;

        // 11/11/25 - 15:00
        $end = 1762873258;

        $isReminderInRange = $reminder->isReminderInRange($start, $end);

        $this->assertTrue($isReminderInRange);
    }
}
