<?php

//patient details
$visit_type = $patient['visit_type'];
$patient_type = $patient['patient_type'];
$patient_othernames = $patient['patient_othernames'];
$patient_surname = $patient['patient_surname'];
$patient_surname = $patient['patient_surname'];
$patient_number = $patient['patient_number'];
$gender = $patient['gender'];

$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
$visit_date = date('jS F Y',strtotime($this->accounts_model->get_visit_date($visit_id)));

//doctor
$doctor = $this->accounts_model->get_att_doctor($visit_id);

//served by
$served_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));
$credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
$debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>SUMC | Receipt</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>assets/bluish/style/bootstrap.css" rel="stylesheet" media="all">
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
    </style>
    </head>
    <body class="receipt_spacing">
    	<div class="row" style=" min-height: 50px;">
        	<img src="<?php echo base_url();?>images/strathmore.gif" class="title-img"/>
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	Strathmore University Medical Center<br/>
                    P.O. Box 59857 00200, Nairobi, Kenya<br/>
                    E-mail: sumedicalcentre@strathmore.edu. Tel : +254703034011<br/>
                    Madaraka Estate<br/>

                </strong>
            </div>
        </div>
        
    	<div class="row" >
        	<div class="col-md-12 center-align">
            	<strong>RECEIPT</strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-6 pull-left">
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
            
        	<div class="col-md-6 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Receipt Number:</div>
                        
                    	<?php echo 'REC/00'.$visit_id; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Att. Doctor::</div> 
                        
                    	<?php echo $doctor; ?>
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
            	<table class="table table-hover table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Charge</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
					$service_rs = $this->accounts_model->get_patient_visit_charge($visit_id);
					$item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
					$total = 0;
					$s=0;
					
					if(count($service_rs) > 0)
					{
						foreach($service_rs as $serv)
						{
							$service_id = $serv->service_id;
							$service_name = $serv->service_name;
							$visit_total = 0;
							$s++;
							
							if(count($item_invoiced_rs) > 0){
							  
							  foreach ($item_invoiced_rs as $key_items):
								$service_id2 = $key_items->service_id;
								
								if($service_id2 == $service_id)
								{
									$visit_charge_amount = $key_items->visit_charge_amount;
									$units = $key_items->visit_charge_units;
			
									$visit_total += $visit_charge_amount * $units;
								}
							  endforeach;
							}
		
							?>
							  <tr >
								<td><?php echo $s;?></td>
								<td><?php echo $service_name;?></td>
								<td><?php echo number_format($visit_total,2);?></td>
							  </tr>


							<?php
                            // enterring the payment stuff
                                      $payments_rs = $this->accounts_model->payments($visit_id);
                                      $total_payments = 0;
                                      $total_amount = ($total + $debit_note_amount) - $credit_note_amount;
                                      if(count($payments_rs) > 0){
                                        $x = $s;
                                        foreach ($payments_rs as $key_items):
                                          $x++;
                                          $payment_method = $key_items->payment_method;
                                          $amount_paid = $key_items->amount_paid;
                                          $time = $key_items->time;
                                          $payment_type = $key_items->payment_type;
                                          $amount_paidd = number_format($amount_paid,2);
                                          $payment_service_id = $key_items->payment_service_id;
                                          
                                          if($payment_service_id > 0)
                                          {
                                            $service_associate = $this->accounts_model->get_service_detail($payment_service_id);
                                          }
                                          else
                                          {
                                            $service_associate = " ";
                                          }

                                          if($payment_type == 2)
                                          {
                                              $amount_paidd = $amount_paidd;
                                          
                                              ?>
                                              <tr>
                                                <td><?php echo $x;?></td>
                                                <td><?php echo $service_associate;?></td>
                                              
                                                <td><?php echo $amount_paidd;?></td>
                                              </tr>
                                              <?php
                                          }
                                          else if($payment_type == 3)
                                          {
                                               
                                          }
                                          

                                        endforeach;
                                          
                                        }
                                        // end of the payments

							$total = $total + $visit_total;
						}
                         $total_amount = ($total + $debit_note_amount) - $credit_note_amount;
						?>
						<tr>
						  <td></td>
						  <td>Total :</td>
						  <td> <?php echo number_format($total_amount,2);?></td>
						</tr>
						<?php
					}
					else{
					   ?>
						<tr>
						  <td colspan="3"> No Charges</td>
						</tr>
						<?php
					}
					$total_amount = ($total + $debit_note_amount) - $credit_note_amount;
					$payments_rs = $this->accounts_model->payments($visit_id);
					$total_payments = 0;
					
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
                    <tr class="receipt_bottom_border">
                    	<td colspan="2">Total Paid</td>
                        <td><?php echo number_format($total_payments, 2);?></td>
                    </tr>
                    <tr class="receipt_bottom_border">
                    	<td colspan="2">Balance</td>
                        <td><?php echo number_format($total_amount - $total_payments, 2);?></td>
                    </tr>
                      
                  </tbody>
                </table>
            </div>
        </div>
        
    	<div class="" style="font-style:italic; font-size:10px;">
        	<div style="float:left; margin:0 10px 0 10px;">
            	Served by: <?php echo $served_by; ?>
            </div>
        	<div style="float:right; margin:0 10px 0 10px;">
            	<?php echo $today; ?> Thank you
            </div>
        </div>
    </body>
    
</html>