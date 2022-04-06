<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function go(){
        return "Testing is on";
    }

    public function nogo(){
        return "Testing is continuoing from the other controller";
    }
}
