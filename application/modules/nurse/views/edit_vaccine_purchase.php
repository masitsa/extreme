<?php

	$purchase_quantity = $purchase_details->purchase_quantity;
	$purchase_pack_size = $purchase_details->purchase_pack_size;
	$expiry_date = $purchase_details->expiry_date;
	
	if(!empty($validation_errors))
	{
		$purchase_quantity = set_value('purchase_quantity');
		$purchase_pack_size = set_value('purchase_pack_size');
		$expiry_date = set_value('expiry_date');
	}
?>
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
                
                <div class="col-md-offset-3 col-md-6">
                    
                    
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Purchase Quantity: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="purchase_quantity" placeholder="Purchase Quantity" value="<?php echo $purchase_quantity;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Pack Size: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="purchase_pack_size" placeholder="Pack Size" value="<?php echo $purchase_pack_size;?>">
                        </div>
                    </div>
        
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Expiry Date: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker_other_patient" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="expiry_date" placeholder="Expiry Date" value="<?php echo $expiry_date;?>">
                                <span class="add-on">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar" style="cursor:pointer;">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            
            <div class="center-align">
            	<a href="<?php echo site_url().'/nurse/vaccine_purchases/'.$vaccine_id;?>" class="btn btn-lg btn-default">Back</a>
                <button class="btn btn-info btn-lg" type="submit">Edit Purchase</button>
            </div>
            <?php echo form_close();?>
            
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>