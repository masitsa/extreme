<?php

class Reports_model extends CI_Model 
{
	public function get_queue_total($date = NULL, $where = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		if($where == NULL)
		{
			$where = 'visit.visit_id = visit_department.visit_id AND visit.close_card = 0 AND visit.visit_date = \''.$date.'\'';
		}
		
		else
		{
			$where .= ' AND visit.visit_id = visit_department.visit_id AND visit.close_card = 0 AND visit.visit_date = \''.$date.'\' ';
		}
		
		$this->db->select('COUNT(visit.visit_id) AS queue_total');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_department');
		
		$result = $query->row();
		
		return $result->queue_total;
	}
	
	public function get_daily_balance($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		//select the user by email from the database
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS total_amount');
		$this->db->where('visit_charge_timestamp LIKE \''.$date.'%\'');
		$this->db->from('visit_charge');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_amount;
	}
	
	public function get_patients_total($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('COUNT(visit_id) AS patients_total');
		$this->db->where('visit_date = \''.$date.'\'');
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->patients_total;
	}
	
	public function get_all_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	public function get_payment_method_total($payment_method_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('payments.visit_id = visit.visit_id AND payment_method_id = '.$payment_method_id.' AND visit_date = \''.$date.'\'');
		$query = $this->db->get('payments, visit');
		
		$result = $query->row();
		
		return $result->total_paid;
	}
	
	public function get_all_visit_types()
	{
		$this->db->select('*');
		$query = $this->db->get('visit_type');
		
		return $query;
	}
	
	public function get_visit_type_total($visit_type_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit_date = \''.$date.'\' AND visit_type = '.$visit_type_id;
		
		$this->db->select('COUNT(visit_id) AS visit_total');
		$this->db->where($where);
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->visit_total;
	}
	
	public function get_all_service_types()
	{
		$this->db->select('*');
		$query = $this->db->get('service');
		
		return $query;
	}
	
	public function get_service_total($service_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		
		$table = 'visit_charge, service_charge';
		
		$where = 'visit_charge_timestamp LIKE \''.$date.'%\' AND visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = '.$service_id;
		
		$visit_search = $this->session->userdata('all_departments_search');
		if(!empty($visit_search))
		{
			$where = 'visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = '.$service_id.' AND visit.visit_id = visit_charge.visit_id'. $visit_search;
			$table .= ', visit';
		}
		
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS service_total');
		$this->db->where($where);
		$query = $this->db->get($table);
		
		$result = $query->row();
		$total = $result->service_total;;
		
		if($total == NULL)
		{
			$total = 0;
		}
		
		return $total;
	}
	
	public function get_all_appointments($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_delete = 0 AND patients.patient_delete = 0 AND visit.visit_type = visit_type.visit_type_id AND visit.patient_id = patients.patient_id AND visit.appointment_id = 1 AND visit.close_card = 2 AND visit.visit_date >= \''.$date.'\'';
		
		$this->db->select('visit.*, visit_type.visit_type_name, patients.*');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type, patients');
		
		return $query;
	}
	
	public function get_doctor_appointments($personnel_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_delete = 0 AND patients.patient_delete = 0 AND visit.visit_type = visit_type.visit_type_id AND visit.patient_id = patients.patient_id AND visit.appointment_id = 1 AND visit.close_card = 2 AND visit.visit_date >= \''.$date.'\' AND visit.personnel_id = '.$personnel_id;
		
		$this->db->select('visit.*, visit_type.visit_type_name, patients.*');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type, patients');
		
		return $query;
	}
	
	public function get_all_sessions($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'personnel.personnel_id = session.personnel_id AND session.session_name_id = session_name.session_name_id AND session_time LIKE \''.$date.'%\'';
		
		$this->db->select('session_name_name, session_time, personnel_fname, personnel_onames');
		$this->db->where($where);
		$this->db->order_by('session_time', 'DESC');
		$query = $this->db->get('session, session_name, personnel');
		
		return $query;
	}
	
	/*
	*	Retrieve visits
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, (visit.visit_time_out - visit.visit_time) AS waiting_time, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id');
		$this->db->where($where);
		$this->db->order_by('visit.visit_date, visit.visit_time','DESC');
		$this->db->group_by('visit.visit_id');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all active services
	*
	*/
	public function get_all_active_services()
	{
		//retrieve all users
		$this->db->from('service');
		$this->db->where('service_delete = 0');
		$this->db->order_by('service_name','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	/*
	*	Retrieve all active services
	*
	*/
	public function get_all_active_payment_method()
	{
		//retrieve all users
		$this->db->from('payment_method');
		$this->db->where('payment_method_id > 0');
		$this->db->order_by('payment_method_id','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	
	
	/*
	*	Retrieve all visit payments
	*
	*/
	public function get_all_visit_payments($visit_id)
	{
		//retrieve all users
		$this->db->from('payments');
		$this->db->select('SUM(payments.amount_paid) AS total_paid');
		$this->db->where('visit_id', $visit_id);
		// $this->db->group_by('visit_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_paid > 0)
		{
			return $cash->total_paid;
		}
		
		else
		{
			return 0;
		}
	}
	
	/*
	*	Retrieve all service charges
	*
	*/
	public function get_all_visit_charges($visit_id, $service_id)
	{
		//retrieve all users
		$this->db->from('visit_charge, service_charge');
		$this->db->select('SUM(visit_charge.visit_charge_amount * visit_charge.visit_charge_units) AS total_invoiced');
		$this->db->where('visit_charge.visit_id = '.$visit_id.' AND service_charge.service_id = '.$service_id.' AND visit_charge.service_charge_id = service_charge.service_charge_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_invoiced > 0)
		{
			return $cash->total_invoiced;
		}
		
		else
		{
			return 0;
		}
	}
	
	public function get_all_payment_values($visit_id,$payment_method_id)
	{
		# code...
		//retrieve all users
		$this->db->from('payments');
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('visit_id = '.$visit_id.' AND payment_method_id = '.$payment_method_id.'');
		$query = $this->db->get();
		
		$cash = $query->row();
		
		if($cash->total_paid > 0)
		{
			return $cash->total_paid;
		}
		
		else
		{
			return 0;
		}
	}
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_total_services_revenue($where, $table)
	{
		//invoiced
		$this->db->from($table.', visit_charge, service_charge');
		$this->db->select('SUM(visit_charge.visit_charge_amount * visit_charge.visit_charge_units) AS total_invoiced');
		$this->db->where($where.' AND visit.visit_id = visit_charge.visit_id AND visit_charge.service_charge_id = service_charge.service_charge_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		$total_invoiced = $cash->total_invoiced;
		
		if($total_invoiced > 0)
		{
			
		}
		
		else
		{
			$total_invoiced = 0;
		}
		
		return $total_invoiced;
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_total_cash_collection($where, $table)
	{
		//payments
		$table_search = $this->session->userdata('all_transactions_tables');
		if(!empty($table_search))
		{
			$this->db->from($table);
		}
		
		else
		{
			$this->db->from($table.', payments');
		}
		$this->db->select('SUM(payments.amount_paid) AS total_paid');
		$this->db->where($where.' AND visit.visit_id = payments.visit_id');
		$query = $this->db->get();
		
		$cash = $query->row();
		$total_paid = $cash->total_paid;
		if($total_paid > 0)
		{
		}
		
		else
		{
			$total_paid = 0;
		}
		
		return $total_paid;
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_normal_payments($where, $table)
	{
		//payments
		$table_search = $this->session->userdata('all_transactions_tables');
		if(!empty($table_search))
		{
			$this->db->from($table);
		}
		
		else
		{
			$this->db->from($table.', payments');
		}
		$this->db->select('*');
		$this->db->where($where.' AND visit.visit_id = payments.visit_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	/*
	*	Export Transactions
	*
	*/
	function export_transactions()
	{
		$this->load->library('excel');
		
		//get all transactions
		$where = 'visit.patient_id = patients.patient_id  ';
		$table = 'visit, patients';
		$visit_search = $this->session->userdata('all_transactions_search');
		$table_search = $this->session->userdata('all_transactions_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		
		$this->db->where($where);
		$this->db->order_by('visit_date', 'ASC');
		$this->db->select('visit.*, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id,patients.dependant_id');
		$this->db->group_by('visit_id');
		$visits_query = $this->db->get($table);
		
		$title = 'Transactions Export';
		
		if($visits_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/
			$row_count = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Visit Date';
			$report[$row_count][2] = 'Patient';
			$report[$row_count][3] = 'Category';
			$report[$row_count][4] = 'Doctor';
			$report[$row_count][5] = 'School/faculty/department';
			$report[$row_count][6] = 'Staff/Student/ID No.';
			$current_column = 7 ;
			
			
			//get & display all services
			$services_query = $this->get_all_active_services();
			
			foreach($services_query->result() as $service)
			{
				$report[$row_count][$current_column] = $service->service_name;
				$current_column++;
			}
			$report[$row_count][$current_column] = 'Debit Note Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Credit Note Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Invoice Total';
			$current_column++;

			
			
			//get & display all services
			$payment_method_query = $this->get_all_active_payment_method();
			
			foreach($payment_method_query->result() as $paymentmethod)
			{
				$report[$row_count][$current_column] = $paymentmethod->payment_method;
				$current_column++;
			}
			$report[$row_count][$current_column] = 'Payments Total';
			$current_column++;
			$report[$row_count][$current_column] = 'Balance';
			$current_column++;
			//display all patient data in the leftmost columns
			foreach($visits_query->result() as $row)
			{
				$row_count++;
				$total_invoiced = 0;
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
				}
				else
				{
					$visit_time_out = '-';
				}
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$visit_type = $row->visit_type;
				if($row->dependant_id != 0)
				{
					$strath_no = $row->dependant_id;
				}
				else
				{
					$strath_no = $strath_no;
				}

				// this is to check for any credit note or debit notes
				$payments_value = $this->accounts_model->total_payments($visit_id);

				$invoice_total = $this->accounts_model->total_invoice($visit_id);

				$balance = $this->accounts_model->balance($payments_value,$invoice_total);
				// end of the debit and credit notes

				// total of debit and credit notes amounts
				$credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
				$debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);
				// end of total debit and credit notes amount

				// get all the payment methods used in payments
				//$payment_type = $this->accounts_model->get_visit_payment_method($visit_id);
				// end of all payments details


				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);

				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				$faculty = $patient['faculty'];
				
				//creators and editors
				$personnel_query = $this->personnel_model->get_all_personnel();
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_onames.' '.$adm->personnel_fname;
							break;
						}
						
						else
						{
							$doctor = '-';
						}
					}
				}
				
				else
				{
					$doctor = '-';
				}
				
				$count++;
				$cash = $this->reports_model->get_all_visit_payments($visit_id);
				
				//display services charged to patient
				$total_invoiced2 = 0;
				foreach($services_query->result() as $service)
				{
					$service_id = $service->service_id;
					$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
					$total_invoiced2 += $visit_charge;
				}
				
				//display all debtors
				$debtors = $this->session->userdata('debtors');
				// if($debtors == 'true' && (($cash - $total_invoiced2) > 0))
				if($debtors == 'true' && ($balance > 0))
				{
					//display the patient data
					$report[$row_count][0] = $count;
					$report[$row_count][1] = $visit_date;
					$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
					$report[$row_count][3] = $visit_type;
					$report[$row_count][4] = $doctor;
					$report[$row_count][5] = $faculty;
					$report[$row_count][6] = $strath_no;
					$current_column = 7;

					
					//display services charged to patient
					foreach($services_query->result() as $service)
					{
						$service_id = $service->service_id;
						$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
						$total_invoiced += $visit_charge;
						
						$report[$row_count][$current_column] = $visit_charge;
						$current_column++;
					}
					$report[$row_count][$current_column] = $debit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $credit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $total_invoiced;
					$current_column++;
					// display amounts collected on every payment method
					foreach($payment_method_query->result() as $paymentmethod)
					{
						$payment_method_id = $paymentmethod->payment_method_id;
						$amount_paid = $this->reports_model->get_all_payment_values($visit_id, $payment_method_id);
						$report[$row_count][$current_column] = $amount_paid;
						$current_column++;
					}
					// //display total for the current visit

					$report[$row_count][$current_column] = $payments_value;
					$current_column++;
					$report[$row_count][$current_column] = $balance;
					$current_column++;
				}
				
				//display cash & all transactions
				else
				{
					//display the patient data
					$report[$row_count][0] = $count;
					$report[$row_count][1] = $visit_date;
					$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
					$report[$row_count][3] = $visit_type;
					$report[$row_count][4] = $doctor;
					$report[$row_count][5] = $faculty;
					$report[$row_count][6] = $strath_no;
					$current_column= 7;
					
					

					//display services charged to patient
					foreach($services_query->result() as $service)
					{
						$service_id = $service->service_id;
						$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
						$total_invoiced += $visit_charge;
						
						$report[$row_count][$current_column] = $visit_charge;
						$current_column++;
					}
					$report[$row_count][$current_column] = $credit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $debit_note_amount;
					$current_column++;
					$report[$row_count][$current_column] = $invoice_total;
					$current_column++;
					foreach($payment_method_query->result() as $paymentmethod)
					{
						$payment_method_id = $paymentmethod->payment_method_id;
						$amount_paid = $this->reports_model->get_all_payment_values($visit_id, $payment_method_id);
						$report[$row_count][$current_column] = $amount_paid;
						$current_column++;
					}
				
					//display total for the current visit
					
					$report[$row_count][$current_column] = $payments_value;
					$current_column++;
					$report[$row_count][$current_column] = $balance;
					$current_column++;
				}
			}
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	/*
	*	Export Time report
	*
	*/
	function export_time_report()
	{
		$this->load->library('excel');
		
		//get all transactions
		$where = 'visit.patient_id = patients.patient_id AND visit.close_card = 1';
		$table = 'visit, patients';
		$visit_search = $this->session->userdata('time_reports_search');
		$table_search = $this->session->userdata('time_reports_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		
		$this->db->where($where);
		$this->db->order_by('visit_date', 'ASC');
		$this->db->select('visit.*, patients.visit_type_id, patients.visit_type_id, patients.patient_othernames, patients.patient_surname, patients.dependant_id, patients.strath_no,patients.patient_national_id,patients.dependant_id');
		$visits_query = $this->db->get($table);
		
		$title = 'Time report Export';
		
		if($visits_query->num_rows() > 0)
		{
			$count = 0;
			/*
				-----------------------------------------------------------------------------------------
				Document Header
				-----------------------------------------------------------------------------------------
			*/

			$row_count = 0;
			$report[$row_count][0] = '#';
			$report[$row_count][1] = 'Visit Date';
			$report[$row_count][2] = 'Patient';
			$report[$row_count][3] = 'Category';
			$report[$row_count][4] = 'Start Time';
			$report[$row_count][5] = 'End time';
			$report[$row_count][6] = 'Total Time (Days h:m:s)';
			//get & display all services
			
			//display all patient data in the leftmost columns
			foreach($visits_query->result() as $row)
			{
				$row_count++;
				$total_invoiced = 0;
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
					$seconds = strtotime($row->visit_time_out) - strtotime($row->visit_time);//$row->waiting_time;
					$days    = floor($seconds / 86400);
					$hours   = floor(($seconds - ($days * 86400)) / 3600);
					$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
					$seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
					
					//$total_time = date('H:i',(strtotime($row->visit_time_out) - strtotime($row->visit_time)));//date('H:i',$row->waiting_time);
					$total_time = $days.' '.$hours.':'.$minutes.':'.$seconds;
				}
				else
				{
					$visit_time_out = '-';
					$total_time = '-';
				}
					
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				
				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);
				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				$faculty = $patient['faculty'];
				$count++;
				
				//display the patient data
				$report[$row_count][0] = $count;
				$report[$row_count][1] = $visit_date;
				$report[$row_count][2] = $patient_surname.' '.$patient_othernames;
				$report[$row_count][3] = $visit_type;
				$report[$row_count][4] = $visit_time;
				$report[$row_count][5] = $visit_time_out;
				$report[$row_count][6] = $total_time;
					
				
				
			}
		}
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	
	/*
	*	Retrieve total revenue
	*
	*/
	public function get_visit_departments($where, $table)
	{
		//invoiced
		$this->db->from($table.', visit_department');
		$this->db->select('visit_department.*');
		$this->db->where($where.' AND visit.visit_id = visit_department.visit_id');
		$query = $this->db->get();
		
		return $query;
	}
}