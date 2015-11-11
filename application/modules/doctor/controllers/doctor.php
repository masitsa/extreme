<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Doctor  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('reception/reception_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('doctor_model');
		$this->load->model('medical_admin/medical_admin_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('administration/reports_model');
		
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('site/site_model');
		$this->load->model('laboratory/lab_model');
		$this->load->model('administration/personnel_model');
		$this->load->model('radiology/xray_model');
		$this->load->model('radiology/ultrasound_model');
		$this->load->model('theatre/theatre_model');
		
		//removed because doctors loose notes
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function index($order = 'category_name', $order_method = 'ASC')
	{
		/*$this->session->unset_userdata('visit_search');
		$this->session->unset_userdata('patient_search');
		
		$where = 'visit.inpatient = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 2 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\' AND visit.personnel_id = \''.$this->session->userdata('personnel_id').'\'';
		
		$table = 'visit_department, visit, patients';
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, 6, 0);
		$v_data['query'] = $query;
		$v_data['page'] = 0;
		
		$v_data['visit'] = 0;
		$v_data['doctor_appointments'] = 1;
		$v_data['department'] = 2;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('nurse/nurse_dashboard', $v_data, TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'doctor_sidebar';
		$this->load->view('admin/templates/general_page', $data);	*/
		
		$where = 'personnel_id = \''.$this->session->userdata('personnel_id').'\'';
		$search = $this->session->userdata('timesheet_search');
		$title = 'All timesheets';
		//echo $search; die();
		
		if(!empty($search))
		{
			$where .= $search;
			$title = $this->session->userdata('timesheet_search_title');
		}
		
		$table = 'schedule_item';
		
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'doctor/dashboard/'.$order.'/'.$order_method;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		$query = $this->doctor_model->get_all_schedule_items($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$v_data['total_due'] = $this->doctor_model->get_total_due($table, $where);
		$v_data['patients_seen'] = $this->doctor_model->get_total_patients_seen($this->session->userdata('personnel_id'), $search);
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['invoices'] =$this->doctor_model->get_all_invoices($table, $where, $config["per_page"], $page, $order, $order_method);
		
		$data['title'] = 'Dashboard';
		$v_data['title'] = $title;
		$v_data['module'] = 1;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('dashboard', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function delete_time($schedule_item_id)
	{
		$this->db->where('schedule_item_id', $schedule_item_id);
		if($this->db->delete('schedule_item'))
		{
			$this->session->set_userdata('success_message', 'Hours deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete hours. Please try again');
		}
		redirect('doctor/dashboard');
	}
	
	public function filter_timesheets()
	{
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where = ' AND (schedule_date >= \''.$date_from.'\' AND schedule_date <= \''.$date_to.'\') ';
			$title = 'Timesheets from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to));
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$where = ' AND schedule_date = \''.$date_to.'\'';
			$title = 'Timesheets for '.date('jS M Y', strtotime($date_to));
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$where = ' AND schedule_date = \''.$date_from.'\'';
			$title = 'Timesheets for '.date('jS M Y', strtotime($date_from));
		}
		
		$this->session->set_userdata('timesheet_search', $where);
		$this->session->set_userdata('timesheet_search_title', $title);
		
		redirect('doctor/dashboard');
	}
	
	public function close_timesheet_search()
	{
		$this->session->unset_userdata('timesheet_search');
		$this->session->unset_userdata('timesheet_search_title');
		
		redirect('doctor/dashboard');
	}
	
	public function create_invoice()
	{
		$personnel_type_id = $this->session->userdata('personnel_type_id');
		$personnel_id = $this->session->userdata('personnel_id');
		$where = 'personnel_id = \''.$personnel_id.'\'';
		$search = $this->session->userdata('timesheet_search');
		$title = 'Invoice for all timesheets';
		//echo $search; die();
		
		if(!empty($search))
		{
			$where .= $search;
			$title = 'Invoice for '.$this->session->userdata('timesheet_search_title');
		}
		
		//add new doctor invoice
		$invoice_number = $this->doctor_model->create_invoice_number();
		$invoice_data = array(
			"doctor_invoice_description" => $title,
			"doctor_invoice_number" => $invoice_number,
			"personnel_id" => $personnel_id,
			"created" => date('Y-m-d H:i:s'),
			"created_by" => $personnel_id,
			"modified_by" => $personnel_id,
			"doctor_invoice_status" => 0,
		);
		
		if($this->db->insert('doctor_invoice', $invoice_data))
		{
			$doctor_invoice_id = $this->db->insert_id();
			
			//create invoice items
			$table = 'schedule_item';
			$this->db->where($where);
			$query = $this->db->get($table);
			
			if($query->num_rows() > 0)
			{
				foreach($query->result() as $row)
				{
					$personnel_id = $row->personnel_id;
					$schedule_item_id = $row->schedule_item_id;
					$schedule_date = $row->schedule_date;
					$schedule_start_time = $row->schedule_start_time;
					$schedule_end_time = $row->schedule_end_time;
					$created_by = $row->created_by;
					$created = $row->created;
					
					//consultant
					if($personnel_type_id == 2)
					{
						$schedule_total = $this->reports_model->get_total_collected($personnel_id, $schedule_date, $schedule_date);
						$units = 1;
						$unit_cost = $schedule_total;
					}
					
					//radiographer
					elseif($personnel_type_id == 3)
					{
						$schedule_total = $this->reports_model->get_total_collected($personnel_id, $schedule_date, $schedule_date);
						$units = 0.3;
						$unit_cost = $schedule_total;
					}
					
					//medical officer
					elseif($personnel_type_id == 4)
					{
						$units = (strtotime($schedule_end_time) - strtotime($schedule_start_time)) / 3600;
						$unit_cost = 500;
					}
					
					//clinic officer
					elseif($personnel_type_id == 5)
					{
						$units = 1;
						$unit_cost = 1000;
					}
					
					$total = $units * $unit_cost;
					$item = date('jS M Y',strtotime($schedule_date)).' '.date('h:i a',strtotime($schedule_start_time)).' '.date('h:i a',strtotime($schedule_end_time));
					
					$item_data = array(
						"doctor_invoice_item_description" => $item,
						"doctor_invoice_item_cost" => $unit_cost,
						"doctor_invoice_item_quantity" => $units,
						"doctor_invoice_id" => $doctor_invoice_id,
					);
					
					if($this->db->insert('doctor_invoice_item', $item_data))
					{
					}
				}
			}
		
			redirect('doctor/print_invoice/'.$doctor_invoice_id);
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to create invoice. Please try again');
			redirect('doctor/dashboard');
		}
	}
	
	public function print_invoice($doctor_invoice_id)
	{
		$data['contacts'] = $this->site_model->get_contacts();
		
		$invoice = $this->doctor_model->get_doctor_invoice($doctor_invoice_id);
		$invoice_items = $this->doctor_model->get_doctor_invoice_items($doctor_invoice_id);
		$data['invoice'] = $invoice;
		$data['invoice_items'] = $invoice_items;
		$this->load->view('invoice', $data);
	}
	
	public function doctor_queue($page_name = NULL)
	{
		$where = 'visit.inpatient = 0 AND visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 2 AND visit_department.accounts = 1 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'AND visit.visit_date = \''.date('Y-m-d').'\' AND visit.personnel_id = \''.$this->session->userdata('personnel_id').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		$patient_visit_search = $this->session->userdata('patient_visit_search');
		
		if(!empty($patient_visit_search))
		{
			$where .= $patient_visit_search;
		}
		
		if($page_name != NULL)
		{
			$segment = 4;
		}
		
		else
		{
			$segment = 3;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'doctor/doctor_queue/'.$page_name;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Doctor Queue';
		$v_data['title'] = 'Doctor Queue';
		$v_data['module'] = 1;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('nurse/nurse_queue', $v_data, true);
		
		if($page_name == NULL)
		{
			$data['sidebar'] = 'doctor_sidebar';
		}
		
		else
		{
			$data['sidebar'] = 'nurse_sidebar';
		}
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function get_doctor_appointments()
	{
		$this->load->model('reports_model');
		//get all appointments
		$appointments_result = $this->reports_model->get_doctor_appointments($this->session->userdata('personnel_id'));
		
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
				$visit_date = date('D M d Y',strtotime($res->visit_date)); 
				$time_start = $visit_date.' '.$res->time_start.':00 GMT+0300'; 
				$time_end = $visit_date.' '.$res->time_end.':00 GMT+0300';
				$visit_type_name = $res->visit_type_name.' Appointment';
				$patient_id = $res->patient_id;
				$dependant_id = $res->dependant_id;
				$visit_type = $res->visit_type;
				$visit_id = $res->visit_id;
				$strath_no = $res->strath_no;
				$patient_data = $this->reception_model->get_patient_details($appointments_result, $visit_type, $dependant_id, $strath_no);
				$color = $this->reception_model->random_color();
				
				$data['title'][$r] = $patient_data;
				$data['start'][$r] = $time_start;
				$data['end'][$r] = $time_start;
				$data['backgroundColor'][$r] = $color;
				$data['borderColor'][$r] = $color;
				$data['allDay'][$r] = FALSE;
				$data['url'][$r] = site_url().'/reception/search_appointment/'.$visit_id;
				$r++;
			}
		}
		
		$data['total_events'] = $r;
		echo json_encode($data);
	}
	
	public function print_checkup($visit_id)
	{
		//$this->doctor_model->print_checkup($visit_id);

		$data = array('visit_id'=>$visit_id);
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['contacts'] = $this->site_model->get_contacts();
		$data['patient'] = $patient;
		$data['title'] = 'Medical Checkup';
		$this->load->view('medical_checkup', $data);
	}
	public function search_visit_patients($module = NULL)
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$patient_number = $this->input->post('patient_number');
		
		if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE '.$patient_number.' ';
		}
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND patients.visit_type_id = '.$visit_type_id.' ';
		}
		
		//search surname
		if(!empty($_POST['surname']))
		{
			$surnames = explode(" ",$_POST['surname']);
			$total = count($surnames);
			
			$count = 1;
			$surname = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
				}
				$count++;
			}
			$surname .= ') ';
		}
		
		else
		{
			$surname = '';
		}
		
		//search other_names
		if(!empty($_POST['othernames']))
		{
			$other_names = explode(" ",$_POST['othernames']);
			$total = count($other_names);
			
			$count = 1;
			$other_name = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $visit_type_id.$patient_number.$surname.$other_name;
		$this->session->set_userdata('patient_visit_search', $search);
		
		$this->doctor_queue();
		
		
	}
	public function close_queue_search()
	{
		$this->session->unset_userdata('patient_visit_search');
		$this->doctor_queue();
	}
	
	public function patient_card($visit_id)
	{
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['patient_type'] = $patient['patient_type'];
		$v_data['patient_othernames'] = $patient['patient_othernames'];
		$v_data['patient_surname'] = $patient['patient_surname'];
		$v_data['patient_type_id'] = $patient['visit_type_id'];
		$v_data['account_balance'] = $patient['account_balance'];
		$v_data['visit_type_name'] = $patient['visit_type_name'];
		$v_data['patient_id'] = $patient['patient_id'];
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$visit_date = $this->reception_model->get_visit_date($visit_id);
		$gender = $patient['gender'];
		$visit_date = date('jS M Y',strtotime($visit_date));
		$v_data['age'] = $age;
		$v_data['visit_date'] = $visit_date;
		$v_data['gender'] = $gender;
		
		$v_data['visit_id'] = $visit_id;
		$v_data['dental'] = 0;
		$data['content'] = $this->load->view('patient_card', $v_data, true);
		
		$data['title'] = 'Patient Card';
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function print_prescription($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$data['contacts'] = $this->site_model->get_contacts();
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['patient'] = $patient;
		$this->load->view('print_prescription', $data);
	}
}
?>