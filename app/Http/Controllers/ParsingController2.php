<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParsingController2 extends Controller
{

    // EXTRACTS MAKE MODEL YEAR AND OTHER FEATURES FROM TITLE AND OR DESCRIPTION 
    public function mmyo(){
        $t = "2009 Nissan Altima";
        $d = "2009 nissan altima 61,242 km style 2.5s 4 door comes with a set of summer and winter tires. great shape, a couple small dints. garage stored, no pets, non smoker.
        automatic | 61,242 km";
        $title = strtolower($t);
        $description = strtolower($d);

        

        $title_arr = explode(" ", $title);
        $description_arr = explode(" ", $description);
        $car_final_year ="";

        // Make sure that the year is between 1980 to 2022 if 4 digits
        
        $year_arr = preg_grep('/^[1,2][0, 1, 2, 9]\d\d$/', $title_arr);
        // check if the arr is not empty else go look for it in the description
        // if it has more than one run the loop to make sure that it is between 1980 and 2022

        if(count($year_arr)!= 0){
            foreach ($year_arr as $key => $value) {
                    if($value>1980 && $value <= 2022){
                    $car_final_year = $value;
                    break;
                }
  
            }

        } else {
            
                $year_arr__ = preg_grep('/^[1,2][0, 1, 2, 9]\d\d$/', $description_arr); // first look thru title and then tru the description
                if(count($year_arr__)!==0){
                    foreach ($year_arr__ as $key => $value) {
                        if($value>1980 && $value <= 2022){
                            $car_final_year = $value;
                            break;
                    }


                }


            }
 

        }




        // When you are going to filter make sure that you build ad preg match that will account of space or no space
        $car_final_make = "";
        $car_makes = DB::table('car_dbs')->select('make')->distinct()->get();

        foreach($car_makes as $car_make){
            $car_make_ = strtolower($car_make->make);
            $car_make_ = str_replace("/", "-", $car_make_);
            // $car_trim_ = strtolower($car_trim);

            if(str_contains($car_make_, " ")){
                $str_arr = explode(" ", $car_make_);
                $str_arr_count = count($str_arr);


                $pattern = '/\b(:?'.$str_arr[0].')'; 
                for ($i=1; $i <$str_arr_count ; $i++) { 
                        # code...
                    if($i == $str_arr_count-1 ){
                            $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                    } else {
                            $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                    }
                        
                }

            } else if(str_contains($car_make_, "-")) {
               $str_arr = explode("-", $car_make_); 
               $pattern = '/\b(:?'.$str_arr[0].')';
               for ($i=1; $i <count($str_arr) ; $i++) { 
                   # code...
                   if($i == count($str_arr)-1 ){
                       $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                   } else {
                       $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                   }
                   
               }

            } else {
                $pattern = '/\b'.$car_make_.'\b/';
            }

            $car_make__ = preg_match($pattern,$title);

           

            if($car_make__ == 1){
                $car_final_make = $car_make->make;
                // $car_final_make = $car_make;
                // break;
            } else {
                $car_make__ = preg_match($pattern,$description);
                if($car_make__ == 1){
                    $car_final_make = $car_make->make;
                    // $car_final_make = $car_make;
                    // break;
                }
            }

        }

        // 
        // Getting Model
        // 

        $car_final_model = "";
        $car_models = DB::table('car_dbs')->select('model')->distinct()->where('make', '=', $car_final_make)->get();

        // $car_trims = ['Ls','lt true north'];
            $test = [];
        foreach($car_models as $car_model){
            $car_model_ = strtolower($car_model->model);
            $car_model_ = str_replace("/", "-", $car_model_);
            // $car_trim_ = strtolower($car_trim);

            if(str_contains($car_model_, " ")){
                $str_arr = explode(" ", $car_model_);
                $str_arr_count = count($str_arr);


                $pattern = '/\b(:?'.$str_arr[0].')'; 
                for ($i=1; $i <$str_arr_count ; $i++) { 
                        # code...
                    if($i == $str_arr_count-1 ){
                            $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                    } else {
                            $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                    }
                        
                }

            } else if(str_contains($car_model_, "-")) {
               $str_arr = explode("-", $car_model_); 
               $pattern = '/\b(:?'.$str_arr[0].')';
               for ($i=1; $i <count($str_arr) ; $i++) { 
                   # code...
                   if($i == count($str_arr)-1 ){
                       $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                   } else {
                       $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                   }
                   
               }

            } else {
                $pattern = '/\b'.$car_model_.'\b/';
            }

            $car_model__ = preg_match($pattern,$title);

           

            if($car_model__ == 1){
                $car_final_model = $car_model->model;
                // $car_final_model = $car_model;
                // break;
            } else {
                $car_model__ = preg_match($pattern,$description);
                if($car_model__ == 1){
                    $car_final_model = $car_model->model;
                    // $car_final_model = $car_model;
                    // break;
                }
            }

        }




        // 
        // Getting Trims
        // 


        $car_final_trim = "";
        $car_trims = DB::table('car_dbs')->select('trim')->where(
            [
                ['make', '=', $car_final_make],
                ['model', '=', $car_final_model]
            ])->get();

        // $car_trims = ['Ls','lt true north'];
            $test = [];
        foreach($car_trims as $car_trim){
            $car_trim_ = strtolower($car_trim->trim);
            $car_trim_ = str_replace("/", "-", $car_trim_);
            // $car_trim_ = strtolower($car_trim);

            if(str_contains($car_trim_, " ")){
                $str_arr = explode(" ", $car_trim_);
                $str_arr_count = count($str_arr);


                $pattern = '/\b(:?'.$str_arr[0].')'; 
                for ($i=1; $i <$str_arr_count ; $i++) { 
                        # code...
                    if($i == $str_arr_count-1 ){
                            $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                    } else {
                            $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                    }
                        
                }

            } else if(str_contains($car_trim_, "-")) {
               $str_arr = explode("-", $car_trim_); 
               $pattern = '/\b(:?'.$str_arr[0].')';
               for ($i=1; $i <count($str_arr) ; $i++) { 
                   # code...
                   if($i == count($str_arr)-1 ){
                       $pattern .= '[\s|-]?(:?'.$str_arr[$i].')\b/';
                   } else {
                       $pattern.='[\s|-]?(:?'.$str_arr[$i].')';
                   }
                   
               }

            } else {
                $pattern = '/\b'.$car_trim_.'\b/';
            }

            $car_trim__ = preg_match($pattern,$title);
            array_push($test, $pattern);
           

            if($car_trim__ == 1){
                $car_final_trim = $car_trim->trim;
                // $car_final_trim = $car_trim;
                // break;
            } else {
                $car_trim__ = preg_match($pattern,$description);
                if($car_trim__ == 1){
                    $car_final_trim = $car_trim->trim;
                    // $car_final_trim = $car_trim;
                    // break;
                }
            }

        }




        return [$car_final_year, $car_final_make, $car_final_model, $car_final_trim];
        // return $pattern;
        // return $car_trim__;
        // return $test;
       
    }

}
