<?php
	if($jobs->num_rows() > 0)
	{
		$count = 0;
			
		$result .= 
		'
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Position</th>
					<th>Date assigned</th>
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
			$created = date('jS M Y H:i a',strtotime($row->created));
			$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
			
			//create deactivated status display
			if($personnel_job_status == 0)
			{
				$status = '<span class="label label-important">Deactivated</span>';
				$button = '<a class="btn btn-info" href="'.site_url().'human-resource/activate-position/'.$personnel_job_id.'" onclick="return confirm(\'Do you want to activate '.$job.'?\');" title="Activate '.$job.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($personnel_job_status == 1)
			{
				$status = '<span class="label label-success">Active</span>';
				$button = '<a class="btn btn-default" href="'.site_url().'human-resource/deactivate-position/'.$personnel_job_id.'" onclick="return confirm(\'Do you want to deactivate '.$job.'?\');" title="Deactivate '.$job.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$job.'</td>
					<td>'.$created.'</td>
					<td>'.$last_modified.'</td>
					<td>'.$status.'</td>
					<td>'.$button.'</td>
					<td><a href="'.site_url().'human-resource/delete-personnel/'.$personnel_job_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$job.'?\');" title="Delete '.$job.'"><i class="fa fa-trash"></i></a></td>
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
}

else
{
	$job_title_id = '';
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Personnel job history</h2>
                </header>
                <div class="panel-body">
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
            
            <?php echo form_open('personnel/add-position', array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
        
        <div class="form-group">
            <label class="col-lg-3 control-label">Job Title: </label>
            
            <div class="col-lg-9">
            	<select class="form-control" name="job_title_id">
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
        
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
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