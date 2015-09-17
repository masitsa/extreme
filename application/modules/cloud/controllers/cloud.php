<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// require_once "./application/modules/auth/controllers/auth.php";
class Cloud  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('cloud_model');


	}
	public function save_cloud_data()
	{
		$json = file_get_contents('php://input');

	    $response = $this->cloud_model->save_visit_data($json);

	    /*$decoded = json_decode($json);
	    $patients = $decoded->patients;
	    $member = $patients[0];
		var_dump($member->patient_id);*/

		echo json_encode($response);
	}
}
?>