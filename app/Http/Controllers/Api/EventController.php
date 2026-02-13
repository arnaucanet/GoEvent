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
        $events = Event::all();
        return $events;
    }

    public function show(Event $event)
    {
        $event->load('user:id,name,surname1');
        return $event;
    }

    public function destroy(Event $event)
    {
        $event->delete();
    }

    public function store(Request $request)
    {
        //$this->authorize('post-edit');

        $data = $request->validate([
            'user_id' => ['int'],
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'description' => ['nullable', 'string', 'min:2'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'capacity' => ['required', 'integer', 'min:1'],
            'featured' => ['boolean'],
            'status' => ['in:borrador,publicado,cancelado'],
        ]);

        //$data['user_id'] =  auth()->user()->id;
        
        $event = Event::create($data);
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        // $this->authorize('event-edit');

        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'description' => ['sometimes', 'nullable', 'string', 'min:2'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after:start_date'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'in:borrador,publicado,cancelado'],
        ]);

        $event->update($data);

        return $event;
    }
}
