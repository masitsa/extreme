<?php
	$result ='';
	if($leave->num_rows() > 0)
	{
		$count = 0;
			
		$result .= 
		'
		<br/>
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Personnel</a></th>
					<th>Start date</a></th>
					<th>End date</a></th>
					<th>Type</a></th>
					<th>Status</a></th>
					<th colspan="5">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($leave->result() as $row)
		{
			$personnel_fname = $row->personnel_fname;
			$personnel_onames = $row->personnel_onames;
			$leave_duration_id = $row->leave_duration_id;
			$leave_type_name = $row->leave_type_name;
			$leave_duration_status = $row->leave_duration_status;
			$start_date = date('jS M Y',strtotime($row->start_date));
			$end_date = date('jS M Y',strtotime($row->end_date));
			
			//create deactivated status display
			if($leave_duration_status == 0)
			{
				$status = '<span class="label label-danger">Unclaimed</span>';
				$button = '<a class="btn btn-sm btn-info" href="'.site_url().'human-resource/activate-leave/'.$leave_duration_id.'/'.$date.'" onclick="return confirm(\'Do you want to claim '.$personnel_fname.' '.$personnel_onames.' leave starting '.$start_date.'?\');" title="Claim '.$start_date.' Leave"><i class="fa fa-thumbs-up"></i></a>';
				$delete = '<a href="'.site_url().'human-resource/delete-leave/'.$leave_duration_id.'/'.$date.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete?\');" title="Delete"><i class="fa fa-trash"></i></a>';
			}
			//create activated status display
			else if($leave_duration_status == 1)
			{
				$status = '<span class="label label-success">Claimed</span>';
				$button = '<a class="btn btn-sm btn-default" href="'.site_url().'human-resource/deactivate-leave/'.$leave_duration_id.'/'.$date.'" onclick="return confirm(\'Do you want to unclaim '.$personnel_fname.' '.$personnel_onames.' leave starting '.$start_date.'?\');" title="Unclaim '.$start_date.' Leave"><i class="fa fa-thumbs-down"></i></a>';
				$delete = '';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$personnel_fname.' '.$personnel_onames.'</td>
					<td>'.$start_date.'</td>
					<td>'.$end_date.'</td>
					<td>'.$leave_type_name.'</td>
					<td>'.$status.'</td>
					<td>'.$button.'</td>
					<td>'.$delete.'</td>
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
		$result = "<p>No leave have been assigned</p>";
	}
	

//repopulate data if validation errors occur
$validation_errors = validation_errors();
				
if(!empty($validation_errors))
{
	$old_personnel_id = set_value('personnel_id');
	$start_date = set_value('start_date');
	$end_date = set_value('end_date');
	$old_leave_type_id = set_value('leave_type_id');
}

else
{
	$old_personnel_id = '';
	$start_date = $date;
	$end_date = '';
	$old_leave_type_id = '';
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
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
            <?php echo form_open("human-resource/add-leave/".$date);?>
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" name="personnel_id">
                    	<option value="">--Select personnel--</option>
                        <?php echo $personnel;?>
                        <?php
							if($personnel->num_rows() > 0)
							{
								foreach($personnel->result() as $res)
								{
									$personnel_id = $res->personnel_id;
									$personnel_fname = $res->personnel_fname;
									$personnel_onames = $res->personnel_onames;
									
									if($old_personnel_id == $personnel_id)
									{
										echo "<option value='".$personnel_id."' selected>".$personnel_fname." ".$personnel_onames."</option>";
									}
									
									else
									{
										echo "<option value='".$personnel_id."'>".$personnel_fname." ".$personnel_onames."</option>";
									}
								}
							}
						?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start date" value="<?php echo $start_date;?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End date" value="<?php echo $end_date;?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="leave_type_id">
                    	<option value="">--Select leave type--</option>
                        <?php
							if($leave_types->num_rows() > 0)
							{
								foreach($leave_types->result() as $res)
								{
									$leave_type_id = $res->leave_type_id;
									$leave_type_name = $res->leave_type_name;
									
									if($old_leave_type_id == $leave_type_id)
									{
										echo '<option value="'.$leave_type_id.'" selected>'.$leave_type_name.'</option>';
									}
									
									else
									{
										echo '<option value="'.$leave_type_id.'" >'.$leave_type_name.'</option>';
									}
								}
							}
						?>
                    </select>
                </div>
            </div>
            
            <div class="row" style="margin-top:10px;">
                <div class="col-md-3 col-md-offset-5">
                    <button type="submit" class="btn btn-primary">Add leave</button>
                </div>
            </div>
            <?php echo form_close();?> 
            
            <?php
				$success = $this->session->userdata('success_message');
				$error = $this->session->userdata('error_message');
				
				if(!empty($success))
				{
					echo '
						<div class="alert alert-success">'.$success.'</div>
					';
					
					$this->session->unset_userdata('success_message');
				}
				
				if(!empty($error))
				{
					echo '
						<div class="alert alert-danger">'.$error.'</div>
					';
					
					$this->session->unset_userdata('error_message');
				}
				
			?>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-4 col-sm-offset-8">
                    <div class="form-actions center-align">
                        <a href="<?php echo site_url().'human-resource/leave';?>" class="btn btn-info pull-right" type="submit">
                            Back to schedule
                        </a>
                    </div>
                </div>
            </div>
            <?php echo $result;?>
                </div>
            </section>