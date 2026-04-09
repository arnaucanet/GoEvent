<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\StorePostRequest;

class EventController extends Controller
{
    private function publicEventsQuery()
    {
        return Event::query()
            ->with(['category'])
            ->where('status', 'publicado')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('start_date', 'asc');
    }

    public function publicList(Request $request)
    {
        $limit = (int) $request->query('limit', 12);
        $limit = max(1, min($limit, 24));

        $query = $this->publicEventsQuery();

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $categoryId = $request->query('category_id');
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $city = trim((string) $request->query('city', ''));
        if ($city !== '') {
            $query->where('city', $city);
        }

        $dateFrom = $request->query('date_from');
        if (!empty($dateFrom)) {
            $query->where('start_date', '>=', $dateFrom);
        }

        $dateTo = $request->query('date_to');
        if (!empty($dateTo)) {
            $query->where('start_date', '<=', $dateTo);
        }

        $events = $query->limit($limit)->get();

        return response()->json($events);
    }

   public function featured(Request $request)
{
    $limit = (int) $request->query('limit', 8);
    $limit = max(1, min($limit, 24));

    $baseQuery = $this->publicEventsQuery();

    $events = (clone $baseQuery)
        ->where('featured', true)
        ->limit($limit)
        ->get();

    if ($events->isEmpty()) {
        $events = $baseQuery->limit($limit)->get();
    }

    return response()->json(['data' => $events]);
}

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
            'city' => ['nullable', 'string', 'max:100'],
            'venue' => ['nullable', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'city' => ['nullable', 'string', 'max:255'],
            'featured' => ['boolean'],
            'status' => ['in:borrador,publicado,cancelado'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data['user_id'] = $request->user()->id;
        
        $event = Event::create($data);

        $event->load(['user', 'category']);
        
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'description' => ['sometimes', 'nullable', 'string', 'min:2'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'venue' => ['sometimes', 'nullable', 'string', 'max:150'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after:start_date'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'in:borrador,publicado,cancelado'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
        ]);

        $event->update($data);

        $event->load(['user', 'category']);

        return $event;
    }
}
