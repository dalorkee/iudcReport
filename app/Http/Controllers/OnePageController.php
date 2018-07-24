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
		$patientByAgeGroup = $this->getPatientByAgeGroup('2017', '02');
		$patientByNation = $this->getPatientByNation('2017', '02');
		arsort($patientByNation);
		$patientByOccupation = $this->getPatientByOccupation('2017', '02');
		arsort($patientByOccupation);
		$top5PtByYear = $this->getTop5PtByYear('2017', '02');
		$patientByProvRegion = $this->getPatientByProvRegion('2017', '02');
		arsort($patientByProvRegion);
		$patientOnLastWeek = $this->getPatientOnLastWeek('2017', '02');
		$patintPerWeek = $this->getPatientPerWeek('2017', '02');
		$lstPatientPerProv = $this->lstPatientPerProv('2017', '02');
		return view(
			'frontend.onePageReport',
			[
				'dsgroups'=>$dsgroups,
				'selectDs'=>$selectDs,
				'patientOnYear'=>$patientOnYear,
				'patientPerProv'=>$patientPerProv,
				'caseDead'=>$caseDead,
				'patientBySex'=>$patientBySex,
				'patientByAgeGroup'=>$patientByAgeGroup,
				'patientByNation'=>$patientByNation,
				'patientByOccupation'=>$patientByOccupation,
				'top5PtByYear'=>$top5PtByYear,
				'patientByProvRegion'=>$patientByProvRegion,
				'patientOnLastWeek'=>$patientOnLastWeek,
				'patintPerWeek'=>$patintPerWeek,
				'lstPatientPerProv'=>$lstPatientPerProv
				
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

	private function getPatientPerYear($year, $diseaseCode) {
		$patientOnYear = parent::patientByYear($year, $diseaseCode);
		$result['patientThisYear'] = number_format((int)$patientOnYear[0]->patient);
		$result['minDate'] = parent::cvDateToTH($patientOnYear[0]->minDate);
		$result['maxDate'] = parent::cvDateToTH($patientOnYear[0]->maxDate);
		return $result;
	}

	private function getPatientPerProv($year, $diseaseCode) {
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode);
		$result['cntProv'] = count($cntPatient);
		$sumPatient = 0;
		foreach ($cntPatient as $val) {
			$sumPatient += $val->patient;
		}
		$result['totalPatient'] = $sumPatient;
		return $result;
	}

	private function getCntCaseResult($year, $diseaseCode, $result506) {
		$cntCaseResult = parent::countCaseResultPerProv($year, $diseaseCode, $result506);
		$sumCaseDead = 0;
		foreach ($cntCaseResult as $val) {
			$sumCaseDead += $val->patient;
		}
		$result['caseDead'] = $sumCaseDead;
		return $result;
	}

	private function getPatientBySexType($year, $diseaseCode) {
		$male = parent::countPatientBySex($year, $diseaseCode, '1');
		$female = parent::countPatientBySex($year, $diseaseCode, '2');
		if ($male < $female) {
			$tmp = ($female/$male);
			$ratio = '1 : '.rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
		} else {
			$tmp = ($male/$female);
			$ratio = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".").' : 1';
		}

		$result['year'] = $year;
		$result['disease'] = $diseaseCode;
		$result['ptMale'] = $male;
		$result['ptFemale'] = $female;
		$result['ratio'] = $ratio;
		return $result;
	}

	private function setCountPatientByAgegroup($tblYear, $diseaseCode) {
		$popTotalByAgegroup = $this->setTotalPopByAgegroup($tblYear);
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

	private function setTotalPopByAgegroup($year=0) {
		$total = parent::totalPopByAgegroup($year);
		if (count($total) <= 0) {
			$nowYear = parent::getLastUr506Year();
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

	private function getPatientByAgeGroup($year, $diseaseCode) {
		$patient = $this->setCountPatientByAgegroup($year, $diseaseCode);
		$i = 0;
		foreach ($patient as $key=>$val) {
			$i += (float)$val;
		}
		foreach ($patient as $key=>$val) {
			switch ($key) {
				case 'g1':
					$tmp = (((float)$val/$i)*100);
					$result['0-4'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g2':
					$tmp = (((float)$val/$i)*100);
					$result['5-9'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g3':
					$tmp = (((float)$val/$i)*100);
					$result['10-14'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g4':
					$tmp = (((float)$val/$i)*100);
					$result['15-24'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g5':
					$tmp = (((float)$val/$i)*100);
					$result['25-34'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g6':
					$tmp = (((float)$val/$i)*100);
					$result['35-44'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g7':
					$tmp = (((float)$val/$i)*100);
					$result['45-54'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g8':
					$tmp = (((float)$val/$i)*100);
					$result['55-64'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
				case 'g9':
					$tmp = (((float)$val/$i)*100);
					$result['65'] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					break;
			}
		}
		arsort($result);
		return $result;
	}

	private function getPatientByNation($year, $diseaseCode) {
		$c_nation = parent::getNation();
		foreach ($c_nation as $val) {
			$nation[$val->cdnation] = $val->nation;
		}
		$patient = parent::cntPatientByNation($year, $diseaseCode);
		$sumPatient = 0;
		foreach ($patient as $val) {
			$sumPatient += (int)$val->patient;
		}
		foreach ($patient as $val) {
			$tmp = (((float)$val->patient/$sumPatient)*100);
			$result[$nation[$val->RACE]] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
		}
		return $result;
	}

	private function getPatientByOccupation($year, $diseaseCode) {
		$occ = parent::getOccupation();
		foreach ($occ as $val) {
			$occupation[$val->cdocc] = $val->occu;
		}
		$patient = parent::cntPatientByOccupation($year, $diseaseCode);
		$sumPatient = 0;
		foreach ($patient as $val) {
			$sumPatient += (int)$val->patient;
		}
		foreach ($patient as $val) {
			$tmp = (((float)$val->patient/$sumPatient)*100);
			$result[$occupation[$val->OCCUPAT]] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
		}
		return $result;

	}

	private function getTop5PtByYear($year, $diseaseCode) {
		$getProv = parent::getProvince();
		$top5Pt = parent::cntTop5PatientPerYear($year, $diseaseCode);
		foreach ($getProv as $val) {
			$prov[$val->prov_code] = $val->prov_name;
		}

		$lstPopPerProv = parent::totalPopPerProv($year);
		foreach ($lstPopPerProv as $val) {
			$ptPerProv[$val->prov_code] = (int)$val->pop;
		}
		foreach ($top5Pt as $val) {
			$tmp = (($val->amount*100000)/$ptPerProv[$val->PROVINCE]);
			$result[$prov[$val->PROVINCE]] = number_format($tmp, 2);
		}
		return $result;
	}

	private function getPatientByProvRegion($year, $diseaseCode) {
		/* get provice */
		$lstProv = parent::getProvince();

		/* split prov_code per region */
		$north = array();
		$central = array();
		$northEastern = array();
		$sourhern = array();
		foreach ($lstProv as $val) {
			switch ($val->prov_zone) {
				case 'North':
					array_push($north, $val->prov_code);
					break;
				case 'Central':
					array_push($central, $val->prov_code);
					break;
				case 'North-Eastern':
					array_push($northEastern, $val->prov_code);
					break;
				case 'Sourhern':
					array_push($sourhern, $val->prov_code);
					break;
			}
		}

		/* patient per region */
		$nPt = parent::countPatientByProv($year, $north, $diseaseCode);
		$cPt = parent::countPatientByProv($year, $central, $diseaseCode);
		$nePt = parent::countPatientByProv($year, $northEastern, $diseaseCode);
		$sPt = parent::countPatientByProv($year, $sourhern, $diseaseCode);

		/* pop per region */
		$nPop = parent::sumPopByProvCode($north, $year);
		$cPop = parent::sumPopByProvCode($central, $year);
		$nePop = parent::sumPopByProvCode($northEastern, $year);
		$sPop = parent::sumPopByProvCode($sourhern, $year);

		/* result */
		$nRegion = (((int)$nPt[0]->amount*100000)/(int)$nPop[0]->pop);
		$nRegion = number_format($nRegion, 2);

		$cRegion = (((int)$cPt[0]->amount*100000)/(int)$cPop[0]->pop);
		$cRegion = number_format($cRegion, 2);

		$neRegion = (((int)$nePt[0]->amount*100000)/(int)$nePop[0]->pop);
		$neRegion = number_format($neRegion, 2);

		$sRegion = (((int)$sPt[0]->amount*100000)/(int)$sPop[0]->pop);
		$sRegion = number_format($sRegion, 2);

		$result = array(
			'ภาคกลาง'=>$cRegion,
			'ภาคเหนือ'=>$nRegion,
			'ภาคใต้'=>$sRegion,
			'ภาคตะวันออกเฉียงเหนือ'=>$neRegion
		);
		return $result;
	}

	private function getPatientOnLastWeek($year, $diseaseCode) {
		$weekNo = parent::getLastWeek($year);
		$dateRange = parent::getDateRangeByWeek($year, $weekNo[0]->lastweek);
		$result['date_start'] = parent::cvDateToTH($dateRange[0]->DATESICK);
		$result['date_end'] = parent::cvDateToTH($dateRange[(count($dateRange)-1)]->DATESICK);

		$patientAllWeek = parent::countPatientPerWeek($year, $diseaseCode);
		$cntWeek = count($patientAllWeek);
		$result['patient'] = number_format($patientAllWeek[($cntWeek-1)]->amount);
		return $result;
	}

	private function getPatientPerWeek($year, $diseaseCode) {
		/* get provice */
		$lstProv = parent::getProvince();

		/* split prov_code per region */
		$north = array();
		$central = array();
		$northEastern = array();
		$sourhern = array();
		foreach ($lstProv as $val) {
			switch ($val->prov_zone) {
				case 'North':
					array_push($north, $val->prov_code);
					break;
				case 'Central':
					array_push($central, $val->prov_code);
					break;
				case 'North-Eastern':
					array_push($northEastern, $val->prov_code);
					break;
				case 'Sourhern':
					array_push($sourhern, $val->prov_code);
					break;
			}
		}
		/* get patient by region */
		$ptCWeek = parent::getPateintPerWeekByProvZone($year, $diseaseCode, $central);
		$ptNWeek = parent::getPateintPerWeekByProvZone($year, $diseaseCode, $north);
		$ptNeWeek = parent::getPateintPerWeekByProvZone($year, $diseaseCode, $northEastern);
		$ptSWeek = parent::getPateintPerWeekByProvZone($year, $diseaseCode, $sourhern);
		$ptTotal = parent::countPatientPerWeek($year, $diseaseCode);

		foreach ($ptCWeek as $val) {
			$ptCWeek_arr[$val->week_no] = (int)$val->amount;
		}
		$result['ptCWeek'] = $ptCWeek_arr;

		foreach ($ptNWeek as $val) {
			$ptNWeek_arr[$val->week_no] = (int)$val->amount;
		}
		$result['ptNWeek'] = $ptNWeek_arr;

		foreach ($ptNeWeek as $val) {
			$ptNeWeek_arr[$val->week_no] = (int)$val->amount;
		}
		$result['ptNeWeek'] = $ptNeWeek_arr;

		foreach ($ptSWeek as $val) {
			$ptSWeek_arr[$val->week_no] = (int)$val->amount;
		}
		$result['ptSWeek'] = $ptSWeek_arr;

		foreach ($ptTotal as $val) {
			$ptTotal_arr[$val->weeks] = (int)$val->amount;
		}
		$result['ptTotal'] =  $ptTotal_arr;
		return $result;
	}

	private function lstPatientPerProv($year, $diseaseCode) {
		/* get provice */
		$lstProv = parent::getProvince();
		foreach ($lstProv as $val) {
			$prov[$val->prov_code] = $val->prov_name_en;
		}
		/* count patient per province */
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode);

		for ($i=0; $i<count($cntPatient); $i++) {
			$cntPatient[$i]->prov_name_en = $prov[$cntPatient[$i]->province];
		}
		return $cntPatient;
	}


}
