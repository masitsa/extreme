<!-- search -->
<?php echo $this->load->view('search_creditor_account', '', TRUE);

?>
<!-- end search -->

<div class="row">
    <div class="col-md-12">

        <section class="panel">
            <header class="panel-heading">
                
                <h2 class="panel-title"><?php echo $title;?></h2>
            </header>
            
            <div class="panel-body">
                <div class="pull-right">
                	<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#record_creditor_account"><i class="fa fa-plus"></i> Record</button>
                	<a href="<?php echo base_url().'accounts/creditors/print_creditor_account/'.$creditor_id.'/'.$date_from.'/'.$date_to;?>" class="btn btn-sm btn-success" target="_blank"><i class="fa fa-print"></i> Print</a>
                	<!--<a href="<?php echo base_url().'administration/sync_app_creditor_account';?>" class="btn btn-sm btn-info"><i class="fa fa-sign-out"></i> Sync</a>-->
                </div>
                <!-- Modal -->
                <div class="modal fade" id="record_creditor_account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Record Transaction</h4>
                            </div>
                            <div class="modal-body">
                                <?php echo form_open("accounts/creditors/record_creditor_account/".$creditor_id, array("class" => "form-horizontal"));?>
                                <input type="hidden" name="creditor_id" value="<?php echo $creditor_id;?>">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Transaction date: </label>
                                    
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="creditor_account_date" placeholder="Transaction date">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Type *</label>
                                    
                                    <div class="col-md-8">
                                        <select class="form-control" name="transaction_type_id">
                                            <option value="">-- Select type --</option>
                                            <option value="1">Payment</option>
                                            <option value="2">Invoice</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Description *</label>
                                    
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="creditor_account_description"></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Amount *</label>
                                    
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="creditor_account_amount" placeholder="Amount"/>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-4">
                                        <div class="center-align">
                                            <button type="submit" class="btn btn-primary">Save record</button>
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
					
			$result =  '';
			
			//if users exist display them
			if ($query->num_rows() > 0)
			{
				
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th style="text-align:center" rowspan=2>#</th>
						  <th style="text-align:center" rowspan=2>Transaction Date</th>
						  <th rowspan=2>Description</th>
						  <th colspan=2 style="text-align:center;">Amount</th>
						
						</tr>
						<tr>
						  <th style="text-align:center">Debit</th>
						  <th style="text-align:center">Credit</th>
						</tr>
					  </thead>
					  <tbody>
				';
				
				$count = 0;
				if($balance_brought_forward > 0)
				{
					$debit = number_format($balance_brought_forward, 2);
					$credit = '';
					$total_debit = $balance_brought_forward;
					$total_credit = 0;
					$count++;
				
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td style="text-align:center"></td>
							<td>Balance brought forward</td>
							<td style="text-align:center">'.$debit.'</td>
							<td style="text-align:center">'.$credit.'</td>
						</tr> 
					';
				}
				
				else if($balance_brought_forward < 0)
				{
					$balance_brought_forward *= -1;
					$debit = '';
					$credit = number_format($balance_brought_forward, 2);
					$total_debit = 0;
					$total_credit = $balance_brought_forward;
					$count++;
				
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td style="text-align:center"></td>
							<td>Balance brought forward</td>
							<td style="text-align:center">'.$debit.'</td>
							<td style="text-align:center">'.$credit.'</td>
						</tr> 
					';
				}
				else
				{
					$total_debit = 0;
					$total_credit = 0;
				}
				
				foreach ($query->result() as $row)
				{
					$creditor_account_id = $row->creditor_account_id;
					$creditor_account_description = $row->creditor_account_description;
					$creditor_account_amount = $row->creditor_account_amount;
					$transaction_type_id = $row->transaction_type_id;
					$creditor_account_date = $row->creditor_account_date;
	
					if($transaction_type_id == 1)
					{
						$debit = number_format($creditor_account_amount,2);
						$credit = '';
						$total_debit += $creditor_account_amount;
					}
					else if($transaction_type_id == 2)
					{
						$credit = number_format($creditor_account_amount,2);
						$debit = '';
						$total_credit += $creditor_account_amount;
					}
					
					else
					{
						$debit = '';
						$credit = '';
					}
					
					$count++;
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td style="text-align:center">'.date('jS M Y', strtotime($creditor_account_date)).'</td>
							<td>'.$creditor_account_description.'</td>
							<td style="text-align:center">'.$debit.'</td>
							<td style="text-align:center">'.$credit.'</td>
						</tr> 
					';
				}
				$result .= 
					'
						<tr>
							<td colspan="3" style="text-align:right">Total</td>
							<td style="text-align:center; font-weight:bold;">'.number_format($total_debit,2).'</td>
							<td style="text-align:center; font-weight:bold;">'.number_format($total_credit,2).'</td>
						</tr> 
					';
					$balance =  $total_debit - $total_credit;
						$result .= 
						'
							<tr>
								<td colspan="3" style="text-align:right; font-weight:bold;">Balance</td>
								<td colspan="2" style="text-align:center; font-weight:bold;">'.number_format($balance,2).'</td>
							</tr> 
						';
				$result .= 
				'
							  </tbody>
							</table>
				';
			}
			
			else
			{
				$result .= "There are no items";
			}
			
			echo $result;
?>
          	</div>
		</section>
    </div>
</div>