<?php

namespace App\Http\Controllers;

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


    // public static function get_patient_sick_death_by_month_not_use(Request $request)
    // {
    //   $tblYear = $request->select_year;
    //   $disease_code = $request->disease_code;
    //
    //
    //   $data = DB::table('ur506_'.$tblYear)
    //
    //     ->select('DISEASE', 'PROVINCE')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan,sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 2,1,0)) as case_feb,sum(if(MONTH(DATESICK) = 2,1,0)) as death_feb')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 3,1,0)) as case_mar,sum(if(MONTH(DATESICK) = 3,1,0)) as death_mar')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 4,1,0)) as case_apr,sum(if(MONTH(DATESICK) = 4,1,0)) as death_apr')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 5,1,0)) as case_may,sum(if(MONTH(DATESICK) = 5,1,0)) as death_may')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun,sum(if(MONTH(DATESICK) = 6,1,0)) as case_jun')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 7,1,0)) as case_jul,sum(if(MONTH(DATEDEATH) = 7,1,0)) as death_jul')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 8,1,0)) as case_aug,sum(if(MONTH(DATEDEATH) = 8,1,0)) as death_aug')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 9,1,0)) as case_sep,sum(if(MONTH(DATEDEATH) = 9,1,0)) as death_sep')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 10,1,0)) as case_oct,sum(if(MONTH(DATEDEATH) =10,1,0)) as death_oct')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 11,1,0)) as case_nov,sum(if(MONTH(DATEDEATH) = 11,1,0)) as death_nov')
    //     ->selectRaw('sum(if(MONTH(DATESICK) = 12,1,0)) as case_dec,sum(if(MONTH(DATEDEATH) = 12,1,0)) as death_dec')
    //     ->whereIn('DISEASE',['26','27','66'])
    //     ->whereIn('PROVINCE',['50','51','52','53'])
    //     ->groupBy('PROVINCE')
  	// 		->get()
  	// 		->toArray();
    //
    //   $recordsTotal = count($data);
    //
    //   //dd($data);
    //   return response()->json(['draw'=> 1,
    //                            "recordsTotal" => $recordsTotal,
    //                            "recordsFiltered" => $recordsTotal,
    //                            'data' => $data]);
    // }

    public static function post_patient_sick_death_by_month($select_year,$disease_code)
    {
      //dd($select_year);
      // $select_year = $request->select_year;
      // $disease_code = $request->disease_code;

      $tblYear = (isset($select_year))? $select_year : date('Y')-1;
      $arr_disease_code = (isset($disease_code))? $disease_code : "01";
      //$disease_code = $request->disease_code;
      //$tblYear  = 2012;
      $get_pop_dpc_group =\App\Http\Controllers\Controller::get_pop_dpc_group();
      $get_provincename_th =\App\Http\Controllers\Controller::get_provincename_th()->toArray();

      foreach ($get_pop_dpc_group as $dpc_code => $dpc_val)
      {
        $data[] = DB::table('ur506_'.$tblYear)

          ->select('DISEASE', 'PROVINCE')
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
          ->whereIn('DISEASE',['26','27','66'])
          ->whereIn('PROVINCE',$dpc_val)
          ->groupBy('PROVINCE')
          //->OrderBy('PROVINCE')
    			->get();
      }
  			//->toArray();

      //    dd($data[0]);
        $arr_dpc_th = array('สคร.1','สคร.2','สคร.3','สคร.4','สคร.5','สคร.6','สคร.7','สคร.8','สคร.9','สคร.10','สคร.11','สคร.12','สปคม.');
         for($i=0;$i<count($data);$i++ ){
          foreach ($data[$i] as $data_key => $data_val)
          {
              $total_case = $data_val->case_jan+$data_val->case_feb+$data_val->case_mar+$data_val->case_apr+$data_val->case_may+$data_val->case_jun+$data_val->case_jul+$data_val->case_aug+$data_val->case_sep+$data_val->case_oct+$data_val->case_nov+$data_val->case_dec;
              $total_death = $data_val->death_jan+$data_val->death_feb+$data_val->death_mar+$data_val->death_apr+$data_val->death_may+$data_val->death_jun+$data_val->death_jul+$data_val->death_aug+$data_val->death_sep+$data_val->death_oct+$data_val->death_nov+$data_val->death_dec;
              $data2[] = array('DPC'=> $arr_dpc_th[$i],'DISEASE' => $data_val->DISEASE,'PROVINCE' => $get_provincename_th[$data_val->PROVINCE],
                               'case_jan' => $data_val->case_jan,'death_jan' => $data_val->death_jan,'case_feb' => $data_val->case_feb,'death_feb'=>$data_val->death_feb,
                               'case_mar' => $data_val->case_mar,'death_mar' =>$data_val->death_mar,'case_apr'=>$data_val->case_apr,'death_apr'=>$data_val->death_apr,
                               'case_may' => $data_val->case_may,'death_may'=>$data_val->death_may,'case_jun'=>$data_val->case_jun,'death_jun'=>$data_val->death_jun,
                               'case_jul' => $data_val->case_jul,'death_jul'=>$data_val->death_jul,'case_aug'=>$data_val->case_aug,'death_aug'=>$data_val->death_aug,
                               'case_sep' => $data_val->case_sep,'death_sep'=>$data_val->death_sep,'case_oct'=>$data_val->case_oct,'death_oct'=>$data_val->death_oct,
                               'case_nov' => $data_val->case_nov,'death_nov'=>$data_val->death_nov,'case_dec'=>$data_val->case_dec,'death_dec'=>$data_val->death_dec,
                               'total_case' => "$total_case", 'total_death' =>"$total_death"
                              );
          }
         }
      //return view('patient-sick-death-month');
      return $data2;
      //dd($data);
      // return response()->json(['draw'=> 1,
      //                          "recordsTotal" => $recordsTotal,
      //                          "recordsFiltered" => $recordsTotal,
      //                          'data' => $data2]);
    }




}
