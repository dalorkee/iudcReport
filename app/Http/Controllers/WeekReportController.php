<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WeekReportController extends DiseasesController
{
	/*
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	*/
	public function index(Request $request) {
		$dsName = parent::getDiseaseName();
		$top10DsPtYear = $this->top10DsPtYear($request);
		$top10DsPtWeek = $this->top10DsPtWeek($request);
		$listWeek = $this->getListUr506WeekFromYear($request);
		return view('frontend.top10DiseasePatient',
			[
				'dsName'=>$dsName,
				'top10DsPtYear'=>$top10DsPtYear,
				'top10DsPtWeek'=>$top10DsPtWeek,
				'listWeek'=>$listWeek
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

	public function top10DsPtYear($request) {
		if (isset($request->year) || !empty($request->year)) {
			$year = $request->year;
		} else {
			$year = parent::getLastUr506Year();
		}
		/* get Pop for sum pop */
		$popTotalByAgegroup = $this->getTotalPopByAgegroup($year);
		$sumPopTotalByAgegroup = 0;
		foreach ($popTotalByAgegroup[0] as $key=>$val) {
			$sumPopTotalByAgegroup += (int)$val;
		}

		$Rawtop10DsPt = parent::top10DiseasePatientYear($year);
		$top10DsPt = array();
		foreach ($Rawtop10DsPt as $val) {
			$top10DsPt[$val->DISNAME] = (((int)$val->total*100000)/$sumPopTotalByAgegroup);
		}
		return $top10DsPt;
	}

	public function top10DsPtWeek($request) {
		if (isset($request->year) || !empty($request->year)) {
			$year = $request->year;
		} else {
			$year = parent::getLastUr506Year();
		}
		/* get Pop for sum pop */
		$popTotalByAgegroup = $this->getTotalPopByAgegroup($year);
		$sumPopTotalByAgegroup = 0;
		foreach ($popTotalByAgegroup[0] as $key=>$val) {
			$sumPopTotalByAgegroup += (int)$val;
		}

		$Rawtop10DsPtWeek = parent::top10DiseasePatientWeek($year, '05');
		$top10DsPtWeek = array();
		foreach ($Rawtop10DsPtWeek as $val) {
			$top10DsPtWeek[(int)$val->DISEASE] = (((int)$val->total_week*100000)/$sumPopTotalByAgegroup);
		}
		//dd($top10DsPtWeek);
		return $top10DsPtWeek;
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

	public function getListUr506WeekFromYear($request) {
		if (isset($request->year) || !empty($request->year)) {
			$year = $request->year;
		} else {
			$year = parent::getLastUr506Year();
		}
		$listWeekFromYear = array();
		$listWeek = parent::listUr506WeekFromYear($year);
		$i = 0;
		foreach ($listWeek as $val) {
			$cv = parent::cvDateToTH($val->DATESICK);
			$listWeek[$i]->DATESICK = $cv;
			$i++;
		}
		$cntListWeek = count($listWeek);
		$listWeekFromYear['year'] = $year;
		$listWeekFromYear['firstWeek'] = $listWeek[0];
		$listWeekFromYear['lastWeek'] = $listWeek[($cntListWeek-1)];
		$listWeekFromYear['allWeek'] = $listWeek;
		$lastWeekDateRage = parent::getDateRangeByWeek($year, array($listWeekFromYear['lastWeek']->week_no));
		$i = 0;
		foreach ($lastWeekDateRage as $val) {
			$cv = parent::cvDateToTH($val->DATESICK);
			$lastWeekDateRage[$i]->DATESICK = $cv;
			$i++;
		}
		$listWeekFromYear['lastWeekAllDate'] = $lastWeekDateRage;
		return $listWeekFromYear;
	}
}
