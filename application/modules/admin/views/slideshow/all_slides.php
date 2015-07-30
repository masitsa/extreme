<div class="padd">
<a href="<?php echo site_url().'administration/add-slide';?>" class="btn btn-success pull-right">Add Slide</a>
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
                    	<th>Slide</th>
                    	<th>Title</th>
                    	<th>Description</th>
                    	<th>Status</th>
                    	<th>Actions</th>
                    </tr>
                <?php
				foreach($query->result() as $cat){
					
					$slideshow_id = $cat->slideshow_id;
					$slideshow_status = $cat->slideshow_status;
					$slideshow_name = $cat->slideshow_name;
					$slideshow_description = $cat->slideshow_description;
					$slideshow_image_name = 'thumbnail_'.$cat->slideshow_image_name;
					
					if($slideshow_status == 1){
						$status = '<span class="label label-success">Active</span>';
					}
					else{
						$status = '<span class="label label-important">Deactivated</span>';
					}
					?>
                    <tr>
                    	<td>
                        <img src="<?php echo $slideshow_location.$slideshow_image_name;?>" width="" class="img-responsive img-thumbnail">
                        </td>
                    	<td><?php echo $slideshow_name?></td>
                    	<td><?php echo $slideshow_description?></td>
                    	<td><?php echo $status?></td>
                    	<td>
                        	<a href="<?php echo site_url()."administration/edit-slide/".$slideshow_id.'/'.$page;?>" class="i_size" title="Edit">
                            <button class="btn btn-success btn-sm" type="button" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                            	
                            </a>
                        	<a href="<?php echo site_url()."administration/delete-slide/".$slideshow_id.'/'.$page;?>" class="i_size" title="Delete" onclick="return confirm('Do you really want to delete this slide?');">
                            	 <button class="btn btn-danger btn-sm" type="button" ><i class="fa fa-trash-o"></i> Delete</button>
                            </a>
                            <?php
								if($slideshow_status == 1){
									?>
                                        <a href="<?php echo site_url()."administration/deactivate-slide/".$slideshow_id.'/'.$page;?>" class="i_size" title="Deactivate" onclick="return confirm('Do you really want to deactivate this slide?');">
                            <button class="btn btn-warning btn-sm" type="button" ><i class="fa fa-thumbs-o-down"></i> Deactivate</button>
                                        </a>
                                    <?php
								}
								else{
									?>
                                        <a href="<?php echo site_url()."administration/activate-slide/".$slideshow_id.'/'.$page;?>" class="i_size" title="Activate" onclick="return confirm('Do you really want to activate this slide?');">
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
				echo "There are no slides to display :-(";
			}
		?>
</div>