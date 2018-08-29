<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder {
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run() {
		DB::table('users')->delete();
		DB::table('users')->insert([
			'username'=>'talek',
			'password'=>$this->generatesPassword('9kg]Hdmu,'),
			'titleName'=>'Mr',
			'firstName'=>'talek',
			'lastname'=>'team',
			'idcard'=>'1234567891234',
			'email'=>'talek@team.com',
			'ref_position'=>1,
			'ref_office'=>1,
			'user_type'=>'root',
			'user_level'=>1,
			'avatar'=>'default-avatar.png',
			'register'=>Carbon::now(),
			'created_at'=>Carbon::now()
		]);
		DB::table('users')->insert([
			'username'=>'jjila',
			'password'=>$this->generatesPassword('hinjampa'),
			'titleName'=>'นางสาว',
			'firstName'=>'จุลจิลา',
			'lastname'=>'หินจำปา',
			'idcard'=>'1234567891234',
			'email'=>'junjila@ucdd.com',
			'ref_position'=>1,
			'ref_office'=>1,
			'user_type'=>'administrator',
			'user_level'=>1,
			'avatar'=>'default-avatar.png',
			'register'=>Carbon::now(),
			'created_at'=>Carbon::now()
		]);
		/* position add */
		DB::table('position')->delete();
		DB::table('position')->insert([
			'positionName'=>'นักวิชาการสาธารณสุข',
			'positionNameEn'=>'Public health scholar'
		]);
	}

	protected function generatesPassword($password='test') {
		return bcrypt($password);
	}
}
