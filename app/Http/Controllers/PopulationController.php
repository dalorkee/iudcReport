<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use Charts;
use Excel;
use App\Population;
use Illuminate\Support\Facades\DB;

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
        $table_name = "ur506_".$year;

        $query = DB::table('c_province')
            ->select('c_province.prov_code','c_province.prov_name','u.total_province as total_province')
            ->leftjoin(DB::raw('(SELECT '.$table_name.'.PROVINCE,count('.$table_name.'.DISEASE) as total_province FROM '.$table_name.' WHERE DISEASE='.$disease_code.' GROUP BY '.$table_name.'.PROVINCE) u'),
            function($join)
            {
               $join->on('c_province.prov_code', '=', 'u.PROVINCE');
            })
            ->orderBy('c_province.prov_code', 'ASC');

           $result['datas_province']  = $query->paginate(10);
          // $result['datas_province']  = $query->get();
           return view('frontend.showbydisease')->with($result);
    }
    public static function ShowByDiseaseSub($province,$year,$disease_code){
      if(empty($province) || empty($year) || empty($disease_code)) return false;
        $year = trim($year);
        $disease_code = trim($disease_code);
        $table_name = "ur506_".$year;
        $province = trim($province);
        $query_amphur = DB::table($table_name)
                                       ->select(DB::raw('count(DISEASE) as total_amphur,urbanname'))
                                       ->where('DISEASE',$disease_code)
                                       ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $province)
                                       ->groupBy('urbanname');
        $result = $query_amphur->get();
        return $result;
    }

    public function ShowByDisease_Export(Request $request){
      if(empty($request->year) || empty($request->disease_code)) return false;
        $get_all_disease =\App\Http\Controllers\Controller::get_provincename_th();
        $select_year = trim($request->year);
        $disease_code = trim($request->disease_code);
        $table_name = "ur506_".$select_year;
        //Query Province
        $query_province = DB::table('c_province')
            ->select('c_province.prov_code','c_province.prov_name','u.total_province as total_province')
            ->leftjoin(DB::raw('(SELECT '.$table_name.'.PROVINCE,count('.$table_name.'.DISEASE) as total_province FROM '.$table_name.' WHERE DISEASE='.$disease_code.' GROUP BY '.$table_name.'.PROVINCE) u'),
            function($join)
            {
               $join->on('c_province.prov_code', '=', 'u.PROVINCE');
            })
            ->orderBy('c_province.prov_code', 'ASC')->get()->toArray();
            //dd($query_province);
            $data[] = array('PROVINCE','TOTAL');

            foreach ($query_province as $val_province_name){
              // +"prov_code": 10
              // +"prov_name": "กรุงเทพมหานคร"
              // +"total_province": 61048
              $data[] = array('PROVINCE' => $val_province_name->prov_name,'TOTAL' => $val_province_name->total_province);
              //Query Amphur/////
              $query_amphur = DB::table($table_name)
                                             ->select(DB::raw('count(DISEASE) as total_amphur,urbanname'))
                                             ->where('DISEASE',$disease_code)
                                             ->where(\DB::raw('substr(urbancode, 1, 2)'), '=' , $val_province_name->prov_code)
                                             ->groupBy('urbanname');
              $result = $query_amphur->get()->toArray();
              //dd($result);
              // +"total_amphur": 37
              // +"urbanname": "แขวงตลาดยอด"
              foreach ($result as $val_amphur){
                $data[] = array('PROVINCE' => '   -'.$val_amphur->urbanname,'TOTAL' => $val_amphur->total_amphur);
              }

            }

            //filename
            $year = $select_year+543;
            $filename = 'จำนวนประชากร โรค'.$get_all_disease[$disease_code].'ปี'.$year;
            //sheetname
            $data2 = 'จำนวนประชากร โรค'.$get_all_disease[$disease_code].'ปี'.$year;

            Excel::create($filename, function($excel) use($data,$data2) {
                // Set the title
                $excel->setTitle('UCD-Report');
                // Chain the setters
                $excel->setCreator('Talek Team')->setCompany('Talek Team');
                //description
                $excel->setDescription('สปคม.');
                $excel->sheet($data2, function ($sheet) use ($data) {
                     $sheet->fromArray($data, null, 'A1', false, false);
                 });
             })->download('xlsx');


            dd($data);
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
}
