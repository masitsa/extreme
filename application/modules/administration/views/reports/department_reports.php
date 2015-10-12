<!-- search -->
<?php echo $this->load->view('search/departments', '', TRUE);?>
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
		
			if($services_result->num_rows() > 0)
			{
			$count = 0;
			
				echo  
					'
						<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Department</th>
							  <th>Total Collection</th>
							</tr>
						</thead>
						<tbody>
					';
				$result = $services_result->result();
				$grand_total = 0;
				
				foreach($result as $res)
				{
					$service_id = $res->service_id;
					$service_name = $res->service_name;
					$count++;
					
					//get service total
					$total = $this->reports_model->get_service_total($service_id);
					$grand_total += $total;
					
					echo '
						<tr>
							<td>'.$count.'</td>
							<td>'.$service_name.'</td>
							<td>'.number_format($total, 0).'</td>
						</tr>
					';
				}
				
				echo 
				'
					
						<tr>
							<td colspan="2">Total</td>
							<td>'.number_format($grand_total, 0).'</td>
						</tr>
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