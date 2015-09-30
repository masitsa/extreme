<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Rooms extends Hospital_administration 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the rooms
	*
	*/
	public function index($ward_id, $order = 'room_name', $order_method = 'ASC') 
	{
		$where = 'ward_id = '.$ward_id;
		$table = 'room';
		//pagination
		$segment = 6;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/rooms/'.$ward_id.'/'.$order.'/'.$order_method;
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
		$query = $this->rooms_model->get_all_rooms($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Rooms';
		$v_data['title'] = $data['title'];
		
		$v_data['ward_id'] = $ward_id;
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('rooms/all_rooms', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new room
	*
	*/
	public function add_room($ward_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('room_name', 'Room Name', 'required|xss_clean');
		$this->form_validation->set_rules('room_capacity', 'Capacity', 'numeric|required|xss_clean');
		$this->form_validation->set_rules('room_status', 'Room Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->rooms_model->add_room($ward_id))
			{
				$this->session->set_userdata('success_message', 'Room added successfully');
				redirect('hospital-administration/rooms/'.$ward_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add room. Please try again');
			}
		}
		
		$v_data['ward_id'] = $ward_id;
		$data['title'] = 'Add room';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('rooms/add_room', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing room
	*	@param int $room_id
	*
	*/
	public function edit_room($ward_id, $room_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('room_name', 'Room Name', 'required|xss_clean');
		$this->form_validation->set_rules('room_capacity', 'Capacity', 'numeric|required|xss_clean');
		$this->form_validation->set_rules('room_status', 'Room Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update room
			if($this->rooms_model->update_room($room_id))
			{
				$this->session->set_userdata('success_message', 'Room updated successfully');
				redirect('hospital-administration/rooms/'.$ward_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update room. Please try again');
			}
		}
		
		$v_data['ward_id'] = $ward_id;
		//open the add new room
		$data['title'] = 'Edit room';
		$v_data['title'] = $data['title'];
		
		//select the room from the database
		$query = $this->rooms_model->get_room($room_id);
		
		$v_data['room'] = $query->result();
		
		$data['content'] = $this->load->view('rooms/edit_room', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing room
	*	@param int $room_id
	*
	*/
	public function delete_room($ward_id, $room_id)
	{
		$this->rooms_model->delete_room($room_id);
		$this->session->set_userdata('success_message', 'Room has been deleted');
		
		redirect('hospital-administration/rooms/'.$ward_id);
	}
    
	/*
	*
	*	Activate an existing room
	*	@param int $room_id
	*
	*/
	public function activate_room($ward_id, $room_id)
	{
		$this->rooms_model->activate_room($room_id);
		$this->session->set_userdata('success_message', 'Room activated successfully');
		
		redirect('hospital-administration/rooms/'.$ward_id);
	}
    
	/*
	*
	*	Deactivate an existing room
	*	@param int $room_id
	*
	*/
	public function deactivate_room($ward_id, $room_id)
	{
		$this->rooms_model->deactivate_room($room_id);
		$this->session->set_userdata('success_message', 'Room disabled successfully');
		
		redirect('hospital-administration/rooms/'.$ward_id);
	}
}
?>