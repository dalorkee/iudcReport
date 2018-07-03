<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DiseasesController extends Controller
{
	public function diseaseGroup() {
		$dsgroups = DB::table('dsgr')->orderBy('DISNAME')->get();
		return $dsgroups;
	}

	/* public function getPatientByDisease($tblYear=null, $diseaseCode=null) {
		$patient = DB::table('ur506_'.$tblYear)->where('DISEASE', $diseaseCode)->orderBy('DISNAME')->get();
		return $patient;
	} */

	public function countPatientBySex($tblYear=null, $diseaseCode=null, $sex=null) {
		$count = DB::table('ur506_'.$tblYear)
			->whereIn('DISEASE', [$diseaseCode])
			->where('SEX', $sex)
			->count();
		return $count;
	}

	public function countPatientByAgegroup($tblYear=null, $diseaseCode=null, $condition=array()) {
		switch ($condition[0]) {
			case "<":
				$count = DB::table('ur506_'.$tblYear)
					->whereIn('DISEASE', [$diseaseCode])
					->where('YEAR', '<', $condition[1])
					->count();
				break;
			case "between":
				$count = DB::table('ur506_'.$tblYear)
					->where('DISEASE', $diseaseCode)
					->whereBetween('YEAR', [$condition[1], $condition[2]])
					->count();
				break;
			case ">":
				$count = DB::table('ur506_'.$tblYear)
					->whereIn('DISEASE', [$diseaseCode])
					->where('YEAR', '>', $condition[1])
					->count();
				break;
		}
		// dd($count);
		return $count;
	}

	public function countPatientPerMonth($tblYear=null, $diseaseCode=null) {
		$count = DB::table('ur506_'.$tblYear)
			->select(DB::raw('SUM(IF(DISEASE <> "", 1, 0)) AS amount, MONTH(datesick) AS month, DISEASE As disease'))
			->whereIn('DISEASE', [$diseaseCode])
			->groupBy(DB::raw('MONTH(DATESICK)'))
			->orderBy(DB::raw('MONTH(DATESICK)'))
			->get()
			->toArray();
		return $count;
	}

	public function countCaseDeadtPerMonth($tblYear=null, $diseaseCode=null) {
		$count = DB::table('ur506_'.$tblYear)
			->select(DB::raw('SUM(IF(DISEASE <> "", 1, 0)) AS amount, MONTH(DATEDEATH) AS month, DISEASE As disease'))
			->where('RESULT', 2)
			->whereIn('DISEASE', [$diseaseCode])
			->groupBy(DB::raw('MONTH(DATEDEATH)'))
			->orderBy(DB::raw('MONTH(DATEDEATH)'))
			->get()
			->toArray();
		return $count;
	}

	public function countPatientPerWeek($tblYear=null, $diseaseCode=null) {
		$count = DB::table('ur506_'.$tblYear)
			->select(DB::raw('SUM(IF(DISEASE <> "", 1, 0)) AS amount, week_no AS weeks, DISEASE As disease'))
			->whereIn('DISEASE', [$diseaseCode])
			->groupBy('week_no')
			->orderBy('week_no')
			->get()
			->toArray();
		return $count;
	}

	public function chArrToStr($arr=array()) {
		$str = null;
		if (sizeof($arr) > 0) {
			foreach($arr as $val) {
				if (is_null($str)) {
					$str = "";
				} else {
					$str = $str.", ";
				}
				$str = $str.$val;
			}
		}
		return $str;
	}

	public function thProvince() {
		return $result = DB::table('c_province')->get()->toArray();
	}

	public function top10DiseasePatient($year=null) {
		$result = DB::table('ur_count_all')
		->where('c_year', $year)
		->orderBy('total', 'desc')
		->limit(10)
		->get()
		->toArray();
		return $result;
	}
}
