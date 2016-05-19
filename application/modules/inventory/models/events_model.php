<?php

class events_model extends CI_Model 
{
	public function all_events()
	{
		$this->db->where('event_deleted = 0 AND event_status = 1');
		$query = $this->db->get('event');
		
		return $query;
	}
	
	public function get_request_event($request_id)
	{
		$this->db->where('request_id = '.$request_id);
		$this->db->select('*');
		$query = $this->db->get('request_event');
		return $query;
	}
	
	public function all_unselected_logistics($request_event_id)
	{
		$query = $this->db->query('select * from logistics where logistic_status = 1 and logistic_id not in(select logistic_id from request_event_logistic where request_event_id='.$request_event_id.')');
		
		return $query;
	}
	
	public function add_event_logistic($request_event_id)
	{
		$logistic_id = $this->input->post('logistic_id');
		$quantity = $this->input->post('quantity');
		$days = $this->input->post('days');
		$logistic_cost = $this->input->post('logistic_cost');
		$data = array(
						
			'logistic_id'=>$logistic_id,
			'request_event_id'=>$request_event_id,
			'request_event_logistic_quantity'=>$quantity,
			'request_event_logistic_price'=>$logistic_cost,
			'request_event_logistic_days'=>$days
			);
				
		if($this->db->insert('request_event_logistic', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_request_event_id($request_id)
	{
		$this->db->select('request_event_id');
		$this->db->where('request_event.request_id = '.$request_id);
		$query = $this->db->get('request_event');
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$request_event_id = $key->request_event_id;
			}
		}
		else
		{
			$request_event_id = 0;
		}
		return $request_event_id;	
	}
	
	public function get_event_logistics($request_event_id)
	{
		$this->db->select('logistics.*, request_event_logistic.*');
		$this->db->where('logistics.logistic_id = request_event_logistic.logistic_id AND logistics.logistic_deleted = 0 AND request_event_logistic.deleted = 0 AND request_event_logistic.request_event_id = '.$request_event_id);
		$query = $this->db->get('logistics, request_event_logistic');
		
		return $query;
	}
}