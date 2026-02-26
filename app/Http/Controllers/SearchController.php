<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
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

    private $movies = [
        ['id' => 1, 'title' => 'Inception', 'genre' => 'Sci-Fi', 'year' => 2010],
        ['id' => 2, 'title' => 'The Godfather', 'genre' => 'Crime', 'year' => 1972],
        ['id' => 3, 'title' => 'Parasite', 'genre' => 'Thriller', 'year' => 2019],
    ];

    public function index(Request $request)
    {
        $data = $request->validate([
            'term' => 'required|string|min:1|max:100',
        ]);

        $term = strtolower($data['term']);

        return response()->json([
            'term' => $data['term'],
            'data' => [
                'bands' => array_values(array_filter($this->bands, function ($item) use ($term) {
                    return strpos(strtolower($item['name']), $term) !== false;
                })),
                'albums' => array_values(array_filter($this->albums, function ($item) use ($term) {
                    return strpos(strtolower($item['title']), $term) !== false;
                })),
                'shows' => array_values(array_filter($this->shows, function ($item) use ($term) {
                    return strpos(strtolower($item['city']), $term) !== false;
                })),
                'movies' => array_values(array_filter($this->movies, function ($item) use ($term) {
                    return strpos(strtolower($item['title']), $term) !== false;
                })),
            ],
        ]);
    }
}
