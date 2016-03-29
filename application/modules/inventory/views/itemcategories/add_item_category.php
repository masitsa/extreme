 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>inventory-setup/item-categories" class="btn btn-primary pull-right btn-sm">Back to Item categories</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Item Category Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Item Category Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="category_name" placeholder="Item Category Name" value="<?php echo set_value('category_name');?>" required>
                        </div>
                    </div>
                    <!-- Item Category Parent -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Item Category Parent</label>
                        <div class="col-lg-6">
                        	<select name="category_parent" class="form-control" required>
                            	<?php
        						echo '<option value="0">No Parent</option>';
        						if($all_categories->num_rows() > 0)
        						{
        							$result = $all_categories->result();
        							
        							foreach($result as $res)
        							{
        								if($res->category_id == set_value('category_parent'))
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
                <div class="col-md-6">
                    <!-- Activate checkbox -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Activate Item Category?</label>
                        <div class="col-lg-6">
                                
                                    <input id="optionsRadios1" type="radio" checked value="1" name="category_status">
                                    Yes
                              
                         
                                    <input id="optionsRadios2" type="radio" value="0" name="category_status">
                                    No
                               
                        </div>
                    </div>
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add Item Category
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>