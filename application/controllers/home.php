<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Base_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['name'] = ['momo', 'phone'];
		
		echo $this->m->render('home', $data);
	}
}
