<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search containers</h2>
    </header>             
    
    <div class="panel-body">
		<?php
        echo form_open("pharmacy/search_container_type", array("class" => "form-horizontal"));
        ?>
        <div class="row">
            <div class="col-md-10">
                
                <div class="form-group">
                    <label class="col-lg-4 control-label">Container type name: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="container_type_name" placeholder="Container types name">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                
                <div class="center-align">
                    <button type="submit" class="btn btn-info btn-sm">Search containers</button>
                </div>
            </div>
        </div>
        
        <?php
        echo form_close();
        ?>
    </div>

</section>