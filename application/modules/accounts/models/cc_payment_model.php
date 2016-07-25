<?php

class Cc_payment_model extends CI_Model 
{	
	public function payments_view($personnel_id)
	{
		$result = $this->cc_payment_model->get_personnel_payments($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_benefits($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_allowances($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_deductions($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_other_deductions($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_savings($personnel_id);
		
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
		$result = $this->cc_payment_model->get_personnel_scheme($personnel_id);
		
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
			$nssf_query = $this->cc_payment_model->get_nssf();
			
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
			$nhif_query = $this->cc_payment_model->calculate_nhif($gross);
			
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
	
	public function calculate_paye($basic_pay)
	{
		$tax = 0;
		$total_tax = 0;
		//$current_amount=0;
		
		if($basic_pay > 0)
		{	
			$witholding_tax = $paye_amount;
			$paye_amount = 5/100 * $basic_pay;
			// //get tax rates
			// $paye_query = $this->cc_payment_model->get_paye();
			// $count = 0;
			// $witholding_tax = 0;
			//  $current_amount = 0;

			
			// if($paye_query->num_rows() > 0)
			// {
			// 	foreach ($paye_query->result() as $row2)
			// 	{
			// 		$count++;
			// 		$paye_id = $row2->paye_id;
			// 		$paye_from = $row2->paye_from;
			// 		$paye_to = $row2->paye_to;
			// 		$paye_amount = $row2 ->paye_amount;
					
			// 		//for people earning more than $paye_from
			// 		//if(($current_amount > $paye_to) && ($paye_to > 0))
			// 		if($paye_to != 0)
			// 		{
			// 			$section_difference = ($paye_to - $paye_from);
			// 			if($current_amount >= $section_difference)
			// 			{
			// 				$tax = (($paye_amount / 100) * ($section_difference));
			// 				//echo $paye_amount.' - '.$tax.' - '.$section_difference.' - '.$current_amount.'<br/>';
			// 				$current_amount -= $section_difference;
			// 				$total_tax += $tax;
			// 			}
						
			// 			else
			// 			{
			// 				$tax = (($paye_amount / 100) * ($current_amount));
			// 				//echo $paye_amount.' - '.$tax.' - '.$current_amount.'<br/>';
			// 				$total_tax += $tax;
			// 				break;
			// 			}
			// 		}
					
			// 		//people earning less than $paye_from
			// 		else
			// 		{
			// 			$tax = ($paye_amount / 100) * $current_amount;
			// 			//echo $paye_amount.' - '.$tax.' - '.$current_amount.'<br/>';
			// 			$total_tax += $tax;
			// 			break;
			// 		}
			// 	}
			// }
		}
		
		return round($withholding_tax);
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
	
	public function create_cc_payment($year, $month, $branch_id)
	{
				
			// var_dump($year);die();
		$table = 'cc_payment';
		
		$mth = $this->month_calc($month);
		
		//update cc_payment of duplicate month/year to inactive
		$where = array(
			"month_id" => $mth,
			"cc_payment_year" => $year,
			"branch_id" => $branch_id
		);
		$update_data['cc_payment_status'] = 0;
		$this->db->where($where);
		$this->db->update($table, $update_data);
		
		$data = array(
			'branch_id' 		=> $branch_id,
			'month_id' 			=> $mth,
			'cc_payment_year' 	=> $year,
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
	
	public function save_salary($cc_payment_id, $branch_id)
	{
		//Delete salary for that month
		$table = "cc_payment_item";
		
		//get casuals or consultants
		$this->db->where('branch_id = '.$branch_id.' AND personnel_type_id = 3 OR personnel_type_id = 5');
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
				$result2 = $this->cc_payment_model->get_personnel_payments($personnel_id);
				$table_payment = $this->get_table_id("payment");
				$total_payments = 0;
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$payment = $row2->amount;
						$payment_id = $row2->id;
						$total_payments += $payment;
				
						$items = array(
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_payment,
							"table_id" => $payment_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($payment)
						);
				
						$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Benefits
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->cc_payment_model->get_personnel_benefits($personnel_id);
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
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_benefit,
							"table_id" => $benefit_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($benefit)
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Allowances
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->cc_payment_model->get_personnel_allowances($personnel_id);
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
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_allowance,
							"table_id" => $allowance_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($allowance)
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
				$nssf_query = $this->cc_payment_model->get_nssf();
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
				
				/*if($personnel_id == 242)
				{
					var_dump($taxable); die();
				}*/
				if($taxable > 10164)
				{
					$paye = $this->cc_payment_model->calculate_paye($basic_pay);//echo $paye.'<br/>';
				}
				
				else
				{
					$paye = 0;
				}
				
				$table_paye = $this->get_table_id("paye");
				
				$items = array(
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_paye,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($paye)
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Monthly relief
					--------------------------------------------------------------------------------------
				*/
				$table_relief = $this->get_table_id("relief");
				$monthly_relief = $this->cc_payment_model->get_monthly_relief();
				$items = array(
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($monthly_relief)
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Insurance relief
					--------------------------------------------------------------------------------------
				*/
				$table_relief = $this->get_table_id("insurance_relief");
				$monthly_relief = $this->cc_payment_model->get_monthly_relief();
				$insurance_res = $this->cc_payment_model->get_insurance_relief($personnel_id);
				$insurance_relief = $insurance_res['relief'];
				$insurance_amount = $insurance_res['amount'];
				$items = array(
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($insurance_relief)
				);
			
				$this->db->insert($table, $items);
				
				//insurance amount
				$table_relief = $this->get_table_id("insurance_amount");
				$items = array(
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_relief,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($insurance_amount)
				);
			
				$this->db->insert($table, $items);
				
				$table_nssf = $this->get_table_id("nssf");
				
				$items = array(
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_nssf,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($nssf)
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					NHIF
					--------------------------------------------------------------------------------------
				*/
				$gross = ($total_payments + $total_allowances);
				$nhif_query = $this->cc_payment_model->calculate_nhif($gross);
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
					"cc_payment_id" => $cc_payment_id,
					"table" => $table_nhif,
					"table_id" => 1,
					"personnel_id" => $personnel_id,
					"cc_payment_item_amount" => round($nhif)
				);
			
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Deductions
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->cc_payment_model->get_personnel_deductions($personnel_id);
				$table_deduction = $this->get_table_id("deduction");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$deduction = $row2->amount;
						$deduction_id = $row2->id;
						
						$items = array(
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_deduction,
							"table_id" => $deduction_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($deduction)
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Other deductions
					--------------------------------------------------------------------------------------
				*/
				$result2 = $this->cc_payment_model->get_personnel_other_deductions($personnel_id);
				$table_other_deduction = $this->get_table_id("other_deduction");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$other_deduction = $row2->amount;
						$other_deduction_id = $row2->id;
						
						$items = array(
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_other_deduction,
							"table_id" => $other_deduction_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($other_deduction)
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Savings
					--------------------------------------------------------------------------------------
				*/
				$result3 = $this->cc_payment_model->get_personnel_savings($personnel_id);
				$table_savings = $this->get_table_id("savings");
				
				if($result3->num_rows() > 0)
				{
					foreach ($result3->result() as $row2):
						$savings = $row2->amount;
						$savings_id = $row2->id;
						
						$items = array(
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_savings,
							"table_id" => $savings_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($savings)
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Loan Schemes
					--------------------------------------------------------------------------------------
				*/
				$result4 = $this->cc_payment_model->get_personnel_scheme($personnel_id);
				
				$table_scheme = $this->get_table_id("loan_scheme");
				
				if($result4->num_rows() > 0)
				{
					foreach ($result4->result() as $row2):
						$amount = $row2->amount;
						$scheme_id = $row2->id;
						
						$items = array(
							"cc_payment_id" => $cc_payment_id,
							"table" => $table_scheme,
							"table_id" => $scheme_id,
							"personnel_id" => $personnel_id,
							"cc_payment_item_amount" => round($amount)
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
			endforeach;
		}
		
		return TRUE;
	}
	//get bank_data reports
	public function get_bank_report_data($personnel_id, $month, $branch_id)
	{
		$this->db->select('');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id ='.$personnel_id. '  AND cc_payment.month_id ='.$month.' AND cc_payment.cc_payment_year ='.date('Y').' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item');
		
		return $query;
	}
	public function get_cc_payment_amount($personnel_id, $cc_payment_id, $table, $table_id)
	{
		$this->db->select('cc_payment_item_amount AS amount');
		$this->db->from('cc_payment_item');
		$this->db->where("personnel_id = $personnel_id AND cc_payment_id = ".$cc_payment_id." AND `table` = ".$table." AND table_id = ".$table_id);
		
		$query = $this->db->get();
		$amount = 0;
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$amount = $row->amount;
		}
		
		return $amount;
	}
	
	public function get_cc_payment_amount2($cc_payment_id, $table)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS amount');
		$this->db->from('cc_payment_item');
		$this->db->where("cc_payment_id = ".$cc_payment_id." AND `table` = ".$table);
		
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
	public function get_all_cc_payment($table, $where, $per_page, $page, $order = 'created', $order_method = 'DESC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_cc_payment($cc_payment_id)
	{
		//retrieve all users
		$this->db->from('cc_payment');
		$this->db->select('*');
		$this->db->where('cc_payment_id', $cc_payment_id);
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
	public function get_p9_form_data($personnel_id, $from_month_id,$to_month_id,$year)
	{
		$this->db->select('');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id ='.$personnel_id. '  AND (cc_payment.month_id >= '.$from_month_id. ' AND cc_payment.month_id <= '.$to_month_id. ') AND cc_payment.cc_payment_year ='.$year. ' AND cc_payment.branch_id ='.$this->session->userdata('branch_id'));
		$query = $this->db->get('cc_payment,cc_payment_item');
		
		return $query;
	}
	
	//p10 data
	public function get_p10_form_data($from_month_id,$to_month_id,$year)
	{
		$this->db->select('');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment.cc_payment_status = 1 AND (cc_payment.month_id >= '.$from_month_id. ' AND cc_payment.month_id <= '.$to_month_id. ') AND cc_payment.cc_payment_year ='.$year. ' AND cc_payment.branch_id ='.$this->session->userdata('branch_id'));
		$query = $this->db->get('cc_payment,cc_payment_item');
		
		return $query;
	}
	
	public function get_p10_cc_payment_amount($cc_payment_id, $table, $table_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS amount');
		$this->db->from('cc_payment_item');
		$this->db->where("cc_payment_id = ".$cc_payment_id." AND `table` = ".$table." AND table_id = ".$table_id);
		
		$query = $this->db->get();
		$amount = 0;
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$amount = $row->amount;
		}
		
		return $amount;
	}
	public function get_cc_payment_report($table, $where, $config, $page, $order, $order_method)
	{
		$this->db->select();
		$this->db->where($where);
		$this->db->order_by($order);
		$query = $this->db->get($table);
		
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
	}
	public function get_cc_payment_summary($where)
	{
		$this->db->select('cc_payment.cc_payment_id, personnel.personnel_id,cc_payment.branch_id');
		$this->db->where($where);
		$query = $this->db->get('personnel, branch, cc_payment_item, cc_payment,month');
		
		return $query;
	}
	public function get_most_recent_month_active_cc_payment($branch_id, $month, $year)
	{
		$this->db->where('cc_payment_status = 1 AND cc_payment_closed = 0 AND branch_id ='.$branch_id.' AND month_id ='.$month.' AND cc_payment_year ='.$year);
		$query = $this->db->get('cc_payment');
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function get_most_recent_year_active_cc_payment($branch_id)
	{
		$this->db->select('MAX(cc_payment_year) as cc_payment_year');
		$this->db->where('cc_payment_status = 1 AND cc_payment_closed = 0 AND branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment');
		if($query->num_rows() > 0)
		{
			$recent_year = $query->row();
			$year = $recent_year->cc_payment_year;
		}
		
		return $year;
	}
	public function get_cc_payment_summary_report($branch_id, $cc_payment_items, $payment_table, $table_id)
	{
		$total = 0;
		if($cc_payment_items->num_rows() > 0)
		{
			foreach($cc_payment_items->result() as $res)
			{
				$cc_payment_item_amount = $res->cc_payment_item_amount;
				$cc_payment_personnel_id = $res->personnel_id;
				$cc_payment_table = $res->table;
				$cc_payment_table_id = $res->table_id;
				
				if(($cc_payment_table == $table) && ($cc_payment_table_id == $table_id) && ($cc_payment_personnel_id == $personnel_id))
				{
					$total += $cc_payment_item_amount;
				}
			}
		}
		
		return $total;
	}
	
	//update payment amounts to 0 when cc_payment is closed
	public function update_payment_closing_balances()
	{
		$items['personnel_payment_amount'] = 0;
		$items['personnel_payment_date'] = date('Y-m-d H-i-s');
		$this->db->where('payment_id != 1');
		$query = $this->db->update('personnel_payment',$items);	
		return $query;
	}
	//update all allowances except house allowance
	public function update_allowances_closing_balances()
	{
		$items['personnel_allowance_amount'] = 0;
		$items['personnel_allowance_date'] = date('Y-m-d H-i-s');
		$this->db->where('allowance_id != 7');
		$query = $this->db->update('personnel_allowance',$items);	
		return $query;
	}
	public function update_overtime_closing_balances()
	{
		$items['personnel_overtime_hours'] = 0;
		$query = $this->db->update('personnel_overtime',$items);	
		return $query;	
	}
	
	public function update_overtime_hours($personnel_id)
	{
		$table = 'personnel_overtime';
		$update_data['personnel_overtime_hours'] = $this->input->post('personnel_overtime_hours');
		$overtime_type = $this->input->post('overtime_type');
		$update_data['overtime_type_rate'] = $this->input->post('overtime_type_rate');
		
		//check if personnel has overtime hours
		$where = array('personnel_id' => $personnel_id, "overtime_type" => $overtime_type);
		$this->db->where($where);
		$query = $this->db->get($table);
		
		//if personnel exists, update
		if($query->num_rows() > 0)
		{
			$this->db->where($where);
			if($this->db->update($table, $update_data))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		
		//if personnel doesn't exist, insert
		else
		{
			$update_data['personnel_id'] = $personnel_id;
			$update_data['overtime_type'] = $overtime_type;
			if($this->db->insert($table, $update_data))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
	}
	
	public function get_overtime_hours($personnel_id)
	{
		$this->db->where('personnel.personnel_id = personnel_overtime.personnel_id AND personnel.branch_id = branch.branch_id AND personnel.personnel_id = '.$personnel_id);
		$query = $this->db->get('personnel, branch, personnel_overtime');
		
		return $query;
	}
	
	public function calculate_single_overtime($personnel_overtime_hours, $overtime_type, $overtime_type_rate, $branch_working_hours, $personnel_id)
	{
		$total_overtime = 0;
		if($overtime_type_rate == 1)
		{
			if($overtime_type == 1)
			{
				$overtime_rate = $this->config->item('normal_overtime_rate');
			}
			else if($overtime_type == 2)
			{
				$overtime_rate = $this->config->item('holiday_overtime_rate');
			}
			
			//get basic pay
			$this->db->where('personnel_id', $personnel_id);
			$basic_pay_query = $this->db->get('personnel_payment');
			$basic_pay = 0;
			if($basic_pay_query->num_rows() > 0)
			{
				$basic_row = $basic_pay_query->row();
				$basic_pay = $basic_row->personnel_payment_amount;
			}
			if($branch_working_hours > 0)
			{
				$total_overtime = ($basic_pay * $overtime_rate * $personnel_overtime_hours) / $branch_working_hours;
			}
			else
			{
				$total_overtime = 0;
			}
		}
		
		else
		{
			$total_overtime = $personnel_overtime_hours;
		}
		
		if(($total_overtime >= 0) && !empty($total_overtime))
		{
			return number_format($total_overtime, 2);
		}
		
		else
		{
			return $total_overtime;
		}
	}
	
	public function calculate_overtime($personnel_id)
	{
		$query = $this->cc_payment_model->get_overtime_hours($personnel_id);
		$total_overtime = 0;
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				$personnel_overtime_hours = $row->personnel_overtime_hours;
				$branch_working_hours = $row->branch_working_hours;
				$overtime_type = $row->overtime_type;
				$overtime_type_rate = $row->overtime_type_rate;
				
				if($overtime_type_rate == 1)
				{
					if($overtime_type == 1)
					{
						$overtime_rate = $this->config->item('normal_overtime_rate');
					}
					else if($overtime_type == 2)
					{
						$overtime_rate = $this->config->item('holiday_overtime_rate');
					}
					
					//get basic pay
					$this->db->where('personnel_id', $personnel_id);
					$basic_pay_query = $this->db->get('personnel_payment');
					$basic_pay = 0;
					if($basic_pay_query->num_rows() > 0)
					{
						$basic_row = $basic_pay_query->row();
						$basic_pay = $basic_row->personnel_payment_amount;
					}
					if ($branch_working_hours > 0)
					{
						$total_overtime += ($basic_pay * $overtime_rate * $personnel_overtime_hours) / $branch_working_hours;
					}
					else
					{
						$total_overtime = 0;
					}
				}
				
				else
				{
					$total_overtime += $personnel_overtime_hours;
				}
			}
		}
		
		//save allowance (overtime)
		$this->db->where(array('personnel_id' => $personnel_id, 'allowance_id' => 1));
		$query = $this->db->get('personnel_allowance');
		
		//if personnel exists, update
		if($query->num_rows() > 0)
		{
			$this->db->where(array('personnel_id' => $personnel_id, 'allowance_id' => 1));
			if($this->db->update('personnel_allowance', array('personnel_allowance_amount' => $total_overtime)))
			{
			}
		}
		
		else
		{
			if($this->db->insert('personnel_allowance', array('personnel_allowance_amount' => $total_overtime, 'personnel_id' => $personnel_id, 'allowance_id' => 1)))
			{
			}
		}
		return number_format($total_overtime, 2);
	}
	
	//total basic pay for each cc_payment
	public function get_total_basic_pay($cc_payment_id,$branch_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS total_basic_pay');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment_item.table = 7 AND  cc_payment_item.table_id = 1 AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id = personnel.personnel_id AND personnel.branch_id = '.$branch_id.' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item,personnel');
		
		if($query->num_rows() > 0)
		{
			$basic_row = $query->row();
			$basic_pay = $basic_row->total_basic_pay;
		}
		return $basic_pay;
	}
	
	//total benefits for each cc_payment
	public function get_total_benefits($cc_payment_id,$branch_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS total_benefits');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment_item.table = 8 AND  cc_payment_item.table_id = benefit.benefit_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id = personnel.personnel_id AND personnel.branch_id = '.$branch_id.' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item,personnel,benefit');
		
		if($query->num_rows() > 0)
		{
			$benefits = $query->row();
			$total_benefits = $benefits->total_benefits;
		}
		return $total_benefits;
	}
	
	//total allowances for each cc_payment
	public function get_total_allowances($cc_payment_id,$branch_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS total_allowances');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment_item.table = 3 AND  cc_payment_item.table_id = allowance.allowance_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id = personnel.personnel_id AND personnel.branch_id = '.$branch_id.' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item,personnel,allowance');
		
		if($query->num_rows() > 0)
		{
			$allowances = $query->row();
			$total_allowances = $allowances->total_allowances;
		}
		return $total_allowances;
	}
	
	//helb
	public function get_total_helb($cc_payment_id,$branch_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS total_helb');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment_item.table = 4 AND  cc_payment_item.table_id = deduction.deduction_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id = personnel.personnel_id AND personnel.branch_id = '.$branch_id.' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item,personnel,deduction');
		
		if($query->num_rows() > 0)
		{
			$helb = $query->row();
			$helb_total = $helb->total_helb;
		}
		return $helb_total;
	}
	//paye
	public function get_total_paye($cc_payment_id,$branch_id)
	{
		$this->db->select('SUM(cc_payment_item_amount) AS total_paye');
		$this->db->where('cc_payment.cc_payment_id = cc_payment_item.cc_payment_id AND cc_payment_item.table = 9 AND  cc_payment_item.table_id = paye.paye_id AND cc_payment.cc_payment_status = 1 AND cc_payment_item.personnel_id = personnel.personnel_id AND personnel.branch_id = '.$branch_id.' AND cc_payment.branch_id ='.$branch_id);
		$query = $this->db->get('cc_payment,cc_payment_item,personnel,paye');
		
		if($query->num_rows() > 0)
		{
			$paye = $query->row();
			$paye_total = $paye->total_paye;
		}
		return $paye_total;
	}
	//import overtime template
	function import_overtime_template()
	{
		$this->load->library('Excel');
		
		$title = 'Overtime Import Template';
		$count=1;
		$row_count=0;
		
		$report[$row_count][0] = 'Employee Number';
		$report[$row_count][1] = 'Amount (Hrs/Value)';
		$report[$row_count][2] = 'Overtime Type (Normal-1,Holiday-2)';
		$report[$row_count][3] = 'Overtime Rate (Rate-1,Amount-2)';
		
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	//import overtime data
	public function import_csv_overtime($upload_path)
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
			$response2 = $this->sort_overtime_data($array);
		
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
	//sort overtime imported data
	public function sort_overtime_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0) && ($total_columns == 4))
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
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$personnel_number = $array[$r][0];
				$personnel_number = str_replace(" ", "", $personnel_number);
				$branch_id = $this->input->post('branch_id');
				
				$items['personnel_overtime_hours'] = $array[$r][1];
				$items['overtime_type'] = $array[$r][2];
				$items['overtime_type_rate'] = $array[$r][3];
				$comment = '';
				if(!empty($personnel_number))
				{
					$personnel_id = $this->get_personnel_id($personnel_number, $branch_id);
					$items['personnel_id'] = $personnel_id;
					$overtime_type = $array[$r][2];
					// check if the personnel overtime already exists
					if($this->check_current_personnel_overtime_exists($personnel_id,$overtime_type))
					{
						$overtime_type = $array[$r][2];
						
						//personnel exists for that overtime type then update existing data
						$this->db->where('personnel_id ='.$personnel_id.' AND overtime_type = '.$overtime_type);
						if($this->db->update('personnel_overtime', $items))
						{
							$this->calculate_overtime($personnel_id);
							$comment .= '<br/>'.$personnel_number.' overtime of '.$items['personnel_overtime_hours'].' successfully updated';
							$class = 'success';
						}
						
						else
						{
							$comment .= '<br/>'.$personnel_number.' overtime of '.$items['personnel_overtime_hours'].' could not be updated';
							$class = 'danger';
						}
					}
					else
					{
						// number does not exisit
						//save product in the db
						if($this->db->insert('personnel_overtime', $items))
						{
							$this->calculate_overtime($personnel_id);
							$comment .= '<br/>'.$personnel_number.' overtime of '.$items['personnel_overtime_hours'].' successfully added to the database';
							$class = 'success';
						}
						
						else
						{
							$comment .= '<br/>Internal error. Could not add mpersonnel to the database. Please contact the site administrator';
							$class = 'warning';
						}
					}
				}
				
				else
				{
					$comment .= '<br/>Not saved ensure you have a member number entered';
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$personnel_number.'</td>
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
	public function get_personnel_id($personnel_number, $branch_id)
	{
		$this->db->where('personnel_number = "'.$personnel_number.'" AND personnel.branch_id = '.$branch_id);
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
	public function check_current_personnel_overtime_exists($personnel_id,$overtime_type)
	{
		$this->db->where('personnel_id =' .$personnel_id.' AND overtime_type = '.$overtime_type);
		
		$query = $this->db->get('personnel_overtime');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function get_personnel_emails($cc_payment_id)
	{
		$this->db->where('cc_payment.cc_payment_id = "'.$cc_payment_id.'" AND personnel.personnel_id = cc_payment_item.personnel_id AND cc_payment.cc_payment_id = cc_payment_item.cc_payment_id ');
		$this->db->select('personnel.*');
		$this->db->group_by(' personnel.personnel_id');
		$result = $this->db->get('personnel, cc_payment, cc_payment_item');
		
		return $result;
	}
	public function get_branch_email($branch_id)
	{
		
		$table = "branch";
		$where = "branch_id = ".$branch_id;
		
		$this->db->where($where);
		$this->db->select('branch_email');
		$result = $this->db->get($table);
		if($result->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function get_other_benefits()
	{
		$this->db->where('benefit_id != 1 ');
		$this->db->select('*');
		$result = $this->db->get('benefit');
		
			return $result;
	}
	public function get_other_allowances()
	{
		$this->db->where('allowance_id != 1 AND allowance_id != 7 AND allowance_id != 9');
		$this->db->select('*');
		$result = $this->db->get('allowance');
		
		return $result;
	}
	
	public function is_payslip_downloaded($personnel_id, $cc_payment_id)
	{
		$this->db->where('personnel_payslip_status = 1 AND personnel_id = '.$personnel_id.' AND cc_payment_id = '.$cc_payment_id);
		$this->db->select('*');
		$result = $this->db->get('personnel_payslip');
		
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$personnel_payslip_name = $row->personnel_payslip_name;
			
			return $personnel_payslip_name;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function download_payslip($cc_payment_id, $personnel_id, $branches, $payslip_path)
	{
		$html = '';
		$where = 'cc_payment_item.personnel_id = personnel.personnel_id AND cc_payment_item.cc_payment_id = '.$cc_payment_id.' AND cc_payment_item.personnel_id = '.$personnel_id;
		
		if($branches->num_rows() > 0)
		{
			$row = $branches->result();
			$branch_id = $row[0]->branch_id;
			$branch_name = $row[0]->branch_name;
			$month_id = $row[0]->month_id;
			$branch_image_name = $row[0]->branch_image_name;
			$branch_address = $row[0]->branch_address;
			$branch_post_code = $row[0]->branch_post_code;
			$branch_city = $row[0]->branch_city;
			$branch_phone = $row[0]->branch_phone;
			$branch_email = $row[0]->branch_email;
			$branch_location = $row[0]->branch_location;
			$month_id = $row[0]->month_id;
			$cc_payment_year = $row[0]->cc_payment_year;
			$file_data = $row[0]->file_data;
			if(empty($file_data))
			{
				echo 'Please generate the cc_payment again to view the bank report';
				die();
			}
			$this->load->helper('file');
			$cc_payment_path = realpath(APPPATH . '../assets/cc_payment/');
			$file = $cc_payment_path.'\\'.$file_data.'.txt';
			$data['cc_payment_data'] = json_decode(read_file($file));
			$where .= ' AND branch_id = '.$branch_id;
		}
		$result = $this->personnel_model->get_personnel($personnel_id);
		
		if($result->num_rows() > 0)
		{
			$row2 = $result->row();
			$onames = $row2->personnel_onames;
			$fname = $row2->personnel_fname;
			$personnel_number = $row2->personnel_number;
			$nssf_number = $row2->personnel_nssf_number;
			$nhif_number = $row2->personnel_nhif_number;
			$kra_pin = $row2->personnel_kra_pin;
			 
			$data['personnel_number'] = $personnel_number;
			$data['nssf_number'] = $nssf_number;
			$data['nhif_number'] = $nhif_number;
			$data['kra_pin'] = $kra_pin;
			$data['personnel_name'] = $fname." ".$onames;
			$data['personnel_id'] = $personnel_id;
			$data['personnel_number'] = $row2->personnel_number;
		}
		$data['branch_name'] = $branch_name;
		$data['branch_image_name'] = $branch_image_name;
		$data['branch_id'] = $branch_id;
		$data['branch_address'] = $branch_address;
		$data['branch_post_code'] = $branch_post_code;
		$data['branch_city'] = $branch_city;
		$data['branch_phone'] = $branch_phone;
		$data['branch_email'] = $branch_email;
		$data['branch_location'] = $branch_location;
		$data['personnel_id'] = $personnel_id;
		$data['cc_payment_id'] = $cc_payment_id;
		$data['savings_table'] = $this->cc_payment_model->get_table_id("savings");
		$data['loan_scheme_table'] = $this->cc_payment_model->get_table_id("loan_scheme");
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		$data['personel_payments'] = $this->cc_payment_model->get_personnel_payments($personnel_id);
		$data['personnel_benefits'] = $this->cc_payment_model->get_personnel_benefits($personnel_id);
		$data['personnel_allowances'] = $this->cc_payment_model->get_personnel_allowances($personnel_id);
		$data['personnel_deductions'] = $this->cc_payment_model->get_personnel_deductions($personnel_id);
		$data['personnel_other_deductions'] = $this->cc_payment_model->get_personnel_other_deductions($personnel_id);
		$data['personnel_savings'] = $this->cc_payment_model->get_personnel_savings($personnel_id);
		$data['personnel_loan_schemes'] = $this->cc_payment_model->get_personnel_scheme($personnel_id);
		$data['cc_payment_items'] = $this->cc_payment_model->get_cc_payment_items($cc_payment_id);
		
		$html = $this->load->view('cc_payment/payslips', $data, TRUE);
		//echo $html; die();
		//download title
		$row = $data['query']->row();
		$personnel_number = $row->personnel_number;
		$personnel_fname = $row->personnel_fname;
		$personnel_onames = $row->personnel_onames;
		$personnel_national_id_number = $row->personnel_national_id_number;
		$title = $month_id.' '.$cc_payment_year.' '.$personnel_onames.' '.$personnel_fname.' payslip.pdf';
        //load mPDF library
		
		/*header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
		header('Pragma: no-cache'); // HTTP 1.0
		header('Expires: 0'); // Proxies*/
        /*$this->load->library('mpdf/mpdf');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->SetProtection(array(), $personnel_national_id_number);
		$this->mpdf->Output($title, 'F');*/
		$mpdf=new mPDF();
		$mpdf->WriteHTML($html);
		$mpdf->SetProtection(array(), $personnel_national_id_number);
		$mpdf->Output($title, 'F');
		
		//Add payslip to database
		$this->db->where(array('personnel_id' => $personnel_id, 'cc_payment_id' => $cc_payment_id));
		$this->db->update('personnel_payslip', array('personnel_payslip_status' => 0));
		
		$this->db->insert('personnel_payslip', array('personnel_payslip_name' => $title, 'personnel_id' => $personnel_id, 'cc_payment_id' => $cc_payment_id, 'personnel_payslip_status' => 1, 'created' => date('Y-m-d H:i:s'), 'created_by' => $this->session->userdata('personnel_id'), 'modified_by' => $this->session->userdata('personnel_id')));
		
		//check if file has finished downloaded
		$payslip = $payslip_path.'/'.$title;
		//echo $payslip;die();
		while(!file_exists($payslip))
		{
			//print_r ($payslip);echo '<br/>';
			$payslip = $payslip_path.'/'.$title;
		}
		return $title;
		
		/*$this->mpdf->WriteHTML($html);
		
		$content = $this->mpdf->Output('', 'S');
		
		$content = chunk_split(base64_encode($content));
		
		$mailto = 'alvaro@omnis.co.ke';
		
		$from_name = 'Omnis Limited';
		
		$from_mail = 'hr@omnis.co.ke';
		
		$replyto = 'hr@omnis.co.ke';
		
		$uid = md5(uniqid(time()));
		
		$subject = 'Payslip';
		
		$message = 'Find your payslip attached';
		
		$filename = 'payslip.pdf';
		
		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		
		$header .= "Reply-To: ".$replyto."\r\n";
		
		$header .= "MIME-Version: 1.0\r\n";
		
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		
		$header .= "This is a multi-part message in MIME format.\r\n";
		
		$header .= "--".$uid."\r\n";
		
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		
		$header .= $message."\r\n\r\n";
		
		$header .= "--".$uid."\r\n";
		
		$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
		
		$header .= "Content-Transfer-Encoding: base64\r\n";
		
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		
		$header .= $content."\r\n\r\n";
		
		$header .= "--".$uid."--";
		
		$is_sent = @mail($mailto, $subject, "", $header);
		
		$this->mpdf->Output();
		
		exit;*/
	}
	public function get_other_payments()
	{
		$this->db->where('payment_id > 1');
		$this->db->select('*');
		$result = $this->db->get('payment');
		
		return $result;
	}
}
?>