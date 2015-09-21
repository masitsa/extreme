<?php
	$drugs_name = $drug_details->drugs_name;
	$drug_type_id = $drug_details->drug_type_id;
	$batch_no = $drug_details->batch_no;
	$drugs_unitprice = $drug_details->drugs_unitprice;
	$drugs_pack_size = $drug_details->drugs_packsize;
	$drug_dose = $drug_details->drugs_dose;
	$drug_dose_unit_id = $drug_details->drug_dose_unit_id;
	$brand_id = $drug_details->brand_id;
	$generic_id = $drug_details->generic_id;
	$drug_administration_route_id = $drug_details->drug_administration_route_id;
	$drug_consumption_id = $drug_details->drug_consumption_id;
	$class_id = $drug_details->class_id;
	$quantity = $drug_details->quantity;
	
	if(!empty($validation_errors))
	{
		$drugs_name = set_value('drug_name');
		$drug_type_id = set_value('drug_type_id');
		$batch_no = set_value('batch_no');
		$drugs_unitprice = set_value('drugs_unitprice');
		$drugs_pack_size = set_value('drugs_packsize');
		$drug_dose = set_value('drugs_dose');
		$drug_dose_unit_id = set_value('drug_dose_unit_id');
		$brand_id = set_value('brand_id');
		$generic_id = set_value('generic_id');
		$drug_administration_route_id = set_value('drug_administration_route_id');
		$drug_consumption_id = set_value('drug_consumption_id');
		$class_id = set_value('class_id');
		$quantity = set_value('quantity');
	}

    $drug_purchase_details = $this->pharmacy_model->get_drug_purchase_details($drugs_id);
    if(count($drug_purchase_details) > 0)
    {
        foreach ($drug_purchase_details as $key_details) {
            # code...
                $expiry_date = $key_details->expiry_date;
        }
    }
    else
    {
        $expiry_date = '';
    }
?>
 <section class="panel">
    <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
          </div>
          <div class="clearfix"></div>
        </header>             

        <!-- Widget content -->
        <div class="panel-body">
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
                        <label class="col-lg-4 control-label">Drug Name <span class="required">*</span>: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drugs_name" placeholder="Drug Name" value="<?php echo $drugs_name;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Unit Price: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drugs_unitprice" placeholder="Unit Price" value="<?php echo $drugs_unitprice;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Batch Number: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="batch_no" placeholder="Batch Number" value="<?php echo $batch_no;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Opening Quantity: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="quantity" placeholder="Opening Quantity" value="<?php echo $quantity;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Drug Type: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="drug_type_id">
                                <?php
                                    if(count($drug_types) > 0)
                                    {
                                        foreach($drug_types as $res)
                                        {
                                            $drug_type_id2 = $res->drug_type_id;
                                            $drug_type_name = $res->drug_type_name;
                                            
                                            if($drug_type_id2 == $drug_type_id)
                                            {
                                                echo '<option value="'.$drug_type_id2.'" selected>'.$drug_type_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_type_id2.'">'.$drug_type_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Administration Route: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="drug_administration_route_id">
                                <?php
                                    if(count($admin_routes) > 0)
                                    {
                                        foreach($admin_routes as $res)
                                        {
                                            $drug_administration_route_id2 = $res->drug_administration_route_id;
                                            $drug_administration_route_name = $res->drug_administration_route_name;
                                            
                                            if($drug_administration_route_id2 == $drug_administration_route_id)
                                            {
                                                echo '<option value="'.$drug_administration_route_id2.'" selected>'.$drug_administration_route_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_administration_route_id2.'">'.$drug_administration_route_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Consumption Method: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="drug_consumption_id">
                                <?php
                                    if(count($consumption) > 0)
                                    {
                                        foreach($consumption as $res)
                                        {
                                            $drug_consumption_id2 = $res->drug_consumption_id;
                                            $drug_consumption_name = $res->drug_consumption_name;
                                            
                                            if($drug_consumption_id2 == $drug_consumption_id)
                                            {
                                                echo '<option value="'.$drug_consumption_id2.'" selected>'.$drug_consumption_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_consumption_id2.'">'.$drug_consumption_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                   
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Pack Size: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drugs_pack_size" placeholder="Pack Size" value="<?php echo $drugs_pack_size;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Dose: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drug_dose" placeholder="Dose" value="<?php echo $drug_dose;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Dose Unit: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="drug_dose_unit_id">
                                <?php
                                    if(count($drug_dose_units) > 0)
                                    {
                                        foreach($drug_dose_units as $res)
                                        {
                                            $drug_dose_unit_id2 = $res->drug_dose_unit_id;
                                            $drug_dose_unit_name = $res->drug_dose_unit_name;
                                            
                                            if($drug_dose_unit_id2 == $drug_dose_unit_id)
                                            {
                                                echo '<option value="'.$drug_dose_unit_id2.'" selected>'.$drug_dose_unit_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_dose_unit_id2.'">'.$drug_dose_unit_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Brand: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="brand_id">
                                <?php
                                    if(count($drug_brands) > 0)
                                    {
                                        foreach($drug_brands as $res)
                                        {
                                            $brand_id2 = $res->brand_id;
                                            $brand_name = $res->brand_name;
                                            
                                            if($brand_id2 == $brand_id)
                                            {
                                                echo '<option value="'.$brand_id2.'" selected>'.$brand_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$brand_id2.'">'.$brand_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Class: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="class_id">
                                <?php
                                    if(count($drug_classes) > 0)
                                    {
                                        foreach($drug_classes as $res)
                                        {
                                            $class_id2 = $res->class_id;
                                            $class_name = $res->class_name;
                                            
                                            if($class_id2 == $class_id)
                                            {
                                                echo '<option value="'.$class_id2.'" selected>'.$class_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$class_id2.'">'.$class_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Generic: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="generic_id">
                                <?php
                                    if(count($drug_generics) > 0)
                                    {
                                        foreach($drug_generics as $res)
                                        {
                                            $generic_id2 = $res->generic_id;
                                            $generic_name = $res->generic_name;
                                            
                                            if($generic_id2 == $generic_id)
                                            {
                                                echo '<option value="'.$generic_id2.'" selected>'.$generic_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$generic_id2.'">'.$generic_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Expiry Date: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker1" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="expiry_date" placeholder="Expiry Date" value="<?php echo $expiry_date;?>">
                                <span class="add-on">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <br/>
            
            <div class="center-align" style="margin-top:10px;">
            	<a href="<?php echo site_url().'pharmacy/inventory';?>" class="btn btn-sm btn-default">Back</a>
                <button class="btn btn-info btn-sm" type="submit">Edit Drug</button>
            </div>
            <?php echo form_close();?>
            
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </section>