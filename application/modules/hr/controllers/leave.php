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
		//Create calender from listed options
		$prefs = array (
		   'start_day'    => 'sunday',
		   'month_type'   => 'long',
		   'day_type'     => 'short',
		   'show_next_prev' => TRUE,
		   'next_prev_url'   => site_url().'human-resource/leave/'
		 );
		 
		 $prefs['template'] = '
	
		   {table_open}<table class="table table-condensed table-hover table-striped calender-table">{/table_open}
		
		   {heading_row_start}<thead><tr>{/heading_row_start}
		
		   {heading_previous_cell}<th><a href="{previous_url}" class="btn btn-info"><i class="fa fa-arrow-left"></i>
</a></th>{/heading_previous_cell}
		   {heading_title_cell}<th colspan="{colspan}" class="center-align big-text">{heading}</th>{/heading_title_cell}
		   {heading_next_cell}<th><a href="{next_url}" class="btn btn-info pull-right"><i class="fa fa-arrow-right"></i>
</a></th>{/heading_next_cell}
		
		   {heading_row_end}</tr></thead>{/heading_row_end}
		
		   {week_row_start}<thead><tr>{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr></thead>{/week_row_end}
		
		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}
		
		   {cal_cell_content}<a href="{content}"><div class="highlight">{day}</div></a>{/cal_cell_content}
		   {cal_cell_content_today}<div class="highlight-current">{day}</div>{/cal_cell_content_today}
		
		   {cal_cell_no_content}{day}{/cal_cell_no_content}
		   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}
		
		   {cal_cell_blank}&nbsp;{/cal_cell_blank}
		
		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}
		
		   {table_close}</table>{/table_close}
		';
	
		$this->load->library('calendar', $prefs);
		
		/*
			--------------------------------------------------------------------------------------
			Retrieve requiested year & month to view or load default current year & month
			--------------------------------------------------------------------------------------
		*/
		$year = $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : date('Y');
		$month = $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : date('m');
		
		/*
			--------------------------------------------------------------------------------------
			Retrieve assigned leave days for that year & month
			--------------------------------------------------------------------------------------
		*/
  		$table = "leave_duration";
		$where = "start_date LIKE '".$year."-".$month."-%'";
		$items = "DISTINCT(start_date) AS start_";
		$order = "start_";
		
		$result = $this->leave_model->get_assigned_leave($year, $month);
		
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $res)
			{
				$start_date = $res->start_;
				$date = explode('-', $start_date);
				$day = intval($date[2]);
				
				$v_data['data'][$day] = site_url().'human-resource/view-leave/'.$start_date;
			}
		}
		else
		{
			$v_data['data'] = NULL;
		}
		
		/*
			--------------------------------------------------------------------------------------
			Load the interface
			--------------------------------------------------------------------------------------
		*/
		$v_data['year'] = $year;
		$v_data['month'] = $month;
		$v_data['title'] = $data['title'] = 'Leave schedule';
		
		$v_data['personnel'] = $this->personnel_model->retrieve_personnel();
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		
		$data['content'] = $this->load->view('leave/calender', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
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
	
	function add_leave($date)
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
			
			redirect('human-resource/view-leave/'.$date);
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
	public function activate_leave($personnel_leave_id, $date)
	{
		if($this->leave_model->activate_leave_duration($personnel_leave_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been claimed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not claimed');
		}
		redirect('human-resource/view-leave/'.$date);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function deactivate_leave($leave_duration_id, $date)
	{
		if($this->leave_model->deactivate_leave_duration($leave_duration_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been unclaimed');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not unclaimed');
		}
		redirect('human-resource/view-leave/'.$date);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_leave($leave_duration_id, $date)
	{
		if($this->leave_model->delete_leave_duration($leave_duration_id))
		{
			$this->session->set_userdata('success_message', 'Leave has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Leave could not deleted');
		}
		redirect('human-resource/view-leave/'.$date);
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
}
?>