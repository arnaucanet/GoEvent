<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()
            ->favoriteEvents()
            ->with(['category', 'venueRelation', 'artists'])
            ->orderByDesc('start_date')
            ->paginate(15);

        return $events;
    }

    public function toggle(Request $request, Event $event)
    {
        $user = $request->user();
        $result = $user->favoriteEvents()->toggle($event->id);

        return response()->json([
            'event_id' => $event->id,
            'favorited' => count($result['attached']) > 0,
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $request->user()->favoriteEvents()->syncWithoutDetaching([$event->id]);
        return response()->json(['favorited' => true, 'event_id' => $event->id], 201);
    }

    public function destroy(Request $request, Event $event)
    {
        $request->user()->favoriteEvents()->detach($event->id);
        return response()->json(['favorited' => false, 'event_id' => $event->id]);
    }
}
