<div class="row">
    <div class="col-md-12">
              <a href="<?php echo site_url();?>/reception/all-patients" class="btn btn-sm btn-primary pull-left">Back to patients lists</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Change patient type for <?php echo $patient['patient_surname']." ".$patient['patient_othernames'];?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
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
                    <div class="col-md-6  col-md-offset-3">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Patient Type: </label>
                            
                            <div class="col-lg-8">
                                <select class="form-control" name="visit_type_id">
                                   <option value="Select Patient Type">--Select Patient Type--</option>
                                   <option value="2">Staff</option>
                                    <option value="3">Housekeeping</option>
                                   <option value="4">SBS</option>
                                   <option value="1">Student</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Staff/Student ID No.: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="strath_no" placeholder="Staff/Student ID No.">
                            </div>
                        </div>
              		</div>
              	</div>
              <?php echo form_close();?>
			
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>