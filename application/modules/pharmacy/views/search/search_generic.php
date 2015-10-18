<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search generics</h2>
    </header>             
    
    <div class="panel-body">
		<?php
        echo form_open("pharmacy/search_generic", array("class" => "form-horizontal"));
        ?>
        <div class="row">
            <div class="col-md-10">
                
                <div class="form-group">
                    <label class="col-md-4 control-label">Generic name: </label>
                    
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="generic_name" placeholder="Generic name">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="center-align">
                    <button type="submit" class="btn btn-info btn-sm">Search generics</button>
                </div>
            </div>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</section>