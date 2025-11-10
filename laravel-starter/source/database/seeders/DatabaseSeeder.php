<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Reminder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Reminder::factory()->create([
            'message' => 'Give Dobby socks',
            'schedule' => 'daily * * 1 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Go to the Great Hall for breakfast',
            'schedule' => 'daily * * 8 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Serve detention with Snape',
            'schedule' => 'weekly 6 * 10 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Lunch in the Great Hall',
            'schedule' => 'daily * * 13 25'
        ]);
        Reminder::factory()->create([
            'message' => 'Transfiguration',
            'schedule' => 'daily * * 15 00'
        ]);
        Reminder::factory()->create([
            'message' => 'Dinner in the Great Hall',
            'schedule' => 'daily * * 18 00'
        ]);
        Reminder::factory()->create([
            'message' => 'Quidditch practice',
            'schedule' => 'daily * * 19 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Visit Hagrid',
            'schedule' => 'weekly 1 * 22 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Visit Dobby',
            'schedule' => 'weekly 5 * 13 30'
        ]);
        Reminder::factory()->create([
            'message' => 'Write to Sirius',
            'schedule' => 'weekly 6 * 13 30'
        ]);
    }
}
