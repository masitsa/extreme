<?php
$data['visit_id'] = $visit_id;
?>
<div class="row">
	<div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-info">
                    <header class="panel-heading">
                        <h2 class="panel-title">Vitals</h2>
                    </header>
                    <div class="panel-body">
                        <!-- vitals from java script -->
                        <?php echo $this->load->view("nurse/show_previous_vitals", $data, TRUE);?>
                        <!-- end of vitals data -->
                    </div>
                 </section>
            </div>
        </div>
    </div>
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/view_procedure", $data, TRUE);?>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
    <div class="col-md-12">
    	<section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Vaccines</h2>
            </header>
            <div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/visit_vaccines/vaccines_assigned", $data, TRUE);?>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Allergies</h2>
			</header>
			<div class="panel-body">
				<?php
				$patient_id = $this->nurse_model->get_patient_id($visit_id);
				
				$get_medical_rs = $this->nurse_model->get_medicals($patient_id);
				$num_rows = count($get_medical_rs);
				//echo $num_rows;
				echo "";
				if($num_rows > 0){
					foreach ($get_medical_rs as $key):
						$food_allergies = $key->food_allergies;
						$medicine_allergies = $key->medicine_allergies;
						$regular_treatment = $key->regular_treatment;
						$recent_medication = $key->medication_name;
					endforeach;
					echo "
					 <div class='row'>
							<div class='col-md-6'>
								<div class='form-group'>
									<label class='col-lg-4 control-label'>Food Allergies: </label>
									
									<div class='col-lg-8'>
										<p>".$food_allergies."</p>
									</div>
								</div>
								<div class='form-group'>
									<label class='col-lg-4 control-label'>Medicine Allergies: </label>
									
									<div class='col-lg-8'>
										<p>".$medicine_allergies."</p>
									</div>
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'>
									<label class='col-lg-4 control-label'>Regular Treatment: </label>
									
									<div class='col-lg-8'>
										<p>".$regular_treatment."</p>
									</div>
								</div>
								<div class='form-group'>
									<label class='col-lg-4 control-label'>Recent Medication: </label>
									
									<div class='col-lg-8'>
										<p>".$recent_medication."</p>
									</div>
								</div>
							</div>
					  </div>
				";
				}
				
				else
				{
					echo '<p>No allergies have been recorded</p>';
				}
?>
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Surgeries</h2>
			</header>
			<div class="panel-body">
				<?php
				$patient_id = $this->nurse_model->get_patient_id($visit_id);
				
				$surgery_rs = $this->nurse_model->get_surgeries($patient_id);
				$num_surgeries = count($surgery_rs);
				
				echo
				"
					<table align='center' class='table table-striped table-hover table-condensed'>
						<tr>
							<th>Year</th>
							<th>Month</th>
							<th>Description</th>
						</tr>
				";
				if($num_surgeries > 0){
					foreach ($surgery_rs as $key):
						$date = $key->surgery_year;
						$month = $key->month_name;
						$description = $key->surgery_description;
						$id = $key->surgery_id;
						echo
						"
								<tr>
									<td>".$date."</td>
									<td>".$month."</td>
									<td>".$description."</td>
								</tr>
						";
					endforeach;
				}
				
				echo '</table>';
				?>
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Patient vaccine history</h2>
			</header>
			<div class="panel-body">
				<!-- vitals from java script -->
				<?php echo $this->load->view("nurse/patient_vaccine", $data, TRUE);?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Family history</h2>
			</header>
			<div class="panel-body">
				<?php
					$v_data['patient_id'] = $this->reception_model->get_patient_id_from_visit($visit_id);
					$v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
					$v_data['family_disease_query'] = $this->nurse_model->get_family_disease();
					$v_data['family_query'] = $this->nurse_model->get_family();
				?>
				<!-- vitals from java script -->
				<?php echo $this->load->view("nurse/patients/family_history", $v_data, TRUE); ?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Nurse notes</h2>
			</header>

			<div class="panel-body">
				<!-- vitals from java script -->
				<?php
				$visit_data1['visit_id'] = $visit_id;
				?>
				<?php echo $this->load->view('nurse/soap/nurse_notes',$visit_data1, TRUE); ?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>