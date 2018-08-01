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
        $disease_code = trim($request->disease_code);
        $table_name = 'ur506_'.$year;

        if($disease_code=='26-27-66'){
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->whereIN('DISEASE',['26','27','66'])
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }else{
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->where('DISEASE','=',$disease_code)
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }
           $result['datas_province']  = $query->paginate(10);
           //$result['datas_province']  = $query->get();
           return view('frontend.showbydisease')->with($result);
    }
    public static function ShowByDiseaseSub($province,$year,$disease_code){
      if(empty($province) || empty($year) || empty($disease_code)) return false;
        $year = trim($year);
        $disease_code = trim($disease_code);
        $table_name = "ur506_".$year;
        $province = trim($province);

        if($disease_code=='26-27-66'){
          $query_amphur = DB::table($table_name)
                                         ->select(DB::raw('count(DISEASE) as total_cases,urbanname'))
                                         ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                         ->whereIN('DISEASE',['26','27','66'])
                                         ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $province)
                                         ->groupBy('urbanname');
          $result = $query_amphur->get();
        }else{
          $query_amphur = DB::table($table_name)
                                         ->select(DB::raw('count(DISEASE) as total_cases,urbanname'))
                                         ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                         ->where('DISEASE',$disease_code)
                                         ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $province)
                                         ->groupBy('urbanname');
          $result = $query_amphur->get();
        }
        return $result;
    }

    public function ShowByDisease_Export(Request $request){
      if(empty($request->year) || empty($request->disease_code)) return false;
        $get_provincename_th = Controller::get_provincename_th();
        $get_list_disease = Controller::list_disease();

        $year = trim($request->year);
        $disease_code = trim($request->disease_code);
        $table_name = "ur506_".$year;
        $total_all_pop = PopulationController::all_population($year);
        //Query Province

        if($disease_code=='26-27-66'){
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->whereIN('DISEASE',['26','27','66'])
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }else{
          $query = DB::table($table_name)
            ->select('PROVINCE')
            ->selectRaw('count('.$table_name.'.DISEASE) as total_cases')
            ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
            ->where('DISEASE','=',$disease_code)
            ->groupBy('ur506_'.$year.'.'.'PROVINCE');
        }
          $data_province = $query->get();
          //dd($data_province);

          $data_excel[] = array('จังหวัด','จำนวน(ป่วย)','อัตรา(ป่วย)','จำนวน(ตาย)','อัตรา(ตาย)','อัตรา(ป่วย/ตาย)');

          foreach($data_province as $val_data_province){
            $data_excel[] = array($get_provincename_th[$val_data_province->PROVINCE], //จังหวัด
                                  $val_data_province->total_cases, //จำนวน(ป่วย)
                                  Controller::cal_ratio_cases($total_all_pop,$val_data_province->total_cases), //อัตรา(ป่วย)
                                  $val_data_province->total_deaths,//จำนวน(ตาย)
                                  Controller::cal_ratio_deaths($total_all_pop,$val_data_province->total_deaths), //อัตรา(ตาย)
                                  Controller::cal_ratio_cases_deaths($val_data_province->total_cases,$val_data_province->total_deaths)//อัตรา(ป่วย/ตาย)
                                 );
            //Query Amphur
            if($disease_code=='26-27-66'){
              $query_amphur = DB::table($table_name)
                                             ->select(DB::raw('count(DISEASE) as total_cases,urbanname'))
                                             ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                             ->whereIN('DISEASE',['26','27','66'])
                                             ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $val_data_province->PROVINCE)
                                             ->groupBy('urbanname');
            }else{
              $query_amphur = DB::table($table_name)
                                             ->select(DB::raw('count(DISEASE) as total_cases,urbanname'))
                                             ->selectRaw('SUM(IF('.$table_name.'.RESULT = "2",1,0)) as total_deaths')
                                             ->where('DISEASE',$disease_code)
                                             ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $val_data_province->PROVINCE)
                                             ->groupBy('urbanname');
            }
              $data_amphur=$query_amphur->get();
                foreach($data_amphur as $val_data_amphur){
                  $data_excel[] = array($val_data_amphur->urbanname, //ชื่อเทศบาล
                                        $val_data_amphur->total_cases, //จำนวน(ป่วย)
                                        Controller::cal_ratio_cases($total_all_pop,$val_data_amphur->total_cases), //อัตรา(ป่วย)
                                        $val_data_amphur->total_deaths,//จำนวน(ตาย)
                                        Controller::cal_ratio_deaths($total_all_pop,$val_data_amphur->total_deaths), //อัตรา(ตาย)
                                        Controller::cal_ratio_cases_deaths($val_data_amphur->total_cases,$val_data_amphur->total_deaths)//อัตรา(ป่วย/ตาย)
                                       );
                }

          }
          //dd($data_excel);

          // if($disease_code=="26-27-66"){
          //  $disease_name = "Total D.H.F.";
          // }else{
          //  $disease_name = $get_list_disease[$disease_code];
          // }

            //filename
            $show_year = $year+543;
            $filename = 'จำนวนประชากร โรค'.$disease_code.'ปี'.$show_year;
            //sheetname
            $data2 = 'จำนวนประชากร โรค'.$disease_code.'ปี'.$show_year;

            Excel::create($filename, function($excel) use($data_excel,$data2) {
                // Set the title
                $excel->setTitle('UCD-Report');
                // Chain the setters
                $excel->setCreator('Talek Team')->setCompany('Talek Team');
                //description
                $excel->setDescription('สปคม.');
                $excel->sheet($data2, function ($sheet) use ($data_excel) {
                     $sheet->fromArray($data_excel, null, 'A1', false, false);
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

}
