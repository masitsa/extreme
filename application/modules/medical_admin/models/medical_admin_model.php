<?php

class Medical_admin_model extends CI_Model 
{
	function objective_findings()
	{
		$table = "objective_findings";
		$where = "objective_findings_status = 1";
		$items = "*";
		$order = "objective_findings.objective_findings_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		return $result;		
	}

	public function get_objective_findings_classes()
	{
		$table = "objective_findings_class";
		$order = "objective_findings_class_name";
		
		$this->db->order_by($order, 'ASC');
		$result = $this->db->get($table);
		return $result;
	}

	function visit_objective($visit_id){

		$table = "visit";
		$where = "visit_id = ". $visit_id;
		$items = "visit_objective_findings";
		$order = "visit_id";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
								
	}
}
?>