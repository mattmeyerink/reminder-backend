<?php
namespace App\Controllers\API;

use App\Controllers\Controller;
use App\Models\Reminder;
use App\Resources\UserResource;
use App\Rules\ReminderScheduleRule;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function create(Request $request) {
        $validated = $request->validate([
            'message' => 'bail|required|string|max:255',
            'schedule' => ['bail', 'required', 'string', 'max:255', new ReminderScheduleRule],
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
            'message' => 'bail|required|string|max:255',
            'schedule' => ['bail', 'required', 'string', 'max:255', new ReminderScheduleRule],
        ]);

        $reminder = Reminder::find($id);
        $reminder->update($validated);
        $updatedReminder = Reminder::find($id);

        return $updatedReminder;
    }
    
    public function delete(string $id) {
        $reminder = Reminder::find($id);
        $reminder->delete();

        return response('Reminder successfully deleted', 200);
    }

    public function search(Request $request) {
        $searchQuery = $request->query('searchText', '');

        $matchingReminders = Reminder::where('message', 'like', '%' . $searchQuery . '%')->get();
        
        return $matchingReminders;
    }

    public function getRemindersInRange(Request $request) {
        $validated = $request->validate([
            'start' => 'bail|required|int',
            'end' => 'bail|required|int|gt:start'
        ]);

        $reminders = Reminder::all();

        $remindersInRange = [];
        for ($i = 0; $i < count($reminders); $i++) {
            if ($reminders[$i]->isReminderInRange($validated['start'], $validated['end'])) {
                $remindersInRange[] = $reminders[$i];
            }
        }

        return $remindersInRange;
    }
}
