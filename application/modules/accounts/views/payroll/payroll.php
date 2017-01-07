<?php
$personnel_id = $this->session->userdata('personnel_id');
$prepared_by = $this->session->userdata('first_name');
$roll = $payroll->row();
$year = $roll->payroll_year;
$month = $roll->month_id;
$totals = array();
		
if($branches->num_rows() > 0)
{
	$row = $branches->result();
	$branch_id = $row[0]->branch_id;
	$branch_name = $row[0]->branch_name;
	$branch_image_name = $row[0]->branch_image_name;
	$branch_address = $row[0]->branch_address;
	$branch_post_code = $row[0]->branch_post_code;
	$branch_city = $row[0]->branch_city;
	$branch_phone = $row[0]->branch_phone;
	$branch_email = $row[0]->branch_email;
	$branch_location = $row[0]->branch_location;

	$data['branch_name'] = $branch_name;
	$data['branch_image_name'] = $branch_image_name;
	$data['branch_id'] = $branch_id;
	$data['branch_address'] = $branch_address;
	$data['branch_post_code'] = $branch_post_code;
	$data['branch_city'] = $branch_city;
	$data['branch_phone'] = $branch_phone;
	$data['branch_email'] = $branch_email;
	$data['branch_location'] = $branch_location;
}
//$query = retrieval of all payroll personnel
if ($query->num_rows() > 0)
{
	$count = 0;
	
	$result = 
	'
	<table class="table table-bordered table-striped table-condensed" id ="customers">
		<thead>
			<tr>
				<th>#</th>
				<th>Ref</th>
				<th>Personnel</th>
				
				';
	$total = 'total_';
	$total_number_of_payments = 3;
	$total_gross = 0;
	$total_paye = 0;
	$total_nssf = 0;
	$total_nhif = 0;
	$total_life_ins = 0;
	$total_allowances = 0;
	$total_savings = 0;
	$total_loans = 0;
	$total_schemes = 0;
	$total_net = 0;
	$total_personnel_benefits = $total_personnel_payments = $total_personnel_allowances = $total_personnel_deductions = $total_personnel_other_deductions = array();
	
	$benefits_amount = $payroll_data->benefits;
	$total_benefits = $payroll_data->total_benefits;
	$payments_amount = $payroll_data->payments;
	$total_payments = $payroll_data->total_payments;
	$allowances_amount = $payroll_data->allowances;
	$total_allowances = $payroll_data->total_allowances;
	$deductions_amount = $payroll_data->deductions;
	$total_deductions = $payroll_data->total_deductions;
	$other_deductions_amount = $payroll_data->other_deductions;
	$total_other_deductions = $payroll_data->total_other_deductions;
	$nssf_amount = $payroll_data->nssf;
	$nhif_amount = $payroll_data->nhif;
	$life_ins_amount = $payroll_data->life_ins;
	$paye_amount = $payroll_data->paye;
	$monthly_relief_amount = $payroll_data->monthly_relief;
	$insurance_relief_amount = $payroll_data->insurance_relief;
	$insurance_amount_amount = $payroll_data->insurance;
	$scheme = $payroll_data->scheme;
	$savings = $payroll_data->savings;
	
	//payments
	if($payments->num_rows() > 0)
	{
		foreach($payments->result() as $res)
		{
			$payment_abbr = $res->payment_name;
			$payment_id = $res->payment_id;
			$total_payment_amount[$payment_id] = 0;
			if(isset($total_payments->$payment_id))
			{
				$total_payment_amount[$payment_id] = $total_payments->$payment_id;
			}
			
			if($total_payment_amount[$payment_id] != 0)
			{
				$result .= '<th>'.$payment_abbr.'</th>';
			}
		}
	}
	
	//benefits
	if($benefits->num_rows() > 0)
	{
		foreach($benefits->result() as $res)
		{
			$benefit_abbr = $res->benefit_name;
			$benefit_id = $res->benefit_id;
				
			$total_benefit_amount[$benefit_id] = 0;
			if(isset($total_benefits->$benefit_id))
			{
				$total_benefit_amount[$benefit_id] = $total_benefits->$benefit_id;
			}
			
			if($total_benefit_amount[$benefit_id] != 0)
			{
				$result .= '<th>'.$benefit_abbr.'</th>';
			}
		}
	}
	
	//allowances
	if($allowances->num_rows() > 0)
	{
		foreach($allowances->result() as $res)
		{
			$allowance_abbr = $res->allowance_name;
			$allowance_id = $res->allowance_id;
				
			$total_allowance_amount[$allowance_id] = 0;
			if(isset($total_allowances->$allowance_id))
			{
				$total_allowance_amount[$allowance_id] = $total_allowances->$allowance_id;
			}
			if($total_allowance_amount[$allowance_id] != 0)
			{
				$result .= '<th>'.$allowance_abbr.'</th>';
			}
		}
	}
	
	//gross
	$result .= '
			<th>Gross</th>
			<th>PAYE</th>
			<th>NSSF</th>
			<th>NHIF</th>
			<th>Life Ins</th>
	';
	
	//deductions
	if($deductions->num_rows() > 0)
	{
		foreach($deductions->result() as $res)
		{
			$deduction_name = $res->deduction_name;
			$deduction_abbr = $res->deduction_name;
			$deduction_id = $res->deduction_id;
			$total.$deduction_abbr = 0;
				
			$total_deduction_amount[$deduction_id] = 0;
			if(isset($total_deductions->$deduction_id))
			{
				$total_deduction_amount[$deduction_id] = $total_deductions->$deduction_id;
			}
			
			if($total_deduction_amount[$deduction_id]!= 0)
			{
				$result .= '<th>'.$deduction_name.'</th>';
			}
			
			//display all except nssf nhif insurance & pension
			/*if(($deduction_id != 1))
			{
				$result .= '<th>'.$deduction_abbr.'</th>';
			}*/
		}
	}
	
	//other deductions
	if($other_deductions->num_rows() > 0)
	{
		foreach($other_deductions->result() as $res)
		{
			$other_deduction_abbr = $res->other_deduction_name;
			$other_deduction_id = $res->other_deduction_id;
				
			$total_other_deduction_amount[$other_deduction_id] = 0;
			if(isset($total_other_deductions->$other_deduction_id))
			{
				$total_other_deduction_amount[$other_deduction_id] = $total_other_deductions->$other_deduction_id;
			}
			
			if($total_other_deduction_amount[$other_deduction_id] != 0)
			{
				$result .= '<th>'.$other_deduction_abbr.'</th>';
			}
		}
	}
	
	$result .= '
				<!--<th>Savings</th>
				<th>Loans</th>-->
				<th>Net pay</th>
			</tr>
		</thead>
		<tbody>
	';
	
	foreach ($query->result() as $row)
	{
		$personnel_id = $row->personnel_id;
		$personnel_number = $row->personnel_number;
		$personnel_fname = $row->personnel_fname;
		$personnel_onames = $row->personnel_onames;
		$staff_id = $row->staff_id;
		$engagement_date = $row->engagement_date;
		$date_of_exit = $row->date_of_exit;
		$cost_center = $row->cost_center;
		$gross = 0;
		
		$table_id = 0;
		
		$count++;
		$result .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td>'.$personnel_number.'</td>
				<td>'.$personnel_onames.' '.$personnel_fname.'</td>
		';
		
		//payments
		if($payments->num_rows() > 0)
		{
			foreach($payments->result() as $res)
			{
				$payment_id = $res->payment_id;
				$payment_abbr = $res->payment_name;
				$table_id = $payment_id;
				
				if($total_payment_amount[$payment_id] != 0)
				{
					$payment_amt = 0;
					if(isset($payments_amount->$personnel_id->$table_id))
					{
						$payment_amt = $payments_amount->$personnel_id->$table_id;
					}
					$gross += $payment_amt;
					if(!isset($total_personnel_payments[$payment_id]))
					{
						$total_personnel_payments[$payment_id] = 0;
					}
					$total_personnel_payments[$payment_id] += $payment_amt;
					$result .= '<td>'.number_format($payment_amt, 2).'</td>';
				}
			}
		}
		
		//benefits
		$total_benefits = 0;
		if($benefits->num_rows() > 0)
		{
			foreach($benefits->result() as $res)
			{
				$benefit_id = $res->benefit_id;
				$benefit_name = $res->benefit_name;
				$table_id = $benefit_id;
								
				if($total_benefit_amount[$benefit_id] != 0)
				{
					$benefit_amt = 0;
					if(isset($benefits_amount->$personnel_id->$table_id))
					{
						$benefit_amt = $benefits_amount->$personnel_id->$table_id;
					}
					$total_benefits += $benefit_amt;
					if(!isset($total_personnel_benefits[$benefit_id]))
					{
						$total_personnel_benefits[$benefit_id] = 0;
					}
					$total_personnel_benefits[$benefit_id] += $benefit_amt;
					$result .= '<td>'.number_format($benefit_amt, 2).'</td>';
				}
			}
		}
		
		//allowances
		if($allowances->num_rows() > 0)
		{
			foreach($allowances->result() as $res)
			{
				$allowance_id = $res->allowance_id;
				$allowance_name = $res->allowance_name;
				$table_id = $allowance_id;
				
				if($total_allowance_amount[$allowance_id] != 0)
				{
					$allowance_amt = 0;
					if(isset( $allowances_amount->$personnel_id->$table_id))
					{
						$allowance_amt =  $allowances_amount->$personnel_id->$table_id;
					}
					$gross += $allowance_amt;
					if(!isset($total_personnel_allowances[$allowance_id]))
					{
						$total_personnel_allowances[$allowance_id] = 0;
					}
					$total_personnel_allowances[$allowance_id] += $allowance_amt;
					$result .= '<td>'.number_format($allowance_amt, 2).'</td>';
				}
			}
		}
		
		$result .= '<td>'.number_format($gross, 2).'</td>';
		$total_gross += $gross;
		
		/*
			--------------------------------------------------------------------------------------
			Select & display untaxable deductions for the personnel
			--------------------------------------------------------------------------------------
		*/
		
		//nssf
		$nssf = $nssf_amount->$personnel_id;
		$total_nssf += $nssf;
		
		//nhif
		$nhif = $nhif_amount->$personnel_id;
		$total_nhif += $nhif;
		
		//paye
		$paye =$paye_amount->$personnel_id;
		
		//relief
		$relief = $monthly_relief_amount->$personnel_id;
		
		//insurance_relief
		$insurance_relief = $insurance_relief_amount->$personnel_id;
		
		//relief
		$insurance_amount = $insurance_amount_amount->$personnel_id;
		//echo $insurance_relief;
		$paye -= ($relief + $insurance_relief);
						
		if($paye < 0)
		{
			$paye = 0;
		}
		$total_paye += $paye;
		$total_life_ins += $insurance_amount;
		$result .= 
		'
				<td>'.number_format($paye, 2).'</td>
				<td>'.number_format($nssf, 2).'</td>
				<td>'.number_format($nhif, 2).'</td>
				<td>'.number_format($insurance_amount, 2).'</td>
		';
		
		//deductions
		$total_deductions = 0;
		if($deductions->num_rows() > 0)
		{
			foreach($deductions->result() as $res)
			{
				$deduction_id = $res->deduction_id;
				$deduction_name = $res->deduction_name;
				
				$table_id = $deduction_id;
				
				if($total_deduction_amount[$deduction_id] != 0)
				{
					$deduction_amt = 0;
					if(isset($deductions_amount->$personnel_id->$table_id))
					{
						$deduction_amt = $deductions_amount->$personnel_id->$table_id;
					}
					$total_deductions += $deduction_amt;
					if(!isset($total_personnel_deductions[$deduction_id]))
					{
						$total_personnel_deductions[$deduction_id] = 0;
					}
					$total_personnel_deductions[$deduction_id] += $deduction_amt;
					$result .= '<td>'.number_format($deduction_amt, 2).'</td>';
				}
			}
		}
		
		//other_deductions
		$total_other_deductions = 0;
		if($other_deductions->num_rows() > 0)
		{
			foreach($other_deductions->result() as $res)
			{
				$other_deduction_id = $res->other_deduction_id;
				$other_deduction_name = $res->other_deduction_name;
				
				$table_id = $other_deduction_id;
				
				if($total_other_deduction_amount[$other_deduction_id] != 0)
				{
					$other_deduction_amt = 0;
					if(isset($other_deductions_amount->$personnel_id->$table_id))
					{
						$other_deduction_amt = $other_deductions_amount->$personnel_id->$table_id;
					}
					$total_other_deductions += $other_deduction_amt;
					if(!isset($total_personnel_other_deductions[$other_deduction_id]))
					{
						$total_personnel_other_deductions[$other_deduction_id] = 0;
					}
					$total_personnel_other_deductions[$other_deduction_id] += $other_deduction_amt;
					$result .= '<td>'.number_format($other_deduction_amt, 2).'</td>';
				}
			}
		}
		
		//savings
		/*$rs_savings = $this->payroll_model->get_savings();
		$total_savings = 0;
		
		if($rs_savings->num_rows() > 0)
		{
			foreach($rs_savings->result() as $res)
			{
				$savings_name = $res->savings_name;
				$savings_id = $res->savings_id;
				
				$table = $this->payroll_model->get_table_id("savings");
			
				//get schemes
				$total_savings += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $savings_id);
			}
		}
		$total_savings += $total_savings;
		$result .= '<th>'.number_format($total_savings, 2).'</th>';
		
		//get loan schemes
		$date = date("Y-m-d");
		$rs_schemes = $this->payroll_model->get_loan_schemes();
		$total_schemes = 0;
		$interest = 0;
		
		if($rs_schemes->num_rows() > 0)
		{
			foreach($rs_schemes->result() as $res)
			{
				$loan_scheme_name = $res->loan_scheme_name;
				$loan_scheme_name = $res->loan_scheme_name;
				$loan_scheme_id = $res->loan_scheme_id;
				
				$table = $this->payroll_model->get_table_id("loan_scheme");
			
				//get schemes
				$total_schemes += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $loan_scheme_id);
				
				//get interest
				$rs_interest = $this->payroll_model->get_loan_scheme_interest($personnel_id, $date, $loan_scheme_id);
				
				if($rs_interest->num_rows() > 0)
				{
					foreach($rs_interest->result() as $res2)
					{
						$interest += $res2->interest;
					}
				}
			}
		}
		$total_loans += ($total_schemes + $interest);
		$result .= '<th>'.number_format(($total_schemes + $interest), 2).'</th>';*/
		
		//total deductions
		$total_deductions = $total_deductions + $total_other_deductions + $total_schemes + $total_savings + $insurance_amount;
		
		//net
		$net = $gross - ($paye + $nssf + $nhif + $total_deductions);
		$total_net += $net;
		$net = number_format($net, 2, '.', ',');
		$result .= 
		'
				<td>'.$net.'</td>
			</tr> 
		';
	}
	
	$result .= '
			<tr> 
				<td colspan="'.$total_number_of_payments.'"></td>';
	if($payments->num_rows() > 0)
	{
		foreach($payments->result() as $res)
		{
			$payment_id = $res->payment_id;
			$table_id = $payment_id;
			
			if($total_payment_amount[$payment_id] != 0)
			{
				$result .= '<th>'.number_format($total_personnel_payments[$payment_id], 2).'</th>';
			}
		}
	}
	
	//benefits
	$total_benefits = 0;
	if($benefits->num_rows() > 0)
	{
		foreach($benefits->result() as $res)
		{
			$benefit_id = $res->benefit_id;
			$table_id = $benefit_id;
			
			if($total_benefit_amount[$benefit_id] != 0)
			{
				$result .= '<th>'.number_format($total_personnel_benefits[$benefit_id], 2).'</th>';
			}
		}
	}
	
	//allowances
	if($allowances->num_rows() > 0)
	{
		foreach($allowances->result() as $res)
		{
			$allowance_id = $res->allowance_id;
			$table_id = $allowance_id;
			
			if($total_allowance_amount[$allowance_id] != 0)
			{
				$result .= '<th>'.number_format($total_personnel_allowances[$allowance_id], 2).'</th>';
			}
		}
	}
	//gross
	$result .= '
			<th>'.number_format($total_gross, 2, '.', ',').'</th>
			<th>'.number_format($total_paye, 2, '.', ',').'</th>
			<th>'.number_format($total_nssf, 2, '.', ',').'</th>
			<th>'.number_format($total_nhif, 2, '.', ',').'</th>
			<th>'.number_format($total_life_ins, 2, '.', ',').'</th>
	';
	
	//deductions
	if($deductions->num_rows() > 0)
	{
		foreach($deductions->result() as $res)
		{
			$deduction_id = $res->deduction_id;
			$table_id = $deduction_id;
			
			if($total_deduction_amount[$deduction_id] != 0)
			{
				$result .= '<th>'.number_format($total_personnel_deductions[$deduction_id], 2).'</th>';
			}
		}
	}
	
	//other deductions
	if($other_deductions->num_rows() > 0)
	{
		foreach($other_deductions->result() as $res)
		{
			$other_deduction_id = $res->other_deduction_id;
			$table_id = $other_deduction_id;
			
			if($total_other_deduction_amount[$other_deduction_id] != 0)
			{
				$result .= '<th>'.number_format($total_personnel_other_deductions[$other_deduction_id], 2).'</th>';
			}
		}
	}
	
	$result .= '
				<!--<th>'.number_format($total_savings, 2, '.', ',').'</th>
				<th>'.number_format($total_loans, 2, '.', ',').'</th>-->
				<th>'.number_format($total_net, 2, '.', ',').'</th>
			</tr>
	';
	
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
    <head>
        <title>Payroll</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
		<script type="text/javascript" src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>tableExport.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>jquery.base64.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>html2canvas.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>libs/sprintf.js"></script>
		<script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>jspdf.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/jspdf/";?>libs/base64.js"></script>
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
            img.logo{max-height:70px; margin:0 auto;}
            .table {margin-bottom: 0;}
        </style>
    </head>
    <body class="receipt_spacing" onLoad="window.print();return false;">
    	<div class="col-md-12 center-align receipt_bottom_border">
    		<table class="table table-condensed">
                <tr>
                    <th><?php echo $child_branch_name;?> Payroll for The month of <?php echo date('M Y',strtotime($year.'-'.$month));?></th>
                    <th class="align-right">
						<?php echo $branch_name;?><br/>
                        <?php echo $branch_address;?> <?php echo $branch_post_code;?> <?php echo $branch_city;?><br/>
                        E-mail: <?php echo $branch_email;?><br/>
                        Tel : <?php echo $branch_phone;?><br/>
                        <?php echo $branch_location;?>
                    </th>
                    <th>
                        <img src="<?php echo base_url().'assets/logo/'.$branch_image_name;?>" alt="<?php echo $branch_name;?>" class="img-responsive logo"/>
                    </th>
                </tr>
            </table>
        </div>
    <!--<body class="receipt_spacing">
    	<div class="row" >
        	<img src="<?php echo base_url().'assets/logo/'.$branch_image_name;?>" alt="<?php echo $branch_name;?>" class="img-responsive logo"/>
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	<?php echo $branch_name;?><br/>
                    <?php echo $branch_address;?> <?php echo $branch_post_code;?> <?php echo $branch_city;?><br/>
                    E-mail: <?php echo $branch_email;?>. Tel : <?php echo $branch_phone;?><br/>
                    <?php echo $branch_location;?><br/>
                </strong>
            </div>
        </div>
        
      	<div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<h4><?php echo '<h3>Payroll for The month of '.date('M Y',strtotime($year.'-'.$month)).'</h3>';?></h4>
            </div>
        </div>-->
        
        <div class="row receipt_bottom_border" >
        	<div class="col-md-12">
            	<?php echo $result;?>
            </div>
        	<div class="col-md-12 center-align">
            	<?php echo 'Prepared By:	'.$prepared_by.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
            </div>
        </div>
		<a href="#" onClick ="$('#customers').tableExport({type:'excel',escape:'false'});">XLS</a>
<!--<a href="#" onClick ="$('#customers').tableExport({type:'csv',escape:'false'});">CSV</a>
<a href="#" onClick ="$('#customers').tableExport({type:'pdf',escape:'false'});">PDF</a>-->

    </body>
</html>
