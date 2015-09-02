<!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Search Service Charge</h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
    <div class="widget-content">
    	<div class="padd">
			
			<?php echo form_open("administration/service_search/", array("class" => "form-horizontal"));?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Service  name: </label>
                        
                        <div class="col-lg-8">
                          	 <input type="text" class="form-control" name="service_name" placeholder=" Service name">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="center-align">
            	<button type="submit" class="btn btn-info btn-lg">Search</button>
            </div>
            <?php
            echo form_close();
            ?>
    	</div>
    </div>
</div>