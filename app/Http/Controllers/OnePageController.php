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
	public $rqDsCode;
	public function __construct() {
		$this->rqDsCode = null;
		return true;
	}
	public function index(Request $request) {
		/* get Disease group name */
		$dsgroups = $this->getDsNameByDsGroup();
		/* request var from view */
		if (isset($request->requestFrm) && $request->requestFrm == "1") {
			$rqYear = $request->year;
			$selected_week = true;
			$selected_year = true;
			if ((int)$request->disease > 0) {
				$ds = array($request->disease);
			} else {
				$ds = parent::setMultiDiseaseCode($request->disease);
			}
			$this->rqDsCode = $request->disease;
			$selected_ds = true;
			if ($request->week_number != 'all') {
				$week_arr = array();
				for ($i=1; $i<=$request->week_number; $i++) {
					array_push($week_arr, sprintf('%02d', $i));
				}
				$rqWeek = $week_arr;
				$str_week = (int)max($rqWeek);
			} else {
				$rqWeek = $request->week_number;
				$str_week = $request->week_number;
			}
		} else {
			$nowYear = parent::getLastUr506Year();
			$rqYear = $nowYear;
			$ds = array(78);
			$this->rqDsCode = $ds[0];
			$rqWeek = 'all';
			$str_week = 'all';
			$selected_ds = false;
			$selected_week = false;
			$selected_year = false;
		}
		/* set the last result request var to array */
		$selectDs = array(
			'disease'=>$this->rqDsCode,
			'selectWeek'=>$rqWeek,
			'str_week'=>$str_week,
			'selectYear'=>$rqYear,
			'selected_ds'=>$selected_ds,
			'selected_week'=>$selected_week,
			'selected_year'=>$selected_year
		);
		/* get result send to view */
		$patientOnYear = $this->getPatientPerYear($rqYear, $ds, $rqWeek);
		$patientPerProv = $this->getPatientPerProv($rqYear, $ds, $rqWeek);
		$caseDead = $this->getCntCaseResult($rqYear, $ds, 2, $rqWeek);
		$patientBySex = $this->getPatientBySexType($rqYear, $ds, $rqWeek);
		$patientByAgeGroup = $this->getPatientByAgeGroup($rqYear, $ds, $rqWeek);
		$patientByNation = $this->getPatientByNation($rqYear, $ds, $rqWeek);
		$patientByOccupation = $this->getPatientByOccupation($rqYear, $ds, $rqWeek);
		$top5PtByYear = $this->getTop5PtByYear($rqYear, $ds, $rqWeek);
		$patientByProvRegion = $this->getPatientByProvRegion($rqYear, $ds, $rqWeek);
		$patientOnLastWeek = $this->getPatientOnLastWeek($rqYear, $ds);
		$patientPerWeek = $this->getPatientPerWeek($rqYear, $ds, $rqWeek);
		$patientMap = $this->getPatientMap($rqYear, $ds, $rqWeek);
		$patientByWeek = $this->getPatientOnWeek($rqYear, $ds, $str_week);
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
				'patintPerWeek'=>$patientPerWeek,
				'patientMap'=>$patientMap,
				'patientByWeek'=>$patientByWeek
			]
		);
	}

	public function getDsNameByDsGroup() {
		$dsgroups = parent::diseaseGroup();
		$result = array();
		foreach ($dsgroups as $val) {
			$tmpKey = $val->DISEASE;
			$tmpVal = array('ds_id' => $val->DISEASE, 'ds_name'=>$val->DISNAME, 'ds_group'=>$val->gr);
			$result[$tmpKey] = $tmpVal;
		}
		return $result;
	}

	private function getPatientPerYear($year, $diseaseCode, $week_no) {
		$result['Year'] = $year;
		$popOnYearCall = parent::sumPopByAgegroup($year);
		$popOnYear = (int)$popOnYearCall[0]->popOnYear;
		$result['popOnYear'] = $popOnYear;
		$patientOnYearCall = parent::patientByYear($year, $diseaseCode, $week_no);
		$patientOnYear = (int)$patientOnYearCall[0]->patient;
		if ($patientOnYear > 0) {
			$minDate = parent::getMinDateSickDate($year, $diseaseCode, $week_no);
			$maxDate = parent::getMaxDateSickDate($year, $diseaseCode, $week_no);
			$result['patientThisYear'] = $patientOnYear;
			$rate = (($patientOnYear*100000)/$popOnYear);
			$result['rate'] = $rate;
			$result['minDate'] = parent::cvDateToTH($minDate[0]->minDate);
			$result['maxDate'] = parent::cvDateToTH($maxDate[0]->maxDate);
		} else {
			$dateRange = parent::getDateRangeByWeek($year, $week_no);
			$cntDateRange = count($dateRange);
			$result['patientThisYear'] = 0;
			$result['rate'] = 0;
			$result['minDate'] = parent::cvDateToTH($dateRange[0]->DATESICK);
			$result['maxDate'] =parent::cvDateToTH($dateRange[((int)$cntDateRange-1)]->DATESICK);
		}
		return $result;
	}

	private function getPatientPerProv($year, $diseaseCode, $week_no) {
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode, $week_no);
		$result['cntProv'] = count($cntPatient);
		$sumPatient = 0;
		if ($result['cntProv'] > 0) {
			foreach ($cntPatient as $val) {
				$sumPatient += $val->patient;
			}
		}
		$result['totalPatient'] = $sumPatient;
		return $result;
	}

	private function getCntCaseResult($year, $diseaseCode, $result506, $week_no) {
		$cntCaseResult = parent::countCaseResultPerProv($year, $diseaseCode, $result506, $week_no);
		$cntCase = count($cntCaseResult);
		$sumCaseDead = 0;
		if ($cntCase > 0) {
			foreach ($cntCaseResult as $val) {
				$sumCaseDead += $val->patient;
			}
		}
		$result['caseDead'] = $sumCaseDead;
		return $result;
	}

	private function getPatientBySexType($year, $diseaseCode, $week_no) {
		$male = parent::countPatientBySex($year, $diseaseCode, '1', $week_no);
		$female = parent::countPatientBySex($year, $diseaseCode, '2', $week_no);
		if ($male > 0 && $female > 0) {
			if ($male < $female) {
				$tmp = ($female/$male);
				$ratio = '1 : '.rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
			} else {
				$tmp = ($male/$female);
				$ratio = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".").' : 1';
			}
		} else {
			$ratio = 0;
		}
		$result['year'] = $year;
		$result['disease'] = $this->rqDsCode;
		$result['ptMale'] = $male;
		$result['ptFemale'] = $female;
		$result['ratio'] = $ratio;
		return $result;
	}

	private function getPatientByAgeGroup($year, $diseaseCode, $week_no) {
		$patient = $this->setCountPatientByAgegroup($year, $diseaseCode, $week_no);
		$i = 0;
		foreach ($patient as $key=>$val) {
			$i += (float)$val;
		}
		foreach ($patient as $key=>$val) {
			switch ($key) {
				case 'g1':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['0-4'] = $rs;
					break;
				case 'g2':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['5-9'] = $rs;
					break;
				case 'g3':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['10-14'] = $rs;
					break;
				case 'g4':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['15-24'] = $rs;
					break;
				case 'g5':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['25-34'] = $rs;
					break;
				case 'g6':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['35-44'] = $rs;
					break;
				case 'g7':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['45-54'] = $rs;
					break;
				case 'g8':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['55-64'] = $rs;
					break;
				case 'g9':
					if ($val > 0) {
						$tmp = (((float)$val/$i)*100);
						$rs = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
					} else {
						$rs = $val;
					}
					$result['65'] = $rs;
					break;
			}
		}
		arsort($result);
		return $result;
	}

	private function setCountPatientByAgegroup($tblYear, $diseaseCode, $week_no) {
		$popTotalByAgegroup = $this->setTotalPopByAgegroup($tblYear);
		if ($popTotalByAgegroup != 0) {
			/* gruop1 */
			$g1 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('<', 5), $week_no);
			$g1 = number_format((((int)$g1*100000)/(int)$popTotalByAgegroup[0]->age_0_4), 2, '.', '');
			/* group2 */
			$g2 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 5, 9), $week_no);
			$g2 = number_format((((int)$g2*100000)/(int)$popTotalByAgegroup[0]->age_5_9), 2, '.', '');
			/* group3 */
			$g3 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 10, 14), $week_no);
			$g3 = number_format((((int)$g3*100000)/(int)$popTotalByAgegroup[0]->age_10_14), 2, '.', '');
			/* group4 */
			$g4 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 15, 24), $week_no);
			$g4 = number_format((((int)$g4*100000)/(int)$popTotalByAgegroup[0]->age_15_24), 2, '.', '');
			/* group5 */
			$g5 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 25, 34), $week_no);
			$g5 = number_format((((int)$g5*100000)/(int)$popTotalByAgegroup[0]->age_25_34), 2, '.', '');
			/* group6 */
			$g6 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 35, 44), $week_no);
			$g6 = number_format((((int)$g6*100000)/(int)$popTotalByAgegroup[0]->age_35_44), 2, '.', '');
			/* group7 */
			$g7 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 45, 54), $week_no);
			$g7 = number_format((((int)$g7*100000)/(int)$popTotalByAgegroup[0]->age_45_54), 2, '.', '');
			/* group8 */
			$g8 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('between', 55, 64), $week_no);
			$g8 = number_format((((int)$g8*100000)/(int)$popTotalByAgegroup[0]->age_55_64), 2, '.', '');
			/* group9 */
			$g9 = parent::countPatientByAgegroup($tblYear, $diseaseCode, array('>', 64), $week_no);
			$g9 = number_format((((int)$g9*100000)/(int)$popTotalByAgegroup[0]->age_65_up), 2, '.', '');
			/* set result */
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

	private function getPatientByNation($year, $diseaseCode, $week_no) {
		/* get nation from db */
		$c_nation = parent::getNation();
		foreach ($c_nation as $val) {
			$nation[$val->cdnation] = $val->nation;
		}
		/* get patient by nation */
		$patient = parent::cntPatientByNation($year, $diseaseCode, $week_no);
		$cntPatient = count($patient);
		$sumPatient = 0;
		if ($cntPatient > 0) {
			foreach ($patient as $val) {
				$sumPatient += (int)$val->patient;
			}
			foreach ($patient as $val) {
				$tmp = (((float)$val->patient/$sumPatient)*100);
				$result[$nation[$val->RACE]] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
			}
			arsort($result);
		} else {
			$result = false;
		}
		return $result;
	}

	private function getPatientByOccupation($year, $diseaseCode, $week_no) {
		/* get occupation from db */
		$occ = parent::getOccupation();
		foreach ($occ as $val) {
			$occupation[$val->cdocc] = $val->occu;
		}
		/* get patient by occupation */
		$patient = parent::cntPatientByOccupation($year, $diseaseCode, $week_no);
		$cntPatient = count($patient);
		$sumPatient = 0;
		if ($cntPatient > 0) {
			foreach ($patient as $val) {
				$sumPatient += (int)$val->patient;
			}
			foreach ($patient as $val) {
				$tmp = (((float)$val->patient/$sumPatient)*100);
				$result[$occupation[$val->OCCUPAT]] = rtrim(rtrim((string)number_format($tmp, 2, ".", ""),"0"),".");
			}
			arsort($result);
		} else {
			$result = false;
		}
		return $result;
	}

	private function getTop5PtByYear($year, $diseaseCode, $week_no) {
		/* get province from db */
		$getProv = parent::getProvince();
		foreach ($getProv as $val) {
			$prov[$val->prov_code] = $val->prov_name;
		}
		/* get population set per province */
		$lstPopPerProv = parent::totalPopPerProv($year);
		foreach ($lstPopPerProv as $val) {
			$popPerProv[$val->prov_code] = (int)$val->pop;
		}
		/* set patient per year */
		$ptPerYear = parent::cntPatientPerYear($year, $diseaseCode, $week_no='all');
		$cntPtPerYear = count($ptPerYear);
		if ($cntPtPerYear > 0) {
			$top5PtPerYear = array_slice($ptPerYear, 0, 5, true);
			foreach ($top5PtPerYear as $val) {
				$tmp = (($val->amount*100000)/$popPerProv[$val->PROVINCE]);
				$result[$prov[$val->PROVINCE]] = $tmp;
			}
		} else {
			$result = false;
		}
		arsort($result);
		return $result;
	}

	private function getPatientByProvRegion($year, $diseaseCode, $week_no) {
		/* get provice by db */
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
		$nPt = parent::countPatientByProv($year, $north, $diseaseCode, $week_no);
		$cPt = parent::countPatientByProv($year, $central, $diseaseCode, $week_no);
		$nePt = parent::countPatientByProv($year, $northEastern, $diseaseCode, $week_no);
		$sPt = parent::countPatientByProv($year, $sourhern, $diseaseCode, $week_no);

		/* pop per region */
		$nPop = parent::sumPopByProvCode($north, $year);
		$cPop = parent::sumPopByProvCode($central, $year);
		$nePop = parent::sumPopByProvCode($northEastern, $year);
		$sPop = parent::sumPopByProvCode($sourhern, $year);

		/* north result */
		if (count($nPop) > 0) {
			$nRegion = (((int)$nPt[0]->amount*100000)/(int)$nPop[0]->pop);
			$nRegion = $nRegion;
		} else {
			$nRegion = 0;
		}
		/* center result */
		if (count($cPop) > 0) {
			$cRegion = (((int)$cPt[0]->amount*100000)/(int)$cPop[0]->pop);
			$cRegion = $cRegion;
		} else {
			$cRegion = 0;
		}
		/* north-east result */
		if (count($nePop) > 0) {
			$neRegion = (((int)$nePt[0]->amount*100000)/(int)$nePop[0]->pop);
			$neRegion = $neRegion;
		} else {
			$neRegion = 0;
		}
		/* south result */
		if (count($sPop) > 0) {
			$sRegion = (((int)$sPt[0]->amount*100000)/(int)$sPop[0]->pop);
			$sRegion = $sRegion;
		} else {
			$sRegion = 0;
		}
		$result = array(
			'ภาคกลาง'=>$cRegion,
			'ภาคเหนือ'=>$nRegion,
			'ภาคใต้'=>$sRegion,
			'ภาคตะวันออกเฉียงเหนือ'=>$neRegion
		);
		arsort($result);
		return $result;
	}

	private function getPatientOnLastWeek($year, $diseaseCode) {
		$week_arr = parent::getLastWeek($year);
		$week_no = array($week_arr[0]->lastweek);
		$dateRange = parent::getDateRangeByWeek($year, $week_no);
		$result['year'] = ((int)$year+543);
		$result['date_start'] = parent::cvDateToTH($dateRange[0]->DATESICK);
		$result['date_end'] = parent::cvDateToTH($dateRange[(count($dateRange)-1)]->DATESICK);
		$patientAllWeek = parent::countPatientPerWeek($year, $diseaseCode);
		$cntWeek = count($patientAllWeek);
		$result['patient'] = number_format($patientAllWeek[($cntWeek-1)]->amount);
		return $result;
	}

	private function getPatientOnWeek($year, $diseaseCode, $week_no) {
		if ($week_no != 'all') {
			$cntPt = parent::patientByWeek($week_no, $year, $diseaseCode);
			$dateList = parent::getDateRangeByWeek($year, array($week_no));
			$cntDateRange = count($dateList);
			$sDate = parent::cvDateToTH($dateList[0]->DATESICK);
			$eDate = parent::cvDateToTH($dateList[((int)$cntDateRange)-1]->DATESICK);
			$dateRange = $sDate." - ".$eDate;
			$ptByWeek = array('week'=>$week_no, 'dateRange'=>$dateRange, 'cntPatient'=>$cntPt[0]->patient);
		} else {
			$ptByWeek = 'all';
		}
		return $ptByWeek;
	}

	private function getPatientPerWeek($year, $diseaseCode, $week_no) {
		/* get provice from db */
		$lstProv = parent::getProvince();
		/* split prov_code per region to array */
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
		/* get max week */
		if ($week_no == "all") {
			$maxWeek_arr = parent::getLastWeek($year);
			$maxWeek = $maxWeek_arr[0]->lastweek;
		} else {
			$maxWeek = max($week_no);
		}
		$maxWeek = (int)$maxWeek;
		/* center region */
		$ptCWeek_coll = parent::getPatientPerWeekByProvZone($year, $diseaseCode, $central, $week_no);
		if (count($ptCWeek_coll) > 0) {
			foreach ($ptCWeek_coll as $val) {
				$ptCWeek_arr[(int)$val->week_no] = (int)$val->amount;
			}
			for ($i=1; $i<=$maxWeek; $i++) {
				if (array_key_exists($i, $ptCWeek_arr)) {
					$ptCWeek[$i] = $ptCWeek_arr[$i];
				} else {
					$ptCWeek[$i] = 0;
				}
			}
		} else {
			for ($i=1; $i<=$maxWeek; $i++) {
				$ptCWeek[$i] = 0;
			}
		}
		/* north region */
		$ptNWeek_coll = parent::getPatientPerWeekByProvZone($year, $diseaseCode, $north, $week_no);
		if (count($ptNWeek_coll) > 0) {
			foreach ($ptNWeek_coll as $val) {
				$ptNWeek_arr[(int)$val->week_no] = (int)$val->amount;
			}
			for ($i=1; $i<=$maxWeek; $i++) {
				if (array_key_exists($i, $ptNWeek_arr)) {
					$ptNWeek[$i] = $ptNWeek_arr[$i];
				} else {
					$ptNWeek[$i] = 0;
				}
			}
		} else {
			for ($i=1; $i<=$maxWeek; $i++) {
				$ptNWeek[$i] = 0;
			}
		}
		/* northEastern region */
		$ptNeWeek_coll = parent::getPatientPerWeekByProvZone($year, $diseaseCode, $northEastern, $week_no);
		if (count($ptNeWeek_coll) > 0) {
			foreach ($ptNeWeek_coll as $val) {
				$ptNeWeek_arr[(int)$val->week_no] = (int)$val->amount;
			}
			for ($i=1; $i<=$maxWeek; $i++) {
				if (array_key_exists($i, $ptNeWeek_arr)) {
					$ptNeWeek[$i] = $ptNeWeek_arr[$i];
				} else {
					$ptNeWeek[$i] = 0;
				}
			}
		} else {
			for ($i=1; $i<=$maxWeek; $i++) {
				$ptNeWeek[$i] = 0;
			}
		}
		/* sourhern region */
		$ptSWeek_coll = parent::getPatientPerWeekByProvZone($year, $diseaseCode, $sourhern, $week_no);
		if (count($ptSWeek_coll) > 0) {
			foreach ($ptSWeek_coll as $val) {
				$ptSWeek_arr[(int)$val->week_no] = (int)$val->amount;
			}
			for ($i=1; $i<=$maxWeek; $i++) {
				if (array_key_exists($i, $ptSWeek_arr)) {
					$ptSWeek[$i] = $ptSWeek_arr[$i];
				} else {
					$ptSWeek[$i] = 0;
				}
			}
		} else {
			for ($i=1; $i<=$maxWeek; $i++) {
				$ptSWeek[$i] = 0;
			}
		}
		/* sumary region */
		for ($i=1; $i<=$maxWeek; $i++) {
			$ptTotal[$i] = $ptCWeek[$i]+$ptNWeek[$i]+$ptNeWeek[$i]+$ptSWeek[$i];
		}
		/* set result */
		$result['ptCWeek'] = $ptCWeek;
		$result['ptNWeek'] = $ptNWeek;
		$result['ptNeWeek'] = $ptNeWeek;
		$result['ptSWeek'] = $ptSWeek;
		$result['ptTotal'] =  $ptTotal;
		return $result;
	}

	private function getPatientMap($year, $diseaseCode, $week_no='all') {
		/* set disease name to array */
		$dsgroup = $this->getDsNameByDsGroup();
		$cntDs = count($diseaseCode);
		if ($cntDs == 1 ) {
			$result['disease'] = $dsgroup[$diseaseCode[0]];
		} else {
			$result['disease'] = $dsgroup[$this->rqDsCode];
		}
		/* set provice to array */
		$lstProv = parent::getProvince();
		foreach ($lstProv as $val) {
			$prov[$val->prov_code] = $val->prov_name_en;
		}
		/* set poputation per province to array */
		$totalPop = parent::totalPopPerProv($year);
		foreach ($totalPop as $key=>$val) {
			$popPerProv[$val->prov_code] = (int)$val->pop;
		}
		/* resetup population per province is missing add it to 0 */
		foreach ($prov as $key=>$val) {
			if (array_key_exists($key, $popPerProv)) {
				$pop_prov[$key] = (int)$popPerProv[$key];
			} else {
				$pop_prov[$key] = 0;
			}
		}
		/* count patient per province */
		$cntPatient = parent::countPatientPerProv($year, $diseaseCode, $week_no);
		$countPatient = count($cntPatient);
		if ($countPatient > 0) {
			/* push patient to array */
			$amount_arr = array();
			foreach ($cntPatient as $val) {
				array_push($amount_arr, (int)$val->patient);
			}
			/* push patient to associative array */
			foreach ($cntPatient as $val) {
				$provPerPt[$val->province] = (int)$val->patient;
			}
			/* set prov => patient */
			foreach ($lstProv as $val) {
				if (array_key_exists($val->prov_code, $provPerPt)) {
					$lstPtPerProv[$val->prov_code] = $provPerPt[$val->prov_code];
				} else {
					$lstPtPerProv[$val->prov_code] = 0;
				}
			}
			/* set map color */
			$color = array(
				'r1'=>'#A1DF96',
				'r2'=>'#438722',
				'r3'=>'#FBBC05',
				'r4'=>'#F85F1F',
				'r5'=>'#D1202E'
			);
			$result['colors'] = $color;
			/* set patient formular */
			foreach ($lstPtPerProv as $key=>$val) {
				if ($val <= 0 || $pop_prov[$key] <= 0) {
					$ptPerPop[$key] = 0;
				} else {
					$ptPerPop[$key] = (($val*100000)/$pop_prov[$key]);
				}
			}
			/* set max && min patient amount */
			$maxPatient = max($ptPerPop);
			$minPatient = min($ptPerPop);
			/* set length for render the map color */
			if ($diseaseCode[0] == 66) {
				foreach ($ptPerPop as $key=>$val) {
					if ( $val <= 0) {
						$mapColor = $color['r1'];
					} elseif ($val <= 50) {
						$mapColor = $color['r2'];
					} elseif ($val <= 100) {
						$mapColor = $color['r3'];
					} elseif ($val <= 150) {
						$mapColor = $color['r4'];
					} elseif ($val > 150) {
						$mapColor = $color['r5'];
					} else {
						$mapColor = '#000000';
					}
					$rs['prov_code'] = $key;
					$rs['prov_name_en'] = $prov[$key];
					$rs['color'] = $mapColor;
					$rs['amount'] = $lstPtPerProv[$key];
					$rs['rate'] = $val;
					$prov_rs[$key] = $rs;
				}
				$result['range'] = array('<0', '1-50', '51-100', '101-150', '150+');
				$result['patient'] = $prov_rs;
			} else {
				$x = (($maxPatient-$minPatient)/5);
				$r1 = ($minPatient+$x);
				$r2 = ($r1+$x);
				$r3 = ($r2+$x);
				$r4 = ($r3+$x);
				$r5 = ($r4+$x);
				foreach ($ptPerPop as $key => $val) {
					if ($val <= $r1) {
						$mapColor = $color['r1'];
					} elseif ($val <= $r2) {
						$mapColor = $color['r2'];
					} elseif ($val <= $r3) {
						$mapColor = $color['r3'];
					} elseif ($val <= $r4) {
						$mapColor = $color['r4'];
					} elseif ($val > $r4) {
						$mapColor = $color['r5'];
					} else {
						$mapColor = '#000000';
					}
					$rs['prov_code'] = $key;
					$rs['prov_name_en'] = $prov[$key];
					$rs['color'] = $mapColor;
					$rs['amount'] = $lstPtPerProv[$key];
					$rs['rate'] = $val;
					$prov_rs[$key] = $rs;
				}
				/* set legend on the map */
				$rg1 = number_format($minPatient, 2)."-".number_format($r1, 2);
				$rg2 = number_format(($r1+0.01), 2)."-".number_format($r2, 2);
				$rg3 = number_format(($r2+0.01), 2)."-".number_format($r3, 2);
				$rg4 = number_format(($r3+0.01), 2)."-".number_format($r4, 2);
				$rg5 = number_format(($r4+0.01), 2)."+";
				$result['range'] = array($rg1, $rg2, $rg3, $rg4, $rg5);
				$result['patient'] = $prov_rs;
			}
		} else {
			$result = false;
		}
		return $result;
	}


}
