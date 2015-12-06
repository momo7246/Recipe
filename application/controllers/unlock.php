<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unlock extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('/login');
		}
	}

	public function index()
	{
		$data['view'] = true;
		$data = array_merge($data, $this->getDropdown());

		echo $this->m->render('unlock', $data);
	}

	public function getStudentSuggestion()
	{
		$keyword = $this->input->post('keyword');
		$students = $this->student_model->autoSuggestion($keyword);

		$data['students'] = !empty($students) ? $students : array();

		echo json_encode($data);
	}

	public function addPayment()
	{
		$input = $this->input->post();
		
		//check enroll existed
		$existed = $this->payment_model->checkStudentEnrollment($input);
		if ($existed) {
			echo false;
			exit;
		}

		$input['date'] = date("Y-m-d");
		$output = $this->payment_model->addPayment($input);
		$data = !empty($output);

		echo $data;
	}

	public function getStudentEnrollment()
	{
		$id = $this->input->get('id');
		$courses = $this->payment_model->getPaymentFromStudentId($id);
		foreach ($courses as $key => $value) {
			if ($value['courseType'] == 0) {
				$name = $this->getCourseName($value['courseID']);
				$typeText = 'course';
			} elseif ($value['courseType'] == 1) {
				$name = $this->getMenuName($value['courseID']);
				$typeText = 'menu';
			}

			$courses[$key]['name'] = !empty($name) ? $name : '';
			$courses[$key]['typeText'] = !empty($typeText) ? $typeText : '';
		}
		
		echo json_encode($courses);
	}

	public function deleteStudentEnroll()
	{
		$id = $this->input->get('id');
		$this->payment_model->deletePaymentFromID($id);

		echo "<script type='text/javascript'>alert('Successfully deleted!'); window.location.href='http://localhost:8888/food_course/unlock';</script>";
	}
}
