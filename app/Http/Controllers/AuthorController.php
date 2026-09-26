<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show($username)
    {
        $author = Author::where('username', $username)->first();

        return view('pages.author.show', compact('author'));
    }
}
