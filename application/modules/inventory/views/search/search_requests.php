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
			echo form_open("requests/requests-search", array("class" => "form-horizontal"));
            ?>
            <div class="row">
           		<div class="col-md-11">
	                <div class="col-md-6">
	                	<div class="form-group">
	                        <label class="col-lg-5 control-label">Request Number: </label>
	                        
	                        <div class="col-lg-7">
	                            <input type="text" class="form-control" name="request_number" placeholder="Request Number">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-lg-5 control-label">Request Date From: </label>
	                        
	                        <div class="col-lg-7">
	                            <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="request_from" placeholder="Request Date From" />
	                        </div>
	                    </div>
                        <div class="form-group">
	                        <label class="col-lg-5 control-label">Request Date To: </label>
	                        
	                        <div class="col-lg-7">
	                            <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="request_to" placeholder="Request Date To" />
	                        </div>
	                    </div>
	                  
	                    
	                </div>
	                
	                <div class="col-md-6">
	                     <div class="form-group">
	                        <label class="col-lg-5 control-label">Client Name: </label>
	                        
	                        <div class="col-lg-7">
	                            <input type="text" class="form-control" name="clients_name" placeholder="Client Name">
	                        </div>
	                    </div>
                        
	                    <div class="form-group">
	                        <label class="col-lg-5 control-label">Request Status: </label>
	                        
	                        <div class="col-lg-7">
	                            <select class="form-control" name="request_status">
                                <option value="">--Select Status--</option>
                                  <?php
                                	if($status_query->num_rows() > 0)
									{
										foreach($status_query->result() AS $key)
										{
										    $request_approval_status = $key->inventory_level_status_id;
											 $request_approval_status_name = $key->inventory_level_status_name;
											 ?>
                                              <option value="<?php echo $request_approval_status?>"><?php echo $request_approval_status_name;?></option>
                                             <?php
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