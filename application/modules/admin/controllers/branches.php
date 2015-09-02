<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Branches extends admin 
{
	var $branches_path;
	var $branches_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('branches_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->branches_path = realpath(APPPATH . '../assets/logo');
		$this->branches_location = base_url().'assets/logo/';
	}
    
	/*
	*
	*	Default action is to show all the branches
	*
	*/
	public function index($order = 'branch_name', $order_method = 'ASC') 
	{
		$where = 'branch_id > 0';
		$table = 'branch';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/branches/'.$order.'/'.$order_method;
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
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->branches_model->get_all_branches($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Branches';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_branches'] = $this->branches_model->all_branches();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('branches/all_branches', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new branch
	*
	*/
	public function add_branch() 
	{
		$v_data['branch_location'] = 'http://placehold.it/200x200';
		
		$this->session->unset_userdata('branch_error_message');
		
		//upload image if it has been selected
		$response = $this->branches_model->upload_branch_logo($this->branches_path, $this->branches_location);
		if($response)
		{
			$v_data['branch_location'] = $this->branches_location.$this->session->userdata('branch_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['branch_error'] = $this->session->userdata('branch_error_message');
		}
		
		$branch_error = $this->session->userdata('branch_error_message');
		
		//form validation rules
		$this->form_validation->set_rules('branch_name', 'Branch name', 'required|xss_clean');
		$this->form_validation->set_rules('branch_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('branch_email', 'Email', 'xss_clean');
		$this->form_validation->set_rules('branch_email', 'Email', 'valid_email|xss_clean');
		$this->form_validation->set_rules('branch_working_weekday', 'Weekday working hours', 'xss_clean');
		$this->form_validation->set_rules('branch_working_weekend', 'Weekend working hours', 'xss_clean');
		$this->form_validation->set_rules('branch_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('branch_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('branch_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('branch_location', 'Location', 'xss_clean');
		$this->form_validation->set_rules('branch_building', 'Building', 'xss_clean');
		$this->form_validation->set_rules('branch_floor', 'Floor', 'xss_clean');
		$this->form_validation->set_rules('branch_status', 'Status', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if(empty($branch_error))
			{
				if($this->branches_model->add_branch($this->session->userdata('branch_file_name'), $this->session->userdata('branch_thumb_name')))
				{
					$this->session->unset_userdata('branch_file_name');
					$this->session->unset_userdata('branch_thumb_name');
					$this->session->unset_userdata('branch_error_message');
					$this->session->set_userdata('success_message', 'Branch added successfully');
					redirect('administration/branches');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not add branch. Please try again');
				}
			}
		}
		
		//open the add new branch
		
		$data['title'] = 'Add branch';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('branches/add_branch', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing branch
	*	@param int $branch_id
	*
	*/
	public function edit_branch($branch_id) 
	{
		//select the branch from the database
		$query = $this->branches_model->get_branch($branch_id);
		$slide_row = $query->row();
		$v_data['query'] = $query;
		$v_data['branch_location'] = $this->branches_location.$slide_row->branch_thumb_name;
		
		$this->session->unset_userdata('branch_error_message');
		
		//upload image if it has been selected
		$response = $this->branches_model->upload_branch_logo($this->branches_path, $this->branches_location);
		if($response)
		{
			$v_data['branch_location'] = $this->branches_location.$this->session->userdata('branch_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['branch_error'] = $this->session->userdata('branch_error_message');
		}
		
		$branch_error = $this->session->userdata('branch_error_message');
		
		//form validation rules
		$this->form_validation->set_rules('branch_name', 'Branch name', 'required|xss_clean');
		$this->form_validation->set_rules('branch_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('branch_email', 'Email', 'valid_email|xss_clean');
		$this->form_validation->set_rules('branch_working_weekday', 'Weekday working hours', 'xss_clean');
		$this->form_validation->set_rules('branch_working_weekend', 'Weekend working hours', 'xss_clean');
		$this->form_validation->set_rules('branch_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('branch_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('branch_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('branch_location', 'Location', 'xss_clean');
		$this->form_validation->set_rules('branch_building', 'Building', 'xss_clean');
		$this->form_validation->set_rules('branch_floor', 'Floor', 'xss_clean');
		$this->form_validation->set_rules('branch_status', 'Status', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if(empty($branch_error))
			{
				$image = $this->session->userdata('branch_file_name');
				$thumb = $this->session->userdata('branch_thumb_name');
				
				if($image == FALSE)
				{
					$image = $slide_row->branch_image_name;
					$thumb = $slide_row->branch_thumb_name;
				}
				if($this->branches_model->update_branch($image, $thumb, $branch_id))
				{
					$this->session->unset_userdata('branch_file_name');
					$this->session->unset_userdata('branch_thumb_name');
					$this->session->unset_userdata('branch_error_message');
					$this->session->set_userdata('success_message', 'Branch updated successfully');
					redirect('administration/branches');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not update branch. Please try again');
				}
			}
		}
		
		//open the add new branch
		$data['title'] = 'Edit branch';
		$v_data['title'] = $data['title'];
		
		if ($query->num_rows() > 0)
		{
			$v_data['branch'] = $query->row();
			
			$data['content'] = $this->load->view('branches/edit_branch', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Branch does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing branch
	*	@param int $branch_id
	*
	*/
	public function delete_branch($branch_id)
	{
		//delete branch image
		$query = $this->branches_model->get_branch($branch_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->branch_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->branches_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->branches_path."/thumbs/".$image);
		}
		$this->branches_model->delete_branch($branch_id);
		$this->session->set_userdata('success_message', 'Branch has been deleted');
		redirect('admin/branches');
	}
    
	/*
	*
	*	Activate an existing branch
	*	@param int $branch_id
	*
	*/
	public function activate_branch($branch_id)
	{
		$this->branches_model->activate_branch($branch_id);
		$this->session->set_userdata('success_message', 'Branch activated successfully');
		redirect('admin/branches');
	}
    
	/*
	*
	*	Deactivate an existing branch
	*	@param int $branch_id
	*
	*/
	public function deactivate_branch($branch_id)
	{
		$this->branches_model->deactivate_branch($branch_id);
		$this->session->set_userdata('success_message', 'Branch disabled successfully');
		redirect('admin/branches');
	}
}
?>