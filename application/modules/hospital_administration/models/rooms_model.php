<?php

class Rooms_model extends CI_Model 
{	
	/*
	*	Retrieve all rooms
	*
	*/
	public function all_rooms()
	{
		$this->db->where('room_status = 1');
		$query = $this->db->get('room');
		
		return $query;
	}
	
	/*
	*	Retrieve all rooms
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_rooms($table, $where, $per_page, $page, $order = 'room_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new room
	*	@param string $image_name
	*
	*/
	public function add_room($ward_id)
	{
		$room_name = $this->input->post('room_name');
		$data = array(
				'room_preffix'=>$this->admin_model->create_preffix($room_name),
				'room_name'=>$room_name,
				'ward_id'=>$ward_id,
				'room_status'=>$this->input->post('room_status'),
				'room_capacity'=>$this->input->post('room_capacity'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing room
	*	@param string $image_name
	*	@param int $room_id
	*
	*/
	public function update_room($room_id)
	{
		$data = array(
				'room_name'=>$this->input->post('room_name'),
				'room_capacity'=>$this->input->post('room_capacity'),
				'room_status'=>$this->input->post('room_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('room_id', $room_id);
		if($this->db->update('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single room's details
	*	@param int $room_id
	*
	*/
	public function get_room($room_id)
	{
		//retrieve all users
		$this->db->from('room');
		$this->db->select('*');
		$this->db->where('room_id = '.$room_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing room
	*	@param int $room_id
	*
	*/
	public function delete_room($room_id)
	{
		if($this->db->delete('room', array('room_id' => $room_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated room
	*	@param int $room_id
	*
	*/
	public function activate_room($room_id)
	{
		$data = array(
				'room_status' => 1
			);
		$this->db->where('room_id', $room_id);
		
		if($this->db->update('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated room
	*	@param int $room_id
	*
	*/
	public function deactivate_room($room_id)
	{
		$data = array(
				'room_status' => 0
			);
		$this->db->where('room_id', $room_id);
		
		if($this->db->update('room', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>