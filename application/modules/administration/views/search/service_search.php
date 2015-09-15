   
 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i>Search <?php echo $title;?></h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
              </header>             

          <!-- Widget content -->
                <div class="panel-body">
          
			
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
            	<button type="submit" class="btn btn-info">Search</button>
            </div>
            <?php
            echo form_close();
            ?>
            </div>
        
		</section>