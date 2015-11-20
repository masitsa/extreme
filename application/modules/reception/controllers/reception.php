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
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function index()
	{
		$this->session->unset_userdata('visit_search');
		$this->session->unset_userdata('patient_search');
		
		$where = 'visit.visit_delete = 0 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\' AND visit.visit_date = \''.date('Y-m-d').'\'';
		
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
			
			if($page_name == 'doctor')
			{
				//$where .= ' AND visit.personnel_id = '.$this->session->userdata('personnel_id');
			}
			
			//terminated visits
			if($visits == 1)
			{
				/*if($page_name == 'nurse' || $page_name == 'doctor')
				{
					$where .= ' ';
				}
				else
				{
					$where .= ' AND visit.close_card = '.$visits;	
				}*/
				$where .= ' AND visit.close_card = '.$visits;
				
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
					$where .= ' AND (visit.close_card = 0 OR visit.close_card = 7)';	
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
		// AND visit.visit_date = \''.date('Y-m-d').'\'
		$where = 'visit.inpatient = 0 AND visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type';
		
		if($page_name != 'reception')
		{
			$where .= ' AND visit.visit_date = \''.date('Y-m-d').'\'';
		}
		
		if($page_name == 'doctor')
		{
			//$where .= ' AND visit.personnel_id = '.$this->session->userdata('personnel_id');
		}
		
		if(($page_name != 'accounts') && ($page_name != 'doctor'))
		{
			$where .= ' AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		}
		
		if(($page_name == 'laboratory') || ($page_name == 'radiology') || ($page_name == 'pharmacy'))
		{
			$where = 'visit.inpatient = 0 AND visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type';
		}
		
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
		
		$v_data['wards'] = $this->reception_model->get_wards();
		$v_data['doctor'] = $this->reception_model->get_doctor();
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
	
	public function get_department_services($department_id, $selected_service_id = NULL)
	{
		echo '<option value="0">--Select Service--</option>';
		
		$service_charge = $this->reception_model->get_services_per_department($department_id);
		foreach($service_charge AS $key) 
		{
			if($selected_service_id == $key->service_id)
			{
				echo '<option value="'.$key->service_id.'" selected="selected">'.$key->service_name.'</option>';
			}
			
			else
			{
				echo '<option value="'.$key->service_id.'">'.$key->service_name.'</option>';
			}
		}
	}
	
	public function get_services_charges($patient_type_id, $service_id, $selected_service_charge_id)
	{
		echo '<option value="0">--Select Consultation Charge--</option>';
		
		$service_charge = $this->reception_model->get_service_charges_per_type($patient_type_id, $service_id);
		foreach($service_charge AS $key) 
		{ 
			if($selected_service_charge_id == $key->service_charge_id)
			{
				echo '<option value="'.$key->service_charge_id.'" selected="selected">'.$key->service_charge_name.'</option>';
			}
			
			else
			{
				echo '<option value="'.$key->service_charge_id.'">'.$key->service_charge_name.'</option>';
			}
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
		$v_data['wards'] = $this->reception_model->get_wards();
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
	
	/*
	*	Add a visit
	*
	*/
	public function edit_visit($visit_id)
	{
		$v_data["visit_id"] = $visit_id;
		$v_data['visit_details'] = $this->reception_model->get_visit($visit_id);
		$v_data['visit_depts'] = $this->reception_model->get_visit_depts($visit_id);
		$v_data['visit_charges'] = $this->reception_model->get_visit_charges($visit_id);
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
		
		$v_data['visit_departments'] = $this->reception_model->get_visit_departments();
		$v_data['visit_types'] = $this->reception_model->get_visit_types();
		$v_data['charge'] = $this->reception_model->get_service_charges2($visit_id);
		$v_data['wards'] = $this->reception_model->get_wards();
		$v_data['doctor'] = $this->reception_model->get_doctor();
		$v_data['type'] = $this->reception_model->get_types();
		
		$data['content'] = $this->load->view('visit/edit_visit', $v_data, true);
		
		$data['title'] = 'Edit Visit';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function save_visit($patient_id)
	{
		$this->form_validation->set_rules('visit_date', 'Visit Date', 'required');
		$this->form_validation->set_rules('department_id', 'Department', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('visit_type_id', 'Visit type', 'required|is_natural_no_zero');
		$visit_type_id = $this->input->post("visit_type_id"); 
		
		if(isset($_POST['department_id'])){
			if(($_POST['department_id'] == 2) || ($_POST['department_id'] == 7) || ($_POST['department_id'] == 14))
			{
				//if nurse visit (7) or theatre (14) service must be selected
				$this->form_validation->set_rules('personnel_id', 'Doctor', 'is_natural_no_zero');
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
		
		if($visit_type_id != 1)
		{
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
				$this->session->set_userdata('error_message', 'Seems like there is another visit that has been initiated');
				redirect('reception/general-queue');
			}
			
			else
			{
				//create visit
				$visit_id = $this->reception_model->create_visit($visit_date, $patient_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $timepicker_start, $timepicker_end, $appointment_id, $close_card);
				$this->sync_model->sync_patient_bookings($visit_id);
				//save consultation charge for nurse visit, counseling or theatre
				if($_POST['department_id'] == 2 || $_POST['department_id'] == 7 || $_POST['department_id'] == 12 || $_POST['department_id'] == 14)
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
	
	public function save_inpatient_visit($patient_id)
	{
		$this->form_validation->set_rules('visit_date', 'Admission Date', 'required');
		$this->form_validation->set_rules('ward_id', 'Ward', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('visit_type_id', 'Visit type', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('personnel_id', 'Doctor', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('service_charge_name', 'Consultation charge', 'required|is_natural_no_zero');
		
		$ward_id = $this->input->post("ward_id"); 
		$visit_type_id = $this->input->post("visit_type_id"); 
		$doctor_id = $this->input->post("personnel_id");
		$service_charge_id = $this->input->post("service_charge_name");
		
		if($visit_type_id != 1)
		{
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
			
			$visit_date = $this->input->post("visit_date");
			
			$close_card = 0;
			//  check if the student exisit for that day and the close card 0;
			$check_visits = $this->reception_model->check_patient_exist($patient_id, $visit_date);
			$check_count = count($check_visits);
			if($check_count > 0)
			{
				$this->session->set_userdata('error_message', 'Seems like there is another visit that has been initiated');
				redirect('reception/set_visit/'.$patient_id);
			}
			
			else
			{
				//create visit
				$visit_id = $this->reception_model->create_inpatient_visit($visit_date, $patient_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $close_card, $ward_id);
				
				//save admission fee
				if($this->reception_model->save_admission_fee($visit_type_id, $visit_id))
				{
					//save consultation fee
					$this->reception_model->save_visit_consultation_charge($visit_id, $service_charge_id);
					
					$this->session->set_userdata('success_message', 'Inpatient visit initiated successfully');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to save admission fee');
				}
				
				redirect('reception/inpatients');
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
		//check if card is held
		if($this->reception_model->is_card_held($visit_id))
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
					redirect('reception/visit_list/3');
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
	
	public function inpatients($page_name)
	{
		$segment = 4;
		
		$where = 'visit.ward_id = ward.ward_id AND visit.inpatient = 1 AND visit.visit_delete = 0 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		
		$table = 'visit, patients, visit_type, ward';
		
		$visit_search = $this->session->userdata('inpatients_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'reception/inpatients/'.$page_name;
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
		$query = $this->reception_model->get_inpatient_visits($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['title'] = $v_data['title'] = 'Inpatients';
		
		$v_data['page_name'] = $page_name;
		$v_data['wards'] = $this->reception_model->get_wards();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('inpatients', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function search_inpatients($page_name)
	{
		$ward_id = $this->input->post('ward_id');
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
		
		if(!empty($ward_id))
		{
			$ward_id = ' AND visit.ward_id = '.$ward_id.' ';
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
		
		$search = $visit_type_id.$patient_number.$surname.$other_name.$patient_national_id.$ward_id;
		$this->session->set_userdata('inpatients_search', $search);
		
		$this->inpatients($page_name);
	}
	public function change_items()
	{
		$this->reception_model->changing_to_osh();
	}
	
	public function change_patient_visit($visit_id, $visit_type_id)
	{
		$this->form_validation->set_rules('visit_date', 'Admission Date', 'required');
		$this->form_validation->set_rules('ward_id', 'Ward', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('personnel_id', 'Doctor', 'required|is_natural_no_zero');
		
		$ward_id = $this->input->post("ward_id"); 
		$doctor_id = $this->input->post("personnel_id");
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('error_message', validation_errors());
			redirect('reception/general-queue');
		}
		else
		{
			$visit_date = $this->input->post("visit_date");
			//create visit
			if($this->reception_model->change_patient_visit($visit_date, $doctor_id, $visit_id, $ward_id))
			{
				//save admission fee
				if($this->reception_model->save_admission_fee($visit_type_id, $visit_id))
				{
					//save consultation fee
					//$this->reception_model->save_visit_consultation_charge($visit_id, $service_charge_id);
					
					$this->session->set_userdata('success_message', 'Inpatient visit initiated successfully');
					redirect('reception/inpatients');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to save admission fee');
					redirect('reception/general-queue');
				}
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to change to inpatient. Please try again');
				redirect('reception/general-queue');
			}
		}
	}

	public function close_todays_visit()
	{
		$response	= $this->reception_model->close_todays_visits();

		echo $response."<br>";
	}
	
	public function update_visit($visit_id)
	{
		$this->form_validation->set_rules('visit_date', 'Visit Date', 'required');
		$this->form_validation->set_rules('department_id', 'Department', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('visit_type_id', 'Visit type', 'required|is_natural_no_zero');
		$visit_type_id = $this->input->post("visit_type_id"); 
		
		if(isset($_POST['department_id'])){
			if(($_POST['department_id'] == 2) || ($_POST['department_id'] == 7) || ($_POST['department_id'] == 14))
			{
				//if nurse visit (7) or theatre (14) service must be selected
				$this->form_validation->set_rules('personnel_id', 'Doctor', 'is_natural_no_zero');
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
		
		if($visit_type_id != 1)
		{
			$this->form_validation->set_rules('insurance_limit', 'Insurance limit', 'required');
			$this->form_validation->set_rules('insurance_number', 'Insurance number', 'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->edit_visit($visit_id);
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
			
			//update visit
			$visit_id = $this->reception_model->update_visit($visit_date, $visit_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $timepicker_start, $timepicker_end, $appointment_id, $close_card);
			
			//save consultation charge for nurse visit, counseling or theatre
			if($_POST['department_id'] == 2 || $_POST['department_id'] == 7 || $_POST['department_id'] == 12 || $_POST['department_id'] == 14)
			{
				//$this->reception_model->save_visit_consultation_charge($visit_id, $service_charge_id);	
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
						$this->session->set_userdata('success_message', 'Visit has been updated');
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
				$this->session->set_userdata('success_message', 'Visit has been updated');
			}
			
			redirect('reception/general-queue');
		}
	}
}
?>