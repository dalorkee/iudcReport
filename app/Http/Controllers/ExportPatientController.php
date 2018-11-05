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
       return view('frontend.patient-sick-by-age');
    }
    public function patient_death_by_age()
    {
       return view('frontend.patient-death-by-age');
    }
    public function patient_sick_death_by_nation()
    {
      return view('frontend.patient-sick-death-by-nation');
    }
    public function patient_sick_by_occupation()
    {
      return view('frontend.patient-sick-by-occupation');
    }
    public function patient_sick_by_sex()
    {
      return view('frontend.patient-sick-by-sex');
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
          ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATEDEATH) = 2,1,0)) as death_feb')
          ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATEDEATH) = 3,1,0)) as death_mar')
          ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATEDEATH) = 4,1,0)) as death_apr')
          ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATEDEATH) = 5,1,0)) as death_may')
          ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATEDEATH) = 6,1,0)) as death_jun')
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
          ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATEDEATH) = 2,1,0)) as death_feb')
          ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATEDEATH) = 3,1,0)) as death_mar')
          ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATEDEATH) = 4,1,0)) as death_apr')
          ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATEDEATH) = 5,1,0)) as death_may')
          ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATEDEATH) = 6,1,0)) as death_jun')
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
                $pt_data[$val->PROVINCE]['case_dec'] = $val->case_dec;
                $pt_data[$val->PROVINCE]['death_dec'] = $val->case_dec;
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
                         ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATEDEATH) = 2,1,0)) as death_feb')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATEDEATH) = 3,1,0)) as death_mar')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATEDEATH) = 4,1,0)) as death_apr')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATEDEATH) = 5,1,0)) as death_may')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATEDEATH) = 6,1,0)) as death_jun')
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
                         ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATEDEATH) = 2,1,0)) as death_feb')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATEDEATH) = 3,1,0)) as death_mar')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATEDEATH) = 4,1,0)) as death_apr')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATEDEATH) = 5,1,0)) as death_may')
                         ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATEDEATH) = 6,1,0)) as death_jun')
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
                               $pt_data[$val->PROVINCE]['case_dec'] = $val->case_dec;
                               $pt_data[$val->PROVINCE]['death_dec'] = $val->case_dec;
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
       })->download('csv');
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
         })->download('csv');
      }

  public static function get_patient_sick_weekly($select_year,$disease_code){
      $tblYear = (isset($select_year))? $select_year : date('Y')-1;
      $post_disease_code = (isset($disease_code))? $disease_code : "01";
      $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
      $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();

      //Check Disease
      $disease_code =  explode(",",$post_disease_code);

      if(count($disease_code)>2){
                 $query[] = DB::table('ur506_'.$tblYear)
                 ->select('prov_dpc','DISEASE', 'PROVINCE')
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
                 ->whereIn('DISEASE',$disease_code)
                 ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                 ->groupBy('PROVINCE')
                 ->get();
          }else{
                  $query[] = DB::table('ur506_'.$tblYear)
                  ->select('prov_dpc','DISEASE', 'PROVINCE')
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
                  ->where('DISEASE','=',$disease_code['0'])
                  ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                  ->groupBy('PROVINCE')
                  ->get();
          }

          foreach ($query[0] as $key => $val) {
            $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
            $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
            $pt_data[$val->PROVINCE]['wk01'] = $val->wk01;
            $pt_data[$val->PROVINCE]['wk02'] = $val->wk02;
            $pt_data[$val->PROVINCE]['wk03'] = $val->wk03;
            $pt_data[$val->PROVINCE]['wk04'] = $val->wk04;
            $pt_data[$val->PROVINCE]['wk05'] = $val->wk05;
            $pt_data[$val->PROVINCE]['wk06'] = $val->wk06;
            $pt_data[$val->PROVINCE]['wk07'] = $val->wk07;
            $pt_data[$val->PROVINCE]['wk08'] = $val->wk08;
            $pt_data[$val->PROVINCE]['wk09'] = $val->wk09;
            $pt_data[$val->PROVINCE]['wk10'] = $val->wk10;
            $pt_data[$val->PROVINCE]['wk11'] = $val->wk11;
            $pt_data[$val->PROVINCE]['wk12'] = $val->wk12;
            $pt_data[$val->PROVINCE]['wk13'] = $val->wk13;
            $pt_data[$val->PROVINCE]['wk14'] = $val->wk14;
            $pt_data[$val->PROVINCE]['wk15'] = $val->wk15;
            $pt_data[$val->PROVINCE]['wk16'] = $val->wk16;
            $pt_data[$val->PROVINCE]['wk17'] = $val->wk17;
            $pt_data[$val->PROVINCE]['wk18'] = $val->wk18;
            $pt_data[$val->PROVINCE]['wk19'] = $val->wk19;
            $pt_data[$val->PROVINCE]['wk20'] = $val->wk20;
            $pt_data[$val->PROVINCE]['wk21'] = $val->wk21;
            $pt_data[$val->PROVINCE]['wk22'] = $val->wk22;
            $pt_data[$val->PROVINCE]['wk23'] = $val->wk23;
            $pt_data[$val->PROVINCE]['wk24'] = $val->wk24;
            $pt_data[$val->PROVINCE]['wk25'] = $val->wk25;
            $pt_data[$val->PROVINCE]['wk26'] = $val->wk26;
            $pt_data[$val->PROVINCE]['wk27'] = $val->wk27;
            $pt_data[$val->PROVINCE]['wk28'] = $val->wk28;
            $pt_data[$val->PROVINCE]['wk29'] = $val->wk29;
            $pt_data[$val->PROVINCE]['wk30'] = $val->wk30;
            $pt_data[$val->PROVINCE]['wk31'] = $val->wk31;
            $pt_data[$val->PROVINCE]['wk32'] = $val->wk32;
            $pt_data[$val->PROVINCE]['wk33'] = $val->wk33;
            $pt_data[$val->PROVINCE]['wk34'] = $val->wk34;
            $pt_data[$val->PROVINCE]['wk35'] = $val->wk35;
            $pt_data[$val->PROVINCE]['wk36'] = $val->wk36;
            $pt_data[$val->PROVINCE]['wk37'] = $val->wk37;
            $pt_data[$val->PROVINCE]['wk38'] = $val->wk38;
            $pt_data[$val->PROVINCE]['wk39'] = $val->wk39;
            $pt_data[$val->PROVINCE]['wk40'] = $val->wk40;
            $pt_data[$val->PROVINCE]['wk41'] = $val->wk41;
            $pt_data[$val->PROVINCE]['wk42'] = $val->wk42;
            $pt_data[$val->PROVINCE]['wk43'] = $val->wk43;
            $pt_data[$val->PROVINCE]['wk44'] = $val->wk44;
            $pt_data[$val->PROVINCE]['wk45'] = $val->wk45;
            $pt_data[$val->PROVINCE]['wk46'] = $val->wk46;
            $pt_data[$val->PROVINCE]['wk47'] = $val->wk47;
            $pt_data[$val->PROVINCE]['wk48'] = $val->wk48;
            $pt_data[$val->PROVINCE]['wk49'] = $val->wk49;
            $pt_data[$val->PROVINCE]['wk50'] = $val->wk50;
            $pt_data[$val->PROVINCE]['wk51'] = $val->wk51;
            $pt_data[$val->PROVINCE]['wk52'] = $val->wk52;
            $pt_data[$val->PROVINCE]['wk53'] = $val->wk53;

          }

          foreach ($get_provincename_th as $key => $value) {
            if (array_key_exists($key, $pt_data)) {
              $excel_data[$key] = $pt_data[$key];
            }else{
              $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                          'PROVINCE' => $get_provincename_th[$key],
                                          'wk01' => 0, 'wk02' => 0, 'wk03' => 0, 'wk04' => 0,'wk05' => 0,
                                          'wk06' => 0, 'wk07' => 0, 'wk08' => 0, 'wk09' => 0,'wk10' => 0,
                                          'wk11' => 0, 'wk12' => 0, 'wk13' => 0, 'wk14' => 0,'wk15' => 0,
                                          'wk16' => 0, 'wk17' => 0, 'wk18' => 0, 'wk19' => 0,'wk20' => 0,
                                          'wk21' => 0, 'wk22' => 0, 'wk23' => 0, 'wk24' => 0,'wk25' => 0,
                                          'wk26' => 0, 'wk27' => 0, 'wk28' => 0, 'wk29' => 0,'wk30' => 0,
                                          'wk31' => 0, 'wk32' => 0, 'wk33' => 0, 'wk34' => 0,'wk35' => 0,
                                          'wk36' => 0, 'wk37' => 0, 'wk38' => 0, 'wk39' => 0,'wk40' => 0,
                                          'wk41' => 0, 'wk42' => 0, 'wk43' => 0, 'wk44' => 0,'wk45' => 0,
                                          'wk46' => 0, 'wk47' => 0, 'wk48' => 0, 'wk49' => 0,'wk50' => 0,
                                          'wk51' => 0, 'wk52' => 0, 'wk53' => 0
                                  );
            }
          }
          //dd(count($excel_data));
        return $excel_data;
  }
  public static function xls_patient_sick_weekly(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    $excel_data[] = array('DPC','Reporting Area','สัปดาห์ที่1','สัปดาห์ที่2','สัปดาห์ที3','สัปดาห์ที4','สัปดาห์ที่5',
                          'สัปดาห์ที6','สัปดาห์ที่7','สัปดาห์ที8','สัปดาห์ที9','สัปดาห์ที่10','สัปดาห์ที่11','สัปดาห์ที่12',
                          'สัปดาห์ที่13','สัปดาห์ที่14','สัปดาห์ที่15','สัปดาห์ที่16','สัปดาห์ที่17','สัปดาห์ที่18','สัปดาห์ที่19',
                          'สัปดาห์ที่20','สัปดาห์ที่21','สัปดาห์ที่22','สัปดาห์ที23','สัปดาห์ที24','สัปดาห์ที่25','สัปดาห์ที่26',
                          'สัปดาห์ที่27','สัปดาห์ที่28','สัปดาห์ที่29','สัปดาห์ที30','สัปดาห์ที่31','สัปดาห์ที่32',
                          'สัปดาห์ที่33','สัปดาห์ที34','สัปดาห์ที่35','สัปดาห์ที36','สัปดาห์ที37','สัปดาห์ที38','สัปดาห์ที่39',
                          'สัปดาห์ที่40','สัปดาห์ที41','สัปดาห์ที่42','สัปดาห์ที43','สัปดาห์ที44','สัปดาห์ที45','สัปดาห์ที46',
                          'สัปดาห์ที่47','สัปดาห์ที48','สัปดาห์ที่49','สัปดาห์ที50','สัปดาห์ที51','สัปดาห์ที52','สัปดาห์ที53',
                         );
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
               $query[] = DB::table('ur506_'.$tblYear)
               ->select('prov_dpc','DISEASE', 'PROVINCE')
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
               ->whereIn('DISEASE',$disease_code)
               ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
               ->groupBy('PROVINCE')
               ->get();
        }else{
                $query[] = DB::table('ur506_'.$tblYear)
                ->select('prov_dpc','DISEASE', 'PROVINCE')
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
                ->where('DISEASE','=',$disease_code['0'])
                ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
                ->groupBy('PROVINCE')
                ->get();
        }
        foreach ($query[0] as $key => $val) {
          $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
          $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
          $pt_data[$val->PROVINCE]['wk01'] = $val->wk01;
          $pt_data[$val->PROVINCE]['wk02'] = $val->wk02;
          $pt_data[$val->PROVINCE]['wk03'] = $val->wk03;
          $pt_data[$val->PROVINCE]['wk04'] = $val->wk04;
          $pt_data[$val->PROVINCE]['wk05'] = $val->wk05;
          $pt_data[$val->PROVINCE]['wk06'] = $val->wk06;
          $pt_data[$val->PROVINCE]['wk07'] = $val->wk07;
          $pt_data[$val->PROVINCE]['wk08'] = $val->wk08;
          $pt_data[$val->PROVINCE]['wk09'] = $val->wk09;
          $pt_data[$val->PROVINCE]['wk10'] = $val->wk10;
          $pt_data[$val->PROVINCE]['wk11'] = $val->wk11;
          $pt_data[$val->PROVINCE]['wk12'] = $val->wk12;
          $pt_data[$val->PROVINCE]['wk13'] = $val->wk13;
          $pt_data[$val->PROVINCE]['wk14'] = $val->wk14;
          $pt_data[$val->PROVINCE]['wk15'] = $val->wk15;
          $pt_data[$val->PROVINCE]['wk16'] = $val->wk16;
          $pt_data[$val->PROVINCE]['wk17'] = $val->wk17;
          $pt_data[$val->PROVINCE]['wk18'] = $val->wk18;
          $pt_data[$val->PROVINCE]['wk19'] = $val->wk19;
          $pt_data[$val->PROVINCE]['wk20'] = $val->wk20;
          $pt_data[$val->PROVINCE]['wk21'] = $val->wk21;
          $pt_data[$val->PROVINCE]['wk22'] = $val->wk22;
          $pt_data[$val->PROVINCE]['wk23'] = $val->wk23;
          $pt_data[$val->PROVINCE]['wk24'] = $val->wk24;
          $pt_data[$val->PROVINCE]['wk25'] = $val->wk25;
          $pt_data[$val->PROVINCE]['wk26'] = $val->wk26;
          $pt_data[$val->PROVINCE]['wk27'] = $val->wk27;
          $pt_data[$val->PROVINCE]['wk28'] = $val->wk28;
          $pt_data[$val->PROVINCE]['wk29'] = $val->wk29;
          $pt_data[$val->PROVINCE]['wk30'] = $val->wk30;
          $pt_data[$val->PROVINCE]['wk31'] = $val->wk31;
          $pt_data[$val->PROVINCE]['wk32'] = $val->wk32;
          $pt_data[$val->PROVINCE]['wk33'] = $val->wk33;
          $pt_data[$val->PROVINCE]['wk34'] = $val->wk34;
          $pt_data[$val->PROVINCE]['wk35'] = $val->wk35;
          $pt_data[$val->PROVINCE]['wk36'] = $val->wk36;
          $pt_data[$val->PROVINCE]['wk37'] = $val->wk37;
          $pt_data[$val->PROVINCE]['wk38'] = $val->wk38;
          $pt_data[$val->PROVINCE]['wk39'] = $val->wk39;
          $pt_data[$val->PROVINCE]['wk40'] = $val->wk40;
          $pt_data[$val->PROVINCE]['wk41'] = $val->wk41;
          $pt_data[$val->PROVINCE]['wk42'] = $val->wk42;
          $pt_data[$val->PROVINCE]['wk43'] = $val->wk43;
          $pt_data[$val->PROVINCE]['wk44'] = $val->wk44;
          $pt_data[$val->PROVINCE]['wk45'] = $val->wk45;
          $pt_data[$val->PROVINCE]['wk46'] = $val->wk46;
          $pt_data[$val->PROVINCE]['wk47'] = $val->wk47;
          $pt_data[$val->PROVINCE]['wk48'] = $val->wk48;
          $pt_data[$val->PROVINCE]['wk49'] = $val->wk49;
          $pt_data[$val->PROVINCE]['wk50'] = $val->wk50;
          $pt_data[$val->PROVINCE]['wk51'] = $val->wk51;
          $pt_data[$val->PROVINCE]['wk52'] = $val->wk52;
          $pt_data[$val->PROVINCE]['wk53'] = $val->wk53;

        }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'wk01' => "0", 'wk02' => "0", 'wk03' => "0", 'wk04' => "0",'wk05' => "0",
                                        'wk06' => "0", 'wk07' => "0", 'wk08' => "0", 'wk09' => "0",'wk10' => "0",
                                        'wk11' => "0", 'wk12' => "0", 'wk13' => "0", 'wk14' => "0",'wk15' => "0",
                                        'wk16' => "0", 'wk17' => "0", 'wk18' => "0", 'wk19' => "0",'wk20' => "0",
                                        'wk21' => "0", 'wk22' => "0", 'wk23' => "0", 'wk24' => "0",'wk25' => "0",
                                        'wk26' => "0", 'wk27' => "0", 'wk28' => "0", 'wk29' => "0",'wk30' => "0",
                                        'wk31' => "0", 'wk32' => "0", 'wk33' => "0", 'wk34' => "0",'wk35' => "0",
                                        'wk36' => "0", 'wk37' => "0", 'wk38' => "0", 'wk39' => "0",'wk40' => "0",
                                        'wk41' => "0", 'wk42' => "0", 'wk43' => "0", 'wk44' => "0",'wk45' => "0",
                                        'wk46' => "0", 'wk47' => "0", 'wk48' => "0", 'wk49' => "0",'wk50' => "0",
                                        'wk51' => "0", 'wk52' => "0", 'wk53' => "0"
                                );
          }
        }

        //dd($excel_data);
        //Year to DC
        $year_th = $tblYear+543;
        //filename
        $filename = 'sick-weekly'.'-year-'.$year_th;
        //sheetname
        $sheetname = 'sheet1';

        // header text
        $header_text = "ตารางข้อมูลจำนวนป่วย รายสัปดาห์ จำแนกรายจังหวัด โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
         })->download('csv');
  }
  public static function get_patient_sick_by_age($select_year,$disease_code){
    $tblYear = (isset($select_year))? $select_year : date('Y')-1;
    $post_disease_code = (isset($disease_code))? $disease_code : "01";
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 0,1,0)) AS case_age_0,SUM(IF(RESULT <> 2 AND YEAR = 1,1,0)) AS case_age_1')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR <> 2,1,0)) AS case_age_2,SUM(IF(RESULT <> 2 AND YEAR = 3,1,0)) AS case_age_3')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 4,1,0)) AS case_age_4,SUM(IF(RESULT <> 2 AND YEAR = 5,1,0)) AS case_age_5')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 6,1,0)) AS case_age_6,SUM(IF(RESULT <> 2 AND YEAR = 7,1,0)) AS case_age_7')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 8,1,0)) AS case_age_8,SUM(IF(RESULT <> 2 AND YEAR = 9,1,0)) AS case_age_9')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 10,1,0)) AS case_age_10,SUM(IF(RESULT <> 2 AND YEAR = 11,1,0)) AS case_age_11')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 12,1,0)) AS case_age_12,SUM(IF(RESULT <> 2 AND YEAR = 13,1,0)) AS case_age_13')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 14,1,0)) AS case_age_14,SUM(IF(RESULT <> 2 AND YEAR = 15,1,0)) AS case_age_15')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 16,1,0)) AS case_age_16,SUM(IF(RESULT <> 2 AND YEAR = 17,1,0)) AS case_age_17')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 18,1,0)) AS case_age_18,SUM(IF(RESULT <> 2 AND YEAR = 19,1,0)) AS case_age_19')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 20,1,0)) AS case_age_20,SUM(IF(RESULT <> 2 AND YEAR = 21,1,0)) AS case_age_21')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 22,1,0)) AS case_age_22,SUM(IF(RESULT <> 2 AND YEAR = 23,1,0)) AS case_age_23')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 24,1,0)) AS case_age_24,SUM(IF(RESULT <> 2 AND YEAR = 25,1,0)) AS case_age_25')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 26,1,0)) AS case_age_26,SUM(IF(RESULT <> 2 AND YEAR = 27,1,0)) AS case_age_27')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 28,1,0)) AS case_age_28,SUM(IF(RESULT <> 2 AND YEAR = 29,1,0)) AS case_age_29')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 30,1,0)) AS case_age_30,SUM(IF(RESULT <> 2 AND YEAR = 31,1,0)) AS case_age_31')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 32,1,0)) AS case_age_32,SUM(IF(RESULT <> 2 AND YEAR = 33,1,0)) AS case_age_33')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 34,1,0)) AS case_age_34,SUM(IF(RESULT <> 2 AND YEAR = 35,1,0)) AS case_age_35')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 36,1,0)) AS case_age_36,SUM(IF(RESULT <> 2 AND YEAR = 37,1,0)) AS case_age_37')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 38,1,0)) AS case_age_38,SUM(IF(RESULT <> 2 AND YEAR = 39,1,0)) AS case_age_39')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 40,1,0)) AS case_age_40,SUM(IF(RESULT <> 2 AND YEAR = 41,1,0)) AS case_age_41')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 42,1,0)) AS case_age_42,SUM(IF(RESULT <> 2 AND YEAR = 43,1,0)) AS case_age_43')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 44,1,0)) AS case_age_44,SUM(IF(RESULT <> 2 AND YEAR = 45,1,0)) AS case_age_45')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 46,1,0)) AS case_age_46,SUM(IF(RESULT <> 2 AND YEAR = 47,1,0)) AS case_age_47')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 48,1,0)) AS case_age_48,SUM(IF(RESULT <> 2 AND YEAR = 49,1,0)) AS case_age_49')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 50,1,0)) AS case_age_50,SUM(IF(RESULT <> 2 AND YEAR = 51,1,0)) AS case_age_51')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 52,1,0)) AS case_age_52,SUM(IF(RESULT <> 2 AND YEAR = 53,1,0)) AS case_age_53')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 54,1,0)) AS case_age_54,SUM(IF(RESULT <> 2 AND YEAR = 55,1,0)) AS case_age_55')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 56,1,0)) AS case_age_56,SUM(IF(RESULT <> 2 AND YEAR = 57,1,0)) AS case_age_57')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 58,1,0)) AS case_age_58,SUM(IF(RESULT <> 2 AND YEAR = 59,1,0)) AS case_age_59')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 60,1,0)) AS case_age_60,SUM(IF(RESULT <> 2 AND YEAR = 61,1,0)) AS case_age_61')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 62,1,0)) AS case_age_62,SUM(IF(RESULT <> 2 AND YEAR = 63,1,0)) AS case_age_63')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 64,1,0)) AS case_age_64,SUM(IF(RESULT <> 2 AND YEAR = 65,1,0)) AS case_age_65')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 66,1,0)) AS case_age_66,SUM(IF(RESULT <> 2 AND YEAR = 67,1,0)) AS case_age_67')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 68,1,0)) AS case_age_68,SUM(IF(RESULT <> 2 AND YEAR = 69,1,0)) AS case_age_69')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 70,1,0)) AS case_age_70,SUM(IF(RESULT <> 2 AND YEAR = 71,1,0)) AS case_age_71')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 72,1,0)) AS case_age_72,SUM(IF(RESULT <> 2 AND YEAR = 73,1,0)) AS case_age_73')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 74,1,0)) AS case_age_74,SUM(IF(RESULT <> 2 AND YEAR = 75,1,0)) AS case_age_75')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 76,1,0)) AS case_age_76,SUM(IF(RESULT <> 2 AND YEAR = 77,1,0)) AS case_age_77')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 78,1,0)) AS case_age_78,SUM(IF(RESULT <> 2 AND YEAR = 79,1,0)) AS case_age_79')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 80,1,0)) AS case_age_80,SUM(IF(RESULT <> 2 AND YEAR = 81,1,0)) AS case_age_81')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 82,1,0)) AS case_age_82,SUM(IF(RESULT <> 2 AND YEAR = 83,1,0)) AS case_age_83')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 84,1,0)) AS case_age_84,SUM(IF(RESULT <> 2 AND YEAR = 85,1,0)) AS case_age_85')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 86,1,0)) AS case_age_86,SUM(IF(RESULT <> 2 AND YEAR = 87,1,0)) AS case_age_87')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 88,1,0)) AS case_age_88,SUM(IF(RESULT <> 2 AND YEAR = 89,1,0)) AS case_age_89')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 90,1,0)) AS case_age_90,SUM(IF(RESULT <> 2 AND YEAR = 91,1,0)) AS case_age_91')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 92,1,0)) AS case_age_92,SUM(IF(RESULT <> 2 AND YEAR = 93,1,0)) AS case_age_93')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 94,1,0)) AS case_age_94,SUM(IF(RESULT <> 2 AND YEAR = 95,1,0)) AS case_age_95')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 96,1,0)) AS case_age_96,SUM(IF(RESULT <> 2 AND YEAR = 97,1,0)) AS case_age_97')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 98,1,0)) AS case_age_98,SUM(IF(RESULT <> 2 AND YEAR = 99,1,0)) AS case_age_99')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR >= 100,1,0)) AS case_age_100')
      ->whereIn('DISEASE',$disease_code)
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }else{
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 0,1,0)) AS case_age_0,SUM(IF(RESULT <> 2 AND YEAR = 1,1,0)) AS case_age_1')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 2,1,0)) AS case_age_2,SUM(IF(RESULT <> 2 AND YEAR = 3,1,0)) AS case_age_3')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 4,1,0)) AS case_age_4,SUM(IF(RESULT <> 2 AND YEAR = 5,1,0)) AS case_age_5')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 6,1,0)) AS case_age_6,SUM(IF(RESULT <> 2 AND YEAR = 7,1,0)) AS case_age_7')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 8,1,0)) AS case_age_8,SUM(IF(RESULT <> 2 AND YEAR = 9,1,0)) AS case_age_9')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 10,1,0)) AS case_age_10,SUM(IF(RESULT <> 2 AND YEAR = 11,1,0)) AS case_age_11')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 12,1,0)) AS case_age_12,SUM(IF(RESULT <> 2 AND YEAR = 13,1,0)) AS case_age_13')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 14,1,0)) AS case_age_14,SUM(IF(RESULT <> 2 AND YEAR = 15,1,0)) AS case_age_15')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 16,1,0)) AS case_age_16,SUM(IF(RESULT <> 2 AND YEAR = 17,1,0)) AS case_age_17')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 18,1,0)) AS case_age_18,SUM(IF(RESULT <> 2 AND YEAR = 19,1,0)) AS case_age_19')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 20,1,0)) AS case_age_20,SUM(IF(RESULT <> 2 AND YEAR = 21,1,0)) AS case_age_21')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 22,1,0)) AS case_age_22,SUM(IF(RESULT <> 2 AND YEAR = 23,1,0)) AS case_age_23')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 24,1,0)) AS case_age_24,SUM(IF(RESULT <> 2 AND YEAR = 25,1,0)) AS case_age_25')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 26,1,0)) AS case_age_26,SUM(IF(RESULT <> 2 AND YEAR = 27,1,0)) AS case_age_27')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 28,1,0)) AS case_age_28,SUM(IF(RESULT <> 2 AND YEAR = 29,1,0)) AS case_age_29')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 30,1,0)) AS case_age_30,SUM(IF(RESULT <> 2 AND YEAR = 31,1,0)) AS case_age_31')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 32,1,0)) AS case_age_32,SUM(IF(RESULT <> 2 AND YEAR = 33,1,0)) AS case_age_33')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 34,1,0)) AS case_age_34,SUM(IF(RESULT <> 2 AND YEAR = 35,1,0)) AS case_age_35')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 36,1,0)) AS case_age_36,SUM(IF(RESULT <> 2 AND YEAR = 37,1,0)) AS case_age_37')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 38,1,0)) AS case_age_38,SUM(IF(RESULT <> 2 AND YEAR = 39,1,0)) AS case_age_39')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 40,1,0)) AS case_age_40,SUM(IF(RESULT <> 2 AND YEAR = 41,1,0)) AS case_age_41')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 42,1,0)) AS case_age_42,SUM(IF(RESULT <> 2 AND YEAR = 43,1,0)) AS case_age_43')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 44,1,0)) AS case_age_44,SUM(IF(RESULT <> 2 AND YEAR = 45,1,0)) AS case_age_45')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 46,1,0)) AS case_age_46,SUM(IF(RESULT <> 2 AND YEAR = 47,1,0)) AS case_age_47')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 48,1,0)) AS case_age_48,SUM(IF(RESULT <> 2 AND YEAR = 49,1,0)) AS case_age_49')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 50,1,0)) AS case_age_50,SUM(IF(RESULT <> 2 AND YEAR = 51,1,0)) AS case_age_51')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 52,1,0)) AS case_age_52,SUM(IF(RESULT <> 2 AND YEAR = 53,1,0)) AS case_age_53')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 54,1,0)) AS case_age_54,SUM(IF(RESULT <> 2 AND YEAR = 55,1,0)) AS case_age_55')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 56,1,0)) AS case_age_56,SUM(IF(RESULT <> 2 AND YEAR = 57,1,0)) AS case_age_57')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 58,1,0)) AS case_age_58,SUM(IF(RESULT <> 2 AND YEAR = 59,1,0)) AS case_age_59')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 60,1,0)) AS case_age_60,SUM(IF(RESULT <> 2 AND YEAR = 61,1,0)) AS case_age_61')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 62,1,0)) AS case_age_62,SUM(IF(RESULT <> 2 AND YEAR = 63,1,0)) AS case_age_63')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 64,1,0)) AS case_age_64,SUM(IF(RESULT <> 2 AND YEAR = 65,1,0)) AS case_age_65')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 66,1,0)) AS case_age_66,SUM(IF(RESULT <> 2 AND YEAR = 67,1,0)) AS case_age_67')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 68,1,0)) AS case_age_68,SUM(IF(RESULT <> 2 AND YEAR = 69,1,0)) AS case_age_69')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 70,1,0)) AS case_age_70,SUM(IF(RESULT <> 2 AND YEAR = 71,1,0)) AS case_age_71')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 72,1,0)) AS case_age_72,SUM(IF(RESULT <> 2 AND YEAR = 73,1,0)) AS case_age_73')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 74,1,0)) AS case_age_74,SUM(IF(RESULT <> 2 AND YEAR = 75,1,0)) AS case_age_75')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 76,1,0)) AS case_age_76,SUM(IF(RESULT <> 2 AND YEAR = 77,1,0)) AS case_age_77')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 78,1,0)) AS case_age_78,SUM(IF(RESULT <> 2 AND YEAR = 79,1,0)) AS case_age_79')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 80,1,0)) AS case_age_80,SUM(IF(RESULT <> 2 AND YEAR = 81,1,0)) AS case_age_81')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 82,1,0)) AS case_age_82,SUM(IF(RESULT <> 2 AND YEAR = 83,1,0)) AS case_age_83')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 84,1,0)) AS case_age_84,SUM(IF(RESULT <> 2 AND YEAR = 85,1,0)) AS case_age_85')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 86,1,0)) AS case_age_86,SUM(IF(RESULT <> 2 AND YEAR = 87,1,0)) AS case_age_87')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 88,1,0)) AS case_age_88,SUM(IF(RESULT <> 2 AND YEAR = 89,1,0)) AS case_age_89')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 90,1,0)) AS case_age_90,SUM(IF(RESULT <> 2 AND YEAR = 91,1,0)) AS case_age_91')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 92,1,0)) AS case_age_92,SUM(IF(RESULT <> 2 AND YEAR = 93,1,0)) AS case_age_93')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 94,1,0)) AS case_age_94,SUM(IF(RESULT <> 2 AND YEAR = 95,1,0)) AS case_age_95')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 96,1,0)) AS case_age_96,SUM(IF(RESULT <> 2 AND YEAR = 97,1,0)) AS case_age_97')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 98,1,0)) AS case_age_98,SUM(IF(RESULT <> 2 AND YEAR = 99,1,0)) AS case_age_99')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR >= 100,1,0)) AS case_age_100')
      ->where('DISEASE','=',$disease_code['0'])
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }

      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['case_age_0'] = $val->case_age_0;
        $pt_data[$val->PROVINCE]['case_age_1'] = $val->case_age_1;
        $pt_data[$val->PROVINCE]['case_age_2'] = $val->case_age_2;
        $pt_data[$val->PROVINCE]['case_age_3'] = $val->case_age_3;
        $pt_data[$val->PROVINCE]['case_age_4'] = $val->case_age_4;
        $pt_data[$val->PROVINCE]['case_age_5'] = $val->case_age_5;
        $pt_data[$val->PROVINCE]['case_age_6'] = $val->case_age_6;
        $pt_data[$val->PROVINCE]['case_age_7'] = $val->case_age_7;
        $pt_data[$val->PROVINCE]['case_age_8'] = $val->case_age_8;
        $pt_data[$val->PROVINCE]['case_age_9'] = $val->case_age_9;
        $pt_data[$val->PROVINCE]['case_age_10'] = $val->case_age_10;
        $pt_data[$val->PROVINCE]['case_age_11'] = $val->case_age_11;
        $pt_data[$val->PROVINCE]['case_age_12'] = $val->case_age_12;
        $pt_data[$val->PROVINCE]['case_age_13'] = $val->case_age_13;
        $pt_data[$val->PROVINCE]['case_age_14'] = $val->case_age_14;
        $pt_data[$val->PROVINCE]['case_age_15'] = $val->case_age_15;
        $pt_data[$val->PROVINCE]['case_age_16'] = $val->case_age_16;
        $pt_data[$val->PROVINCE]['case_age_17'] = $val->case_age_17;
        $pt_data[$val->PROVINCE]['case_age_18'] = $val->case_age_18;
        $pt_data[$val->PROVINCE]['case_age_19'] = $val->case_age_19;
        $pt_data[$val->PROVINCE]['case_age_20'] = $val->case_age_20;
        $pt_data[$val->PROVINCE]['case_age_21'] = $val->case_age_21;
        $pt_data[$val->PROVINCE]['case_age_22'] = $val->case_age_22;
        $pt_data[$val->PROVINCE]['case_age_23'] = $val->case_age_23;
        $pt_data[$val->PROVINCE]['case_age_24'] = $val->case_age_24;
        $pt_data[$val->PROVINCE]['case_age_25'] = $val->case_age_25;
        $pt_data[$val->PROVINCE]['case_age_26'] = $val->case_age_26;
        $pt_data[$val->PROVINCE]['case_age_27'] = $val->case_age_27;
        $pt_data[$val->PROVINCE]['case_age_28'] = $val->case_age_28;
        $pt_data[$val->PROVINCE]['case_age_29'] = $val->case_age_29;
        $pt_data[$val->PROVINCE]['case_age_30'] = $val->case_age_30;
        $pt_data[$val->PROVINCE]['case_age_31'] = $val->case_age_31;
        $pt_data[$val->PROVINCE]['case_age_32'] = $val->case_age_32;
        $pt_data[$val->PROVINCE]['case_age_33'] = $val->case_age_33;
        $pt_data[$val->PROVINCE]['case_age_34'] = $val->case_age_34;
        $pt_data[$val->PROVINCE]['case_age_35'] = $val->case_age_35;
        $pt_data[$val->PROVINCE]['case_age_36'] = $val->case_age_36;
        $pt_data[$val->PROVINCE]['case_age_37'] = $val->case_age_37;
        $pt_data[$val->PROVINCE]['case_age_38'] = $val->case_age_38;
        $pt_data[$val->PROVINCE]['case_age_39'] = $val->case_age_39;
        $pt_data[$val->PROVINCE]['case_age_40'] = $val->case_age_40;
        $pt_data[$val->PROVINCE]['case_age_41'] = $val->case_age_41;
        $pt_data[$val->PROVINCE]['case_age_42'] = $val->case_age_42;
        $pt_data[$val->PROVINCE]['case_age_43'] = $val->case_age_43;
        $pt_data[$val->PROVINCE]['case_age_44'] = $val->case_age_44;
        $pt_data[$val->PROVINCE]['case_age_45'] = $val->case_age_45;
        $pt_data[$val->PROVINCE]['case_age_46'] = $val->case_age_46;
        $pt_data[$val->PROVINCE]['case_age_47'] = $val->case_age_47;
        $pt_data[$val->PROVINCE]['case_age_48'] = $val->case_age_48;
        $pt_data[$val->PROVINCE]['case_age_49'] = $val->case_age_49;
        $pt_data[$val->PROVINCE]['case_age_50'] = $val->case_age_50;
        $pt_data[$val->PROVINCE]['case_age_51'] = $val->case_age_51;
        $pt_data[$val->PROVINCE]['case_age_52'] = $val->case_age_52;
        $pt_data[$val->PROVINCE]['case_age_53'] = $val->case_age_53;
        $pt_data[$val->PROVINCE]['case_age_54'] = $val->case_age_54;
        $pt_data[$val->PROVINCE]['case_age_55'] = $val->case_age_55;
        $pt_data[$val->PROVINCE]['case_age_56'] = $val->case_age_56;
        $pt_data[$val->PROVINCE]['case_age_57'] = $val->case_age_57;
        $pt_data[$val->PROVINCE]['case_age_58'] = $val->case_age_58;
        $pt_data[$val->PROVINCE]['case_age_59'] = $val->case_age_59;
        $pt_data[$val->PROVINCE]['case_age_60'] = $val->case_age_60;
        $pt_data[$val->PROVINCE]['case_age_61'] = $val->case_age_61;
        $pt_data[$val->PROVINCE]['case_age_62'] = $val->case_age_62;
        $pt_data[$val->PROVINCE]['case_age_63'] = $val->case_age_63;
        $pt_data[$val->PROVINCE]['case_age_64'] = $val->case_age_64;
        $pt_data[$val->PROVINCE]['case_age_65'] = $val->case_age_65;
        $pt_data[$val->PROVINCE]['case_age_66'] = $val->case_age_66;
        $pt_data[$val->PROVINCE]['case_age_67'] = $val->case_age_67;
        $pt_data[$val->PROVINCE]['case_age_68'] = $val->case_age_68;
        $pt_data[$val->PROVINCE]['case_age_69'] = $val->case_age_69;
        $pt_data[$val->PROVINCE]['case_age_70'] = $val->case_age_70;
        $pt_data[$val->PROVINCE]['case_age_71'] = $val->case_age_71;
        $pt_data[$val->PROVINCE]['case_age_72'] = $val->case_age_72;
        $pt_data[$val->PROVINCE]['case_age_73'] = $val->case_age_73;
        $pt_data[$val->PROVINCE]['case_age_74'] = $val->case_age_74;
        $pt_data[$val->PROVINCE]['case_age_75'] = $val->case_age_75;
        $pt_data[$val->PROVINCE]['case_age_76'] = $val->case_age_76;
        $pt_data[$val->PROVINCE]['case_age_77'] = $val->case_age_77;
        $pt_data[$val->PROVINCE]['case_age_78'] = $val->case_age_78;
        $pt_data[$val->PROVINCE]['case_age_79'] = $val->case_age_79;
        $pt_data[$val->PROVINCE]['case_age_80'] = $val->case_age_80;
        $pt_data[$val->PROVINCE]['case_age_81'] = $val->case_age_81;
        $pt_data[$val->PROVINCE]['case_age_82'] = $val->case_age_82;
        $pt_data[$val->PROVINCE]['case_age_83'] = $val->case_age_83;
        $pt_data[$val->PROVINCE]['case_age_84'] = $val->case_age_84;
        $pt_data[$val->PROVINCE]['case_age_85'] = $val->case_age_85;
        $pt_data[$val->PROVINCE]['case_age_86'] = $val->case_age_86;
        $pt_data[$val->PROVINCE]['case_age_87'] = $val->case_age_87;
        $pt_data[$val->PROVINCE]['case_age_88'] = $val->case_age_88;
        $pt_data[$val->PROVINCE]['case_age_89'] = $val->case_age_89;
        $pt_data[$val->PROVINCE]['case_age_90'] = $val->case_age_90;
        $pt_data[$val->PROVINCE]['case_age_91'] = $val->case_age_91;
        $pt_data[$val->PROVINCE]['case_age_92'] = $val->case_age_92;
        $pt_data[$val->PROVINCE]['case_age_93'] = $val->case_age_93;
        $pt_data[$val->PROVINCE]['case_age_94'] = $val->case_age_94;
        $pt_data[$val->PROVINCE]['case_age_95'] = $val->case_age_95;
        $pt_data[$val->PROVINCE]['case_age_96'] = $val->case_age_96;
        $pt_data[$val->PROVINCE]['case_age_97'] = $val->case_age_97;
        $pt_data[$val->PROVINCE]['case_age_98'] = $val->case_age_98;
        $pt_data[$val->PROVINCE]['case_age_99'] = $val->case_age_99;
        $pt_data[$val->PROVINCE]['case_age_100'] = $val->case_age_100;

      }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'case_age_0' => 0,'case_age_1' => 0,'case_age_2' => 0,'case_age_3' => 0,'case_age_4' => 0,
                                        'case_age_5' => 0,'case_age_6' => 0,'case_age_7' => 0,'case_age_8' => 0,'case_age_9' => 0,
                                        'case_age_10' => 0,'case_age_11' => 0,'case_age_12' => 0,'case_age_13' => 0,'case_age_14' => 0,
                                        'case_age_15' => 0,'case_age_16' => 0,'case_age_17' => 0,'case_age_18' => 0,'case_age_19' => 0,
                                        'case_age_20' => 0,'case_age_21' => 0,'case_age_22' => 0,'case_age_23' => 0,'case_age_24' => 0,
                                        'case_age_25' => 0,'case_age_26' => 0,'case_age_27' => 0,'case_age_28' => 0,'case_age_29' => 0,
                                        'case_age_30' => 0,'case_age_31' => 0,'case_age_32' => 0,'case_age_33' => 0,'case_age_34' => 0,
                                        'case_age_35' => 0,'case_age_36' => 0,'case_age_37' => 0,'case_age_38' => 0,'case_age_39' => 0,
                                        'case_age_40' => 0,'case_age_41' => 0,'case_age_42' => 0,'case_age_43' => 0,'case_age_44' => 0,
                                        'case_age_45' => 0,'case_age_46' => 0,'case_age_47' => 0,'case_age_48' => 0,'case_age_49' => 0,
                                        'case_age_50' => 0,'case_age_51' => 0,'case_age_52' => 0,'case_age_53' => 0,'case_age_54' => 0,
                                        'case_age_55' => 0,'case_age_56' => 0,'case_age_57' => 0,'case_age_58' => 0,'case_age_59' => 0,
                                        'case_age_60' => 0,'case_age_61' => 0,'case_age_62' => 0,'case_age_63' => 0,'case_age_64' => 0,
                                        'case_age_65' => 0,'case_age_66' => 0,'case_age_67' => 0,'case_age_68' => 0,'case_age_69' => 0,
                                        'case_age_70' => 0,'case_age_71' => 0,'case_age_72' => 0,'case_age_73' => 0,'case_age_74' => 0,
                                        'case_age_75' => 0,'case_age_76' => 0,'case_age_77' => 0,'case_age_78' => 0,'case_age_79' => 0,
                                        'case_age_80' => 0,'case_age_81' => 0,'case_age_82' => 0,'case_age_83' => 0,'case_age_84' => 0,
                                        'case_age_85' => 0,'case_age_86' => 0,'case_age_87' => 0,'case_age_88' => 0,'case_age_89' => 0,
                                        'case_age_90' => 0,'case_age_91' => 0,'case_age_92' => 0,'case_age_93' => 0,'case_age_94' => 0,
                                        'case_age_95' => 0,'case_age_96' => 0,'case_age_97' => 0,'case_age_98' => 0,'case_age_99' => 0,
                                        'case_age_100' => 0
                                );
          }
        }
        return $excel_data;
  }
  public static function xls_patient_sick_by_age(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Col A1 Excel
    $excel_data[] = array('DPC','Reporting Area','age-0','age-1','age-2','age-3','age-4','age-5','age-6','age-7','age-8','age-9','age-10','age-11','age-12',
                          'age-13','age-14','age-15','age-16','age-17','age-18','age-19','age-20','age-21','age-22','age-23','age-24','age-25','age-26',
                          'age-27','age-28','age-29','age-30','age-31','age-32','age-33','age-34','age-35','age-36','age-37','age-38','age-39',
                          'age-40','age-41','age-42','age-43','age-44','age-45','age-46','age-47','age-48','age-49','age-50','age-51','age-52','age-53','age-54',
                          'age-55','age-56','age-57','age-58','age-59','age-60','age-61','age-62','age-63','age-64','age-65','age-66','age-67','age-68','age-69','age-70','age-71','age-72','age-73','age-74','age-75','age-76','age-77','age-78','age-79',
                          'age-80','age-81','age-82','age-83','age-84','age-85','age-86','age-87','age-88','age-89','age-90','age-91','age-91','age-93','age-94','age-95','age-96','age-97','age-98','age-99','age->=100'
                         );
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 0,1,0)) AS case_age_0,SUM(IF(RESULT <> 2 AND YEAR = 1,1,0)) AS case_age_1')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR <> 2,1,0)) AS case_age_2,SUM(IF(RESULT <> 2 AND YEAR = 3,1,0)) AS case_age_3')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 4,1,0)) AS case_age_4,SUM(IF(RESULT <> 2 AND YEAR = 5,1,0)) AS case_age_5')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 6,1,0)) AS case_age_6,SUM(IF(RESULT <> 2 AND YEAR = 7,1,0)) AS case_age_7')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 8,1,0)) AS case_age_8,SUM(IF(RESULT <> 2 AND YEAR = 9,1,0)) AS case_age_9')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 10,1,0)) AS case_age_10,SUM(IF(RESULT <> 2 AND YEAR = 11,1,0)) AS case_age_11')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 12,1,0)) AS case_age_12,SUM(IF(RESULT <> 2 AND YEAR = 13,1,0)) AS case_age_13')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 14,1,0)) AS case_age_14,SUM(IF(RESULT <> 2 AND YEAR = 15,1,0)) AS case_age_15')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 16,1,0)) AS case_age_16,SUM(IF(RESULT <> 2 AND YEAR = 17,1,0)) AS case_age_17')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 18,1,0)) AS case_age_18,SUM(IF(RESULT <> 2 AND YEAR = 19,1,0)) AS case_age_19')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 20,1,0)) AS case_age_20,SUM(IF(RESULT <> 2 AND YEAR = 21,1,0)) AS case_age_21')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 22,1,0)) AS case_age_22,SUM(IF(RESULT <> 2 AND YEAR = 23,1,0)) AS case_age_23')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 24,1,0)) AS case_age_24,SUM(IF(RESULT <> 2 AND YEAR = 25,1,0)) AS case_age_25')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 26,1,0)) AS case_age_26,SUM(IF(RESULT <> 2 AND YEAR = 27,1,0)) AS case_age_27')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 28,1,0)) AS case_age_28,SUM(IF(RESULT <> 2 AND YEAR = 29,1,0)) AS case_age_29')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 30,1,0)) AS case_age_30,SUM(IF(RESULT <> 2 AND YEAR = 31,1,0)) AS case_age_31')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 32,1,0)) AS case_age_32,SUM(IF(RESULT <> 2 AND YEAR = 33,1,0)) AS case_age_33')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 34,1,0)) AS case_age_34,SUM(IF(RESULT <> 2 AND YEAR = 35,1,0)) AS case_age_35')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 36,1,0)) AS case_age_36,SUM(IF(RESULT <> 2 AND YEAR = 37,1,0)) AS case_age_37')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 38,1,0)) AS case_age_38,SUM(IF(RESULT <> 2 AND YEAR = 39,1,0)) AS case_age_39')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 40,1,0)) AS case_age_40,SUM(IF(RESULT <> 2 AND YEAR = 41,1,0)) AS case_age_41')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 42,1,0)) AS case_age_42,SUM(IF(RESULT <> 2 AND YEAR = 43,1,0)) AS case_age_43')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 44,1,0)) AS case_age_44,SUM(IF(RESULT <> 2 AND YEAR = 45,1,0)) AS case_age_45')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 46,1,0)) AS case_age_46,SUM(IF(RESULT <> 2 AND YEAR = 47,1,0)) AS case_age_47')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 48,1,0)) AS case_age_48,SUM(IF(RESULT <> 2 AND YEAR = 49,1,0)) AS case_age_49')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 50,1,0)) AS case_age_50,SUM(IF(RESULT <> 2 AND YEAR = 51,1,0)) AS case_age_51')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 52,1,0)) AS case_age_52,SUM(IF(RESULT <> 2 AND YEAR = 53,1,0)) AS case_age_53')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 54,1,0)) AS case_age_54,SUM(IF(RESULT <> 2 AND YEAR = 55,1,0)) AS case_age_55')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 56,1,0)) AS case_age_56,SUM(IF(RESULT <> 2 AND YEAR = 57,1,0)) AS case_age_57')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 58,1,0)) AS case_age_58,SUM(IF(RESULT <> 2 AND YEAR = 59,1,0)) AS case_age_59')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 60,1,0)) AS case_age_60,SUM(IF(RESULT <> 2 AND YEAR = 61,1,0)) AS case_age_61')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 62,1,0)) AS case_age_62,SUM(IF(RESULT <> 2 AND YEAR = 63,1,0)) AS case_age_63')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 64,1,0)) AS case_age_64,SUM(IF(RESULT <> 2 AND YEAR = 65,1,0)) AS case_age_65')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 66,1,0)) AS case_age_66,SUM(IF(RESULT <> 2 AND YEAR = 67,1,0)) AS case_age_67')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 68,1,0)) AS case_age_68,SUM(IF(RESULT <> 2 AND YEAR = 69,1,0)) AS case_age_69')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 70,1,0)) AS case_age_70,SUM(IF(RESULT <> 2 AND YEAR = 71,1,0)) AS case_age_71')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 72,1,0)) AS case_age_72,SUM(IF(RESULT <> 2 AND YEAR = 73,1,0)) AS case_age_73')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 74,1,0)) AS case_age_74,SUM(IF(RESULT <> 2 AND YEAR = 75,1,0)) AS case_age_75')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 76,1,0)) AS case_age_76,SUM(IF(RESULT <> 2 AND YEAR = 77,1,0)) AS case_age_77')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 78,1,0)) AS case_age_78,SUM(IF(RESULT <> 2 AND YEAR = 79,1,0)) AS case_age_79')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 80,1,0)) AS case_age_80,SUM(IF(RESULT <> 2 AND YEAR = 81,1,0)) AS case_age_81')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 82,1,0)) AS case_age_82,SUM(IF(RESULT <> 2 AND YEAR = 83,1,0)) AS case_age_83')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 84,1,0)) AS case_age_84,SUM(IF(RESULT <> 2 AND YEAR = 85,1,0)) AS case_age_85')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 86,1,0)) AS case_age_86,SUM(IF(RESULT <> 2 AND YEAR = 87,1,0)) AS case_age_87')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 88,1,0)) AS case_age_88,SUM(IF(RESULT <> 2 AND YEAR = 89,1,0)) AS case_age_89')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 90,1,0)) AS case_age_90,SUM(IF(RESULT <> 2 AND YEAR = 91,1,0)) AS case_age_91')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 92,1,0)) AS case_age_92,SUM(IF(RESULT <> 2 AND YEAR = 93,1,0)) AS case_age_93')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 94,1,0)) AS case_age_94,SUM(IF(RESULT <> 2 AND YEAR = 95,1,0)) AS case_age_95')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 96,1,0)) AS case_age_96,SUM(IF(RESULT <> 2 AND YEAR = 97,1,0)) AS case_age_97')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 98,1,0)) AS case_age_98,SUM(IF(RESULT <> 2 AND YEAR = 99,1,0)) AS case_age_99')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR >= 100,1,0)) AS case_age_100')
      ->whereIn('DISEASE',$disease_code)
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }else{
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 0,1,0)) AS case_age_0,SUM(IF(RESULT <> 2 AND YEAR = 1,1,0)) AS case_age_1')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 2,1,0)) AS case_age_2,SUM(IF(RESULT <> 2 AND YEAR = 3,1,0)) AS case_age_3')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 4,1,0)) AS case_age_4,SUM(IF(RESULT <> 2 AND YEAR = 5,1,0)) AS case_age_5')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 6,1,0)) AS case_age_6,SUM(IF(RESULT <> 2 AND YEAR = 7,1,0)) AS case_age_7')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 8,1,0)) AS case_age_8,SUM(IF(RESULT <> 2 AND YEAR = 9,1,0)) AS case_age_9')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 10,1,0)) AS case_age_10,SUM(IF(RESULT <> 2 AND YEAR = 11,1,0)) AS case_age_11')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 12,1,0)) AS case_age_12,SUM(IF(RESULT <> 2 AND YEAR = 13,1,0)) AS case_age_13')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 14,1,0)) AS case_age_14,SUM(IF(RESULT <> 2 AND YEAR = 15,1,0)) AS case_age_15')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 16,1,0)) AS case_age_16,SUM(IF(RESULT <> 2 AND YEAR = 17,1,0)) AS case_age_17')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 18,1,0)) AS case_age_18,SUM(IF(RESULT <> 2 AND YEAR = 19,1,0)) AS case_age_19')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 20,1,0)) AS case_age_20,SUM(IF(RESULT <> 2 AND YEAR = 21,1,0)) AS case_age_21')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 22,1,0)) AS case_age_22,SUM(IF(RESULT <> 2 AND YEAR = 23,1,0)) AS case_age_23')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 24,1,0)) AS case_age_24,SUM(IF(RESULT <> 2 AND YEAR = 25,1,0)) AS case_age_25')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 26,1,0)) AS case_age_26,SUM(IF(RESULT <> 2 AND YEAR = 27,1,0)) AS case_age_27')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 28,1,0)) AS case_age_28,SUM(IF(RESULT <> 2 AND YEAR = 29,1,0)) AS case_age_29')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 30,1,0)) AS case_age_30,SUM(IF(RESULT <> 2 AND YEAR = 31,1,0)) AS case_age_31')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 32,1,0)) AS case_age_32,SUM(IF(RESULT <> 2 AND YEAR = 33,1,0)) AS case_age_33')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 34,1,0)) AS case_age_34,SUM(IF(RESULT <> 2 AND YEAR = 35,1,0)) AS case_age_35')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 36,1,0)) AS case_age_36,SUM(IF(RESULT <> 2 AND YEAR = 37,1,0)) AS case_age_37')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 38,1,0)) AS case_age_38,SUM(IF(RESULT <> 2 AND YEAR = 39,1,0)) AS case_age_39')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 40,1,0)) AS case_age_40,SUM(IF(RESULT <> 2 AND YEAR = 41,1,0)) AS case_age_41')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 42,1,0)) AS case_age_42,SUM(IF(RESULT <> 2 AND YEAR = 43,1,0)) AS case_age_43')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 44,1,0)) AS case_age_44,SUM(IF(RESULT <> 2 AND YEAR = 45,1,0)) AS case_age_45')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 46,1,0)) AS case_age_46,SUM(IF(RESULT <> 2 AND YEAR = 47,1,0)) AS case_age_47')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 48,1,0)) AS case_age_48,SUM(IF(RESULT <> 2 AND YEAR = 49,1,0)) AS case_age_49')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 50,1,0)) AS case_age_50,SUM(IF(RESULT <> 2 AND YEAR = 51,1,0)) AS case_age_51')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 52,1,0)) AS case_age_52,SUM(IF(RESULT <> 2 AND YEAR = 53,1,0)) AS case_age_53')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 54,1,0)) AS case_age_54,SUM(IF(RESULT <> 2 AND YEAR = 55,1,0)) AS case_age_55')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 56,1,0)) AS case_age_56,SUM(IF(RESULT <> 2 AND YEAR = 57,1,0)) AS case_age_57')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 58,1,0)) AS case_age_58,SUM(IF(RESULT <> 2 AND YEAR = 59,1,0)) AS case_age_59')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 60,1,0)) AS case_age_60,SUM(IF(RESULT <> 2 AND YEAR = 61,1,0)) AS case_age_61')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 62,1,0)) AS case_age_62,SUM(IF(RESULT <> 2 AND YEAR = 63,1,0)) AS case_age_63')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 64,1,0)) AS case_age_64,SUM(IF(RESULT <> 2 AND YEAR = 65,1,0)) AS case_age_65')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 66,1,0)) AS case_age_66,SUM(IF(RESULT <> 2 AND YEAR = 67,1,0)) AS case_age_67')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 68,1,0)) AS case_age_68,SUM(IF(RESULT <> 2 AND YEAR = 69,1,0)) AS case_age_69')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 70,1,0)) AS case_age_70,SUM(IF(RESULT <> 2 AND YEAR = 71,1,0)) AS case_age_71')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 72,1,0)) AS case_age_72,SUM(IF(RESULT <> 2 AND YEAR = 73,1,0)) AS case_age_73')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 74,1,0)) AS case_age_74,SUM(IF(RESULT <> 2 AND YEAR = 75,1,0)) AS case_age_75')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 76,1,0)) AS case_age_76,SUM(IF(RESULT <> 2 AND YEAR = 77,1,0)) AS case_age_77')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 78,1,0)) AS case_age_78,SUM(IF(RESULT <> 2 AND YEAR = 79,1,0)) AS case_age_79')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 80,1,0)) AS case_age_80,SUM(IF(RESULT <> 2 AND YEAR = 81,1,0)) AS case_age_81')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 82,1,0)) AS case_age_82,SUM(IF(RESULT <> 2 AND YEAR = 83,1,0)) AS case_age_83')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 84,1,0)) AS case_age_84,SUM(IF(RESULT <> 2 AND YEAR = 85,1,0)) AS case_age_85')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 86,1,0)) AS case_age_86,SUM(IF(RESULT <> 2 AND YEAR = 87,1,0)) AS case_age_87')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 88,1,0)) AS case_age_88,SUM(IF(RESULT <> 2 AND YEAR = 89,1,0)) AS case_age_89')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 90,1,0)) AS case_age_90,SUM(IF(RESULT <> 2 AND YEAR = 91,1,0)) AS case_age_91')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 92,1,0)) AS case_age_92,SUM(IF(RESULT <> 2 AND YEAR = 93,1,0)) AS case_age_93')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 94,1,0)) AS case_age_94,SUM(IF(RESULT <> 2 AND YEAR = 95,1,0)) AS case_age_95')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 96,1,0)) AS case_age_96,SUM(IF(RESULT <> 2 AND YEAR = 97,1,0)) AS case_age_97')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR = 98,1,0)) AS case_age_98,SUM(IF(RESULT <> 2 AND YEAR = 99,1,0)) AS case_age_99')
      ->selectRaw('SUM(IF(RESULT <> 2 AND YEAR >= 100,1,0)) AS case_age_100')
      ->where('DISEASE','=',$disease_code['0'])
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }

      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['case_age_0'] = $val->case_age_0;
        $pt_data[$val->PROVINCE]['case_age_1'] = $val->case_age_1;
        $pt_data[$val->PROVINCE]['case_age_2'] = $val->case_age_2;
        $pt_data[$val->PROVINCE]['case_age_3'] = $val->case_age_3;
        $pt_data[$val->PROVINCE]['case_age_4'] = $val->case_age_4;
        $pt_data[$val->PROVINCE]['case_age_5'] = $val->case_age_5;
        $pt_data[$val->PROVINCE]['case_age_6'] = $val->case_age_6;
        $pt_data[$val->PROVINCE]['case_age_7'] = $val->case_age_7;
        $pt_data[$val->PROVINCE]['case_age_8'] = $val->case_age_8;
        $pt_data[$val->PROVINCE]['case_age_9'] = $val->case_age_9;
        $pt_data[$val->PROVINCE]['case_age_10'] = $val->case_age_10;
        $pt_data[$val->PROVINCE]['case_age_11'] = $val->case_age_11;
        $pt_data[$val->PROVINCE]['case_age_12'] = $val->case_age_12;
        $pt_data[$val->PROVINCE]['case_age_13'] = $val->case_age_13;
        $pt_data[$val->PROVINCE]['case_age_14'] = $val->case_age_14;
        $pt_data[$val->PROVINCE]['case_age_15'] = $val->case_age_15;
        $pt_data[$val->PROVINCE]['case_age_16'] = $val->case_age_16;
        $pt_data[$val->PROVINCE]['case_age_17'] = $val->case_age_17;
        $pt_data[$val->PROVINCE]['case_age_18'] = $val->case_age_18;
        $pt_data[$val->PROVINCE]['case_age_19'] = $val->case_age_19;
        $pt_data[$val->PROVINCE]['case_age_20'] = $val->case_age_20;
        $pt_data[$val->PROVINCE]['case_age_21'] = $val->case_age_21;
        $pt_data[$val->PROVINCE]['case_age_22'] = $val->case_age_22;
        $pt_data[$val->PROVINCE]['case_age_23'] = $val->case_age_23;
        $pt_data[$val->PROVINCE]['case_age_24'] = $val->case_age_24;
        $pt_data[$val->PROVINCE]['case_age_25'] = $val->case_age_25;
        $pt_data[$val->PROVINCE]['case_age_26'] = $val->case_age_26;
        $pt_data[$val->PROVINCE]['case_age_27'] = $val->case_age_27;
        $pt_data[$val->PROVINCE]['case_age_28'] = $val->case_age_28;
        $pt_data[$val->PROVINCE]['case_age_29'] = $val->case_age_29;
        $pt_data[$val->PROVINCE]['case_age_30'] = $val->case_age_30;
        $pt_data[$val->PROVINCE]['case_age_31'] = $val->case_age_31;
        $pt_data[$val->PROVINCE]['case_age_32'] = $val->case_age_32;
        $pt_data[$val->PROVINCE]['case_age_33'] = $val->case_age_33;
        $pt_data[$val->PROVINCE]['case_age_34'] = $val->case_age_34;
        $pt_data[$val->PROVINCE]['case_age_35'] = $val->case_age_35;
        $pt_data[$val->PROVINCE]['case_age_36'] = $val->case_age_36;
        $pt_data[$val->PROVINCE]['case_age_37'] = $val->case_age_37;
        $pt_data[$val->PROVINCE]['case_age_38'] = $val->case_age_38;
        $pt_data[$val->PROVINCE]['case_age_39'] = $val->case_age_39;
        $pt_data[$val->PROVINCE]['case_age_40'] = $val->case_age_40;
        $pt_data[$val->PROVINCE]['case_age_41'] = $val->case_age_41;
        $pt_data[$val->PROVINCE]['case_age_42'] = $val->case_age_42;
        $pt_data[$val->PROVINCE]['case_age_43'] = $val->case_age_43;
        $pt_data[$val->PROVINCE]['case_age_44'] = $val->case_age_44;
        $pt_data[$val->PROVINCE]['case_age_45'] = $val->case_age_45;
        $pt_data[$val->PROVINCE]['case_age_46'] = $val->case_age_46;
        $pt_data[$val->PROVINCE]['case_age_47'] = $val->case_age_47;
        $pt_data[$val->PROVINCE]['case_age_48'] = $val->case_age_48;
        $pt_data[$val->PROVINCE]['case_age_49'] = $val->case_age_49;
        $pt_data[$val->PROVINCE]['case_age_50'] = $val->case_age_50;
        $pt_data[$val->PROVINCE]['case_age_51'] = $val->case_age_51;
        $pt_data[$val->PROVINCE]['case_age_52'] = $val->case_age_52;
        $pt_data[$val->PROVINCE]['case_age_53'] = $val->case_age_53;
        $pt_data[$val->PROVINCE]['case_age_54'] = $val->case_age_54;
        $pt_data[$val->PROVINCE]['case_age_55'] = $val->case_age_55;
        $pt_data[$val->PROVINCE]['case_age_56'] = $val->case_age_56;
        $pt_data[$val->PROVINCE]['case_age_57'] = $val->case_age_57;
        $pt_data[$val->PROVINCE]['case_age_58'] = $val->case_age_58;
        $pt_data[$val->PROVINCE]['case_age_59'] = $val->case_age_59;
        $pt_data[$val->PROVINCE]['case_age_60'] = $val->case_age_60;
        $pt_data[$val->PROVINCE]['case_age_61'] = $val->case_age_61;
        $pt_data[$val->PROVINCE]['case_age_62'] = $val->case_age_62;
        $pt_data[$val->PROVINCE]['case_age_63'] = $val->case_age_63;
        $pt_data[$val->PROVINCE]['case_age_64'] = $val->case_age_64;
        $pt_data[$val->PROVINCE]['case_age_65'] = $val->case_age_65;
        $pt_data[$val->PROVINCE]['case_age_66'] = $val->case_age_66;
        $pt_data[$val->PROVINCE]['case_age_67'] = $val->case_age_67;
        $pt_data[$val->PROVINCE]['case_age_68'] = $val->case_age_68;
        $pt_data[$val->PROVINCE]['case_age_69'] = $val->case_age_69;
        $pt_data[$val->PROVINCE]['case_age_70'] = $val->case_age_70;
        $pt_data[$val->PROVINCE]['case_age_71'] = $val->case_age_71;
        $pt_data[$val->PROVINCE]['case_age_72'] = $val->case_age_72;
        $pt_data[$val->PROVINCE]['case_age_73'] = $val->case_age_73;
        $pt_data[$val->PROVINCE]['case_age_74'] = $val->case_age_74;
        $pt_data[$val->PROVINCE]['case_age_75'] = $val->case_age_75;
        $pt_data[$val->PROVINCE]['case_age_76'] = $val->case_age_76;
        $pt_data[$val->PROVINCE]['case_age_77'] = $val->case_age_77;
        $pt_data[$val->PROVINCE]['case_age_78'] = $val->case_age_78;
        $pt_data[$val->PROVINCE]['case_age_79'] = $val->case_age_79;
        $pt_data[$val->PROVINCE]['case_age_80'] = $val->case_age_80;
        $pt_data[$val->PROVINCE]['case_age_81'] = $val->case_age_81;
        $pt_data[$val->PROVINCE]['case_age_82'] = $val->case_age_82;
        $pt_data[$val->PROVINCE]['case_age_83'] = $val->case_age_83;
        $pt_data[$val->PROVINCE]['case_age_84'] = $val->case_age_84;
        $pt_data[$val->PROVINCE]['case_age_85'] = $val->case_age_85;
        $pt_data[$val->PROVINCE]['case_age_86'] = $val->case_age_86;
        $pt_data[$val->PROVINCE]['case_age_87'] = $val->case_age_87;
        $pt_data[$val->PROVINCE]['case_age_88'] = $val->case_age_88;
        $pt_data[$val->PROVINCE]['case_age_89'] = $val->case_age_89;
        $pt_data[$val->PROVINCE]['case_age_90'] = $val->case_age_90;
        $pt_data[$val->PROVINCE]['case_age_91'] = $val->case_age_91;
        $pt_data[$val->PROVINCE]['case_age_92'] = $val->case_age_92;
        $pt_data[$val->PROVINCE]['case_age_93'] = $val->case_age_93;
        $pt_data[$val->PROVINCE]['case_age_94'] = $val->case_age_94;
        $pt_data[$val->PROVINCE]['case_age_95'] = $val->case_age_95;
        $pt_data[$val->PROVINCE]['case_age_96'] = $val->case_age_96;
        $pt_data[$val->PROVINCE]['case_age_97'] = $val->case_age_97;
        $pt_data[$val->PROVINCE]['case_age_98'] = $val->case_age_98;
        $pt_data[$val->PROVINCE]['case_age_99'] = $val->case_age_99;
        $pt_data[$val->PROVINCE]['case_age_100'] = $val->case_age_100;

      }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'case_age_0' => "0",'case_age_1' => "0",'case_age_2' => "0",'case_age_3' => "0",'case_age_4' => "0",
                                        'case_age_5' => "0",'case_age_6' => "0",'case_age_7' => "0",'case_age_8' => "0",'case_age_9' => "0",
                                        'case_age_10' => "0",'case_age_11' => "0",'case_age_12' => "0",'case_age_13' => "0",'case_age_14' => "0",
                                        'case_age_15' => "0",'case_age_16' => "0",'case_age_17' => "0",'case_age_18' => "0",'case_age_19' => "0",
                                        'case_age_20' => "0",'case_age_21' => "0",'case_age_22' => "0",'case_age_23' => "0",'case_age_24' => "0",
                                        'case_age_25' => "0",'case_age_26' => "0",'case_age_27' => "0",'case_age_28' => "0",'case_age_29' => "0",
                                        'case_age_30' => "0",'case_age_31' => "0",'case_age_32' => "0",'case_age_33' => "0",'case_age_34' => "0",
                                        'case_age_35' => "0",'case_age_36' => "0",'case_age_37' => "0",'case_age_38' => "0",'case_age_39' => "0",
                                        'case_age_40' => "0",'case_age_41' => "0",'case_age_42' => "0",'case_age_43' => "0",'case_age_44' => "0",
                                        'case_age_45' => "0",'case_age_46' => "0",'case_age_47' => "0",'case_age_48' => "0",'case_age_49' => "0",
                                        'case_age_50' => "0",'case_age_51' => "0",'case_age_52' => "0",'case_age_53' => "0",'case_age_54' => "0",
                                        'case_age_55' => "0",'case_age_56' => "0",'case_age_57' => "0",'case_age_58' => "0",'case_age_59' => "0",
                                        'case_age_60' => "0",'case_age_61' => "0",'case_age_62' => "0",'case_age_63' => "0",'case_age_64' => "0",
                                        'case_age_65' => "0",'case_age_66' => "0",'case_age_67' => "0",'case_age_68' => "0",'case_age_69' => "0",
                                        'case_age_70' => "0",'case_age_71' => "0",'case_age_72' => "0",'case_age_73' => "0",'case_age_74' => "0",
                                        'case_age_75' => "0",'case_age_76' => "0",'case_age_77' => "0",'case_age_78' => "0",'case_age_79' => "0",
                                        'case_age_80' => "0",'case_age_81' => "0",'case_age_82' => "0",'case_age_83' => "0",'case_age_84' => "0",
                                        'case_age_85' => "0",'case_age_86' => "0",'case_age_87' => "0",'case_age_88' => "0",'case_age_89' => "0",
                                        'case_age_90' => "0",'case_age_91' => "0",'case_age_92' => "0",'case_age_93' => "0",'case_age_94' => "0",
                                        'case_age_95' => "0",'case_age_96' => "0",'case_age_97' => "0",'case_age_98' => "0",'case_age_99' => "0",
                                        'case_age_100' => 0
                                );
          }
        }
        //Year to DC
        $year_th = $tblYear+543;
        //filename
        $filename = 'sick-age'.'-year-'.$year_th;
        //sheetname
        $sheetname = 'sheet1';

        // header text
        $header_text = "ตารางข้อมูลจำนวนป่วย ตามอายุ จำแนกรายจังหวัด โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
         })->download('csv');
  }
  public static function get_patient_death_by_age($select_year,$disease_code){
    $tblYear = (isset($select_year))? $select_year : date('Y')-1;
    $post_disease_code = (isset($disease_code))? $disease_code : "01";
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 0,1,0)) AS death_age_0,SUM(IF(RESULT = 2 AND YEAR = 1,1,0)) AS death_age_1')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR <> 2,1,0)) AS death_age_2,SUM(IF(RESULT = 2 AND YEAR = 3,1,0)) AS death_age_3')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 4,1,0)) AS death_age_4,SUM(IF(RESULT = 2 AND YEAR = 5,1,0)) AS death_age_5')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 6,1,0)) AS death_age_6,SUM(IF(RESULT = 2 AND YEAR = 7,1,0)) AS death_age_7')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 8,1,0)) AS death_age_8,SUM(IF(RESULT = 2 AND YEAR = 9,1,0)) AS death_age_9')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 10,1,0)) AS death_age_10,SUM(IF(RESULT = 2 AND YEAR = 11,1,0)) AS death_age_11')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 12,1,0)) AS death_age_12,SUM(IF(RESULT = 2 AND YEAR = 13,1,0)) AS death_age_13')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 14,1,0)) AS death_age_14,SUM(IF(RESULT = 2 AND YEAR = 15,1,0)) AS death_age_15')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 16,1,0)) AS death_age_16,SUM(IF(RESULT = 2 AND YEAR = 17,1,0)) AS death_age_17')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 18,1,0)) AS death_age_18,SUM(IF(RESULT = 2 AND YEAR = 19,1,0)) AS death_age_19')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 20,1,0)) AS death_age_20,SUM(IF(RESULT = 2 AND YEAR = 21,1,0)) AS death_age_21')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 22,1,0)) AS death_age_22,SUM(IF(RESULT = 2 AND YEAR = 23,1,0)) AS death_age_23')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 24,1,0)) AS death_age_24,SUM(IF(RESULT = 2 AND YEAR = 25,1,0)) AS death_age_25')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 26,1,0)) AS death_age_26,SUM(IF(RESULT = 2 AND YEAR = 27,1,0)) AS death_age_27')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 28,1,0)) AS death_age_28,SUM(IF(RESULT = 2 AND YEAR = 29,1,0)) AS death_age_29')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 30,1,0)) AS death_age_30,SUM(IF(RESULT = 2 AND YEAR = 31,1,0)) AS death_age_31')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 32,1,0)) AS death_age_32,SUM(IF(RESULT = 2 AND YEAR = 33,1,0)) AS death_age_33')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 34,1,0)) AS death_age_34,SUM(IF(RESULT = 2 AND YEAR = 35,1,0)) AS death_age_35')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 36,1,0)) AS death_age_36,SUM(IF(RESULT = 2 AND YEAR = 37,1,0)) AS death_age_37')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 38,1,0)) AS death_age_38,SUM(IF(RESULT = 2 AND YEAR = 39,1,0)) AS death_age_39')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 40,1,0)) AS death_age_40,SUM(IF(RESULT = 2 AND YEAR = 41,1,0)) AS death_age_41')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 42,1,0)) AS death_age_42,SUM(IF(RESULT = 2 AND YEAR = 43,1,0)) AS death_age_43')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 44,1,0)) AS death_age_44,SUM(IF(RESULT = 2 AND YEAR = 45,1,0)) AS death_age_45')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 46,1,0)) AS death_age_46,SUM(IF(RESULT = 2 AND YEAR = 47,1,0)) AS death_age_47')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 48,1,0)) AS death_age_48,SUM(IF(RESULT = 2 AND YEAR = 49,1,0)) AS death_age_49')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 50,1,0)) AS death_age_50,SUM(IF(RESULT = 2 AND YEAR = 51,1,0)) AS death_age_51')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 52,1,0)) AS death_age_52,SUM(IF(RESULT = 2 AND YEAR = 53,1,0)) AS death_age_53')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 54,1,0)) AS death_age_54,SUM(IF(RESULT = 2 AND YEAR = 55,1,0)) AS death_age_55')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 56,1,0)) AS death_age_56,SUM(IF(RESULT = 2 AND YEAR = 57,1,0)) AS death_age_57')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 58,1,0)) AS death_age_58,SUM(IF(RESULT = 2 AND YEAR = 59,1,0)) AS death_age_59')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 60,1,0)) AS death_age_60,SUM(IF(RESULT = 2 AND YEAR = 61,1,0)) AS death_age_61')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 62,1,0)) AS death_age_62,SUM(IF(RESULT = 2 AND YEAR = 63,1,0)) AS death_age_63')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 64,1,0)) AS death_age_64,SUM(IF(RESULT = 2 AND YEAR = 65,1,0)) AS death_age_65')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 66,1,0)) AS death_age_66,SUM(IF(RESULT = 2 AND YEAR = 67,1,0)) AS death_age_67')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 68,1,0)) AS death_age_68,SUM(IF(RESULT = 2 AND YEAR = 69,1,0)) AS death_age_69')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 70,1,0)) AS death_age_70,SUM(IF(RESULT = 2 AND YEAR = 71,1,0)) AS death_age_71')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 72,1,0)) AS death_age_72,SUM(IF(RESULT = 2 AND YEAR = 73,1,0)) AS death_age_73')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 74,1,0)) AS death_age_74,SUM(IF(RESULT = 2 AND YEAR = 75,1,0)) AS death_age_75')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 76,1,0)) AS death_age_76,SUM(IF(RESULT = 2 AND YEAR = 77,1,0)) AS death_age_77')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 78,1,0)) AS death_age_78,SUM(IF(RESULT = 2 AND YEAR = 79,1,0)) AS death_age_79')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 80,1,0)) AS death_age_80,SUM(IF(RESULT = 2 AND YEAR = 81,1,0)) AS death_age_81')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 82,1,0)) AS death_age_82,SUM(IF(RESULT = 2 AND YEAR = 83,1,0)) AS death_age_83')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 84,1,0)) AS death_age_84,SUM(IF(RESULT = 2 AND YEAR = 85,1,0)) AS death_age_85')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 86,1,0)) AS death_age_86,SUM(IF(RESULT = 2 AND YEAR = 87,1,0)) AS death_age_87')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 88,1,0)) AS death_age_88,SUM(IF(RESULT = 2 AND YEAR = 89,1,0)) AS death_age_89')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 90,1,0)) AS death_age_90,SUM(IF(RESULT = 2 AND YEAR = 91,1,0)) AS death_age_91')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 92,1,0)) AS death_age_92,SUM(IF(RESULT = 2 AND YEAR = 93,1,0)) AS death_age_93')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 94,1,0)) AS death_age_94,SUM(IF(RESULT = 2 AND YEAR = 95,1,0)) AS death_age_95')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 96,1,0)) AS death_age_96,SUM(IF(RESULT = 2 AND YEAR = 97,1,0)) AS death_age_97')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 98,1,0)) AS death_age_98,SUM(IF(RESULT = 2 AND YEAR = 99,1,0)) AS death_age_99')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR >= 100,1,0)) AS death_age_100')
      ->whereIn('DISEASE',$disease_code)
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }else{
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 0,1,0)) AS death_age_0,SUM(IF(RESULT = 2 AND YEAR = 1,1,0)) AS death_age_1')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 2,1,0)) AS death_age_2,SUM(IF(RESULT = 2 AND YEAR = 3,1,0)) AS death_age_3')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 4,1,0)) AS death_age_4,SUM(IF(RESULT = 2 AND YEAR = 5,1,0)) AS death_age_5')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 6,1,0)) AS death_age_6,SUM(IF(RESULT = 2 AND YEAR = 7,1,0)) AS death_age_7')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 8,1,0)) AS death_age_8,SUM(IF(RESULT = 2 AND YEAR = 9,1,0)) AS death_age_9')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 10,1,0)) AS death_age_10,SUM(IF(RESULT = 2 AND YEAR = 11,1,0)) AS death_age_11')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 12,1,0)) AS death_age_12,SUM(IF(RESULT = 2 AND YEAR = 13,1,0)) AS death_age_13')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 14,1,0)) AS death_age_14,SUM(IF(RESULT = 2 AND YEAR = 15,1,0)) AS death_age_15')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 16,1,0)) AS death_age_16,SUM(IF(RESULT = 2 AND YEAR = 17,1,0)) AS death_age_17')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 18,1,0)) AS death_age_18,SUM(IF(RESULT = 2 AND YEAR = 19,1,0)) AS death_age_19')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 20,1,0)) AS death_age_20,SUM(IF(RESULT = 2 AND YEAR = 21,1,0)) AS death_age_21')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 22,1,0)) AS death_age_22,SUM(IF(RESULT = 2 AND YEAR = 23,1,0)) AS death_age_23')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 24,1,0)) AS death_age_24,SUM(IF(RESULT = 2 AND YEAR = 25,1,0)) AS death_age_25')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 26,1,0)) AS death_age_26,SUM(IF(RESULT = 2 AND YEAR = 27,1,0)) AS death_age_27')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 28,1,0)) AS death_age_28,SUM(IF(RESULT = 2 AND YEAR = 29,1,0)) AS death_age_29')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 30,1,0)) AS death_age_30,SUM(IF(RESULT = 2 AND YEAR = 31,1,0)) AS death_age_31')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 32,1,0)) AS death_age_32,SUM(IF(RESULT = 2 AND YEAR = 33,1,0)) AS death_age_33')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 34,1,0)) AS death_age_34,SUM(IF(RESULT = 2 AND YEAR = 35,1,0)) AS death_age_35')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 36,1,0)) AS death_age_36,SUM(IF(RESULT = 2 AND YEAR = 37,1,0)) AS death_age_37')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 38,1,0)) AS death_age_38,SUM(IF(RESULT = 2 AND YEAR = 39,1,0)) AS death_age_39')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 40,1,0)) AS death_age_40,SUM(IF(RESULT = 2 AND YEAR = 41,1,0)) AS death_age_41')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 42,1,0)) AS death_age_42,SUM(IF(RESULT = 2 AND YEAR = 43,1,0)) AS death_age_43')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 44,1,0)) AS death_age_44,SUM(IF(RESULT = 2 AND YEAR = 45,1,0)) AS death_age_45')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 46,1,0)) AS death_age_46,SUM(IF(RESULT = 2 AND YEAR = 47,1,0)) AS death_age_47')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 48,1,0)) AS death_age_48,SUM(IF(RESULT = 2 AND YEAR = 49,1,0)) AS death_age_49')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 50,1,0)) AS death_age_50,SUM(IF(RESULT = 2 AND YEAR = 51,1,0)) AS death_age_51')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 52,1,0)) AS death_age_52,SUM(IF(RESULT = 2 AND YEAR = 53,1,0)) AS death_age_53')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 54,1,0)) AS death_age_54,SUM(IF(RESULT = 2 AND YEAR = 55,1,0)) AS death_age_55')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 56,1,0)) AS death_age_56,SUM(IF(RESULT = 2 AND YEAR = 57,1,0)) AS death_age_57')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 58,1,0)) AS death_age_58,SUM(IF(RESULT = 2 AND YEAR = 59,1,0)) AS death_age_59')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 60,1,0)) AS death_age_60,SUM(IF(RESULT = 2 AND YEAR = 61,1,0)) AS death_age_61')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 62,1,0)) AS death_age_62,SUM(IF(RESULT = 2 AND YEAR = 63,1,0)) AS death_age_63')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 64,1,0)) AS death_age_64,SUM(IF(RESULT = 2 AND YEAR = 65,1,0)) AS death_age_65')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 66,1,0)) AS death_age_66,SUM(IF(RESULT = 2 AND YEAR = 67,1,0)) AS death_age_67')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 68,1,0)) AS death_age_68,SUM(IF(RESULT = 2 AND YEAR = 69,1,0)) AS death_age_69')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 70,1,0)) AS death_age_70,SUM(IF(RESULT = 2 AND YEAR = 71,1,0)) AS death_age_71')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 72,1,0)) AS death_age_72,SUM(IF(RESULT = 2 AND YEAR = 73,1,0)) AS death_age_73')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 74,1,0)) AS death_age_74,SUM(IF(RESULT = 2 AND YEAR = 75,1,0)) AS death_age_75')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 76,1,0)) AS death_age_76,SUM(IF(RESULT = 2 AND YEAR = 77,1,0)) AS death_age_77')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 78,1,0)) AS death_age_78,SUM(IF(RESULT = 2 AND YEAR = 79,1,0)) AS death_age_79')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 80,1,0)) AS death_age_80,SUM(IF(RESULT = 2 AND YEAR = 81,1,0)) AS death_age_81')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 82,1,0)) AS death_age_82,SUM(IF(RESULT = 2 AND YEAR = 83,1,0)) AS death_age_83')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 84,1,0)) AS death_age_84,SUM(IF(RESULT = 2 AND YEAR = 85,1,0)) AS death_age_85')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 86,1,0)) AS death_age_86,SUM(IF(RESULT = 2 AND YEAR = 87,1,0)) AS death_age_87')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 88,1,0)) AS death_age_88,SUM(IF(RESULT = 2 AND YEAR = 89,1,0)) AS death_age_89')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 90,1,0)) AS death_age_90,SUM(IF(RESULT = 2 AND YEAR = 91,1,0)) AS death_age_91')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 92,1,0)) AS death_age_92,SUM(IF(RESULT = 2 AND YEAR = 93,1,0)) AS death_age_93')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 94,1,0)) AS death_age_94,SUM(IF(RESULT = 2 AND YEAR = 95,1,0)) AS death_age_95')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 96,1,0)) AS death_age_96,SUM(IF(RESULT = 2 AND YEAR = 97,1,0)) AS death_age_97')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 98,1,0)) AS death_age_98,SUM(IF(RESULT = 2 AND YEAR = 99,1,0)) AS death_age_99')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR >= 100,1,0)) AS death_age_100')
      ->where('DISEASE','=',$disease_code['0'])
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }

      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['death_age_0'] = $val->death_age_0;
        $pt_data[$val->PROVINCE]['death_age_1'] = $val->death_age_1;
        $pt_data[$val->PROVINCE]['death_age_2'] = $val->death_age_2;
        $pt_data[$val->PROVINCE]['death_age_3'] = $val->death_age_3;
        $pt_data[$val->PROVINCE]['death_age_4'] = $val->death_age_4;
        $pt_data[$val->PROVINCE]['death_age_5'] = $val->death_age_5;
        $pt_data[$val->PROVINCE]['death_age_6'] = $val->death_age_6;
        $pt_data[$val->PROVINCE]['death_age_7'] = $val->death_age_7;
        $pt_data[$val->PROVINCE]['death_age_8'] = $val->death_age_8;
        $pt_data[$val->PROVINCE]['death_age_9'] = $val->death_age_9;
        $pt_data[$val->PROVINCE]['death_age_10'] = $val->death_age_10;
        $pt_data[$val->PROVINCE]['death_age_11'] = $val->death_age_11;
        $pt_data[$val->PROVINCE]['death_age_12'] = $val->death_age_12;
        $pt_data[$val->PROVINCE]['death_age_13'] = $val->death_age_13;
        $pt_data[$val->PROVINCE]['death_age_14'] = $val->death_age_14;
        $pt_data[$val->PROVINCE]['death_age_15'] = $val->death_age_15;
        $pt_data[$val->PROVINCE]['death_age_16'] = $val->death_age_16;
        $pt_data[$val->PROVINCE]['death_age_17'] = $val->death_age_17;
        $pt_data[$val->PROVINCE]['death_age_18'] = $val->death_age_18;
        $pt_data[$val->PROVINCE]['death_age_19'] = $val->death_age_19;
        $pt_data[$val->PROVINCE]['death_age_20'] = $val->death_age_20;
        $pt_data[$val->PROVINCE]['death_age_21'] = $val->death_age_21;
        $pt_data[$val->PROVINCE]['death_age_22'] = $val->death_age_22;
        $pt_data[$val->PROVINCE]['death_age_23'] = $val->death_age_23;
        $pt_data[$val->PROVINCE]['death_age_24'] = $val->death_age_24;
        $pt_data[$val->PROVINCE]['death_age_25'] = $val->death_age_25;
        $pt_data[$val->PROVINCE]['death_age_26'] = $val->death_age_26;
        $pt_data[$val->PROVINCE]['death_age_27'] = $val->death_age_27;
        $pt_data[$val->PROVINCE]['death_age_28'] = $val->death_age_28;
        $pt_data[$val->PROVINCE]['death_age_29'] = $val->death_age_29;
        $pt_data[$val->PROVINCE]['death_age_30'] = $val->death_age_30;
        $pt_data[$val->PROVINCE]['death_age_31'] = $val->death_age_31;
        $pt_data[$val->PROVINCE]['death_age_32'] = $val->death_age_32;
        $pt_data[$val->PROVINCE]['death_age_33'] = $val->death_age_33;
        $pt_data[$val->PROVINCE]['death_age_34'] = $val->death_age_34;
        $pt_data[$val->PROVINCE]['death_age_35'] = $val->death_age_35;
        $pt_data[$val->PROVINCE]['death_age_36'] = $val->death_age_36;
        $pt_data[$val->PROVINCE]['death_age_37'] = $val->death_age_37;
        $pt_data[$val->PROVINCE]['death_age_38'] = $val->death_age_38;
        $pt_data[$val->PROVINCE]['death_age_39'] = $val->death_age_39;
        $pt_data[$val->PROVINCE]['death_age_40'] = $val->death_age_40;
        $pt_data[$val->PROVINCE]['death_age_41'] = $val->death_age_41;
        $pt_data[$val->PROVINCE]['death_age_42'] = $val->death_age_42;
        $pt_data[$val->PROVINCE]['death_age_43'] = $val->death_age_43;
        $pt_data[$val->PROVINCE]['death_age_44'] = $val->death_age_44;
        $pt_data[$val->PROVINCE]['death_age_45'] = $val->death_age_45;
        $pt_data[$val->PROVINCE]['death_age_46'] = $val->death_age_46;
        $pt_data[$val->PROVINCE]['death_age_47'] = $val->death_age_47;
        $pt_data[$val->PROVINCE]['death_age_48'] = $val->death_age_48;
        $pt_data[$val->PROVINCE]['death_age_49'] = $val->death_age_49;
        $pt_data[$val->PROVINCE]['death_age_50'] = $val->death_age_50;
        $pt_data[$val->PROVINCE]['death_age_51'] = $val->death_age_51;
        $pt_data[$val->PROVINCE]['death_age_52'] = $val->death_age_52;
        $pt_data[$val->PROVINCE]['death_age_53'] = $val->death_age_53;
        $pt_data[$val->PROVINCE]['death_age_54'] = $val->death_age_54;
        $pt_data[$val->PROVINCE]['death_age_55'] = $val->death_age_55;
        $pt_data[$val->PROVINCE]['death_age_56'] = $val->death_age_56;
        $pt_data[$val->PROVINCE]['death_age_57'] = $val->death_age_57;
        $pt_data[$val->PROVINCE]['death_age_58'] = $val->death_age_58;
        $pt_data[$val->PROVINCE]['death_age_59'] = $val->death_age_59;
        $pt_data[$val->PROVINCE]['death_age_60'] = $val->death_age_60;
        $pt_data[$val->PROVINCE]['death_age_61'] = $val->death_age_61;
        $pt_data[$val->PROVINCE]['death_age_62'] = $val->death_age_62;
        $pt_data[$val->PROVINCE]['death_age_63'] = $val->death_age_63;
        $pt_data[$val->PROVINCE]['death_age_64'] = $val->death_age_64;
        $pt_data[$val->PROVINCE]['death_age_65'] = $val->death_age_65;
        $pt_data[$val->PROVINCE]['death_age_66'] = $val->death_age_66;
        $pt_data[$val->PROVINCE]['death_age_67'] = $val->death_age_67;
        $pt_data[$val->PROVINCE]['death_age_68'] = $val->death_age_68;
        $pt_data[$val->PROVINCE]['death_age_69'] = $val->death_age_69;
        $pt_data[$val->PROVINCE]['death_age_70'] = $val->death_age_70;
        $pt_data[$val->PROVINCE]['death_age_71'] = $val->death_age_71;
        $pt_data[$val->PROVINCE]['death_age_72'] = $val->death_age_72;
        $pt_data[$val->PROVINCE]['death_age_73'] = $val->death_age_73;
        $pt_data[$val->PROVINCE]['death_age_74'] = $val->death_age_74;
        $pt_data[$val->PROVINCE]['death_age_75'] = $val->death_age_75;
        $pt_data[$val->PROVINCE]['death_age_76'] = $val->death_age_76;
        $pt_data[$val->PROVINCE]['death_age_77'] = $val->death_age_77;
        $pt_data[$val->PROVINCE]['death_age_78'] = $val->death_age_78;
        $pt_data[$val->PROVINCE]['death_age_79'] = $val->death_age_79;
        $pt_data[$val->PROVINCE]['death_age_80'] = $val->death_age_80;
        $pt_data[$val->PROVINCE]['death_age_81'] = $val->death_age_81;
        $pt_data[$val->PROVINCE]['death_age_82'] = $val->death_age_82;
        $pt_data[$val->PROVINCE]['death_age_83'] = $val->death_age_83;
        $pt_data[$val->PROVINCE]['death_age_84'] = $val->death_age_84;
        $pt_data[$val->PROVINCE]['death_age_85'] = $val->death_age_85;
        $pt_data[$val->PROVINCE]['death_age_86'] = $val->death_age_86;
        $pt_data[$val->PROVINCE]['death_age_87'] = $val->death_age_87;
        $pt_data[$val->PROVINCE]['death_age_88'] = $val->death_age_88;
        $pt_data[$val->PROVINCE]['death_age_89'] = $val->death_age_89;
        $pt_data[$val->PROVINCE]['death_age_90'] = $val->death_age_90;
        $pt_data[$val->PROVINCE]['death_age_91'] = $val->death_age_91;
        $pt_data[$val->PROVINCE]['death_age_92'] = $val->death_age_92;
        $pt_data[$val->PROVINCE]['death_age_93'] = $val->death_age_93;
        $pt_data[$val->PROVINCE]['death_age_94'] = $val->death_age_94;
        $pt_data[$val->PROVINCE]['death_age_95'] = $val->death_age_95;
        $pt_data[$val->PROVINCE]['death_age_96'] = $val->death_age_96;
        $pt_data[$val->PROVINCE]['death_age_97'] = $val->death_age_97;
        $pt_data[$val->PROVINCE]['death_age_98'] = $val->death_age_98;
        $pt_data[$val->PROVINCE]['death_age_99'] = $val->death_age_99;
        $pt_data[$val->PROVINCE]['death_age_100'] = $val->death_age_100;

      }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'death_age_0' => "0",'death_age_1' => "0",'death_age_2' => "0",'death_age_3' => "0",'death_age_4' => "0",
                                        'death_age_5' => "0",'death_age_6' => "0",'death_age_7' => "0",'death_age_8' => "0",'death_age_9' => "0",
                                        'death_age_10' => "0",'death_age_11' => "0",'death_age_12' => "0",'death_age_13' => "0",'death_age_14' => "0",
                                        'death_age_15' => "0",'death_age_16' => "0",'death_age_17' => "0",'death_age_18' => "0",'death_age_19' => "0",
                                        'death_age_20' => "0",'death_age_21' => "0",'death_age_22' => "0",'death_age_23' => "0",'death_age_24' => "0",
                                        'death_age_25' => "0",'death_age_26' => "0",'death_age_27' => "0",'death_age_28' => "0",'death_age_29' => "0",
                                        'death_age_30' => "0",'death_age_31' => "0",'death_age_32' => "0",'death_age_33' => "0",'death_age_34' => "0",
                                        'death_age_35' => "0",'death_age_36' => "0",'death_age_37' => "0",'death_age_38' => "0",'death_age_39' => "0",
                                        'death_age_40' => "0",'death_age_41' => "0",'death_age_42' => "0",'death_age_43' => "0",'death_age_44' => "0",
                                        'death_age_45' => "0",'death_age_46' => "0",'death_age_47' => "0",'death_age_48' => "0",'death_age_49' => "0",
                                        'death_age_50' => "0",'death_age_51' => "0",'death_age_52' => "0",'death_age_53' => "0",'death_age_54' => "0",
                                        'death_age_55' => "0",'death_age_56' => "0",'death_age_57' => "0",'death_age_58' => "0",'death_age_59' => "0",
                                        'death_age_60' => "0",'death_age_61' => "0",'death_age_62' => "0",'death_age_63' => "0",'death_age_64' => "0",
                                        'death_age_65' => "0",'death_age_66' => "0",'death_age_67' => "0",'death_age_68' => "0",'death_age_69' => "0",
                                        'death_age_70' => "0",'death_age_71' => "0",'death_age_72' => "0",'death_age_73' => "0",'death_age_74' => "0",
                                        'death_age_75' => "0",'death_age_76' => "0",'death_age_77' => "0",'death_age_78' => "0",'death_age_79' => "0",
                                        'death_age_80' => "0",'death_age_81' => "0",'death_age_82' => "0",'death_age_83' => "0",'death_age_84' => "0",
                                        'death_age_85' => "0",'death_age_86' => "0",'death_age_87' => "0",'death_age_88' => "0",'death_age_89' => "0",
                                        'death_age_90' => "0",'death_age_91' => "0",'death_age_92' => "0",'death_age_93' => "0",'death_age_94' => "0",
                                        'death_age_95' => "0",'death_age_96' => "0",'death_age_97' => "0",'death_age_98' => "0",'death_age_99' => "0",
                                        'death_age_100' => 0
                                );
          }
        }
        return $excel_data;
  }
  public static function xls_patient_death_by_age(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Col A1 Excel
    $excel_data[] = array('DPC','Reporting Area','age-0','age-1','age-2','age-3','age-4','age-5','age-6','age-7','age-8','age-9','age-10','age-11','age-12',
                          'age-13','age-14','age-15','age-16','age-17','age-18','age-19','age-20','age-21','age-22','age-23','age-24','age-25','age-26',
                          'age-27','age-28','age-29','age-30','age-31','age-32','age-33','age-34','age-35','age-36','age-37','age-38','age-39',
                          'age-40','age-41','age-42','age-43','age-44','age-45','age-46','age-47','age-48','age-49','age-50','age-51','age-52','age-53','age-54',
                          'age-55','age-56','age-57','age-58','age-59','age-60','age-61','age-62','age-63','age-64','age-65','age-66','age-67','age-68','age-69','age-70','age-71','age-72','age-73','age-74','age-75','age-76','age-77','age-78','age-79',
                          'age-80','age-81','age-82','age-83','age-84','age-85','age-86','age-87','age-88','age-89','age-90','age-91','age-91','age-93','age-94','age-95','age-96','age-97','age-98','age-99','age->=100'
                         );
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 0,1,0)) AS death_age_0,SUM(IF(RESULT = 2 AND YEAR = 1,1,0)) AS death_age_1')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR <> 2,1,0)) AS death_age_2,SUM(IF(RESULT = 2 AND YEAR = 3,1,0)) AS death_age_3')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 4,1,0)) AS death_age_4,SUM(IF(RESULT = 2 AND YEAR = 5,1,0)) AS death_age_5')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 6,1,0)) AS death_age_6,SUM(IF(RESULT = 2 AND YEAR = 7,1,0)) AS death_age_7')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 8,1,0)) AS death_age_8,SUM(IF(RESULT = 2 AND YEAR = 9,1,0)) AS death_age_9')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 10,1,0)) AS death_age_10,SUM(IF(RESULT = 2 AND YEAR = 11,1,0)) AS death_age_11')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 12,1,0)) AS death_age_12,SUM(IF(RESULT = 2 AND YEAR = 13,1,0)) AS death_age_13')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 14,1,0)) AS death_age_14,SUM(IF(RESULT = 2 AND YEAR = 15,1,0)) AS death_age_15')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 16,1,0)) AS death_age_16,SUM(IF(RESULT = 2 AND YEAR = 17,1,0)) AS death_age_17')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 18,1,0)) AS death_age_18,SUM(IF(RESULT = 2 AND YEAR = 19,1,0)) AS death_age_19')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 20,1,0)) AS death_age_20,SUM(IF(RESULT = 2 AND YEAR = 21,1,0)) AS death_age_21')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 22,1,0)) AS death_age_22,SUM(IF(RESULT = 2 AND YEAR = 23,1,0)) AS death_age_23')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 24,1,0)) AS death_age_24,SUM(IF(RESULT = 2 AND YEAR = 25,1,0)) AS death_age_25')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 26,1,0)) AS death_age_26,SUM(IF(RESULT = 2 AND YEAR = 27,1,0)) AS death_age_27')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 28,1,0)) AS death_age_28,SUM(IF(RESULT = 2 AND YEAR = 29,1,0)) AS death_age_29')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 30,1,0)) AS death_age_30,SUM(IF(RESULT = 2 AND YEAR = 31,1,0)) AS death_age_31')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 32,1,0)) AS death_age_32,SUM(IF(RESULT = 2 AND YEAR = 33,1,0)) AS death_age_33')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 34,1,0)) AS death_age_34,SUM(IF(RESULT = 2 AND YEAR = 35,1,0)) AS death_age_35')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 36,1,0)) AS death_age_36,SUM(IF(RESULT = 2 AND YEAR = 37,1,0)) AS death_age_37')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 38,1,0)) AS death_age_38,SUM(IF(RESULT = 2 AND YEAR = 39,1,0)) AS death_age_39')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 40,1,0)) AS death_age_40,SUM(IF(RESULT = 2 AND YEAR = 41,1,0)) AS death_age_41')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 42,1,0)) AS death_age_42,SUM(IF(RESULT = 2 AND YEAR = 43,1,0)) AS death_age_43')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 44,1,0)) AS death_age_44,SUM(IF(RESULT = 2 AND YEAR = 45,1,0)) AS death_age_45')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 46,1,0)) AS death_age_46,SUM(IF(RESULT = 2 AND YEAR = 47,1,0)) AS death_age_47')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 48,1,0)) AS death_age_48,SUM(IF(RESULT = 2 AND YEAR = 49,1,0)) AS death_age_49')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 50,1,0)) AS death_age_50,SUM(IF(RESULT = 2 AND YEAR = 51,1,0)) AS death_age_51')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 52,1,0)) AS death_age_52,SUM(IF(RESULT = 2 AND YEAR = 53,1,0)) AS death_age_53')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 54,1,0)) AS death_age_54,SUM(IF(RESULT = 2 AND YEAR = 55,1,0)) AS death_age_55')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 56,1,0)) AS death_age_56,SUM(IF(RESULT = 2 AND YEAR = 57,1,0)) AS death_age_57')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 58,1,0)) AS death_age_58,SUM(IF(RESULT = 2 AND YEAR = 59,1,0)) AS death_age_59')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 60,1,0)) AS death_age_60,SUM(IF(RESULT = 2 AND YEAR = 61,1,0)) AS death_age_61')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 62,1,0)) AS death_age_62,SUM(IF(RESULT = 2 AND YEAR = 63,1,0)) AS death_age_63')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 64,1,0)) AS death_age_64,SUM(IF(RESULT = 2 AND YEAR = 65,1,0)) AS death_age_65')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 66,1,0)) AS death_age_66,SUM(IF(RESULT = 2 AND YEAR = 67,1,0)) AS death_age_67')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 68,1,0)) AS death_age_68,SUM(IF(RESULT = 2 AND YEAR = 69,1,0)) AS death_age_69')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 70,1,0)) AS death_age_70,SUM(IF(RESULT = 2 AND YEAR = 71,1,0)) AS death_age_71')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 72,1,0)) AS death_age_72,SUM(IF(RESULT = 2 AND YEAR = 73,1,0)) AS death_age_73')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 74,1,0)) AS death_age_74,SUM(IF(RESULT = 2 AND YEAR = 75,1,0)) AS death_age_75')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 76,1,0)) AS death_age_76,SUM(IF(RESULT = 2 AND YEAR = 77,1,0)) AS death_age_77')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 78,1,0)) AS death_age_78,SUM(IF(RESULT = 2 AND YEAR = 79,1,0)) AS death_age_79')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 80,1,0)) AS death_age_80,SUM(IF(RESULT = 2 AND YEAR = 81,1,0)) AS death_age_81')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 82,1,0)) AS death_age_82,SUM(IF(RESULT = 2 AND YEAR = 83,1,0)) AS death_age_83')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 84,1,0)) AS death_age_84,SUM(IF(RESULT = 2 AND YEAR = 85,1,0)) AS death_age_85')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 86,1,0)) AS death_age_86,SUM(IF(RESULT = 2 AND YEAR = 87,1,0)) AS death_age_87')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 88,1,0)) AS death_age_88,SUM(IF(RESULT = 2 AND YEAR = 89,1,0)) AS death_age_89')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 90,1,0)) AS death_age_90,SUM(IF(RESULT = 2 AND YEAR = 91,1,0)) AS death_age_91')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 92,1,0)) AS death_age_92,SUM(IF(RESULT = 2 AND YEAR = 93,1,0)) AS death_age_93')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 94,1,0)) AS death_age_94,SUM(IF(RESULT = 2 AND YEAR = 95,1,0)) AS death_age_95')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 96,1,0)) AS death_age_96,SUM(IF(RESULT = 2 AND YEAR = 97,1,0)) AS death_age_97')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 98,1,0)) AS death_age_98,SUM(IF(RESULT = 2 AND YEAR = 99,1,0)) AS death_age_99')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR >= 100,1,0)) AS death_age_100')
      ->whereIn('DISEASE',$disease_code)
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }else{
      $query[] = DB::table('ur506_'.$tblYear)
      ->select('prov_dpc','PROVINCE')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 0,1,0)) AS death_age_0,SUM(IF(RESULT = 2 AND YEAR = 1,1,0)) AS death_age_1')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 2,1,0)) AS death_age_2,SUM(IF(RESULT = 2 AND YEAR = 3,1,0)) AS death_age_3')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 4,1,0)) AS death_age_4,SUM(IF(RESULT = 2 AND YEAR = 5,1,0)) AS death_age_5')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 6,1,0)) AS death_age_6,SUM(IF(RESULT = 2 AND YEAR = 7,1,0)) AS death_age_7')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 8,1,0)) AS death_age_8,SUM(IF(RESULT = 2 AND YEAR = 9,1,0)) AS death_age_9')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 10,1,0)) AS death_age_10,SUM(IF(RESULT = 2 AND YEAR = 11,1,0)) AS death_age_11')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 12,1,0)) AS death_age_12,SUM(IF(RESULT = 2 AND YEAR = 13,1,0)) AS death_age_13')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 14,1,0)) AS death_age_14,SUM(IF(RESULT = 2 AND YEAR = 15,1,0)) AS death_age_15')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 16,1,0)) AS death_age_16,SUM(IF(RESULT = 2 AND YEAR = 17,1,0)) AS death_age_17')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 18,1,0)) AS death_age_18,SUM(IF(RESULT = 2 AND YEAR = 19,1,0)) AS death_age_19')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 20,1,0)) AS death_age_20,SUM(IF(RESULT = 2 AND YEAR = 21,1,0)) AS death_age_21')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 22,1,0)) AS death_age_22,SUM(IF(RESULT = 2 AND YEAR = 23,1,0)) AS death_age_23')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 24,1,0)) AS death_age_24,SUM(IF(RESULT = 2 AND YEAR = 25,1,0)) AS death_age_25')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 26,1,0)) AS death_age_26,SUM(IF(RESULT = 2 AND YEAR = 27,1,0)) AS death_age_27')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 28,1,0)) AS death_age_28,SUM(IF(RESULT = 2 AND YEAR = 29,1,0)) AS death_age_29')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 30,1,0)) AS death_age_30,SUM(IF(RESULT = 2 AND YEAR = 31,1,0)) AS death_age_31')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 32,1,0)) AS death_age_32,SUM(IF(RESULT = 2 AND YEAR = 33,1,0)) AS death_age_33')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 34,1,0)) AS death_age_34,SUM(IF(RESULT = 2 AND YEAR = 35,1,0)) AS death_age_35')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 36,1,0)) AS death_age_36,SUM(IF(RESULT = 2 AND YEAR = 37,1,0)) AS death_age_37')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 38,1,0)) AS death_age_38,SUM(IF(RESULT = 2 AND YEAR = 39,1,0)) AS death_age_39')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 40,1,0)) AS death_age_40,SUM(IF(RESULT = 2 AND YEAR = 41,1,0)) AS death_age_41')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 42,1,0)) AS death_age_42,SUM(IF(RESULT = 2 AND YEAR = 43,1,0)) AS death_age_43')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 44,1,0)) AS death_age_44,SUM(IF(RESULT = 2 AND YEAR = 45,1,0)) AS death_age_45')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 46,1,0)) AS death_age_46,SUM(IF(RESULT = 2 AND YEAR = 47,1,0)) AS death_age_47')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 48,1,0)) AS death_age_48,SUM(IF(RESULT = 2 AND YEAR = 49,1,0)) AS death_age_49')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 50,1,0)) AS death_age_50,SUM(IF(RESULT = 2 AND YEAR = 51,1,0)) AS death_age_51')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 52,1,0)) AS death_age_52,SUM(IF(RESULT = 2 AND YEAR = 53,1,0)) AS death_age_53')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 54,1,0)) AS death_age_54,SUM(IF(RESULT = 2 AND YEAR = 55,1,0)) AS death_age_55')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 56,1,0)) AS death_age_56,SUM(IF(RESULT = 2 AND YEAR = 57,1,0)) AS death_age_57')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 58,1,0)) AS death_age_58,SUM(IF(RESULT = 2 AND YEAR = 59,1,0)) AS death_age_59')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 60,1,0)) AS death_age_60,SUM(IF(RESULT = 2 AND YEAR = 61,1,0)) AS death_age_61')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 62,1,0)) AS death_age_62,SUM(IF(RESULT = 2 AND YEAR = 63,1,0)) AS death_age_63')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 64,1,0)) AS death_age_64,SUM(IF(RESULT = 2 AND YEAR = 65,1,0)) AS death_age_65')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 66,1,0)) AS death_age_66,SUM(IF(RESULT = 2 AND YEAR = 67,1,0)) AS death_age_67')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 68,1,0)) AS death_age_68,SUM(IF(RESULT = 2 AND YEAR = 69,1,0)) AS death_age_69')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 70,1,0)) AS death_age_70,SUM(IF(RESULT = 2 AND YEAR = 71,1,0)) AS death_age_71')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 72,1,0)) AS death_age_72,SUM(IF(RESULT = 2 AND YEAR = 73,1,0)) AS death_age_73')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 74,1,0)) AS death_age_74,SUM(IF(RESULT = 2 AND YEAR = 75,1,0)) AS death_age_75')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 76,1,0)) AS death_age_76,SUM(IF(RESULT = 2 AND YEAR = 77,1,0)) AS death_age_77')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 78,1,0)) AS death_age_78,SUM(IF(RESULT = 2 AND YEAR = 79,1,0)) AS death_age_79')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 80,1,0)) AS death_age_80,SUM(IF(RESULT = 2 AND YEAR = 81,1,0)) AS death_age_81')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 82,1,0)) AS death_age_82,SUM(IF(RESULT = 2 AND YEAR = 83,1,0)) AS death_age_83')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 84,1,0)) AS death_age_84,SUM(IF(RESULT = 2 AND YEAR = 85,1,0)) AS death_age_85')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 86,1,0)) AS death_age_86,SUM(IF(RESULT = 2 AND YEAR = 87,1,0)) AS death_age_87')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 88,1,0)) AS death_age_88,SUM(IF(RESULT = 2 AND YEAR = 89,1,0)) AS death_age_89')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 90,1,0)) AS death_age_90,SUM(IF(RESULT = 2 AND YEAR = 91,1,0)) AS death_age_91')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 92,1,0)) AS death_age_92,SUM(IF(RESULT = 2 AND YEAR = 93,1,0)) AS death_age_93')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 94,1,0)) AS death_age_94,SUM(IF(RESULT = 2 AND YEAR = 95,1,0)) AS death_age_95')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 96,1,0)) AS death_age_96,SUM(IF(RESULT = 2 AND YEAR = 97,1,0)) AS death_age_97')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR = 98,1,0)) AS death_age_98,SUM(IF(RESULT = 2 AND YEAR = 99,1,0)) AS death_age_99')
      ->selectRaw('SUM(IF(RESULT = 2 AND YEAR >= 100,1,0)) AS death_age_100')
      ->where('DISEASE','=',$disease_code['0'])
      ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
      ->groupBy('PROVINCE')
      ->get();
    }

      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['death_age_0'] = $val->death_age_0;
        $pt_data[$val->PROVINCE]['death_age_1'] = $val->death_age_1;
        $pt_data[$val->PROVINCE]['death_age_2'] = $val->death_age_2;
        $pt_data[$val->PROVINCE]['death_age_3'] = $val->death_age_3;
        $pt_data[$val->PROVINCE]['death_age_4'] = $val->death_age_4;
        $pt_data[$val->PROVINCE]['death_age_5'] = $val->death_age_5;
        $pt_data[$val->PROVINCE]['death_age_6'] = $val->death_age_6;
        $pt_data[$val->PROVINCE]['death_age_7'] = $val->death_age_7;
        $pt_data[$val->PROVINCE]['death_age_8'] = $val->death_age_8;
        $pt_data[$val->PROVINCE]['death_age_9'] = $val->death_age_9;
        $pt_data[$val->PROVINCE]['death_age_10'] = $val->death_age_10;
        $pt_data[$val->PROVINCE]['death_age_11'] = $val->death_age_11;
        $pt_data[$val->PROVINCE]['death_age_12'] = $val->death_age_12;
        $pt_data[$val->PROVINCE]['death_age_13'] = $val->death_age_13;
        $pt_data[$val->PROVINCE]['death_age_14'] = $val->death_age_14;
        $pt_data[$val->PROVINCE]['death_age_15'] = $val->death_age_15;
        $pt_data[$val->PROVINCE]['death_age_16'] = $val->death_age_16;
        $pt_data[$val->PROVINCE]['death_age_17'] = $val->death_age_17;
        $pt_data[$val->PROVINCE]['death_age_18'] = $val->death_age_18;
        $pt_data[$val->PROVINCE]['death_age_19'] = $val->death_age_19;
        $pt_data[$val->PROVINCE]['death_age_20'] = $val->death_age_20;
        $pt_data[$val->PROVINCE]['death_age_21'] = $val->death_age_21;
        $pt_data[$val->PROVINCE]['death_age_22'] = $val->death_age_22;
        $pt_data[$val->PROVINCE]['death_age_23'] = $val->death_age_23;
        $pt_data[$val->PROVINCE]['death_age_24'] = $val->death_age_24;
        $pt_data[$val->PROVINCE]['death_age_25'] = $val->death_age_25;
        $pt_data[$val->PROVINCE]['death_age_26'] = $val->death_age_26;
        $pt_data[$val->PROVINCE]['death_age_27'] = $val->death_age_27;
        $pt_data[$val->PROVINCE]['death_age_28'] = $val->death_age_28;
        $pt_data[$val->PROVINCE]['death_age_29'] = $val->death_age_29;
        $pt_data[$val->PROVINCE]['death_age_30'] = $val->death_age_30;
        $pt_data[$val->PROVINCE]['death_age_31'] = $val->death_age_31;
        $pt_data[$val->PROVINCE]['death_age_32'] = $val->death_age_32;
        $pt_data[$val->PROVINCE]['death_age_33'] = $val->death_age_33;
        $pt_data[$val->PROVINCE]['death_age_34'] = $val->death_age_34;
        $pt_data[$val->PROVINCE]['death_age_35'] = $val->death_age_35;
        $pt_data[$val->PROVINCE]['death_age_36'] = $val->death_age_36;
        $pt_data[$val->PROVINCE]['death_age_37'] = $val->death_age_37;
        $pt_data[$val->PROVINCE]['death_age_38'] = $val->death_age_38;
        $pt_data[$val->PROVINCE]['death_age_39'] = $val->death_age_39;
        $pt_data[$val->PROVINCE]['death_age_40'] = $val->death_age_40;
        $pt_data[$val->PROVINCE]['death_age_41'] = $val->death_age_41;
        $pt_data[$val->PROVINCE]['death_age_42'] = $val->death_age_42;
        $pt_data[$val->PROVINCE]['death_age_43'] = $val->death_age_43;
        $pt_data[$val->PROVINCE]['death_age_44'] = $val->death_age_44;
        $pt_data[$val->PROVINCE]['death_age_45'] = $val->death_age_45;
        $pt_data[$val->PROVINCE]['death_age_46'] = $val->death_age_46;
        $pt_data[$val->PROVINCE]['death_age_47'] = $val->death_age_47;
        $pt_data[$val->PROVINCE]['death_age_48'] = $val->death_age_48;
        $pt_data[$val->PROVINCE]['death_age_49'] = $val->death_age_49;
        $pt_data[$val->PROVINCE]['death_age_50'] = $val->death_age_50;
        $pt_data[$val->PROVINCE]['death_age_51'] = $val->death_age_51;
        $pt_data[$val->PROVINCE]['death_age_52'] = $val->death_age_52;
        $pt_data[$val->PROVINCE]['death_age_53'] = $val->death_age_53;
        $pt_data[$val->PROVINCE]['death_age_54'] = $val->death_age_54;
        $pt_data[$val->PROVINCE]['death_age_55'] = $val->death_age_55;
        $pt_data[$val->PROVINCE]['death_age_56'] = $val->death_age_56;
        $pt_data[$val->PROVINCE]['death_age_57'] = $val->death_age_57;
        $pt_data[$val->PROVINCE]['death_age_58'] = $val->death_age_58;
        $pt_data[$val->PROVINCE]['death_age_59'] = $val->death_age_59;
        $pt_data[$val->PROVINCE]['death_age_60'] = $val->death_age_60;
        $pt_data[$val->PROVINCE]['death_age_61'] = $val->death_age_61;
        $pt_data[$val->PROVINCE]['death_age_62'] = $val->death_age_62;
        $pt_data[$val->PROVINCE]['death_age_63'] = $val->death_age_63;
        $pt_data[$val->PROVINCE]['death_age_64'] = $val->death_age_64;
        $pt_data[$val->PROVINCE]['death_age_65'] = $val->death_age_65;
        $pt_data[$val->PROVINCE]['death_age_66'] = $val->death_age_66;
        $pt_data[$val->PROVINCE]['death_age_67'] = $val->death_age_67;
        $pt_data[$val->PROVINCE]['death_age_68'] = $val->death_age_68;
        $pt_data[$val->PROVINCE]['death_age_69'] = $val->death_age_69;
        $pt_data[$val->PROVINCE]['death_age_70'] = $val->death_age_70;
        $pt_data[$val->PROVINCE]['death_age_71'] = $val->death_age_71;
        $pt_data[$val->PROVINCE]['death_age_72'] = $val->death_age_72;
        $pt_data[$val->PROVINCE]['death_age_73'] = $val->death_age_73;
        $pt_data[$val->PROVINCE]['death_age_74'] = $val->death_age_74;
        $pt_data[$val->PROVINCE]['death_age_75'] = $val->death_age_75;
        $pt_data[$val->PROVINCE]['death_age_76'] = $val->death_age_76;
        $pt_data[$val->PROVINCE]['death_age_77'] = $val->death_age_77;
        $pt_data[$val->PROVINCE]['death_age_78'] = $val->death_age_78;
        $pt_data[$val->PROVINCE]['death_age_79'] = $val->death_age_79;
        $pt_data[$val->PROVINCE]['death_age_80'] = $val->death_age_80;
        $pt_data[$val->PROVINCE]['death_age_81'] = $val->death_age_81;
        $pt_data[$val->PROVINCE]['death_age_82'] = $val->death_age_82;
        $pt_data[$val->PROVINCE]['death_age_83'] = $val->death_age_83;
        $pt_data[$val->PROVINCE]['death_age_84'] = $val->death_age_84;
        $pt_data[$val->PROVINCE]['death_age_85'] = $val->death_age_85;
        $pt_data[$val->PROVINCE]['death_age_86'] = $val->death_age_86;
        $pt_data[$val->PROVINCE]['death_age_87'] = $val->death_age_87;
        $pt_data[$val->PROVINCE]['death_age_88'] = $val->death_age_88;
        $pt_data[$val->PROVINCE]['death_age_89'] = $val->death_age_89;
        $pt_data[$val->PROVINCE]['death_age_90'] = $val->death_age_90;
        $pt_data[$val->PROVINCE]['death_age_91'] = $val->death_age_91;
        $pt_data[$val->PROVINCE]['death_age_92'] = $val->death_age_92;
        $pt_data[$val->PROVINCE]['death_age_93'] = $val->death_age_93;
        $pt_data[$val->PROVINCE]['death_age_94'] = $val->death_age_94;
        $pt_data[$val->PROVINCE]['death_age_95'] = $val->death_age_95;
        $pt_data[$val->PROVINCE]['death_age_96'] = $val->death_age_96;
        $pt_data[$val->PROVINCE]['death_age_97'] = $val->death_age_97;
        $pt_data[$val->PROVINCE]['death_age_98'] = $val->death_age_98;
        $pt_data[$val->PROVINCE]['death_age_99'] = $val->death_age_99;
        $pt_data[$val->PROVINCE]['death_age_100'] = $val->death_age_100;

      }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'death_age_0' => "0",'death_age_1' => "0",'death_age_2' => "0",'death_age_3' => "0",'death_age_4' => "0",
                                        'death_age_5' => "0",'death_age_6' => "0",'death_age_7' => "0",'death_age_8' => "0",'death_age_9' => "0",
                                        'death_age_10' => "0",'death_age_11' => "0",'death_age_12' => "0",'death_age_13' => "0",'death_age_14' => "0",
                                        'death_age_15' => "0",'death_age_16' => "0",'death_age_17' => "0",'death_age_18' => "0",'death_age_19' => "0",
                                        'death_age_20' => "0",'death_age_21' => "0",'death_age_22' => "0",'death_age_23' => "0",'death_age_24' => "0",
                                        'death_age_25' => "0",'death_age_26' => "0",'death_age_27' => "0",'death_age_28' => "0",'death_age_29' => "0",
                                        'death_age_30' => "0",'death_age_31' => "0",'death_age_32' => "0",'death_age_33' => "0",'death_age_34' => "0",
                                        'death_age_35' => "0",'death_age_36' => "0",'death_age_37' => "0",'death_age_38' => "0",'death_age_39' => "0",
                                        'death_age_40' => "0",'death_age_41' => "0",'death_age_42' => "0",'death_age_43' => "0",'death_age_44' => "0",
                                        'death_age_45' => "0",'death_age_46' => "0",'death_age_47' => "0",'death_age_48' => "0",'death_age_49' => "0",
                                        'death_age_50' => "0",'death_age_51' => "0",'death_age_52' => "0",'death_age_53' => "0",'death_age_54' => "0",
                                        'death_age_55' => "0",'death_age_56' => "0",'death_age_57' => "0",'death_age_58' => "0",'death_age_59' => "0",
                                        'death_age_60' => "0",'death_age_61' => "0",'death_age_62' => "0",'death_age_63' => "0",'death_age_64' => "0",
                                        'death_age_65' => "0",'death_age_66' => "0",'death_age_67' => "0",'death_age_68' => "0",'death_age_69' => "0",
                                        'death_age_70' => "0",'death_age_71' => "0",'death_age_72' => "0",'death_age_73' => "0",'death_age_74' => "0",
                                        'death_age_75' => "0",'death_age_76' => "0",'death_age_77' => "0",'death_age_78' => "0",'death_age_79' => "0",
                                        'death_age_80' => "0",'death_age_81' => "0",'death_age_82' => "0",'death_age_83' => "0",'death_age_84' => "0",
                                        'death_age_85' => "0",'death_age_86' => "0",'death_age_87' => "0",'death_age_88' => "0",'death_age_89' => "0",
                                        'death_age_90' => "0",'death_age_91' => "0",'death_age_92' => "0",'death_age_93' => "0",'death_age_94' => "0",
                                        'death_age_95' => "0",'death_age_96' => "0",'death_age_97' => "0",'death_age_98' => "0",'death_age_99' => "0",
                                        'death_age_100' => 0
                                );
          }
        }
        //Year to DC
        $year_th = $tblYear+543;
        //filename
        $filename = 'sick-death'.'-year-'.$year_th;
        //sheetname
        $sheetname = 'sheet1';

        // header text
        $header_text = "ตารางข้อมูลจำนวนตาย ตามอายุ จำแนกรายจังหวัด โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
         })->download('csv');
  }
  public static function get_patient_sick_death_by_nation($select_year,$disease_code){
    $tblYear = (isset($select_year))? $select_year : date('Y')-1;
    $post_disease_code = (isset($disease_code))? $disease_code : "01";
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(RACE = 1,1,0)) AS case_th,SUM(IF(RESULT = 2 AND RACE = 1,1,0)) AS death_th')
        ->selectRaw('SUM(IF(RACE = 3,1,0)) AS case_bm,SUM(IF(RESULT = 2 AND RACE = 3,1,0)) AS death_bm')
        ->selectRaw('SUM(IF(RACE = 4,1,0)) AS case_ms,SUM(IF(RESULT = 2 AND RACE = 4,1,0)) AS death_ms')
        ->selectRaw('SUM(IF(RACE = 5,1,0)) AS case_cd,SUM(IF(RESULT = 2 AND RACE = 5,1,0)) AS death_cd')
        ->selectRaw('SUM(IF(RACE = 6,1,0)) AS case_los,SUM(IF(RESULT = 2 AND RACE = 6,1,0)) AS death_los')
        ->selectRaw('SUM(IF(RACE = 7,1,0)) AS case_vn,SUM(IF(RESULT = 2 AND RACE = 7,1,0)) AS death_vn')
        ->selectRaw('SUM(IF(RACE = 2,1,0)) AS case_ch,SUM(IF(RESULT = 2 AND RACE = 2,1,0)) AS death_ch')
        ->selectRaw('SUM(IF(RACE = 8,1,0)) AS case_oth,SUM(IF(RESULT = 2 AND RACE = 8,1,0)) AS death_oth')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(RACE = 1,1,0)) AS case_th,SUM(IF(RESULT = 2 AND RACE = 1,1,0)) AS death_th')
        ->selectRaw('SUM(IF(RACE = 3,1,0)) AS case_bm,SUM(IF(RESULT = 2 AND RACE = 3,1,0)) AS death_bm')
        ->selectRaw('SUM(IF(RACE = 4,1,0)) AS case_ms,SUM(IF(RESULT = 2 AND RACE = 4,1,0)) AS death_ms')
        ->selectRaw('SUM(IF(RACE = 5,1,0)) AS case_cd,SUM(IF(RESULT = 2 AND RACE = 5,1,0)) AS death_cd')
        ->selectRaw('SUM(IF(RACE = 6,1,0)) AS case_los,SUM(IF(RESULT = 2 AND RACE = 6,1,0)) AS death_los')
        ->selectRaw('SUM(IF(RACE = 7,1,0)) AS case_vn,SUM(IF(RESULT = 2 AND RACE = 7,1,0)) AS death_vn')
        ->selectRaw('SUM(IF(RACE = 2,1,0)) AS case_ch,SUM(IF(RESULT = 2 AND RACE = 2,1,0)) AS death_ch')
        ->selectRaw('SUM(IF(RACE = 8,1,0)) AS case_oth,SUM(IF(RESULT = 2 AND RACE = 8,1,0)) AS death_oth')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
    foreach ($query[0] as $key => $val) {
      $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
      $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
      $pt_data[$val->PROVINCE]['case_th'] = $val->case_th;
      $pt_data[$val->PROVINCE]['death_th'] = $val->death_th;
      $pt_data[$val->PROVINCE]['case_bm'] = $val->case_bm;
      $pt_data[$val->PROVINCE]['death_bm'] = $val->death_bm;
      $pt_data[$val->PROVINCE]['case_ms'] = $val->case_ms;
      $pt_data[$val->PROVINCE]['death_ms'] = $val->death_ms;
      $pt_data[$val->PROVINCE]['case_cd'] = $val->case_cd;
      $pt_data[$val->PROVINCE]['death_cd'] = $val->death_cd;
      $pt_data[$val->PROVINCE]['case_los'] = $val->case_los;
      $pt_data[$val->PROVINCE]['death_los'] = $val->death_los;
      $pt_data[$val->PROVINCE]['case_vn'] = $val->case_vn;
      $pt_data[$val->PROVINCE]['death_vn'] = $val->death_vn;
      $pt_data[$val->PROVINCE]['case_ch'] = $val->case_ch;
      $pt_data[$val->PROVINCE]['death_ch'] = $val->death_ch;
      $pt_data[$val->PROVINCE]['case_oth'] = $val->case_oth;
      $pt_data[$val->PROVINCE]['death_oth'] = $val->death_oth;
      $total_case = $val->case_th+$val->case_bm+$val->case_ms+$val->case_cd+$val->case_los+$val->case_vn+$val->case_ch+$val->case_oth;
      $total_death = $val->death_th+$val->death_bm+$val->death_ms+$val->death_cd+$val->death_los+$val->death_vn+$val->death_ch+$val->death_oth;
      $pt_data[$val->PROVINCE]['total_case'] = $total_case;
      $pt_data[$val->PROVINCE]['total_death'] = $total_death;
    }

    foreach ($get_provincename_th as $key => $value) {
      if (array_key_exists($key, $pt_data)) {
        $excel_data[$key] = $pt_data[$key];
      }else{
        $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                    'PROVINCE' => $get_provincename_th[$key],
                                    'case_th' => "0",'death_th' => "0",'case_bm' => "0",'death_bm' => "0",'case_ms' => "0",
                                    'death_ms' => "0",'case_cd' => "0",'death_cd' => "0",'case_los' => "0",'death_los' => "0",
                                    'case_vn' => "0",'death_vn' => "0",'case_ch' => "0",'death_ch' => "0",'case_oth' => "0",
                                    'death_oth' => "0",'total_case' => "0",'total_death' => "0"
                            );
      }
    }
  //dd($excel_data);
  return $excel_data;
  }
  public static function xls_patient_sick_death_by_nation(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Col A1 Excel
    $excel_data[] = array('DPC','Reporting Area','รวม-ป่วย','รวม-ตาย','ป่วย-ไทย','ตาย-ไทย','ป่วย-พม่า','ตาย-พม่า','ป่วย-มาเลเซีย','ตาย-มาเลเซีย','ป่วย-กัมพูชา','ตาย-กัมพูชา',
                          'ป่วย-ลาว','ตาย-ลาว','ป่วย-เวียดนาม','ตาย-เวียดนาม','ป่วย-จีน','ตาย-จีน','ป่วย-อื่นๆ','ตาย-อื่นๆ'
                         );
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(RACE = 1,1,0)) AS case_th,SUM(IF(RESULT = 2 AND RACE = 1,1,0)) AS death_th')
        ->selectRaw('SUM(IF(RACE = 3,1,0)) AS case_bm,SUM(IF(RESULT = 2 AND RACE = 3,1,0)) AS death_bm')
        ->selectRaw('SUM(IF(RACE = 4,1,0)) AS case_ms,SUM(IF(RESULT = 2 AND RACE = 4,1,0)) AS death_ms')
        ->selectRaw('SUM(IF(RACE = 5,1,0)) AS case_cd,SUM(IF(RESULT = 2 AND RACE = 5,1,0)) AS death_cd')
        ->selectRaw('SUM(IF(RACE = 6,1,0)) AS case_los,SUM(IF(RESULT = 2 AND RACE = 6,1,0)) AS death_los')
        ->selectRaw('SUM(IF(RACE = 7,1,0)) AS case_vn,SUM(IF(RESULT = 2 AND RACE = 7,1,0)) AS death_vn')
        ->selectRaw('SUM(IF(RACE = 2,1,0)) AS case_ch,SUM(IF(RESULT = 2 AND RACE = 2,1,0)) AS death_ch')
        ->selectRaw('SUM(IF(RACE = 8,1,0)) AS case_oth,SUM(IF(RESULT = 2 AND RACE = 8,1,0)) AS death_oth')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(RACE = 1,1,0)) AS case_th,SUM(IF(RESULT = 2 AND RACE = 1,1,0)) AS death_th')
        ->selectRaw('SUM(IF(RACE = 3,1,0)) AS case_bm,SUM(IF(RESULT = 2 AND RACE = 3,1,0)) AS death_bm')
        ->selectRaw('SUM(IF(RACE = 4,1,0)) AS case_ms,SUM(IF(RESULT = 2 AND RACE = 4,1,0)) AS death_ms')
        ->selectRaw('SUM(IF(RACE = 5,1,0)) AS case_cd,SUM(IF(RESULT = 2 AND RACE = 5,1,0)) AS death_cd')
        ->selectRaw('SUM(IF(RACE = 6,1,0)) AS case_los,SUM(IF(RESULT = 2 AND RACE = 6,1,0)) AS death_los')
        ->selectRaw('SUM(IF(RACE = 7,1,0)) AS case_vn,SUM(IF(RESULT = 2 AND RACE = 7,1,0)) AS death_vn')
        ->selectRaw('SUM(IF(RACE = 2,1,0)) AS case_ch,SUM(IF(RESULT = 2 AND RACE = 2,1,0)) AS death_ch')
        ->selectRaw('SUM(IF(RACE = 8,1,0)) AS case_oth,SUM(IF(RESULT = 2 AND RACE = 8,1,0)) AS death_oth')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
    foreach ($query[0] as $key => $val) {
      $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
      $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
      $total_case = $val->case_th+$val->case_bm+$val->case_ms+$val->case_cd+$val->case_los+$val->case_vn+$val->case_ch+$val->case_oth;
      $total_death = $val->death_th+$val->death_bm+$val->death_ms+$val->death_cd+$val->death_los+$val->death_vn+$val->death_ch+$val->death_oth;
      $pt_data[$val->PROVINCE]['total_case'] = ($total_case>0) ? $total_case : "0";
      $pt_data[$val->PROVINCE]['total_death'] = ($total_death>0) ? $total_death : "0";
      $pt_data[$val->PROVINCE]['case_th'] = $val->case_th;
      $pt_data[$val->PROVINCE]['death_th'] = $val->death_th;
      $pt_data[$val->PROVINCE]['case_bm'] = $val->case_bm;
      $pt_data[$val->PROVINCE]['death_bm'] = $val->death_bm;
      $pt_data[$val->PROVINCE]['case_ms'] = $val->case_ms;
      $pt_data[$val->PROVINCE]['death_ms'] = $val->death_ms;
      $pt_data[$val->PROVINCE]['case_cd'] = $val->case_cd;
      $pt_data[$val->PROVINCE]['death_cd'] = $val->death_cd;
      $pt_data[$val->PROVINCE]['case_los'] = $val->case_los;
      $pt_data[$val->PROVINCE]['death_los'] = $val->death_los;
      $pt_data[$val->PROVINCE]['case_vn'] = $val->case_vn;
      $pt_data[$val->PROVINCE]['death_vn'] = $val->death_vn;
      $pt_data[$val->PROVINCE]['case_ch'] = $val->case_ch;
      $pt_data[$val->PROVINCE]['death_ch'] = $val->death_ch;
      $pt_data[$val->PROVINCE]['case_oth'] = $val->case_oth;
      $pt_data[$val->PROVINCE]['death_oth'] = $val->death_oth;
    }

    foreach ($get_provincename_th as $key => $value) {
      if (array_key_exists($key, $pt_data)) {
        $excel_data[$key] = $pt_data[$key];
      }else{
        $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                    'PROVINCE' => $get_provincename_th[$key],
                                    'total_case' => "0",'total_death' => "0",
                                    'case_th' => "0",'death_th' => "0",'case_bm' => "0",'death_bm' => "0",'case_ms' => "0",
                                    'death_ms' => "0",'case_cd' => "0",'death_cd' => "0",'case_los' => "0",'death_los' => "0",
                                    'case_vn' => "0",'death_vn' => "0",'case_ch' => "0",'death_ch' => "0",'case_oth' => "0",
                                    'death_oth' => "0"
                            );
      }
    }
    //Year to DC
    $year_th = $tblYear+543;
    //filename
    $filename = 'sick-death-race'.'-year-'.$year_th;
    //sheetname
    $sheetname = 'sheet1';

    // header text
    $header_text = "ตารางข้อมูลจำนวนป่วย/ตาย จำแนกตามสัญชาติ โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
     })->download('csv');
  }

  public static function get_patient_sick_by_occupation($select_year,$disease_code){
    $tblYear = (isset($select_year))? $select_year : date('Y')-1;
    $post_disease_code = (isset($disease_code))? $disease_code : "01";
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(OCCUPAT = 1,1,0)) AS famer_case,SUM(IF(OCCUPAT = 2,1,0)) AS official_case')
        ->selectRaw('SUM(IF(OCCUPAT = 3,1,0)) AS hire_case,SUM(IF(OCCUPAT = 4,1,0)) AS trade_case')
        ->selectRaw('SUM(IF(OCCUPAT = 5,1,0)) AS housework_case,SUM(IF(OCCUPAT = 6,1,0)) AS student_case')
        ->selectRaw('SUM(IF(OCCUPAT = 7,1,0)) AS soldier_case,SUM(IF(OCCUPAT = 8,1,0)) AS fishing_case')
        ->selectRaw('SUM(IF(OCCUPAT = 9,1,0)) AS instructor_case,SUM(IF(OCCUPAT = 12,1,0)) AS animal_husbandry_case')
        ->selectRaw('SUM(IF(OCCUPAT = 13,1,0)) AS priest_case,SUM(IF(OCCUPAT = 14,1,0)) AS special_career_STI_case')
        ->selectRaw('SUM(IF(OCCUPAT = 15,1,0)) AS public_health_officer_case,SUM(IF(OCCUPAT = 11,1,0)) AS unknow_govern_case')
        ->selectRaw('SUM(IF(OCCUPAT = 10,1,0)) AS other_case')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(OCCUPAT = 1,1,0)) AS famer_case,SUM(IF(OCCUPAT = 2,1,0)) AS official_case')
        ->selectRaw('SUM(IF(OCCUPAT = 3,1,0)) AS hire_case,SUM(IF(OCCUPAT = 4,1,0)) AS trade_case')
        ->selectRaw('SUM(IF(OCCUPAT = 5,1,0)) AS housework_case,SUM(IF(OCCUPAT = 6,1,0)) AS student_case')
        ->selectRaw('SUM(IF(OCCUPAT = 7,1,0)) AS soldier_case,SUM(IF(OCCUPAT = 8,1,0)) AS fishing_case')
        ->selectRaw('SUM(IF(OCCUPAT = 9,1,0)) AS instructor_case,SUM(IF(OCCUPAT = 12,1,0)) AS animal_husbandry_case')
        ->selectRaw('SUM(IF(OCCUPAT = 13,1,0)) AS priest_case,SUM(IF(OCCUPAT = 14,1,0)) AS special_career_STI_case')
        ->selectRaw('SUM(IF(OCCUPAT = 15,1,0)) AS public_health_officer_case,SUM(IF(OCCUPAT = 11,1,0)) AS unknow_govern_case')
        ->selectRaw('SUM(IF(OCCUPAT = 10,1,0)) AS other_case')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
        foreach ($query[0] as $key => $val) {
          $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
          $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
          $pt_data[$val->PROVINCE]['famer_case'] = $val->famer_case;
          $pt_data[$val->PROVINCE]['official_case'] = $val->official_case;
          $pt_data[$val->PROVINCE]['hire_case'] = $val->hire_case;
          $pt_data[$val->PROVINCE]['trade_case'] = $val->trade_case;
          $pt_data[$val->PROVINCE]['housework_case'] = $val->housework_case;
          $pt_data[$val->PROVINCE]['student_case'] = $val->student_case;
          $pt_data[$val->PROVINCE]['soldier_case'] = $val->soldier_case;
          $pt_data[$val->PROVINCE]['fishing_case'] = $val->fishing_case;
          $pt_data[$val->PROVINCE]['instructor_case'] = $val->instructor_case;
          $pt_data[$val->PROVINCE]['animal_husbandry_case'] = $val->animal_husbandry_case;
          $pt_data[$val->PROVINCE]['priest_case'] = $val->priest_case;
          $pt_data[$val->PROVINCE]['special_career_STI_case'] = $val->special_career_STI_case;
          $pt_data[$val->PROVINCE]['public_health_officer_case'] = $val->public_health_officer_case;
          $pt_data[$val->PROVINCE]['unknow_govern_case'] = $val->unknow_govern_case;
          $pt_data[$val->PROVINCE]['other_case'] = $val->other_case;
        }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'famer_case' => "0",'official_case' => "0",
                                        'hire_case' => "0",'trade_case' => "0",'housework_case' => "0",'student_case' => "0",'soldier_case' => "0",
                                        'fishing_case' => "0",'instructor_case' => "0",'animal_husbandry_case' => "0",'priest_case' => "0",'special_career_STI_case' => "0",
                                        'public_health_officer_case' => "0",'unknow_govern_case' => "0",'other_case' => "0"
                                );
          }
        }
          //dd($excel_data);
          return $excel_data;

  }
  public static function xls_patient_sick_by_occupation(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Col A1 Excel
    $excel_data[] = array('DPC','Reporting Area','เกษตรกรรม','ข้าราชการ','รับจ้าง','ค้าขาย','งานบ้าน','นักเรียน','ทหาร/ตำรวจ','ประมง','ครู','เลี้ยงสัตว์',
                          'นักบวช','อาชีพพิเศษ','บุคคลากรสาธารณสุข','อื่นๆ','ไม่ทราบ/นปค'
                         );
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(OCCUPAT = 1,1,0)) AS famer_case,SUM(IF(OCCUPAT = 2,1,0)) AS official_case')
        ->selectRaw('SUM(IF(OCCUPAT = 3,1,0)) AS hire_case,SUM(IF(OCCUPAT = 4,1,0)) AS trade_case')
        ->selectRaw('SUM(IF(OCCUPAT = 5,1,0)) AS housework_case,SUM(IF(OCCUPAT = 6,1,0)) AS student_case')
        ->selectRaw('SUM(IF(OCCUPAT = 7,1,0)) AS soldier_case,SUM(IF(OCCUPAT = 8,1,0)) AS fishing_case')
        ->selectRaw('SUM(IF(OCCUPAT = 9,1,0)) AS instructor_case,SUM(IF(OCCUPAT = 12,1,0)) AS animal_husbandry_case')
        ->selectRaw('SUM(IF(OCCUPAT = 13,1,0)) AS priest_case,SUM(IF(OCCUPAT = 14,1,0)) AS special_career_STI_case')
        ->selectRaw('SUM(IF(OCCUPAT = 15,1,0)) AS public_health_officer_case,SUM(IF(OCCUPAT = 11,1,0)) AS unknow_govern_case')
        ->selectRaw('SUM(IF(OCCUPAT = 10,1,0)) AS other_case')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(OCCUPAT = 1,1,0)) AS famer_case,SUM(IF(OCCUPAT = 2,1,0)) AS official_case')
        ->selectRaw('SUM(IF(OCCUPAT = 3,1,0)) AS hire_case,SUM(IF(OCCUPAT = 4,1,0)) AS trade_case')
        ->selectRaw('SUM(IF(OCCUPAT = 5,1,0)) AS housework_case,SUM(IF(OCCUPAT = 6,1,0)) AS student_case')
        ->selectRaw('SUM(IF(OCCUPAT = 7,1,0)) AS soldier_case,SUM(IF(OCCUPAT = 8,1,0)) AS fishing_case')
        ->selectRaw('SUM(IF(OCCUPAT = 9,1,0)) AS instructor_case,SUM(IF(OCCUPAT = 12,1,0)) AS animal_husbandry_case')
        ->selectRaw('SUM(IF(OCCUPAT = 13,1,0)) AS priest_case,SUM(IF(OCCUPAT = 14,1,0)) AS special_career_STI_case')
        ->selectRaw('SUM(IF(OCCUPAT = 15,1,0)) AS public_health_officer_case,SUM(IF(OCCUPAT = 11,1,0)) AS unknow_govern_case')
        ->selectRaw('SUM(IF(OCCUPAT = 10,1,0)) AS other_case')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['famer_case'] = $val->famer_case;
        $pt_data[$val->PROVINCE]['official_case'] = $val->official_case;
        $pt_data[$val->PROVINCE]['hire_case'] = $val->hire_case;
        $pt_data[$val->PROVINCE]['trade_case'] = $val->trade_case;
        $pt_data[$val->PROVINCE]['housework_case'] = $val->housework_case;
        $pt_data[$val->PROVINCE]['student_case'] = $val->student_case;
        $pt_data[$val->PROVINCE]['soldier_case'] = $val->soldier_case;
        $pt_data[$val->PROVINCE]['fishing_case'] = $val->fishing_case;
        $pt_data[$val->PROVINCE]['instructor_case'] = $val->instructor_case;
        $pt_data[$val->PROVINCE]['animal_husbandry_case'] = $val->animal_husbandry_case;
        $pt_data[$val->PROVINCE]['priest_case'] = $val->priest_case;
        $pt_data[$val->PROVINCE]['special_career_STI_case'] = $val->special_career_STI_case;
        $pt_data[$val->PROVINCE]['public_health_officer_case'] = $val->public_health_officer_case;
        $pt_data[$val->PROVINCE]['unknow_govern_case'] = $val->unknow_govern_case;
        $pt_data[$val->PROVINCE]['other_case'] = $val->other_case;
      }

      foreach ($get_provincename_th as $key => $value) {
        if (array_key_exists($key, $pt_data)) {
          $excel_data[$key] = $pt_data[$key];
        }else{
          $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                      'PROVINCE' => $get_provincename_th[$key],
                                      'famer_case' => "0",'official_case' => "0",
                                      'hire_case' => "0",'trade_case' => "0",'housework_case' => "0",'student_case' => "0",'soldier_case' => "0",
                                      'fishing_case' => "0",'instructor_case' => "0",'animal_husbandry_case' => "0",'priest_case' => "0",'special_career_STI_case' => "0",
                                      'public_health_officer_case' => "0",'unknow_govern_case' => "0",'other_case' => "0"
                              );
        }
      }
      //Year to DC
      $year_th = $tblYear+543;
      //filename
      $filename = 'sick-by-occupation'.'-year-'.$year_th;
      //sheetname
      $sheetname = 'sheet1';

      // header text
      $header_text = "ตารางข้อมูลจำนวนป่วยแยกตามอาชีพ โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
       })->download('csv');
  }
  public static function get_patient_sick_by_sex($select_year,$disease_code){
    $tblYear = (isset($select_year))? $select_year : date('Y')-1;
    $post_disease_code = (isset($disease_code))? $disease_code : "01";
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(SEX = "1",1,0)) AS male,SUM(IF(SEX = "2",1,0)) AS female')
        ->selectRaw('SUM(IF(SEX <> "" OR SEX IS NOT NULL,1,0)) AS total_sex_case')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(SEX = "1",1,0)) AS male,SUM(IF(SEX = "2",1,0)) AS female')
        ->selectRaw('SUM(IF(SEX <> "" OR SEX IS NOT NULL,1,0)) AS total_sex_case')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
        foreach ($query[0] as $key => $val) {
          $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
          $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
          $pt_data[$val->PROVINCE]['male'] = $val->male;
          $pt_data[$val->PROVINCE]['female'] = $val->female;
          $pt_data[$val->PROVINCE]['total_sex_case'] = $val->total_sex_case;
        }

        foreach ($get_provincename_th as $key => $value) {
          if (array_key_exists($key, $pt_data)) {
            $excel_data[$key] = $pt_data[$key];
          }else{
            $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                        'PROVINCE' => $get_provincename_th[$key],
                                        'male' => "0",
                                        'female' => "0",
                                        'total_sex_case' => "0",
                                );
          }
        }
          //dd($excel_data);
          return $excel_data;

  }
  public static function xls_patient_sick_by_sex(Request $request){
    if(empty($request->select_year) || empty($request->disease_code)) return false;
    $post_disease_code = $request->disease_code;
    $tblYear = $request->select_year;
    $disease_name =\App\Http\Controllers\Controller::All_disease()->toArray();
    $get_dpc_nameth = \App\Http\Controllers\Controller::get_dpc_nameth()->toArray();
    $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();
    //Col A1 Excel
    $excel_data[] = array('DPC','Reporting Area','ชาย','หญิง','รวม'
                         );
    //Check Disease
    $disease_code =  explode(",",$post_disease_code);
    if(count($disease_code)>2){
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(SEX = "1",1,0)) AS male,SUM(IF(SEX = "2",1,0)) AS female')
        ->selectRaw('SUM(IF(SEX <> "" OR SEX IS NOT NULL,1,0)) AS total_sex_case')
        ->whereIn('DISEASE',$disease_code)
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }else{
        $query[] = DB::table('ur506_'.$tblYear)
        ->select('prov_dpc','PROVINCE')
        ->selectRaw('SUM(IF(SEX = "1",1,0)) AS male,SUM(IF(SEX = "2",1,0)) AS female')
        ->selectRaw('SUM(IF(SEX <> "" OR SEX IS NOT NULL,1,0)) AS total_sex_case')
        ->where('DISEASE','=',$disease_code['0'])
        ->join('c_province','ur506_'.$tblYear.'.PROVINCE','=','c_province.prov_code')
        ->groupBy('PROVINCE')
        ->get();
    }
      foreach ($query[0] as $key => $val) {
        $pt_data[$val->PROVINCE]['prov_dpc'] = $val->prov_dpc;
        $pt_data[$val->PROVINCE]['PROVINCE'] = $get_provincename_th[$val->PROVINCE];
        $pt_data[$val->PROVINCE]['male'] = $val->male;
        $pt_data[$val->PROVINCE]['female'] = $val->female;
        $pt_data[$val->PROVINCE]['total_sex_case'] = $val->total_sex_case;
      }
      foreach ($get_provincename_th as $key => $value) {
        if (array_key_exists($key, $pt_data)) {
          $excel_data[$key] = $pt_data[$key];
        }else{
          $excel_data[$key] = array(  'prov_dpc'=>$get_dpc_nameth[$key],
                                      'PROVINCE' => $get_provincename_th[$key],
                                      'male' => "0",
                                      'female' => "0",
                                      'total_sex_case' => "0",
                              );
        }

      }
      //Year to DC
      $year_th = $tblYear+543;
      //filename
      $filename = 'sick-by-sex'.'-year-'.$year_th;
      //sheetname
      $sheetname = 'sheet1';

      // header text
      $header_text = "ตารางข้อมูลจำนวนป่วยแยกตามเพศ โรค ".$disease_name[$post_disease_code]." ปี ".$year_th;

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
       })->download('csv');
  }


}
