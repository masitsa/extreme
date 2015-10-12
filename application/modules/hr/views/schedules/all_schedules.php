<?php
		
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="'.site_url().'human-resource/schedules/schedule_name/'.$order_method.'/'.$page.'">Schedule name</a></th>
						<th><a href="'.site_url().'human-resource/schedules/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'human-resource/schedules/modified_by/'.$order_method.'/'.$page.'">Modified by</a></th>
						<th><a href="'.site_url().'human-resource/schedules/schedule_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->users_model->get_active_users();
			if ($administrators->num_rows() > 0)
			{
				$admins = $administrators->result();
			}
			
			else
			{
				$admins = NULL;
			}
			
			foreach ($query->result() as $row)
			{
				$schedule_id = $row->schedule_id;
				$schedule_name = $row->schedule_name;
				$schedule_status = $row->schedule_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				
				//status
				if($schedule_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($schedule_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'human-resource/activate-schedule/'.$schedule_id.'" onclick="return confirm(\'Do you want to activate '.$schedule_name.'?\');" title="Activate '.$schedule_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($schedule_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'human-resource/deactivate-schedule/'.$schedule_id.'" onclick="return confirm(\'Do you want to deactivate '.$schedule_name.'?\');" title="Deactivate '.$schedule_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$personnel_id = $adm->personnel_id;
						
						if($personnel_id == $created_by)
						{
							$created_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
						}
					}
				}
				
				else
				{
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$schedule_name.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$schedule_id.'" class="btn btn-primary btn-sm" data-toggle="modal" title="Expand '.$schedule_name.'"><i class="fa fa-plus"></i></a>
							
							<!-- Modal -->
							<div id="user'.$schedule_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$schedule_name.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												<tr>
												'.form_open("hr/schedules/edit_schedule/".$schedule_id).'
													<th>Schedule Name</th>
													<td><input type="text" class="form-control" name="schedule_name" value="'.$schedule_name.'"/><button type="submit" class="btn btn-primary">Update schedule</button></td>
												'.form_close().'
												</tr>
												<tr>
													<th>Status</th>
													<td>'.$status.'</td>
												</tr>
												<tr>
													<th>Date Created</th>
													<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
												</tr>
												<tr>
													<th>Created By</th>
													<td>'.$created_by.'</td>
												</tr>
												<tr>
													<th>Date Modified</th>
													<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
												</tr>
												<tr>
													<th>Modified By</th>
													<td>'.$modified_by.'</td>
												</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'human-resource/schedule-personnel/'.$schedule_id.'" class="btn btn-sm btn-success" title="Add personnel to '.$schedule_name.'"><i class="fa fa-users"></i></a>
											'.$button.'
											<a href="'.site_url().'human-resource/delete-schedule/'.$schedule_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$schedule_name.'?\');" title="Delete '.$schedule_name.'"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'human-resource/schedule-personnel/'.$schedule_id.'" class="btn btn-sm btn-success" title="Add personnel to '.$schedule_name.'"><i class="fa fa-users"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'human-resource/delete-schedule/'.$schedule_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$schedule_name.'?\');" title="Delete '.$schedule_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no schedules";
		}
?>
<div class="row">
    <div class="col-md-12">
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
    </div>
</div>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Add schedule</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <?php echo form_open("hr/schedules/add_schedule");?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-md-5 control-label">Schedule name: </label>
                            
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="schedule_name" placeholder="Schedule name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Add schedule</button>
                    </div>
                </div>
                <?php echo form_close();?> 
            </div>
        </div>
    </div>
</section>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
    </header>
    <div class="panel-body">
        <div class="table-responsive">
            
            <?php echo $result;?>
    
        </div>
    </div>
</section>