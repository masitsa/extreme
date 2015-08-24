
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
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
				
				if(!empty($validation_errors))
				{
					echo '<div class="alert alert-danger">'.$validation_errors.'</div>';
				}
				
				if(!empty($success))
				{
					echo '<div class="alert alert-success">'.$success.'</div>';
					$this->session->unset_userdata('success_message');
				}
			?>
          </div>
			<?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal"));?>
        	<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Vaccine Name <span class="required">*</span>: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="vaccine_name" placeholder="Vaccine Name" value="<?php echo set_value('vaccine_name');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Unit Price: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="vaccine_unitprice" placeholder="Unit Price" value="<?php echo set_value('vaccine_unitprice');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Batch Number: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="batch_no" placeholder="Batch Number" value="<?php echo set_value('batch_no');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Opening Quantity: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="quantity" placeholder="Opening Quantity:" value="<?php echo set_value('quantity');?>">
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Pack Size: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="vaccine_pack_size" placeholder="Pack Size" value="<?php echo set_value('vaccine_pack_size');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Dose: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="vaccine_dose" placeholder="Dose" value="<?php echo set_value('vaccine_dose');?>">
                        </div>
                    </div>
                   
                    
                 
                    
                </div>
            </div>
            
            <div class="center-align">
            	<a href="<?php echo site_url().'/pharmacy/inventory';?>" class="btn btn-lg btn-default">Back</a>
                <button class="btn btn-info btn-lg" type="submit">Add vaccine</button>
            </div>
            <?php echo form_close();?>
            
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>