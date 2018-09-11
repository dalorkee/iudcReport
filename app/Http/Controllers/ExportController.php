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
      $post_disease_code = (isset($request->disease_code)) ? $request->disease_code : '';
      $year = (isset($request->select_year)) ? $request->select_year : $current_year ;
      $list_disease_all = \App\Http\Controllers\Controller::list_disease_all()->toArray();
      $disease_code =  explode(",",$post_disease_code);

      if(count($disease_code)>2){
        $file_name = $list_disease_all[$post_disease_code].'-Y'.$year.'.csv';
      }else{
        $file_name = 'DIS'.$disease_code['0'].'-Y'.$year.'.csv';
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
          return view('frontend.population-sex-age-province');
    }

    public function population_sex_age_municipality(){
          return view('frontend.population-sex-age-municipality');
    }

    public function post_population_sector(Request $request){

        $id_prov = Controller::get_pop_sector($request->sector);
        $sector_th_name = Controller::get_pop_sector_th_name();

        $select_year = $request->select_year;

        if($request->sector=='all-region'){
          $data[] = array('REGION','MALE','FEMALE');
          $array_sector = Controller::get_pop_sector_not_key();
          //Total north-region
          $s1 = $array_sector[0]['north-region'];
              for($j=0 ; $j < count($s1); $j++){
                $id_prov_s1[] = $s1[$j];
              }
              $query_s1 = DB::table('pop_urban_sex')
                ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female'))
                ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
                ->whereIn('pop_urban_sex.prov_code', $id_prov_s1)
                ->where('pop_urban_sex.pop_year','=',$select_year)
                ->get();
              if(count($query_s1)>0){
                    foreach ($query_s1 as $value_s1){
                      $data[] = array('REGION' => 'ภาคเหนือ','MALE' => (int)$value_s1->male,'FEMALE' => (int)$value_s1->female);
                    }
              }
              //Total central-region
              $s2 = $array_sector[1]['central-region'];
                  for($k=0 ; $k < count($s2); $k++){
                    $id_prov_s2[] = $s2[$k];
                  }
                  $query_s2 = DB::table('pop_urban_sex')
                    ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female'))
                    ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
                    ->whereIn('pop_urban_sex.prov_code', $id_prov_s2)
                    ->where('pop_urban_sex.pop_year','=',$select_year)
                    ->get();
                  if(count($query_s1)>0){
                        foreach ($query_s2 as $value_s2){
                          $data[] = array('REGION' => 'ภาคกลาง','MALE' => (int)$value_s2->male,'FEMALE' => (int)$value_s2->female);
                        }
                  }
                  //Total north-eastern-region
                  $s3 = $array_sector[2]['north-eastern-region'];
                      for($m=0 ; $m < count($s3); $m++){
                        $id_prov_s3[] = $s3[$m];
                      }
                      $query_s3 = DB::table('pop_urban_sex')
                        ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female'))
                        ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
                        ->whereIn('pop_urban_sex.prov_code', $id_prov_s3)
                        ->where('pop_urban_sex.pop_year','=',$select_year)
                        ->get();
                      if(count($query_s3)>0){
                            foreach ($query_s3 as $value_s3){
                              $data[] = array('REGION' => 'ภาคตะวันออกเฉียงเหนือ','MALE' => (int)$value_s3->male,'FEMALE' => (int)$value_s3->female);
                            }
                      }
                      //Total north-eastern-region
                      $s4 = $array_sector[3]['southern-region'];
                          for($n=0 ; $n < count($s4); $n++){
                            $id_prov_s4[] = $s4[$n];
                          }
                          $query_s4 = DB::table('pop_urban_sex')
                            ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female'))
                            ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
                            ->whereIn('pop_urban_sex.prov_code', $id_prov_s4)
                            ->where('pop_urban_sex.pop_year','=',$select_year)
                            ->get();
                          if(count($query_s4)>0){
                                foreach ($query_s4 as $value_s4){
                                  $data[] = array('REGION' => 'ภาคใต้','MALE' => (int)$value_s4->male,'FEMALE' => (int)$value_s4->female);
                                }
                          }
                          $year = $select_year+543;
                          $filename = 'จำนวนประชากรจำแนกตามเพศ รวมทุกภาค ปี'.$year;
                          $data2 = 'รวมทุกภาค ปี'.$year;
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
        }else{
              $query = DB::table('pop_urban_sex')
        				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
                ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
        				->whereIn('pop_urban_sex.prov_code', $id_prov)
                ->where('pop_urban_sex.pop_year','=',$select_year)
        				->groupBy(DB::raw('pop_urban_sex.prov_code'))
                ->orderBy('pop_urban_sex.prov_code','ASC')
        				->get();

              if(count($query)<1){
                //dd('No Record');
                $message = "ไม่พบข้อมูล";
                flash()->overlay($message, 'ข้อความจากระบบ');
                return redirect()->route('export-population.sector');
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

    }

    public function post_population_area(Request $request){

        $select_year = $request->select_year;
        $dpc_th_name = Controller::get_pop_dpc_nameth();


        if($request->dpc_code=='dpc99'){

          //dd('fdfdfdf');
          $data[] = array('DPC','MALE','FEMALE');
          $array_dpc_code_group = Controller::get_pop_dpc_group();

          for($i=1; $i <= count($array_dpc_code_group); $i++ ){
            $num_padded = 'dpc'.sprintf("%02d", $i);
            //var_dump($array_dpc_code_group[$num_padded]);
            $arr_prov_id = $array_dpc_code_group[$num_padded];
            $query[$num_padded] = DB::table('pop_urban_sex')
              ->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female'))
              ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
              ->whereIn('pop_urban_sex.prov_code', $arr_prov_id)
              ->where('pop_urban_sex.pop_year','=',$select_year)
              ->get()->toArray();
          }


          if(count($query)<1){
              //dd('No Record');
              $message = "ไม่พบข้อมูล";
              flash()->overlay($message, 'ข้อความจากระบบ');
              return redirect()->route('export-population.sector');
          }else{

            foreach ($query as $key => $value){
              $data[] = array('DPC' => $dpc_th_name[$key],'MALE' => (int)$value['0']->male,'FEMALE' => (int)$value['0']->female);
            }

            //$data[] = array('Total');
            //filename
            $year = $select_year+543;
            $filename = 'ป.จำแนกตามเพศรวมทุก สคร.-ปี'.$year;
            //sheetname
            $data2 = 'ป.จำแนกตามเพศรวมทุก สคร.-ปี'.$year;

            Excel::create($filename, function($excel) use($data,$data2) {
                // Set the title
                $excel->setTitle('UCD-Report');
                // Chain the setters
                $excel->setCreator('Talek Team')->setCompany('Talek Team');
                //description
                $excel->setDescription('สปคม.');
                $excel->sheet($data2, function ($sheet) use ($data) {
                     $sheet->fromArray($data, null, 'A1', false, false);
                     //$sheet->setCellValue('B15','=SUM(B2:B14)');
                     //$sheet->setCellValue('C15','=SUM(C2:C14)');
                 });
             })->download('xlsx');
            //dd($data);
          }


        }else{
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
                  //dd('No Record');
                  $message = "ไม่พบข้อมูล";
                  flash()->overlay($message, 'ข้อความจากระบบ');
                  return redirect()->route('export-population.sector');
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
    }

    public function post_population_province(Request $request){

            $province_name_th = Controller::get_provincename_th();
            $province_id = $request->provice_code;
            $select_year = $request->select_year;
            $year = $select_year+543;

            //dd($province_id);

          if($province_id=='All'){
            $filename = 'จำนวนประชากรจำแนกตามเพศรวมทุกจังหวัด-ปี'.$year;
            //sheetname
            $data2 = 'รวมทุกจังหวัด-ปี'.$year;
            $query = DB::table('pop_urban_sex')
      				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
              ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
              ->where('pop_urban_sex.pop_year','=',$select_year)
              ->groupBy(DB::raw('pop_urban_sex.prov_code'))
              ->orderBy('pop_urban_sex.prov_code','ASC')
      				->get();
          }else{
            $filename = 'จำนวนประชากรจำแนกตามเพศ '.$province_name_th[$province_id].'-ปี'.$year;
            //sheetname
            $data2 = $province_name_th[$province_id].'ปี'.$year;
            $query = DB::table('pop_urban_sex')
      				->select(DB::raw('sum(pop_urban_sex.male) as male , sum(pop_urban_sex.female) as female , c_province.prov_name as provincename,pop_urban_sex.prov_code'))
              ->leftjoin('c_province','pop_urban_sex.prov_code','=','c_province.prov_code')
      				->where('pop_urban_sex.prov_code','=' ,$province_id)
              ->where('pop_urban_sex.pop_year','=',$select_year)
      				->get();
          }

            if(count($query)<1){
                //dd('No Record');
                $message = "ไม่พบข้อมูล";
                flash()->overlay($message, 'ข้อความจากระบบ');
                return redirect()->route('export-population.sector');
            }else{
            $data[] = array('ID','PROVINCE','MALE','FEMALE');

            foreach ($query as $value){
              $data[] = array('ID' => $value->prov_code,'PROVINCE' => $value->provincename,'MALE' => (int)$value->male,'FEMALE' => (int)$value->female);
            }
            //filename
            //$year = $select_year+543;
            //$filename = 'จำนวนประชากรจำแนกตามเพศ '.$province_name_th[$province_id].'-ปี'.$year;
            //sheetname
            //$data2 = $province_name_th[$province_id].'ปี'.$year;

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
              //dd('No Record');
              $message = "ไม่พบข้อมูล";
              flash()->overlay($message, 'ข้อความจากระบบ');
              return redirect()->route('export-population.sector');
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
    public function post_population_sex_age_province(Request $request){
      $province_name_th = Controller::get_provincename_th();
      $province_id = $request->provice_code;
      $select_year = $request->select_year;

      if($province_id=='All'){
        //filename
        $year = $select_year+543;
        $filename = 'ป.จำแนกตามอายุและเพศทุกจังหวัด-ปี'.$year;
        //sheetname
        $data2 = 'อายุและเพศทุกจังหวัด-ปี'.$year;
        $query = DB::table('pop_urban_age')
          ->select(DB::raw('sum(pop_urban_age.m_age_0) as male_0,sum(pop_urban_age.f_age_0) as female_0,
                            sum(pop_urban_age.m_age_1) as male_1,sum(pop_urban_age.f_age_1) as female_1,
                            sum(pop_urban_age.m_age_2) as male_2,sum(pop_urban_age.f_age_2) as female_2,
                            sum(pop_urban_age.m_age_3) as male_3,sum(pop_urban_age.f_age_3) as female_3,
                            sum(pop_urban_age.m_age_4) as male_4,sum(pop_urban_age.f_age_4) as female_4,
                            sum(pop_urban_age.m_age_5) as male_5,sum(pop_urban_age.f_age_5) as female_5,
                            sum(pop_urban_age.m_age_6) as male_6,sum(pop_urban_age.f_age_6) as female_6,
                            sum(pop_urban_age.m_age_7) as male_7,sum(pop_urban_age.f_age_7) as female_7,
                            sum(pop_urban_age.m_age_8) as male_8,sum(pop_urban_age.f_age_8) as female_8,
                            sum(pop_urban_age.m_age_9) as male_9,sum(pop_urban_age.f_age_9) as female_9,
                            sum(pop_urban_age.m_age_10) as male_10,sum(pop_urban_age.f_age_10) as female_10,
                            sum(pop_urban_age.m_age_11) as male_11,sum(pop_urban_age.f_age_11) as female_11,
                            sum(pop_urban_age.m_age_12) as male_12,sum(pop_urban_age.f_age_12) as female_12,
                            sum(pop_urban_age.m_age_13) as male_13,sum(pop_urban_age.f_age_13) as female_13,
                            sum(pop_urban_age.m_age_14) as male_14,sum(pop_urban_age.f_age_14) as female_14,
                            sum(pop_urban_age.m_age_15) as male_15,sum(pop_urban_age.f_age_15) as female_15,
                            sum(pop_urban_age.m_age_16) as male_16,sum(pop_urban_age.f_age_16) as female_16,
                            sum(pop_urban_age.m_age_17) as male_17,sum(pop_urban_age.f_age_17) as female_17,
                            sum(pop_urban_age.m_age_18) as male_18,sum(pop_urban_age.f_age_18) as female_18,
                            sum(pop_urban_age.m_age_19) as male_19,sum(pop_urban_age.f_age_19) as female_19,
                            sum(pop_urban_age.m_age_20) as male_20,sum(pop_urban_age.f_age_20) as female_20,
                            sum(pop_urban_age.m_age_21) as male_21,sum(pop_urban_age.f_age_21) as female_21,
                            sum(pop_urban_age.m_age_22) as male_22,sum(pop_urban_age.f_age_22) as female_22,
                            sum(pop_urban_age.m_age_23) as male_23,sum(pop_urban_age.f_age_23) as female_23,
                            sum(pop_urban_age.m_age_24) as male_24,sum(pop_urban_age.f_age_24) as female_24,
                            sum(pop_urban_age.m_age_25) as male_25,sum(pop_urban_age.f_age_25) as female_25,
                            sum(pop_urban_age.m_age_26) as male_26,sum(pop_urban_age.f_age_26) as female_26,
                            sum(pop_urban_age.m_age_27) as male_27,sum(pop_urban_age.f_age_27) as female_27,
                            sum(pop_urban_age.m_age_28) as male_28,sum(pop_urban_age.f_age_28) as female_28,
                            sum(pop_urban_age.m_age_29) as male_29,sum(pop_urban_age.f_age_29) as female_29,
                            sum(pop_urban_age.m_age_30) as male_30,sum(pop_urban_age.f_age_30) as female_30,
                            sum(pop_urban_age.m_age_31) as male_31,sum(pop_urban_age.f_age_31) as female_31,
                            sum(pop_urban_age.m_age_32) as male_32,sum(pop_urban_age.f_age_32) as female_32,
                            sum(pop_urban_age.m_age_33) as male_33,sum(pop_urban_age.f_age_33) as female_33,
                            sum(pop_urban_age.m_age_34) as male_34,sum(pop_urban_age.f_age_34) as female_34,
                            sum(pop_urban_age.m_age_35) as male_35,sum(pop_urban_age.f_age_35) as female_35,
                            sum(pop_urban_age.m_age_36) as male_36,sum(pop_urban_age.f_age_36) as female_36,
                            sum(pop_urban_age.m_age_37) as male_37,sum(pop_urban_age.f_age_37) as female_37,
                            sum(pop_urban_age.m_age_38) as male_38,sum(pop_urban_age.f_age_38) as female_38,
                            sum(pop_urban_age.m_age_39) as male_39,sum(pop_urban_age.f_age_39) as female_39,
                            sum(pop_urban_age.m_age_40) as male_40,sum(pop_urban_age.f_age_40) as female_40,
                            sum(pop_urban_age.m_age_41) as male_41,sum(pop_urban_age.f_age_41) as female_41,
                            sum(pop_urban_age.m_age_42) as male_42,sum(pop_urban_age.f_age_42) as female_42,
                            sum(pop_urban_age.m_age_43) as male_43,sum(pop_urban_age.f_age_43) as female_43,
                            sum(pop_urban_age.m_age_44) as male_44,sum(pop_urban_age.f_age_44) as female_44,
                            sum(pop_urban_age.m_age_45) as male_45,sum(pop_urban_age.f_age_45) as female_45,
                            sum(pop_urban_age.m_age_46) as male_46,sum(pop_urban_age.f_age_46) as female_46,
                            sum(pop_urban_age.m_age_47) as male_47,sum(pop_urban_age.f_age_47) as female_47,
                            sum(pop_urban_age.m_age_48) as male_48,sum(pop_urban_age.f_age_48) as female_48,
                            sum(pop_urban_age.m_age_49) as male_49,sum(pop_urban_age.f_age_49) as female_49,
                            sum(pop_urban_age.m_age_50) as male_50,sum(pop_urban_age.f_age_50) as female_50,
                            sum(pop_urban_age.m_age_51) as male_51,sum(pop_urban_age.f_age_51) as female_51,
                            sum(pop_urban_age.m_age_52) as male_52,sum(pop_urban_age.f_age_52) as female_52,
                            sum(pop_urban_age.m_age_53) as male_53,sum(pop_urban_age.f_age_53) as female_53,
                            sum(pop_urban_age.m_age_54) as male_54,sum(pop_urban_age.f_age_54) as female_54,
                            sum(pop_urban_age.m_age_55) as male_55,sum(pop_urban_age.f_age_55) as female_55,
                            sum(pop_urban_age.m_age_56) as male_56,sum(pop_urban_age.f_age_56) as female_56,
                            sum(pop_urban_age.m_age_57) as male_57,sum(pop_urban_age.f_age_57) as female_57,
                            sum(pop_urban_age.m_age_58) as male_58,sum(pop_urban_age.f_age_58) as female_58,
                            sum(pop_urban_age.m_age_59) as male_59,sum(pop_urban_age.f_age_59) as female_59,
                            sum(pop_urban_age.m_age_60) as male_60,sum(pop_urban_age.f_age_60) as female_60,
                            sum(pop_urban_age.m_age_61) as male_61,sum(pop_urban_age.f_age_61) as female_61,
                            sum(pop_urban_age.m_age_62) as male_62,sum(pop_urban_age.f_age_62) as female_62,
                            sum(pop_urban_age.m_age_63) as male_63,sum(pop_urban_age.f_age_63) as female_63,
                            sum(pop_urban_age.m_age_64) as male_64,sum(pop_urban_age.f_age_64) as female_64,
                            sum(pop_urban_age.m_age_65) as male_65,sum(pop_urban_age.f_age_65) as female_65,
                            sum(pop_urban_age.m_age_66) as male_66,sum(pop_urban_age.f_age_66) as female_66,
                            sum(pop_urban_age.m_age_67) as male_67,sum(pop_urban_age.f_age_67) as female_67,
                            sum(pop_urban_age.m_age_68) as male_68,sum(pop_urban_age.f_age_68) as female_68,
                            sum(pop_urban_age.m_age_69) as male_69,sum(pop_urban_age.f_age_69) as female_69,
                            sum(pop_urban_age.m_age_70) as male_70,sum(pop_urban_age.f_age_70) as female_70,
                            sum(pop_urban_age.m_age_71) as male_71,sum(pop_urban_age.f_age_71) as female_71,
                            sum(pop_urban_age.m_age_72) as male_72,sum(pop_urban_age.f_age_72) as female_72,
                            sum(pop_urban_age.m_age_73) as male_73,sum(pop_urban_age.f_age_73) as female_73,
                            sum(pop_urban_age.m_age_74) as male_74,sum(pop_urban_age.f_age_74) as female_74,
                            sum(pop_urban_age.m_age_75) as male_75,sum(pop_urban_age.f_age_75) as female_75,
                            sum(pop_urban_age.m_age_76) as male_76,sum(pop_urban_age.f_age_76) as female_76,
                            sum(pop_urban_age.m_age_77) as male_77,sum(pop_urban_age.f_age_77) as female_77,
                            sum(pop_urban_age.m_age_78) as male_78,sum(pop_urban_age.f_age_78) as female_78,
                            sum(pop_urban_age.m_age_79) as male_79,sum(pop_urban_age.f_age_79) as female_79,
                            sum(pop_urban_age.m_age_80) as male_80,sum(pop_urban_age.f_age_80) as female_80,
                            sum(pop_urban_age.m_age_81) as male_81,sum(pop_urban_age.f_age_81) as female_81,
                            sum(pop_urban_age.m_age_82) as male_82,sum(pop_urban_age.f_age_82) as female_82,
                            sum(pop_urban_age.m_age_83) as male_83,sum(pop_urban_age.f_age_83) as female_83,
                            sum(pop_urban_age.m_age_84) as male_84,sum(pop_urban_age.f_age_84) as female_84,
                            sum(pop_urban_age.m_age_85) as male_85,sum(pop_urban_age.f_age_85) as female_85,
                            sum(pop_urban_age.m_age_86) as male_86,sum(pop_urban_age.f_age_86) as female_86,
                            sum(pop_urban_age.m_age_87) as male_87,sum(pop_urban_age.f_age_87) as female_87,
                            sum(pop_urban_age.m_age_88) as male_88,sum(pop_urban_age.f_age_88) as female_88,
                            sum(pop_urban_age.m_age_89) as male_89,sum(pop_urban_age.f_age_89) as female_89,
                            sum(pop_urban_age.m_age_90) as male_90,sum(pop_urban_age.f_age_90) as female_90,
                            sum(pop_urban_age.m_age_91) as male_91,sum(pop_urban_age.f_age_91) as female_91,
                            sum(pop_urban_age.m_age_92) as male_92,sum(pop_urban_age.f_age_92) as female_92,
                            sum(pop_urban_age.m_age_93) as male_93,sum(pop_urban_age.f_age_93) as female_93,
                            sum(pop_urban_age.m_age_94) as male_94,sum(pop_urban_age.f_age_94) as female_94,
                            sum(pop_urban_age.m_age_95) as male_95,sum(pop_urban_age.f_age_95) as female_95,
                            sum(pop_urban_age.m_age_96) as male_96,sum(pop_urban_age.f_age_96) as female_96,
                            sum(pop_urban_age.m_age_97) as male_97,sum(pop_urban_age.f_age_97) as female_97,
                            sum(pop_urban_age.m_age_98) as male_98,sum(pop_urban_age.f_age_98) as female_98,
                            sum(pop_urban_age.m_age_99) as male_99,sum(pop_urban_age.f_age_99) as female_99,
                            sum(pop_urban_age.m_age_100) as male_100,sum(pop_urban_age.f_age_100) as female_100,
                            sum(pop_urban_age.m_age_101) as male_101,sum(pop_urban_age.f_age_101) as female_101
                            ,c_province.prov_name as provincename,pop_urban_age.prov_code')
                  )
          ->leftjoin('c_province','pop_urban_age.prov_code','=','c_province.prov_code')
          ->where('pop_urban_age.pop_year','=',$select_year)
          ->groupBy(DB::raw('pop_urban_age.prov_code'))
          ->orderBy('pop_urban_age.prov_code','ASC')
          ->get();
      }else{
        //filename
        $year = $select_year+543;
        $filename = 'ป.จำแนกตามอายุและเพศ '.$province_name_th[$province_id].'-ปี'.$year;
        //sheetname
        $data2 = $province_name_th[$province_id].'ปี'.$year;
        $query = DB::table('pop_urban_age')
          ->select(DB::raw('sum(pop_urban_age.m_age_0) as male_0,sum(pop_urban_age.f_age_0) as female_0,
                            sum(pop_urban_age.m_age_1) as male_1,sum(pop_urban_age.f_age_1) as female_1,
                            sum(pop_urban_age.m_age_2) as male_2,sum(pop_urban_age.f_age_2) as female_2,
                            sum(pop_urban_age.m_age_3) as male_3,sum(pop_urban_age.f_age_3) as female_3,
                            sum(pop_urban_age.m_age_4) as male_4,sum(pop_urban_age.f_age_4) as female_4,
                            sum(pop_urban_age.m_age_5) as male_5,sum(pop_urban_age.f_age_5) as female_5,
                            sum(pop_urban_age.m_age_6) as male_6,sum(pop_urban_age.f_age_6) as female_6,
                            sum(pop_urban_age.m_age_7) as male_7,sum(pop_urban_age.f_age_7) as female_7,
                            sum(pop_urban_age.m_age_8) as male_8,sum(pop_urban_age.f_age_8) as female_8,
                            sum(pop_urban_age.m_age_9) as male_9,sum(pop_urban_age.f_age_9) as female_9,
                            sum(pop_urban_age.m_age_10) as male_10,sum(pop_urban_age.f_age_10) as female_10,
                            sum(pop_urban_age.m_age_11) as male_11,sum(pop_urban_age.f_age_11) as female_11,
                            sum(pop_urban_age.m_age_12) as male_12,sum(pop_urban_age.f_age_12) as female_12,
                            sum(pop_urban_age.m_age_13) as male_13,sum(pop_urban_age.f_age_13) as female_13,
                            sum(pop_urban_age.m_age_14) as male_14,sum(pop_urban_age.f_age_14) as female_14,
                            sum(pop_urban_age.m_age_15) as male_15,sum(pop_urban_age.f_age_15) as female_15,
                            sum(pop_urban_age.m_age_16) as male_16,sum(pop_urban_age.f_age_16) as female_16,
                            sum(pop_urban_age.m_age_17) as male_17,sum(pop_urban_age.f_age_17) as female_17,
                            sum(pop_urban_age.m_age_18) as male_18,sum(pop_urban_age.f_age_18) as female_18,
                            sum(pop_urban_age.m_age_19) as male_19,sum(pop_urban_age.f_age_19) as female_19,
                            sum(pop_urban_age.m_age_20) as male_20,sum(pop_urban_age.f_age_20) as female_20,
                            sum(pop_urban_age.m_age_21) as male_21,sum(pop_urban_age.f_age_21) as female_21,
                            sum(pop_urban_age.m_age_22) as male_22,sum(pop_urban_age.f_age_22) as female_22,
                            sum(pop_urban_age.m_age_23) as male_23,sum(pop_urban_age.f_age_23) as female_23,
                            sum(pop_urban_age.m_age_24) as male_24,sum(pop_urban_age.f_age_24) as female_24,
                            sum(pop_urban_age.m_age_25) as male_25,sum(pop_urban_age.f_age_25) as female_25,
                            sum(pop_urban_age.m_age_26) as male_26,sum(pop_urban_age.f_age_26) as female_26,
                            sum(pop_urban_age.m_age_27) as male_27,sum(pop_urban_age.f_age_27) as female_27,
                            sum(pop_urban_age.m_age_28) as male_28,sum(pop_urban_age.f_age_28) as female_28,
                            sum(pop_urban_age.m_age_29) as male_29,sum(pop_urban_age.f_age_29) as female_29,
                            sum(pop_urban_age.m_age_30) as male_30,sum(pop_urban_age.f_age_30) as female_30,
                            sum(pop_urban_age.m_age_31) as male_31,sum(pop_urban_age.f_age_31) as female_31,
                            sum(pop_urban_age.m_age_32) as male_32,sum(pop_urban_age.f_age_32) as female_32,
                            sum(pop_urban_age.m_age_33) as male_33,sum(pop_urban_age.f_age_33) as female_33,
                            sum(pop_urban_age.m_age_34) as male_34,sum(pop_urban_age.f_age_34) as female_34,
                            sum(pop_urban_age.m_age_35) as male_35,sum(pop_urban_age.f_age_35) as female_35,
                            sum(pop_urban_age.m_age_36) as male_36,sum(pop_urban_age.f_age_36) as female_36,
                            sum(pop_urban_age.m_age_37) as male_37,sum(pop_urban_age.f_age_37) as female_37,
                            sum(pop_urban_age.m_age_38) as male_38,sum(pop_urban_age.f_age_38) as female_38,
                            sum(pop_urban_age.m_age_39) as male_39,sum(pop_urban_age.f_age_39) as female_39,
                            sum(pop_urban_age.m_age_40) as male_40,sum(pop_urban_age.f_age_40) as female_40,
                            sum(pop_urban_age.m_age_41) as male_41,sum(pop_urban_age.f_age_41) as female_41,
                            sum(pop_urban_age.m_age_42) as male_42,sum(pop_urban_age.f_age_42) as female_42,
                            sum(pop_urban_age.m_age_43) as male_43,sum(pop_urban_age.f_age_43) as female_43,
                            sum(pop_urban_age.m_age_44) as male_44,sum(pop_urban_age.f_age_44) as female_44,
                            sum(pop_urban_age.m_age_45) as male_45,sum(pop_urban_age.f_age_45) as female_45,
                            sum(pop_urban_age.m_age_46) as male_46,sum(pop_urban_age.f_age_46) as female_46,
                            sum(pop_urban_age.m_age_47) as male_47,sum(pop_urban_age.f_age_47) as female_47,
                            sum(pop_urban_age.m_age_48) as male_48,sum(pop_urban_age.f_age_48) as female_48,
                            sum(pop_urban_age.m_age_49) as male_49,sum(pop_urban_age.f_age_49) as female_49,
                            sum(pop_urban_age.m_age_50) as male_50,sum(pop_urban_age.f_age_50) as female_50,
                            sum(pop_urban_age.m_age_51) as male_51,sum(pop_urban_age.f_age_51) as female_51,
                            sum(pop_urban_age.m_age_52) as male_52,sum(pop_urban_age.f_age_52) as female_52,
                            sum(pop_urban_age.m_age_53) as male_53,sum(pop_urban_age.f_age_53) as female_53,
                            sum(pop_urban_age.m_age_54) as male_54,sum(pop_urban_age.f_age_54) as female_54,
                            sum(pop_urban_age.m_age_55) as male_55,sum(pop_urban_age.f_age_55) as female_55,
                            sum(pop_urban_age.m_age_56) as male_56,sum(pop_urban_age.f_age_56) as female_56,
                            sum(pop_urban_age.m_age_57) as male_57,sum(pop_urban_age.f_age_57) as female_57,
                            sum(pop_urban_age.m_age_58) as male_58,sum(pop_urban_age.f_age_58) as female_58,
                            sum(pop_urban_age.m_age_59) as male_59,sum(pop_urban_age.f_age_59) as female_59,
                            sum(pop_urban_age.m_age_60) as male_60,sum(pop_urban_age.f_age_60) as female_60,
                            sum(pop_urban_age.m_age_61) as male_61,sum(pop_urban_age.f_age_61) as female_61,
                            sum(pop_urban_age.m_age_62) as male_62,sum(pop_urban_age.f_age_62) as female_62,
                            sum(pop_urban_age.m_age_63) as male_63,sum(pop_urban_age.f_age_63) as female_63,
                            sum(pop_urban_age.m_age_64) as male_64,sum(pop_urban_age.f_age_64) as female_64,
                            sum(pop_urban_age.m_age_65) as male_65,sum(pop_urban_age.f_age_65) as female_65,
                            sum(pop_urban_age.m_age_66) as male_66,sum(pop_urban_age.f_age_66) as female_66,
                            sum(pop_urban_age.m_age_67) as male_67,sum(pop_urban_age.f_age_67) as female_67,
                            sum(pop_urban_age.m_age_68) as male_68,sum(pop_urban_age.f_age_68) as female_68,
                            sum(pop_urban_age.m_age_69) as male_69,sum(pop_urban_age.f_age_69) as female_69,
                            sum(pop_urban_age.m_age_70) as male_70,sum(pop_urban_age.f_age_70) as female_70,
                            sum(pop_urban_age.m_age_71) as male_71,sum(pop_urban_age.f_age_71) as female_71,
                            sum(pop_urban_age.m_age_72) as male_72,sum(pop_urban_age.f_age_72) as female_72,
                            sum(pop_urban_age.m_age_73) as male_73,sum(pop_urban_age.f_age_73) as female_73,
                            sum(pop_urban_age.m_age_74) as male_74,sum(pop_urban_age.f_age_74) as female_74,
                            sum(pop_urban_age.m_age_75) as male_75,sum(pop_urban_age.f_age_75) as female_75,
                            sum(pop_urban_age.m_age_76) as male_76,sum(pop_urban_age.f_age_76) as female_76,
                            sum(pop_urban_age.m_age_77) as male_77,sum(pop_urban_age.f_age_77) as female_77,
                            sum(pop_urban_age.m_age_78) as male_78,sum(pop_urban_age.f_age_78) as female_78,
                            sum(pop_urban_age.m_age_79) as male_79,sum(pop_urban_age.f_age_79) as female_79,
                            sum(pop_urban_age.m_age_80) as male_80,sum(pop_urban_age.f_age_80) as female_80,
                            sum(pop_urban_age.m_age_81) as male_81,sum(pop_urban_age.f_age_81) as female_81,
                            sum(pop_urban_age.m_age_82) as male_82,sum(pop_urban_age.f_age_82) as female_82,
                            sum(pop_urban_age.m_age_83) as male_83,sum(pop_urban_age.f_age_83) as female_83,
                            sum(pop_urban_age.m_age_84) as male_84,sum(pop_urban_age.f_age_84) as female_84,
                            sum(pop_urban_age.m_age_85) as male_85,sum(pop_urban_age.f_age_85) as female_85,
                            sum(pop_urban_age.m_age_86) as male_86,sum(pop_urban_age.f_age_86) as female_86,
                            sum(pop_urban_age.m_age_87) as male_87,sum(pop_urban_age.f_age_87) as female_87,
                            sum(pop_urban_age.m_age_88) as male_88,sum(pop_urban_age.f_age_88) as female_88,
                            sum(pop_urban_age.m_age_89) as male_89,sum(pop_urban_age.f_age_89) as female_89,
                            sum(pop_urban_age.m_age_90) as male_90,sum(pop_urban_age.f_age_90) as female_90,
                            sum(pop_urban_age.m_age_91) as male_91,sum(pop_urban_age.f_age_91) as female_91,
                            sum(pop_urban_age.m_age_92) as male_92,sum(pop_urban_age.f_age_92) as female_92,
                            sum(pop_urban_age.m_age_93) as male_93,sum(pop_urban_age.f_age_93) as female_93,
                            sum(pop_urban_age.m_age_94) as male_94,sum(pop_urban_age.f_age_94) as female_94,
                            sum(pop_urban_age.m_age_95) as male_95,sum(pop_urban_age.f_age_95) as female_95,
                            sum(pop_urban_age.m_age_96) as male_96,sum(pop_urban_age.f_age_96) as female_96,
                            sum(pop_urban_age.m_age_97) as male_97,sum(pop_urban_age.f_age_97) as female_97,
                            sum(pop_urban_age.m_age_98) as male_98,sum(pop_urban_age.f_age_98) as female_98,
                            sum(pop_urban_age.m_age_99) as male_99,sum(pop_urban_age.f_age_99) as female_99,
                            sum(pop_urban_age.m_age_100) as male_100,sum(pop_urban_age.f_age_100) as female_100,
                            sum(pop_urban_age.m_age_101) as male_101,sum(pop_urban_age.f_age_101) as female_101
                            ,c_province.prov_name as provincename,pop_urban_age.prov_code')
                  )
          ->leftjoin('c_province','pop_urban_age.prov_code','=','c_province.prov_code')
          ->where('pop_urban_age.prov_code','=' ,$province_id)
          ->where('pop_urban_age.pop_year','=',$select_year)
          ->orderBy('pop_urban_age.prov_code','ASC')
          ->get();
      }

      if(count($query)<1){
          //dd('No Record');
          $message = "ไม่พบข้อมูล";
          flash()->overlay($message, 'ข้อความจากระบบ');
          return redirect()->route('export-population.sector');
      }else{
      $data[] = array('PROVINCE','M_AGE_0','F_AGE_0','M_AGE_1','F_AGE_1','M_AGE_2','F_AGE_2','M_AGE_3','F_AGE_3','M_AGE_4','F_AGE_4','M_AGE_5','F_AGE_5','M_AGE_6','F_AGE_6','M_AGE_7','F_AGE_7','M_AGE_8','F_AGE_8','M_AGE_9','F_AGE_9','M_AGE_10','F_AGE_10','M_AGE_11','F_AGE_11','M_AGE_12','F_AGE_12','M_AGE_13','F_AGE_13','M_AGE_14','F_AGE_14','M_AGE_15','F_AGE_15','M_AGE_16','F_AGE_16','M_AGE_17','F_AGE_17','M_AGE_18','F_AGE_18','M_AGE_19','F_AGE_19','M_AGE_20','F_AGE_20','M_AGE_21','F_AGE_21','M_AGE_22','F_AGE_22','M_AGE_23','F_AGE_23','M_AGE_24','F_AGE_24','M_AGE_25','F_AGE_25','M_AGE_26','F_AGE_26','M_AGE_27','F_AGE_27','M_AGE_28','F_AGE_28','M_AGE_29','F_AGE_29','M_AGE_30','F_AGE_30','M_AGE_31','F_AGE_31','M_AGE_32','F_AGE_32','M_AGE_33','F_AGE_33','M_AGE_34','F_AGE_34','M_AGE_35','F_AGE_35','M_AGE_36','F_AGE_36','M_AGE_37','F_AGE_37','M_AGE_38','F_AGE_38','M_AGE_39','F_AGE_39','M_AGE_40','F_AGE_40','M_AGE_41','F_AGE_41','M_AGE_42','F_AGE_42','M_AGE_43','F_AGE_43','M_AGE_44','F_AGE_44','M_AGE_45','F_AGE_45','M_AGE_46','F_AGE_46','M_AGE_47','F_AGE_47','M_AGE_48','F_AGE_48','M_AGE_49','F_AGE_49','M_AGE_50','F_AGE_50','M_AGE_51','F_AGE_51','M_AGE_52','F_AGE_52','M_AGE_53','F_AGE_53','M_AGE_54','F_AGE_54','M_AGE_55','F_AGE_55','M_AGE_56','F_AGE_56','M_AGE_57','F_AGE_57','M_AGE_58','F_AGE_58','M_AGE_59','F_AGE_59','M_AGE_60','F_AGE_60','M_AGE_61','F_AGE_61','M_AGE_62','F_AGE_62','M_AGE_63','F_AGE_63','M_AGE_64','F_AGE_64','M_AGE_65','F_AGE_65','M_AGE_66','F_AGE_66','M_AGE_67','F_AGE_67','M_AGE_68','F_AGE_68','M_AGE_69','F_AGE_69','M_AGE_70','F_AGE_70','M_AGE_71','F_AGE_71','M_AGE_72','F_AGE_72','M_AGE_73','F_AGE_73','M_AGE_74','F_AGE_74','M_AGE_75','F_AGE_75','M_AGE_76','F_AGE_76','M_AGE_77','F_AGE_77','M_AGE_78','F_AGE_78','M_AGE_79','F_AGE_79','M_AGE_80','F_AGE_80','M_AGE_81','F_AGE_81','M_AGE_82','F_AGE_82','M_AGE_83','F_AGE_83','M_AGE_84','F_AGE_84','M_AGE_85','F_AGE_85','M_AGE_86','F_AGE_86','M_AGE_87','F_AGE_87','M_AGE_88','F_AGE_88','M_AGE_89','F_AGE_89','M_AGE_90','F_AGE_90','M_AGE_91','F_AGE_91','M_AGE_92','F_AGE_92','M_AGE_93','F_AGE_93','M_AGE_94','F_AGE_94','M_AGE_95','F_AGE_95','M_AGE_96','F_AGE_96','M_AGE_97','F_AGE_97','M_AGE_98','F_AGE_98','M_AGE_99','F_AGE_99','M_AGE_100','F_AGE_100','M_AGE_101','F_AGE_101');


      foreach ($query as $value){
        $data[] = array('PROVINCE' => $value->provincename,
                        "M_AGE_0" => (int)$value->male_0,"F_AGE_0" => (int)$value->female_0,
                        "M_AGE_1" => (int)$value->male_1,"F_AGE_1" => (int)$value->female_1,
                        "M_AGE_2" => (int)$value->male_2,"F_AGE_2" => (int)$value->female_2,
                        "M_AGE_3" => (int)$value->male_3,"F_AGE_3" => (int)$value->female_3,
                        "M_AGE_4" => (int)$value->male_4,"F_AGE_4" => (int)$value->female_4,
                        "M_AGE_5" => (int)$value->male_5,"F_AGE_5" => (int)$value->female_5,
                        "M_AGE_6" => (int)$value->male_6,"F_AGE_6" => (int)$value->female_6,
                        "M_AGE_7" => (int)$value->male_7,"F_AGE_7" => (int)$value->female_7,
                        "M_AGE_8" => (int)$value->male_8,"F_AGE_8" => (int)$value->female_8,
                        "M_AGE_9" => (int)$value->male_9,"F_AGE_9" => (int)$value->female_9,
                        "M_AGE_10" => (int)$value->male_10,"F_AGE_10" => (int)$value->female_10,
                        "M_AGE_11" => (int)$value->male_11,"F_AGE_11" => (int)$value->female_11,
                        "M_AGE_12" => (int)$value->male_12,"F_AGE_12" => (int)$value->female_12,
                        "M_AGE_13" => (int)$value->male_13,"F_AGE_13" => (int)$value->female_13,
                        "M_AGE_14" => (int)$value->male_14,"F_AGE_14" => (int)$value->female_14,
                        "M_AGE_15" => (int)$value->male_15,"F_AGE_15" => (int)$value->female_15,
                        "M_AGE_16" => (int)$value->male_16,"F_AGE_16" => (int)$value->female_16,
                        "M_AGE_17" => (int)$value->male_17,"F_AGE_17" => (int)$value->female_17,
                        "M_AGE_18" => (int)$value->male_18,"F_AGE_18" => (int)$value->female_18,
                        "M_AGE_19" => (int)$value->male_19,"F_AGE_19" => (int)$value->female_19,
                        "M_AGE_20" => (int)$value->male_20,"F_AGE_20" => (int)$value->female_20,
                        "M_AGE_21" => (int)$value->male_21,"F_AGE_21" => (int)$value->female_21,
                        "M_AGE_22" => (int)$value->male_22,"F_AGE_22" => (int)$value->female_22,
                        "M_AGE_23" => (int)$value->male_23,"F_AGE_23" => (int)$value->female_23,
                        "M_AGE_24" => (int)$value->male_24,"F_AGE_24" => (int)$value->female_24,
                        "M_AGE_25" => (int)$value->male_25,"F_AGE_25" => (int)$value->female_25,
                        "M_AGE_26" => (int)$value->male_26,"F_AGE_26" => (int)$value->female_26,
                        "M_AGE_27" => (int)$value->male_27,"F_AGE_27" => (int)$value->female_27,
                        "M_AGE_28" => (int)$value->male_28,"F_AGE_28" => (int)$value->female_28,
                        "M_AGE_29" => (int)$value->male_29,"F_AGE_29" => (int)$value->female_29,
                        "M_AGE_30" => (int)$value->male_30,"F_AGE_30" => (int)$value->female_30,
                        "M_AGE_31" => (int)$value->male_31,"F_AGE_31" => (int)$value->female_31,
                        "M_AGE_32" => (int)$value->male_32,"F_AGE_32" => (int)$value->female_32,
                        "M_AGE_33" => (int)$value->male_33,"F_AGE_33" => (int)$value->female_33,
                        "M_AGE_34" => (int)$value->male_34,"F_AGE_34" => (int)$value->female_34,
                        "M_AGE_35" => (int)$value->male_35,"F_AGE_35" => (int)$value->female_35,
                        "M_AGE_36" => (int)$value->male_36,"F_AGE_36" => (int)$value->female_36,
                        "M_AGE_37" => (int)$value->male_37,"F_AGE_37" => (int)$value->female_37,
                        "M_AGE_38" => (int)$value->male_38,"F_AGE_38" => (int)$value->female_38,
                        "M_AGE_39" => (int)$value->male_39,"F_AGE_39" => (int)$value->female_39,
                        "M_AGE_40" => (int)$value->male_40,"F_AGE_40" => (int)$value->female_40,
                        "M_AGE_41" => (int)$value->male_41,"F_AGE_41" => (int)$value->female_41,
                        "M_AGE_42" => (int)$value->male_42,"F_AGE_42" => (int)$value->female_42,
                        "M_AGE_43" => (int)$value->male_43,"F_AGE_43" => (int)$value->female_43,
                        "M_AGE_44" => (int)$value->male_44,"F_AGE_44" => (int)$value->female_44,
                        "M_AGE_45" => (int)$value->male_45,"F_AGE_45" => (int)$value->female_45,
                        "M_AGE_46" => (int)$value->male_46,"F_AGE_46" => (int)$value->female_46,
                        "M_AGE_47" => (int)$value->male_47,"F_AGE_47" => (int)$value->female_47,
                        "M_AGE_48" => (int)$value->male_48,"F_AGE_48" => (int)$value->female_48,
                        "M_AGE_49" => (int)$value->male_49,"F_AGE_49" => (int)$value->female_49,
                        "M_AGE_50" => (int)$value->male_50,"F_AGE_50" => (int)$value->female_50,
                        "M_AGE_51" => (int)$value->male_51,"F_AGE_51" => (int)$value->female_51,
                        "M_AGE_52" => (int)$value->male_52,"F_AGE_52" => (int)$value->female_52,
                        "M_AGE_53" => (int)$value->male_53,"F_AGE_53" => (int)$value->female_53,
                        "M_AGE_54" => (int)$value->male_54,"F_AGE_54" => (int)$value->female_54,
                        "M_AGE_55" => (int)$value->male_55,"F_AGE_55" => (int)$value->female_55,
                        "M_AGE_56" => (int)$value->male_56,"F_AGE_56" => (int)$value->female_56,
                        "M_AGE_57" => (int)$value->male_57,"F_AGE_57" => (int)$value->female_57,
                        "M_AGE_58" => (int)$value->male_58,"F_AGE_58" => (int)$value->female_58,
                        "M_AGE_59" => (int)$value->male_59,"F_AGE_59" => (int)$value->female_59,
                        "M_AGE_60" => (int)$value->male_60,"F_AGE_60" => (int)$value->female_60,
                        "M_AGE_61" => (int)$value->male_61,"F_AGE_61" => (int)$value->female_61,
                        "M_AGE_62" => (int)$value->male_62,"F_AGE_62" => (int)$value->female_62,
                        "M_AGE_63" => (int)$value->male_63,"F_AGE_63" => (int)$value->female_63,
                        "M_AGE_64" => (int)$value->male_64,"F_AGE_64" => (int)$value->female_64,
                        "M_AGE_65" => (int)$value->male_65,"F_AGE_65" => (int)$value->female_65,
                        "M_AGE_66" => (int)$value->male_66,"F_AGE_66" => (int)$value->female_66,
                        "M_AGE_67" => (int)$value->male_67,"F_AGE_67" => (int)$value->female_67,
                        "M_AGE_68" => (int)$value->male_68,"F_AGE_68" => (int)$value->female_68,
                        "M_AGE_69" => (int)$value->male_69,"F_AGE_69" => (int)$value->female_69,
                        "M_AGE_70" => (int)$value->male_70,"F_AGE_70" => (int)$value->female_70,
                        "M_AGE_71" => (int)$value->male_71,"F_AGE_71" => (int)$value->female_71,
                        "M_AGE_72" => (int)$value->male_72,"F_AGE_72" => (int)$value->female_72,
                        "M_AGE_73" => (int)$value->male_73,"F_AGE_73" => (int)$value->female_73,
                        "M_AGE_74" => (int)$value->male_74,"F_AGE_74" => (int)$value->female_74,
                        "M_AGE_75" => (int)$value->male_75,"F_AGE_75" => (int)$value->female_75,
                        "M_AGE_76" => (int)$value->male_76,"F_AGE_76" => (int)$value->female_76,
                        "M_AGE_77" => (int)$value->male_77,"F_AGE_77" => (int)$value->female_77,
                        "M_AGE_78" => (int)$value->male_78,"F_AGE_78" => (int)$value->female_78,
                        "M_AGE_79" => (int)$value->male_79,"F_AGE_79" => (int)$value->female_79,
                        "M_AGE_80" => (int)$value->male_80,"F_AGE_80" => (int)$value->female_80,
                        "M_AGE_81" => (int)$value->male_81,"F_AGE_81" => (int)$value->female_81,
                        "M_AGE_82" => (int)$value->male_82,"F_AGE_82" => (int)$value->female_82,
                        "M_AGE_83" => (int)$value->male_83,"F_AGE_83" => (int)$value->female_83,
                        "M_AGE_84" => (int)$value->male_84,"F_AGE_84" => (int)$value->female_84,
                        "M_AGE_85" => (int)$value->male_85,"F_AGE_85" => (int)$value->female_85,
                        "M_AGE_86" => (int)$value->male_86,"F_AGE_86" => (int)$value->female_86,
                        "M_AGE_87" => (int)$value->male_87,"F_AGE_87" => (int)$value->female_87,
                        "M_AGE_88" => (int)$value->male_88,"F_AGE_88" => (int)$value->female_88,
                        "M_AGE_89" => (int)$value->male_89,"F_AGE_89" => (int)$value->female_89,
                        "M_AGE_90" => (int)$value->male_90,"F_AGE_90" => (int)$value->female_90,
                        "M_AGE_91" => (int)$value->male_91,"F_AGE_91" => (int)$value->female_91,
                        "M_AGE_92" => (int)$value->male_92,"F_AGE_92" => (int)$value->female_92,
                        "M_AGE_93" => (int)$value->male_93,"F_AGE_93" => (int)$value->female_93,
                        "M_AGE_94" => (int)$value->male_94,"F_AGE_94" => (int)$value->female_94,
                        "M_AGE_95" => (int)$value->male_95,"F_AGE_95" => (int)$value->female_95,
                        "M_AGE_96" => (int)$value->male_96,"F_AGE_96" => (int)$value->female_96,
                        "M_AGE_97" => (int)$value->male_97,"F_AGE_97" => (int)$value->female_97,
                        "M_AGE_98" => (int)$value->male_98,"F_AGE_98" => (int)$value->female_98,
                        "M_AGE_99" => (int)$value->male_99,"F_AGE_99" => (int)$value->female_99,
                        "M_AGE_100" => (int)$value->male_100,"F_AGE_100" => (int)$value->female_100,
                        "M_AGE_101" => (int)$value->male_101,"F_AGE_101" => (int)$value->female_101);
      }

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
    public function post_population_sex_age_municipality(Request $request){
          $select_year = $request->select_year;
          $query = DB::table('pop_urban_age')
            ->select(DB::raw('sum(pop_urban_age.m_age_0) as male_0,sum(pop_urban_age.f_age_0) as female_0,
                              sum(pop_urban_age.m_age_1) as male_1,sum(pop_urban_age.f_age_1) as female_1,
                              sum(pop_urban_age.m_age_2) as male_2,sum(pop_urban_age.f_age_2) as female_2,
                              sum(pop_urban_age.m_age_3) as male_3,sum(pop_urban_age.f_age_3) as female_3,
                              sum(pop_urban_age.m_age_4) as male_4,sum(pop_urban_age.f_age_4) as female_4,
                              sum(pop_urban_age.m_age_5) as male_5,sum(pop_urban_age.f_age_5) as female_5,
                              sum(pop_urban_age.m_age_6) as male_6,sum(pop_urban_age.f_age_6) as female_6,
                              sum(pop_urban_age.m_age_7) as male_7,sum(pop_urban_age.f_age_7) as female_7,
                              sum(pop_urban_age.m_age_8) as male_8,sum(pop_urban_age.f_age_8) as female_8,
                              sum(pop_urban_age.m_age_9) as male_9,sum(pop_urban_age.f_age_9) as female_9,
                              sum(pop_urban_age.m_age_10) as male_10,sum(pop_urban_age.f_age_10) as female_10,
                              sum(pop_urban_age.m_age_11) as male_11,sum(pop_urban_age.f_age_11) as female_11,
                              sum(pop_urban_age.m_age_12) as male_12,sum(pop_urban_age.f_age_12) as female_12,
                              sum(pop_urban_age.m_age_13) as male_13,sum(pop_urban_age.f_age_13) as female_13,
                              sum(pop_urban_age.m_age_14) as male_14,sum(pop_urban_age.f_age_14) as female_14,
                              sum(pop_urban_age.m_age_15) as male_15,sum(pop_urban_age.f_age_15) as female_15,
                              sum(pop_urban_age.m_age_16) as male_16,sum(pop_urban_age.f_age_16) as female_16,
                              sum(pop_urban_age.m_age_17) as male_17,sum(pop_urban_age.f_age_17) as female_17,
                              sum(pop_urban_age.m_age_18) as male_18,sum(pop_urban_age.f_age_18) as female_18,
                              sum(pop_urban_age.m_age_19) as male_19,sum(pop_urban_age.f_age_19) as female_19,
                              sum(pop_urban_age.m_age_20) as male_20,sum(pop_urban_age.f_age_20) as female_20,
                              sum(pop_urban_age.m_age_21) as male_21,sum(pop_urban_age.f_age_21) as female_21,
                              sum(pop_urban_age.m_age_22) as male_22,sum(pop_urban_age.f_age_22) as female_22,
                              sum(pop_urban_age.m_age_23) as male_23,sum(pop_urban_age.f_age_23) as female_23,
                              sum(pop_urban_age.m_age_24) as male_24,sum(pop_urban_age.f_age_24) as female_24,
                              sum(pop_urban_age.m_age_25) as male_25,sum(pop_urban_age.f_age_25) as female_25,
                              sum(pop_urban_age.m_age_26) as male_26,sum(pop_urban_age.f_age_26) as female_26,
                              sum(pop_urban_age.m_age_27) as male_27,sum(pop_urban_age.f_age_27) as female_27,
                              sum(pop_urban_age.m_age_28) as male_28,sum(pop_urban_age.f_age_28) as female_28,
                              sum(pop_urban_age.m_age_29) as male_29,sum(pop_urban_age.f_age_29) as female_29,
                              sum(pop_urban_age.m_age_30) as male_30,sum(pop_urban_age.f_age_30) as female_30,
                              sum(pop_urban_age.m_age_31) as male_31,sum(pop_urban_age.f_age_31) as female_31,
                              sum(pop_urban_age.m_age_32) as male_32,sum(pop_urban_age.f_age_32) as female_32,
                              sum(pop_urban_age.m_age_33) as male_33,sum(pop_urban_age.f_age_33) as female_33,
                              sum(pop_urban_age.m_age_34) as male_34,sum(pop_urban_age.f_age_34) as female_34,
                              sum(pop_urban_age.m_age_35) as male_35,sum(pop_urban_age.f_age_35) as female_35,
                              sum(pop_urban_age.m_age_36) as male_36,sum(pop_urban_age.f_age_36) as female_36,
                              sum(pop_urban_age.m_age_37) as male_37,sum(pop_urban_age.f_age_37) as female_37,
                              sum(pop_urban_age.m_age_38) as male_38,sum(pop_urban_age.f_age_38) as female_38,
                              sum(pop_urban_age.m_age_39) as male_39,sum(pop_urban_age.f_age_39) as female_39,
                              sum(pop_urban_age.m_age_40) as male_40,sum(pop_urban_age.f_age_40) as female_40,
                              sum(pop_urban_age.m_age_41) as male_41,sum(pop_urban_age.f_age_41) as female_41,
                              sum(pop_urban_age.m_age_42) as male_42,sum(pop_urban_age.f_age_42) as female_42,
                              sum(pop_urban_age.m_age_43) as male_43,sum(pop_urban_age.f_age_43) as female_43,
                              sum(pop_urban_age.m_age_44) as male_44,sum(pop_urban_age.f_age_44) as female_44,
                              sum(pop_urban_age.m_age_45) as male_45,sum(pop_urban_age.f_age_45) as female_45,
                              sum(pop_urban_age.m_age_46) as male_46,sum(pop_urban_age.f_age_46) as female_46,
                              sum(pop_urban_age.m_age_47) as male_47,sum(pop_urban_age.f_age_47) as female_47,
                              sum(pop_urban_age.m_age_48) as male_48,sum(pop_urban_age.f_age_48) as female_48,
                              sum(pop_urban_age.m_age_49) as male_49,sum(pop_urban_age.f_age_49) as female_49,
                              sum(pop_urban_age.m_age_50) as male_50,sum(pop_urban_age.f_age_50) as female_50,
                              sum(pop_urban_age.m_age_51) as male_51,sum(pop_urban_age.f_age_51) as female_51,
                              sum(pop_urban_age.m_age_52) as male_52,sum(pop_urban_age.f_age_52) as female_52,
                              sum(pop_urban_age.m_age_53) as male_53,sum(pop_urban_age.f_age_53) as female_53,
                              sum(pop_urban_age.m_age_54) as male_54,sum(pop_urban_age.f_age_54) as female_54,
                              sum(pop_urban_age.m_age_55) as male_55,sum(pop_urban_age.f_age_55) as female_55,
                              sum(pop_urban_age.m_age_56) as male_56,sum(pop_urban_age.f_age_56) as female_56,
                              sum(pop_urban_age.m_age_57) as male_57,sum(pop_urban_age.f_age_57) as female_57,
                              sum(pop_urban_age.m_age_58) as male_58,sum(pop_urban_age.f_age_58) as female_58,
                              sum(pop_urban_age.m_age_59) as male_59,sum(pop_urban_age.f_age_59) as female_59,
                              sum(pop_urban_age.m_age_60) as male_60,sum(pop_urban_age.f_age_60) as female_60,
                              sum(pop_urban_age.m_age_61) as male_61,sum(pop_urban_age.f_age_61) as female_61,
                              sum(pop_urban_age.m_age_62) as male_62,sum(pop_urban_age.f_age_62) as female_62,
                              sum(pop_urban_age.m_age_63) as male_63,sum(pop_urban_age.f_age_63) as female_63,
                              sum(pop_urban_age.m_age_64) as male_64,sum(pop_urban_age.f_age_64) as female_64,
                              sum(pop_urban_age.m_age_65) as male_65,sum(pop_urban_age.f_age_65) as female_65,
                              sum(pop_urban_age.m_age_66) as male_66,sum(pop_urban_age.f_age_66) as female_66,
                              sum(pop_urban_age.m_age_67) as male_67,sum(pop_urban_age.f_age_67) as female_67,
                              sum(pop_urban_age.m_age_68) as male_68,sum(pop_urban_age.f_age_68) as female_68,
                              sum(pop_urban_age.m_age_69) as male_69,sum(pop_urban_age.f_age_69) as female_69,
                              sum(pop_urban_age.m_age_70) as male_70,sum(pop_urban_age.f_age_70) as female_70,
                              sum(pop_urban_age.m_age_71) as male_71,sum(pop_urban_age.f_age_71) as female_71,
                              sum(pop_urban_age.m_age_72) as male_72,sum(pop_urban_age.f_age_72) as female_72,
                              sum(pop_urban_age.m_age_73) as male_73,sum(pop_urban_age.f_age_73) as female_73,
                              sum(pop_urban_age.m_age_74) as male_74,sum(pop_urban_age.f_age_74) as female_74,
                              sum(pop_urban_age.m_age_75) as male_75,sum(pop_urban_age.f_age_75) as female_75,
                              sum(pop_urban_age.m_age_76) as male_76,sum(pop_urban_age.f_age_76) as female_76,
                              sum(pop_urban_age.m_age_77) as male_77,sum(pop_urban_age.f_age_77) as female_77,
                              sum(pop_urban_age.m_age_78) as male_78,sum(pop_urban_age.f_age_78) as female_78,
                              sum(pop_urban_age.m_age_79) as male_79,sum(pop_urban_age.f_age_79) as female_79,
                              sum(pop_urban_age.m_age_80) as male_80,sum(pop_urban_age.f_age_80) as female_80,
                              sum(pop_urban_age.m_age_81) as male_81,sum(pop_urban_age.f_age_81) as female_81,
                              sum(pop_urban_age.m_age_82) as male_82,sum(pop_urban_age.f_age_82) as female_82,
                              sum(pop_urban_age.m_age_83) as male_83,sum(pop_urban_age.f_age_83) as female_83,
                              sum(pop_urban_age.m_age_84) as male_84,sum(pop_urban_age.f_age_84) as female_84,
                              sum(pop_urban_age.m_age_85) as male_85,sum(pop_urban_age.f_age_85) as female_85,
                              sum(pop_urban_age.m_age_86) as male_86,sum(pop_urban_age.f_age_86) as female_86,
                              sum(pop_urban_age.m_age_87) as male_87,sum(pop_urban_age.f_age_87) as female_87,
                              sum(pop_urban_age.m_age_88) as male_88,sum(pop_urban_age.f_age_88) as female_88,
                              sum(pop_urban_age.m_age_89) as male_89,sum(pop_urban_age.f_age_89) as female_89,
                              sum(pop_urban_age.m_age_90) as male_90,sum(pop_urban_age.f_age_90) as female_90,
                              sum(pop_urban_age.m_age_91) as male_91,sum(pop_urban_age.f_age_91) as female_91,
                              sum(pop_urban_age.m_age_92) as male_92,sum(pop_urban_age.f_age_92) as female_92,
                              sum(pop_urban_age.m_age_93) as male_93,sum(pop_urban_age.f_age_93) as female_93,
                              sum(pop_urban_age.m_age_94) as male_94,sum(pop_urban_age.f_age_94) as female_94,
                              sum(pop_urban_age.m_age_95) as male_95,sum(pop_urban_age.f_age_95) as female_95,
                              sum(pop_urban_age.m_age_96) as male_96,sum(pop_urban_age.f_age_96) as female_96,
                              sum(pop_urban_age.m_age_97) as male_97,sum(pop_urban_age.f_age_97) as female_97,
                              sum(pop_urban_age.m_age_98) as male_98,sum(pop_urban_age.f_age_98) as female_98,
                              sum(pop_urban_age.m_age_99) as male_99,sum(pop_urban_age.f_age_99) as female_99,
                              sum(pop_urban_age.m_age_100) as male_100,sum(pop_urban_age.f_age_100) as female_100,
                              sum(pop_urban_age.m_age_101) as male_101,sum(pop_urban_age.f_age_101) as female_101
                              ,c_province.prov_name as provincename,pop_urban_age.prov_code,pop_urban_age.name_addr')
                    )
            ->leftjoin('c_province','pop_urban_age.prov_code','=','c_province.prov_code')
            //->where('pop_urban_age.prov_code','=' ,$province_id)
            ->where('pop_urban_age.pop_year','=',$select_year)
            ->groupBy(DB::raw('pop_urban_age.name_addr'))
            ->orderBy('pop_urban_age.name_addr','ASC')
            ->get();
          if(count($query)<1){
              //dd('No Record');
              $message = "ไม่พบข้อมูล";
              flash()->overlay($message, 'ข้อความจากระบบ');
              return redirect()->route('export-population.sector');
          }else{
          $data[] = array('PROVINCE','NAME_ADDR','M_AGE_0','F_AGE_0','M_AGE_1','F_AGE_1','M_AGE_2','F_AGE_2','M_AGE_3','F_AGE_3','M_AGE_4','F_AGE_4','M_AGE_5','F_AGE_5','M_AGE_6','F_AGE_6','M_AGE_7','F_AGE_7','M_AGE_8','F_AGE_8','M_AGE_9','F_AGE_9','M_AGE_10','F_AGE_10','M_AGE_11','F_AGE_11','M_AGE_12','F_AGE_12','M_AGE_13','F_AGE_13','M_AGE_14','F_AGE_14','M_AGE_15','F_AGE_15','M_AGE_16','F_AGE_16','M_AGE_17','F_AGE_17','M_AGE_18','F_AGE_18','M_AGE_19','F_AGE_19','M_AGE_20','F_AGE_20','M_AGE_21','F_AGE_21','M_AGE_22','F_AGE_22','M_AGE_23','F_AGE_23','M_AGE_24','F_AGE_24','M_AGE_25','F_AGE_25','M_AGE_26','F_AGE_26','M_AGE_27','F_AGE_27','M_AGE_28','F_AGE_28','M_AGE_29','F_AGE_29','M_AGE_30','F_AGE_30','M_AGE_31','F_AGE_31','M_AGE_32','F_AGE_32','M_AGE_33','F_AGE_33','M_AGE_34','F_AGE_34','M_AGE_35','F_AGE_35','M_AGE_36','F_AGE_36','M_AGE_37','F_AGE_37','M_AGE_38','F_AGE_38','M_AGE_39','F_AGE_39','M_AGE_40','F_AGE_40','M_AGE_41','F_AGE_41','M_AGE_42','F_AGE_42','M_AGE_43','F_AGE_43','M_AGE_44','F_AGE_44','M_AGE_45','F_AGE_45','M_AGE_46','F_AGE_46','M_AGE_47','F_AGE_47','M_AGE_48','F_AGE_48','M_AGE_49','F_AGE_49','M_AGE_50','F_AGE_50','M_AGE_51','F_AGE_51','M_AGE_52','F_AGE_52','M_AGE_53','F_AGE_53','M_AGE_54','F_AGE_54','M_AGE_55','F_AGE_55','M_AGE_56','F_AGE_56','M_AGE_57','F_AGE_57','M_AGE_58','F_AGE_58','M_AGE_59','F_AGE_59','M_AGE_60','F_AGE_60','M_AGE_61','F_AGE_61','M_AGE_62','F_AGE_62','M_AGE_63','F_AGE_63','M_AGE_64','F_AGE_64','M_AGE_65','F_AGE_65','M_AGE_66','F_AGE_66','M_AGE_67','F_AGE_67','M_AGE_68','F_AGE_68','M_AGE_69','F_AGE_69','M_AGE_70','F_AGE_70','M_AGE_71','F_AGE_71','M_AGE_72','F_AGE_72','M_AGE_73','F_AGE_73','M_AGE_74','F_AGE_74','M_AGE_75','F_AGE_75','M_AGE_76','F_AGE_76','M_AGE_77','F_AGE_77','M_AGE_78','F_AGE_78','M_AGE_79','F_AGE_79','M_AGE_80','F_AGE_80','M_AGE_81','F_AGE_81','M_AGE_82','F_AGE_82','M_AGE_83','F_AGE_83','M_AGE_84','F_AGE_84','M_AGE_85','F_AGE_85','M_AGE_86','F_AGE_86','M_AGE_87','F_AGE_87','M_AGE_88','F_AGE_88','M_AGE_89','F_AGE_89','M_AGE_90','F_AGE_90','M_AGE_91','F_AGE_91','M_AGE_92','F_AGE_92','M_AGE_93','F_AGE_93','M_AGE_94','F_AGE_94','M_AGE_95','F_AGE_95','M_AGE_96','F_AGE_96','M_AGE_97','F_AGE_97','M_AGE_98','F_AGE_98','M_AGE_99','F_AGE_99','M_AGE_100','F_AGE_100','M_AGE_101','F_AGE_101');


          foreach ($query as $value){
            $data[] = array('PROVINCE' => $value->provincename,'NAME_ADDR' => $value->name_addr,
                            "M_AGE_0" => (int)$value->male_0,"F_AGE_0" => (int)$value->female_0,
                            "M_AGE_1" => (int)$value->male_1,"F_AGE_1" => (int)$value->female_1,
                            "M_AGE_2" => (int)$value->male_2,"F_AGE_2" => (int)$value->female_2,
                            "M_AGE_3" => (int)$value->male_3,"F_AGE_3" => (int)$value->female_3,
                            "M_AGE_4" => (int)$value->male_4,"F_AGE_4" => (int)$value->female_4,
                            "M_AGE_5" => (int)$value->male_5,"F_AGE_5" => (int)$value->female_5,
                            "M_AGE_6" => (int)$value->male_6,"F_AGE_6" => (int)$value->female_6,
                            "M_AGE_7" => (int)$value->male_7,"F_AGE_7" => (int)$value->female_7,
                            "M_AGE_8" => (int)$value->male_8,"F_AGE_8" => (int)$value->female_8,
                            "M_AGE_9" => (int)$value->male_9,"F_AGE_9" => (int)$value->female_9,
                            "M_AGE_10" => (int)$value->male_10,"F_AGE_10" => (int)$value->female_10,
                            "M_AGE_11" => (int)$value->male_11,"F_AGE_11" => (int)$value->female_11,
                            "M_AGE_12" => (int)$value->male_12,"F_AGE_12" => (int)$value->female_12,
                            "M_AGE_13" => (int)$value->male_13,"F_AGE_13" => (int)$value->female_13,
                            "M_AGE_14" => (int)$value->male_14,"F_AGE_14" => (int)$value->female_14,
                            "M_AGE_15" => (int)$value->male_15,"F_AGE_15" => (int)$value->female_15,
                            "M_AGE_16" => (int)$value->male_16,"F_AGE_16" => (int)$value->female_16,
                            "M_AGE_17" => (int)$value->male_17,"F_AGE_17" => (int)$value->female_17,
                            "M_AGE_18" => (int)$value->male_18,"F_AGE_18" => (int)$value->female_18,
                            "M_AGE_19" => (int)$value->male_19,"F_AGE_19" => (int)$value->female_19,
                            "M_AGE_20" => (int)$value->male_20,"F_AGE_20" => (int)$value->female_20,
                            "M_AGE_21" => (int)$value->male_21,"F_AGE_21" => (int)$value->female_21,
                            "M_AGE_22" => (int)$value->male_22,"F_AGE_22" => (int)$value->female_22,
                            "M_AGE_23" => (int)$value->male_23,"F_AGE_23" => (int)$value->female_23,
                            "M_AGE_24" => (int)$value->male_24,"F_AGE_24" => (int)$value->female_24,
                            "M_AGE_25" => (int)$value->male_25,"F_AGE_25" => (int)$value->female_25,
                            "M_AGE_26" => (int)$value->male_26,"F_AGE_26" => (int)$value->female_26,
                            "M_AGE_27" => (int)$value->male_27,"F_AGE_27" => (int)$value->female_27,
                            "M_AGE_28" => (int)$value->male_28,"F_AGE_28" => (int)$value->female_28,
                            "M_AGE_29" => (int)$value->male_29,"F_AGE_29" => (int)$value->female_29,
                            "M_AGE_30" => (int)$value->male_30,"F_AGE_30" => (int)$value->female_30,
                            "M_AGE_31" => (int)$value->male_31,"F_AGE_31" => (int)$value->female_31,
                            "M_AGE_32" => (int)$value->male_32,"F_AGE_32" => (int)$value->female_32,
                            "M_AGE_33" => (int)$value->male_33,"F_AGE_33" => (int)$value->female_33,
                            "M_AGE_34" => (int)$value->male_34,"F_AGE_34" => (int)$value->female_34,
                            "M_AGE_35" => (int)$value->male_35,"F_AGE_35" => (int)$value->female_35,
                            "M_AGE_36" => (int)$value->male_36,"F_AGE_36" => (int)$value->female_36,
                            "M_AGE_37" => (int)$value->male_37,"F_AGE_37" => (int)$value->female_37,
                            "M_AGE_38" => (int)$value->male_38,"F_AGE_38" => (int)$value->female_38,
                            "M_AGE_39" => (int)$value->male_39,"F_AGE_39" => (int)$value->female_39,
                            "M_AGE_40" => (int)$value->male_40,"F_AGE_40" => (int)$value->female_40,
                            "M_AGE_41" => (int)$value->male_41,"F_AGE_41" => (int)$value->female_41,
                            "M_AGE_42" => (int)$value->male_42,"F_AGE_42" => (int)$value->female_42,
                            "M_AGE_43" => (int)$value->male_43,"F_AGE_43" => (int)$value->female_43,
                            "M_AGE_44" => (int)$value->male_44,"F_AGE_44" => (int)$value->female_44,
                            "M_AGE_45" => (int)$value->male_45,"F_AGE_45" => (int)$value->female_45,
                            "M_AGE_46" => (int)$value->male_46,"F_AGE_46" => (int)$value->female_46,
                            "M_AGE_47" => (int)$value->male_47,"F_AGE_47" => (int)$value->female_47,
                            "M_AGE_48" => (int)$value->male_48,"F_AGE_48" => (int)$value->female_48,
                            "M_AGE_49" => (int)$value->male_49,"F_AGE_49" => (int)$value->female_49,
                            "M_AGE_50" => (int)$value->male_50,"F_AGE_50" => (int)$value->female_50,
                            "M_AGE_51" => (int)$value->male_51,"F_AGE_51" => (int)$value->female_51,
                            "M_AGE_52" => (int)$value->male_52,"F_AGE_52" => (int)$value->female_52,
                            "M_AGE_53" => (int)$value->male_53,"F_AGE_53" => (int)$value->female_53,
                            "M_AGE_54" => (int)$value->male_54,"F_AGE_54" => (int)$value->female_54,
                            "M_AGE_55" => (int)$value->male_55,"F_AGE_55" => (int)$value->female_55,
                            "M_AGE_56" => (int)$value->male_56,"F_AGE_56" => (int)$value->female_56,
                            "M_AGE_57" => (int)$value->male_57,"F_AGE_57" => (int)$value->female_57,
                            "M_AGE_58" => (int)$value->male_58,"F_AGE_58" => (int)$value->female_58,
                            "M_AGE_59" => (int)$value->male_59,"F_AGE_59" => (int)$value->female_59,
                            "M_AGE_60" => (int)$value->male_60,"F_AGE_60" => (int)$value->female_60,
                            "M_AGE_61" => (int)$value->male_61,"F_AGE_61" => (int)$value->female_61,
                            "M_AGE_62" => (int)$value->male_62,"F_AGE_62" => (int)$value->female_62,
                            "M_AGE_63" => (int)$value->male_63,"F_AGE_63" => (int)$value->female_63,
                            "M_AGE_64" => (int)$value->male_64,"F_AGE_64" => (int)$value->female_64,
                            "M_AGE_65" => (int)$value->male_65,"F_AGE_65" => (int)$value->female_65,
                            "M_AGE_66" => (int)$value->male_66,"F_AGE_66" => (int)$value->female_66,
                            "M_AGE_67" => (int)$value->male_67,"F_AGE_67" => (int)$value->female_67,
                            "M_AGE_68" => (int)$value->male_68,"F_AGE_68" => (int)$value->female_68,
                            "M_AGE_69" => (int)$value->male_69,"F_AGE_69" => (int)$value->female_69,
                            "M_AGE_70" => (int)$value->male_70,"F_AGE_70" => (int)$value->female_70,
                            "M_AGE_71" => (int)$value->male_71,"F_AGE_71" => (int)$value->female_71,
                            "M_AGE_72" => (int)$value->male_72,"F_AGE_72" => (int)$value->female_72,
                            "M_AGE_73" => (int)$value->male_73,"F_AGE_73" => (int)$value->female_73,
                            "M_AGE_74" => (int)$value->male_74,"F_AGE_74" => (int)$value->female_74,
                            "M_AGE_75" => (int)$value->male_75,"F_AGE_75" => (int)$value->female_75,
                            "M_AGE_76" => (int)$value->male_76,"F_AGE_76" => (int)$value->female_76,
                            "M_AGE_77" => (int)$value->male_77,"F_AGE_77" => (int)$value->female_77,
                            "M_AGE_78" => (int)$value->male_78,"F_AGE_78" => (int)$value->female_78,
                            "M_AGE_79" => (int)$value->male_79,"F_AGE_79" => (int)$value->female_79,
                            "M_AGE_80" => (int)$value->male_80,"F_AGE_80" => (int)$value->female_80,
                            "M_AGE_81" => (int)$value->male_81,"F_AGE_81" => (int)$value->female_81,
                            "M_AGE_82" => (int)$value->male_82,"F_AGE_82" => (int)$value->female_82,
                            "M_AGE_83" => (int)$value->male_83,"F_AGE_83" => (int)$value->female_83,
                            "M_AGE_84" => (int)$value->male_84,"F_AGE_84" => (int)$value->female_84,
                            "M_AGE_85" => (int)$value->male_85,"F_AGE_85" => (int)$value->female_85,
                            "M_AGE_86" => (int)$value->male_86,"F_AGE_86" => (int)$value->female_86,
                            "M_AGE_87" => (int)$value->male_87,"F_AGE_87" => (int)$value->female_87,
                            "M_AGE_88" => (int)$value->male_88,"F_AGE_88" => (int)$value->female_88,
                            "M_AGE_89" => (int)$value->male_89,"F_AGE_89" => (int)$value->female_89,
                            "M_AGE_90" => (int)$value->male_90,"F_AGE_90" => (int)$value->female_90,
                            "M_AGE_91" => (int)$value->male_91,"F_AGE_91" => (int)$value->female_91,
                            "M_AGE_92" => (int)$value->male_92,"F_AGE_92" => (int)$value->female_92,
                            "M_AGE_93" => (int)$value->male_93,"F_AGE_93" => (int)$value->female_93,
                            "M_AGE_94" => (int)$value->male_94,"F_AGE_94" => (int)$value->female_94,
                            "M_AGE_95" => (int)$value->male_95,"F_AGE_95" => (int)$value->female_95,
                            "M_AGE_96" => (int)$value->male_96,"F_AGE_96" => (int)$value->female_96,
                            "M_AGE_97" => (int)$value->male_97,"F_AGE_97" => (int)$value->female_97,
                            "M_AGE_98" => (int)$value->male_98,"F_AGE_98" => (int)$value->female_98,
                            "M_AGE_99" => (int)$value->male_99,"F_AGE_99" => (int)$value->female_99,
                            "M_AGE_100" => (int)$value->male_100,"F_AGE_100" => (int)$value->female_100,
                            "M_AGE_101" => (int)$value->male_101,"F_AGE_101" => (int)$value->female_101);
          }
          //filename
          $year = $select_year+543;
          $filename = 'ป.จำแนกตามอายุและเพศ-ปี'.$year;
          //sheetname
          $data2 = 'ป.จำแนกตามอายุและเพศ-ปี'.$year;

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
