<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Settings extends admin {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('settings_model');
	}
    
	/*
	*
	*	Edit system settings
	*
	*/
	public function index() 
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Contact Email', 'xss_clean');
		$this->form_validation->set_rules('phone', 'Company Phone', 'xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('post_code', 'Address', 'xss_clean');
		$this->form_validation->set_rules('physical', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('facebook', 'Facebook Name', 'xss_clean');
		$this->form_validation->set_rules('twitter', 'Twitter Name', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$this->settings_model->edit_settings();
		}
		
		//open the add new user page
		$data['title'] = 'Edit System Settings';
		
		//select the user from the database
		$query = $this->settings_model->get_settings();
		$v_data['settings'] = $query->result();
		$data['content'] = $this->load->view('settings', $v_data, true);
		
		$this->load->view('templates/no_accordion', $data);
	}
}