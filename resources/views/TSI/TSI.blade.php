<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/personars.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSI</title>
</head>
<body>
<h2>TWICE Song Info</h2>
<div>
    <form id="search" action="{{ route('search.submit') }}" method="post">
    @csrf
        <div>
            Person<input type="radio" name="choice" id="personChoice" value="1" @if(session('choice') == '1') checked @endif>
            Song<input type="radio" name="choice" id="songChoice" value="2" @if(session('choice') == '2') checked @endif>
        </div>
        <div id="personBlock">
            Name<input type="text" name="name" id="name" @if(session()->has('name')) velue="name" @endif>
            For<select name="job" id="job">
                    <option value="1">Lyrics</option>
                    <option value="2">Composer</option>
                    <option value="3">Arrange</option>
                </select>
        </div>
        <div id="songBlock">
            Title<input type="text" name="songTitle" id="songTitle">
        </div>
        <div style="margin-bottom: 10px;margin-top: 15px;">
            <input type="submit" value="Search">
        </div>
    </form>
</div>

<div style="margin-bottom: 10px;margin-top: 30px;" class="container">
    <table>
        <tr>
            <th>Title</th>
            <th>Lyrics</th>
            <th>Compose</th>
            <th>Arrange</th>
            <th>Album</th>
        </tr>
        
        @if(session()->has('songs'))
            @php
                $arrayLyricist = [];
                $arrayComposer = [];
                $arrayArranger = [];
            @endphp
            
            @foreach(session('songs') as $song)
            <tr>
                <td>{{ $song['title'] }}</td>
                <td>
                    @if(session('choice') == '1')
                        {{ $song['lyricists'] }}
                    @else
                        @foreach($song['lyricists'] as $lyricist)
                            @if (!in_array($lyricist['name'], $arrayLyricist))
                                {{ $lyricist['name'] }},
                            @endif
                            @php  
                                array_push($arrayLyricist, $lyricist['name']);
                            @endphp
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(session('choice') == '1')
                        {{ $song['composers'] }}
                    @else
                        @foreach($song['composers'] as $composer)
                            @if (!in_array($composer['name'], $arrayComposer))
                                {{ $composer['name'] }},
                            @endif
                            @php  
                                array_push($arrayComposer, $composer['name']);
                            @endphp
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(session('choice') == '1')
                        {{ $song['arrangers'] }}
                    @else
                        @foreach($song['arrangers'] as $arranger)
                            @if (!in_array($arranger['name'], $arrayArranger))
                                {{ $arranger['name'] }},
                            @endif
                            @php  
                                array_push($arrayArranger, $arranger['name']);
                            @endphp
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(session('choice') == '1')
                        {{ $song['album'] }}
                    @else
                        {{ $song['album']['title'] }}
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
        
    </table>
</div>

<script>
let personChoice = document.getElementById("personChoice");
let songChoice = document.getElementById("songChoice");
let personBlock = document.getElementById("personBlock");
let songBlock = document.getElementById("songBlock");

// ラジオボタンが変更された時のイベントリスナーを追加
personChoice.addEventListener("change", toggleTextBox);
songChoice.addEventListener("change", toggleTextBox);

// テキストボックスの表示を切り替える関数
function toggleTextBox() {
    if (personChoice.checked) {
        personBlock.style.display = "block";
        songBlock.style.display = "none";
    } else if (songChoice.checked) {
        personBlock.style.display = "none";
        songBlock.style.display = "block";
    }
}

// ページ読み込み時に初期表示を設定
toggleTextBox();
</script>
</body>
</html>