<?php
?>
<section class ="panel">
	 <div class="row">
        <header class="panel-heading">
    	<h2 class="panel-title">Add Timesheet</h2>
    	</header>    
     </div>
    <div class="row">
    <?php echo form_open('timesheets/add-timesheet/');?>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-lg-12 ">Date</label>
                <div class="col-lg-12">
                     <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date" placeholder="Date" value="<?php echo set_value('date');?>" />
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-lg-12 ">Start Time</label>
                <div class="col-lg-12">
                     <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </span>
                        <input type="text" class="form-control" data-plugin-timepicker="" name="start_time">
                     </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="col-lg-12 ">End Time</label>
                <div class="col-lg-12">
                     <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </span>
                        <input type="text" class="form-control" data-plugin-timepicker="" name="end_time">
                     </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
			                <label class="col-lg-12 control-label">Task Description</label>
			                <div class="col-lg-12">
                              <textarea id="request_instructions" class="cleditor" rows="10" name="description"><?php echo set_value('description');?></textarea>
			                </div>
			            </div>
        </div>
        &nbsp;
        <div class="center-align">
             <button class="btn btn-primary btn-sm" type="submit">Add TimeSheet</button>
        </div>
        <?php echo form_close();?>
    </div>
        &nbsp;
        <div class="panel-body">
     	<?php
			$personnel_id = $this->session->userdata('personnel_id');
			$personnel_timesheet_query = $this->personnel_model->get_personnel_timesheet($personnel_id);
			//var_dump ($request_event_personnel_query);die();
				$result ='';
				if($personnel_timesheet_query->num_rows() > 0)
				{	
					$result .= 
					'
					<div class="row">
						<div class="col-md-12">
							<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
							  <thead>
								<tr>
								  <th>Personnel Name</th>
								  <th>Date</th>
								  <th>Start Time</th>
								  <th>End Time</th>
								  <th>Tasks Done</th>
								  
								</tr>
							  </thead>
							  <tbody>
							';
									
					foreach($personnel_timesheet_query->result() as $personnel_timesheet)
					{
						$personnel_fname = $personnel_timesheet->personnel_fname;
						$personnel_onames=$personnel_timesheet->personnel_onames;
						$date = $personnel_timesheet->timesheet_date;
						$start_time = $personnel_timesheet->start_time;
						$end_time = $personnel_timesheet->end_time;
						$description = $personnel_timesheet->tasks_done;
						
						$result .='
							<tr>
								<td>'.$personnel_fname.' '.$personnel_onames.'</td>
								<td>'.$date.'</td>
								<td>'.$start_time.'</td>
								<td>'.$end_time.'</td>
								<td>'.$description.'</td>
							</tr>
								';
					}
					$result .= '
								</tbody>
							</table>
							';
							echo $result;
				}
						?>  
               </div>      
</section>