 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>inventory-setup/suppliers" class="btn btn-primary pull-right btn-sm">Back to suppliers</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- supplier Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Supplier Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="supplier_name" placeholder="supplier Name" value="<?php echo set_value('supplier_name');?>" required>
                        </div>
                    </div>
                   <div class="form-group">
                        <label class="col-lg-6 control-label">Supplier Contact Person Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="supplier_contact_person" placeholder="Contact Person" value="<?php echo set_value('supplier_contact_person');?>" required>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Activate supplier?</label>
                        <div class="col-lg-6">
                                
                                    <input id="optionsRadios1" type="radio" checked value="1" name="supplier_status">
                                    Yes
                              
                         
                                    <input id="optionsRadios2" type="radio" value="0" name="supplier_status">
                                    No
                               
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6">
                	<div class="form-group">
                        <label class="col-lg-6 control-label">Supplier Physical Address</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="supplier_physical_address" placeholder="supplier Name" value="<?php echo set_value('supplier_physical_address');?>" required>
                        </div>
                    </div>
                	<div class="form-group">
                        <label class="col-lg-6 control-label">Supplier Phone</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="supplier_phone" placeholder="Supplier Phone" value="<?php echo set_value('supplier_phone');?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Supplier Email</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="supplier_email" placeholder="Supplier Email" value="<?php echo set_value('supplier_email');?>" required>
                        </div>
                    </div>
                    <!-- Activate checkbox -->
                  
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add supplier
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>