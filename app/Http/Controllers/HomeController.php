<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	* Show the application dashboard.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request) {
		//return view('frontend.dashboard');
		$position_id = Auth::user()->ref_position;
		$this->setUserPositionSession($request, $position_id);
		return redirect()->action('DashboardController@index');
	}

	/* get user position */
	protected function getUserPosition($position_id=0) {
		$position = DB::table('position')
		->where('id', $position_id)
		->orderBy('id')
		->get();
		return $position;
	}

	protected function setUserPositionSession($request, $position_id) {
		$position = $this->getUserPosition($position_id);
		return $request->session()->put(
			'userPosition',
			array(
				$position[0]->id,
				$position[0]->pos_name,
				$position[0]->pos_name_en
			)
		);
	}
}
