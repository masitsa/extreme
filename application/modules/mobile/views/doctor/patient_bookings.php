<!-- search -->
 <section class="panel panel-default">
    <header class="panel-heading">
        <h2 class="panel-title">Patient Bookings Search</h2>
    </header>
    <div class="panel-body">
        <div class="row">
        	<div class="col-md-4">
            	<?php echo form_open('doctor/filter_timesheets', array(''));?>
                <div class="row">
                    <div class="col-md-12" style="margin-bottom:15px;">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Date from: </label>
                            
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date_from" placeholder="Date" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8 col-md-offset-4">
                    	<div class="center-align">
                        	<?php 
							
							$bookings_search = $this->session->userdata('bookings_search');
							
							if(!empty($bookings_search))
							{
								?>
                                <a href="<?php echo site_url().'doctor/close_bookings_search';?>" class="btn btn-success">Close filter</a>
                                <?php
							}
							
							?>
                        	<button type="submit" class="btn btn-info">Filter</button>
                        </div>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
            
            <div class="col-md-4">
            	<section class="panel panel-featured-left panel-featured-primary">
                    <div class="panel-body">
                        <div class="widget-summary">
                            <div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-primary">
                                    <i class="fa fa-life-ring"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Patients seen</h4>
                                    <div class="info">
                                        <strong class="amount"><?php echo number_format($total_patients, 0);?></strong>
                                    </div>
                                </div>
                                <div class="summary-footer">
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            
            <div class="col-md-4">
            	<section class="panel panel-featured-left panel-featured-secondary">
                    <div class="panel-body">
                        <div class="widget-summary">
                            <div class="widget-summary-col widget-summary-col-icon">
                                <div class="summary-icon bg-secondary">
                                    <i class="fa fa-usd"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">Total due</h4>
                                    <div class="info">
                                        <strong class="amount">Kes <?php echo number_format($total_due, 2);?></strong>
                                    </div>
                                </div>
                                <div class="summary-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        
    </div>
</section>
<div class="row">
    <div class="col-md-12">
		<!-- Widget -->
		<section class="panel">
			<!-- Widget head -->
			<header class="panel-heading">
				<h2 class="panel-title">Patient Bookings</h2>
			</header>             

			<!-- Widget content -->
			<div class="panel-body">
          	<?php
		
			if($query->num_rows() > 0)
			{
			$count = 0;
			
				echo  
					'
					<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Visit Date</th>
							  <th>Patient Name</th>
							  <th>Time In</th>
							  <th>Branch Code</th>
							</tr>
						</thead>
						<tbody>
					';
				$result = $query->result();
				$grand_total = 0;
				$patients_total = 0;
				
				foreach($result as $res)
				{
					$patient_id = $res->patient_id;
					$patient_surname = $res->patient_surname;
					$patient_othernames = $res->patient_othernames;
					$patient_number = $res->patient_number;
					$booking_datetime = $res->booking_datetime;
					$branch_code = $res->branch_code;
					$booking_date = $res->booking_date;
					$collected_amount = $res->collected_amount;

					$booking_date = date('jS M Y',strtotime($booking_date));
					$booking_datetime = date('H:i a',strtotime($booking_datetime));

					$grand_total = $grand_total+$collected_amount;


					$count++;
					
					
					echo '
						<tr>
							<td>'.$count.'</td>
							<td>'.$booking_date.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$booking_datetime.'</td>
							<td>'.$branch_code.'</td>
							<td>'.number_format($collected_amount,2).'</td>
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