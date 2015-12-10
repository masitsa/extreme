<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nurse  extends MX_Controller
{	
	var $signature_path;
	var $signature_location;
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
		$this->load->model('nurse_model');
		$this->load->model('reception/reception_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('reception/database');
		$this->load->model('medical_admin/medical_admin_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('administration/administration_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		$this->load->model('laboratory/lab_model');
		$this->load->model('radiology/xray_model');
		$this->load->model('radiology/ultrasound_model');
		$this->load->model('theatre/theatre_model');
		$this->signature_path = realpath(APPPATH . '../assets/signatures');
		$this->signature_location = base_url().'assets/signatures/';
		
		//removed because doctors loose notes
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
		
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 7 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		$table = 'visit_department, visit, patients';
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, 6, 0);
		$v_data['query'] = $query;
		$v_data['page'] = 0;
		
		$v_data['visit'] = 0;
		$v_data['department'] = 1;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('nurse_dashboard', $v_data, TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'nurse_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function nurse_queue($page_name = NULL)
	{
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 7 AND visit_department.accounts = 1 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		$patient_visit_search = $this->session->userdata('patient_visit_search');
		
		if(!empty($patient_visit_search))
		{
			$where .= $patient_visit_search;
		}
		
		if($page_name == NULL)
		{
			$segment = 3;
		}
		
		else
		{
			$segment = 4;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/nurse_queue/'.$page_name;
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
		
		$data['title'] = 'Nurse Queue';
		$v_data['title'] = 'Nurse Queue';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('nurse_queue', $v_data, true);
		
		if($page_name == 'doctor')
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
	
	public function check_queues($module = NULL)
	{
		

		$table = 'visit_department, visit, patients';
		if($module == 1)
		{
			$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 2 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\' AND visit.personnel_id = '.$this->session->userdata('personnel_id');
		}
		else
		{
		 $where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 7 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
	
		}
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
	public function patient_card($visit_id, $mike = NULL, $module = NULL,$opener = NULL)
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
		
		$v_data['module'] = $module;
		$v_data['mike'] = $mike;
		$v_data['visit_id'] = $visit_id;
		$v_data['dental'] = 0;

		// other updates

		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
		    # code...
		      $visit_t = $rs1->visit_type;
		  }
		}



		// echo $visit_t; die();
		$consumable_order = 'service_charge.service_charge_name';
		$consumable_table = 'service_charge,product,category';
		$consumble_where = 'service_charge.product_id = product.product_id AND category.category_id = product.category_id AND category.category_name = "Consumable" AND service_charge.visit_type_id = '.$visit_t;

		$consumable_query = $this->nurse_model->get_inpatient_consumable_list($consumable_table, $consumble_where, $consumable_order);
		$rs8 = $consumable_query->result();
		$consumables = '';
		foreach ($rs8 as $consumable_rs) :


		$consumable_id = $consumable_rs->service_charge_id;
		$consumable_name = $consumable_rs->service_charge_name;

		$consumable_name_stud = $consumable_rs->service_charge_amount;

		    $consumables .="<option value='".$consumable_id."'>".$consumable_name." KES.".$consumable_name_stud."</option>";

		endforeach;


		$order = 'service_charge.service_charge_name';

		$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "OSH" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;

		$table = 'service_charge,visit_type,service, departments';
		$config["per_page"] = 0;
		$procedure_query = $this->nurse_model->get_other_procedures($table, $where, $order);

		$rs9 = $procedure_query->result();
		$procedures = '';
		foreach ($rs9 as $rs10) :


		$procedure_id = $rs10->service_charge_id;
		$proced = $rs10->service_charge_name;
		$visit_type = $rs10->visit_type_id;
		$visit_type_name = $rs10->visit_type_name;

		$stud = $rs10->service_charge_amount;

		    $procedures .="<option value='".$procedure_id."'>".$proced." KES.".$stud."</option>";

		endforeach;

		$vaccine_order = 'service_charge.service_charge_name';

		$vaccine_where = 'service_charge.service_id = service.service_id AND (service.service_name = "vaccine" OR service.service_name = "Vaccination") AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;
		$vaccine_table = 'service_charge,visit_type,service';

		$vaccine_query = $this->nurse_model->get_inpatient_vaccines_list($vaccine_table, $vaccine_where, $vaccine_order);
		    

		$rs10 = $vaccine_query->result();
		$vaccines = '';
		foreach ($rs10 as $vaccine_rs) :


		  $vaccine_id = $vaccine_rs->service_charge_id;
		  $vaccine_name = $vaccine_rs->service_charge_name;
		  $visit_type_name = $vaccine_rs->visit_type_name;

		  $vaccine_price = $vaccine_rs->service_charge_amount;

		  $vaccines .="<option value='".$vaccine_id."'>".$vaccine_name." KES.".$vaccine_price."</option>";

		endforeach;





		// put it undet the soap 


		$lab_test_order = 'service_charge_name';

		//$where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
		$lab_test_where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
		    
		$lab_test_table = '`service_charge`, lab_test_class, lab_test, service';

		$lab_test_query = $this->lab_model->get_inpatient_lab_tests($lab_test_table, $lab_test_where, $lab_test_order);

		$rs11 = $lab_test_query->result();
		$lab_tests = '';
		foreach ($rs11 as $lab_test_rs) :


		  $lab_test_id = $lab_test_rs->service_charge_id;
		  $lab_test_name = $lab_test_rs->service_charge_name;

		  $lab_test_price = $lab_test_rs->service_charge_amount;

		  $lab_tests .="<option value='".$lab_test_id."'>".$lab_test_name." KES.".$lab_test_price."</option>";

		endforeach;




		$xray_order = 'service_charge_name';
		$xray_where = 'service_charge.service_id = service.service_id AND (service.service_name = "X Ray" OR service.service_name = "xray" OR service.service_name = "XRay" OR service.service_name = "xray" OR service.service_name = "Xray")  AND  service_charge.visit_type_id = '.$visit_t;
		$xray_table = '`service_charge`, service';
		$xray_query = $this->xray_model->get_inpatient_xrays($xray_table, $xray_where, $xray_order);

		$rs12 = $xray_query->result();
		$xrays = '';
		foreach ($rs12 as $xray_rs) :


		  $xray_id = $xray_rs->service_charge_id;
		  $xray_name = $xray_rs->service_charge_name;

		  $xray_price = $xray_rs->service_charge_amount;

		  $xrays .="<option value='".$xray_id."'>".$xray_name." KES.".$xray_price."</option>";

		endforeach;



		$ultra_sound_order = 'service_charge_name';
		    
		$ultra_sound_where = 'service_charge.service_id = service.service_id AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND  service_charge.visit_type_id = '.$visit_t;


		$ultra_sound_table = '`service_charge`, service';

		$ultra_sound_query = $this->ultrasound_model->get_inpatient_ultrasounds($ultra_sound_table, $ultra_sound_where, $ultra_sound_order);
		$rs13 = $ultra_sound_query->result();
		$ultrasound = '';
		foreach ($rs13 as $ultra_sound_rs) :


		  $ultra_sound_id = $ultra_sound_rs->service_charge_id;
		  $ultra_sound_name = $ultra_sound_rs->service_charge_name;

		  $ultra_sound_price = $ultra_sound_rs->service_charge_amount;

		  $ultrasound .="<option value='".$ultra_sound_id."'>".$ultra_sound_name." KES.".$ultra_sound_price."</option>";

		endforeach;




		// start of surgeries


		$orthopaedic_order = 'service_charge_name';
		    
		$orthopaedic_where = 'service_charge.service_id = service.service_id AND service.service_id = 25  AND  service_charge.visit_type_id = '.$visit_t;
		$orthopaedic_table = '`service_charge`, service';

		$orthopaedic_query = $this->theatre_model->get_inpatient_surgeries($orthopaedic_table, $orthopaedic_where,$orthopaedic_order);

		$rs14 = $orthopaedic_query->result();
		$orthopaedics = '';
		foreach ($rs14 as $orthopaedic_rs) :


		  $orthopaedic_id = $orthopaedic_rs->service_charge_id;
		  $orthopaedic_name = $orthopaedic_rs->service_charge_name;

		  $orthopaedic_price = $orthopaedic_rs->service_charge_amount;

		  $orthopaedics .="<option value='".$orthopaedic_id."'>".$orthopaedic_name." KES.".$orthopaedic_price."</option>";

		endforeach;



		$opthamology_order = 'service_charge_name';
		    
		$opthamology_where = 'service_charge.service_id = service.service_id AND service.service_id = 29  AND  service_charge.visit_type_id = '.$visit_t;
		$opthamology_table = '`service_charge`, service';

		$opthamology_query = $this->theatre_model->get_inpatient_surgeries($opthamology_table, $opthamology_where,$opthamology_order);

		$rs14 = $opthamology_query->result();
		$opthamology = '';
		foreach ($rs14 as $opthamology_rs) :


		  $opthamology_id = $opthamology_rs->service_charge_id;
		  $opthamology_name = $opthamology_rs->service_charge_name;

		  $opthamology_price = $opthamology_rs->service_charge_amount;

		  $opthamology .="<option value='".$opthamology_id."'>".$opthamology_name." KES.".$opthamology_price."</option>";

		endforeach;


		$obstetrics_order = 'service_charge_name';
		    
		$obstetrics_where = 'service_charge.service_id = service.service_id AND service.service_id = 30  AND  service_charge.visit_type_id = '.$visit_t;
		$obstetrics_table = '`service_charge`, service';

		$obstetrics_query = $this->theatre_model->get_inpatient_surgeries($obstetrics_table, $obstetrics_where,$obstetrics_order);

		$rs14 = $obstetrics_query->result();
		$obstetrics = '';
		foreach ($rs14 as $obstetrics_rs) :


		  $obstetrics_id = $obstetrics_rs->service_charge_id;
		  $obstetrics_name = $obstetrics_rs->service_charge_name;

		  $obstetrics_price = $obstetrics_rs->service_charge_amount;

		  $obstetrics .="<option value='".$obstetrics_id."'>".$obstetrics_name." KES.".$obstetrics_price."</option>";

		endforeach;


		$theatre_order = 'service_charge_name';
		    
		$theatre_where = 'service_charge.service_id = service.service_id  AND service.service_id = 27  AND  service_charge.visit_type_id = '.$visit_t;
		$theatre_table = '`service_charge`, service';

		$theatre_query = $this->theatre_model->get_inpatient_surgeries($theatre_table, $theatre_where,$theatre_order);

		$rs14 = $theatre_query->result();
		$theatre = '';
		foreach ($rs14 as $theatre_rs) :


		  $theatre_id = $theatre_rs->service_charge_id;
		  $theatre_name = $theatre_rs->service_charge_name;

		  $theatre_price = $theatre_rs->service_charge_amount;

		  $theatre .="<option value='".$theatre_id."'>".$theatre_name." KES.".$theatre_price."</option>";

		endforeach;

		// end of surgeries



		$drugs_order = 'product.product_name'; 
		$drugs_where = 'product.product_id = service_charge.product_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Pharmacy" OR service.service_name = "pharmacy") AND service_charge.visit_type_id = '.$visit_t;
		$drugs_table = 'product, service_charge, service';
		$drug_query = $this->pharmacy_model->get_inpatient_drugs($drugs_table, $drugs_where, $drugs_order);

		$rs15 = $drug_query->result();
		$drugs = '';
		foreach ($rs15 as $drug_rs) :


		  $drug_id = $drug_rs->service_charge_id;
		  $drug_name = $drug_rs->service_charge_name;
		  $brand_name = $drug_rs->brand_name;

		  $drug_price = $drug_rs->service_charge_amount;

		  $drugs .="<option value='".$drug_id."'>".$drug_name." Brand: ".$brand_name." KES.".$drug_price."</option>";

		endforeach;


		$disease_order = 'diseases_id';
        
		$disease_where = 'diseases_id > 0 ';
		$disease_table = 'diseases';
		
		$query = $this->nurse_model->get_inpatient_diseases($disease_table, $disease_where,$disease_order);

		$rs9 = $query->result();
		$diseases = '';
		// var_dump($query->result());
		foreach ($rs9 as $rs10) :


		$diseases_name = $rs10->diseases_name;
		$diseases_id = $rs10->diseases_id;
		$diseases_code = $rs10->diseases_code;

		  $diseases .="<option value='".$diseases_id."'>".$diseases_name." Disease Code: ".$diseases_code." </option>";

		endforeach;

		$v_data['consumables'] = $consumables;
		$v_data['diseases'] = $diseases;
		$v_data['drugs'] = $drugs;
		$v_data['procedures'] = $procedures;
		$v_data['orthopaedics'] = $orthopaedics;
		$v_data['theatre'] = $theatre;
		$v_data['drugs'] = $drugs;
		$v_data['vaccines'] = $vaccines;
		$v_data['xrays'] = $xrays;
		$v_data['lab_tests'] = $lab_tests;
		$v_data['ultrasound'] = $ultrasound;
		$v_data['opthamology'] = $opthamology;
		$v_data['obstetrics'] = $obstetrics;
		// end of surgeries
		// take all the updates

		$data['content'] = $this->load->view('patient_card', $v_data, true);
		
		$data['title'] = 'Patient Card';
		if(($mike != NULL) && ($mike != 'a')){
			$this->load->view('admin/templates/no_sidebar', $data);	
		}else{
			$this->load->view('admin/templates/general_page', $data);	
		}
	}
	
	public function dental_visit($visit_id, $mike = NULL, $module = NULL)
	{
		$v_data['module'] = $module;
		$v_data['visit_id'] = $visit_id;
		$v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['content'] = $this->load->view('dental_visit', $v_data, true);
		
		$data['title'] = "Dental Visit";
		
		if($module == 0)
		{
			$data['sidebar'] = 'nurse_sidebar';
		}
		
		else
		{
			$data['sidebar'] = 'doctor_sidebar';
		}
		if(($mike != NULL) && ($mike != 'a')){
			$this->load->view('admin/templates/no_sidebar', $data);	
		}else{
			$this->load->view('admin/templates/general_page', $data);	
		}
	}
	public function close_queue_search()
	{
		$this->session->unset_userdata('patient_visit_search');
		$this->nurse_queue();
	}

	public function vitals_interface($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('show_vitals',$data);	
	}

	public function load_vitals($vital_id,$visit_id)
	{
		$data = array('vitals_id'=>$vital_id,'visit_id'=>$visit_id);
		$this->load->view('show_loaded_vitals',$data);	
	}

	public function save_vitals($visit_id)
	{
		


		// revampped one
		//  check the visit counter value 
		$counter = $this->nurse_model->check_visit_counter($visit_id);
		// end of checking the visit counter value
		$systolic =$this->input->post('systolic');
		// first insert
		$time = date('h:i:s');
		$visit_data = array('vital_id'=>5,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$systolic,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		//  fisrt insert

		$diastolic =$this->input->post('diastolic');

		//  second insert
		$visit_data = array('vital_id'=>6,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$diastolic,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$weight =$this->input->post('weight');
		//  second insert
		$visit_data = array('vital_id'=>8,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$weight,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$height =$this->input->post('height');
		//  second insert
		$visit_data = array('vital_id'=>9,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$height,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$hip =$this->input->post('hip');
		//  second insert
		$visit_data = array('vital_id'=>4,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$hip,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$waist =$this->input->post('waist');
		//  second insert
		$visit_data = array('vital_id'=>3,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$waist,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$temperature =$this->input->post('temperature');
		//  second insert
		$visit_data = array('vital_id'=>1,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$temperature,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$pulse =$this->input->post('pulse');
		//  second insert
		$visit_data = array('vital_id'=>7,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$pulse,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$respiration =$this->input->post('respiration');
		//  second insert
		$visit_data = array('vital_id'=>2,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$respiration,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		
		$oxygen =$this->input->post('oxygen');
		//  second insert
		$visit_data = array('vital_id'=>11,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$oxygen,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert
		$pain =$this->input->post('pain');
		//  second insert
		$visit_data = array('vital_id'=>10,'visit_vitals_time'=>$time,'visit_id'=>$visit_id,'visit_vital_value'=>$pain,'visit_counter'=>$counter,'created_by'=>$this->session->userdata("personnel_id"),'created'=>date("Y-m-d"));
		$this->db->insert('visit_vital', $visit_data);
		// end of second insert

		
		// end of revamped one
	}
	public function save_nurse_notes($visit_id)
	{
		$signature_name = '';
		if(isset($_POST['signature']))
		{
			$this->load->library('signature/signature');
			//require_once 'signature-to-image.php';
	
			$json = $_POST['signature']; // From Signature Pad
			//var_dump($json); die();
			$img = $this->signature->sigJsonToImage($json);
			$signature_name = $this->session->userdata('username').'_signature_'.date('Y-m-d-H-i-s').'.png';
			imagepng($img, $this->signature_path.'\\'.$image_name);
			//imagedestroy($img);
		}
		
		if($this->nurse_model->add_notes($visit_id, $signature_name))
		{
			$v_data['signature_location'] = $this->signature_location;
			$v_data['query'] = $this->nurse_model->get_notes(1);
			$return['result'] = 'success';
			$return['message'] = $this->load->view('patients/notes', $v_data, TRUE);
			echo 'success';
		}
		
		else
		{
			echo 'fail';
		}
		// end of things to do with the trail
	}
	
	public function dental_vitals($visit_id)
	{
		//saving vitals
		if (isset($_POST['submit']))
		{
			$this->save_dental_vitals($visit_id);
			$this->patient_card($visit_id);
		}
		
		//saving & going to dentist
		else if (isset($_POST['submit']))
		{
			$this->save_dental_vitals($visit_id);
			$this->end_dental_vitals($visit_id);
		}
		
		//updating & going to dentist
		else if (isset($_POST['update']))
		{
			$dental_vitals_id = $this->input->post('dental_vitals_id');
			
			$this->update_dental_vitals($visit_id, $dental_vitals_id);
			$this->patient_card($visit_id);
		}
		
		//updating & going to dentist
		else if (isset($_POST['update1']))
		{
			$dental_vitals_id = $this->input->post('dental_vitals_id');
			
			$this->update_dental_vitals($visit_id, $dental_vitals_id);
			$this->end_dental_vitals($visit_id);
		}
		
		else
		{
			$this->patient_card($visit_id);
		}
	}
	
	public function save_dental_vitals($visit_id)
	{
		$save = $this->nurse_model->save_dental_vitals($visit_id);
		
		if($save != FALSE)
		{
			$this->session->set_userdata('success_message', 'Dental vitals saved successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Error saving dental vitals. Please try again');
		}
		
		return TRUE;
	}
	
	public function end_dental_vitals($visit_id)
	{
		$save = $this->nurse_model->save_dental_vitals($visit_id);	
		
		if($save != FALSE)
		{
			$update = $this->nurse_model->update_dental_visit($visit_id);
			
			if($update)
			{
				$this->session->set_userdata('success_message', 'Dental vitals saved successfully');
				
				redirect('nurse/nurse_queue');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Error updating visit. Please try again');
				$this->patient_card($visit_id);
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Error saving dental vitals. Please try again');
			$this->patient_card($visit_id);
		}
	}
	
	public function update_dental_vitals($visit_id, $dental_vitals_id)
	{	
		$update = $this->nurse_model->update_dental_vitals($dental_vitals_id);
		
		if($update != FALSE)
		{
			$this->session->set_userdata('success_message', 'Dental vitals updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Error updating dental vitals. Please try again');
		}
		
		return TRUE;
	}
	
	public function get_family_history($visit_id)
	{
		$v_data['visit_id'] = $visit_id;
		$v_data['patient_id'] = $this->reception_model->get_patient_id_from_visit($visit_id);
		$v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['family_disease_query'] = $this->nurse_model->get_family_disease();
		$v_data['family_query'] = $this->nurse_model->get_family();
		
		echo $this->load->view('patients/family_history', $v_data, TRUE);
	}
	function previous_vitals($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('show_previous_vitals',$data);	
	}

	function calculate_bmi($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('calculate_bmi',$data);
	}
	function calculate_hwr($vitals_id,$visit_id){
		$data = array('vitals_id'=>$vitals_id,'visit_id'=>$visit_id);
		$this->load->view('calculate_hwr',$data);
	}
	function view_procedure($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('view_procedure',$data);
	}
	
	function view_bed_charges($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('display_bed_charges',$data);
	}
	
	function view_consultation_charges($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('display_consultation_charges',$data);
	}
	
	public function search_procedures($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND service_charge.service_charge_name LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('procedure_search', $search);
		}
		
		$this->procedures($visit_id);
	}
	function view_vaccines($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('view_vaccine',$data);
	}
	
	public function search_vaccines($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND service_charge.service_charge_name LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('vaccine_search', $search);
		}
		
		redirect('nurse/vaccines_list/'.$visit_id);
	}
	
	public function close_vaccine_search($visit_id)
	{
		$this->session->unset_userdata('vaccine_search');
		
		redirect('nurse/vaccines_list/'.$visit_id);
	}

	public function search_consumables($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND service_charge.service_charge_name LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('consumable_search', $search);
		}
		
		redirect('nurse/consumables_list/'.$visit_id);
	}
	
	public function close_consumable_search($visit_id)
	{
		$this->session->unset_userdata('consumable_search');
		
		redirect('nurse/consumables_list/'.$visit_id);
	}

	function vaccines_list($visit_id)
	{
		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'service_charge.service_charge_name';
		
		$where = 'service_charge.service_id = service.service_id AND (service.service_name = "vaccine" OR service.service_name = "Vaccination") AND service.branch_code = "'.$this->session->userdata('branch_code').'" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;
		$vaccine_search = $this->session->userdata('vaccine_search');
		
		if(!empty($vaccine_search))
		{
			$where .= $vaccine_search;
		}
		
		$table = 'service_charge,visit_type,service';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/vaccines/'.$visit_id;
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
		$query = $this->nurse_model->get_vaccines_list($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Vaccines List';
		$v_data['title'] = 'Vaccines List';
		
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('vaccines_list', $v_data, true);
		
		$data['title'] = 'Vaccines List';
		$this->load->view('admin/templates/no_sidebar', $data);	
	}

	function vaccines($vaccine_id,$visit_id,$suck)
	{
		$data = array('vaccine_id'=>$vaccine_id,'visit_id'=>$visit_id,'suck'=>$suck);
		$this->nurse_model->submitvisitvaccine($vaccine_id,$visit_id,$suck);

		$visit_type_rs = $this->nurse_model->get_visit_type($visit_id);
		
		if(count($visit_type_rs) >0){
			foreach ($visit_type_rs as $rs1 ) :
				# code...
				$visit_t = $rs1->visit_type;
		
			endforeach;
		}
		$this->nurse_model->visit_charge_insert($visit_id,$vaccine_id,$suck);
		$this->visit_vaccines($visit_id);	
	}
	function consumables($consumable_id,$visit_id,$suck)
	{
		$data = array('consumable_id'=>$consumable_id,'visit_id'=>$visit_id,'suck'=>$suck);
		$this->nurse_model->submitvisitconsumable($consumable_id,$visit_id,$suck);

		$visit_type_rs = $this->nurse_model->get_visit_type($visit_id);
		
		if(count($visit_type_rs) >0){
			foreach ($visit_type_rs as $rs1 ) :
				# code...
				$visit_t = $rs1->visit_type;
		
			endforeach;
		}
		$this->nurse_model->visit_charge_insert($visit_id,$consumable_id,$suck);
		$this->visit_consumables($visit_id);	
	}

	function inpatient_consumables($consumable_id,$visit_id,$suck)
	{
		$data = array('consumable_id'=>$consumable_id,'visit_id'=>$visit_id,'suck'=>$suck);
		$this->nurse_model->submitvisitconsumable($consumable_id,$visit_id,$suck);

		$visit_type_rs = $this->nurse_model->get_visit_type($visit_id);
		
		if(count($visit_type_rs) >0){
			foreach ($visit_type_rs as $rs1 ) :
				# code...
				$visit_t = $rs1->visit_type;
		
			endforeach;
		}
		$this->nurse_model->visit_charge_insert($visit_id,$consumable_id,$suck);
		$this->visit_consumables($visit_id);	
	}

	function visit_vaccines($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('visit_vaccines/vaccines_assigned',$data);	
	}

	function visit_consumables($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('visit_consumables/consumables_assigned',$data);
	}
	public function search_diseases($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND diseases_name LIKE \'%'.$this->input->post('search_item').'%\' OR diseases_code LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('disease_search', $search);
		}
		
		$this->disease($visit_id);
	}
	
	public function close_procedure_search($visit_id)
	{
		$this->session->unset_userdata('procedure_search');
		$this->procedures($visit_id);
	}

	public function close_disease_search($visit_id)
	{
		$this->session->unset_userdata('disease_search');
		$this->disease($visit_id);
	}
	function procedures($visit_id)
	{
		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'service_charge.service_charge_name';
		
		$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "'.$this->session->userdata('branch_code').'" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;
		$procedure_search = $this->session->userdata('procedure_search');
		
		if(!empty($procedure_search))
		{
			$where .= $procedure_search;
		}
		
		$table = 'service_charge,visit_type,service, departments';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/procedures/'.$visit_id;
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
		
		$data['title'] = 'Procedure List';
		$v_data['title'] = 'Procedure List';
		
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('procedures_list', $v_data, true);
		
		$data['title'] = 'Procedure List';
		$this->load->view('admin/templates/no_sidebar', $data);	
	}

	function procedure($procedure_id,$visit_id,$suck)
	{
		$this->nurse_model->submitvisitprocedure($procedure_id,$visit_id,$suck);
		$this->nurse_model->visit_charge_insert($visit_id,$procedure_id,$suck);
		
		$this->view_procedure($visit_id);	
	}
	function delete_procedure($procedure_id)
	{
		$visit_data = array('visit_charge_delete'=>1,'deleted_by'=>$this->session->userdata("personnel_id"),'deleted_on'=>date("Y-m-d"),'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));

		$this->db->where(array("visit_charge_id"=>$procedure_id));
		$this->db->update('visit_charge', $visit_data);
	}
	function delete_bed($procedure_id)
	{
		$visit_data = array('visit_charge_delete'=>1,'deleted_by'=>$this->session->userdata("personnel_id"),'deleted_on'=>date("Y-m-d"),'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));

		$this->db->where(array("visit_charge_id"=>$procedure_id));
		$this->db->update('visit_charge', $visit_data);
	}
	function delete_vaccine($vaccine_id)
	{
		$visit_data = array('visit_charge_delete'=>1,'deleted_by'=>$this->session->userdata("personnel_id"),'deleted_on'=>date("Y-m-d"),'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));

		$this->db->where(array("visit_charge_id"=>$vaccine_id));
		$this->db->update('visit_charge', $visit_data);
		
	}
	public function delete_consumable($consumable_id)
	{
		$visit_data = array('visit_charge_delete'=>1,'deleted_by'=>$this->session->userdata("personnel_id"),'deleted_on'=>date("Y-m-d"),'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));

		$this->db->where(array("visit_charge_id"=>$consumable_id));
		$this->db->update('visit_charge', $visit_data);
		
	}
	function add_lifestyle($patient_id){

	}
	
	public function save_family_disease($disease_id, $family_id, $patient_id)
	{
		$this->nurse_model->save_family_disease($family_id, $patient_id, $disease_id);
	}
	
	public function delete_family_disease($disease_id, $family_id, $patient_id)
	{
		$this->nurse_model->delete_family_disease($family_id, $patient_id, $disease_id);
	}


	public function save_lifestyle($visit_id){
		$this->form_validation->set_rules('diet', 'Diet', 'trim|xss_clean');
		$this->form_validation->set_rules('drugs', 'Drugs', 'trim|xss_clean');
		$this->form_validation->set_rules('alcohol_percentage', 'Alcohol %', 'trim|xss_clean');
		$this->form_validation->set_rules('alcohol_qty', 'Alcohol Qty', 'trim|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata("error_message","Fill in the fields");
			$this->patient_card($visit_id);
		}
		
		else
		{
			$patient_id = $this->nurse_model->get_patient_id($visit_id);
			
			if($patient_id != FALSE)
			{
				$this->nurse_model->submit_lifestyle_values($patient_id);
				$this->patient_card($visit_id);	
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not find patient. Please try again");
				$this->patient_card($visit_id);	
			}
		}
		
	}
	public function procedure_total($procedure_id,$units,$amount){
		

		$visit_data = array('visit_charge_units'=>$units,'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));
		$this->db->where(array("visit_charge_id"=>$procedure_id));
		$this->db->update('visit_charge', $visit_data);
	}
	public function bed_total($visit_charge_id,$units,$amount){
		

		$visit_data = array('visit_charge_units'=>$units,'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));
		$this->db->where(array("visit_charge_id"=>$visit_charge_id));
		$this->db->update('visit_charge', $visit_data);
	}
	public function vaccine_total($vaccine_id,$units,$amount){
		

		$visit_data = array('visit_charge_units'=>$units,'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));
		$this->db->where(array("visit_charge_id"=>$vaccine_id));
		$this->db->update('visit_charge', $visit_data);
	}
	public function consuamble_total($consumable_id,$units,$amount){
		

		$visit_data = array('visit_charge_units'=>$units,'modified_by'=>$this->session->userdata("personnel_id"),'date_modified'=>date("Y-m-d"));
		$this->db->where(array("visit_charge_id"=>$consumable_id));
		$this->db->update('visit_charge', $visit_data);
	}

	public function view_symptoms($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/view_symptoms',$data);	
	}
	public function view_objective_findings($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/view_objective_findings',$data);
	}
	public function view_assessment($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/view_assessment',$data);
	}
	public function view_plan($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/view_plan',$data);
	}
	public function symptoms_list($visit_id){

		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'symptoms_id';
		
		$where = 'symptoms_id > 0 ';
		$symptoms_search = $this->session->userdata('symptoms_search');
		
		if(!empty($symptoms_search))
		{
			$where .= $symptoms_search;
		}
		
		$table = 'symptoms';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/symptoms_list/'.$visit_id;
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
		$query = $this->nurse_model->get_symptom_list($table, $where, $config["per_page"], $page, $order);

		$v_data['visit_symptoms'] = $this->nurse_model->get_symptoms_visit($visit_id);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Symptoms List';
		$v_data['title'] = 'Symptoms List';
		
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('symptoms_list', $v_data, true);
		
		$data['title'] = 'Symptoms List';
		$this->load->view('admin/templates/no_sidebar', $data);

	}
	function symptoms($symptoms_id,$status,$visit_id,$description=NULL)
	{
		if(isset($_POST['description']))
		{
			$description = $_POST['description'];
		}
		if ($status==0)
		{
			$this->nurse_model->update_visit_sypmtom($symptoms_id,$visit_id,$description);
		}
		else
		{
			$this->nurse_model->save_visit_sypmtom($symptoms_id,$visit_id,$status);
		}
		
		$this->view_selected_symptoms($visit_id);
	}
	function view_selected_symptoms($visit_id)
	{
		$rs2 = $this->nurse_model->get_visit_symptoms($visit_id);
		$num_rows2 = count($rs2);
		
		echo"<table class='table table-striped table-condensed table-bordered'>"; 
			echo"<tr>"; 
				echo"<th>";
					echo"#"; 
				echo"</th>"; 
				echo"<th>";
					echo"Symptom"; 
				echo"</th>"; 
				echo"<th>";
					echo"Yes/ No"; 
				echo"</th>"; 
				echo"<th>";
					echo"Description"; 
				echo"</th>"; 
			echo"</tr>"; 
			
			$count=0;
			if($num_rows2 > 0)
			{
				foreach ($rs2 as $key):	
					$count++;
					$symptoms_name = $key->symptoms_name;
					$status_name = $key->status_name;
					$visit_symptoms_id = $key->visit_symptoms_id;
					$description= $key->description;
					
					echo"<tr>";
						echo"<td>";
							echo $count;
						echo"</td>";
						echo"<td>";
							echo $symptoms_name; 
						echo"</td>";
						echo"<td>";
							echo $status_name; 
						echo"</td>";
						echo"<td>";
							echo $description; 
						echo"</td>";
					echo"</tr>";
				endforeach;
			}
			
		echo "</table>";
	}
	function objective_finding($visit_id){
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('soap/objective_finding', $v_data, true);
		
		$data['title'] = 'Objective findings';
		$this->load->view('admin/templates/no_sidebar', $data);
	}
	
	function add_objective_findings($objective_finding_id, $visit_id, $status)
	{
		if($status == 1)
		{
			$this->nurse_model->delete_objective_finding($objective_finding_id, $visit_id);
		}
		
		else
		{
			$this->nurse_model->save_objective_finding($objective_finding_id, $visit_id);
		}
		
		$this->view_selected_findings($visit_id);
	}
	
	public function view_selected_findings($visit_id)
	{
		$rs2 = $this->nurse_model->get_visit_objective_findings($visit_id);
		$num_rows2 = count($rs2);
			
		echo"<table class='table table-condensed table-striped table-bordered'>"; 
			echo"<tr>"; 
				echo"<th>";
					echo"#"; 
				echo"</th>"; 
				echo"<th>";
					echo"Group"; 
				echo"</th>"; 
				echo"<th>";
					echo"Name"; 
				echo"</th>"; 
				echo"<th>";
					echo"Description"; 
				echo"</th>"; 
			echo"</tr>"; 
			$count=0;
			
			if($num_rows2 > 0)
			{
				foreach ($rs2 as $key):
					$count++;
					$objective_findings_name = $key->objective_findings_name;
					$visit_objective_findings_id = $key->visit_objective_findings_id;
					$objective_findings_class_name = $key->objective_findings_class_name;
					$description= $key->description;
					
					echo"<tr>"; 
						echo"<td>";
							echo $count; 
						echo"</td>"; 
						echo"<td>";
							echo $objective_findings_class_name; 
						echo"</td>"; 
						echo"<td>";
							echo $objective_findings_name; 
						echo"</td>"; 
						echo"<td>";
							echo $description; 
						echo"</td>"; 
					echo"<tr>"; 
				endforeach;
			}
		echo "</table>";
	}
	
	function update_objective_findings($objective_finding_id, $visit_id, $status, $description = NULL)
	{
		if($this->nurse_model->update_objective_finding($objective_finding_id, $visit_id, $description))
		{
			$this->view_selected_findings($visit_id);
		}
	}
	
	function get_visit_objective_findings($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/add_objective_findings', $data);
	}

	function save_assessment($visit_id){
		$assessment = $this->input->post('notes');
		$visit_data = array('visit_assessment'=>$assessment);
		$this->db->where(array("visit_id"=>$visit_id));
		$this->db->update('visit', $visit_data);
	}
	
	function save_plan($visit_id){
		$plan = $this->input->post('notes');
		$visit_data = array('visit_plan'=>$plan);
		$this->db->where(array("visit_id"=>$visit_id));
		$this->db->update('visit', $visit_data);
	}
	

	function save_objective_findings($visit_id){
		$objective_finding = $this->input->post('notes');
		// $objective_finding = str_replace('%20', ' ',$objective_finding);
		$visit_data = array('visit_objective_findings'=>$objective_finding);

		$this->db->where(array("visit_id"=>$visit_id));
		$this->db->update('visit', $visit_data);
	}
	function save_symptoms($visit_id){
		$symptoms=$this->input->post('notes');
		//$symptoms = str_replace('%20', ' ',$symptoms);
		$visit_data = array('visit_symptoms'=>$symptoms);

		$this->db->where(array("visit_id"=>$visit_id));
		$this->db->update('visit', $visit_data);
	}


	public function disease($visit_id){
		
		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		
		$order = 'diseases_id';
		
		$where = 'diseases_id > 0 ';
		$desease_search = $this->session->userdata('disease_search');
		
		if(!empty($desease_search))
		{
			$where .= $desease_search;
		}
		
		$table = 'diseases';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/disease/'.$visit_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 4;
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
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->nurse_model->get_diseases($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Disease List';
		$v_data['title'] = 'Disease List';
		
		$v_data['visit_id'] = $visit_id;
		//$v_data['all_diseases'] = $this->nurse_model->get_all_diseases($table, $order);
		$data['content'] = $this->load->view('soap/disease', $v_data, true);
		
		$data['title'] = 'Disease List';
		$this->load->view('admin/templates/no_sidebar', $data);	

	}

	function get_diagnosis($visit_id){
		$data = array('visit_id'=>$visit_id);
		$this->load->view('soap/get_diagnosis',$data);
	}
	function save_diagnosis($disease_id,$visit_id){

		$visit_data = array('visit_id'=>$visit_id,'disease_id'=>$disease_id);
		$this->db->insert('diagnosis', $visit_data);
	}
	function delete_diagnosis($diagnosis_id)
	{
		$this->db->where('diagnosis_id', $diagnosis_id);
		if($this->db->delete('diagnosis'))
		{
			echo 'true';
		}
		
		else
		{
			echo 'false';
		}
	}
	function diagnose($visit_id){
		$visit_data = array('visit_id'=>$visit_id);
		$this->load->view('soap/diagnose',$visit_data);
	}

	public function doctor_notes($visit_id)
	{
		$visit_data = array('visit_id'=>$visit_id);
		$this->load->view('soap/doctor_notes',$visit_data);
	}
	public function save_doctor_notes($visit_id){


		$notes=$this->input->post('notes');

		$patient_id = $this->nurse_model->get_patient_id($visit_id);
		$rs = $this->nurse_model->get_doctor_notes($patient_id);
		$num_doc_notes = count($rs);
		
		if($num_doc_notes == 0){	
			$visit_data = array('patient_id'=>$patient_id,'doctor_notes'=>$notes);
			$this->db->insert('doctor_notes', $visit_data);

		}
		else {
			$visit_data = array('patient_id'=>$patient_id,'doctor_notes'=>$notes);
			$this->db->where('patient_id',$patient_id);
			$this->db->update('doctor_notes', $visit_data);
		}

		//  enter into the nurse notes trail
		$trail_data = array(
        		"patient_id" => $patient_id,
        		"doctor_notes" => $notes,
        		"added_by" => $this->session->userdata("personnel_id"),
        		"visit_id" => $visit_id,
        		"created" => date("Y-m-d")
	    		);

		$this->db->insert('doctor_patient_notes', $trail_data);
		// end of things to do with the trail

	}
	public function nurse_notes($visit_id){
		$visit_data = array('visit_id'=>$visit_id);
		$this->load->view('soap/nurse_notes',$visit_data);
	}

	public function send_to_doctor($visit_id)
	{
		if($this->reception_model->set_visit_department($visit_id, 2))
		{
			redirect('nurse/nurse_queue');
		}
		else
		{
			FALSE;
		}
	}
	public function send_to_labs($visit_id,$module)
	{
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('visit');
		$row = $query->row();
		$visit_type = $row->visit_type;
		if($this->reception_model->set_visit_department($visit_id, 18, $visit_type))
		{
			if($module == 1){
				redirect('doctor/doctor_queue');
			}else{
				redirect('nurse/nurse_queue');
			}
			
		}
		else
		{
			FALSE;
		}
	}
	
	public function send_to_pharmacy($visit_id,$module)
	{
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('visit');
		$row = $query->row();
		$visit_type = $row->visit_type;
		if($this->reception_model->set_visit_department($visit_id, 5, $visit_type))
		{
			if($module == 1){
				redirect('doctor/doctor_queue');
			}else{
				redirect('nurse/nurse_queue');
			}
		}
		else
		{
			FALSE;
		}
	}
	
	public function send_to_xray($visit_id, $module)
	{
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('visit');
		$row = $query->row();
		$visit_type = $row->visit_type;
		if($this->reception_model->set_visit_department($visit_id, 11, $visit_type))
		{
			if($module == 1){
				redirect('doctor/doctor_queue');
			}else{
				redirect('nurse/nurse_queue');
			}
		}
		else
		{
			FALSE;
		}
	}
	
	public function send_to_ultrasound($visit_id, $module)
	{
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('visit');
		$row = $query->row();
		$visit_type = $row->visit_type;
		if($this->reception_model->set_visit_department($visit_id, 11, $visit_type))
		{
			if($module == 1){
				redirect('doctor/doctor_queue');
			}else{
				redirect('nurse/nurse_queue');
			}
		}
		else
		{
			FALSE;
		}
	}
	
	public function save_illness($mec_id, $visit_id)
	{
		$illness = $this->input->post('illness');
		//$illness = str_replace('%20', ' ', $illness);
		
		//check if illness has been saved
		$query = $this->nurse_model->check_text_save($mec_id,$visit_id);
		
		//update if it exists
		if($query->num_rows() > 0)
		{
			if($this->nurse_model->update_illness($illness, $query->row()))
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
		
		//otherwise insert new row
		else
		{
			if($this->nurse_model->save_illness($illness, $mec_id, $visit_id))
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
	}
	
	public function save_medical_exam($cat_items_id, $format_id, $visit_id)
	{
		if($this->nurse_model->save_medical_exam($cat_items_id, $format_id, $visit_id))
		{
			echo 'true';
		}
		else
		{
			echo 'false';
		}
	}
	
	public function delete_medical_exam($cat_items_id, $format_id, $visit_id)
	{
		if($this->nurse_model->delete_medical_exam($cat_items_id, $format_id, $visit_id))
		{
			echo 'true';
		}
		else
		{
			echo 'false';
		}
	}

	
	function queue_totals()
	{
		//initialize required variables
		$totals = '';
		$names = '';
		$highest_bar = 0;
		$r = 1;
		$date = date('Y-m-d');
		
		//get nurse total
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 7 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		$table = 'visit_department, visit, patients';
		$nurse_total = $this->nurse_model->get_queue_total($table, $where);
		
		if($nurse_total > $highest_bar)
		{
			$highest_bar = $nurse_total;
		}
		
		//get doctor total
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 2 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		$doctor_total = $this->nurse_model->get_queue_total($table, $where);
		
		if($doctor_total > $highest_bar)
		{
			$highest_bar = $doctor_total;
		}
		
		//get lab total
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 4 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		$lab_total = $this->nurse_model->get_queue_total($table, $where);
		
		if($lab_total > $highest_bar)
		{
			$highest_bar = $lab_total;
		}
		
		//get pharnacy total
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 5 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		$pharmacy_total = $this->nurse_model->get_queue_total($table, $where);
		
		if($pharmacy_total > $highest_bar)
		{
			$highest_bar = $pharmacy_total;
		}
		
		$result['total_services'] = 4;
		$result['names'] = 'nurse, doctor, laboratory, pharmacy';
		$result['bars'] = $nurse_total.', '.$doctor_total.', '.$lab_total.', '.$pharmacy_total;
		$result['highest_bar'] = $highest_bar;
		echo json_encode($result);
	}
	public function send_to_accounts($visit_id, $module)
	{
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('visit');
		$row = $query->row();
		$visit_type = $row->visit_type;
		if($this->reception_model->set_visit_department($visit_id, 6, $visit_type))
		{
			if($module == 0){
				redirect("nurse/nurse_queue");
			}else if($module == 2){
				redirect("laboratory/lab_queue");
			}else if($module == 3){
				redirect("pharmacy/pharmacy_queue");
			}else if($module == 4){
				redirect("radiology/ultrasound-outpatients");
			}else if($module == 5){
				redirect("radiology/x-ray-outpatients");
			}else{
				redirect("doctor/doctor_queue");
			}
		}
		else
		{
			echo 'error';
		}
	}
	function from_lab_queue($page_name = NULL){
		// this is it

		$where = 'visit.visit_delete = 0  AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND doc_visit=1 AND lab_visit=22 AND visit.nurse_visit = 1 AND visit.pharmarcy !=7 AND visit.visit_date = \''.date('Y-m-d').'\'';
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		if($page_name == NULL)
		{
			$segment = 3;
		}
		
		else
		{
			$segment = 4;
		}
		$table = 'visit, patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/from_lab_queue/'.$page_name;
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
		
		$data['title'] = 'From Lab Queue';
		$v_data['title'] = 'From Lab Queue';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('from_lab_queue', $v_data, true);
		
		if($page_name == 'doctor')
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
	public function load_medication($visit_id)
	{
		$data['visit_id'] = $visit_id;
		$this->load->view('load_medication', $data);	

	}
	public function load_surgeries($visit_id)
	{
		$data['visit_id'] = $visit_id;
		$this->load->view('load_surgeries', $data);		
	}
	public function medication($visit_id)
	{
		
		$medication =$this->input->post('medication');
		$medicine_allergies =$this->input->post('medicine_allergies');
		$food_allergies =$this->input->post('food_allergies');
		$regular_treatment =$this->input->post('regular_treatment');
		
		$patient_id = $this->nurse_model->get_patient_id($visit_id);

		$rs = $this->nurse_model->get_medicals($patient_id);
		$num_medication= count($rs);
		

		$visit_data = array(
        		"medication_name" => $medication,
        		"patient_id" => $patient_id,
        		"food_allergies" => $food_allergies,
        		"medicine_allergies" => $medicine_allergies,
        		"regular_treatment" => $regular_treatment
	    		);

		if($num_medication ==0){

				$this->db->insert('medication', $visit_data);
		}
		
		else {
			$this->db->where('patient_id',$patient_id);
			$this->db->update('medication', $visit_data);
		}
	

	}

	public function surgeries($date,$description,$month,$visit_id)
	{

		$patient_id = $this->nurse_model->get_patient_id($visit_id);
		$description = str_replace('%20', ' ',$description);

		$visit_data = array(
        		"surgery_description" => $description,
        		"surgery_year" => $date,
        		"patient_id" => $patient_id,
        		"month_id" => $month
	    		);
		$this->db->insert('surgery', $visit_data);
	}
	public function delete_surgeries($id){
		
		$this->db->where('surgery_id',$id);
		$this->db->delete('surgery', $visit_data);
	}
	public function patient_vaccine($visit_id)
	{
		$data['visit_id'] = $visit_id;
		$this->load->view('patient_vaccine', $data);		
	}
	public function vaccine($vaccine_id,$status,$visit_id)
	{

		$patient_id = $this->nurse_model->get_patient_id($visit_id);


		$visit_data = array(
        		"vaccine_id" => $vaccine_id,
        		"status_id" => $status,
        		"patient_id" => $patient_id
	    		);
		$this->db->insert('patients_vaccine', $visit_data);
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
		
		$this->nurse_queue();
		
		
	}
	// vaccine inventory
	public function inventory()
	{
		$segment = 3;
		$order = 'vaccines.vaccine_name';
		//$where = 'drugs.brand_id = brand.brand_id AND class.class_id = drugs.class_id AND drugs.generic_id = generic.generic_id AND drugs.drug_type_id = drug_type.drug_type_id AND drugs.drug_administration_route_id = drug_administration_route.drug_administration_route_id AND drugs.drug_dose_unit_id = drug_dose_unit.drug_dose_unit_id AND drugs.drug_consumption_id = drug_consumption.drug_consumption_id';
		
		$where = 'vaccines.vaccine_deleted = 0';
		$vaccine_inventory_search = $this->session->userdata('vaccine_inventory_search');
		
		if(!empty($vaccine_inventory_search))
		{
			$where .= $vaccine_inventory_search;
		}
		
		$table = 'vaccines';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/inventory';
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
		$query = $this->nurse_model->get_vaccine_list($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Vaccine List';
		$v_data['title'] = 'Vaccine List';
		$data['sidebar'] = 'nurse_sidebar';
		$data['content'] = $this->load->view('vaccine_list', $v_data, true);
		
		$this->load->view('admin/templates/no_sidebar', $data);
	}

	public function consumables_list($visit_id)
	{

		$segment = 4;

		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}
		// echo $visit_t; die();
		$order = 'service_charge.service_charge_name';
		//$where = 'drugs.brand_id = brand.brand_id AND class.class_id = drugs.class_id AND drugs.generic_id = generic.generic_id AND drugs.drug_type_id = drug_type.drug_type_id AND drugs.drug_administration_route_id = drug_administration_route.drug_administration_route_id AND drugs.drug_dose_unit_id = drug_dose_unit.drug_dose_unit_id AND drugs.drug_consumption_id = drug_consumption.drug_consumption_id';
		
		$where = 'service_charge.product_id = product.product_id AND category.category_id = product.category_id AND category.category_name = "Consumable" AND service_charge.visit_type_id = '.$visit_t;
		$consumable_search = $this->session->userdata('consumable_search');
		
		if(!empty($consumable_search))
		{
			$where .= $consumable_search;
		}
		
		$table = 'service_charge,product,category';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/consumables_list/'.$visit_id;
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
		$query = $this->nurse_model->get_vaccine_list($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['visit_id'] = $visit_id;
		
		$data['title'] = 'Consumables List';
		$v_data['title'] = 'Consumables List';
		$data['content'] = $this->load->view('consumables_list', $v_data, true);
		
		$this->load->view('admin/templates/no_sidebar', $data);
	}

	/*
	*	Add Drug
	*
	*/
	public function add_vaccine()
	{
		//form validation rules
		$this->form_validation->set_rules('vaccine_name', 'Drug Name', 'required|xss_clean');
		$this->form_validation->set_rules('vaccine_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('vaccine_unitprice', 'Unit Price', 'numeric|xss_clean');
		$this->form_validation->set_rules('vaccine_dose', 'Dose', 'numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->nurse_model->save_vaccine())
			{
				$this->session->userdata('success_message', 'Drug has been added successfully');

				redirect('nurse/inventory');
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to add drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Add Vaccine';
		$v_data['title'] = 'Add Vaccine';
		$data['sidebar'] = 'nurse_sidebar';
		$v_data['drug_types'] = $this->pharmacy_model->get_drug_forms();
		$v_data['drug_brands'] = $this->pharmacy_model->get_drug_brands();
		$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();
		$v_data['drug_generics'] = $this->pharmacy_model->get_drug_generics();
		$v_data['drug_dose_units'] = $this->pharmacy_model->get_drug_dose_units();
		$v_data['admin_routes'] = $this->pharmacy_model->get_admin_route();
		$v_data['consumption'] = $this->pharmacy_model->get_consumption();
		$data['content'] = $this->load->view('add_vaccine', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*
	*	Edit Drug
	*
	*/
	public function edit_vaccine($vaccine_id)
	{
		//form validation rules
		$this->form_validation->set_rules('vaccine_name', 'Drug Name', 'required|xss_clean');
		$this->form_validation->set_rules('vaccine_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('vaccine_unitprice', 'Unit Price', 'numeric|xss_clean');
		$this->form_validation->set_rules('vaccine_dose', 'Dose', 'numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->nurse_model->edit_vaccine($vaccine_id))
			{
				$this->session->userdata('success_message', 'Vaccine has been editted successfully');
				redirect('nurse/inventory');
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to edit drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Vaccine';
		$v_data['title'] = 'Edit Vaccine';
		$data['sidebar'] = 'nurse_sidebar';
		$vaccine_details = $this->nurse_model->get_vaccine_details($vaccine_id);
		
		if($vaccine_details->num_rows() > 0)
		{
			$v_data['vaccine_details'] = $vaccine_details->row();
			$v_data['vaccine_id'] = $vaccine_id;

			$data['content'] = $this->load->view('edit_vaccine', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	public function vaccine_purchases($vaccine_id)
	{
		$segment = 4;
		$order = 'vaccine_purchase.purchase_date';
		$where = 'vaccine_purchase.vaccine_id = '.$vaccine_id;
		
		$vaccine_search = $this->session->userdata('vaccine_purchases_search');
		
		if(!empty($vaccine_search))
		{
			$where .= $vaccine_search;
		}
		
		$table = 'vaccine_purchase';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/vaccine_purchases/'.$vaccine_id;
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
		$query = $this->nurse_model->get_vaccine_purchases($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['vaccine_id'] = $vaccine_id;
		
		$data['title'] = 'vaccine List';
		$v_data['title'] = 'vaccine List';
		$data['sidebar'] = 'nurse_sidebar';
		$vaccine_details = $this->nurse_model->get_vaccine_details($vaccine_id);
		
		if($vaccine_details->num_rows() > 0)
		{
			$row = $vaccine_details->row();
			$v_data['title'] = $row->vaccine_name.' Purchases';
			$data['content'] = $this->load->view('vaccine_purchases', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function purchase_vaccine($vaccine_id)
	{
		//form validation rules
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->nurse_model->purchase_vaccine($vaccine_id))
			{
				$this->session->userdata('success_message', 'vaccine has been purchased successfully');
				redirect('nurse/vaccine_purchases/'.$vaccine_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase vaccine. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Buy vaccine';
		$data['sidebar'] = 'nurse_sidebar';
		$vaccine_details = $this->nurse_model->get_vaccine_details($vaccine_id);
		
		if($vaccine_details->num_rows() > 0)
		{
			$row = $vaccine_details->row();
			$v_data['title'] = 'Buy '.$row->vaccine_name;
			$v_data['vaccine_id'] = $vaccine_id;
			$v_data['container_types'] = $this->pharmacy_model->get_container_types();
			$data['content'] = $this->load->view('buy_vaccine', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find vaccine';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function edit_vaccine_purchase($purchase_id, $vaccine_id)
	{
		//form validation rules
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->nurse_model->edit_vaccine_purchase($purchase_id))
			{
				$this->session->userdata('success_message', 'vaccine has been purchased successfully');
				redirect('nurse/vaccine_purchases/'.$vaccine_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase vaccine. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Purchase';
		$data['sidebar'] = 'nurse_sidebar';
		$vaccine_details = $this->nurse_model->get_vaccine_details($vaccine_id);
		$purchase_details = $this->nurse_model->get_purchase_details($purchase_id);
		
		if($vaccine_details->num_rows() > 0)
		{
			$row = $vaccine_details->row();
			$v_data['title'] = 'Edit '.$row->vaccine_name.' Purchase';
			$v_data['vaccine_id'] = $vaccine_id;
			$v_data['purchase_details'] = $purchase_details->row();
			$data['content'] = $this->load->view('edit_vaccine_purchase', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find purchase details';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	public function patient_treatment_statement($module = NULL)
	{
		$segment = 4;
		$patient_treatment_statement_search = $this->session->userdata('patient_treatment_statement_search');
		// $where = '(visit_type_id <> 2 OR visit_type_id <> 1) AND patient_delete = '.$delete;
		$where = 'patient_delete = 0';

		if(!empty($patient_treatment_statement_search))
		{
			$where .= $patient_treatment_statement_search;
		}
		
		$table = 'patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/patient_treatment_statement/'.$module;
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
	
		$data['title'] = 'Patients Treatment Statements';
		$v_data['title'] = ' Patients Treatment Statements';
	
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['delete'] = 1;
		$v_data['module'] = $module;
		$v_data['type'] = $this->reception_model->get_types();
		$data['content'] = $this->load->view('patient_treatment/patients_list_statement', $v_data, true);

		if($module == 'doctor')
		{
			$data['sidebar'] = 'doctor_sidebar';
		}
		else
		{
			$data['sidebar'] = 'nurse_sidebar';
		}
		
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function search_patient_treatment_statement($module)
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
		
			$search = $visit_type_id.$strath_no.$surname.$other_name;
			$this->session->set_userdata('patient_treatment_statement_search', $search);
			
			$this->patient_treatment_statement($module);
	}
	public function treatment_statement($patient_id,$module = NULL)
	{
		$segment = 5;		// this is it

		$where = 'visit.patient_id = patients.patient_id AND visit.`patient_id`='.$patient_id;
		
		
		$table = 'visit,patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/treatment_statement/'.$patient_id.'/'.$module;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 4;
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
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->nurse_model->get_all_patient_history($table, $where, $config["per_page"], $page);

		$data['title'] = 'Patient Statement';
		$v_data['title'] = 'Patient Statement';
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('patient_treatment/treatment_statement', $v_data, true);

		if($module == NULL)
		{
			$data['sidebar'] = 'nurse_sidebar';	
		}
		else if($module == 'doctor')
		{
			$data['sidebar'] = 'doctor_sidebar';
		}
		else
		{
			$data['sidebar'] = 'nurse_sidebar';
		}
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function inpatient_card($visit_id, $mike = NULL, $module = NULL,$opener = NULL)
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
		// other updates

		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
		    # code...
		      $visit_t = $rs1->visit_type;
		  }
		}



		// echo $visit_t; die();
		$consumable_order = 'service_charge.service_charge_name';
		$consumable_table = 'service_charge,product,category';
		$consumble_where = 'service_charge.product_id = product.product_id AND category.category_id = product.category_id AND category.category_name = "Consumable" AND service_charge.visit_type_id = '.$visit_t;

		$consumable_query = $this->nurse_model->get_inpatient_consumable_list($consumable_table, $consumble_where, $consumable_order);
		$rs8 = $consumable_query->result();
		$consumables = '';
		foreach ($rs8 as $consumable_rs) :


		$consumable_id = $consumable_rs->service_charge_id;
		$consumable_name = $consumable_rs->service_charge_name;

		$consumable_name_stud = $consumable_rs->service_charge_amount;

		    $consumables .="<option value='".$consumable_id."'>".$consumable_name." KES.".$consumable_name_stud."</option>";

		endforeach;


		$order = 'service_charge.service_charge_name';

		$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "OSH" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;

		$table = 'service_charge,visit_type,service, departments';
		$config["per_page"] = 0;
		$procedure_query = $this->nurse_model->get_other_procedures($table, $where, $order);

		$rs9 = $procedure_query->result();
		$procedures = '';
		foreach ($rs9 as $rs10) :


		$procedure_id = $rs10->service_charge_id;
		$proced = $rs10->service_charge_name;
		$visit_type = $rs10->visit_type_id;
		$visit_type_name = $rs10->visit_type_name;

		$stud = $rs10->service_charge_amount;

		    $procedures .="<option value='".$procedure_id."'>".$proced." KES.".$stud."</option>";

		endforeach;

		$vaccine_order = 'service_charge.service_charge_name';

		$vaccine_where = 'service_charge.service_id = service.service_id AND (service.service_name = "vaccine" OR service.service_name = "Vaccination") AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;
		$vaccine_table = 'service_charge,visit_type,service';

		$vaccine_query = $this->nurse_model->get_inpatient_vaccines_list($vaccine_table, $vaccine_where, $vaccine_order);
		    

		$rs10 = $vaccine_query->result();
		$vaccines = '';
		foreach ($rs10 as $vaccine_rs) :


		  $vaccine_id = $vaccine_rs->service_charge_id;
		  $vaccine_name = $vaccine_rs->service_charge_name;
		  $visit_type_name = $vaccine_rs->visit_type_name;

		  $vaccine_price = $vaccine_rs->service_charge_amount;

		  $vaccines .="<option value='".$vaccine_id."'>".$vaccine_name." KES.".$vaccine_price."</option>";

		endforeach;





		// put it undet the soap 


		$lab_test_order = 'service_charge_name';

		//$where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
		$lab_test_where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
		    
		$lab_test_table = '`service_charge`, lab_test_class, lab_test, service';

		$lab_test_query = $this->lab_model->get_inpatient_lab_tests($lab_test_table, $lab_test_where, $lab_test_order);

		$rs11 = $lab_test_query->result();
		$lab_tests = '';
		foreach ($rs11 as $lab_test_rs) :


		  $lab_test_id = $lab_test_rs->service_charge_id;
		  $lab_test_name = $lab_test_rs->service_charge_name;

		  $lab_test_price = $lab_test_rs->service_charge_amount;

		  $lab_tests .="<option value='".$lab_test_id."'>".$lab_test_name." KES.".$lab_test_price."</option>";

		endforeach;




		$xray_order = 'service_charge_name';
		$xray_where = 'service_charge.service_id = service.service_id AND (service.service_name = "X Ray" OR service.service_name = "xray" OR service.service_name = "XRay" OR service.service_name = "xray" OR service.service_name = "Xray")  AND  service_charge.visit_type_id = '.$visit_t;
		$xray_table = '`service_charge`, service';
		$xray_query = $this->xray_model->get_inpatient_xrays($xray_table, $xray_where, $xray_order);

		$rs12 = $xray_query->result();
		$xrays = '';
		foreach ($rs12 as $xray_rs) :


		  $xray_id = $xray_rs->service_charge_id;
		  $xray_name = $xray_rs->service_charge_name;

		  $xray_price = $xray_rs->service_charge_amount;

		  $xrays .="<option value='".$xray_id."'>".$xray_name." KES.".$xray_price."</option>";

		endforeach;



		$ultra_sound_order = 'service_charge_name';
		    
		$ultra_sound_where = 'service_charge.service_id = service.service_id AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND  service_charge.visit_type_id = '.$visit_t;


		$ultra_sound_table = '`service_charge`, service';

		$ultra_sound_query = $this->ultrasound_model->get_inpatient_ultrasounds($ultra_sound_table, $ultra_sound_where, $ultra_sound_order);
		$rs13 = $ultra_sound_query->result();
		$ultrasound = '';
		foreach ($rs13 as $ultra_sound_rs) :


		  $ultra_sound_id = $ultra_sound_rs->service_charge_id;
		  $ultra_sound_name = $ultra_sound_rs->service_charge_name;

		  $ultra_sound_price = $ultra_sound_rs->service_charge_amount;

		  $ultrasound .="<option value='".$ultra_sound_id."'>".$ultra_sound_name." KES.".$ultra_sound_price."</option>";

		endforeach;




		// start of surgeries


		$orthopaedic_order = 'service_charge_name';
		    
		$orthopaedic_where = 'service_charge.service_id = service.service_id AND service.service_id = 25  AND  service_charge.visit_type_id = '.$visit_t;
		$orthopaedic_table = '`service_charge`, service';

		$orthopaedic_query = $this->theatre_model->get_inpatient_surgeries($orthopaedic_table, $orthopaedic_where,$orthopaedic_order);

		$rs14 = $orthopaedic_query->result();
		$orthopaedics = '';
		foreach ($rs14 as $orthopaedic_rs) :


		  $orthopaedic_id = $orthopaedic_rs->service_charge_id;
		  $orthopaedic_name = $orthopaedic_rs->service_charge_name;

		  $orthopaedic_price = $orthopaedic_rs->service_charge_amount;

		  $orthopaedics .="<option value='".$orthopaedic_id."'>".$orthopaedic_name." KES.".$orthopaedic_price."</option>";

		endforeach;



		$opthamology_order = 'service_charge_name';
		    
		$opthamology_where = 'service_charge.service_id = service.service_id AND service.service_id = 29  AND  service_charge.visit_type_id = '.$visit_t;
		$opthamology_table = '`service_charge`, service';

		$opthamology_query = $this->theatre_model->get_inpatient_surgeries($opthamology_table, $opthamology_where,$opthamology_order);

		$rs14 = $opthamology_query->result();
		$opthamology = '';
		foreach ($rs14 as $opthamology_rs) :


		  $opthamology_id = $opthamology_rs->service_charge_id;
		  $opthamology_name = $opthamology_rs->service_charge_name;

		  $opthamology_price = $opthamology_rs->service_charge_amount;

		  $opthamology .="<option value='".$opthamology_id."'>".$opthamology_name." KES.".$opthamology_price."</option>";

		endforeach;


		$obstetrics_order = 'service_charge_name';
		    
		$obstetrics_where = 'service_charge.service_id = service.service_id AND service.service_id = 30  AND  service_charge.visit_type_id = '.$visit_t;
		$obstetrics_table = '`service_charge`, service';

		$obstetrics_query = $this->theatre_model->get_inpatient_surgeries($obstetrics_table, $obstetrics_where,$obstetrics_order);

		$rs14 = $obstetrics_query->result();
		$obstetrics = '';
		foreach ($rs14 as $obstetrics_rs) :


		  $obstetrics_id = $obstetrics_rs->service_charge_id;
		  $obstetrics_name = $obstetrics_rs->service_charge_name;

		  $obstetrics_price = $obstetrics_rs->service_charge_amount;

		  $obstetrics .="<option value='".$obstetrics_id."'>".$obstetrics_name." KES.".$obstetrics_price."</option>";

		endforeach;


		$theatre_order = 'service_charge_name';
		    
		$theatre_where = 'service_charge.service_id = service.service_id  AND service.service_id = 27  AND  service_charge.visit_type_id = '.$visit_t;
		$theatre_table = '`service_charge`, service';

		$theatre_query = $this->theatre_model->get_inpatient_surgeries($theatre_table, $theatre_where,$theatre_order);

		$rs14 = $theatre_query->result();
		$theatre = '';
		foreach ($rs14 as $theatre_rs) :


		  $theatre_id = $theatre_rs->service_charge_id;
		  $theatre_name = $theatre_rs->service_charge_name;

		  $theatre_price = $theatre_rs->service_charge_amount;

		  $theatre .="<option value='".$theatre_id."'>".$theatre_name." KES.".$theatre_price."</option>";

		endforeach;

		// end of surgeries



		$drugs_order = 'product.product_name'; 
		$drugs_where = 'product.product_id = service_charge.product_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Pharmacy" OR service.service_name = "pharmacy") AND service_charge.visit_type_id = '.$visit_t;
		$drugs_table = 'product, service_charge, service';
		$drug_query = $this->pharmacy_model->get_inpatient_drugs($drugs_table, $drugs_where, $drugs_order);

		$rs15 = $drug_query->result();
		$drugs = '';
		foreach ($rs15 as $drug_rs) :


		  $drug_id = $drug_rs->service_charge_id;
		  $drug_name = $drug_rs->service_charge_name;
		  $brand_name = $drug_rs->brand_name;

		  $drug_price = $drug_rs->service_charge_amount;

		  $drugs .="<option value='".$drug_id."'>".$drug_name." Brand: ".$brand_name." KES.".$drug_price."</option>";

		endforeach;


		$disease_order = 'diseases_id';
        
		$disease_where = 'diseases_id > 0 ';
		$disease_table = 'diseases';
		
		$query = $this->nurse_model->get_inpatient_diseases($disease_table, $disease_where,$disease_order);

		$rs9 = $query->result();
		$diseases = '';
		// var_dump($query->result());
		foreach ($rs9 as $rs10) :


		$diseases_name = $rs10->diseases_name;
		$diseases_id = $rs10->diseases_id;
		$diseases_code = $rs10->diseases_code;

		  $diseases .="<option value='".$diseases_id."'>".$diseases_name." Disease Code: ".$diseases_code." </option>";

		endforeach;

		$v_data['consumables'] = $consumables;
		$v_data['diseases'] = $diseases;
		$v_data['drugs'] = $drugs;
		$v_data['procedures'] = $procedures;
		$v_data['orthopaedics'] = $orthopaedics;
		$v_data['theatre'] = $theatre;
		$v_data['drugs'] = $drugs;
		$v_data['vaccines'] = $vaccines;
		$v_data['xrays'] = $xrays;
		$v_data['lab_tests'] = $lab_tests;
		$v_data['ultrasound'] = $ultrasound;
		$v_data['opthamology'] = $opthamology;
		$v_data['obstetrics'] = $obstetrics;
		// end of surgeries
		// take all the updates
		
		$v_data['module'] = $module;
		$v_data['mike'] = $mike;
		$v_data['visit_id'] = $visit_id;
		$v_data['dental'] = 0;
		$v_data['mobile_personnel_id'] = NULL;
		$data['content'] = $this->load->view('doctor/inpatient/patient_card', $v_data, true);
		
		$data['title'] = 'Patient Card';
		if(($mike != NULL) && ($mike != 'a')){
			$this->load->view('admin/templates/no_sidebar', $data);	
		}else{
			$this->load->view('admin/templates/general_page', $data);	
		}
	}
	
	public function get_room_beds($room_id)
	{
		$room_beds = $this->nurse_model->get_room_beds($room_id);
		$beds = '';
		
		if($room_beds->num_rows() > 0)
		{
			foreach($room_beds->result() as $res)
			{
				$bed_id = $res->bed_id;
				$bed_number = $res->bed_number;
				
				$beds .= '<option value="'.$bed_id.'">'.$bed_number.'</option>';
			}
		}
		
		echo $beds;
	}
	
	public function get_ward_rooms($ward_id)
	{
		$ward_rooms = $this->nurse_model->get_ward_rooms($ward_id);
		$rooms = '';
		
		if($ward_rooms->num_rows() > 0)
		{
			foreach($ward_rooms->result() as $res)
			{
				$room_id = $res->room_id;
				$room_name = $res->room_name;
				
				$rooms .= '<option value="'.$room_id.'">'.$room_name.'</option>';
			}
		}
		
		echo $rooms;
	}
	
	public function set_patient_room($visit_id)
	{
		$this->form_validation->set_rules('bed_id', 'Bed', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->nurse_model->update_room_details($visit_id))
			{
				if($this->nurse_model->bill_bed($this->input->post('bed_id'), $visit_id))
				{
					$this->session->set_userdata('success_message', 'Room details updated successfully');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to bill for bed');
				}
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update room details. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('nurse/inpatient_card/'.$visit_id.'/a/0');
	}
	
	public function add_consultant($visit_id)
	{
		$this->form_validation->set_rules('personnel_id', 'Doctor', 'required|xss_clean');
		$this->form_validation->set_rules('service_charge_id', 'Charge', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->nurse_model->add_consultant($visit_id))
			{
				$this->session->userdata('success_message', 'Doctor added successfully');	
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to add doctor. Please try again');
			}
		}
		
		else
		{
			$this->session->userdata('error_message', validation_errors());
		}
		
		redirect('nurse/inpatient_card/'.$visit_id.'/a/0');
	}
}
?>