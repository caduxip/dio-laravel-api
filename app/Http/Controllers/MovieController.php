<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    private $movies = [
        [
            'id' => 1,
            'title' => 'Inception',
            'genre' => 'Sci-Fi',
            'year' => 2010,
            'rating' => 8.8,
            'director' => 'Christopher Nolan',
        ],
        [
            'id' => 2,
            'title' => 'The Godfather',
            'genre' => 'Crime',
            'year' => 1972,
            'rating' => 9.2,
            'director' => 'Francis Ford Coppola',
        ],
        [
            'id' => 3,
            'title' => 'Parasite',
            'genre' => 'Thriller',
            'year' => 2019,
            'rating' => 8.5,
            'director' => 'Bong Joon-ho',
        ],
    ];

    private $casts = [
        1 => ['Leonardo DiCaprio', 'Joseph Gordon-Levitt', 'Elliot Page'],
        2 => ['Marlon Brando', 'Al Pacino', 'James Caan'],
        3 => ['Song Kang-ho', 'Choi Woo-shik', 'Park So-dam'],
    ];

    private $reviews = [
        1 => [
            ['id' => 1, 'reviewer' => 'Ana', 'rating' => 9, 'comment' => 'Mind-blowing concept'],
            ['id' => 2, 'reviewer' => 'Carlos', 'rating' => 8, 'comment' => 'Great visuals and soundtrack'],
        ],
        2 => [
            ['id' => 1, 'reviewer' => 'Maria', 'rating' => 10, 'comment' => 'Classic masterpiece'],
        ],
        3 => [
            ['id' => 1, 'reviewer' => 'Joao', 'rating' => 9, 'comment' => 'Sharp social critique'],
        ],
    ];

    public function index()
    {
        return response()->json(['data' => $this->movies]);
    }

    public function show($id)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json(['data' => $movie]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'genre' => 'required|string|max:80',
            'year' => 'required|integer|min:1888|max:2100',
            'rating' => 'required|numeric|min:0|max:10',
            'director' => 'required|string|max:120',
        ]);

        $newMovie = [
            'id' => $this->nextId(),
            'title' => $data['title'],
            'genre' => $data['genre'],
            'year' => $data['year'],
            'rating' => (float) $data['rating'],
            'director' => $data['director'],
        ];

        return response()->json([
            'message' => 'Movie created',
            'data' => $newMovie,
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    public function update($id, Request $request)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'genre' => 'sometimes|required|string|max:80',
            'year' => 'sometimes|required|integer|min:1888|max:2100',
            'rating' => 'sometimes|required|numeric|min:0|max:10',
            'director' => 'sometimes|required|string|max:120',
        ]);

        $updatedMovie = [
            'id' => $movie['id'],
            'title' => $data['title'] ?? $movie['title'],
            'genre' => $data['genre'] ?? $movie['genre'],
            'year' => $data['year'] ?? $movie['year'],
            'rating' => isset($data['rating']) ? (float) $data['rating'] : $movie['rating'],
            'director' => $data['director'] ?? $movie['director'],
        ];

        return response()->json([
            'message' => 'Movie updated',
            'data' => $updatedMovie,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function destroy($id)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json([
            'message' => 'Movie deleted',
            'data' => $movie,
            'note' => 'Demo API sem persistencia em banco',
        ]);
    }

    public function byGenre($genre)
    {
        $genre = strtolower($genre);
        $filtered = array_values(array_filter($this->movies, function ($movie) use ($genre) {
            return strtolower($movie['genre']) === $genre;
        }));

        return response()->json(['data' => $filtered]);
    }

    public function byYear($year)
    {
        $filtered = array_values(array_filter($this->movies, function ($movie) use ($year) {
            return (int) $movie['year'] === (int) $year;
        }));

        return response()->json(['data' => $filtered]);
    }

    public function search($term)
    {
        $term = strtolower($term);
        $filtered = array_values(array_filter($this->movies, function ($movie) use ($term) {
            return strpos(strtolower($movie['title']), $term) !== false;
        }));

        return response()->json(['data' => $filtered]);
    }

    public function cast($id)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json([
            'data' => [
                'movie_id' => $movie['id'],
                'title' => $movie['title'],
                'cast' => $this->casts[$movie['id']] ?? [],
            ],
        ]);
    }

    public function topRated()
    {
        $sorted = $this->movies;

        usort($sorted, function ($a, $b) {
            return $b['rating'] <=> $a['rating'];
        });

        return response()->json(['data' => $sorted]);
    }

    public function byDirector($name)
    {
        $needle = strtolower($name);

        $filtered = array_values(array_filter($this->movies, function ($movie) use ($needle) {
            return strpos(strtolower($movie['director']), $needle) !== false;
        }));

        return response()->json(['data' => $filtered]);
    }

    public function similar($id)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $similar = array_values(array_filter($this->movies, function ($item) use ($movie) {
            return $item['id'] !== $movie['id'] && strtolower($item['genre']) === strtolower($movie['genre']);
        }));

        return response()->json([
            'data' => [
                'movie' => $movie,
                'similar' => $similar,
            ],
        ]);
    }

    public function reviews($id)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        return response()->json([
            'data' => [
                'movie' => $movie,
                'reviews' => $this->reviews[$movie['id']] ?? [],
            ],
        ]);
    }

    public function storeReview($id, Request $request)
    {
        $movie = $this->findById((int) $id);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $data = $request->validate([
            'reviewer' => 'required|string|max:80',
            'rating' => 'required|numeric|min:0|max:10',
            'comment' => 'required|string|max:500',
        ]);

        $newReview = [
            'id' => $this->nextReviewId($movie['id']),
            'reviewer' => $data['reviewer'],
            'rating' => (float) $data['rating'],
            'comment' => $data['comment'],
        ];

        return response()->json([
            'message' => 'Review created',
            'data' => [
                'movie' => $movie,
                'review' => $newReview,
            ],
            'note' => 'Demo API sem persistencia em banco',
        ], 201);
    }

    private function findById($id)
    {
        foreach ($this->movies as $movie) {
            if ($movie['id'] === $id) {
                return $movie;
            }
        }

        return null;
    }

    private function nextId()
    {
        return max(array_column($this->movies, 'id')) + 1;
    }

    private function nextReviewId($movieId)
    {
        $reviews = $this->reviews[$movieId] ?? [];

        if (empty($reviews)) {
            return 1;
        }

        return max(array_column($reviews, 'id')) + 1;
    }
}
