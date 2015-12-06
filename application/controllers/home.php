<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Base_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
<<<<<<< Updated upstream
		$data['name'] = ['momo', 'phone'];
		
		echo $this->m->render('home', $data);
=======
		$year = $this->input->post('year');

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$data['pagination'] = $this->getPagination($this->table, 'home', $page);
		$data['hasPagination'] = !empty($data['pagination']);

		$info = $this->student_model->getStudentByPage($page);
		$data['info'] = json_decode(json_encode($info), true);

		foreach ($data['info'] as $key => $student) {
			$payments = $this->payment_model->getPaymentFromStudentId($student['ID']);
			$data['info'][$key]['payment'] = $payments;

			foreach ($payments as $k => $payment) {

				if ($payment['courseType'] == '0') { //whole course
					$course = $this->getCourseName($payment['courseID']);

					if (empty($course)) {
						continue;
					}

					$data['info'][$key]['payment'][$k]['name'] = $course;

				} else if ($payment['courseType'] == '1') { //menu only
					$menu = $this->getMenuName($payment['courseID']);

					if (empty($menu)) {
						continue;
					}

					$data['info'][$key]['payment'][$k]['name'] = $menu;
				}
			}
		}

		$data['payment'] = $this->getPendingPayments();
		$data['hasPayment'] = !empty($data['payment']);
		$count = $this->getTotalEnrollmentCount($year);
		$data = array_merge($data, $count);

		if (!empty($year)) {

			echo $this->m->render('home_partial', $data);
		} else {
			
			echo $this->m->render('home', $data);
		}

	}

	protected function getTotalEnrollmentCount($inputYear)
	{
		$year = (!empty($inputYear)) ? $inputYear : date('Y');

		$data['count'] = $this->payment_model->getCurrentTotal($year);

		foreach ($data['count'] as $key => $value) {
			$month = $value['month'];
			$data['count'][$key]['monthName'] = date('F', mktime(0, 0, 0, $month, 10));
			$data['count'][$key]['courses'] = $this->payment_model->getCourseInPayment($month, $year);

			foreach ($data['count'][$key]['courses'] as $k => $value) {
				if ($value['courseType'] == '0') { //whole course
					$course = $this->getCourseName($value['courseID']);

					if (empty($course)) {
						continue;
					}

					$data['count'][$key]['courses'][$k]['name'] = $course;

				} else if ($value['courseType'] == '1') { //menu only
					$menu = $this->getMenuName($value['courseID']);

					if (empty($menu)) {
						continue;
					}

					$data['count'][$key]['courses'][$k]['name'] = $menu;
				}
			}
		}

		return $data;
	}

	protected function getPendingPayments()
	{
		$payments = $this->getInCompletePayment();
		foreach ($payments as $key => $value) {
			$student = $this->student_model->getStudentFromId($value['studentID']);

			if ($value['courseType'] == '0') { //course
				$course = $this->course_model->getCourseFromId($value['courseID']);
				$title = $this->getCourseHeaderFallback($course[0]);
			} else {
				$menu = $this->menu_model->getMenuFromId($value['courseID']);
				$title = $this->getMenuTitleFallback($menu[0]);
			}

			$payments[$key]['name'] = $student['Name'] . ' ' . $student['Surname'];
			$payments[$key]['courseName'] = $title['name'];
		}

		return $payments;
	}

	protected function getInCompletePayment()
	{
		$payments = $this->payment_model->getPendingPayments();

		return $payments;
	}

	public function recheckPayment()
	{
		$payments = $this->getInCompletePayment();
		foreach ($payments as $key => $value) {
			$payIds[] = array(
				'payID' => $value['paymentID'],
				'paymentID' => $value['ID']
			);
		}

		foreach ($payIds as $key => $value) {
			$token = $this->getPaypalToken();
			$response = $this->processPayment($value['payID'], $token);

			if (!empty($response->state) && $response->state == 'approved') {
				$this->payment_model->updatePayment(array('status' => $response->state), $value['paymentID']);
			}
		}

		redirect('home/index', 'refresh');
	}

	public function deleteCourse()
	{
		$input = $this->input->post();
		if ($input['proceed']) {
			$this->payment_model->deletePayment($input['id']);
		}

		return true;
>>>>>>> Stashed changes
	}
}
