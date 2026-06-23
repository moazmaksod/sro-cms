<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\News;
use App\Models\Pages;

class HomeController extends Controller
{
    public function index()
    {
        $data = News::getPosts();
        return view('pages.home.index', compact('data'));
    }

    public function show($slug)
    {
        $data = Pages::getPage($slug);

        abort_if(!$data, 404);

        return view('pages.content.show', compact('data'));
    }

    public function download()
    {
        $data = Download::getDownloads();
        return view('pages.download.index', compact('data'));
    }
}
