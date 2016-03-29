<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
    <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
                <a href="<?php echo base_url();?>inventory-setup/clients" class="btn btn-primary pull-right btn-sm">Back to clientss</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
        <?php
        if(isset($error)){
            echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
        }
    	
    	//the clients details
    	$clients_id = $clients[0]->client_id;
    	$clients_name = $clients[0]->client_name;
    	$clients_phone = $clients[0]->client_phone;
    	$clients_status = $clients[0]->client_status;
    	$clients_email = $clients[0]->client_email;
    	$clients_physical_address = $clients[0]->client_physical_address;
    	$clients_contact_person = $clients[0]->client_contact_person;
        
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
        {
    		$clients_id = set_value('clients_id');
    		$clients_name = set_value('clients_name');
    		$clients_phone = set_value('clients_phone');
    		$clients_status = set_value('clients_status');
    		$clients_email = set_value('clients_email');
    		$clients_physical_address = set_value('clients_physical_address');
    		$clients_contact_person = set_value('clients_contact_person');
    		
            echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
        }
    	
        ?>
        
        <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
        <div class="row">
            <div class="col-md-6">
                <!-- clients Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">clients Name</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="clients_name" placeholder="clients Name" value="<?php echo $clients_name;?>" required>
                    </div>
                </div>
                 <!-- clients Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">clients Contact Person Name</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="clients_contact_person" placeholder="clients Contact Person" value="<?php echo $clients_contact_person;?>" required>
                    </div>
                </div>
                 <!-- Activate checkbox -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Activate clients?</label>
                    <div class="col-lg-4">
                        <div class="radio">
                            <label>
                            	<?php
                                if($clients_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="clients_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="1" name="clients_status">';}
            					?>
                                Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                            	<?php
                                if($clients_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="clients_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="0" name="clients_status">';}
            					?>
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- clients Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">clients Physical Address</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="clients_physical_address" placeholder="clients Contact Person" value="<?php echo $clients_physical_address;?>" required>
                    </div>
                </div>
                 <!-- clients Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">clients Phone</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="clients_phone" placeholder="clients Contact Person" value="<?php echo $clients_phone;?>" required>
                    </div>
                </div>
                 <!-- clients Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">clients Email</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="clients_email" placeholder="clients Contact Person" value="<?php echo $clients_email;?>" required>
                    </div>
                </div>
                <div class="form-actions center-align">
                    <button class="submit btn btn-primary btn-sm" type="submit">
                        Edit clients
                    </button>
                </div>
            </div>
        <?php echo form_close();?>
    </div>
    </section>