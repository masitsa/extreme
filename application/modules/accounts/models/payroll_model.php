<?php

class Payroll_model extends CI_Model 
{	
	public function payments_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_payments($personnel_id);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$total = $total + $row2->amount;
			}
		}
		
		return $total;
	}
	
	public function benefits_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_benefits($personnel_id);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$taxable = $row2->taxable;
				
				if($taxable == 1)
				{
					$total = $total + $row2->amount;
				}
			}
		}
		
		return $total;
	}
	
	public function allowances_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_allowances($personnel_id);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$taxable = $row2->taxable;
				
				if($taxable == 1)
				{
					$total = $total + $row2->amount;
				}
			}
		}
		
		return $total;
	}
	
	public function deductions_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_deductions($personnel_id);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$taxable = $row2->taxable;
				
				if($taxable == 1)
				{
					$total = $total + $row2->amount;
				}
			}
		}
		
		return $total;
	}
	
	public function other_deductions_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_other_deductions($personnel_id);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$taxable = $row2->taxable;
				
				if($taxable == 1)
				{
					$total = $total + $row2->amount;
				}
			}
		}
		
		return $total;
	}
	
	public function savings_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_savings($personnel_id);
		
		$total_savings = 0;
											
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $allow)
			{
				$amount = $allow->amount;
				$total_savings += $amount;
			}
		}
		
		return $total_savings;
	}
	
	public function scheme_view($personnel_id)
	{
		$result = $this->payroll_model->get_personnel_scheme($personnel_id);
		
		$total_loan_schemes = 0;
                                
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $open)
			{
				$amount = $open->amount;
					
				if($amount > 0)
				{
					$monthly = $open->monthly;
					$total_loan_schemes += $monthly;
				}
			}
		}
		
		
		return $total_loan_schemes;
	}
	
	public function nssf_view($gross)
	{
		$nssf = 0;
		if($gross > 0)
		{
			$nssf_query = $this->payroll_model->get_nssf();
			
			if($nssf_query->num_rows() > 0)
			{
				foreach ($nssf_query->result() as $row2)
				{
					$nssf_id = $row2->nssf_id;
					$nssf = $row2->amount;
						
					$nssf_percentage = $row2->percentage;
					
					if($nssf_percentage == 1)
					{
						$nssf_deduction_amount = $gross;
						
						if($nssf_deduction_amount > 18000)
						{
							$nssf_deduction_amount = 18000;
						}
						$nssf = $nssf_deduction_amount * ($nssf/100);
					}
				}
			}
		}
		
		return $nssf;
	}
	
	public function nhif_view($gross)
	{
		$nhif = 0;
		if($gross > 0)
		{
			$nhif_query = $this->payroll_model->calculate_nhif($gross);
			
			if($nhif_query->num_rows() > 0)
			{
				foreach ($nhif_query->result() as $row2)
				{
					$nhif = $row2->amount;
				}
			}
		}
		
		return $nhif;
	}
	
	function calculate_paye($taxable)
	{
		$tax = 0;
		$total_tax = 0;
		
		if($taxable > 0)
		{	
			//get tax rates
			$paye_query = $this->payroll_model->get_paye();
			$count = 0;
			$total_tax = 0;
			$current_amount = $taxable;
			
			if($paye_query->num_rows() > 0)
			{
				foreach ($paye_query->result() as $row2)
				{
					$count++;
					$paye_id = $row2->paye_id;
					$paye_from = $row2->paye_from;
					$paye_to = $row2->paye_to;
					$paye_amount = $row2->paye_amount;
					
					//for people earning more than $paye_from
					//if(($current_amount > $paye_to) && ($paye_to > 0))
					if($paye_to != 0)
					{
						$section_difference = ($paye_to - $paye_from);
						if($current_amount >= $section_difference)
						{
							$tax = (($paye_amount / 100) * ($section_difference));
							//echo $paye_amount.' - '.$tax.' - '.$section_difference.' - '.$current_amount.'<br/>';
							$current_amount -= $section_difference;
							$total_tax += $tax;
						}
						
						else
						{
							$tax = (($paye_amount / 100) * ($current_amount));
							//echo $paye_amount.' - '.$tax.' - '.$current_amount.'<br/>';
							$total_tax += $tax;
							break;
						}
					}
					
					//people earning less than $paye_from
					else
					{
						$tax = ($paye_amount / 100) * $current_amount;
						//echo $paye_amount.' - '.$tax.' - '.$current_amount.'<br/>';
						$total_tax += $tax;
						break;
					}
				}
			}
		}
		
		return $total_tax;
	}
	
	public function get_all_allowances()
	{
		$table = "allowance";
		$items = "*";
		$order = "allowance_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_all_deductions()
	{
		$table = "deduction";
		$items = "*";
		$order = "deduction_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_all_other_deductions()
	{
		$table = "other_deduction";
		$items = "*";
		$order = "other_deduction_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_relief()
	{
		$table = "relief";
		$items = "*";
		$order = "relief_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_all_savings()
	{
		$table = "savings";
		$items = "*";
		$order = "savings_name";
		$where = "savings_status = 0";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_all_loan_schemes()
	{
		$table = "loan_scheme";
		$items = "*";
		$order = "loan_scheme_name";
		$where = "loan_scheme_status = 0";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_details($personnel_id)
	{
		$table = "personnel";
		$where = "personnel_id = $personnel_id";
		$items = "*";
		$order = "personnel_id";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	
	function dateDiff($time1, $time2, $interval) 
	{
		// If not numeric then convert texts to unix timestamps
		if (!is_int($time1)) {
		  $time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
		  $time2 = strtotime($time2);
		}
		
		// If time1 is bigger than time2
		// Then swap time1 and time2
		if ($time1 > $time2) {
		  $ttime = $time1;
		  $time1 = $time2;
		  $time2 = $ttime;
		}
		
		// Set up intervals and diffs arrays
		$intervals = array('year','month','day','hour','minute','second');
		if (!in_array($interval, $intervals)) {
		  return false;
		}
		
		$diff = 0;
		// Create temp time from time1 and interval
		$ttime = strtotime("+1 " . $interval, $time1);
		// Loop until temp time is smaller than time2
		while ($time2 >= $ttime) {
		  $time1 = $ttime;
		  $diff++;
		  // Create new temp time from time1 and interval
		  $ttime = strtotime("+1 " . $interval, $time1);
		}
		
		return $diff;
 	}
	
	public function month_calc($month)
	{
		if($month == "Jan"){
			$month = 1;
		}
		else if($month == "Feb"){
			$month = 2;
		}
		else if($month == "Mar"){
			$month = 3;
		}
		else if($month == "Apr"){
			$month = 4;
		}
		else if($month == "May"){
			$month = 5;
		}
		else if($month == "Jun"){
			$month = 6;
		}
		else if($month == "Jul"){
			$month = 7;
		}
		else if($month == "Aug"){
			$month = 8;
		}
		else if($month == "Sep"){
			$month = 9;
		}
		else if($month == "Oct"){
			$month = 10;
		}
		else if($month == "Nov"){
			$month = 11;
		}
		else if($month == "Dec"){
			$month = 12;
		}
		
		return $month;
	}
	
	function get_financial_year()
	{
		//get the financial year
  		$table = "financial_year";
		$where = "financial_year_status = 0";
		
		$this->db->where($where);
		$result = $this->db->get($table);
		
		$financial_year_id = 0;
		
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row)
			{
				$financial_year_id = $row->financial_year_id;
			}
		}
		
		return $financial_year_id;
	}
	
	public function get_table_id($table_name)
	{
		$table = "table";
		$where = "table_name = '$table_name'";
		
		$this->db->where($where);
		$result = $this->db->get($table);
		
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row):
				$table_id = $row->table_id;
			endforeach;
		}
		else
		{
			$items2 = array("table_name" => $table_name);
			$this->db->insert($table, $items2);
			$table_id = $this->db->insert_id();
		}
		return $table_id;
	}
	
	public function create_payroll($year, $month, $branch_id)
	{
		$table = 'payroll';
		
		$mth = $this->month_calc($month);
		
		//update payrolls of duplicate month/year to inactive
		$where = array(
			"month_id" => $mth,
			"payroll_year" => $year,
			"branch_id" => $branch_id
		);
		$update_data['payroll_status'] = 0;
		$this->db->where($where);
		$this->db->update($table, $update_data);
		
		$data = array(
			'branch_id' 		=> $branch_id,
			'month_id' 			=> $mth,
			'payroll_year' 		=> $year,
			'created'			=> date('Y-m-d H:i:s'),
			'created_by'		=> $this->session->userdata('personnel_id'),
			'modified_by'		=> $this->session->userdata('personnel_id')
		);
		
		if($this->db->insert($table, $data))
		{
			return $this->db->insert_id();
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function save_salary($payroll_id, $branch_id)
	{
		//Delete salary for that month
		$table = "payroll_item";
		
		//get personnel
		$this->db->where('branch_id = '.$branch_id.' AND personnel_type_id = 1');
		$result = $this->db->get('personnel');//echo $result->num_rows();die();
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row):
				$personnel_id = $row->personnel_id;
				
				/*
					--------------------------------------------------------------------------------------
					Payments
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->payroll_model->get_personnel_payments($personnel_id);
				$table_payment = $this->get_table_id("payment");
				$total_payments = 0;
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$payment = $row2->amount;
						$payment_id = $row2->id;
						$total_payments += $payment;
				
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_payment,
							"table_id" => $payment_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $payment
						);
				
						$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Benefits
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->payroll_model->get_personnel_benefits($personnel_id);
				$table_benefit = $this->get_table_id("benefit");
				$total_benefits = 0;
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$taxable = $row2->taxable;
						$benefit = $row2->amount;
						$benefit_id = $row2->id;
						
						if($taxable == 1)
						{
							$total_benefits += $benefit;
						}
				
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_benefit,
							"table_id" => $benefit_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $benefit
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Allowances
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->payroll_model->get_personnel_allowances($personnel_id);
				$table_allowance = $this->get_table_id("allowance");
				$total_allowances = 0;
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$allowance = $row2->amount;
						$allowance_id = $row2->id;
						$taxable = $row2->taxable;
						
						if($taxable == 1)
						{
							$total_allowances += $allowance;
						}
				
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_allowance,
							"table_id" => $allowance_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $allowance
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					PAYE
					--------------------------------------------------------------------------------------
				*/
				$gross_taxable = $total_payments + $total_benefits + $total_allowances;//echo $taxable.'<br/>';
				
				/*
					--------------------------------------------------------------------------------------
					NSSF
					--------------------------------------------------------------------------------------
				*/
				$nssf_query = $this->payroll_model->get_nssf();
				$nssf = 0;
				
				if($nssf_query->num_rows() > 0)
				{
					foreach ($nssf_query->result() as $row2)
					{
						$nssf_id = $row2->nssf_id;
						$nssf = $row2->amount;
						
						$nssf_percentage = $row2->percentage;
						
						if($nssf_percentage == 1)
						{
							$nssf_deduction_amount = $gross_taxable;
							
							if($nssf_deduction_amount > 18000)
							{
								$nssf_deduction_amount = 18000;
							}
							$nssf = $nssf_deduction_amount * ($nssf/100);
						}
					}
				}
				
				$taxable = $gross_taxable - $nssf;
				
				if($taxable > 10164)
				{
					$paye = $this->payroll_model->calculate_paye($taxable);//echo $paye.'<br/>';
				}
				
				else
				{
					$paye = 0;
				}
				
				$table_paye = $this->get_table_id("paye");
				
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_paye,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $paye
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Monthly relief
					--------------------------------------------------------------------------------------
				*/
				$table_relief = $this->get_table_id("relief");
				$monthly_relief = $this->payroll_model->get_monthly_relief();
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $monthly_relief
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Insurance relief
					--------------------------------------------------------------------------------------
				*/
				$table_relief = $this->get_table_id("insurance_relief");
				$monthly_relief = $this->payroll_model->get_monthly_relief();
				$insurance_res = $this->payroll_model->get_insurance_relief($personnel_id);
				$insurance_relief = $insurance_res['relief'];
				$insurance_amount = $insurance_res['amount'];
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $insurance_relief
				);
			
				$this->db->insert($table, $items);
				
				//insurance amount
				$table_relief = $this->get_table_id("insurance_amount");
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $insurance_amount
				);
			
				$this->db->insert($table, $items);
				
				$table_nssf = $this->get_table_id("nssf");
				
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_nssf,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $nssf
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					NHIF
					--------------------------------------------------------------------------------------
				*/
				$gross = ($total_payments + $total_allowances);
				$nhif_query = $this->payroll_model->calculate_nhif($gross);
				$nhif = 0;
				
				if($nhif_query->num_rows() > 0)
				{
					foreach ($nhif_query->result() as $row2)
					{
						$nhif = $row2->amount;
					}
				}
				$table_nhif = $this->get_table_id("nhif");
				
				$items = array(
					"payroll_id" => $payroll_id,
					"table" => $table_nhif,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"payroll_item_amount" => $nhif
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Deductions
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->payroll_model->get_personnel_deductions($personnel_id);
				$table_deduction = $this->get_table_id("deduction");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$deduction = $row2->amount;
						$deduction_id = $row2->id;
						
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_deduction,
							"table_id" => $deduction_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $deduction
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Other deductions
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->payroll_model->get_personnel_other_deductions($personnel_id);
				$table_other_deduction = $this->get_table_id("other_deduction");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$other_deduction = $row2->amount;
						$other_deduction_id = $row2->id;
						
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_other_deduction,
							"table_id" => $other_deduction_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $other_deduction
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Savings
					--------------------------------------------------------------------------------------
				*/
				$result3 = $this->payroll_model->get_personnel_savings($personnel_id);
				$table_savings = $this->get_table_id("savings");
				
				if($result3->num_rows() > 0)
				{
					foreach ($result3->result() as $row2):
						$savings = $row2->amount;
						$savings_id = $row2->id;
						
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_savings,
							"table_id" => $savings_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $savings
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Loan Schemes
					--------------------------------------------------------------------------------------
				*/
				$result4 = $this->payroll_model->get_personnel_scheme($personnel_id);
				
				$table_scheme = $this->get_table_id("loan_scheme");
				
				if($result4->num_rows() > 0)
				{
					foreach ($result4->result() as $row2):
						$amount = $row2->amount;
						$scheme_id = $row2->id;
						
						$items = array(
							"payroll_id" => $payroll_id,
							"table" => $table_scheme,
							"table_id" => $scheme_id,
							"personnel_id" => $personnel_id,
							"payroll_item_amount" => $amount
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
			endforeach;
		}
		
		return TRUE;
	}
	
	public function get_payroll_amount($personnel_id, $payroll_id, $table, $table_id)
	{
		$this->db->select('payroll_item_amount AS amount');
		$this->db->from('payroll_item');
		$this->db->where("personnel_id = $personnel_id AND payroll_id = ".$payroll_id." AND `table` = ".$table." AND table_id = ".$table_id);
		
		$query = $this->db->get();
		$amount = 0;
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$amount = $row->amount;
		}
		
		return $amount;
	}
	
	public function get_payroll_amount2($payroll_id, $table)
	{
		$this->db->select('SUM(payroll_item_amount) AS amount');
		$this->db->from('payroll_item');
		$this->db->where("payroll_id = ".$payroll_id." AND `table` = ".$table);
		
		$query = $this->db->get();
		$amount = 0;
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$amount = $row->amount;
		}
		
		return $amount;
	}
	
	function get_savings()
	{
		$this->db->where('savings_status', 0);
		$query = $this->db->get('savings');
		
		return $query;
	}
	
	function get_loan_schemes()
	{
		$this->db->where('loan_scheme_status', 0);
		$query = $this->db->get('loan_scheme');
		
		return $query;
	}
	
	function get_loan_scheme_interest($personnel_id, $date, $loan_scheme_id)
	{
		$this->db->select('loan_scheme.loan_scheme_id, personnel_scheme.personnel_scheme_amount AS amount, personnel_scheme.personnel_scheme_interest AS interest, loan_scheme.loan_scheme_name AS scheme_name, personnel_scheme.personnel_scheme_repayment_sdate AS sdate, personnel_scheme.personnel_scheme_repayment_edate AS edate, personnel_scheme_monthly AS monthly, personnel_scheme_int AS total_interest');
		$this->db->where("personnel_scheme.personnel_id = $personnel_id 
		AND loan_scheme.loan_scheme_status = 0
		AND loan_scheme.loan_scheme_id = $loan_scheme_id
		AND personnel_scheme.personnel_scheme_status = 0 
		AND personnel_scheme.personnel_scheme_repayment_sdate <= '$date' 
		AND personnel_scheme.personnel_scheme_repayment_edate >= '$date' 
		AND personnel_scheme.loan_scheme_id = loan_scheme.loan_scheme_id");
		$query = $this->db->get('personnel_scheme, loan_scheme');
		
		return $query;
	}
	
	function get_months()
	{
		$result = $this->db->get("month");
		
		return $result;
	}
	
	public function get_month_id($month)
	{
		$this->db->where('month_name', $month);
		$query = $this->db->get('month');
		
		$row = $query->row();
		return $row->month_id;
	}
	
	public function get_all_payments()
	{
		$table = "payment";
		$items = "*";
		$order = "payment_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_all_benefits()
	{
		$table = "benefit";
		$items = "*";
		$order = "benefit_name";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_nssf()
	{
		$table = "nssf";
		$items = "*";
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_nhif()
	{
		$table = "nhif";
		$items = "*";
		$this->db->order_by('nhif_from');
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function calculate_nhif($amount)
	{
		$table = "nhif";
		$items = "nhif_amount AS amount";
		$where = '(('.$amount.' >= nhif_from AND '.$amount.' <= nhif_to) OR ('.$amount.' >= nhif_from AND nhif_to = 0)) AND nhif_status = 1';
		$this->db->where($where);
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function add_new_nhif()
	{
		$data = array(
			'nhif_from'=>$this->input->post('nhif_from'),
			'nhif_to'=>$this->input->post('nhif_to'),
			'nhif_amount'=>$this->input->post('nhif_amount')
		);
		
		if($this->db->insert('nhif', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_nhif($nhif_id)
	{
		$data = array(
			'nhif_from'		=> $this->input->post('nhif_from'.$nhif_id),
			'nhif_to'		=> $this->input->post('nhif_to'.$nhif_id),
			'nhif_amount'	=> $this->input->post('nhif_amount'.$nhif_id)
		);
		
		$this->db->where('nhif_id', $nhif_id);
		if($this->db->update('nhif', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_paye()
	{
		$table = "paye";
		$items = "*";
		$this->db->order_by('paye_from');
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function add_new_paye()
	{
		$data = array(
			'paye_from'=>$this->input->post('paye_from'),
			'paye_to'=>$this->input->post('paye_to'),
			'paye_amount'=>$this->input->post('paye_amount')
		);
		
		if($this->db->insert('paye', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_paye($paye_id)
	{
		$data = array(
			'paye_from'		=> $this->input->post('paye_from'.$paye_id),
			'paye_to'		=> $this->input->post('paye_to'.$paye_id),
			'paye_amount'	=> $this->input->post('paye_amount'.$paye_id)
		);
		
		$this->db->where('paye_id', $paye_id);
		if($this->db->update('paye', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_nssf($nssf_id)
	{
		$data = array(
			'amount'		=> $this->input->post('amount'),
			'percentage'		=> $this->input->post('percentage')
		);
		
		$this->db->where('nssf_id', $nssf_id);
		if($this->db->update('nssf', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_payment()
	{
		$data = array(
			'payment_name'=>$this->input->post('payment_name')
		);
		
		if($this->db->insert('payment', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_payment($payment_id)
	{
		$data = array(
			'payment_name'		=> $this->input->post('payment_name'.$payment_id)
		);
		
		$this->db->where('payment_id', $payment_id);
		if($this->db->update('payment', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_benefit()
	{
		$data = array(
			'benefit_name'		=> $this->input->post('benefit_name'),
			'benefit_taxable'	=> $this->input->post('benefit_taxable')
		);
		
		if($this->db->insert('benefit', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_benefit($benefit_id)
	{
		$data = array(
			'benefit_name'	=> $this->input->post('benefit_name'.$benefit_id),
			'benefit_taxable'	=> $this->input->post('benefit_taxable'.$benefit_id)
		);
		
		$this->db->where('benefit_id', $benefit_id);
		if($this->db->update('benefit', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_allowance()
	{
		$data = array(
			'allowance_name'		=> $this->input->post('allowance_name'),
			'allowance_taxable'	=> $this->input->post('allowance_taxable')
		);
		
		if($this->db->insert('allowance', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_allowance($allowance_id)
	{
		$data = array(
			'allowance_name'	=> $this->input->post('allowance_name'.$allowance_id),
			'allowance_taxable'	=> $this->input->post('allowance_taxable'.$allowance_id)
		);
		
		$this->db->where('allowance_id', $allowance_id);
		if($this->db->update('allowance', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}

	}
	
	public function add_new_deduction()
	{
		$data = array(
			'deduction_name'		=> $this->input->post('deduction_name'),
			'deduction_taxable'	=> $this->input->post('deduction_taxable')
		);
		
		if($this->db->insert('deduction', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_deduction($deduction_id)
	{
		$data = array(
			'deduction_name'	=> $this->input->post('deduction_name'.$deduction_id),
			'deduction_taxable'	=> $this->input->post('deduction_taxable'.$deduction_id)
		);
		
		$this->db->where('deduction_id', $deduction_id);
		if($this->db->update('deduction', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_other_deduction()
	{
		$data = array(
			'other_deduction_name'		=> $this->input->post('other_deduction_name'),
			'other_deduction_taxable'	=> $this->input->post('other_deduction_taxable')
		);
		
		if($this->db->insert('other_deduction', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_other_deduction($other_deduction_id)
	{
		$data = array(
			'other_deduction_name'	=> $this->input->post('other_deduction_name'.$other_deduction_id),
			'other_deduction_taxable'	=> $this->input->post('other_deduction_taxable'.$other_deduction_id)
		);
		
		$this->db->where('other_deduction_id', $other_deduction_id);
		if($this->db->update('other_deduction', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_loan_scheme()
	{
		$data = array(
			'loan_scheme_name'		=> $this->input->post('loan_scheme_name'),
			//'loan_scheme_taxable'	=> $this->input->post('loan_scheme_taxable')
		);
		
		if($this->db->insert('loan_scheme', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_loan_scheme($loan_scheme_id)
	{
		$data = array(
			'loan_scheme_name'	=> $this->input->post('loan_scheme_name'.$loan_scheme_id),
			//'loan_scheme_taxable'	=> $this->input->post('loan_scheme_taxable'.$loan_scheme_id)
		);
		
		$this->db->where('loan_scheme_id', $loan_scheme_id);
		if($this->db->update('loan_scheme', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function add_new_saving()
	{
		$data = array(
			'savings_name'		=> $this->input->post('saving_name'),
			//'saving_taxable'	=> $this->input->post('saving_taxable')
		);
		
		if($this->db->insert('savings', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_saving($saving_id)
	{
		$data = array(
			'savings_name'	=> $this->input->post('saving_name'.$saving_id),
			//'saving_taxable'	=> $this->input->post('saving_taxable'.$saving_id)
		);
		
		$this->db->where('savings_id', $saving_id);
		if($this->db->update('savings', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_personnel_payments($personnel_id)
	{
		$table = "personnel_payment";
		$items = "personnel_payment_amount AS amount, payment_id AS id";
		$order = "id";
		$where = "personnel_payment_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->where($where);
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_benefits($personnel_id)
	{
		$table = "personnel_benefit, benefit";
		$items = "personnel_benefit_amount AS amount, personnel_benefit.benefit_id AS id, benefit_taxable AS taxable";
		$order = "id";
		$where = "personnel_benefit.benefit_id = benefit.benefit_id AND personnel_benefit_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->where($where);
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_allowances($personnel_id)
	{
		$table = "personnel_allowance, allowance";
		$items = "personnel_allowance_amount AS amount, personnel_allowance.allowance_id AS id, allowance_taxable AS taxable";
		$order = "id";
		$where = "personnel_allowance.allowance_id = allowance.allowance_id AND personnel_allowance_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->where($where);
		$this->db->select($items);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_deductions($personnel_id)
	{
		$table = "personnel_deduction, deduction";
		$items = "personnel_deduction_amount AS amount, personnel_deduction.deduction_id AS id, deduction_taxable AS taxable";
		$order = "id";
		$where = "personnel_deduction.deduction_id = deduction.deduction_id AND personnel_deduction_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_other_deductions($personnel_id)
	{
		$table = "personnel_other_deduction, other_deduction";
		$items = "personnel_other_deduction_amount AS amount, personnel_other_deduction.other_deduction_id AS id, other_deduction_taxable AS taxable";
		$order = "id";
		$where = "personnel_other_deduction.other_deduction_id = other_deduction.other_deduction_id AND personnel_other_deduction_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_savings($personnel_id)
	{
		$table = "personnel_savings, savings";
		$where = "personnel_savings.personnel_id = $personnel_id AND personnel_savings.personnel_savings_status = 1 AND savings.savings_id = personnel_savings.savings_id";
		$items = "personnel_savings.personnel_savings_amount AS amount, savings.savings_name, personnel_savings.savings_id AS id, personnel_savings_opening";
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_personnel_scheme($personnel_id)
	{
		$table = "personnel_scheme";
		$items = "personnel_scheme_int AS interest2, personnel_scheme_amount AS amount, personnel_scheme_monthly AS monthly, personnel_scheme_interest AS interest, loan_scheme_id AS id, personnel_scheme_repayment_sdate AS sdate, personnel_scheme_repayment_edate AS edate";
		$order = "amount";
		$where = "personnel_scheme_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	/*
	*	Retrieve all personnel
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_payrolls($table, $where, $per_page, $page, $order = 'created', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_payroll($payroll_id)
	{
		//retrieve all users
		$this->db->from('payroll');
		$this->db->select('*');
		$this->db->where('payroll_id', $payroll_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	public function edit_relief($relief_id)
	{
		$data = array(
			'relief_name'	=> $this->input->post('relief_name'.$relief_id),
			'relief_type'	=> $this->input->post('relief_type'.$relief_id),
			'relief_amount'	=> $this->input->post('relief_amount'.$relief_id)
		);
		
		$this->db->where('relief_id', $relief_id);
		if($this->db->update('relief', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_personnel_relief($personnel_id)
	{
		$table = "personnel_relief";
		$items = "personnel_relief_amount AS amount, relief_id AS id";
		$where = "personnel_relief_status = 1 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_monthly_relief()
	{
		$table = "relief";
		$items = "SUM(relief_amount) AS amount";
		$where = "relief_type = 1";
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		$amount = 0;
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$amount = $row->amount;
		}
		return $amount;
	}

	
	public function get_insurance_relief($personnel_id)
	{
		$table = "relief";
		$items = "relief_amount, relief_id";
		$where = "relief_type = 0";
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		$amount = 0;
		$relief = 0;
		
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $row)
			{
				$relief_amount = $row->relief_amount;
				$relief_id = $row->relief_id;
				$where = 'personnel_id = '.$personnel_id.' AND relief_id = '.$relief_id;
				//get personnel_relief
				$this->db->select('personnel_relief_amount AS amount');
				$this->db->where($where);
				$query = $this->db->get('personnel_relief');
				
				if($query->num_rows() > 0)
				{
					$row2 = $query->row();
					$amount = $row2->amount;
					
					//get relief
					$relief = ($relief_amount/100) * $amount;
				}
			}
		}
		$return['amount'] = $amount;
		$return['relief'] = $relief;
		
		return $return;
	}
	public function edit_payment_details($personnel_id)
	{
		$data = array(
			'personnel_account_number' => $this->input->post('personnel_account_number'),
			'personnel_nssf_number' => $this->input->post('personnel_nssf_number'),
			'personnel_kra_pin' => $this->input->post('personnel_kra_pin'),
			'personnel_nhif_number' => $this->input->post('personnel_nhif_number')
		);
		
		$this->db->where('personnel_id', $personnel_id);
		if($this->db->update('personnel', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>