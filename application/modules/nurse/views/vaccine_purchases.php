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
                        	<a href="<?php echo site_url().'/nurse/inventory';?>" class="btn btn-sm btn-default pull-right">Back</a>
                        	<a href="<?php echo site_url().'/nurse/purchase_vaccine/'.$vaccine_id;?>" class="btn btn-sm btn-success">Purchase Vaccine</a>
                        	<div class="table-responsive">
                                <table border="0" class="table table-hover table-condensed">
                                    <thead> 
                                        <th>#</th>
                                        <th>Purchase Date</th>
                                        <th>Expiry Date</th>
                                        <th>Pack Size</th>
                                        <th>Quantity</th>
                                        <th></th>
                                    </thead>
                        
                                    <?php 
                                    //echo "current - ".$current_item."end - ".$end_item;
                                    $count = $page;
                                    $rs9 = $query->result();
                                    foreach ($rs9 as $rs10) :
                                        $purchase_date = $last_visit = date('jS M Y H:i:s',strtotime($rs10->purchase_date));
                                        $expiry_date = $last_visit = date('jS M Y',strtotime($rs10->expiry_date));
                                        $purchase_id = $rs10->purchase_id;
                                        $purchase_pack_size = $rs10->purchase_pack_size;
                                        $purchase_quantity = $rs10->purchase_quantity;
                                        $count++;
                                    ?>
                                   <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $purchase_date;?></td>
                                        <td><?php echo $expiry_date;?></td>		
                                        <td><?php echo $purchase_pack_size;?></td>						         
                                        <td><?php echo $purchase_quantity;?></td>
                                        <td><a href="<?php echo site_url().'/nurse/edit_vaccine_purchase/'.$purchase_id.'/'.$vaccine_id;?>" class="btn btn-sm btn-primary">Edit</a></td>
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