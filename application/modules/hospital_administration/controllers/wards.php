<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Wards extends Hospital_administration 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the wards
	*
	*/
	public function index($order = 'ward_name', $order_method = 'ASC') 
	{
		$where = 'ward_id > 0';
		$table = 'ward';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/wards/'.$order.'/'.$order_method;
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
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->wards_model->get_all_wards($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Wards';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('wards/all_wards', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new ward
	*
	*/
	public function add_ward() 
	{
		//form validation rules
		$this->form_validation->set_rules('ward_name', 'Ward Name', 'required|xss_clean');
		$this->form_validation->set_rules('ward_status', 'Ward Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->wards_model->add_ward())
			{
				$this->session->set_userdata('success_message', 'Ward added successfully');
				redirect('hospital-administration/wards');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add ward. Please try again');
			}
		}
		
		$data['title'] = 'Add ward';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('wards/add_ward', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing ward
	*	@param int $ward_id
	*
	*/
	public function edit_ward($ward_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('ward_name', 'Ward Name', 'required|xss_clean');
		$this->form_validation->set_rules('ward_status', 'Ward Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update ward
			if($this->wards_model->update_ward($ward_id))
			{
				$this->session->set_userdata('success_message', 'Ward updated successfully');
				redirect('hospital-administration/wards');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update ward. Please try again');
			}
		}
		
		//open the add new ward
		$data['title'] = 'Edit ward';
		$v_data['title'] = $data['title'];
		
		//select the ward from the database
		$query = $this->wards_model->get_ward($ward_id);
		
		$v_data['ward'] = $query->result();
		
		$data['content'] = $this->load->view('wards/edit_ward', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing ward
	*	@param int $ward_id
	*
	*/
	public function delete_ward($ward_id)
	{
		$this->wards_model->delete_ward($ward_id);
		$this->session->set_userdata('success_message', 'Ward has been deleted');
		
		redirect('hospital-administration/wards');
	}
    
	/*
	*
	*	Activate an existing ward
	*	@param int $ward_id
	*
	*/
	public function activate_ward($ward_id)
	{
		$this->wards_model->activate_ward($ward_id);
		$this->session->set_userdata('success_message', 'Ward activated successfully');
		
		redirect('hospital-administration/wards');
	}
    
	/*
	*
	*	Deactivate an existing ward
	*	@param int $ward_id
	*
	*/
	public function deactivate_ward($ward_id)
	{
		$this->wards_model->deactivate_ward($ward_id);
		$this->session->set_userdata('success_message', 'Ward disabled successfully');
		
		redirect('hospital-administration/wards');
	}
}
?>