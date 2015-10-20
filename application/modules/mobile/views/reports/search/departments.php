
		<!-- Widget -->
		<section class="panel">


			<!-- Widget head -->
			<header class="panel-heading">
				<h2 class="panel-title">Search <?php echo $title;?></h2>
			</header>             

			<!-- Widget content -->
			<div class="panel-body">
			<form id="department_search" method="post">
            <div class="row">
                <div class="col-md-6">
                  

                     <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Date From: </label>
                        
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date_from" placeholder="Visit Date From">
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                
                <div class="col-md-6">
                    
                   
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Date To: </label>
                        
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date_to" placeholder="Visit Date To">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <br>
            <div class="center-align">
            	<button type="submit" class="btn btn-info">Search</button>
            </div>
            </form>
          
            </div>
		</section>