<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DataTableController extends Controller
{
    public function fetchdata($id){
        // $id = 0;
        $freshcarsdata = DB::table('clean_scrapes')->where('id', '>', $id)->get();
        $i = 0;
        $latest_id;
        $data_array =[];

        foreach($freshcarsdata as $freshcardata){
            $i++;
            $data_as_json =[];
            $data_as_json['id'] = $freshcardata->id;
            $data_as_json['year'] = $freshcardata->year;
            $data_as_json['make'] = $freshcardata->brand;
            $data_as_json['model'] = $freshcardata->model;
            $data_as_json['trim'] =  $freshcardata->trim;
            $data_as_json['mileage'] = $freshcardata->mileage;
            $data_as_json['drive_type'] = $freshcardata->drivetype;
            $data_as_json['time_posted'] = $freshcardata->datetimeposted;
            $data_as_json['price'] = "$ ".$freshcardata->price.".00";
            
            array_push($data_array, $data_as_json);
            $latest_id = $data_as_json['id'];
            // $data_array[$i]= $data_as_json;

        }

        if($id == 0){
            return json_encode(['data' => $data_array]);
        } else {
            return json_encode($data_array);
        }
        
        
    }
}
