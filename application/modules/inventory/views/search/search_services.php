<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Search Servicess</h2>
    </header>
    <div class="panel-body">
			<div class="row">
			
				<?php
				echo form_open("inventory/services-search", array("class" => "form-horizontal"));
	            ?>
	            <div class="row">
	           		<div class="col-md-11">
		                <div class="col-md-6">
		                    <div class="form-group">
		                        <label class="col-lg-5 control-label">Search Name: </label>
		                        
		                        <div class="col-lg-7">
		                            <input type="text" class="form-control" name="service_name" placeholder="Service Name">
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