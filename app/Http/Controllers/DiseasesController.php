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

	public function top10DiseasePatientYear($year=0) {
		$result = DB::table('ur_count_all')
		->where('c_year', $year)
		->orderBy('total', 'desc')
		->limit(10)
		->get()
		->toArray();
		return $result;
	}

	public function top10DiseasePatientWeek($tblYear=0, $week=0) {
		$result =  DB::table('ur506_'.$tblYear)
		// $result = DB::select('select DISEASE,week_no,sum(if(week_no="" or week_no is not null,1,0)) as total_week from ? where week_no=? group by DISEASE order by sum(if(week_no = "" or week_no is not null,1,0)) desc limit 10', [$tbl,$week]);
		->select('DISEASE', 'week_no', DB::raw('sum(if(week_no="" or week_no is not null, 1, 0)) as total_week'))
		->where('week_no', $week)
		->groupBy('DISEASE')
		->orderBy(DB::raw('sum(if(week_no="" or week_no is not null,1,0))'), 'desc')
		->limit(10)
		->get();
		dd($result);
		return $result;
	}

	public function setAgeRange() {
		$range = array(
			'g1'=>'<5',
			'g2'=>'5-9',
			'g3'=>'10-14',
			'g4'=>'15-24',
			'g5'=>'25-34',
			'g6'=>'35-44',
			'g7'=>'45-54',
			'g8'=>'55-64',
			'g9'=>'65>'
		);
		return $range;
	}

	public function setMonthLabel() {
		$lblMonth = array(
			1=>'มค.',
			2=>'กพ.',
			3=>'มี.ค.',
			4=>'เม.ย.',
			5=>'พ.ค.',
			6=>'มิ.ย.',
			7=>'ก.ค.',
			8=>'ส.ค.',
			9=>'ก.ย.',
			10=>'ต.ค.',
			11=>'พ.ย.',
			12=>'ธ.ค.'
		);
		return $lblMonth;
	}

	public function totalPopByAgegroup($year=0) {
		$result = DB::table('pop_urban_age_group')
		->select('age_0_4', 'age_5_9', 'age_10_14', 'age_15_24', 'age_25_34', 'age_35_44', 'age_45_54', 'age_55_64', 'age_65_up' )
		->where('year_', $year)
		->get()
		->toArray();
		return $result;
	}

	public function getLastWeek($year=0) {
		$result = DB::table('ur506_'.$year)
		->select(DB::raw('MAX(week_no) AS lastweek'))
		->get()
		->toArray();
		return $result;
	}
}
