<?php if($mike == 1){

}else{?>
<section class="panel">
<div class="row">

	<?php if ($module == 0){?>
        <div class="col-md-2">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_doctor/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-primary" value="Send To Doctor" onclick="return confirm('Send to Doctor?');"/>
            <?php echo form_close();?>
          </div>
          
        </div>
    <?php } ?>
        <div class="col-md-2">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_pharmacy/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy" onclick="return confirm('Send to Pharmacy?');"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="center-align">
           <?php echo form_open("nurse/send_to_labs/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory" onclick="return confirm('Send to Laboratory?');"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_accounts/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-danger center-align" value="Send To Accounts" onclick="return confirm('Send to Accounts?');"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_xray/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-primary" value="Send To Xray" onclick="return confirm('Send to X-Ray?');"/>
            <?php echo form_close();?>
          </div>
          
        </div>
        <div class="col-md-2">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_ultrasound/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-default" value="Send To Ultrasound" onclick="return confirm('Send to Ultrasound?');"/>
            <?php echo form_close();?>
          </div>
          
        </div>

    </div>
  </section>
 <?php } ?>
<!-- <div class="row">
    <div class="col-md-12">
       <div class="alert alert-danger">The process of keying in patient vitals has been changed from auto saving to a manual button saving. Please find a button named  <a hred="#" class="btn btn-sm btn-success" >Save Vitals</a> to save the keyed in vitals. The next row will display the vitals you have keyed in. ~ development team </div>
    </div>
</div> -->

 <section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">Patient card</h2>
	</header>
	
	<!-- Widget content -->
	
	<div class="panel-body">
		
		<div class="well well-sm info">
			<h5 style="margin:0;">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>First name:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $patient_surname;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-6">
								<strong>Other names:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $patient_othernames;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>Gender:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $gender;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>Age:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $age;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-6">
								<strong>Account balance:</strong>
							</div>
							<div class="col-lg-6">
								Kes <?php echo number_format($account_balance, 2);?>
							</div>
						</div>
					</div>
				</div>
			</h5>
		</div>
        
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
         <div class="clearfix"></div>
          </div>

          <?php echo $this->load->view("allergies_brief", '', TRUE);?>
        
           <div class="clearfix"></div>

			     <div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#vitals-pane" data-toggle="tab">Vitals</a></li>
                <li><a href="#lifestyle" data-toggle="tab">Lifestyle</a></li>
                <?php if($mike == 1){

                }else{?>
                <li><a href="#previous-vitals" data-toggle="tab">Previous Vitals</a></li>
                <li><a href="#patient-history" data-toggle="tab">Patient history</a></li>
                <?php
                }
                ?>
                <li><a href="#soap" data-toggle="tab">SOAP</a></li>
                <li><a href="#medical-checkup" data-toggle="tab">Medical Checkup</a></li>
                <li><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="vitals-pane">
                  	<?php echo $this->load->view("patients/vitals", '', TRUE);?>
                </div>
               
                
                <div class="tab-pane" id="lifestyle">
                	<?php echo $this->load->view("patients/lifestyle", '', TRUE); ?>
                </div>
                <?php
                if($mike == 1){

                }else{?>
                 <div class="tab-pane" id="previous-vitals">
                  
                  <?php echo $this->load->view("patient_previous_vitals", '', TRUE);?>
                  
                </div>
                <div class="tab-pane" id="patient-history">
                  
                  <?php echo $this->load->view("patient_history", '', TRUE);?>
                  
                </div>
                <?php
                }
                ?>

                 <div class="tab-pane" id="soap">
                  
                  <?php echo $this->load->view("patients/soap", '', TRUE);?>
                  
                </div>

                 <div class="tab-pane" id="medical-checkup">
                  
                  <?php echo $this->load->view("patients/medical_checkup", '', TRUE);?>
                  
                </div>

                 <div class="tab-pane" id="visit_trail">
                  
                  <?php echo $this->load->view("patients/visit_trail", '', TRUE);?>
                  
                </div>
                
              </div>
            </div>

              <?php if($mike == 1){

              }else{?>
              <div class="row">
                <?php if ($module == 0){?>
                    <div class="col-md-2">
                      <div class="center-align">
                        <?php echo form_open("nurse/send_to_doctor/".$visit_id, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-primary" value="Send To Doctor" onclick="return confirm('Send to Doctor?');"/>
                        <?php echo form_close();?>
                      </div>
                      
                    </div>
                <?php } ?>
                    <div class="col-md-2">
                      <div class="center-align">
                        <?php echo form_open("nurse/send_to_pharmacy/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy" onclick="return confirm('Send to Pharmacy?');"/>
                        <?php echo form_close();?>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="center-align">
                       <?php echo form_open("nurse/send_to_labs/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory" onclick="return confirm('Send to Laboratory?');"/>
                        <?php echo form_close();?>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="center-align">
                        <?php echo form_open("nurse/send_to_accounts/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-danger center-align" value="Send To Accounts" onclick="return confirm('Send to Accounts?');"/>
                        <?php echo form_close();?>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="center-align">
                        <?php echo form_open("nurse/send_to_xray/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-primary" value="Send To Xray" onclick="return confirm('Send to X-Ray?');"/>
                        <?php echo form_close();?>
                      </div>
                      
                    </div>
                    <div class="col-md-2">
                      <div class="center-align">
                        <?php echo form_open("nurse/send_to_ultrasound/".$visit_id."/".$module, array("class" => "form-horizontal"));?>
                          <input type="submit" class="btn btn-large btn-default" value="Send To Ultrasound" onclick="return confirm('Send to Ultrasound?');"/>
                        <?php echo form_close();?>
                      </div>
                      
                    </div>

                    </div>
                 <?php } ?>
              

          </div>
        
  </section>
  
  <script type="text/javascript">
  	var config_url = document.getElementById("config_url").value;

	$(document).ready(function(){
	 	var config_url = document.getElementById("config_url").value;
   
	  	$.get(config_url+"nurse/get_family_history/<?php echo $visit_id;?>", function( data ) {
			$("#new-nav").html(data);
			$("#checkup_history").html(data);
		});
	});
  </script>

