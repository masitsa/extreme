  
                <!-- Modal -->
                <div class="modal fade" id="record_petty_cash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Filter Transaction Information</h4>
                            </div>
                            <div class="modal-body">
							  <!-- Widget content -->
									<div class="panel-body">
							
										<form id="transaction_search" method="post">
											
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="col-lg-4 control-label">Visit Type: </label>
														
														<div class="col-lg-8">
															<select class="form-control" name="visit_type_id">
																<option value="_">---Select Visit Type---</option>
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
												</div>
												
												<div class="col-md-4">
													
													<div class="form-group">
														<label class="col-lg-4 control-label">Visit Date From: </label>
														
														<div class="col-lg-8">
															<div class="input-group">
																<span class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</span>
																<input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date_from" placeholder="Visit Date From">
															</div>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-lg-4 control-label">Visit Date To: </label>
														
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
												
												<div class="col-md-4">
													
													<!--<div class="form-group">
														<label class="col-lg-4 control-label">Patient number: </label>
														
														<div class="col-lg-8">
															<input type="text" class="form-control" name="patient_number" placeholder="Patient number">
														</div>
													</div>-->
													
													<div class="form-group">
														<label class="col-lg-4 control-label">Branch: </label>
														
														<div class="col-lg-8">
															<select class="form-control" name="branch_code">
																<option value="_">---Select branch---</option>
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
													
													<div class="form-group">
														<div class="col-lg-8 col-lg-offset-4">
															<div class="center-align">
																<button type="submit" class="btn btn-info">Search</button>
															</div>
														</div>
													</div>
												</div>
											</div>
									</form>
								  </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
		
		