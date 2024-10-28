<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function home() {
        return view('pages.home');
    }

    public function profile() {
        return view('pages.profile');
    }

    public function songs() {
        $songs = json_decode(File::get(storage_path('json/songs.json')), true);
        return view('pages.songs', compact('songs'));
    }

    public function movie() {
        $movies = json_decode(File::get(storage_path('json/movie.json')), true);
        return view('pages.movie', compact('movies'));
    }

    public function goods() {
        $goods = json_decode(File::get(storage_path('json/goods.json')), true);
        return view('pages.goods', compact('goods'));
    }

    public function photo() {
        return view('pages.photo');
    }
}