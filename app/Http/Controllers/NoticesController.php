<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PrepareNoticesRequest;
use App\Http\Controllers\Controller;
use App\Provider;

use Illuminate\Http\Request;
use Illuminate\Auth\Guard;

class NoticesController extends Controller {


	/**
	* Create a new notices controller instance
	**/
	public function __construct()
	{
		$this->middleware('auth');
	}


	/**
	* Show all notices
	**/
	public function index()
	{
		return 'All notices';
	}


	/**
	* Show a page to create a new notice
	**/
	public function create()
	{
		//get list of priveders
		$providers = Provider::lists('name', 'id');

		//load a view to create new notice
		return view('notices.create', compact('providers'));
	}

	/**
	* Ask the user to confirm the DMCA that will be delivered
	**/

	public function confirm(PrepareNoticesRequest $request, Guard $auth)
	{
		$templaten = $this->compileDmcaTemplate($request->all(), $auth);

		return view('notices.confirm', compact('template'));
	}


	/**
	* Compile the DMCA template from the form data-
	**/
	public function compileDmcaTemplate($data, Guard $auth)
	{
		$data = $data + [
			'name' => $auth->user()->name,
			'email' => $auth->user()->email,
		];
		$template = view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
	}
}
