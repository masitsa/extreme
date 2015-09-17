
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
                            <a href="<?php echo site_url();?>cloud/sync-tables" class="btn btn-info pull-right">Back to sync tables</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! '.$error.' </div>';
            }
			
			//the sync_table details
			$sync_table_name = $sync_table[0]->sync_table_name;
			$table_key_name = $sync_table[0]->table_key_name;
			$sync_table_status = $sync_table[0]->sync_table_status;
			$branch_code = $sync_table[0]->branch_code;
			$sync_table_cloud_save_function = $sync_table[0]->sync_table_cloud_save_function;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$sync_table_name = set_value('sync_table_name');
				$table_key_name = set_value('table_key_name');
				$branch_code = set_value('branch_code');
				$sync_table_cloud_save_function = set_value('sync_table_cloud_save_function');
				$sync_table_status = set_value('sync_table_status');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Sync table name</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="sync_table_name" placeholder="sync_table name" value="<?php echo $sync_table_name;?>" required>
                        </div>
                    </div>
                    <!-- Company Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Table key name</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="table_key_name" placeholder="Table key name" value="<?php echo $table_key_name;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Branch code</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="branch_code" placeholder="Branch code" value="<?php echo $branch_code;?>">
                        </div>
                    </div>
                </div>
                        
                <div class="col-md-6">
                    <!-- Company Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Cloud save function</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="sync_table_cloud_save_function" placeholder="Cloud save function" value="<?php echo $sync_table_cloud_save_function;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Activate sync table?</label>
                        <div class="col-lg-8">
                            <div class="radio">
                                <label>
                                    <?php
                                    if($sync_table_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="sync_table_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="1" name="sync_table_status">';}
                                    ?>
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <?php
                                    if($sync_table_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="sync_table_status">';}
                                    else{echo '<input id="optionsRadios1" type="radio" value="0" name="sync_table_status">';}
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
                    Edit sync table
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>