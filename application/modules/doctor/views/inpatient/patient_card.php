
<input type="hidden" value="<?php echo base_url();?>" id="config_url">
 <section class="panel">
	<header class="panel-heading">
		<h2 class="panel-title">Patient card</h2>
	</header>
	
	<!-- Widget content -->
	
	<div class="panel-body">
		
		<div class="well well-sm info">
			<h5 style="margin:0;">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>First name:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $patient_surname;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-6">
								<strong>Other names:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $patient_othernames;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>Gender:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $gender;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-6">
								<strong>Age:</strong>
							</div>
							<div class="col-lg-6">
								<?php echo $age;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-6">
								<strong>Account balance:</strong>
							</div>
							<div class="col-lg-6">
								Kes <?php echo number_format($account_balance, 2);?>
							</div>
						</div>
					</div>
				</div>
			</h5>
		</div>
        
          <div class="center-align">
          	<?php
              	$error = $this->session->userdata('error_message');
              	$validation_error = validation_errors();
				$success = $this->session->userdata('success_message');
				
				if(!empty($error))
				{
					echo '<div class="alert alert-danger">'.$error.'</div>';
					$this->session->unset_userdata('error_message');
				}
				
				if(!empty($validation_error))
				{
					echo '<div class="alert alert-danger">'.$validation_error.'</div>';
				}
				
				if(!empty($success))
				{
					echo '<div class="alert alert-success">'.$success.'</div>';
					$this->session->unset_userdata('success_message');
				}
      		?>
         <div class="clearfix"></div>
          </div>

        
       <div class="clearfix"></div>

			<div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#about-pane" data-toggle="tab">Patient details</a></li>
                <li><a href="#vitals-pane" data-toggle="tab">Vitals</a></li>
                <li><a href="#lifestyle" data-toggle="tab">Nurse Notes</a></li>
                <li><a href="#previous-vitals" data-toggle="tab">Billing</a></li>
                <li><a href="#soap" data-toggle="tab">SOAP</a></li>
                <li><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="about-pane">
                	<?php echo $this->load->view("doctor/inpatient/about", '', TRUE);?>
                </div>
               
                <div class="tab-pane" id="vitals-pane">
                	  <?php echo $this->load->view("doctor/patients/vitals", '', TRUE);?>
                </div>
                
                <div class="tab-pane" id="lifestyle">
                    <?php echo $this->load->view("doctor/patients/nurse_notes", '', TRUE);?>
                </div>
               
                 <div class="tab-pane" id="previous-vitals">
                  <?php echo $this->load->view("doctor/patients/billing", '', TRUE);?>
                </div>

                 <div class="tab-pane" id="soap">
                  <?php echo $this->load->view("doctor/inpatient/soap", '', TRUE);?>
                  
                </div>
                 <div class="tab-pane" id="visit_trail">
                  <?php echo $this->load->view("nurse/patients/visit_trail", '', TRUE);?>
                </div>
                
              </div>
            </div>
        
  </section>
  


<script text="javascript">

var config_url = document.getElementById("config_url").value;
$(document).ready(function(){

  vitals_interface(<?php echo $visit_id;?>);
});

 function vitals_interface(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var url = config_url+"nurse/vitals_interface/"+visit_id;

            
    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("vitals");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                obj.innerHTML = XMLHttpRequestObject.responseText;
                
                var count;
                for(count = 1; count < 12; count++){
                    load_vitals(count, visit_id);
                }
				display_bed_charges(visit_id);
				display_consultation_charges(visit_id);
                previous_vitals(visit_id);
                // get_family_history(visit_id);
                // nurse_notes(visit_id);
                 get_xray_table(visit_id);
                 get_ultrasound_table(visit_id);


                 // suregies
                get_orthopaedic_surgery_table(visit_id);
                get_opthamology_surgery_table(visit_id);
                get_obstetrics_surgery_table(visit_id);
                get_theatre_procedures_table(visit_id);

				display_procedure(visit_id);
				//get_medication(visit_id);
				get_lab_table(visit_id);
				//get_vaccines(visit_id);
				display_vaccines(visit_id);
				display_visit_vaccines(visit_id);
                display_visit_consumables(visit_id);
                display_inpatient_prescription(visit_id,0);

            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}


function load_vitals(vitals_id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/load_vitals/"+vitals_id+"/"+visit_id;//window.alert(url);
  
    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("vital"+vitals_id);
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
                
                if((vitals_id == 8) || (vitals_id == 9)){
                    calculate_bmi(visit_id);
                }
                
                if((vitals_id == 3) || (vitals_id == 4)){
                    calculate_hwr(vitals_id, visit_id);
                }
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function previous_vitals(visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/previous_vitals/"+visit_id;//window.alert(url);

    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("previous_vitals");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function getXMLHTTP() {
     //fuction to return the xml http object
        var xmlhttp=false;  
        try{
            xmlhttp=new XMLHttpRequest();
        }
        catch(e)    {       
            try{            
                xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e){
                try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch(e1){
                    xmlhttp=false;
                }
            }
        }
            
        return xmlhttp;
    }
	
    function save_vital(visit_id)
	{
        var config_url = document.getElementById("config_url").value;
        var data_url = config_url+"nurse/save_vitals/"+visit_id;
       
        var vital5_systolic = $('#vital5').val();
        var vital6_diastolic = $('#vital6').val();
        var vital8_weight = $('#vital8').val();
        var vital9_height = $('#vital9').val();
        var vital4_hip = $('#vital4').val();
        var vital3_waist = $('#vital3').val();
        var vital1_temperature = $('#vital1').val();
        var vital7_pulse = $('#vital7').val();
        var vital2_respiration = $('#vital2').val();
        var vital11_oxygen = $('#vital11').val();
        var vital10_pain = $('#vital10').val();
         
        $.ajax({
        type:'POST',
        url: data_url,
        data:{systolic: vital5_systolic, diastolic : vital6_diastolic, weight: vital8_weight, height : vital9_height,hip : vital4_hip,waist : vital3_waist, temperature : vital1_temperature,pulse : vital7_pulse,respiration: vital2_respiration,oxygen: vital11_oxygen, pain: vital10_pain},
        dataType: 'text',
        success:function(data)
		{
			//calculate_bmi(visit_id);
         //get_medication(visit_id);
         alert("You have successfully entered the vitals");
          previous_vitals(visit_id);
        //obj.innerHTML = XMLHttpRequestObject.responseText;
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
        
    }

    function calculate_bmi(visit_id){
    
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var config_url = document.getElementById("config_url").value;
        var url = config_url+"nurse/calculate_bmi/"+visit_id;

        if(XMLHttpRequestObject) {
            
            var obj = document.getElementById("bmi_out");
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                    //obj.innerHTML = XMLHttpRequestObject.responseText;
                }
            }
                    
            XMLHttpRequestObject.send(null);
        }
    }

function calculate_hwr(vitals_id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/calculate_hwr/"+vitals_id+"/"+visit_id;//window.alert(url);

    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("hwr_out");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                //obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function display_procedure(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"doctor/inpatient/view_procedure/"+visit_id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                document.getElementById("procedures").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function display_bed_charges(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/view_bed_charges/"+visit_id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                document.getElementById("bed_charges").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function display_consultation_charges(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/view_consultation_charges/"+visit_id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                document.getElementById("consultation_charges").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function myPopup3(visit_id) {
    var config_url = document.getElementById("config_url").value;
    var win = window.open( config_url+"nurse/procedures/"+visit_id, "myWindow", "status = 1, height = auto, width = 600, resizable = 0" );
  	win.focus();
}

function calculatevaccinetotal(amount, id, procedure_id, v_id)
{
    var units = document.getElementById('units'+id).value;  

    grand_vaccine_total(id, units, amount, v_id);
}
function calculateconsumabletotal(amount, id, procedure_id, v_id)
{
    var units = document.getElementById('units'+id).value;  

    grand_consumable_total(id, units, amount, v_id);
}

function display_visit_vaccines(visit_id)
{
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/visit_vaccines/"+visit_id;
    
    if(XMLHttpRequestObject) {
                
        var obj = document.getElementById("vaccines_to_patients");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function display_visit_consumables(visit_id)
{
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/visit_consumables/"+visit_id;
    
    if(XMLHttpRequestObject) {
                
        var obj = document.getElementById("consumables_to_patients");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
            {
                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function grand_vaccine_total(vaccine_id, units, amount, v_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/vaccine_total/"+vaccine_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                display_visit_vaccines(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function grand_consumable_total(vaccine_id, units, amount, v_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/consuamble_total/"+vaccine_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                display_visit_consumables(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

//Calculate procedure total
function calculatetotal(amount, id, procedure_id, v_id){
       
    var units = document.getElementById('units'+id).value;  

    grand_total(id, units, amount, v_id);
}

//Calculate bed total
function calculatebedtotal(amount, visit_charge_id, service_charge_id, v_id){
       
    var units = document.getElementById('bed_charge_units'+visit_charge_id).value;  

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/bed_total/"+visit_charge_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
    			display_bed_charges(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function delete_bed_charge(visit_charge_id, v_id)
{
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/delete_bed/"+visit_charge_id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
    			display_bed_charges(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

//Calculate consultation total
function calculateconsultationtotal(amount, visit_charge_id, service_charge_id, v_id){
       
    var units = document.getElementById('consultation_charge_units'+visit_charge_id).value;  

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/bed_total/"+visit_charge_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
    			display_consultation_charges(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function grand_total(procedure_id, units, amount, v_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"nurse/procedure_total/"+procedure_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
    			display_procedure(v_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_procedure(id, visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
     var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/delete_procedure/"+id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                display_procedure(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_bed(id, visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
     var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/delete_bed/"+id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                display_bed_charges(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_consultation(id, visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
     var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/delete_bed/"+id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                display_bed_charges(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_vaccine(id, visit_id){
	
	var confirmation = confirm('Do you really want to delete this vaccine ?');
	
	if(confirmation)
	{
		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		 var config_url = document.getElementById("config_url").value;
		var url = config_url+"nurse/delete_vaccine/"+id;
		
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
	
					display_visit_vaccines(visit_id);
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}
}
function delete_consumable(id, visit_id){
	
	var confirmation = confirm('Delete consumable?');
	
	if(confirmation)
	{
		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var config_url = document.getElementById("config_url").value;
		var url = config_url+"nurse/delete_consumable/"+id;
		
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
	
					display_visit_consumables(visit_id);
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}
}




// start of vaccine

function display_vaccines(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/visit_vaccines/"+visit_id;
  
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                document.getElementById("vaccines_to_patients").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function myPopup4(visit_id) {
    var config_url = document.getElementById("config_url").value;
    var win = window.open( config_url+"nurse/vaccines_list/"+visit_id, "myWindow", "status = 1, height = auto, width = 600, resizable = 0" );
  	win.focus();
}
function myPopup5(visit_id) {
    var config_url = document.getElementById("config_url").value;
    var win = window.open( config_url+"nurse/consumables_list/"+visit_id, "myWindow", "status = 1, height = auto, width = 600, resizable = 0" );
    win.focus();
}
// end of vaccine
function save_medication(visit_id){
    var config_url = document.getElementById("config_url").value;
    var data_url = config_url+"nurse/medication/"+visit_id;
   
     var patient_medication = $('#medication_description').val();
     var patient_medicine_allergies = $('#medicine_allergies').val();
     var patient_food_allergies = $('#food_allergies').val();
     var patient_regular_treatment = $('#regular_treatment').val();
     
    $.ajax({
    type:'POST',
    url: data_url,
    data:{medication: patient_medication,medicine_allergies: patient_medicine_allergies, food_allergies: patient_food_allergies, regular_treatment: patient_regular_treatment },
    dataType: 'text',
    success:function(data){
     get_medication(visit_id);
    //obj.innerHTML = XMLHttpRequestObject.responseText;
    },
    error: function(xhr, status, error) {
    //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
    alert(error);
    }

    });

       
}

function get_medication(visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/load_medication/"+visit_id;

    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("medication");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function get_surgeries(visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/load_surgeries/"+visit_id;
    
    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("surgeries");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function save_surgery(visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var date = document.getElementById("datepicker").value;
    var description = document.getElementById("surgery_description").value;
    var month = document.getElementById("month").value;
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/surgeries/"+date+"/"+description+"/"+month+"/"+visit_id;
   
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                get_surgeries(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_surgery(id, visit_id){
    //alert(id);
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
      var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/delete_surgeries/"+id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                get_surgeries(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function get_vaccines(visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/patient_vaccine/"+visit_id;
    
    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("patient_vaccine");
                
        XMLHttpRequestObject.open("GET", url);

                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
function save_vaccine(vaccine_id, value, visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/";
    
    if(value == 1){
        var yes =  document.getElementById("yes"+vaccine_id);
        
        if(yes.checked == true){
        
            url = url + "vaccine/"+vaccine_id+"/1" ;
            
        }
        else if(yes.checked == false){
        
            url = url + "vaccine/"+vaccine_id+"/2";
            
        }
    }
    
    else if(value == 0){
        var no =  document.getElementById("no"+vaccine_id);
        
        if(no.checked == false){
            url = url + "vaccine/"+vaccine_id+"/1";
            
        }
        else if(no.checked == true){
        
            url = url + "vaccine/"+vaccine_id+"/2";
            
        }
    }
    url = url + "/"+visit_id;
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
                get_vaccines(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
	
}

</script>


<!-- soap items -->
<script type="text/javascript">
    function get_lab_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>laboratory/test_lab/"+visit_id;
    
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("lab_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    function get_xray_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>radiology/xray/test_xray/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("xray_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
     function get_ultrasound_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>radiology/ultrasound/test_ultrasound/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("ultrasound_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    function get_orthopaedic_surgery_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>theatre/test_orthopaedic_surgery/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("orthopaedic_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    function get_opthamology_surgery_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>theatre/test_opthamology_surgery/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("opthamology_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    function get_obstetrics_surgery_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>theatre/test_obstetrics_surgery/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("obstetrics_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    function get_theatre_procedures_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>theatre/test_theatre_procedures/"+visit_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("theatre_procedures_table").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
    // laboratory
    function delete_cost(visit_charge_id, visit_id){
  
      var XMLHttpRequestObject = false;
      
      if (window.XMLHttpRequest) {
        XMLHttpRequestObject = new XMLHttpRequest();
      } 
      
      else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
      }
      var url = config_url+"laboratory/delete_cost/"+visit_charge_id+"/"+visit_id;
      
      if(XMLHttpRequestObject) {
        var obj = document.getElementById("lab_table");
        
        XMLHttpRequestObject.open("GET", url);
        
        XMLHttpRequestObject.onreadystatechange = function(){
          
          if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
            
            obj.innerHTML = XMLHttpRequestObject.responseText;
            //window.location.href = config_url+"data/doctor/laboratory.php?visit_id="+visit_id;
          }
        }
        XMLHttpRequestObject.send(null);
      }
    }
    function delete_procedure(id, visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
     var config_url = document.getElementById("config_url").value;
    var url = config_url+"nurse/delete_procedure/"+id;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                display_procedure(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function delete_ultrasound_cost(visit_charge_id, visit_id)
{
    var res = confirm('Are you sure you want to delete this charge?');
    
    if(res)
    {
        var XMLHttpRequestObject = false;
        
        if (window.XMLHttpRequest) {
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
        
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = config_url+"radiology/ultrasound/delete_cost/"+visit_charge_id+"/"+visit_id;
        
        if(XMLHttpRequestObject) {
            var obj = document.getElementById("ultrasound_table");
            
            XMLHttpRequestObject.open("GET", url);
            
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    obj.innerHTML = XMLHttpRequestObject.responseText;
                    get_ultrasound_table(visit_id);
                }
            }
            XMLHttpRequestObject.send(null);
        }
    }
}

function delete_xray_cost(visit_charge_id, visit_id)
{
    var res = confirm('Are you sure you want to delete this charge?');
    
    if(res)
    {
        var XMLHttpRequestObject = false;
        
        if (window.XMLHttpRequest) {
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
        
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = config_url+"radiology/xray/delete_cost/"+visit_charge_id+"/"+visit_id;
        
        if(XMLHttpRequestObject) {
            var obj = document.getElementById("xray_table");
            
            XMLHttpRequestObject.open("GET", url);
            
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    obj.innerHTML = XMLHttpRequestObject.responseText;
                    get_xray_table(visit_id);
                }
            }
            XMLHttpRequestObject.send(null);
        }
    }
}

function display_inpatient_prescription(visit_id,module){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var config_url = document.getElementById("config_url").value;
    var url = config_url+"pharmacy/display_inpatient_prescription/"+visit_id+"/"+module;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                document.getElementById("visit_prescription").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function save_symptoms(visit_id){
  

  var config_url = $('#config_url').val();
  var data_url = "<?php echo site_url();?>nurse/save_symptoms/"+visit_id;
  //window.alert(data_url);
   var symptoms = document.getElementById("visit_symptoms").value; //$('#visit_symptoms').val();
   window.alert(symptoms);
  $.ajax({
  type:'POST',
  url: data_url,
  data:{notes: symptoms},
  dataType: 'text',
  success:function(data){
    window.alert("You have successfully updated the symptoms");
  //obj.innerHTML = XMLHttpRequestObject.responseText;
  },
  error: function(xhr, status, error) {
  //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
  alert(error);
  }

  });
}

</script>
