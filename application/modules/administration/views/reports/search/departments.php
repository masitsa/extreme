
		<!-- Widget -->
		<section class="panel">


			<!-- Widget head -->
			<header class="panel-heading">
				<h2 class="panel-title">Search <?php echo $title;?></h2>
			</header>             

			<!-- Widget content -->
			<div class="panel-body">
			<?php
            echo form_open("administration/reports/search_departments", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Date From: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker1" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="visit_date_from" placeholder="Visit Date From">
                                <span class="add-on" style="cursor:pointer;">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Date To: </label>
                        
                        <div class="col-lg-8">
                            <div id="datetimepicker_other_patient" class="input-append">
                                <input data-format="yyyy-MM-dd" class="form-control" type="text" name="visit_date_to" placeholder="Visit Date To">
                                <span class="add-on" style="cursor:pointer;">
                                    &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                            </div>
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