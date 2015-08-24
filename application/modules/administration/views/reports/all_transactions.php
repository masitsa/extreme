<!-- search -->
<?php echo $this->load->view('search/transactions', '', TRUE);?>
<!-- end search -->
<?php echo $this->load->view('transaction_statistics', '', TRUE);?>
 
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
          <h5 class="center-align"><?php echo $this->session->userdata('search_title');?></h5>
<?php
		$result = '<a href="'.site_url().'/administration/reports/export_transactions" class="btn btn-success pull-right">Export</a>';
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/administration/reports/close_search/'.$module.'" class="btn btn-warning">Close Search</a>';
		}
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered table-striped table-responsive col-md-12">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Patient</th>
						  <th>Category</th>
						  <th>Doctor</th>
						  <th>School/ Faculty/ Department</th>
						  <th>Staff/ Student/ID No.</th>
						  <th>Cash</th>
						  
				';
				
			foreach($services_query->result() as $service)
			{
				//$result .= '<th>'.$service->service_name.'</th>';
			}
				
			$result .= '
			
						  <th>Invoice Total</th>
						  <th>Balance</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$total_invoiced = 0;
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
				}
				else
				{
					$visit_time_out = '-';
				}
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				
				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);
				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				$faculty = $patient['faculty'];
				if($patient['staff_dependant_no'] != 0)
				{
					$strath_no = $patient['staff_dependant_no'];
				}
				else
				{
					$strath_no = $strath_no;
				}
				


				// this is to check for any credit note or debit notes
				$payments_value = $this->accounts_model->total_payments($visit_id);

				$invoice_total = $this->accounts_model->total_invoice($visit_id);

				$balance = $this->accounts_model->balance($payments_value,$invoice_total);
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
							$doctor = $adm->personnel_onames.' '.$adm->personnel_fname;
							break;
						}
						
						else
						{
							$doctor = '-';
						}
					}
				}
				
				else
				{
					$doctor = '-';
				}
				
				$count++;
				
				//payment data
				$cash = $this->reports_model->get_all_visit_payments($visit_id);
				$charges = '';
				
				foreach($services_query->result() as $service)
				{
					$service_id = $service->service_id;
					$visit_charge = $this->reports_model->get_all_visit_charges($visit_id, $service_id);
					$total_invoiced += $visit_charge;
					
					//$charges .= '<td>'.$visit_charge.'</td>';
				}

				// payment value ///
				
				//display all debtors
				if($debtors == 'true' && (($cash - $total_invoiced) > 0))
				{
					$result .= 
						'
							<tr>
								<td>'.$count.'</td>
								<td>'.$visit_date.'</td>
								<td>'.$patient_surname.' '.$patient_othernames.'</td>
								<td>'.$visit_type.'</td>
								<td>'.$doctor.'</td>
								<td>'.$faculty.'</td>
								<td>'.$strath_no.'</td>
								<td>'.$payments_value.'</td>
						'.$charges;
						
					$result .= '
								<td>'.$invoice_total.'</td>
								<td>'.($balance).'</td>
							</tr> 
					';
				}
				
				//display cash & all transactions
				else
				{
					$result .= 
						'
							<tr>
								<td>'.$count.'</td>
								<td>'.$visit_date.'</td>
								<td>'.$patient_surname.' '.$patient_othernames.'</td>
								<td>'.$visit_type.'</td>
								<td>'.$doctor.'</td>
								<td>'.$faculty.'</td>
								<td>'.$strath_no.'</td>
								<td>'.$payments_value.'</td>
						'.$charges;
						
					$result .= '
								<td>'.$invoice_total.'</td>
								<td>'.($balance).'</td>
							</tr> 
					';
				}
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no visits";
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