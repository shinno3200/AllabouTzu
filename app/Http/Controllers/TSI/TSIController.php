<?php

namespace App\Http\Controllers\TSI;

use App\Http\Controllers\Controller;
use App\Models\TSI\Lyricist;
use App\Models\TSI\Song;
use Illuminate\Http\Request;

class TSIController extends Controller
{
    public function index()
    {
        // phpinfo();
        return view('/TSI.tsi');
    }

    public function search(Request $request)
    {
        $choice = $request->input('choice');
        if ($choice === '1') {
            $name = $request->name;
            $job = $request->job;
            // $songs = Lyricist::where('name',$name)->with('songs','composers','arrangers')->get()->unique(['id']);
            // 入力された名前を含むLyricistを取得する
            $lyricists = Lyricist::where('name', 'LIKE', "%$name%")->get();
            $data = [];
            foreach ($lyricists as $lyricist) {
                // Lyricistに紐づくSongを取得する
                $songs = $lyricist->songs;

                foreach ($songs as $song) {
                    // 曲名、作曲家、編曲家を取得し、一つの連想配列にまとめる
                    $songData = [
                        'title' => $song->title,
                        'lyricists' => $lyricist->name,
                        'composers' => $song->composers->implode('name', ', '),
                        'arrangers' => $song->arrangers->implode('name', ', '),
                        'album' => $song->album->title,
                    ];

                    // 一つの連想配列を$data配列に追加する
                    $data[] = $songData;
                }
            }

            return redirect('/tsi')->with('songs', $data)
                ->with('choice', $choice)
                ->with('name', $name)
                ->with('job', $job);
        } elseif ($choice === '2') {
            $songTitle = $request->songTitle;
            $songs = Song::where('title', $songTitle)->with('album', 'lyricists', 'composers', 'arrangers')->get()->unique(['id']);

            return redirect('/tsi')->with('songs', $songs)
                ->with('choice', $choice)
                ->with('songTitle', $songTitle);
        }
    }
}
