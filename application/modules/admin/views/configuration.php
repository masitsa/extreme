<?php
	$mandrill = '';
	$configuration_id = 0;
	
	if($configuration->num_rows() > 0)
	{
		$res = $configuration->row();
		$configuration_id = $res->configuration_id;
		$mandrill = $res->mandrill;
	}
			
?>
            	<section class="panel">
                    <header class="panel-heading">
                        <h2 class="panel-title"><?php echo $title;?></h2>
                    </header>
                    <div class="panel-body">
                    	<?php echo form_open('administration/edit-configuration/'.$configuration_id);?>
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
                    	
                        <div class="row" style="margin-top:2%;">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-lg-5 control-label">Email API key: </label>
                                    
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="mandrill" placeholder="Email API key" value="<?php echo $mandrill;?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="margin-top:2%;">
                        	<div class="col-sm-4 col-sm-offset-4">
                            	<div class="center-align">
                                	<button class="btn btn-primary" type="submit"><i class='fa fa-pencil'></i> Update configuration</button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close();?>
                    </div>
                </section>