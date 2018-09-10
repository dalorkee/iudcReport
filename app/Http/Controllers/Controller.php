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
    public static function list_merge_disease(){
      $query = DB::table('dsgr')->whereIn('DISEASE',[-1,-2,-3,-4,-5,-6,-7,-8])->pluck('DISNAME','dis_code_grp');
      return $query;
    }
    public static function All_disease(){
      $dis_all = self::list_disease();
      $dis_total_code = self::list_merge_disease();
      foreach ($dis_total_code as $key_merge => $val_merge){
        //echo $val_merge;
        self::array_push_assoc($dis_all,$key_merge,$val_merge);
      }
      return $dis_all;
    }
    public static function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
    }
    //List of ProvinceName TH
    public static function get_provincename_th(){
      $query = Province::orderBy('dpc_code','asc')->pluck('prov_name','prov_code');
      //$query = DB::table('c_province')->orderBy('dpc_code','asc')->get();
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
    public static function get_dpc_nameth(){
      $query = Province::pluck('prov_dpc','prov_code');
      return $query;
    }
    //list sector
    public static function get_pop_sector($sector){

      if($sector=="north-region"){
        $array_sector = array("50","51","52","58","54","55","56","57","53","63","64","65","67","60","61","62","66");
      }elseif($sector == "central-region"){
        $array_sector = array("10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","70","71","72","73","74","75","76","77");
      }elseif($sector == "north-eastern-region"){
        $array_sector = array("40","44","45","46","38","39","41","42","43","47","48","30","31","32","36","33","34","35","37","49");
      }elseif($sector == "southern-region"){
        $array_sector = array("80","81","82","83","84","85","86","90","91","91","93","94","95","96");
      //}//elseif($sector == "all-region"){
      //  $array_sector = array("50","51","52","58","54","55","56","57","53","63","64","65","67","60","61","62","66","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","70","71","72","73","74","75","76","77","40","44","45","46","38","39","41","42","43","47","48","30","31","32","36","33","34","35","37","49","80","81","82","83","84","85","86","90","91","91","93","94","95","96");
      }else{
        $array_sector = array();
      }
        return $array_sector;
    }
    //list sector
    public static function get_pop_sector_not_key(){


        $array_sector[] = array('north-region' => array("50","51","52","58","54","55","56","57","53","63","64","65","67","60","61","62","66"));

        $array_sector[] = array('central-region' =>array("10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","70","71","72","73","74","75","76","77"));

        $array_sector[] = array('north-eastern-region' =>array("40","44","45","46","38","39","41","42","43","47","48","30","31","32","36","33","34","35","37","49"));

        $array_sector[] = array('southern-region' =>array("80","81","82","83","84","85","86","90","91","91","93","94","95","96"));

        return $array_sector;
    }
    //List Sector th
    public static function get_pop_sector_th_name(){
        //$array_name_th = array();
        $array_name_th = array("north-region" => "ภาคเหนือ",
                          "central-region" => "ภาคกลาง",
                          "north-eastern-region" => "ภาคตะวันออกเฉียงเหนือ",
                          "southern-region" => "ภาคใต้",
                          "all-region" => "รวมทุกภาค");
        return $array_name_th;
    }
    //DPC code group
    public static function get_pop_dpc_group(){

          $array_dpc_code['dpc01'] = array('50','51','52','54','55','56','57','58');

          $array_dpc_code['dpc02'] = array('53','63','64','65','67');

          $array_dpc_code['dpc03'] = array('18','60','61','62','66');

          $array_dpc_code['dpc04'] = array('12','13','14','15','16','17','19','26');

          $array_dpc_code['dpc05'] = array('70','71','72','73','74','75','76','77');

          $array_dpc_code['dpc06'] = array('11','20','21','22','23','24','25','27');

          $array_dpc_code['dpc07'] = array('40','44','45','46');

          $array_dpc_code['dpc08'] = array('38','39','41','42','43','47','48');

          $array_dpc_code['dpc09'] = array('30','31','32','36');

          $array_dpc_code['dpc10'] = array('33','34','35','37','49');

          $array_dpc_code['dpc11'] = array('80','81','82','83','84','85','86');

          $array_dpc_code['dpc12'] = array('90','91','92','93','94','95','96');

          $array_dpc_code['dpc13'] = array('10');

      return $array_dpc_code;
    }
    //DPC code
    public static function get_pop_dpc($dpc_code){
      if($dpc_code=='dpc01'){
          $array_dpc_code = array('50','51','52','54','55','56','57','58');
      }elseif($dpc_code=='dpc02'){
          $array_dpc_code = array('53','63','64','65','67');
      }elseif($dpc_code=='dpc03'){
          $array_dpc_code = array('18','60','61','62','66');
      }elseif($dpc_code=='dpc04'){
         $array_dpc_code = array('12','13','14','15','16','17','19','26');
      }elseif($dpc_code=='dpc05'){
         $array_dpc_code = array('70','71','72','73','74','75','76','77');
      }elseif($dpc_code=='dpc06'){
         $array_dpc_code = array('11','20','21','22','23','24','25','27');
      }elseif($dpc_code=='dpc07'){
         $array_dpc_code = array('40','44','45','46');
      }elseif($dpc_code=='dpc08'){
         $array_dpc_code = array('38','39','41','42','43','47','48');
      }elseif($dpc_code=='dpc09'){
         $array_dpc_code = array('30','31','32','36');
      }elseif($dpc_code=='dpc10'){
         $array_dpc_code = array('33','34','35','37','49');
      }elseif($dpc_code=='dpc11'){
         $array_dpc_code = array('80','81','82','83','84','85','86');
      }elseif($dpc_code=='dpc12'){
         $array_dpc_code = array('90','91','92','93','94','95','96');
      }elseif($dpc_code=='dpc13'){
         $array_dpc_code = array('10');
      }
      return $array_dpc_code;
    }
    //DCP Name
    public static function get_pop_dpc_nameth(){
      return array('dpc01' => 'สคร.1',
                   'dpc02' => 'สคร.2',
                   'dpc03' => 'สคร.3',
                   'dpc04' => 'สคร.4',
                   'dpc05' => 'สคร.5',
                   'dpc06' => 'สคร.6',
                   'dpc07' => 'สคร.7',
                   'dpc08' => 'สคร.8',
                   'dpc09' => 'สคร.9',
                   'dpc10' => 'สคร.10',
                   'dpc11' => 'สคร.11',
                   'dpc12' => 'สคร.12',
                   'dpc13' => 'สปคม.',
                   'dpc99' => 'รวมทุก สคร.'
                 );
    }
    //calculate ratio cases
    public static function cal_ratio_cases($total_all_pop,$total_cases){
          $total_ratio_cases = $total_cases*100000/$total_all_pop;
          return number_format($total_ratio_cases,2);
    }
    //calculate ratio deaths
    public static function cal_ratio_deaths($total_all_pop,$total_deaths){
          if($total_deaths<=0) return 0;
          $total_ratio_deaths = $total_deaths*100000/$total_all_pop;
          return number_format($total_ratio_deaths,2);
    }
    //calculate ratio cases-deaths
    public static function cal_ratio_cases_deaths($total_cases,$total_deaths){
        if($total_deaths<=0) return 0;
          $total_ratio = ($total_deaths*100)/$total_cases;
          return number_format($total_ratio,2);
    }
    //List of ProvinceCODE
    public static function get_provincecode_th(){
      $query = Province::pluck('prov_code');
      return $query;
    }
    public static function convert_zero_to_string($number){
      if($number==0){
        $ret = "0";
      }else{
        $ret = (string)$number;
      }
      return $ret;
    }
    public static function list_disease_all(){

      $get_all_disease_merge = \App\Http\Controllers\Controller::list_merge_disease();
      $get_all_disease = \App\Http\Controllers\Controller::list_disease();

      //add array
      function array_push_assoc($array, $key, $value){
      $array[$key] = $value;
      return $array;
      }

      foreach ($get_all_disease_merge as $key_merge => $val_merge){

        array_push_assoc($get_all_disease,$key_merge,$val_merge);
      }

      return $get_all_disease;
    }

}
