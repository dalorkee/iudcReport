<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DateTime;

class OnePageController extends DiseasesController
{
	/*
	* Display a listing of the resource.
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request) {
		$dsgroups = $this->getDsNameByDsGroup();
		if (isset($request) && isset($request->year)) {
			$selectDs = array('disease'=>$request->disease, 'selectYear'=>$request->year, 'selected'=>true);
		} else {
			$nowYear = parent::getLastUr506Year();
			$selectDs = array('disease'=>78, 'selectYear'=>$nowYear, 'selected'=>false);
		}

		$patientOnYear = $this->getPatientPerYear('2017', '02');
		$patientPerProv = $this->getPatientPerProv('2017', '02');
		$caseDead = $this->getCntCaseResult('2017', '02', 1);
		$patientBySex = $this->getPatientBySexType('2017', '02');
		return view(
			'frontend.onePageReport',
			[
				'dsgroups'=>$dsgroups,
				'selectDs'=>$selectDs,
				'patientOnYear'=>$patientOnYear,
				'patientPerProv'=>$patientPerProv,
				'caseDead'=>$caseDead,
				'patientBySex'=>$patientBySex
			]
		);
	}

	/*
	* Show the form for creating a new resource.
	* @return \Illuminate\Http\Response
	*/
	public function create() {
		//
	}

	/*
	* Store a newly created resource in storage.
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request) {
		//
	}

	/*
	* Display the specified resource.
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id) {
		//
	}

	/*
	* Show the form for editing the specified resource.
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id) {
		//
	}

	/*
	* Update the specified resource in storage.
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id) {
		//
	}

	/*
	* Remove the specified resource from storage.
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id) {
		//
	}

	public function getDsNameByDsGroup() {
		$dsgroups = parent::diseaseGroup();
		$result = array();
		foreach ($dsgroups as $val) {
			$tmpKey = $val->DISEASE;
			$tmpVal = array('ds_id' => $val->DISEASE, 'ds_name'=>$val->DISNAME, 'ds_group'=>$val->gr);
			$result[(int)$tmpKey] = $tmpVal;
		}
		return $result;
	}

	public function getPatientPerYear($year, $diseaseCode) {
		$patientOnYear = parent::patientByYear($year, $diseaseCode);
		$result['patientThisYear'] = number_format((int)$patientOnYear[0]->patient);
		$result['minDate'] = parent::cvDateToTH($patientOnYear[0]->minDate);
		$result['maxDate'] = parent::cvDateToTH($patientOnYear[0]->maxDate);
		return $result;
	}

	public function getPatientPerProv($year, $diseaseCode) {
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode);
		$result['cntProv'] = count($cntPatient);
		$sumPatient = 0;
		foreach ($cntPatient as $val) {
			$sumPatient += $val->patient;
		}
		$result['totalPatient'] = $sumPatient;
		return $result;
	}

	public function getCntCaseResult($year, $diseaseCode, $result506) {
		$cntCaseResult = parent::countCaseResultPerProv($year, $diseaseCode, $result506);
		$sumCaseDead = 0;
		foreach ($cntCaseResult as $val) {
			$sumCaseDead += $val->patient;
		}
		$result['caseDead'] = $sumCaseDead;
		return $result;
	}

	public function getPatientBySexType($year, $diseaseCode) {
		$male = parent::countPatientBySex($year, $diseaseCode, '1');
		$female = parent::countPatientBySex($year, $diseaseCode, '2');
		if ($male < $female) {
			$tmp = ($female/$male);
			$ratio = '1:'.rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
		} else {
			$tmp = ($male/$female);
			$ratio = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".").':1';
		}

		$result['year'] = $year;
		$result['disease'] = $diseaseCode;
		$result['ptMale'] = $male;
		$result['ptFemale'] = $female;
		$result['ratio'] = $ratio;
		return $result;
	}

}
