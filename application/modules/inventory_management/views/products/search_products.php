<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Search Products</h2>
    </header>
    <div class="panel-body">
			<div class="row">
			
				<?php
				echo form_open("inventory/search-products", array("class" => "form-horizontal"));
	            ?>
	            <div class="row">
	           		<div class="col-md-11">
		                <div class="col-md-4">
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Product Name: </label>
		                        
		                        <div class="col-lg-7">
		                            <input type="text" class="form-control" name="product_name" placeholder="Product Name">
		                        </div>
		                    </div>
		                </div>
		                <div class="col-md-4">
							 <div class="form-group">
		                        <label class="col-lg-5 control-label">Brand Name: </label>
		                        
		                        <div class="col-lg-7">
		                             <select name="brand_id" id="brand_id" class="form-control">
		                                <?php
		                                echo '<option value="0">No Brand</option>';
		                                if($all_brands->num_rows() > 0)
		                                {
		                                    $result = $all_brands->result();
		                                    
		                                    foreach($result as $res)
		                                    {
		                                        if($res->brand_id == set_value('brand_id'))
		                                        {
		                                            echo '<option value="'.$res->brand_id.'" selected>'.$res->brand_name.'</option>';
		                                        }
		                                        else
		                                        {
		                                            echo '<option value="'.$res->brand_id.'">'.$res->brand_name.'</option>';
		                                        }
		                                    }
		                                }
		                                ?>
		                            </select>
		                        </div>
		                    </div>
							 <div class="form-group">
		                        <label class="col-lg-5 control-label">Generic Name: </label>
		                        
		                        <div class="col-lg-7">
		                             <select name="generic_id" id="generic_id" class="form-control">
		                                <?php
		                                echo '<option value="0">No generic</option>';
		                                if($all_generics->num_rows() > 0)
		                                {
		                                    $result = $all_generics->result();
		                                    
		                                    foreach($result as $res)
		                                    {
		                                        if($res->generic_id == set_value('generic_id'))
		                                        {
		                                            echo '<option value="'.$res->generic_id.'" selected>'.$res->generic_id.'</option>';
		                                        }
		                                        else
		                                        {
		                                            echo '<option value="'.$res->generic_id.'">'.$res->generic_name.'</option>';
		                                        }
		                                    }
		                                }
		                                ?>
		                            </select>
		                        </div>
		                    </div>
						</div>
		                <div class="col-md-4">
		                    
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Product Category: </label>
		                        
		                        <div class="col-lg-7">
		                             <select name="category_id" id="category_id" class="form-control">
		                                <?php
		                                echo '<option value="0">No Category</option>';
		                                if($all_categories->num_rows() > 0)
		                                {
		                                    $result = $all_categories->result();
		                                    
		                                    foreach($result as $res)
		                                    {
		                                        if($res->category_id == set_value('category_id'))
		                                        {
		                                            echo '<option value="'.$res->category_id.'" selected>'.$res->category_name.'</option>';
		                                        }
		                                        else
		                                        {
		                                            echo '<option value="'.$res->category_id.'">'.$res->category_name.'</option>';
		                                        }
		                                    }
		                                }
		                                ?>
		                            </select>
		                        </div>
		                    </div>
		                    
		                </div>
		              </div>
	            </div>
	            <br/>
	            <div class="center-align">
					<?php
					$product_inventory_search = $this->session->userdata('product_inventory_search');
					
					if(!empty($product_inventory_search))
					{
						?>
						<a href="<?php echo site_url().'inventory_management/close_inventory_search'?>" class="btn btn-sm btn-warning">Close search</a>
						<?php
					}
					?>
	            	<button type="submit" class="btn btn-info btn-sm">Search</button>
	            </div>
	            <?php
	            echo form_close();
	            ?>
				    	
			</div>
		</div>
	</section>