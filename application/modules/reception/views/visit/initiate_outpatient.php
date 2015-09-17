<style type="text/css">
	#insured_company{display:none;}
</style>
						<?php 
                        $validation_error = validation_errors();
                        
                        if(!empty($validation_error))
                        {
                            echo '<div class="alert alert-danger center-align">'.$validation_error.'</div>';
                        }
                        ?>
                        <?php echo form_open("reception/save_visit/".$patient_id, array("class" => "form-horizontal"));?>
                        <div class="row">
                        	<div class="col-md-6">
                            
								<h4 class="center-align" style="margin-bottom:10px;">Visit details</h4>
								
                                <div class="form-group">
									<label class="col-lg-4 control-label">Visit type: </label>
									
									<div class="col-lg-8">
										<select name="visit_type_id" id="visit_type_id" class="form-control">
											<option value="">----Select a visit type----</option>
											<?php
																	
												if($visit_types->num_rows() > 0){

													foreach($visit_types->result() as $row):
														$visit_type_name = $row->visit_type_name;
														$visit_type_id = $row->visit_type_id;

														if($visit_type_id == set_value('visit_type_id'))
														{
															echo "<option value='".$visit_type_id."' selected='selected'>".$visit_type_name."</option>";
														}
														
														else
														{
															echo "<option value='".$visit_type_id."'>".$visit_type_name."</option>";
														}
													endforeach;
												}
											?>
										</select>
									</div>
								 </div>
								
								<div id="insured_company">
                                    
									<div class="form-group" style="margin-bottom: 15px;">
										<label class="col-lg-4 control-label">Insurance Number: </label>
										<div class="col-lg-8">
											<input type="text" name="insurance_number" class="form-control">
										</div>
									</div>
                                    
									<div class="form-group" style="margin-bottom: 15px;">
										<label class="col-lg-4 control-label">Insurance Limit: </label>
										<div class="col-lg-8">
											<input type="text" name="insurance_limit" class="form-control">
										</div>
									</div>
								</div>
                                
								<div class="form-group">
									<label class="col-lg-4 control-label">Department: </label>
									
									<div class="col-lg-8">
										<select name="department_id" id="department_id" class="form-control" onchange="check_department_type()">
											<option value="">----Select a Department----</option>
											<?php
																	
												if($visit_departments->num_rows() > 0){

													foreach($visit_departments->result() as $row):
														$department_name = $row->department_name;
														$department_id = $row->department_id;

														
														if($department_id == set_value('department_id'))
														{
															echo "<option value='".$department_id."' selected='selected'>".$department_name."</option>";
														}
														
														else
														{
															echo "<option value='".$department_id."'>".$department_name."</option>";
														}
													endforeach;
												}
											?>
										</select>
									</div>
								 </div>
								 
								<!--<div class="form-group">
									<label class="col-lg-4 control-label">Visit type: </label>
                                    <?php						
										if($visit_types->num_rows() > 0){

											foreach($visit_types->result() as $row):
												$visit_type_name = $row->visit_type_name;
												$visit_type_id = $row->visit_type_id;

												?>
                                                <div class="col-lg-4">
                                                    <div class="radio">
                                                        <label>
                                                            <input id="optionsRadios2" type="radio" name="visit_type" value="<?php echo $patient_type_id;?>" checked="checked" onclick="do_patient_type_function(<?php echo $visit_type_id;?>)">
                                                            <?php echo $visit_type_name;?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
											endforeach;
										}
									?>
								</div>-->
								<div  id="department_type" style="display:none;">
									<div class="form-group">
										<label class="col-lg-4 control-label">Doctor: </label>
										
										<div class="col-lg-8">
											 <select name="personnel_id" class="form-control">
												<option value="">----Select a Doctor----</option>
												<?php
																		
													if(count($doctor) > 0){
														foreach($doctor as $row):
															$fname = $row->personnel_fname;
															$onames = $row->personnel_onames;
															$personnel_id = $row->personnel_id;
															
															if($personnel_id == set_value('personnel_id'))
															{
																echo "<option value='".$personnel_id."' selected='selected'>".$onames." ".$fname."</option>";
															}
															
															else
															{
																echo "<option value='".$personnel_id."'>".$onames." ".$fname."</option>";
															}
														endforeach;
													}
												?>
											</select>
										</div>
									</div>
										
									<div class="form-group">
										<label class="col-lg-4 control-label">Select service: </label>
										
										<div class="col-lg-8">
											<select name="service_id" class="form-control" id="department_services">
												
											</select>
										</div>
									</div>
										
									<div class="form-group">
										<label class="col-lg-4 control-label">Select charge: </label>
										
										<div class="col-lg-8">
											<select name="service_charge_name" class="form-control" id="services_charges">
												
											</select>
										</div>
									</div>
								
							   </div>
								<input type="hidden" name="patient_type_id" value="<?php echo $patient_type_id;?>">
							</div>
							<!--end left -->
							<!-- start right -->
							<div class="col-md-6">
								<h4 class="center-align" style="margin-bottom:10px;">Appointment details</h4>
                                
								<div class="form-group">
									<label class="col-lg-4 control-label">Visit date: </label>
									
									<div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date" placeholder="Visit Date" value="<?php echo date('Y-m-d');?>">
                                        </div>
									</div>
								</div>
                          		
                                <div class="form-group">
									<label class="col-lg-4 control-label">Schedule appointment? </label>
                                    <div class="col-lg-4">
                                        <div class="radio">
                                            <label>
                                                <input id="optionsRadios2" type="radio" name="appointment_id" value="0" checked="checked" onclick="schedule_appointment(0)">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <div class="radio">
                                            <label>
                                                <input id="optionsRadios2" type="radio" name="appointment_id" value="1" onclick="schedule_appointment(1)">
                                                Yes
                                            </label>
                                        </div>
                                    </div>
								</div>
                                
                                <div id="appointment_details" style="display:none;">
                                    <!--<div class="form-group">
                                        <label class="col-lg-4 control-label">Schedule: </label>
                                        
                                        <div class="col-lg-8">
                                            <a onclick="check_date()" style="cursor:pointer;">[Show Doctor's Schedule]</a><br>
                                            <div id="show_doctor" style="display:none;"> 
                                                <select name="doctor" id="doctor" onChange="load_schedule()" class="form-control">
                                                    <option >----Select Doctor to View Schedule---</option>
                                                        <?php
                                                            if(count($doctor) > 0){
                                                                foreach($doctor as $row):
                                                                    $fname = $row->personnel_fname;
                                                                    $onames = $row->personnel_onames;
                                                                    $personnel_id = $row->personnel_id;
                                                                    echo "<option value=".$personnel_id.">".$onames."</option>";
                                                                endforeach;
                                                            }
                                                        ?>
                                                </select>
                                            </div>
                                            <div  id="doctors_schedule"> </div>
                                        </div>
                                    </div>-->
                                    
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label">Start time : </label>
                                    
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </span>
                                                <input type="text" class="form-control" data-plugin-timepicker="" name="timepicker_start">
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label class="col-lg-4 control-label">End time : </label>
                                        
                                        <div class="col-lg-8">		
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </span>
                                                <input type="text" class="form-control" data-plugin-timepicker="" name="timepicker_end">
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
							<!-- end right -->
						</div>
						<!-- end of row -->
						<div class="center-align">
							<input type="submit" value="Initiate Visit" class="btn btn-info btn-sm"/>
						</div>
						<div class="center-align">
							<div class="alert alert-info center-align">Note: For Appointments ensure that you have filled in both sections on this page.</div>
						</div>
					<?php echo form_close();?>
					 <!-- end of form -->
            
 <script type="text/javascript" charset="utf-8">
 	$(document).ready(function(){
      do_patient_type_function(<?php echo $patient_type_id;?>);
      
  	});
	 function check_date(){
	     var datess=document.getElementById("datepicker").value;
	     if(datess){
		  $('#show_doctor').fadeToggle(1000); return false;
		 }
		 else{
		  alert('Select Date First')
		 }
	}
	function load_schedule(){
		var config_url = $('#config_url').val();
		var datess=document.getElementById("datepicker").value;
		var doctor=document.getElementById("doctor").value;
		var url= config_url+"/reception/doc_schedule/"+doctor+"/"+datess;
		
		  $('#doctors_schedule').load(url);
		  $('#doctors_schedule').fadeIn(1000); return false;	
	}
 
	function getXMLHTTP() {
	 //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
	}
	
	
	
	function getCity(strURL) {		
		
		var req = getXMLHTTP();
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('citydiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
	function checks(patient_type){
		var patient_type=document.getElementById('patient_type').value;
		if(patient_type==0){
			alert('Ensure you have selected The patient type');
		}
		
	}
	function check_department_type()
	{
		var myTarget = document.getElementById("department_id").value;
		
		//get department services
		$.get( "<?php echo site_url();?>reception/get_department_services/"+myTarget, function( data ) 
		{
			$( "#department_services" ).html( data );
			
			var myTarget2 = document.getElementById("department_type");
			//var myTarget3 = document.getElementById("patient_type_div");
			// counseling department div
			//var myTarget4 = document.getElementById("counseling_department");
			// end of counseling department div
			
			if(myTarget==7)
			{
				myTarget2.style.display = 'block';
				//myTarget4.style.display = 'none';
				//myTarget3.style.display = 'none';	
			}
			else if(myTarget==12)
			{
				//myTarget4.style.display = 'block';
				myTarget2.style.display = 'none';
				//myTarget3.style.display = 'none';
			}
			else{
				myTarget2.style.display = 'none';
				//myTarget3.style.display = 'block';	
				//myTarget4.style.display = 'none';
			}
		});
	}
	
	$(document).on("change","select#patient_insurance_id",function(e)
	{
		var patient_type_id = '<?php echo $patient_type_id;?>';
		var service_id = $(this).val();
		
		//get department services
		$.get( "<?php echo site_url();?>reception/get_services_charges/"+patient_type_id+"/"+service_id, function( data ) 
		{
			$( "#services_charges" ).html( data );
		});
	});
	
	$(document).on("change","select#department_services",function(e)
	{
		var visit_type_id = $("select#visit_type_id").val();
		var service_id = $(this).val();
		
		//get service charges
		$.get( "<?php echo site_url();?>reception/get_services_charges/"+visit_type_id+"/"+service_id, function( data ) 
		{
			$( "#services_charges" ).html( data );
		});
	});
	
	$(document).on("change","select#visit_type_id",function(e)
	{
		var visit_type_id = $(this).val();
		
		if(visit_type_id != '1')
		{
			$('#insured_company').css('display', 'block');
			// $('#consultation').css('display', 'block');
		}
		else
		{
			$('#insured_company').css('display', 'none');
			// $('#consultation').css('display', 'block');
		}
		
		var department_id = $("select#department_id").val();
		
		//get department services
		$.get( "<?php echo site_url();?>reception/get_department_services/"+department_id, function( data ) 
		{
			$( "#department_services" ).html( data );
		});
			
		var service_id = $("select#department_services").val();
		
		//get service charges
		$.get( "<?php echo site_url();?>reception/get_services_charges/"+visit_type_id+"/"+service_id, function( data ) 
		{
			$( "#services_charges" ).html( data );
		});
	});
	
	function do_patient_type_function(patient_type_id)
	{
		if(patient_type_id == '4')
		{
			$('#insured_company').css('display', 'block');
			// $('#consultation').css('display', 'block');
		}
		else
		{
			$('#insured_company').css('display', 'none');
			// $('#consultation').css('display', 'block');
		}
		var config_url = $('#config_url').val();
		// var data_url = config_url+"/reception/load_charges/"+patient_type_id;
		
		// getCity(data_url);
	}
	
	function schedule_appointment(appointment_id)
	{
		if(appointment_id == '1')
		{
			$('#appointment_details').css('display', 'block');
		}
		else
		{
			$('#appointment_details').css('display', 'none');
		}
	}
	
</script>