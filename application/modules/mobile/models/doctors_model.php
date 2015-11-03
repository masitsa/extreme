<?php

class Doctors_model extends CI_Model 
{
	/*
	*	Retrieve visits
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function count_items($table, $where, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	public function get_all_bookings($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('bookings.booking_date, bookings.booking_datetime','DESC');
		$this->db->group_by('bookings.visit_id');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_total_collections_due()
	{
		$this->db->select('SUM(collected_amount) AS total_amount');
		$this->db->where('booking_date = "'.date('Y-m-d').'" AND personnel_id ='.$this->session->userdata('personnel_id'));
		$query = $this->db->get('bookings');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$total_amount = $key->total_amount;
			}
		}
		else
		{
			$total_amount = 0;
		}
		return $total_amount;
	}
}
	