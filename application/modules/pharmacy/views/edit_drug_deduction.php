<?php
	$container_type_id = $deduction_details->container_type_id;
	$stock_deduction_quantity = $deduction_details->stock_deductions_quantity;
	$stock_deduction_pack_size = $deduction_details->stock_deductions_pack_size;
	
	if(!empty($validation_errors))
	{
		$container_type_id = set_value('container_type_id');
		$stock_deduction_quantity = set_value('stock_deduction_quantity');
		$stock_deduction_pack_size = set_value('stock_deduction_pack_size');
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
                
                <div class="col-md-offset-3 col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Container Type: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="container_type_id">
                                <?php
                                    if(count($container_types) > 0)
                                    {
                                        foreach($container_types as $res)
                                        {
                                            $container_type_id = $res->container_type_id;
                                            $container_type_name = $res->container_type_name;
                                            
                                            if($container_type_id == set_value("container_type_id"))
                                            {
                                                echo '<option value="'.$container_type_id.'" selected>'.$container_type_name.'</option>';
                                            }
                                            
                                            else
                                            {
                                                echo '<option value="'.$container_type_id.'">'.$container_type_name.'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Deduction Quantity: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="stock_deduction_quantity" placeholder="Deduction Quantity" value="<?php echo $stock_deduction_quantity;?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Pack Size: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="stock_deduction_pack_size" placeholder="Pack Size" value="<?php echo $stock_deduction_pack_size;?>">
                        </div>
                    </div>
                   
                </div>
            </div>
            <br/>
            <div class="center-align">
            	<a href="<?php echo site_url().'pharmacy/drug_deductions/'.$drugs_id;?>" class="btn btn-sm btn-default">Back</a>
                <button class="btn btn-info btn-sn" type="submit">Edit Deduction</button>
            </div>
            <?php echo form_close();?>
            
          </div>
        </div>
        <!-- Widget ends -->

      </div>
 </section>