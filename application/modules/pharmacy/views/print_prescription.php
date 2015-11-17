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

//get drug times
$times_rs = $this->pharmacy_model->get_drug_times();
$num_times = count($times_rs);
$time_list = "<select name = 'x' class='form-control'>";

	foreach ($times_rs as $key_items):

		$time = $key_items->drug_times_name;
		$drug_times_id = $key_items->drug_times_id;
		
		if($drug_times_id == set_value('x'))
		{
			$time_list = $time_list."<option value='".$drug_times_id."' selected='selected'>".$time."</option>";
		}
		
		else
		{
			$time_list = $time_list."<option value='".$drug_times_id."'>".$time."</option>";
		}
	endforeach;
$time_list = $time_list."</select>";

//get consumption
$rs_cons = $this->pharmacy_model->get_consumption();
$num_cons = count($rs_cons);
$cons_list = "<select name = 'consumption' class='form-control'>";
	foreach ($rs_cons as $key_cons):

	$con = $key_cons->drug_consumption_name;
	$drug_consumption_id = $key_cons->drug_consumption_id;
		
	if($drug_times_id == set_value('consumption'))
	{
		$cons_list = $cons_list."<option value='".$drug_consumption_id."' selected='selected'>".$con."</option>";
	}
	
	else
	{
		$cons_list = $cons_list."<option value='".$drug_consumption_id."'>".$con."</option>";
	}
	endforeach;
$cons_list = $cons_list."</select>";

//get durations
$duration_rs = $this->pharmacy_model->get_drug_duration();
$num_duration = count($duration_rs);
$duration_list = "<select name = 'duration' class='form-control'>";
	foreach ($duration_rs as $key_duration):
	$durations = $key_duration->drug_duration_name;
	$drug_duration_id = $key_duration->drug_duration_id;
		
	if($drug_times_id == set_value('duration'))
	{
		$duration_list = $duration_list."<option value='".$drug_duration_id."' selected='selected'>".$durations."</option>";
	}
	
	else
	{
		$duration_list = $duration_list."<option value='".$drug_duration_id."'>".$durations."</option>";
	}
	endforeach;
$duration_list = $duration_list."</select>";


$rs = $this->pharmacy_model->select_prescription($visit_id);

                   
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | Prescription</title>
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
            	<strong>PRESCRIPTION DETAILS</strong>
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
                    	<div class="title-item">Prescription Number:</div>
                    	<?php echo $this->session->userdata('branch_code').'-PRES-00'.$visit_id; ?>
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
                    	<div class="title-item">Date:</div>
                        
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
            	<strong>DRUGS PRESCRIBED</strong>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
            	<table class="table table-hover table-bordered table-striped col-md-12">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Drug Name</th>
                      <th>Prescription</th>
                      <th>Days</th>
                      <th>Units</th>
                      <th>Unit Cost</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $temp_visit_charge_amount =0;
                   	$total_selected_drugs = count($selected_drugs);
					$num_rows =count($rs);
			        $s=0;
			        if($num_rows > 0){
			        foreach ($rs as $key_rs):
			            //var_dump($key_rs->prescription_substitution);
			            $service_charge_id =$key_rs->product_id;
			            $checker_id = $key_rs->checker_id;
						if(empty($checker_id))
						{
							$checker_id = $service_charge_id;
						}
			            $frequncy = $key_rs->drug_times_name;
			            $id = $key_rs->prescription_id;
			            $date1 = $key_rs->prescription_startdate;
			            $date2 = $key_rs->prescription_finishdate;
			            $sub = $key_rs->prescription_substitution;
			            $duration = $key_rs->drug_duration_name;
			            $sub = $key_rs->prescription_substitution;
			            $duration = $key_rs->drug_duration_name;
			            $consumption = $key_rs->drug_consumption_name;
			            $quantity = $key_rs->prescription_quantity;
			            $medicine = $key_rs->product_name;
			            $charge = $key_rs->product_charge;
			            $visit_charge_id = $key_rs->visit_charge_id;
			            $number_of_days = $key_rs->number_of_days;
			            $units_given = $key_rs->units_given;


			            $rs2 = $this->pharmacy_model->get_drug($service_charge_id);
                        $doseunit = '';
                        foreach ($rs2 as $key_rs2 ):
	                        $drug_type_id = $key_rs2->drug_type_id;
	                        $admin_route_id = $key_rs2->drug_administration_route_id;
	                        $doseunit = $key_rs2->unit_of_measure;
	                        $drug_type_name = $key_rs2->drug_type_name;
                        endforeach;

                        if(!empty($admin_route_id)){
                            $rs3 = $this->pharmacy_model->get_admin_route2($admin_route_id);
                            $num_rows3 = count($rs3);
                            if($num_rows3 > 0){
                                foreach ($rs3 as $key_rs3):
                                    $admin_route = $key_rs3->drug_administration_route_name;
                                endforeach;
                            }
                        }
                        

			            $rsf = $this->pharmacy_model->select_invoice_drugs($visit_id,$service_charge_id);
                        $num_rowsf = count($rsf);
						$sum_units = 0;
                        $visit_charge_amount = 0;
                        
						foreach ($rsf as $key_price):
                            $sum_units = $key_price->visit_charge_units;
                            $visit_charge_amount = $key_price->visit_charge_amount;
                        endforeach;
						
						//display selected drugs
						if(is_array($selected_drugs) && ($total_selected_drugs > 0))
						{
							$selected = 0;
							for($r = 0; $r < $total_selected_drugs; $r++)
							{
								$prescription_id = $selected_drugs[$r];
								if($prescription_id == $id)
								{
									$amoun=$visit_charge_amount* $sum_units ;
									$total_visit_charge_amount=$amoun+$temp_visit_charge_amount;
									$temp_visit_charge_amount=$total_visit_charge_amount; 
									$s++;
									?>
										<tr>
											<td><?php echo $s;?></td>
											<td><?php echo $medicine;?></td>
											<td><?php echo $consumption;?> - <?php echo $duration;?> - <?php echo $frequncy;?></td>
											<td><?php echo $number_of_days;?></td>
											<td><?php echo $sum_units;?></td>
											<td><?php echo number_format($visit_charge_amount,2);?></td>
											<td><?php echo number_format($amoun,2);?></td>
										</tr>
									<?php
								}
							}
						}
						
						else
						{
							$amoun=$visit_charge_amount* $sum_units ;
							$total_visit_charge_amount=$amoun+$temp_visit_charge_amount;
							$temp_visit_charge_amount=$total_visit_charge_amount; 
							$s++;
							?>
								<tr>
									<td><?php echo $s;?></td>
									<td><?php echo $medicine;?></td>
									<td><?php echo $consumption;?> - <?php echo $duration;?> - <?php echo $frequncy;?></td>
									<td><?php echo $number_of_days;?></td>
									<td><?php echo $sum_units;?></td>
									<td><?php echo number_format($visit_charge_amount,2);?></td>
									<td><?php echo number_format($amoun,2);?></td>
								</tr>
							<?php
						}
						endforeach;

						?>

                         <tr>
	                        <td colspan="6" style="align:right">Grand Total :</td>
	                        <td><?php echo $temp_visit_charge_amount;?></td>
	                    </tr>
                    </tbody>
               </table>
            </div>
        </div>
        <?php
		$this->session->unset_userdata('selected_drugs');
		}
		
        if($doctor == '-')
        {

        }
        else
        {
        ?>
            <div class="row" style="font-style:bold; font-size:11px;">
                <div class="col-md-10 pull-left">
                    <div class="col-md-6 pull-left">
                        <p>Prescribed By: </p>
                        <p><strong>Dr. <?php echo $doctor;?></strong></p>
                        <br>

                        <p>Sign : ................................ </p>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-12 ">
                <div class="col-md-6 pull-left">
                    	Prepared by: <?php echo $served_by;?> 
                </div>
                <div class="col-md-6 pull-right">
            			<?php echo date('jS M Y H:i a'); ?> Thank you
            	</div>
          	</div>
        	
        </div>
    </body>
    
</html>