<div class="modal fade" id="search_cash_reports" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Filter Cash Transactions</h4>
			</div>
			<div class="modal-body">
			  <!-- Widget content -->
					<div class="panel-body">
					<form id="inpatient_search" method="post">
			            <div class="row">
			                <div class="col-md-6">
			                    <div class="form-group">
			                        <label class="col-md-4 control-label">Patient Type: </label>
			                        
			                        <div class="col-md-8">
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
			                        <label class="col-md-4 control-label">Patient number: </label>
			                        
			                        <div class="col-md-8">
			                            <input type="text" class="form-control" name="patient_number" placeholder="Patient number">
			                        </div>
			                    </div>
			                    
			                    <div class="form-group">
			                        <label class="col-md-4 control-label">I.D. No.: </label>
			                        
			                        <div class="col-md-8">
			                            <input type="text" class="form-control" name="patient_national_id" placeholder="I.D. No.">
			                        </div>
			                    </div>
			                </div>
			                
			                <div class="col-md-6">
			                                
			                        <div class="form-group">
			                            <label class="col-md-4 control-label">Ward: </label>
			                            
			                            <div class="col-md-8">
			                                <select name="ward_id" id="ward_id" class="form-control" onchange="check_department_type()">
			                                    <option value="">----Select a ward----</option>
			                                    <?php
			                                                            
			                                        if($wards->num_rows() > 0){

			                                            foreach($wards->result() as $row):
			                                                $ward_name = $row->ward_name;
			                                                $ward_id = $row->ward_id;

			                                                
			                                                if($ward_id == set_value('ward_id'))
			                                                {
			                                                    echo "<option value='".$ward_id."' selected='selected'>".$ward_name."</option>";
			                                                }
			                                                
			                                                else
			                                                {
			                                                    echo "<option value='".$ward_id."'>".$ward_name."</option>";
			                                                }
			                                            endforeach;
			                                        }
			                                    ?>
			                                </select>
			                            </div>
			                        </div>
			                    
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
			                        <div class="col-md-8 col-md-offset-4">
			                            <div class="center-align">
			                                <button type="submit" class="btn btn-info">Search</button>
			                            </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
            </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>