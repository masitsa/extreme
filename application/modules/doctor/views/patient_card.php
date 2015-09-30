<?php
$data['visit_id'] = $visit_id;
?>
<section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title"Patient card</h2>
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
        	<h4>Visit date: <?php echo $visit_date;?></h4>
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
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#vitals-pane" data-toggle="tab">Vitals</a></li>
                <li><a href="#lifestyle" data-toggle="tab">Lifestyle</a></li>
                <li><a href="#previous-vitals" data-toggle="tab">Previous Vitals</a></li>
                <li><a href="#soap" data-toggle="tab">SOAP</a></li>
            </ul>
            <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="vitals-pane">
                  <?php echo $this->load->view("patients/vitals", '', TRUE);?>
                </div>
                
                <div class="tab-pane" id="lifestyle">
                	<?php echo $this->load->view("nurse/patients/lifestyle", '', TRUE); ?>
                </div>
                <div class="tab-pane" id="previous-vitals">
                  
                  <?php echo $this->load->view("nurse/patient_previous_vitals", '', TRUE);?>
                  
                </div>
                <div class="tab-pane" id="soap">
                  
                  <?php echo $this->load->view("patients/soap", '', TRUE);?>
                  
                </div>
                
            </div>
        </div>

    </div>
        
</section>

<script type="text/javascript">
	$(document).ready(function(){
		$('button').css('display', 'none');
		$('#soap div.alert').css('display', 'none');
		$('#vitals-pane #nurses-notes-div').css('display', 'none');
		$('a.btn').css('display', 'none');
		$('input.btn').css('display', 'none');
		$('input').prop('disabled', true);
		$('textarea').prop('disabled', true);
		$('select').prop('disabled', true);
	});
</script>