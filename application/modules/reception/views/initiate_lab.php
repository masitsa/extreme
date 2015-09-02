<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Initiate Visit for <?php echo $patient;?></h4>
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
                    
                        $errors = validation_errors();
                        
                        if(!empty($errors))
                        {
                            ?>
                            <div class="alert alert-danger">
                                <?php echo $errors;?>
                            </div>
                            <?php
                        }
                    
                    ?>
				<?php echo form_open("reception/save_initiate_lab/".$patient_id, array("class" => "form-horizontal"));?>
				<div class="row">
					
                    <div class="col-md-6">
				        
					        <div class="form-group">
					            <label class="col-lg-4 control-label">Patient type: </label>
					            
					            <div class="col-lg-8">
					     			 <select name="patient_type" id="patient_type"  onChange='insurance_company("patient_type","insured");' class="form-control">
						                <option value="0">--- Select Patient Type---</option>
						            	<?php
											if(count($type) > 0){
						                		foreach($type as $row):
													$type_name = $row->visit_type_name;
													$type_id= $row->visit_type_id;
													?><option value="<?php echo $type_id; ?>" ><?php echo $type_name ?></option><?php	
												endforeach;
											}
										?>
						             </select>
                            	</div>
                            </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group" style="display:none;" id="insured" >
                            <label class="col-lg-4 control-label" >Patient type: </label>
                            
                            <div class="col-lg-8">
                                
                                <select name="patient_insurance_id" class="form-control">
                                    <option value="0">--- Select Insurance Company---</option>
                                        <?PHP
                                        if(count($patient_insurance) > 0){	
                                        foreach($patient_insurance as $row):
                                                $company_name = $row->company_name;
                                                $insurance_company_name = $row->insurance_company_name;
                                                $patient_insurance_id = $row->patient_insurance_id;
                                                echo "<option value=".$patient_insurance_id.">".$company_name." - ".$insurance_company_name."</option>";						endforeach;	} ?>
                                 </select>
                                    <br>
                                 <input name="insurance_id" id="insurance_id"  type="text" placeholder="Input Insurance Number" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="center-align">
                    <input type="submit" value="Initiate Lab Visit" class="btn btn-large btn-primary"/>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
     </div>
  </div>
  </div>
 <script type="text/javascript" src="<?php echo base_url("js/script.js");?>"></script>
 <script type="text/javascript" charset="utf-8">
	var config_url = $('#config_url').val();
		function check_date(){
		var datess=document.getElementById("datepicker").value;
		if(datess){
			$('#show_doctor').fadeToggle(1000); return false;
		}
		else{
	 
			alert('Select Date First')
		}
	}
	function load_schedule(){
		var datess=document.getElementById("datepicker").value;
		var doctor=document.getElementById("doctor").value;
		var url=config_url+"/welcome/doc_schedule/"+doctor+"/"+datess;
		
		$('#doctors_schedule').load(url);
		$('#doctors_schedule').fadeIn(1000); 
		return false;	
	}

</script>
