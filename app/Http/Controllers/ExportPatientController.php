<?php

namespace App\Http\Controllers;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportPatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function patient_main()
    {
        return view('frontend.patient-main');
    }
    public function patient_sick_death_by_month()
    {
        return view('frontend.patient-sick-death-month');
    }
    public function patient_sick_death_ratio()
    {
        return view('frontend.patient-sick-death-ratio');
    }
    public function patient_sick_weekly()
    {
        return view('frontend.patient-sick-weekly');
    }
    public function patient_sick_by_age()
    {
       return view('frontend.patient_sick_by_age');
    }
    public function patient_death_by_age()
    {
       return view('frontend.patient_death_by_age');
    }
    public function patient_sick_death_by_nation()
    {
      return view('frontend.patient_sick_death_by_nation');
    }
    public function patient_sick_by_occupation()
    {
      return view('frontend.patient_sick_by_occupation');
    }
    public function patient_sick_by_sex()
    {
      return view('frontend.patient_sick_by_sex');
    }

    public static function get_patient_sick_death_by_month($select_year,$disease_code)
    {
      $tblYear = (isset($select_year))? $select_year : date('Y')-1;
      $post_disease_code = (isset($disease_code))? $disease_code : "01";

      //$get_pop_dpc_group =\App\Http\Controllers\Controller::get_pop_dpc_group();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
      $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();

      //Check Disease
      $disease_code =  explode(",",$post_disease_code);
      //dd($disease_code);

      if(count($disease_code)>2){
        //Total>1 DISEASE select
        $data[] = DB::table('ur506_'.$tblYear)
          ->select('prov_dpc','DISEASE', 'PROVINCE')
          ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan,sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')
          ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATESICK) = 2,1,0)) as death_feb')
          ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATESICK) = 3,1,0)) as death_mar')
          ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATESICK) = 4,1,0)) as death_apr')
          ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATESICK) = 5,1,0)) as death_may')
          ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATESICK) = 6,1,0)) as death_jun')
          ->selectRaw('sum(if(MONTH(DATESICK) = 7,1,0)) as case_jul,sum(if(MONTH(DATEDEATH) = 7,1,0)) as death_jul')
          ->selectRaw('sum(if(MONTH(DATESICK) = 8,1,0)) as case_aug,sum(if(MONTH(DATEDEATH) = 8,1,0)) as death_aug')
          ->selectRaw('sum(if(MONTH(DATESICK) = 9,1,0)) as case_sep,sum(if(MONTH(DATEDEATH) = 9,1,0)) as death_sep')
          ->selectRaw('sum(if(MONTH(DATESICK) = 10,1,0)) as case_oct,sum(if(MONTH(DATEDEATH) =10,1,0)) as death_oct')
          ->selectRaw('sum(if(MONTH(DATESICK) = 11,1,0)) as case_nov,sum(if(MONTH(DATEDEATH) = 11,1,0)) as death_nov')
          ->selectRaw('sum(if(MONTH(DATESICK) = 12,1,0)) as case_dec,sum(if(MONTH(DATEDEATH) = 12,1,0)) as death_dec')
          ->whereIn('DISEASE',$disease_code)
          ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
          ->groupBy('PROVINCE')
          ->get();

      }else{
        // 1 DISEASE SELECT
        $data[] = DB::table('ur506_'.$tblYear)
          ->select('prov_dpc','DISEASE', 'PROVINCE')
          ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan,sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')
          ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATESICK) = 2,1,0)) as death_feb')
          ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATESICK) = 3,1,0)) as death_mar')
          ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATESICK) = 4,1,0)) as death_apr')
          ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATESICK) = 5,1,0)) as death_may')
          ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATESICK) = 6,1,0)) as death_jun')
          ->selectRaw('sum(if(MONTH(DATESICK) = 7,1,0)) as case_jul,sum(if(MONTH(DATEDEATH) = 7,1,0)) as death_jul')
          ->selectRaw('sum(if(MONTH(DATESICK) = 8,1,0)) as case_aug,sum(if(MONTH(DATEDEATH) = 8,1,0)) as death_aug')
          ->selectRaw('sum(if(MONTH(DATESICK) = 9,1,0)) as case_sep,sum(if(MONTH(DATEDEATH) = 9,1,0)) as death_sep')
          ->selectRaw('sum(if(MONTH(DATESICK) = 10,1,0)) as case_oct,sum(if(MONTH(DATEDEATH) =10,1,0)) as death_oct')
          ->selectRaw('sum(if(MONTH(DATESICK) = 11,1,0)) as case_nov,sum(if(MONTH(DATEDEATH) = 11,1,0)) as death_nov')
          ->selectRaw('sum(if(MONTH(DATESICK) = 12,1,0)) as case_dec,sum(if(MONTH(DATEDEATH) = 12,1,0)) as death_dec')
          ->where('DISEASE','=',$disease_code['0'])
          ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
          ->groupBy('PROVINCE')
          ->get();
      }
              //if data province is not null set value from DB
              foreach ($data[0] as $key => $val) {
                $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
                $pt_data[$val->PROVINCE]['DISEASE'] = $val->DISEASE;
                $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
                $pt_data[$val->PROVINCE]['case_jan'] = $val->case_jan;
                $pt_data[$val->PROVINCE]['death_jan'] = $val->death_jan;
                $pt_data[$val->PROVINCE]['case_feb'] = $val->case_feb;
                $pt_data[$val->PROVINCE]['death_feb'] = $val->death_feb;
                $pt_data[$val->PROVINCE]['case_mar'] = $val->case_mar;
                $pt_data[$val->PROVINCE]['death_mar'] = $val->death_mar;
                $pt_data[$val->PROVINCE]['case_apr'] = $val->case_apr;
                $pt_data[$val->PROVINCE]['death_apr'] = $val->death_apr;
                $pt_data[$val->PROVINCE]['case_may'] = $val->case_may;
                $pt_data[$val->PROVINCE]['death_may'] = $val->death_may;
                $pt_data[$val->PROVINCE]['case_jun'] = $val->case_jun;
                $pt_data[$val->PROVINCE]['death_jun'] = $val->death_jun;
                $pt_data[$val->PROVINCE]['case_jul'] = $val->case_jul;
                $pt_data[$val->PROVINCE]['death_jul'] = $val->death_jul;
                $pt_data[$val->PROVINCE]['case_aug'] = $val->case_aug;
                $pt_data[$val->PROVINCE]['death_aug'] = $val->death_aug;
                $pt_data[$val->PROVINCE]['case_sep'] = $val->case_sep;
                $pt_data[$val->PROVINCE]['death_sep'] = $val->death_sep;
                $pt_data[$val->PROVINCE]['case_oct'] = $val->case_oct;
                $pt_data[$val->PROVINCE]['death_oct'] = $val->death_oct;
                $pt_data[$val->PROVINCE]['case_nov'] = $val->case_nov;
                $pt_data[$val->PROVINCE]['death_nov'] = $val->death_nov;
                $pt_data[$val->PROVINCE]['case_dec'] = $val->case_feb;
                $pt_data[$val->PROVINCE]['death_dec'] = $val->case_feb;
                //Total
                $total_case = $val->case_jan+$val->case_feb+$val->case_mar+$val->case_apr+$val->case_may+$val->case_jun+$val->case_jul+$val->case_aug+$val->case_sep+$val->case_oct+$val->case_nov+$val->case_dec;
                $total_death = $val->death_jan+$val->death_feb+$val->death_mar+$val->death_apr+$val->death_may+$val->death_jun+$val->death_jul+$val->death_aug+$val->death_sep+$val->death_oct+$val->death_nov+$val->death_dec;
                $pt_data[$val->PROVINCE]['total_case'] = $total_case;
                $pt_data[$val->PROVINCE]['total_death'] = $total_death;

              }
              //add province
              foreach ($get_provincename_th as $key => $value) {
                if (array_key_exists($key, $pt_data)) {
                  $pt_rs[$key] = $pt_data[$key];
                } else {
                  //if data province is not set value = 0
                  $pt_rs[$key] = array(    'prov_dpc'=>$get_dpc_nameth[$key],'DISEASE'=>$val->DISEASE,'PROVINCE' => $get_provincename_th[$key],
                                           'case_jan' => "0",'death_jan' => "0",'case_feb' => "0",'death_feb'=> "0",
                                           'case_mar' => "0",'death_mar' => "0",'case_apr'=> "0",'death_apr'=> "0",
                                           'case_may' => "0",'death_may'=> "0",'case_jun'=> "0",'death_jun'=> "0",
                                           'case_jul' => "0",'death_jul'=> "0",'case_aug'=> "0",'death_aug'=> "0",
                                           'case_sep' => "0",'death_sep'=> "0",'case_oct'=> "0",'death_oct'=> "0",
                                           'case_nov' => "0",'death_nov'=> "0",'case_dec'=> "0",'death_dec'=> "0",
                                           'total_case' => "0", 'total_death' =>"0"
                                      );
                }
              }
              return $pt_rs;
    }

    public function xls_patient_sick_death_by_month(Request $request){
      $post_disease_code = $request->disease_code;
      $tblYear = $request->select_year;
      $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
      $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
      $excel_data[] = array('DPC','Reporting Area',
                      'Cases-jan','Deaths-jan','Cases-Feb','Deaths-Feb','Cases-Mar','Deaths-Mar','Cases-Apr','Deaths-Apr','Cases-May','Deaths-May','Cases-June','Deaths-June',
                      'Cases-July','Deaths-July','Cases-Aug','Deaths-Aug','Cases-Sept','Deaths-Sept','Cases-Oct','Deaths-Oct','Cases-Nov','Deaths-Nov','Cases-Dec','Deaths-Dec',
                      'Total_Cases','Total_Deaths'
                     );
                     //Check Disease
                     $disease_code =  explode(",",$post_disease_code);

                     if(count($disease_code)>2){
                       //Total>1 DISEASE select
                       $data_query[] = DB::table('ur506_'.$tblYear)
                         ->select('prov_dpc','DISEASE', 'PROVINCE')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan,sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATESICK) = 2,1,0)) as death_feb')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATESICK) = 3,1,0)) as death_mar')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATESICK) = 4,1,0)) as death_apr')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATESICK) = 5,1,0)) as death_may')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATESICK) = 6,1,0)) as death_jun')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 7,1,0)) as case_jul,sum(if(MONTH(DATEDEATH) = 7,1,0)) as death_jul')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 8,1,0)) as case_aug,sum(if(MONTH(DATEDEATH) = 8,1,0)) as death_aug')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 9,1,0)) as case_sep,sum(if(MONTH(DATEDEATH) = 9,1,0)) as death_sep')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 10,1,0)) as case_oct,sum(if(MONTH(DATEDEATH) =10,1,0)) as death_oct')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 11,1,0)) as case_nov,sum(if(MONTH(DATEDEATH) = 11,1,0)) as death_nov')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 12,1,0)) as case_dec,sum(if(MONTH(DATEDEATH) = 12,1,0)) as death_dec')
                         ->whereIn('DISEASE',$disease_code)
                         ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                         ->groupBy('PROVINCE')
                         ->get();

                     }else{
                       // 1 DISEASE SELECT
                       $data_query[] = DB::table('ur506_'.$tblYear)
                         ->select('prov_dpc','DISEASE', 'PROVINCE')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan,sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATESICK) = 2,1,0)) as death_feb')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATESICK) = 3,1,0)) as death_mar')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATESICK) = 4,1,0)) as death_apr')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATESICK) = 5,1,0)) as death_may')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATESICK) = 6,1,0)) as death_jun')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 7,1,0)) as case_jul,sum(if(MONTH(DATEDEATH) = 7,1,0)) as death_jul')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 8,1,0)) as case_aug,sum(if(MONTH(DATEDEATH) = 8,1,0)) as death_aug')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 9,1,0)) as case_sep,sum(if(MONTH(DATEDEATH) = 9,1,0)) as death_sep')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 10,1,0)) as case_oct,sum(if(MONTH(DATEDEATH) =10,1,0)) as death_oct')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 11,1,0)) as case_nov,sum(if(MONTH(DATEDEATH) = 11,1,0)) as death_nov')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 12,1,0)) as case_dec,sum(if(MONTH(DATEDEATH) = 12,1,0)) as death_dec')
                         ->where('DISEASE','=',$disease_code['0'])
                         ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                         ->groupBy('PROVINCE')
                         ->get();
                     }

                             foreach ($data_query[0] as $key => $val) {
                               $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
                               $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
                               $pt_data[$val->PROVINCE]['case_jan'] = $val->case_jan;
                               $pt_data[$val->PROVINCE]['death_jan'] = $val->death_jan;
                               $pt_data[$val->PROVINCE]['case_feb'] = $val->case_feb;
                               $pt_data[$val->PROVINCE]['death_feb'] = $val->death_feb;
                               $pt_data[$val->PROVINCE]['case_mar'] = $val->case_mar;
                               $pt_data[$val->PROVINCE]['death_mar'] = $val->death_mar;
                               $pt_data[$val->PROVINCE]['case_apr'] = $val->case_apr;
                               $pt_data[$val->PROVINCE]['death_apr'] = $val->death_apr;
                               $pt_data[$val->PROVINCE]['case_may'] = $val->case_may;
                               $pt_data[$val->PROVINCE]['death_may'] = $val->death_may;
                               $pt_data[$val->PROVINCE]['case_jun'] = $val->case_jun;
                               $pt_data[$val->PROVINCE]['death_jun'] = $val->death_jun;
                               $pt_data[$val->PROVINCE]['case_jul'] = $val->case_jul;
                               $pt_data[$val->PROVINCE]['death_jul'] = $val->death_jul;
                               $pt_data[$val->PROVINCE]['case_aug'] = $val->case_aug;
                               $pt_data[$val->PROVINCE]['death_aug'] = $val->death_aug;
                               $pt_data[$val->PROVINCE]['case_sep'] = $val->case_sep;
                               $pt_data[$val->PROVINCE]['death_sep'] = $val->death_sep;
                               $pt_data[$val->PROVINCE]['case_oct'] = $val->case_oct;
                               $pt_data[$val->PROVINCE]['death_oct'] = $val->death_oct;
                               $pt_data[$val->PROVINCE]['case_nov'] = $val->case_nov;
                               $pt_data[$val->PROVINCE]['death_nov'] = $val->death_nov;
                               $pt_data[$val->PROVINCE]['case_dec'] = $val->case_feb;
                               $pt_data[$val->PROVINCE]['death_dec'] = $val->case_feb;
                               //Total
                               $total_case = $val->case_jan+$val->case_feb+$val->case_mar+$val->case_apr+$val->case_may+$val->case_jun+$val->case_jul+$val->case_aug+$val->case_sep+$val->case_oct+$val->case_nov+$val->case_dec;
                               $total_death = $val->death_jan+$val->death_feb+$val->death_mar+$val->death_apr+$val->death_may+$val->death_jun+$val->death_jul+$val->death_aug+$val->death_sep+$val->death_oct+$val->death_nov+$val->death_dec;
                               $pt_data[$val->PROVINCE]['total_case'] = ($total_case > 0)? $total_case : '0';
                               $pt_data[$val->PROVINCE]['total_death'] = ($total_death > 0)? $total_death : '0';

                             }
                             foreach ($get_provincename_th as $key => $value) {
                               if (array_key_exists($key, $pt_data)) {
                                 $excel_data[$key] = $pt_data[$key];
                               }else{
                                 $excel_data[$key] = array(     'prov_dpc'=>$get_dpc_nameth[$key],
                                                          'PROVINCE' => $get_provincename_th[$key],
                                                          'case_jan' => "0",'death_jan' => "0",'case_feb' => "0",'death_feb'=> "0",
                                                          'case_mar' => "0",'death_mar' => "0",'case_apr'=> "0",'death_apr'=> "0",
                                                          'case_may' => "0",'death_may'=> "0",'case_jun'=> "0",'death_jun'=> "0",
                                                          'case_jul' => "0",'death_jul'=> "0",'case_aug'=> "0",'death_aug'=> "0",
                                                          'case_sep' => "0",'death_sep'=> "0",'case_oct'=> "0",'death_oct'=> "0",
                                                          'case_nov' => "0",'death_nov'=> "0",'case_dec'=> "0",'death_dec'=> "0",
                                                          'total_case' => "0", 'total_death' =>"0"
                                                     );
                               }
                             }
      //Year to DC
      $year_th = $tblYear+543;
      //filename
      $filename = 'sick-death-disease'.'-year-'.$year_th;;
      //sheetname
      $sheetname = 'sheet1';

      // header text
      $header_text = "ตารางข้อมูลผู้ป่วยจำนวนป่วย/ตาย โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

      Excel::create($filename, function($excel) use($excel_data,$sheetname,$header_text) {
          // Set the title
          $excel->setTitle('UCD-Report');
          // Chain the setters
          $excel->setCreator('Talek Team')->setCompany('Talek Team');
          //description
          $excel->setDescription('สปคม.');

          $excel->sheet($sheetname, function ($sheet) use ($excel_data,$header_text) {
              //Header Text
               $sheet->row(1, [$header_text]);
               $sheet->setAutoFilter('A2:AB2');
               $sheet->fromArray($excel_data, null, 'A2', false, false);
           });
       })->download('xlsx');
    }

    public static function get_patient_sick_death_ratio($select_year,$disease_code){
      $tblYear = (isset($select_year))? $select_year : date('Y')-1;
      $post_disease_code = (isset($disease_code))? $disease_code : "01";

      $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
      $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();

      //Check Disease
      $disease_code =  explode(",",$post_disease_code);

      if(count($disease_code)>2){
        //Total>1 DISEASE select
               $query[] = DB::table('ur506_'.$tblYear)
                 ->select('prov_dpc','DISEASE', 'PROVINCE')
                 ->selectRaw('sum(if(RESULT <> 2,1,0)) AS case_total')
                 ->selectRaw('sum(if(RESULT = 2,1,0)) AS death_total')
                 ->whereIn('DISEASE',$disease_code)
                 ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                 ->groupBy('PROVINCE')
                 ->get();
           }else{
              // 1 DISEASE SELECT
               $query[] = DB::table('ur506_'.$tblYear)
                 ->select('prov_dpc','DISEASE', 'PROVINCE')
                 ->selectRaw('sum(if(RESULT <> 2,1,0)) AS case_total')
                 ->selectRaw('sum(if(RESULT = 2,1,0)) AS death_total')
                 ->where('DISEASE','=',$disease_code['0'])
                 ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                 ->groupBy('PROVINCE')
                 ->get();
           }


    //  dd($query);

          foreach ($query[0] as $key => $val) {
            $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
            $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
            $pt_data[$val->PROVINCE]['PROVINCE_CODE'] = $val->PROVINCE;
            $pt_data[$val->PROVINCE]['case_total'] = $val->case_total;
            $pt_data[$val->PROVINCE]['death_total'] = $val->death_total;
          }

          foreach ($get_provincename_th as $key => $value) {
            if (array_key_exists($key, $pt_data)) {
              $excel_data[$key] = $pt_data[$key];
            }else{
              $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                          'PROVINCE' => $get_provincename_th[$key],
                                          'PROVINCE_CODE' => $key,
                                          'case_total' => "0.00",
                                          'death_total' => "0.00",
                                  );
            }
          }
          //dd(count($excel_data));
        return $excel_data;


    }

  public static function xls_patient_sick_death_ratio(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    $excel_data[] = array('DPC','Reporting Area','จำนวนผู้ป่วย','อัตราป่วย(ต่อประชากรแสนคน)','จำนวนผู้เสียชีวิต','อัตราป่วยตาย(%)','อัตราตาย(ต่อประชากรแสนคน)','จำนวนประชากร');

    //Check Disease
    $disease_code =  explode(",",$post_disease_code);

        if(count($disease_code)>2){
          //Total>1 DISEASE select
                 $query[] = DB::table('ur506_'.$tblYear)
                   ->select('prov_dpc','DISEASE', 'PROVINCE')
                   ->selectRaw('sum(if(RESULT <> 2,1,0)) AS case_total')
                   ->selectRaw('sum(if(RESULT = 2,1,0)) AS death_total')
                   ->whereIn('DISEASE',$disease_code)
                   ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                   ->groupBy('PROVINCE')
                   ->get();
        }else{
          // 1 DISEASE SELECT
                 $query[] = DB::table('ur506_'.$tblYear)
                   ->select('prov_dpc','DISEASE', 'PROVINCE')
                   ->selectRaw('sum(if(RESULT <> 2,1,0)) AS case_total')
                   ->selectRaw('sum(if(RESULT = 2,1,0)) AS death_total')
                   ->where('DISEASE','=',$disease_code['0'])
                   ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                   ->groupBy('PROVINCE')
                   ->get();
        }
        $total_pop_in_province =\App\Http\Controllers\PopulationController::all_population_by_province($tblYear);

        foreach ($query[0] as $key => $val) {
            $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc; //f1
            $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE]; //f2
            //$pt_data[$val->PROVINCE]['PROVINCE_CODE'] = $val->PROVINCE;
            $pt_data[$val->PROVINCE]['case_total'] = $val->case_total; //f3
            $pt_data[$val->PROVINCE]['death_total'] = $val->death_total; //f5

          if($total_pop_in_province[$val->PROVINCE]['poptotal_in_province']){
            $pt_data[$val->PROVINCE]['cal_ratio_cases'] = \App\Http\Controllers\Controller::cal_ratio_cases($total_pop_in_province[$val->PROVINCE]['poptotal_in_province'],$val->case_total); //f4
            $pt_data[$val->PROVINCE]['cal_ratio_cases_deaths'] = \App\Http\Controllers\Controller::cal_ratio_cases_deaths($val->case_total,$val->death_total); //f5
            $pt_data[$val->PROVINCE]['cal_ratio_deaths'] = Controller::cal_ratio_cases_deaths($total_pop_in_province[$val->PROVINCE]['poptotal_in_province'],$val->death_total); //f6
            $pt_data[$val->PROVINCE]['total_pop'] = number_format($total_pop_in_province[$val->PROVINCE]['poptotal_in_province']); //f7
          }else{

            $pt_data[$val->PROVINCE]['cal_ratio_cases'] = 0; //f4
            $pt_data[$val->PROVINCE]['cal_ratio_cases_deaths'] = 0; //f6
            $pt_data[$val->PROVINCE]['cal_ratio_deaths'] = 0; //f7
            $pt_data[$val->PROVINCE]['total_pop'] = 0; //f8
          }

        }

         //dd($pt_data);



        //dd($total_pop_in_province);

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        "case_total" => "4",
                                        "death_total" => "0",
                                        "cal_ratio_cases" => "0",
                                        "cal_ratio_cases_deaths" => "0",
                                        "cal_ratio_deaths" => "0",
                                        "total_pop" => "0",
                                      );
          }
        }

        //Year to DC
        $year_th = $tblYear+543;
        //filename
        $filename = 'sick-death-ratio'.'-year-'.$year_th;
        //sheetname
        $sheetname = 'sheet1';

        // header text
        $header_text = "ตารางข้อมูลอัตราป่วย/อัตราตาย/อัตราป่วย-ตาย จำแนกรายจังหวัด โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

        Excel::create($filename, function($excel) use($excel_data,$sheetname,$header_text) {
            // Set the title
            $excel->setTitle('UCD-Report');
            // Chain the setters
            $excel->setCreator('Talek Team')->setCompany('Talek Team');
            //description
            $excel->setDescription('สปคม.');

            $excel->sheet($sheetname, function ($sheet) use ($excel_data,$header_text) {
                //Header Text
                 $sheet->row(1, [$header_text]);
                 $sheet->setAutoFilter('A2:H2');
                 $sheet->fromArray($excel_data, null, 'A2', false, false);
             });
         })->download('xlsx');
      }

  public static function get_patient_sick_weekly($select_year,$disease_code){
      $tblYear = (isset($select_year))? $select_year : date('Y')-1;
      $arr_disease_code = (isset($disease_code))? $disease_code : "01";
      $get_pop_dpc_group =\App\Http\Controllers\Controller::get_pop_dpc_group();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
      foreach ($get_pop_dpc_group as $dpc_code => $dpc_val)
      {
           if($disease_code=='26-27-66'){
                 $query[] = DB::table('ur506_'.$tblYear)
                 ->select('DISEASE', 'PROVINCE')
                 ->selectRaw('SUM(IF(week_no = 01,1,0)) AS wk01,SUM(IF(week_no = 02,1,0)) AS wk02,SUM(IF(week_no = 03,1,0)) AS wk03')
                 ->selectRaw('SUM(IF(week_no = 04,1,0)) AS wk04,SUM(IF(week_no = 05,1,0)) AS wk05,SUM(IF(week_no = 06,1,0)) AS wk06')
                 ->selectRaw('SUM(IF(week_no = 07,1,0)) AS wk07,SUM(IF(week_no = 08,1,0)) AS wk08,SUM(IF(week_no = 09,1,0)) AS wk09')
                 ->selectRaw('SUM(IF(week_no = 10,1,0)) AS wk10,SUM(IF(week_no = 11,1,0)) AS wk11,SUM(IF(week_no = 12,1,0)) AS wk12')
                 ->selectRaw('SUM(IF(week_no = 13,1,0)) AS wk13,SUM(IF(week_no = 14,1,0)) AS wk14,SUM(IF(week_no = 15,1,0)) AS wk15')
                 ->selectRaw('SUM(IF(week_no = 16,1,0)) AS wk16,SUM(IF(week_no = 17,1,0)) AS wk17,SUM(IF(week_no = 18,1,0)) AS wk18')
                 ->selectRaw('SUM(IF(week_no = 19,1,0)) AS wk19,SUM(IF(week_no = 20,1,0)) AS wk20,SUM(IF(week_no = 21,1,0)) AS wk21')
                 ->selectRaw('SUM(IF(week_no = 22,1,0)) AS wk22,SUM(IF(week_no = 23,1,0)) AS wk23,SUM(IF(week_no = 24,1,0)) AS wk24')
                 ->selectRaw('SUM(IF(week_no = 25,1,0)) AS wk25,SUM(IF(week_no = 26,1,0)) AS wk26,SUM(IF(week_no = 27,1,0)) AS wk27')
                 ->selectRaw('SUM(IF(week_no = 28,1,0)) AS wk28,SUM(IF(week_no = 29,1,0)) AS wk29,SUM(IF(week_no = 30,1,0)) AS wk30')
                 ->selectRaw('SUM(IF(week_no = 31,1,0)) AS wk31,SUM(IF(week_no = 32,1,0)) AS wk32,SUM(IF(week_no = 33,1,0)) AS wk33')
                 ->selectRaw('SUM(IF(week_no = 34,1,0)) AS wk34,SUM(IF(week_no = 35,1,0)) AS wk35,SUM(IF(week_no = 36,1,0)) AS wk36')
                 ->selectRaw('SUM(IF(week_no = 37,1,0)) AS wk37,SUM(IF(week_no = 38,1,0)) AS wk38,SUM(IF(week_no = 39,1,0)) AS wk39')
                 ->selectRaw('SUM(IF(week_no = 40,1,0)) AS wk40,SUM(IF(week_no = 41,1,0)) AS wk41,SUM(IF(week_no = 42,1,0)) AS wk42')
                 ->selectRaw('SUM(IF(week_no = 43,1,0)) AS wk43,SUM(IF(week_no = 44,1,0)) AS wk44,SUM(IF(week_no = 45,1,0)) AS wk45')
                 ->selectRaw('SUM(IF(week_no = 46,1,0)) AS wk46,SUM(IF(week_no = 47,1,0)) AS wk47,SUM(IF(week_no = 48,1,0)) AS wk48')
                 ->selectRaw('SUM(IF(week_no = 49,1,0)) AS wk49,SUM(IF(week_no = 50,1,0)) AS wk50,SUM(IF(week_no = 51,1,0)) AS wk51')
                 ->selectRaw('SUM(IF(week_no = 52,1,0)) AS wk52,SUM(IF(week_no = 53,1,0)) AS wk53')
                 ->whereIn('DISEASE',['26','27','66'])
                 ->whereIn('PROVINCE',$dpc_val)
                 ->groupBy('PROVINCE')
                 ->get();
          }else{
                  $query[] = DB::table('ur506_'.$tblYear)
                  ->select('DISEASE', 'PROVINCE')
                  ->selectRaw('SUM(IF(week_no = 01,1,0)) AS wk01,SUM(IF(week_no = 02,1,0)) AS wk02,SUM(IF(week_no = 03,1,0)) AS wk03')
                  ->selectRaw('SUM(IF(week_no = 04,1,0)) AS wk04,SUM(IF(week_no = 05,1,0)) AS wk05,SUM(IF(week_no = 06,1,0)) AS wk06')
                  ->selectRaw('SUM(IF(week_no = 07,1,0)) AS wk07,SUM(IF(week_no = 08,1,0)) AS wk08,SUM(IF(week_no = 09,1,0)) AS wk09')
                  ->selectRaw('SUM(IF(week_no = 10,1,0)) AS wk10,SUM(IF(week_no = 11,1,0)) AS wk11,SUM(IF(week_no = 12,1,0)) AS wk12')
                  ->selectRaw('SUM(IF(week_no = 13,1,0)) AS wk13,SUM(IF(week_no = 14,1,0)) AS wk14,SUM(IF(week_no = 15,1,0)) AS wk15')
                  ->selectRaw('SUM(IF(week_no = 16,1,0)) AS wk16,SUM(IF(week_no = 17,1,0)) AS wk17,SUM(IF(week_no = 18,1,0)) AS wk18')
                  ->selectRaw('SUM(IF(week_no = 19,1,0)) AS wk19,SUM(IF(week_no = 20,1,0)) AS wk20,SUM(IF(week_no = 21,1,0)) AS wk21')
                  ->selectRaw('SUM(IF(week_no = 22,1,0)) AS wk22,SUM(IF(week_no = 23,1,0)) AS wk23,SUM(IF(week_no = 24,1,0)) AS wk24')
                  ->selectRaw('SUM(IF(week_no = 25,1,0)) AS wk25,SUM(IF(week_no = 26,1,0)) AS wk26,SUM(IF(week_no = 27,1,0)) AS wk27')
                  ->selectRaw('SUM(IF(week_no = 28,1,0)) AS wk28,SUM(IF(week_no = 29,1,0)) AS wk29,SUM(IF(week_no = 30,1,0)) AS wk30')
                  ->selectRaw('SUM(IF(week_no = 31,1,0)) AS wk31,SUM(IF(week_no = 32,1,0)) AS wk32,SUM(IF(week_no = 33,1,0)) AS wk33')
                  ->selectRaw('SUM(IF(week_no = 34,1,0)) AS wk34,SUM(IF(week_no = 35,1,0)) AS wk35,SUM(IF(week_no = 36,1,0)) AS wk36')
                  ->selectRaw('SUM(IF(week_no = 37,1,0)) AS wk37,SUM(IF(week_no = 38,1,0)) AS wk38,SUM(IF(week_no = 39,1,0)) AS wk39')
                  ->selectRaw('SUM(IF(week_no = 40,1,0)) AS wk40,SUM(IF(week_no = 41,1,0)) AS wk41,SUM(IF(week_no = 42,1,0)) AS wk42')
                  ->selectRaw('SUM(IF(week_no = 43,1,0)) AS wk43,SUM(IF(week_no = 44,1,0)) AS wk44,SUM(IF(week_no = 45,1,0)) AS wk45')
                  ->selectRaw('SUM(IF(week_no = 46,1,0)) AS wk46,SUM(IF(week_no = 47,1,0)) AS wk47,SUM(IF(week_no = 48,1,0)) AS wk48')
                  ->selectRaw('SUM(IF(week_no = 49,1,0)) AS wk49,SUM(IF(week_no = 50,1,0)) AS wk50,SUM(IF(week_no = 51,1,0)) AS wk51')
                  ->selectRaw('SUM(IF(week_no = 52,1,0)) AS wk52,SUM(IF(week_no = 53,1,0)) AS wk53')
                  ->where('DISEASE','=',$arr_disease_code)
                  ->whereIn('PROVINCE',$dpc_val)
                  ->groupBy('PROVINCE')
                  ->get();
          }
      }

      $arr_dpc_th = array('สคร.1','สคร.2','สคร.3','สคร.4','สคร.5','สคร.6','สคร.7','สคร.8','สคร.9','สคร.10','สคร.11','สคร.12','สปคม.');
            for($i=0;$i<count($query);$i++ ){
                  foreach ($query[$i] as $data_key => $data_val)
                  {
                      $data2[] = array('DPC' =>  $arr_dpc_th[$i],
                                      'PROVINCE' => $get_provincename_th[$data_val->PROVINCE],
                                      'wk01' => $data_val->wk01, 'wk02' => $data_val->wk02, 'wk03' => $data_val->wk03, 'wk04' => $data_val->wk04,'wk05' => $data_val->wk05,
                                      'wk06' => $data_val->wk06, 'wk07' => $data_val->wk07, 'wk08' => $data_val->wk08, 'wk09' => $data_val->wk09,'wk10' => $data_val->wk10,
                                      'wk11' => $data_val->wk11, 'wk12' => $data_val->wk12, 'wk13' => $data_val->wk13, 'wk14' => $data_val->wk14,'wk15' => $data_val->wk15,
                                      'wk16' => $data_val->wk16, 'wk17' => $data_val->wk17, 'wk18' => $data_val->wk18, 'wk19' => $data_val->wk19,'wk20' => $data_val->wk20,
                                      'wk21' => $data_val->wk21, 'wk22' => $data_val->wk22, 'wk23' => $data_val->wk23, 'wk24' => $data_val->wk24,'wk25' => $data_val->wk25,
                                      'wk26' => $data_val->wk26, 'wk27' => $data_val->wk27, 'wk28' => $data_val->wk28, 'wk29' => $data_val->wk29,'wk30' => $data_val->wk30,
                                      'wk31' => $data_val->wk31, 'wk32' => $data_val->wk32, 'wk33' => $data_val->wk33, 'wk34' => $data_val->wk34,'wk35' => $data_val->wk35,
                                      'wk36' => $data_val->wk36, 'wk37' => $data_val->wk37, 'wk38' => $data_val->wk38, 'wk39' => $data_val->wk39,'wk40' => $data_val->wk40,
                                      'wk41' => $data_val->wk41, 'wk42' => $data_val->wk42, 'wk43' => $data_val->wk43, 'wk44' => $data_val->wk44,'wk45' => $data_val->wk45,
                                      'wk46' => $data_val->wk46, 'wk47' => $data_val->wk47, 'wk48' => $data_val->wk48, 'wk49' => $data_val->wk49,'wk50' => $data_val->wk50,
                                      'wk51' => $data_val->wk51, 'wk52' => $data_val->wk52, 'wk53' => $data_val->wk53
                                      );
                  }
            }

      //dd($data2);
      return $data2;
  }
  public static function xls_patient_sick_weekly(Request $request){

  }
}
