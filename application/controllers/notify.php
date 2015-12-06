<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify extends Base_Controller {

	protected $passphrase = '1234';

	function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('logged_in')) {
			redirect('/login');
		}
	}

	public function index()
	{	
		$data = array();

		echo $this->m->render('notify', $data);
	}

	public function sendNotify()
	{
		$q = $this->getQuery();
		if (!empty($q)) {
			$students = $this->student_model->getAll();
			$result = $this->sendToMobile($token, $q['message']);
			foreach ($students as $student) {
				if (!empty($student['Token'])) {
					$this->sendToMobile($student['Token'], $q['message']);
				}
			}
		}
	}

	protected function sendToMobile($token, $message)
	{
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', './assets/addition/ck_Vschool_pro.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
		'ssl://gateway.push.apple.com:2195', $err, //'ssl://gateway.push.apple.com:2195', $err,
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);

		$return = 'Connected to APNS' . PHP_EOL;

		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'sound' => 'default'
			);

		// Encode the payload as JSON
		$payload = json_encode($body);

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		if (!$result)
			$return .= 'Message not delivered' . PHP_EOL;
		else
			$return .= 'Message successfully delivered' . PHP_EOL;

		// Close the connection to the server
		fclose($fp);

		return $return;
	}
}
