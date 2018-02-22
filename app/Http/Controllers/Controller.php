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
}
