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

	public function getPagination($table, $controller)
	{
		$pagination = array();
	    $noRows = $this->db->get($table)->num_rows();
	    $itemPerPage = 10;
	    if ($noRows > $itemPerPage) {
	        $pageNo = (int) ceil($noRows / $itemPerPage);
	        for ($i = 1; $i <= $pageNo; $i++) {
	            $pagination[] = array(
	            	'page' => $i,
	            	'href' => $this->getSiteUrl() . $controller .'/index/' . $i
	            );
	        }
	    }

	    return $pagination;
	}
}
