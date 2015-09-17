          
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
                            <a href="<?php echo site_url();?>hospital-administration/visit-types" class="btn btn-info btn-sm pull-right">Back to visit types</a>
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
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
                    <div class="row">
                    	<div class="col-md-4">
                            <!-- Company Name -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="visit_type_name" placeholder="Name" value="<?php echo set_value('visit_type_name');?>" required>
                                </div>
                            </div>
                        </div>
                        
                    	<div class="col-md-4">
                            <div class="form-group">
                                <label class="col-lg-5 control-label">Insurance company: </label>
                                <div class="col-lg-7">
                                    <select id="insurance_company_id" name="insurance_company_id" class="form-control">
                                        <option value="">--- None ---</option>
                                        <?php
                                        if($insurance_companies->num_rows() > 0)
                                        {	
                                            foreach($insurance_companies->result() as $row):
												// $company_name = $row->company_name;
												$insurance_company_name = $row->insurance_company_name;
												$insurance_company_id = $row->insurance_company_id;
												
												if($insurance_company_id == set_value('insurance_company_id'))
												{
                                            		echo "<option value=".$insurance_company_id." selected='selected'> ".$insurance_company_name."</option>";
												}
												
												else
												{
                                            		echo "<option value=".$insurance_company_id."> ".$insurance_company_name."</option>";
												}
                                            endforeach;	
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    	<div class="col-md-4">
                            <!-- Activate checkbox -->
                            <div class="form-group">
                                <label class="col-lg-6 control-label">Activate visit type?</label>
                                <div class="col-lg-3">
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios1" type="radio" checked value="1" name="visit_type_status">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios2" type="radio" value="0" name="visit_type_status">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            		<div class="form-actions center-align" style="margin-top:10px;">
                        <button class="submit btn btn-primary" type="submit">
                            Add visit type
                        </button>
                    </div>
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>