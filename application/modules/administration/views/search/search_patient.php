
        <section class="panel">
            <header class="panel-heading">
                
                <h2 class="panel-title">Search patient statement</h2>
            </header>
            
            <div class="panel-body">
			<?php
			
			
			echo form_open("administration/search_patient_statement", array("class" => "form-horizontal"));
			
            
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Patient Type: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="visit_type_id">
                            	<option value="">---Select Visit Type---</option>
                                <?php
                                    if($type->num_rows() > 0){
                                        foreach($type->result() as $row):
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
                        <label class="col-lg-4 control-label">ID Number: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="patient_national_id" placeholder="ID Number">
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
            
            <div class="center-align">
            	<button type="submit" class="btn btn-info">Search</button>
            </div>
            <?php
            echo form_close();
            ?>
          
            </div>
		</section>