<?php
$drug_size ="";
$drug_size_type ="";

$rs21 = $this->nurse_model->get_visit_type($visit_id);

$num_type= count($rs21);
foreach ($rs21 as $key):
	$visit_t = $key->visit_type;
endforeach;

$drug_dose ="";
$unit_of_measure ="";
$drug_type_name ="";
$admin_route ="";
$dose_unit ="";
$service_charge_name ="";

$temp_visit_charge_amount="";
$total_visit_charge_amount="";
if(!empty($service_charge_id)){

	$rs2 = $this->pharmacy_model->get_drug($service_charge_id);
	
	$s = 0;
	foreach ($rs2 as $key2):
		$drug_type_id = $key2->drug_type_id;
		$admin_route_id = $key2->drug_administration_route_id;
		// $drug_dose = $key2->drugs_dose;
		$unit_of_measure = $key2->unit_of_measure;
		// $drug_size = $key2->drug_size;
		// $drug_size_type = $key2->drug_size_type;
		$service_charge_name = $key2->service_charge_name;
	endforeach;
		
		if(!empty($drug_type_id)){
			$rs3 = $this->pharmacy_model->get_drug_type_name($drug_type_id);
			$num_rows3 = count($rs3);
			if($num_rows3 > 0){
				foreach ($rs3 as $key3):
					$drug_type_name = $key3->drug_type_name;
				endforeach;
			}
		}
		// if(!empty($dose_unit_id)){
		// 	$rs3 = $this->pharmacy_model->get_dose_unit2($dose_unit_id);
		// 	$num_rows3 = count($rs3);
		// 	if($num_rows3 > 0){
		// 		foreach ($rs3 as $key3):
		// 			$dose_unit = $key3->drug_dose_unit_name;
		// 		endforeach;
		// 	}
		// }
		if(!empty($admin_route_id)){
			$rs3 = $this->pharmacy_model->get_admin_route2($admin_route_id);
			$num_rows3 = count($rs3);
			if($num_rows3 > 0){
				foreach ($rs3 as $key3):
					$admin_route = $key3->drug_administration_route_name;
				endforeach;
			}
		}
}else{
	$service_charge_id =0;
}

if(!empty($delete)){
	
	$visit_charge_id = $_GET['visit_charge_id'];
	$del = new prescription();
	$del->delete_visit_charge($visit_charge_id);
	
	$del = new prescription();
	$del->delete_prescription($delete);
}
//if the update button is clicked
$rs_forms = $this->pharmacy_model->get_drug_forms();
$num_forms = count($rs_forms);

if($num_forms > 0){
	
	$xray = "'";
	$t = 0;
	foreach ($rs_forms as $key_forms):
		if($t == ($num_forms-1)){
	
			$xray = $xray."".$key_forms->drug_type_name."'";
		}

		else{
			$xray = $xray."".$key_forms->drug_type_name."','";
		}
		$t++;
	endforeach;
}

$rs_admin = $this->pharmacy_model->get_admin_route();
$num_admin = count($rs_admin);

if($num_admin > 0){
	
	$xray2 = "'";
	$k = 0;
	foreach ($rs_admin as $key_admin):

		if($k == ($num_admin-1)){
	
			$xray2 = $xray2."".$key_admin->drug_administration_route_name."'";
		}

		else{
	
			$xray2 = $xray2."".$key_admin->drug_administration_route_name.",";
		}
		$k++;
	endforeach;
}

$rs_units = $this->pharmacy_model->get_dose_unit();
$num_units = count($rs_units);

if($num_units > 0){
	
	$xray3 = "'";
	$l=0;
	foreach ($rs_units as $key_units):

		if($l == ($num_units-1)){
	
			$xray3 = $xray3.$key_units->drug_dose_unit_name."'";
		}

		else{
	
			$xray3 = $xray3.$key_units->drug_dose_unit_name."','";
		}
	endforeach;
}

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

//warnings
$warnings_rs = $this->pharmacy_model->get_warnings();
$num_warnings = count($warnings_rs);

$warning = "'";
$i = 0;
	foreach ($warnings_rs as $key_warning):

		if($i == ($num_warnings-1)){
		
			$warning = $warning."".$key_warning->warnings_name."'";
		}

		else{
		
			$warning = $warning."".$key_warning->warnings_name."','";
		}
		$i++;
	endforeach;

//instructions
$instructions_rs = $this->pharmacy_model->get_instructions();
$num_instructions = count($instructions_rs);

$inst = "'";
$p = 0;

	foreach ($instructions_rs as $key_instructions):
		if($p == ($num_instructions-1)){
		
			$inst = $inst."".$key_instructions->instructions_name."'";
		}

		else{
			$inst = $inst."".$key_instructions->instructions_name."','";
		}
	$p++;
	endforeach;
?>



    
<div class="center-align">
	<?php
	$error = $this->session->userdata('error_message');
	$validation_error = validation_errors();
	$success = $this->session->userdata('success_message');
	
	if(!empty($error))
	{
		echo '<div class="alert alert-danger">'.$error.'</div>';
		$this->session->unset_userdata('error_message');
	}
	
	if(!empty($validation_error))
	{
		echo '<div class="alert alert-danger">'.$validation_error.'</div>';
	}
	
	if(!empty($success))
	{
		echo '<div class="alert alert-success">'.$success.'</div>';
		$this->session->unset_userdata('success_message');
	}
?>
</div>
	<!-- end #header -->
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Prescribe drugs</h2>
    </header>

    <div class="panel-body">
		
		<div class="well well-sm info">
			<h5 style="margin:0;">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>First name:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $patient_surname;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-5">
								<strong>Other names:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $patient_othernames;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>Gender:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $gender;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>Age:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $age;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-5">
								<strong>Account balance:</strong>
							</div>
							<div class="col-lg-5">
								Kes <?php echo number_format($account_balance, 2);?>
							</div>
						</div>
					</div>
				</div>
			</h5>
		</div>
        
<?php echo form_open($this->uri->uri_string, array("class" => "form-horizontal"));?>
<div class="row">
	<div class="col-md-4">
         
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Drug details</h2>
            </header>
        
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Medicine: </label>
                    
                    <div class="col-lg-8">
                             <?php
                            if($module == 1)
                            {

                                ?> 
                            <input type="text" name="passed_value" id="passed_value"  class="form-control" onClick="myPopup2(<?php echo $visit_id;?>,<?php echo $module;?>)" value="<?php echo $service_charge_name;?>"/>
                           
                            <a href="#" onClick="myPopup2(<?php echo $visit_id;?>,<?php echo $module;?>)">Get Drug</a>
                                <?php
                            }
                            
                            else
                            {
                                ?> 
                            <input type="text" name="passed_value" id="passed_value"  class="form-control" onClick="myPopup2_soap(<?php echo $visit_id;?>)" value="<?php echo $service_charge_name;?>"/>
                           
                            <a href="#" onClick="myPopup2_soap(<?php echo $visit_id;?>)">Get Drug</a>
                                <?php
                            }
                            ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">Allow substitution: </label>
                    
                    <div class="col-lg-8">
                        <?php
                            if(set_value('substitution') == 'Yes')
                            {
                                echo '<input name="substitution" type="checkbox" value="Yes" checked="checked" />';
                            }
                            else
                            {
                                echo '<input name="substitution" type="checkbox" value="Yes"/>';
                            }
                        ?>
                            
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Dose Unit: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $unit_of_measure;?>
                    </div>
                </div>
			</div>
		</section>
	</div>
	<!-- end of drugs -->
    
    <!-- start of admission -->
    <div class="col-md-4">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Admission</h2>
            </header>
            
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Drug Type: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $drug_type_name?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Admin Route: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $admin_route?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">Number of Days: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" id="days" class='form-control' name="number_of_days"  autocomplete="off" value="<?php echo set_value('days');?>"/>
                    </div>
                </div>
                <?php if($drug_size_type!=""){
                 ?>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Amount contained in One Pack: </label>
                        
                        <div class="col-lg-8">
                            <?php echo $drug_size.'  '.$drug_size_type ?>
                        </div>
                    </div>
                  <?php
                  }
                  ?>
			</div>
		</section>
	</div>
    <!-- end of admission -->
    
    <!-- start of usage -->
    <div class="col-md-4">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Usage</h2>
            </header>
            
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Method: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $cons_list?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Quantity: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" name="quantity" class='form-control' autocomplete="off" value="<?php echo set_value('quantity');?>" /> <?php echo $drug_size_type?> <input name="service_charge_id" id="service_charge_id" value="<?php echo $service_charge_id;?>" type="hidden">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Times: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $time_list;?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">Duration: </label>
                    
                    <div class="col-lg-8">
                        <?php echo $duration_list;?>
                    </div>
                </div>
			</div>
		</section>
	</div>
	<!-- end of usage -->
</div>
<!-- end of drugs tab -->

<div class="row col-md-12">
    <div class="center-align">
        <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
        <input name="submit" type="submit" class="btn btn-info" value="Prescribe" />
    </div>
</div>

                         
<?php echo form_close();?>
<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Prescribed drugs</h2>
            </header>
            
            <div class="panel-body">
                <table class='table table-striped table-hover table-condensed'>
                     <tr>
                        <th>No.</th>
                        <th>Medicine:</th>
                        <th>Days:</th>
                        <?php
                        if($module == 1)
                        {
                            ?>
                             <th>Unit Price:</th>
                             <th>Total:</th>
                             <th>Units given:</th>
                            <?php
                        }
                        else
                        {

                        }
                        ?>
                        <th colspan="3"> </th>
                    </tr>
                   <?php 
                   $rs = $this->pharmacy_model->select_prescription($visit_id);

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


                        // checking for the stocks in drugs
                        $purchases = $this->pharmacy_model->item_purchases($checker_id);
                        $sales = $this->pharmacy_model->get_drug_units_sold($checker_id);
                        $deductions = $this->pharmacy_model->item_deductions($checker_id);
                        $in_stock = ($quantity + $purchases) - $sales - $deductions;
                        // end of checking stocks
                        
                        $substitution = "<select id ='substitution".$id."' name='substitution".$id."' class='form-control'>";
                        if($sub == "No"){
                            $substitution = $substitution."<option selected>No</option><option>Yes</option>";
                        }
                        else{
                            $substitution = $substitution."<option>No</option><option selected>Yes</option>";
                        }
                        $substitution = $substitution."</select>";
                        
                        //$drugs = new prescription();
                        //$medicine = $drugs->get_product_name($service_charge_id);
                        
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
                        
                        $time_list2 = "<select id = 'x".$id."' name = 'x".$id."' class='form-control'>";
                        
                            foreach ($times_rs as $key_items):

                                $time = $key_items->drug_times_name;
                                $drug_times_id = $key_items->drug_times_id;
                                if($time == $frequncy)
                                {
                                    $time_list2 = $time_list2."<option value='".$drug_times_id."' selected>".$time."</option>";										
                                }
                                
                                else
                                {
                                    $time_list2 = $time_list2."<option value='".$drug_times_id."'>".$time."</option>";
                                }
                            endforeach;
                        $time_list2 = $time_list2."</select>";
                        
                        $duration_list2 = "<select id = 'duration".$id."' name = 'duration".$id."' class='form-control'>";
                        
                        foreach ($duration_rs as $key_duration):
                            $durations = $key_duration->drug_duration_name;
                            $drug_duration_id = $key_duration->drug_duration_id;
                            if($durations == $duration)
                            {
                                $duration_list2 = $duration_list2."<option value='".$drug_duration_id."' selected>".$durations."</option>";
                            }
                            
                            else
                            {
                                $duration_list2 = $duration_list2."<option value='".$drug_duration_id."'>".$durations."</option>";
                            }
                        endforeach;
                        $duration_list2 = $duration_list2."</select>";
                        
                        $cons_list2 = "<select id = 'consumption".$id."' name = 'consumption".$id."' class='form-control'>";
                        
                        foreach ($rs_cons as $key_cons):
                            $con = $key_cons->drug_consumption_name;
                            $drug_consumption_id = $key_cons->drug_consumption_id;
                            if($con == $consumption)
                            {
                                $cons_list2 = $cons_list2."<option value='".$drug_consumption_id."' selected>".$con."</option>";
                            }
                            
                            else
                            {
                                $cons_list2 = $cons_list2."<option value='".$drug_consumption_id."'>".$con."</option>";
                            }
                        endforeach;
                        $cons_list2 = $cons_list2."</select>";


                        $rsf = $this->pharmacy_model->select_invoice_drugs($visit_id,$service_charge_id);
                        $num_rowsf = count($rsf);
                        foreach ($rsf as $key_price):
                            $sum_units = $key_price->visit_charge_units;
                            $charge = $key_price->visit_charge_amount;
                        endforeach;
                        
                                    
                        $amoun=$charge* $sum_units ;
                            
                        $total_visit_charge_amount=$amoun+$temp_visit_charge_amount;
                        $temp_visit_charge_amount=$total_visit_charge_amount; 
                        $s++;


                    ?>
                    <?php
                        if($module == 1)
                        {
                            // pharmacy
                             echo form_open('pharmacy/dispense_prescription/'.$visit_id.'/'.$visit_charge_id.'/'.$id.'/'.$module, array("class" => "form-horizontal"));
                        }
                        else
                        {
                             echo form_open('pharmacy/update_prescription/'.$visit_id.'/'.$visit_charge_id.'/'.$id.'/'.$module, array("class" => "form-horizontal"));
                        }
                    ?>
                    <tr>
                        <td><?php echo $s; ?></td>
                        <td><?php echo $medicine;?></td>
                        <td><?php echo $number_of_days;?></td>
                       
                        <?php
                        if($module == 1)
                        {
                            ?>
                                <!--<td><?php echo $charge;?></td>-->
                                <td><input type="text" name="charge<?php echo $id?>" class='form-control' id="charge<?php echo $id?>" required="required" placeholder="charge" value="<?php echo $charge; ?>"  /></td>
                                <td><?php echo number_format($amoun, 2);?></td>
                                <td><input type="text" name="units_given<?php echo $id?>" class='form-control' id="units_given<?php echo $id?>" required="required" placeholder="units given" value="<?php echo $sum_units; ?>"  /></td>
                            <?php
                        }
                        else
                        {

                        }
                        ?>
                        <td>
							<a  class="btn btn-sm btn-primary" id="open_visit<?php echo $id;?>" onclick="get_visit_trail(<?php echo $id;?>);"> View Detail</a>
							<a  class="btn btn-sm btn-warning " id="close_visit<?php echo $id;?>" style="display:none;" onclick="close_visit_trail(<?php echo $id;?>);"> Close Detail</a></td>
						</td>
                       
                         <td>
                          <?php
                            if($module == 1)
                            {
                                ?>
                                <input name="update" type="submit" value="Update & dispense" class="btn btn-sm btn-success" />
                                <?php
                            }
                            else
                            {
                                ?>
                                <input name="update" type="submit" value="Update" class="btn btn-sm btn-success" />
                                <?php
                            }
                            ?>
                            <input type="hidden" name="hidden_id" value="<?php echo $id?>" />
                            <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
                         </td>
                         <td>
                            <div class='btn-toolbar'>
                                <div class='btn-group'>
                                    <a class='btn btn-danger btn-sm' href='<?php echo site_url();?>pharmacy/delete_prescription/<?php echo $id;?>/<?php echo $visit_id?>/<?php echo $visit_charge_id?>/<?php echo $module;?>' onclick="return confirm('Are you sure you want to remove this drug?');"><i class='fa fa-trash'></i></a>
                                </div>
                             </div>
                         </td>
                    </tr>

         
                        
                    <?php
                    	 $v_data['prescription_id'] = $id;
                    	 $v_data['substitution'] = $substitution;
                    	 $v_data['duration_list2'] = $duration_list2;
                    	 $v_data['cons_list2'] = $cons_list2;
                    	 $v_data['time_list2'] = $time_list2;
                    	 $v_data['doseunit'] = $doseunit;
                    	 $v_data['quantity'] = $quantity;
                    	 $v_data['visit_id'] = $visit_id;
                    	 $v_data['visit_charge_id'] = $visit_charge_id;
                    	 $v_data['module'] = $module;
                   	?>

					<tr id="visit_trail<?php echo $id;?>" style="display:none;">
						<td colspan="10"><?php echo $this->load->view("prescription_detail", $v_data, TRUE);?></td>
					</tr>
                    <?php echo form_close();?>
              <?php
              endforeach;
                if($module == 1)
                {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Grand Total</td>
                        <td><?php echo number_format($total_visit_charge_amount, 2);?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                }
                }

              ?>
              </table>
           </div>
       </section>
	</div>
</div>

<div class="row col-md-12">
	<?php
		if($module == 1){
			?>
			<br/>
			
			<div class="center-align">
			<?php echo '<a href="'.site_url().'pharmacy/send_to_accounts/'.$visit_id.'" onclick="return confirm(\'Send to accounts?\');" class="btn btn-sm btn-success">Send to Accounts</a>';?>
			<?php echo '<a href="'.site_url().'pharmacy/print-prescription/'.$visit_id.'" class="btn btn-sm btn-warning fa fa-print" target="_blank"> Print Prescription</a>';?>

		 	</div>
			<?php
		}else{
			?>
			<br/>
			<div class="center-align">
		 	 <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
		            <input name="pharmacy_doctor" onClick="send_to_pharmacy2(<?php echo $visit_id;?>);unload()" type="button" class="btn btn-sm btn-success" value="Done" />
		    </div>
			<?php
		}
	?>
 	
 </div>  
 </div>
 </div>
    </div>
   </section>

  
<script type="text/javascript">

	function myPopup2(visit_id,module) {
		var config_url = $('#config_url').val();
		var win_drugs = window.open(config_url+"pharmacy/drugs/"+visit_id+"/"+module,"Popup3","height=1200,width=1000,,scrollbars=yes,"+ 
							"directories=yes,location=yes,menubar=yes," + 
							 "resizable=no status=no,history=no top = 50 left = 100"); 
  		win_drugs.focus();
	}
	
	function myPopup2_soap(visit_id) {
		var config_url = $('#config_url').val();
		var win_drugs = window.open(config_url+"pharmacy/drugs/"+visit_id,"Popup2","height=1200,width=1000,,scrollbars=yes,"+ 
							"directories=yes,location=yes,menubar=yes," + 
							 "resizable=no status=no,history=no top = 50 left = 100"); 
  		win_drugs.focus();
	}
	
	function send_to_pharmacy2(visit_id)
	{
		var config_url = $('#config_url').val();
		var url = config_url+"pharmacy/display_prescription/"+visit_id;
	
		$.get(url, function( data ) {
			var obj = window.opener.document.getElementById("prescription");
			obj.innerHTML = data;
			window.close(this);
		});
	}
</script>



<script type="text/javascript">

	function get_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = '';
		button.style.display = 'none';
		button2.style.display = '';
	}
	function close_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = 'none';
		button.style.display = '';
		button2.style.display = 'none';
	}

	function update_prescription_values(visit_id,visit_charge_id,prescription_id,module)
    {
      
       //var product_deductions_id = $(this).attr('href');
       var quantity = $('#quantity'+prescription_id).val();
       var x = $('#x'+prescription_id).val();
       var duration = $('#duration'+prescription_id).val();
       var consumption = $('#consumption'+prescription_id).val();


       var url = "<?php echo base_url();?>pharmacy/update_prescription/"+visit_id+'/'+visit_charge_id+'/'+prescription_id+'/'+module;
  
        //window.alert(data_url);
		  $.ajax({
		  type:'POST',
		  url: data_url,
		  data:{quantity: quantity, x: x, duration: duration,consumption: consumption},
		  dataType: 'text',
           success:function(data){
            
            window.alert(data.result);
            if(module == 1){
				window.location.href = "<?php echo base_url();?>pharmacy/prescription1/"+visit_id+"/1'";
			
			}else{
				window.location.href = "<?php echo base_url();?>pharmacy/prescription1/"+visit_id+"";
			}
           },
           error: function(xhr, status, error) {
            alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
           
           }
        });
        return false;
     }
  </script>

                                        