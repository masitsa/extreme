<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ultrasound  extends MX_Controller
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
		$this->load->model('ultrasound_model');
		$this->load->model('reception/reception_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('nurse/nurse_model');

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
		
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 4 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit_department, visit, patients';
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, 6, 0);
		$v_data['query'] = $query;
		$v_data['page'] = 0;
		$v_data['visit'] = 4;
		$v_data['department'] = 4;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('dashboard', $v_data, TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'ultrasound_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function ultrasound_queue($page_name = 12)
	{
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 11 AND visit_department.accounts = 1 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit_type.visit_type_id = visit.visit_type AND visit.visit_date = \''.date('Y-m-d').'\' AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND visit_department.department_id = service.department_id';
		
		$table = 'visit_department, visit, patients, visit_type, service';
		
		$visit_search = $this->session->userdata('patient_visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'radiology/ultrasound/ultrasound_queue/'.$page_name;
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
		
		$data['title'] = $v_data['title'] = 'Ultrasound Queue';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('ultrasound_queue', $v_data, true);
		
		
		$data['sidebar'] = 'ultrasound_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	public function queue_cheker($page_name = NULL)
	{
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 4 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND (visit.close_card = 0 OR visit.close_card = 7) AND visit.visit_date = \''.date('Y-m-d').'\'';
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
	public function test($visit_id)
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
		$v_data['visit'] = 1;
		
		$data['content'] = $this->load->view('tests_ultrasound/test', $v_data, true);
		$data['title'] = 'Ultrasound';
		$this->load->view('admin/templates/general_page', $data);
	}
	public function search_ultrasounds($visit_id)
	{
		$this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$search = ' AND service_charge_name LIKE \'%'.$this->input->post('search_item').'%\'';
			$this->session->set_userdata('ultrasound_search', $search);
		}
		
		$this->ultrasound_list(0,$visit_id);
	}
	
	public function close_ultrasound_search($visit_id)
	{
		$this->session->unset_userdata('ultrasound_search');
		$this->ultrasound_list(0,$visit_id);
	}
	public function test1($visit_id)
	{
		$data = array('visit_id'=>$visit_id, 'ultrasound'=>1, 'coming_from'=>'Lab');
		$this->load->view('tests_ultrasound/test1',$data);
	}
	public function test2($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$this->load->view('tests_ultrasound/test2',$data);
	}
	public function ultrasound_list($ultrasound, $visit_id)
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
		
		//$where = 'service_charge.service_id = service.service_id AND service.branch_code = "'.$this->session->userdata('branch_code').'" AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND  service_charge.visit_type_id = '.$visit_t;
		$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND  service_charge.visit_type_id = '.$visit_t;
		$test_search = $this->session->userdata('ultrasound_search');
		
		if(!empty($test_search))
		{
			$where .= $test_search;
		}
		
		$table = '`service_charge`, service';
		$segment = 6;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'radiology/ultrasound/ultrasound_list/'.$ultrasound.'/'.$visit_id;
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
		$query = $this->ultrasound_model->get_ultrasounds($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = $v_data['title'] = 'X Ray List';
		
		$v_data['ultrasound'] = $ultrasound;
		$v_data['visit_id'] = $visit_id;
		$data['content'] = $this->load->view('ultrasound_list', $v_data, true);
		
		$data['title'] = 'Laboratory Test List';
		$this->load->view('admin/templates/no_sidebar', $data);
	}
	
	public function delete_cost($visit_charge_id, $visit_id)
	{
		$this->ultrasound_model->delete_visit_ultrasound($visit_charge_id);
		
		$this->test_ultrasound($visit_id);
	}
	public function remove_cost($visit_charge_id, $visit_id)
	{
		$this->ultrasound_model->delete_cost($visit_charge_id);

		redirect("ultrasound/test/".$visit_id);
	}
	public function add_ultrasound_cost($service_charge_id,$visit_id)
	{
		if($this->ultrasound_model->save_ultrasound_visit($visit_id,$service_charge_id))
		{
			redirect("ultrasound/test/".$visit_id);
		}
		else
		{
			redirect("ultrasound/test/".$visit_id);
		}
	}

	public function test_ultrasound($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('test_ultrasound', $data);
	}
	public function remove_ultrasound($service_charge_id,$visit_id)
	{
		$this->ultrasound_model->delete_visit_ultrasound($service_charge_id,$visit_id);
		redirect("ultrasound/test/".$visit_id);
	}

	public function confirm_ultrasound_charge($visit_id, $service_charge_id=NULL){
		$data = array('service_charge_id' => $service_charge_id, 'visit_id' => $visit_id);
		$this->load->view('confirm_test_ultrasound', $data);
	}
	
	public function finish_ultrasound($visit_id){
		redirect('ultrasound/ultrasound_queue');
	}

	public function save_comment($comment,$id){
		$comment = str_replace('%20', ' ',$comment);
		$this->ultrasound_model->save_comment($comment, $id);
	}

	public function send_to_doctor($visit_id)
	{
		if($this->reception_model->set_visit_department($visit_id, 2))
		{
			redirect('ultrasound/ultrasound_queue');
		}
		else
		{
			FALSE;
		}
	}
	public function send_to_accounts($visit_id,$module= NULL)
	{
		redirect("nurse/send_to_accounts/".$visit_id."/4");
	}
	public function test_history($visit_id,$page_name = NULL)
	{
		// this is it
		$where = 'visit.patient_id = patients.patient_id AND visit.patient_id = (SELECT patient_id FROM visit WHERE visit.visit_id = visit_department.visit_id ) AND visit_department.department_id = 4 AND visit_department.visit_id != '.$visit_id.'  AND visit.visit_id = '.$visit_id.' ';
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
		$table = 'visit_department,visit, patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'ultrasound/test_history/'.$page_name;
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
		$query = $this->ultrasound_model->get_all_ongoing_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Test History';
		$v_data['title'] = 'Test History';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('test_history', $v_data, true);
		
		
		$data['sidebar'] = 'ultrasound_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	}
	
	public function print_ultrasound($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$data['contacts'] = $this->site_model->get_contacts();
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['patient'] = $patient;
		$this->load->view('print_ultrasound', $data);
	}

	function print_test_old($visit_id, $patient_id)
	{
		$this->load->library('fpdf');
		$this->fpdf->AliasNbPages();
		$this->fpdf->AddPage();
		$this->fpdf->setFont('Times', '', 10);
		$this->fpdf->SetFillColor(190, 186, 211);
		
		$ultrasound_rs = $this->ultrasound_model->get_ultrasound_visit($visit_id);
		$num_ultrasound_visit = count($ultrasound_rs);

		$rs2 = $this->ultrasound_model->get_comment($visit_id);
		$num_rows2 = count($rs2);

		if($num_rows2 > 0){
			foreach ($rs2 as $key2):
				$comment = $key2->ultrasound_visit_comment;
				$visit_date = $key2->visit_date;
				$this->session->set_userdata('visit_date',$visit_date);
			endforeach;
			
		}

		$s = 0;
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$visit_type = $patient['visit_type'];
		$patient_type = $patient['patient_type'];
		$patient_othernames = $patient['patient_othernames'];
		$patient_surname = $patient['patient_surname'];
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$patient_number = $patient['patient_number'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$gender = $patient['gender'];
		$contacts = $this->site_model->get_contacts();
		
		$lineBreak = 10;
		
		//Colors of frames, background and Text
		$this->fpdf->SetDrawColor(41, 22, 111);
		$this->fpdf->SetFillColor(190, 186, 211);
		$this->fpdf->SetTextColor(41, 22, 111);

		//thickness of frame (mm)
		//$this->fpdf->SetLineWidth(1);
		//Logo
		$this->fpdf->Image(base_url().'assets/logo/'.$contacts['logo'],90,3,0,30);
		//font
		$this->fpdf->SetFont('Arial', 'B', 12);
		//title

		$this->fpdf->Ln(25);
		$this->fpdf->Cell(0,5, 'DIAGNOSTIC LABORATORY SERVICES', 0, 1, 'C');
		$this->fpdf->Cell(0, 5, 'Laboratory Report Form', 0, 1, 'C');
		$this->fpdf->Cell(0, 5, 'Date: '.$visit_date, '0', 1, 'C');

		$this->fpdf->Cell(100,5,'Name:	'.$patient_surname." ".$patient_othernames, 0, 0, 'L');
		$this->fpdf->Cell(50,5,'Age:'.$age, 0, 0, 'L');
		
		$this->session->set_userdata('patient_sex',$gender);
		$this->fpdf->Cell(50,5,'Sex:'.$gender, 0, 1, 'L');
		//$this->fpdf->Cell(-30);//move left
		$this->fpdf->Cell(0,7,'Clinic Number:'.$patient_number, 'B', 1, 'L');
		//line break
		$pageH = 7;
		$this->fpdf->SetTextColor(0, 0, 0);
		$this->fpdf->SetDrawColor(0, 0, 0);
		$this->fpdf->SetFont('Times','B',10);
			
		$this->fpdf->SetDrawColor(41, 22, 111);
		$personnel_id = $this->session->userdata('personnel_id');
	
		$rs2 = $this->ultrasound_model->get_ultrasound_personnel($personnel_id);
		$num_rows = count($rs2);

		if($num_rows > 0){
			foreach($rs2 as $key):
				$personnel = $key->personnel_surname;
				$personnel = $personnel." ".$key->personnel_fname;
			endforeach;
			
		}
		
		else{
			$personnel = "";
		}

		//HEADER
		$billTotal = 0;
		$linespacing = 2;
		$majorSpacing = 7;
		$pageH = 5;
		$counter = 0;
		 $next_name ="";  $test_format=""; $ultrasound_name=""; $fill="";
		if($num_ultrasound_visit > 0){
			foreach ($ultrasound_rs as $key_ultrasound){
				$visit_charge_id = $key_ultrasound->visit_ultrasound_id;
				
				$rsy2 = $this->ultrasound_model->get_test_comment($visit_charge_id);
				$num_rowsy2 = count($rsy2);
				
				if($num_rowsy2 >0){
					$comment4= $rsy2[0]->ultrasound_visit_format_comments;
				}
				else {
				
					$comment4="";	
				}
				$format_rs = $this->ultrasound_model->get_ultrasound_visit_result($visit_charge_id);
				$num_format = count($format_rs);
				
				if($num_format > 0){
					$rs = $this->ultrasound_model->get_test($visit_charge_id);
					$num_ultrasound = count($rs);
				}
				
				else{
					$rs = $this->ultrasound_model->get_m_test($visit_charge_id);
					$num_ultrasound = count($rs);
				}
				
				if($num_ultrasound > 0){
					$counts =0;
					foreach ($rs as $key_what){
						$counts++;
						$ultrasound_name = $key_what->ultrasound_name;
						$ultrasound_class_name = $key_what->ultrasound_class_name;
						$ultrasound_units = $key_what->ultrasound_units;
						$ultrasound_lower_limit = $key_what->ultrasound_malelowerlimit;
						$ultrasound_upper_limit = $key_what->ultrasound_malelupperlimit;
						$ultrasound_lower_limit1 = $key_what->ultrasound_femalelowerlimit;
						$ultrasound_upper_limit1 = $key_what->ultrasound_femaleupperlimit;
						$visit_charge_id = $key_what->ultrasound_visit_id;
						$ultrasound_results = $key_what->ultrasound_visit_result;
						
						//results for formats
						if($this->session->userdata('test') ==0){
						
							$test_format = $key_what->ultrasound_formatname;
							$ultrasound_format_id = $key_what->ultrasound_format_id;
							$ultrasound_results = $key_what->ultrasound_visit_results_result;
							$ultrasound_units = $key_what->ultrasound_format_units;
							$ultrasound_lower_limit = $key_what->ultrasound_format_malelowerlimit;
							$ultrasound_upper_limit = $key_what->ultrasound_format_maleupperlimit;
							$ultrasound_lower_limit1 = $key_what->ultrasound_format_femalelowerlimit;
							$ultrasound_upper_limit1 = $key_what->ultrasound_format_femaleupperlimit;
						}
						
						//if there are no formats
						else{
							$test_format ="-";
						}
						
						if(($counter % 2) == 0){
							$fill = TRUE;
						}
						
						else{
							$fill = FALSE;
						}
						
						if ($counts < ($num_ultrasound-1)){
							$next_name = $rs[$counts]->ultrasound_name;
						}
						
						if(($ultrasound_name <> $next_name) || ($counts == 1))
						{
							$this->fpdf->Ln(5);
							
							$this->fpdf->SetFont('Times', 'B', 10);
							$this->fpdf->Cell(50,$pageH,"TEST: ".$ultrasound_name, 'B',1,'L', FALSE);
							$this->fpdf->Cell(50,$pageH,"CLASS: ".$ultrasound_class_name, 'B',1,'L', FALSE);
							
							$this->fpdf->Ln(2);
							
							$this->fpdf->Cell(50,$pageH,"Sub Test", 1,0,'L', FALSE);
							$this->fpdf->Cell(50,$pageH,"Results",1,0,'L', FALSE);
							$this->fpdf->Cell(50,$pageH,"Units",1,0,'L', FALSE);
							$this->fpdf->Cell(30,$pageH,"Normal Limits",1,1,'L', FALSE);
							$this->fpdf->SetFont('Times', '', 10);
						}
						
						$this->fpdf->Cell(50,$pageH,$test_format, 1,0,'L', $fill);
						$this->fpdf->Cell(50,$pageH,$ultrasound_results,1,0,'L', $fill);
						$this->fpdf->Cell(50,$pageH,$ultrasound_units,1,0,'L', $fill);
						
						if($this->session->userdata('patient_sex') == "Male"){
							$this->fpdf->Cell(30,$pageH,$ultrasound_lower_limit." - ".$ultrasound_upper_limit,1,1,'L', $fill);
						}
						
						else{
							$this->fpdf->Cell(30,$pageH,$ultrasound_lower_limit1." - ".$ultrasound_upper_limit1,1,1,'L', $fill);
						}
						$counter++;
					}
			
				if($test_format !="-"){ 
					$this->fpdf->Ln(3);
					$this->fpdf->SetFont('Times', 'B', 10);
					$this->fpdf->Cell(0,10,"".$ultrasound_name ."  Comment ",'B',1,'L', $fill);
					$this->fpdf->SetFont('Times', '', 10);
					$this->fpdf->Cell(0,10,$comment4,0,1,'L', $fill=true);
				
				}	
			}}
				
				if(($counter % 2) == 0){
					$fill = TRUE;
				}
				
				else{
					$fill = FALSE;
				}
				
				$this->fpdf->Ln(5);
				$this->fpdf->SetFont('Times', 'B', 10);
				$this->fpdf->Cell(0,10,"Comments ",'B',1,'L', $fill);
				$this->fpdf->SetFont('Times', '', 10);
				$this->fpdf->Cell(0,10,$comment,0,1,'L', $fill);
		}
		$this->fpdf->Output();

	}
	
	public function save_result()
	{
		$visit_charge_id = $this->input->post('visit_charge_id');
		if($this->ultrasound_model->save_tests_format2($visit_charge_id))
		{
			echo 'true';
		}
		
		else
		{
			echo 'false';
		}
	}
	
	public function save_ultrasound_comment()
	{
		$visit_charge_id = $this->input->post('visit_charge_id');
		$query = $this->ultrasound_model->get_ultrasound_comments($visit_charge_id);
		$num_rows = $query->num_rows();
		
		
		if ($num_rows == 0)
		{
			$this->ultrasound_model->save_new_ultrasound_comment();
		}
			
		else
		{
			$this->ultrasound_model->update_existing_ultrasound_comment($visit_charge_id);
		} 
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
		
		$this->ultrasound_queue();
		
		
	}
	public function close_queue_search()
	{
		$this->session->unset_userdata('patient_visit_search');
		$this->ultrasound_queue();
	}
}
?>