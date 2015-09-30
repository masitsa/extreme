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
		                <div class="col-md-6">
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Product Name: </label>
		                        
		                        <div class="col-lg-7">
		                            <input type="text" class="form-control" name="product_name" placeholder="Product Name">
		                        </div>
		                    </div>
		                     <div class="form-group">
		                        <label class="col-lg-5 control-label">Product Code: </label>
		                        
		                        <div class="col-lg-7">
		                            <input type="text" class="form-control" name="product_code" placeholder="Product code">
		                        </div>
		                    </div>
		                </div>
		                
		                <div class="col-md-6">
		                    
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
	            
	            <div class="center-align">
	            	<button type="submit" class="btn btn-info btn-sm">Search</button>
	            </div>
	            <?php
	            echo form_close();
	            ?>
				    	
			</div>
		</div>
	</section>