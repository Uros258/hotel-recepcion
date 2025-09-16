<?php

namespace App\Http\Controllers;

use App\Models\Soba;

class HomeController extends Controller
{
    public function index() {
        $sobe = Soba::orderBy('broj_sobe')->paginate(12);
        return view('home', compact('sobe'));
    }
}
