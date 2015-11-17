          
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
                            <a href="<?php echo site_url();?>hospital-administration/beds/<?php echo $room_id;?>" class="btn btn-info btn-sm pull-right">Back to beds</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! '.$error.'. </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
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
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
                    <div class="row">
                    	<div class="col-md-4">
                            <!-- Company Name -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Cash Price</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="cash_price" placeholder="Cash Price" value="<?php echo set_value('cash_price');?>" required>
                                </div>
                            </div>
                        </div>
                    	<div class="col-md-4">
                            <!-- Company Name -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Insurance Price</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="insurance_price" placeholder="Insurance Price" value="<?php echo set_value('insurance_price');?>" required>
                                </div>
                            </div>
                        </div>
                        
                    	<div class="col-md-4">
                            <!-- Activate checkbox -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Activate?</label>
                                <div class="col-lg-4">
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios1" type="radio" checked value="1" name="bed_status">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios2" type="radio" value="0" name="bed_status">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions center-align" style="margin-top:10px">
                        <button class="submit btn btn-primary" type="submit">
                            Add bed
                        </button>
                    </div>
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>