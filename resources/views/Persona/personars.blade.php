<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/TSI.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personars</title>
</head>
<body onload="changeBackGround('{{ $colorCd }}');">
<h2>Personars</h2>
<div style="margin-bottom: 10px;margin-top: 30px;" class="container">
    <table>
        <tr>
            <th>Character</th>
            <th>Persona</th>
            <th>Strong/Weak</th>
        </tr>
        @foreach($datas as $data)
        <tr>
            <td>{{ $data['character'] }}</td>
            <td>{{ $data['persona']['name'] }}</td>
            <td>{{ $data['strong'] }}/{{ $data['persona']['weak'] }}</td>
        </tr>
        @endforeach
    </table>
</div>

<div>
    <form id="Add" action="{{ route('add.submit') }}" method="post">
    @csrf
        <div id="addInfo">
            Persona<input type="text" name="addPersona" id="addPersona" velue="">
            weak<input type="text" name="addWeak" id="addWeak" velue="">
        </div>
        <div style="margin-bottom: 10px;margin-top: 15px;">
            <input type="submit" value="Add">
        </div>
    </form>
</div>

<script>
function changeBackGround(colorCd) {
    document.body.style.backgroundColor = colorCd;
}
</script>
</body>
</html>