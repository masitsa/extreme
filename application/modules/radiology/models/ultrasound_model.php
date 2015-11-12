<?php

class Ultrasound_model extends CI_Model 
{

	function get_ultrasound_visit2($visit_id){
		$table = "visit_charge, service_charge, service";
		$where = 'visit_charge_delete = 0 AND service.service_id = service_charge.service_id AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND visit_charge_delete = 0 AND service_charge.service_charge_id = visit_charge.service_charge_id AND visit_charge.visit_id = '.$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_ultrasound_visit_test($visit_id ){
		$table = "visit_department";
		$where = "visit_id = ".$visit_id;
		$items = "department_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_comment($visit_id)
	{

		$table = "visit_charge";
		$where = "visit_id = ".$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_ultrasound_visit_result($visit_charge_id){
		$table = "ultrasound_visit_results";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "*";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_test_old($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "ultrasound, visit_charge, ultrasound_class, ultrasound_format, ultrasound_visit_results, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.ultrasound_id = ultrasound.ultrasound_id 
		AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id 
		AND ultrasound_format.ultrasound_id = ultrasound.ultrasound_id 
		AND visit_charge.visit_charge_id = ultrasound_visit_results.visit_charge_id 
		AND ultrasound_visit_results.ultrasound_visit_result_format = ultrasound_format.ultrasound_format_id AND visit_charge.visit_charge_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS ultrasound_name, ultrasound_class.ultrasound_class_name, ultrasound.ultrasound_units, ultrasound.ultrasound_malelowerlimit, ultrasound.ultrasound_malelupperlimit, ultrasound.ultrasound_femalelowerlimit, ultrasound.ultrasound_femaleupperlimit,ultrasound_format.ultrasound_format_id, visit_charge.visit_charge_id AS ultrasound_visit_id,  visit_charge.visit_charge_results AS ultrasound_visit_result, ultrasound_format.ultrasound_formatname, ultrasound_format.ultrasound_format_units, ultrasound_format.ultrasound_format_malelowerlimit, ultrasound_format.ultrasound_format_maleupperlimit, ultrasound_format.ultrasound_format_femalelowerlimit, ultrasound_format.ultrasound_format_femaleupperlimit, ultrasound_visit_results.ultrasound_visit_results_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_test($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "ultrasound, visit_ultrasound, ultrasound_class, ultrasound_format, ultrasound_visit_results, service_charge";
		$where = "visit_ultrasound.service_charge_id = service_charge.service_charge_id 
		AND service_charge.ultrasound_id = ultrasound.ultrasound_id 
		AND visit_ultrasound.visit_ultrasound_status = 1 
		AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id 
		AND ultrasound_format.ultrasound_id = ultrasound.ultrasound_id 
		AND visit_ultrasound.visit_ultrasound_id = ultrasound_visit_results.visit_charge_id 
		AND ultrasound_visit_results.ultrasound_visit_result_format = ultrasound_format.ultrasound_format_id AND visit_ultrasound.visit_ultrasound_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS ultrasound_name, ultrasound_class.ultrasound_class_name, ultrasound.ultrasound_units, ultrasound.ultrasound_malelowerlimit, ultrasound.ultrasound_malelupperlimit, ultrasound.ultrasound_femalelowerlimit, ultrasound.ultrasound_femaleupperlimit,ultrasound_format.ultrasound_format_id, visit_ultrasound.visit_ultrasound_id AS ultrasound_visit_id,  visit_ultrasound.visit_ultrasound_results AS ultrasound_visit_result, ultrasound_format.ultrasound_formatname, ultrasound_format.ultrasound_format_units, ultrasound_format.ultrasound_format_malelowerlimit, ultrasound_format.ultrasound_format_maleupperlimit, ultrasound_format.ultrasound_format_femalelowerlimit, ultrasound_format.ultrasound_format_femaleupperlimit, ultrasound_visit_results.ultrasound_visit_results_result, visit_ultrasound.visit_ultrasound_comment";
		$order = "visit_ultrasound.visit_ultrasound_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}


	function get_m_test_old($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "ultrasound, visit_charge, ultrasound_class, service_charge";
		
		$where = "visit_charge.visit_charge_id = $visit_charge_id
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.ultrasound_id = ultrasound.ultrasound_id 
		AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id
		AND visit_charge.visit_charge_id NOT IN (SELECT visit_charge_id FROM ultrasound_visit_results)";
		
		$items = "service_charge.service_charge_name AS ultrasound_name, ultrasound_class.ultrasound_class_name, ultrasound.ultrasound_units, ultrasound.ultrasound_malelowerlimit, ultrasound.ultrasound_malelupperlimit, ultrasound.ultrasound_femalelowerlimit, ultrasound.ultrasound_femaleupperlimit, visit_charge.visit_charge_id AS ultrasound_visit_id, visit_charge.visit_charge_results AS ultrasound_visit_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_m_test($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "ultrasound, visit_ultrasound, ultrasound_class, service_charge";
		
		$where = "visit_ultrasound.visit_ultrasound_id = $visit_charge_id
		AND visit_ultrasound.service_charge_id = service_charge.service_charge_id 
		AND service_charge.ultrasound_id = ultrasound.ultrasound_id 
		AND visit_ultrasound.visit_ultrasound_status = 1 
		AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id
		AND visit_ultrasound.visit_ultrasound_id NOT IN (SELECT visit_charge_id FROM ultrasound_visit_results)";
		
		$items = "service_charge.service_charge_name AS ultrasound_name, ultrasound_class.ultrasound_class_name, ultrasound.ultrasound_units, ultrasound.ultrasound_malelowerlimit, ultrasound.ultrasound_malelupperlimit, ultrasound.ultrasound_femalelowerlimit, ultrasound.ultrasound_femaleupperlimit, visit_ultrasound.visit_ultrasound_id AS ultrasound_visit_id, visit_ultrasound.visit_ultrasound_results AS ultrasound_visit_result, visit_ultrasound.visit_ultrasound_comment";
		$order = "visit_ultrasound.visit_ultrasound_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_test_comment($visit_charge_id){

		$table = "ultrasound_visit_format_comment";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "ultrasound_visit_format_comments";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	public function get_ultrasounds($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_inpatient_ultrasounds($table, $where,$order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('');
		
		return $query;
	}

	function get_ultrasound_visit_old($visit_id, $service_charge_id=NULL){
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
	function get_ultrasound_visit($visit_id, $service_charge_id=NULL){
		$table = "visit_charge";
		$where = "visit_charge_delete = 0 AND visit_id = ". $visit_id ." AND service_charge_id = ". $service_charge_id;
		
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_ultrasound_visit_item_old($visit_id){
		$table = "visit_charge, service, service_charge";
		$where = "(service.service_name = 'Ultrasound' OR service.service_name = 'ultrasound')
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_charge.visit_id = ". $visit_id;
		$items = "visit_charge.visit_charge_id";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_ultrasound_visit_item($visit_id){
		$table = "visit_charge, service, service_charge";
		$where = "visit_charge_delete = 0 AND (service.service_name = 'Ultrasound' OR service.service_name = 'ultrasound')
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_charge.visit_id = ". $visit_id;
		$items = "*";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function save_ultrasound_visit($visit_id, $service_charge_id)
	{
		$table = "service_charge";
		$where = "service_charge_id = ". $service_charge_id;
		$items = "service_charge_amount";
		$order = "service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$service_charge_amount = 0;
		
		if(count($result) > 0)
		{
			foreach ($result as $key): 
				$service_charge_amount = $key->service_charge_amount;
			endforeach;
		}

		$visit_data = array(
			'visit_id'=>$visit_id,
			'service_charge_id'=>$service_charge_id,
			'visit_charge_amount'=>$service_charge_amount,
			'created_by'=>$this->session->userdata("personnel_id")
		);
		if($this->db->insert('visit_charge', $visit_data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	

	public function check_visit_charge_ultrasound($service_charge_id,$visit_id){
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
	
	function save_ultrasound_visit_format($visit_id, $service_charge_id, $ultrasound_format_id){
		$table = "visit_ultrasound";
		$where = "visit_id = ". $visit_id. " AND service_charge_id = ". $service_charge_id;
		$items = "visit_ultrasound_id";
		$order = "visit_id";


		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				# code...
				$visit_ultrasound_id = $key->visit_ultrasound_id;
			endforeach;
			
		}
		$visit_data = array('visit_charge_id'=>$visit_ultrasound_id,'ultrasound_visit_result_format'=>$ultrasound_format_id,'visit_id'=>$visit_id);
		$this->db->insert('ultrasound_visit_results', $visit_data);

	}
	
	function delete_cost($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$this->db->delete('visit_charge');
	}

	function delete_visit_ultrasound($visit_charge_id)
	{
		$table = "visit_charge";
		$where = "visit_charge_id = ". $visit_charge_id;
		$data['visit_charge_delete'] = 1;
		$data['deleted_by'] = $this->session->userdata('personnel_id');
		$data['deleted_on'] = date('Y-m-d H:i:s');
	
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	function get_ultrasound($visit_id){
		
		$this->session->set_userdata('test',1);

		$table = "ultrasound, visit_charge, service_charge, ultrasound_class";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.ultrasound_id = ultrasound.ultrasound_id 
		AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id AND ultrasound.ultrasound_class_id = ultrasound_class.ultrasound_class_id AND visit_charge.visit_id = ".$visit_id;
		$items = "service_charge.service_charge_name AS ultrasound_name, ultrasound_class.ultrasound_class_name AS class_name, service_charge.service_charge_amount AS ultrasound_price, visit_charge.visit_charge_id AS ultrasound_visit_id";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;


		
	}
	function save_tests_old($res, $ultrasound){
		$data['visit_charge_results'] = $res;
	
		$this->db->where('visit_charge_id', $ultrasound);
		if($this->db->update('visit_charge', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
		
		
	}

	function save_tests($res, $ultrasound){
		$data['visit_ultrasound_results'] = $res;
	
		$this->db->where('visit_ultrasound_id', $ultrasound);
		if($this->db->update('visit_ultrasound', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}

	function save_comment($comment, $visit_charge_id){

		$table = "visit_ultrasound";
		$where = "visit_ultrasound_id = ".$visit_charge_id;
		$items = "visit_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach($result as $rs):
				$visit_id = $rs->visit_id;
			endforeach;
		}
		$data['ultrasound_visit_comment'] = $comment;
		
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
  	function get_ultrasound_personnel($id){
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
	
	function save_tests_format2($visit_charge_id)
	{
		$result = $this->input->post('result');
		
		$data['visit_charge_comment'] = $result;
		$this->db->where(array('visit_charge_id' => $visit_charge_id));
		
		if($this->db->update('visit_charge', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}

	
	function get_ultrasound_comments($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$query = $this->db->get('ultrasound_visit_format_comment');
		
		return $query;
	}
	
	function save_new_ultrasound_comment()
	{
		$data['ultrasound_visit_format_comments'] = $this->input->post('ultrasound_visit_format_comments');
		$data['visit_charge_id'] = $this->input->post('visit_charge_id');
		
		$this->db->insert('ultrasound_visit_format_comment', $data);
	}
	
	function update_existing_ultrasound_comment($visit_charge_id)
	{
		$data['ultrasound_visit_format_comments'] = $this->input->post('ultrasound_visit_format_comments');
		$where['visit_charge_id'] = $visit_charge_id;
		
		$this->db->where($where);
		$this->db->update('ultrasound_visit_format_comment', $data);
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
	
	public function save_ultrasound_charge($visit_id, $service_charge_id)
	{
		//get service charge amount
		$this->db->where('service_charge_id', $service_charge_id);
		$query = $this->db->get('service_charge');
		
		$service_charge = 0;
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$service_charge = $row->service_charge_amount;
		}
		$visit_charge_data = array(
			"visit_id" => $visit_id,
			"service_charge_id" => $service_charge_id,
			"created_by" => $this->session->userdata("personnel_id"),
			"date" => date("Y-m-d"),
			"visit_charge_amount" => $service_charge
		);
		if($this->db->insert('visit_charge', $visit_charge_data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
}
?>