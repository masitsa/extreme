<!-- search -->
<?php echo $this->load->view('search/accounts_search', '', TRUE);?>
<!-- end search -->
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?> </h2>
    </header>

    <!-- Widget content -->
    <div class="panel-body">
    	<div class="padd">
          <?php
            	$error = $this->session->userdata('error_message');
				$success = $this->session->userdata('success_message');
				
				if(!empty($error))
				{
					echo '<div class="alert alert-danger">'.$error.'</div>';
					$this->session->unset_userdata('error_message');
				}
				
				if(!empty($success))
				{
					echo '<div class="alert alert-success">'.$success.'</div>';
					$this->session->unset_userdata('success_message');
				}
			?>
            
           	<div style="min-height:30px;">
            	<div class="pull-right">
                	<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_account"><i class="fa fa-plus"></i> Add account</button>
                	<?php
					$search = $this->session->userdata('search_hospital_accounts');
		
					if(!empty($search))
					{
						echo '<a href="'.site_url().'accounts/hospital_accounts/close_search_hospital_accounts" class="btn btn-warning btn-sm">Close Search</a>';
					}
					?>
                </div>
            </div>
                <!-- Modal -->
                <div class="modal fade" id="add_account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add account</h4>
                            </div>
                            <div class="modal-body">
                                <?php echo form_open("accounts/hospital_accounts/add_account", array("class" => "form-horizontal"));?>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Account *</label>
                                    
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="account_name" placeholder="Account"/>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Activate?</label>
                                    <div class="col-lg-6">
                                        <div class="radio">
                                            <label>
                                                <input id="optionsRadios1" type="radio" checked value="1" name="account_status">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input id="optionsRadios2" type="radio" value="0" name="account_status">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-4">
                                        <div class="center-align">
                                            <button type="submit" class="btn btn-primary">Add account</button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close();?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
<?php
		
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= '
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Account name</th>
						  <th>Total expenditure</th>
						  <th colspan="2">Actions</th>';
				$result .= 	'</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				$count++;
				$account_id = $row->account_id;
				$account_name = $row->account_name;
				$total_expenditure = $row->total_expenditure;
				$account_status = $row->account_status;
				
				if($account_status == 1)
				{
					$checked_active = 'checked';
					$checked_inactive = '';
				}
				else
				{
					$checked_active = '';
					$checked_inactive = 'checked';
				}
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$account_name.'</td>
							<td>'.number_format($total_expenditure, 2).'</td>
							<td><a href="'.site_url().'accounts/hospital_accounts/statement/'.$account_id.'" class="btn btn-sm btn-info">Statement</a></td>
							<td>
								<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#edit_account'.$account_id.'"><i class="fa fa-pencil"></i> Edit</button>
								<div class="modal fade" id="edit_account'.$account_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">Edit '.$account_name.'</h4>
											</div>
											<div class="modal-body">
												'.form_open("accounts/hospital_accounts/edit_account/".$account_id, array("class" => "form-horizontal")).'
												<div class="form-group">
													<label class="col-md-4 control-label">Account *</label>
													
													<div class="col-md-8">
														<input type="text" class="form-control" name="account_name" placeholder="Account" value="'.$account_name.'"/>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-lg-4 control-label">Activate?</label>
													<div class="col-lg-6">
														<div class="radio">
															<label>
																<input id="optionsRadios1" type="radio" '.$checked_active.' value="1" name="account_status">
																Yes
															</label>
														</div>
														<div class="radio">
															<label>
																<input id="optionsRadios2" type="radio" '.$checked_inactive.' value="0" name="account_status">
																No
															</label>
														</div>
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-8 col-md-offset-4">
														<div class="center-align">
															<button type="submit" class="btn btn-primary">Edit account</button>
														</div>
													</div>
												</div>
												'.form_close().'
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
							</td>';
				
			}
			
			$result .= 
				'
							  </tbody>
							</table>
				';
		}
		
		else
		{
			$result .= "There are no accounts";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
</section>