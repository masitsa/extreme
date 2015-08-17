<?php
	if($jobs->num_rows() > 0)
	{
		$count = 0;
			
		$result = 
		'
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Place of work</th>
					<th>Position</th>
					<th>Employment date</th>
					<th>Status</th>
					<th colspan="2">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($jobs->result() as $row)
		{
			$individual_job_id = $row->individual_job_id;
			$job = $row->job_title;
			$employer = $row->employer;
			$individual_job_status = $row->individual_job_status;
			$employment_date = date('jS M Y',strtotime($row->employment_date));
			
			//create deactivated status display
			if($individual_job_status == 0)
			{
				$status = '<span class="label label-default">Former</span>';
				$button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-position/'.$individual_job_id.'/'.$individual_id.'" onclick="return confirm(\'Do you want to activate '.$job.'?\');" title="Activate '.$job.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($individual_job_status == 1)
			{
				$status = '<span class="label label-success">Current</span>';
				$button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-position/'.$individual_job_id.'/'.$individual_id.'" onclick="return confirm(\'Do you want to deactivate '.$job.'?\');" title="Deactivate '.$job.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$employer.'</td>
					<td>'.$job.'</td>
					<td>'.$employment_date.'</td>
					<td>'.$status.'</td>
					<td>'.$button.'</td>
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

$employer = '';
$job_title = '';
$employment_date = '';

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$employer = set_value('employer');
	$job_title = set_value('job_title');
	$employment_date = set_value('employment_date');
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Employment history</h2>
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
            
            <?php echo form_open('microfinance/add-position/'.$individual_id, array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-4">
    
    	<div class="form-group">
            <label class="col-lg-5 control-label">Employer: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="employer" placeholder="Employer" value="<?php echo $employer;?>">
            </div>
        </div>
        
    </div>
	<div class="col-md-4">
    
    	<div class="form-group">
            <label class="col-lg-5 control-label">Job title: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="job_title" placeholder="Job title" value="<?php echo $job_title;?>">
            </div>
        </div>
        
    </div>
	<div class="col-md-4">
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Employment date: </label>
            
            <div class="col-lg-8">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="employment_date" placeholder="Start date" value="<?php echo $employment_date;?>">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin:10px 0 10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Add position
            </button>
        </div>
    </div>
</div>

            <?php echo form_close();?>
            <?php echo $result;?>
                </div>
            </section>