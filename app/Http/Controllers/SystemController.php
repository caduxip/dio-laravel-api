<?php

namespace App\Http\Controllers;

class SystemController extends Controller
{
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'dio-laravel-api',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function stats()
    {
        return response()->json([
            'data' => [
                'bands' => 3,
                'albums' => 3,
                'shows' => 3,
                'movies' => 3,
                'tracks' => 6,
                'reviews' => 4,
            ],
            'note' => 'Contagem baseada nos dados em memoria desta API demo',
        ]);
    }
}
