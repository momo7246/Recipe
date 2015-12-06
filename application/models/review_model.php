<?php

class Review_model extends CI_Model 
{
	protected $tableName = 'Review';

	public function addReview($data)
	{
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);

		return $this->db->insert_id();
	}

	public function getComments($ids)
	{
		$response = array();
		$data = array();
		$this->db->where_in('menuID', $ids);
		$this->db->sort_by('ID', 'desc');
		$comments = $this->db->get($this->tableName);
		if ($comments->num_rows() > 0) {
			foreach ($comments->result() as $row) {
				$response[] = $row;
			}
		}

		return json_decode(json_encode($response), true);
	}
}