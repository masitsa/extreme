<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Search Items</h2>
    </header>
    <div class="panel-body">
			<div class="row">
			
				<?php
				echo form_open("inventory/items-search", array("class" => "form-horizontal"));
	            ?>
	            <div class="row">
	           		<div class="col-md-11">
		                <div class="col-md-6">
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Item Name: </label>
		                        
		                        <div class="col-lg-7">
		                            <input type="text" class="form-control" name="item_name" placeholder="Item Name">
		                        </div>
		                    </div>
		                </div>
		                
		                <div class="col-md-6">
		                    
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Item Category: </label>
		                        
		                        <div class="col-lg-7">
		                             <select name="item_category_id" id="item_category_id" class="form-control">
		                                <?php
		                                echo '<option value="0">No Category</option>';
		                                if($all_categories->num_rows() > 0)
		                                {
		                                    $result = $all_categories->result();
		                                    
		                                    foreach($result as $res)
		                                    {
		                                        if($res->item_category_id == set_value('item_category_id'))
		                                        {
		                                            echo '<option value="'.$res->item_category_id.'" selected>'.$res->category_name.'</option>';
		                                        }
		                                        else
		                                        {
		                                            echo '<option value="'.$res->item_category_id.'">'.$res->category_name.'</option>';
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