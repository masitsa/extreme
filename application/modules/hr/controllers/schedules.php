<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hr/controllers/hr.php";

class Schedules extends hr 
{
	var $schedules_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
	}
    
	/*
	*
	*	Default action is to show all the schedules
	*
	*/
	public function index($order = 'schedule_name', $order_method = 'ASC') 
	{
		$where = 'schedule_id > 0';
		$table = 'schedule';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'human-resource/schedules/'.$order.'/'.$order_method;
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
		$query = $this->schedules_model->get_all_schedules($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Schedules';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_schedules'] = $this->schedules_model->all_schedules();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('schedules/all_schedules', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new schedule
	*
	*/
	public function add_schedule() 
	{
		//form validation rules
		$this->form_validation->set_rules('schedule_name', 'Schedule Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->schedules_model->add_schedule())
			{
				$this->session->set_userdata('success_message', 'Schedule added successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add schedule. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Edit an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function edit_schedule($schedule_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('schedule_name', 'Schedule Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update schedule
			if($this->schedules_model->update_schedule($schedule_id))
			{
				$this->session->set_userdata('success_message', 'Schedule updated successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update schedule. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Delete an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function delete_schedule($schedule_id)
	{
		$this->schedules_model->delete_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule has been deleted');
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Activate an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function activate_schedule($schedule_id)
	{
		$this->schedules_model->activate_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule activated successfully');
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Deactivate an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function deactivate_schedule($schedule_id)
	{
		$this->schedules_model->deactivate_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule disabled successfully');
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Default action is to show all the schedules
	*
	*/
	public function schedule_personnel($schedule_id) 
	{
		$this->db->where('schedule_id', $schedule_id);
		$query = $this->db->get('schedule');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$schedule_name = $row->schedule_name;
		}
		
		else
		{
			$schedule_name = 'This schedule does not exist';
		}
		$v_data['personnel'] = $this->personnel_model->retrieve_personnel();
		
		$v_data['schedule_id'] = $schedule_id;
		$data['title'] = $schedule_name;
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('schedules/all_personnel_schedules', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new schedule
	*
	*/
	public function add_personnel_schedule($schedule_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('personnel_id', 'Schedule Name', 'required|xss_clean');
		$this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
		$this->form_validation->set_rules('start_time', 'Start time', 'required|xss_clean');
		$this->form_validation->set_rules('end_time', 'End time', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->schedules_model->add_personnel_schedule($schedule_id))
			{
				$this->session->set_userdata('success_message', 'Schedule added successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add schedule. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('human-resource/schedule-personnel/'.$schedule_id);
	}
    
	/*
	*
	*	Add hours
	*
	*/
	public function fill_timesheet($schedule_id, $personnel_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('date', 'Date', 'required|xss_clean');
		$this->form_validation->set_rules('start_time', 'Start time', 'required|xss_clean');
		$this->form_validation->set_rules('end_time', 'End time', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->schedules_model->fill_timesheet($schedule_id, $personnel_id))
			{
				$this->session->set_userdata('success_message', 'Schedule added successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add schedule. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('doctor/dashboard');
	}
    
	/*
	*
	*	Edit an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function edit_personnel_schedule($schedule_id, $schedule_item_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('schedule_name', 'Schedule Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update schedule
			if($this->schedules_model->edit_personnel_schedule($schedule_id, $schedule_item_id))
			{
				$this->session->set_userdata('success_message', 'Schedule updated successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update schedule. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('human-resource/schedule-personnel/'.$schedule_id);
	}
    
	/*
	*
	*	Delete an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function delete_personnel_schedule($schedule_id)
	{
		$this->schedules_model->delete_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule has been deleted');
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Activate an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function activate_personnel_schedule($schedule_id)
	{
		$this->schedules_model->activate_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule activated successfully');
		redirect('human-resource/schedules');
	}
    
	/*
	*
	*	Deactivate an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function deactivate_personnel_schedule($schedule_id)
	{
		$this->schedules_model->deactivate_schedule($schedule_id);
		$this->session->set_userdata('success_message', 'Schedule disabled successfully');
		redirect('human-resource/schedules');
	}
	
	function get_schedules($schedule_id)
	{
		//get all appointments
		$appointments_result = $this->schedules_model->get_all_schedule_items($schedule_id);
		
		//initialize required variables
		$totals = '';
		$highest_bar = 0;
		$r = 0;
		$data = array();
		
		if($appointments_result->num_rows() > 0)
		{
			$result = $appointments_result->result();
			
			foreach($result as $res)
			{
				//$schedule_date = date('D M d Y',strtotime($res->schedule_date)); 
				/*$time_start = $schedule_date.' '.date('H:i a',strtotime($res->schedule_start_time)).':00 GMT+0300'; 
				$time_end = $schedule_date.' '.date('H:i a',strtotime($res->schedule_end_time)).':00 GMT+0300';*/
				$schedule_date = date('Y-m-d',strtotime($res->schedule_date)); 
				$time_start = $schedule_date.'T'.date('H:i:s',strtotime($res->schedule_start_time)); 
				$time_end = $schedule_date.'T'.date('H:i:s',strtotime($res->schedule_end_time));
				$personnel_fname = $res->personnel_fname;
				$personnel_onames = $res->personnel_onames;
				$schedule_item_id = $res->schedule_item_id;
				$personnel_data = $personnel_fname.' '.$personnel_onames;
				//$color = $this->reception_model->random_color();
				$color = '#0088CC';
				
				$data['title'][$r] = $personnel_data;
				$data['start'][$r] = $time_start;
				$data['end'][$r] = $time_end;
				$data['backgroundColor'][$r] = $color;
				$data['borderColor'][$r] = $color;
				$data['allDay'][$r] = FALSE;
				$data['url'][$r] = site_url().'reception/search_appointment/'.$schedule_item_id;
				$r++;
			}
		}
		
		$data['total_events'] = $r;
		echo json_encode($data);
	}
	
	public function doctors_schedule()
	{
		$data['title'] = 'Doctors\' schedule';
		$v_data['title'] = $data['title'];
		$v_data['schedule_id'] = 3;
		$data['content'] = $this->load->view('schedules/doctors_schedule', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
}
?>