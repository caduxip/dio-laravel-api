<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    private $albums = [
        ['id' => 1, 'title' => 'Abbey Road', 'band_id' => 1, 'year' => 1969],
        ['id' => 2, 'title' => 'Led Zeppelin IV', 'band_id' => 2, 'year' => 1971],
        ['id' => 3, 'title' => 'The Dark Side of the Moon', 'band_id' => 3, 'year' => 1973],
    ];

    private $tracks = [
        1 => [
            ['id' => 1, 'title' => 'Come Together', 'duration_seconds' => 259],
            ['id' => 2, 'title' => 'Something', 'duration_seconds' => 182],
        ],
        2 => [
            ['id' => 1, 'title' => 'Black Dog', 'duration_seconds' => 296],
            ['id' => 2, 'title' => 'Rock and Roll', 'duration_seconds' => 221],
        ],
        3 => [
            ['id' => 1, 'title' => 'Money', 'duration_seconds' => 382],
            ['id' => 2, 'title' => 'Time', 'duration_seconds' => 413],
        ],
    ];

    public function index()
    {
        return response()->json(['data' => $this->albums]);
    }

    public function show($id)
    {
        $album = $this->findById((int) $id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        return response()->json(['data' => $album]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'band_id' => 'required|integer|min:1',
            'year' => 'required|integer|min:1900|max:2100',
        ]);

        $newAlbum = [
            'id' => $this->nextId(),
            'title' => $data['title'],
            'band_id' => $data['band_id'],
            'year' => $data['year'],
        ];

        return response()->json([
            'message' => 'Album created',
            'data' => $newAlbum,
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    public function update($id, Request $request)
    {
        $album = $this->findById((int) $id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'band_id' => 'sometimes|required|integer|min:1',
            'year' => 'sometimes|required|integer|min:1900|max:2100',
        ]);

        $updatedAlbum = [
            'id' => $album['id'],
            'title' => $data['title'] ?? $album['title'],
            'band_id' => $data['band_id'] ?? $album['band_id'],
            'year' => $data['year'] ?? $album['year'],
        ];

        return response()->json([
            'message' => 'Album updated',
            'data' => $updatedAlbum,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function destroy($id)
    {
        $album = $this->findById((int) $id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        return response()->json([
            'message' => 'Album deleted',
            'data' => $album,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function tracks($id)
    {
        $album = $this->findById((int) $id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        return response()->json([
            'data' => [
                'album' => $album,
                'tracks' => $this->tracks[$album['id']] ?? [],
            ],
        ]);
    }

    public function storeTrack($id, Request $request)
    {
        $album = $this->findById((int) $id);

        if (!$album) {
            return response()->json(['message' => 'Album not found'], 404);
        }

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'duration_seconds' => 'required|integer|min:30|max:1800',
        ]);

        $newTrack = [
            'id' => $this->nextTrackId($album['id']),
            'title' => $data['title'],
            'duration_seconds' => (int) $data['duration_seconds'],
        ];

        return response()->json([
            'message' => 'Track created',
            'data' => [
                'album' => $album,
                'track' => $newTrack,
            ],
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    private function findById($id)
    {
        foreach ($this->albums as $album) {
            if ($album['id'] === $id) {
                return $album;
            }
        }

        return null;
    }

    private function nextId()
    {
        return max(array_column($this->albums, 'id')) + 1;
    }

    private function nextTrackId($albumId)
    {
        $tracks = $this->tracks[$albumId] ?? [];

        if (empty($tracks)) {
            return 1;
        }

        return max(array_column($tracks, 'id')) + 1;
    }
}
