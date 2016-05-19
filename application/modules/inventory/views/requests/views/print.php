<?php
$quote_amount = 0;
$request_details=$this->requests_model->get_request_details($supplier_request_id);
if($request_details->num_rows() > 0)
{
	foreach($request_details->result() as $results)
	{
		$request_id = $results->request_id;
		$request_number = $results->request_number;
		$request_date = $results->request_date;
		$created = $results->created;
		$request_instructions = $results->request_instructions;
		$request_status_name = $results->request_status_name;
		$client_name = $results->client_name;
		$personnel_fname = $results->personnel_fname;
		$personnel_onames = $results->personnel_onames;
		$client_contact = $results->client_contact_person;
	
	}
}
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
			.custom-table .table-content{padding:0 50px 0 0;}
		</style>
    </head>
    <body class="receipt_spacing">
    	<div class="col-md-12 receipt_bottom_border">
    		<table class="custom-table">
            	<tr>
                	<td class="table-content">
                     <img src="<?php echo base_url() . 'assets/logo/' . $contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="img-responsive logo"/>
                    </td>
                	<!--About company-->
                	<td class="table-content">
                        <?php 
							echo $contacts['company_name'];?><br/>
							P.O. Box <?php echo $contacts['address'];?> <?php echo $contacts['post_code'];?>, <?php echo $contacts['city'];?><br/>
							E-mail: <?php echo $contacts['email'];?><br/>
							Tel : <?php echo $contacts['phone'];?><br/>
							<?php echo $contacts['location'];?>, 
							<?php echo $contacts['building'];?>, <?php echo $contacts['floor'];?>

                    </td>
                	<!-- End About company-->
                	<!--Request Details-->
                    <td class="table-content">
                    	 <table>
                            <tr>
                                <td align="right">Quotation No:</td>
                                <td align="left"><?php echo $request_number;?></td>
                            </tr>
                            <tr>
                                <td align="right">Requested On <?php $request_date = $this->requests_model->get_request_date($supplier_request_id);?>:</td>
                                <td align="left"><?php echo date('jS F Y', strtotime($request_date));?></td>
                            </tr>
                            <tr>
                                <td align="right">Pin No: </td>
                                <td align="left"><?php echo $contacts['branch_pin'];?></td>
                            </tr>
                            <tr>
                                <td align="right">VAT No: </td>
                                <td align="left"><?php echo $contacts['branch_vat'];?></td>
                            </tr>
                        </table>
                    </td>
                    <!--End Request Details-->
                    <!--Client Details-->
                    <td class="table-content">
                    	<table>
                        	<tr>
                                <td align="right">Request Number:</td>
                                <td align="left"><?php echo $request_number;?></td>
                            </tr>
                            <tr>
                                <td align="right">Client Name :</td>
                                <td align="left"><?php echo $client_name;?></td>
                            </tr>
                            <tr>
                                <td align="right">Client Contact :</td>
                                <td align="left"><?php echo $client_contact;?></td>
                            </tr>
                            <tr>
                                <td align="right">Date :</td>
                                <td align="left"><?php echo date('jS F Y', strtotime(date("Y-m-d")));?></td>
                            </tr>
                        </table>
                    </td>
                    <!--End CLient Details-->
                </tr>
            </table>
			
	   <?php
		$request_event_details = $this->events_model->get_request_event($request_id);
		//var_dump($request_event_details); die();
		if($request_event_details->num_rows()>0)
		{
			$result = 
			'
					<table class="table table-striped table-hover table-condensed table-bordered">
					  <thead>
						<tr>
						   <th>Days</th>
						   <th>Quantity</th>
						   <th>Category</th>
						   <th>Description</th>
						   <th>Price Per Item (KES)</th>
						   <th>Total (KES)</th>
						</tr>
					  </thead>
					  <tbody>
					';
			foreach($request_event_details->result() as $events)
			{
				//var_dump($request_event_details);
				$event_name = $events->request_event_name;
				$request_event_id =$events->request_event_id;
				$event_venue = $events->request_event_venue;
				$start_date = $events->request_event_start_date;
				$end_date = $events->request_event_end_date;
				$budget = $events->request_event_budget;
				
				$event_logistic_query = $this->events_model->get_event_logistics($request_event_id);
			
				$request_item_query = $this->requests_model->get_request_items($request_event_id);
				//$result ='';
				if($request_item_query->num_rows() > 0)
				{
					$result .= 	'
					<tr>
						<td colspan="6">
							<div class="row">
								<div class="col-md-4">
									<strong>Event Name: </strong>
								</div>
								<div class="col-md-8">
									'.$event_name.'
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<strong>Date: </strong>
								</div>
								<div class="col-md-8">
									'.date('jS F Y', strtotime($start_date)).' to '.date('jS F Y', strtotime($end_date)).'
								</div>
							</div>
						
							<div class="row">
								<div class="col-md-4">
									<strong>Venue: </strong>
								</div>
								<div class="col-md-8">
									'.$event_venue.'
								</div>
							</div>
						</td>
					</tr>';
					$count = 0;
					$invoice_total_item_price = 0;
					$total= 0;
					foreach($request_item_query->result() as $res)
					{
						$request_id = $res->request_id;
						$item_name = $res->item_name;
						$category_name = $res->category_name;
						$days =$res->days;
						$request_item_quantity = $res->request_item_quantity;
						$request_item_id = $res->request_item_id;
						$supplier_unit_price = $res->supplier_unit_price;
						$item_hiring_price = $res->item_hiring_price_kshs;
						$request_item_price = $res->request_item_price;
						$total = $request_item_price*$request_item_quantity*$days;
						$invoice_total_item_price=$invoice_total_item_price + $total;
						$count++;
						$quote_amount += $total;	
						$result .= ' 
									<tr>
										<td align="right">'.$days.'</td>
										<td align="right">'.$request_item_quantity.'</td>
										<td>'.$category_name.'</td>
										<td>'.$item_name.'</td>
										<td align="right">'.number_format($request_item_price,2).'</td>
										<td align="right">'.number_format($total,2).'</td>
									</tr>
									';
						?>
						<?php
					}
					
					$result .= '
					<tr>
						<td colspan="4"></td>
						<th>SUB TOTAL AMOUNT</th>
						<th>'.number_format($invoice_total_item_price,2).'</th>
					</tr>';
				}
				
				if($event_logistic_query->num_rows() > 0)
				{
					$result .= '
					<tr>
						<td colspan="5">
							<div class="row">
								<div class="col-md-12">
									<strong>Logistics for '.$event_name.'</strong>
								</div>
							</div>
						</td>
					</tr>';
					
					$count = 0;
					$invoice_logistic_total = 0;
					$total= 0;
					foreach($event_logistic_query->result() as $event_logistics)
					{
						$logistic_id = $event_logistics->logistic_id;
						$logistic_name = $event_logistics->logistic_name;
						//$logistic_name = $this->events_model->get_event_logistic_name($logistic_id );
						$days =$event_logistics->request_event_logistic_days;
						$event_logistic_quantity = $event_logistics->request_event_logistic_quantity;
						$event_logistic_price = $event_logistics->request_event_logistic_price;
						$request_event_id = $request_event_id;
						$total = $event_logistic_price*$event_logistic_quantity *$days;
						$invoice_logistic_total = $invoice_logistic_total+$total;
						$count++;
						$quote_amount+=$total;
						$result .= '
						<tr>
							<td align="right">'.$days.'</td>
							<td align="right">'.$event_logistic_quantity.'</td>
							<td></td>
							<td>'.$logistic_name.'</td>
							<td align="right">'.number_format($event_logistic_price,2).'</td>
							<td align="right">'.number_format($total,2).'</td>
						</tr>
						';
					}
					$result .= '<tr>
									<td colspan="4"></td>
									<th>SUB TOTAL AMOUNT</th>
									<th>'.number_format($invoice_logistic_total,2).'</th>
									
								</tr>';	
				}
			}
			$result .= '<tr>
									<td colspan="4"></td>
									<th>TOTAL AMOUNT</th>
									<th>'.number_format($quote_amount,2).'</th>
									
						</tr>';	
			$vat = 0.16 * $quote_amount;
			$result .= '<tr>
				<td colspan="4"></td>
				<th>VAT 16%</th>
				<th>'.number_format($vat,2).'</th>
				
			</tr>';	
			$amount_payable = 0;
			$amount_payable = $vat + $quote_amount;
			$result .= '<tr>
				<td colspan="4"></td>
				<th>TOTAL</th>
				<th>'.number_format($amount_payable,2).'</th>
				
			</tr>';	

			$result .= '
					</tbody>
				</table>';
			echo $result;
		}
		?>
		<br/>
        <div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-6">
            	<div class="col-md-8 pull-left">
	            	<div class="col-md-6 pull-left">
                    	<?php
						$personnel_name   = $this->requests_model->get_request_creator($supplier_request_id);
						$approved_by_name = $this->requests_model->get_request_approver($supplier_request_id);
						?>
				<strong>Prepared by: </strong><?php echo $personnel_name;?>
                Signature:
					</div>
                </div>
            </div>
            <div class="col-md-6">
            	<div class="col-md-8 pull-left">
	            	<div class="col-md-6 pull-left">
                     <?php
					$approved_by_name = $this->requests_model->get_request_approver($supplier_request_id);?>
	              	<strong>Approved by: </strong>
					<?php echo $approved_by_name; ?>
                    Signature: 
	            	</div>
                </div>
            </div>
            <!--<div class="col-md-4">
            	<div class="col-md-8 pull-left">
	            	<div class="col-md-6 pull-left">
                    <?php
					// get office who officiated the transaction
					$authorising_officer = $this->requests_model->get_lpo_authorising_personnel($supplier_request_id);?>
                    <strong>Authorised By :</strong> 
                    <?php echo $authorising_officer;?>
	            	<?php //echo $contacts['company_name'];?>
                    </div>
                </div>
            </div>-->
        </div>
        <div class="col-md-12">
        	<div class="col-md-4 center-align">
            	<?php
echo date('jS M Y H:i a');
?> Thank you
            </div>
        </div>
        <br>
        <div class="row center-align" >
        	<div class="col-md-12">
        		<strong> &nbsp;  &nbsp;<p align="center"> Please note that payment is 50% in advance with LPO and the  balance on completion of the job. We shall require the venue for 1 day set up before the event.</p></strong>
        	</div>
        </div>
    </body>
    
</html>