<?php

class Medical_admin_model extends CI_Model 
{


	function objective_findings(){

		$table = "objective_findings,objective_findings_class";
		$where = "objective_findings_class.objective_findings_class_id = objective_findings.objective_findings_class_id";
		$items = "objective_findings.objective_findings_id, objective_findings.objective_findings_name,objective_findings_class.objective_findings_class_name";
		$order = "objective_findings_class.objective_findings_class_name";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
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