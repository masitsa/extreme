
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Import</h4>
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
          	<div class="row">
                
            	<div class="col-md-6 center-align">
                	<h3>Import Staff</h3>
                    <a href="<?php echo site_url().'/administration/bulk_add_all_staff';?>" class="btn btn-lg btn-primary">Import Staff</a>
                </div>
                
            	<div class="col-md-6 center-align">
                	<h3>Import Students</h3>
                    <a href="<?php echo site_url().'/administration/bulk_add_all_students';?>" class="btn btn-lg btn-warning">Import Students</a>
                </div>
            </div>
          </div>
        </div>
        
    </div>
</div>