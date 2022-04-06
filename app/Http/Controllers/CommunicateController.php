<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommunicateController extends Controller
{
    public $cardealsarray;
    private $lastrowprocessed;

    public function __construct(){
        $this->cardealsarray = [];
    }



    public function sendmail(){
        $success = 'false';
        $email = new \SendGrid\Mail\Mail();

        $contents = $this->cardealsarray;

        $count = count($contents);
        $email_body_html = "";
        $email_body_text = "";
        $i = 1;
        foreach($contents as $content){
            $email_body_html .= "<h2>".$content['mileage']."kms | ".$content['year']." | ".$content['make']." | ".$content['model']." | ".$content['trim']." | $".$content['price'].".00</h2><div><a href = '".$content['url']."'>Click Here </a> For the respective ad on Kijiji</div><br><br><hr style = 'width: 50%; background-color: gray; margin-left: 0; margin-right: auto'>";
            $email_body_text .= "Mileage: ".$content['mileage'].", Year: ".$content['year'].", Make: ".$content['make'].", Model: ".$content['model'].", Trim".$content['trim'].", Url for the Ad: ".$content['url'];
            $i++;

        }
        $email_subject = "Car deal Alerts: ".$count." cars found on Classified Ads";

        

        $email->setFrom("alec@oasysdigitalarts.com", "Adimn");
        $email->setSubject($email_subject);
        $email->addTo("neelok@msn.com", "alok");
        $email->addContent('text/plain', $email_body_text);

        $email->addContent("text/html", $email_body_html);

        $sendgrid = new \SendGrid($_ENV['SENDGRID_API_KEY']);

        try{
            $response = $sendgrid->send($email);
            if($response->statusCode() >=200 && $response->statusCode() < 300){
                Storage::disk('local')->put("test.txt", $this->lastrowprocessed);
                $success = 'true';
            } else {
                $success = 'false';
            }
        } catch(Exception $e) {
            return $e;
        }

        return $success;
    }


    public function sendgooddeals(){
        $lastId = 0;
        $this->cardealsarray = [];


        if(Storage::disk('local')->exists('test.txt')){
            $lastId = intval(Storage::disk('local')->get("test.txt"));
        } else {
            Storage::disk('local')->put("test.txt", $lastId);
        }

        
       
        $this-> lastrowprocessed = DB::table('clean_scrapes')->orderBy('id', 'desc')->first()->id;
        
        $newrows = DB::table('clean_scrapes')->where('id', '>', $lastId)->get();
        $count = $newrows->count();
        
        if($count > 0){
            // do all the filtering and email
            // retrieve the last id from the db and write it back to the file
            // get the filter criteria
            // make an api call if need be to get the price
            // email send notification to the main page

            foreach($newrows as $newrow){
                $year = $newrow->year;
                $mileage = $newrow ->mileage;
                $make = $newrow->brand;
                $model = $newrow->model;
                $trim = $newrow ->trim;
                $price = $newrow->price;

                if($this->isRecordcomplete($year, $mileage, $model, $trim, $price)){
                    $arr['year'] =$year;
                    $arr['mileage'] = $mileage;
                    $arr['make'] = $make;
                    $arr['model'] = $model;
                    $arr['price'] =  $price;
                    $arr['trim'] = $trim;
                    $arr['url'] = $newrow->pageurl;
                    array_push($this->cardealsarray, $arr);
                }

            }
            
        } else {
            // return an indication that nothing new found
            return false;
        }
        $clean_count = count($this->cardealsarray);

        if($clean_count > 0 ){
            return $this->sendmail();
        } else {
            return false;
        }

        

    }

    public function isRecordcomplete($year, $mileage, $model, $trim, $price){
        if($year < 2011){
            return false;
        }
        if($mileage < 0 || $mileage > 200000){
            return false;
        }

        if($model == "" ){
            return false;
        }

        if($trim == ""){
            return false;
        }

        if($price <= 0){
            return false;
        }
        
        return true;



    }
}
