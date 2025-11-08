<?php
namespace App\Controllers\API;

use App\Controllers\Controller;
use App\Models\Reminder;
use App\Resources\UserResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function create(Request $request) {
        $validated = $request->validate([
            // TODO -> Better validation 
            // Custom messages for each error
            // A custom cron schedule validation function
            'message' => 'required|string|max:255',
            'schedule' => 'required|string|max:255',
        ]);

        $reminder = Reminder::create([
            'message' => $validated['message'],
            'schedule' => $validated['schedule'],
            'user_id' => null,
        ]);

        return $reminder;
    }

    public function update(Request $request, string $id) {
        $validated = $request->validate([
            // TODO -> Better validation 
            // Custom messages for each error
            // A custom cron schedule validation function
            'message' => 'required|string|max:255',
            'schedule' => 'required|string|max:255',
        ]);

        $reminder = Reminder::find($id);
        $reminder->update($validated);
        $updatedReminder = Reminder::find($id);

        return $updatedReminder;
    }
}
