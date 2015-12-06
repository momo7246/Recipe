<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Media extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		$login = $this->input->post('logged_in');
    	if (isset($login)) {
    		$this->session->set_userdata(array('logged_in' => $login));
    	} elseif (!$this->session->userdata('logged_in')) {
			redirect('/login');
		}
	}

	public function uploadVideoPath()
	{
		$input = $this->input->post();
		$success = false;

		$existed = $this->media_model->checkVideoExisted($input['menuID']);
		if ($existed) {
			$success = $this->media_model->updateVideoFromMenuId($input['mediaPath'], $input['menuID']);
		} else {
			$id = $this->media_model->addVideo($input);
			if (!empty($id)) {
				$success = true;
			}
		}

		echo $success;
	}

	public function uploadEmbedded()
	{
		$response = array('status' => 'error');

		if (!empty($_FILES)) {
			$file = $_FILES['embedded'];
			$menuId = $_POST['id'];

			if (!file_exists($this->getVideoPath())) {
			   	$fileExisted = mkdir($this->getVideoPath(), 0777, true);
			}

			$fileName = $this->getFileName($file['name']);

			if (move_uploaded_file($file['tmp_name'], $this->getVideoPath() . '/' .$fileName)) {
				$fullPath = $this->getSiteUrl() . 'uploads/videos/' . $fileName;
				$existed = $this->media_model->checkVideoExisted($menuId);
				if ($existed) {
					//delete physical video
					$this->deletePhysicalVideo($menuId);
					$success = $this->media_model->updateVideoFromMenuId($fullPath, $menuId);

					if ($success) {
						$response = array(
							'status' => 'success',
							'name'	=> $file['name']
						);
					}
				} else {
					$data = array(
						'menuID' => $menuId,
						'type'	=> 'video',
						'mediaPath' => $fullPath
					);


					$id = $this->media_model->addVideo($data);

					if (!empty($id)) {
						$response = array(
							'status' => 'success',
							'name'	=> $file['name']
						);
					}
				}
			}
		}
		
		echo json_encode($response);
	}

	public function deleteVideo()
	{
		$id = $this->input->post('id');
		$this->deletePhysicalVideo($id);
		$status = $this->media_model->deleteVideo($id);

		echo $status;
	}

	public function uploadCoverImage()
	{
		$id = $_POST['id'];
		$section = $_POST['section'];
		$file = $_FILES['cover'];

		$fileName = $this->getFileName($file['name']);
		$fullPath = $this->getSiteUrl() . 'uploads/images/' . $fileName;
		$success = false;
		$status = array('status' => 'error');

		if (!$this->media_model->checkCoverImageExisted($id, $section)) {
			$status = $this->uploadImageToServer($file);
			if ($status == 'success') {
				$data = array(
					'mediaPath' => $fullPath,
					'menuID' => $id,
					'type' => $section,
					'coverImage' => 1
				);
				$success = $this->media_model->addMedia($data);
			}
		} else {
			$status = $this->uploadImageToServer($file);
			if ($status == 'success') {
				$success = $this->media_model->updateCoverImage($fullPath, $id, $section);
			} 
		}

		if ($success) {
			$status = array(
				'status' => 'success',
				'path' => $fullPath
			);
		}

		echo json_encode($status);
	}

	public function uploadImageFromTextArea()
	{
		$file = $_FILES['upload'];
		$status = $this->uploadImageToServer($file);

		echo $status;
	}

	protected function uploadImageToServer($file)
	{
		$status = 'error';
		$type = $file['type'];
		$tmpFile = $file['tmp_name'];
		$fileName = $this->getFileName($file['name']);

		if (!file_exists($this->getPhotoPath())) {
   			mkdir($this->getPhotoPath(), 0777, true);
		}
		switch ($type) {
			case 'image/png':
				$image = imagecreatefrompng($tmpFile);
				imagepng($image, $this->getPhotoPath() .'/'. $fileName);

				$status = 'success';
				break;
			case 'image/jpeg':
				$image = imagecreatefromjpeg($tmpFile);
				imagejpeg($image, $this->getPhotoPath() .'/'. $fileName);

				$status = 'success';
				break;
			default:

				$status = 'error';
		}

		return $status;
	}

	public function browseImage()
	{
		$directory = scandir($this->getPhotoPath());
		if (!empty($directory)) {
			$scanned_directory = array_diff($directory, array('..', '.'));
		}

		if (!empty($scanned_directory)) {
			$photos = array_values($scanned_directory);
			$array = array();
			foreach ($photos as $key => $photo) {
				$array[$key]['url'] = $this->getSiteUrl() . 'uploads/images/' . $photo;
				$array[$key]['timestamp'] = filemtime($this->getPhotoPath().$photo);
			}

			usort($array, create_function('$a, $b', 'return strcmp($a["timestamp"], $b["timestamp"]);'));
			krsort($array);

			$data = array_values($array);
		}

		//echo $this->m->render('image_upload_partial', $data);
		echo json_encode($data);
	}

	protected function getFileName($name)
	{
		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$timestamp = date('YmdHis');
		$fileName = basename($name, ".".$ext);

		$output = $fileName.'_'.$timestamp.'.'.$ext;

		return $output;
	}

	protected function deletePhysicalVideo($id)
	{
		$video = $this->media_model->getVideoFromMenuId($id);
		$oldPath = $video[0]['mediaPath'];
		$name = str_replace($this->getSiteUrl() . 'uploads/videos/', "", $oldPath);
		$path = $this->getVideoPath().'/'.$name;

		if (file_exists ($path)) {
			$status = unlink($path);
		}
	}

	public function uploadMultiImages()
	{
		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$filename = $this->getFileName($_FILES['Filedata']['name']);
			$targetFile = $this->getPhotoPath() .'/' . $filename;

			move_uploaded_file($tempFile,$targetFile);
		}
	}

	public function uploadSubtitles()
	{
		$response = array('status' => 'error');
		$pathList = array();
		if (!empty($_FILES)) {
			$files = array(
				'subtitleTH' => $_FILES['subth'],
				'subtitleEN' => $_FILES['suben'],
				'subtitleCN' => $_FILES['subcn']
			);
			$menuId = $_POST['id'];

			if (!file_exists($this->getSubtitlePath())) {
			   	$fileExisted = mkdir($this->getSubtitlePath(), 0777, true);
			}

			foreach ($files as $key => $file) {
				if (!empty($file['name'])) {
					$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
					$name = str_replace('.' . $ext, "", $file['name']);
					$fileName = $name . '_' . $this->getFileName($file['name']);
					if (move_uploaded_file($file['tmp_name'], $this->getSubtitlePath() . '/' .$fileName)) {
						$fullPath = $this->getSiteUrl() . 'uploads/subtitles/' . $fileName;
						$pathList = array_merge($pathList, array($key => $fullPath));
					}
				}
			}
			$existed = $this->subtitle_model->getSubtitleFromMenuId($menuId);
			if (!empty($existed)) {
				$this->subtitle_model->updateSubtitle($pathList, $menuId);
				foreach ($pathList as $key => $value) {
					$this->deletePhysicalSubtitles($existed[0][$key]);
				}
				
				$response = array(
					'status' => 'success',
					'subtitles' => $pathList
				);
			} else {
				$subtitles = array_merge($pathList, array('menuID' => $menuId));
				$this->subtitle_model->addSubtitle($subtitles);
				$response = array(
					'status' => 'success',
					'subtitles' => $pathList
				);
			}
		}

		echo json_encode($response);
	}

	protected function deletePhysicalSubtitles($data)
	{
		$name = str_replace($this->getSiteUrl() . 'uploads/subtitles/', "", $data);
		$path = $this->getSubtitlePath().'/'.$name;

		if (file_exists ($path)) {
			$status = unlink($path);
		}
	}
}
