<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('id')->cursorPaginate(10);

        return view('articles', ['articles' => $articles]);
    }
}
