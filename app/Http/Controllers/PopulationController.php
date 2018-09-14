<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Charts;
use Excel;
use App\Population;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\Controller as Controller;

class PopulationController extends Controller
{
    // public function index()
    // {
    // $chart = Charts::database(Population::all(), 'bar', 'google')
    // ->elementLabel("Total")
    // ->GroupBy('DISEASE');
    //     return view('population',compact('chart'));
    // }
    public function index()
    {
        return view('frontend.population');
    }

    public static function get_total_population($c_year)
    {
        $year = (isset($c_year)) ? $c_year : date('Y');
        $query = Population::all()->where('c_year',$year)->groupBy('gr');
        return $query;
    }
    // export_population_by_disease form
    public static function export_form()
    {
        return view('frontend.export');
    }

    public function ShowByDisease(Request $request){
      if(empty($request->year) || empty($request->disease_code)) return false;
        $year = trim($request->year);
        $post_disease_code = (isset($request->disease_code))? $request->disease_code : "01";
        $table_name = 'ur506_'.$year;
        $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
        $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();

        //Check Disease
        $disease_code =  explode(",",$post_disease_code);
        //dd($disease_code);

        if(count($disease_code)>2){

          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            //->whereIN('DISEASE',['26','27','66'])
            ->whereIn('DISEASE',$disease_code)
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }else{
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->where('DISEASE','=',$disease_code['0'])
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }
           $result['datas_province']  = $query->paginate(10);
           //$result['datas_province']  = $query->get();
           return view('frontend.showbydisease')->with($result);
    }
    public static function ShowByDiseaseSub($province,$year,$disease_code){
      if(empty($province) || empty($year) || empty($disease_code)) return false;
        $year = trim($year);
        $post_disease_code = (isset($disease_code))? $disease_code : "01";
        $table_name = "ur506_".$year;
        $province = trim($province);
        //Check Disease
        $disease_code =  explode(",",$post_disease_code);

        if(count($disease_code)>2){
          $query_amphur = DB::table($table_name)
                                         ->select(DB::raw('count(DISEASE) as total_cases,urbanname,urbancode'))
                                         ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                         //->whereIN('DISEASE',['26','27','66'])
                                         ->whereIn('DISEASE',$disease_code)
                                         ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $province)
                                         ->groupBy('urbanname');
          $result = $query_amphur->get();
        }else{
          $query_amphur = DB::table($table_name)
                                         ->select(DB::raw('count(DISEASE) as total_cases,urbanname,urbancode'))
                                         ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                         ->where('DISEASE','=',$disease_code['0'])
                                         ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $province)
                                         ->groupBy('urbanname');
          $result = $query_amphur->get();
        }
        return $result;
        //dd($result);
    }

    public function ShowByDisease_Export(Request $request){
      if(empty($request->year) || empty($request->disease_code)) return false;
        $get_provincename_th = Controller::get_provincename_th();
        $get_list_disease = Controller::list_disease();


        $year = trim($request->year);
        $post_disease_code = (isset($request->disease_code))? $request->disease_code : "01";
        $table_name = "ur506_".$year;
        $total_all_pop = PopulationController::all_population($year);
        $total_pop_in_province = PopulationController::all_population_by_province($year);
        $total_pop_in_urban = PopulationController::all_population_by_urban($year);
        //Query Province

        //Check Disease
        $disease_code =  explode(",",$post_disease_code);
        //dd($disease_code);

        if(count($disease_code)>2){
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->whereIn('DISEASE',$disease_code)
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }else{
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->where('DISEASE','=',$disease_code['0'])
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }
          $data_province = $query->get();
          //dd($data_province);

          $data_excel[] = array('จังหวัด','จำนวน(ป่วย)','อัตรา(ป่วย)','จำนวน(ตาย)','อัตรา(ตาย)','อัตรา(ป่วย/ตาย)');

          foreach($data_province as $val_data_province){
                    if(isset($total_pop_in_province[$val_data_province->PROVINCE]['poptotal_in_province'])){
                            $total_pop_province = number_format($total_pop_in_province[$val_data_province->PROVINCE]['poptotal_in_province']);
                            $cal_ratio_cases_province = Controller::cal_ratio_cases($total_pop_in_province[$val_data_province->PROVINCE]['poptotal_in_province'],$val_data_province->total_cases);
                            $cal_ratio_deaths_province = Controller::cal_ratio_cases_deaths($total_pop_in_province[$val_data_province->PROVINCE]['poptotal_in_province'],$val_data_province->total_deaths);
                            $cal_ratio_cases_deaths_province = Controller::cal_ratio_cases_deaths($val_data_province->total_cases,$val_data_province->total_deaths);
                    }else{
                    $total_pop_province = '0';
                    $cal_ratio_cases_province = '0';
                    $cal_ratio_deaths_province = '0';
                    $cal_ratio_cases_deaths_province = '0';
                  }
            $data_excel[] = array($get_provincename_th[$val_data_province->PROVINCE], //จังหวัด
                                  $val_data_province->total_cases, //จำนวน(ป่วย)
                                  $cal_ratio_cases_province, //อัตรา(ป่วย)
                                  $val_data_province->total_deaths,//จำนวน(ตาย)
                                  $cal_ratio_deaths_province, //อัตรา(ตาย)
                                  $cal_ratio_cases_deaths_province//อัตรา(ป่วย/ตาย)
                                 );
            //Query Amphur
            if(count($disease_code)>2){
              $query_amphur = DB::table($table_name)
                                             ->select(DB::raw('count(DISEASE) as total_cases,urbanname,urbancode'))
                                             ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                             ->whereIn('DISEASE',$disease_code)
                                             ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $val_data_province->PROVINCE)
                                             ->groupBy('urbanname');
            }else{
              $query_amphur = DB::table($table_name)
                                             ->select(DB::raw('count(DISEASE) as total_cases,urbanname,urbancode'))
                                             ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                             ->where('DISEASE','=',$disease_code['0'])
                                             ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $val_data_province->PROVINCE)
                                             ->groupBy('urbanname');
            }
              $data_amphur=$query_amphur->get();

                foreach($data_amphur as $val_data_amphur){
                  if(isset($total_pop_in_urban[$val_data_amphur->urbanname]['poptotal_in_urban'])){
                          $total_pop_urban = number_format($total_pop_in_urban[$val_data_amphur->urbanname]['poptotal_in_urban']);
                          $cal_ratio_cases_urban = Controller::cal_ratio_cases($total_pop_in_urban[$val_data_amphur->urbanname]['poptotal_in_urban'],$val_data_amphur->total_cases);
                          $cal_ratio_deaths_urban = Controller::cal_ratio_cases_deaths($total_pop_in_urban[$val_data_amphur->urbanname]['poptotal_in_urban'],$val_data_amphur->total_deaths);
                          $cal_ratio_cases_deaths_urban = Controller::cal_ratio_cases_deaths($val_data_amphur->total_cases,$val_data_amphur->total_deaths);
                  }else{
                    $total_pop_urban = '0';
                    $cal_ratio_cases_urban = '0';
                    $cal_ratio_deaths_urban = '0';
                    $cal_ratio_cases_deaths_urban = '0';
                  }
                  $data_excel[] = array($val_data_amphur->urbanname, //ชื่อเทศบาล
                                        $val_data_amphur->total_cases, //จำนวน(ป่วย)
                                        $cal_ratio_cases_urban, //อัตรา(ป่วย)
                                        $val_data_amphur->total_deaths,//จำนวน(ตาย)
                                        $cal_ratio_deaths_urban, //อัตรา(ตาย)
                                        $cal_ratio_cases_deaths_urban//อัตรา(ป่วย/ตาย)
                                       );
                }

          }
          $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
          //Year to DC
          $year_th = $year+543;
          //filename
          $filename = $disease_name[$post_disease_code].'-year'.$year_th;
          //sheetname
          $sheetname = 'sheet1';

          // header text
          $header_text = "ตารางข้อมูล โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

          Excel::create($filename, function($excel) use($data_excel,$sheetname,$header_text) {
              // Set the title
              $excel->setTitle('UCD-Report');
              // Chain the setters
              $excel->setCreator('Talek Team')->setCompany('Talek Team');
              //description
              $excel->setDescription('สปคม.');

              $excel->sheet($sheetname, function ($sheet) use ($data_excel,$header_text) {
                  //Header Text
                   $sheet->row(1, [$header_text]);
                   //$sheet->setAutoFilter('A2:H2');
                   $sheet->fromArray($data_excel, null, 'A2', false, false);
               });
           })->download('xlsx');
    }

    //Population Menu 2
    public function population_main(){
          return view('frontend.population-main');
      }
    public static function all_population($year){
      $query = DB::table('pop_urban_age_group')
        ->select(DB::raw("pop_urban_age_group.age_0_4+
                          pop_urban_age_group.age_5_9+
                          pop_urban_age_group.age_10_14+
                          pop_urban_age_group.age_15_24+
                          pop_urban_age_group.age_25_34+
                          pop_urban_age_group.age_35_44+
                          pop_urban_age_group.age_45_54+
                          pop_urban_age_group.age_55_64+
                          pop_urban_age_group.age_65_up as poptotal"))
        ->where('year_','=',$year);
        $result = $query->get()->toArray();
        //$total = $result[0];
        foreach ($result as $value){
          $total = $value->poptotal;
        }        return $total;
    }
    public static function all_population_by_province($year){
      $query = DB::table('pop_urban_sex')
        ->select(DB::raw("pop_urban_sex.prov_code,SUM(pop_urban_sex.male)+SUM(pop_urban_sex.female) AS poptotal"))
        ->where('pop_urban_sex.pop_year','=',$year)
        ->groupBy('pop_urban_sex.prov_code');
        $result = $query->get()->toArray();
        foreach ($result as $val){
          $data[$val->prov_code] = array('poptotal_in_province' => $val->poptotal);
        }
         return $data;
    }
    public static function all_population_by_urban($year){
      $query = DB::table('pop_urban_sex')
        ->select(DB::raw("pop_urban_sex.urbancode,SUM(pop_urban_sex.male)+SUM(pop_urban_sex.female) AS poptotal,pop_urban_sex.name_addr"))
        ->where('pop_urban_sex.pop_year','=',$year)
        ->groupBy('pop_urban_sex.name_addr');
        $result = $query->get()->toArray();
        foreach ($result as $val){
          $data[trim($val->name_addr)] = array('poptotal_in_urban' => $val->poptotal);
        }
         return $data;
    }
    public static function Show_disease_more_code($c_year,$post_disease_code){
        $get_disease_more_code = \App\Http\Controllers\Controller::list_merge_disease();
        $disease_code =  explode(",",$post_disease_code);
        $year = (isset($c_year)) ? $c_year : date('Y');
        $query = DB::table('ur_count_all')
                  ->select(DB::raw("SUM(total) AS total_case,SUM(totald) AS total_death"))
                  ->where('c_year','=',$year)
                  ->whereIn('DISEASE',$disease_code)->get()->toArray();
        //dd($query);

        $data = array();
        foreach ($query as $data_val) {
            $total_case = $data_val->total_case;
            $total_death = $data_val->total_death;
        }
        $data = array('total_case' => $total_case,'total_death' => $total_death);
        //dd($data);
        return $data;
    }
}
