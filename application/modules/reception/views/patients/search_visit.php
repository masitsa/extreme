
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Search</h2>
    </header>
      <div class="panel-body">
			<?php
            echo form_open("reception/search_visits/".$visit.'/'.$page_name, array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Patient Type: </label>
                        
                        <div class="col-md-8">
                            <select class="form-control" name="visit_type_id">
                            	<option value="">-Select Patient Type-</option>
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
                        <label class="col-md-4 control-label">Doctor: </label>
                        
                        <div class="col-md-8">
                            <select class="form-control" name="personnel_id">
                            	<option value="">-Select Doctor-</option>
                                <?php
									if(count($doctors) > 0){
										foreach($doctors as $row):
											$fname = $row->personnel_fname;
											$onames = $row->personnel_onames;
											$personnel_id = $row->personnel_id;
											echo "<option value=".$personnel_id.">".$onames." ".$fname."</option>";
										endforeach;
									}
								?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Patient number: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="patient_number" placeholder="Patient number">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">I.D. No.: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="patient_national_id" placeholder="I.D. No.">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Visit Date: </label>
                        
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date" placeholder="Visit Date">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">First name: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="surname" placeholder="First name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-4 control-label">Other Names: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="othernames" placeholder="Other Names">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-4">
                            <div class="center-align">
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
           
            <?php
            echo form_close();
            ?>
          </div>
       

  </section>