<?php
$temp_visit_charge_amount="";
//get drug times
$times_rs = $this->pharmacy_model->get_drug_times();
$num_times = count($times_rs);
$time_list = "<select name = 'x' id='x_value' class='form-control'>";

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
$cons_list = "<select name = 'consumption' id='consumption_value' class='form-control'>";
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
$duration_list = "<select name = 'duration' id='duration_value' class='form-control'>";
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
?>

<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Prescribed drugs</h2>
            </header>
            
            <div class="panel-body">
                <table class='table table-striped table-hover table-condensed'>
                     <tr>
                        <th></th>
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
                            ?>
                            <th>Admin route:</th>
                            <th>Qty:</th>
                            <th>X:</th>
                            <th>Duration:</th>
                            <?php

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
						$prescription_id = $key_rs->prescription_id;

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
                        $time_item = '';
                        $time_list2 = "<select id = 'x".$id."' name = 'x".$id."' class='form-control'>";
                        
                            foreach ($times_rs as $key_items):

                                $time = $key_items->drug_times_name;
                                $drug_times_id = $key_items->drug_times_id;
                                if($time == $frequncy)
                                {
                                    $time_item = $time;
                                    $time_list2 = $time_list2."<option value='".$drug_times_id."' selected>".$time."</option>";										
                                }
                                
                                else
                                {
                                    $time_list2 = $time_list2."<option value='".$drug_times_id."'>".$time."</option>";
                                }
                            endforeach;
                        $time_list2 = $time_list2."</select>";
                        $duration_item = '';
                        $duration_list2 = "<select id = 'duration".$id."' name = 'duration".$id."' class='form-control'>";
                        
                        foreach ($duration_rs as $key_duration):
                            $durations = $key_duration->drug_duration_name;
                            $drug_duration_id = $key_duration->drug_duration_id;
                            if($durations == $duration)
                            {
                                $duration_item = $durations;
                                $duration_list2 = $duration_list2."<option value='".$drug_duration_id."' selected>".$durations."</option>";
                            }
                            
                            else
                            {
                                $duration_list2 = $duration_list2."<option value='".$drug_duration_id."'>".$durations."</option>";
                            }
                        endforeach;
                        $duration_list2 = $duration_list2."</select>";
                         $consumption_item = '';
                        $cons_list2 = "<select id = 'consumption".$id."' name = 'consumption".$id."' class='form-control'>";
                        
                        foreach ($rs_cons as $key_cons):
                            $con = $key_cons->drug_consumption_name;
                            $drug_consumption_id = $key_cons->drug_consumption_id;
                            if($con == $consumption)
                            {
                                $consumption_item = $con;
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
						$sum_units = 0;
                        foreach ($rsf as $key_price):
                            $sum_units = $key_price->visit_charge_units;
                            $charge = $key_price->visit_charge_amount;
                        endforeach;
                        
                                    
                        $amoun=$charge* $sum_units ;
                            
                        $total_visit_charge_amount=$amoun+$temp_visit_charge_amount;
                        $temp_visit_charge_amount=$total_visit_charge_amount; 
                        $s++;
						
						$checkbox_data = array(
								  'name'        => 'visit_charge[]',
								  'id'          => 'checkbox'.$prescription_id,
								  'class'          => 'css-checkbox lrg',
								  'value'       => $prescription_id
								);

                    ?>
                  
                    <tr>
                        <td><?php echo form_checkbox($checkbox_data); ?><label for="checkbox<?php echo $visit_id; ?>" name="checkbox79_lbl" class="css-label lrg klaus"></label></td>
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
                            ?>
                                <th><?php echo $consumption_item;?></th>
                                <th><?php echo $quantity;?></th>
                                <th><?php echo $time_item;?></th>
                                <th><?php echo $duration_item;?></th>
                            <?php

                        }

                        ?>

                        <td>
							<a  class="btn btn-sm btn-primary" id="open_visit<?php echo $id;?>" onclick="get_visit_trail(<?php echo $id;?>);"> View Detail</a>
							<a  class="btn btn-sm btn-warning " id="close_visit<?php echo $id;?>" style="display:none;" onclick="close_visit_trail(<?php echo $id;?>);"> Close Detail</a>
						</td>
                       
                         <td>
                          <?php
                            if($module == 1)
                            {
                                ?>
                                <button name="update" type="submit" class="btn btn-sm btn-success" onclick="dispense_prescription(<?php echo $visit_id?>,<?php echo $visit_charge_id;?>,<?php echo $id;?>,<?php echo $module;?>)" > Update & dispense </button>
                                <?php
                            }
                            else
                            {
                                ?>
                                <!-- <button name="update" type="submit" value="Update" class="btn btn-sm btn-success" onclick="button_update_prescription(<?php echo $visit_id?>,<?php echo $visit_charge_id;?>,<?php echo $id;?>,<?php echo $module;?>);">Update prescription</button> -->
                                <?php
                            }
                            ?>
                            <input type="hidden" name="hidden_id" value="<?php echo $id?>" />
                            <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
                         </td>
                         <td>
                            <div class='btn-toolbar'>
                                <div class='btn-group'>
                                    <a class='btn btn-danger btn-sm'  onclick="delete_prescription(<?php echo $id;?>,<?php echo $visit_id;?>,<?php echo $visit_charge_id;?>,<?php echo $module?>);"><i class='fa fa-trash'></i></a>
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
						<td colspan="10">
								
						 <section class="panel">            

						    <!-- Widget content -->
						    <div class="panel-body">
						      <div class="padd">
						      	<div class="row">
						      		<div class="col-md-12">
						      			 <div class="col-md-3">
						                    <div class="form-group">
						                        
						                        	<?php echo $cons_list2;?>
						                      
						                    </div>
						                 </div>
						                  <div class="col-md-3">
						                    <div class="form-group">
						                        <input type="text" name="quantity<?php echo $id?>"  id="quantity<?php echo $id?>" class='form-control' autocomplete="off" value="<?php echo $quantity?>" />
						                    </div>
						                 </div>
						                  <div class="col-md-3">
						                    <div class="form-group">
						                        	<?php echo $time_list2;?>
						                        
						                    </div>
						                 </div>
						                  <div class="col-md-3">
						                    <div class="form-group">
						                        <?php echo $duration_list2;?>
						                    </div>
						                 </div>
						      		</div>
						      	</div>
						      	 <input type="hidden" name="hidden_id" value="<?php echo $id?>" />
						         <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
						      	<br>
						      	<div class="row">
						      		<div class="col-md-12 center-align">
						      			<button type="submit" name="update" class="btn btn-sm btn-warning" onclick="button_update_prescription(<?php echo $visit_id?>,<?php echo $visit_charge_id;?>,<?php echo $id;?>,<?php echo $module;?>);">Update Prescription</button>	<a class="btn btn-sm btn-success" href="#">Print Label</a>
						      		</div>
						      	</div>
						      </div>
						    </div>
						</section>

						</td>
					</tr>
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
