<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Author;
use App\Models\News;

class LandingController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        $featureds = News::where('is_featured', true)->get();
        $news = News::orderBy('created_at','desc')->get()->take(4);
        $topAuthors = Author::withCount('news')->orderByDesc('news_count')->take(5)->get();

        return view ('pages.landing', compact('banners', 'featureds','news','topAuthors'));
    }
}
