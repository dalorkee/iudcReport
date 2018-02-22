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
        return view('population');
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
        return view('export');
    }
    public function ExportByDiseaseXLS(Request $request)
    {
        $current_year = date('Y');
        $disease_code = (isset($request->disease_code)) ? $request->disease_code : '';
        $year = (isset($request->select_year)) ? $request->select_year : $current_year ;
        //dd($request);
        $table_name = "ur506_".$year;
        $query = DB::table($table_name)->where('DISEASE',$disease_code)
                                       ->whereYear('DATEDEFINE',$year);
        $result = $query->get();
        //$data = json_decode(json_encode($result), true);
        //Header custom columns
        $data[] = array('DISEASE','SEX','YEAR','MONTH','DAY','MARIETAL','RACE','RACE1','OCCUPAT','ADDRCODE','PROVINCE','urbancode','urbanname','METROPOL','TYPE','RESULT','HSERV','HOSPITAL','DATESICK','DATEDEFINE','DATEDEATH','DATERECORD','DATEREACH','ORGANISM','COMPLICA','age_group','week_no','week_nod');
        //echo count($data[0]);
        //alignment array to excel
        foreach ($result as $value)
        {
         $data[] = array('DISEASE' => $value->DISEASE,'SEX' => $value->SEX,'YEAR' => $value->YEAR,'MONTH' => $value->MONTH,'DAY' => $value->DAY,'MARIETAL' => $value->MARIETAL,'RACE' => $value->RACE,'RACE1' => $value->RACE1,'OCCUPAT' => $value->OCCUPAT,'ADDRCODE' => $value->ADDRCODE,'PROVINCE' => $value->PROVINCE,'urbancode' => $value->urbancode,'urbanname' => $value->urbanname,'METROPOL' => $value->METROPOL,'TYPE' => $value->TYPE,'RESULT' => $value->RESULT,'HSERV' => $value->HSERV,'HOSPITAL' => $value->HOSPITAL,'DATESICK' => $value->DATESICK,'DATEDEFINE' => $value->DATEDEFINE,'DATEDEATH' => $value->DATEDEATH,'DATERECORD' => $value->DATERECORD,'DATEREACH' => $value->DATEREACH,'ORGANISM' => $value->ORGANISM,'COMPLICA' => $value->COMPLICA,'age_group' => $value->age_group,'week_no' => $value->week_no,'week_nod' => $value->week_nod);
         //$data[] = array($value->DISEASE,$value->SEX,$value->YEAR,$value->MONTH,$value->DAY,$value->MARIETAL,$value->RACE,$value->RACE1,$value->OCCUPAT,$value->ADDRCODE,$value->PROVINCE,$value->urbancode,$value->urbanname,$value->METROPOL,$value->TYPE,$value->RESULT,$value->HSERV,$value->HOSPITAL,$value->DATESICK,$value->DATEDEFINE,$value->DATEDEATH,$value->DATERECORD,$value->DATEREACH,$value->ORGANISM,$value->COMPLICA,$value->age_group,$value->week_no,$value->week_nod);
        }


        //Excel Export
        //filename
        $filename = 'DISEASE'.$disease_code.'-'.$year;
        //sheetname
        $data2 = 'DISEASE'.$disease_code.'-'.$year;
        // header text
        //$data3 = "รายชื่อผู้ป่วย ".$type.' รหัสที่อยู่ '.$addr_code;

        Excel::create($filename, function($excel) use($data,$data2) {
            // Set the title
            $excel->setTitle('BOE');
            // Chain the setters
            $excel->setCreator('BOE')->setCompany('BOE');
            //description
            $excel->setDescription('สปคม.');
            $excel->sheet($data2, function ($sheet) use ($data) {
                 $sheet->fromArray($data, null, 'A1', false, false);
             });
         })->download('csv');

    }
    public function ShowByDisease(Request $request){
      if(empty($request->year) || empty($request->disease_code)) return false;
        $year = trim($request->year);
        $disease_code = trim($request->disease_code);
        $table_name = "ur506_".$year;
        //Total Data Province
        $query = DB::table($table_name)
                                       ->select(DB::raw('count(DISEASE) as total_province,PROVINCE'))
                                       ->where('DISEASE',$disease_code)
                                       ->groupBy('PROVINCE');
        $result['datas_province']  = $query->paginate(10);
        //$result['datas_province'] = $query->get();

        return view('showbydisease')->with($result);
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

}
