<!-- search -->
<?php echo $this->load->view('search/search_patient', '', TRUE);?>
<!-- end search -->

<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
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
				
		$result =  '';
		if($module > 0)
		{

		}
		else
		{
			$result = '<a href="'.site_url().'/administration/patient_statement" class="btn btn-success">Back to Statements</a>';
	
		}
		
		
		
		$result .= '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			
			$result .= 
			'
				<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th style="text-align:center" rowspan=2>Date</th>
					  <th rowspan=2>Document Number</th>
					  <th rowspan=2>Document Type</th>
					  <th colspan=2 style="text-align:center;">Amount</th>
					
					</tr>
					<tr>
					  
					  <th style="text-align:center">Debit</th>
					  <th style="text-align:center">Credit</th>
					</tr>
				  </thead>
				  <tbody>
			';
			
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			$total_invoiced_amount = 0;
			$total_paid_amount = 0;
			foreach ($query->result() as $row)
			{
				$visit_id = $row->visit_id;
				$visit_date = $row->visit_date;
				$visit_date = $row->visit_date;
				$total_invoice = $this->accounts_model->total_invoice($visit_id);
				$total_payments = $this->accounts_model->total_payments($visit_id);

				$total_paid_amount = $total_paid_amount + $total_payments;
				$total_invoiced_amount = $total_invoiced_amount + $total_invoice;
				
				$invoice_number =  $visit_id;


				$count++;
				if($total_invoice > 0)
				{

					$result .= 
					'
						<tr>
							<td style="text-align:center">'.$visit_date.'</td>
							<td>'.$invoice_number.'</td>
							<td>Invoice</td>
							<td style="text-align:center">'.number_format($total_invoice,2).'</td>
							<td style="text-align:center">0.00</td>
						</tr> 
					';
				}

				if($total_payments > 0)
				{
					$result .= 
					'
						<tr>
							<td style="text-align:center">'.$visit_date.'</td>
							<td>'.$invoice_number.'</td>
							<td>Receipt</td>
							<td style="text-align:center">0.00</td>
							<td style="text-align:center">'.number_format($total_payments,2).'</td>
						</tr> 
					';

				}
				
			}
				$result .= 
					'
						<tr>
							<td></td>
							<td></td>
							<td style="text-align:center">Total Payment</td>
							<td style="text-align:center; font-weight:bold;"> '.number_format($total_invoiced_amount,2).'</td>
							<td style="text-align:center; font-weight:bold;">'.number_format($total_paid_amount,2).'</td>
						</tr> 
					';
				$Balance =  $total_invoiced_amount -$total_paid_amount;
					$result .= 
					'
						<tr>
							<td></td>
							<td></td>
							<td style="text-align:center; font-weight:bold;">Balance</td>
							<td colspan="2" style="text-align:center; font-weight:bold;">'.number_format($Balance,2).'</td>
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
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>