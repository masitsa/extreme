<!-- search -->
<?php echo $this->load->view('search/search_inpatients', '', TRUE);?>
<!-- end search -->
 
 <section class="panel">
    <header class="panel-heading">
          <h2 class="panel-title"><i class="icon-reorder"></i><?php echo $title;?></h2>

        	<button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#search_cash_reports"><i class="fa fa-plus"></i> Search patient</button>
    
        </header>
      <div class="panel-body">
       
        <br/>
          <div class="padd">
          
<?php
		$search = $this->session->userdata('general_queue_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'reception/close_general_queue_search/" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = 0;
				
				
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Admission date</th>
						  <th>Patient</th>
						  <th>Ward</th>
						  <th>Room</th>
						  <th>Bed</th>
						  <th>Doctor</th>
						  <th colspan="2">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$admission_date = date('jS M Y',strtotime($row->visit_date));
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$visit_table_visit_type = $visit_type;
				$patient_table_visit_type = $visit_type_id;
				$coming_from = $this->reception_model->coming_from($visit_id);
				$sent_to = $this->reception_model->going_to($visit_id);
				$visit_type_name = $row->visit_type_name;
				$patient_othernames = $row->patient_othernames;
				$patient_surname = $row->patient_surname;
				$patient_date_of_birth = $row->patient_date_of_birth;
				
				//bed details
				$bed_query = $this->reception_model->get_visit_bed($visit_id);
				$ward = $row->ward_name;
				$bed = '<span class="lable label-danger">Unasigned</span>';
				$room = '<span class="lable label-danger">Unasigned</span>';
				
				if($bed_query->num_rows() > 0)
				{
					$row_bed = $bed_query->row();
					
					$ward = $row_bed->ward_name;
					$bed = $row_bed->bed_number;
					$room = $row_bed->room_name;
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
				$v_data = array('visit_id'=>$visit_id);
				$count++;
				
				$buttons = '
				<td>
					<a  class="btn btn-sm btn-danger" id="open_visit'.$visit_id.'" onclick="get_visit_trail('.$visit_id.');">Visit Trail</a>
					<a  class="btn btn-sm btn-danger" id="close_visit'.$visit_id.'" style="display:none;" onclick="close_visit_trail('.$visit_id.');">Close Trail</a></td>
				</td>

				<td><a href="patient-card.html?id='.$visit_id.'" class="btn btn-sm btn-info">Patient Card</a></td>
				';
				
								
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$admission_date.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$ward.'</td>
							<td>'.$room.'</td>
							<td>'.$bed.'</td>
							<td>'.$doctor.'</td>
							'.$buttons.'
						</tr> 
					';
					
					$v_data['patient_type'] = $visit_type_id;
				$result .=
						'<tr id="visit_trail'.$visit_id.'" style="display:none;">

							<td colspan="10">'.$this->load->view("nurse/patients/visit_trail", $v_data, TRUE).'</td>
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