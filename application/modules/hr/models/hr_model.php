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
	
	//payroll template
	public function import_payroll_template()
	{
		$this->load->library('Excel');
		
		$title = 'Payroll Import Template';
		$count=0;
		$row_count=0;
		
		$report[$row_count][$count] = 'Employee Number';
		$count++;
		
		//get payments
		$this->db->where('payment_status = 0');
		$query = $this->db->get('payment');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$payment_name = $res->payment_name;
				$report[$row_count][$count] = $payment_name;
				$count++;
			}
		}
		
		//get allowances
		$this->db->where('allowance_status = 0');
		$query = $this->db->get('allowance');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$allowance_name = $res->allowance_name;
				$report[$row_count][$count] = $allowance_name;
				$count++;
			}
		}
		
		//get benefits
		$this->db->where('benefit_status = 0');
		$query = $this->db->get('benefit');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$benefit_name = $res->benefit_name;
				$report[$row_count][$count] = $benefit_name;
				$count++;
			}
		}
		
		//get deductions
		$this->db->where('deduction_status = 1');
		$query = $this->db->get('deduction');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$deduction_name = $res->deduction_name;
				$report[$row_count][$count] = $deduction_name;
				$count++;
			}
		}
		
		//get other_deductions
		$this->db->where('other_deduction_status = 1');
		$query = $this->db->get('other_deduction');
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$other_deduction_name = $res->other_deduction_name;
				$report[$row_count][$count] = $other_deduction_name;
				$count++;
			}
		}
		
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	public function import_csv_payroll($upload_path)
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
			$response2 = $this->sort_payroll_data($array);
		
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
	
	//sort the payroll data
	public function sort_payroll_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0))
		{
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Member Number</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//get payments
			$this->db->where('payment_status = 0');
			$payments_query = $this->db->get('payment');
				
			//get allowances
			$this->db->where('allowance_status = 0');
			$allowances_query = $this->db->get('allowance');
				
			//get benefits
			$this->db->where('benefit_status = 0');
			$benefits_query = $this->db->get('benefit');
			
			//get deductions
			$this->db->where('deduction_status = 1');
			$deductions_query = $this->db->get('deduction');
			
			//get deductions
			$this->db->where('other_deduction_status = 1');
			$other_deductions_query = $this->db->get('other_deduction');
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$count = 0;
				$comment = '';
				$items = $items1 = $items2 = $items3 = $items4 = $items5 = array();
				$personnel_number = $items['personnel_number'] = $array[$r][$count];
				$count++;
				$personnel_id = $this->get_personnel_id($personnel_number);
				$items1['personnel_id'] = $items2['personnel_id'] = $items3['personnel_id'] = $items4['personnel_id'] = $items5['personnel_id'] = $personnel_id;
				
				//payments
				if($payments_query->num_rows() > 0)
				{
					foreach($payments_query->result() as $res)
					{
						$payment_name = $res->payment_name;
						$payment_id = $res->payment_id;
						$payment_amount = $array[$r][$count];
						$count++;
						
						$items1['payment_id'] = $payment_id;
						$items1['personnel_payment_amount'] = $payment_amount;
						
						if($payment_amount > 0)
						{
							if($this->db->insert('personnel_payment', $items1))
							{
								$comment .= '<br/>'.$payment_name.' successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment .= '<br/>Internal error. Could not add '.$payment_name.' to the database.';
								$class = 'warning';
							}
						}
						
						else
						{
							$comment .= '<br/>Ensure '.$payment_name.' is more than 0 to add it';
							$class = 'warning';
						}
					}
				}
				
				//allowances
				if($allowances_query->num_rows() > 0)
				{
					foreach($allowances_query->result() as $res)
					{
						$allowance_name = $res->allowance_name;
						$allowance_id = $res->allowance_id;
						$payment_amount = $array[$r][$count];
						$count++;
						
						$items2['personnel_allowance_amount'] = $payment_amount;
						$items2['allowance_id'] = $allowance_id;
						
						if($payment_amount > 0)
						{
							if($this->db->insert('personnel_allowance', $items2))
							{
								$comment .= '<br/>'.$allowance_name.' successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment .= '<br/>Internal error. Could not add '.$allowance_name.' to the database.';
								$class = 'warning';
							}
						}
						
						else
						{
							$comment .= '<br/>Ensure '.$allowance_name.' is more than 0 to add it';
							$class = 'warning';
						}
					}
				}
				
				//benefits
				if($benefits_query->num_rows() > 0)
				{
					foreach($benefits_query->result() as $res)
					{
						$benefit_name = $res->benefit_name;
						$benefit_id = $res->benefit_id;
						$payment_amount = $array[$r][$count];
						$count++;
						
						$items3['personnel_benefit_amount'] = $payment_amount;
						$items3['benefit_id'] = $benefit_id;
						
						if($payment_amount > 0)
						{
							if($this->db->insert('personnel_benefit', $items3))
							{
								$comment .= '<br/>'.$benefit_name.' successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment .= '<br/>Internal error. Could not add '.$benefit_name.' to the database.';
								$class = 'warning';
							}
						}
						
						else
						{
							$comment .= '<br/>Ensure '.$benefit_name.' is more than 0 to add it';
							$class = 'warning';
						}
					}
				}
				
				//deductions
				if($deductions_query->num_rows() > 0)
				{
					foreach($deductions_query->result() as $res)
					{
						$deduction_name = $res->deduction_name;
						$deduction_id = $res->deduction_id;
						$payment_amount = $array[$r][$count];
						$count++;
						
						$items4['personnel_deduction_amount'] = $payment_amount;
						$items4['deduction_id'] = $deduction_id;
						
						if($payment_amount > 0)
						{
							if($this->db->insert('personnel_deduction', $items4))
							{
								$comment .= '<br/>'.$deduction_name.' successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment .= '<br/>Internal error. Could not add '.$deduction_name.' to the database.';
								$class = 'warning';
							}
						}
						
						else
						{
							$comment .= '<br/>Ensure '.$deduction_name.' is more than 0 to add it';
							$class = 'warning';
						}
					}
				}
				
				//other_deductions
				if($other_deductions_query->num_rows() > 0)
				{
					foreach($other_deductions_query->result() as $res)
					{
						$other_deduction_name = $res->other_deduction_name;
						$other_deduction_id = $res->other_deduction_id;
						$payment_amount = $array[$r][$count];
						$count++;
						
						$items5['personnel_other_deduction_amount'] = $payment_amount;
						$items5['other_deduction_id'] = $other_deduction_id;
						
						if($payment_amount > 0)
						{
							if($this->db->insert('personnel_other_deduction', $items5))
							{
								$comment .= '<br/>'.$other_deduction_name.' successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment .= '<br/>Internal error. Could not add '.$other_deduction_name.' to the database.';
								$class = 'warning';
							}
						}
						
						else
						{
							$comment .= '<br/>Ensure '.$other_deduction_name.' is more than 0 to add it';
							$class = 'warning';
						}
					}
				}
					
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$items['personnel_number'].'</td>
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
			$return['response'] = 'Member data not found ';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	public function get_personnel_id($personnel_number)
	{
		$this->db->where('personnel_number = "'.$personnel_number.'"');
		$this->db->select('personnel_id');
		$result = $this->db->get('personnel');
		$personnelid = 0;
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $personnel)
			{
				$personnelid = $personnel->personnel_id;
			}
		}
		return $personnelid;
	}
}
?>