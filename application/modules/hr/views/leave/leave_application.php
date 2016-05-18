   <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Leave Application</h2>
                </header>
                <div class="panel-body">
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-4 col-sm-offset-8">
                    <div class="form-actions center-align">
                        <a href="<?php echo site_url().'dashboard';?>" class="btn btn-info pull-right" type="submit">
                            Back
                        </a>
                    </div>
                </div>
            </div>
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
			?>
            <?php echo form_open($this->uri->uri_string());?>
            <input type="hidden" name="personnel_id" value="<?php echo $personnel_id;?>"/>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                	
                    <div class="form-group">
                        <label class="col-lg-5 control-label">Start date: </label>
                        
                        <div class="col-lg-7">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start date" value="<?php echo set_value('start_date');?>">
                            </div>
                        </div>
                    </div>
                	
                    <div class="form-group">
                        <label class="col-lg-5 control-label">End date: </label>
                        
                        <div class="col-lg-7">
                    
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End date" value="<?php echo set_value('end_date');?>">
                            </div>
                        </div>
                    </div>
                	
                    <div class="form-group">
                        <label class="col-lg-5 control-label">Leave type: </label>
                        
                        <div class="col-lg-7">
                    
                            <select class="form-control" name="leave_type_id">
                                <option value="">--Select leave type--</option>
                                <?php
                                    if($leave_types->num_rows() > 0)
                                    {
                                        foreach($leave_types->result() as $res)
                                        {
                                            $leave_type_id = $res->leave_type_id;
                                            $leave_type_name = $res->leave_type_name;
                                            
                                            if($selected_leave_type_id == $leave_type_id)
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
                </div>
            </div>
            
            <div class="row" style="margin-top:10px;">
                <div class="col-md-3 col-md-offset-5">
                    <button type="submit" class="btn btn-primary">Apply for leave</button>
                </div>
            </div>
            <?php echo form_close();?> 
                </div>
            </section>