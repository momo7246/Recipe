<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MultiMedia extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('/login');
		}
	}

	public function index()
	{
		echo $this->m->render('multiMedia', array());
	}
}
