<?php

//patient details
$visit_type = $patient['visit_type'];
$patient_type = $patient['patient_type'];
$patient_othernames = $patient['patient_othernames'];
$patient_surname = $patient['patient_surname'];
$patient_surname = $patient['patient_surname'];
$patient_number = $patient['patient_number'];
$gender = $patient['gender'];
$patient_insurance_number = $patient['patient_insurance_number'];
$inpatient = $patient['inpatient'];
$visit_type_name = $patient['visit_type_name'];

$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
$visit_date = date('jS F Y',strtotime($this->accounts_model->get_visit_date($visit_id)));

//doctor
$doctor = $this->accounts_model->get_att_doctor($visit_id);

//served by
$served_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));

//services details
$item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
$credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
$debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);

//payments
$payments_rs = $this->accounts_model->payments($visit_id);
$total_payments = 0;
$s = 0;
$total_amount = 0;

//at times credit & debit notes may not be assigned
//to a particular service but still need to be displayed
/*
$display_notes = array();

if($all_notes->num_rows() > 0)
{
	foreach($all_notes->result() as $row)
	{
		$payment_service_name = $row->service_name;
		$payment_service_id = $row->payment_service_id;
		$amount_paid = $row->amount_paid;
		$payment_type = $row->payment_type;
		$found = 0;
		
		//check if service exist in query from service charge
		if(count($item_invoiced_rs) > 0)
		{
			foreach ($item_invoiced_rs as $key_items):
				$service_id = $key_items->service_id;
				
				if($service_id == $payment_service_id)
				{
					$found = $service_id;
					break;
				}
				
			endforeach;
		}
			
		//if item was not found
		if($found == 0)
		{
			$data['payment_service_name'] = $payment_service_name;
			$data['payment_service_id'] = $payment_service_id;
			$data['amount_paid'] = $amount_paid;
			$data['payment_type'] = $payment_type;
			
			array_push($display_notes, $data);
		}
	}
}
$total_notes = count($display_notes);*/

$services_billed = array();
$all_notes = $this->accounts_model->get_all_notes($visit_id);
if($all_notes->num_rows() > 0)
{
	foreach($all_notes->result() as $row)
	{
		$payment_service_name = $row->service_name;
		$payment_service_id = $row->payment_service_id;
		$in_array = 0;
		
		$total_services = count($services_billed);
		if($total_services > 0)
		{
			for($t = 0; $t < $total_services; $t++)
			{
				$saved_service_id = $services_billed[$t]['payment_service_id'];
				
				if($saved_service_id == $payment_service_id)
				{
					$in_array = 1;
					break;
				}
			}
		}
		
		if($in_array == 0)
		{
			$data['payment_service_name'] = $payment_service_name;
			$data['payment_service_id'] = $payment_service_id;
			
			array_push($services_billed, $data);
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | Invoice</title>
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
            	<strong>INVOICE</strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-4 pull-left">
            	<div class="row">
                	<div class="col-md-12">
                    	
                    	<div class="title-item">Patient Name:</div>
                        
                    	<?php echo $patient_surname.' '.$patient_othernames; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Patient Number:</div> 
                        
                    	<?php echo $patient_number; ?>
                    </div>
                </div>
            
            </div>
            
        	<div class="col-md-4">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Number:</div>
                    	<?php echo $this->session->userdata('branch_code').'-INV-00'.$visit_id; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Att. Doctor:</div> 
                        
                    	<?php echo $doctor; ?>
                    </div>
                </div>
            </div>
            
        	<div class="col-md-4 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Date:</div>
                        
                    	<?php echo $visit_date; ?>
                    </div>
                </div>
                <?php if($visit_type != 1){?>
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Insurance:</div>
                        
                    	<?php echo $visit_type_name; ?> - <?php echo $patient_insurance_number; ?>
                    </div>
                </div>
                <?php }?>
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
                                  <th>Service</th>
                                  <th>Item Name</th>
                                  <th>Units</th>
                                  <th>Unit Cost</th>
                                  <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
									<?php
                                    $total = 0;
                                    if(count($item_invoiced_rs) > 0){
										$s=0;
										$total_nhif_days = 0;
										
										foreach ($item_invoiced_rs as $key_items):
											$service_charge_id = $key_items->service_charge_id;
											$service_charge_name = $key_items->service_charge_name;
											$visit_charge_amount = $key_items->visit_charge_amount;
											$service_name = $key_items->service_name;
											$units = $key_items->visit_charge_units;
											$service_id = $key_items->service_id;
											$personnel_id = $key_items->personnel_id;
											$doctor = '';
											
											if($service_name == 'Bed charge')
											{
												$total_nhif_days = $units;
											}
											
											if($personnel_id > 0)
											{
												$doctor_rs = $this->reception_model->get_personnel($personnel_id);
												if($doctor_rs->num_rows() > 0)
												{
													$key_personnel = $doctor_rs->row();
													$first_name = $key_personnel->personnel_fname;
													$personnel_onames = $key_personnel->personnel_onames;
													$doctor = ' : Dr. '.$personnel_onames.' '.$first_name;
												}
											}
											//var_dump($service_id);
											//if lab check to see if drug is in pres
											if($service_id == 4)
											{
												if($this->accounts_model->in_pres($service_charge_id, $visit_id))
												{
													$visit_total = $visit_charge_amount * $units;
													$s++;
													
													?>
													<tr>
														<td><?php echo $s;?></td>
														<td><?php echo $service_name;?></td>
														<td><?php echo $service_charge_name.$doctor;?></td>
														<td><?php echo $units;?></td>
														<td><?php echo number_format($visit_charge_amount,2);?></td>
														<td><?php echo number_format($visit_total,2);?></td>
													</tr>
													<?php
													$total = $total + $visit_total;
												}
											}
											
											else
											{
												//$debit_note_pesa = $this->accounts_model->total_debit_note_per_service($service_id,$visit_id);
												
												//$credit_note_pesa = $this->accounts_model->total_credit_note_per_service($service_id,$visit_id);
												
												$visit_total = $visit_charge_amount * $units;
												$s++;
												
												//$visit_total = ($visit_total + $debit_note_pesa) - $credit_note_pesa;
												?>
												<tr>
													<td><?php echo $s;?></td>
													<td><?php echo $service_name;?></td>
													<td><?php echo $service_charge_name.$doctor;?></td>
                                                    <td><?php echo $units;?></td>
                                                    <td><?php echo number_format($visit_charge_amount,2);?></td>
													<td><?php echo number_format($visit_total,2);?></td>
												</tr>
												<?php
												$total = $total + $visit_total;
											}
										endforeach;
										
										//less NHIF Rebate
										if(($inpatient == 1) && (!empty($patient_insurance_number)))
										{
											$rebate = 1600;
											$total_rebate = $rebate * $total_nhif_days;
											$s++;
											?>
                                            <tr>
                                                <td><?php echo $s;?></td>
                                                <td colspan="2">Less NHIF Rebate</td>
                                                <td><?php echo $total_nhif_days;?></td>
                                                <td><?php echo number_format($rebate,2);?></td>
                                                <td>(<?php echo number_format($total_rebate,2);?>)</td>
                                            </tr>
                                            <?php
											$total = $total - $total_rebate;
										}
										$total_amount = $total ;
										
										// $total_amount = ($total + $debit_note_amount) - $credit_note_amount;
                                    }
									
									$total_services = count($services_billed);
									if($total_services > 0)
									{
										for($t = 0; $t < $total_services; $t++)
										{
											$s++;
											$debit_note_pesa  = 0;
											$credit_note_pesa = 0;
											
											$payment_service_name = $services_billed[$t]['payment_service_name'];
											$payment_service_id = $services_billed[$t]['payment_service_id'];
											
											$debit_note_pesa = $this->accounts_model->total_debit_note_per_service($payment_service_id, $visit_id);
											
											$credit_note_pesa = $this->accounts_model->total_credit_note_per_service($payment_service_id, $visit_id);
											//get service name
											$service_name = $payment_service_name;
											if($debit_note_pesa > 0)
											{
												?>
												<tr>
													<td><?php echo $s;?></td>
													<td><?php echo $service_name;?></td>
													<td>Debit notes</td>
													<td>1</td>
													<td><?php echo number_format($debit_note_pesa,2);?></td>
													<td><?php echo number_format($debit_note_pesa,2);?></td>
												</tr>
												<?php
											}
											
											if($credit_note_pesa > 0)
											{
												?>
												<tr>
													<td><?php echo $s;?></td>
													<td><?php echo $service_name;?></td>
													<td>Credit notes</td>
													<td>1</td>
													<td>(<?php echo number_format($credit_note_pesa,2);?>)</td>
													<td>(<?php echo number_format($credit_note_pesa,2);?>)</td>
												</tr>
												<?php
											}
											$total_amount = ($total_amount + $debit_note_pesa) - $credit_note_pesa;
										}
									}
								  	
									//display solo debit and credit notes
									/*if($total_notes > 0)
									{
										for($r = 0; $r < $total_notes; $r++)
										{
											$payment_service_name = $display_notes[$r]['payment_service_name'];
											$payment_service_id = $display_notes[$r]['payment_service_id'];
											$amount_paid = $display_notes[$r]['amount_paid'];
											$payment_type = $display_notes[$r]['payment_type'];
											$payment_units = 1;
											
											//credit notes are negative
											if($payment_type == 3)
											{
												$amount_paid *= -1;
											}
											
											$visit_total = $amount_paid * $payment_units;
											?>
											<tr>
                                                <td><?php echo ($r+1);?></td>
                                                <td><?php echo $payment_service_name;?></td>
                                                <td></td>
                                                <td><?php echo number_format($visit_total,2);?></td>
											</tr>
											<?php
											$total = $total + $visit_total;
										}
										
										$total_amount = $total;
									}*/
								  
								  	if(count($payments_rs) > 0)
									{
										$x=0;
										
										foreach ($payments_rs as $key_items):
											$x++;
											$payment_type = $key_items->payment_type;
											$payment_status = $key_items->payment_status;
											
											if($payment_type == 1 && $payment_status == 1)
											{
												$payment_method = $key_items->payment_method;
												$amount_paid = $key_items->amount_paid;
												
												$total_payments = $total_payments + $amount_paid;
											}
										endforeach;
									}
								  
                                      ?>
                                      <tr>
                                        <td colspan="5" align="right"><strong>Total Payments:</strong></td>
                                        <td><strong> <?php echo number_format($total_payments,2);?></strong></td>
                                      </tr>
                                      <tr>
                                        <td colspan="5" align="right"><strong>Total Invoice:</strong></td>
                                        <td><strong> <?php echo number_format($total_amount - $total_payments,2);?></strong></td>
                                      </tr>
                                      <?php

                                  ?>
                                    
                                </tbody>
                              </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-10 pull-left">
            	<?php if($inpatient == 0){?>
                <div class="col-md-4 pull-left">
                    Prepared by: <?php echo $served_by;?> 
                </div>
                <div class="col-md-4 pull-left">
                  Signature by: .....................................
                </div>
                <div class="col-md-4 pull-left">
                  Patient Signature : ................................
                </div>
                
                <?php } else{?>
                <div class="col-md-3 pull-left">
                   Prepared by: <?php echo $served_by;?> 
                </div>
                <div class="col-md-3 pull-left">
                  Confirmed by: .....................................
                </div>
                <div class="col-md-3 pull-left">
                  Approved by: .....................................
                </div>
                <div class="col-md-3 pull-left">
                  Patient Signature : ................................
                </div>
                <?php } ?>
          	</div>
        	<div class="col-md-2 pull-right">
            	<?php echo date('jS M Y H:i a'); ?> Thank you
            </div>
        </div>
    </body>
    
</html>