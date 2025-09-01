<?php
namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use Request;

class CBHook extends Controller {

	/*
	| --------------------------------------
	| Please note that you should re-login to see the session work
	| --------------------------------------
	|
	*/
	public function afterLogin() {

	}
}