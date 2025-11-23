<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, \App\Models\Movie $movie)
    {
        $request->validate([
            'content' => 'required|min:3|max:500',
        ]);

        $comment = $movie->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('comments.single', compact('comment'))->render()
            ]);
        }

        return back();
    }
}
