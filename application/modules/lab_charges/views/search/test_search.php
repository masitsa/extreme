 <section class="panel">
    <header class="panel-heading">
        <h4 class="pull-left"><i class="icon-reorder"></i>Search Laboratory Tests </h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </header>             
    
       <!-- Widget content -->
        <div class="panel-body">
    	<div class="padd">
			<?php
            echo form_open("lab_charges/search_lab_tests", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Laboratory Test: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="lab_test_name" placeholder="Test name">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Laboratory Test Class: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="test_class" placeholder="Test class">
                        </div>
                    </div>
                    
                
                </div>
            </div>
            
            <div class="center-align">
            	<button type="submit" class="btn btn-info btn-sm">Search lab tests</button>
            </div>
            
            <?php
            echo form_close();
            ?>
    	</div>
    </div>
</section>