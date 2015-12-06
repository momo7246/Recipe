<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Api_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	protected function validateError($fields, $input = array())
	{
		if (!$input) {
			echo json_encode(array('status' => false, 'massage' => 'Please insert value'));
			exit;
		}

		$error = array();
		foreach ($fields as $field) {
			if (!array_key_exists($field, $input)) {
				$error[] = $field; 
			} elseif (empty($input[$field])) {
				$error[] = $field;
			}
		}

		if (count($error)) {
			$error = implode(', ', $error);
			$message = 'Please insert in these fields ' . $error;
			
			echo json_encode(array('status' => false, 'message' => $message));
			exit;
		}

		return;
	}

	protected function setMenuMedia($menus)
	{
		if (!empty($menus)) {
			foreach ($menus as $menu) {
				$ids[] = $menu['ID'];
			}

			$medias = $this->media_model->getMedia($ids);
			$menus = $this->applyMedia($menus, $medias);

			foreach ($menus as $key => $value) {
				$menus[$key]['media'] = $this->setMediaType($value['media']);
			}

			$menus = $this->setVideoSubtitles($menus, $ids);
		}

		return $menus;
	}

	protected function applyMedia($menus, $medias)
	{
		foreach ($menus as $key => $value) {
			if (!empty($medias[$value['ID']])) {
				$menus[$key]['media'] = $medias[$value['ID']];
			} else {
				$menus[$key]['media'] = array();
			}
		}

		return $menus;
	}

	protected function setMediaType($medias)
	{
		$keys = array('images', 'video');
		$data = array();
		foreach ($medias as $media) {
			if (!empty($media)) {
				switch ($media['type']) {
					case 'image/menu':
						$data['images'][] = htmlentities($media['mediaPath']);
						break;
					case 'image/course':
						$data['images'][] = htmlentities($media['mediaPath']);
						break;
					case 'video':
						$data['video'][] = htmlentities($media['mediaPath']);
						break;
					default:
						break;
				}

				foreach ($keys as $key) {
					if (!array_key_exists($key, $data)) {
						$data[$key] = array();
					}
				}
			}
		}
		if (empty($data)) {
			foreach ($keys as $key) {
				$data[$key] = array("");
			}
		}

		return $data;
	}

	protected function setVideoSubtitles($menus, $ids)
	{
		$subtitles = $this->subtitle_model->getSubtitles($ids);
		$videoSub = array();
		foreach ($menus as $key => $value) {
			if (!empty($subtitles[$value['ID']]) && !empty($value['media']['video'])) {
				unset($subtitles[$value['ID']]['menuID']);
				$videoSub = $subtitles[$value['ID']];
			}
			$menus[$key]['media']['subtitles'] = $videoSub;
		}

		return $menus;
	}
}
