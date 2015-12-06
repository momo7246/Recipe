<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ManageAdmin extends Base_Controller {

	protected $table = 'Admin';

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
		$data['pagination'] = $this->getPagination($this->table, 'manageAdmin');
		$data['hasPagination'] = !empty($data['pagination']);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$data['content'] = $this->admin_model->getAdminByPage($page);
		echo $this->m->render('manageAdmin', $data);
	}

	public function editAdmin($output = array())
	{
		$data['edit'] = true;
		$data = array_merge($data, $output);
		if (isset($data['id'])) {
			$id = $data['id'];
		} else {
			$q = $this->getQuery();
			$id = $q['id'];
		}
		
		$data['admin'] = $this->admin_model->getAdminFromId($id);
		echo $this->m->render('manageAdmin', $data);
	}

	public function deleteAdmin()
	{
		$data['delete'] = true;
		$q = $this->getQuery();
		$id = $q['id'];
		$data['status']['delete'] = $this->admin_model->deleteAdmin($id);
		
		echo $this->m->render('manageAdmin', $data);
	}

	public function updateAdmin()
	{
		$data['id'] = $this->uri->segment(3);
		if (!$data['id']) {
			$this->index();
		}
		$q = $this->getQuery();
		$errorPassword = $this->checkPasswordMatching($q['password'], $q['re-password']);
		if ($errorPassword) {
			$data['validate']['password'] = true;
		} else {
			unset($q['re-password']);
			$data['status']['update'] = $this->admin_model->updateAdmin($q, $data['id']);
		}
		$this->editAdmin($data);
	}

	public function addAdmin()
	{
		$data['add'] = true;
		echo $this->m->render('manageAdmin', $data);
	}

	public function addNewAdmin()
	{
		$data['add'] = true;
		$q = $this->getQuery();
		$errorPassword = $this->checkPasswordMatching($q['password'], $q['re-password']);
		if ($errorPassword) {
			$data['validate']['password'] = true;
		} else {
			unset($q['re-password']);
			$existed = $this->admin_model->checkUsernameExisted($q['username']);
			if (!$existed) {
				$id = $this->admin_model->addAdmin($q);
				if (!empty($id)) {
					$data['status']['add'] = true;
				}
			} else {
				$data['status']['error'] = true;
			}
		}
		$data['content'] = $q;
		echo $this->m->render('manageAdmin', $data);
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