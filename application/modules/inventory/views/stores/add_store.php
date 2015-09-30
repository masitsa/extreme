 
<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel">
            <header class="panel-heading">
              <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
              <div class="widget-icons pull-right">
                    <a href="<?php echo base_url();?>inventory-setup/inventory-stores" class="btn btn-primary pull-right btn-sm">Back to stores</a>
              </div>
              <div class="clearfix"></div>
        </header>
        <div class="panel-body">
          
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <div class="row">
                <div class="col-md-6">
                    <!-- store Name -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Store Name</label>
                        <div class="col-lg-6">
                        	<input type="text" class="form-control" name="store_name" placeholder="store Name" value="<?php echo set_value('store_name');?>" required>
                        </div>
                    </div>
                    <!-- store Parent -->
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Store Parent</label>
                        <div class="col-lg-6">
                        	<select name="store_parent" class="form-control" required>
                            	<?php
        						echo '<option value="0">No Parent</option>';
        						if($all_stores->num_rows() > 0)
        						{
        							$result = $all_stores->result();
        							
        							foreach($result as $res)
        							{
        								if($res->store_id == set_value('store_parent'))
        								{
        									echo '<option value="'.$res->store_id.'" selected>'.$res->store_name.'</option>';
        								}
        								else
        								{
        									echo '<option value="'.$res->store_id.'">'.$res->store_name.'</option>';
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
                        <label class="col-lg-6 control-label">Activate store?</label>
                        <div class="col-lg-6">
                                
                                    <input id="optionsRadios1" type="radio" checked value="1" name="store_status">
                                    Yes
                              
                         
                                    <input id="optionsRadios2" type="radio" value="0" name="store_status">
                                    No
                               
                        </div>
                    </div>
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary btn-sm" type="submit">
                            Add store
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
    
</section>