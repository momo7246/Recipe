<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_api_controller.php';

class Home extends Base_Api_Controller {

	public function login()
	{
		$id = '';
		$name = '';
		$input = $this->input->post();
		if (empty($input)) {
			
			echo json_encode(array("status" => false, "message" => "Please insert values"));
			exit;
		}
		$student = $this->student_model->checkUserExisted(array(
			'Username' => $input['Username'],
			'Email'	=> $input['Email']
		));

		if ($student['existed']) {

			$id = $student['student']['ID'];
			$name = $student['student']['Name'] . " " . $student['student']['Surname'];
			$this->student_model->updateStudent($input, $id);

		} elseif (!$student['existed'] && $input['type'] == 'facebook') {

			$response = $this->registerStudent($input);
			$id = $response['id'];
			$name = $response['name'];
		}

		$response = array(
			'status' => !empty($id),
			'id' => $id,
			'name' => $name
		);

		echo json_encode($response);
	}

	public function register()
	{		
		$input = $this->input->post();
		$response = $this->registerStudent($input);

		echo json_encode($response);
	}

	protected function registerStudent($input)
	{
		$required = array('Name', 'Surname', 'Username', 'Password', 'Email');
		$this->validateError($required, $input);

		$id = $this->student_model->register($input);

		$response = array(
			'status' => !empty($id),
			'id' => !empty($id) ? $id : '',
			'name' => $input['Name'] . ' ' . $input['Surname']
		);

		return $response;
	}
}
