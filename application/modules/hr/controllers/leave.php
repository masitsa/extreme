<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hr/controllers/hr.php";

class Leave extends hr 
{
	function __construct()
	{
		parent:: __construct();
	}
	
	function calender()
	{
		$v_data['title'] = $data['title'] = 'Leave Schedule';
		
		$v_data['personnel'] = $this->personnel_model->retrieve_personnel();
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		
		$data['content'] = $this->load->view('leave/calender', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function leave_schedule()
	{
		$leave_result = $this->leave_model->get_assigned_leave();
		
		//initialize required variables
		$totals = '';
		$highest_bar = 0;
		$r = 0;
		$data = array();
		
		if($leave_result->num_rows() > 0)
		{
			$result = $leave_result->result();
			
			foreach($result as $res)
			{
				$personnel_id = $res->personnel_id;
				$personnel_fname = $res->personnel_fname;
				$personnel_onames = $res->personnel_onames;
				$leave_type_name = $res->leave_type_name;
				$leave_duration_status = $res->leave_duration_status;
				$start_date = $res->start_date;
				$end_date = $res->end_date;
				$start_date = date('D M d Y',strtotime($start_date)); 
				$end_date = date('D M d Y',strtotime($end_date)); 
				$time_start = $start_date.' 8:00 AM:00 GMT+0300'; 
				$time_end = $end_date.' 5:00 PM:00 GMT+0300';
				//$color = $this->reception_model->random_color();
				
				if($leave_duration_status == 1)
				{
					$color = '#0088CC';
				}
				
				else
				{
					$color = '#b71c1c';
				}
				$leave_days = $this->site_model->calculate_leave_days($start_date, $end_date);
				
				$data['title'][$r] = $personnel_fname.' '.$personnel_onames.' - '.$leave_type_name.'. '.$leave_days.' days';
				$data['start'][$r] = $time_start;
				$data['end'][$r] = $time_start;
				$data['backgroundColor'][$r] = $color;
				$data['borderColor'][$r] = $color;
				$data['allDay'][$r] = TRUE;
				$data['url'][$r] = site_url().'human-resource/edit-personnel/'.$personnel_id;
				$r++;
			}
		}
		
		$data['total_events'] = $r;
		echo json_encode($data);
	}
	
	public function view_leave($date)
    {
        $v_data['leave'] = $this->leave_model->get_day_leave($date);
        $v_data['date'] = $date;
		
		$v_data['title'] = $data['title'] = 'Leave starting '.date('jS M Y',strtotime($date));
		
		$v_data['personnel'] = $this->personnel_model->retrieve_personnel();
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		
		$data['content'] = $this->load->view('leave/list', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
    }
	
	function add_leave($date, $personnel_id)
	{
		$this->form_validation->set_rules('personnel_id', 'Personnel', 'trim|numeric|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');

		if ($this->form_validation->run())
		{
			if($this->personnel_model->add_personnel_leave($this->input->post('personnel_id')))
			{
				$this->session->set_userdata("success_message", "Personnel leave  added successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel leave. Please try again");
			}
			
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
			$this->view_leave($date);
		}
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function activate_leave($personnel_leave_id, $personnel_id)
	{
		if($this->leave_model->activate_leave_duration($personnel_leave_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been claimed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not claimed');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function deactivate_leave($leave_duration_id, $personnel_id)
	{
		if($this->leave_model->deactivate_leave_duration($leave_duration_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been unclaimed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not unclaimed');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_leave($leave_duration_id, $personnel_id)
	{
		if($this->leave_model->delete_leave_duration($leave_duration_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
	
	function add_calender_leave()
	{
		$this->form_validation->set_rules('personnel_id', 'Personnel', 'trim|numeric|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');

		if ($this->form_validation->run())
		{
			if($this->personnel_model->add_personnel_leave($this->input->post('personnel_id')))
			{
				$this->session->set_userdata("success_message", "Personnel leave  added successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel leave. Please try again");
			}
			
			redirect('human-resource/leave');
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
			$this->calender();
		}
	}
	
	public function leave_application($leave_type_id)
	{
		$this->form_validation->set_rules('personnel_id', 'Personnel', 'trim|numeric|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');
		
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
		
		if($end_date < $start_date)
		{
			$this->session->set_userdata("error_message","The leave end date cannot be earlier than the start date.");
		}
		
		else if ($this->form_validation->run())
		{
			if($this->personnel_model->add_personnel_leave($this->input->post('personnel_id')))
			{
				$this->session->set_userdata("success_message", "Leave application successfully pending approval.");
			
				redirect('dashboard');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not apply for leave. Please try again");
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		$v_data['title'] = $data['title'] = 'Leave Application';
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		$v_data['personnel_id'] = $this->session->userdata('personnel_id');
		$v_data['selected_leave_type_id'] = $leave_type_id;
		
		$data['content'] = $this->load->view('leave/leave_application', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
}
?>