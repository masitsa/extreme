<?php

// COMPANY DETAILS
$data['contacts'] = $this->site_model->get_contacts();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php
echo $contacts['company_name'];
?> | LPO</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php
echo base_url() . "assets/themes/porto-admin/1.4.1/";
?>assets/vendor/bootstrap/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" href="<?php
echo base_url() . "assets/themes/porto-admin/1.4.1/";
?>assets/stylesheets/theme-custom.css" media="all"/>
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
            	<img src="<?php
echo base_url() . 'assets/logo/' . $contacts['logo'];
?>" alt="<?php
echo $contacts['company_name'];
?>" class="img-responsive logo"/>
            </div>
        </div>
    	<div class="row">
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	<?php
echo $contacts['company_name'];
?><br/>
                    P.O. Box <?php
echo $contacts['address'];
?> <?php
echo $contacts['post_code'];
?>, <?php
echo $contacts['city'];
?><br/>
                    E-mail: <?php
echo $contacts['email'];
?>. Tel : <?php
echo $contacts['phone'];
?><br/>
                    <?php
echo $contacts['location'];
?>, <?php
echo $contacts['building'];
?>, <?php
echo $contacts['floor'];
?><br/>
                </strong>
            </div>
        </div>
        
      <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<strong>QUOTATION</strong>
            </div>
        </div>
      
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="row">
	             <div class="row" style="margin-left:20px;margin-right:20px;">
	                   <div class="col-md-6 pull-left" >
	                		<strong>Request No: </strong>
                            <?php echo $request_number;?>
                       </div>
                       <div class="col-md-6 pull-right">
	                		<strong>Requested On: </strong>
                            <?php $request_date = $this->requests_model->get_request_date($supplier_request_id);?>
                            <?php echo $request_date;?>
                      </div>
                 </div> 
                            
                 <div class="row" style="margin-left:20px;margin-right:20px;">
                      <div class="col-md-6 pull-right">      
	                    	 <strong>Date :</strong> <?php echo date('jS F Y', strtotime(date("Y-m-d")));
?>
						</div>         
                 </div>
                           
                             <br>
                             <div class="col-md-12 center-align">
	            				<div class="row">
	                    	 These particulars cannot be changed.
                             </div>

	                    	 </div>
	                    </div>
	                </div>
	            	
	            </div>
	        </div>
            
        </div>
        
    	<div class="row receipt_bottom_border center-align">
        	<div class="col-md-12 center-align">
            <?php
			$as_at = $this->requests_model->request_approved_on($supplier_request_id);
			?>
            This is a copy of the items requested as at <strong> <?php echo $as_at;?> </strong>
            </div>
        </div>
    	<div class="row">
        	<div class="col-md-12">
            <br/>
            				<table class="table table-hover table-bordered table-striped col-md-12">
                                <thead>
	                                <tr>
	                                  <th>ITEM No.</th>
                                      <th>CLIENT</th>
                                      <th>CATEGORY</th>
	                                  <th>DESCRIPTION</th>
	                                  <th>QTY</th>
	                                  <th>UNIT PRICE</th>
                                      <th>DAYS</th>
	                                  <th colspan="2">AMOUNT (KSHS)</th>
	                                </tr>
	                               
                                </thead>
                                 <tbody>
                                	<?php
$client_name  = $this->requests_model->get_client_name($supplier_request_id);
$item_no      = 0;
$total_amount = 0;
//retrieve request details
//$request_event_id = $this->events_model->get_request_event_id($supplier_request_id);

$result ='';
$request_event_details = $this->events_model->get_request_event($supplier_request_id);
if($request_event_details->num_rows()>0)
{
	foreach($request_event_details->result() as $events)
	{
		//var_dump($request_event_details);
		$event_name = $events->request_event_name;
		$request_event_id =$events->request_event_id;
		$event_venue = $events->request_event_venue;
		$start_date = $events->request_event_start_date;
		$end_date = $events->request_event_end_date;
		$budget = $events->request_event_budget;
		$request_item_query = $this->requests_model->get_request_items($request_event_id);
		$event_logistic_query = $this->events_model->get_event_logistics($request_event_id);
		foreach ($request_item_query->result() as $key) 
		{	
			# code...
			$request_id = $key->request_id;
			$item_name = $key->item_name;
			$days =$key->days;
			$request_item_quantity = $key->request_item_quantity;
			$item_category = $key->category_name;
			$request_item_id = $key->request_item_id;
			$supplier_unit_price = $key->supplier_unit_price;
			$item_hiring_price = $key->item_hiring_price_kshs;
			$request_item_price = $key->request_item_price;
			$total_cost = $request_item_price * $request_item_quantity * $days;
			$item_no++;
		?>
				<tr>
					<td><?php  echo $item_no;?></td>
					<td><?php  echo $client_name;?></td>
					<td><?php  echo $item_category;?></td>
					<td><?php  echo $item_name;?></td>
					<td><?php  echo $request_item_quantity;?></td>
					<td><?php  echo $request_item_price;?></td>
					<td><?php  echo $days; ?></td>
					<td><?php  echo $total_cost;?></td>
					
				</tr>
												<?php
		   		$total_amount = $total_amount + $total_cost;
		}
	}
?>
									 
                                      <tr>
                                      	<td colspan="6"></td>
                                        
                                        <td><strong>Total Amount :</strong></td>
                                        <td><strong> <?php
echo $total_amount;
?></strong></td>
                                        
                                      </tr>
                                      <?php
	
}
?>
?>
                                    
                                </tbody>
                              </table>
						 <?php
						 $request_event_id = $this->events_model->get_request_event_id($supplier_request_id);
                         $logistic_details = $this->events_model->get_event_logistics($request_event_id);
						 //var_dump($logistic_details);die();
                         ?>
                             <table class="table table-hover table-bordered table-striped col-md-12">
                                <thead>
	                                <tr>
	                                  <th>Logistic No.</th>
                                      <th>CLIENT</th>
	                                  <th>LOGISTIC NAME</th>
                                      <th>QTY</th>
	                                  <th>UNIT PRICE</th>
                                      <th>DAYS</th>
	                                  <th colspan="2">AMOUNT (KSHS)</th>
	                                </tr>
	                               
                                </thead>
                                 <tbody>
                                <?php
								$logistic_no      = 0;
								$total_logistic_amount = 0;
								
								if ($event_logistic_query->num_rows() > 0) 
								{
									foreach($event_logistic_query->result() as $event_logistics)
									{
										$logistic_id = $event_logistics->logistic_id;
										$logistic_name = $event_logistics->logistic_name;
										$days = $event_logistics->request_event_logistic_days;
										$event_logistic_quantity = $event_logistics->request_event_logistic_quantity;
										$event_logistic_price = $event_logistics->request_event_logistic_price;
										$request_event_id = $request_event_id;
										$logistic_amount = $event_logistic_price*$event_logistic_quantity *$days;
										$total_logistic_amount=$invoice_total+$total;
										$logistic_id++;
											?>
											<tr>
                                            <td><?php echo $logistic_no;?></td>
                                            <td><?php echo $client_name;?></td>
                                            <td><?php echo $logistic_name;?></td>
                                            <td><?php echo $logistic_qty;?></td>
                                            <td><?php echo $logistic_price;?></td>
                                            <td><?php echo $logistic_days;?></td>
                                            <td><?php echo $logistic_amount;?></td>
                                                                        
                                        </tr>
										<?php
										$total_logistic_amount += $logistic_amount;
									}
								} 
										?> 
                                        <tr>
                                            <td colspan="5"></td>
                                            <td><strong>Total Amount :</strong></td>
                                            <td><strong> <?php echo $total_logistic_amount;?></strong></td>
                                        </tr>
								                         
                                </tbody>
                              </table>
            </div>
        </div>
  			<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
	            <div class="col-md-6 pull-left">
                <?php
$personnel_name   = $this->requests_model->get_request_creator($supplier_request_id);
$approved_by_name = $this->requests_model->get_request_approver($supplier_request_id);

?>
	            Prepared by:<?php
echo $personnel_name;
?>
	            </div>
	            <div class="col-md-6 pull-left">
	              	Signature by: .....................................
	            </div>
	          </div>
              </div>
              <br/>
              <div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
	            <div class="col-md-6 pull-left">
                <?php
$approved_by_name = $this->requests_model->get_request_approver($supplier_request_id);

?>
	              Approved by:<?php
echo $approved_by_name;
?>
	            </div>
	            <div class="col-md-6 pull-left">
	              Signature by: .....................................
	            </div>
	          </div>
              
        	<div class="col-md-4 pull-right">
            	<?php
echo date('jS M Y H:i a');
?> Thank you
            </div>
        </div>
        <?php
// get office who officiated the transaction

$authorising_officer = $this->requests_model->get_lpo_authorising_personnel($supplier_request_id);
?>
        <div class="row" style="font-style:bold; font-size:11px; margin-top:10px">
        	<div class="col-md-8 pull-left">
	            <div class="col-md-6 pull-left"> 
	            	<strong>Authorised By :</strong> 
                    <?php
echo $authorising_officer;
?>
	            	<?php
echo $contacts['company_name'];
?>

	            </div>
	           
	        </div>
        </div>
        <br>
         <div class="row" >
        	<div class="col-md-12">
        		<strong> &nbsp;  &nbsp; Only the Items in this quote will be rented out to you</strong>
        	</div>
        </div>
    </body>
    
</html>