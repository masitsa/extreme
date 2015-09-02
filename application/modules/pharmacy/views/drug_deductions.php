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
                        	<a href="<?php echo site_url().'/pharmacy/inventory';?>" class="btn btn-sm btn-default pull-right">Back</a>
                        	<a href="<?php echo site_url().'/pharmacy/deduct_drug/'.$drugs_id;?>" class="btn btn-sm btn-success">Deduct Drug</a>
                        	<div class="table-responsive">
                                <table border="0" class="table table-hover table-condensed">
                                    <thead> 
                                        <th>#</th>
                                        <th>Deduction Date</th>
                                        <th>Container</th>
                                        <th>Pack Size</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </thead>
                        
                                    <?php 
                                    //echo "current - ".$current_item."end - ".$end_item;
                                    $count = $page;
                                    $rs9 = $query->result();
                                    foreach ($rs9 as $rs10) :
                                        $deduction_date = $last_visit = date('jS M Y H:i:s',strtotime($rs10->stock_deductions_date));
                                        $stock_deduction_id = $rs10->stock_deductions_id;
                                        $container_name = $rs10->container_type_name;
                                        $stock_deduction_pack_size = $rs10->stock_deductions_pack_size;
                                        $stock_deduction_quantity = $rs10->stock_deductions_quantity;
                                        $count++;
                                    ?>
                                   <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $deduction_date;?></td>					         
                                        <td><?php echo $container_name;?></td>
                                        <td><?php echo $stock_deduction_pack_size;?></td>
                                        <td><?php echo $stock_deduction_quantity;?></td>
                                        <td><a href="<?php echo site_url().'/pharmacy/edit_drug_deduction/'.$stock_deduction_id.'/'. $drugs_id;?>" class="btn btn-sm btn-primary">Edit</a></td>
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