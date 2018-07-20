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


    public static function get_patient_sick_death_by_month(Request $request)
    {
      $tblYear = $request->select_year;
      $disease_code = $request->disease_code;

      // SELECT
      // ur506_2012.DISEASE,
      // ur506_2012.PROVINCE,
      // sum(if(MONTH(ur506_2012.DATESICK) = 1,1,0)) as case_jan,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 1,1,0)) as death_jan,
      // sum(if(MONTH(ur506_2012.DATESICK) = 2,1,0)) as case_feb,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 2,1,0)) as death_feb,
      // sum(if(MONTH(ur506_2012.DATESICK) = 3,1,0)) as case_mar,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 3,1,0)) as death_mar,
      // sum(if(MONTH(ur506_2012.DATESICK) = 4,1,0)) as case_apr,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 4,1,0)) as death_apr,
      // sum(if(MONTH(ur506_2012.DATESICK) = 5,1,0)) as case_may,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 5,1,0)) as death_may,
      // sum(if(MONTH(ur506_2012.DATESICK) = 6,1,0)) as case_jun,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 6,1,0)) as death_jun,
      // sum(if(MONTH(ur506_2012.DATESICK) = 7,1,0)) as case_jul,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 7,1,0)) as death_jul,
      // sum(if(MONTH(ur506_2012.DATESICK) = 8,1,0)) as case_aug,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 8,1,0)) as death_aug,
      // sum(if(MONTH(ur506_2012.DATESICK) = 9,1,0)) as case_sep,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 9,1,0)) as death_sep,
      // sum(if(MONTH(ur506_2012.DATESICK) = 10,1,0)) as case_oct,
      // sum(if(MONTH(ur506_2012.DATEDEATH) =10,1,0)) as death_oct,
      // sum(if(MONTH(ur506_2012.DATESICK) = 11,1,0)) as case_nov,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 11,1,0)) as death_nov,
      // sum(if(MONTH(ur506_2012.DATESICK) = 12,1,0)) as case_dec,
      // sum(if(MONTH(ur506_2012.DATEDEATH) = 12,1,0)) as death_dec
      // FROM
      // ur506_2012
      // WHERE ur506_2012.DISEASE = '26'
      // GROUP BY PROVINCE
      // ORDER BY PROVINCE ASC

      $count = DB::table('ur506_'.$tblYear)

        ->select('DISEASE', 'PROVINCE')
        ->selectRaw('sum(if(MONTH(DATESICK) = 1,1,0)) as case_jan')
        ->selectRaw('sum(if(MONTH(DATEDEATH) = 1,1,0)) as death_jan')

        ->whereIn('DISEASE',['26','27','66'])
        ->whereIn('PROVINCE',['50','51','52','53'])
        ->groupBy('PROVINCE')
  			->get()
  			->toArray();
  		//return $count;


      //dd($count);

      $data[] = array('id'=> '1',
      'name' => 'sataphat',
      'email1' => 'sataphat@debv.com',
      'email2' => 'sataphat@debv.com',
      'email3' => 'sataphat@debv.com',
      'email4' => 'sataphat@debv.com',
      'email5' => 'sataphat@debv.com',
      'email6' => 'sataphat@debv.com',
      'email7' => 'sataphat@debv.com',
      'email8' => 'sataphat@debv.com',
      'email9' => 'sataphat@debv.com',
      'email10' => 'sataphat@debv.com',
      'email11' => 'sataphat@debv.com',
      'email12' => 'sataphat@debv.com',
      'email13' => 'sataphat@debv.com',
      'email14' =>'sataphat@debv.com',
      'email15' => 'sataphat@debv.com',
      'email16' => 'sataphat@debv.com',
      'email17' => 'sataphat@debv.com',
      'email18' => 'sataphat@debv.com',
      'email19' => 'sataphat@debv.com',
      'email20' => 'sataphat@debv.com',
      'email21' => 'sataphat@debv.com',
      'email22' => 'sataphat@debv.com',
      'email23' => 'sataphat@debv.com',
      'email24' => 'sataphat@debv.com',
      'email25' => 'sataphat@debv.com'
    );
      $data[] = array('id'=> '2',
      'name' => 'sataphat',
      'email1' => 'sataphat@debv.com',
      'email2' => 'sataphat@debv.com',
      'email3' => 'sataphat@debv.com',
      'email4' => 'sataphat@debv.com',
      'email5' => 'sataphat@debv.com',
      'email6' => 'sataphat@debv.com',
      'email7' => 'sataphat@debv.com',
      'email8' => 'sataphat@debv.com',
      'email9' => 'sataphat@debv.com',
      'email10' => 'sataphat@debv.com',
      'email11' => 'sataphat@debv.com',
      'email12' => 'sataphat@debv.com',
      'email13' => 'sataphat@debv.com',
      'email14' =>'sataphat@debv.com',
      'email15' => 'sataphat@debv.com',
      'email16' => 'sataphat@debv.com',
      'email17' => 'sataphat@debv.com',
      'email18' => 'sataphat@debv.com',
      'email19' => 'sataphat@debv.com',
      'email20' => 'sataphat@debv.com',
      'email21' => 'sataphat@debv.com',
      'email22' => 'sataphat@debv.com',
      'email23' => 'sataphat@debv.com',
      'email24' => 'sataphat@debv.com',
      'email25' => 'sataphat@debv.com'
    );
      //$data = array();
      //dd($data);
      return response()->json(['data' => $data]);
    }






}
