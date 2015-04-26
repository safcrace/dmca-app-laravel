<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\PrepareNoticesRequest;
use App\Http\Controllers\Controller;
use App\Provider;
use App\Notice;

use Illuminate\Http\Request;
use Illuminate\Auth\Guard;
//use Illuminate\Session\SessionManager;

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
		return \Auth::user()->notices;
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
		$template = $this->compileDmcaTemplate($data = $request->all(), $auth);

		session()->flash('dmca', $data);

		return view('notices.confirm', compact('template'));
	}

	/**
	* Store a new DMCA notice.
	**/

	public function store(Request $request)
	{
		$this->createNotice($request);
		return redirect('notices');
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
		return $template;
	}


	/**
	* Create and persist a new DMCA notice
	**/
	private function createNotice(Request $request)
	{
		$data = session()->get('dmca');
		
		$notice = Notice::open($data)->useTemplate($request->input('template'));

		\Auth::user()->notices()->save($notice);

	}
}
