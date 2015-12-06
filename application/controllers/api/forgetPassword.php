<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ForgetPassword extends Base_Controller {

	public function index()
	{
		$status = true;
		$msg = 'success';
		$input = $this->input->post();
		if (!isset($input['username'])) {
			$status = false;
			$msg = 'Please send username';
		} else {
			$existed = $this->student_model->checkUserExisted(array('Username' => $input['username']));
			if (!$existed['existed']) {
				$status = false;
				$msg = 'No user existed';
			} else {
				$student = $this->student_model->getStudentFromUsername($input['username']);
				$this->sendEmail($student);
			}
		}

		echo json_encode(array('status' => $status, 'message' => $msg));
	}

	protected function sendEmail($data)
	{
		$this->load->library('email');
		$config = array(
			'protocal' => 'sendmail',
			'mailpath' => '/usr/sbin/sendmail',
			'charset' => 'iso-8859-1',
			'wordwrap' => true
		);
		$this->email->initialize($config);
		$this->email->from('noreply@thevschooldb.com');
		$this->email->to($data['Email']);
		$this->email->subject('Password Reset');
		$this->email->message($this->m->render('emailTemplate', array('link' => '/forgetPassword?user=' . $data['Username'])));
		$this->email->send();
	}
}
