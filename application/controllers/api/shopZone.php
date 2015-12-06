<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_api_controller.php';

class ShopZone extends Base_Api_Controller {

	public function index()
	{
		$data = array();	
		$input = $this->input->post();
		$data['show'] = $this->getShowCourse();
		$data['review'] = $this->getReviewCourse();
		$data['special'] = $this->getSpecialMenu();

		if (!empty($input['id'])) {
			$data['show'] = $this->resetStatus($input['id'], $data['show']);
			$data['review'] = $this->resetStatus($input['id'], $data['review']);
			$data['special'] = $this->resetMenuStatus($input['id'], $data['special']);

			$data['myCourse'] = $this->setMyCourse($data['show'], $data['review']);
		}

		echo json_encode($data);
	}

	protected function setMyCourseInit($courses)
	{
		foreach ($courses as $key => $value) {
			if ($value['status']) {
				unset($courses[$key]);
			}
		}
		$courses = array_values($courses);

		return $courses;
	}

	protected function setMyCourse($shows, $reviews)
	{
		array_walk($shows, function($v, $k) use(&$reviews) {
			foreach ($reviews as $key => $value) {
				if ( $value['ID'] == $v['ID'] || $value['status'] == 0) { 
	    			unset($reviews[$key]);
				}
			}
		});
		echo json_encode($shows); exit;
		foreach ($shows as $key => $value) {
			if ($value['status'] == 0) {
				unset($shows[$key]);
			}
		}
		$reviews = array_values($reviews);
		$shows = array_values($shows);

		$data['mycourse'] = $shows;
		$data['myreview'] = $reviews;

		return $data;
	}

	protected function resetStatus($id, $courses)
	{
		foreach ($courses as $key => $value) {
			$courseExisted = $this->payment_model->getPayment(array(
				'studentID' => $id,
				'courseID' => $value['ID'],
				'courseType' => 0
				));
			if ($courseExisted) {
				$courses[$key]['status'] = 1;
			}

			if (!empty($courses[$key]['menus'])) {
				if ($courses[$key]['status'] == 1) {
					foreach ($courses[$key]['menus'] as $k => $v) {
						$courses[$key]['menus'][$k]['status'] = 1;
					}
				} else {
					$courses[$key]['menus'] = $this->resetMenuStatus($id, $courses[$key]['menus']);
				}
			}
		}

		return $courses;
	}

	protected function resetMenuStatus($id, $menus)
	{
		foreach ($menus as $key => $value) {
			$menuExisted = $this->payment_model->getPayment(array(
				'studentID' => $id,
				'courseID' => $value['ID'],
				'courseType' => 1
				));

			if ($menuExisted) {
				$menus[$key]['status'] = 1;
			}
		}

		return $menus;
	}

	protected function getReviewCourse()
	{
		$courses = $this->course_model->getPaidCourse();
		$courses = $this->getCourseAdditionData($courses);
		$courses = $this->setInitialStatus($courses);

		return $courses;
	}

	protected function getShowCourse()
	{
		//AFF = 0 show = 1
		$courses = $this->course_model->getPaidCourse(array('show' => 1));
		$courses = $this->getCourseAdditionData($courses);
		$courses = $this->setInitialStatus($courses);

		return $courses;
	}

	protected function getSpecialMenu()
	{
		$menus = $this->menu_model->getSpecialMenu();
		if (!empty($menus)) {
			$menus = $this->setMenuMedia($menus);

			// initial menu status
			foreach ($menus as $key => $value) {
				$menus[$key]['status'] = 0;
			}
		}

		return $menus;
	}

	protected function getCourseAdditionData($courses)
	{
		$courseIds = array();
		foreach ($courses as $course) {
			$courseIds[] = $course['ID'];
		}

		if (!empty($courseIds)) {
			$medias = $this->media_model->getMedia($courseIds, 'image/course');
			$courses = $this->applyMediaToCourse($medias, $courses);

			$menus = $this->menu_model->findMenusFromCourseIds($courseIds);
			//$menus = $this->getComments($menus);
			$menus = $this->setMenuMedia($menus);
			$courses = $this->applyMenuToCourse($menus, $courses);
		}

		return $courses;
	}

	protected function applyMenuToCourse($menus, $courses)
	{
		$courseMenus = array();
		foreach ($menus as $key => $value) {
			$courseMenus[$value['courseID']][] = $menus[$key];
		}

		foreach ($courses as $key => $value) {
			if (isset($courseMenus[$value['ID']])) {
				$courses[$key]['menus'] = $courseMenus[$value['ID']];
			} else {
				$courses[$key]['menus'] = array();
			}
		}

		return $courses;
	}

	protected function applyMediaToCourse($medias, $courses)
	{
		foreach ($courses as $key => $value) {
			if (isset($medias[$value['ID']])) {
				$courses[$key]['media'] = $this->setMediaType($medias[$value['ID']]);
			} else {
				$courses[$key]['media'] = array();
			}
		}

		return $courses;
	}

	protected function setInitialStatus($courses)
	{
		foreach ($courses as $key => $value) {
			$courses[$key]['status'] = 0;

			if (!empty($courses[$key]['menus'])) {
				foreach ($courses[$key]['menus'] as $k => $v) {
					$courses[$key]['menus'][$k]['status'] = 0;
				}
			}
		}

		return $courses;
	}

	protected function getComments($menus)
	{
		foreach ($menus as $menu) {
			$ids[] = $menu['ID'];
		}

		if (!empty($ids)) {
			$comments = $this->review_model->getComments($ids);
			if (!empty($comments)) {
				foreach ($menus as $key => $value) {
					if (!empty($comments[$value['ID']])) {
						$menus[$key]['comments'] = $comments[$value['ID']];
					}
				}
			} else {
				foreach ($menus as $key => $value) {
					$menus[$key]['comments'] = array();
				}
			}
		}

		return $menus;
	}
}
