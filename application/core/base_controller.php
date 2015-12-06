<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	var $m;

	function __construct()
	{
		parent::__construct();
		$this->m = new Mustache_Engine(array(
		    'loader' => new Mustache_Loader_FilesystemLoader(APPPATH . '/views'),
		    'partials_loader' => new Mustache_Loader_FilesystemLoader(APPPATH . '/views/partials')
		));
	}

	public function getSiteUrl()
	{
		return $this->config->config['base_url'];
	}

<<<<<<< Updated upstream
=======
	public function getPhotoPath()
	{
		return './uploads/images/';
	}

	public function getVideoPath()
	{
		return './uploads/videos';
	}

	public function getSubtitlePath()
	{
		return './uploads/subtitles';
	}

>>>>>>> Stashed changes
	public function getQuery()
	{
		if (empty($_SERVER['QUERY_STRING'])) {
			return [];
		}

		$string = $_SERVER['QUERY_STRING'];
		$strArr = [$string];
		if (strpos($string,'&') !== false) {
			$strArr = explode('&', $string);
		}
		foreach ($strArr as $value) {
			list($k, $v) = explode('=', $value);
			$result[$k] = urldecode($v);
		}

		return $result;
	}

	public function getPagination($table, $controller, $page = 1)
	{
		$pagination = array();
	    $noRows = $this->db->get($table)->num_rows();
	    $itemPerPage = 25;
	    if ($noRows > $itemPerPage) {
	        $pageNo = (int) ceil($noRows / $itemPerPage);
	        for ($i = 1; $i <= $pageNo; $i++) {
	            $pagination[] = array(
	            	'page' => $i,
	            	'href' => $this->getSiteUrl() . $controller .'/index/' . $i,
	            	'selected' => ($i == $page) ? true : false
	            );
	        }
	    }

	    return $pagination;
	}
<<<<<<< Updated upstream
=======

	public function getCourseHeaderFallback($course)
	{
		$courseArray = (array) $course;

		foreach ($courseArray as $key => $value) {
			if ($key != 'ID') {
				if (!empty($value)) {
					$course = array(
						'ID' => $courseArray['ID'],
						'name' => $value
					);

					return $course;
				}
			}
		}

		return [];
	}

	public function getMenuTitleFallback($menu)
	{
		$menuArray = (array) $menu;
		foreach ($menuArray as $key => $value) {
			if ($key != 'ID' && $key != 'courseID') {
				if (!empty($value)) {
					$menu = array(
						'ID' => $menuArray['ID'],
						'courseID' => $menuArray['courseID'],
						'name' => $value
					);

					return $menu;
				}
			}
		}

		return [];
	}

	public function getCourseName($id)
	{
		$course = $this->course_model->getCourseNameFromId($id);
		if (empty($course)) {
			return array();
		}

		$courseName = $this->getCourseHeaderFallback($course[0])['name'];

		return $courseName;
	}

	public function getMenuName($id)
	{
		$menu = $this->menu_model->getMenuNameFromId($id);
		if (empty($menu)) {
			return array();
		}

		$menuName = $this->getMenuTitleFallback($menu[0])['name'];

		return $menuName;
	}

	public function getDropdown()
	{
		$courses = $this->course_model->getAll();
		foreach ($courses as $course) {
			$data['course'][] = $this->getCourseHeaderFallback($course);
		}
		$countries = $this->country_model->getAllCountries();

		foreach ($countries as $key => $value) {
			$data['countries'][] = [
				'value' => $value['code'],
				'name'	=> $value['fulltext']
			];
		}

		$menus = $this->menu_model->getAll();
		foreach ($menus as $menu) {
			$data['menu'][] = $this->getMenuTitleFallback($menu);
		}

		return $data;	
	}

	protected function getPaypalToken()
	{
		$clientId = $this->config->config['client_id'];
	 	$secret = $this->config->config['client_secret'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

		$result = curl_exec($ch);

		if(empty($result))die("Error: No response.");
		else
		{
		    $json = json_decode($result);
		}

		curl_close($ch);

		return $json->access_token;
	}

	protected function processPayment($id, $token)
	{
		$response = array();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payment/' . $id);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token));

		$result = curl_exec($ch);

		if (!empty($result)) {
			$response = json_decode($result);
		}

		curl_close($ch);

		return $response;
	}
>>>>>>> Stashed changes
}
