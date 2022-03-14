<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ParsingController;

class Delete2Controller extends Controller
{

 

    public function __construct(){

    }
    public function test(){

        $ctr = new ParsingController;
        $result = $ctr->bmyo( '2014 Honda Civic EX- manual, accident free');
        return $result;
    }
}
