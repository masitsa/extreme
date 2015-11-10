<div class="row">
	<div class="col-md-6">
         <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Search Laboratory Tests</h2>
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
                    <br>
                    <div class="center-align">
                        <button type="submit" class="btn btn-info btn-sm">Search lab tests</button>
                    </div>
                    
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </section>
	</div>
    
	<div class="col-md-6">
         <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Search Test Results</h2>
            </header>           
            
               <!-- Widget content -->
                <div class="panel-body">
                <div class="padd">
                    <?php
                    echo form_open("lab_charges/search_lab_test_results", array("class" => "form-horizontal"));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Date From: </label>
                                
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
                                <label class="col-lg-4 control-label">Date To: </label>
                                
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
                        <button type="submit" class="btn btn-info btn-sm">Search lab tests</button>
                    </div>
                    
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </section>
	</div>
</div>