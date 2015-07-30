          
          <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                    </div>
            
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>administration/sections" class="btn btn-info pull-right">Back to sections</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
                    <!-- Section Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Section Name</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="section_name" placeholder="Section Name" value="<?php echo set_value('section_name');?>" >
                        </div>
                    </div>
                    <!-- Section Parent -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Parent</label>
                        <div class="col-lg-6">
                            <select name="section_parent" class="form-control" >
                                <?php
                                echo '<option value="0">No Parent</option>';
                                if($all_sections->num_rows() > 0)
                                {
                                    $result = $all_sections->result();
                                    
                                    foreach($result as $res)
                                    {
                                        if($res->section_id == set_value('section_parent'))
                                        {
                                            echo '<option value="'.$res->section_id.'" selected>'.$res->section_name.'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$res->section_id.'">'.$res->section_name.'</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Section Position -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Position</label>
                        <div class="col-lg-6">
                            <input type="number" class="form-control" name="section_position" placeholder="Position" value="<?php echo set_value('section_position');?>" >
                        </div>
                    </div>
                    <!-- Section Icon -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Icon</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="section_icon" placeholder="Icon" value="<?php echo set_value('section_icon');?>" >
                        </div>
                    </div>
                    <!-- Activate checkbox -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Activate Section?</label>
                        <div class="col-lg-6">
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios1" type="radio" checked value="1" name="section_status">
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios2" type="radio" value="0" name="section_status">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-lg-offset-4">
                            <div class="form-actions center-align">
                                <button class="submit btn btn-primary" type="submit">
                                    Add section
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>