<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_api_controller.php';

class Review extends Base_Api_Controller {

	public function index()
	{
		$input = $this->input->post();
		$fields = array('menuID', 'studentID', 'comment');
		$this->validateError($fields, $input);

		$this->review_model->addReview($input);
		$comments = $this->getComments($input['menuID']);

		echo json_encode($comments);
	}

	public function comment()
	{
		$menuId = $this->input->post('id');
		$comments = $this->getComments($menuId);

		echo json_encode($comments);
	}

	protected function getComments($menuId)
	{
		$comments = $this->review_model->getComment($menuId);

		return $comments;
	}
}
