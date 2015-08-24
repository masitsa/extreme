<?php

class Nurse_model extends CI_Model 
{
	public function save_dental_vitals($visit_id)
	{
		$visit_major_reason= $this->input->post('reason');
		//$treatment= $this->input->post('treatment');
		$treatment_hospital= $this->input->post('hospital');
		$treatment_doctor=$this->input->post('doctor');
		$Food_allergies=$this->input->post('food_allergies');
		$Regular_treatment=$this->input->post('regular_treatment');
		$Recent_medication=$this->input->post('medication_description');
		$Medicine_allergies=$this->input->post('medicine_allergies');
		$prior_treatment=$this->input->post('prior_treatment');
		$alcohol=$this->input->post('alcohol');
		$smoke=$this->input->post('smoke');
		
		$women_pregnant=$this->input->post('preg');
		$pregnancy_month=$this->input->post('months');
		$serious_illness=$this->input->post('illness');
		$serious_illness_xplain=$this->input->post('illness_exp');
		$additional_infor=$this->input->post('additional');
		
		$data = array(
			'visit_id' => $visit_id,
			'visit_major_reason'=>$visit_major_reason,
			'serious_illness'=>$serious_illness,
			'serious_illness_xplain'=>$serious_illness_xplain,
			//'treatment'=>$treatment,
			'treatment_hospital'=>$treatment_hospital,
			'treatment_doctor'=>$treatment_doctor,
			'Food_allergies'=>$Food_allergies,
			'Regular_treatment'=>$Regular_treatment,
			'Recent_medication'=>$Recent_medication,
			'Medicine_allergies'=>$Medicine_allergies,
			/*'chest_trouble'=>$chest_trouble,
			'heart_problems'=>$heart_problems,
			'diabetic'=>$diabetic,
			'epileptic'=>$epileptic,
			'rheumatic_fever'=>$rheumatic_fever,
			'elongated_bleeding'=>$elongated_bleeding,
			'jaundice'=>$jaundice,
			'hepatitis'=>$hepatitis,
			'asthma'=>$asthma,
			'eczema'=>$eczema,
			'cancer'=>$cancer,*/
			'women_pregnant'=>$women_pregnant,
			'pregnancy_month'=>$pregnancy_month,
			'additional_infor'=>$additional_infor,
			'prior_treatment'=>$prior_treatment,
			'smoke'=>$smoke,
			'alcohol'=>$alcohol
		);
		
		if($this->db->insert('dental_vitals', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}

	public function select_current_dental_vitals($visit_id)
	{	
		$this->db->select('*');
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get('dental_vitals');
		
		return $query;	
	}
	
	public function update_dental_vitals($dental_vitals_id)
	{	
		$visit_major_reason= $this->input->post('reason');
		$treatment_hospital= $this->input->post('hospital');
		$treatment_doctor=$this->input->post('doctor');
		$Food_allergies=$this->input->post('food_allergies');
		$Regular_treatment=$this->input->post('regular_treatment');
		$Recent_medication=$this->input->post('medication_description');
		$Medicine_allergies=$this->input->post('medicine_allergies');
		$prior_treatment=$this->input->post('prior_treatment');
		$alcohol=$this->input->post('alcohol');
		$smoke=$this->input->post('smoke');
		$women_pregnant=$this->input->post('preg');
		$pregnancy_month=$this->input->post('months');
		$serious_illness=$this->input->post('illness');
		$serious_illness_xplain=$this->input->post('illness_exp');
		$additional_infor=$this->input->post('additional');
		
		$data = array(
			'visit_major_reason'=>$visit_major_reason,
			'serious_illness'=>$serious_illness,
			'serious_illness_xplain'=>$serious_illness_xplain,
			//'treatment'=>$treatment,
			'treatment_hospital'=>$treatment_hospital,
			'treatment_doctor'=>$treatment_doctor,
			'Food_allergies'=>$Food_allergies,
			'Regular_treatment'=>$Regular_treatment,
			'Recent_medication'=>$Recent_medication,
			'Medicine_allergies'=>$Medicine_allergies,
			/*'chest_trouble'=>$chest_trouble,
			'heart_problems'=>$heart_problems,
			'diabetic'=>$diabetic,
			'epileptic'=>$epileptic,
			'rheumatic_fever'=>$rheumatic_fever,
			'elongated_bleeding'=>$elongated_bleeding,
			'jaundice'=>$jaundice,
			'hepatitis'=>$hepatitis,
			'asthma'=>$asthma,
			'eczema'=>$eczema,
			'cancer'=>$cancer,*/
			'women_pregnant'=>$women_pregnant,
			'pregnancy_month'=>$pregnancy_month,
			'additional_infor'=>$additional_infor,
			'prior_treatment'=>$prior_treatment,
			'smoke'=>$smoke,
			'alcohol'=>$alcohol
		);
		
		$this->db->where('dental_vitals_id', $dental_vitals_id);
		if($this->db->update('dental_vitals', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function update_dental_visit($visit_id)
	{
		$data['dental_visit'] = 1;
		$data['nurse_visit'] = 1;
		
		$this->db->where('visit_id', $visit_id);
		if($this->db->update('visit', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_family_disease()
	{
		$this->db->select('*');
		$this->db->order_by('family_disease_name', 'ASC');
		$query = $this->db->get('family_disease');
		
		return $query;
	}
	
	public function get_family()
	{
		$this->db->select('*');
		$this->db->order_by('family_id', 'DESC');
		$query = $this->db->get('family');
		
		return $query;
	}
	
	public function get_family_history($family, $patient_id, $disease)
	{
		$this->db->select('*');
		$this->db->where(array('patient_id' => $patient_id, 'family_id' => $family, 'disease_id' => $disease));
		$query = $this->db->get('family_history_disease');
		
		return $query;
	}

	function get_visit_vitals($visit_id, $vitals_id){
		
		$table = "visit_vital";
		$where = "visit_id = '$visit_id' and vital_id = '$vitals_id'";
		$items = "*";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function vitals_range($vitals_id){
		
		$table = "vitals_range";
		$where = "vitals_id = '$vitals_id'";
		$items = "*";
		$order = "vitals_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_previous_vitals($visit_id){
		

		$table = "visit_vital, visit, patients, vitals";
		$where = "visit_vital.vital_id = vitals.vitals_id 
		AND visit_vital.visit_id = visit.visit_id 
		AND visit.visit_id = $visit_id 
		AND visit.patient_id = patients.patient_id
		AND patients.patient_id = (SELECT patients.patient_id FROM patients, visit WHERE visit.visit_id = $visit_id AND visit.patient_id = patients.patient_id)
		AND visit.close_card = 1";
		$items = "visit_vital.visit_vital_value, vitals.vitals_name, visit.visit_id, visit.visit_date";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_vitals($id){
		$table = "visit_vital";
		$where = "visit_id = '$id'";
		$items = "*";
		$order = "vital_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function check_visit_type($id){
		$table = "visit";
		$where = "visit_id = '$id'";
		$items = "visit_type, visit_id";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function visit_charge($visit_id){
		$table = "visit_charge";
		$where = "visit_charge.visit_id  = '$visit_id'";
		$items = "*";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function get_service_charge($procedure_id){
		$table = "service_charge";
		$where = "service_charge_id = '$procedure_id'";
		$items = "*";
		$order = "service_charge_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function search_procedures($order, $search,$visit_t){
		$table = "service_charge, service";
		$where = "service_charge_name LIKE '%$search%' AND service.service_id = 3 AND service_charge.service_id = service.service_id AND service_charge.visit_type_id = $visit_t";
		$items = "service_charge.service_charge_name, service_charge.visit_type_id,service_charge.service_charge_id , service_charge.service_charge_amount ";
		$order = "'$order'";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_procedures($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}


	function submitvisitprocedure($procedure_id,$visit_id,$suck){
		$visit_data = array('procedure_id'=>$procedure_id,'visit_id'=>$visit_id,'units'=>$suck);
		$this->db->insert('visit_procedure', $visit_data);
	}

	function get_visit_type($visit_id){
		$table = "visit";
		$where = "visit_id = '$visit_id'";
		$items = "visit_type, visit_id";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function visit_charge_insert($v_id,$procedure_id,$suck){


		$service_charge_rs = $this->get_service_charge($procedure_id);

		foreach ($service_charge_rs as $key) :
			# code...
			$visit_charge_amount = $key->service_charge_amount;
		endforeach;

		$visit_data = array('service_charge_id'=>$procedure_id,'visit_id'=>$v_id,'visit_charge_amount'=>$visit_charge_amount,'visit_charge_units'=>$suck);
		$this->db->insert('visit_charge', $visit_data);
	}


	function get_visit_procedure_charges($v_id){
		$table = "visit_charge";
		$where = "visit_id = $v_id";
		$items = "*";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;
	}
	public function get_all_patient_history($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*,patients.*');
		$this->db->where($where);
		$this->db->order_by('visit_time','desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function get_patient_lifestyle($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('patient_id','desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function get_patient_id($visit_id){
		$table = "visit";
		$where = "visit_id = ". $visit_id;
		$items = "patient_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				 $patient_id = $row2->patient_id;
			endforeach;
		}
		return $patient_id;
	}

	public function waiting_time($visit_id)
	{
		
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_time, visit_time_out";
		$order = "visit_time";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0){
			foreach ($result as $row2):
				$visit_time = $row2->visit_time;
				$visit_time_out = $row2->visit_time_out;
			endforeach;
		}
		
		if($visit_time_out == "0000-00-00 00:00:00"){
			$time1 = date('y-m-d  H:i:s');
		}
		else{
			$time1 = $visit_time_out;
		}
		
		$time_difference = $this->time_difference($time1, $visit_time);
		return $time_difference;
	}

	public function time_difference($higher_time, $lower_time)
	{
		$seconds = strtotime($higher_time) - strtotime($lower_time);
		$hours = $seconds/3600;
		$hours_rounded = intval(($seconds/3600));
		$minutes = ($hours - $hours_rounded) * 60;
		$minutes_rounded = intval($minutes);
		$ms = ($minutes - $minutes_rounded) * 60;
		$ms_rounded = intval($ms);
		return $hours_rounded.":".$minutes_rounded.":".$ms_rounded;
	}
	public function save_family_disease($family_id, $patient_id, $disease_id)
	{
		$data = array(
			'family_id'=>$family_id,
			'patient_id'=>$patient_id,
			'disease_id'=>$disease_id
		);
		
		if($this->db->insert('family_history_disease', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	public function delete_condition($family_id, $patient_id, $disease_id)
	{
		$data = array(
			'family_id'=>$family_id,
			'patient_id'=>$patient_id,
			'disease_id'=>$disease_id
		);
		$this->db->where($data);
		if($this->db->delete('family_history_disease'))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function get_exercices_values(){
		$table = "excersise";
		$where = "excersise_id > 0 ";
		$items = "*";
		$order = "excersise_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	public function get_exercices_duration_values(){
		$table = "excersise_duration";
		$where = "excersise_duration_id > 0 ";
		$items = "*";
		$order = "excersise_duration_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	public function get_sleep_values(){
		$table = "sleep";
		$where = "sleep_id > 0 ";
		$items = "*";
		$order = "sleep_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	public function get_values($table_name,$primary_key){
		$table = "$table_name";
		$where = "$primary_key > 0 ";
		$items = "*";
		$order = "$primary_key";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	public function submit_lifestyle_values($patient_id){
		$excercise= $this->input->post('excercise');
		$excercise_duration= $this->input->post('excercise_duration');
		$sleep=$this->input->post('sleep');
		$meals=$this->input->post('meals');
		$coffee=$this->input->post('coffee');
		$housing=$this->input->post('housing');
		$education=$this->input->post('education');
		$drugs=$this->input->post('drugs');
		$diet=$this->input->post('diet');
		$alcohol_qty=$this->input->post('alcohol_qty');
		$alcohol_percentage=$this->input->post('alcohol_percentage');
		
		$data = array(
			'excersise_id'=>$excercise,
			'excersise_duration_id'=>$excercise_duration,
			'sleep_id'=>$sleep,
			'meals_id'=>$meals,
			'coffee_id'=>$coffee,
			'housing_id'=>$housing,
			'education_id'=>$education_id,
			'lifestyle_diet'=>$diet,
			'lifestyle_drugs'=>$drugs,
			'lifestyle_alcohol_percentage'=>$alcohol_percentage,
			'lifestyle_alcohol_quantity'=>$alcohol_qty,
			'patient_id'=>$patient_id
		);
		
		if($this->db->insert('dental_vitals', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}

	public function get_symptoms($visit_id){
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	function get_visit_symptoms($visit_id){
		$table = "status, visit_symptoms, symptoms";
		$where = "visit_symptoms.visit_id = $visit_id AND visit_symptoms.symptoms_id = symptoms.symptoms_id AND visit_symptoms.status_id = status.status_id";
		$items = "visit_symptoms.description, symptoms.symptoms_name, status.status_name, visit_symptoms.visit_symptoms_id";
		$order = "symptoms.symptoms_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
	}

	public function get_objective_findings($visit_id){
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_objective_findings";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
		
		
	}

	function get_visit_objective_findings($visit_id){

		$table = "objective_findings, objective_findings_class, visit_objective_findings";
		$where = "objective_findings_class.objective_findings_class_id = objective_findings.objective_findings_class_id 
		AND visit_objective_findings.`objective_findings_id` = objective_findings.objective_findings_id
		AND visit_objective_findings.visit_id = ".$visit_id;
		$items = "objective_findings.objective_findings_name, objective_findings_class.objective_findings_class_name, objective_findings.objective_findings_id, visit_objective_findings.visit_objective_findings_id,visit_objective_findings.description";
		$order = "objective_findings.objective_findings_name";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;
		
	
	}

	function get_assessment($visit_id){
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_assessment";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;
	}

	function get_plan($visit_id){
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_plan";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	function get_diagnosis($visit_id){

		$table = "diagnosis, diseases";
		$where = "diagnosis.disease_id = diseases.diseases_id AND diagnosis.visit_id = ".$visit_id;
		$items = "diagnosis.diagnosis_id, diseases.diseases_name, diseases.diseases_code";
		$order = "diagnosis.disease_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		


	}

	function get_symptom_list($table, $where, $per_page, $page, $order){

		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	function update_visit_sypmtom($symptoms_id,$visit_id,$description){
		$description = str_replace('%20', ' ',$description);
		$visit_data = array('description'=>$description);

		$this->db->where(array("symptoms_id"=>$symptoms_id,"visit_id"=>$visit_id));
		$this->db->update('visit_symptoms', $visit_data);
		
	}
	function save_visit_sypmtom($symptoms_id,$visit_id,$status){
		$visit_data = array('visit_id'=>$visit_id,'symptoms_id'=>$symptoms_id,'status_id'=>$status);
		$this->db->insert('visit_symptoms', $visit_data);
	}

	function update_objective_finding($objective_finding_id, $visit_id, $description){
		
		$description = str_replace('%20', ' ',$description);
		$visit_data = array('description'=>$description);

		$this->db->where(array("objective_findings_id"=>$objective_finding_id,"visit_id"=>$visit_id));
		$this->db->update('visit_objective_findings', $visit_data);
		
	}
	function save_objective_finding($objective_finding_id, $visit_id){
		$visit_data = array('visit_id'=>$visit_id,'objective_findings_id'=>$objective_finding_id);
		$this->db->insert('visit_objective_findings', $visit_data);
	}

	public function get_diseases($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	function get_doctor_notes($patient_id){
		$table = "doctor_notes";
		$where = "patient_id = ".$patient_id;
		$items = "*";
		$order = "doctor_note_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	
	


	function get_nurse_notes($patient_id){
		$table = "nurse_notes";
		$where = "patient_id = ".$patient_id;
		$items = "*";
		$order = "note_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function save_nurse_notes($nurse_notes, $patient_id){	
		$rs = $this->get_doctor_notes($patient_id);
		$num_doc_notes = count($rs);
		
		if($num_doc_notes == 0){	
			$visit_data = array('patient_id'=>$patient_id,'nurse_notes'=>$doctor_notes);
			$this->db->insert('nurse_notes', $visit_data);

		}
		else {
			$visit_data = array('patient_id'=>$patient_id,'nurse_notes'=>$doctor_notes);
			$this->db->where('patient_id',$patient_id);
			$this->db->update('nurse_notes', $visit_data);
		}
	}



	public function medical_exam_categories()
	{
		$query = $this->db->get('medical_exam_categories');
		
		return $query;
	}

	public function get_illness($visit_id, $mec_id)
	{
		$this->db->where('med_id = '.$mec_id.' AND visit_id = '.$visit_id);
		$query = $this->db->get('med_check_text_save');
		
		return $query;
	}
	
	function get_visit_charge($visit_id)
	{
		$this->db->select('visit_charge_amount, visit_charge_timestamp');
		$this->db->where('visit_id = '.$visit_id);
		$query = $this->db->get('visit_charge');
		
		return $query;
	}
	
	function get_credit_amount($visit_type_id)
	{
		$this->db->select('account_credit, Amount, efect_date');
		$this->db->where('visit_type_id = '.$visit_type_id);
		$query = $this->db->get('account_credit');
		
		return $query;
	}
	
	function get_visit_type_name($visit_type_id)
	{
		$this->db->select('visit_type_id,visit_type_name');
		$this->db->where('visit_type_id = '.$visit_type_id);
		$query = $this->db->get('visit_type');
		
		return $query;
	}
	
	function get_visit_payment($visit_id)
	{
		$this->db->select('amount_paid');
		$this->db->where('visit_id = '.$visit_id);
		$query = $this->db->get('payments');
		
		return $query;
	}
	
	function max_visit($p_id)
	{
		$this->db->select('MAX(visit_id)');
		$this->db->where('patient_id = '.$p_id);
		$query = $this->db->get('visit');
		
		return $query;
	}
	
	function min_visit($visit_id,$payment_method_id,$amount_paid)
	{
		$this->db->select('MIN(time), payment_id');
		$this->db->where('payment_method_id = '.$payment_method_id.' AND visit_id = '.$visit_id.' AND amount_paid = '.$amount_paid);
		$query = $this->db->get('payments');
		
		return $query;
	}
		
	function mec_med($mec_id)
	{
		$this->db->select('DISTINCT(item_format_id)');
		$this->db->where('mec_id = '.$mec_id);
		$query = $this->db->get('cat_items');
		
		return $query;
	}
		
	function format_id($item_format_id)
	{
		$this->db->where('item_format_id = '.$item_format_id);
		$query = $this->db->get('format');
		
		return $query;
	}
		
	function get_cat_items($item_format_id, $mec_id)
	{
		$this->db->select('cat_items.cat_item_name, cat_items.cat_items_id, cat_items.item_format_id, format.format_name, format.format_id');
		$this->db->where('cat_items.item_format_id = format.item_format_id AND cat_items.item_format_id = '.$item_format_id.' AND mec_id = '.$mec_id);
		$query = $this->db->get('cat_items, format');
		
		return $query;
	}
	
	function cat_items($item_format_id, $mec_id)
	{
		$this->db->select('cat_items.cat_item_name, cat_items.cat_items_id');
		$this->db->where('cat_items.item_format_id = '.$item_format_id.' AND mec_id = '.$mec_id);
		$query = $this->db->get('cat_items');
		
		return $query;
	}
	
	function cat_items2($cat_items_id,$format_id,$visit_id)
	{
		$this->db->where('cat_id = '.$cat_items_id.' AND format_id = '.$format_id.' AND visit_id = '.$visit_id);
		$query = $this->db->get('medical_checkup_results');
		
		return $query;
	}
	
	function check_text_save($mec_id, $visit_id)
	{			
		$this->db->where('med_id = '.$mec_id.' AND visit_id = '.$visit_id);
		$query = $this->db->get('med_check_text_save');
		
		return $query;
		
	}
	
	function save_illness($illness, $mec_id, $visit_id)
	{	
		$data['med_id'] = $mec_id;
		$data['infor'] = $illness;
		$data['visit_id'] = $visit_id;
		
		if($this->db->insert('med_check_text_save', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	function update_illness($illness, $result)
	{
		$mcts_id= $result->mcts_id;
		
		$data['infor'] = $illness;
		
		$this->db->where('mcts_id', $mcts_id);
		if($this->db->update('med_check_text_save', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	function save_medical_exam($cat_items_id, $format_id, $visit_id)
	{
		$data['cat_id'] = $cat_items_id;
		$data['format_id'] = $format_id;
		$data['visit_id'] = $visit_id;
		
		if($this->db->insert('medical_checkup_results', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	function delete_medical_exam($cat_items_id,$format_id,$visit_id)
	{
		$data['cat_id'] = $cat_items_id;
		$data['format_id'] = $format_id;
		$data['visit_id'] = $visit_id;
		$this->db->where($data);
		
		if($this->db->delete('medical_checkup_results'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_queue_total($table, $where)
	{
		$total = $this->reception_model->count_items($table, $where);
		
		if($total > 0)
		{
			return $total;
		}
		
		else
		{
			return 0;
		}
	}
	function get_medicals($id){
		$table = "medication";
		$where = "patient_id = ".$id;
		$items = "*";
		$order = "patient_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function get_surgeries($patient_id){
		$table = "surgery, month";
		$where = "surgery.month_id = month.month_id AND patient_id = '$patient_id'";
		$items = "*";
		$order = "patient_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	function get_vaccines(){
		$table = "vaccine";
		$where = "vaccine_id > 0";
		$items = "*";
		$order = "vaccine_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function check_vaccine($patient_id, $patient_vaccine){
		$table = "patients_vaccine";
		$where = "patient_id = '". $patient_id ."' AND vaccine_id = ". $patient_vaccine;
		$items = "patient_vaccine_id, status_id ";
		$order = "vaccine_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

		
	}
	
}
?>
