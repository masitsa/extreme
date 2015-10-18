 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search Patients for <?php echo date('jS M Y',strtotime(date('Y-m-d'))); ?></h2>
    </header>             
    
    <div class="panel-body">
		<?php
            echo form_open("pharmacy/search_visit_patients", array("class" => "form-horizontal"));
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Patient Type: </label>
                    
                    <div class="col-lg-8">
                        <select class="form-control" name="visit_type_id">
                            <option value="">---Select Visit Type---</option>
                            <?php
                                if(count($type) > 0){
                                    foreach($type as $row):
                                        $type_name = $row->visit_type_name;
                                        $type_id= $row->visit_type_id;
                                            ?><option value="<?php echo $type_id; ?>" ><?php echo $type_name ?></option>
                                    <?php	
                                    endforeach;
                                }
                            ?>
                        </select>
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
                    <label class="col-lg-4 control-label">Surname: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="surname" placeholder="Surname">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-4 control-label">Other Names: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="othernames" placeholder="Other Names">
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="center-align">
            <button type="submit" class="btn btn-info btn-sm">Search</button>
        </div>
        <?php
        echo form_close();
        ?>
    </div>
</section>