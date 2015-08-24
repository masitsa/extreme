<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pharmacy  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('pharmacy_model');
		$this->load->model('reception/reception_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('accounts/accounts_model');

		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('administration/personnel_model');
	}
	
	public function index()
	{
		$this->session->unset_userdata('visit_search');
		$this->session->unset_userdata('patient_search');
		
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 5 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit_department, visit, patients';
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, 6, 0);
		$v_data['query'] = $query;
		$v_data['page'] = 0;
		$v_data['visit'] = 5;
		$v_data['department'] = 5;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('laboratory/dashboard', $v_data, TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}

	public function prescription($visit_id,$service_charge_id=NULL,$module=NULL,$prescription_id=NULL)
	{
		//$this->form_validation->set_rules('substitution', 'Substitution', 'xss_clean');
		// $this->form_validation->set_rules('prescription_finishdate', 'Finish Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('x', 'Times Per Day', 'trim|required|xss_clean');
		$this->form_validation->set_rules('number_of_days', 'Number of Day', 'required|xss_clean');
		//$this->form_validation->set_rules('visit_charge_id', 'Cost', 'trim|required|xss_clean');
		$this->form_validation->set_rules('duration', 'Duration', 'trim|required|xss_clean');
		$this->form_validation->set_rules('consumption', 'Consumption', 'trim|required|xss_clean');
		$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|xss_clean');
		$this->form_validation->set_rules('service_charge_id', 'Drug', 'trim|required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$this->pharmacy_model->save_prescription($visit_id,$module);
			if($module == 1){
				redirect('pharmacy/prescription1/'.$visit_id."/1");
			}else{
				redirect('pharmacy/prescription/'.$visit_id);
			}
			
		}
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$visit_type = $patient['visit_type'];
		$patient_type = $patient['patient_type'];
		$patient_othernames = $patient['patient_othernames'];
		$patient_surname = $patient['patient_surname'];
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$visit_date = $this->reception_model->get_visit_date($visit_id);
		$gender = $patient['gender'];
		$visit_date = date('jS M Y',strtotime($visit_date));
		
		$patient_data = 'Visit Date: <span style="font-weight: normal;"> '.$visit_date.' </span> Surname: <span style="font-weight: normal;">'.$patient_surname.'</span> Othernames: <span style="font-weight: normal;">'.$patient_othernames.'</span> Age: <span style="font-weight: normal;">'.$age.'</span> Gender: <span style="font-weight: normal;">'.$gender.'</span> Patient Type: <span style="font-weight: normal;">'.$visit_type.'</span>';
		
		$v_data = array('visit_id'=>$visit_id,'service_charge_id'=>$service_charge_id,'prescription_id'=>$prescription_id,'module'=>$module,'patient'=>$patient_data);
		$data['content'] = $this->load->view('prescription', $v_data, true);
		
		if($module == 1){
			$data['title'] = 'Prescription';
			$data['sidebar'] = 'pharmacy_sidebar';
			$this->load->view('admin/templates/general_page', $data);

		}else{
			$data['title'] = 'Pharmacy medicine ';
			$this->load->view('admin/templates/no_sidebar', $data);	
		}
		
	}
	
	public function update_prescription($visit_id, $visit_charge_id, $prescription_id,$module = NULL){
		$this->form_validation->set_rules('substitution'.$prescription_id, 'Substitution', 'trim|required|xss_clean');
		$this->form_validation->set_rules('x'.$prescription_id, 'Times Per Day', 'trim|required|xss_clean');
		$this->form_validation->set_rules('duration'.$prescription_id, 'Duration', 'trim|required|xss_clean');
		$this->form_validation->set_rules('consumption'.$prescription_id, 'Consumption', 'trim|required|xss_clean');
		$this->form_validation->set_rules('quantity'.$prescription_id, 'Quantity', 'trim|required|xss_clean');

		if($module == 1)
		{
			$this->form_validation->set_rules('units_given'.$prescription_id, 'Units Given', 'trim|required|xss_clean');	
		}
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('error_message', validation_errors());
		}

		else
		{
			if($this->pharmacy_model->update_prescription($visit_id, $visit_charge_id, $prescription_id))
			{
				$this->session->set_userdata('success_message', 'Prescription updated successfully');
			}

			else
			{
				$this->session->set_userdata('error_message', 'Could not update the prescription. Please try again');
			}
		
		}
		if($module == 1){
			redirect('pharmacy/prescription1/'.$visit_id.'/1');
			
		}else{
			redirect('pharmacy/prescription/'.$visit_id);
		}
	}
	public function dispense_prescription($visit_id, $visit_charge_id, $prescription_id,$module = NULL){
		$this->form_validation->set_rules('substitution'.$prescription_id, 'Substitution', 'trim|required|xss_clean');
		$this->form_validation->set_rules('x'.$prescription_id, 'Times Per Day', 'trim|required|xss_clean');
		$this->form_validation->set_rules('duration'.$prescription_id, 'Duration', 'trim|required|xss_clean');
		$this->form_validation->set_rules('consumption'.$prescription_id, 'Consumption', 'trim|required|xss_clean');
		$this->form_validation->set_rules('quantity'.$prescription_id, 'Quantity', 'trim|required|xss_clean');

		if($module == 1)
		{
			$this->form_validation->set_rules('units_given'.$prescription_id, 'Units Given', 'trim|required|xss_clean');	
		}
		
		//if form conatins invalid data
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('error_message', validation_errors());
		}

		else
		{
			if($this->pharmacy_model->dispense_drug($visit_id, $visit_charge_id, $prescription_id))
			{
				$this->session->set_userdata('success_message', 'Prescription updated successfully');
			}

			else
			{
				$this->session->set_userdata('error_message', 'Could not update the prescription. Please try again');
			}
		
		}
		if($module == 1){
			redirect('pharmacy/prescription1/'.$visit_id.'/1');
			
		}else{
			redirect('pharmacy/prescription/'.$visit_id);
		}
	}
	public function search_drugs($visit_id)
	{
		// $this->form_validation->set_rules('search_item', 'Search', 'trim|required|xss_clean');

		
		//if form conatins invalid data
		// if ($this->form_validation->run())
		// {
			 $search_item = $this->input->post('search_item');
			 $generic_name = $this->input->post('generic_name');
			if(!empty($search_item))
			{
				$search_item = ' AND drugs.drugs_name LIKE \'%'.$this->input->post('search_item').'%\'';
			}
			if(!empty($generic_name))
			{
				$generic_name = ' AND generic.generic_name LIKE \'%'.$this->input->post('generic_name').'%\'';
			}
			

			
			$search_items = $search_item.$generic_name;
			$this->session->set_userdata('drugs_search', $search_items);
		// }
		
		$this->drugs($visit_id,0);
	}
	
	public function close_drugs_search($visit_id)
	{
		$this->session->unset_userdata('drugs_search');
		$this->drugs($visit_id,0);
	}
	
	public function drugs($visit_id,$module= NULL){

		//check patient visit type
		$rs = $this->nurse_model->check_visit_type($visit_id);
		if(count($rs)>0){
		  foreach ($rs as $rs1) {
			# code...
			  $visit_t = $rs1->visit_type;
		  }
		}


		
		$order = 'drugs.drugs_id';
		
		if(($visit_t =1) || ($visit_t = 2)){
				$where = 'drugs.drugs_id = service_charge.drug_id AND drugs.generic_id = generic.generic_id AND drugs.brand_id = brand.brand_id AND class.class_id  = drugs.class_id AND service_charge.visit_type_id = 0';
		}else if($visit_t == 4){
				$where = 'drugs.drugs_id = service_charge.drug_id AND drugs.generic_id = generic.generic_id AND drugs.brand_id = brand.brand_id AND class.class_id  = drugs.class_id AND service_charge.visit_type_id = 4 ';
		}else{
				$where = 'drugs.drugs_id = service_charge.drug_id AND drugs.generic_id = generic.generic_id AND drugs.brand_id = brand.brand_id AND class.class_id  = drugs.class_id AND service_charge.visit_type_id = 0';
		 }
		
		$drugs_search = $this->session->userdata('drugs_search');
		
		if(!empty($drugs_search))
		{
			$where .= $drugs_search;
		}
		if($module != 1)
		{
			$segment = 4;
		}
		else
		{
			$segment = 5;
		}
		$table = 'drugs, service_charge, generic, brand, class';
		//pagination
		$this->load->library('pagination');
		if($module != 1)
		{
			$config['base_url'] = site_url().'/pharmacy/drugs/'.$visit_id;
		}
		else
		{
			$config['base_url'] = site_url().'/pharmacy/drugs/'.$visit_id.'/'.$module;
		}
		
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->pharmacy_model->get_drugs($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Drugs List';
		$v_data['title'] = 'Drugs List';
		
		$v_data['visit_id'] = $visit_id;
		$v_data['module'] = $module;
		$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();

		$data['content'] = $this->load->view('drugs', $v_data, true);
		
		$data['title'] = 'Drugs List';
		$this->load->view('admin/templates/no_sidebar', $data);
	}
	public function display_prescription($visit_id){
		$visit_data = array('visit_id'=>$visit_id);
		$this->load->view('display_prescription',$visit_data);
	}
	public function pharmacy_queue($page_name = NULL)
	{
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 5 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit.visit_date = \''.date('Y-m-d').'\'';
		$table = 'visit_department, visit, patients';
		$visit_search = $this->session->userdata('patient_visit_search');
		
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
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/pharmacy_queue/'.$page_name;
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
		
		$data['title'] = 'Pharmacy Queue';
		$v_data['title'] = 'Pharmacy Queue';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('pharmacy_queue', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	public function queue_cheker($page_name = NULL)
	{
		$where = 'visit_department.visit_id = visit.visit_id AND visit_department.department_id = 5 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit.visit_date = \''.date('Y-m-d').'\'';
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
	public function prescription1($visit_id,$module=NULL)
	{	
		$v_data['visit_id'] = $visit_id;
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$visit_type = $patient['visit_type'];
		$patient_type = $patient['patient_type'];
		$patient_othernames = $patient['patient_othernames'];
		$patient_surname = $patient['patient_surname'];
		$patient_date_of_birth = $patient['patient_date_of_birth'];
		$age = $this->reception_model->calculate_age($patient_date_of_birth);
		$gender = $patient['gender'];
		
		$v_data['patient'] = 'Surname: <span style="font-weight: normal;">'.$patient_surname.'</span> Othernames: <span style="font-weight: normal;">'.$patient_othernames.'</span> Age: <span style="font-weight: normal;">'.$age.'</span> Gender: <span style="font-weight: normal;">'.$gender.'</span> Patient Type: <span style="font-weight: normal;">'.$visit_type.'</span>';
		
		$v_data['module'] = $module;
		
		$data['content'] = $this->load->view('prescription', $v_data, TRUE);
		
		$data['title'] = 'Prescription';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	public function send_to_accounts($primary_key)
	{
		redirect('nurse/send_to_accounts/'.$primary_key.'/3');
	}
	public function delete_prescription($prescription_id,$visit_id,$visit_charge_id,$module)
	{
		//  delete the visit charge

		$this->db->where(array("visit_charge_id"=>$visit_charge_id));
		$this->db->delete('visit_charge');
		
		//  check if the visit charge has been deleted

		$rs = $this->pharmacy_model->check_deleted_visitcharge($visit_charge_id);
		$num_rows =count($rs);

		//echo BB.$visit_charge_id;
		if($num_rows==0){
			$this->db->where(array("prescription_id"=>$prescription_id));
			$this->db->delete('pres');
		}
		if($module == 1)
		{
			redirect('pharmacy/prescription1/'.$visit_id."/1");
		}
		else
		{
			redirect('pharmacy/prescription/'.$visit_id);
		}
	}
	public function prescription_history($visit_id,$page_name = NULL)
	{
		// this is it
		$where = 'visit.patient_id = patients.patient_id AND visit.patient_id = (SELECT patient_id FROM visit WHERE visit.visit_id = visit_department.visit_id ) AND visit_department.department_id = 5 AND visit_department.visit_id != '.$visit_id.'  AND visit.visit_id = '.$visit_id.' ';
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
		$config['base_url'] = site_url().'/pharmacy/prescription_history/'.$page_name;
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
         $v_data["visit_id"] = $visit_id;
		$query = $this->pharmacy_model->get_all_previous_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Prescription History';
		$v_data['title'] = 'Prescription History';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('prescription_history', $v_data, true);
		$data['sidebar'] = 'pharmacy_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	}
	
	public function inventory()
	{
		$segment = 3;
		$order = 'drugs.drugs_name';
		//$where = 'drugs.brand_id = brand.brand_id AND class.class_id = drugs.class_id AND drugs.generic_id = generic.generic_id AND drugs.drug_type_id = drug_type.drug_type_id AND drugs.drug_administration_route_id = drug_administration_route.drug_administration_route_id AND drugs.drug_dose_unit_id = drug_dose_unit.drug_dose_unit_id AND drugs.drug_consumption_id = drug_consumption.drug_consumption_id';
		
		$where = 'drugs.brand_id = brand.brand_id AND class.class_id = drugs.class_id AND drugs.generic_id = generic.generic_id AND drugs.drug_type_id = drug_type.drug_type_id AND drugs.drug_administration_route_id = drug_administration_route.drug_administration_route_id AND drugs.drug_dose_unit_id = drug_dose_unit.drug_dose_unit_id AND drugs.drug_consumption_id = drug_consumption.drug_consumption_id';
		$drugs_inventory_search = $this->session->userdata('drugs_inventory_search');
		
		if(!empty($drugs_inventory_search))
		{
			$where .= $drugs_inventory_search;
		}
		
		$table = 'drugs, drug_type, generic, brand, class, drug_administration_route, drug_dose_unit, drug_consumption';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/inventory';
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
		$query = $this->pharmacy_model->get_drugs_list($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Drugs List';
		$v_data['title'] = 'Drugs List';
		$data['sidebar'] = 'pharmacy_sidebar';
		$data['content'] = $this->load->view('drugs_list', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*
	*	Add Drug
	*
	*/
	public function add_drug()
	{
		//form validation rules
		$this->form_validation->set_rules('drugs_name', 'Drug Name', 'required|xss_clean');
		$this->form_validation->set_rules('drugs_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('drugs_unitprice', 'Unit Price', 'numeric|xss_clean');
		$this->form_validation->set_rules('batch_no', 'Batch Number', 'numeric|xss_clean');
		$this->form_validation->set_rules('brand_id', 'Brand', 'numeric|xss_clean');
		$this->form_validation->set_rules('generic_id', 'Generic', 'numeric|xss_clean');
		$this->form_validation->set_rules('class_id', 'Class', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_type_id', 'Type', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_dose', 'Dose', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_dose_unit_id', 'Dose Unit', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_administration_route_id', 'Administration Route', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('drug_consumption_id', 'Consumption Method', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->save_drug())
			{
				$this->session->userdata('success_message', 'Drug has been added successfully');
				redirect('pharmacy/inventory');
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
		$data['title'] = 'Add Drug';
		$v_data['title'] = 'Add Drug';
		$data['sidebar'] = 'pharmacy_sidebar';
		$v_data['drug_types'] = $this->pharmacy_model->get_drug_forms();
		$v_data['drug_brands'] = $this->pharmacy_model->get_drug_brands();
		$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();
		$v_data['drug_generics'] = $this->pharmacy_model->get_drug_generics();
		$v_data['drug_dose_units'] = $this->pharmacy_model->get_drug_dose_units();
		$v_data['admin_routes'] = $this->pharmacy_model->get_admin_route();
		$v_data['consumption'] = $this->pharmacy_model->get_consumption();
		$data['content'] = $this->load->view('add_drug', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*
	*	Edit Drug
	*
	*/
	public function edit_drug($drugs_id)
	{
		//form validation rules
		$this->form_validation->set_rules('drugs_name', 'Drug Name', 'required|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('drugs_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('drugs_unitprice', 'Unit Price', 'numeric|xss_clean');
		$this->form_validation->set_rules('batch_no', 'Batch Number', 'numeric|xss_clean');
		$this->form_validation->set_rules('brand_id', 'Brand', 'numeric|xss_clean');
		$this->form_validation->set_rules('generic_id', 'Generic', 'numeric|xss_clean');
		$this->form_validation->set_rules('class_id', 'Class', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_type_id', 'Type', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_dose', 'Dose', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_dose_unit_id', 'Dose Unit', 'numeric|xss_clean');
		$this->form_validation->set_rules('drug_administration_route_id', 'Administration Route', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('drug_consumption_id', 'Consumption Method', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->edit_drug($drugs_id))
			{
				$this->session->userdata('success_message', 'Drug has been editted successfully');
				redirect('pharmacy/inventory');
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
		$data['title'] = 'Edit Drug';
		$v_data['title'] = 'Edit Drug';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		
		if($drug_details->num_rows() > 0)
		{
			$v_data['drug_details'] = $drug_details->row();
			$v_data['drug_types'] = $this->pharmacy_model->get_drug_forms();
			$v_data['drug_brands'] = $this->pharmacy_model->get_drug_brands();
			$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();
			$v_data['drug_generics'] = $this->pharmacy_model->get_drug_generics();
			$v_data['drug_dose_units'] = $this->pharmacy_model->get_drug_dose_units();
			$v_data['admin_routes'] = $this->pharmacy_model->get_admin_route();
			$v_data['consumption'] = $this->pharmacy_model->get_consumption();
			$v_data['drugs_id'] = $drugs_id;
			$data['content'] = $this->load->view('edit_drug', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function purchase_drug($drugs_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->purchase_drug($drugs_id))
			{
				$this->session->userdata('success_message', 'Drug has been purchased successfully');
				redirect('pharmacy/drug_purchases/'.$drugs_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Buy Drug';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = 'Buy '.$row->drugs_name;
			$v_data['drugs_id'] = $drugs_id;
			$v_data['container_types'] = $this->pharmacy_model->get_container_types();
			$data['content'] = $this->load->view('buy_drug', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function edit_drug_purchase($purchase_id, $drugs_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->edit_drug_purchase($purchase_id))
			{
				$this->session->userdata('success_message', 'Drug has been purchased successfully');
				redirect('pharmacy/drug_purchases/'.$drugs_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Purchase';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		$purchase_details = $this->pharmacy_model->get_purchase_details($purchase_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = 'Edit '.$row->drugs_name.' Purchase';
			$v_data['drugs_id'] = $drugs_id;
			$v_data['container_types'] = $this->pharmacy_model->get_container_types();
			$v_data['purchase_details'] = $purchase_details->row();
			$data['content'] = $this->load->view('edit_drug_purchase', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find purchase details';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function drug_purchases($drugs_id)
	{
		$segment = 4;
		$order = 'purchase_date';
		$where = 'purchase.container_type_id = container_type.container_type_id AND purchase.drugs_id = '.$drugs_id;
		
		$drugs_search = $this->session->userdata('drugs_purchases_search');
		
		if(!empty($drugs_search))
		{
			$where .= $drugs_search;
		}
		
		$table = 'purchase, container_type';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/drug_purchases/'.$drugs_id;
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
		$query = $this->pharmacy_model->get_drugs_purchases($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['drugs_id'] = $drugs_id;
		
		$data['title'] = 'Drugs List';
		$v_data['title'] = 'Drugs List';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = $row->drugs_name.' Purchases';
			$data['content'] = $this->load->view('drug_purchases', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function deduct_drug($drugs_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('stock_deduction_quantity', 'deduct Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('stock_deduction_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->deduct_drug($drugs_id))
			{
				$this->session->userdata('success_message', 'Drug has been deducted successfully');
				redirect('pharmacy/drug_deductions/'.$drugs_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to deduct drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Deduct Drug';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = 'Deduct '.$row->drugs_name;
			$v_data['drugs_id'] = $drugs_id;
			$v_data['container_types'] = $this->pharmacy_model->get_container_types();
			$data['content'] = $this->load->view('deduct_drug', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function edit_drug_deduction($stock_deduction_id, $drugs_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('stock_deduction_quantity', 'deduct Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('stock_deduction_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->pharmacy_model->edit_drug_deduction($stock_deduction_id))
			{
				$this->session->userdata('success_message', 'Drug has been deductd successfully');
				redirect('pharmacy/drug_deductions/'.$drugs_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to deduct drug. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Deduction';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		$deduction_details = $this->pharmacy_model->get_deduction_details($stock_deduction_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = 'Edit '.$row->drugs_name.' Purchase';
			$v_data['drugs_id'] = $drugs_id;
			$v_data['container_types'] = $this->pharmacy_model->get_container_types();
			$v_data['deduction_details'] = $deduction_details->row();
			$data['content'] = $this->load->view('edit_drug_deduction', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find deduction details';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function drug_deductions($drugs_id)
	{
		$segment = 4;
		$order = 'stock_deductions_date';
		$where = 'stock_deductions.container_type_id = container_type.container_type_id AND stock_deductions.drugs_id = '.$drugs_id;
		
		$drugs_search = $this->session->userdata('drugs_deductions_search');
		
		if(!empty($drugs_search))
		{
			$where .= $drugs_search;
		}
		
		$table = 'stock_deductions, container_type';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/drug_deductions/'.$drugs_id;
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
		$query = $this->pharmacy_model->get_drugs_deductions($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['drugs_id'] = $drugs_id;
		
		$data['title'] = 'Drugs List';
		$v_data['title'] = 'Drugs List';
		$data['sidebar'] = 'pharmacy_sidebar';
		$drug_details = $this->pharmacy_model->get_drug_details($drugs_id);
		
		if($drug_details->num_rows() > 0)
		{
			$row = $drug_details->row();
			$v_data['title'] = $row->drugs_name.' Deductions';
			$data['content'] = $this->load->view('drug_deductions', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find drug';
		}
		
		$this->load->view('admin/templates/general_page', $data);
				
    }
	
	public function brands()
	{
		// this is it
		$where = 'brand_id > 0';
		$brands_search = $this->session->userdata('brands_search');
		
		if(!empty($brands_search))
		{
			$where .= $brands_search;
		}
		
		$segment = 3;
		
		$table = 'brand';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/brands';
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
		$query = $this->pharmacy_model->get_all_drug_brands($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Drug Brands';
		$v_data['title'] = 'Drug Brands';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('setup/drug_brands', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	} 
	function add_brand($brand_id = NULL)
	{
		if($brand_id > 0)
		{
			$v_data['title'] = "Edit laboratory test";
			$v_data['brand_details'] = $this->pharmacy_model->get_brands_details($brand_id);
		}
		else
		{
			$v_data['title'] = "Add new brand";
			$v_data['brand_details'] = '';
		}
		
		//$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['brand_id'] = $brand_id;
		$data['content'] = $this->load->view('setup/add_brand', $v_data, true);
		
		$data['title'] = 'Add brand';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_brand()
	{
		$this->form_validation->set_rules('brand_name', 'Brand name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->add_brand();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the brand");
				redirect('pharmacy/add_brand');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_brand');	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the brand name then try again");
			redirect('pharmacy/add_brand');					
		}
	}
	public function search_brand()
	{
		$brand_name = $this->input->post('brand_name');
		
		if(!empty($brand_name))
		{
			$brand_name = ' AND brand_name LIKE \'%'.$brand_name.'%\' ';
		}
	
		
		
		$search = $brand_name;
		$this->session->set_userdata('brands_search', $search);
		
		$this->brands();
	}	
	public function close_brand_search()
	{
		$this->session->unset_userdata('brands_search');
		$this->brands();
	}
	public function close_inventory_search()
	{
		$this->session->unset_userdata('drugs_inventory_search');
		$this->inventory();
	}

	 function update_brand($brand_id)
    {
    	$this->form_validation->set_rules('brand_name', 'Brand name', 'is_numeric|xss_clean');
    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->edit_brand($brand_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('pharmacy/add_brand/'.$brand_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_brand/'.$brand_id);	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_brand/'.$brand_id);			
		}
    }
    public function generics()
	{
		// this is it
		$where = 'generic_id > 0';
		$generics_search = $this->session->userdata('generics_search');
		
		if(!empty($generics_search))
		{
			$where .= $generics_search;
		}
		
		$segment = 3;
		
		$table = 'generic';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/generics';
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
		$query = $this->pharmacy_model->get_all_drug_generics($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Generics';
		$v_data['title'] = 'Generics';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('setup/generics', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	} 
	function add_generic($generic_id = NULL)
	{
		if($generic_id > 0)
		{
			$v_data['title'] = "Edit laboratory test";
			$v_data['generic_details'] = $this->pharmacy_model->get_generics_details($generic_id);
		}
		else
		{
			$v_data['title'] = "Add new generic";
			$v_data['generic_details'] = '';
		}
		
		//$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['generic_id'] = $generic_id;
		$data['content'] = $this->load->view('setup/add_generic', $v_data, true);
		
		$data['title'] = 'Add generic';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_generic()
	{
		$this->form_validation->set_rules('generic_name', 'generic name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->add_generic();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the generic");
				redirect('pharmacy/add_generic');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_generic');	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the generic name then try again");
			redirect('pharmacy/add_generic');					
		}
	}
	public function search_generic()
	{
		$generic_name = $this->input->post('generic_name');
		
		if(!empty($generic_name))
		{
			$generic_name = ' AND generic_name LIKE \'%'.$generic_name.'%\' ';
		}
	
		
		
		$search = $generic_name;
		$this->session->set_userdata('generics_search', $search);
		
		$this->generics();
	}	
	public function close_generic_search()
	{
		$this->session->unset_userdata('generics_search');
		$this->generics();
	}
	 function update_generic($generic_id)
    {
    	$this->form_validation->set_rules('generic_name', 'generic name', 'is_numeric|xss_clean');
    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->edit_generic($generic_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('pharmacy/add_generic/'.$generic_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_generic/'.$generic_id);	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_generic/'.$generic_id);			
		}
    }
    public function classes()
	{
		// this is it
		$where = 'class_id > 0';
		$classes_search = $this->session->userdata('classes_search');
		
		if(!empty($classes_search))
		{
			$where .= $classes_search;
		}
		
		$segment = 3;
		
		$table = 'class';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/classes';
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
		$query = $this->pharmacy_model->get_all_drug_classes($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'classes';
		$v_data['title'] = 'classes';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('setup/classes', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	} 
	function add_class($class_id = NULL)
	{
		if($class_id > 0)
		{
			$v_data['title'] = "Edit laboratory test";
			$v_data['class_details'] = $this->pharmacy_model->get_classes_details($class_id);
		}
		else
		{
			$v_data['title'] = "Add new class";
			$v_data['class_details'] = '';
		}
		
		//$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['class_id'] = $class_id;
		$data['content'] = $this->load->view('setup/add_class', $v_data, true);
		
		$data['title'] = 'Add class';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_class()
	{
		$this->form_validation->set_rules('class_name', 'class name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->add_class();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the class");
				redirect('pharmacy/add_class');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_class');	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_class');					
		}
	}
	public function search_class()
	{
		$class_name = $this->input->post('class_name');
		
		if(!empty($class_name))
		{
			$class_name = ' AND class_name LIKE \'%'.$class_name.'%\' ';
		}
	
		
		
		$search = $class_name;
		$this->session->set_userdata('classes_search', $search);
		
		$this->classes();
	}	
	public function search_inventory_drugs()
	{
		$drug_name = $this->input->post('drug_name');
		
		if(!empty($drug_name))
		{
			$drug_name = ' AND drugs.drugs_name LIKE \''.$drug_name.'%\' ';
		}
	
		
		
		$search = $drug_name;
		$this->session->set_userdata('drugs_inventory_search', $search);
		
		$this->inventory();
	}	
	public function close_class_search()
	{
		$this->session->unset_userdata('classes_search');
		$this->classes();
	}
	 function update_class($class_id)
    {
    	$this->form_validation->set_rules('class_name', 'class name', 'is_numeric|xss_clean');
    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->edit_class($class_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('pharmacy/add_class/'.$class_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_class/'.$class_id);	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_class/'.$class_id);			
		}
    }
    public function types()
	{
		// this is it
		$where = 'drug_type_id > 0';
		$types_search = $this->session->userdata('types_search');
		
		if(!empty($types_search))
		{
			$where .= $types_search;
		}
		
		$segment = 3;
		
		$table = 'drug_type';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/types';
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
		$query = $this->pharmacy_model->get_all_drug_types($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Drug types';
		$v_data['title'] = 'Drug types';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('setup/drug_types', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	} 
	function add_type($drug_type_id = NULL)
	{
		if($drug_type_id > 0)
		{
			$v_data['title'] = "Edit laboratory test";
			$v_data['type_details'] = $this->pharmacy_model->get_types_details($drug_type_id);
		}
		else
		{
			$v_data['title'] = "Add new type";
			$v_data['type_details'] = '';
		}
		
		//$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['drug_type_id'] = $drug_type_id;
		$data['content'] = $this->load->view('setup/add_type', $v_data, true);
		
		$data['title'] = 'Add type';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_type()
	{
		$this->form_validation->set_rules('drug_type_name', 'type name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->add_type();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the type");
				redirect('pharmacy/add_type');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_type');	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the type name then try again");
			redirect('pharmacy/add_type');					
		}
	}
	public function search_type()
	{
		$drug_type_name = $this->input->post('drug_type_name');
		
		if(!empty($drug_type_name))
		{
			$drug_type_name = ' AND drug_type_name LIKE \'%'.$drug_type_name.'%\' ';
		}
	
		
		
		$search = $drug_type_name;
		$this->session->set_userdata('types_search', $search);
		
		$this->types();
	}	
	public function close_type_search()
	{
		$this->session->unset_userdata('types_search');
		$this->types();
	}
	 function update_type($drug_type_id)
    {
    	$this->form_validation->set_rules('drug_type_name', 'type name', 'is_numeric|xss_clean');
    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->edit_type($drug_type_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('pharmacy/add_type/'.$drug_type_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_type/'.$drug_type_id);	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_type/'.$drug_type_id);			
		}
    }
    public function containers()
	{
		// this is it
		$where = 'container_type_id > 0';
		$containers_search = $this->session->userdata('containers_search');
		
		if(!empty($containers_search))
		{
			$where .= $containers_search;
		}
		
		$segment = 3;
		
		$table = 'container_type';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/pharmacy/containers';
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
		$query = $this->pharmacy_model->get_all_drug_containers($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Drug containers';
		$v_data['title'] = 'Drug containers';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('setup/drug_containers', $v_data, true);
		
		
		$data['sidebar'] = 'pharmacy_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	} 
	function add_container_type($container_type_id = NULL)
	{
		if($container_type_id > 0)
		{
			$v_data['title'] = "Edit container types";
			$v_data['container_type_details'] = $this->pharmacy_model->get_containers_details($container_type_id);
		}
		else
		{
			$v_data['title'] = "Add new container type";
			$v_data['container_type_details'] = '';
		}
		
		//$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['container_type_id'] = $container_type_id;
		$data['content'] = $this->load->view('setup/add_container_type', $v_data, true);
		
		$data['title'] = 'Add type';
		$data['sidebar'] = 'pharmacy_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_container_type()
	{
		$this->form_validation->set_rules('container_type_name', 'type name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->add_container_type();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the type");
				redirect('pharmacy/add_container_type');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_container_type');	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the type name then try again");
			redirect('pharmacy/add_container_type');					
		}
	}
	public function search_container_type()
	{
		$container_type_name = $this->input->post('container_type_name');
		
		if(!empty($container_type_name))
		{
			$container_type_name = ' AND container_type_name LIKE \'%'.$container_type_name.'%\' ';
		}
	
		
		
		$search = $container_type_name;
		$this->session->set_userdata('containers_search', $search);
		
		$this->containers();
	}	
	public function close_container_type_search()
	{
		$this->session->unset_userdata('containers_search');
		$this->containers();
	}
	 function update_container_type($container_type_id)
    {
    	$this->form_validation->set_rules('container_type_name', 'type name', 'is_numeric|xss_clean');
    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->pharmacy_model->edit_container_type($container_type_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('pharmacy/add_container_type/'.$container_type_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				redirect('pharmacy/add_container_type/'.$container_type_id);	
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('pharmacy/add_container_type/'.$container_type_id);			
		}
    }
	
	public function activation($type, $page, $id)
    {
    	// the pages are test format, tests, classes
    	$date = date("Y-m-d");
    	
    	if($type == "deactivate")
    	{
    		$insert = array(
			"drugs_deleted" => 1,
			"deleted_by" => $this->session->userdata("personnel_id"),
			"deleted_on" => $date
			);
			$this->db->where('drugs_id', $id);
			$this->db->update('drugs', $insert);
			$this->session->set_userdata("success_message","You have successfully disabled the drug");
			redirect('pharmacy/inventory');	
    	}
    	else if($type == "activate")
    	{
    		$insert = array(
			"drugs_deleted" => 0,
			"deleted_by" => $this->session->userdata("personnel_id"),
			"deleted_on" => $date
			);
			$this->db->where('drugs_id', $id);
			$this->db->update('drugs', $insert);
			$this->session->set_userdata("success_message","You have successfully enabled the drug");
			redirect('pharmacy/inventory');	
    	}
    }
    public function search_visit_patients($module = NULL)
    	{
		$visit_type_id = $this->input->post('visit_type_id');
		$strath_no = $this->input->post('strath_no');
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND patients.visit_type_id = '.$visit_type_id.' ';
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
		
		$search = $visit_type_id.$strath_no.$surname.$other_name;
		$this->session->set_userdata('patient_visit_search', $search);
		
		$this->pharmacy_queue();
		
		
	}
	public function close_queue_search()
	{
		$this->session->unset_userdata('patient_visit_search');
		$this->pharmacy_queue();
	}
	
	public function sort_drugs()
	{
		//$this->db->where('drugs_id > 1341');
		$query = $this->db->get('drugs');
		
		foreach($query->result() as $res)
		{
			$drug_id = $res->drugs_id;
			$price = $res->drugs_unitprice;
			$drugs_name = $res->drugs_name;
			$markup = round(($price * 1.33), 0);
			$markdown = $markup;//round(($markup * 0.9), 0);
			
			$service_data = array(
				'drug_id'=>$drug_id,
				'service_charge_amount'=>$markdown,
				'service_charge_status'=>1,
				'service_id'=>4,
				'visit_type_id'=>0,
				'service_charge_name'=>$drugs_name,
			);
			
			//check if drug exists
			$where = array(
				'drug_id'=>$drug_id,
				'visit_type_id'=>0,
			);
			$this->db->where($where);
			$query2 = $this->db->get('service_charge');
			
			if($query2->num_rows() > 0)
			{
				$this->db->where($where);
				$this->db->update('service_charge', $service_data);
			}
			
			else
			{
				$this->db->insert('service_charge', $service_data);
			}
		}
	}
}
?>