<style type="text/css">
	#insured_company{display:none;}
</style>
<section class="panel">

		     
		        <!-- Widget head -->
		       <header class="panel-heading">
		          <h5 class="pull-left"><i class="icon-reorder"></i>Initiate Visit for <?php echo $patient;?></h5>
		          <div class="widget-icons pull-right">
		          	 <a href="<?php echo site_url();?>reception/patients-list" class="btn btn-success btn-sm pull-right">  Patients List</a>
		          </div>
		          <div class="clearfix"></div>
		        </header>             

		        <!-- Widget content -->
		        <div class="panel-body">
		          <div class="padd">
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
							 <div class="widget boxed">
						        <!-- Widget head -->
						        <div class="widget-head">
						          <h4 class="pull-left"><i class="icon-reorder"></i>Patient Visit</h4>
						          <div class="widget-icons pull-right">
						            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
						            <a href="#" class="wclose"><i class="icon-remove"></i></a>
						          </div>
						          <div class="clearfix"></div>
						        </div> 
						        <div class="widget-content">
		         				  <div class="padd">
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
		                             <div class="form-group">
		                             	<label class="col-lg-4 control-label">Visit type: </label>
										<div class="col-lg-8">
											<input type="radio" name="visit_type" value="<?php echo $patient_type_id;?>" checked="checked" onclick="do_patient_type_function(1)">Normal
		                                    <input type="radio" name="visit_type" value="4" onclick="do_patient_type_function(4)"> Insurance
										</div>
		                             </div>
							        <div id="insured_company">
							        	 <div class="form-group">
											<label class="col-lg-4 control-label">Insurance Name: </label>
											<div class="col-lg-8">
												<select name="patient_insurance_id" class="form-control">
														<option value="">--- Select Insurance Company---</option>
															<?php
															if(count($patient_insurance) > 0){	
															foreach($patient_insurance as $row):
																	// $company_name = $row->company_name;
																	$insurance_company_name = $row->insurance_company_name;
																	$insurance_company_id = $row->insurance_company_id;
															echo "<option value=".$insurance_company_id."> ".$insurance_company_name."</option>";
															endforeach;	} ?>
											  </select>
											
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-4 control-label">Insurance Number: </label>
											<div class="col-lg-8">
												<input type="text" name="insurance_number" class="form-control">
											</div>
										</div>
									</div>
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
								            <label class="col-lg-4 control-label">Consultation Type: </label>
								            
								            <div class="col-lg-8">
								            	<select name="service_charge_name" class="form-control">
													<option value='0'>Select Consultation Charge </option>	
													<?php
													$service_charge = $this->reception_model->get_service_charges_per_type($patient_type_id);
													foreach($service_charge AS $key) 
													{ 
														?>
														    <option value="<?php echo  $key->service_charge_id;?>"><?php echo $key->service_charge_name;?></option>
														<?php 
													} 
													?>
												</select>
								            </div>
								        </div>
								    
								 	
							       </div>
							        	
		                            <div id="counseling_department" style="display:none">
		                            		<div class="form-group">
									            <label class="col-lg-4 control-label">Counselor: </label>
			                                    
									            <div class="col-lg-8">
									     			 <select name="personnel_id2" class="form-control">
			                                         	<option value="">----Select a Counselor----</option>
							                        	<?php
												             $counselors = $this->reception_model->get_counselors();
													        	
															if(count($counselors) > 0){
							                            		foreach($counselors as $row):
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
								            <label class="col-lg-4 control-label">Consultation Type: </label>
								            
								            <div class="col-lg-8">
								            		<select name="service_charge_name2" class="form-control">
														<option value='0'>Select Consultation Charge </option>	
														<?php
														$service_charge = $this->reception_model->get_counseling_service_charges_per_type($patient_type_id);
														foreach($service_charge AS $key) 
														{ 
															?>
															    <option value="<?php echo  $key->service_charge_id;?>"><?php echo $key->service_charge_name;?></option>
															<?php 
														} 
														?>
													</select>
											</div>
			                            	
								        </div>
							       </div>
								 	
							     
							       <input type="hidden" name="patient_type_id" value="<?php echo $patient_type_id;?>">
							    

						        </div>

						     </div>
						    </div>
						   </div>
						     <!--end left -->
						     <!-- start right -->
						     <div class="col-md-6">
						     <div class="widget boxed">
						        <!-- Widget head -->
						        <div class="widget-head">
						          <h4 class="pull-left"><i class="icon-reorder"></i>Appointments</h4>
						          <div class="widget-icons pull-right">
						            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
						            <a href="#" class="wclose"><i class="icon-remove"></i></a>
						          </div>
						          <div class="clearfix"></div>
						        </div> 
						        <div class="widget-content">
		         				  <div class="padd">
						     			
						        <div class="form-group">
						            <label class="col-lg-4 control-label">Date posted: </label>
						            
						            <div class="col-lg-8">
						            	<div id="datetimepicker1" class="input-append">
						                    <input data-format="yyyy-MM-dd" class="form-control" type="text" id="datepicker" name="visit_date" placeholder="Visit Date" value="<?php echo date('Y-m-d');?>">
						                    <span class="add-on" style="cursor:pointer;">
						                        &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
						                        </i>
						                    </span>
						                </div>
						            </div>
						        </div>

						       
						        
						        <div class="form-group">
						            <label class="col-lg-4 control-label">Schedule: </label>
						            
						            <div class="col-lg-8">
						            	<a onclick="check_date()" style="cursor:pointer;">[Show Doctor's Schedule]</a><br>
						            	<!-- show the doctors -->
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
						            	<!-- the other one -->
						            	<div  id="doctors_schedule"> </div>
						            </div>
						        </div>
						        <div class="form-group">
						            	<label class="col-lg-4 control-label">Start time : </label>
						            
							            <div class="col-lg-8">
										    <div id="datetimepicker2" class="input-append">
						                       <input data-format="hh:mm" class="picker" id="timepicker_start" name="timepicker_start"  type="text" class="form-control">
						                       <span class="add-on" style="cursor:pointer;">
						                         &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
						                         </i>
						                       </span>
						                    </div>
							            </div>
							        </div>
							         <div class="form-group">
							            <label class="col-lg-4 control-label">End time : </label>
							            
							            <div class="col-lg-8">				            
											<div id="datetimepicker3" class="input-append">
						                       <input data-format="hh:mm" class="picker" id="timepicker_end" name="timepicker_end"  type="text" class="form-control">
						                       <span class="add-on" style="cursor:pointer;">
						                         &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
						                         </i>
						                       </span>
						                    </div>
										</div>
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
					
				</div>
			
		</div>
</section>


         
 <script type="text/javascript" src="<?php echo base_url("js/script.js");?>"></script>
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
	function check_department_type(){
		var myTarget = document.getElementById("department_id").value;
		var myTarget2 = document.getElementById("department_type");
		var myTarget3 = document.getElementById("patient_type_div");
		// counseling department div
		var myTarget4 = document.getElementById("counseling_department");
		// end of counseling department div
		
		if(myTarget==7)
		{
		 	myTarget2.style.display = 'block';
		 	myTarget4.style.display = 'none';
		 	myTarget3.style.display = 'none';	
		}
		else if(myTarget==12)
		{
		 	myTarget4.style.display = 'block';
		 	myTarget2.style.display = 'none';
		 	myTarget3.style.display = 'none';
		}
		else{
	 	 	myTarget2.style.display = 'none';
	 	 	myTarget3.style.display = 'block';	
	 	 	myTarget4.style.display = 'none';
		}
	}
	
	$(document).on("change","select#patient_type",function(e){
		var patient_type_id = $(this).val();
		
		if(patient_type_id == '4')
		{
			$('#insured_company').css('display', 'block');
		}
		else
		{
			$('#insured_company').css('display', 'none');
		}
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
	
</script>