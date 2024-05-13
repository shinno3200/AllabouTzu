<?php

namespace App\Http\Controllers\Persona;

use App\Enums\Personars;
use App\Http\Controllers\Controller;
use App\Models\Persona\Human;
use App\Models\Persona\Persona;
use Illuminate\Http\Request;

class PersonarsController extends Controller
{
    public function index() {
        $series = config('personars.series');
        switch ($series) {
            case 3;
                $colorCd = Personars::Persona3;
                break;
            case 4;
                $colorCd = Personars::Persona4;
                break;
            case 5;
                $colorCd = Personars::Persona5;
                break;
            default;
                $colorCd = "#FFFFFF";
                break;
        }

        $data = Human::with('Persona')->where('series', $series)->get();
        return view("/Persona.personars")->with('colorCd', $colorCd)->with('datas', $data);
    }

    public function store(Request $request) {

    }
}
