<?php

class Pharmacy_model extends CI_Model 
{

	function get_drug($service_charge_id){
		
		
		$table = "drugs, service_charge";
		$where = "service_charge.drug_id = drugs.drugs_id AND service_charge.service_charge_id = ". $service_charge_id;
		$items = "service_charge.service_charge_name,drugs.drug_type_id, drugs.drug_size,drugs.drug_size_type,drugs.drug_administration_route_id, drugs.drugs_dose, drugs.drug_dose_unit_id";
		$order = "drugs.drugs_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_drug_type_name($id){
		
		$table = "drug_type";
		$where = "drug_type_id = ". $id;
		$items = "drug_type_name";
		$order = "drug_type_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

		
	}

	function get_dose_unit2($id){
		
		$table = "drug_dose_unit";
		$where = "drug_dose_unit_id = ". $id;
		$items = "drug_dose_unit_name";
		$order = "drug_dose_unit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

		
	}

	function get_admin_route2($id){
		
		$table = "drug_administration_route";
		$where = "drug_administration_route_id = ". $id;
		$items = "drug_administration_route_name";
		$order = "drug_administration_route_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_drug_time($time){
		$table = "drug_times";
		$where = "drug_times_name = ". $time;
		$items = "*";
		$order = "drug_times_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

	
	}

	function select_prescription($visit_id)
	{		
		// $table = "pres, drugs, drug_times, drug_duration, drug_consumption, visit_charge, service_charge";
		// $where = "service_charge.service_charge_id = pres.service_charge_id 
		// 				AND service_charge.drug_id = drugs.drugs_id
		// 				AND pres.drug_times_id = drug_times.drug_times_id 
		// 				AND pres.drug_duration_id = drug_duration.drug_duration_id
		// 				AND pres.drug_consumption_id = drug_consumption.drug_consumption_id
		// 				AND pres.visit_charge_id = visit_charge.visit_charge_id
		// 				 AND visit_charge.visit_id = ". $visit_id;
		// $items = "drugs.drugs_id AS checker_id,visit_charge.visit_charge_id, service_charge.service_charge_name AS drugs_name,service_charge.service_charge_amount  AS drugs_charge , drug_duration.drug_duration_name, pres.prescription_substitution, pres.prescription_id,pres.units_given, pres.visit_charge_id,pres.prescription_startdate, pres.prescription_finishdate, drug_times.drug_times_name, pres.prescription_startdate, pres.prescription_finishdate, pres.service_charge_id AS drugs_id, pres.prescription_substitution, drug_duration.drug_duration_name, pres.prescription_quantity, drug_consumption.drug_consumption_name, pres.number_of_days";
		// $order = "`drugs`.drugs_id";

		$table = "pres, drugs, drug_times, drug_duration, drug_consumption, service_charge";
		$where = "service_charge.service_charge_id = pres.service_charge_id 
						AND service_charge.drug_id = drugs.drugs_id
						AND pres.drug_times_id = drug_times.drug_times_id 
						AND pres.drug_duration_id = drug_duration.drug_duration_id
						AND pres.drug_consumption_id = drug_consumption.drug_consumption_id
						 AND pres.visit_id = ". $visit_id;
		$items = "drugs.drugs_id AS checker_id, service_charge.service_charge_name AS drugs_name,service_charge.service_charge_amount  AS drugs_charge , drug_duration.drug_duration_name, pres.prescription_substitution, pres.prescription_id,pres.units_given, pres.visit_charge_id,pres.prescription_startdate, pres.prescription_finishdate, drug_times.drug_times_name, pres.prescription_startdate, pres.prescription_finishdate, pres.service_charge_id AS drugs_id, pres.prescription_substitution, drug_duration.drug_duration_name, pres.prescription_quantity, drug_consumption.drug_consumption_name, pres.number_of_days";
		$order = "`drugs`.drugs_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function get_drug_forms(){
		$table = "drug_type";
		$where = "drug_type_id > 0 ";
		$items = "*";
		$order = "drug_type_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_drug_times(){
		$table = "drug_times";
		$where = "drug_times_id > 0 ";
		$items = "*";
		$order = "drug_times_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_consumption(){
		
		$table = "drug_consumption";
		$where = "drug_consumption_id > 0 ";
		$items = "*";
		$order = "drug_consumption_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_drug_duration(){
		
		$table = "drug_duration";
		$where = "drug_duration_id > 0 ";
		$items = "*";
		$order = "drug_duration_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	
	function get_warnings(){
		$table = "warnings";
		$where = "warnings_id > 0 ";
		$items = "*";
		$order = "warnings_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

		
	}

	function get_instructions(){
		$table = "instructions";
		$where = "instructions_id > 0 ";
		$items = "*";
		$order = "instructions_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_admin_route(){
		$table = "drug_administration_route";
		$where = "drug_administration_route_id > 0 ";
		$items = "*";
		$order = "drug_administration_route_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

	}

	function get_dose_unit(){
		$table = "drug_dose_unit";
		$where = "drug_dose_unit_id > 0 ";
		$items = "*";
		$order = "drug_dose_unit_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	public function get_drugs($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_id, service_charge.visit_type_id,generic.generic_name, brand.brand_name, service_charge.service_charge_amount, service_charge.drug_id , service_charge.service_charge_name, class.class_name,drugs.drugs_id,drugs.quantity');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function save_prescription($visit_id,$module)
	{
		$varpassed_value = $_POST['passed_value'];
		if(isset($_POST['substitution'])){
			$varsubstitution = $_POST['substitution'];
		}
		else
		{
			$varsubstitution = "No";
		}
		
		
		$date = date("Y-m-d"); 
		$time = date("H:i:s");
		$service_charge_id = $this->input->post('service_charge_id');
		//  insert into visit charge 

		if($module != NULL)
		{
			$amount_rs  = $this->get_service_charge_amount($service_charge_id);

			foreach ($amount_rs as $key_amount):
				# code...
				$visit_charge_amount = $key_amount->service_charge_amount;
			endforeach;

			$visit_charge_qty = $this->input->post('quantity');

			$array = array('visit_id'=>$visit_id,'service_charge_id'=>$service_charge_id,'visit_charge_amount'=>$visit_charge_amount,'date'=>$date,'time'=>$time,'visit_charge_qty'=>$visit_charge_qty,'created_by'=>$this->session->userdata("personnel_id"));
			if($this->db->insert('visit_charge', $array))
			{
				$visit_charge_id = $this->db->insert_id();
			}
			else{
				return FALSE;
			}
		}

		$rs = $this->get_visit_charge_id($visit_id, $date, $time);
		foreach ($rs as $key):
			$visit_charge_id = $key->visit_charge_id;
		endforeach;
		$data = array(
			'prescription_substitution'=>$varsubstitution,
			'prescription_startdate'=>$date,
			'prescription_finishdate'=>$this->input->post('finishdate'),
			'drug_times_id'=>$this->input->post('x'),
			// 'visit_charge_id'=>$visit_charge_id,
			'visit_id'=>$visit_id,
			'drug_duration_id'=>$this->input->post('duration'),
			'drug_consumption_id'=>$this->input->post('consumption'),
			'prescription_quantity'=>$this->input->post('quantity'),
			'number_of_days'=>$this->input->post('number_of_days'),
			'service_charge_id'=>$this->input->post('service_charge_id')
		);
		
		if($this->db->insert('pres', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function update_prescription($visit_id, $visit_charge_id, $prescription_id)
	{
		//$varpassed_value = $_POST['passed_value'];
		$varsubstitution = $_POST['substitution'.$prescription_id];
		
		if(empty($varsubstitution)){
			$varsubstitution = "No";
		}
		$date = date("Y-m-d"); 
		$time = date("H:i:s");

		$data2 = array(
			'prescription_substitution'=>$varsubstitution,
			'prescription_finishdate'=>$this->input->post('finishdate'.$prescription_id),
			'drug_times_id'=>$this->input->post('x'.$prescription_id),
			'drug_duration_id'=>$this->input->post('duration'.$prescription_id),
			'drug_consumption_id'=>$this->input->post('consumption'.$prescription_id),
			'units_given'=>$this->input->post('units_given'.$prescription_id),
			'prescription_quantity'=>$this->input->post('quantity'.$prescription_id)
		);
		
		$this->db->where('prescription_id', $prescription_id);
		if($this->db->update('pres', $data2))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function get_prescribed_drug($prescription_id,$visit_id)
	{
		$table = "pres,service_charge";
		$where = "pres.visit_id = ".$visit_id." AND service_charge.service_charge_id = pres.service_charge_id AND pres.prescription_id = ".$prescription_id;
		$items = "*";
		$order = "pres.prescription_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}
	public function check_id_drugs_invoiced($service_charge_id,$visit_id)
	{
		$table = "visit_charge";
		$where = "visit_id = ".$visit_id." AND service_charge_id = ".$service_charge_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}
	public function dispense_drug($visit_id, $visit_charge_id, $prescription_id)
	{
		//$varpassed_value = $_POST['passed_value'];
		$varsubstitution = $_POST['substitution'.$prescription_id];
		
		if(empty($varsubstitution)){
			$varsubstitution = "No";
		}
		$date = date("Y-m-d"); 
		$time = date("H:i:s");
		
		$visit_charge_qty = $this->input->post('quantity'.$prescription_id);
		$visit_charge_units = $this->input->post('units_given'.$prescription_id);

		// check if this drug 
		$result = $this->get_prescribed_drug($prescription_id,$visit_id);
		$num_rows = count($result);
		if($num_rows > 0){
			foreach($result as $key):
				$service_charge_id = $key->service_charge_id;
				$prescription_quantity = $key->prescription_quantity;
				$visit_id = $key->visit_id;
				$drug_consumption_id = $key->drug_consumption_id;
				$drug_times_id = $key->drug_times_id;
				$drug_duration_id = $key->drug_duration_id;
				$service_charge_amount = $key->service_charge_amount;
			endforeach;

			// check if the drug exisit in the table of visit charge
			$check = $this->check_id_drugs_invoiced($service_charge_id,$visit_id);
			// end of checking
			$check_num_rows = count($check);

			if($check_num_rows > 0){
				foreach($check as $key2):
				$visit_charge_id = $key2->visit_charge_id;
				endforeach;
				// if it exisit then update
				$array = array('visit_charge_qty'=>$visit_charge_qty,
							'visit_charge_units'=>$visit_charge_units,
							'created_by'=>$this->session->userdata("personnel_id"),
							'modified_by'=>$this->session->userdata("personnel_id"),
							'visit_id'=>$visit_id,
							'date_modified'=>date("Y-m-d")
							);
				$this->db->where('visit_charge_id',$visit_charge_id);
				if($this->db->update('visit_charge', $array))
				{
					// $visit_charge_id = $this->db->insert_id();
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else
			{
				// else insert
				$array = array(
							'service_charge_id'=>$service_charge_id,
							'visit_charge_qty'=>$visit_charge_qty,
							'visit_charge_amount'=>$service_charge_amount,
							'visit_charge_units'=>$visit_charge_units,
							'created_by'=>$this->session->userdata("personnel_id"),
							'modified_by'=>$this->session->userdata("personnel_id"),
							'visit_id'=>$visit_id,
							'date_modified'=>date("Y-m-d")
							);
			
				if($this->db->insert('visit_charge', $array))
				{
					$visit_charge_id = $this->db->insert_id();
				}
				else{
					return FALSE;
				}
			}



			
		}
		else
		{
			
		}

			

		$data2 = array(
			'prescription_substitution'=>$varsubstitution,
			'prescription_finishdate'=>$this->input->post('finishdate'.$prescription_id),
			'drug_times_id'=>$this->input->post('x'.$prescription_id),
			'drug_duration_id'=>$this->input->post('duration'.$prescription_id),
			'drug_consumption_id'=>$this->input->post('consumption'.$prescription_id),
			'units_given'=>$this->input->post('units_given'.$prescription_id),
			'visit_charge_id'=>$visit_charge_id,
			'prescription_quantity'=>$this->input->post('quantity'.$prescription_id)
		);
		
		$this->db->where('prescription_id', $prescription_id);
		if($this->db->update('pres', $data2))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	function get_visit_charge_id($visit_id, $date, $time){
		$table = "visit_charge";
		$where = "visit_id = ". $visit_id ." AND date = '$date'  AND time = '$time' ";
		$items = "visit_charge_id";
		$order = "visit_charge_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

	}
	public function get_service_charge_amount($service_charge_id)
	{
		# code...
		$table = "service_charge";
		$where = "service_charge_id = ". $service_charge_id;
		$items = "service_charge_amount";
		$order = "service_charge_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function get_visit_charge_id1($id){
		$table = "pres";
		$where = "prescription_id = ". $id;
		$items = "service_charge_id";
		$order = "service_charge_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function check_deleted_visitcharge($id)
	{	
		$table = "visit_charge";
		$where = "visit_charge_id = ". $id;
		$items = "*";
		$order = "visit_charge_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function select_invoice_drugs($visit_id,$service_charge_id){
		
		$table = "visit_charge";
		$where = "visit_id = ". $visit_id ." AND service_charge_id = ".$service_charge_id;
		$items = "sum(visit_charge_units) AS num_units";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_all_previous_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, visit_department.created AS visit_created, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id, visit_department.visit_id AS previous_visit');
		$this->db->where($where);
		$this->db->order_by('visit_department.created','ASC');
		$query = $this->db->get();
		
		return $query;
	}

	public function get_drugs_list($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('drugs.*, generic.generic_name, brand.brand_name, drug_type.drug_type_name, drug_administration_route.drug_administration_route_name , drug_dose_unit.drug_dose_unit_name, class.class_name, drug_consumption.drug_consumption_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function item_purchases($drugs_id)
	{
  		$table = "purchase, drugs";
		$where = "drugs.drugs_id = ".$drugs_id." AND purchase.drugs_id = drugs.drugs_id";
		$items = "purchase.purchase_pack_size, purchase.purchase_quantity";
		$order = "purchase_pack_size";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$purchase_pack_size = $row2->purchase_pack_size;
				$purchase_quantity = $row2->purchase_quantity;
				$total = $total + ($purchase_pack_size * $purchase_quantity);
			}
		}
		return $total;
	}
	
	public function item_deductions($drugs_id)
	{
  		$table = "stock_deductions, drugs";
		$where = "drugs.drugs_id = ".$drugs_id." AND stock_deductions.drugs_id = drugs.drugs_id";
		$items = "stock_deductions.stock_deductions_pack_size, stock_deductions.stock_deductions_quantity";
		$order = "stock_deductions_pack_size";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$stock_deductions_pack_size = $row2->stock_deductions_pack_size;
				$stock_deductions_quantity = $row2->stock_deductions_quantity;
				$total = $total + ($stock_deductions_pack_size * $stock_deductions_quantity);
			}
		}
		return $total;
	}
	
	public function get_drug_units_sold($drug_id)
	{
		$table = "visit_charge, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.drug_id = ". $drug_id;
		$items = "SUM(visit_charge.visit_charge_units) AS total_sold";
		$order = "total_sold";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$total_sold = 0;
		if(count($result) > 0)
		{
			foreach ($result as $key) {
				# code...
				$total_sold = $key->total_sold;
			}
		}
		return $total_sold;
	}

	function get_drug_classes()
	{
		$table = "class";
		$where = "class_id >= 0 ";
		$items = "*";
		$order = "class_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_drug_generics()
	{
		$table = "generic";
		$where = "delete_generic = 0 ";
		$items = "*";
		$order = "generic_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_all_drug_brands($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('brand_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		return $query;
	}
	public function add_brand()
	{
		
		$brand_name = $this->input->post('brand_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_brand($brand_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"brand_name" => $brand_name
				);
			$this->database->insert_entry('brand', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function check_brand($brand_name)
	{
		$table = "brand";
		$where = "brand_name = '$brand_name'";
		$items = "*";
		$order = "brand_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_brands_details($brand_id)
	{
		$this->db->from('brand');
		$this->db->select('*');
		$this->db->where('brand_id = \''.$brand_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_brand($brand_id)
	{
		$brand_name = $this->input->post('brand_name');
		$insert = array(
				"brand_name" => $brand_name
			);
		$this->db->where('brand_id',$brand_id);
		$this->db->update('brand', $insert);

		return TRUE;
	}
	public function get_all_drug_generics($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('generic_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		return $query;
	}
	public function add_generic()
	{
		
		$generic_name = $this->input->post('generic_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_generic($generic_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"generic_name" => $generic_name
				);
			$this->database->insert_entry('generic', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function check_generic($generic_name)
	{
		$table = "generic";
		$where = "generic_name = '$generic_name'";
		$items = "*";
		$order = "generic_id";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_drug_brands()
	{
		$table = "brand";
		$where = "brand_id >= 0 ";
		$items = "*";
		$order = "brand_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_drug_dose_units()
	{
		$table = "drug_dose_unit";
		$where = "drug_dose_unit_id >= 0 ";
		$items = "*";
		$order = "drug_dose_unit_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function save_drug()
	{
		$array = array(
			'drugs_name'=>$this->input->post('drugs_name'),
			'quantity'=>$this->input->post('quantity'),
			'drug_type_id'=>$this->input->post('drug_type_id'),
			'batch_no'=>$this->input->post('batch_no'),
			'drugs_unitprice'=>$this->input->post('drugs_unitprice'),
			'drugs_packsize'=>$this->input->post('drugs_pack_size'),
			'drugs_dose'=>$this->input->post('drug_dose'),
			'drug_dose_unit_id'=>$this->input->post('drug_dose_unit_id'),
			'brand_id'=>$this->input->post('brand_id'),
			'generic_id'=>$this->input->post('generic_id'),
			'drug_administration_route_id'=>$this->input->post('drug_administration_route_id'),
			'drug_consumption_id'=>$this->input->post('drug_consumption_id'),
			'class_id'=>$this->input->post('class_id')
		);
		if($this->db->insert('drugs', $array))
		{
			//calculate the price of the drug
			$drug_id = $this->db->insert_id();
			
			$markup = round(($this->input->post('drugs_unitprice') * 1.33), 0);
			$markdown = $markup;//round(($markup * 0.9), 0);
			
			$service_data = array(
				'drug_id'=>$drug_id,
				'service_charge_amount'=>$markdown,
				'service_id'=>4,
				'visit_type_id'=>0,
				'service_charge_status'=>1,
				'service_charge_name'=>$this->input->post('drugs_name')
			);
			$this->db->insert('service_charge', $service_data);
			
			/*$service_data['visit_type_id'] = 2;
			$this->db->insert('service_charge', $service_data);
			
			$service_data['visit_type_id'] = 3;
			$this->db->insert('service_charge', $service_data);
			
			$service_data['visit_type_id'] = 4;
			$this->db->insert('service_charge', $service_data);*/
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_drug($drugs_id)
	{
		$array = array(
			'drugs_name'=>$this->input->post('drugs_name'),
			'quantity'=>$this->input->post('quantity'),
			'drug_type_id'=>$this->input->post('drug_type_id'),
			'batch_no'=>$this->input->post('batch_no'),
			'drugs_unitprice'=>$this->input->post('drugs_unitprice'),
			'drugs_packsize'=>$this->input->post('drugs_pack_size'),
			'drugs_dose'=>$this->input->post('drug_dose'),
			'drug_dose_unit_id'=>$this->input->post('drug_dose_unit_id'),
			'brand_id'=>$this->input->post('brand_id'),
			'generic_id'=>$this->input->post('generic_id'),
			'drug_administration_route_id'=>$this->input->post('drug_administration_route_id'),
			'drug_consumption_id'=>$this->input->post('drug_consumption_id'),
			'class_id'=>$this->input->post('class_id')
		);
		
		$this->db->where('drugs_id', $drugs_id);
		if($this->db->update('drugs', $array))
		{
			//edit service charge
			$drug_id = $drugs_id;
			
			$markup = round(($this->input->post('drugs_unitprice') * 1.33), 0);
			$markdown = $markup;//round(($markup * 0.9), 0);
			
			$service_data = array(
				'drug_id'=>$drug_id,
				'service_charge_amount'=>$markdown,
				'service_id'=>4,
				'visit_type_id'=>0,
				'service_charge_status'=>1,
				'service_charge_name'=>$this->input->post('drugs_name'),
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
			
			$purchases_array = array(
			'expiry_date'=>$this->input->post('expiry_date')
			);
			$this->db->where('drugs_id', $drugs_id);
			if($this->db->update('purchase', $purchases_array))
			{
				return TRUE;
			}else
			{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	
	public function get_drug_details($drugs_id)
	{
		$this->db->where('drugs_id', $drugs_id);
		$query = $this->db->get('drugs');
		
		return $query;
	}

	function get_container_types()
	{
		$table = "container_type";
		$where = "container_type_id >= 0 ";
		$items = "*";
		$order = "container_type_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_generics_details($generic_id)
	{
		$this->db->from('generic');
		$this->db->select('*');
		$this->db->where('generic_id = \''.$generic_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_generic($generic_id)
	{
		$generic_name = $this->input->post('generic_name');
		$insert = array(
				"generic_name" => $generic_name
			);
		$this->db->where('generic_id',$generic_id);
		$this->db->update('generic', $insert);

		return TRUE;
	}
	public function get_all_drug_classes($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('class_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		return $query;
	}
	public function add_class()
	{
		
		$class_name = $this->input->post('class_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_class($class_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"class_name" => $class_name
				);
			$this->database->insert_entry('class', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function check_class($class_name)
	{
		$table = "class";
		$where = "class_name = '$class_name'";
		$items = "*";
		$order = "class_id";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	public function get_drugs_purchases($table, $where, $per_page, $page, $order)
	{
		//retrieve all purchases
		$this->db->from($table);
		$this->db->select('purchase.*, container_type.container_type_name');
		$this->db->where($where);
		$this->db->order_by($order,'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function purchase_drug($drugs_id)
	{
		$array = array(
			'drugs_id'=>$drugs_id,
			'container_type_id'=>$this->input->post('container_type_id'),
			'purchase_quantity'=>$this->input->post('purchase_quantity'),
			'purchase_pack_size'=>$this->input->post('purchase_pack_size'),
			'expiry_date'=>$this->input->post('expiry_date')
		);
		if($this->db->insert('purchase', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_drug_purchase($purchase_id)
	{
		$array = array(
			'container_type_id'=>$this->input->post('container_type_id'),
			'purchase_quantity'=>$this->input->post('purchase_quantity'),
			'purchase_pack_size'=>$this->input->post('purchase_pack_size'),
			'expiry_date'=>$this->input->post('expiry_date')
		);
		$this->db->where('purchase_id', $purchase_id);
		if($this->db->update('purchase', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_purchase_details($purchase_id)
	{
		$this->db->where('purchase_id', $purchase_id);
		$query = $this->db->get('purchase');
		
		return $query;
	}

	public function get_drugs_deductions($table, $where, $per_page, $page, $order)
	{
		//retrieve all purchases
		$this->db->from($table);
		$this->db->select('stock_deductions.*, container_type.container_type_name');
		$this->db->where($where);
		$this->db->order_by($order,'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function deduct_drug($drugs_id)
	{
		$array = array(
			'drugs_id'=>$drugs_id,
			'container_type_id'=>$this->input->post('container_type_id'),
			'stock_deductions_quantity'=>$this->input->post('stock_deduction_quantity'),
			'stock_deductions_pack_size'=>$this->input->post('stock_deduction_pack_size')
		);
		if($this->db->insert('stock_deductions', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_drug_deduction($stock_deduction_id)
	{
		$array = array(
			'container_type_id'=>$this->input->post('container_type_id'),
			'stock_deductions_quantity'=>$this->input->post('stock_deduction_quantity'),
			'stock_deductions_pack_size'=>$this->input->post('stock_deduction_pack_size')
		);
		$this->db->where('stock_deductions_id', $stock_deduction_id);
		if($this->db->update('stock_deductions', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_deduction_details($stock_deduction_id)
	{
		$this->db->where('stock_deductions_id', $stock_deduction_id);
		$query = $this->db->get('stock_deductions');
		
		return $query;
	}
	
	public function get_classes_details($class_id)
	{
		$this->db->from('class');
		$this->db->select('*');
		$this->db->where('class_id = \''.$class_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_class($class_id)
	{
		$class_name = $this->input->post('class_name');
		$insert = array(
				"class_name" => $class_name
			);
		$this->db->where('class_id',$class_id);
		$this->db->update('class', $insert);

		return TRUE;
	}
	public function get_all_drug_types($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('drug_type_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		return $query;
	}
	public function add_type()
	{
		
		$drug_type_name = $this->input->post('drug_type_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_type($drug_type_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"drug_type_name" => $drug_type_name
				);
			$this->database->insert_entry('drug_type', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function check_type($drug_type_name)
	{
		$table = "drug_type";
		$where = "drug_type_name = '$drug_type_name'";
		$items = "*";
		$order = "drug_type_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	public function get_drug_purchase_details($drugs_id)
	{
		$table = "purchase";
		$where = "drugs_id = '$drugs_id'";
		$items = "*";
		$order = "drugs_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_types_details($drug_type_id)
	{
		$this->db->from('drug_type');
		$this->db->select('*');
		$this->db->where('drug_type_id = \''.$drug_type_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_type($drug_type_id)
	{
		$drug_type_name = $this->input->post('drug_type_name');
		$insert = array(
				"drug_type_name" => $drug_type_name
			);
		$this->db->where('drug_type_id',$drug_type_id);
		$this->db->update('drug_type', $insert);

		return TRUE;
	}
	public function get_all_drug_containers($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('container_type_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		return $query;
	}
	public function add_container_type()
	{
		
		$container_type_name = $this->input->post('container_type_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_container_type($container_type_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"container_type_name" => $container_type_name
				);
			$this->database->insert_entry('container_type', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function check_container_type($container_type_name)
	{
		$table = "container_type";
		$where = "container_type_name = '$container_type_name'";
		$items = "*";
		$order = "container_type_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_containers_details($container_type_id)
	{
		$this->db->from('container_type');
		$this->db->select('*');
		$this->db->where('container_type_id = \''.$container_type_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_container_type($container_type_id)
	{
		$container_type_name = $this->input->post('container_type_name');
		$insert = array(
				"container_type_name" => $container_type_name
			);
		$this->db->where('container_type_id',$container_type_id);
		$this->db->update('container_type', $insert);

		return TRUE;
	}
}
?>