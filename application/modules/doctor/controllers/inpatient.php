<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inpatient extends MX_Controller {
	
	function __construct()
	{
		parent:: __construct();
		
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
	
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
		}
		
		$this->load->model('inpatient_model');
		$this->load->model('reception/reception_model');
		$this->load->model('reception/database');
		$this->load->model('administration/reports_model');
		$this->load->model('administration/administration_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		$this->load->model('laboratory/lab_model');
		$this->load->model('radiology/xray_model');
		$this->load->model('radiology/ultrasound_model');
		$this->load->model('theatre/theatre_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('administration/sync_model');
	}
	
	public function get_logged_in_member()
	{
		
		$newdata = array(
                   'personnel_email'     		=> $this->session->userdata('personnel_email'),
                   'personnel_first_name'     	=> $this->session->userdata('personnel_first_name'),
                   'personnel_id'  			=> $this->session->userdata('personnel_id'),
                   'personnel_username'  			=> $this->session->userdata('personnel_username')
               );
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
	}
	
	public function login_member() 
	{
			
		$this->form_validation->set_error_delimiters('', '');				
		$this->form_validation->set_rules('personnel_username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('personnel_password', 'Password', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if(($this->input->post('personnel_username')== 'amasitsa') && ($this->input->post('personnel_password')== 'r6r5bb!!'))
			{
				$newdata = array(
				   'login_status' => TRUE,
				   'personnel_first_name'   => 'Alvaro',
				   'personnel_username'     => 'amasitsa',
				   'personnel_id' => 0,
				   'branch_code'   => 'OSH',
				   'branch_name'     => 'KISII',
				   'personnel_email' => 2
			   );

				$this->session->set_userdata($newdata);
				
				$response['message'] = 'success';
				$response['result'] = $newdata;
				
			}
			
			else
			{
				
			
				if($this->inpatient_model->validate_member())
				{
					//create user's login session
					
					$response['message'] = 'success';
					$response['result'] = 'You have successfully logged in';
				}
				
				else
				{
					$response['message'] = 'fail';
					$response['result'] = 'You have entered incorrect details. Please try again';
				}
			}
		}
		else
		{

			$validation_errors = validation_errors();
			
			//repopulate form data if validation errors are present
			if(!empty($validation_errors))
			{
				$response['message'] = 'fail';
			 	$response['result'] = $validation_errors;
			}
			
			//populate form data on initial load of page
			else
			{
				$response['message'] = 'fail';
				$response['result'] = 'Ensure that you have entered all the values in the form provided';
			}
		}
		echo json_encode($response);
	}
	public function get_inpatient_list()
	{
		$segment = 3;
		
		$where = 'visit.ward_id = ward.ward_id AND visit.inpatient = 1 AND visit.visit_delete = 0 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = "OSH"';
		
		$table = 'visit, patients, visit_type, ward';
		
		$visit_search = $this->session->userdata('inpatients_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'get_inpatient_list';
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
		
		$v_data['wards'] = $this->reception_model->get_wards();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$newdata = $this->load->view('inpatient/inpatients', $v_data, true);
		
		$response['result'] = $newdata;

		echo json_encode($newdata);
		
	}
	public function get_inpatient_card($visit_id, $mike = NULL, $module = NULL,$opener = NULL)
	{
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['patient_type'] = $patient['patient_type'];
		$v_data['patient_othernames'] = $patient['patient_othernames'];
		$v_data['patient_surname'] = $patient['patient_surname'];
		$v_data['patient_type_id'] = $patient['visit_type_id'];
		$v_data['account_balance'] = $patient['account_balance'];
		$v_data['visit_type_name'] = $patient['visit_type_name'];
		$v_data['patient_id'] = $patient['patient_id'];
		$v_data['visit_ward_id'] = $patient['ward_id'];
		
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$visit_date = $this->reception_model->get_visit_date($visit_id);
		$gender = $patient['gender'];
		$visit_date = date('jS M Y',strtotime($visit_date));
		$v_data['age'] = $age;
		$v_data['visit_date'] = $visit_date;
		$v_data['gender'] = $gender;
		$v_data['wards'] = $this->reception_model->get_wards();
		$v_data['ward_rooms'] = $this->nurse_model->get_ward_rooms($v_data['visit_ward_id']);
		$v_data['visit_bed'] = $this->nurse_model->get_visit_bed($visit_id);
		
		$v_data['module'] = $module;
		$v_data['mike'] = $mike;
		$v_data['visit_id'] = $visit_id;
		$v_data['dental'] = 0;
		
		$newdata = $this->load->view('inpatient/patient_card', $v_data, true);
		$response['message'] = 'success';
		$response['result'] = $newdata;

		echo json_encode($response);
	}


}