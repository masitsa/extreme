<?php
	$result = '';
	if($jobs->num_rows() > 0)
	{
		$count = 0;
			
		$result .= 
		'
		<br/>
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Position</th>
					<th>Department</th>
					<th>Start date</th>
					<th>Last editted</th>
					<th colspan="3">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($jobs->result() as $row)
		{
			$personnel_job_id = $row->personnel_job_id;
			$job = $row->job_title_name;
			$personnel_job_status = $row->personnel_job_status;
			$department_name = $row->department_name;
			$job_commencement_date =  date('jS M Y ',strtotime($row->job_commencement_date));
			$created = date('jS M Y H:i a',strtotime($row->created));
			$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
			
			//create deactivated status display
			if($personnel_job_status == 0)
			{
				$status = '<span class="label label-danger">Deactivated</span>';
				$button = '<a class="btn btn-sm btn-info" href="'.site_url().'human-resource/activate-position/'.$personnel_job_id.'/'.$personnel_id.'" onclick="return confirm(\'Do you want to activate '.$job.'?\');" title="Activate '.$job.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($personnel_job_status == 1)
			{
				$status = '<span class="label label-success">Active</span>';
				$button = '<a class="btn btn-sm btn-default" href="'.site_url().'human-resource/deactivate-position/'.$personnel_job_id.'/'.$personnel_id.'" onclick="return confirm(\'Do you want to deactivate '.$job.'?\');" title="Deactivate '.$job.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$job.'</td>
					<td>'.$department_name.'</td>
					<td>'.$job_commencement_date.'</td>
					<td>'.$last_modified.'</td>
					<td>'.$status.'</td>
					<td>'.$button.'</td>
					<td><a href="'.site_url().'human-resource/delete-personnel-job/'.$personnel_job_id.'/'.$personnel_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$job.'?\');" title="Delete '.$job.'"><i class="fa fa-trash"></i></a></td>
				</tr> 
			';
		}
		
		$result .= 
		'
					  </tbody>
					</table>
		';
	}
	
	else
	{
		$result = "No positions have been assigned";
	}
	

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$job_title_id = set_value('job_title_id');
	$dept_id = set_value('department_id');
	$job_commencement_date = set_value('job_commencement_date');
}

else
{
	$job_title_id = '';
	$dept_id = '';
	$job_commencement_date = '';
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Personnel job history</h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>human-resource/configuration" class="btn btn-info pull-right">Edit job titles</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open('human-resource/add-personnel-job/'.$personnel_id, array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-3">
        
        <div class="form-group">
            <label class="col-lg-3 control-label">Job title: </label>
            
            <div class="col-lg-9">
            	<select class="form-control" name="job_title_id">
                	<option value="">--Select position--</option>
                	<?php
                    	if($job_titles_query->num_rows() > 0)
						{
							$job_titles = $job_titles_query->result();
							
							foreach($job_titles as $res)
							{
								$db_job_title_id = $res->job_title_id;
								$job_title_name = $res->job_title_name;
								
								if($db_job_title_id == $job_title_id)
								{
									echo '<option value="'.$db_job_title_id.'" selected>'.$job_title_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_job_title_id.'">'.$job_title_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
   	</div>
	<div class="col-md-3">
        
        <div class="form-group">
            <label class="col-lg-3 control-label">Department: </label>
            
            <div class="col-lg-9">
            	<select class="form-control" name="department_id">
                	<option value="">--Select department--</option>
                	<?php
                    	if($departments->num_rows() > 0)
						{	
							foreach($departments->result() as $res)
							{
								$department_id = $res->department_id;
								$department_name = $res->department_name;
								
								if($department_id == $dept_id)
								{
									echo '<option value="'.$department_id.'" selected>'.$department_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$department_id.'">'.$department_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
   	</div>
    
	<div class="col-md-3">
        
        <div class="form-group">
            <label class="col-lg-3 control-label">Start date: </label>
            
            <div class="col-lg-9">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="job_commencement_date" placeholder="Start date" value="<?php echo $job_commencement_date;?>">
                </div>
            </div>
        </div>
   	</div>
    
	<div class="col-md-3">
        
        <div class="form-group">
            <div class="col-md-12">
            	<div class="form-actions center-align">
                    <button class="btn btn-primary" type="submit">
                        Add position
                    </button>
                </div>
            </div>
        </div>
        
	</div>
</div>

            <?php echo form_close();?>
            <?php echo $result;?>
                </div>
            </section>