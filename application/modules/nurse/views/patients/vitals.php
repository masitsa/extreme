<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Vitals</h2>
            </header>
            <div class="panel-body">
                <!-- vitals from java script -->
                <div id="vitals"></div>
                <!-- end of vitals data -->
            </div>
         </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Visit vitals</h2>
			</header>
			<div class="panel-body">
				<div id="previous_vitals"></div>
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12"> 

        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='procedure_id' name='procedure_id' class='form-control custom-select '>
                    <!-- <select class="form-control custom-select " id='procedure_id' name='procedure_id'> -->
                      <option value=''>None - Please Select</option>
                      <?php echo $procedures;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_procedures(<?php echo $visit_id;?>,1);"> Add Procedure</button>
                  </div>
                </div>

            </div>


                     
           <!-- visit Procedures from java script -->
            <div id="procedures"></div>
            <!-- end of visit procedures -->
            
         </section>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Vaccines</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='vaccine_id' name='vaccine_id' class='form-control custom-select '>
                  	
                    <!-- <select class="form-control custom-select " id='vaccine_id' name='vaccine_id'> -->
                      <option value=''>None - Please Select a vaccine</option>
                      <?php echo $vaccines;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_vaccine(<?php echo $visit_id;?>,1);"> Add Vaccine</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
            <div id="vaccines_to_patients"></div>
         </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Consumables</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='consumable_id' name='consumable_id' class='form-control custom-select '>
                    <!-- <select class="form-control custom-select" id='consumable_id' name='consumable_id'> -->
                      <option value=''>None - Please Select a consumable</option>
                      <?php echo $consumables;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button class="btn btn-sm btn-success"  onclick="parse_consumable_charge(<?php echo $visit_id;?>,1);"> Add Consumable</button>
                  </div>
                </div>
               
            </div>
             <!-- visit Procedures from java script -->
                <div id="consumables_to_patients"></div>
                <!-- end of visit procedures -->
         </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Nurse notes</h2>
			</header>

			<div class="panel-body">
				<!-- vitals from java script -->
				<?php
				$visit_data1['visit_id'] = $visit_id;
				?>
				<?php echo $this->load->view('soap/nurse_notes',$visit_data1, TRUE); ?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<script text="javascript">
$(function() {
    $("#consumable_id").customselect();
    $("#procedure_id").customselect();
    $("#vaccine_id").customselect();
});
$(document).ready(function(){
  	vitals_interface(<?php echo $visit_id;?>);

	$(function() {
		$("#consumable_id").customselect();
		$("#vaccine_id").customselect();
		$("#procedure_id").customselect();
	});
});

 function vitals_interface(visit_id){

    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var config_url = document.getElementById("config_url").value;
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
                previous_vitals(visit_id);
                // get_family_history(visit_id);
                // nurse_notes(visit_id);
                // patient_details(visit_id);
				display_procedure(visit_id);
				get_medication(visit_id);
				get_surgeries(visit_id);
				get_vaccines(visit_id);
				display_vaccines(visit_id);
				display_visit_vaccines(visit_id);
                display_visit_consumables(visit_id);
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
    var url = config_url+"nurse/view_procedure/"+visit_id;
    
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

<script type="text/javascript">
    // other changes
    function parse_procedures(visit_id,suck)
    {
      var procedure_id = document.getElementById("procedure_id").value;
       procedures(procedure_id, visit_id, suck);
      
    }
    function parse_vaccine(visit_id,suck)
    {
      var vaccine_id = document.getElementById("vaccine_id").value;
       vaccines_anchor(vaccine_id, visit_id, suck);
      
    }
    function procedures(id, v_id, suck){
       
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        var url = "<?php echo site_url();?>nurse/procedure/"+id+"/"+v_id+"/"+suck;
       
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
    function vaccines_anchor(id, v_id, suck){
   
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
      
        var url = "<?php echo site_url();?>nurse/vaccines/"+id+"/"+v_id+"/"+suck;
      
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
    function parse_consumable_charge(visit_id,suck)
    {
      var consumable_id = document.getElementById("consumable_id").value;
       // alert(consumable_id);
      consumable(consumable_id, visit_id,suck);

    }

    function consumable(id, visit_id,suck){
        
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>nurse/inpatient_consumables/"+id+"/"+visit_id+"/"+suck;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                   document.getElementById("consumables_to_patients").innerHTML = XMLHttpRequestObject.responseText;
                   //get_surgery_table(visit_id);
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }

</script>