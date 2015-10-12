<?php

class Schedules_model extends CI_Model 
{	
	/*
	*	Retrieve all schedules
	*
	*/
	public function all_schedules()
	{
		$this->db->where('schedule_status = 1');
		$query = $this->db->get('schedule');
		
		return $query;
	}
	/*
	*	Retrieve latest schedule
	*
	*/
	public function latest_schedule()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('schedule');
		
		return $query;
	}
	/*
	*	Retrieve all parent schedules
	*
	*/
	public function all_parent_schedules()
	{
		$this->db->where('schedule_status = 1 AND schedule_parent = 0');
		$this->db->order_by('schedule_name', 'ASC');
		$query = $this->db->get('schedule');
		
		return $query;
	}
	/*
	*	Retrieve all children schedules
	*
	*/
	public function all_child_schedules()
	{
		$this->db->where('schedule_status = 1 AND schedule_parent > 0');
		$this->db->order_by('schedule_name', 'ASC');
		$query = $this->db->get('schedule');
		
		return $query;
	}
	
	/*
	*	Retrieve all schedules
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_schedules($table, $where, $per_page, $page, $order = 'schedule_name', $order_method = 'ASC')
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
	*	Add a new schedule
	*	@param string $image_name
	*
	*/
	public function add_schedule()
	{
		$data = array(
				'schedule_name'=>$this->input->post('schedule_name'),
				'schedule_status'=>1,
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('schedule', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing schedule
	*	@param string $image_name
	*	@param int $schedule_id
	*
	*/
	public function update_schedule($schedule_id)
	{
		$data = array(
				'schedule_name'=>$this->input->post('schedule_name'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('schedule_id', $schedule_id);
		if($this->db->update('schedule', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single schedule's children
	*	@param int $schedule_id
	*
	*/
	public function get_sub_schedules($schedule_id)
	{
		//retrieve all users
		$this->db->from('schedule');
		$this->db->select('*');
		$this->db->where('schedule_parent = '.$schedule_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single schedule's details
	*	@param int $schedule_id
	*
	*/
	public function get_schedule($schedule_id)
	{
		//retrieve all users
		$this->db->from('schedule');
		$this->db->select('*');
		$this->db->where('schedule_id = '.$schedule_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing schedule
	*	@param int $schedule_id
	*
	*/
	public function delete_schedule($schedule_id)
	{
		if($this->db->delete('schedule', array('schedule_id' => $schedule_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated schedule
	*	@param int $schedule_id
	*
	*/
	public function activate_schedule($schedule_id)
	{
		$data = array(
				'schedule_status' => 1
			);
		$this->db->where('schedule_id', $schedule_id);
		

		if($this->db->update('schedule', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated schedule
	*	@param int $schedule_id
	*
	*/
	public function deactivate_schedule($schedule_id)
	{
		$data = array(
				'schedule_status' => 0
			);
		$this->db->where('schedule_id', $schedule_id);
		
		if($this->db->update('schedule', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new schedule
	*	@param string $image_name
	*
	*/
	public function add_personnel_schedule($schedule_id)
	{
		$data = array(
				'personnel_id'=>$this->input->post('personnel_id'),
				'schedule_date'=>$this->input->post('date'),
				'schedule_start_time'=>date('H:i:s',strtotime($this->input->post('start_time'))),
				'schedule_end_time'=>date('H:i:s',strtotime($this->input->post('end_time'))),
				'schedule_item_status'=>1,
				'schedule_id'=>$schedule_id,
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('schedule_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new schedule
	*	@param string $image_name
	*
	*/
	public function fill_timesheet($schedule_id, $personnel_id)
	{
		$data = array(
				'personnel_id'=>$personnel_id,
				'schedule_date'=>$this->input->post('date'),
				'schedule_start_time'=>date('H:i:s',strtotime($this->input->post('start_time'))),
				'schedule_end_time'=>date('H:i:s',strtotime($this->input->post('end_time'))),
				'schedule_item_status'=>1,
				'schedule_id'=>$schedule_id,
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('schedule_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing schedule_item
	*	@param string $image_name
	*	@param int $schedule_item_id
	*
	*/
	public function update_schedule_item($schedule_item_id)
	{
		$data = array(
				'schedule_item_name'=>$this->input->post('schedule_item_name'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('schedule_item_id', $schedule_item_id);
		if($this->db->update('schedule_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing schedule_item
	*	@param int $schedule_item_id
	*
	*/
	public function delete_schedule_item($schedule_item_id)
	{
		if($this->db->delete('schedule_item', array('schedule_item_id' => $schedule_item_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated schedule_item
	*	@param int $schedule_item_id
	*
	*/
	public function activate_schedule_item($schedule_item_id)
	{
		$data = array(
				'schedule_item_status' => 1
			);
		$this->db->where('schedule_item_id', $schedule_item_id);
		

		if($this->db->update('schedule_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated schedule_item
	*	@param int $schedule_item_id
	*
	*/
	public function deactivate_schedule_item($schedule_item_id)
	{
		$data = array(
				'schedule_item_status' => 0
			);
		$this->db->where('schedule_item_id', $schedule_item_id);
		
		if($this->db->update('schedule_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_all_schedule_items($schedule_id)
	{
		$this->db->where('schedule_item.personnel_id = personnel.personnel_id AND schedule_item.schedule_id = '.$schedule_id);
		$query = $this->db->get('schedule_item, personnel');
		
		return $query;
	}
}
?>