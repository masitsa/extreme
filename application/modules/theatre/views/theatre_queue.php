<!-- search -->
<?php //echo $this->load->view('patients/search_visit', '', TRUE);?>
<!-- end search -->
 
<div class="row">
    <div class="col-md-12">
 	<section class="panel">
    	<header class="panel-heading">
          	<h2 class="panel-title"><?php echo $title;?> for <?php echo date('jS M Y',strtotime(date('Y-m-d')));?></h2>
        </header>             

        <!-- Widget content -->
        <div class="panel-body">
          
<?php
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'nurse/close_queue_search" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Patient</th>
						  <th>Visit Type</th>
						  <th>Sent At</th>
						  <th>Coming From</th>
						  <th>Doctor</th>
						  <th colspan="2">Actions</th>
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
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$visit_created.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$visit_type_name.'</td>
							<td>'.$visit_time.'</td>
							<td>'.$coming_from.'</td>
							<td>'.$doctor.'</td>
							<td><a href="'.site_url().'nurse/patient_card/'.$visit_id.'/a/2" class="btn btn-sm btn-success">Patient Card</a></td>
							<td><a href="'.site_url().'theatre/send_to_account/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to accounts?\');">To Accounts</a></td>
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
			$result .= "There are no patients";
		}
		
		echo $result;
?>
                  </div>
                  
                  <div class="panel-foot">
                                        
                        <?php if(isset($links)){echo $links;}?>
                    
                    </div>
                </div>
                <!-- Widget ends -->
        </section>
    </div>
</div>
  <audio id="sound1" src="<?php echo base_url();?>sound/beep.mp3"></audio>
  <script type="text/javascript">
  	$(document).ready(function(){
		setInterval(function(){check_new_patients()},10000);
	 });
	function check_new_patients()
		{	

		 var XMLHttpRequestObject = false;
        
		    if (window.XMLHttpRequest) {
		    
		        XMLHttpRequestObject = new XMLHttpRequest();
		    } 
		        
		    else if (window.ActiveXObject) {
		        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		    }
		    
		    var config_url = $('#config_url').val();
		    var url = config_url+"/dental/queue_cheker";
		    
		    if(XMLHttpRequestObject) {
		                
		        XMLHttpRequestObject.open("GET", url);
		                
		        XMLHttpRequestObject.onreadystatechange = function(){
		            
		            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
		            	
	         			var one = XMLHttpRequestObject.responseText;
	         			if(one == 1)
	         			{
	         				 var audio1 = document.getElementById("sound1");
						 	 if (audio1.paused !== true){
							    audio1.pause();
							 }
							 else
							 {
								audio1.play();
							 }
	         			}
	         			else
	         			{

	         			}
			         	
	         
		            }
		        }
		                
		        XMLHttpRequestObject.send(null);
		    }
		}
	

  </script>