<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->query('per_page', 15), 100));
        $query = Venue::query();

        if ($q = trim((string) $request->query('q', ''))) {
            $query->where(function ($s) use ($q) {
                $s->where('name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getList()
    {
        return Venue::where('active', true)->orderBy('name')->get(['id', 'name', 'city', 'capacity']);
    }

    public function getCities()
    {
        $cities = Venue::where('active', true)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->filter()
            ->values()
            ->map(fn ($c) => ['name' => $c]);

        return response()->json(['data' => $cities]);
    }

    public function show(Venue $venue)
    {
        return $venue;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1'],
            'active' => ['boolean'],
        ]);
        return Venue::create($data);
    }

    public function update(Request $request, Venue $venue)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'city' => ['sometimes', 'required', 'string', 'max:100'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'active' => ['sometimes', 'boolean'],
        ]);
        $venue->update($data);
        return $venue;
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();
        return response()->json(['message' => 'Recinto eliminado'], 200);
    }
}
