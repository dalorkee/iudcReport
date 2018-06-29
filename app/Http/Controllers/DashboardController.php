<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class dashboardController extends DiseasesController
{
	public function index(Request $request) {
		if (isset($request) && isset($request->year)) {
			$dsgroups = $this->getDsNameByDsGroup();
			$countPatientBySex = $this->getCountPatientBySex($request->year, $request->disease);
			$countPatientByAgegroup = $this->getCountPatientByAgegroup($request->year, $request->disease);
			$countPatientPerMonth = $this->getCountPatientPerMonth($request->year, $request->disease);
			$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth($request->year, $request->disease);
			$countPatientPerWeek = $this->getCountPatientPerWeek($request->year, $request->disease);
			$selectDs = array('disease'=>$request->disease, 'selectYear'=>$request->year, 'selected'=>true);
		} else {
			$dsgroups = $this->getDsNameByDsGroup();
			$countPatientBySex = $this->getCountPatientBySex('2018', 78);
			$countPatientByAgegroup = $this->getCountPatientByAgegroup('2018', 78);
			$countPatientPerMonth = $this->getCountPatientPerMonth('2018', 78);
			$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth('2018', 78);
			$countPatientPerWeek = $this->getCountPatientPerWeek('2018', 78);
			$selectDs = array('disease'=>78, 'selectYear'=>'2018', 'selected'=>false);
		}

		return view('
					frontend.dashboard', [
						'dsgroups' => $dsgroups,
						'cpBySex' => $countPatientBySex,
						'cpByAge' => $countPatientByAgegroup,
						'cpPerMonth' => $countPatientPerMonth,
						'cDeadPerMonth' => $countCaseDeadPerMonth,
						'cpPerWeek' => $countPatientPerWeek,
						'selectDs' => $selectDs
					]
				);
	}

	public function getCountPatientBySex($tblYear, $diseaseCode) {
		$malePatient = parent::countPatientBySex($tblYear, $diseaseCode, 1);
		$femalePatient = parent::countPatientBySex($tblYear, $diseaseCode, 2);
		$result = array('patient' => array('male' => $malePatient, 'female' => $femalePatient));
		return $result;
	}

	public function getCountPatientByAgegroup($tblYear, $diseaseCode) {
		$g1 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('<', 5));
		$g2 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 5, 9));
		$g3 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 10, 14));
		$g4 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 15, 24));
		$g5 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 15, 34));
		$g6 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 35, 44));
		$g7 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 45, 54));
		$g8 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 55, 64));
		$g9 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('>', 64));
		$result = array('g1'=>$g1, 'g2'=>$g2, 'g3'=>$g3, 'g4'=>$g4, 'g5'=>$g5, 'g6'=>$g6, 'g7'=>$g7, 'g8'=>$g8, 'g9'=>$g9);
		return $result;
	}

	public function divPop($int=0) {
		if ($int != 0) {
			$result = (int)(100000/$int);
		} else {
			$result = 0;
		}
		return $result;
	}

	public function getCountPatientPerMonth($tblYear, $diseaseCode) {
		$result = parent::countPatientPerMonth($tblYear, $diseaseCode);
		return $result;
	}

	public function getCountCaseDeadPerMonth($tblYear, $diseaseCode) {
		$result = parent::countCaseDeadtPerMonth($tblYear, $diseaseCode);
		return $result;
	}

	public function getCountPatientPerWeek($tblYear, $diseaseCode) {
		$result = parent::countPatientPerWeek($tblYear, $diseaseCode);
		return $result;
	}

	public function getDsNameByDsGroup() {
		$dsgroups = parent::diseaseGroup();
		$result = array();
		foreach ($dsgroups as $val) {
			$tmpKey = $val->DISEASE;
			$tmpVal = array('ds_id' => $val->DISEASE, 'ds_name'=>$val->DISNAME, 'ds_group'=>$val->gr);
			$result[(int)$tmpKey] = $tmpVal;
		}
		//dd($result);
		return $result;
	}

}
