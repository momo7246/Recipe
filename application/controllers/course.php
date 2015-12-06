<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
class Course extends Base_Controller {

	protected $table = 'Course';

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
		$data['content'] = $this->course_model->getCourseByPage($page);

		echo $this->m->render('course', $data);
	}

	public function addCourse()
	{
		$data['add'] = true;
		$q = (!empty($this->getQuery())) ? $this->getQuery() : null;

		if (!empty($q)) {
			$titles = [
				$q['headerTH'],
				$q['headerEN'],
				$q['headerCN']
			];
			$existed = $this->course_model->checkCourseExisted($titles);
			if (!$existed) {
				$q = $this->checkEntity($q);
				$id = $this->course_model->addCourse($q);
				if (!empty($id)) {
					$data['status']['success'] = true;
				}
			} else {
				$data['status']['error'] = true;
			}
		}
		$data['content'] = $q;

		echo $this->m->render('course', $data);
	}

	public function deleteCourse()
	{
		$data['delete'] = true;
		$q = $this->getQuery();
		$id = $q['id'];

		$data['status']['delete'] = $this->course_model->deleteCourse($id);

		echo $this->m->render('course', $data);
	}

	public function editCourse($output = array())
	{
		$data['edit'] = true;
		$q = $this->getQuery();

		if (isset($data['id'])) {
			$id = $data['id'];
		} else {
			$q = $this->getQuery();
			$id = $q['id'];
		}

		$data['content'] = $this->course_model->getCourseFromId($q['id']);
		$data = array_merge($data, $output);

		echo $this->m->render('course', $data);
	}

	public function updateCoure()
	{
		$data['id'] = $this->uri->segment(3);
		if(!$data['id']) {
			$this->index();
		}

		$q = $this->checkEntity($this->getQuery());
		$data['status']['update'] = $this->course_model->updateCourse($q, $data['id']);

		$this->editCourse($data);
	}

	protected function checkEntity($entity)
	{
		$tableEntities = ['headerTH', 'headerEN', 'headerCN', 'price', 'discount', 'AFF', 'show'];
		foreach ($tableEntities as $tableEntity) {
			if (!array_key_exists($tableEntity, $entity)) {
				$entity[$tableEntity] = null;
			}
		}

		$entity['price'] = (!empty($entity['price'])) ? $entity['price'] : 0;
		$entity['discount'] = (!empty($entity['discount'])) ? $entity['discount'] : 0;
		$entity['show'] = (!empty($entity['show'])) ? $entity['show'] : 0;
		$entity['AFF'] = (!empty($entity['AFF'])) ? $entity['AFF'] : 0;

		return $entity;
	}
}
