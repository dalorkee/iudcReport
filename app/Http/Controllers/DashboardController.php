<?php

namespace App\Http\Controllers;

class dashboardController extends DiseasesController
{

	public function index() {
		/* get disease all group */
		$dsgroups = $this->getDiseaseGroup();
		/* get count patient by sex */
		$countPatientBySex = $this->getCountPatientBySex();
		$countPatientByAgegroup = $this->getCountPatientByAgegroup();
		$countPatientPerMonth = $this->getCountPatientPerMonth();
		$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth();
		$countPatientPerWeek = $this->getCountPatientPerWeek();

		return view('
					frontend.dashboard', [
						'dsgroups' => $dsgroups,
						'cpBySex' => $countPatientBySex,
						'cpByAge' => $countPatientByAgegroup,
						'cpPerMonth' => $countPatientPerMonth,
						'cDeadPerMonth' => $countCaseDeadPerMonth,
						'cpPerWeek' => $countPatientPerWeek
					]
				);
	}

	public function getDiseaseGroup() {
		$result = parent::diseaseGroup();
		return $result;
	}

	public function getCountPatientBySex() {
		$malePatient = parent::countPatientBySex(2018, '02', 1);
		$femalePatient = parent::countPatientBySex(2018, '02', 2);
		$result = array('patient' => array('male' => $malePatient, 'female' => $femalePatient));

		return $result;
	}

	public function getCountPatientByAgegroup() {
		$g1 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('<', 5)));
		$g2 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 5, 9)));
		$g3 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 10, 14)));
		$g4 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 15, 24)));
		$g5 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 15, 34)));
		$g6 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 35, 44)));
		$g7 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 45, 54)));
		$g8 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('between', 55, 64)));
		$g9 = $this->divPop(parent::countPatientByAgegroup(2018, '02', array('>', 64)));
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

	public function getCountPatientPerMonth() {
		$result = parent::countPatientPerMonth('2017', '03');
		return $result;
	}

	public function getCountCaseDeadPerMonth() {
		$result = parent::countCaseDeadtPerMonth('2017', '31');
		return $result;
	}

	public function getCountPatientPerWeek() {
		$result = parent::countPatientPerWeek('2017', '31');
		return $result;
	}

}
