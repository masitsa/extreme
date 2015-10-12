<!-- search -->
<?php echo $this->load->view('search/patient_search', '', TRUE);?>
<!-- end search -->
 <section class="panel">
    <header class="panel-heading">
        	<?php
        	if($type_links == 1){
        		?>
        		 <h2 class="panel-title"><i class="icon-reorder"></i><?php echo $title;?> for <?php echo date('jS M Y',strtotime(date('Y-m-d')));?></h2>

        		<?php
        	}else{
        		?>
        		<h2 class="panel-title"><?php echo $title;?> </h2>

        		<?php
        	}
        	?>
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
<?php
		$search = $this->session->userdata('visit_accounts_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'accounts/close_queue_search/'.$type_links.'" class="btn btn-warning btn-sm">Close Search</a>';
		}
		
		if($module == 0)
		{
			$data['onSubmit'] = 'return confirm(\'Are you sure you want to end multiple visits?\');';
			$result = form_open('accounts/bulk_close_visits/'.$page, $data);
		}
		
		else
		{
			$result = '';
		}
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			//accounts bulk close visit
			if($module == 0)
			{
				$result .= '
					<div class="center-align">
						<button type="submit" class="btn btn-sm btn-danger">End Visits</button>
					</div>
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th></th>
						  <th>Visit Date</th>
						  <th>Patient</th>
						  <th>Doctor</th>
						  <th>Coming From</th>
						  <th>Invoice</th>
						  <th>Payments</th>
						  <th>Balance</th>';

						  if($type_links == 3){
							  $result .=  '<th colspan="2">Actions</th>';
						  }
						  else{
							  $result .= '<th colspan="5">Actions</th>';
						  }
				$result .= 	'</tr>
					  </thead>
					  <tbody>
				';
			}
			
			else
			{
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Patient</th>
						  <th>Doctor</th>
						  <th>Coming From</th>
						  <th>Invoice</th>
						  <th>Payments</th>
						  <th>Balance</th>';

						  	if($type_links == 3){
						  		$result .=  '<th colspan="2">Actions</th>';
						  	}else{
						  		$result .= '<th colspan="5">Actions</th>';
						  	}
				$result .= 	'</tr>
					  </thead>
					  <tbody>
				';
			}
			
			$personnel_query = $this->personnel_model->retrieve_personnel();
			
			foreach ($query->result() as $row)
			{
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
				$coming_from = $this->reception_model->coming_from($visit_id);
				
				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);
				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				
				$checkbox_data = array(
								  'name'        => 'visit[]',
								  'id'          => 'checkbox'.$visit_id,
								  'class'          => 'css-checkbox lrg',
								  'value'       => $visit_id
								);
				
				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_fname;
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
				
				if($module != 1)
				{
					$to_doctor = '<td><a href="'.site_url().'nurse/send_to_doctor/'.$visit_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Send to doctor?\');">To Doctor</a></td>';
				}
				
				else
				{
					$to_doctor = '';
				}
				
				$payments_value = $this->accounts_model->total_payments($visit_id);

				$invoice_total = $this->accounts_model->total_invoice($visit_id);

				$balance = $this->accounts_model->balance($payments_value,$invoice_total);
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>';
				
				//bulk close visits
				if($module == 0)
				{
					$result .= 
					'
							<td>'.form_checkbox($checkbox_data).'<label for="checkbox'.$visit_id.'" name="checkbox79_lbl" class="css-label lrg klaus"></label>'.'</td>';
				}
				
				$result .= 
					'
							<td>'.$visit_date.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$doctor.'</td>
							<td>'.$coming_from.'</td>
							<td>'.$invoice_total.'</td>
							<td>'.$payments_value.'</td>
							<td>'.$balance.'</td>
							
							<td><a href="'.site_url().'accounts/print_receipt_new/'.$visit_id.'" target="_blank" class="btn btn-sm btn-info">Receipt</a></td>
							<td><a href="'.site_url().'accounts/print_invoice_new/'.$visit_id.'" target="_blank" class="btn btn-sm btn-success">Invoice </a></td>';
							if($type_links == 3){

							}else{
							$result .='<td><a href="'.site_url().'accounts/payments/'.$visit_id.'/'.$close_page.'" class="btn btn-sm btn-primary" >Payments</a></td>
							<td><a href="'.site_url().'reception/end_visit/'.$visit_id.'/1" class="btn btn-sm btn-danger" onclick="return confirm(\'End this visit?\');">End Visit</a></td>';
							}

						$result .='</tr> 
					';
			}
			
			$result .= 
				'
							  </tbody>
							</table>
				';
		
			if($module == 0)
			{
				$result .= '
				<br>
				<div class="center-align">
					<button type="submit" class="btn btn-sm btn-danger">End Visits</button>
				</div>
				'.form_close();
			}
		}
		
		else
		{
			$result .= "There are no patients";
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