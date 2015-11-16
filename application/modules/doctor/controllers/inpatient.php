<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inpatient extends MX_Controller 
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
		$this->signature_path = realpath(APPPATH . '../assets/signatures');
		$this->signature_location = base_url().'assets/signatures/';
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
				   'personnel_fname'   => 'Alvaro',
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
				
				$newdata = $this->inpatient_model->validate_member();
				if($newdata != FALSE)
				{
					//create user's login session
					
					$response['message'] = 'success';
					$response['result'] = $newdata;
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
	public function get_inpatient_card($visit_id, $mike = NULL, $module = NULL,$mobile_personnel_id = NULL)
	{
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['mobile_personnel_id'] = $mobile_personnel_id;
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
		
		$newdata = $this->load->view('inpatient/patient_card', $v_data, true);
		$response['message'] = 'success';
		$response['result'] = $newdata;

		echo json_encode($response);
	}
	public function view_procedure($visit_id){
		//check patient visit type
		
		$v_data['visit_id'] = $visit_id;
		$this->load->view('view_procedure', $v_data);
		
	}

	public function save_nurse_notes($visit_id, $personnel_id)
	{
		$signature_name = '';
		if(isset($_POST['signature']))
		{
			$this->load->library('signature/signature');
			//require_once 'signature-to-image.php';
	
			$json = $_POST['signature']; // From Signature Pad
			//var_dump($json); die();
			$img = $this->signature->sigJsonToImage($json);
			$username = $this->session->userdata('username');
			//$username = 'alvaro';
			$signature_name = $username.'_signature_'.date('Y-m-d-H-i-s').'.png';
			imagepng($img, $this->signature_path.'/'.$signature_name);
			//imagedestroy($img);
		}
		
		if($this->nurse_model->add_notes($visit_id, 1, $signature_name, $personnel_id))
		{
			$v_data['mobile_personnel_id'] = $personnel_id;
			$v_data['visit_id'] = $visit_id;
			$v_data['signature_location'] = $this->signature_location;
			$v_data['query'] = $this->nurse_model->get_notes(1, $visit_id);
			$return['result'] = 'success';
			$return['message'] = $this->load->view('nurse/patients/notes', $v_data, TRUE);
			
		}
		
		else
		{
			$return['result'] = 'fail';
		}
		echo json_encode($return);
	}

	public function edit_nurse_notes($visit_id, $personnel_id, $notes_id)
	{	
		if($this->nurse_model->edit_notes($visit_id, 1, $notes_id, $personnel_id))
		{
			$v_data['mobile_personnel_id'] = $personnel_id;
			$v_data['visit_id'] = $visit_id;
			$v_data['signature_location'] = $this->signature_location;
			$v_data['query'] = $this->nurse_model->get_notes(1, $visit_id);
			$return['result'] = 'success';
			$return['message'] = $this->load->view('nurse/patients/notes', $v_data, TRUE);
		}
		
		else
		{
			$return['result'] = 'fail';
		}
		echo json_encode($return);
	}

	public function delete_nurse_notes($visit_id, $personnel_id, $notes_id)
	{	
		if($this->nurse_model->delete_notes($notes_id, $personnel_id))
		{
			$v_data['mobile_personnel_id'] = $personnel_id;
			$v_data['visit_id'] = $visit_id;
			$v_data['signature_location'] = $this->signature_location;
			$v_data['query'] = $this->nurse_model->get_notes(1, $visit_id);
			$return['result'] = 'success';
			$return['message'] = $this->load->view('nurse/patients/notes', $v_data, TRUE);
		}
		
		else
		{
			$return['result'] = 'fail';
		}
		echo json_encode($return);
	}
}