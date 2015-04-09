<?php

class Course_model extends CI_Model 
{
	protected $tableName = 'Course';

	public function addCourse($data)
	{
		$this->db->insert($this->tableName, $data);

		return $this->db->insert_id();;
	}

	public function checkCourseExisted($titles)
	{
		$existed = false;
		$sql = "SELECT * FROM Course WHERE headerTH = ? AND headerEN = ? AND headerCN = ?";
		$q = $this->db->query($sql, $titles);
		$admin = array();

		if ($q->num_rows() > 0) {
			$existed = true;
		}

		return $existed;
	}

	public function getCourseByPage($page = 1, $sort = 'DESC')
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

	public function deleteCourse($id)
	{
		$sql = "DELETE FROM Course WHERE ID = ?";
		$q = $this->db->query($sql, $id);

		return $q;
	}

	public function updateCourse($data, $id)
	{
		$success = false;
		$this->db->where('id', $id);
		$this->db->update('Course', $data);

		if ($this->db->affected_rows() > 0) {
			$success = true;
		}

		return $success;
	}

	public function getCourseFromId($id)
	{
		$sql = "SELECT * FROM Course WHERE ID = ?";
		$q = $this->db->query($sql, $id);
		$course = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$course[] = $row;
			}
		}

		return $course;
	}
}