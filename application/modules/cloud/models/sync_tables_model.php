<?php

class Sync_tables_model extends CI_Model 
{	
	/*
	*	Retrieve all sync_tables
	*
	*/
	public function all_sync_tables()
	{
		$this->db->where('sync_table_status = 1');
		$query = $this->db->get('sync_table');
		
		return $query;
	}
	
	/*
	*	Retrieve all sync_tables
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_sync_tables($table, $where, $per_page, $page, $order = 'sync_table_name', $order_method = 'ASC')
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
	*	Add a new sync_table
	*	@param string $image_name
	*
	*/
	public function add_sync_table()
	{
		$data = array(
				'branch_code'=>$this->input->post('branch_code'),
				'table_key_name'=>$this->input->post('table_key_name'),
				'sync_table_name'=>$this->input->post('sync_table_name'),
				'sync_table_cloud_save_function'=>$this->input->post('sync_table_cloud_save_function'),
				'sync_table_status'=>$this->input->post('sync_table_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('sync_table', $data))
		{
			return TRUE;
		}
		else
		{
			$this->session->set_userdata('error_message', 'Unable to add a new sync_table. Please try again.');
			return FALSE;
		}
	}
	
	/*
	*	Update an existing sync_table
	*	@param string $image_name
	*	@param int $sync_table_id
	*
	*/
	public function update_sync_table($sync_table_id)
	{
		$data = array(
				'branch_code'=>$this->input->post('branch_code'),
				'table_key_name'=>$this->input->post('table_key_name'),
				'sync_table_name'=>$this->input->post('sync_table_name'),
				'sync_table_cloud_save_function'=>$this->input->post('sync_table_cloud_save_function'),
				'sync_table_status'=>$this->input->post('sync_table_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('sync_table_id', $sync_table_id);
		if($this->db->update('sync_table', $data))
		{
			return TRUE;
		}
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update sync_table. Please try again.');
			return FALSE;
		}
	}
	
	/*
	*	get a single sync_table's details
	*	@param int $sync_table_id
	*
	*/
	public function get_sync_table($sync_table_id)
	{
		//retrieve all users
		$this->db->from('sync_table');
		$this->db->select('*');
		$this->db->where('sync_table_id = '.$sync_table_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing sync_table
	*	@param int $sync_table_id
	*
	*/
	public function delete_sync_table($sync_table_id)
	{
		if($this->db->delete('sync_table', array('sync_table_id' => $sync_table_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated sync_table
	*	@param int $sync_table_id
	*
	*/
	public function activate_sync_table($sync_table_id)
	{
		$data = array(
				'sync_table_status' => 1
			);
		$this->db->where('sync_table_id', $sync_table_id);
		
		if($this->db->update('sync_table', $data))
		{
			return TRUE;
		}
		else
		{
			$this->session->set_userdata('error_message', 'Unable to add a new sync_table. Please try again.');
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated sync_table
	*	@param int $sync_table_id
	*
	*/
	public function deactivate_sync_table($sync_table_id)
	{
		$data = array(
				'sync_table_status' => 0
			);
		$this->db->where('sync_table_id', $sync_table_id);
		
		if($this->db->update('sync_table', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>