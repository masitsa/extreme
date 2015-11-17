<!-- search -->
<?php echo $this->load->view('search/debtors_invoices', '', TRUE);?>
<!-- end search -->

<div id="transaction_statistics">
	<div class="preloader"></div>
</div>
<style type="text/css">
.bootstrap-datetimepicker-widget{z-index:2000;}
</style>

<div class="row">
    <div class="col-md-12">
    	<section class="panel panel-featured">
            <header class="panel-heading">
            	 <h2 class="panel-title">Debtor's reports for <?php echo $visit_type_name;?></h2>
            </header>             

			<!-- Widget content -->
			<div class="panel-body">
          		<h5 class="center-align"><?php echo $this->session->userdata('search_title');?></h5>
<?php
		$result = '
		<!-- Button to trigger modal -->
		<a href="#new_batch" class="btn btn-primary" data-toggle="modal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create new batch</a>
		
		<!-- Modal -->
		<div id="new_batch" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">Add new batch to '.$visit_type_name.'</h4>
					</div>
					
					<div class="modal-body">
					'.form_open("administration/reports/create_new_batch/".$visit_type_id, array("class" => "form-horizontal")).'
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Invoice date from: </label>
                                
                                <div class="col-lg-8">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="invoice_date_from" placeholder="Invoice date from">
									</div>
                                </div>
                            </div>
                    	</div>
                        
                        <div class="col-md-12" style="margin:10px 0 10px;">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Invoice date to: </label>
                                
                                <div class="col-lg-8">
                                    <div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="invoice_date_to" placeholder="Invoice date to">
									</div>
                                </div>
                            </div>
                    	</div>
                    </div>   
                    
                    <div class="center-align">
                        <button type="submit" class="btn btn-info">Create new batch</button>
                    </div>

                    '.form_close().'
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
					</div>
				</div>
			</div>
		</div>
		';
		if(!empty($search))
		{
			$result .= '<a href="'.site_url().'administration/reports/close_debtors_search/'.$visit_type_id.'" class="btn btn-warning">Close Search</a>';
		}
		
		//if users exist display them
		//var_dump($query->result());
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
					  <thead>
						<tr>
						  <th>#</th>
						  <th><a href="'.site_url().'administration/reports/debtors_report_data/'.$visit_type_id.'/batch_no/'.$order_method.'">Batch no.</a></th>
						  <th><a href="'.site_url().'administration/reports/debtors_report_data/'.$visit_type_id.'/debtor_invoice_created/'.$order_method.'">Invoice date</a></th>
						  <th>Date range</th>
						  <th><a href="'.site_url().'administration/reports/debtors_report_data/'.$visit_type_id.'/debtor_invoice_created_by/'.$order_method.'">Created by</a></th>
						  <th><a href="'.site_url().'administration/reports/debtors_report_data/'.$visit_type_id.'/debtor_invoice_status/'.$order_method.'">Status</a></th>
						  <th>Total</th>
						  <th colspan="3">Actions</th>
						  
				';
				
			$result .= '
						</tr>
					  </thead>
					  <tbody>
			';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$invoice_date = date('jS M Y H:i a',strtotime($row->debtor_invoice_created));
				$debtor_invoice_id = $row->debtor_invoice_id;
				$batch_no = $row->batch_no;
				$status = $row->debtor_invoice_status;
				$personnel_id = $row->debtor_invoice_created_by;
				$date_from = date('jS M Y',strtotime($row->date_from));
				$date_to = date('jS M Y',strtotime($row->date_to));
				$total_invoiced = number_format($this->reports_model->calculate_debt_total($debtor_invoice_id, $where, $table), 2);
								
				//get status
				if($status == 0)
				{
					$status = '<span class="label label-danger">Unpaid</span>';
				}
				
				else
				{
					$status = '<span class="label label-success">Paid</span>';
				}
				
				// this is to check for any credit note or debit notes
				/*$payments_value = $this->accounts_model->total_payments($visit_id);

				$invoice_total = $this->accounts_model->total_invoice($visit_id);

				$balance = $this->accounts_model->balance($payments_value,$invoice_total);*/
				// end of the debit and credit notes


				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$created_by = $adm->personnel_onames.' '.$adm->personnel_fname;
							break;
						}
						
						else
						{
							$created_by = '-';
						}
					}
				}
				
				else
				{
					$created_by = '-';
				}
				
				$count++;
				
				//payment data
				/*$cash = $this->reports_model->get_all_visit_payments($visit_id);
				$charges = '';
				
				foreach($services_query->result() as $service)
				{
					$service_id = $service->service_id;
					$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
					$total_invoiced += $visit_charge;
					
					//$charges .= '<td>'.$visit_charge.'</td>';
				}*/

				// payment value ///
				
				//display all debtors
				$result .= 
						'
							<tr>
								<td>'.$count.'</td>
								<td>'.$batch_no.'</td>
								<td>'.$invoice_date.'</td>
								<td>'.$date_from.' - '.$date_to.'</td>
								<td>'.$created_by.'</td>
								<td>'.$status.'</td>
								<td>'.$total_invoiced.'</td>
								<td><a href="'.site_url().'administration/reports/view_invoices/'.$debtor_invoice_id.'" class="btn btn-info btn-sm">View invoices</a></td>
								<td><a href="'.site_url().'administration/reports/export_debt_transactions/'.$debtor_invoice_id.'" class="btn btn-success btn-sm pull-right">Export</a></td>
								<td><a href="'.site_url().'administration/reports/invoice/'.$debtor_invoice_id.'" class="btn btn-warning btn-sm pull-right" target="_blank">Print</a></td>
							</tr> 
					';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "<p>There are no invoices</p>";
		}
		
		//var_dump($result);
		
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
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </section>
        <!-- Widget ends -->

    </div>
  </div>
  
  <script type="text/javascript">
  	$(document).ready(function(){
			$.get( "<?php echo site_url();?>administration/reports/load_debtors_statistics/<?php echo $visit_type_id;?>/<?php echo $order_method;?>", function( data ) {
				  $( "#transaction_statistics" ).html( data );
			});
		}
	);
	
  </script>