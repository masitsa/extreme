<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>hospital-administration/services" class="btn btn-sm btn-primary"> Back to Service List </a>

		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
   
 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
              </header>             

          <!-- Widget content -->
                <div class="panel-body">
          
			
            <div class="center-align">
              <?php
                $error = $this->session->userdata('error_message');
                $validation_error = validation_errors();
                $success = $this->session->userdata('success_message');
                
                if(!empty($error))
                {
                  echo '<div class="alert alert-danger">'.$error.'</div>';
                  $this->session->unset_userdata('error_message');
                }
                
                if(!empty($validation_error))
                {
                  echo '<div class="alert alert-danger">'.$validation_error.'</div>';
                }
                
                if(!empty($success))
                {
                  echo '<div class="alert alert-success">'.$success.'</div>';
                  $this->session->unset_userdata('success_message');
                }
              ?>
            </div>

            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal'));?>
                 <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Service name</label>
                            <div class="col-lg-8">
                            	<input type="text" class="form-control" name="service_name" placeholder="Service  Name" value="<?php echo $service_name;?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Department: </label>
                            
                            <div class="col-lg-8">
                                <select class="form-control" name="department_id">
                                    <?php
                                        if($departments->num_rows() > 0)
                                        {
                                            $department = $departments->result();
                                            
                                            foreach($department as $res)
                                            {
                                                $department_id = $res->department_id;
                                                $department_name = $res->department_name;
                                                
                                                if($department_id == $dept_id)
                                                {
                                                    echo '<option value="'.$department_id.'" selected="selected">'.$department_name.'</option>';
                                                }
                                                
                                                else
                                                {
                                                    echo '<option value="'.$department_id.'">'.$department_name.'</option>';
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                     
                    <div class="col-md-2">
                        <div class="center-align">
                          <button type="submit" class="btn btn-info"> Update service</button>
                        </div>
                    </div>
                </div>

            	<?php echo form_close(); ?>
            
            </div>
        
		</section>
  </div>
</div>