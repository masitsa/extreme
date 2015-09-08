<?php
//personnel data
$row = $personnel->row();

$personnel_onames = $row->personnel_username;
$personnel_fname = $row->personnel_fname;
$personnel_account_status = $row->personnel_account_status;
$personnel_id = $row->personnel_id;

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$personnel_username = set_value('personnel_username');
	$personnel_account_status = set_value('personnel_account_status');
}

?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">User account details</h2>
                </header>
                <div class="panel-body">
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
            
            <?php echo form_open(''.site_url().'human-resource/edit-personnel-account/'.$personnel_id.'', array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Username: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_username" placeholder="Other Names" value="<?php echo $personnel_onames;?>">
            </div>
        </div>
    </div>
     
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-5 control-label">Login status: </label>
            
            <div class="col-lg-7">
            	<?php
                	if($personnel_account_status == '1')
					{
						?>
                        <div class="radio">
                            <label>
                                <input type="radio" checked="checked" value="1" id="personnel_account_status" name="personnel_account_status"> Active
                            </label>
                        </div>
                        
                        <div class="radio">
                            <label>
                                <input type="radio" value="0" id="personnel_account_status" name="personnel_account_status"> Disabled
                            </label>
                        </div>
                        <?php
					}
					
					else
					{
						?>
                        <div class="radio">
                            <label>
                                <input type="radio" value="1" id="personnel_account_status" name="personnel_account_status">
                                Active
                            </label>
                        </div>
                        
                        <div class="radio">
                            <label>
                                <input type="radio" checked="checked" value="0" id="personnel_account_status" name="personnel_account_status">
                                Disabled
                            </label>
                        </div>
                        <?php
					}
				?>
            </div>
        </div>
        
	</div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
        	<a href="<?php echo site_url().'human-resource/reset-password/'.$personnel_id;?>" class="btn btn-warning" onclick="return confirm('Reset password for <?php echo $personnel_fname;?>?');">Reset Password</a>
            <button class="btn btn-primary" type="submit">
                Edit account
            </button>
        </div>
    </div>
</div>
            <?php echo form_close();?>
            <?php echo form_open(''.site_url().'human-resource/update-personnel-roles/'.$personnel_id.'', array("class" => "form-horizontal", "role" => "form"));?>
                    <div class="row">
                    	<h3>Edit <?php echo $personnel_fname;?>'s roles</h3>
                    	<div class="col-md-4">
                        	<div class="parent_sections">
                            	<select class="form-control" name="section_id">
                                	<option value="" selected="selected">--Select section--</option>
									<?php
                                        if($parent_sections->num_rows() > 0)
                                        {
                                            foreach($parent_sections->result() as $res)
                                            {
                                                $section_id = $res->section_id;
                                                $section_name = $res->section_name;
                                                
                                                echo '<option value="'.$section_id.'" >'.$section_name.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            
                        	<div class="child_sections">
                            	<select class="form-control" name="child_id">
                                	<option value="" >--Select section to display sub sections--</option>
                                </select>
                            </div>
                            
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-12">
                                    <div class="form-actions center-align">
                                        <button class="btn btn-primary" type="submit">
                                            Add role
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php echo form_close();?>                         
                    	<div class="col-md-8">
                        	<h4>Assigned roles</h4>
                        	<?php
                             // var_dump($roles->num_rows()) or die();
                            	if($roles->num_rows() > 0)
								{
									$count = 0;
									?>
                                    <table class="table table-condensed table-hover table-striped table-bordered">
                                    	<tr>
                                        	<th>#</th>
                                            <th>Section</th>
                                            <th>Sub section</th>
                                            <th>Actions</th>
                                        </tr>
                                    <?php

									foreach($roles->result() as $res)
									{
										$personnel_section_id = $res->personnel_section_id;
										$section_id = $res->section_id;
										$section_name = $res->section_name;
										$section_parent = $res->section_parent;
										$count++;
										if($section_parent == 0)
										{
											$section_children = $this->sections_model->get_sub_sections($section_id);
											?>
                                            <tr>
                                            	<td><?php echo $count;?></td>
                                            	<td><?php echo $section_name;?></td>
                                            	<td></td>
                                            	<td><a href="<?php echo site_url().'human-resource/delete-personnel-role/'.$personnel_section_id.'/'.$personnel_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete <?php echo $section_name?>?');" title="Delete <?php echo $section_name;?>"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <?php
											foreach($section_children->result() as $res2)
											{
												$child_section_id = $res2->section_id;
												$child_section_name = $res2->section_name;
												$child_section_parent = $res2->section_parent;
												
												if($child_section_parent == $section_id)
												{
													?>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><?php echo $child_section_name;?></td>
                                                    </tr>
                                                    <?php
												}
											}
										}
										
										else
										{
											//get parent section
											$parent_query = $this->sections_model->get_section($section_parent);
											
											$parent_row = $parent_query->row();
											$parent_name = $parent_row->section_name;
											?>
                                            <tr>
                                            	<td><?php echo $count;?></td>
                                            	<td><?php echo $parent_name;?></td>
                                            	<td><?php echo $section_name;?></td>
                                            	<td><a href="<?php echo site_url().'human-resource/delete-personnel-role/'.$personnel_section_id.'/'.$personnel_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete <?php echo $section_name?>?');" title="Delete <?php echo $section_name;?>"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                            <?php
										}
									}
									?>
                                    </table>
									<?php
								}
								
								else
								{
									echo '<p>'.$personnel_fname.' doesn\'t have any roles assigned</p>';
								}
							?>
                        </div>
                    </div>
                </div>
            </section>