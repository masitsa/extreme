<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
         <h2 class="panel-title pull-left">Search Requests</h2>
         <div class="widget-icons pull-right">
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
		<div class="row">

			<?php
			echo form_open("vendor/search-requests", array("class" => "form-horizontal"));
            ?>
            <div class="row">
           		<div class="col-md-11">
	                <div class="col-md-6">
	                	<div class="form-group">
	                        <label class="col-lg-5 control-label">Request Number: </label>
	                        
	                        <div class="col-lg-7">
	                            <input type="text" class="form-control" name="order_number" placeholder="Order number">
	                        </div>
	                    </div>
	                    
	                  
	                    
	                </div>
	                
	                <div class="col-md-6">
	                     <div class="form-group">
	                        <label class="col-lg-5 control-label">Customer Surname: </label>
	                        
	                        <div class="col-lg-7">
	                            <input type="text" class="form-control" name="customer_surname" placeholder="Customer surname">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-lg-5 control-label">Customer First name: </label>
	                        
	                        <div class="col-lg-7">
	                            <input type="text" class="form-control" name="customer_first_name" placeholder="Customer first name">
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