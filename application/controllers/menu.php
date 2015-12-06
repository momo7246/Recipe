<<<<<<< Updated upstream
=======
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Menu extends Base_Controller {

	protected $table = 'Menu';

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
		$data['pagination'] = $this->getPagination($this->table, strtolower($this->table));
		$data['hasPagination'] = !empty($data['pagination']);

		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
		$data['content'] = $this->menu_model->getMenuByPage($page);
		foreach ($data['content'] as $key => $menu) {
			$courseName = '';
			$hasCourse = ($menu->courseID != 0) ? true : false;
			if ($hasCourse) {
				$course = $this->course_model->getCourseNameFromId($menu->courseID);
				$courseName = $this->getCourseHeaderFallback($course[0]);
			}

			$data['content'][$key]->courseName = is_array($courseName) ? $courseName['name'] : $courseName;
		}

		echo $this->m->render('menu', $data);
	}

	public function addMenu()
	{
		$data['add'] = true;

		$data = array_merge($data, $this->getDropdown());

		echo $this->m->render('menu', $data);
	}

	public function stepOne()
	{
		$menuInfo = $this->input->post();
		
		$menuId = $this->menu_model->addMenu($menuInfo);
		$status = array(
			'status' => 'success',
			'menuId' => $menuId
		);

		echo json_encode($status);
	}

	public function deleteMenu()
	{
		$data['delete'] = true;
		$q = $this->getQuery();
		$id = $q['id'];

		$subtitles = $this->subtitle_model->getSubtitleFromMenuId($id);
		if (!empty($subtitles[0])) {
			$this->deletePhysicalSubtitles($subtitles);

			$this->subtitle_model->deleteSubtitle($id);
		}

		$data['status']['delete'] = $this->menu_model->deleteMenu($id);

		//delete media too
		
		echo $this->m->render('menu', $data);
	}

	protected function deletePhysicalSubtitles($data)
	{
		$subtitles = array(
			'th' => $data[0]['subtitleTH'],
			'en' => $data[0]['subtitleEN'],
			'cn' => $data[0]['subtitleCN']
		);

		foreach ($subtitles as $key => $value) {
			$name = str_replace($this->getSiteUrl() . 'uploads/subtitles/', "", $value);
			$path = $this->getSubtitlePath().'/'.$name;

			if (file_exists ($path)) {
				$status = unlink($path);
			}
		}
	}

	public function editMenu()
	{
		$data['edit'] = true;
		$q = $this->getQuery();
		$id = $q['id'];

		$data['menuContent'] = $this->menu_model->getMenuFromId($id);
		$data['video'] = $this->media_model->getVideoFromMenuId($id);
		$data['coverImage'] = $this->media_model->getCoverImage($id, 'image/menu');
		$subtitles = $this->subtitle_model->getSubtitleFromMenuId($id);
		if (!empty($subtitles)) {
			$data['subtitles'] = $subtitles[0];
		}
		

		$data = array_merge($data, $this->getDropdown());

		echo $this->m->render('menu', $data);
	}

	public function editStepOne()
	{
		$data = $this->input->post();

		if ($data['id']) {
			$id = $data['id'];
			unset($data['id']);
		}

		$updateStatus = $this->menu_model->updateMenu($data, $id);

		$status = 'false';
		if ($updateStatus) {
			$status = 'true';
		}
	
		echo $status;
	}
}
>>>>>>> Stashed changes
