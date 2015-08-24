<?php
class Dental_model extends CI_Model 
{
	function submitvisitbilling($procedure_id,$visit_id,$suck){
		$visit_data = array('procedure_id'=>$procedure_id,'visit_id'=>$visit_id,'units'=>$suck);
		$this->db->insert('visit_procedure', $visit_data);
	}
}
?>