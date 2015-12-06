<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_controller.php';

class ForgetPassword extends Base_Controller {

	public function index()
	{
		$input = !empty($this->input->get()) ? $this->input->get() : array();
		$data = array_merge(array(), $input);
		if (!isset($data['user'])) {
			$data['error'] = 'Invalid Session';
		} else {
			$existed = $this->student_model->checkUserExisted(array('Username' => $data['user']));
			if (!$existed['existed']) {
				$data['error'] = 'Invalid User';
			}
		}

		echo $this->m->render('forgetPassword', $data);
	}

	public function rePassword()
	{
		$input = !empty($this->input->get()) ? $this->input->get() : array();
		$data = array_merge(array(), $input);

		$success = $this->student_model->rePassword($input['password'], $input['user']);

		echo "<script type='text/javascript'>alert('Successfully changed password!'); window.location.href='http://www.thevschool.com/';</script>";
	}
}
