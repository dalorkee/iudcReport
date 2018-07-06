<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class WeekReportController extends DiseasesController
{
	/*
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	*/
	public function index() {
		//
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

	public function top10DsPt(Request $request) {
		if (isset($request->year) || !empty($request->year)) {
			$year = $request->year;
		} else {
			$year = date('Y');
		}
		/* get Pop for sum pop */
		$popTotalByAgegroup = $this->getTotalPopByAgegroup($year);
		$sumPopTotalByAgegroup = 0;
		foreach ($popTotalByAgegroup[0] as $key=>$val) {
			$sumPopTotalByAgegroup += (int)$val;
		}

		$Rawtop10DsPt = parent::top10DiseasePatient($year);
		$top10DsPt = array();
		foreach ($Rawtop10DsPt as $val) {
			$top10DsPt[$val->DISNAME] = (((int)$val->total*100000)/$sumPopTotalByAgegroup);
		}

		return view('frontend.top10DiseasePatient',
			[
				'top10DsPt'=>$top10DsPt
			]
		);
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
