<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search drugs</h2>
    </header>
    
    <div class="panel-body">

        <?php 
        $validation_error = validation_errors();
        
        if(!empty($validation_error))
        {
            echo '<div class="alert alert-danger">'.$validation_error.'</div>';
        }
        echo form_open('pharmacy/search_drugs/'.$visit_id.'/'.$module, array('class'=>'form-inline'));
        ?>
        
        
         <div class="row" style="margin-bottom:5px;">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-6 control-label">Drug Name: </label>
                    
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="search_item" placeholder="Drug name">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 <div class="form-group">
                    <label class="col-md-6 control-label">Generic Name: </label>
                    
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="generic_name" placeholder="Generic name">
                    </div>
                </div>
            </div>
            
        </div>
        <input type="hidden" value="<?php echo $visit_id?>" name="visit_id">
        <div class="center-align">
            <div class="form-group">
                <?php
                $search = $this->session->userdata('product_search');
                if(!empty($search))
                {
                ?>
                <a href="<?php echo site_url().'pharmacy/close_drugs_search/'.$visit_id;?>" class="btn btn-warning">Close Search</a>
                <?php }?>
                <input type="submit" class="btn btn-info" value="Search" name="search"/>
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</section>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Drugs List</h2>
                <!-- Widget content -->
                    <div class="panel-body">
                        <div class="padd">
                          
                            <div class="row">
                                <div class="col-md-12">
                                    <table border="0" class="table table-hover table-condensed">
                                        <thead> 
                                            <th>Name</th>
                                            <th>Generic</th>
                                            <th>Brand</th>
                                            <th>In Stock</th>
                                            <th>Price</th>
                                        </thead>
                            
                                        <?php 
                                        //echo "current - ".$current_item."end - ".$end_item;
                                        
                                        $rs9 = $query->result();
                                        foreach ($rs9 as $rs10) :
                                        
                                        
	                                       	$service_charge_id = $rs10->service_charge_id;
											$service_charge_name = $rs10->service_charge_name;
                                            $product_id = $rs10->product_id;
											$productcost = $rs10->service_charge_amount;
											$generic_name = $rs10->generic_name;
											$brand_name = $rs10->brand_name;
											$visit_type_idv = $rs10->visit_type_id;
                                            $quantity = $rs10->quantity;
                                            $purchases = $this->inventory_management_model->item_purchases($product_id);
                                            $sales = $this->inventory_management_model->get_product_units_sold($product_id);
                                            $deductions = $this->inventory_management_model->item_deductions($product_id);
                                            $in_stock = ($quantity + $purchases) - $sales - $deductions;
                                        
                                        ?>
                                       <tr>
							            <tr> </tr>
                                    <?php
									if($module == 1)
									{
										?> 
							        		<td><a onClick="close_drug('<?php echo $service_charge_name;?>', <?php echo $visit_id?>, <?php echo $service_charge_id;?>,<?php echo $module?>)" href="#"><?php echo $service_charge_name?></a></td>
                                    	<?php
									}
									
									else
									{
										?> 
							        		<td><a onClick="close_drug_soap('<?php echo $service_charge_name;?>', <?php echo $visit_id?>, <?php echo $service_charge_id;?>)" href="#"><?php echo $service_charge_name?></a></td>
                                    	<?php
									}
									?>
							                <td><?php echo $generic_name;?></td>
							                <td><?php echo $brand_name;?></td>
                                            <td><?php echo $in_stock;?></td>
							                <td><?php echo $productcost;?></td>
							        	</tr>
                                        <?php endforeach;?>
                                    </table>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                        
                    <div class="widget-foot">
                    <?php
                    if(isset($links)){echo $links;}
                    ?>
                    </div>
                 </div>
            </div>
  </div>
  </section>
<script type="text/javascript">
  
function close_drug(val, visit_id, service_charge_id,module){
	window.close(this);
	var config_url = $('#config_url').val();
	window.opener.location.href = config_url+"pharmacy/prescription/"+visit_id+"/"+service_charge_id+"/"+module;
}
  
function close_drug_soap(val, visit_id, service_charge_id){

	window.close(this);
	var config_url = $('#config_url').val();
	window.opener.location.href = config_url+"pharmacy/prescription/"+visit_id+"/"+service_charge_id;
	/*window.open(config_url+"pharmacy/prescription/"+visit_id+"/"+service_charge_id,"Popup","height=1200,width=1300,,scrollbars=yes,"+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");*/
}
function close_drug1(val, visit_id, service_charge_id){

    window.close(this);
    var config_url = $('#config_url').val();
    window.opener.location.href = config_url+"pharmacy/prescription/"+visit_id+"/"+service_charge_id;
    
    // window.open(config_url+"/pharmacy/prescription/"+visit_id+"/"+service_charge_id,"Popup","height=1200,width=1300,,scrollbars=yes,"+ 
 //                        "directories=yes,location=yes,menubar=yes," + 
 //                         "resizable=no status=no,history=no top = 50 left = 100"); 
}
</script>
