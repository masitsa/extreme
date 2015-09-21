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
                            <input type="text" class="form-control" name="drugs_name" placeholder="Drug Name" value="<?php echo set_value('drugs_name');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Unit Price: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drugs_unitprice" placeholder="Unit Price" value="<?php echo set_value('drugs_unitprice');?>">
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
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Drug Type: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="drug_type_id">
                                <?php
                                    if(count($drug_types) > 0)
                                    {
                                        foreach($drug_types as $res)
                                        {
                                            $drug_type_id = $res->drug_type_id;
                                            $drug_type_name = $res->drug_type_name;
                                            
                                            if($drug_type_id == set_value("drug_type_id"))
                                            {
                                                echo '<option value="'.$drug_type_id.'" selected>'.$drug_type_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_type_id.'">'.$drug_type_name.'</option>';
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
                                            $drug_administration_route_id = $res->drug_administration_route_id;
                                            $drug_administration_route_name = $res->drug_administration_route_name;
                                            
                                            if($drug_administration_route_id == set_value("drug_administration_route_id"))
                                            {
                                                echo '<option value="'.$drug_administration_route_id.'" selected>'.$drug_administration_route_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_administration_route_id.'">'.$drug_administration_route_name.'</option>';
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
                                            $drug_consumption_id = $res->drug_consumption_id;
                                            $drug_consumption_name = $res->drug_consumption_name;
                                            
                                            if($drug_consumption_id == set_value("drug_consumption_id"))
                                            {
                                                echo '<option value="'.$drug_consumption_id.'" selected>'.$drug_consumption_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_consumption_id.'">'.$drug_consumption_name.'</option>';
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
                            <input type="text" class="form-control" name="drugs_pack_size" placeholder="Pack Size" value="<?php echo set_value('drugs_pack_size');?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Dose: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="drug_dose" placeholder="Dose" value="<?php echo set_value('drug_dose');?>">
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
                                            $drug_dose_unit_id = $res->drug_dose_unit_id;
                                            $drug_dose_unit_name = $res->drug_dose_unit_name;
                                            
                                            if($drug_dose_unit_id == set_value("drug_dose_unit_id"))
                                            {
                                                echo '<option value="'.$drug_dose_unit_id.'" selected>'.$drug_dose_unit_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$drug_dose_unit_id.'">'.$drug_dose_unit_name.'</option>';
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
                                            $brand_id = $res->brand_id;
                                            $brand_name = $res->brand_name;
                                            
                                            if($brand_id == set_value("brand_id"))
                                            {
                                                echo '<option value="'.$brand_id.'" selected>'.$brand_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$brand_id.'">'.$brand_name.'</option>';
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
                                            $class_id = $res->class_id;
                                            $class_name = $res->class_name;
                                            
                                            if($class_id == set_value("class_id"))
                                            {
                                                echo '<option value="'.$class_id.'" selected>'.$class_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$class_id.'">'.$class_name.'</option>';
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
                                            $generic_id = $res->generic_id;
                                            $generic_name = $res->generic_name;
                                            
                                            if($generic_id == set_value("generic_id"))
                                            {
                                                echo '<option value="'.$generic_id.'" selected>'.$generic_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$generic_id.'">'.$generic_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="center-align" style="margin-top:10px;">
            	<a href="<?php echo site_url().'pharmacy/inventory';?>" class="btn btn-success btn-sm">Back</a>
                <button class="btn btn-info btn-sm" type="submit">Add Drug</button>
            </div>
            <?php echo form_close();?>
            
          </div>
        </div>
        <!-- Widget ends -->

      </div>
</section>