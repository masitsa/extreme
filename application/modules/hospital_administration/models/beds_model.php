<?php

class Beds_model extends CI_Model 
{	
	/*
	*	Retrieve all beds
	*
	*/
	public function all_beds()
	{
		$this->db->where('bed_status = 1');
		$query = $this->db->get('bed');
		
		return $query;
	}
	
	/*
	*	Retrieve all beds
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_beds($table, $where, $per_page, $page, $order = 'bed_number', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function create_bed_number($room_id)
	{
		$room_preffix = '';
		$ward_preffix = '';
		
		//get room preffix
		$this->db->where('room_id = '.$room_id);
		$query = $this->db->get('room');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$room_preffix = $row->room_preffix;
			$ward_id = $row->ward_id;
		
			//get ward preffix
			$this->db->where('ward_id = '.$ward_id);
			$query = $this->db->get('ward');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$ward_preffix = $row->ward_preffix;
			}
		}
		
		$bed_preffix = $ward_preffix.'-'.$room_preffix.'-';
		
		//select room numbers
		$this->db->from('bed');
		$this->db->where('bed_number LIKE \''.$bed_preffix.'%\'');
		$this->db->select('MAX(bed_number) AS number');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$real_number = str_replace($bed_preffix, "", $number);
			$real_number++;//go to the next number
			$number = $bed_preffix.sprintf('%03d', $real_number);
		}
		else{//start generating receipt numbers
			$number = $bed_preffix.sprintf('%03d', 1);
		}
		
		return $number;
	}
	
	/*
	*	Add a new bed
	*	@param string $image_name
	*
	*/
	public function add_bed($room_id)
	{
		$status = $this->input->post('bed_status');
		//Check room bed capacity
		if($this->check_room_capacity_against_active_beds($room_id, $status))
		{
			$data = array(
					'bed_number'=>$this->beds_model->create_bed_number($room_id),
					'room_id'=>$room_id,
					'cash_price'=>$this->input->post('cash_price'),
					'insurance_price'=>$this->input->post('insurance_price'),
					'bed_status'=>$this->input->post('bed_status'),
					'created'=>date('Y-m-d H:i:s'),
					'created_by'=>$this->session->userdata('personnel_id'),
					'modified_by'=>$this->session->userdata('personnel_id')
				);
				
			if($this->db->insert('bed', $data))
			{
				return TRUE;
			}
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add a new bed. Please try again.');
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing bed
	*	@param string $image_name
	*	@param int $bed_id
	*
	*/
	public function update_bed($room_id, $bed_id)
	{
		$data = array(
				'cash_price'=>$this->input->post('cash_price'),
				'insurance_price'=>$this->input->post('insurance_price'),
				'bed_status'=>$this->input->post('bed_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('bed_id', $bed_id);
		if($this->db->update('bed', $data))
		{
			return TRUE;
		}
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update bed. Please try again.');
			return FALSE;
		}
	}
	
	/*
	*	get a single bed's details
	*	@param int $bed_id
	*
	*/
	public function get_bed($bed_id)
	{
		//retrieve all users
		$this->db->from('bed');
		$this->db->select('*');
		$this->db->where('bed_id = '.$bed_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing bed
	*	@param int $bed_id
	*
	*/
	public function delete_bed($bed_id)
	{
		if($this->db->delete('bed', array('bed_id' => $bed_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated bed
	*	@param int $bed_id
	*
	*/
	public function activate_bed($room_id, $bed_id)
	{
		$status = 1;
		//Check room bed capacity
		if($this->check_room_capacity_against_active_beds($room_id, $status))
		{
			$data = array(
					'bed_status' => 1
				);
			$this->db->where('bed_id', $bed_id);
			
			if($this->db->update('bed', $data))
			{
				return TRUE;
			}
			else
			{
				$this->session->set_userdata('error_message', 'Unable to add a new bed. Please try again.');
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated bed
	*	@param int $bed_id
	*
	*/
	public function deactivate_bed($bed_id)
	{
		$data = array(
				'bed_status' => 0
			);
		$this->db->where('bed_id', $bed_id);
		
		if($this->db->update('bed', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function check_room_capacity_against_active_beds($room_id, $status)
	{
		if($status == 0)
		{
			return TRUE;
		}
		
		else
		{
			$room_capacity = 0;
			$active_beds = 0;
			
			//get room details
			$this->db->where('room_id = '.$room_id);
			$query = $this->db->get('room');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$room_capacity = $row->room_capacity;
				$ward_id = $row->ward_id;
			}
			
			//get active beds details
			$this->db->where('bed_status = 1 AND room_id = '.$room_id);
			$this->db->from('bed');
			$active_beds = $this->db->count_all_results();
			
			if($active_beds >= $room_capacity)
			{
				$this->session->set_userdata('error_message', 'Unable to add a new active bed. The bed count will exceed the allowed room capacity. Either <a href="'.site_url().'hospital-administration/edit-room/'.$ward_id.'/'.$room_id.'">click here</a> to increase the room capacity or add the bed as inactive.');
			
				return FALSE;
			}
			
			else
			{
				return TRUE;
			}
		}
	}
}
?>