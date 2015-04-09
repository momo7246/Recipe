<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Base_Controller {

	protected $table = 'Admin';

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{	
		$data['view'] = true;
		$data['pagination'] = $this->getPagination($this->table, strtolower($this->table));
		$data['hasPagination'] = !empty($data['pagination']);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$data['content'] = $this->admin_model->getAdminByPage($page);

		echo $this->m->render('admin', $data);
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

		echo $this->m->render('admin', $data);
	}

	public function deleteAdmin()
	{
		$data['delete'] = true;
		$q = $this->getQuery();
		$id = $q['id'];

		$data['status']['delete'] = $this->admin_model->deleteAdmin($id);
		
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

	public function addAdmin()
	{
		$data['add'] = true;

		echo $this->m->render('admin', $data);
	}

	public function addNewAdmin()
	{
		$data['add'] = true;
		$q = $this->getQuery();
		$match = $this->checkPasswordMatching($q['password'], $q['re-password']);

		if (!$match) {
			$data['validate']['error']['password'] = true;
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
