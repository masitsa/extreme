<?php

class Lab_model extends CI_Model 
{

	function get_lab_visit2($visit_id){
		$table = "visit_lab_test,service_charge";
		$where = "visit_lab_test_status = 1 AND service_charge.service_charge_id = visit_lab_test.service_charge_id AND visit_lab_test.visit_id = ".$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_lab_visit_test($visit_id ){
		$table = "visit_department";
		$where = "visit_id = ".$visit_id;
		$items = "department_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_comment($visit_id){

		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "lab_visit_comment, visit_date";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_lab_visit_result($visit_charge_id){
		$table = "lab_visit_results";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "*";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_test_old($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "lab_test, visit_charge, lab_test_class, lab_test_format, lab_visit_results, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.lab_test_id = lab_test.lab_test_id 
		AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id 
		AND lab_test_format.lab_test_id = lab_test.lab_test_id 
		AND visit_charge.visit_charge_id = lab_visit_results.visit_charge_id 
		AND lab_visit_results.lab_visit_result_format = lab_test_format.lab_test_format_id AND visit_charge.visit_charge_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS lab_test_name, lab_test_class.lab_test_class_name, lab_test.lab_test_units, lab_test.lab_test_malelowerlimit, lab_test.lab_test_malelupperlimit, lab_test.lab_test_femalelowerlimit, lab_test.lab_test_femaleupperlimit,lab_test_format.lab_test_format_id, visit_charge.visit_charge_id AS lab_visit_id,  visit_charge.visit_charge_results AS lab_visit_result, lab_test_format.lab_test_formatname, lab_test_format.lab_test_format_units, lab_test_format.lab_test_format_malelowerlimit, lab_test_format.lab_test_format_maleupperlimit, lab_test_format.lab_test_format_femalelowerlimit, lab_test_format.lab_test_format_femaleupperlimit, lab_visit_results.lab_visit_results_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_test($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "lab_test, visit_lab_test, lab_test_class, lab_test_format, lab_visit_results, service_charge";
		$where = "visit_lab_test.service_charge_id = service_charge.service_charge_id 
		AND service_charge.lab_test_id = lab_test.lab_test_id 
		AND visit_lab_test.visit_lab_test_status = 1 
		AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id 
		AND lab_test_format.lab_test_id = lab_test.lab_test_id 
		AND visit_lab_test.visit_lab_test_id = lab_visit_results.visit_charge_id 
		AND lab_visit_results.lab_visit_result_format = lab_test_format.lab_test_format_id AND visit_lab_test.visit_lab_test_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS lab_test_name, lab_test_class.lab_test_class_name, lab_test.lab_test_units, lab_test.lab_test_malelowerlimit, lab_test.lab_test_malelupperlimit, lab_test.lab_test_femalelowerlimit, lab_test.lab_test_femaleupperlimit,lab_test_format.lab_test_format_id, visit_lab_test.visit_lab_test_id AS lab_visit_id,  visit_lab_test.visit_lab_test_results AS lab_visit_result, lab_test_format.lab_test_formatname, lab_test_format.lab_test_format_units, lab_test_format.lab_test_format_malelowerlimit, lab_test_format.lab_test_format_maleupperlimit, lab_test_format.lab_test_format_femalelowerlimit, lab_test_format.lab_test_format_femaleupperlimit, lab_visit_results.lab_visit_results_result, visit_lab_test.visit_lab_test_comment";
		$order = "visit_lab_test.visit_lab_test_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}


	function get_m_test_old($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "lab_test, visit_charge, lab_test_class, service_charge";
		
		$where = "visit_charge.visit_charge_id = $visit_charge_id
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.lab_test_id = lab_test.lab_test_id 
		AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id
		AND visit_charge.visit_charge_id NOT IN (SELECT visit_charge_id FROM lab_visit_results)";
		
		$items = "service_charge.service_charge_name AS lab_test_name, lab_test_class.lab_test_class_name, lab_test.lab_test_units, lab_test.lab_test_malelowerlimit, lab_test.lab_test_malelupperlimit, lab_test.lab_test_femalelowerlimit, lab_test.lab_test_femaleupperlimit, visit_charge.visit_charge_id AS lab_visit_id, visit_charge.visit_charge_results AS lab_visit_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_m_test($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "lab_test, visit_lab_test, lab_test_class, service_charge";
		
		$where = "visit_lab_test.visit_lab_test_id = $visit_charge_id
		AND visit_lab_test.service_charge_id = service_charge.service_charge_id 
		AND service_charge.lab_test_id = lab_test.lab_test_id 
		AND visit_lab_test.visit_lab_test_status = 1 
		AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id
		AND visit_lab_test.visit_lab_test_id NOT IN (SELECT visit_charge_id FROM lab_visit_results)";
		
		$items = "service_charge.service_charge_name AS lab_test_name, lab_test_class.lab_test_class_name, lab_test.lab_test_units, lab_test.lab_test_malelowerlimit, lab_test.lab_test_malelupperlimit, lab_test.lab_test_femalelowerlimit, lab_test.lab_test_femaleupperlimit, visit_lab_test.visit_lab_test_id AS lab_visit_id, visit_lab_test.visit_lab_test_results AS lab_visit_result, visit_lab_test.visit_lab_test_comment";
		$order = "visit_lab_test.visit_lab_test_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_test_comment($visit_charge_id){

		$table = "lab_visit_format_comment";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "lab_visit_format_comments";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	public function get_lab_tests($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name, lab_test_class.lab_test_class_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	function get_lab_test_id($service_charge_id){
		$table = "service_charge";
		$where = "service_charge_id = ".$service_charge_id;
		$items = "lab_test_id";
		$order = "lab_test_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;

	}

	function get_visits_lab_result($visit_id, $lab_id){

		$table = "lab_test_format";
		$where = "lab_test_id = ". $lab_id;
		$items = "lab_test_format_id";
		$order = "lab_test_format_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_lab_visit_old($visit_id, $service_charge_id=NULL){
		$table = "visit_charge";
		if($service_charge_id != NULL){
				$where = "visit_id = ". $visit_id ." AND service_charge_id = ". $service_charge_id;
		}else{
				$where = "visit_id = ". $visit_id;
		}
		
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_lab_visit($visit_id, $service_charge_id=NULL){
		$table = "visit_lab_test";
		if($service_charge_id != NULL){
				$where = "visit_id = ". $visit_id ." AND service_charge_id = ". $service_charge_id;
		}else{
				$where = "visit_id = ". $visit_id;
		}
		
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_lab_visit_item_old($visit_id){
		$table = "visit_charge, service, service_charge";
		$where = "service.service_id = 5 
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_charge.visit_id = ". $visit_id;
		$items = "visit_charge.visit_charge_id";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_lab_visit_item($visit_id){
		$table = "visit_lab_test, service, service_charge";
		$where = "service.service_id = 5 
		AND visit_lab_test.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_lab_test.visit_id = ". $visit_id;
		$items = "visit_lab_test.visit_lab_test_id,visit_lab_test.service_charge_id";
		$order = "visit_lab_test.visit_lab_test_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function save_lab_visit($visit_id, $service_charge_id){
		
		$table = "service_charge";
		$where = "service_charge_id = ". $service_charge_id;
		$items = "service_charge_amount";
		$order = "service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				# code...
				$service_charge_amount = $key->service_charge_amount;
			endforeach;
			
		}

		$visit_data = array('visit_id'=>$visit_id,'service_charge_id'=>$service_charge_id,'visit_charge_amount'=>$service_charge_amount,'created_by'=>$this->session->userdata("personnel_id"));
		$this->db->insert('visit_charge', $visit_data);

		
	}
	

	public function check_visit_charge_lab_test($service_charge_id,$visit_id){
		$table = "visit_charge";
		$where = "visit_charge_delete = 0 AND visit_id = ".$visit_id." AND service_charge_id = ". $service_charge_id;
		$items = "*";
		$order = "service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				$visit_charge_id = $key->visit_charge_id;
			endforeach;
			return $visit_charge_id;
		}
		else
		{
			return 0;
		}
	}
	// this will ensure that the lab test shall only be authorised by the relevant laboratory technician

	function save_lab_visit_trail($visit_id, $service_charge_id){
	
		// this should be the service charge from the service charge table same to the lab test id

		$visit_data = array('visit_id'=>$visit_id,'service_charge_id'=>$service_charge_id,'created'=>date("Y-m-d"),'visit_lab_test_status'=>1,'created_by'=>$this->session->userdata("personnel_id"));
		$this->db->insert('visit_lab_test', $visit_data);

		
	}


	


	function save_lab_visit_format($visit_id, $service_charge_id, $lab_test_format_id){
		$table = "visit_lab_test";
		$where = "visit_id = ". $visit_id. " AND service_charge_id = ". $service_charge_id;
		$items = "visit_lab_test_id";
		$order = "visit_id";


		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				# code...
				$visit_lab_test_id = $key->visit_lab_test_id;
			endforeach;
			
		}
		$visit_data = array('visit_charge_id'=>$visit_lab_test_id,'lab_visit_result_format'=>$lab_test_format_id,'visit_id'=>$visit_id);
		$this->db->insert('lab_visit_results', $visit_data);

	}
	
	function delete_cost($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$this->db->delete('visit_charge');
	}

	function delete_visit_lab_test($service_charge_id,$visit_id)
	{
		$table = "visit_lab_test";
		$where = "visit_id = ". $visit_id. " AND service_charge_id = ". $service_charge_id;
		$items = "visit_lab_test_id";
		$order = "visit_id";


		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				# code...
				$visit_lab_test_id = $key->visit_lab_test_id;
			endforeach;
			
		}
		$data['visit_lab_test_status'] = 0;
	
		$this->db->where('visit_lab_test_id', $visit_lab_test_id);
		$this->db->update('visit_lab_test', $data);
	}

	function get_lab_test($visit_id){
		
		$this->session->set_userdata('test',1);

		$table = "lab_test, visit_charge, service_charge, lab_test_class";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.lab_test_id = lab_test.lab_test_id 
		AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id AND lab_test.lab_test_class_id = lab_test_class.lab_test_class_id AND visit_charge.visit_id = ".$visit_id;
		$items = "service_charge.service_charge_name AS lab_test_name, lab_test_class.lab_test_class_name AS class_name, service_charge.service_charge_amount AS lab_test_price, visit_charge.visit_charge_id AS lab_visit_id";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;


		
	}
	function save_tests_old($res, $lab){
		$data['visit_charge_results'] = $res;
	
		$this->db->where('visit_charge_id', $lab);
		if($this->db->update('visit_charge', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
		
		
	}

	function save_tests($res, $lab){
		$data['visit_lab_test_results'] = $res;
	
		$this->db->where('visit_lab_test_id', $lab);
		if($this->db->update('visit_lab_test', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}

	function save_comment($comment, $visit_charge_id){

		$table = "visit_lab_test";
		$where = "visit_lab_test_id = ".$visit_charge_id;
		$items = "visit_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach($result as $rs):
				$visit_id = $rs->visit_id;
			endforeach;
		}
		$data['lab_visit_comment'] = $comment;
		
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
	function get_patient_id($visit_id)
	{

		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "patient_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		foreach ($result as $key):
			# code...
			$patient_id = $key->patient_id;
		endforeach;
		return $patient_id;
	}
	function get_patient2($id){

		$table = "patients, visit";
		$where = "visit.patient_id = patients.patient_id AND patients.patient_id = ".$id;
		$items = "patients.strath_no, visit.visit_type, patients.patient_number, patients.patient_othernames, patients.patient_surname, patients.patient_date_of_birth, patients.gender_id,patients.visit_type_id ";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);


		return $result;
	}

	function get_patient_3($strath_no){
       	$table = "staff";
		$where = "Staff_Number = '$strath_no'";
		$items = "*";
		$order = "staff_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;
		
	}
	function dateDiff($time1, $time2, $interval) {
	    // If not numeric then convert texts to unix timestamps
	    if (!is_int($time1)) {
	      $time1 = strtotime($time1);
	    }
	    if (!is_int($time2)) {
	      $time2 = strtotime($time2);
	    }
	 
	    // If time1 is bigger than time2
	    // Then swap time1 and time2
	    if ($time1 > $time2) {
	      $ttime = $time1;
	      $time1 = $time2;
	      $time2 = $ttime;
	    }
	 
	    // Set up intervals and diffs arrays
	    $intervals = array('year','month','day','hour','minute','second');
	    if (!in_array($interval, $intervals)) {
	      return false;
	    }
	 
	    $diff = 0;
	    // Create temp time from time1 and interval
	    $ttime = strtotime("+1 " . $interval, $time1);
	    // Loop until temp time is smaller than time2
	    while ($time2 >= $ttime) {
	      $time1 = $ttime;
	      $diff++;
	      // Create new temp time from time1 and interval
	      $ttime = strtotime("+1 " . $interval, $time1);
	    }
	 
	    return $diff;
  	}
  	function get_lab_personnel($id){
		$table = "personnel";
		$where = "personnel_id = '$id'";
		$items = "personnel_fname, personnel_surname";
		$order = "personnel_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;
		
	}

	function get_patient_2($strath_no){

		$table = "student";
		$where = "student_Number = '$strath_no'";
		$items = "*";
		$order = "student_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;
		
	}
	
	function save_tests_format2($visit_id)
	{
		$result = $this->input->post('res');
		$format = $this->input->post('format');
		
		$data['lab_visit_results_result'] = $result;
		$this->db->where(array('lab_visit_result_format' => $format, 'visit_id' => $visit_id));
		
		$this->db->update('lab_visit_results', $data);
	}

	
	function get_lab_comments($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$query = $this->db->get('lab_visit_format_comment');
		
		return $query;
	}
	
	function save_new_lab_comment()
	{
		$data['lab_visit_format_comments'] = $this->input->post('lab_visit_format_comments');
		$data['visit_charge_id'] = $this->input->post('visit_charge_id');
		
		$this->db->insert('lab_visit_format_comment', $data);
	}
	
	function update_existing_lab_comment($visit_charge_id)
	{
		$data['lab_visit_format_comments'] = $this->input->post('lab_visit_format_comments');
		$where['visit_charge_id'] = $visit_charge_id;
		
		$this->db->where($where);
		$this->db->update('lab_visit_format_comment', $data);
	}
	public function get_all_ongoing_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, visit_department.created AS visit_created, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id, visit_department.visit_id AS previous_visit');
		$this->db->where($where);
		$this->db->order_by('visit_department.created','ASC');
		$query = $this->db->get();
		
		return $query;
	}
}
?>