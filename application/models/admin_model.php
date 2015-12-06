<?php

class Admin_model extends CI_Model 
{
	protected $tableName = 'Admin';

	public function getAdminFromId($id)
	{
		$sql = "SELECT * FROM Admin WHERE ID = ?";
		$q = $this->db->query($sql, $id);
		$admin = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$admin[] = $row;
			}
		}

		return $admin;
	}

	public function deleteAdmin($id)
	{
		$sql = "DELETE FROM Admin WHERE ID = ?";
		$q = $this->db->query($sql, $id);

		return $q;
	}

	public function addAdmin($data)
	{
		$this->db->insert('Admin', $data);

		return $this->db->insert_id();;
	}

	public function updateAdmin($data, $id)
	{
		$success = false;
		$this->db->where('id', $id);
		$this->db->update('Admin', $data);

		if ($this->db->affected_rows() > 0) {
			$success = true;
		}

		return $success;
	}

	public function getAdminByPage($page = 1, $sort = 'DESC')
	{
		$this->db->from($this->tableName);
		if ($page == 1) {
			$this->db->limit(25);
    	} else {
        	$offset = ($page - 1) * 25;
        	$this->db->limit(25, $offset);
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

	public function checkUsernameExisted($username)
	{
		$existed = false;
		$sql = "SELECT * FROM Admin WHERE username = ?";
		$q = $this->db->query($sql, $username);
		$admin = array();

		if ($q->num_rows() > 0) {
			$existed = true;
		}

		return $existed;
	}
}