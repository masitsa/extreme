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
		
		// $info = file_get_contents('http://159.203.78.242/cloud/save_cloud_data');
		
	    $this->cloud_model->insert_into_test('sdaasda');
	}
}
?>