 
 <div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>lab_charges/test_list" class="btn btn-success pull-right">Back to lab tests</a>
	</div>
</div>
 <section class="panel">
    <header class="panel-heading">
        <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?> </h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </header>             
    
      <!-- Widget content -->
        <div class="panel-body">
    	<div class="padd">
			<?php
			$error = $this->session->userdata('error_message');
			$success = $this->session->userdata('success_message');
			
			if(!empty($error))
			{
				echo '<div class="alert alert-danger">'.$error.'</div>';
				$this->session->unset_userdata('error_message');
			}
			
			if(!empty($success))
			{
				echo '<div class="alert alert-success">'.$success.'</div>';
				$this->session->unset_userdata('success_message');
			}
           	if(!empty($test_id))
           	{
           		echo form_open("lab_charges/update_lab_test/".$test_id, array("class" => "form-horizontal"));

           		if($lab_test_details->num_rows() > 0)
           		{
       				$lab_test_details = $lab_test_details->result();
									
					foreach($lab_test_details as $details)
					{
						$lab_test_class_idd = $details->lab_test_class_id;
						$lab_test_name = $details->lab_test_name;
						$lab_test_price = $details->lab_test_price;
						$male_lower_limit = $details->lab_test_malelowerlimit;
						$male_upper_limit = $details->lab_test_malelupperlimit;
						$female_upper_limit = $details->lab_test_femaleupperlimit;
						$female_lower_limit = $details->lab_test_femalelowerlimit;
						$lab_test_units = $details->lab_test_units;
						$lab_test_id = $details->lab_test_id;

					
					}
           		}
            ?>
           
            <div class="row">
                <div class="col-md-6">
                	
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Test Class *: </label>
                        
                        <div class="col-lg-8">
                           <select class="form-control" name="lab_test_class_id">
                           	<option value="0"> Select a lab test class </option>
			                	<?php
			                    	if($lab_test_classes->num_rows() > 0)
									{
										$lab_test_class = $lab_test_classes->result();
										
										foreach($lab_test_class as $res)
										{

											$lab_test_class_id = $res->lab_test_class_id;
											$lab_test_class_name = $res->lab_test_class_name;

											if($lab_test_class_id == $lab_test_class_idd)
											{
												echo '<option value="'.$lab_test_class_id.'" selected="selected">'.$lab_test_class_name.'</option>';
											}
											else
											{
												echo '<option value="'.$lab_test_class_id.'">'.$lab_test_class_name.'</option>';
											}
											
											
										}
									}
								?>
			                </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Test Name *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="lab_test_name" placeholder="Test name" value="<?php echo $lab_test_name;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Units: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="units" placeholder="Units" value="<?php echo $lab_test_units;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Price *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="price" placeholder="Price" value="<?php echo $lab_test_price;?>">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Male Lower Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="male_lower_limit" placeholder="Male lower limit" value="<?php echo $male_lower_limit;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Male Upper Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="male_upper_limit" placeholder="Male upper limit" value="<?php echo $male_upper_limit;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Female Lower Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="female_lower_limit" placeholder="Female lower limit" value="<?php echo $female_lower_limit;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Female Upper Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="female_upper_limit" placeholder="Female upper limit" value="<?php echo $female_upper_limit?>">
                        </div>
                    </div>
                    
                
                </div>
            </div>
            
            <div class="center-align" style="margin-top:10px;">
            	<button type="submit" class="btn btn-info">Update Lab Test</button>
            </div>
            
            <?php
            echo form_close();
           	}
           	else
           	{
            echo form_open("lab_charges/create_new_lab_test", array("class" => "form-horizontal"));
            ?>
           
            <div class="row">
                <div class="col-md-6">
                	
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Test Class *: </label>
                        
                        <div class="col-lg-8">
                           <select class="form-control" name="lab_test_class_id">
                           	<option value="0"> Select a lab test class </option>
			                	<?php
			                    	if($lab_test_classes->num_rows() > 0)
									{
										$lab_test_class = $lab_test_classes->result();
										
										foreach($lab_test_class as $res)
										{
											$lab_test_class_id = $res->lab_test_class_id;
											$lab_test_class_name = $res->lab_test_class_name;
											
											echo '<option value="'.$lab_test_class_id.'">'.$lab_test_class_name.'</option>';
											
										}
									}
								?>
			                </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Test Name *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="lab_test_name" placeholder="Test name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Units: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="units" placeholder="Units">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Price *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="price" placeholder="Price">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Male Lower Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="male_lower_limit" placeholder="Male lower limit">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Male Upper Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="male_upper_limit" placeholder="Male upper limit">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Female Lower Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="female_lower_limit" placeholder="Female lower limit">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Female Upper Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="female_upper_limit" placeholder="Female upper limit">
                        </div>
                    </div>
                    
                
                </div>
            </div>
            
            <div class="center-align" style="margin-top:10px;">
            	<button type="submit" class="btn btn-info">Create Lab Test</button>
            </div>
            
            <?php
            echo form_close();
        	}
            ?>
    	</div>
    </div>
</section>