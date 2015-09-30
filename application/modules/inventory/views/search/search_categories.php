<div class="row">
	<!-- Widget -->
	<div class="widget boxed">
	    <!-- Widget head -->
        <div class="widget-head">
            <h4 class="pull-left"><i class="fa fa-search"></i>Search all categories</h4>
            <div class="clearfix"></div>
        </div>      
	    
	    <!-- Widget content -->
	    <div class="widget-content">
	    	<div class="">
				<?php
				echo form_open("vendor/search-categories", array("class" => "form-inline"));
	            ?>
                <div class="row">
                    <div class="col-md-12 col-lg-12 center-align">
                        <div class="form-group">
                            <label class="control-label">Category Name: </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="category_name" placeholder="Category name">
                            </div>
                                <button type="submit" class="btn btn-info btn-lg">Search</button>
                        </div>
                    </div>
	            </div>
	            
	            <?php
	            echo form_close();
	            ?>
	    	</div>
	    </div>
	</div>
</div>