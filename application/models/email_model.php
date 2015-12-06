<?php

class Email_model extends CI_Model 
{
	protected $tableName = 'Email';

	public function addEmail($data)
	{
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);
	}

	public function getWaitList()
	{
		$result = array();
		$this->db->where('status', 'WAIT');
		$q = $this->db->get($this->tableName);
		if ($q->num_rows() > 0) {
			$result = $q->first_row();
		}

		return json_decode(json_encode($result), true);
	}

	public function updateEmailStatus($id)
	{
		$this->db->where('ID', $id);
		$this->db->update($this->tableName, array('status' => 'SEND'));
	}
}
