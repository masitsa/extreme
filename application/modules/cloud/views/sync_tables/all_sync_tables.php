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
						<th><a href="'.site_url().'cloud/sync-tables/sync_table_name/'.$order_method.'/'.$page.'">Table</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/branch_code/'.$order_method.'/'.$page.'">Branch code</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/table_key_name/'.$order_method.'/'.$page.'">Key name</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/sync_table_cloud_save_function/'.$order_method.'/'.$page.'">Cloud save fn</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/modified_by/'.$order_method.'/'.$page.'">Modified by</a></th>
						<th><a href="'.site_url().'cloud/sync-tables/sync_table_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->personnel_model->retrieve_personnel();
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
				$sync_table_id = $row->sync_table_id;
				$branch_code = $row->branch_code;
				$table_key_name = $row->table_key_name;
				$sync_table_name = $row->sync_table_name;
				$sync_table_cloud_save_function = $row->sync_table_cloud_save_function;
				$sync_table_status = $row->sync_table_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
				$created = date('jS M Y H:i a',strtotime($row->created));
				
				//create deactivated status display
				if($sync_table_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info btn-sm" href="'.site_url().'cloud/activate-sync-table/'.$sync_table_id.'" onclick="return confirm(\'Do you want to activate '.$sync_table_name.'?\');" title="Activate '.$sync_table_name.'"><i class="fa fa-thumbs-up"></i> Activate</a>';
				}
				//create activated status display
				else if($sync_table_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default btn-sm" href="'.site_url().'cloud/deactivate-sync-table/'.$sync_table_id.'" onclick="return confirm(\'Do you want to deactivate '.$sync_table_name.'?\');" title="Deactivate '.$sync_table_name.'"><i class="fa fa-thumbs-down"></i> Deactivate</a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$user_id = $adm->personnel_id;
						
						if($user_id == $created_by)
						{
							$created_by = $adm->first_name;
						}
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->first_name;
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
						<td>'.$sync_table_name.'</td>
						<td>'.$branch_code.'</td>
						<td>'.$table_key_name.'</td>
						<td>'.$sync_table_cloud_save_function.'</td>
						<td>'.$last_modified.'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'cloud/edit-sync-table/'.$sync_table_id.'" class="btn btn-sm btn-info" title="Edit '.$sync_table_name.'"><i class="fa fa-pencil"></i> Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'cloud/delete-sync-table/'.$sync_table_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$sync_table_name.'?\');" title="Delete '.$sync_table_name.'"><i class="fa fa-trash"></i> Delete</a></td>
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
			$result .= "There are no sync tables";
		}
?>

						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>cloud/add-sync-table" class="btn btn-success btn-sm pull-right" style="margin-right:20px;">Add sync table</a>
                                    </div>
                                </div>
                                <?php
								$error = $this->session->userdata('error_message');
								$success = $this->session->userdata('success_message');
								
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
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
                            
                            <div class="panel-foot">
                                
								<?php if(isset($links)){echo $links;}?>
                            
                                <div class="clearfix"></div> 
                            
                            </div>
						</section>