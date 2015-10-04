 <?php
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
	<div class="col-md-12">
		<a href="<?php echo site_url();?>lab_charges/test_format/<?php echo $test_id;?>" class="btn btn-success pull-right">Back to <?php echo $lab_test_name;?> formats</a>
	</div>
</div>
 <section class="panel">
    <header class="panel-heading">
        <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?> for <?php echo $lab_test_name;?> </h4>
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
			$validation_errors = validation_errors();
			
			if(!empty($error))
			{
				echo '<div class="alert alert-danger">'.$error.'</div>';
				$this->session->unset_userdata('error_message');
			}
			
			if(!empty($validation_errors))
			{
				echo '<div class="alert alert-danger">'.$validation_errors.'</div>';
			}
			
			if(!empty($success))
			{
				echo '<div class="alert alert-success">'.$success.'</div>';
				$this->session->unset_userdata('success_message');
			}
           	if(!empty($format_id))
           	{
           		echo form_open("lab_charges/update_lab_test_format/".$test_id."/".$format_id, array("class" => "form-horizontal"));

           		if($lab_test_format_details->num_rows() > 0)
           		{
       				$lab_test_format_details = $lab_test_format_details->result();
									
					foreach($lab_test_format_details as $format_details)
					{
						$lab_test_format_id = $format_details->lab_test_format_id;
						$lab_test_formatname = $format_details->lab_test_formatname;
						$lab_test_format_units = $format_details->lab_test_format_units;
						$male_lower_limit = $format_details->lab_test_format_malelowerlimit;
						$male_upper_limit = $format_details->lab_test_format_maleupperlimit;
						$female_upper_limit = $format_details->lab_test_format_femaleupperlimit;
						$female_lower_limit = $format_details->lab_test_format_femalelowerlimit;
						$lab_test_id = $format_details->lab_test_id;

					
					}
           		}
            ?>
           
            
            <div class="row">
                <div class="col-md-6">
                	
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Format *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="lab_test_format" placeholder="Test format" value="<?php echo $lab_test_formatname;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Units: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="units" placeholder="Units" value="<?php echo $lab_test_format_units;?>">
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
                            <input type="text" class="form-control" name="female_lower_limit" placeholder="Female lower limit" value = "<?php echo $female_lower_limit;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Female Upper Limit: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="female_upper_limit" placeholder="Female upper limit" value="<?php echo $female_upper_limit;?>">
                        </div>
                    </div>
                    
                
                </div>
            </div>
            
            <div class="center-align" style="margin-top:10px;">
            	<button type="submit" class="btn btn-info">Update Lab Test format</button>
            </div>
            
            <?php
            echo form_close();
           	}
           	else
           	{
            echo form_open("lab_charges/create_new_lab_test_format/".$test_id."", array("class" => "form-horizontal"));
            ?>
           
            <div class="row">
                <div class="col-md-6">
                	
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Format *: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="lab_test_format" placeholder="Test format">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Units: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="units" placeholder="Units">
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
            	<button type="submit" class="btn btn-info">Create Lab Test format</button>
            </div>
            
            <?php
            echo form_close();
        	}
            ?>
    	</div>
    </div>
</section>