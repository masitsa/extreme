<?php
$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
//served by
$served_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));

//if users exist display them
if ($query->num_rows() > 0)
{
	
	$result = 
	'
		<table class="table table-hover table-bordered ">
		  <thead>
			<tr>
			  <th style="text-align:center" rowspan=2>#</th>
			  <th style="text-align:center" rowspan=2>Transaction Date</th>
			  <th rowspan=2>Description</th>
			  <th colspan=2 style="text-align:center;">Amount</th>
			
			</tr>
			<tr>
			  <th style="text-align:center">Debit</th>
			  <th style="text-align:center">Credit</th>
			</tr>
		  </thead>
		  <tbody>
	';
	
	$total_debit = 0;
	$total_credit = 0;
	$count = 0;
	
	foreach ($query->result() as $row)
	{
		$creditor_account_id = $row->creditor_account_id;
		$creditor_account_description = $row->creditor_account_description;
		$creditor_account_amount = $row->creditor_account_amount;
		$transaction_type_id = $row->transaction_type_id;
		$creditor_account_date = $row->creditor_account_date;

		if($transaction_type_id == 1)
		{
			$debit = number_format($creditor_account_amount,2);
			$credit = '';
			$total_debit += $creditor_account_amount;
		}
		else if($transaction_type_id == 2)
		{
			$credit = number_format($creditor_account_amount,2);
			$debit = '';
			$total_credit += $creditor_account_amount;
		}
		
		else
		{
			$debit = '';
			$credit = '';
		}
		
		$count++;
		$result .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td style="text-align:center">'.date('jS M Y', strtotime($creditor_account_date)).'</td>
				<td>'.$creditor_account_description.'</td>
				<td style="text-align:center">'.$debit.'</td>
				<td style="text-align:center">'.$credit.'</td>
			</tr> 
		';
	}
	$result .= 
		'
			<tr>
				<td colspan="3" style="text-align:right">Total</td>
				<td style="text-align:center; font-weight:bold;">'.number_format($total_debit,2).'</td>
				<td style="text-align:center; font-weight:bold;">'.number_format($total_credit,2).'</td>
			</tr> 
		';
		$balance =  $total_debit - $total_credit;
			$result .= 
			'
				<tr>
					<td colspan="3" style="text-align:right; font-weight:bold;">Balance</td>
					<td colspan="2" style="text-align:center; font-weight:bold;">'.number_format($balance,2).'</td>
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
	$result = "There are no items";
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | Petty cash</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css" media="all"/>
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
			.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td
			{
				 padding: 2px;
			}
			
			.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
			.title-img{float:left; padding-left:30px;}
			img.logo{max-height:70px; margin:0 auto;}
		</style>
    </head>
    <body class="receipt_spacing">
    	<div class="row">
        	<div class="col-xs-12">
            	<img src="<?php echo base_url().'assets/logo/'.$contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="img-responsive logo"/>
            </div>
        </div>
    	<div class="row">
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	<?php echo $contacts['company_name'];?><br/>
                    P.O. Box <?php echo $contacts['address'];?> <?php echo $contacts['post_code'];?>, <?php echo $contacts['city'];?><br/>
                    E-mail: <?php echo $contacts['email'];?>. Tel : <?php echo $contacts['phone'];?><br/>
                    <?php echo $contacts['location'];?>, <?php echo $contacts['building'];?>, <?php echo $contacts['floor'];?><br/>
                </strong>
            </div>
        </div>
        
      <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<strong><?php echo $title;?></strong>
            </div>
        </div>
        
    	<div class="row">
        	<div class="col-md-12">
            	<?php echo $result;?>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-10 pull-left">
            	Prepared by: <?php echo $served_by;?> 
          	</div>
        	<div class="col-md-2 pull-right">
            	<?php echo date('jS M Y H:i a'); ?>
            </div>
        </div>
    </body>
    
</html>