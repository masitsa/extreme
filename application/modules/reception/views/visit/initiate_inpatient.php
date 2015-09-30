<style type="text/css">
	#insured_company2{display:none;}
</style>
						<?php 
                        $validation_error = validation_errors();
                        
                        if(!empty($validation_error))
                        {
                            echo '<div class="alert alert-danger center-align">'.$validation_error.'</div>';
                        }
                        ?>
                        <?php echo form_open("reception/save_inpatient_visit/".$patient_id, array("class" => "form-horizontal"));?>
                        <div class="row">
                        	<div class="col-md-6">
								
                                <div class="form-group">
									<label class="col-lg-4 control-label">Visit type: </label>
									
									<div class="col-lg-8">
										<select name="visit_type_id" id="visit_type_id2" class="form-control">
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
								
								<div id="insured_company2">
                                    
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
									<label class="col-lg-4 control-label">Ward: </label>
									
									<div class="col-lg-8">
										<select name="ward_id" id="ward_id" class="form-control" onchange="check_department_type()">
											<option value="">----Select a ward----</option>
											<?php
																	
												if($wards->num_rows() > 0){

													foreach($wards->result() as $row):
														$ward_name = $row->ward_name;
														$ward_id = $row->ward_id;

														
														if($ward_id == set_value('ward_id'))
														{
															echo "<option value='".$ward_id."' selected='selected'>".$ward_name."</option>";
														}
														
														else
														{
															echo "<option value='".$ward_id."'>".$ward_name."</option>";
														}
													endforeach;
												}
											?>
										</select>
									</div>
								</div>
								
								<input type="hidden" name="patient_type_id" value="<?php echo $patient_type_id;?>">
							</div>
							<!--end left -->
							<!-- start right -->
							<div class="col-md-6">
                                 
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
									<label class="col-lg-4 control-label">Admission date: </label>
									
									<div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date" placeholder="Visit Date" value="<?php echo date('Y-m-d');?>">
                                        </div>
									</div>
								</div>
							</div>
							<!-- end right -->
						</div>
						<!-- end of row -->
						<div class="center-align" style="margin-top:10px;">
							<input type="submit" value="Initiate Visit" class="btn btn-info btn-sm"/>
						</div>
					<?php echo form_close();?>
					 <!-- end of form -->
            
 <script type="text/javascript" charset="utf-8">
 	$(document).ready(function(){
      	do_patient_type_function(<?php echo $patient_type_id;?>);
      
  	});
	
	$(document).on("change","select#visit_type_id2",function(e)
	{
		var visit_type_id = $(this).val();
		
		if(visit_type_id != '1')
		{
			$('#insured_company2').css('display', 'block');
			// $('#consultation').css('display', 'block');
		}
		else
		{
			$('#insured_company2').css('display', 'none');
			// $('#consultation').css('display', 'block');
		}
	});
	
	function do_patient_type_function(patient_type_id)
	{
		if(patient_type_id == '4')
		{
			$('#insured_company2').css('display', 'block');
			// $('#consultation').css('display', 'block');
		}
		else
		{
			$('#insured_company2').css('display', 'none');
			// $('#consultation').css('display', 'block');
		}
	}
	
</script>