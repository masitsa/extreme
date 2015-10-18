 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title pull-right">Active branch: <?php echo $branch_name;?></h2>
        <h2 class="panel-title">Search Visits</h2>
    </header>
    
    <!-- Widget content -->
    <div class="panel-body">
        <div class="padd">
            <?php
            echo form_open("accounts/search_visits/".$type_links, array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Visit Type: </label>
                        
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
                        <label class="col-lg-4 control-label">Doctor: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="personnel_id">
                                <option value="">---Select Doctor---</option>
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
                        <label class="col-lg-4 control-label">Visit Date: </label>
                        
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date" placeholder="Visit Date">
                            </div>
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
                        <label class="col-lg-4 control-label">Other Names: </label>
                        
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="othernames" placeholder="Other Names">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Branch: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="branch_code">
                            	<option value="">---Select branch---</option>
                                <?php
									if($branches->num_rows() > 0){
										foreach($branches->result() as $row):
											$branch_name = $row->branch_name;
											$branch_code = $row->branch_code;
											echo "<option value=".$branch_code.">".$branch_name."</option>";
										endforeach;
									}
								?>
                            </select>
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
    </div>
</section>