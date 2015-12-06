<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('/login');
		}
	}

	public function index()
	{	
		$data = array();

		echo $this->m->render('email', $data);
	}

	public function sendEmail()
	{
		$q = $this->getQuery();
		if (!empty($q)) {
			$fromArr = array();
			$students = $this->student_model->getAll();

			$fromArr = array_chunk($students, 5);

			foreach ($fromArr as $key => $value) {
				$emails[] = $this->getRecievers($value);
			}

			foreach ($emails as $email) {
				$data = array(
					'subject' => $q['subject'],
					'message' => $q['message'],
					'to'	=> $email,
					'status' => 'WAIT'
				);
				$this->email_model->addEmail($data);
			}
		}

		redirect('email/index', 'refresh');
	}

	public function getRecievers($student)
	{
		$emailArr = array();
		foreach ($student as $key => $value) {
			$email[] = $value['Email']; 
		}

		$emailArr = implode(", ", $email);

		return $emailArr;
	}

	public function sendCronEmail()
	{
		$email = $this->email_model->getWaitList();
		$this->load->library('email');
		$config = array(
			'protocal' => 'sendmail',
			'mailpath' => '/usr/sbin/sendmail',
			'charset' => 'iso-8859-1',
			'wordwrap' => true
		);
		$this->email->initialize($config);
		$this->email->from('noreply@thevschooldb.com');
		$this->email->to($email['to']);
		$this->email->subject($email['subject']);
		$this->email->message($email['message']);
		$this->email->send();

		$this->email_model->updateEmailStatus($email['ID']);

		echo $this->email->print_debugger();
	}
}
