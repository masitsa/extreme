<div class="row">
	<?php if ($module == 0){?>
        <div class="col-md-4">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_doctor/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-primary" value="Send To Doctor"/>
            <?php echo form_close();?>
          </div>
          
        </div>
    <?php } ?>
        <div class="col-md-4">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_pharmacy/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_labs/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory"/>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $patient;?> Patient Card</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
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

			<div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs">
                <li class="active" ><a href="#dental-vitals" data-toggle="tab">Dental Vitals</a></li>
                <li><a href="#patient-history" data-toggle="tab">Patient history</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
            
                
                <div class="tab-pane active" id="dental-vitals">

                  <?php echo $this->load->view("patients/vitals", '', TRUE);?>

                  <?php echo $this->load->view("patients/dental_vitals", '', TRUE);?>
                 
                </div>
                
                <div class="tab-pane" id="patient-history">
                  
                  <?php echo $this->load->view("patient_history", '', TRUE);?>
                  
                </div>
                
              </div>
            </div>

              <div class="row">
               
	<?php if ($module == 0){?>
        <div class="col-md-4">
          <div class="center-align">
            <?php echo form_open("nurse/send_to_doctor/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-primary" value="Send To Doctor"/>
            <?php echo form_close();?>
          </div>
          
        </div>
    <?php } ?>
                <div class="col-md-4">
                  <div class="center-align">
                    <?php echo form_open("nurse/send_to_pharmacy/".$visit_id, array("class" => "form-horizontal"));?>
                      <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy"/>
                    <?php echo form_close();?>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="center-align">
                    <?php echo form_open("nurse/send_to_labs/".$visit_id, array("class" => "form-horizontal"));?>
                      <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory"/>
                    <?php echo form_close();?>
                  </div>
                </div>
              </div>

          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>
  
  <script type="text/javascript">
  	
	var config_url = $("#config_url").val();
		
	$(document).ready(function(){
		
	  	$.get( config_url+"/nurse/get_family_history/<?php echo $visit_id;?>", function( data ) {
			$("#new-nav").html(data);
			$("#checkup_history").html(data);
		});
	});
  </script>

