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
	
	public function get_total_daily_quotes($date = NULL)
	{
		if ($date == NULL)
		{
			$date = date ('Y-m-d');
		}
		//select the total quotes from the database
		$this->db->select('requests.*, SUM(request_item.request_item_quantity * request_item.request_item_price) AS total_quotes');
		$this->db->where('requests.request_id = request_item.request_id AND requests.request_date = \''.$date.'\'');
		$this->db->from('requests, request_item');
		$query= $this->db->get();
		
		$result = $query->row();
		$total = $result->total_quotes;
		
		if(empty($total) || ($total == NULL))
		{
			$total = 0;
		}
		return $total;
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