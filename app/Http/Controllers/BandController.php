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
