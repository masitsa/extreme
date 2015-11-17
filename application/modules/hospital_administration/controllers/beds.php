<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Beds extends Hospital_administration 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the beds
	*
	*/
	public function index($room_id, $order = 'bed_number', $order_method = 'ASC') 
	{
		$where = 'room_id = '.$room_id;
		$table = 'bed';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/beds/'.$room_id.'/'.$order.'/'.$order_method;
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
		$query = $this->beds_model->get_all_beds($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$this->db->where('room_id = '.$room_id);
		$query2 = $this->db->get('room');
		if($query2->num_rows() > 0)
		{
			$row = $query2->row();
			$room_capacity = $row->room_capacity;
			$ward_id = $row->ward_id;
			$v_data['ward_id'] = $ward_id;
		}
		
		$data['title'] = 'Beds';
		$v_data['title'] = $data['title'];
		
		$v_data['room_id'] = $room_id;
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('beds/all_beds', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new bed
	*
	*/
	public function add_bed($room_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('cash_price', 'Cash Price', 'required|xss_clean');
		$this->form_validation->set_rules('insurance_price', 'Insurance Price', 'required|xss_clean');
		$this->form_validation->set_rules('bed_status', 'Bed Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->beds_model->add_bed($room_id))
			{
				$this->session->set_userdata('success_message', 'Bed added successfully');
				redirect('hospital-administration/beds/'.$room_id);
			}
		}
		
		$v_data['room_id'] = $room_id;
		$data['title'] = 'Add bed';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('beds/add_bed', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing bed
	*	@param int $bed_id
	*
	*/
	public function edit_bed($room_id, $bed_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('cash_price', 'Cash Price', 'required|xss_clean');
		$this->form_validation->set_rules('insurance_price', 'Insurance Price', 'required|xss_clean');
		$this->form_validation->set_rules('bed_status', 'Bed Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update bed
			if($this->beds_model->update_bed($room_id, $bed_id))
			{
				$this->session->set_userdata('success_message', 'Bed updated successfully');
				redirect('hospital-administration/beds/'.$room_id);
			}
			
			else
			{
			}
		}
		else
		{
			
            $validation_errors = validation_errors();
			//var_dump($validation_errors); die();
		}
		
		
		$v_data['room_id'] = $room_id;
		//open the add new bed
		$data['title'] = 'Edit bed';
		$v_data['title'] = $data['title'];
		
		//select the bed from the database
		$query = $this->beds_model->get_bed($bed_id);
		
		$v_data['bed'] = $query->result();
		
		$data['content'] = $this->load->view('beds/edit_bed', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing bed
	*	@param int $bed_id
	*
	*/
	public function delete_bed($room_id, $bed_id)
	{
		$this->beds_model->delete_bed($bed_id);
		$this->session->set_userdata('success_message', 'Bed has been deleted');
		
		redirect('hospital-administration/beds/'.$room_id);
	}
    
	/*
	*
	*	Activate an existing bed
	*	@param int $bed_id
	*
	*/
	public function activate_bed($room_id, $bed_id)
	{
		if($this->beds_model->activate_bed($room_id, $bed_id))
		{
			$this->session->set_userdata('success_message', 'Bed activated successfully');
		}
		
		redirect('hospital-administration/beds/'.$room_id);
	}
    
	/*
	*
	*	Deactivate an existing bed
	*	@param int $bed_id
	*
	*/
	public function deactivate_bed($room_id, $bed_id)
	{
		$this->beds_model->deactivate_bed($bed_id);
		$this->session->set_userdata('success_message', 'Bed disabled successfully');
		
		redirect('hospital-administration/beds/'.$room_id);
	}
}
?>