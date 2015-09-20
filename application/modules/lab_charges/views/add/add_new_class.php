
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title"><?php echo $title;?></h2>
			</header>

			<div class="panel-body">
			<?php
$class_results = $this->lab_charges_model->get_class_details($class_id);
if($class_results->num_rows() > 0)
{
    $class_results = $class_results->result();
                    
    foreach($class_results as $format_details)
    {
        $lab_test_class_name = $format_details->lab_test_class_name;
        $lab_test_class_id = $format_details->lab_test_class_id;

    
    }
}
if($class_id > 0)
{
    ?>
      <div class="row">
        <div class="col-md-12">
            <a href="<?php echo site_url();?>lab_charges/classes" class="btn btn-success btn-sm pull-right">Back to classes</a>
        </div>
    </div>

    <?php
}


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
            if($class_id > 0)
            {
                echo form_open("lab_charges/update_lab_test_class/".$class_id, array("class" => "form-horizontal"));
                ?>
              
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label"> Class Name: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="class_name" placeholder="Class Name" value="<?php echo $lab_test_class_name;?>">
                            </div>
                        </div>
                     
                    </div>
                    
                </div>
                
                <div class="center-align" style="margin-top:10px;">
                    <button type="submit" class="btn btn-info">Edit Class</button>
                </div>
              
                <?php
                echo form_close();
            }
            else
            {
            echo form_open("lab_charges/create_new_class", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-10">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label"> Class Name: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="class_name" placeholder="Class Name">
                        </div>
                    </div>
                 
                </div>
                
            </div>
            
            <div class="center-align" style="margin-top:10px;">
            	<button type="submit" class="btn btn-info">Create a new Class</button>
            </div>
          
            <?php
            echo form_close();
            }
            ?>
    	</div>
    </section>