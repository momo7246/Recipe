<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_api_controller.php';

class FreeZone extends Base_Api_Controller {

	public function index()
	{
		$data = array();	

		$data['vschool'] = $this->getVSchool();

		$data['walter'] = $this->getWalterLee();

		$data['version'] = '1.0';

        $data['app_url'] = 'http://';
        
		echo json_encode($data);
	}

	protected function getVSchool()
	{
		$menus = array();
		$menus = $this->menu_model->getFreeMenu('', array('country' => ''));

		$menus = $this->setMenuMedia($menus);

		return $menus;
	}

	protected function getWalterLee()
	{
		$countries = $this->getCountries();

		if (!empty($countries)) {
			foreach ($countries as $key => $value) {
				$menus = $this->menu_model->getFreeMenu('', array('country' => $value['code']));
				$menus = $this->setMenuMedia($menus);
				$countries[$key]['menus'] = $menus;
			}
		}

		return $countries;
	}

	protected function getCountries()
	{
		$countries = $this->menu_model->getFreeMenu('country');
		foreach ($countries as $key => $value) {
			if (empty($value['country'])) {
				unset($countries[$key]);
			}
		}

		array_values($countries);
		$data = array();
		foreach ($countries as $country) {
			$data[] = $this->country_model->getCountryFromCode($country['country']);
		}

		return $data;
	}
}
