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

    <div class="panel-body">
<div class="row">
	<div class="col-md-4">
         
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Drug details</h2>
            </header>
        
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Medicine: </label>
                     <input type="hidden" name="visit_id" id="visit_id"  class="form-control" value="<?php echo $visit_id;?>" readonly/>
                     <input type="hidden" name="drud_id" id="drud_id"  class="form-control" value="<?php echo $service_charge_id;?>" readonly/>
                     <input type="hidden" name="module" id="module"  class="form-control" value="<?php echo $module;?>" readonly/>

                    <div class="col-lg-8">
                        <input type="text" name="passed_value" id="passed_value"  class="form-control" value="<?php echo $service_charge_name;?>" readonly/>
                           
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
                        <input type="text"  class='form-control' name="number_of_days" id="number_of_days_value" autocomplete="off" value="<?php echo set_value('days');?>"/>
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
                        <input type="text" name="quantity" class='form-control' id="quantity_value" autocomplete="off" value="<?php echo set_value('quantity');?>" /> <?php echo $drug_size_type?> <input name="service_charge_id" id="service_charge_id" value="<?php echo $service_charge_id;?>" type="hidden">
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
<div class="row col-md-12">
    <div class="center-align">
        <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
        <button type="submit" class="btn btn-primary hidden-xs" onclick="pass_prescription()">Prescribe Drugs</button>
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

	    window.alert(url);
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

	 //Login member
$(document).on("submit","form#update_prescription",function(e)
 {
  alert("sdahsdkj");
 });





  </script>

                                        
