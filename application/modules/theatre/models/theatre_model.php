<?php
class Theatre_model extends CI_Model 
{
	function submitvisitbilling($procedure_id,$visit_id,$suck){
		$visit_data = array('procedure_id'=>$procedure_id,'visit_id'=>$visit_id,'units'=>$suck);
		$this->db->insert('visit_procedure', $visit_data);
	}

	function get_theatre_visit_test($visit_id ){
		$table = "visit_department";
		$where = "visit_id = ".$visit_id;
		$items = "department_id";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}

	function get_theatre_visit_item($visit_id)
	{
		$table = "visit_charge, service, service_charge";
		$where = "visit_charge_delete = 0 AND (service.service_name = 'Surgery' OR service.service_name = 'surgery')
		AND visit_charge.service_charge_id = service_charge.service_charge_id 
		AND service_charge.service_id = service.service_id AND service.branch_code = '".$this->session->userdata('branch_code')."' AND visit_charge.visit_id = ". $visit_id;
		$items = "*";
		$order = "visit_charge.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_surgeries($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function get_inpatient_surgeries($table, $where,$order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('service_charge.service_charge_amount, service_charge.service_charge_id, service_charge.service_charge_name');
		$this->db->where($where);
		$this->db->order_by($order,'ASC');
		$query = $this->db->get('');
		
		return $query;
	}
	
	function get_surgery_visit($visit_id, $service_charge_id=NULL){
		$table = "visit_charge";
		$where = "visit_charge_delete = 0 AND visit_id = ". $visit_id ." AND service_charge_id = ". $service_charge_id;
		
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function save_surgery_visit($visit_id, $service_charge_id)
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
	
	function get_surgery_visit2($visit_id)
	{
		$table = "visit_charge, service_charge, service";
		$where = 'visit_charge_delete = 0 AND service.service_id = service_charge.service_id AND (service.service_name = "Surgery" OR service.service_name = "surgery") AND visit_charge_delete = 0 AND service_charge.service_charge_id = visit_charge.service_charge_id AND visit_charge.visit_id = '.$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}
	function get_surgery_inpatient($visit_id,$service_id)
	{
		$table = "visit_charge, service_charge, service";
		$where = 'visit_charge_delete = 0 AND service.service_id = service_charge.service_id  AND visit_charge_delete = 0 AND service_charge.service_charge_id = visit_charge.service_charge_id AND service.service_id = '.$service_id.' AND visit_charge.visit_id = '.$visit_id;
		$items = "*";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
		
	}

	function delete_visit_theatre($visit_charge_id)
	{
		$table = "visit_charge";
		$where = "visit_charge_id = ". $visit_charge_id;
		$data['visit_charge_delete'] = 1;
		$data['deleted_by'] = $this->session->userdata('personnel_id');
		$data['deleted_on'] = date('Y-m-d H:i:s');
	
		$this->db->where($where);
		$this->db->update($table, $data);
	}
}
?>