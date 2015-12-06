<?php

class Student_model extends CI_Model 
{
	protected $tableName = 'Student';
	protected $entities = array('Name', 'Surname', 'Username', 'Password', 'Token', 'Email');

	public function autoSuggestion($keyword)
	{
		$sql = 'SELECT ID, name, surname, email FROM Student WHERE name LIKE ? OR email LIKE ?';
		$q = $this->db->query($sql, array('%'.$keyword.'%', '%'.$keyword.'%'));
		$students = array();
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$student[] = $row;
			}
		}

    	return $student;
	}

	public function getStudentByPage($page = 1, $sort = 'DESC')
	{
		$itemsArr = array();
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

	public function checkStudentExisted($username, $password)
	{
		$student = array();
		$sql = "SELECT * FROM Student WHERE Username = ? AND Password = ?";
		$q = $this->db->query($sql, array($username, $password));

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$student[] = $row;
			}
		}

		return json_decode(json_encode($student), true);
	}

	public function checkUserExisted($wheres)
	{
		$student = array();
		$message = array();
		$result = array();
		$error = 0;
		foreach ($wheres as $key => $value) {
			$this->db->where($key, $value);
			$result = $this->db->get($this->tableName);
			if ($result->num_rows > 0) {
				foreach ($result->result() as $row) {
					$student = $row;
				}
				$error++;
				$message[] = $key;
			}
		}

		$response = array(
			'existed' => ($error > 0),
			'student' => $student,
			'message' => implode(", ", $message)
		);

		return $response;
	}

	public function register($data = array())
	{
		if (empty($data)) {

			return array();
		}

		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);

		return $this->db->insert_id();
	}

	public function updateStudent($data, $id)
	{
		$this->db->where('id', $id);
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);

		$success = ($this->db->affected_rows() > 0) ? true : false;

		return $success;
	}

	public function getStudentFromId($id)
	{
		$this->db->where('ID', $id);
		$student = $this->db->get($this->tableName);

		$result = array();
		if ($student->num_rows() > 0) {
			foreach ($student->result() as $row) {
				$result = $row;
			}
		}

		return json_decode(json_encode($result), true);
	}

	public function deleteStudent($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete($this->tableName);
	}

	public function getAll()
	{
		$result = array();
		$students = $this->db->get($this->tableName);
		if ($students->num_rows() > 0) {
			foreach ($students->result() as $row) {
				$result[] = $row;
			}
		}

		return json_decode(json_encode($result), true);
	}

	public function rePassword($password, $user)
	{
		$this->db->where('Username', $user);
		$this->db->set('Password', $password);
		
		$this->db->update($this->tableName);

		$success = ($this->db->affected_rows() > 0) ? true : false;

		return $success;
	}

	public function getStudentFromUsername($username)
	{
		$result = array();
		$this->db->where('Username', $username);
		$students = $this->db->get($this->tableName);

		$result = $students->first_row('array');

		return json_decode(json_encode($result), true);
	}
}