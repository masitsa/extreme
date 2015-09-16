
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
                            <a href="<?php echo site_url();?>hospital-administration/departments" class="btn btn-info pull-right">Back to departments</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! '.$error.' </div>';
            }
			
			//the department details
			$department_name = $department[0]->department_name;
			$department_status = $department[0]->department_status;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$department_name = set_value('department_name');
				$department_status = set_value('department_status');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit type name</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="department_name" placeholder="Visit type name" value="<?php echo $department_name;?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Activate department?</label>
                        <div class="col-lg-4">
                            <div class="radio">
                                <label>
                                    <?php
                                    if($department_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="department_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="1" name="department_status">';}
                                    ?>
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <?php
                                    if($department_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="department_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="0" name="department_status">';}
                                    ?>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Edit department
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>