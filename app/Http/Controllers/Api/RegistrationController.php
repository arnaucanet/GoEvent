<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->registeredEvents()
            ->with(['category', 'venueRelation'])
            ->orderByDesc('start_date')
            ->paginate(15);
    }

    public function adminIndex(Request $request)
    {
        $perPage = max(1, min((int) $request->query('per_page', 15), 100));

        $query = Registration::query()
            ->with(['user:id,name,surname1,email', 'event:id,title,start_date,venue_id', 'event.venueRelation:id,name,city']);

        if ($eventId = $request->query('event_id')) {
            $query->where('event_id', $eventId);
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($q = trim((string) $request->query('q', ''))) {
            $query->whereHas('user', function ($u) use ($q) {
                $u->where('name', 'like', "%{$q}%")
                    ->orWhere('surname1', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function store(Request $request, Event $event)
    {
        $user = $request->user();

        $registered = $user->registeredEvents()
            ->wherePivot('status', 'inscrito')
            ->wherePivot('event_id', $event->id)
            ->exists();

        if ($registered) {
            return response()->json(['message' => 'Ya estás inscrito'], 422);
        }

        $confirmedCount = $event->registeredUsers()
            ->wherePivot('status', 'inscrito')
            ->count();

        if ($event->capacity && $confirmedCount >= $event->capacity) {
            return response()->json(['message' => 'Aforo completo'], 422);
        }

        Registration::updateOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            ['status' => 'inscrito']
        );

        return response()->json([
            'message' => 'Inscripción realizada',
            'event_id' => $event->id,
            'status' => 'inscrito',
        ], 201);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['inscrito', 'cancelado', 'asistido'])],
        ]);

        $registration = Registration::where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->firstOrFail();

        $registration->update(['status' => $data['status']]);

        return $registration;
    }

    public function destroy(Request $request, Event $event)
    {
        $registration = Registration::where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->firstOrFail();

        $registration->update(['status' => 'cancelado']);

        return response()->json(['message' => 'Inscripción cancelada']);
    }
}
