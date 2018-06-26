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

	public function getPatientByDisease($tblYear=null, $diseaseCode=null) {
		$patient = DB::table('ur506_'.$tblYear)->where('DISEASE', $diseaseCode)->orderBy('DISNAME')->get();
		return $patient;
	}

	public function countPatientBySex($tblYear=null, $diseaseCode=null, $sex=null) {
		$count = DB::table('ur506_'.$tblYear)
			->whereIn('DISEASE', [$diseaseCode])
			->where('SEX', $sex)
			->count();
		return $count;
	}

	public function countPatientByAgegroup($tblYear=null, $diseaseCode=null, $condition=array('operator', 'value')) {
		switch ($condition[0]) {
			case "<":
				$count = DB::table('ur506_'.$tblYear)->where([
					['DISEASE', '=', $diseaseCode],
					['age_group', $condition[0], $condition[1]]
				])->count();
				break;
			case "between":
				$count = DB::table('ur506_'.$tblYear)
					->where('DISEASE', $diseaseCode)
					->whereBetween('age_group', [$condition[1], $condition[2]])->count();
				break;
			case ">":
				$count = DB::table('ur506_'.$tblYear)->where([
					['DISEASE', '>', $diseaseCode],
					['age_group', $condition[0], $condition[1]]
				])->count();
				break;
		}
		return $count;
	}

	public function countPatientPerMonth($tblYear=null, $diseaseCode=null) {
		// SELECT COUNT(*) FROM ur506_2017 WHERE DISEASE IN('02') AND YEAR(DATESICK) = '2017' AND MONTH(DATESICK) = '01'
		/* $count = DB::table('ur506_'.$tblYear)
			->whereIn('DISEASE', [$diseaseCode])
			->whereMonth('DATESICK', $month)
			->count();
			*/
			// SELECT SUM(IF(DISEASE <> "", 1, 0)) AS amount, MONTH(datesick) AS mnt, DISEASE FROM ur506_2017 WHERE DISEASE IN('03') GROUP BY MONTH(datesick)
			$count = DB::table('ur506_'.$tblYear)
				->select(DB::raw('SUM(IF(DISEASE <> "", 1, 0)) AS amount, MONTH(datesick) AS mnt, DISEASE As ds'))
				->whereIn('DISEASE', [$diseaseCode])
				->groupBy(DB::raw('MONTH(DATESICK)'))
				->get()->toArray();
				// dd($count);
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
}
