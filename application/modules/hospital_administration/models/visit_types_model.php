<?php

class Visit_types_model extends CI_Model 
{	
	/*
	*	Retrieve all visit_types
	*
	*/
	public function all_visit_types()
	{
		$this->db->where('visit_type_status = 1');
		$query = $this->db->get('visit_type');
		
		return $query;
	}
	
	/*
	*	Retrieve all visit_types
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_visit_types($table, $where, $per_page, $page, $order = 'visit_type_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->join('insurance_company', 'insurance_company.insurance_company_id = visit_type.insurance_company_id', 'left');
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new visit_type
	*	@param string $image_name
	*
	*/
	public function add_visit_type()
	{
		$data = array(
				'insurance_company_id'=>$this->input->post('insurance_company_id'),
				'visit_type_name'=>$this->input->post('visit_type_name'),
				'visit_type_status'=>$this->input->post('visit_type_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('visit_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing visit_type
	*	@param string $image_name
	*	@param int $visit_type_id
	*
	*/
	public function update_visit_type($visit_type_id)
	{
		$data = array(
				'insurance_company_id'=>$this->input->post('insurance_company_id'),
				'visit_type_name'=>$this->input->post('visit_type_name'),
				'visit_type_status'=>$this->input->post('visit_type_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('visit_type_id', $visit_type_id);
		if($this->db->update('visit_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single visit_type's children
	*	@param int $visit_type_id
	*
	*/
	public function get_sub_visit_types($visit_type_id)
	{
		//retrieve all users
		$this->db->from('visit_type');
		$this->db->select('*');
		$this->db->where('visit_type_parent = '.$visit_type_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single visit_type's details
	*	@param int $visit_type_id
	*
	*/
	public function get_visit_type($visit_type_id)
	{
		//retrieve all users
		$this->db->from('visit_type');
		$this->db->select('*');
		$this->db->where('visit_type_id = '.$visit_type_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing visit_type
	*	@param int $visit_type_id
	*
	*/
	public function delete_visit_type($visit_type_id)
	{
		if($this->db->delete('visit_type', array('visit_type_id' => $visit_type_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated visit_type
	*	@param int $visit_type_id
	*
	*/
	public function activate_visit_type($visit_type_id)
	{
		$data = array(
				'visit_type_status' => 1
			);
		$this->db->where('visit_type_id', $visit_type_id);
		
		if($this->db->update('visit_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated visit_type
	*	@param int $visit_type_id
	*
	*/
	public function deactivate_visit_type($visit_type_id)
	{
		$data = array(
				'visit_type_status' => 0
			);
		$this->db->where('visit_type_id', $visit_type_id);
		
		if($this->db->update('visit_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>