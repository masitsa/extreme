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
				$visit_data1['mobile_personnel_id'] = $mobile_personnel_id;
				
				?>
				<?php echo $this->load->view('nurse/soap/nurse_notes',$visit_data1, TRUE); ?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>