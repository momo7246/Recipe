<?php

class Payment_model extends CI_Model 
{
	protected $tableName = 'Payment';

	public function addPayment($data)
	{
		foreach ($data as $key => $value) {
			$this->db->set($key, $value);
		}
		$this->db->insert($this->tableName);

		return $this->db->insert_id();
	}

	public function getPaymentFromStudentId($id)
	{
		$sql = 'SELECT * FROM Payment WHERE StudentID = ?';
		$q = $this->db->query($sql, $id);
		$payment = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$payment[] = $row;
			}
		}

		return json_decode(json_encode($payment), true);
	}

	public function getCurrentTotal($year)
	{
		$sql = 'SELECT count(studentID) AS studentNo, month(date) AS month FROM Payment WHERE year(date) = ? GROUP BY month(date) ORDER BY month(date)';
		
		$q = $this->db->query($sql, $year);
		$total = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$total[] = $row;
			}
		}

		return json_decode(json_encode($total), true);
	}

	public function getCourseInPayment($month, $year)
	{
		$sql = 'SELECT courseID, courseType, count(studentID) AS count FROM Payment WHERE month(date) = ? AND year(date) = ? GROUP BY courseID, courseType';

		$q = $this->db->query($sql, array($month, $year));
		$courses = array();

		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$courses[] = $row;
			}
		}

		return json_decode(json_encode($courses), true);
	}

	public function getPayment($where = array())
	{
		$this->db->where($where);
		$q = $this->db->get($this->tableName);
		$payment = array();

		$existed = false;
		if ($q->num_rows() > 0) {
			$existed = true;
		}

		return $existed;
	}

	public function deletePayment($id)
	{
		$this->db->where('studentID', $id);
		$this->db->delete($this->tableName);
	}

	public function getPendingPayments()
	{
		$result = array();
		$where = array(
			'paymentID IS NOT NULL' => null,
			'status !=' => 'approved'
		);

		foreach ($where as $key => $value) {
			$this->db->where($key, $value);
		}

		$payment = $this->db->get($this->tableName);
		if ($payment->num_rows() > 0) {
			foreach ($payment->result() as $row) {
				$result[] = $row;
			}
		}

		return json_decode(json_encode($result), true);
	}

	public function updatePayment($payment, $id)
	{
		$this->db->where('ID', $id);
		$this->db->update($this->tableName, $payment);
	}

	public function checkStudentEnrollment($wheres)
	{
		foreach ($wheres as $key => $value) {
			$this->db->where($key, $value);
		}
		$result = $this->db->get($this->tableName);
		$existed = ($result->num_rows() > 0) ? true : false;

		return $existed;
	}

	public function deletePaymentFromID($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete($this->tableName);
	}
}