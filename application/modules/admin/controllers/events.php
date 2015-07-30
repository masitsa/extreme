<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Events extends admin {
	var $event_path;
	var $event_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('event_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->event_path = realpath(APPPATH . '../assets/event');
		$this->event_location = base_url().'assets/event/';
	}
    
	/*
	*
	*	Default action is to show all the registered event
	*
	*/
	public function index() 
	{
		$where = 'event_id > 0';
		$table = 'event';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-events';
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
		$query = $this->event_model->get_all_events($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['event_location'] = $this->event_location;
			$data['content'] = $this->load->view('event/all_events', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-event" class="btn btn-success pull-right">Add Event</a>There are no events';
		}
		$data['title'] = 'All Events';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function add_event()
	{
		$v_data['event_location'] = 'http://placehold.it/500x500';
		
		$this->session->unset_userdata('event_error_message');
		
		//upload image if it has been selected
		$response = $this->event_model->upload_event_image($this->event_path);
		if($response)
		{
			$v_data['event_location'] = $this->event_location.$this->session->userdata('event_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['event_error'] = $this->session->userdata('event_error_message');
		}
		
		$event_error = $this->session->userdata('event_error_message');
		
		$this->form_validation->set_rules('event_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('event_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($event_error))
			{
				$data2 = array(
					'event_name'=>$this->input->post("event_name"),
					'event_venue'=>$this->input->post("event_venue"),
					'event_start_time'=>$this->input->post("event_start_time"),
					'event_end_time'=>$this->input->post("event_end_time"),
					'event_location'=>$this->input->post("event_location"),
					'event_admission'=>$this->input->post("event_admission"),
					'event_status'=>1,
					'event_image_name'=>$this->session->userdata('event_file_name'),
					'event_web_name' => $this->users_model->create_web_name($this->input->post("event_name")),
					'created_by' => $this->session->userdata('user_id'),
					'created' => date('Y-m-d H:i:s'),
					'modified_by' => $this->session->userdata('user_id')
				);
				
				$table = "event";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('event_file_name');
				$this->session->unset_userdata('event_thumb_name');
				$this->session->unset_userdata('event_error_message');
				$this->session->set_userdata('success_message', 'Event has been added');
				
				redirect('administration/all-events');
			}
		}
		
		$event = $this->session->userdata('event_file_name');
		
		if(!empty($event))
		{
			$v_data['event_location'] = $this->event_location.$this->session->userdata('event_file_name');
		}
		$v_data['error'] = $event_error;
		
		$data['content'] = $this->load->view("event/add_event", $v_data, TRUE);
		$data['title'] = 'Add Event';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function edit_event($event_id, $page)
	{
		//get event data
		$table = "event";
		$where = "event_id = ".$event_id;
		
		$this->db->where($where);
		$events_query = $this->db->get($table);
		$event_row = $events_query->row();
		$v_data['event_row'] = $event_row;
		$v_data['event_location'] = $this->event_location.$event_row->event_image_name;
		
		$this->session->unset_userdata('event_error_message');
		
		//upload image if it has been selected
		$response = $this->event_model->upload_event_image($this->event_path, $edit = $event_row->event_image_name);
		if($response)
		{
			$v_data['event_location'] = $this->event_location.$this->session->userdata('event_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['event_error'] = $this->session->userdata('event_error_message');
		}
		
		$event_error = $this->session->userdata('event_error_message');
		
		$this->form_validation->set_rules('check', 'check', 'trim|xss_clean');
		$this->form_validation->set_rules('event_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('event_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($event_error))
			{
				$event = $this->session->userdata('event_file_name');
				
				if($event == FALSE)
				{
					$event = $event_row->event_image_name;
				}
				$data2 = array(
					'event_name'=>$this->input->post("event_name"),
					'event_venue'=>$this->input->post("event_venue"),
					'event_start_time'=>$this->input->post("event_start_time"),
					'event_end_time'=>$this->input->post("event_end_time"),
					'event_location'=>$this->input->post("event_location"),
					'event_admission'=>$this->input->post("event_admission"),
					'event_description'=>$this->input->post("event_description"),
					'event_image_name'=>$event,
					'event_web_name' => $this->users_model->create_web_name($this->input->post("event_name")),
					'modified_by' => $this->session->userdata('user_id')
				);
				
				$table = "event";
				$this->db->where('event_id', $event_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('event_file_name');
				$this->session->unset_userdata('event_thumb_name');
				$this->session->unset_userdata('event_error_message');
				$this->session->set_userdata('success_message', 'Event has been edited');
				
				redirect('administration/all-events/'.$page);
			}
		}
		
		$event = $this->session->userdata('event_file_name');
		
		if(!empty($event))
		{
			$v_data['event_location'] = $this->event_location.$this->session->userdata('event_file_name');
		}
		$v_data['error'] = $event_error;
		
		$data['content'] = $this->load->view("event/edit_event", $v_data, TRUE);
		$data['title'] = 'Edit Event';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing event
	*	@param int $event_id
	*
	*/
	function delete_event($event_id, $page)
	{
		//get event data
		$table = "event";
		$where = "event_id = ".$event_id;
		
		$this->db->where($where);
		$events_query = $this->db->get($table);
		$event_row = $events_query->row();
		$event_path = $this->event_path;
		
		$image_name = $event_row->event_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($event_path."\\".$image_name);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($event_path."\\thumbnail_".$image_name);
		
		if($this->event_model->delete_event($event_id))
		{
			$this->session->set_userdata('success_message', 'Event has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Event could not be deleted');
		}
		redirect('administration/all-events/'.$page);
	}
    
	/*
	*
	*	Activate an existing event
	*	@param int $event_id
	*
	*/
	public function activate_event($event_id, $page)
	{
		if($this->event_model->activate_event($event_id))
		{
			$this->session->set_userdata('success_message', 'Event has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Event could not be activated');
		}
		redirect('administration/all-events/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing event
	*	@param int $event_id
	*
	*/
	public function deactivate_event($event_id, $page)
	{
		if($this->event_model->deactivate_event($event_id))
		{
			$this->session->set_userdata('success_message', 'Event has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Event could not be disabled');
		}
		redirect('administration/all-events/'.$page);
	}
}
?>