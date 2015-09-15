<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>administration/services" class="btn btn-sm btn-primary"> Back to Service List </a>

		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
   
 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i>Search <?php echo $title;?></h4>
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

            <?php
            if($service_id > 0)
            {
            	?>
            	<?php echo form_open('administration/update_service/'.$service_id, array('class' => 'form-horizontal'));?>
                 <div class="row">
                    <div class="col-md-12">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_name" placeholder="Service  Name" value="<?php echo $service_name;?>">
                              </div>
                          </div>
                         
                      </div>
                     
                </div>

                <div class="center-align">
                  <button type="submit" class="btn btn-info btn-lg"> Update Service</button>
                </div>
            	<?php echo form_close();
            }else
            {
            	?>
            	<?php echo form_open('administration/service_add', array('class' => 'form-horizontal'));?>
                 <div class="row">
                    <div class="col-md-12">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_name" placeholder="Service  Name" value="">
                              </div>
                          </div>
                         
                      </div>
                     
                </div>

                <div class="center-align">
                  <button type="submit" class="btn btn-info">Add New Service</button>
                </div>
            	<?php echo form_close();
            }
            ?>
            
            </div>
        
		</section>
  </div>
</div>