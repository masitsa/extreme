
      <div class="row">
        <div class="col-md-12">
              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
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
                                	<a href="<?php echo site_url().'/nurse/add_vaccine';?>" class="btn btn-sm btn-success">Add Vaccine</a>
                                	<div class="table-responsive">
                                        <table border="0" class="table table-responsive table-hover table-condensed">
                                            <thead> 
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Dose</th>
                                                <th>Unit Price</th>
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
                                                $vaccine_name = $rs10->vaccine_name;
                                                $vaccine_dose = $rs10->vaccine_dose;
                                                $vaccine_unitprice = $rs10->vaccine_unitprice;
                                                $quantity = $rs10->quantity;
                                                $vaccine_deleted = $rs10->vaccine_deleted;
                                                
                                                $profit_margin =  $vaccine_unitprice;
                                                $vaccine_id = $rs10->vaccine_id;
                                                $purchases = 0; // $this->pharmacy_model->item_purchases($vaccine_id);
                                                $sales = 0;// $this->pharmacy_model->get_vaccine_units_sold($vaccine_id);
                                                $deductions = 0;//$this->pharmacy_model->item_deductions($vaccine_id);
                                                $in_stock = ($quantity + $purchases) - $sales - $deductions;
                                                $count++;
												
												if($vaccine_deleted == 0)
												{
													$button = '<a href="'.site_url().'/nurse/activation/deactivate/'.$page.'/'.$vaccine_id.'" class="btn btn-sm btn-default" onclick="return confirm(\'Do you want to disable '.$vaccine_name.'?\');">Disable</a>';
												}
												
												else
												{
													$button = '<a href="'.site_url().'/nurse/activation/activate/'.$page.'/'.$vaccine_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to enable '.$vaccine_name.'?\');">Enable</a>';
												}
                                            ?>
                                           <tr>
                                                <td><?php echo $count;?></td>
                                                <td><?php echo $vaccine_name;?></td>
                                                <td><?php echo $vaccine_dose;?></td>							         
                                                <td><?php echo $vaccine_unitprice;?></td>
                                                <td><?php echo $profit_margin;?></td>						         
                                                <td><?php echo $quantity;?></td>						         
                                                <td><?php echo $purchases;?></td>
                                                <td><?php echo $sales;?></td>						         
                                                <td><?php echo $deductions;?></td>
                                                <td><?php echo $in_stock;?></td>
                                                <td><a href="<?php echo site_url().'/nurse/edit_vaccine/'.$vaccine_id;?>" class="btn btn-sm btn-primary">Edit</a></td>
                                                <td><a href="<?php echo site_url().'/nurse/vaccine_purchases/'.$vaccine_id;?>" class="btn btn-sm btn-warning">Purchases</a></td>
                                                <td><a href="<?php echo site_url().'/nurse/vaccine_deductions/'.$vaccine_id;?>" class="btn btn-sm btn-info">Deductions</a></td>
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
            </div>
        </div>