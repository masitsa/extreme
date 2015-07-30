<?php

class Jobs_model extends CI_Model 
{
	
	/*
	*	Retrieve all jobs
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_jobs($table, $where, $per_page, $page, $order, $order_method)
	{
		//retrieve all jobs
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new job to the database
	*
	*/
	public function add_job()
	{
		$data = array(
				'job_status'=>$this->input->post('job_status'),
				'job_description'=>$this->input->post('job_description'),
				'job_title'=>$this->input->post('job_title'),
				'created'=>date('Y-m-d H:i:s')
			);
			
		if($this->db->insert('jobs', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing job
	*	@param int $job_id
	*
	*/
	public function edit_job($job_id)
	{
		$data = array(
				'job_status'=>$this->input->post('job_status'),
				'job_description'=>$this->input->post('job_description'),
				'job_title'=>$this->input->post('job_title'),
			);
		
		$this->db->where('job_id', $job_id);
		
		if($this->db->update('jobs', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve a single job
	*	@param int $job_id
	*
	*/
	public function get_job($job_id)
	{
		//retrieve all jobs
		$this->db->from('jobs');
		$this->db->select('*');
		$this->db->where('job_id = '.$job_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing job
	*	@param int $job_id
	*
	*/
	public function delete_job($job_id)
	{
		if($this->db->delete('jobs', array('job_id' => $job_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated job
	*	@param int $job_id
	*
	*/
	public function activate_job($job_id)
	{
		$data = array(
				'job_status' => 1
			);
		$this->db->where('job_id', $job_id);
		
		if($this->db->update('jobs', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated job
	*	@param int $job_id
	*
	*/
	public function deactivate_job($job_id)
	{
		$data = array(
				'job_status' => 0
			);
		$this->db->where('job_id', $job_id);
		
		if($this->db->update('jobs', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>