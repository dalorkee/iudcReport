<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Population;
use App\Province;
use App\Amphur;
use App\Tambol;
use App\Hospital;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // get_group_of_disease on ur_count_all table
    public static function get_group_of_disease($c_year)
    {
        $year = (isset($c_year)) ? $c_year : date('Y');
        $query = Population::where('c_year',$year)->groupBy('gr')->pluck('gr');
        return $query;
    }

    public static function get_group_of_disease_th($c_year)
    {
        $year = (isset($c_year)) ? $c_year : date('Y');
        $query = Population::where('c_year',$year)->groupBy('gr')->pluck('gr_th','gr');
        return $query;
    }
    //list of disease on dsgr table
    public static function list_disease()
    {
        //$query = DB::table('dsgr')->select('DISEASE','DISNAME')->where('list',1)->get();
        $query = DB::table('dsgr')->where('list',1)->pluck('DISNAME','DISEASE');
        //dd($query);
        return $query;
    }
    //List of ProvinceName TH
    public static function get_provincename_th(){
      $query = Province::pluck('prov_name','prov_code');
      return $query;
    }
    //List of AmphurName TH
    public static function get_amphurname_th(){
      $query = Amphur::pluck('amp_name','amp_code');
      return $query;
    }
    //List of TumbolName TH
    public static function get_tumbolname_th(){
      $query = Tumbol::pluck('tum_name','tum_code');
      return $query;
    }
    //List of Hospitalname TH
    public static function get_hospitalname_th(){
      $query = Tumbol::pluck('htype','code');
      return $query;
    }
    //list sector
    public static function get_pop_sector($sector){
      //if(empty($sector)) return false;
      //echo $sector;
      //exit;

      if($sector=="north-region"){
        $array_sector = array("50","51","52","58","54","55","56","57","53","63","64","65","67","60","61","62","66");
      }elseif($sector == "central-region"){
        $array_sector = array("10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","70","71","72","73","74","75","76","77");
      }elseif($sector == "north-eastern-region"){
        $array_sector = array("40","44","45","46","38","39","41","42","43","47","48","30","31","32","36","33","34","35","37","49");
      }elseif($sector == "southern-region"){
        $array_sector = array("80","81","82","83","84","85","86","90","91","91","93","94","95","96");
      }else{
        $array_sector = array("50","51","52","58","54","55","56","57","53","63","64","65","67","60","61","62","66","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","70","71","72","73","74","75","76","77","40","44","45","46","38","39","41","42","43","47","48","30","31","32","36","33","34","35","37","49","80","81","82","83","84","85","86","90","91","91","93","94","95","96");
      }
        return $array_sector;
    }
    //List Sector th
    public static function get_pop_sector_th_name(){
        //$array_name_th = array();
        $array_name_th = array("north-region" => "ภาคเหนือ",
                          "central-region" => "ภาคกลาง",
                          "north-eastern-region" => "ภาคตะวันออกเฉียงเหนือ",
                          "southern-region" => "ภาคใต้");
        return $array_name_th;
    }
}
