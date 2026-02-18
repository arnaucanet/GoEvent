<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\StorePostRequest;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['user', 'category'])->get();
        return $events;
    }

    public function show(Event $event)
    {
        $event->load(['user', 'category']);
        return $event;
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'description' => ['nullable', 'string', 'min:2'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'capacity' => ['required', 'integer', 'min:1'],
            'featured' => ['boolean'],
            'status' => ['in:borrador,publicado,cancelado'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data['user_id'] = auth()->user()->id;
        
        $event = Event::create($data);

        $event->load(['user', 'category']);
        
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'description' => ['sometimes', 'nullable', 'string', 'min:2'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after:start_date'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'in:borrador,publicado,cancelado'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
        ]);

        $event->update($data);

        $event->load(['user', 'category']);

        return $event;
    }
}
