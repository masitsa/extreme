<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Services extends admin {
	var $service_path;
	var $service_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('service_model');
		$this->load->model('department_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->service_path = realpath(APPPATH . '../assets/service');
		$this->service_location = base_url().'assets/service/';
	}
    
	/*
	*
	*	Default action is to show all the registered service
	*
	*/
	public function index() 
	{
		$where = 'service.department_id = department.department_id';
		$table = 'service, department';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-services';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->service_model->get_all_services($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['service_location'] = $this->service_location;
			$v_data['active_departments'] = $this->department_model->get_active_departments();
			$data['content'] = $this->load->view('service/all_services', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-service" class="btn btn-success pull-right">Add Service</a>There are no services';
		}
		$data['title'] = 'All Services';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function add_service()
	{
		$v_data['service_location'] = 'http://placehold.it/500x500';
		
		$this->session->unset_userdata('service_error_message');
		
		//upload image if it has been selected
		$response = $this->service_model->upload_service_image($this->service_path);
		if($response)
		{
			$v_data['service_location'] = $this->service_location.$this->session->userdata('service_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['service_error'] = $this->session->userdata('service_error_message');
		}
		
		$service_error = $this->session->userdata('service_error_message');
		
		$this->form_validation->set_rules('service_name', 'Service name', 'trim|xss_clean');
		$this->form_validation->set_rules('service_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('department_id', 'Department', 'numeric|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($service_error))
			{
				$data2 = array(
					'service_name'=>$this->input->post("service_name"),
					'service_description'=>$this->input->post("service_description"),
					'service_status'=>1,
					'service_image_name'=>$this->session->userdata('service_file_name'),
					'department_id'=>$this->input->post("department_id")
				);
				
				$table = "service";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('service_file_name');
				$this->session->unset_userdata('service_thumb_name');
				$this->session->unset_userdata('service_error_message');
				$this->session->set_userdata('success_message', 'Service has been added');
				
				redirect('administration/all-services');
			}
		}
		
		$service = $this->session->userdata('service_file_name');
		
		if(!empty($service))
		{
			$v_data['service_location'] = $this->service_location.$this->session->userdata('service_file_name');
		}
		$v_data['error'] = $service_error;
		$v_data['active_departments'] = $this->department_model->get_active_departments();
		
		$data['content'] = $this->load->view("service/add_service", $v_data, TRUE);
		$data['title'] = 'Add Service';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function edit_service($service_id, $page)
	{
		//get service data
		$table = "service";
		$where = "service_id = ".$service_id;
		
		$this->db->where($where);
		$services_query = $this->db->get($table);
		$service_row = $services_query->row();
		$v_data['service_row'] = $service_row;
		$v_data['service_location'] = $this->service_location.$service_row->service_image_name;
		
		$this->session->unset_userdata('service_error_message');
		
		//upload image if it has been selected
		$response = $this->service_model->upload_service_image($this->service_path, $edit = $service_row->service_image_name);
		if($response)
		{
			$v_data['service_location'] = $this->service_location.$this->session->userdata('service_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['service_error'] = $this->session->userdata('service_error_message');
		}
		
		$service_error = $this->session->userdata('service_error_message');
		
		$this->form_validation->set_rules('service_name', 'Service name', 'trim|xss_clean');
		$this->form_validation->set_rules('service_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('department_id', 'Department', 'numeric|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($service_error))
			{
		
				$service = $this->session->userdata('service_file_name');
				
				if($service == FALSE)
				{
					$service = $service_row->service_image_name;
				}
				$data2 = array(
					'service_name'=>$this->input->post("service_name"),
					'service_description'=>$this->input->post("service_description"),
					'department_id'=>$this->input->post("department_id"),
					'service_image_name'=>$service
				);
				
				$table = "service";
				$this->db->where('service_id', $service_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('service_file_name');
				$this->session->unset_userdata('service_thumb_name');
				$this->session->unset_userdata('service_error_message');
				$this->session->set_userdata('success_message', 'Service has been edited');
				
				redirect('administration/all-services/'.$page);
			}
		}
		
		$service = $this->session->userdata('service_file_name');
		
		if(!empty($service))
		{
			$v_data['service_location'] = $this->service_location.$this->session->userdata('service_file_name');
		}
		$v_data['error'] = $service_error;
		$v_data['active_departments'] = $this->department_model->get_active_departments();
		
		$data['content'] = $this->load->view("service/edit_service", $v_data, TRUE);
		$data['title'] = 'Edit Service';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing service
	*	@param int $service_id
	*
	*/
	function delete_service($service_id, $page)
	{
		//get service data
		$table = "service";
		$where = "service_id = ".$service_id;
		
		$this->db->where($where);
		$services_query = $this->db->get($table);
		$service_row = $services_query->row();
		$service_path = $this->service_path;
		
		$image_name = $service_row->service_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($service_path."\\".$image_name);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($service_path."\\thumbnail_".$image_name);
		
		if($this->service_model->delete_service($service_id))
		{
			$this->session->set_userdata('success_message', 'Service has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Service could not be deleted');
		}
		redirect('administration/all-services/'.$page);
	}
    
	/*
	*
	*	Activate an existing service
	*	@param int $service_id
	*
	*/
	public function activate_service($service_id, $page)
	{
		if($this->service_model->activate_service($service_id))
		{
			$this->session->set_userdata('success_message', 'Service has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Service could not be activated');
		}
		redirect('administration/all-services/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing service
	*	@param int $service_id
	*
	*/
	public function deactivate_service($service_id, $page)
	{
		if($this->service_model->deactivate_service($service_id))
		{
			$this->session->set_userdata('success_message', 'Service has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Service could not be disabled');
		}
		redirect('administration/all-services/'.$page);
	}
}
?>