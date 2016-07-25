
<?php
$personnel_id = $this->session->userdata('personnel_id');
$prepared_by = $this->session->userdata('first_name');
$roll = $cc_payment->row();
$year = $roll->cc_payment_year;
$month = $roll->month_id;
$totals = array();

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
				<th>Pin</th>
				<th>Chargeable Pay</th>
				<th>PAYE</th>
	';
	$total_gross = 0;
	$total_paye = 0;
	
	$result .= '
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
		$personnel_kra_pin = $row->personnel_kra_pin;
		$gross = 0;
		
		//basic
		$table = $this->cc_payment_model->get_table_id("basic_pay");
		$table_id = 0;
		$basic_pay = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, $table_id);
		//$total_basic_pay += $basic_pay;
		//$gross += $basic_pay;
		
		$count++;
		$result .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td>'.$personnel_number.'</td>
				<td>'.$personnel_onames.' '.$personnel_fname.'</td>
				<td>'.$personnel_kra_pin.'</td>
		';
		
		//payments
		if($payments->num_rows() > 0)
		{
			foreach($payments->result() as $res)
			{
				$payment_id = $res->payment_id;
				$payment_abbr = $res->payment_name;
				$table = $this->cc_payment_model->get_table_id("payment");
				$table_id = $payment_id;
				
				$payment_amt = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, $table_id);
				$gross += $payment_amt;
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
				$table = $this->cc_payment_model->get_table_id("benefit");
				$table_id = $benefit_id;
				
				$benefit_amt = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, $table_id);
				$total_benefits += $benefit_amt;
			}
		}
		
		//allowances
		if($allowances->num_rows() > 0)
		{
			foreach($allowances->result() as $res)
			{
				$allowance_id = $res->allowance_id;
				$allowance_name = $res->allowance_name;
				$table = $this->cc_payment_model->get_table_id("allowance");
				$table_id = $allowance_id;
				
				$allowance_amt = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, $table_id);
				$gross += $allowance_amt;
			}
		}
		
		$result .= '<td>'.number_format($gross + $total_benefits, 2).'</td>';
		$total_gross += $gross;
		
		//paye
		$table = $this->cc_payment_model->get_table_id("paye");
		$paye =$this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, 1);
		
		//relief
		$table = $this->cc_payment_model->get_table_id("relief");
		$relief = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, 1);
		
		//insurance_relief
		$table = $this->cc_payment_model->get_table_id("insurance_relief");
		$insurance_relief = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, 1);
		
		//relief
		$table = $this->cc_payment_model->get_table_id("insurance_amount");
		$insurance_amount = $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_id, $table, 1);
		
		$paye -= ($relief + $insurance_relief);
						
		if($paye < 0)
		{
			$paye = 0;
		}
		$total_paye += $paye;
		$result .= 
		'
				<td>'.number_format($paye, 2).'</td>
		';
	}
	
	$result .= '
			<tr> 
				<td colspan="4"></td>';
	//gross
	$result .= '
			<th>'.number_format($total_gross, 2, '.', ',').'</th>
			<th>'.number_format($total_paye, 2, '.', ',').'</th>
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
	</style>
    <head>
        <title>PAYE Report</title>
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
            	<h4><?php echo '<h3>PAYE for The month of '.date('M Y',strtotime($year.'-'.$month)).'</h3>';?></h4>
            </div>
        </div>
        
        <div class="row receipt_bottom_border" >
        	<div class="col-md-12">
            	<?php echo $result;?>
            </div>
        	<div class="col-md-12 center-align">
            	<?php echo 'Prepared By: '.$prepared_by.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
            </div>
        </div>
    </body>
</html>
