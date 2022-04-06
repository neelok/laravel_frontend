<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GetCarTrims extends Controller
{
    public function trims($year, $make, $model){
        $api_key = $_ENV['CAR_TRIMS_API_KEY'];
        $response = Http::get('https://carmakemodeldb.com/api/v1/car-lists/get/trims/'.$year.'/'.$make.'/'.$model.'?api_token='.$api_key);
        $arr = json_decode($response->body());

        $res = [];

        foreach($arr as $unit){
            array_push($res, $unit->trim);
        }
        return $res;
    }
}
