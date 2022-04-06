<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Scrape;
use App\Http\Controllers\ParsingController;

class UrlScrapper extends Controller
{
    private $url;
    private $total_inserted;

    public function __construct(){
        $this->url = "138.128.241.63/scrape";
        $this->total_inserted = 0;
    }


    public function callfordata(){

        // Sending an api request to scrape the data
        $response = Http::get($this->url);

        //Converting the Json Response to array for easy parsing
        $scrape_results = json_decode($response, true);
        $cln__title_obj = new ParsingController;

        // Running a for loop and extracting the data and putting it in the local variables
        // to sore them in database

        foreach($scrape_results as $scrape_result){

            if(array_key_exists('location', $scrape_result )){
                $location = $scrape_result['location']; 
                // returns datetime the ad was posted\        
                
            } else {
                $location = '';
            }
              
            if(array_key_exists('picture', $scrape_result )){
                $picture = $scrape_result['picture'];
            } else {
                $picture = '';
            }

            if(array_key_exists('pageurl', $scrape_result )){
                $pageurl = $scrape_result['pageurl'];
            } else {
                $pageurl = '';
            }

            if(array_key_exists('price', $scrape_result )){
                $price = $scrape_result['price'];
            } else {
                $price = '';
            }

            if(array_key_exists('title', $scrape_result )){
                $title = $scrape_result['title'];

            } else {
                $title = '';
            }

            if(array_key_exists('description', $scrape_result )){
                $description = $scrape_result['description'];
            } else {
                $description= '';
            }

            if(array_key_exists('distance', $scrape_result )){
                $distance = $scrape_result['distance'];
            } else {
                $distance = '';
            } 

            if(array_key_exists('kms', $scrape_result )){
                $kms = $scrape_result['kms'];
            } else {
                $kms = '';
            }

            if(array_key_exists('dealer', $scrape_result )){
                $dealer = $scrape_result['dealer'];
            } 

            if(!$dealer){
                $description = strtolower($description);
                if (str_contains($description, "dealer")){
                    $dealer = true;
                } else {
                    $dealer = false;
                }
            }

            



            if(str_contains($pageurl, "part")){
                $parts = true;
            } else {
                $parts = false;
            }

            // Creating an unique id so to prevent same data entering the database twice
            $uid = md5($price.$title);


            
            

            // so long as the data doesnt already exist in the database and it is not posted by a dealer
            // this data needs to cleaned up and added

            if (DB::table('scrapes')->where('uid', $uid)->doesntExist() && !$dealer && !$parts) {
                $id = DB::table('scrapes')->insertGetId(
                    [
                        'pictureurl' => $picture, 
                        'pageurl' => $pageurl,
                        'price' => $price, 
                        'title' => $title,
                        'description' => $description,  
                        'distance' => $distance,
                        'kms' => $kms, 
                        'location' => $location,
                        'uid' => $uid
                        ]
                );

                $this->total_inserted+=1;

                // year brand model and other features
                $cln__title_arr = $cln__title_obj->mmyo($title, $description);
                $cln__year = $cln__title_arr['year'];
                $cln__make = $cln__title_arr['make'];
                $cln__model = $cln__title_arr['model'];
                $cln__trim = $cln__title_arr['trim'];

                // mileage and drivetype
                $cln__title_arr = $cln__title_obj->kdt($kms); 
                $cln__mileage = $cln__title_arr['mileage'];
                $cln__drivetype = $cln__title_arr['drivetype'];

                // date time
                $cln__title_arr = $cln__title_obj->posted($location);
                $cln__datetime_posted = $cln__title_arr['datetime'];

                // price
                $cln__title_arr = $cln__title_obj->price($price);
                $cln__price = $cln__title_arr['price'];
                // page url
                $cln__pageurl = "https://www.kijiji.ca".$pageurl;

                if($cln__make != ""){
                    DB::table('clean_scrapes')->insert([
                        'year' => $cln__year,
                        'brand' => $cln__make,
                        'model' => $cln__model,
                        'otherfeatures' => "",
                        'mileage' => $cln__mileage,
                        'drivetype' => $cln__drivetype,
                        'datetimeposted' => $cln__datetime_posted,
                        'price' => $cln__price,
                        'pageurl' => $cln__pageurl,
                        'trim' => $cln__trim,
                        'uid' => $uid
    
                    ]);

                }


            }




        }





        // this snippet keeps tract of how many data entered in the database
        // i have to add a functionality to see if an error has occured

        DB::table('scrape_cycles')->insert([
 
            'TotalNewCarsAdded' => $this->total_inserted,
            'error' => false
        ]);

        return $this->total_inserted." records inserted";
        
    }
}
