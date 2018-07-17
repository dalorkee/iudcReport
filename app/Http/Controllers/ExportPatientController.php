<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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






}
