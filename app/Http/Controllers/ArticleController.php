<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('articles.index', [
            'featured' => Article::with('author')->where('is_featured', true)->first(),
            'articles' => Article::with('author')->latest()->get(),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::with('author')->where('slug', $slug)->firstOrFail();

        return view('articles.show', [
            'article' => $article,
            'categories' => Category::orderBy('position')->get(),
        ]);
    }
}
