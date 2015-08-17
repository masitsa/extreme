
<?php
$personnel_id = $this->session->userdata('personnel_id');
$personnel_fname = $this->session->userdata('first_name');

if(empty($year)){
	$year = date("Y");
}
if(empty($month)){
	$month = $this->payroll_model->get_month_id(date("M"));
}

//HEADER
$billTotal = 0;
$total_cost = 0;

if ($query->num_rows() > 0)
{
	$count = 0;
	
	$result = 
	'
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th>Ref</th>
				<th>Personnel</th>
				<th>Basic</th>
				';
	
	//allowances
	if($allowances->num_rows() > 0)
	{
		foreach($allowances->result() as $res)
		{
			$allowance_abbr = $res->allowance_abbr;
			$allowance_id = $res->allowance_id;
			
			$result .= '<th>'.$allowance_abbr.'</th>';
		}
	}
	
	//gross
	$result .= '
			<th>Gross</th>
			<th>NSSF</th>
			<th>NHIF</th>
			<th>INS</th>
			<th>PENS</th>
			<th>TAXABLE</th>
			<th>PAYE</th>
	';
	
	//deductions
	if($deductions->num_rows() > 0)
	{
		foreach($deductions->result() as $res)
		{
			$deduction_abbr = $res->deduction_abbr;
			$deduction_id = $res->deduction_id;
			
			//display all except nssf nhif insurance & pension
			if(($deduction_id != 1))
			{
				if(($deduction_id != 2))
				{
					if(($deduction_id != 8))
					{
						if($deduction_id != 9)
						{
							$result .= '<th>'.$deduction_abbr.'</th>';
						}
					}
				}
			}
		}
	}
	
	$result .= '
				<th>Savings</th>
				<th>Loans</th>
				<th>Net pay</th>
			</tr>
		</thead>
		<tbody>
	';
	
	$total_basic_pay = 0;
	
	foreach ($query->result() as $row)
	{
		$personnel_id = $row->personnel_id;
		$personnel_number = $row->personnel_number;
		$personnel_fname = $row->personnel_fname;
		$personnel_onames = $row->personnel_onames;
		$gross = 0;
		
		
		//basic
		$table = $this->payroll_model->get_table_id("basic_pay");
		$table_id = 0;
		$basic_pay = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, $table_id);
		$total_basic_pay += $basic_pay;
		$gross += $basic_pay;
		
		$count++;
		$result .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td>'.$personnel_number.'</td>
				<td>'.$personnel_onames.' '.$personnel_fname.'</td>
				<td>'. number_format($basic_pay, 2).'</td>
		';
		
		//allowances
		if($allowances->num_rows() > 0)
		{
			foreach($allowances->result() as $res)
			{
				$allowance_id = $res->allowance_id;
				$table = $this->payroll_model->get_table_id("allowance");
				$table_id = $allowance_id;
				
				$allowance_amt = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, $table_id);
				$gross += $allowance_amt;
				$result .= '<td>'.number_format($allowance_amt, 2).'</td>';
			}
		}
		
		$result .= '<td>'.number_format($gross, 2).'</td>';
		
		/*
			--------------------------------------------------------------------------------------
			Select & display untaxable deductions for the personnel
			--------------------------------------------------------------------------------------
		*/
		$table = $this->payroll_model->get_table_id("deduction");
		
		//nssf
		$nssf = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, 1);
		
		//nhif
		$nhif = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, 2);
		
		//insurance
		$insurance =$this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, 8);
		
		//pension
		$pension = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, 9);
		
		//calculate taxable
		$taxable = $gross - $nssf - $insurance - $pension - $nhif;//echo $taxable."</br>";
		$paye = $this->payroll_model->paye_view($taxable);
		
		$result .= 
		'
				<td>'.number_format($nssf, 2).'</td>
				<td>'.number_format($nhif, 2).'</td>
				<td>'.number_format($insurance, 2).'</td>
				<td>'.number_format($pension, 2).'</td>
				<td>'.number_format($taxable, 2).'</td>
				<td>'.number_format($paye, 2).'</td>
		';
		
		//deductions
		$total_deductions = 0;
		if($deductions->num_rows() > 0)
		{
			foreach($deductions->result() as $res)
			{
				$deduction_id = $res->deduction_id;
				
				if(($deduction_id != 1))
				{
					if(($deduction_id != 2))
					{
						if(($deduction_id != 8))
						{
							if($deduction_id != 9)
							{
								$table_id = $deduction_id;
								$deduction_amt = $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, $table_id);
								$total_deductions += $deduction_amt;
								$result .= '<td>'.number_format($deduction_amt, 2).'</td>';
							}
						}
					}
				}
			}
		}
		
		//savings
		$rs_savings = $this->payroll_model->get_savings();
		$total_savings = 0;
		
		if($rs_savings->num_rows() > 0)
		{
			foreach($rs_savings->result() as $res)
			{
				$savings_name = $res->savings_name;
				$savings_id = $res->savings_id;
				
				$table = $this->payroll_model->get_table_id("savings");
			
				//get schemes
				$total_savings += $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, $savings_id);
			}
		}
		$result .= '<th>'.number_format($total_savings, 2).'</th>';
		
		//get loan schemes
		$date = date("Y-m-d");
		$rs_schemes = $this->payroll_model->get_loan_schemes();
		$total_schemes = 0;
		
		if($rs_schemes->num_rows() > 0)
		{
			foreach($rs_schemes->result() as $res)
			{
				$loan_scheme_name = $res->loan_scheme_name;
				$loan_scheme_id = $res->loan_scheme_id;
				
				$table = $this->payroll_model->get_table_id("loan_scheme");
			
				//get schemes
				$total_schemes += $this->payroll_model->get_payroll_amount($personnel_id, $year, $month, $table, $loan_scheme_id);
				
				//get interest
				$interest = 0;
				$rs_interest = $this->payroll_model->get_loan_scheme_interest($personnel_id, $date, $loan_scheme_id);
				
				if($rs_interest->num_rows() > 0)
				{
					foreach($rs_interest->result() as $res2)
					{
						$interest += $res2->interest;
					}
				}
				
				$total_deductions = $total_deductions + $total_schemes + $interest;
			}
		}
		$result .= '<th>'.number_format(($total_schemes + $interest), 2).'</th>';
			
		//net
		$net = number_format(round($gross - ($paye + $nssf + $nhif + $insurance + $pension + $total_deductions)), 2, '.', ',');
		
		$result .= 
		'
				<td>'.$net.'</td>
			</tr> 
		';
	}
	
	$result .= 
	'
				  </tbody>
				</table>
	';
}

else
{
	$result = "There are no personnel";
}

?>

<!DOCTYPE html>
<html lang="en">
	<style type="text/css">
		.receipt_spacing{letter-spacing:0px; font-size: 12px;}
		.center-align{margin:0 auto; text-align:center;}
		
		.receipt_bottom_border{border-bottom: #888888 medium solid;}
		.row .col-md-12 table {
			border:solid #000 !important;
			border-width:1px 0 0 1px !important;
			font-size:10px;
		}
		.row .col-md-12 th, .row .col-md-12 td {
			border:solid #000 !important;
			border-width:0 1px 1px 0 !important;
		}
		
		.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
		.title-img{float:left; padding-left:30px;}
	</style>
    <head>
        <title>Payroll</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
    </head>
    <body class="receipt_spacing">
    	<div class="row" >
        	<img src="<?php echo base_url().'assets/logo/'.$contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="img-responsive logo"/>
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	<?php echo $contacts['company_name'];?><br/>
                    <?php echo $contacts['address'];?> <?php echo $contacts['post_code'];?> <?php echo $contacts['city'];?><br/>
                    E-mail: <?php echo $contacts['email'];?>. Tel : <?php echo $contacts['phone'];?><br/>
                    <?php echo $contacts['location'];?><br/>
                </strong>
            </div>
        </div>
        
      	<div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<h4><?php echo '<h3>Payroll for The month of '.date('M Y',strtotime($year.'-'.$month)).'</h3>';?></h4>
            </div>
        </div>
        
        <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<?php echo $result; echo 'Prepared By:	'.$personnel_fname.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
            </div>
        </div>
    </body>
</html>
