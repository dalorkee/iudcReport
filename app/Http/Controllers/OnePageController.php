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
				'patientByOccupation'=>$patientByOccupation 
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

	private function getPatientByNation($year=0, $diseaseCode=0) {
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

	private function getPatientByOccupation($year=0, $diseaseCode=0) {
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


}
