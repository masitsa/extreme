<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// require_once "./application/modules/auth/controllers/auth.php";

class Theatre  extends MX_Controller
{	
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
		$this->load->model('theatre_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('reception/reception_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('medical_admin/medical_admin_model');
		$this->load->model('pharmacy/pharmacy_model');

		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('administration/personnel_model');
		
		/*$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}*/
	}
	
	public function index()
	{
		$this->session->unset_userdata('visit_search');
		$this->session->unset_userdata('patient_search');
		
		$where = 'visit.inpatient = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 14 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\' AND visit.personnel_id = '.$this->session->userdata('personnel_id');
		
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
		$data['sidebar'] = 'theatre_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function theatre_queue($page_name = NULL)
	{
		// this is it
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 14 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
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
		$config['base_url'] = site_url().'theatre/theatre_queue/'.$page_name;
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
		
		$data['title'] = 'Theatre Queue';
		$v_data['title'] = 'Theatre Queue';
		$v_data['module'] = 1;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('theatre_queue', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	public function queue_cheker($page_name = NULL)
	{
		$where = 'visit.inpatient = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 2 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\' AND visit.personnel_id = '.$this->session->userdata('personnel_id');
		$table = 'visit_department, visit, patients';
		$items = "*";
		$order = "visit.visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{
			echo 1;
		}
		else
		{
			echo 0;
		}

	}
	public function patient_card($visit_id, $mike = NULL)
	{
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$visit_type = $patient['visit_type'];
		$patient_type = $patient['patient_type'];
		$patient_othernames = $patient['patient_othernames'];
		$patient_surname = $patient['patient_surname'];
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$gender = $patient['gender'];
		
		$v_data['patient'] = 'Surname: <span style="font-weight: normal;">'.$patient_surname.'</span> Othernames: <span style="font-weight: normal;">'.$patient_othernames.'</span> Age: <span style="font-weight: normal;">'.$age.'</span> Gender: <span style="font-weight: normal;">'.$gender.'</span> Patient Type: <span style="font-weight: normal;">'.$visit_type.'</span>';

		$v_data['mike'] = $mike;
		$v_data['visit_id'] = $visit_id;
		$v_data['theatre'] = 1;
		
		$data['content'] = $this->load->view('patient_card', $v_data, true);
		
		$data['title'] = 'Patient Card';
		
		if(($mike != NULL) && ($mike != 'a')){
			$this->load->view('auth/template_no_sidebar', $data);	
		}else{
			$this->load->view('admin/templates/general_page', $data);	
		}
	}
	public function search_surgery($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND service_charge_name LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('surgery_search', $search);
		}
		
		redirect('theatre/surgery_list/0/'.$visit_id);
	}
	public function close_theatre_billing_search($visit_id)
	{
		$this->session->unset_userdata('billing_search');
		$this->theatre_services($visit_id);
	}
	function theatre_services($visit_id)
	{
		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'service_charge_name';
		
		$where = 'service_id = 9 AND visit_type_id = '.$visit_t;
		$billing_search = $this->session->userdata('billing_search');
		
		if(!empty($billing_search))
		{
			$where .= $billing_search;
		}
		
		$table = 'service_charge';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/theatre/theatre_services/'.$visit_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 4;
		$config['per_page'] = 15;
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
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->nurse_model->get_procedures($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Billing List';
		$v_data['title'] = 'Billing List';
		
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('billing_list', $v_data, true);
		
		$data['title'] = 'Billing List';
		$this->load->view('auth/template_no_sidebar', $data);	
	}

	public function view_billing($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('view_billing',$data);
	}
	function billing_service($service_id,$visit_id,$suck){
		$data = array('procedure_id'=>$service_id,'visit_id'=>$visit_id,'suck'=>$suck);
		$this->load->view('billing/billing',$data);	
	}
	public function billing_total($procedure_id,$units,$amount){
		$visit_data = array('visit_charge_units'=>$units);
		$this->db->where(array("visit_charge_id"=>$procedure_id));
		$this->db->update('visit_charge', $visit_data);
	}
	function delete_billing($procedure_id)
	{
		$this->db->where(array("visit_charge_id"=>$procedure_id));
		$this->db->delete('visit_charge', $visit_data);
	}
	public function send_to_accounts($visit_id)
	{
		if($this->reception_model->set_visit_department($visit_id, 6))
		{
				redirect("theatre/theatre_queue");
		}
		else
		{
			echo 'error';
		}

	}
	public function send_to_pharmacy($visit_id)
	{
		if($this->reception_model->set_visit_department($visit_id, 5))
		{
			redirect("theatre/theatre_queue");
		}
		else
		{
			FALSE;
		}
	}
	public function send_to_labs($visit_id)
	{
		if($this->reception_model->set_visit_department($visit_id, 4))
		{
			redirect("theatre/theatre_queue");
			
		}
		else
		{
			FALSE;
		}
	}
	
	public function surgery_list($ultrasound, $visit_id)
	{
		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'service_charge_name';
		
		$where = 'service_charge.service_id = service.service_id AND service.branch_code = "'.$this->session->userdata('branch_code').'" AND (service.service_name = "Surgery" OR service.service_name = "surgery") AND  service_charge.visit_type_id = '.$visit_t;
		$test_search = $this->session->userdata('surgery_search');
		
		if(!empty($test_search))
		{
			$where .= $test_search;
		}
		
		$table = '`service_charge`, service';
		$segment = 5;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'theatre/surgery_list/'.$ultrasound.'/'.$visit_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 10;
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
		$query = $this->theatre_model->get_surgeries($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = $v_data['title'] = 'Surgery List';
		
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('surgery_list', $v_data, true);
		
		$data['title'] = 'Laboratory Test List';
		$this->load->view('admin/templates/no_sidebar', $data);
	}

	public function test_surgery($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_surgery', $data);
	}

	public function test_orthopaedic_surgery($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_orthopaedic_surgery', $data);
	}
	public function test_opthamology_surgery($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_opthamology_surgery', $data);
	}
	public function test_obstetrics_surgery($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_obstetrics_surgery', $data);
	}
	public function test_theatre_procedures($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_theatre_procedures', $data);
	}

	public function test2($visit_id){
		$data = array('visit_id' => $visit_id);
		$this->load->view('tests/test2', $data);
	}
	
	public function delete_cost($visit_charge_id, $visit_id)
	{
		$this->theatre_model->delete_visit_theatre($visit_charge_id);
		
		$this->test_surgery($visit_id);
	}

	public function delete_inpatient_cost($visit_charge_id, $visit_id)
	{
		$this->theatre_model->delete_visit_theatre($visit_charge_id);
	}
}