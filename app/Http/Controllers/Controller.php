<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	protected $user;

	protected $signedIn;

	/**
	* Created a new controller instance.
	**/
	public function __construct()
	{
		$this->user = $this->signedIn = \Auth::user();
	}

}
