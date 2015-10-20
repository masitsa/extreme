<?php

class Administration_model extends CI_Model 
{

	public function get_all_services($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('service_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_visit_types($table = 'visit_type', $where = 'visit_type_status = 1')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('visit_type_name','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	public function get_patient($patient_id)
	{
		//retrieve all users
		$this->db->from('patients');
		$this->db->select('*');
		$this->db->where('patient_id = '.$patient_id);
		$query = $this->db->get();
		
		return $query;
	}
	public function get_all_service_charges($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('service.service_name, service_charge.service_charge_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_service_names($service_id)
	{
		$table = "service";
		$where = "service_id =". $service_id;
		$items = "service_name";
		$order = "service_name";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{
			foreach ($result as $key):
				$service_name = $key->service_name;
			endforeach;
		}
		return $service_name;
	}

	public function submit_service_charges($service_id)
	{
		$service_charge_name = $this->input->post('service_charge_name');
		$charge = $this->input->post('charge');
		$patient_type = $this->input->post('patient_type');

		//  check if the value exisit
		$result = $this->check_service_charge_exist($service_id,$patient_type,$service_charge_name);

		if($result == TRUE)
		{
			return FALSE;
		}
		else
		{
			$visit_data = array('service_id'=>$service_id,'service_charge_name'=>$service_charge_name,'service_charge_amount'=>$charge,'visit_type_id'=>$patient_type, 'service_charge_status'=>1);
			$this->db->insert('service_charge', $visit_data);

			return TRUE;
		}

	}

	public function check_service_charge_exist($service_id,$patient_type,$service_charge_name)
	{
		$table = "service_charge";
		$where = "service_charge_name = '$service_charge_name' AND service_id = ".$service_id." AND visit_type_id = ".$patient_type;
		$items = "*";
		$order = "service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;	
		}
		
	}
	public function get_service_charge_data($service_charge_id)
	{
		$table = "service_charge";
		$where = "service_charge_id = ".$service_charge_id;
		$items = "*";
		$order = "service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);

		return $result;

	}
	public function submit_service()
	{
		$service_name = $this->input->post('service_name');

		//  check if the value exisit
		$result = $this->check_service_exist($service_name);

		if($result == TRUE)
		{
			return FALSE;
		}
		else
		{
			$visit_data = array('service_name'=>$service_name);
			$this->db->insert('service', $visit_data);

			return TRUE;
		}

	}
	public function check_service_exist($service_name)
	{
		$table = "service";
		$where = "service_name = '$service_name'";
		$items = "*";
		$order = "service_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;	
		}
	}
	
	public function delete_service($service_id)
	{
		$data['service_delete'] = 1;
		$this->db->where('service_id', $service_id);
		if($this->db->update('service', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function delete_service_charge($service_charge_id)
	{
		$data['service_charge_delete'] = 1;
		$this->db->where('service_charge_id', $service_charge_id);
		if($this->db->update('service_charge', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function get_all_patient_visit($table, $where, $per_page, $page, $items = '*')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select($items);
		$this->db->where($where);
		$this->db->order_by('visit_date','desc');
		$query = $this->db->get();
		
		return $query;
	}

	public function patient_account_balance($patient_id)
	{
		//retrieve all users
		$this->db->from('visit');
		$this->db->select('*');
		$this->db->where('patient_id = '.$patient_id);
		$this->db->order_by('visit_date','desc');
		$query = $this->db->get();

		$total_invoiced_amount = 0;
		$total_paid_amount = 0;

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$visit_id = $row->visit_id;
				$visit_date = $row->visit_date;
				$visit_date = $row->visit_date;
				$total_invoice = $this->accounts_model->total_invoice($visit_id);
				$total_payments = $this->accounts_model->total_payments($visit_id);

				$total_paid_amount = $total_paid_amount + $total_payments;
				$total_invoiced_amount = $total_invoiced_amount + $total_invoice;
				
				$invoice_number =  $visit_id;
			}
			$difference = $total_invoiced_amount -$total_paid_amount;
		}
		else
		{
			$difference = $total_invoiced_amount -$total_paid_amount;
		}

		return $difference;
	}

	public function get_all_expenses($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('reason','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_expense_total_amount($where)
	{
		$this->db->from('expenses');
		$this->db->select('SUM(amount) AS total_amount');
		$this->db->where($where);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{

			foreach ($query->result() as $key) {
				# code...
				$total_amount = $key->total_amount;
			}
		}
		else
		{
				$total_amount = 0;
		}
		return $total_amount;
	}
	public function get_expense_names($expense_id)
	{
		$table = "expenses";
		$where = "expenses_id =". $expense_id;
		$items = "reason";
		
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('reason','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	public function submit_expense()
	{
		$expense_name = $this->input->post('expense_name');
		$transaction_date = $this->input->post('transaction_date');
		$transacted_amount = $this->input->post('transacted_amount');
		$expense_description = $this->input->post('expense_description');
		$recepient = $this->input->post('recepient');

		//  check if the value exisit
		
		$visit_data = array('recipient'=>$expense_name,
							'reason'=>$expense_description,
							'amount'=>$transacted_amount,
							'dateofissue'=>$transaction_date
							);
		$this->db->insert('expenses', $visit_data);

		return TRUE;
		
	}
	public function delete_expense($expense_id)
	{
		$data['expense_status'] = 1;
		$this->db->where('expenses_id', $expense_id);
		if($this->db->update('expenses', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}

	}


	// suppliers 
	public function get_all_suppliers($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('supplier_name','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_supplier_total_amount($where)
	{
		$this->db->from('suppliers');
		$this->db->select('SUM(amount) AS total_amount');
		$this->db->where($where);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{

			foreach ($query->result() as $key) {
				# code...
				$total_amount = $key->total_amount;
			}
		}
		else
		{
				$total_amount = 0;
		}
		return $total_amount;
	}
	public function get_supplier_names($supplier_id)
	{
		$table = "suppliers";
		$where = "suppliers_id =". $supplier_id;
		$items = "supplier_name";
		
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('supplier_name','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	public function submit_supplier()
	{
		$supplier_name = $this->input->post('supplier_name');
		$transaction_date = $this->input->post('transaction_date');
		$transacted_amount = $this->input->post('transacted_amount');
		$supplier_description = $this->input->post('supplier_description');
		$recepient = $this->input->post('recepient');

		//  check if the value exisit
		
		$visit_data = array('supplier_name'=>$supplier_name,
							'reason'=>$supplier_description,
							'amount'=>$transacted_amount,
							'dateofissue'=>$transaction_date
							);
		$this->db->insert('suppliers', $visit_data);

		return TRUE;
		
	}
	public function delete_supplier($supplier_id)
	{
		$data['supplier_status'] = 1;
		$this->db->where('suppliers_id', $supplier_id);
		if($this->db->update('suppliers', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}

	}
	// end of suppliers
	public function get_sum_days_credit_notes()
	{
		$table = "payments";
		$where = "payments.payment_type = 3 AND payments.payment_created = '".date('Y-m-d')."'";
		$items = "SUM(payments.amount_paid) AS amount_paid";
		$order = "payments.payment_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{

			foreach ($result as $key):
				# code...
				$amount = $key->amount_paid;

				if(!is_numeric($amount))
				{
					return 0;
				}
				else
				{
					return $amount;
				}
			endforeach;
		}
		else
		{
			return 0;
		}

	}
	public function get_sum_days_debit_notes()
	{
		$table = "payments";
		$where = "payments.payment_type = 2 AND payments.payment_created = '".date('Y-m-d')."'";
		$items = "SUM(payments.amount_paid) AS amount_paid";
		$order = "payments.payment_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{

			foreach ($result as $key):
				# code...
				$amount = $key->amount_paid;

				if(!is_numeric($amount))
				{
					return 0;
				}
				else
				{
					return $amount;
				}
			endforeach;
		}
		else
		{
			return 0;
		}

	}
	public function get_sum_days_cash_notes()
	{
		$table = "payments";
		$where = "payments.payment_type = 1 AND payments.payment_created = '".date('Y-m-d')."'";
		$items = "SUM(payments.amount_paid) AS amount_paid";
		$order = "payments.payment_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{

			foreach ($result as $key):
				# code...
				$amount = $key->amount_paid;

				if(!is_numeric($amount))
				{
					return 0;
				}
				else
				{
					return $amount;
				}
			endforeach;
		}
		else
		{
			return 0;
		}

	}
	public function get_sum_of_days_invoices()
	{
		$table = "visit_charge";
		$where = "visit_charge.date ='".date('Y-m-d')."' AND visit_charge_delete = 0";
		$items = "SUM(visit_charge.visit_charge_amount) AS amount_invoiced";
		$order = "visit_charge.service_charge_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{

			foreach ($result as $key):
				# code...
				$amount = $key->amount_invoiced;

				if(!is_numeric($amount))
				{
					return 0;
				}
				else
				{
					return $amount;
				}
			endforeach;
		}
		else
		{
			return 0;
		}
	}
}
?>