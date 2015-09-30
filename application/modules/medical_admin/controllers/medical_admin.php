<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medical_admin extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('medical_admin_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('reception/reception_model');
		$this->load->model('database');
		$this->load->model('reception/reception_model');
		$this->load->model('nurse/nurse_model');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function index()
	{
		echo "no patient id";
	}

}
?>