<!-- search -->
<?php echo $this->load->view('search/search_petty_cash', '', TRUE);

?>
<!-- end search -->

<div class="row">
    <div class="col-md-12">

        <section class="panel">
            <header class="panel-heading">
                
                <h2 class="panel-title"><?php echo $title;?></h2>
            </header>
            
            <div class="panel-body">
                 
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
						  <th rowspan=2>Account</th>
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
							<td></td>
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
							<td></td>
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
					$petty_cash_id = $row->petty_cash_id;
					$account_name = $row->account_name;
					$petty_cash_description = $row->petty_cash_description;
					$petty_cash_amount = $row->petty_cash_amount;
					$transaction_type_id = $row->transaction_type_id;
					$petty_cash_date = $row->petty_cash_date;
	
					if($transaction_type_id == 1)
					{
						$debit = number_format($petty_cash_amount,2);
						$credit = '';
						$total_debit += $petty_cash_amount;
					}
					else if($transaction_type_id == 2)
					{
						$credit = number_format($petty_cash_amount,2);
						$debit = '';
						$total_credit += $petty_cash_amount;
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
							<td style="text-align:center">'.date('jS M Y', strtotime($petty_cash_date)).'</td>
							<td>'.$account_name.'</td>
							<td>'.$petty_cash_description.'</td>
							<td style="text-align:center">'.$debit.'</td>
							<td style="text-align:center">'.$credit.'</td>
						</tr> 
					';
				}
				$result .= 
					'
						<tr>
							<td colspan="4" style="text-align:right">Total</td>
							<td style="text-align:center; font-weight:bold;">'.number_format($total_debit,2).'</td>
							<td style="text-align:center; font-weight:bold;">'.number_format($total_credit,2).'</td>
						</tr> 
					';
					$balance =  $total_debit - $total_credit;
						$result .= 
						'
							<tr>
								<td colspan="4" style="text-align:right; font-weight:bold;">Balance</td>
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