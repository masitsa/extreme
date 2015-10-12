<?php
//invoice details
$row = $invoice->row();
$invoice_date = $row->created;
$created_by = $row->created_by;
$personnel_id = $row->personnel_id;
$doctor_invoice_number = $row->doctor_invoice_number;
$doctor_invoice_description = $row->doctor_invoice_description;

//$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
$invoice_date = date('jS F Y',strtotime($invoice_date));

$doctor = $this->accounts_model->get_personnel($personnel_id);
$printed_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | Doctor Invoice</title>
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
            	<strong><?php echo $doctor_invoice_description;?></strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-4 pull-left">
            	<div class="row">
                	<div class="col-md-12">
                    	
                    	<div class="title-item">Personnel:</div>
                        
                    	Dr. <?php echo $doctor; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Number:</div>
                    	<?php echo $doctor_invoice_number; ?>
                    </div>
                </div>
            
            </div>
            
        	<div class="col-md-4 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Date:</div>
                        
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
            	<table class="table table-hover table-bordered table-striped col-md-12">
                	<thead>
                    	<tr>
                        	<th>#</th>
                            <th>Item</th>
                            <th>Units</th>
                            <th>Unit Cost</th>
                            <th>Total</th>
                         </tr>
                    </thead>
                     
                    <tbody>
					<?php
                    $total = 0;
                    if($invoice_items->num_rows() > 0)
					{
						$s=0;
						
						foreach ($invoice_items->result() as $key_items)
						{
							$item = $key_items->doctor_invoice_item_description;
							$unit_cost = $key_items->doctor_invoice_item_cost;
							$units = $key_items->doctor_invoice_item_quantity;
							$total_cost = $units * $unit_cost;
							$s++;
							?>
							<tr>
								<td><?php echo $s;?></td>
								<td><?php echo $item;?></td>
								<td><?php echo $units;?></td>
								<td><?php echo number_format($unit_cost,2);?></td>
								<td><?php echo number_format($total_cost,2);?></td>
							</tr>
							<?php
							$total += $total_cost;
						}
					}
                    
                    ?>
                    <tr>
                        <td colspan="4" align="right"><strong>Total:</strong></td>
                        <td><strong> <?php echo number_format($total,2);?></strong></td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-10 pull-left">
            	<div class="col-md-3 pull-left">
                   Prepared by: <?php echo $printed_by;?> 
                </div>
                <div class="col-md-3 pull-left">
                  Signature : ................................
                </div>
                <div class="col-md-3 pull-left">
                  Confirmed by: .....................................
                </div>
                <div class="col-md-3 pull-left">
                  Approved by: .....................................
                </div>
          	</div>
        	<div class="col-md-2 pull-right">
            	<?php echo date('jS M Y H:i a'); ?>
            </div>
        </div>
    </body>
    
</html>