<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BandController extends Controller
{
    private $bands = [
        ['id' => 1, 'name' => 'The Beatles', 'genre' => 'Rock'],
        ['id' => 2, 'name' => 'Led Zeppelin', 'genre' => 'Rock'],
        ['id' => 3, 'name' => 'Pink Floyd', 'genre' => 'Progressive Rock'],
    ];

    private $albums = [
        ['id' => 1, 'title' => 'Abbey Road', 'band_id' => 1, 'year' => 1969],
        ['id' => 2, 'title' => 'Led Zeppelin IV', 'band_id' => 2, 'year' => 1971],
        ['id' => 3, 'title' => 'The Dark Side of the Moon', 'band_id' => 3, 'year' => 1973],
    ];

    private $shows = [
        ['id' => 1, 'band_id' => 1, 'city' => 'London', 'date' => '2026-06-10'],
        ['id' => 2, 'band_id' => 2, 'city' => 'New York', 'date' => '2026-07-21'],
        ['id' => 3, 'band_id' => 3, 'city' => 'Sao Paulo', 'date' => '2026-08-15'],
    ];

    public function index()
    {
        return response()->json(['data' => $this->bands]);
    }

    public function show($id)
    {
        $band = $this->findById((int) $id);

        if (!$band) {
            return response()->json(['message' => 'Band not found'], 404);
        }

        return response()->json(['data' => $band]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'genre' => 'required|string|max:120',
        ]);

        $newBand = [
            'id' => $this->nextId(),
            'name' => $data['name'],
            'genre' => $data['genre'],
        ];

        return response()->json([
            'message' => 'Band created',
            'data' => $newBand,
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    public function update($id, Request $request)
    {
        $band = $this->findById((int) $id);

        if (!$band) {
            return response()->json(['message' => 'Band not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:120',
            'genre' => 'sometimes|required|string|max:120',
        ]);

        $updatedBand = [
            'id' => $band['id'],
            'name' => $data['name'] ?? $band['name'],
            'genre' => $data['genre'] ?? $band['genre'],
        ];

        return response()->json([
            'message' => 'Band updated',
            'data' => $updatedBand,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function destroy($id)
    {
        $band = $this->findById((int) $id);

        if (!$band) {
            return response()->json(['message' => 'Band not found'], 404);
        }

        return response()->json([
            'message' => 'Band deleted',
            'data' => $band,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function albums($id)
    {
        $band = $this->findById((int) $id);

        if (!$band) {
            return response()->json(['message' => 'Band not found'], 404);
        }

        $albums = array_values(array_filter($this->albums, function ($album) use ($id) {
            return (int) $album['band_id'] === (int) $id;
        }));

        return response()->json([
            'data' => [
                'band' => $band,
                'albums' => $albums,
            ],
        ]);
    }

    public function shows($id)
    {
        $band = $this->findById((int) $id);

        if (!$band) {
            return response()->json(['message' => 'Band not found'], 404);
        }

        $shows = array_values(array_filter($this->shows, function ($show) use ($id) {
            return (int) $show['band_id'] === (int) $id;
        }));

        return response()->json([
            'data' => [
                'band' => $band,
                'shows' => $shows,
            ],
        ]);
    }

    private function findById($id)
    {
        foreach ($this->bands as $band) {
            if ($band['id'] === $id) {
                return $band;
            }
        }

        return null;
    }

    private function nextId()
    {
        return max(array_column($this->bands, 'id')) + 1;
    }
}
