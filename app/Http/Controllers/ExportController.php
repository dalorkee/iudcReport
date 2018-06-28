<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Excel;
use Response;
use App\Population;
use App\PopulationUrbanSex;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\Controller as Controller;

class ExportController extends Controller
{
    // public function index()
    // {
    //     //return view('frontend.population');
    // }

    //Load View
    public function export_by_disease(){
      return view('frontend.exportbydisease');
    }
    public function get_files_export_by_disease(Request $request){
      $current_year = date('Y');
      $disease_code = (isset($request->disease_code)) ? $request->disease_code : '';
      $year = (isset($request->select_year)) ? $request->select_year : $current_year ;

      if($disease_code=="26-27-66"){
        $file_name = 'TOTAL-DHF-Y'.$year.'.csv';
      }else{
        $file_name = 'DIS'.$disease_code.'-Y'.$year.'.csv';
      }
      $path = storage_path().'/'.'app'.'/report_disease/'.$file_name;
      if (file_exists($path)) {
          return Response::download($path);
      }else{
          dd("File Not Found");
          return view('frontend.exportbydisease');
      }
    }

    //Population MenuSub 2
    public function population_main(){
          return view('frontend.population-main');
    }

    public function population_sector(){
          return view('frontend.population-sector');
    }

    public function population_area(){
          return view('frontend.population-area');
    }

    public function population_municipality(){
          return view('frontend.population-municipality');
    }

    public function population_province(){
          return view('frontend.population-province');
    }

    public function population_sex_age_province(){
          return view('frontend.sex-age-province');
    }

    public function population_sex_age_municipality(){
          return view('frontend.sex-age-municipality');
    }

    public function post_population_sector(Request $request){

        $id_prov = Controller::get_pop_sector($request->sector);
        $sector_th_name = Controller::get_pop_sector_th_name();

        $select_year = $request->select_year;
        $query = DB::table('pop_urban_sex')
  				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
          ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
  				->whereIn('pop_urban_sex.prov_code', $id_prov)
          ->where('pop_urban_sex.pop_year','=',$select_year)
  				->groupBy(DB::raw('pop_urban_sex.prov_code'))
          ->orderBy('pop_urban_sex.prov_code','ASC')
  				->get();

        if(count($query)<1){
            dd('No Record');
        }else{

        $data[] = array('ID','PROVINCE','MALE','FEMALE');

        foreach ($query as $value){
          $data[] = array('ID' => $value->prov_code,'PROVINCE' => $value->provincename,'MALE' => (int)$value->male,'FEMALE' => (int)$value->female);
        }

        //filename
        $year = $select_year+543;
        $filename = 'จำนวนประชากรจำแนกตามเพศ '.$sector_th_name[$request->sector].'ปี'.$year;
        //sheetname
        $data2 = $sector_th_name[$request->sector].'ปี'.$year;

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

      }

    }

    public function post_population_area(Request $request){

        $select_year = $request->select_year;
        $dpc_th_name = Controller::get_pop_dpc_nameth();
        $dpc_code = Controller::get_pop_dpc($request->dpc_code);
        $query = DB::table('pop_urban_sex')
  				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
          ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
  				->whereIn('pop_urban_sex.prov_code', $dpc_code)
          ->where('pop_urban_sex.pop_year','=',$select_year)
  				->groupBy(DB::raw('pop_urban_sex.prov_code'))
          ->orderBy('pop_urban_sex.prov_code','ASC')
  				->get();

        if(count($query)<1){
            dd('No Record');
        }else{

        $data[] = array('ID','PROVINCE','MALE','FEMALE');

        foreach ($query as $value){
          $data[] = array('ID' => $value->prov_code,'PROVINCE' => $value->provincename,'MALE' => (int)$value->male,'FEMALE' => (int)$value->female);
        }
        //filename
        $year = $select_year+543;
        $filename = 'จำนวนประชากรจำแนกตามเพศ '.$dpc_th_name[$request->dpc_code].'-ปี'.$year;
        //sheetname
        $data2 = $dpc_th_name[$request->dpc_code].'ปี'.$year;

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
      }
    }

    public function post_population_province(Request $request){

            $province_name_th = Controller::get_provincename_th();
            $province_id = $request->provice_code;
            $select_year = $request->select_year;
            $query = DB::table('pop_urban_sex')
      				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
              ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
      				->where('pop_urban_sex.prov_code','=' ,$province_id)
              ->where('pop_urban_sex.pop_year','=',$select_year)
      				->get();
            if(count($query)<1){
                dd('No Record');
            }else{
            $data[] = array('ID','PROVINCE','MALE','FEMALE');

            foreach ($query as $value){
              $data[] = array('ID' => $value->prov_code,'PROVINCE' => $value->provincename,'MALE' => (int)$value->male,'FEMALE' => (int)$value->female);
            }
            //filename
            $year = $select_year+543;
            $filename = 'จำนวนประชากรจำแนกตามเพศ '.$province_name_th[$province_id].'-ปี'.$year;
            //sheetname
            $data2 = $province_name_th[$province_id].'ปี'.$year;

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
          }
    }
    public function post_population_municipality(Request $request){
          $select_year = $request->select_year;
          $query = DB::table('pop_urban_sex')
            ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.name_addr'))
            ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
            ->where('pop_urban_sex.pop_year','=',$select_year)
            ->groupBy(DB::raw('pop_urban_sex.name_addr'))
            ->orderBy('pop_urban_sex.name_addr','ASC')
            ->get();
          if(count($query)<1){
              dd('No Record');
          }else{
          $data[] = array('PROVINCE','NAME_ADDR','MALE','FEMALE');
            foreach ($query as $value){
              $data[] = array('PROVINCE' => $value->provincename,'NAME_ADDR' => $value->name_addr,'MALE' => (int)$value->male,'FEMALE' => (int)$value->female);
            }
            //filename
            $year = $select_year+543;
            $filename = 'จำนวนประชากรจำแนกตามเพศ รายพื้นที่-ปี'.$year;
            //sheetname
            $data2 = 'จำแนกตามเพศ-รายพื้นที่-ปี'.$year;

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


          }
    }


}
