<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->query('per_page', 15), 100));
        $query = Artist::query();

        if ($q = trim((string) $request->query('q', ''))) {
            $query->where('name', 'like', "%{$q}%");
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function getList()
    {
        return Artist::where('active', true)->orderBy('name')->get(['id', 'name']);
    }

    public function show(Artist $artist)
    {
        $artist->load('events');
        return $artist;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'active' => ['boolean'],
        ]);
        return Artist::create($data);
    }

    public function update(Request $request, Artist $artist)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ]);
        $artist->update($data);
        return $artist;
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return response()->json(['message' => 'Artista eliminado'], 200);
    }
}
