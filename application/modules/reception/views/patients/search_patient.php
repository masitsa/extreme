 <section class="panel">
    <header class="panel-heading">
        <h5 class="pull-left"><i class="icon-reorder"></i>Search Patients</h5>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </header>             
    
    <!-- Widget content -->
   <div class="panel-body">
    	<div class="padd">
			<?php
            echo form_open("reception/search_patients", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Branch: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="branch_code">
                            	<option value="">---Select Branch---</option>
                                <?php
                                    if($branches->num_rows() > 0){
                                        foreach($branches->result() as $row):
                                            $branch_name = $row->branch_name;
                                            $branch_code= $row->branch_code;
                                                ?><option value="<?php echo $branch_code; ?>" ><?php echo $branch_name; ?></option>
                                        <?php	
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">I.D. number: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="patient_national_id" placeholder="National ID">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Patient number: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="patient_number" placeholder="Patient number">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">First name: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="surname" placeholder="First name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Other names: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="othernames" placeholder="Other Names">
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12 center-align">
                	<button type="submit" class="btn btn-info btn-sm">Search</button>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
    	</div>
    </div>
</section>