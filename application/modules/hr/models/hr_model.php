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
}
?>