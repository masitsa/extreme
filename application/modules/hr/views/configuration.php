		<div class="row">
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
        	<!-- Job config -->
        	<div class="col-md-6">
            	<section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title">Job titles</h2>
                    </header>
                    <div class="panel-body">
                    	<?php echo form_open('human-resource/add-job-title');?>
                        <div class="row">
                        	<div class="col-sm-8">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="job_title_name" placeholder="Job title">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                            	<button class="btn btn-primary btn-sm" type="submit">Add job title</button>
                            </div>
                        </div>
                        
                        <?php echo form_close();?>
                    	<?php
							if($job_titles_query->num_rows() > 0)
							{
								$job_titles = $job_titles_query->result();
								
								foreach($job_titles as $res)
								{
									$job_title_id = $res->job_title_id;
									$job_title_name = $res->job_title_name;
									?>
                                    <?php echo form_open('human-resource/edit-job-title/'.$job_title_id);?>
                                    <div class="row" style="margin-top:2%;">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="col-lg-12">
                                                    <input type="text" class="form-control" name="job_title_name" value="<?php echo $job_title_name;?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <button class="btn btn-success btn-xs" type="submit"><i class='fa fa-pencil'></i> Edit</button>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <a href="<?php echo site_url("human-resource/delete-job-title/".$job_title_id);?>" onclick="return confirm('Do you want to delete <?php echo $job_title_name;?>?');" title="Delete <?php echo $job_title_name;?>"class='btn btn-danger btn-xs'><i class='fa fa-trash'></i> Delete</a>
                                        </div>
                                    </div>
                                    <?php echo form_close();?>
                                    <?php
								}
							}
						?>
                    </div>
                </section>
            </div>
        	<!-- End job config -->
        </div>