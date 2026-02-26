<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowController extends Controller
{
    private $shows = [
        ['id' => 1, 'band_id' => 1, 'city' => 'London', 'date' => '2026-06-10'],
        ['id' => 2, 'band_id' => 2, 'city' => 'New York', 'date' => '2026-07-21'],
        ['id' => 3, 'band_id' => 3, 'city' => 'Sao Paulo', 'date' => '2026-08-15'],
    ];

    public function index()
    {
        return response()->json(['data' => $this->shows]);
    }

    public function show($id)
    {
        $show = $this->findById((int) $id);

        if (!$show) {
            return response()->json(['message' => 'Show not found'], 404);
        }

        return response()->json(['data' => $show]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'band_id' => 'required|integer|min:1',
            'city' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        $newShow = [
            'id' => $this->nextId(),
            'band_id' => $data['band_id'],
            'city' => $data['city'],
            'date' => $data['date'],
        ];

        return response()->json([
            'message' => 'Show created',
            'data' => $newShow,
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    public function update($id, Request $request)
    {
        $show = $this->findById((int) $id);

        if (!$show) {
            return response()->json(['message' => 'Show not found'], 404);
        }

        $data = $request->validate([
            'band_id' => 'sometimes|required|integer|min:1',
            'city' => 'sometimes|required|string|max:100',
            'date' => 'sometimes|required|date',
        ]);

        $updatedShow = [
            'id' => $show['id'],
            'band_id' => $data['band_id'] ?? $show['band_id'],
            'city' => $data['city'] ?? $show['city'],
            'date' => $data['date'] ?? $show['date'],
        ];

        return response()->json([
            'message' => 'Show updated',
            'data' => $updatedShow,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function destroy($id)
    {
        $show = $this->findById((int) $id);

        if (!$show) {
            return response()->json(['message' => 'Show not found'], 404);
        }

        return response()->json([
            'message' => 'Show deleted',
            'data' => $show,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    private function findById($id)
    {
        foreach ($this->shows as $show) {
            if ($show['id'] === $id) {
                return $show;
            }
        }

        return null;
    }

    private function nextId()
    {
        return max(array_column($this->shows, 'id')) + 1;
    }
}
