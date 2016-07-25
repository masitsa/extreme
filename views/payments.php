
 <section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title"><?php echo $visit_type_name;?> patient</h2>
	</header>
	
	<!-- Widget content -->
	
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
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
			</div>
		</div>
		
		<div class="well well-sm info">
			<h5 style="margin:0;">
				<div class="row">
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-6">
								<strong>First name:</strong>
							</div>
							<div class="col-md-6">
								<?php echo $patient_surname;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-6">
								<strong>Other names:</strong>
							</div>
							<div class="col-md-6">
								<?php echo $patient_othernames;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-6">
								<strong>Account balance:</strong>
							</div>
							<div class="col-md-6">
								Kes <?php echo number_format($account_balance, 2);?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-12">
								<a href="<?php echo site_url();?>administration/individual_statement/<?php echo $patient_id;?>/2" class="btn btn-sm btn-success pull-right" target="_blank" style="margin-top: 5px;">Patient Statement</a>
							</div>
						</div>
					</div>
				</div>
			</h5>
		</div>
		
        <!--<div class="row">
        	<div class="col-sm-3 col-sm-offset-3">
            	<a href="<?php echo site_url().'doctor/print_prescription'.$visit_id;?>" class="btn btn-warning">Print prescription</a>
            </div>
            
        	<div class="col-sm-3">
            	<a href="<?php echo site_url().'doctor/print_lab_tests'.$visit_id;?>" class="btn btn-danger">Print lab tests</a>
            </div>
        </div>-->
        
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-12">
							<section class="panel panel-featured panel-featured-info">
								<header class="panel-heading">
									
									<h2 class="panel-title">Invoices Charges</h2>
								</header>
								<div class="panel-body">
                                	<div class="row">
                                    	<div class="col-md-12">
                                        	<a href="<?php echo site_url();?>accounts/print_invoice_new/<?php echo $visit_id;?>" target="_blank" class="btn btn-sm btn-primary pull-right" style="margin-bottom:10px;" >Print Invoice</a>
                                        </div>
                                    </div>
									<table class="table table-hover table-bordered col-md-12">
										<thead>
											<tr>
												<th>#</th>
												<th>Service</th>
												<th>Item Name</th>
												<th>Units</th>
												<th>Unit Cost</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
											$credit_note_amount = $this->accounts_model->get_sum_credit_notes($visit_id);
											$debit_note_amount = $this->accounts_model->get_sum_debit_notes($visit_id);
											$total = 0;
											$s=0;
											if(count($item_invoiced_rs) > 0)
											{
												foreach ($item_invoiced_rs as $key_items):
													$s++;
													$service_charge_name = $key_items->service_charge_name;
													$visit_charge_amount = $key_items->visit_charge_amount;
													$service_name = $key_items->service_name;
													$units = $key_items->visit_charge_units;
													$visit_total = $visit_charge_amount * $units;
													$personnel_id = $key_items->personnel_id;
													$doctor = '';
													
													if($personnel_id > 0)
													{
														$doctor_rs = $this->reception_model->get_personnel($personnel_id);
														if($doctor_rs->num_rows() > 0)
														{
															$key_personnel = $doctor_rs->row();
															$first_name = $key_personnel->personnel_fname;
															$personnel_onames = $key_personnel->personnel_onames;
															$doctor = ' : Dr. '.$personnel_onames.' '.$first_name;
														}
													}
													?>
													<tr>
														<td><?php echo $s;?></td>
														<td><?php echo $service_name;?></td>
														<td><?php echo $service_charge_name.$doctor;?></td>
														<td><?php echo $units;?></td>
														<td><?php echo number_format($visit_charge_amount,2);?></td>
														<td><?php echo number_format($visit_total,2);?></td>
													</tr>
													<?php
													$total = $total + $visit_total;
												endforeach;
											}
											$total_amount = $total ;
											// enterring the payment stuff
											$payments_rs = $this->accounts_model->payments($visit_id);
											$total_payments = 0;
											$total_amount = ($total + $debit_note_amount) - $credit_note_amount;
											
											if(count($payments_rs) > 0)
											{
												$x = $s;
												foreach ($payments_rs as $key_items):
													$x++;
													$payment_method = $key_items->payment_method;
													
													$amount_paid = $key_items->amount_paid;
													$time = $key_items->time;
													$payment_type = $key_items->payment_type;
													$amount_paidd = number_format($amount_paid,2);
													$payment_service_id = $key_items->payment_service_id;
													
													if($payment_service_id > 0)
													{
													$service_associate = $this->accounts_model->get_service_detail($payment_service_id);
													}
													else
													{
													$service_associate = " ";
													}

													if($payment_type == 2)
													{
														$type = "Debit Note";
														$amount_paidd = $amount_paidd;
														
														?>
														<tr>
															<td><?php echo $x;?></td>
															<td colspan="2"><?php echo $service_associate;?></td>
															<td>1</td>
															<td><?php echo $amount_paidd;?></td>
														</tr>
														<?php
													}
													
													else if($payment_type == 3)
													{
														$type = "Credit Note";
														$amount_paidd = "($amount_paidd)";
														
														?>
														<tr>
															<td><?php echo $x;?></td>
															<td colspan="2"><?php echo $service_associate;?></td>
															<td><?php echo $amount_paidd;?></td>
														</tr>
														<?php
													}
													
												endforeach;
											}
											// end of the payments
											$total_amount = ($total + $debit_note_amount) - $credit_note_amount;
											$total_payments = 0;
											
											if(count($payments_rs) > 0)
											{
												$x=0;
												
												foreach ($payments_rs as $key_items):
													$x++;
													$payment_type = $key_items->payment_type;
													$payment_status = $key_items->payment_status;
													
													if($payment_type == 1 && $payment_status == 2)
													{
														$payment_method = $key_items->payment_method;
														$amount_paid = $key_items->amount_paid;
														
														$total_payments = $total_payments + $amount_paid;
													}
												endforeach;
										  
											}
											?>
											<tr>
												<td colspan="5" align="right"><strong>Total Payments:</strong></td>
												<td><strong> <?php echo number_format($total_payments,2);?></strong></td>
											</tr>
											<tr>
												<td colspan="5" align="right"><strong>Total Invoice:</strong></td>
												<td><strong> <?php echo number_format($total_amount - $total_payments,2);?></strong></td>
											</tr>
											<?php
											/*}
											else
											{
												?>
												<tr>
													<td colspan="4"> No Charges</td>
												</tr>
												<?php
											}*/

											?>
										</tbody>
									</table>
								</div>
							</section>
						</div>
					</div>

					<!-- END OF FIRST ROW -->

					<!--<div class="row" style= "margin-top:2em">
					<div class="col-md-12">

						<section class="panel panel-featured panel-featured-info">
							<header class="panel-heading">
								<h2 class="panel-title">Debit/ Credit notes</h2>
							</header>
							<div class="panel-body">
								<table class="table table-hover table-bordered col-md-12">
									<thead>
										<tr>
											<th>Service Associate</th>
											<th>Time</th>
											<th>Debit</th>
											<th>Credit</th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										$payments_rs = $this->accounts_model->payments($visit_id);
										$total_payments = 0;
										$total_amount = ($total + $debit_note_amount) - $credit_note_amount;
										if(count($payments_rs) > 0)
										{
											$x=0;

											foreach ($payments_rs as $key_items):
												$x++;
												$payment_method = $key_items->payment_method;
												$amount_paid = $key_items->amount_paid;
												$time = $key_items->time;
												$payment_type = $key_items->payment_type;
												$amount_paidd = number_format($amount_paid,2);
												$payment_service_id = $key_items->payment_service_id;

												if($payment_service_id > 0)
												{
													$service_associate = $this->accounts_model->get_service_detail($payment_service_id);
												}
												else
												{
													$service_associate = " ";
												}

												if($payment_type == 2)
												{
													$type = "Debit Note";
													$amount_paidd = $amount_paidd;

													?>
													<tr>
														<td><?php echo $service_associate;?></td>
														<td><?php echo $time;?></td>
														<td><?php echo $amount_paidd;?></td>
														<td></td>
													</tr>
													<?php
												}
												
												else if($payment_type == 3)
												{
													$type = "Credit Note";
													$amount_paidd = "($amount_paidd)";

													?>
													<tr>
														<td><?php echo $service_associate;?></td>
														<td><?php echo $time;?></td>
														<td></td>
														<td><?php echo $amount_paidd;?></td>
													</tr>
													<?php
												}
											endforeach;
											
											?>
											<tr>
												<td colspan="2"><strong>Totals</strong></td>
												<td><strong><?php echo number_format($debit_note_amount,2);?></strong></td>
												<td><strong><?php echo number_format($credit_note_amount,2);?></strong></td>
											</tr>
											<tr>
												<td colspan="3"><strong>Difference </strong></td>
												<td><strong><?php echo number_format($debit_note_amount - $credit_note_amount,2);?></strong></td>
											</tr>
											<?php
										}
										
										else
										{
											?>
											<tr>
												<td colspan="4"> No payments made yet</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
								
							</div>
							
						</section>

					</div>

					</div>-->
					<!-- END OF SECOND ROW -->
					
					<div class="row" style= "margin-top:2em">
						<div class="col-md-12">
							<section class="panel panel-featured panel-featured-info">
								<header class="panel-heading">
									<h2 class="panel-title">Receipts</h2>
								</header>
								
								<div class="panel-body">
                                	<div class="row">
                                    	<div class="col-md-12">
                                        	<a href="<?php echo site_url();?>accounts/print_receipt_new/<?php echo $visit_id;?>" target="_blank" class="btn btn-sm btn-primary pull-right" style="margin-bottom:10px;" >Print all Receipts</a>
                                        </div>
                                    </div>
									<table class="table table-hover table-bordered col-md-12">
										<thead>
											<tr>
												<th>#</th>
												<th>Time</th>
												<th>Service</th>
												<th>Method</th>
												<th>Amount</th>
												<th colspan="2"></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$payments_rs = $this->accounts_model->payments($visit_id);
											$total_payments = 0;
											$total_amount = ($total + $debit_note_amount) - $credit_note_amount;
											
											if(count($payments_rs) > 0)
											{
												$x=0;

												foreach ($payments_rs as $key_items):
													$x++;
													$payment_method = $key_items->payment_method;

													$time = $key_items->time;
													$payment_type = $key_items->payment_type;
													$payment_id = $key_items->payment_id;
													$payment_status = $key_items->payment_status;
													$payment_service_id = $key_items->payment_service_id;
													$service_name = '';
													
													if($payment_type == 1 && $payment_status == 1)
													{
														$amount_paid = $key_items->amount_paid;
														$amount_paidd = number_format($amount_paid,2);
														
														if(count($item_invoiced_rs) > 0)
														{
															foreach ($item_invoiced_rs as $key_items):
															
																$service_id = $key_items->service_id;
																
																if($service_id == $payment_service_id)
																{
																	$service_name = $key_items->service_name;
																	break;
																}
															endforeach;
														}
													
														//display DN & CN services
														if((count($payments_rs) > 0) && ($service_name == ''))
														{
															foreach ($payments_rs as $key_items):
																$payment_type = $key_items->payment_type;
																
																if(($payment_type == 2) || ($payment_type == 3))
																{
																	$payment_service_id2 = $key_items->payment_service_id;
																	
																	if($payment_service_id2 == $payment_service_id)
																	{
																		$service_name = $this->accounts_model->get_service_detail($payment_service_id);
																		break;
																	}
																}
																
															endforeach;
														}
														?>
														<tr>
															<td><?php echo $x;?></td>
															<td><?php echo $time;?></td>
															<td><?php echo $service_name;?></td>
															<td><?php echo $payment_method;?></td>
															<td><?php echo $amount_paidd;?></td>
															<td><a href="<?php echo site_url().'accounts/print_single_receipt/'.$payment_id;?>" class="btn btn-small btn-warning" target="_blank"><i class="fa fa-print"></i></a></td>
															<td>
                                                            	<button type="button" class="btn btn-small btn-default" data-toggle="modal" data-target="#refund_payment<?php echo $payment_id;?>"><i class="fa fa-times"></i></button>
<!-- Modal -->
<div class="modal fade" id="refund_payment<?php echo $payment_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title" id="myModalLabel">Cancel payment</h4>
            </div>
            <div class="modal-body">
            	<?php echo form_open("accounts/cancel_payment/".$payment_id.'/'.$visit_id, array("class" => "form-horizontal"));?>
                <div class="form-group">
                    <label class="col-md-4 control-label">Action: </label>
                    
                    <div class="col-md-8">
                        <select class="form-control" name="cancel_action_id">
                        	<option value="">-- Select action --</option>
                            <?php
                                if($cancel_actions->num_rows() > 0)
                                {
                                    foreach($cancel_actions->result() as $res)
                                    {
                                        $cancel_action_id = $res->cancel_action_id;
                                        $cancel_action_name = $res->cancel_action_name;
                                        
                                        echo '<option value="'.$cancel_action_id.'">'.$cancel_action_name.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-4 control-label">Description: </label>
                    
                    <div class="col-md-8">
                        <textarea class="form-control" name="cancel_description"></textarea>
                    </div>
                </div>
                
                <div class="row">
                	<div class="col-md-8 col-md-offset-4">
                    	<div class="center-align">
                        	<button type="submit" class="btn btn-primary">Save action</button>
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
                                                            </td>
														</tr>
														<?php
														$total_payments =  $total_payments + $amount_paid;
													}
												endforeach;

												?>
												<tr>
													<td colspan="4"><strong>Total : </strong></td>
													<td><strong> <?php echo number_format($total_payments,2);?></strong></td>
												</tr>
												<?php
											}
											
											else
											{
												?>
												<tr>
													<td colspan="4"> No payments made yet</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</section>
						</div>
					</div>
					<!-- END OF THIRD ROW -->
					
					<div class="row" style= "margin-top:2em">
						<div class="col-md-12">
							<table class="table table-hover table-bordered">
								<tbody>
									<tr>
										<td colspan="3"><strong>Balance :</strong></td>
										<td><strong> <?php echo number_format(($total_amount - $total_payments),2) ;?> </strong></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- END OF FOURTH ROW -->
				</div>
				<!-- END OF THE SPAN 7 -->
				  
				<!-- START OF THE SPAN 5 -->
				<div class="col-md-5">
					<div class="row">
						<div class="col-md-12">
							<section class="panel panel-featured panel-featured-info">
								<header class="panel-heading">
									
									<h2 class="panel-title">Add payment</h2>
								</header>
                                
								<div class="panel-body">
									<?php echo form_open("accounts/make_payments/".$visit_id.'/'.$close_page, array("class" => "form-horizontal"));?>
										<div class="form-group">
											<div class="col-lg-4">
                                            	<div class="radio">
                                                    <label>
                                                        <input id="optionsRadios2" type="radio" name="type_payment" value="1" checked="checked" onclick="getservices(1)"> 
                                                        Normal
                                                    </label>
                                                </div>
											</div>
											<div class="col-lg-4">
                                            	<div class="radio">
                                                    <label>
                                                        <input id="optionsRadios2" type="radio" name="type_payment" value="2" onclick="getservices(2)"> 
                                                        Debit Note
                                                    </label>
                                                </div>
											</div>
											<div class="col-lg-4">
                                            	<div class="radio">
                                                    <label>
                                                        <input id="optionsRadios2" type="radio" name="type_payment" value="3" onclick="getservices(3)"> 
                                                        Credit Note
                                                    </label>
                                                </div>
											</div>
										</div>
                                        
										<div id="service_div2" class="form-group">
											<label class="col-lg-4 control-label">Service: </label>
										  
											<div class="col-lg-8">
                                            	<select name="service_id" class="form-control">
                                                	<option value="">All services</option>
                                            	<?php
												if(count($item_invoiced_rs) > 0)
												{
													$s=0;
													foreach ($item_invoiced_rs as $key_items):
														$s++;
														$service_id = $key_items->service_id;
														$service_name = $key_items->service_name;
														?>
                                                        <option value="<?php echo $service_id;?>"><?php echo $service_name;?></option>
														<?php
													endforeach;
												}
													
												//display DN & CN services
												if(count($payments_rs) > 0)
												{
													foreach ($payments_rs as $key_items):
														$payment_type = $key_items->payment_type;
														
														if(($payment_type == 2) || ($payment_type == 3))
														{
															$payment_service_id = $key_items->payment_service_id;
															
															if($payment_service_id > 0)
															{
																$service_associate = $this->accounts_model->get_service_detail($payment_service_id);
																?>
																<option value="<?php echo $payment_service_id;?>"><?php echo $service_associate;?></option>
																<?php
															}
														}
														
													endforeach;
												}
												?>
                                                </select>
											</div>
										</div>
                                        
                                    	<div id="service_div" class="form-group" style="display:none;">
                                            <label class="col-lg-4 control-label"> Services: </label>
                                            
                                            <div class="col-lg-8">
                                                <select class="form-control" name="payment_service_id" >
                                                	<option value="">--Select a service--</option>
													<?php
                                                    $service_rs = $this->accounts_model->get_all_service();
                                                    $service_num_rows = count($service_rs);
                                                    if($service_num_rows > 0)
                                                    {
														foreach($service_rs as $service_res)
														{
															$service_id = $service_res->service_id;
															$service_name = $service_res->service_name;
															
															echo '<option value="'.$service_id.'">'.$service_name.'</option>';
														}
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

										<div class="form-group">
											<label class="col-lg-4 control-label">Amount: </label>
										  
											<div class="col-lg-8">
												<input type="text" class="form-control" name="amount_paid" placeholder="" autocomplete="off">
											</div>
										</div>
										
										<div class="form-group" id="payment_method">
											<label class="col-lg-4 control-label">Payment Method: </label>
											  
											<div class="col-lg-8">
												<select class="form-control" name="payment_method" onchange="check_payment_type(this.value)">
                                                	<?php
													  $method_rs = $this->accounts_model->get_payment_methods();
													  $num_rows = count($method_rs);
													 if($num_rows > 0)
													  {
														
														foreach($method_rs as $res)
														{
														  $payment_method_id = $res->payment_method_id;
														  $payment_method = $res->payment_method;
														  
															echo '<option value="'.$payment_method_id.'">'.$payment_method.'</option>';
														  
														}
													  }
												  ?>
												</select>
											  </div>
										</div>
										<div id="mpesa_div" class="form-group" style="display:none;" >
											<label class="col-lg-4 control-label"> Mpesa TX Code: </label>

											<div class="col-lg-8">
												<input type="text" class="form-control" name="mpesa_code" placeholder="">
											</div>
										</div>
									  
										<div id="insuarance_div" class="form-group" style="display:none;" >
											<label class="col-lg-4 control-label"> Insurance Number: </label>
											<div class="col-lg-8">
												<input type="text" class="form-control" name="insuarance_number" placeholder="">
											</div>
										</div>
									  
										<div id="cheque_div" class="form-group" style="display:none;" >
											<label class="col-lg-4 control-label"> Cheque Number: </label>
										  
											<div class="col-lg-8">
												<input type="text" class="form-control" name="cheque_number" placeholder="">
											</div>
										</div>
									  
										<div id="username_div" class="form-group" style="display:none;" >
											<label class="col-lg-4 control-label"> Username: </label>
										  
											<div class="col-lg-8">
												<input type="text" class="form-control" name="username" placeholder="">
											</div>
										</div>
									  
										<div id="password_div" class="form-group" style="display:none;" >
											<label class="col-lg-4 control-label"> Password: </label>
										  
											<div class="col-lg-8">
												<input type="password" class="form-control" name="password" placeholder="">
											</div>
										</div>
										
										<div class="center-align">
											<button class="btn btn-info btn-sm" type="submit">Add Payment Information</button>
										</div>
										<?php echo form_close();?>
								</div>
							</section>
						</div>
					</div>
				
					<!-- Bill Methods -->
					<div class="row">
						<div class="col-md-12">
							<section class="panel panel-featured panel-featured-info">
								<header class="panel-heading">
									
									<h2 class="panel-title">Calculator</h2>
								</header>
								
								<div class="panel-body">
									
                                   <link rel="stylesheet" href="<?php echo base_url().'assets/calculator/';?>prism.css">
                                   <script src="<?php echo base_url().'assets/calculator/';?>prism.js"></script>
                                
                                   <link rel="stylesheet" href="<?php echo base_url().'assets/calculator/';?>SimpleCalculadorajQuery.css">
                                   <script src="<?php echo base_url().'assets/calculator/';?>SimpleCalculadorajQuery.js"></script>
                                   
                                   <div id="micalc"></div>
                                   <script>
									 $("#micalc").Calculadora({'EtiquetaBorrar':'Clear',TituloHTML:''});
									
										$("#CalcOptoins").Calculadora({
											EtiquetaBorrar:'Clear',
											ClaseBtns1: 'warning', /* Color Numbers*/
											ClaseBtns2: 'success', /* Color Operators*/
											ClaseBtns3: 'primary', /* Color Clear*/
											TituloHTML:'<h2>Develoteca.com</h2>',
											Botones:["+","-","*","/","0","1","2","3","4","5","6","7","8","9",".","="]
										});
									 
									
									</script>
								</div>
							</section>
						</div>
					</div>
					<!-- End Bill Methods -->

				</div>
				<!-- END OF THE SPAN 5 -->
			</div>
		</div>
			
		<div class="row " style= "margin-top:2em">
			<div class="center-align">
				<!-- redirect to unclosed accounts queue -->
				<?php
			  
				if($going_to->num_rows() > 0)
				{
					$row = $going_to->row();
				  
					$department_id = $row->department_id;
					$accounts = $row->accounts;
					$department_name = $row->department_name;
					
					if($department_name == 'Accounts')
					{
						$query = $this->accounts_model->get_last_department($visit_id);
						
						if($query->num_rows() > 0)
						{
							$row2 = $query->row();
						  
							$department_id = $row2->department_id;
							$accounts = 0;
							$department_name = $row2->department_name;
						}
					}
				  
				  	//without end visit
					//if(($accounts == 0) && ($department_id != 6))
					if(($accounts == 0))
					{
						?>
						<a href= "<?php echo site_url();?>accounts/send_to_department/<?php echo $visit_id;?>/<?php echo $department_id;?>" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to send to <?php echo $department_name;?>?\');">Send to <?php echo $department_name;?></a>
                        
						<a href= "<?php echo site_url();?>reception/end_visit/<?php echo $visit_id;?>/1" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to end visit?\');">End Visit</a>
						<?php
					}
				  	
					//with end visit
					else
					{
						//if(($accounts == 0) && ($department_id == 6))
						if(($accounts == 0))
						{
							?>
							<a href= "<?php echo site_url();?>accounts/send_to_department/<?php echo $visit_id;?>/<?php echo $department_id;?>" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to send to <?php echo $department_name;?>?\');">Send to <?php echo $department_name;?></a>
							<?php
						}
						if(isset($close_page))
						{
							?>
							<a href= "<?php echo site_url();?>reception/end_visit/<?php echo $visit_id;?>/<?php echo $close_page;?>" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to end visit?\');">End Visit</a>
							<?php
						}
					  
						else
						{
							?>
							<a href= "<?php echo site_url();?>reception/end_visit/<?php echo $visit_id;?>/1" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to end visit?\');">End Visit</a>
							<?php
						}
				  }
			  }
			 ?>
			</div>
		</div>
		<!-- END OF PADD -->
	</div>
</section>
  <!-- END OF ROW -->
<script type="text/javascript">
  function getservices(id){

        var myTarget1 = document.getElementById("service_div");
        var myTarget2 = document.getElementById("username_div");
        var myTarget3 = document.getElementById("password_div");
        var myTarget4 = document.getElementById("service_div2");
        var myTarget5 = document.getElementById("payment_method");
		
        if(id == 1)
        {
          myTarget1.style.display = 'none';
          myTarget2.style.display = 'none';
          myTarget3.style.display = 'none';
          myTarget4.style.display = 'block';
          myTarget5.style.display = 'block';
        }
        else
        {
          myTarget1.style.display = 'block';
          myTarget2.style.display = 'block';
          myTarget3.style.display = 'block';
          myTarget4.style.display = 'none';
          myTarget5.style.display = 'none';
        }
        
  }
  function check_payment_type(payment_type_id){
   
    var myTarget1 = document.getElementById("cheque_div");

    var myTarget2 = document.getElementById("mpesa_div");

    var myTarget3 = document.getElementById("insuarance_div");

    if(payment_type_id == 1)
    {
      // this is a check
     
      myTarget1.style.display = 'block';
      myTarget2.style.display = 'none';
      myTarget3.style.display = 'none';
    }
    else if(payment_type_id == 2)
    {
      myTarget1.style.display = 'none';
      myTarget2.style.display = 'none';
      myTarget3.style.display = 'none';
    }
    else if(payment_type_id == 3)
    {
      myTarget1.style.display = 'none';
      myTarget2.style.display = 'none';
      myTarget3.style.display = 'block';
    }
    else if(payment_type_id == 4)
    {
      myTarget1.style.display = 'none';
      myTarget2.style.display = 'none';
      myTarget3.style.display = 'none';
    }
    else if(payment_type_id == 5)
    {
      myTarget1.style.display = 'none';
      myTarget2.style.display = 'block';
      myTarget3.style.display = 'none';
    }
    else
    {
      myTarget2.style.display = 'none';
      myTarget3.style.display = 'block';  
    }

  }
</script>