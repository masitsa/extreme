<?php

class Companies_model extends CI_Model 
{	
	/*
	*	Retrieve all companies
	*
	*/
	public function all_companies()
	{
		$this->db->where('insurance_company_status = 1');
		$this->db->order_by('insurance_company_name');
		$query = $this->db->get('insurance_company');
		
		return $query;
	}
	
	/*
	*	Retrieve all companies
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_companies($table, $where, $per_page, $page, $order = 'insurance_company_name', $order_method = 'ASC')
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
	*	Add a new company
	*	@param string $image_name
	*
	*/
	public function add_company()
	{
		$data = array(
				'insurance_company_name'=>$this->input->post('insurance_company_name'),
				'insurance_company_contact_person_name'=>$this->input->post('insurance_company_contact_person_name'),
				'insurance_company_contact_person_phone1'=>$this->input->post('insurance_company_contact_person_phone1'),
				'insurance_company_contact_person_phone2'=>$this->input->post('insurance_company_contact_person_phone2'),
				'insurance_company_contact_person_email1'=>$this->input->post('insurance_company_contact_person_email1'),
				'insurance_company_contact_person_email2'=>$this->input->post('insurance_company_contact_person_email2'),
				'insurance_company_description'=>$this->input->post('insurance_company_description'),
				'insurance_company_status'=>$this->input->post('insurance_company_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing company
	*	@param string $image_name
	*	@param int $company_id
	*
	*/
	public function update_company($company_id)
	{
		$data = array(
				'insurance_company_name'=>$this->input->post('insurance_company_name'),
				'insurance_company_contact_person_name'=>$this->input->post('insurance_company_contact_person_name'),
				'insurance_company_contact_person_phone1'=>$this->input->post('insurance_company_contact_person_phone1'),
				'insurance_company_contact_person_phone2'=>$this->input->post('insurance_company_contact_person_phone2'),
				'insurance_company_contact_person_email1'=>$this->input->post('insurance_company_contact_person_email1'),
				'insurance_company_contact_person_email2'=>$this->input->post('insurance_company_contact_person_email2'),
				'insurance_company_description'=>$this->input->post('insurance_company_description'),
				'insurance_company_status'=>$this->input->post('insurance_company_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('insurance_company_id', $company_id);
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single company's details
	*	@param int $company_id
	*
	*/
	public function get_company($company_id)
	{
		//retrieve all users
		$this->db->from('insurance_company');
		$this->db->select('*');
		$this->db->where('insurance_company_id = '.$company_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing company
	*	@param int $company_id
	*
	*/
	public function delete_company($company_id)
	{
		if($this->db->delete('insurance_company', array('insurance_company_id' => $company_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated company
	*	@param int $company_id
	*
	*/
	public function activate_company($company_id)
	{
		$data = array(
				'insurance_company_status' => 1
			);
		$this->db->where('insurance_company_id', $company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated company
	*	@param int $company_id
	*
	*/
	public function deactivate_company($company_id)
	{
		$data = array(
				'insurance_company_status' => 0
			);
		$this->db->where('insurance_company_id', $company_id);
		
		if($this->db->update('insurance_company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>