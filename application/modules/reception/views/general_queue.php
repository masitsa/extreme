<?php
$all_wards = '';
if($wards->num_rows() > 0)
{
	foreach($wards->result() as $row):
		$ward_name = $row->ward_name;
		$ward_id = $row->ward_id;
		
		if($ward_id == set_value('ward_id'))
		{
			$all_wards .= "<option value='".$ward_id."' selected='selected'>".$ward_name."</option>";
		}
		
		else
		{
			$all_wards .= "<option value='".$ward_id."'>".$ward_name."</option>";
		}
	endforeach;
}

$all_doctors = '';
if(count($doctor) > 0){
	foreach($doctor as $row):
		$fname = $row->personnel_fname;
		$onames = $row->personnel_onames;
		$personnel_id = $row->personnel_id;
		
		if($personnel_id == set_value('personnel_id'))
		{
			$all_doctors .= "<option value='".$personnel_id."' selected='selected'>".$onames." ".$fname."</option>";
		}
		
		else
		{
			$all_doctors .= "<option value='".$personnel_id."'>".$onames." ".$fname."</option>";
		}
	endforeach;
}
?>
<!-- search -->
<?php echo $this->load->view('search/search_patients', '', TRUE);?>
<!-- end search -->
 
 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?> for <?php echo date('jS M Y',strtotime(date('Y-m-d')));?></h2>
    </header>
      <div class="panel-body">
          <div class="padd">
          
<?php
		$search = $this->session->userdata('general_queue_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'reception/close_general_queue_search/'.$page_name.'" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
				
				if($page_name == 'nurse')
				{
					$actions = 5;
				}
				
				else if($page_name == 'doctor')
				{
					$actions = 4;
				}
				
				else if($page_name == 'laboratory')
				{
					$actions = 4;
				}
				
				else if($page_name == 'xray')
				{
					$actions = 4;
				}
				
				else if($page_name == 'ultrasound')
				{
					$actions = 4;
				}
				
				else if($page_name == 'pharmacy')
				{
					$actions = 3;
				}
				
				else if($page_name == 'accounts')
				{
					$actions = 5;
				}
				else if($page_name == 'administration')
				{
					$actions = 2;
				}
				
				else
				{
					$actions = 5;
				}
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Patient</th>
						  <th>Visit Type</th>
						  <th>Sent At</th>
						  <th>Going To</th>
						  <th>Coming From</th>
						  <th>Doctor</th>
						  <th colspan="'.$actions.'">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
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
				$visit_created = date('H:i a',strtotime($row->visit_created));
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$accounts = $row->accounts;
				$visit_table_visit_type = $visit_type;
				$patient_table_visit_type = $visit_type_id;
				$coming_from = $this->reception_model->coming_from($visit_id);
				$sent_to = $this->reception_model->going_to($visit_id);
				$visit_type_name = $row->visit_type_name;
				$patient_othernames = $row->patient_othernames;
				$patient_surname = $row->patient_surname;
				$patient_date_of_birth = $row->patient_date_of_birth;
				
				//cash paying patient sent to department but has to pass through the accounts
				if($accounts == 0)
				{
					$sent_to = 'Accounts';
				}
				
				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_onames;
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
				$v_data = array('visit_id'=>$visit_id);
				$count++;
				
				if($page_name == 'nurse')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'nurse/patient_card/'.$visit_id.'/a/0" class="btn btn-sm btn-info">Patient Card</a></td>
					<td><a href="'.site_url().'nurse/send_to_doctor/'.$visit_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Send to doctor?\');">To Doctor</a></td>
					<td><a href="'.site_url().'nurse/send_to_labs/'.$visit_id.'/0" class="btn btn-sm btn-success" onclick="return confirm(\'Send to lab?\');">To Lab</a></td>
					<td><a href="'.site_url().'nurse/send_to_pharmacy/'.$visit_id.'/0" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to pharmacy?\');">To Pharmacy</a></td>
					';
				}
				
				else if($page_name == 'doctor')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'nurse/patient_card/'.$visit_id.'/a/1" class="btn btn-sm btn-info">Patient Card</a></td>
					<td><a href="'.site_url().'nurse/send_to_labs/'.$visit_id.'/1" class="btn btn-sm btn-success" onclick="return confirm(\'Send to lab?\');">To Lab</a></td>
					<td><a href="'.site_url().'nurse/send_to_pharmacy/'.$visit_id.'/1" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to pharmacy?\');">To Pharmacy</a></td>
					';
				}
				
				else if($page_name == 'laboratory')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'laboratory/test/'.$visit_id.'" class="btn btn-sm btn-info">Tests</a></td>
					<td><a href="'.site_url().'laboratory/test_history/'.$visit_id.'" class="btn btn-sm btn-danger">History</a></td>
					<td><a href="'.site_url().'laboratory/send_to_accounts/'.$visit_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Send to accounts?\');">To Accounts</a></td>
					';
				}
				
				else if($page_name == 'radiology')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'radiology/ultrasound/test/'.$visit_id.'" class="btn btn-sm btn-info">Ultrasound</a></td>
					<td><a href="'.site_url().'radiology/xray/test/'.$visit_id.'" class="btn btn-sm btn-warning">X Ray</a></td>
					<td><a href="'.site_url().'radiology/ultrasound/send_to_accounts/'.$visit_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Send to accounts?\');">To Accounts</a></td>
					';
				}
				
				else if($page_name == 'pharmacy')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'pharmacy/prescription1/'.$visit_id.'/1" class="btn btn-sm btn-info">Prescription</a></td>
				
					<td><a href="'.site_url().'pharmacy/send_to_accounts/'.$visit_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Send to accounts?\');">To Accounts</a></td>
					';
				}
				else if($page_name == 'administration')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>';
					//if staff was registered as other
					if(($visit_table_visit_type == 2) && ($patient_table_visit_type != $visit_table_visit_type))
					{
						$buttons .= '<td><a href="'.site_url().'reception/change_patient_type/'.$patient_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to change this patient type?\');">Change Patient Type</a></td>';
					}
					//if student was registered as other
					else if(($visit_table_visit_type == 1) && ($patient_table_visit_type != $visit_table_visit_type))
					{
						$buttons .= '<td><a href="'.site_url().'reception/change_patient_type/'.$patient_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to change this patient type?\');">Change Patient Type</a></td>';
					}
					
					else
					{
						$buttons .= '<td></td>';
					}
					
				}
				
				else if($page_name == 'accounts')
				{
					$buttons = '
					<td>
						<a  class="btn btn-sm btn-success" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-success" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>

					<td><a href="'.site_url().'accounts/print_receipt_new/'.$visit_id.'" target="_blank" class="btn btn-sm btn-info">Receipt</a></td>
					<td><a href="'.site_url().'accounts/print_invoice_new/'.$visit_id.'" target="_blank" class="btn btn-sm btn-success">Invoice </a></td>
					<td><a href="'.site_url().'accounts/payments/'.$visit_id.'" class="btn btn-sm btn-primary" >Payments</a></td>
					<td><a href="'.site_url().'reception/end_visit/'.$visit_id.'/1" class="btn btn-sm btn-danger" onclick="return confirm(\'End this visit?\');">End Visit</a></td>
					';
				}
				
				else
				{

					$buttons = '
					<td>
						<a  class="btn btn-sm btn-success" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
						<a  class="btn btn-sm btn-success" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
					</td>
					<td>
						<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#create_inpatient'.$visit_id.'">Inpatient</button>
						
						<div class="modal fade" id="create_inpatient'.$visit_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Change to inpatient</h4>
									</div>
									<div class="modal-body">
										'.form_open('reception/change_patient_visit/'.$visit_id.'/'.$visit_type_id, array("class" => "form-horizontal")).'
										<div class="form-group">
											<label class="col-md-4 control-label">Ward: </label>
											
											<div class="col-md-8">
												<select name="ward_id" id="ward_id" class="form-control" onchange="check_department_type()">
													<option value="">----Select a ward----</option>
													'.$all_wards.'
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-4 control-label">Doctor: </label>
											
											<div class="col-md-8">
												 <select name="personnel_id" class="form-control">
													<option value="">----Select a Doctor----</option>
													'.$all_doctors.'
												</select>
											</div>
										</div>
                                
										<div class="form-group">
											<label class="col-lg-4 control-label">Admission date: </label>
											
											<div class="col-lg-8">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</span>
													<input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="visit_date" placeholder="Admission Date" value="'.date('Y-m-d').'">
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-8 col-md-offset-4">
												<div class="center-align">
													<button type="submit" class="btn btn-primary">Create inpatient</button>
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
					</td>
					<td><a href="'.site_url().'reception/edit_visit/'.$visit_id.'" class="btn btn-sm btn-primary"> Edit </a></td>
					<td><a href="'.site_url().'reception/end_visit/'.$visit_id.'" class="btn btn-sm btn-info" onclick="return confirm(\'Do you really want to end this visit ?\');">End Visit</a></td>
					<td><a href="'.site_url().'reception/delete_visit/'.$visit_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this visit?\');">Delete</a></td>';
					$buttons .= '<td></td>';
				}
			
								
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$visit_type_name.'</td>
							<td>'.$visit_time.'</td>
							<td>'.$sent_to.'</td>
							<td>'.$coming_from.'</td>
							<td>'.$doctor.'</td>
							'.$buttons.'
						</tr> 
					';
					if($page_name == 'accounts')
					{
						$pink = 15;
					}
					if($page_name == 'administration')
					{
						$pink = 15;
					}
					else if($page_name == 'laboratory')
					{
						$pink = 12;
					}
					else
					{
						$pink = 12;
					}
					$v_data['patient_type'] = $visit_type_id;
				$result .=
						'<tr id="visit_trail'.$visit_id.'" style="display:none;">

							<td colspan="'.$pink.'">'.$this->load->view("nurse/patients/visit_trail", $v_data, TRUE).'</td>
						</tr>';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no patients";
		}
		
?>
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
		echo $result;
		?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->
       

  </section>

  <script type="text/javascript">

	function get_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = '';
		button.style.display = 'none';
		button2.style.display = '';
	}
	function close_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = 'none';
		button.style.display = '';
		button2.style.display = 'none';
	}
  </script>