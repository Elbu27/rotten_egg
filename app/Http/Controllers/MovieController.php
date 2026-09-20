<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Movie::with('user', 'genres');

        //Search by title
        if ($search = $request->input('q')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        //Filter by genre ID
        if ($genreId = $request->input('genre')) {
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        //Paginate (keeps q & genre in links)
        $movies = $query->paginate(6)->withQueryString();

        //Needed for the <select> in Blade
        $genres = \App\Models\Genre::orderBy('name')->get();

        return view('movies.index', compact('movies', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // The route's can:create middleware applies MoviePolicy consistently.
        $genres = \App\Models\Genre::all();

        return view('movies.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // $this->authorize('create', MovieController::class);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'release_year' => 'required|integer',
            'age_rating' => 'required|integer',
            'poster' => 'nullable|image|max:2048',
            'genres' => 'array'
        ]);

        $data = $request->only(['title','description','release_year','age_rating']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters','public');
        }
        
        $movie = \App\Models\Movie::create($data);
        $movie->genres()->attach($request->genres);

        return redirect()->route('movies.show', $movie)->with('success', 'Movie created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Movie $movie)
    {
        $movie->load(['comments.user', 'ratings.user', 'genres']);

        return view('movies.show', compact('movie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Movie $movie)
    {
        
        $genres = \App\Models\Genre::all();

        return view('movies.edit', compact('movie','genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'release_year' => 'required|integer',
            'age_rating' => 'required|integer',
            'poster' => 'nullable|image|max:2048',
            'genres' => 'array'
        ]);

        $movie->update($request->only(['title','description','release_year','age_rating']));

        if ($request->hasFile('poster')) {
            $movie->poster = $request->file('poster')->store('posters','public');
            $movie->save();
        }

        $movie->genres()->sync($request->genres);

        return redirect()->route('movies.show', $movie)->with('success','Movie updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Movie $movie)
    {
        $movie->delete();
        return redirect()->route('movies.index')->with('success','Deleted successfully!');
    }
}
