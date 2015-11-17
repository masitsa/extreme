<?php

$row = $query->row();
$invoice_date = date('jS M Y H:i a',strtotime($row->debtor_invoice_created));
$debtor_invoice_id = $row->debtor_invoice_id;
$visit_type_name = $row->visit_type_name;
//$patient_insurance_number = $row->patient_insurance_number;
$batch_no = $row->batch_no;
$status = $row->debtor_invoice_status;
$personnel_id = $row->debtor_invoice_created_by;
$date_from = date('jS M Y',strtotime($row->date_from));
$date_to = date('jS M Y',strtotime($row->date_to));
$total_invoiced = number_format($this->reports_model->calculate_debt_total($debtor_invoice_id, $where, $table), 2);
				
//get status
if($status == 0)
{
	$status = '<span class="label label-danger">Unpaid</span>';
}

else
{
	$status = '<span class="label label-success">Paid</span>';
}

//creators and editors
if($personnel_query->num_rows() > 0)
{
	$personnel_result = $personnel_query->result();
	
	foreach($personnel_result as $adm)
	{
		$personnel_id2 = $adm->personnel_id;
		
		if($personnel_id == $personnel_id2)
		{
			$created_by = $adm->personnel_onames.' '.$adm->personnel_fname;
			break;
		}
		
		else
		{
			$created_by = '-';
		}
	}
}

else
{
	$created_by = '-';
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
        <title>Debtors | Invoice</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>assets/bluish/style/bootstrap.css" rel="stylesheet" media="all">
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
            	<strong>INVOICE</strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px; padding:0 10px;">
        	<div class="col-md-4">
            	<strong>Invoice to:</strong>
                <span class="pull-right"><?php echo $visit_type_name; ?></span>
            </div>
            
        	<div class="col-md-4">
            	<strong>Invoice Number:</strong>
                <span class="pull-right"><?php echo $batch_no; ?></span>
            </div>
            
        	<div class="col-md-4">
            	<strong>Invoice date:</strong>
                <span class="pull-right"><?php echo $invoice_date; ?></span>
            </div>
        </div>
        
    	<div class="row receipt_bottom_border">
        	<div class="col-md-12 center-align">
            	<strong>Invoice for services rendered between <?php echo $date_from;?> and <?php echo $date_to;?> as per the attached invoices</strong>
            </div>
        </div>
        
    	<div class="row">
        	<div class="col-md-12"> 
            	<table class="table table-hover table-bordered col-md-12">
                	<thead>
                    	<tr>
                            <th>#</th>
                            <th>Invoice Date</th>
                            <th>Member Number</th>
                            <th>Patient</th>
                            <th>Invoice Number</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    
                    <tbody> 
                    	<?php
						$total_amount = 0;
                        if($debtor_invoice_items->num_rows() > 0)
						{
							$count = 0;
							foreach($debtor_invoice_items->result() as $res)
							{
								$count++;
								$invoice_amount = $res->invoice_amount;
								$patient_surname = $res->patient_surname;
								$patient_othernames = $res->patient_othernames;
								$patient_number = $res->patient_number;
								$patient_insurance_number = $res->patient_insurance_number;
								$current_patient_number = $res->current_patient_number;
								$visit_id = $res->visit_id;
								$visit_date = date('jS F Y',strtotime($res->visit_date));
								$debtor_invoice_item_status = $res->debtor_invoice_item_status;
								
								//display only active items
								if($debtor_invoice_item_status == 0)
								{
									$total_amount += $invoice_amount;
									?>
									<tr>
										<td><?php echo $count;?></td>
										<td><?php echo $visit_date;?></td>
										<td><?php echo $patient_insurance_number;?></td>
										<td><?php echo $patient_surname;?> <?php echo $patient_othernames;?></td>
										<td><?php echo $this->session->userdata('branch_code').'-INV-00'.$visit_id; ?></td>
										<td><?php echo number_format($invoice_amount, 2);?></td>
									</tr>
									<?php
								}
							}
						}
						?>
                        <tr>
                            <th colspan="5" align="right">Total</th>
                            <th><?php echo number_format($total_amount, 2);?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
                <div class="col-md-4 pull-left">
                    Raised by: <?php echo $created_by;?> 
                </div>
                <div class="col-md-8pull-left">
                  Signature by: .....................................................................
                </div>
            
          	</div>
            
        	<div class="col-md-4 pull-right">
            	<?php echo date('jS M Y H:i a'); ?> Thank you
            </div>
        </div>
    </body>
    
</html>