<?php

class Payroll_model extends CI_Model 
{	
	public function allowances_view($personnel_id)
	{
		$table = "personnel_allowance";
		$where = "personnel_allowance_status = 0 AND personnel_id = $personnel_id";
		$items = "personnel_allowance_amount";
		$order = "personnel_allowance_amount";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$total = $total + $row2->personnel_allowance_amount;
			}
		}
		
		return $total;
	}
	
	public function deductions_view($personnel_id)
	{
		$table = "personnel_deduction";
		$where = "personnel_deduction_status = 0 AND personnel_id = $personnel_id";
		$items = "personnel_deduction_amount";
		$order = "personnel_deduction_amount";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		$total = 0;
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$total = $total + $row2->personnel_deduction_amount;
			}
		}
		
		return $total;
	}
	
	public function nssf_view($personnel_id)
	{
		$table = "personnel_deduction";
		$where = "personnel_deduction_status = 0 AND personnel_id = $personnel_id AND deduction_id = 4";
		$items = "personnel_deduction_amount";
		$order = "personnel_deduction_amount";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$personnel_deduction_amount = $row2->personnel_deduction_amount;
			}
		}
		
		else{
			$personnel_deduction_amount = 0;
		}
		
		return $personnel_deduction_amount;
	}
	
	public function insurance_view($personnel_id)
	{
		$table = "personnel_deduction";
		$where = "personnel_deduction_status = 0 AND personnel_id = $personnel_id AND deduction_id = 1";
		$items = "personnel_deduction_amount";
		$order = "personnel_deduction_amount";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$personnel_deduction_amount = $row2->personnel_deduction_amount;
			}
		}
		
		else{
			$personnel_deduction_amount = 0;
		}
		
		return $personnel_deduction_amount;
	}
	
	public function pension_view($personnel_id)
	{
		$table = "personnel_deduction";
		$where = "personnel_deduction_status = 0 AND personnel_id = $personnel_id AND deduction_id = 2";
		$items = "personnel_deduction_amount";
		$order = "personnel_deduction_amount";
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row2)
			{
				$personnel_deduction_amount = $row2->personnel_deduction_amount;
			}
		}
		
		else{
			$personnel_deduction_amount = 0;
		}
		
		return $personnel_deduction_amount;
	}
	
	public function taxable_view($gross, $nssf, $insurance, $pension)
	{
		if($insurance > 5000){
			$insurance = 0;
		}
		
		if($pension > 20000){
			$pension = 0;
		}
		$total = $gross - ($nssf + $insurance + $pension);
		
		return $total;
	}
	
	public function paye_view($taxable)
	{
		$tax = $this->calculate_paye($taxable);
		
		return $tax;
	}
	
	function calculate_paye($paye){
		
		$count = 1;
		$tax = 0;
		//echo $paye;
		while (($count < 6) && ($paye > 0)){ 
			
			if($count == 1){
				$rate = 10164;
				$tax = ($rate *0.10);
				//echo "<br/>1 = ".$tax;
				$paye = $paye - $rate;
				$count ++;
			}
			
			else if($count == 2){
				$rate = 9576;
				$tax = $tax + ($rate *0.15);
				//echo "<br/>2 = ".$tax;
				
				$paye = $paye - $rate;
				$count ++;
			}
			
			else if($count == 3){
				$rate = 9576;
				$tax = $tax + ($rate *0.20);
				//echo "<br/>3 = ".$tax;
				
				$paye = $paye - $rate;
				$count ++;
			}
			
			else if($count == 4){
				$rate = 9576;
				$tax = $tax + ($rate *0.25);
				//echo "<br/>4 = ".$tax;
				
				$paye = $paye - $rate;
				$count ++;
			}
			
			else if($count == 5){
				$tax = $tax + ($paye *0.30);
				//echo "<br/>5 = ".$tax."<br/>";
				
				$count ++;
			}
		}
		//echo $tax - 1162;
		
		if($tax > 0)
		{
			return $tax - 1162;
		}
		
		else
		{
			return 0;
		}
	}
	
	public function net_view($gross, $paye, $deductions)
	{
		$total = $gross - ($paye + $deductions);
		
		return $total;
	}
	
	public function get_personnel_savings($personnel_id)
	{
		$table = "personnel_savings";
		$items = "personnel_savings_amount AS amount, savings_id";
		$order = "amount";
		$where = "personnel_savings_status = 0 AND personnel_id = ".$personnel_id;
		
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
		$where = "personnel_scheme_status = 0 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
	}
	
	public function get_savings_opening($personnel_id)
	{
		$table = "savings_opening";
		$items = "*";
		$order = "savings_opening_id";
		$where = "personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
		$result = $this->db->get($table);
		return $result;
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
	
	public function get_personel_allowances($personnel_id)
	{
		$table = "personnel_allowance";
		$items = "personnel_allowance_amount AS amount, allowance_id AS id";
		$order = "id";
		$where = "personnel_allowance_status = 0 AND personnel_id = ".$personnel_id;
		
		$this->db->where($where);
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
	
	public function get_personel_deductions($personnel_id)
	{
		$table = "personnel_deduction";
		$items = "personnel_deduction_amount AS amount, deduction_id AS id";
		$order = "id";
		$where = "personnel_deduction_status = 0 AND personnel_id = ".$personnel_id;
		
		$this->db->select($items);
		$this->db->where($where);
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
	
	public function save_salary()
	{
		//Delete salary for that month
		$table = "payroll";
		$month = date("M");
		$year = date("Y");
		$mth = $this->month_calc($month);
		
		$where = array(
			"month_id" => $mth,
			"payroll_year" => $year
		);
		$this->db->where($where);
		$this->db->delete($table);
		
		//get personnel
		$result = $this->personnel_model->retrieve_personnel();
		if($result->num_rows() > 0)
		{
			foreach ($result->result() as $row):
				$personnel_id = $row->personnel_id;
				$basic_pay = $row->basic_pay;
				//$basic_pay_id = $row->basic_pay_id;
				$table_basic_pay = $this->get_table_id("basic_pay");
				$table_personnel = $this->get_table_id("personnel");
				$financial_year_id = $this->get_financial_year();
				
				/*
					--------------------------------------------------------------------------------------
					Basic Pay
					--------------------------------------------------------------------------------------
				*/
				if(!empty($basic_pay))
				{
					$pay = $basic_pay;
				}
				else{
					$pay = 0;
				}
				
				$items = array(
					"table" => $this->get_table_id("basic_pay"),
					"table_id" => 0,
					"month_id" => $mth,
					"payroll_year" => $year,
					"personnel_id" => $personnel_id,
					"payroll_amount" => $pay
				);
				
				$this->db->insert($table, $items);
				
				/*
					--------------------------------------------------------------------------------------
					Allowances
					--------------------------------------------------------------------------------------
				*/
				$where = "personnel_id = $personnel_id AND personnel_allowance_status = 0";
				$this->db->where($where);
				$result2 = $this->db->get("personnel_allowance");
				$table_allowance = $this->get_table_id("allowance");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$allowance = $row2->personnel_allowance_amount;
						$allowance_id = $row2->allowance_id;
				
						$items = array(
							"table" => $table_allowance,
							"table_id" => $allowance_id,
							"month_id" => $mth,
							"payroll_year" => $year,
							"personnel_id" => $personnel_id,
							"payroll_amount" => $allowance
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Deductions
					--------------------------------------------------------------------------------------
				*/
				$where = "personnel_id = $personnel_id AND personnel_deduction_status = 0";
				$this->db->where($where);
				$result2 = $this->db->get("personnel_deduction");
				$table_deduction = $this->get_table_id("deduction");
				
				if($result2->num_rows() > 0)
				{
					foreach ($result2->result() as $row2):
						$deduction = $row2->personnel_deduction_amount;
						$deduction_id = $row2->deduction_id;
						
						$items = array(
							"table" => $table_deduction,
							"table_id" => $deduction_id,
							"month_id" => $mth,
							"payroll_year" => $year,
							"personnel_id" => $personnel_id,
							"payroll_amount" => $deduction
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Savings
					--------------------------------------------------------------------------------------
				*/
				$table2 = "personnel_savings, savings";
				$where = "personnel_savings.personnel_id = $personnel_id AND personnel_savings.personnel_savings_status = 0 AND savings.savings_id = personnel_savings.savings_id";
				$items = "personnel_savings.personnel_savings_amount AS amount, savings.savings_name, personnel_savings.savings_id";
				$this->db->select($items);
				$this->db->where($where);
				$result3 = $this->db->get($table2);
				$table_savings = $this->get_table_id("savings");
				
				if($result3->num_rows() > 0)
				{
					foreach ($result3->result() as $row2):
						$savings = $row2->amount;
						$savings_id = $row2->savings_id;
						
						$items = array(
							"table" => $table_savings,
							"table_id" => $savings_id,
							"month_id" => $mth,
							"payroll_year" => $year,
							"personnel_id" => $personnel_id,
							"payroll_amount" => $savings
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
				
				/*
					--------------------------------------------------------------------------------------
					Loan Schemes
					--------------------------------------------------------------------------------------
				*/
				$date = date("Y-m-d");
				$table2 = "personnel_scheme, loan_scheme";
				$where = "personnel_scheme.personnel_id = $personnel_id 
				AND personnel_scheme.personnel_scheme_status = 0 
				AND personnel_scheme.personnel_scheme_repayment_sdate <= '$date' 
				AND personnel_scheme.personnel_scheme_repayment_edate >= '$date' 
				AND personnel_scheme.loan_scheme_id = loan_scheme.loan_scheme_id";
				$items = "personnel_scheme.personnel_scheme_monthly AS amount, personnel_scheme.personnel_scheme_interest AS interest, loan_scheme.loan_scheme_name AS scheme_name, personnel_scheme.personnel_scheme_repayment_sdate AS sdate, personnel_scheme.personnel_scheme_repayment_edate AS edate, personnel_scheme.loan_scheme_id";
				
				$this->db->select($items);
				$this->db->where($where);
				$result4 = $this->db->get($table2);
				
				$table_scheme = $this->get_table_id("loan_scheme");
				
				if($result4->num_rows() > 0)
				{
					foreach ($result4->result() as $row2):
						$amount = $row2->amount;
						$interest = $row2->interest;
						$scheme_id = $row2->loan_scheme_id;
						
						$items = array(
							"table" => $table_scheme,
							"table_id" => $scheme_id,
							"month_id" => $mth,
							"payroll_year" => $year,
							"personnel_id" => $personnel_id,
							"payroll_amount" => $amount
						);
				
					$this->db->insert($table, $items);
					endforeach;
				}
			endforeach;
		}
		
		return $mth;
	}
	
	public function get_payroll_amount($personnel_id, $year, $month, $table, $table_id)
	{
		$this->db->select('payroll_amount AS amount');
		$this->db->from('payroll');
		$this->db->where("personnel_id = $personnel_id AND payroll_year = '".$year."' AND month_id = ".$month." AND `table` = ".$table." AND table_id = ".$table_id);
		
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
}
?>