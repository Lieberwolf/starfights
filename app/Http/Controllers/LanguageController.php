<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function set($lang) {
        session(['applocale' => $lang]);

        return back();
    }
}
