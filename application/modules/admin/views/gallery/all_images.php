<div class="padd">
<a href="<?php echo site_url().'administration/add-gallery';?>" class="btn btn-success pull-right">Add Gallery</a>
<?php	

		$success = $this->session->userdata('success_message');
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
			$this->session->unset_userdata('success_message');
		}
		
		$error = $this->session->userdata('error_message');
		
		if(!empty($error))
		{
			echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
			$this->session->unset_userdata('error_message');
		}
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
				?>
                <table class="table table-condensed table-striped table-hover">
                    <tr>
                    	<th>#</th>
                    	<th>Image</th>
                    	<th>Department</th>
                    	<th>Status</th>
                    	<th>Actions</th>
                    </tr>
                <?php
				$count = $page;
				foreach($query->result() as $cat){
					
					$gallery_id = $cat->gallery_id;
					$gallery_status = $cat->gallery_status;
					$gallery_name = $cat->gallery_name;
					$department_name = $cat->department_name;
					$gallery_image_name = 'thumbnail_'.$cat->gallery_image_name;
					$count++;
					
					if($gallery_status == 1){
						$status = '<span class="label label-success">Active</span>';
					}
					else{
						$status = '<span class="label label-important">Deactivated</span>';
					}
					?>
                    <tr>
                    	<td><?php echo $count?></td>
                    	<td>
                        <img src="<?php echo $gallery_location.$gallery_image_name;?>" width="100" class="img-responsive img-thumbnail">
                        </td>
                    	<td><?php echo $department_name?></td>
                    	<td><?php echo $status?></td>
                    	<td>
                        	<a href="<?php echo site_url()."administration/edit-gallery/".$gallery_id.'/'.$page;?>" class="i_size" title="Edit">
                            <button class="btn btn-success btn-sm" type="button" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                            	
                            </a>
                        	<a href="<?php echo site_url()."administration/delete-gallery/".$gallery_id.'/'.$page;?>" class="i_size" title="Delete" onclick="return confirm('Do you really want to delete this image?');">
                            	 <button class="btn btn-danger btn-sm" type="button" ><i class="fa fa-trash-o"></i> Delete</button>
                            </a>
                            <?php
								if($gallery_status == 1){
									?>
                                        <a href="<?php echo site_url()."administration/deactivate-gallery/".$gallery_id.'/'.$page;?>" class="i_size" title="Deactivate" onclick="return confirm('Do you really want to deactivate this image?');">
                            <button class="btn btn-warning btn-sm" type="button" ><i class="fa fa-thumbs-o-down"></i> Deactivate</button>
                                        </a>
                                    <?php
								}
								else{
									?>
                                        <a href="<?php echo site_url()."administration/activate-gallery/".$gallery_id.'/'.$page;?>" class="i_size" title="Activate" onclick="return confirm('Do you really want to activate this image?');">
                            <button class="btn btn-info btn-sm" type="button" ><i class="fa fa-thumbs-o-up"></i> Activate</button>
                                        </a>
                                    <?php
								}
							?>
                        </td>
                    </tr>
                    <?php
				}
				?>
                </table>
                <?php
			}
			
			else{
				echo "There are no gallerys to display :-(";
			}
		?>
</div>