<?php

class requests_model extends CI_Model 
{
	/*
	*	Retrieve all requests
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_requests($table, $where, $per_page, $page, $order, $order_method)
	{
		//retrieve all requests
		$this->db->from($table);
		$this->db->select('requests.*,inventory_level_status.inventory_level_status_name, inventory_level_status.inventory_level_status_id, client.client_name');
		$this->db->where($where);
		$this->db->order_by($order,$order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all requests of a user
	*
	*/
	public function get_user_requests($user_id)
	{
		$this->db->where('user_id = '.$user_id);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('requests');
		
		return $query;
	}
	public function get_request_suppliers($request_id)
	{
		$this->db->where('supplier.supplier_id = supplier_request.supplier_id AND supplier_request.request_id = '.$request_id);
		$query = $this->db->get('supplier,supplier_request');
		
		return $query;
	}
	public function get_supplier_request_details($supplier_request_id)
	{
		$this->db->select('supplier.*,supplier_request.*,requests.*');
		$this->db->where('supplier.supplier_id = supplier_request.supplier_id AND requests.request_id = supplier_request.request_id AND supplier_request.supplier_request_id = '.$supplier_request_id);
		$query = $this->db->get('supplier,supplier_request,requests');
		
		return $query;
	}
	public function get_request_approval_status($request_id)
	{
		$this->db->select('request_approval_status');
		$this->db->where('request_id = '.$request_id);
		$query = $this->db->get('requests');
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$request_approval_status = $key->request_approval_status;
			}
		}
		else
		{
			$request_approval_status = 0;
		}
		return $request_approval_status;
	}
	
	/*
	*	Retrieve an request
	*
	*/
	public function get_request($request_id)
	{
		$this->db->select('*');
		$this->db->where('requests.request_status = request_status.request_status_id AND users.user_id = requests.user_id AND requests.request_id = '.$request_id);
		$query = $this->db->get('requests, request_status, users');
		
		return $query;
	}
	/*
	*	Retrieve all requests
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_request_status()
	{
		//retrieve all requests
		$this->db->from('request_status');
		$this->db->select('*');
		$this->db->order_by('request_status_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function all_status()
	{
		$this->db->select('inventory_level_status.*'); 
		$query = $this->db->get('inventory_level_status');
		return $query;
	}
	
	public function get_request_creator($request_id)
	{
  
 
		$this->db->from('request_status');
		$this->db->select('requests.*,  personnel.personnel_onames, personnel.personnel_fname');
		$this->db->where('requests.created_by = personnel.personnel_id AND requests.request_id ='.$request_id);
		$query = $this->db->get('requests,personnel');
		foreach ($query->result() as $key)
		{
			$personnel_fname=$key->personnel_fname;
		}		
		return $personnel_fname;
	}
	
	public function get_request_approver($request_id)
	{
		
		$this->db->from('request_status');
		$this->db->select('requests.*,  personnel.personnel_onames, personnel.personnel_fname');
		$this->db->where('requests.approved_by = personnel.personnel_id AND requests.request_id ='.$request_id);
		$query = $this->db->get('requests,personnel');
		foreach ($query->result() as $key)
		{
			$personnel_fname=$key->personnel_fname;
		}		
		return $personnel_fname;		
		}
		
		public function get_request_date($request_id)
		{
				$this->db->select('requests.*');
		$this->db->where('request_id ='.$request_id);
		$query = $this->db->get('requests');
		foreach ($query->result() as $key)
		{
		$request_date=$key->request_date;	
		}		
		return $request_date;
			}
	public function request_approved_on($request_id)
	{
		$this->db->select('requests.*,  personnel.personnel_onames, personnel.personnel_fname');
		$this->db->where('requests.approved_by = personnel.personnel_id AND requests.request_id ='.$request_id);
		$query = $this->db->get('requests,personnel');
		foreach ($query->result() as $key)
		{
		$last_modified=$key->last_modified;	
		}		
		return $last_modified;
	}
	
	// Retrieve request details to display on the request items page
	public function get_request_details($request_id)
	{
		$this->db->select('requests.*, client.client_name, personnel.personnel_onames, 	personnel.personnel_fname, request_status.request_status_name');
		$this->db->where('requests.client_id = client.client_id 
		AND requests.created_by = personnel.personnel_id
		AND requests.deleted = 0		
		AND requests.request_status_id= request_status.request_status_id	
		AND requests.modified_by = personnel.personnel_id AND requests.request_id = '.$request_id);
		$query = $this->db->get('requests,client,personnel,request_status');
		
		return $query;
		}
		
		/*
	*	Retrieve all request items of an request
	*
	*/
	public function get_request_items($request_id)
	{
		$this->db->select('item.*, request_item.*');
		$this->db->where('item.item_id = request_item.item_id AND request_item.deleted = 0 AND request_item.request_id = '.$request_id);
		$query = $this->db->get('request_item, item');
		
		return $query;
	}
	
	/*
	*	Create request number
	*
	*/
	public function create_request_number()
	{
		//select item code
		$this->db->from('requests');
		$this->db->where("request_number LIKE '".$this->session->userdata('branch_code')."".date('y')."-%'");
		$this->db->select('MAX(request_number) AS number');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$number++;//go to the next number
			
			if($number == 1){
				$number = "".$this->session->userdata('branch_code')."".date('y')."-001";
			}
		}
		else{//start generating receipt numbers
			$number = "".$this->session->userdata('branch_code')."".date('y')."-001";
		}
		
		return $number;
	}
	
	/*
	*	Create the total cost of an request
	*	@param int request_id
	*
	*/
	public function calculate_request_cost($request_id)
	{
		//select item code
		$this->db->from('request_item');
		$this->db->where('request_id = '.$request_id);
		$this->db->select('SUM(price * quantity) AS total_cost');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$total_cost =  $result[0]->total_cost;
		}
		
		else
		{
			$total_cost = 0;
		}
		
		return $total_cost;
	}
	
	/*
	*	Add a new request
	*
	*/
	public function get_personnel_name($created_by){
		$query=$this->db->query('select personnel_fname from personnel where personnel_id='.$created_by.'');
		$result = $query->result();
		foreach($result AS $key)
		{
		return $key->personnel_fname;
		}
	}
	public function add_request()
	{

		$request_number = $this->create_request_number();
		
		$data = array(
				'request_number'=>$request_number,
				'created_by'=>$this->session->userdata('personnel_id'),
				'request_date'=>$this->input->post('request_date'),
				'request_status_id'=>1,
				'deleted'=>0,
				'request_instructions'=>$this->input->post('request_instructions'),
				'client_id'=>$this->input->post('client_id'),
				'created'=>date('Y-m-d H:i:s'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'approved_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('requests', $data))
		{
			$request_id = $this->db->insert_id();

			// insert into the request_level_status

			$insert_data = array(
					'request_id'=>$request_id,
					'request_level_status_status'=>1,
					'inventory_level_status_id'=>1,
					'created'=>date("Y-m-d H:i:s"),
					'created_by' => $this->session->userdata('personnel_id'),
					'modified_by' =>$this->session->userdata('personnel_id')
				);

			$this->db->insert('request_level_status', $insert_data);

			return $request_id;



		}
		else{
			return FALSE;
		}
	}

	public function add_supplier_to_request($request_id)
	{
		$supplier_id = $this->input->post('supplier_id');

		$this->db->from('supplier_request');
		$this->db->where('request_id = '.$request_id.' AND supplier_id = '.$supplier_id);
		$this->db->select('*');
		$query = $this->db->get();

		if($query->num_rows() == 0)
		{

			$data = array(
					'request_id'=>$request_id,
					'supplier_id'=>$supplier_id,
					'created_by'=>$this->session->userdata('personnel_id'),
					'created'=>date('Y-m-d H:i:s'),
					'modified_by'=>$this->session->userdata('personnel_id')
				);
				
			if($this->db->insert('supplier_request', $data))
			{
				return $this->db->insert_id();
			}
			else{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	
	/*
	function to calculate the time 
	taken from the request_date
	 to date
	 */
	 
	 public function get_turnaround_time($request_id)
	 {
		$this->db->from('requests');
		$this->db->select('requests.request_date, requests.last_modified ');
		$this->db->where('requests.request_id = '.$request_id);
		$query = $this->db->get('');
		 $result = $query->result();
		foreach($result AS $key)
		{
			$request_date=strtotime($key->request_date);
			$approved_date=strtotime($key->last_modified);
			$turnaround_time=$approved_date-$request_date;
			
			}
			return $turnaround_time/(24*3600);
		 }
	
	/*
	*	Update an request
	*	@param int $request_id
	*
	*/
	public function _update_request($request_id)
	{
		
		$data = array(
				'created_by'=>$this->input->post('personnel_id'),
				'request_status'=>1,
				'request_instructions'=>$this->input->post('request_instructions'),
				'approved_by'=>$this->session->userdata('personnel_id'),
				'created'=>date('Y-m-d H:i:s'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
		
		$this->db->where('request_id', $request_id);
		if($this->db->update('requests', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Retrieve all requests
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_payment_methods()
	{
		//retrieve all requests
		$this->db->from('payment_method');
		$this->db->select('*');
		$this->db->order_by('payment_method_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a request item
	*
	*/
	public function add_request_item($request_id)
	{
		$item_id = $this->input->post('item_id');
		$quantity = $this->input->post('quantity');
		$request_item_price=$this->input->post('request_item_price');
		
		//Check if item exists
		$this->db->select('*');
		$this->db->where('item_id = '.$item_id.' AND request_id = '.$request_id);
		$query = $this->db->get('request_item','item');
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$qty = $result->quantity;
			$item_hiring_price=$result->item_hiring_price;
			$quantity += $qty;
			
			$data = array(
					//'request_item_price'=>$item_hiring_price,
					'request_item_quantity'=>$quantity,
					'deleted'=>0,
					'request_item_price'=>$request_item_price
				);
				
			$this->db->where('item_id = '.$item_id.' AND request_id = '.$request_id);
			if($this->db->update('request_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		
		
		else
		{
			$data = array(
						
					'request_id'=>$request_id,
					'item_id'=>$item_id,
					'request_item_price'=>$request_item_price,
					'request_item_quantity'=>$quantity
					
				);
				
			if($this->db->insert('request_item', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	public function update_request_item($request_id,$request_item_id)
	{
		$data = array(
					'request_item_quantity'=>$this->input->post('quantity')
				);
				
		$this->db->where('request_item_id = '.$request_item_id);
		if($this->db->update('request_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function update_request_item_price($request_id,$request_item_id)
	{
		$data = array(
					'supplier_unit_price'=>$this->input->post('unit_price')
				);
				
		$this->db->where('request_item_id = '.$request_item_id);
		if($this->db->update('request_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an request item
	*
	*/
	public function update_cart($request_item_id, $quantity)
	{
		$data = array(
					'quantity'=>$quantity
				);
				
		$this->db->where('request_item_id = '.$request_item_id);
		if($this->db->update('request_item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing request item
	*	@param int $item_id
	*
	*/
	public function delete_request_item($request_item_id)
	{
		$data = array(
			'deleted'=>1,
			'deleted_on'=>date('Y-m-d H:i:s'),
			'deleted_by'=>$this->session->userdata('personnel_id'),
		);
		$this->db->where('request_item_id', $request_item_id);	
			if($this->db->update('request_item',$data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
	public function get_next_approval_status_name($status)
	{
		$this->db->select('inventory_level_status_name');
		$this->db->where('inventory_level_status_id = '.$status);
		$query = $this->db->get('inventory_level_status');
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$inventory_level_status_name = $key->inventory_level_status_name;
			}
		}
		else
		{
			$inventory_level_status_name = 0;
		}
		return $inventory_level_status_name;	
	}
	public function check_assigned_next_approval($next_level_status)
	{
		$this->db->select('*');
		$this->db->where('approval_status_id = '.($next_level_status+1).' AND personnel_id = '.$this->session->userdata('personnel_id').'');
		$query = $this->db->get('personnel_approval');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}	
	}
	public function check_if_can_access($inventory_level_status_id, $personnel_approval_query)
	{
		if($personnel_approval_query->num_rows() > 0)
		{
			//get personnel's approval level
			$personnel_approval_level = 0;
			
			foreach($personnel_approval_query->result() as $res)
			{
				$appoval_status_id = $res->approval_status_id;
				
				if($appoval_status_id > $personnel_approval_level)
				{
					$personnel_approval_level = $appoval_status_id;
				}
			}
			
			if($personnel_approval_level >= $inventory_level_status_id)
			{
				return TRUE;
			}
			
			else
			{
			}
		}
		else
		{
			return FALSE;
		}

	}
	public function get_rfq_authorising_personnel($request_id)
	{
		$this->db->select('*');
		$this->db->where('request_level_status.created_by = personnel.personnel_id AND job_title.job_title_id = personnel_job.job_title_id AND personnel.personnel_id = personnel_job.personnel_id AND request_level_status.request_level_status_status = 1 AND title.title_id = personnel.title_id AND request_level_status.personnel_request_approval_status = 2');
		$query = $this->db->get('personnel,request_level_status,title,personnel_job,job_title');
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$other_names = $key->personnel_onames;
				$first_name = $key->personnel_fname;
				$title_name = $key->title_name;
				$job_title_name = $key->job_title_name;

				$item = '<br>'.$title_name.' '.$first_name.' '.$other_names.' <br> '.$job_title_name.' <br> ';
			}

		}
		else
		{
			$item = '';
		}
		return $item;
	}
	public function update_request_status($request_id,$request_status)
	{
		$data = array(
					'request_approval_status'=>$request_status
				);
				
		$this->db->where('request_id = '.$request_id);
		if($this->db->update('requests', $data))
		{
			$this->save_request_approval_status($request_id,$request_status);

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function save_request_approval_status($request_id,$request_status)
	{
		$insert_data = array(
					'request_id'=>$request_id,
					//'personnel_request_approval_status'=>$request_status,
					'request_level_status_status'=>1,
					'created'=>date("Y-m-d H:i:s"),
					'created_by' => $this->session->userdata('personnel_id'),
					'modified_by' =>$this->session->userdata('personnel_id')
				);
		if($this->db->insert('request_level_status', $insert_data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}


	}
	public function get_lpo_authorising_personnel($request_id)
	{
		$this->db->select('*');
		$this->db->where('request_level_status.created_by = personnel.personnel_id AND job_title.job_title_id = personnel_job.job_title_id AND personnel.personnel_id = personnel_job.personnel_id AND request_level_status.request_level_status_status = 1 AND title.title_id = personnel.title_id');
		$query = $this->db->get('personnel,request_level_status,title,personnel_job,job_title');
		
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$other_names = $key->personnel_onames;
				$first_name = $key->personnel_fname;
				$title_name = $key->title_name;
				$job_title_name = $key->job_title_name;

				$item = '<br>'.$title_name.' '.$first_name.' '.$other_names.' <br> '.$job_title_name.' <br> ';
			}

		}
		else
		{
			$item = '';
		}
		return $item;
	}
}