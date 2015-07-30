          <div class="padd">
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
            
            <?php  echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- user_type Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">User Type Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="user_type_name" placeholder="User Type Name" value="<?php echo set_value('user_type_name');?>" required>
                </div>
            </div>
            <!-- user_type Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">User Type Cost</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="user_type_cost" placeholder="User Type Cost" value="<?php echo set_value('user_type_cost');?>" required>
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate user_type?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                            <input id="optionsRadios1" type="radio" checked value="1" name="user_type_status">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" value="0" name="user_type_status">
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Add User Type
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>