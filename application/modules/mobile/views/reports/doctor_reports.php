<!-- search -->
<?php echo $this->load->view('search/doctors', '', TRUE);?>
<div class="row">
    <div class="col-md-12">
		<!-- Widget -->
		<section class="panel">


			<!-- Widget head -->
			<header class="panel-heading">
				<h2 class="panel-title"><?php echo $title;?></h2>
			</header>             

			<!-- Widget content -->
			<div class="panel-body">
          	<?php
		
			if($doctor_results->num_rows() > 0)
			{
			$count = 0;
			
				echo  
					'
						<a href="'.site_url().'/administration/reports/doctor_reports_export/'.$date_from.'/'.$date_to.'" class="btn btn-success">Export</a>
						<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Doctor\'s name</th>
							  <th>Total collection</th>
							  <th>Patients seen</th>
							  <th>Full amount</th>
							  <th>30%</th>
							  <th>500/hr</th>
							  <th>100/d</th>
							  <th>Actions</th>
							</tr>
						</thead>
						<tbody>
					';
				$result = $doctor_results->result();
				$grand_total = 0;
				$patients_total = 0;
				
				foreach($result as $res)
				{
					$personnel_id = $res->personnel_id;
					$personnel_onames = $res->personnel_onames;
					$personnel_fname = $res->personnel_fname;
					$personnel_type_id = $res->personnel_type_id;
					$count++;
					
					//get service total
					$total = $this->reports_model->get_total_collected($personnel_id, $date_from, $date_to);
					$patients = $this->reports_model->get_total_patients($personnel_id, $date_from, $date_to);
					$grand_total += $total;
					$patients_total += $patients;
					
					//consultant
					if($personnel_type_id == 2)
					{
						$full = $total;
						$percentage = 0;
						$hourly = 0;
						$daily = 0;
					}
					
					//radiographer
					elseif($personnel_type_id == 3)
					{
						$percentage = 0.3 * $total;
						$full = 0;
						$hourly = 0;
						$daily = 0;
					}
					
					//medical officer
					elseif($personnel_type_id == 4)
					{
						$hours_worked = $this->reports_model->calculate_hours_worked($personnel_id, $date_from, $date_to);
						$hourly = 500 * $hours_worked;
						$full = 0;
						$percentage = 0;
						$daily = 0;
					}
					
					//clinic officer
					elseif($personnel_type_id == 5)
					{
						$days_worked = $this->reports_model->calculate_days_worked($personnel_id, $date_from, $date_to);
						$daily = 1000 * $days_worked;
						$full = 0;
						$percentage = 0;
						$hourly = 0;
					}
					
					echo '
						<tr>
							<td>'.$count.'</td>
							<td>'.$personnel_fname.' '.$personnel_onames.'</td>
							<td>'.number_format($total, 2).'</td>
							<td>'.$patients.'</td>
							<td>'.number_format($full, 2).'</td>
							<td>'.number_format($percentage, 2).'</td>
							<td>'.number_format($hourly, 2).'</td>
							<td>'.number_format($daily, 2).'</td>
							<td>
								<a href="'.site_url().'/administration/reports/doctor_patients_export/'.$personnel_id.'/'.$date_from.'/'.$date_to.'" class="btn btn-success btn-sm">Patients</a>
							</td>
						</tr>
					';
				}
				
				echo 
				'
					
						<tr>
							<td colspan="2">Total</td>
							<td><span class="bold">'.number_format($grand_total, 2).'</span></td>
							<td><span class="bold" >'.$patients_total.' patients</span></td>
							<td> <span class="bold">'.number_format(0,2).'</span></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				';
			}
			?>
       		</div>
			<div class="widget-foot">
								
				<?php if(isset($links)){echo $links;}?>
			
				<div class="clearfix"></div> 
			
			</div>
		</section>
	</div>
</div>