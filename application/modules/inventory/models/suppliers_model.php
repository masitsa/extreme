<?php

class Suppliers_model extends CI_Model 
{	
	/*
	*	Retrieve all suppliers
	*
	*/
	public function all_suppliers()
	{
		$this->db->where('supplier_status = 1 AND deleted = 0');
		$this->db->order_by('supplier_name');
		$query = $this->db->get('supplier');
		
		return $query;
	}
	/*
	*	Retrieve latest supplier
	*
	*/
	public function latest_supplier()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('supplier');
		
		return $query;
	}
	
	
	/*
	*	Retrieve all suppliers
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_suppliers($table, $where, $per_page, $page,$order, $order_method)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by( $order,$order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new supplier
	*	@param string $image_name
	*
	*/
	public function add_supplier()
	{
		$data = array(
				'supplier_name'=>ucwords(strtolower($this->input->post('supplier_name'))),
				'supplier_phone'=>$this->input->post('supplier_phone'),
				'supplier_email'=>$this->input->post('supplier_email'),
				'supplier_physical_address'=>$this->input->post('supplier_physical_address'),
				'supplier_contact_person'=>$this->input->post('supplier_contact_person'),
				'supplier_status'=>$this->input->post('supplier_status'),
				'created'=>date('Y-m-d H:i:s'),
				'deleted'=>0,
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		if($this->db->insert('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing supplier
	*	@param string $image_name
	*	@param int $supplier_id
	*
	*/
	
	function import_template()
	{
		$this->load->library('Excel');
		
		$title = 'Extreme Suppliers Import Template';
		$count=1;
		$r = 0;
		
		$array[$r][0] = 'Vendor';
		$array[$r][1] = 'Address';
		$array[$r][2] = 'Contact Person';

		$r++;
		
		//create the excel document
		$this->excel->addArray ($array);
		$this->excel->generateXML ($title);
	}
	
	
	public function update_supplier($supplier_id)
	{
		$data = array(
				'supplier_name'=>ucwords(strtolower($this->input->post('supplier_name'))),
				'supplier_phone'=>$this->input->post('supplier_phone'),
				'supplier_email'=>$this->input->post('supplier_email'),
				'supplier_physical_address'=>$this->input->post('supplier_physical_address'),
				'supplier_contact_person'=>$this->input->post('supplier_contact_person'),
				'supplier_status'=>$this->input->post('supplier_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'"AND supplier_id = '.$supplier_id);
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	/*
	*	get a single supplier's details
	*	@param int $supplier_id
	*
	*/
	public function get_supplier($supplier_id)
	{
		//retrieve all users
		$this->db->from('supplier');
		$this->db->select('*');
		$this->db->where('supplier_id = '.$supplier_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single supplier's details
	*	@param int $supplier_id
	*
	*/
	public function get_supplier_by_name($supplier_name)
	{
		//retrieve all users
		$this->db->from('supplier');
		$this->db->select('*');
		$this->db->where('supplier_name = \''.$supplier_name.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function delete_supplier($supplier_id)
	{
		$data = array(
			'deleted'=>1,
			'deleted_on'=>date('Y-m-d H:i:s'),
			'deleted_by'=>$this->session->userdata('personnel_id'),
		);
		$this->db->where('supplier_id', $supplier_id);	
			if($this->db->update('supplier',$data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
	
	/*
	*	Activate a deactivated supplier
	*	@param int $supplier_id
	*
	*/
	public function activate_supplier($supplier_id)
	{
		$data = array(
				'supplier_status' => 1
			);
		$this->db->where('supplier_id', $supplier_id);
		
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated supplier
	*	@param int $supplier_id
	*
	*/
	public function deactivate_supplier($supplier_id)
	{
		$data = array(
				'supplier_status' => 0
			);
		$this->db->where('supplier_id', $supplier_id);
		
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function import_csv_suppliers($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	
		public function check_current_supplier_exists($supplier_name)
	{
		$this->db->from('supplier');
		$this->db->where('supplier_name', $supplier_name);
		
		$query = $this->db->get('');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function sort_csv_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if($total_rows > 0)
		{
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Supplier Name</th>
						  <th>Supplier Address</th>
						  <th>Supplier Contact</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$supplier_name = $array[$r][0];
				$supplier_address = $array[$r][1];
				$contact_person = $array[$r][2];
			
				$suppliers['supplier_name'] = $supplier_name;
				$suppliers['supplier_contact_person'] = $contact_person;
				$suppliers['supplier_physical_address'] = $supplier_address;
				$suppliers['branch_code'] =$this->session->userdata('branch_code');
				
				$comment ='';
				
				// check if item exist
				if(!empty($suppliers['supplier_name']))
				{
					if(!empty($suppliers['supplier_name']))
					{
						// check if the number already exists
						if($this->check_current_supplier_exists($supplier_name))
						{
							//number exists
							$comment = '<br/>Duplicate supplier entered';
							$class = 'danger';
						}
						else
						{
							// number does not exisit
							//save product in the db
							if($this->db->insert('supplier', $suppliers))
							{
								$supplier_id = $this->db->insert_id();
							
								$comment = '<br/>Supplier successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment = '<br/>Internal error. Could not add client to the database. Please contact the site administrator. Supplier code '.$suppliers['supplier_name'];
								$class = 'warning';
							}
						}
					}
					else{
						$comment = '<br/>Internal error. Could not add client to the database. Please contact the site administrator. Supplier code '.$suppliers['item_name'];
								$class = 'warning';
					}
				}
				else
				{
					$comment = '<br/>Not saved. Ensure you have a client entered'.$suppliers['supplier_name'];
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$suppliers['supplier_name'].'</td>
							<td>'.$suppliers['supplier_name'].'</td>
							<td>'.$suppliers['supplier_contact_person'].'</td>
							<td>'.$comment.'</td>
						</tr> 
				';
				
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Supplier data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	
}
?>