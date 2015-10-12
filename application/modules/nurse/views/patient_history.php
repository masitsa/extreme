<!-- search -->
<?php 

		
		 $patient_id = $this->nurse_model->get_patient_id($visit_id);
		// this is it

		$where = 'visit.`close_card` = 1 AND visit.patient_id = patients.patient_id AND visit.`patient_id`='.$patient_id;
		
		$table = 'visit,patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'nurse/patient_card/'.$visit_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->nurse_model->get_all_patient_history($table, $where, $config["per_page"], $page);
		
	
		
?>
<!-- end search -->
 
<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Patient history</h2>
			</header>

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
                                  <th>Assessment</th>
                                  <th>Diagnosis</th>
                                  <th>Plan</th>
                                  <th>Waiting time</th>
                                  <th>Doctor</th>
                                  <th colspan="4">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                        ';
                    
                    $personnel_query = $this->personnel_model->get_all_personnel();
                    $count = 1;
                    foreach ($query->result() as $row)
                    {
                        $visit_date = date('jS M Y',strtotime($row->visit_date));
                        $visit_time = date('H:i a',strtotime($row->visit_time));
        
                        $visit_id1 = $row->visit_id;
                        $waiting_time = $this->nurse_model->waiting_time($visit_id1);
                        $patient_id = $row->patient_id;
                        $personnel_id = $row->personnel_id;
                        $dependant_id = $row->dependant_id;
                        $strath_no = $row->strath_no;
                        $created_by = $row->created_by;
                        $modified_by = $row->modified_by;
                        $visit_type_id = $row->visit_type_id;
                        $visit_type = $row->visit_type;
                        $created = $row->patient_date;
                        $last_modified = $row->last_modified;
                        $last_visit = $row->last_visit;
                        
                        $patient_type = $this->reception_model->get_patient_type($visit_type_id);
                        
                        $visit_type = 'General';
                        
                        $patient_othernames = $row->patient_othernames;
                        $patient_surname = $row->patient_surname;
                        $title_id = $row->title_id;
                        $patient_date_of_birth = $row->patient_date_of_birth;
                        $civil_status_id = $row->civil_status_id;
                        $patient_address = $row->patient_address;
                        $patient_post_code = $row->patient_postalcode;
                        $patient_town = $row->patient_town;
                        $patient_phone1 = $row->patient_phone1;
                        $patient_phone2 = $row->patient_phone2;
                        $patient_email = $row->patient_email;
                        $patient_national_id = $row->patient_national_id;
                        $religion_id = $row->religion_id;
                        $gender_id = $row->gender_id;
                        $patient_kin_othernames = $row->patient_kin_othernames;
                        $patient_kin_sname = $row->patient_kin_sname;
                        $relationship_id = $row->relationship_id;
                        
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
        
                        //  get the visit assessment
        
                        $assesment_rs = $this->nurse_model->get_assessment($visit_id1);
                         $assesment_num_rows = count($assesment_rs);
                        $assessment = '';
                        if($assesment_num_rows > 0){
                            foreach ($assesment_rs as $key_assessment):
                            $visit_assessment = $key_assessment->visit_assessment;
                            endforeach;
                            $assessment .="".$visit_assessment."";
                            
                        }
                        else
                        {
                            $assessment .='-';
                        }
                        // end of the visit assessment
        
        
                        //  start of plan
                        $plan_rs = $this->nurse_model->get_plan($visit_id1);
                        $plan_num_rows = count($plan_rs);
                        $plan = "";
                        if($plan_num_rows > 0){
                            foreach ($plan_rs as $key_plan):
                                $visit_plan = $key_plan->visit_plan;
                            endforeach;
                            $plan .="".$visit_plan."";
                            
                        }
                        else
                        {
                            $plan .="-";
                        }
                        // end of plan
        
                        // start of diagnosis
                        $diagnosis_rs = $this->nurse_model->get_diagnosis($visit_id1);
                        $diagnosis_num_rows = count($diagnosis_rs);
                        //echo $num_rows;
                        $patient_diagnosis = "";
                        if($diagnosis_num_rows > 0){
                            foreach ($diagnosis_rs as $diagnosis_key):
                                $diagnosis_id = $diagnosis_key->diagnosis_id;
                                $diagnosis_name = $diagnosis_key->diseases_name;
                                $diagnosis_code = $diagnosis_key->diseases_code;
                                $patient_diagnosis .="".$diagnosis_code."-".$diagnosis_name."<br>";
                                
                            endforeach;
                        }
                        else
                        {
                            $patient_diagnosis .= "-";
                        }
                        // end of diagnosis
                        
                        
                        
                        $result .= 
                            '
                                <tr>
                                    <td>'.$count.'</td>
                                    <td>'.$visit_date.'</td>
                                    <td>'.$assessment.'</td>
                                    <td>'.$patient_diagnosis.'</td>
                                    <td>'.$plan.'</td>
                                    <td align=center>'.$waiting_time.'</td>
                                    <td>'.$doctor.'</td>
                                    <td><a href="'.site_url().'doctor/patient_card/'.$visit_id1.'" target="_blank" class="btn btn-sm btn-info">Patient Card</a></td>
                                </tr> 
                            ';
                            $count++;
                    }
                    
                    $result .= 
                    '
                                  </tbody>
                                </table>
                    ';
                }
                
                else
                {
                    $result .= "There is no history";
                }
                
                echo $result;
				?>
          	</div>
          
          	<div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
		</section>
    </div>
</div>
  <script>
	  function patient_history_popup(visit_id,mike) {
	    var config_url = $('#config_url').val();
	    window.open( config_url+"nurse/patient_card/"+visit_id+"/"+mike, "myWindow", "status = 1, height = auto, width = 100%, resizable = 0" )
		}

  </script>