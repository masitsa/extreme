
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
                            <a href="<?php echo site_url();?>hospital-administration/visit-types" class="btn btn-info pull-right">Back to visit types</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! '.$error.' </div>';
            }
			
			//the visit_type details
			$visit_type_name = $visit_type[0]->visit_type_name;
			$visit_type_status = $visit_type[0]->visit_type_status;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$visit_type_name = set_value('visit_type_name');
				$visit_type_status = set_value('visit_type_status');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit type name</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="visit_type_name" placeholder="Visit type name" value="<?php echo $visit_type_name;?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Activate visit type?</label>
                        <div class="col-lg-4">
                            <div class="radio">
                                <label>
                                    <?php
                                    if($visit_type_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="visit_type_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="1" name="visit_type_status">';}
                                    ?>
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <?php
                                    if($visit_type_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="visit_type_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="0" name="visit_type_status">';}
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
                    Edit visit type
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>