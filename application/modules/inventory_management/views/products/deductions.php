<?php
	$segment = 4;
        $order = 'product_deductions.product_deductions_date';
        $where = 'store_product.store_id = product_deductions.store_id AND store_product.store_id = store.store_id AND product_deductions.product_id = product.product_id AND store_product.product_id = '.$product_id;
        
        $product_search = $this->session->userdata('product_deductions_search');
        
        if(!empty($product_search))
        {
            $where .= $product_search;
        }
        
        $table = 'product_deductions,store,store_product,product';
        
        //pagination
        $this->load->library('pagination');
        $config['base_url'] = site_url().'inventory/product-deductions/'.$product_id;
        $config['total_rows'] = $this->reception_model->count_items($table, $where);
        $config['uri_segment'] = $segment;
        $config['per_page'] = 20;
        $config['num_links'] = 5;
        
        $config['full_tag_open'] = '<ul class="pagination pull-right">';
        $config['full_tag_close'] = '</ul>';
        
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        
        $config['next_tag_open'] = '<li>';
        $config['next_link'] = 'Next';
        $config['next_tag_close'] = '</span>';
        
        $config['prev_tag_open'] = '<li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
        $query = $this->inventory_management_model->get_product_deductions($table, $where, $config["per_page"], $page, $order);
        
        $v_data['query'] = $query;
        $v_data['page'] = $page;
        $v_data['product_id'] = $product_id;
        
        $data['title'] = 'product List';
        $v_data['title'] = 'product List';
        $data['sidebar'] = 'pharmacy_sidebar';
        $product_details = $this->inventory_management_model->get_product_details($product_id);
        
        $v_data['title'] = $product_details[0]->product_name.' Deductions';
?>

 <section class="panel">
    <header class="panel-heading">
      <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
      <div class="widget-icons pull-right">
        <a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-default">Back to inventory</a>
        <a href="<?php echo site_url().'inventory/deduct-product/'.$product_id;?>" class="btn btn-sm btn-success">Deduct Product</a>
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
                                      	<th>Sub Store</th>
                                        <th>Deduction Date</th>
                                        <th>Pack Size</th>
                                        <th>Quantity</th>
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
                                        $count++;
                                    ?>
                                   <tr>
                                        <td><?php echo $count;?></td>
                                        <td></td>
                                        <td><?php echo $deduction_date;?></td>	
                                        <td><?php echo $product_deduction_pack_size;?></td>
                                        <td><?php echo $product_deduction_quantity;?></td>
                                        <td><a href="<?php echo site_url().'/pharmacy/edit_drug_deduction/'.$product_deduction_id.'/'. $drugs_id;?>" class="btn btn-sm btn-primary">Edit</a></td>
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