<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/core/base_api_controller.php';

class Payment extends Base_Api_Controller {

	public function index()
	{
		$input = $this->input->post();
		$paymentId = '';
		$token = $this->getPaypalToken();
		$id = $input->response->id;

		$response = $this->processPayment($id, $token);

		if (empty($response)) {
			echo json_encode(array('statue' => false, 'message' => 'Cannot verified'));
			exit;
		}

		if ($response->state == 'approved') {
			$currentDate = 
			$data = array(
				'courseID' => $input->menuId,
				'courseType' => $this->getPaymentType($input->type),
				'studentID' => $input->studentId,
				'paymentID' => $id,
				'date' => $response->create_time
			);
			$paymentId = $this->payment_model->addPayment($data);
		}

		echo json_encode(array('status' => !empty($paymentId), 'message' => 'Payment verified'));
	}

	protected function getPaypalToken()
	{
		$clientId = $this->config->config['client_id'];
	 	$secret = $this->config->config['client_secret'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

		$result = curl_exec($ch);

		if(empty($result))die("Error: No response.");
		else
		{
		    $json = json_decode($result);
		}

		curl_close($ch);

		return $json->access_token;
	}

	protected function processPayment($id, $token)
	{
		$response = array();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payment/' . $id);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token));

		$result = curl_exec($ch);

		if (!empty($result)) {
			$response = json_decode($result);
		}

		curl_close($ch);

		return $response;
	}

	protected function getPaymentType($input)
	{
		$type = array(
			'course' => 0,
			'menu' => 1
		);

		return $type[$input];
	}
}
