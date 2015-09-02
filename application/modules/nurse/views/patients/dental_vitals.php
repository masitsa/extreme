<?php
$dental_vitals = $this->nurse_model->select_current_dental_vitals($visit_id);

if($dental_vitals->num_rows() > 0){
	
	$rs_dental = $dental_vitals->row();
	
	$dental_vitals_id = $rs_dental->dental_vitals_id;
	$visit_major_reason = $rs_dental->visit_major_reason;
	$serious_illness = $rs_dental->serious_illness;
	$serious_illness_xplain = $rs_dental->serious_illness_xplain;
	$treatment = $rs_dental->treatment;
	$treatment_hospital = $rs_dental->treatment_hospital;
	$treatment_doctor = $rs_dental->treatment_doctor;
	$Food_allergies = $rs_dental->Food_allergies;
	$Regular_treatment = $rs_dental->Regular_treatment;
	$Recent_medication = $rs_dental->Recent_medication;
	$Medicine_allergies = $rs_dental->Medicine_allergies;
	$chest_trouble = $rs_dental->chest_trouble;
	$heart_problems = $rs_dental->heart_problems;
	$diabetic = $rs_dental->diabetic;
	$epileptic = $rs_dental->epileptic;
	$rheumatic_fever = $rs_dental->rheumatic_fever;
	$elongated_bleeding = $rs_dental->elongated_bleeding;
	$explain_bleeding = $rs_dental->explain_bleeding;
	$jaundice = $rs_dental->jaundice;
	$hepatitis = $rs_dental->hepatitis;
	$asthma = $rs_dental->asthma;
	$eczema = $rs_dental->eczema;
	$cancer = $rs_dental->cancer;
	$women_pregnant = $rs_dental->women_pregnant;
	$pregnancy_month = $rs_dental->pregnancy_month;
	$additional_infor = $rs_dental->additional_infor;
	$prior_treatment = $rs_dental->prior_treatment;
	$alcohol = $rs_dental->alcohol;
	$smoke = $rs_dental->smoke;
}

else
{
	$dental_vitals_id = "";
	$visit_major_reason = "";
	$serious_illness = "";
	$serious_illness_xplain = "";
	$treatment = "";
	$treatment_hospital = "";
	$treatment_doctor = "";
	$Food_allergies = "";
	$Regular_treatment = "";
	$Recent_medication = "";
	$Medicine_allergies = "";
	$chest_trouble = "";
	$heart_problems = "";
	$diabetic = "";
	$epileptic = "";
	$rheumatic_fever = "";
	$elongated_bleeding = "";
	$explain_bleeding = "";
	$jaundice = "";
	$hepatitis = "";
	$asthma = "";
	$eczema = "";
	$cancer = "";
	$women_pregnant = "";
	$pregnancy_month = "";
	$additional_infor = "";
	$prior_treatment = "";
	$alcohol = "";
	$smoke = "";
}

	echo form_open("nurse/dental_vitals/".$visit_id, array("class" => "form-horizontal"));
	?>
    <div class="row">
        <div class="col-md-6">
        
            <!-- Widget -->
            <div class="widget">
                <!-- Widget head -->
                <div class="widget-head">
                    <h4 class="pull-left"><i class="icon-reorder"></i>Complaints</h4>
                    <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>             
                
                <!-- Widget content -->
                <div class="widget-content">
                	<div class="padd">
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Chief Complaint: </label>
                            
                            <div class="col-lg-8">
                                <textarea name="reason" id="reason" placeholder="Reason for Visit" required class="form-control"><?php echo $visit_major_reason;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Serious Illness or operation: </label>
                            
                            <div class="col-lg-8">
                            	<textarea name="doctor" id="doctor" placeholder="Doctor" class="form-control"><?php echo $treatment_doctor;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label"> </label>
                            
                            <div class="col-lg-8">
                                <textarea name="hospital" id="hospital" placeholder="Hospital" class="form-control"><?php echo $treatment_hospital;?></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
        
            <!-- Widget -->
            <div class="widget">
                <!-- Widget head -->
                <div class="widget-head">
                    <h4 class="pull-left"><i class="icon-reorder"></i>Allergies</h4>
                    <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>             
                
                <!-- Widget content -->
                <div class="widget-content">
                	<div class="padd">
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Food Allergies: </label>
                            
                            <div class="col-lg-8">
                                <textarea id='food_allergies' name='food_allergies' class="form-control"><?php echo $Food_allergies;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Medicine Allergies: </label>
                            
                            <div class="col-lg-8">
            					<textarea id='medicine_allergies' name='medicine_allergies' class="form-control"><?php echo $Medicine_allergies;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Regular Treatment: </label>
                            
                            <div class="col-lg-8">
            					<textarea id='regular_treatment' name='regular_treatment' class="form-control"><?php echo $Regular_treatment;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Recent Medication: </label>
                            
                            <div class="col-lg-8">
            					<textarea id='medication_description' name='medication_description' class="form-control"><?php echo $Recent_medication;?></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row">
        <div class="col-md-6">
        
            <!-- Widget -->
            <div class="widget">
                <!-- Widget head -->
                <div class="widget-head">
                    <h4 class="pull-left"><i class="icon-reorder"></i>Family History</h4>
                    <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>             
                
                <!-- Widget content -->
                <div class="widget-content">
                	<div class="padd">
                    	
                    	<ol id="new-nav"></ol>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
        
            <!-- Widget -->
            <div class="widget">
                <!-- Widget head -->
                <div class="widget-head">
                    <h4 class="pull-left"><i class="icon-reorder"></i>Others</h4>
                    <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>             
                
                <!-- Widget content -->
                <div class="widget-content">
                	<div class="padd">
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Any Serious Illness? </label>
                            
                            <div class="col-lg-8">
            					<select name="illness" id="illness" onChange="toggleFieldh('myTFh','illness')"  class="form-control">
                                	<?php
                                    if($serious_illness == 'YES')
									{
										$se = 'selected';
										$se2 = '';
										$display = 'style="display:block;"';
									}
									else
									{
										$se2 = 'selected';
										$se = '';
										$display = 'style="display:none;"';
									}
									?>
                                    <option value="NO" <?php echo $se2;?>>NO </option>
                                    <option value="YES" <?php echo $se;?>>YES </option>
                                </select>
                            </div>
                        </div>
                        
                    	<div class="form-group" id="myTFh" name="myTFh" <?php echo $display;?>>
                            <label class="col-lg-4 control-label">Explain Illness: </label>
                            
                            <div class="col-lg-8">
            					<textarea id="illness_exp" name="illness_exp" placeholder="Illness Name" class="form-control"><?php echo $serious_illness_xplain;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Is patient pregnant? </label>
                            
                            <div class="col-lg-8">
            					<select name="preg" id="preg" onChange="toggleFieldX('myTFx','preg')" class="form-control">
                                	<?php
                                    if($women_pregnant == 'NA')
									{
										$se = 'selected';
										$se2 = '';
										$se3 = '';
										$display = 'style="display:none;"';
									}
									else if($women_pregnant == 'YES')
									{
										$se = '';
										$se2 = 'selected';
										$se3 = '';
										$display = 'style="display:block;"';
									}
									else
									{
										$se = '';
										$se2 = '';
										$se3 = 'selected';
										$display = 'style="display:none;"';
									}
									?>
                                    <option value="NA" <?php echo $se;?>>NOT APPLICABLE </option>
                                    <option value="YES" <?php echo $se2;?>>YES </option>
                                    <option value="NO" <?php echo $se3;?>>NO </option>
                                </select>
                            </div>
                        </div>
                        
                    	<div class="form-group" id="myTFx" name="myTFx" <?php echo $display;?>>
                            <label class="col-lg-4 control-label">How far Along (Months)? </label>
                            
                            <div class="col-lg-8">
            					<textarea id="months" name="months" placeholder="How far Along Months" class="form-control"><?php echo $pregnancy_month;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Additional Information: </label>
                            
                            <div class="col-lg-8">
            					<textarea name="additional" class="form-control" placeholder="Additional Information"><?php echo $additional_infor;?></textarea>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Have You been Dissatisfied With Previous Treatment? </label>
                            
							<?php
                            if($prior_treatment == 'YES')
                            {
                                $se = ' checked="checked"';
                                $se2 = '';
                            }
                            else
                            {
                                $se2 = ' checked="checked"';
                                $se = '';
                            }
                            ?>
                            <div class="col-lg-4">
            					<input name="prior_treatment" type="radio" value="YES" <?php echo $se;?>> <strong> Yes</strong>
                            </div>
                            <div class="col-lg-4">
                                <input name="prior_treatment" type="radio" value="NO" <?php echo $se2;?>> <strong>No </strong>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Alcohol Consupmtion? </label>
                            
							<?php
                            if($alcohol == 'YES')
                            {
                                $se = ' checked="checked"';
                                $se2 = '';
                            }
                            else
                            {
                                $se2 = ' checked="checked"';
                                $se = '';
                            }
                            ?>
                            
                            <div class="col-lg-4">
            					<input name="alcohol" type="radio" value="YES" <?php echo $se;?>> <strong> Yes</strong>
                            </div>
                            <div class="col-lg-4">
                                <input name="alcohol" type="radio" value="NO" <?php echo $se2;?>> <strong>No </strong>
                            </div>
                        </div>
                        
                    	<div class="form-group">
                            <label class="col-lg-4 control-label">Smoke? </label>
                            
							<?php
                            if($smoke == 'YES')
                            {
                                $se = ' checked="checked"';
                                $se2 = '';
                            }
                            else
                            {
                                $se2 = ' checked="checked"';
                                $se = '';
                            }
                            ?>
                            
                            <div class="col-lg-4">
            					<input name="smoke" type="radio" value="YES" <?php echo $se;?>> <strong> Yes</strong>
                            </div>
                            <div class="col-lg-4">
                                <input name="smoke" type="radio" value="NO" <?php echo $se2;?>> <strong>No </strong>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="alert alert-warning">Kindly Let your Dentist Know if you have any new illness, or if any of the above details change durring the course of treatment</div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if($dental_vitals->num_rows() > 0){ ?>
    <div class="center-align">
    	<input type="hidden" name="dental_vitals_id" value="<?php echo $dental_vitals_id;?>"/>
    	<input type="submit" name="update" class="btn btn-large btn-info" align="center" value="Update"/>
        <input type="submit" name="update1" align="center" class="btn btn-large btn-success"value="Update & Send To Dentist"/>
    </div>
    <?php } 
	
	else{ ?>
    <div class="center-align">
    	<input type="submit" name="submit" id="submit" class="btn btn-large btn-info" align="center" value="Save"/>
        <input type="submit" name="submit1" id="submit1" align="center" class="btn btn-large btn-success"value="Save & Send To Dentist"/>
    </div>
    <?php } ?>
<script>
		
	
	function toggleFieldh(myTFh,illness) 
	{
		var myTarget = document.getElementById(myTFh);
		var illness = document.getElementById(illness).value;
		
		if((myTarget.style.display == 'none')&&(illness=='YES')){
		  myTarget.style.display = 'block';
		} 
		else {
		  myTarget.style.display = 'none';
		  myTarget.value = '';
		}
	}
	
	function toggleFieldX(myTFx,preg) {
		var myTarget = document.getElementById(myTFx);
		var pregnant = document.getElementById(preg).value;
		//alert(pregnant);
		if((myTarget.style.display == 'none')&&(pregnant=='YES')){
		  myTarget.style.display = 'block';
		} 
		else {
		  myTarget.style.display = 'none';
		  myTarget.value = '';
		}
	}
	
</script>