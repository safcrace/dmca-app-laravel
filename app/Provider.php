<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model {

	/**
	* No timestamps for provider.
	**/
	public $timestamps = false;

	/**
	* Fillable fields for a provider
	**/
	protected $fillable = [
		'name',
		'copyright_email'
	];


}
