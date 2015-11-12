<?php

class Xray_model extends CI_Model 
{

	function get_xray_visit2($visit_id){
		$table = "visit_charge, service_charge, service";
		$where = 'visit_charge_delete = 0 AND service.service_id = service_charge.service_id AND (service.service_name = "X Ray" OR service.service_name = "xray" OR service.service_name = "XRay" OR service.service_name = "xray" OR service.service_name = "Xray") AND visit_charge_delete = 0 AND service_charge.service_charge_id = visit_charge.service_charge_id AND visit_charge.visit_id = '.$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_xray_visit_test($visit_id ){
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

	function get_xray_visit_result($visit_charge_id){
		$table = "xray_visit_results";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "*";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_test_old($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "xray, visit_charge, xray_class, xray_format, xray_visit_results, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.xray_id = xray.xray_id 
		AND xray.xray_class_id = xray_class.xray_class_id 
		AND xray_format.xray_id = xray.xray_id 
		AND visit_charge.visit_charge_id = xray_visit_results.visit_charge_id 
		AND xray_visit_results.xray_visit_result_format = xray_format.xray_format_id AND visit_charge.visit_charge_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS xray_name, xray_class.xray_class_name, xray.xray_units, xray.xray_malelowerlimit, xray.xray_malelupperlimit, xray.xray_femalelowerlimit, xray.xray_femaleupperlimit,xray_format.xray_format_id, visit_charge.visit_charge_id AS xray_visit_id,  visit_charge.visit_charge_results AS xray_visit_result, xray_format.xray_formatname, xray_format.xray_format_units, xray_format.xray_format_malelowerlimit, xray_format.xray_format_maleupperlimit, xray_format.xray_format_femalelowerlimit, xray_format.xray_format_femaleupperlimit, xray_visit_results.xray_visit_results_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_test($visit_charge_id){
			
		// $_SESSION['test'] = 0;
		$this->session->set_userdata('test',0);

		$table = "xray, visit_xray, xray_class, xray_format, xray_visit_results, service_charge";
		$where = "visit_xray.service_charge_id = service_charge.service_charge_id 
		AND service_charge.xray_id = xray.xray_id 
		AND visit_xray.visit_xray_status = 1 
		AND xray.xray_class_id = xray_class.xray_class_id 
		AND xray_format.xray_id = xray.xray_id 
		AND visit_xray.visit_xray_id = xray_visit_results.visit_charge_id 
		AND xray_visit_results.xray_visit_result_format = xray_format.xray_format_id AND visit_xray.visit_xray_id = ".$visit_charge_id;
		$items = "service_charge.service_charge_name AS xray_name, xray_class.xray_class_name, xray.xray_units, xray.xray_malelowerlimit, xray.xray_malelupperlimit, xray.xray_femalelowerlimit, xray.xray_femaleupperlimit,xray_format.xray_format_id, visit_xray.visit_xray_id AS xray_visit_id,  visit_xray.visit_xray_results AS xray_visit_result, xray_format.xray_formatname, xray_format.xray_format_units, xray_format.xray_format_malelowerlimit, xray_format.xray_format_maleupperlimit, xray_format.xray_format_femalelowerlimit, xray_format.xray_format_femaleupperlimit, xray_visit_results.xray_visit_results_result, visit_xray.visit_xray_comment";
		$order = "visit_xray.visit_xray_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}


	function get_m_test_old($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "xray, visit_charge, xray_class, service_charge";
		
		$where = "visit_charge.visit_charge_id = $visit_charge_id
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.xray_id = xray.xray_id 
		AND xray.xray_class_id = xray_class.xray_class_id
		AND visit_charge.visit_charge_id NOT IN (SELECT visit_charge_id FROM xray_visit_results)";
		
		$items = "service_charge.service_charge_name AS xray_name, xray_class.xray_class_name, xray.xray_units, xray.xray_malelowerlimit, xray.xray_malelupperlimit, xray.xray_femalelowerlimit, xray.xray_femaleupperlimit, visit_charge.visit_charge_id AS xray_visit_id, visit_charge.visit_charge_results AS xray_visit_result, visit_charge.visit_charge_comment";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_m_test($visit_charge_id)
	{
		$this->session->set_userdata('test',1);
		$table = "xray, visit_xray, xray_class, service_charge";
		
		$where = "visit_xray.visit_xray_id = $visit_charge_id
		AND visit_xray.service_charge_id = service_charge.service_charge_id 
		AND service_charge.xray_id = xray.xray_id 
		AND visit_xray.visit_xray_status = 1 
		AND xray.xray_class_id = xray_class.xray_class_id
		AND visit_xray.visit_xray_id NOT IN (SELECT visit_charge_id FROM xray_visit_results)";
		
		$items = "service_charge.service_charge_name AS xray_name, xray_class.xray_class_name, xray.xray_units, xray.xray_malelowerlimit, xray.xray_malelupperlimit, xray.xray_femalelowerlimit, xray.xray_femaleupperlimit, visit_xray.visit_xray_id AS xray_visit_id, visit_xray.visit_xray_results AS xray_visit_result, visit_xray.visit_xray_comment";
		$order = "visit_xray.visit_xray_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	
	function get_test_comment($visit_charge_id){

		$table = "xray_visit_format_comment";
		$where = "visit_charge_id = ".$visit_charge_id;
		$items = "xray_visit_format_comments";
		$order = "visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	public function get_xrays($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_inpatient_xrays($table, $where,$order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('');
		
		return $query;
	}

	function get_xray_visit_old($visit_id, $service_charge_id=NULL){
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
	function get_xray_visit($visit_id, $service_charge_id=NULL){
		$table = "visit_charge";
		$where = "visit_charge_delete = 0 AND visit_id = ". $visit_id ." AND service_charge_id = ". $service_charge_id;
		
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function get_xray_visit_item_old($visit_id){
		$table = "visit_charge, service, service_charge";
		$where = "(service.service_name = 'X Ray' OR service.service_name = 'xray' OR service.service_name = 'XRay' OR service.service_name = 'xray' OR service.service_name = 'Xray') 
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_charge.visit_id = ". $visit_id;
		$items = "visit_charge.visit_charge_id";
		$order = "visit_charge.visit_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_xray_visit_item($visit_id){
		$table = "visit_charge, service, service_charge";
		$where = "visit_charge_delete = 0 AND (service.service_name = 'X Ray' OR service.service_name = 'xray' OR service.service_name = 'XRay' OR service.service_name = 'xray' OR service.service_name = 'Xray')
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND visit_charge.visit_id = ". $visit_id;
		$items = "*";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function save_xray_visit($visit_id, $service_charge_id)
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
	

	public function check_visit_charge_xray($service_charge_id,$visit_id){
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
	
	function save_xray_visit_format($visit_id, $service_charge_id, $xray_format_id){
		$table = "visit_xray";
		$where = "visit_id = ". $visit_id. " AND service_charge_id = ". $service_charge_id;
		$items = "visit_xray_id";
		$order = "visit_id";


		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach ($result as $key): 
				# code...
				$visit_xray_id = $key->visit_xray_id;
			endforeach;
			
		}
		$visit_data = array('visit_charge_id'=>$visit_xray_id,'xray_visit_result_format'=>$xray_format_id,'visit_id'=>$visit_id);
		$this->db->insert('xray_visit_results', $visit_data);

	}
	
	function delete_cost($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$this->db->delete('visit_charge');
	}

	function delete_visit_xray($visit_charge_id)
	{
		$table = "visit_charge";
		$where = "visit_charge_id = ". $visit_charge_id;
		$data['visit_charge_delete'] = 1;
		$data['deleted_by'] = $this->session->userdata('personnel_id');
		$data['deleted_on'] = date('Y-m-d H:i:s');
	
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	function get_xray($visit_id){
		
		$this->session->set_userdata('test',1);

		$table = "xray, visit_charge, service_charge, xray_class";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.xray_id = xray.xray_id 
		AND xray.xray_class_id = xray_class.xray_class_id AND xray.xray_class_id = xray_class.xray_class_id AND visit_charge.visit_id = ".$visit_id;
		$items = "service_charge.service_charge_name AS xray_name, xray_class.xray_class_name AS class_name, service_charge.service_charge_amount AS xray_price, visit_charge.visit_charge_id AS xray_visit_id";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;


		
	}
	function save_tests_old($res, $xray){
		$data['visit_charge_results'] = $res;
	
		$this->db->where('visit_charge_id', $xray);
		if($this->db->update('visit_charge', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
		
		
	}

	function save_tests($res, $xray){
		$data['visit_xray_results'] = $res;
	
		$this->db->where('visit_xray_id', $xray);
		if($this->db->update('visit_xray', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}

	function save_comment($comment, $visit_charge_id){

		$table = "visit_xray";
		$where = "visit_xray_id = ".$visit_charge_id;
		$items = "visit_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		if(count($result) > 0){
			foreach($result as $rs):
				$visit_id = $rs->visit_id;
			endforeach;
		}
		$data['xray_visit_comment'] = $comment;
		
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
  	function get_xray_personnel($id){
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

	
	function get_xray_comments($visit_charge_id)
	{
		$this->db->where('visit_charge_id', $visit_charge_id);
		$query = $this->db->get('xray_visit_format_comment');
		
		return $query;
	}
	
	function save_new_xray_comment()
	{
		$data['xray_visit_format_comments'] = $this->input->post('xray_visit_format_comments');
		$data['visit_charge_id'] = $this->input->post('visit_charge_id');
		
		$this->db->insert('xray_visit_format_comment', $data);
	}
	
	function update_existing_xray_comment($visit_charge_id)
	{
		$data['xray_visit_format_comments'] = $this->input->post('xray_visit_format_comments');
		$where['visit_charge_id'] = $visit_charge_id;
		
		$this->db->where($where);
		$this->db->update('xray_visit_format_comment', $data);
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
	
	public function save_xray_charge($visit_id, $service_charge_id)
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