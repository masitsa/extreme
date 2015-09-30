<?php

// COMPANY DETAILS
$data['contacts'] = $this->site_model->get_contacts();

$supplier_order_details = $this->orders_model->get_supplier_order_details($supplier_order_id);

if($supplier_order_details->num_rows() > 0)
{
	foreach ($supplier_order_details->result() as $key_supplier) {
		# code...

		$order_number = $key_supplier->order_number;
		$supplier_name = $key_supplier->supplier_name;
		$supplier_contact_person = $key_supplier->supplier_contact_person;
		$supplier_phone = $key_supplier->supplier_phone;
		$supplier_email = $key_supplier->supplier_email;
		$supplier_status = $key_supplier->supplier_status;
		$supplier_order_id = $key_supplier->supplier_order_id;

		$total_qoute_amount = $key_supplier->total_qoute_amount;
		$qoutation_attachment = $key_supplier->qoutation_attachment;
		$request_form_attachment = $key_supplier->request_form_attachment;
		$lpo_form_attachment = $key_supplier->lpo_form_attachment;

		$supplier_physical_address = $key_supplier->supplier_physical_address;


		$order_id = $key_supplier->order_id;
	}
}

// order details
$order_details = $this->orders_model->get_order_items($order_id);
// end of order details

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | LPO</title>
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
            	<strong>LOCAL PURCHASE ORDER</strong>
            </div>
        </div>
      
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="row">
	        	<div class="col-md-7 pull-left">
	            	<div class="row">
	                	<div class="col-md-10">
	                		<div class="col-md-6">
		                		<strong>Supplier :</strong> <?php echo $supplier_name;?><br>
		                		<strong>Physical Address :</strong>  <?php echo $supplier_physical_address;?><br>
		                		<strong>Phone :</strong> <?php echo $supplier_phone;?><br>
		                		<strong>Email :</strong>  <?php echo $supplier_email;?><br>
		                	</div>
	                    </div>
	                </div>
	            
	            </div>
	            
	        	<div class="col-md-5 pull-right">
	            	<div class="row">
	                	<div class="col-md-10">
	                		<div class="col-md-6">
	                		<strong>Order No: </strong>
	                    	 <?php echo $order_number;?><br>
	                    	 <strong>Date :</strong> <?php echo date('jS F Y',strtotime(date("Y-m-d"))); ?><br>
	                    	 These particulars must be quoted on all invoices and statements.

	                    	 </div>
	                    </div>
	                </div>
	            	
	            </div>
	        </div>
            
        </div>
        
    	<div class="row receipt_bottom_border">
        	<div class="col-md-12">
            	Please supply the following goods/services to Oasis Specialist Hospital on or before <strong> <?php echo date('jS F Y',strtotime(date("Y-m-d"))); ?> </strong>  and submit the invoice without delay
            </div>
        </div>
        
    	<div class="row">
        	<div class="col-md-12">
            				<table class="table table-hover table-bordered table-striped col-md-12">
                                <thead>
	                                <tr>
	                                  <th>ITEM No.</th>
	                                  <th>DESCRIPTION</th>
	                                  <th>QTY</th>
	                                  <th>UNIT PRICE</th>
	                                  <th colspan="2">AMOUNT</th>
	                                </tr>
	                                 <tr>
	                                  <th></th>
	                                  <th></th>
	                                  <th></th>
	                                  <th></th>
	                                  <th>SHS</th>
	                                  <th>CTS</th>
	                                </tr>
                                </thead>
                                <tbody>
                                	<?php
                                	$item_no = 0;
                                	if($order_details->num_rows() > 0)
                                	{
                                		$total_amount = 0;
                                		foreach ($order_details->result() as $key) {
                                			# code...
                                			$order_id = $key->order_id;
											$product_name = $key->product_name;
											$order_item_quantity = $key->order_item_quantity;
											$order_item_id = $key->order_item_id;
											$supplier_unit_price = $key->supplier_unit_price;

											$total_cost = $supplier_unit_price * $order_item_quantity;
											$item_no++;
											?>
											<tr>
		                                        <td><?php echo $item_no;?></td>
		                                        <td><?php echo $product_name;?></td>
		                                        <td><?php echo $order_item_quantity;?></td>
		                                        <td><?php echo $supplier_unit_price;?></td>
		                                        <td><?php echo $total_cost;?></td>
		                                        <td>00</td>
											</tr>
											<?php
											$total_amount = $total_amount + $total_cost;
                                		}
                                	}
                                	?>
									 
                                      <tr>
                                      	<td colspan="3"></td>
                                        <td><strong>Total Amount :</strong></td>
                                        <td><strong> <?php echo $total_amount;?></strong></td>
                                        <td>00</td>
                                      </tr>
                                      <?php

                                  ?>
                                    
                                </tbody>
                              </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
	            <div class="col-md-6 pull-left">
	            	Prepared by: Lucy
	            </div>
	            <div class="col-md-6 pull-left">
	              Signature by: .....................................
	            </div>
	          </div>
        	<div class="col-md-4 pull-right">
            	<?php echo date('jS M Y H:i a'); ?> Thank you
            </div>
        </div>
        <?php
        // get office who officiated the transaction

        $authorising_officer =  $this->orders_model->get_lpo_authorising_personnel($order_id);
        ?>
        <div class="row" style="font-style:bold; font-size:11px; margin-top:10px">
        	<div class="col-md-8 pull-left">
	            <div class="col-md-6 pull-left"> 
	            	<strong>Authorised By :</strong> 
                    <?php echo $authorising_officer;?>
	            	<?php echo $contacts['company_name'];?>

	            </div>
	           
	        </div>
        </div>
        <br>
         <div class="row" >
        	<div class="col-md-12">
        		<strong> *Merchants are warned not to supply goods against any order than this form fully completed</strong>
        	</div>
        </div>
    </body>
    
</html>