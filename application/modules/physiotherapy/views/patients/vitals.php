<?php echo form_open("reception/register-other-patient", array("class" => "form-horizontal"));?>

<div class="row">
	<div class="col-md-6">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Vitals</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                              <!-- vitals from java script -->
                                <div id="vitals"></div>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
       <div class="row">
        <div class="col-md-12">

          <!-- Widget -->
          <div class="widget boxed">
                <!-- Widget head -->
                <div class="widget-head">
                  <h4 class="pull-left"><i class="icon-reorder"></i>Procedures</h4>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                    <a href="#" class="wclose"><i class="icon-remove"></i></a>
                  </div>
                  <div class="clearfix"></div>
                </div>             

            <!-- Widget content -->
                <div class="widget-content">
                    <div class="padd">
                            <!-- visit Procedures from java script -->
                                <div id="procedures"></div>
                             <!-- end of visit procedures -->
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Allergies</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                              <!-- vitals from java script -->
                                <div id="medication"></div>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Surgeries</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                              <!-- vitals from java script -->
                                <div id="surgeries"></div>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Vaccines</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                              <!-- vitals from java script -->
                                <div id="patient_vaccine"></div>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Family History</h4>
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
                                    $v_data['patient_id'] = $this->reception_model->get_patient_id_from_visit($visit_id);
                                    $v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
                                    $v_data['family_disease_query'] = $this->nurse_model->get_family_disease();
                                    $v_data['family_query'] = $this->nurse_model->get_family();
                                ?>
                              <!-- vitals from java script -->
                               <?php echo $this->load->view("patients/family_history", $v_data, TRUE); ?>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Nurse Notes</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                              <!-- vitals from java script -->
                                <?php
                                    $visit_data1['visit_id'] = $visit_id;
                                ?>
                                <?php echo $this->load->view('soap/nurse_notes',$visit_data1, TRUE); ?>
                                <!-- end of vitals data -->
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
    
</div>


<?php echo form_close();?>

<script text="javascript">

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

    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/vitals_interface/"+visit_id;

            
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
                 display_procedure(visit_id);
                 get_medication(visit_id);
                 get_surgeries(visit_id);
                 get_vaccines(visit_id);
                // nurse_notes(visit_id);
                // patient_details(visit_id);
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/load_vitals/"+vitals_id+"/"+visit_id;//window.alert(url);

    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("display"+vitals_id);
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
                
                if((vitals_id == 8) || (vitals_id == 9)){
                    calculate_bmi(vitals_id, visit_id);
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/previous_vitals/"+visit_id;//window.alert(url);

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
    function save_vital(visit_id, vital_id){
        
        var config_url = $('#config_url').val();
        var data_url = config_url+"/nurse/save_vitals/"+vital_id+"/"+visit_id;
        //window.alert(data_url);
         var patient_vital = $('#vital'+vital_id).val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url,
        data:{vital: patient_vital},
        dataType: 'text',
        success:function(data){
        load_vitals(vital_id, visit_id);
        //obj.innerHTML = XMLHttpRequestObject.responseText;
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });
        
    }

    function calculate_bmi(vitals_id, visit_id){
    
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var config_url = $('#config_url').val();
        var url = config_url+"/nurse/calculate_bmi/"+vitals_id+"/"+visit_id;//window.alert(url);

        if(XMLHttpRequestObject) {
            
            var obj = document.getElementById("bmi_out");
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                    obj.innerHTML = XMLHttpRequestObject.responseText;
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/calculate_hwr/"+vitals_id+"/"+visit_id;//window.alert(url);

    if(XMLHttpRequestObject) {
        
        var obj = document.getElementById("hwr_out");
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

                obj.innerHTML = XMLHttpRequestObject.responseText;
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
    
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/view_procedure/"+visit_id;
  
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

function myPopup3(visit_id) {
    var config_url = $('#config_url').val();
    window.open( config_url+"/nurse/procedures/"+visit_id, "myWindow", "status = 1, height = auto, width = 600, resizable = 0" )
}






function calculatetotal(amount, id, procedure_id, v_id){
       
    var units = document.getElementById('units'+id).value;  

    grand_total(id, units, amount);
    display_procedure(v_id);
}

function grand_total(procedure_id, units, amount){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var url = config_url+"/nurse/procedure_total/"+procedure_id+"/"+units+"/"+amount;
    
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
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
     var config_url = $('#config_url').val();
    var url = config_url+"/nurse/delete_procedure/"+id;
    
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
function save_medication(visit_id){
    var config_url = $('#config_url').val();
    var data_url = config_url+"/nurse/medication/"+visit_id;
   
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

    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/load_medication/"+visit_id;

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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/load_surgeries/"+visit_id;
    
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/surgeries/"+date+"/"+description+"/"+month+"/"+visit_id;
 
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
      var config_url = $('#config_url').val();
    var url = config_url+"/nurse/delete_surgeries/"+id;
    
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/patient_vaccine/"+visit_id;
    
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
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/";
    
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