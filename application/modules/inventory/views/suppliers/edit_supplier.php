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
        <?php
        if(isset($error)){
            echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
        }
    	
    	//the supplier details
    	$supplier_id = $supplier[0]->supplier_id;
    	$supplier_name = $supplier[0]->supplier_name;
    	$supplier_phone = $supplier[0]->supplier_phone;
    	$supplier_status = $supplier[0]->supplier_status;
    	$supplier_email = $supplier[0]->supplier_email;
    	$supplier_physical_address = $supplier[0]->supplier_physical_address;
    	$supplier_contact_person = $supplier[0]->supplier_contact_person;
        
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
        {
    		$supplier_id = set_value('supplier_id');
    		$supplier_name = set_value('supplier_name');
    		$supplier_phone = set_value('supplier_phone');
    		$supplier_status = set_value('supplier_status');
    		$supplier_email = set_value('supplier_email');
    		$supplier_physical_address = set_value('supplier_physical_address');
    		$supplier_contact_person = set_value('supplier_contact_person');
    		
            echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
        }
    	
        ?>
        
        <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
        <div class="row">
            <div class="col-md-6">
                <!-- supplier Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Supplier Name</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="supplier_name" placeholder="supplier Name" value="<?php echo $supplier_name;?>" required>
                    </div>
                </div>
                 <!-- supplier Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Supplier Contact Person Name</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="supplier_contact_person" placeholder="Supplier Contact Person" value="<?php echo $supplier_contact_person;?>" required>
                    </div>
                </div>
                 <!-- Activate checkbox -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Activate supplier?</label>
                    <div class="col-lg-4">
                        <div class="radio">
                            <label>
                            	<?php
                                if($supplier_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="supplier_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="1" name="supplier_status">';}
            					?>
                                Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                            	<?php
                                if($supplier_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="supplier_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="0" name="supplier_status">';}
            					?>
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- supplier Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Supplier Physical Address</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="supplier_physical_address" placeholder="Supplier Contact Person" value="<?php echo $supplier_physical_address;?>" required>
                    </div>
                </div>
                 <!-- supplier Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Supplier Phone</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="supplier_phone" placeholder="Supplier Contact Person" value="<?php echo $supplier_phone;?>" required>
                    </div>
                </div>
                 <!-- supplier Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Supplier Email</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="supplier_email" placeholder="Supplier Contact Person" value="<?php echo $supplier_email;?>" required>
                    </div>
                </div>
                <div class="form-actions center-align">
                    <button class="submit btn btn-primary btn-sm" type="submit">
                        Edit supplier
                    </button>
                </div>
            </div>
        <?php echo form_close();?>
    </div>
    </section>