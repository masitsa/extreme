<?php

class Leave_model extends CI_Model 
{
	public function get_assigned_leave($year, $month)
	{
		$table = "leave_duration";
		$where = "start_date LIKE '".$year."-".$month."-%'";
		$items = "DISTINCT(start_date) AS start_";
		$order = "start_";
		
		$this->db->where($where);
		$this->db->order_by($order);
		$this->db->select($items);
		
		return $this->db->get($table);
	}
	
	public function get_day_leave($date)
	{
		//retrieve all users
 		$this->db->where('start_date', $date);
		$this->db->from('leave_duration, leave_type, personnel');
		$this->db->select('leave_duration.*, leave_type.leave_type_name, personnel.personnel_fname, personnel.personnel_onames');
		$this->db->order_by('leave_type.leave_type_name');
		$this->db->where('personnel.personnel_id = leave_duration.personnel_id AND leave_duration.leave_type_id = leave_type.leave_type_id AND start_date = \''.$date.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing leave_duration
	*	@param int $leave_duration_id
	*
	*/
	public function delete_leave_duration($leave_duration_id)
	{
		//delete parent
		if($this->db->delete('leave_duration', array('leave_duration_id' => $leave_duration_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated leave_duration
	*	@param int $leave_duration_id
	*
	*/
	public function activate_leave_duration($leave_duration_id)
	{
		$data = array(
				'leave_duration_status' => 1
			);
		$this->db->where('leave_duration_id', $leave_duration_id);
		
		if($this->db->update('leave_duration', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated leave_duration
	*	@param int $leave_duration_id
	*
	*/
	public function deactivate_leave_duration($leave_duration_id)
	{
		$data = array(
				'leave_duration_status' => 0
			);
		$this->db->where('leave_duration_id', $leave_duration_id);
		
		if($this->db->update('leave_duration', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>