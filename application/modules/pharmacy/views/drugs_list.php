  <?php echo $this->load->view('search/search_drugs', '', TRUE);?>
    <section class="panel">
    <header class="panel-heading">
      <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
      <div class="widget-icons pull-right">
        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
        <a href="#" class="wclose"><i class="icon-remove"></i></a>
      </div>
      <div class="clearfix"></div>
    </header>             

        <!-- Widget content -->
            <div class="panel-body">
                <div class="padd">
                  <div class="center-align">
                    <?php
                        $search = $this->session->userdata('drugs_inventory_search');

                        if(!empty($search))
                        {
                            echo '<a href="'.site_url().'/pharmacy/close_inventory_search" class="btn btn-warning">Close Search</a>';
                        }
                        $result = '';
                        $error = $this->session->userdata('error_message');
                        $success = $this->session->userdata('success_message');
                        
                        if(!empty($error))
                        {
                            echo '<div class="alert alert-danger">'.$error.'</div>';
                            $this->session->unset_userdata('error_message');
                        }
                        
                        if(!empty($success))
                        {
                            echo '<div class="alert alert-success">'.$success.'</div>';
                            $this->session->unset_userdata('success_message');
                        }
                    ?>
                  </div>
                  
                    <div class="row">
                        <div class="col-md-12">
                        	<a href="<?php echo site_url().'/pharmacy/add_drug';?>" class="btn btn-sm btn-success">Add Drug</a>
                        	<div class="table-responsive">
                                <table border="0" class="table table-hover table-condensed">
                                    <thead> 
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Dose</th>
                                        <th>Dose Unit</th>
                                        <th>Type</th>
                                        <th>Unit Price</th>
                                        <th>33% MU</th>
                                        <th>Profit</th>
                                        <th>Opening</th>
                                        <th>P</th>
                                        <th>S</th>
                                        <th>D</th>
                                        <th>Stock</th>
                                    </thead>
                        
                                    <?php 
                                    //echo "current - ".$current_item."end - ".$end_item;
                                    $count = $page;
                                    $rs9 = $query->result();
                                    foreach ($rs9 as $rs10) :
                                        $drug_name = $rs10->drugs_name;
                                        $drugs_dose = $rs10->drugs_dose;
                                        $drug_dose_unit_name = $rs10->drug_dose_unit_name;
                                        $drug_type_name = $rs10->drug_type_name;
                                        $drugs_unitprice = $rs10->drugs_unitprice;
                                        $quantity = $rs10->quantity;
                                        $drugs_deleted = $rs10->drugs_deleted;
                                        $markup = round(($drugs_unitprice * 1.33), 0);
                                        $markdown = $markup;//round(($markup * 0.9), 0);
                                        $profit_margin = $markdown - $drugs_unitprice;
                                        $drugs_id = $rs10->drugs_id;
                                        $purchases = $this->pharmacy_model->item_purchases($drugs_id);
                                        $sales = $this->pharmacy_model->get_drug_units_sold($drugs_id);
                                        $deductions = $this->pharmacy_model->item_deductions($drugs_id);
                                        $in_stock = ($quantity + $purchases) - $sales - $deductions;
                                        $count++;
										
										if($drugs_deleted == 0)
										{
											$button = '<a href="'.site_url().'/pharmacy/activation/deactivate/'.$page.'/'.$drugs_id.'" class="btn btn-sm btn-default" onclick="return confirm(\'Do you want to disable '.$drug_name.'?\');">Disable</a>';
										}
										
										else
										{
											$button = '<a href="'.site_url().'/pharmacy/activation/activate/'.$page.'/'.$drugs_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to enable '.$drug_name.'?\');">Enable</a>';
										}
                                    ?>
                                   <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $drug_name;?></td>
                                        <td><?php echo $drugs_dose;?></td>							         
                                        <td><?php echo $drug_dose_unit_name;?></td>
                                        <td><?php echo $drug_type_name;?></td>						         
                                        <td><?php echo $drugs_unitprice;?></td>
                                        <td><?php echo $markup;?></td>	
                                        <td><?php echo $profit_margin;?></td>						         
                                        <td><?php echo $quantity;?></td>						         
                                        <td><?php echo $purchases;?></td>
                                        <td><?php echo $sales;?></td>						         
                                        <td><?php echo $deductions;?></td>
                                        <td><?php echo $in_stock;?></td>
                                        <td><a href="<?php echo site_url().'/pharmacy/edit_drug/'.$drugs_id;?>" class="btn btn-sm btn-primary">Edit</a></td>
                                        <td><a href="<?php echo site_url().'/pharmacy/drug_purchases/'.$drugs_id;?>" class="btn btn-sm btn-warning">Purchases</a></td>
                                        <td><a href="<?php echo site_url().'/pharmacy/drug_deductions/'.$drugs_id;?>" class="btn btn-sm btn-info">Deductions</a></td>
                                        <td><?php echo $button;?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </table>
                        	</div>
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
 </section>