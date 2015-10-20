<?php

$row = $query->row();
$invoice_date = date('jS M Y H:i a',strtotime($row->debtor_invoice_created));
$debtor_invoice_id = $row->debtor_invoice_id;
$visit_type_name = $row->visit_type_name;
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
        <title>SUMC | Invoice</title>
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
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-6 pull-left">
            	<div class="row">
                	<div class="col-md-12">
                    	
                    	<div class="title-item">Invoice to:</div>
                        
                    	<?php echo $visit_type_name; ?>
                    </div>
                </div>
            	
            	<!--<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Patient Number:</div> 
                        
                    	<?php //echo $patient_number; ?>
                    </div>
                </div>-->
            
            </div>
            
        	<div class="col-md-6 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Number:</div>
                        
                    	<?php echo $batch_no; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice date:</div> 
                        
                    	<?php echo $invoice_date; ?>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="row receipt_bottom_border">
        	<div class="col-md-12 center-align">
            	<strong>BILLED ITEMS</strong>
            </div>
        </div>
        
    	<div class="row">
        	<div class="col-md-12">
            				<table class="table table-hover table-bordered col-md-12">
                                <thead>
                                <tr>
                                  <th>Description</th>
                                  <th>Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                  
                                <tr>
                                  <td>Invoice for services rendered between <?php echo $date_from;?> and <?php echo $date_to;?> as per the attached invoices</td>
                                  <td><?php echo $total_invoiced;?></td>
                                </tr>
                                  
                                <tr>
                                  <th align="center">Total</td>
                                  <th align="center"><?php echo $total_invoiced;?></th>
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