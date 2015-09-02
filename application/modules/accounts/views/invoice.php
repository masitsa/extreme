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
    	<div class="row" >
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
                    	<div class="title-item">Invoice Number:</div>
                        
                    	<?php echo 'INV/00'.$visit_id; ?>
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
            				<table class="table table-hover table-bordered col-md-12">
                                <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Service</th>
                                  <th>Item Name</th>
                                  <th>Charge</th>
                                </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
                                  $credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
                                  $debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);
                                  $total = 0;
                                  if(count($item_invoiced_rs) > 0){
                                    $s=0;
                                     $debit_note_pesa  = 0;
                                     $credit_note_pesa = 0;
                                    foreach ($item_invoiced_rs as $key_items):
                                      $s++;
                                      $service_charge_name = $key_items->service_charge_name;
                                      $visit_charge_amount = $key_items->visit_charge_amount;
                                      $service_name = $key_items->service_name;
                                      $units = $key_items->visit_charge_units;
                                      $service_id = $key_items->service_id;

                                      $debit_note_pesa = $this->accounts_model->total_debit_note_per_service($service_id,$visit_id);

                                      $credit_note_pesa = $this->accounts_model->total_credit_note_per_service($service_id,$visit_id);

                                      $visit_total = $visit_charge_amount * $units;

                                      $visit_total = ($visit_total + $debit_note_pesa) - $credit_note_pesa;
                                      ?>
                                        <tr>
                                          <td><?php echo $s;?></td>
                                          <td><?php echo $service_name;?></td>
                                          <td><?php echo $service_charge_name;?></td>
                                          <td><?php echo number_format($visit_total,2);?></td>
                                        </tr>
                                      <?php
                                        $total = $total + $visit_total;
                                    endforeach;
                                      $total_amount = $total ;
                                      // enterring the payment stuff
                                      $payments_rs = $this->accounts_model->payments($visit_id);
                                      
                                        // end of the payments

                                        // $total_amount = ($total + $debit_note_amount) - $credit_note_amount;
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
                                      <tr>
                                        <td colspan="3"><strong>Total Payments:</strong></td>
                                        <td><strong> <?php echo number_format($total_payments,2);?></strong></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3"><strong>Total Invoice:</strong></td>
                                        <td><strong> <?php echo number_format($total_amount - $total_payments,2);?></strong></td>
                                      </tr>
                                      <?php
                                  }else{
                                     ?>
                                      <tr>
                                        <td colspan="4"> No Charges</td>
                                      </tr>
                                      <?php
                                  }

                                  ?>
                                    
                                </tbody>
                              </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
            <div class="col-md-4 pull-left">
            	Served by: <?php echo $served_by;?> 
            </div>
            <div class="col-md-4 pull-left">
              Signature by: .....................................
            </div>
            <div class="col-md-4 pull-left">
              Patient Signature : ................................
            </div>
          </div>
        	<div class="col-md-4 pull-right">
            	<?php echo $visit_date; ?> Thank you
            </div>
        </div>
    </body>
    
</html>