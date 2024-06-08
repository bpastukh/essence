<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private const CACHE_KEY = 'articles_';
    private const CACHE_TTL_SECONDS = 60;
    private const PAGINATION_SIZE = 10;

    public function index(Repository $cache, Request $request)
    {
        $cursor = $request->query->get('cursor', '');
        $cacheKey = self::CACHE_KEY . $cursor;

        $articles = $cache->remember($cacheKey, self::CACHE_TTL_SECONDS, function () {
            return Article::orderBy('id', 'desc')->cursorPaginate(self::PAGINATION_SIZE);
        });

        return view('articles', ['articles' => $articles]);
    }
}
