<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Base_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{	
		$data['path'] = $this->getSiteUrl() . 'login/verifyLogin';

		echo $this->m->render('login', $data);
	}

	public function verifyLogin()
	{
		redirect('/home');
	}
}
