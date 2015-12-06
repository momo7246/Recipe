<?php

class Subtitle_model extends CI_Model 
{
	protected $tableName = 'Subtitle';

	public function getSubtitleFromMenuId($id)
	{
		$subtitles = array();
		$this->db->where('menuID', $id);
		$q = $this->db->get($this->tableName);
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$subtitles[] = $row;
			}
		}

		return json_decode(json_encode($subtitles), true);
	}

	public function addSubtitle($data)
	{
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);
	}

	public function checkExisted($id)
	{
		$this->db->where('menuID', $id);
		$q = $this->db->get($this->tableName);
		$existed = ($q->num_rows() > 0) ? true : false;

		return $existed;
	}

	public function updateSubtitle($subtitles, $id)
	{
		foreach ($subtitles as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->where('menuID', $id);
		$this->db->update($this->tableName);
	}

	public function getSubtitles($ids)
	{
		$itemsArr = array();
		$this->db->select(array('menuID', 'subtitleTH', 'subtitleEN', 'subtitleCN'));
		$this->db->from($this->tableName);
		$this->db->where_in('menuID', $ids);
		$items = $this->db->get();

    	if ($items->num_rows() > 0) {
			foreach ($items->result() as $row) {
				$itemsArr[] = $row;
			}
		}
		$subtitles = array();
		foreach ($itemsArr as $key => $value) {
			$subtitles[$value->menuID] = $itemsArr[$key];
		}

    	return json_decode(json_encode($subtitles), true);
	}

	public function deleteSubtitle()
	{
		$this->db->where('menuID', $id);
		$this->db->delete($this->tableName);
	}
}