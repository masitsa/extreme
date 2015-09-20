<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// require_once "./application/modules/auth/controllers/auth.php";
class Reception  extends MX_Controller
{	
	var $csv_path;
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception_model');
		$this->load->model('strathmore_population');
		$this->load->model('auth/auth_model');
		$this->load->model('database');
		$this->load->model('administration/reports_model');
		$this->load->model('administration/administration_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		$this->load->model('administration/sync_model');
		
		$this->csv_path = realpath(APPPATH . '../assets/csv');
	}
	
	public function index()
	{
		$this->session->unset_userdata('visit_search');
		$this->session->unset_userdata('patient_search');
		
		$where = 'visit.visit_delete = 0 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit, patients, visit_type';
		$query = $this->reception_model->get_all_ongoing_visits2($table, $where, 10, 0);
		$v_data['query'] = $query;
		$v_data['page'] = 0;
		
		$v_data['visit'] = 0;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('reception_dashboard', $v_data, TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	/*
	* Code for displaying deleted patients removed
	*/
	public function patients()
	{
		$delete = 0;
		$segment = 3;
		
		$patient_search = $this->session->userdata('patient_search');
		//$where = '(visit_type_id <> 2 OR visit_type_id <> 1) AND patient_delete = '.$delete;
		$where = 'patient_delete = '.$delete;
		if(!empty($patient_search))
		{
			$where .= $patient_search;
		}
		
		else
		{
			$where .= ' AND patients.branch_code = \''.$this->session->userdata('branch_code').'\'';
		}
		
		$table = 'patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/patients';
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
		$query = $this->reception_model->get_all_patients($table, $where, $config["per_page"], $page);
		
		if($delete == 1)
		{
			$data['title'] = 'Deleted Patients';
			$v_data['title'] = 'Deleted Patients';
		}
		
		else
		{
			$search_title = $this->session->userdata('patient_search_title');
			
			if(!empty($search_title))
			{
				$data['title'] = $v_data['title'] = 'Patients filtered by :'.$search_title;
			}
			
			else
			{
				$data['title'] = 'Patients';
				$v_data['title'] = 'Patients';
			}
		}
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['delete'] = $delete;
		$v_data['branches'] = $this->reception_model->get_branches();
		$data['content'] = $this->load->view('all_patients', $v_data, true);
		
		$data['sidebar'] = 'reception_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*
	*
	*	$visits = 0 :ongoing visits of the current day
	*	$visits = 1 :terminated visits
	*	$visits = 2 :deleted visits
	*	$visits = 3 :all other ongoing visits
	*
	*/
	public function visit_list($visits, $page_name = 'none')
	{
		//Deleted visits
		if($visits == 2)
		{
			$delete = 1;
		}
		//undeleted visits
		else
		{
			$delete = 0;
		}
		
		if(empty($page_name))
		{
			$segment = 4;
		}
		
		else
		{
			$segment = 5;
		}
		
		// this is it
		if($visits != 2)
		{
			$where = 'visit.visit_delete = '.$delete.' AND visit.patient_id = patients.patient_id AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
			
			//terminated visits
			if($visits == 1)
			{
				if($page_name == 'nurse' || $page_name == 'doctor')
				{
					$where .= ' ';
				}
				else
				{
					$where .= ' AND visit.close_card = '.$visits;	
				}
				
			}
			
			//ongoing visits
			else
			{
				if($page_name == 'nurse' || $page_name == 'doctor')
				{
					$where .= ' ';
				}
				else
				{
					$where .= ' AND visit.close_card = 0';	
				}
				
				//visits of the current day
				if($visits == 0)
				{
					$where .= ' AND visit.visit_date = \''.date('Y-m-d').'\'';
				}
				
				else
				{
					$where .= ' AND visit.visit_date < \''.date('Y-m-d').'\'';
				}
			}
		}
		
		else
		{
			$where = 'visit.visit_delete = '.$delete.' AND visit.patient_id = patients.patient_id AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		}
		$table = 'visit, patients, visit_type';
		
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/visit_list/'.$visits.'/'.$page_name;
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
		$query = $this->reception_model->get_all_ongoing_visits2($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		if($visits == 0)
		{
			$data['title'] = 'General Queue';
			$v_data['title'] = 'General Queue';
		}
		
		elseif($visits == 2)
		{
			$data['title'] = 'Deleted Visits';
			$v_data['title'] = 'Deleted Visits';
		}
		
		elseif($visits == 3)
		{
			$data['title'] = 'Unclosed Visits';
			$v_data['title'] = 'Unclosed Visits';
		}
		
		else
		{
			$data['title'] = 'Visit History';
			$v_data['title'] = 'Visit History';
		}
		$v_data['visit'] = $visits;
		$v_data['page_name'] = $page_name;
		$v_data['delete'] = $delete;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('ongoing_visit', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	/*
	*
	*	$visits = 0 :ongoing visits of the current day
	*	$visits = 1 :terminated visits
	*	$visits = 2 :deleted visits
	*	$visits = 3 :all other ongoing visits
	*
	*/
	public function general_queue($page_name)
	{
		$segment = 4;
		
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit.visit_date = \''.date('Y-m-d').'\' AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		
		$visit_search = $this->session->userdata('general_queue_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/general_queue/'.$page_name;
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
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		if($page_name == 'administration')
		{
			$data['title'] = 'All visits';
			$v_data['title'] = 'All visits';
		}
		else
		{
			$data['title'] = 'General Queue';
			$v_data['title'] = 'General Queue';
		}
		
		$v_data['page_name'] = $page_name;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('general_queue', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	/*
	*	Add a new patient
	*
	*/
	public function add_patient($dependant_staff = NULL)
	{
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['insurance'] = $this->reception_model->get_insurance();
		$v_data['dependant_staff'] = $dependant_staff;
		$data['content'] = $this->load->view('add_patient', $v_data, true);
		
		$data['title'] = 'Add Patients';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	/*
	*	Add a new patient
	*
	*/
	public function add_other_dependant($dependant_parent)
	{
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['dependant_parent'] = $dependant_parent;
		$patient = $this->reception_model->patient_names2($dependant_parent);
		$v_data['patient_othernames'] = $patient['patient_othernames'];
		$v_data['patient_surname'] = $patient['patient_surname'];
		$data['content'] = $this->load->view('add_other_dependant', $v_data, true);
		
		$data['title'] = 'Add Patients';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	/*
	*	Register other patient
	*
	*/
	public function register_other_patient()
	{
		//form validation rules
		$this->form_validation->set_rules('title_id', 'Title', 'is_numeric|xss_clean');
		$this->form_validation->set_rules('patient_surname', 'Surname', 'required|xss_clean');
		$this->form_validation->set_rules('patient_othernames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('patient_dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'trim|xss_clean');
		$this->form_validation->set_rules('religion_id', 'Religion', 'trim|xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_email', 'Email Address', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_address', 'Postal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_postalcode', 'Postal Code', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_town', 'Town', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_phone1', 'Primary Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_phone2', 'Other Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_kin_sname', 'Next of Kin Surname', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_kin_othernames', 'Next of Kin Other Names', 'trim|xss_clean');
		$this->form_validation->set_rules('relationship_id', 'Relationship With Kin', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_national_id', 'National ID', 'trim|xss_clean');
		$this->form_validation->set_rules('next_of_kin_contact', 'Next of Kin Contact', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->add_patient();
		}
		
		else
		{
			$patient_id = $this->reception_model->save_other_patient();
			//echo $patient_id; die();
			if($patient_id != FALSE)
			{
				$this->get_found_patients($patient_id,3);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add patient. Please try again");
				$this->add_patient();	
			}
		}
	}
	
	public function get_found_patients($patient_id,$place_id)
	{
		//  1 for students 2 for staff 3 for others 4 for dependants
		$this->session->set_userdata('patient_search', ' AND patients.patient_id = '.$patient_id);
	
		redirect('reception/patients-list');
		
		
	}
	
	public function get_department_services($department_id)
	{
		echo '<option value="0">--Select Service--</option>';
		
		$service_charge = $this->reception_model->get_services_per_department($department_id);
		foreach($service_charge AS $key) 
		{ 
			echo '<option value="'.$key->service_id.'">'.$key->service_name.'</option>';
		}
	}
	
	public function get_services_charges($patient_type_id, $service_id)
	{
		echo '<option value="0">--Select Consultation Charge--</option>';
		
		$service_charge = $this->reception_model->get_service_charges_per_type($patient_type_id, $service_id);
		foreach($service_charge AS $key) 
		{ 
			echo '<option value="'.$key->service_charge_id.'">'.$key->service_charge_name.'</option>';
		}
	}
	
	/*
	*	Add a visit
	*
	*/
	public function set_visit($primary_key)
	{
		$v_data["patient_id"] = $primary_key;
		$v_data['visit_departments'] = $this->reception_model->get_visit_departments();
		$v_data['visit_types'] = $this->reception_model->get_visit_types();
		$v_data['charge'] = $this->reception_model->get_service_charges($primary_key);
		$v_data['doctor'] = $this->reception_model->get_doctor();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['patient_insurance'] = $this->reception_model->get_patient_insurance($primary_key);
		$patient = $this->reception_model->patient_names2($primary_key);
		$v_data['patient_type'] = $patient['patient_type'];
		$v_data['patient_othernames'] = $patient['patient_othernames'];
		$v_data['patient_surname'] = $patient['patient_surname'];
		$v_data['patient_type_id'] = $patient['visit_type_id'];
		$v_data['account_balance'] = $patient['account_balance'];
		
		$data['content'] = $this->load->view('visit/initiate_visit', $v_data, true);
		
		$data['title'] = 'Create Visit';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function save_visit($patient_id)
	{
		$this->form_validation->set_rules('visit_date', 'Visit Date', 'required');
		$this->form_validation->set_rules('department_id', 'Department', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('visit_type_id', 'Visit type', 'required|is_natural_no_zero');
		$visit_type_id = $this->input->post("visit_type_id"); 
		
		if(isset($_POST['department_id'])){
			if($_POST['department_id'] == 7)
			{
				//if nurse visit doctor must be selected
				$this->form_validation->set_rules('personnel_id', 'Doctor', 'required|is_natural_no_zero');
				$this->form_validation->set_rules('service_charge_name', 'Consultation Type', 'required|is_natural_no_zero');
				$service_charge_id = $this->input->post("service_charge_name");
				$doctor_id = $this->input->post('personnel_id');
			}
			else if($_POST['department_id'] == 12)
			{
				//if nurse visit doctor must be selected
				$this->form_validation->set_rules('personnel_id2', 'Doctor', 'required|is_natural_no_zero');
				$this->form_validation->set_rules('service_charge_name2', 'Consultation Type', 'required|is_natural_no_zero');
				$service_charge_id = $this->input->post("service_charge_name2");
				$doctor_id = $this->input->post('personnel_id2');
			}
			else 
			{
				$service_charge_id = 0;
				$doctor_id = 0;
			}
		}
		
		if($visit_type_id != 1){
			$this->form_validation->set_rules('insurance_limit', 'Insurance limit', 'required');
			$this->form_validation->set_rules('insurance_number', 'Insurance number', 'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->set_visit($patient_id);
		}
		else
		{
			$insurance_limit = $this->input->post("insurance_limit");
			$insurance_number = $this->input->post("insurance_number");
			
			//$visit_type = $this->get_visit_type($type_name);
			$visit_date = $this->input->post("visit_date");
			$timepicker_start = $this->input->post("timepicker_start");
			$timepicker_end = $this->input->post("timepicker_end");
			
			$appointment_id = $this->input->post("appointment_id");
			if($appointment_id == 1)
			{
				$close_card = 2;
			}
			else
			{		
				$close_card = 0;
			}
			//  check if the student exisit for that day and the close card 0;
			$check_visits = $this->reception_model->check_patient_exist($patient_id, $visit_date);
			$check_count = count($check_visits);
			if($check_count > 0)
			{
				$this->session->set_userdata('error_message', 'Seems like there is another visit has been initiated');
				redirect('reception/general-queue');
			}
			
			else
			{
				//create visit
				$visit_id = $this->reception_model->create_visit($visit_date, $patient_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $timepicker_start, $timepicker_end, $appointment_id, $close_card);
				
				//save consultation charge for nurse visit and counseling
				if($_POST['department_id'] == 7 || $_POST['department_id'] == 12)
				{
					$this->reception_model->save_visit_consultation_charge($visit_id, $service_charge_id);	
				}
				
				//set visit department if not appointment
				if($appointment_id == 0)
				{
					//update patient last visit
					$this->reception_model->set_last_visit_date($patient_id, $visit_date);
					
					$department_id = $this->input->post('department_id');
					if($this->reception_model->set_visit_department($visit_id, $department_id, $visit_type_id))
					{
						if($appointment_id == 0)
						{
							$this->session->set_userdata('success_message', 'Visit has been initiated');
						}
						else
						{
							$this->session->set_userdata('success_message', 'Appointment has been created');
						}
					}
					else
					{
						$this->session->set_userdata('error_message', 'Internal error. Could not add the visit');
					}
				}
				
				else
				{
					$this->session->set_userdata('success_message', 'Visit has been initiated');
				}
				
				redirect('reception/general-queue');
			}
			
		}
	}
	
	public function update_patient_number()
	{
		if($this->strathmore_population->update_patient_numbers())
		{
			echo 'SUCCESS :-)';
		}
		
		else
		{
			echo 'Failure';
		}
	}
	
	public function search_patients()
	{
		$patient_national_id = $this->input->post('patient_national_id');
		$patient_number = $this->input->post('patient_number');
		$branch_code = $this->input->post('branch_code');
		$search_title = '';
		
		if(!empty($patient_number))
		{
			$search_title .= ' patient number <strong>'.$patient_number.'</strong>';
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\'';
		}
		
		if(!empty($patient_national_id))
		{
			$search_title .= ' I.D. number <strong>'.$patient_national_id.'</strong>';
			$patient_national_id = ' AND patients.patient_national_id = \''.$patient_national_id.'\' ';
		}
		
		if(!empty($branch_code))
		{
			$search_title .= ' branch code <strong>'.$branch_code.'</strong>';
			$branch_code = ' AND patients.branch_code = \''.$branch_code.'\' ';
		}
		
		//search surname
		if(!empty($_POST['surname']))
		{
			$search_title .= ' first name <strong>'.$_POST['surname'].'</strong>';
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
			$search_title .= ' other names <strong>'.$_POST['othernames'].'</strong>';
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
		
		$search = $patient_national_id.$patient_number.$surname.$other_name.$branch_code;
		$this->session->set_userdata('patient_search', $search);
		$this->session->set_userdata('patient_search_title', $search_title);
		
		$this->patients();
	}
	
	public function search_visits($visits, $page_name = NULL)
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date = $this->input->post('visit_date');
		$patient_number = $this->input->post('patient_number');
		$patient_national_id = $this->input->post('patient_national_id');
		
		if(!empty($patient_national_id))
		{
			$patient_national_id = ' AND patients.patient_national_id = \''.$patient_national_id.'\' ';
		}
		
		if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\'';
		}
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
		}
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
		}
		
		if(!empty($visit_date))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date.'\' ';
		}
		
		//search surname
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
		
		//search other_names
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
		
		$search = $visit_type_id.$patient_number.$surname.$other_name.$visit_date.$patient_national_id.$personnel_id;
		$this->session->set_userdata('visit_search', $search);
		
		if($visits == 13)
		{
			$this->appointment_list();
		}
		
		else
		{
			$this->visit_list($visits, $page_name);
		}
	}
	
	function doc_schedule($personnel_id,$date)
	{
		$data = array('personnel_id'=>$personnel_id,'date'=>$date);
		$this->load->view('show_schedule',$data);	
	}
	function load_charges($patient_type){
		
		$v_data['service_charge'] = $this->reception_model->get_service_charges_per_type($patient_type);
		
		$this->load->view('service_charges_pertype',$v_data);	
		
	}
	public function save_initiate_lab($primary_key)
	{
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('insurance_id', 'Insurance Company', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_insurance_id', 'Patient Insurance Number', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->initiate_lab($primary_key);
		}
		
		else
		{
			$visit_type_id = $this->input->post("patient_type");
			$patient_insurance_number = $this->input->post("insurance_id");
			$patient_insurance_id = $this->input->post("patient_insurance_id");
			$insert = array(
				"close_card" => 0,
				"patient_id" => $primary_key,
				"visit_type" => $visit_type_id,
				"patient_insurance_id" => $patient_insurance_id,
				"patient_insurance_number" => $patient_insurance_number,
				"visit_date" => date("y-m-d"),
				"nurse_visit"=>1,
				"lab_visit" => 12
			);
			$this->database->insert_entry('visit', $insert);
	
			$this->visit_list(0);
		}
	}
	
	public function save_initiate_pharmacy($patient_id)
	{
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('insurance_id', 'Insurance Company', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_insurance_id', 'Patient Insurance Number', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->initiate_pharmacy($primary_key);
		}
		
		else
		{
			$visit_type_id = $this->input->post("patient_type");
			$patient_insurance_number = $this->input->post("insurance_id");
			$patient_insurance_id = $this->input->post("patient_insurance_id");
				$insert = array(
					"close_card" => 0,
					"patient_id" => $patient_id,
					"visit_type" => $visit_type_id,
					"patient_insurance_id" => $patient_insurance_id,
					"patient_insurance_number" => $patient_insurance_number,
					"visit_date" => date("y-m-d"),
					"visit_time" => date("Y-m-d H:i:s"),
					"nurse_visit" => 1,
					"pharmarcy" => 6
				);
			$table = "visit";
			$this->database->insert_entry($table, $insert);
	
			$this->visit_list(0);
		}
	}
	
	public function close_visit_search($visit, $page_name = NULL)
	{
		$this->session->unset_userdata('visit_search');
		
		if($visit == 13)
		{
			redirect('reception/appointments-list');
		}
		
		else
		{
			$this->visit_list($visit, $page_name);
		}
	}
	
	public function close_patient_search($page = NULL)
	{
		if($page == NULL)
		{
			$this->session->unset_userdata('patient_search');
			$this->session->unset_userdata('patient_staff_search');
			$this->session->unset_userdata('patient_dependants_search');
			$this->session->unset_userdata('patient_student_search');
			redirect('reception/patients');
		} else if($page == 2)
		{
			$this->session->unset_userdata('patient_staff_search');
			redirect('reception/staff');
		}
		else if($page == 3)
		{
			$this->session->unset_userdata('patient_student_search');
			redirect('reception/students');
		}
		else if($page == 4)
		{
			$this->session->unset_userdata('patient_dependants_search');
			redirect('reception/staff_dependants');
		}
		else
		{
			$this->session->unset_userdata('patient_dependants_search');
			redirect('reception/staff_dependants');
		}
	}
	
	
	public function dependants($patient_id)
	{
		$v_data['dependants_query'] = $this->reception_model->get_all_patient_dependant($patient_id);
		$v_data['patient_query'] = $this->reception_model->get_patient_data($patient_id);
		$v_data['patient_id'] = $patient_id;
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$data['content'] = $this->load->view('dependants', $v_data, true);
		
		$data['title'] = 'Dependants';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function register_dependant($patient_id, $visit_type_id, $staff_no)
	{
		//form validation rules
		$this->form_validation->set_rules('title_id', 'Title', 'is_numeric|xss_clean');
		$this->form_validation->set_rules('patient_surname', 'Surname', 'required|xss_clean');
		$this->form_validation->set_rules('patient_othernames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('patient_dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'trim|xss_clean');
		$this->form_validation->set_rules('religion_id', 'Religion', 'trim|xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->dependants($patient_id);
		}
		
		else
		{
			//add staff dependant
			if($visit_type_id == 2)
			{
				$patient_id = $this->reception_model->save_dependant_patient($staff_no);
			}
			
			else
			{
				$patient_id = $this->reception_model->save_other_dependant_patient($patient_id);
			}
			
			if($patient_id != FALSE)
			{
				//initiate visit for the patient
				$this->session->set_userdata('success_message', 'Patient added successfully');
				$this->get_found_patients($patient_id,3);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not create patient. Please try again');
				$this->dependants($patient_id);
			}
		}
	}
	
	public function end_visit($visit_id, $page = NULL)
	{
		$data = array(
        	"close_card" => 1,
        	"visit_time_out" => date('Y-m-d H:i:s')
    	);
		$table = "visit";
		$key = $visit_id;
		$this->database->update_entry($table, $data, $key);
		
		//sync data
		$response = $this->sync_model->syn_up_on_closing_visit($visit_id);
		
		if($response)
		{
			if($page == 0)
			{
				redirect('reception/visit_list/0');
			}
			
			if($page == 1)
			{
				redirect('accounts/accounts_queue');
			}
			
			if($page == 2)
			{
				redirect('accounts/accounts_unclosed_queue');
			}
			
			if($page == 3)
			{
				redirect('accounts/accounts_closed_queue');
			}
			
			else
			{
				redirect('reception/visit_list/'.$page);
			}
		}
		
		else
		{
			if($page == 0)
			{
				redirect('reception/visit_list/0');
			}
			
			if($page == 1)
			{
				redirect('accounts/accounts_queue');
			}
			
			if($page == 2)
			{
				redirect('accounts/accounts_unclosed_queue');
			}
			
			if($page == 3)
			{
				redirect('accounts/accounts_closed_queue');
			}
			
			else
			{
				redirect('reception/visit_list/'.$page);
			}
		}
	}
	public function appointment_list()
	{
		$where = 'visit.visit_delete = 0 AND visit.patient_id = patients.patient_id AND visit.close_card = 2 AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		
		$table = 'visit, patients, visit_type';
		$appointment_search = $this->session->userdata('visit_search');
		
		if(!empty($appointment_search))
		{
			$where .= $appointment_search;
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/appointment_list';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 3;
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
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reception_model->get_all_ongoing_visits2($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Appointment List';
		$v_data['title'] = 'Appointment List';
		$v_data['visit'] = 13;
		$v_data['page_name'] = 'none';
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('appointment_list', $v_data, true);
		$data['sidebar'] = 'reception_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function initiate_visit_appointment($visit_id)
	{
		$data = array(
        	"close_card" => 0
    	);
		$table = "visit";
		$key = $visit_id;
		
		$this->database->update_entry($table, $data, $key);
		$this->reception_model->set_visit_department($visit_id, 7);
		
		$this->session->set_userdata('success_message', 'The patient has been added to the queue');
		redirect('reception/general-queue');
	}
	
	function get_appointments()
	{	
		$this->load->model('reports_model');
		//get all appointments
		$appointments_result = $this->reports_model->get_all_appointments();
		
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
				$patient_othernames = $res->patient_othernames;
				$patient_surname = $res->patient_surname;
				$personnel_fname = $res->personnel_fname;
				$personnel_onames = $res->personnel_onames;
				$visit_id = $res->visit_id;
				$strath_no = $res->strath_no;
				$patient_data = $patient_surname.' '.$patient_othernames.' to see Dr. '.$personnel_onames;
				//$color = $this->reception_model->random_color();
				$color = '#0088CC';
				
				$data['title'][$r] = $patient_data;
				$data['start'][$r] = $time_start;
				$data['end'][$r] = $time_start;
				$data['backgroundColor'][$r] = $color;
				$data['borderColor'][$r] = $color;
				$data['allDay'][$r] = FALSE;
				$data['url'][$r] = site_url().'reception/search_appointment/'.$visit_id;
				$r++;
			}
		}
		
		$data['total_events'] = $r;
		echo json_encode($data);
	}
	
	function search_appointment($visit_id)
	{
		if($visit_id > 0)
		{
			$search = ' AND visit.visit_id = '.$visit_id;
			$this->session->set_userdata('visit_search', $search);
		}
		
		redirect('reception/appointment_list');
	}
	
	public function delete_patient($patient_id, $page)
	{
		if($this->reception_model->delete_patient($patient_id))
		{
			$this->session->set_userdata('success_message', 'The patient has been deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Could not delete patient. Please <a href="'.site_url().'reception/delete_patient/'.$patient_id.'">try again</a>');
		}
		
		if($page == 1)
		{
			redirect('reception/patients');
		}
		
		if($page == 2)
		{
			redirect('reception/staff');
		}
		
		if($page == 3)
		{
			redirect('reception/staff_dependants');
		}
		
		if($page == 4)
		{
			redirect('reception/students');
		}
	}
	
	public function delete_visit($visit_id, $visit)
	{
		if($this->reception_model->delete_visit($visit_id))
		{
			$this->session->set_userdata('success_message', 'The visit has been deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Could not delete visit. Please <a href="'.site_url().'reception/delete_patient/'.$patient_id.'">try again</a>');
		}
		
		redirect('reception/visit_list/'.$visit);
	}
	
	public function change_patient_type($patient_id)
	{
		//form validation rules
		$this->form_validation->set_rules('visit_type_id', 'Visit Type', 'required|is_numeric|xss_clean');
		$this->form_validation->set_rules('strath_no', 'Staff/Student ID No.', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reception_model->change_patient_type($patient_id))
			{
				$this->session->set_userdata('success_message', 'Patient type updated successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to update patient type. Please try again');
			}
			
			if($this->input->post('visit_type_id') == 1)
			{
				// this is a student
				redirect('reception/students');
			}
			else
			{
				redirect('reception/staff');
			}
			
		}
		
		$v_data['patient'] = $this->reception_model->patient_names2($patient_id);
		$data['content'] = $this->load->view('change_patient_type', $v_data, true);
		$data['sidebar'] = 'reception_sidebar';
		$data['title'] = 'Change Patient Type';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function change_patient_to_others($patient_id,$visit_type_idd)
	{
		
		if($this->reception_model->change_patient_type_to_others($patient_id,$visit_type_idd))
		{
			$this->session->set_userdata('success_message', 'Patient type updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update patient type. Please try again');
		}
		
		redirect('reception/patients-list');
		
		
	}
	
	public function staff_sbs()
	{
		//form validation rules
		$this->form_validation->set_rules('strath_no', 'Staff Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('surname', 'Surname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('other_names', 'Other Names', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|xss_clean');
		$this->form_validation->set_rules('gender', 'Gender', 'trime|required|xss_clean');
		$this->form_validation->set_rules('staff_type', 'Staff Type', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->add_patient();
		}
		
		else
		{
			$patient_id = $this->reception_model->add_sbs_patient();
			if($patient_id != FALSE)
			{
				$this->session->set_userdata('success_message', 'Patient added successfully');
				$this->get_found_patients($patient_id,2);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to update patient type. Please try again');
				$this->add_patient();
			}
		}
	}
	
	public function bulk_add_sbs_staff()
	{
		if($this->reception_model->bulk_add_sbs_staff())
		{
			echo 'SUCCESS';
		}
		
		else
		{
			echo 'FAILURE';
		}
	}
	
	public function bulk_add_all_staff()
	{
		if($this->strathmore_population->get_hr_staff())
		{
			echo 'SUCCESS';
		}
		
		else
		{
			echo 'FAILURE';
		}
	}
	
	public function staff_dependants()
	{
		$segment = 3;
		
		$dependant_search = $this->session->userdata('patient_dependants_search');
		$where = 'patients.visit_type_id = 2 AND patients.dependant_id IS NOT NULL AND patients.dependant_id = staff.Staff_Number  AND patients.patient_delete = 0';
		
		if(!empty($dependant_search))
		{
			$where .= $dependant_search;
		}
		
		$table = 'patients,staff';
		$items = 'patients.*,staff.*';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/staff_dependants';
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
		$query = $this->reception_model->get_all_patients($table, $where, $config["per_page"], $page, $items);
		
		$data['title'] = 'Staff Dependants';
		$v_data['title'] = 'Staff Dependants';
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['type'] = $this->reception_model->get_types();
		$data['content'] = $this->load->view('patients/staff_dependants', $v_data, true);
		
		$data['sidebar'] = 'reception_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function students()
	{
		$segment = 3;
		
		$patient_search = $this->session->userdata('patient_student_search');
		
		if(!empty($patient_search))
		{
			$where = 'patients.visit_type_id = 1  AND patients.strath_no = student.student_Number AND patients.patient_delete = 0';
			$where .= $patient_search;
			$table = 'patients, student';
			$items = 'student.*, patients.*';
		}
		
		else
		{
			$where = 'patients.visit_type_id = 1 AND patients.patient_delete = 0';
			$table = 'patients';
			$items = 'patients.*';
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/students';
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
		$query = $this->reception_model->get_all_patients($table, $where, $config["per_page"], $page, $items);
		
		$data['title'] = 'Students ';
		$v_data['title'] = 'Students ';
		
		$v_data['query'] = $query;
		$v_data['patient_search'] = $patient_search;
		$v_data['page'] = $page;
		$v_data['type'] = $this->reception_model->get_types();
		$data['content'] = $this->load->view('patients/students', $v_data, true);
		
		$data['sidebar'] = 'reception_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function staff()
	{
		$segment = 3;
		
		$patient_search = $this->session->userdata('patient_staff_search');
		$where = 'patients.visit_type_id = 2  AND patients.strath_no = staff.Staff_Number AND patients.patient_delete = 0 AND patients.dependant_id = 0';
		
		if(!empty($patient_search))
		{
			$where .= $patient_search;
		}
		
		$table = 'patients, staff';
		$items = '*';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/staff';
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
		$query = $this->reception_model->get_all_patients($table, $where, $config["per_page"], $page, $items);
		
		$data['title'] = 'Staff ';
		$v_data['title'] = 'Staff ';
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['type'] = $this->reception_model->get_types();
		$data['content'] = $this->load->view('patients/staff', $v_data, true);
		
		$data['sidebar'] = 'reception_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function search_staff_dependant_patients()
	{
		$staff_no = $this->input->post('staff_no');
		$registration_date = $this->input->post('registration_date');
		
		if(!empty($staff_no))
		{
			$staff_no = ' AND patients.dependant_id LIKE \''.$staff_no.'\' ';
		}
		else
		{
			$staff_no = '';
		}
		
		if(!empty($registration_date))
		{
			$registration_date = ' AND patients.patient_date LIKE \''.$registration_date.'\' ';
		}
		else
		{
			$registration_date ='';
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
					$surname .= ' staff.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' staff.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
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
					$other_name .= ' staff.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' staff.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $staff_no.$surname.$other_name.$registration_date;
		$this->session->set_userdata('patient_dependants_search', $search);
		
		$this->staff_dependants();
	}
	public function search_staff_patients()
	{
		$strath_no = $this->input->post('strath_no');
		$registration_date = $this->input->post('registration_date');
		
		if(!empty($strath_no))
		{
			$strath_no = " AND patients.strath_no = '".$strath_no."' ";
		}
		
		if(!empty($registration_date))
		{
			$registration_date = ' AND patients.patient_date LIKE \'%'.$registration_date.'%\'';
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
					$surname .= ' staff.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' staff.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
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
					$other_name .= ' staff.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' staff.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $strath_no.$surname.$other_name.$registration_date;
		$this->session->set_userdata('patient_staff_search', $search);
			//echo $this->session->userdata('patient_dependants_search');
		
		$this->staff();
	}	
	public function search_student_patients()
	{
		$strath_no = $this->input->post('strath_no');
		$registration_date = $this->input->post('registration_date');
		
		if(!empty($strath_no))
		{
			$strath_no = ' AND student.student_Number LIKE \'%'.$strath_no.'%\'';
		}
		
		if(!empty($registration_date))
		{
			$registration_date = ' AND patients.patient_date LIKE \''.$registration_date.' \'';
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
					$surname .= ' student.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' student.Surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
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
					$other_name .= ' student.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' student.Other_names LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $strath_no.$surname.$other_name.$registration_date;
		$this->session->set_userdata('patient_student_search', $search);
		
		$this->students();
	}	
	/*
	*	Register dependant patient
	*
	*/
	public function add_staff_dependant()
	{
		//form validation rules
		$this->form_validation->set_rules('title_id', 'Title', 'is_numeric|xss_clean');
		$this->form_validation->set_rules('staff_number', 'Staff Number', 'required|xss_clean');
		$this->form_validation->set_rules('patient_surname', 'Surname', 'required|xss_clean');
		$this->form_validation->set_rules('patient_othernames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('patient_dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'trim|xss_clean');
		$this->form_validation->set_rules('religion_id', 'Religion', 'trim|xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$patient_id = $this->reception_model->save_dependant_patient($this->input->post('staff_number'));
			
			if($patient_id != FALSE)
			{
				$this->session->set_userdata('patient_dependants_search', ' AND patients.patient_id = '.$patient_id);
				
				redirect('reception/staff_dependants');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add staff depndant. Please try again');
			}
		}
		
		else
		{
			$v_data['staff_number'] = 0;
			$v_data['relationships'] = $this->reception_model->get_relationship();
			$v_data['religions'] = $this->reception_model->get_religion();
			$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
			$v_data['titles'] = $this->reception_model->get_title();
			$v_data['genders'] = $this->reception_model->get_gender();
			$data['content'] = $this->load->view('patients/dependants', $v_data, true);
			
			$data['title'] = 'Add Staff Dependant';
			$data['sidebar'] = 'reception_sidebar';
			
			$this->load->view('admin/templates/general_page', $data);
		}
	}
	
	function sort_staff_dependants()
	{
		$query = $this->db->get('staff_dependants');
		
		foreach($query->result() as $res)
		{
			$staff_dependant_id = $res->staff_dependants_id;
			$names = $res->names;
			
			if(!empty($names))
			{
				$data['surname'] = $names;
				
				$this->db->where('staff_dependants_id', $staff_dependant_id);
				$this->db->update('staff_dependants', $data);
			}
		}
	}
	
	/*
	*	Edit other patient
	*
	*/
	public function edit_patient($patient_id)
	{
		//form validation rules
		$this->form_validation->set_rules('title_id', 'Title', 'is_numeric|xss_clean');
		$this->form_validation->set_rules('patient_surname', 'Surname', 'required|xss_clean');
		$this->form_validation->set_rules('patient_othernames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('patient_dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'xss_clean');
		$this->form_validation->set_rules('religion_id', 'Religion', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('patient_email', 'Email Address', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_address', 'Postal Address', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_postalcode', 'Postal Code', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_town', 'Town', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_phone1', 'Primary Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_phone2', 'Other Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_kin_sname', 'Next of Kin Surname', 'trim|xss_clean');
		$this->form_validation->set_rules('patient_kin_othernames', 'Next of Kin Other Names', 'trim|xss_clean');
		$this->form_validation->set_rules('relationship_id', 'Relationship With Kin', 'xss_clean');
		$this->form_validation->set_rules('patient_national_id', 'National ID', 'trim|xss_clean');
		$this->form_validation->set_rules('next_of_kin_contact', 'Next of Kin Contact', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reception_model->edit_other_patient($patient_id))
			{
				$this->session->set_userdata("success_message","Patient edited successfully");
				//redirect('reception/patients');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add patient. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['patient'] = $this->reception_model->get_patient_data($patient_id);
		$v_data['insurance'] = $this->reception_model->get_insurance();
		$data['content'] = $this->load->view('patients/edit_other_patient', $v_data, true);
		
		$data['title'] = 'Edit Patients';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	/*
	*	Edit other patient
	*
	*/
	public function edit_staff($patient_id)
	{
		//form validation rules
		$this->form_validation->set_rules('phone_number', 'Primary Phone', 'trim|xss_clean');
		// $this->form_validation->set_rules('patient_phone2', 'Other Phone', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reception_model->edit_staff_patient($patient_id))
			{
				$this->session->set_userdata("success_message","Patient edited successfully");
				//redirect('reception/patients');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add patient. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['patient'] = $this->reception_model->get_patient_staff_data($patient_id);
		$data['content'] = $this->load->view('patients/edit_staff', $v_data, true);
		
		$data['title'] = 'Edit Patients';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	/*
	*	Edit other patient
	*
	*/
	public function edit_student($patient_id)
	{
		//form validation rules
		$this->form_validation->set_rules('phone_number', 'Primary Phone', 'trim|xss_clean');
		// $this->form_validation->set_rules('patient_phone2', 'Other Phone', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reception_model->edit_student_patient($patient_id))
			{
				$this->session->set_userdata("success_message","Patient edited successfully");
				//redirect('reception/patients');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add patient. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['patient'] = $this->reception_model->get_patient_student_data($patient_id);
		$data['content'] = $this->load->view('patients/edit_student', $v_data, true);
		
		$data['title'] = 'Edit Patients';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	public function edit_staff_dependant_patient($patient_id)
	{
		//form validation rules
		$this->form_validation->set_rules('title_id', 'Title', 'is_numeric|xss_clean');
		$this->form_validation->set_rules('patient_surname', 'Surname', 'required|xss_clean');
		$this->form_validation->set_rules('patient_othernames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('patient_dob', 'Date of Birth', 'trim|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'xss_clean');
		$this->form_validation->set_rules('religion_id', 'Religion', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('relationship_id', 'Relationship With Kin', 'xss_clean');
		$this->form_validation->set_rules('staff_number', 'Next of Kin Contact', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reception_model->edit_staff_dependant_patient($patient_id))
			{
				$this->session->set_userdata("success_message","Patient edited successfully");
				
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit patient. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['patient'] = $this->reception_model->get_patient_data($patient_id);
		$data['content'] = $this->load->view('patients/edit_staff_dependant', $v_data, true);
		
		$data['title'] = 'Edit Staff Dependant';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function patient_names($visit_id)
	{
		var_dump($this->reception_model->patient_names2(NULL, $visit_id));
	}
	
	public function sort_student_duplicates($per_page, $page)
	{
		echo 'Sorting duplicates for records from '.$page.' to '.$per_page.'<br/>';
		//select all students from sumc db
		$query = $this->reception_model->get_all_students($per_page, $page);
		$faults_count = 0;
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$student_number = $res->student_Number;
				
				//check if there are duplicates in the patients table
				$check = $this->reception_model->get_all_student_patients($student_number);
				
				if($check->num_rows() > 1)
				{
					$faults_count++;
					
					//get the patient id that will stand for the duplicates
					$result = $check->result();
					$standing_patient_id = $result[0]->patient_id;
					
					for($r = 1; $r < $check->num_rows(); $r++)
					{
						$patient_id = $result[$r]->patient_id;
						//update visit data
						if($this->reception_model->change_patient_id($standing_patient_id, $patient_id))
						{
							//delete duplicate
							$this->reception_model->delete_duplicate_patient($patient_id);
							echo 'Faults = '.$faults_count.' :: student = '.$student_number.' :: changed from = '.$patient_id.' :: changed to = '.$standing_patient_id.'<br/>';
						}
					}
				}
			}
		}
		echo '<br/>Finished';
	}
	
	public function migrate_dependants()
	{
		//get all dependants
		$staff_query = $this->reception_model->get_all_dependants();
		
		if($staff_query->num_rows() > 0)
		{
			foreach($staff_query->result() as $res)
			{
				$surname = $res->surname;
				$other_names = $res->other_names;
				$dob = $res->DOB;
				$gender = $res->Gender;
				$patient_id = $res->patient_id;
				
				if($gender == 'Male')
				{
					$gender_id = 1;
				}
				
				else if($gender == 'Female')
				{
					$gender_id = 2;
				}
				
				else
				{
					$gender_id = 0;
				}
				
				$data = array
				(
					'patient_surname' => $surname,
					'patient_othernames' => $other_names,
					'gender_id' => $gender_id,
					'patient_date_of_birth' => $dob
				);
				
				$this->db->where('patient_id', $patient_id);
				if($this->db->update('patients', $data))
				{
					echo 'Updated :: '.$surname.' '.$other_names.'<br/>';
				}
			}
		}
	}
	public function search_general_queue($page_name)
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$patient_national_id = $this->input->post('patient_national_id');
		$patient_number = $this->input->post('patient_number');
		
		if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\'';
		}
		
		if(!empty($patient_national_id))
		{
			$patient_national_id = ' AND patients.patient_national_id = \''.$patient_national_id.'\' ';
		}
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
		}
		
		if(!empty($strath_no))
		{
			$strath_no = ' AND patients.strath_no LIKE '.$strath_no.' ';
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
		
		$search = $visit_type_id.$patient_number.$surname.$other_name.$patient_national_id;
		$this->session->set_userdata('general_queue_search', $search);
		
		$this->general_queue($page_name);
	}
	
	public function close_general_queue_search($page_name)
	{
		$this->session->unset_userdata('general_queue_search');
		$this->general_queue($page_name);
	}
	
	public function check_patient($visit_id)
	{
		$result = $this->reception_model->patient_names2(NULL, $visit_id);
		
		var_dump($result);
	}
	function import_template()
	{
		//export products template in excel 
		 $this->reception_model->import_template();
	}
	function import_patients()
	{
		//open the add new product
		$v_data['title'] = 'Import Patients';
		$data['title'] = 'Import Patients';
		$data['content'] = $this->load->view('patients/import_patients', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function do_patients_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->reception_model->import_csv_products($this->csv_path);
				
				if($response == FALSE)
				{
				}
				
				else
				{
					if($response['check'])
					{
						$v_data['import_response'] = $response['response'];
					}
					
					else
					{
						$v_data['import_response_error'] = $response['response'];
					}
				}
			}
			
			else
			{
				$v_data['import_response_error'] = 'Please select a file to import.';
			}
		}
		
		else
		{
			$v_data['import_response_error'] = 'Please select a file to import.';
		}
		
		//open the add new product
		$v_data['title'] = 'Import Patients';
		$data['title'] = 'Import Patients';
		$data['content'] = $this->load->view('patients/import_patients', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
}
?>