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

		parent::__construct();
	}


	/**
	* Show all notices
	**/
	public function index()
	{
		return $this->user->notices;
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

	public function confirm(PrepareNoticesRequest $request)
	{
		$template = $this->compileDmcaTemplate($data = $request->all());

		session()->flash('dmca', $data);

		return view('notices.confirm', compact('template'));
	}

	/**
	* Store a new DMCA notice.
	**/

	public function store(Request $request)
	{
		$notice = $this->createNotice($request);

		\Mail::queue('emails.dmca', compact('notice'), function($message) use ($notice) {
			$message->from($notice->getOwnerEmail())
					->to($notice->getRecipientEmail())
					->subject('DMCA Notice');
		});
		return redirect('notices');
	}


	/**
	* Compile the DMCA template from the form data-
	**/
	public function compileDmcaTemplate($data)
	{
		$data = $data + [
			'name' => $this->user->name,
			'email' => $this->user->email,
		];
		$template = view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
		return $template;
	}


	/**
	* Create and persist a new DMCA notice
	**/
	private function createNotice(Request $request)
	{
		$notice = session()->get('dmca') + ['template' => $request->input('template')];

		$notice = $this->user->notices()->create($notice);

		return $notice;

	}
}
