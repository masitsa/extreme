<?php

class Charts_model extends CI_Model 
{
	
	public function get_patient_totals()
	{
		$this->db->select('COUNT(visit_id) AS queue_total');
		$this->db->where('close_card = 0');
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->queue_total;
	}
	
	public function get_daily_balance($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		//select the user by email from the database
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS total_amount');
		$this->db->where('visit_charge_timestamp LIKE \''.$date.'%\'');
		$this->db->from('visit_charge');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_amount;
	}
}
?>