<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->query('per_page', 15), 100));
        return EventType::orderBy('name')->paginate($perPage);
    }

    public function getList()
    {
        return EventType::where('active', true)->orderBy('name')->get(['id', 'name']);
    }

    public function show(EventType $eventType)
    {
        return $eventType;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:event_types,name'],
            'description' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);
        return EventType::create($data);
    }

    public function update(Request $request, EventType $eventType)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', 'unique:event_types,name,' . $eventType->id],
            'description' => ['sometimes', 'nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ]);
        $eventType->update($data);
        return $eventType;
    }

    public function destroy(EventType $eventType)
    {
        $eventType->delete();
        return response()->json(['message' => 'Tipo eliminado'], 200);
    }
}
