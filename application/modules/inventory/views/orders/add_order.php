<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
         <h2 class="panel-title pull-left"><?php echo $title;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory/orders" class="btn btn-success btn-sm">Back to orders</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
     		<div class="row">
     			<div class="col-md-12">
     				 <!-- brand Name -->
			            <div class="form-group">
			                <label class="col-lg-2 control-label">Order Instructions</label>
			                <div class="col-lg-8">
			                	<textarea class="form-control" name="order_instructions"><?php echo set_value('order_instructions');?></textarea>
			                </div>
			            </div>
     			</div>
     		</div>
     		<br>
     		<div class="row">
	            <div class="form-actions center-align">
	                <button class="submit btn btn-primary btn-sm" type="submit">
	                    Create New Order
	                </button>
	            </div>
	         </div>
            <br />
            <?php echo form_close();?>
    </div>
</section>