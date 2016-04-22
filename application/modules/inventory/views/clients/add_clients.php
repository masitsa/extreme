 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
              <h4 class="panel-title pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>inventory-setup/clients" class="btn btn-primary pull-right btn-sm">Back to Clients</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- clients Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Clients Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="clients_name" placeholder="clients Name" value="<?php echo set_value('clients_name');?>" required>
                        </div>
                    </div>
                   <div class="form-group">
                        <label class="col-lg-6 control-label">Clients Contact Person Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="clients_contact_person" placeholder="Contact Person" value="<?php echo set_value('clients_contact_person');?>" required>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-lg-6 control-label">Activate Clients?</label>
                        <div class="col-lg-6">
                                
                                    <input id="optionsRadios1" type="radio" checked value="1" name="clients_status">
                                    Yes
                              
                         
                                    <input id="optionsRadios2" type="radio" value="0" name="clients_status">
                                    No
                               
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6">
                	<div class="form-group">
                        <label class="col-lg-6 control-label">Clients Physical Address</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="clients_physical_address" placeholder="clients Name" value="<?php echo set_value('clients_physical_address');?>" required>
                        </div>
                    </div>
                	<div class="form-group">
                        <label class="col-lg-6 control-label">Clients Phone</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="clients_phone" placeholder="Client Phone" value="<?php echo set_value('clients_phone');?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Clients Email</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="clients_email" placeholder="Client Email" value="<?php echo set_value('clients_email');?>" required>
                        </div>
                    </div>
                    <!-- Activate checkbox -->
                  
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Client
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>