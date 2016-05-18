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
}