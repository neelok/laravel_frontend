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

    public function mmyo($t, $d){  
        $title = strtolower($t);
        $description = strtolower($d);

        $title = str_replace("/", "-", $title);
        $description = str_replace("/", "-", $description);

        

        $title_arr = explode(" ", $title);
        $description_arr = explode(" ", $description);
        $car_final_year =0;

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
        // $test = [];


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
            // array_push($test, $pattern);

           

            if($car_make__ == 1){
                $car_final_make = $car_make->make;
                // array_push($test, $pattern);
                // $car_final_make = $car_make;
                break;
            } else {
                $car_make__ = preg_match($pattern,$description);
                if($car_make__ == 1){
                    $car_final_make = $car_make->make;
                    // $car_final_make = $car_make;
                    break;
                    // array_push($test, $pattern);
                }
            }

        }


        if($car_final_make == ""){
            $aliases = DB::table('car_aliases')->get();
            foreach($aliases as $alias){
                $alias__ = strtolower($alias->alias);
                

                if(str_contains($alias__, " ")){
                    $str_arr = explode(" ", $alias__);
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
    
                } else if(str_contains($alias__, "-")) {
                   $str_arr = explode("-", $alias__); 
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
                    $pattern = '/\b'.$alias__.'\b/';
                }

                $car_make__ = preg_match($pattern,$title);

           

                if($car_make__ == 1){
                    $car_final_make = $alias->make;
                    // $car_final_make = $car_make;
                    // break;
                } else {
                    $car_make__ = preg_match($pattern,$description);
                    if($car_make__ == 1){
                        $car_final_make = $alias->make;
                        // $car_final_make = $car_make;
                        // break;
                    }
                }
                

            }
        }



        // 
        // Getting Model
        // 

        $car_final_model = "";
        $car_models = DB::table('car_dbs')->select('model')->distinct()->where('make', '=', $car_final_make)->get();

        $car_models_collection = [];
        $count = 0;

        foreach($car_models as $car_model){
            array_push($car_models_collection, $car_model->model);
        }

        $car_models_collection = collect($car_models_collection);

        $car_models_collection = $car_models_collection->sortBy(function($product, $key){
            return strlen($product);
        });

        // return $car_models_collection;


        foreach($car_models_collection as $car_model_collect){
            $car_model_collect_ = strtolower($car_model_collect);
            $car_model_collect_ = str_replace("/", "-", $car_model_collect_);



            if(str_contains($car_model_collect_, " ")){
                $str_arr = explode(" ", $car_model_collect_);
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

            } else if(str_contains($car_model_collect_, "-")) {
               $str_arr = explode("-", $car_model_collect_); 
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
                $pattern = '/\b'.$car_model_collect_.'\b/';
            }

            $car_model__ = preg_match($pattern,$title);

           

            if($car_model__ == 1){
                $car_final_model = $car_model_collect;

            } else {
                $car_model__ = preg_match($pattern,$description);
                if($car_model__ == 1){
                    $car_final_model = $car_model_collect;

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


        // convert stdClass into collection
        $car_trims_collection = [];

        foreach($car_trims as $car_trim){
            array_push($car_trims_collection, $car_trim->trim);
        }

        $car_trims_collection = collect($car_trims_collection);

        $car_trims_collection = $car_trims_collection->sortBy(function($product, $key){
            return strlen($product);
        });

        foreach($car_trims_collection as $car_trim_collect){
            $car_trim_collect_ = strtolower($car_trim_collect);
            $car_trim_collect_ = str_replace("/", "-", $car_trim_collect_);


            if(str_contains($car_trim_collect_, " ")){
                $str_arr = explode(" ", $car_trim_collect_);
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

            } else if(str_contains($car_trim_collect_, "-")) {
               $str_arr = explode("-", $car_trim_collect_); 
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
                $pattern = '/\b'.$car_trim_collect_.'\b/';
            }

            $car_trim__ = preg_match($pattern,$title);
            // array_push($test, $pattern);
           

            if($car_trim__ == 1){
                $car_final_trim = $car_trim_collect;

            } else {
                $car_trim__ = preg_match($pattern,$description);
                if($car_trim__ == 1){
                    $car_final_trim = $car_trim_collect;
                }
            }

        }




        return ['year' => $car_final_year, 'make' => $car_final_make, 'model' => $car_final_model, 'trim' => $car_final_trim];
        // return $test;

       
    }


    // this function returns car kms and drive types in a an array from the kms field fo the data scraped
    // kms and drive type short form is kdt
    // done

    public function kdt($string){

        $var = strtolower($string);
        $var = str_replace("|", "", $var);
        $var = str_replace(",", "", $var);

        $pattern_price = '/\b(\d{3,6})\b/';
        $pattern_dt = '/\b(automatic|manual)\b/';

        if(preg_match($pattern_price, $var, $matches)){
            $car_kms = intval($matches[1]);
        } else {
            $car_kms = 0;
        }
        

        if(preg_match($pattern_dt, $var, $matches)){
            $car_drivetype = $matches[1];
        } else {
            $car_drivetype = "automatic";
        }
        

        return ['mileage' => $car_kms, 'drivetype' => $car_drivetype];
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
    // done
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

    // done
    public function price($string){
        $string = str_replace(["$", ",", ".00"], "", $string);

        $pattern = "/\b(\d{3,6})\b/";
        if(preg_match($pattern, $string, $matches)){
            $p =  intval($matches[1]);
            return ['price' => $p];
        } else {
            return ['price' => 0];
        };
    }

    public function cleandb(){
        $rows = DB::table('scrapes')->get();

        $count = 1;
        foreach($rows as $row){
            $title = $row->title;
            $description = $row->description;
            $pageurl = 'https://www.kijiji.ca'.$row->pageurl;
            $kms = $row->kms;
            $location = $row->location;
            $price = $row->price;
            $uid = $row->uid;

            $mmyo = $this->mmyo($title, $description);
            $kms__ = $this->kdt($kms);
            $datetimeposted = $this->posted($location);
            $price__ = $this->price($price);

            
            if($mmyo['make'] !== ""){
                try {
                    DB::table('clean_scrapes')->insert([
                        'year'=>$mmyo['year'],
                        'brand'=>$mmyo['make'],
                        'model'=>$mmyo['model'],
                        'trim'=>$mmyo['trim'],
                        'price'=>$price__['price'],
                        'mileage'=>$kms__['mileage'],
                        'drivetype'=>$kms__['drivetype'],
                        'datetimeposted' =>$datetimeposted['datetime'],
                        'pageurl'=> $pageurl, 
                        'uid'=> $uid
    
                    ]);
                } catch (\Throwable $th) {
                    return $th;
                }


            }




        }

        return "success";
    }

    
}
