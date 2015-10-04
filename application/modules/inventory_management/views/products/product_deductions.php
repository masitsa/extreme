 <section class="panel">
        <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-default">Back to inventory</a>
                <!-- <a href="#user" class="btn btn-primary  btn-sm" data-toggle="modal">View Order Details</a>
              
                <div id="user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title"> <span class="bold">Order Number : </span>dsfgsjdfj</h4>
                            </div>
                            
                            <div class="modal-body">
                                '.$items.'
                            </div>
                            <div class="modal-footer">
                                '.$button.'
                                <button type="button" class="btn btn-default btn-sm " data-dismiss="modal" aria-hidden="true">Close</button>
                            </div>
                        </div>
                    </div>
                </div> -->
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
                    	<div class="table-responsive">
                            <table border="0" class="table table-hover table-condensed">
                                <thead> 
                                    <th>#</th>
                                  	<th>Ordering Store</th>
                                    <th>Product Name</th>
                                    <th>Sub Store QTY</th>
                                    <th>In Store QTY</th>
                                    <th>QTY Requested</th>
                                    <th>QTY Received</th>
                                    <th>Order Status</th>
                                    <th>QTY Given</th>
                                    <th></th>
                                </thead>
                    
                                <?php 
                                //echo "current - ".$current_item."end - ".$end_item;
                                $count = $page;
                                $rs9 = $query->result();
                                foreach ($rs9 as $rs10) :
                                    $deduction_date = $last_visit = date('jS M Y H:i:s',strtotime($rs10->product_deductions_date));
                                    $product_deduction_id = $rs10->product_deductions_id;
                                    $product_deduction_pack_size = $rs10->product_deductions_pack_size;
                                    $product_deduction_quantity = $rs10->product_deductions_quantity;
                                    $parent_store_qty = $rs10->parent_store_qty;
                                    $quantity_requested = $rs10->quantity_requested;
                                    $quantity_received = $rs10->quantity_received;
                                    $quantity_given = $rs10->quantity_given;
                                    $store_name = $rs10->store_name;
                                    $product_name = $rs10->product_name;
                                    $product_deductions_status = $rs10->product_deductions_status;
                                    $product_id = $rs10->product_id;
                                    $store_id = $rs10->store_id;
                                    $sub_store_quantity = $this->inventory_management_model->get_store_inventory_quantity($store_id,$product_id);

                                    // calculate the current stoe
                                    if($product_deductions_status == 0)
                                    {
                                        $status = '<span class="label label-warning">Not Awarded</span>';
                                        $input_filed = 
                                        '
                                         <td><input type="text" class="form-control" id="quantity_given'.$product_deduction_id.'" name="quantity_given'.$product_deduction_id.'" size="1" value="'.$quantity_given.'"></td>
                                            <td><a id="update_action_point_form"  onclick="update_quantity('.$product_deduction_id.','.$store_id.')" class="btn btn-sm btn-warning fa fa-pencil"> Award</a></td>
                                        ';
                                    }
                                    //create activated status display
                                    else if($product_deductions_status == 1)
                                    {
                                        $status = '<span class="label label-info">Awarded</span>';
                                        $input_filed = 
                                            '
                                            <td><input type="text" class="form-control" id="quantity_given'.$product_deduction_id.'" name="quantity_given'.$product_deduction_id.'" size="1" value="'.$quantity_given.'"></td>
                                            <td><a id="update_action_point_form"  onclick="update_quantity('.$product_deduction_id.','.$store_id.')" class="btn btn-sm btn-warning fa fa-pencil">Update Award</a></td>
                                            ';
                                    }
                                    else if($product_deductions_status == 2)
                                    {
                                        $status = '<span class="label label-success">Received</span>';
                                         $input_filed = 
                                            '
                                            <td><input type="text" class="form-control" id="quantity_given'.$product_deduction_id.'" name="quantity_given'.$product_deduction_id.'" size="1" value="'.$quantity_given.'" readonly></td>
                                            ';
                                    }

                                    $count++;
                                ?>
                               <tr>

                                    <td><?php echo $count;?></td>
                                    <td><?php echo $store_name;?></td>
                                    <td><?php echo $product_name;?></td>
                                    <td><?php echo $sub_store_quantity;?></td>
                                    <td><?php echo $parent_store_qty;?></td>
                                    <td><?php echo $quantity_requested;?></td>
                                    <td><?php echo $quantity_received;?></td>
                                     <td><?php echo $status;?></td>
                                    <?php echo $input_filed;?>
                                    
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
</section>
<script type="text/javascript">
    
    function update_quantity(product_deductions_id,store_id)
    {
      
       //var product_deductions_id = $(this).attr('href');
       var quantity = $('#quantity_given'+product_deductions_id).val();
       var url = "<?php echo base_url();?>inventory/award-store-order/"+product_deductions_id+'/'+quantity;
  
        $.ajax({
           type:'POST',
           url: url,
           data:{quantity: quantity},
           cache:false,
           contentType: false,
           processData: false,
           dataType: 'json',
           success:function(data){
            
            window.alert(data.result);
            window.location.href = "<?php echo base_url();?>inventory/product-deductions";
           },
           error: function(xhr, status, error) {
            alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
           
           }
        });
        return false;
     }
</script>