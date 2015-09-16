<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hospital_administration extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/users_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('hr/personnel_model');
		$this->load->model('administration/administration_model');
		$this->load->model('companies_model');
		$this->load->model('visit_types_model');
		$this->load->model('departments_model');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
}
?>