<?php
if($mike == 0){
?>
  <div class="row"> 
  <div class="center-align"> 
     <div class="col-md-3">
          <div class="center-align">
            <?php echo form_open("dental/send_to_pharmacy/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="center-align">
           <?php echo form_open("dental/send_to_labs/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory"/>
            <?php echo form_close();?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="center-align">
            <?php echo form_open("dental/send_to_accounts/".$visit_id, array("class" => "form-horizontal"));?>
              <input type="submit" class="btn btn-large btn-danger center-align" value="Send To Accounts"/>
            <?php echo form_close();?>
          </div>
        </div>
      </div>
  </div>
<?php
}else{

}
?>
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $patient;?> </h4>
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
      <?php echo $this->load->view("nurse/allergies_brief", '', TRUE);?>
        
       <div class="clearfix"></div>

			<div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#vitals-pane" data-toggle="tab">Vitals</a></li>
                <?php if($mike == 1){

                }else{?>
                <li><a href="#previous-vitals" data-toggle="tab">Vital Trend</a></li>
                <li><a href="#patient-history" data-toggle="tab">Patient history</a></li>
                <?php
                }
                ?>
                <li><a href="#billing" data-toggle="tab">Billing</a></li>
                <li><a href="#soap" data-toggle="tab">SOAP</a></li>
                <li><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="vitals-pane">
                  <?php echo $this->load->view("nurse/patients/vitals", '', TRUE);?>
                </div>
               
                <?php
                if($mike == 1){

                }else{?>
                 <div class="tab-pane" id="previous-vitals">
                  
                  <?php echo $this->load->view("nurse/patient_previous_vitals", '', TRUE);?>
                  
                </div>
                <div class="tab-pane" id="patient-history">
                  
                  <?php echo $this->load->view("nurse/patient_history", '', TRUE);?>
                  
                </div>
                <?php
                }
                ?>

                 <div class="tab-pane" id="billing">
                  
                    <?php echo $this->load->view("billing", '', TRUE);?>
                  </div>
                   <div class="tab-pane" id="soap">
               
                    <?php echo $this->load->view("nurse/patients/soap", '', TRUE);?>
                  </div>

                

                 <div class="tab-pane" id="visit_trail">
                  
                  <?php echo $this->load->view("nurse/patients/visit_trail", '', TRUE);?>
                  
                </div>
                
              </div>
            </div>
              

          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>
  <div class="row">
      <div class="center-align"> 
         <div class="col-md-3">
              <div class="center-align">
                <?php echo form_open("dental/send_to_pharmacy/".$visit_id, array("class" => "form-horizontal"));?>
                  <input type="submit" class="btn btn-large btn-warning center-align" value="Send To Pharmacy"/>
                <?php echo form_close();?>
              </div>
            </div>
            <div class="col-md-3">
              <div class="center-align">
               <?php echo form_open("dental/send_to_labs/".$visit_id, array("class" => "form-horizontal"));?>
                  <input type="submit" class="btn btn-large btn-success center-align" value="Send To Laboratory"/>
                <?php echo form_close();?>
              </div>
            </div>
            <div class="col-md-3">
              <div class="center-align">
                <?php echo form_open("dental/send_to_accounts/".$visit_id, array("class" => "form-horizontal"));?>
                  <input type="submit" class="btn btn-large btn-danger center-align" value="Send To Accounts"/>
                <?php echo form_close();?>
              </div>
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

