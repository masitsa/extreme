<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Jobs extends admin 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('jobs_model');
	}
    
	/*
	*
	*	Default action is to show all the jobs
	*
	*/
	public function index($order = 'created', $order_method = 'DESC') 
	{
		$where = 'job_id > 0';
		$table = 'jobs';
		//pagination
		$this->load->library('pagination');
		$segment = 5;
		$config['base_url'] = base_url().'administration/all-jobs/'.$order.'/'.$order_method;
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
		$query = $this->jobs_model->get_all_jobs($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		if ($query->num_rows() > 0)
		{
			$v_data['order'] = $order;
			$v_data['order_method'] = $order_method;
			$v_data['jobs'] = $query;
			$v_data['page'] = $page;
			$data['content'] = $this->load->view('jobs/all_jobs', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-job" class="btn btn-success pull-right">Add job</a> There are no jobs';
		}
		$data['title'] = 'All jobs';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new job page
	*
	*/
	public function add_job() 
	{
		//form validation rules
		$this->form_validation->set_rules('job_title', 'Job title', 'required|xss_clean');
		$this->form_validation->set_rules('job_description', 'Job description', 'required|xss_clean');
		$this->form_validation->set_rules('job_status', 'Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if job has valid login credentials
			if($this->jobs_model->add_job())
			{
				$this->session->set_userdata('success_message', 'Job added successfully');
				redirect('administration/all-jobs');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add job. Please try again');
			}
		}
		
		//open the add new job page
		$data['title'] = 'Add job';
		$data['content'] = $this->load->view('jobs/add_job', '', TRUE);
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Edit an existing job page
	*	@param int $job_id
	*
	*/
	public function edit_job($job_id, $page) 
	{
		//form validation rules
		$this->form_validation->set_rules('job_title', 'Job title', 'required|xss_clean');
		$this->form_validation->set_rules('job_description', 'Job description', 'required|xss_clean');
		$this->form_validation->set_rules('job_status', 'Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if job has valid login credentials
			if($this->jobs_model->edit_job($job_id))
			{
				$this->session->set_userdata('success_message', 'Job edited successfully');
				redirect('administration/all-jobs/'.$page);
			}
			
			else
			{
				$data['error'] = 'Unable to add job. Please try again';
			}
		}
		
		//open the add new job page
		$data['title'] = 'Edit job';
		
		//select the job from the database
		$query = $this->jobs_model->get_job($job_id);
		if ($query->num_rows() > 0)
		{
			$v_data['row'] = $query->row();
			$data['content'] = $this->load->view('jobs/edit_job', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'job does not exist';
		}
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing job page
	*	@param int $job_id
	*
	*/
	public function delete_job($job_id, $page) 
	{
		if($this->jobs_model->delete_job($job_id))
		{
			$this->session->set_userdata('success_message', 'Administrator has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Administrator could not be deleted');
		}
		
		redirect('administration/all-jobs/'.$page);
	}
    
	/*
	*
	*	Activate an existing job page
	*	@param int $job_id
	*
	*/
	public function activate_job($job_id, $page) 
	{
		if($this->jobs_model->activate_job($job_id))
		{
			$this->session->set_userdata('success_message', 'Administrator has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Administrator could not be activated');
		}
		
		redirect('administration/all-jobs/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing job page
	*	@param int $job_id
	*
	*/
	public function deactivate_job($job_id, $page) 
	{
		if($this->jobs_model->deactivate_job($job_id))
		{
			$this->session->set_userdata('success_message', 'Administrator has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Administrator could not be disabled');
		}
		
		redirect('administration/all-jobs/'.$page);
	}
}
?>