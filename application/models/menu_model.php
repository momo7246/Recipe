<?php

class Menu_model extends CI_Model 
{
	protected $tableName = 'Menu';

	public function addMenu($data)
	{
		$this->db->insert($this->tableName, $data);

		return $this->db->insert_id();
	}

	public function getMenuByPage($page = 1, $sort = 'DESC')
	{
		$this->db->from($this->tableName);
		if ($page == 1) {
			$this->db->limit(10);
    	} else {
        	$offset = ($page - 1) * 10;
        	$this->db->limit(10, $offset);
    	}
    	
    	$this->db->order_by('ID', $sort);
		$items = $this->db->get();

    	if ($items->num_rows() > 0) {
			foreach ($items->result() as $row) {
				$itemsArr[] = $row;
			}
		}

    	return $itemsArr;
	}

	public function getMenuFromCourseId($id)
	{
		$status = false;
		$sql = "SELECT * FROM Menu WHERE CourseID = ?";
		$q = $this->db->query($sql, $id);

		if ($q->num_rows() > 0) {
			$status = true;
		}

		return $status;
	}

	public function getMenuFromId($id)
	{
		$menu = array();
		$sql = "SELECT * FROM Menu WHERE ID = ?";
		$q = $this->db->query($sql, $id);
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$menu[] = $row;
			}
		}

    	return $menu;
	}

	public function deleteMenu($id)
	{
		$sql = "DELETE FROM Menu WHERE ID = ?";
		$q = $this->db->query($sql, $id);

		return $q;
	}

	public function updateMenu($data, $id)
	{
		$success = false;
		$this->db->where('id', $id);
		$this->db->update('Menu', $data);

		if ($this->db->affected_rows() > 0) {
			$success = true;
		}

		return $success;
	}

	public function getAll()
	{
		$sql = "SELECT ID, courseID, titleTH, titleEN, titleCN FROM Menu";
		$q = $this->db->query($sql);
		$menus = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$menus[] = $row;
			}
		}

		return $menus;
	}

	public function getMenuNameFromId($id)
	{
		$sql = "SELECT ID, courseID, titleTH, titleEN, titleCN FROM Menu WHERE ID = ?";
		$q = $this->db->query($sql, $id);
		$menu = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$menu[] = $row;
			}
		}

		return json_decode(json_encode($menu), true);
	}

	public function getFreeMenu($groupBy = "", $addWhere = array())
	{
		$where = array('AFF' => 1, 'CourseID' => 0);
		$where = array_merge($where, $addWhere);

		$selected = array();
		$multiLangs = array('title', 'subtitle', 'topic', 'definition', 'ingredient', 'howto');
		foreach ($multiLangs as $field) {
			$selected = array_merge(array('ID','country'), $selected, array($field . 'TH', $field . 'EN', $field . 'CN'));
		}
		$this->db->select($selected);
		$this->db->from($this->tableName);
		$this->db->where($where);

		if (!empty($groupBy)) {
			$this->db->group_by($groupBy);
		}

		$items = $this->db->get();
		$menus = array();
    	if ($items->num_rows() > 0) {
			foreach ($items->result() as $row) {
				$menus[] = $row;
			}
		}
		$menus = json_decode(json_encode($menus), true);
		for ($i=0; $i < count($menus); $i++) { 
			foreach ($menus[$i] as $key => $value) {
				$menus[$i][$key] = htmlentities($value, ENT_NOQUOTES, 'UTF-8', false);
			}
		}

    	return $menus;
	}

	public function findMenusFromCourseIds($ids)
	{
		$this->db->where('AFF', 0);
		$this->db->where_in('courseID', $ids);
		$result = $this->db->get($this->tableName);
		$menus = array();
		if ($result->num_rows > 0) {
			foreach ($result->result() as $row) {
				$menus[] = $row;
			}
		}
		$menus = json_decode(json_encode($menus), true);

		for ($i=0; $i < count($menus); $i++) {
			foreach ($menus[$i] as $key => $value) {
				$menus[$i][$key] = htmlentities($value, ENT_NOQUOTES, 'UTF-8', false);
			}
		}

		return $menus;
	}

	public function getSpecialMenu()
	{
		$this->db->where(array('courseID' => 0, 'AFF' => 0));
		$result = $this->db->get($this->tableName);

		if ($result->num_rows > 0) {
			foreach ($result->result() as $row) {
				$menus[] = $row;
			}
		}

		return json_decode(json_encode($menus), true);
	}
}