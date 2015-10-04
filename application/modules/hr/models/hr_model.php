<?php

class Hr_model extends CI_Model 
{	
	public function add_job_title()
	{
		$data['job_title_name'] = $this->input->post('job_title_name');
		
		if($this->db->insert('job_title', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function edit_job_title($job_title_id)
	{
		$data['job_title_name'] = $this->input->post('job_title_name');
		
		$this->db->where('job_title_id', $job_title_id);
		if($this->db->update('job_title', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function delete_job_title($job_title_id)
	{
		$this->db->where('job_title_id', $job_title_id);
		if($this->db->delete('job_title'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function get_non_personnel_roles($personnel_id)
	{
		$this->db->where('inventory_level_status.inventory_level_status_id NOT IN (SELECT personnel_approval.approval_status_id FROM personnel_approval WHERE personnel_id = '.$personnel_id.')');
		$query = $this->db->get('inventory_level_status');

		return $query;
	}

	public function get_personnel_approvals($personnel_id)
	{
		$this->db->where('inventory_level_status.inventory_level_status_id = personnel_approval.approval_status_id AND personnel_approval.personnel_id = '.$personnel_id);
		$query = $this->db->get('inventory_level_status,personnel_approval');

		return $query;
	}
	public function get_non_assigned_stores($personnel_id)
	{
		$this->db->where('store.store_id NOT IN (SELECT personnel_store.store_id FROM personnel_store WHERE personnel_id = '.$personnel_id.')');
		$query = $this->db->get('store');

		return $query;
	}
	public function get_personnel_stores($personnel_id)
	{
		$this->db->where('store.store_id = personnel_store.store_id AND personnel_store.personnel_id = '.$personnel_id);
		$query = $this->db->get('store,personnel_store');

		return $query;
	}
}
?>