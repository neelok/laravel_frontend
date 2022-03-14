<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GetCarTrims;

class ParsingController extends Controller
{
    private $array;

    public function __construct(){
        $this ->array = [];
    }


    // brand model year ... shor form is bmy
    // this functions returns car brand model year and other features based on title of the data scraped
    // thin one returns an array

    public function bmyo(){
        $string = "2012 nissan altima";
        $var = strtolower($string);
        $arr = explode(" ", $var);
        $car_final_year = intval($arr[0]);
        $car_final_brand = "";
        $car_final_model = "";

        $car_brands = DB::table('cars')->pluck('brand');
        foreach($car_brands as $car_brand){
            $car_brand = strtolower($car_brand);
            if(str_contains($var, $car_brand)){
                $car_final_brand = $car_brand;
            }

        }

        $car_models = DB::table('cars')->where('brand','=', $car_final_brand)->pluck('models');
        foreach($car_models as $car_model){
            $car_model = strtolower($car_model);
            if(str_contains($var, $car_model)){
                $car_final_model = $car_model;
            }
        }

        // $car_trims = 
        if(!empty($car_final_year) && !empty($car_final_brand) && !empty($car_final_model)){
            $trim_arr = (new GetCarTrims)->trims($car_final_year, $car_final_brand, $car_final_model);
        }


        $other_features = str_replace($car_final_brand, "", $var);
        $other_features = str_replace($car_final_model, "", $other_features);
        $other_features = str_replace($arr[0], "", $other_features);



        // return ["brand" => $car_final_brand, "model" => $car_final_model, "year"=> $car_final_year, "other_features" => $other_features];
        return $trim_arr;
       
    }

    // this function returns car kms and drive types in a an array from the kms field fo the data scraped
    // kms and drive type short form is kdt

    public function kdt($string){

        $var = strtolower($string);
        $var = str_replace("|", "", $var);
        $var = str_replace(",", "", $var);
        $var = preg_replace('/[\t\n\r\s]+/', ' ', $var);

        $arr = explode(" ", $var);

        $drivetypearr = ['automatic', 'manual'];
        
        $car_kms = 0;
        $car_drivetype = "";

        if(str_contains($var, "km")){
            $car_kms = intval($arr[1]);
        }

    
        foreach($drivetypearr as $drivetype){
 
            if(str_contains($var, $drivetype)){
                $car_drivetype = $drivetype;
            }

        }


       return [ "mileage" => $car_kms, "drivetype" => $car_drivetype];
    }


    // this is just a helper function to extract a substring that lies between 2 strings
    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    //  this function returns the date posted from the location data field
    public function posted($string){

        $var = strtolower($string);
        $var = str_replace(",", "", $var);
        $var = str_replace("minutes", "minute", $var);
        $var = str_replace("hours", "hour", $var);
        $var = preg_replace('/[\t\n\r\s]+/', ' ', $var);

        $arr = explode(" ", $var);

        date_default_timezone_set('America/Vancouver');
        $dt = date_create(date("Y/m/d h:i:sa"));


        
        $car_posted_within = "";
        $date_posted ="";
        $matches = [];


        if(str_contains($var, "minute")){
            $car_posted_within = "minute";
            $date_posted = $this->get_string_between($string, "<", "minute");
            date_sub($dt, date_interval_create_from_date_string($date_posted."minutes"));
        } elseif(str_contains($var, "hour")){
            $car_posted_within = "hour";
            $date_posted = $this->get_string_between($string, "<", "hour");
            date_sub($dt, date_interval_create_from_date_string($date_posted." hours"));
        } else {
            // date month year
            $pattern = '/[0, 1, 2]\d\/[0, 1]\d\/[1, 2][0, 9][9, 0, 1, 2]\d/';
            if(preg_match($pattern, $string, $matches)){
                $car_posted_within = "days";
                $date_posted = strtotime($matches[0]);
            } else {
                $car_posted_within = "No Date";
            }
        }



       return ['datetime' => date_format($dt, "Y m d h:i:sa")];

    }

    public function price($string){
        $string = str_replace(["$", ",", ".00"], "", $string);
        $string = intval($string);
        
        return ['price' => $string];
    }

    
}
