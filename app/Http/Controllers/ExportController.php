<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Excel;
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

    //Population MenuSub 2
    public function population_main(){
          return view('frontend.population-main');
    }

    public function population_sector(){
          return view('frontend.population_sector');
    }

    public function population_area(){
          return view('frontend.population_area');
    }

    public function population_municipality(){
          return view('frontend.population_municipality');
    }

    public function population_province(){
          return view('frontend.population_province');
    }

    public function population_sex_age_province(){
          return view('frontend.sex_age_province');
    }

    public function population_sex_age_municipality(){
          return view('frontend.sex_age_municipality');
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
                 $sheet->setColumnFormat(array('C'=>'0'));
                 $sheet->fromArray($data, null, 'A1', false, false);
             });
         })->download('xlsx');
  				 //dd($query);
  		//return $query;
      }

    }


    //Load View
}
