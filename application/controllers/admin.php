<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
class Admin extends Base_Controller {

	protected $table = 'Student';

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{	
		$data['view'] = true;
		$data['pagination'] = $this->getPagination($this->table, 'admin');
		$data['hasPagination'] = !empty($data['pagination']);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$data['content'] = $this->student_model->getStudentByPage($page);

		echo $this->m->render('admin', $data);
	}

	public function editStudent($output = array())
	{
		$data['edit'] = true;
		$data = array_merge($data, $output);

		if (isset($data['id'])) {
			$id = $data['id'];
		} else {
			$q = $this->getQuery();
			$id = $q['id'];
		}
		
		$data['student'] = $this->student_model->getStudentFromId($id);

		echo $this->m->render('admin', $data);
	}

	public function deleteStudent()
	{
		$data['delete'] = true;
		$q = $this->getQuery();
		if (!empty($q)) {
			$id = $q['id'];
		}

		$input = $this->input->post();

		if (empty($input)) {
			$hasPayment = $this->payment_model->getPaymentFromStudentId($id);

			if (!empty($hasPayment)) {
				$data['hasPayment'] = true;
				$data['count'] = count($hasPayment);
				$data['id'] = $id;
			} else {
				$data['status']['delete'] = true;
				$this->student_model->deleteStudent($id);
			}
		} else {
			$data['status']['delete'] = true;
			$this->student_model->deleteStudent($input['id']);
			$this->payment_model->deletePayment($input['id']);
		}

		echo $this->m->render('admin', $data);
	}

	public function updateAdmin()
	{
		$data['id'] = $this->uri->segment(3);
		if (!$data['id']) {
			$this->index();
		}
		$q = $this->getQuery();
		$match = $this->checkPasswordMatching($q['password'], $q['re-password']);

		if (!$match) {
			$data['validate']['error']['password'] = true;
		} else {
			unset($q['re-password']);
			$data['status']['update'] = $this->admin_model->updateAdmin($q, $data['id']);
		}

		$this->editAdmin($data);
	}

	public function addStudent()
	{
		$data['add'] = true;

		echo $this->m->render('admin', $data);
	}

	public function addNewStudent()
	{
		$data['add'] = true;
		$q = $this->getQuery();
		$match = $this->checkPasswordMatching($q['password'], $q['re-password']);

		if (!$match) {
			$data['validate']['error']['password'] = true;
		} else {
			unset($q['re-password']);

			$result = $this->student_model->checkUserExisted(array(
				'Username' => $q['username'],
				'Email' => $q['email']
			));

			if (!$result['existed']) {
				$id = $this->student_model->register($q);
				if (!empty($id)) {
					$data['status']['add'] = true;
				}
			} else {
				$data['status'] = array(
					'error' => true,
					'message' => $result['message']
				);
			}

		}

		$data['content'] = $q;

		echo $this->m->render('admin', $data);
	}

	protected function checkPasswordMatching($password, $passwordcon)
	{
		$error = false;
		if ($password !== $passwordcon) {
			$error = true;
		}

		return $error;
	}
}
