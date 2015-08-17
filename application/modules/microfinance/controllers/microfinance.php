<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Microfinance extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/users_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/admin_model');
		$this->load->model('individual_model');
		$this->load->model('group_model');
		$this->load->model('savings_plan_model');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
    
	/*
	*
	*	Dashboard
	*
	*/
	public function dashboard() 
	{
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('dashboard', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
}
?>