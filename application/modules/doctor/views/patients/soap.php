<?php
$data['visit_id'] = $visit_id;
?>
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Symptoms</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("nurse/soap/view_symptoms", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Objective findings</h2>
			</header>

			<div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/soap/view_objective_findings", $data, TRUE); ?>
                <!-- end of visit procedures -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Assesment</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("nurse/soap/view_assessment", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Plan</h2>
			</header>

			<div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/soap/view_plan", $data, TRUE); ?>
                <div id="plan"></div>
                <!-- end of visit procedures -->
                <div id='visit_diagnosis_original'></div>
            </div>
		</section>
    </div>
</div>

<?php echo $this->load->view("laboratory/tests/test2", $data, TRUE); ?>

<?php echo $this->load->view("radiology/tests/test2", $data, TRUE); ?>

<?php echo $this->load->view("radiology/tests_ultrasound/test2", $data, TRUE); ?>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Prescription</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("pharmacy/display_prescription", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Doctor's notes</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("nurse/soap/doctor_notes", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>