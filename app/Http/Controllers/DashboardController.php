<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DateTime;

class dashboardController extends DiseasesController
{
	public function index(Request $request) {
		/* get Disease group name */
		$dsgroups = $this->getDsNameByDsGroup();
		/* set age range */
		$ageRange = parent::setAgeRange();
		/* get month name */
		$monthLabel = parent::setMonthLabel();
		/* get th province name */
		$thProv = $this->getThProvince();
		/* request var from view */
		if (isset($request) && isset($request->year)) {
			$rqYear = $request->year;
			$ds = $request->disease;
			$selected = true;
		} else {
			$nowYear = parent::getLastUr506Year();
			$rqYear = $nowYear;
			$ds = 78;
			$selected = false;
		}
		$selectDs = array('disease'=>$ds, 'selectYear'=>$rqYear, 'selected'=>$selected);
		/* pass var for get the result */
		$countPatientBySex = $this->getCountPatientBySex($rqYear, $ds);
		$countPatientByAgegroup = $this->getCountPatientByAgegroup($rqYear, $ds);
		$countPatientPerMonth = $this->getCountPatientPerMonth($rqYear, $ds);
		$countCaseDeadPerMonth = $this->getCountCaseDeadPerMonth($rqYear, $ds);
		$countPatientPerWeek = $this->getCountPatientPerWeek($rqYear, $ds);
		$patientMap = $this->getPatientMap($rqYear, $ds);
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
				'monthLabel' => $monthLabel,
				'patientMap'=>$patientMap
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

	private function getPatientMap($year, $diseaseCode) {
		$result['disease'] = $diseaseCode;

		/* get provice */
		$lstProv = parent::getProvince();
		foreach ($lstProv as $val) {
			$prov[$val->prov_code] = $val->prov_name_en;
		}
		/* count patient per province */
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode);

		/* list for get max&&min value */
		$amount_arr = array();
		foreach ($cntPatient as $val) {
			if ((int)$val->patient > 0) {
				array_push($amount_arr, (int)$val->patient);
			}
		}
		$maxAmount = max($amount_arr);
		$minAmount = min($amount_arr);

		/* set formular for render the map */
		$x = (($maxAmount-$minAmount)/5);
		$r1 = $minAmount+$x;
		$r2 = (($r1)+$x);
		$r3 = (($r2)+$x);
		$r4 = (($r3)+$x);
		$r5 = (($r4)+$x);
		$color = array(
			'r1'=>'#A1DF96',
			'r2'=>'#438722',
			'r3'=>'#FBBC05',
			'r4'=>'#F85F1F',
			'r5'=>'#D1202E'
		);
		for ($i=0; $i<count($cntPatient); $i++) {
			if ($cntPatient[$i]->patient <= $r1) {
				$mapColor = $color['r1'];
			} elseif ($cntPatient[$i]->patient <= $r2) {
				$mapColor = $color['r2'];
			} elseif ($cntPatient[$i]->patient <= $r3) {
				$mapColor = $color['r3'];
			} elseif ($cntPatient[$i]->patient <= $r4) {
				$mapColor = $color['r4'];
			} elseif ($cntPatient[$i]->patient <= $r5) {
				$mapColor = $color['r5'];
			}

			$cntPatient[$i]->prov_name_en = $prov[$cntPatient[$i]->province];
			$cntPatient[$i]->mapColor = $mapColor;
		}
		$result['patient'] = $cntPatient;
		return $result;
	}


}
