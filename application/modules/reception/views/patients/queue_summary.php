<div class="row">
    <div class="col-md-12">

        <section class="panel">
            <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i>Queue summary</h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
            </header>             
    
            <!-- Widget content -->
            <div class="panel-body">
<?php
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			if($visit == 0)
			{
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Patient</th>
						  <th>Visit Type</th>
						  <th>Time In</th>
						  <th>Doctor</th>
						  <th>Actions</th>
						</tr>
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
						  <th>Visit Type</th>
						  <th>Time In</th>
						  <th>Time Out</th>
						  <th>Doctor</th>
						</tr>
					  </thead>
					  <tbody>
				';
			}
			
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
				$patient_national_id = $row->patient_national_id;
				$time_start = $row->time_start;
				$time_end = $row->time_end;
				
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
				
				if($visit == 0)
				{
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$visit_type_name.'</td>
							<td>'.$visit_time.'</td>
							<td>'.$doctor.'</td>
							<td><a href="'.site_url().'/reception/end_visit/'.$visit_id.'/0" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to end this visit?\');">End Visit</a></td>
						</tr> 
					';
				}
				
				else
				{
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$visit_type.'</td>
							<td>'.$visit_time.'</td>
							<td>'.$visit_time_out.'</td>
							<td>'.$doctor.'</td>
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
    
    </section>