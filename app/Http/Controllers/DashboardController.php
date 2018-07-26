<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DateTime;

class dashboardController extends DiseasesController
{
	public function index(Request $request) {
		$dsgroups = $this->getDsNameByDsGroup();
		$ageRange = parent::setAgeRange();
		$monthLabel = parent::setMonthLabel();
		$thProv = $this->getThProvince();
		
		if (isset($request) && isset($request->year)) {
			$countPatientBySex = $this->getCountPatientBySex($request->year, $request->disease);
			$countPatientByAgegroup = $this->getCountPatientByAgegroup($request->year, $request->disease);
			$countPatientPerMonth = $this->getCountPatientPerMonth($request->year, $request->disease);
			$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth($request->year, $request->disease);
			$countPatientPerWeek = $this->getCountPatientPerWeek($request->year, $request->disease);
			$selectDs = array('disease'=>$request->disease, 'selectYear'=>$request->year, 'selected'=>true);
		} else {
			$nowYear = parent::getLastUr506Year();
			$countPatientBySex = $this->getCountPatientBySex($nowYear, 78);
			$countPatientByAgegroup = $this->getCountPatientByAgegroup($nowYear, 78);
			$countPatientPerMonth = $this->getCountPatientPerMonth($nowYear, 78);
			$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth($nowYear, 78);
			$countPatientPerWeek = $this->getCountPatientPerWeek($nowYear, 78);
			$selectDs = array('disease'=>78, 'selectYear'=>$nowYear, 'selected'=>false);
		}

		return view('frontend.dashboard',
			[
				'dsgroups'=>$dsgroups,
				'thProv'=>$thProv,
				'cpBySex'=>$countPatientBySex,
				'cpByAge'=>$countPatientByAgegroup,
				'cpPerMonth'=>$countPatientPerMonth,
				'cDeadPerMonth'=>$countCaseDeadPerMonth,
				'cpPerWeek'=>$countPatientPerWeek,
				'selectDs'=>$selectDs,
				'ageRange' => $ageRange,
				'monthLabel' => $monthLabel
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
		$popTotalByAgegroup = $this->getTotalPopByAgegroup($tblYear);
		if ($popTotalByAgegroup != 0) {
			/* gruop1 */
			$g1 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('<', 5));
			$g1 = number_format((((int)$g1*100000)/(int)$popTotalByAgegroup[0]->age_0_4), 2, '.', '');
			/* group2 */
			$g2 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 5, 9));
			$g2 = number_format((((int)$g2*100000)/(int)$popTotalByAgegroup[0]->age_5_9), 2, '.', '');
			/* group3 */
			$g3 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 10, 14));
			$g3 = number_format((((int)$g3*100000)/(int)$popTotalByAgegroup[0]->age_10_14), 2, '.', '');
			/* group4 */
			$g4 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 15, 24));
			$g4 = number_format((((int)$g4*100000)/(int)$popTotalByAgegroup[0]->age_15_24), 2, '.', '');
			/* group5 */
			$g5 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 25, 34));
			$g5 = number_format((((int)$g5*100000)/(int)$popTotalByAgegroup[0]->age_25_34), 2, '.', '');
			/* group6 */
			$g6 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 35, 44));
			$g6 = number_format((((int)$g6*100000)/(int)$popTotalByAgegroup[0]->age_35_44), 2, '.', '');
			/* group7 */
			$g7 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 45, 54));
			/* group8 */
			$g7 = number_format((((int)$g7*100000)/(int)$popTotalByAgegroup[0]->age_45_54), 2, '.', '');
			$g8 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 55, 64));
			$g8 = number_format((((int)$g8*100000)/(int)$popTotalByAgegroup[0]->age_55_64), 2, '.', '');
			/* group9 */
			$g9 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('>', 64));
			$g9 = number_format((((int)$g9*100000)/(int)$popTotalByAgegroup[0]->age_65_up), 2, '.', '');
			$result = array('g1'=>$g1, 'g2'=>$g2, 'g3'=>$g3, 'g4'=>$g4, 'g5'=>$g5, 'g6'=>$g6, 'g7'=>$g7, 'g8'=>$g8, 'g9'=>$g9);
		} else {
			$result = array('g1'=>0, 'g2'=>0, 'g3'=>0, 'g4'=>0, 'g5'=>0, 'g6'=>0, 'g7'=>0, 'g8'=>0, 'g9'=>0);
		}
		return $result;
	}

	public function getCountPatientPerMonth($tblYear, $diseaseCode) {
		$result = $this->setDefaultMonth($tblYear);
		$count_arr = parent::countPatientPerMonth($tblYear, $diseaseCode);
		foreach ($count_arr as $val) {
			$result[$val->month] = (int)$val->amount;
		}
		return $result;
	}

	public function getCountCaseDeadPerMonth($tblYear, $diseaseCode) {
		$result = $this->setDefaultMonth($tblYear);
		$count_arr = parent::countCaseDeadtPerMonth($tblYear, $diseaseCode);
		foreach ($count_arr as $val) {
			$result[$val->month] = (int)$val->amount;
		}
		return $result;
	}

	public function getCountPatientPerWeek($tblYear, $diseaseCode) {
		/* get this week */
		$date = new DateTime();
		$nowYear = $date->format('Y');
		// $nowWeek = $date->format('W');
		$r506Lastweek = parent::getLastWeek($tblYear);
		$lastWeek = (int)$r506Lastweek[0]->lastweek;


		$week_arr = array();
		for ($i=1; $i<=$lastWeek; $i++) {
			$week_arr[$i] = 0;
		}

		$count_arr = parent::countPatientPerWeek($tblYear, $diseaseCode);

		foreach ($count_arr as $val) {
			$week = $val->weeks;

			$week_arr[(int)$week] = (int)$val->amount;
		}
		return $week_arr;
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

	public function setDefaultMonth($year=null) {
		$result = array();
		$nowYear = date('Y');
		$nowMonth = date('n');
		$iMonth = 12;
		if ($year == $nowYear) {
			$iMonth = $nowMonth;
		}
		for ($i=1; $i<=$iMonth; $i++) {
			$result[$i] = 0;
		}
		return $result;
	}

	public function getThProvince() {
		$result = parent::thProvince();
		return $result;
	}

	public function getTotalPopByAgegroup($year=0) {
		$total = parent::totalPopByAgegroup($year);
		if (count($total) <= 0) {
			$nowYear = date('Y');
			if ($year == $nowYear) {
				$newYear = ($year-1);
				$total = parent::totalPopByAgegroup($newYear);
				if (count($total) <= 0) {
					$total = 0;
				}
			} else {
				$total = 0;
			}
		}
		return $total;
	}


}
