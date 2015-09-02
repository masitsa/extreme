<div class="row">
    <div class="col-md-12">

        <!-- Widget -->
        <div class="widget boxed">
            <!-- Widget head -->
            <div class="widget-head">
                <h4 class="pull-left"><i class="icon-reorder"></i>Initiate Pharmacy Visit For <?php echo $patient;?></h4>
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
                    <?php echo form_open("reception/save_initiate_pharmacy/".$patient_id, array("class" => "form-horizontal"));?>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-lg-4 control-label">Patient Type: </label>
                    
                            <div class="col-lg-8">
                                <select name="patient_type" id="patient_type" onChange='insurance_company("patient_type","insured");' class="form-control">
                                    <option value="">--- Select Patient Type---</option>
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
                        
                        <div class="col-md-6" id="insured" style="display:none;">
                            <?php
                                if(count($patient_insurance) > 0){
                            ?>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Insurance Company: </label>
                        
                                <div class="col-lg-8">
                                    <select name="patient_insurance_id" class="form-control">
                                    <option value="">--- Select Insurance Company---</option>
                                        <?php
                                        if(count($patient_insurance) > 0){	
                                            foreach($patient_insurance as $row):
                                                $company_name = $row->company_name;
                                                $insurance_company_name = $row->insurance_company_name;
                                                $patient_insurance_id = $row->patient_insurance_id;
                                                echo "<option value=".$patient_insurance_id.">".$company_name." - ".$insurance_company_name."</option>";
                                            endforeach;	
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Insurance Number: </label>
                        
                                <div class="col-lg-8">
                                    <input name="insurance_id" id="insurance_id" class="form-control" type="text" placeholder="Input Insurance Number">
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="center-align" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-info btn-lg">Initiate Pharmacy Visit</button>
                    </div>
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