<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function news()
    {
        $data = News::getPosts();
        return view('pages.news.index', compact('data'));
    }

    public function post($slug)
    {
        $data = News::getPost($slug);

        abort_if(!$data, 404);

        return view('pages.news.show', compact('data'));
    }
}
