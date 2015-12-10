<?php
if($dental == 1)
{

}
else{
$data['visit_id'] = $visit_id;
$data['lab_test'] = 100;
?>

<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Symptoms</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <div id="symptoms">
               	<?php echo $this->load->view("nurse/soap/view_symptoms", $data, TRUE); ?>
                </div>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Objective findings</h2>
			</header>

			<div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/soap/view_objective_findings", $data, TRUE); ?>
                <!-- end of visit procedures -->
            </div>
		</section>
    </div>
</div>

<div class="row">
 	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Assesment</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("nurse/soap/view_assessment", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
</div>
<?php
}
?>


<div id="test_results">
  <div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Lab Tests</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='lab_test_id' name='lab_test_id' class='form-control custom-select ' >
                      <option value=''>None - Please Select a Lab test</option>
                      <?php echo $lab_tests;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_lab_test(<?php echo $visit_id;?>);"> Add Lab Test</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
            <div id="lab_table"></div>
            <?php echo $this->load->view("laboratory/tests/test2", $data, TRUE); ?>
         </section>
    </div>
</div>

</div>

<div id="xray_results">
  <div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">XRAY</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='xray_id' name='xray_id' class='form-control custom-select ' >
                      <option value=''>None - Please Select an XRAY</option>
                      <?php echo $xrays;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_xray(<?php echo $visit_id;?>);"> Add an XRAY</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
            <div id="xray_table"></div>
            <?php echo $this->load->view("radiology/tests/test2", $data, TRUE); ?>
         </section>
    </div>
</div>
	
</div>

<div id="ultrasound_results">
  <div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">ULTRASOUND</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='ultrasound_id' name='ultrasound_id' class='form-control custom-select ' >
                      <option value=''>None - Please Select an Ulra sound</option>
                      <?php echo $ultrasound;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_ultrasound(<?php echo $visit_id;?>);"> Add an Ultrasound</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="ultrasound_table"></div>
             <?php echo $this->load->view("radiology/tests_ultrasound/test2", $data, TRUE); ?>
         </section>
    </div>
</div>
	
</div>

<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Prescription</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='drug_id' name='drug_id' class='form-control custom-select '>
                      <option value=''>None - Please Select an drug</option>
                      <?php echo $drugs;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="get_drug_to_prescribe(<?php echo $visit_id;?>);"> Prescribe drug</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="prescription_view"></div>
             <div id="visit_prescription"></div>

             <?php // echo $this->load->view("pharmacy/display_prescription", $data, TRUE); ?>
              
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-info">
      <header class="panel-heading">
        <h2 class="panel-title">Plan</h2>
      </header>

      <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='diseases_id' name='diseases_id' class='form-control custom-select ' >
                      <option value=''>None - Please Select a diagnosis</option>
                      <?php echo $diseases;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="pass_diagnosis(<?php echo $visit_id;?>);"> Identify the disease</button>
                  </div>
                </div>
      </div>
      <!-- visit Procedures from java script -->
     
      <!-- end of visit procedures -->
      <div id="patient_diagnosis"></div>

      <?php echo $this->load->view("nurse/soap/view_plan", $data, TRUE); ?>
            
    </section>
    </div>
</div>

<div class="row">
 	<div class="col-md-6">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Doctor's notes</h2>
			</header>

			<div class="panel-body">
                <!-- vitals from java script -->
                <?php echo $this->load->view("nurse/soap/doctor_notes", $data, TRUE); ?>
                <!-- end of vitals data -->
            </div>
		</section>
    </div>
    
    <div class="col-md-6">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Nurse notes</h2>
			</header>

			<div class="panel-body">
                <!-- visit Procedures from java script -->
                <?php echo $this->load->view("nurse/soap/nurse_notes", $data, TRUE); ?>
                <!-- end of visit procedures -->
            </div>
		</section>
    </div>
</div>




<script type="text/javascript">

$(function() {
    $("#drug_id").customselect();
    $("#lab_test_id").customselect();
    $("#diseases_id").customselect();
    $("#xray_id").customselect();
    $("#ultrasound_id").customselect();
});
$(document).ready(function(){

	//symptoms(<?php echo $visit_id;?>);
	//objective_findings(<?php echo $visit_id;?>);
	//assessment(<?php echo $visit_id;?>);
	//plan(<?php echo $visit_id;?>);
	//doctor_notes(<?php echo $visit_id;?>);
	//nurse_notes(<?php echo $visit_id?>);
  	get_disease(<?php echo $visit_id?>);
  	display_prescription(<?php echo $visit_id?>,0);
  	get_lab_table(<?php echo $visit_id;?>);
    get_xray_table(<?php echo $visit_id;?>);
    get_ultrasound_table(<?php echo $visit_id;?>);
    get_disease(<?php echo $visit_id;?>);


                 // suregies
    // get_orthopaedic_surgery_table(<?php echo $visit_id;?>);
    // get_opthamology_surgery_table(<?php echo $visit_id;?>);
    // get_obstetrics_surgery_table(<?php echo $visit_id;?>);
    // get_theatre_procedures_table(<?php echo $visit_id;?>);

  
    display_inpatient_prescription(<?php echo $visit_id;?>,0);
});
  
function symptoms(visit_id)
{
	var XMLHttpRequestObject = false;
    
  	if (window.XMLHttpRequest) {
  
    	XMLHttpRequestObject = new XMLHttpRequest();
  	} 
    
  	else if (window.ActiveXObject) {
    	XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  	}
  
   	var config_url = $('#config_url').val();

  	var url = config_url+"nurse/view_symptoms/"+visit_id;
 
  	if(XMLHttpRequestObject) {
    
    	var obj = document.getElementById("symptoms");
      
    	XMLHttpRequestObject.open("GET", url);
        
    	XMLHttpRequestObject.onreadystatechange = function(){
      
      		if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        		obj.innerHTML = XMLHttpRequestObject.responseText;
        
				// patient_details(visit_id);
				// objective_findings(visit_id);
				// assessment(visit_id);
				// plan(visit_id);
				// doctor_notes(visit_id);
				// nurse_notes(visit_id);
      		}
    	}
        
    	XMLHttpRequestObject.send(null);
  	}
}


function objective_findings(visit_id){
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
   var config_url = $('#config_url').val();
  var url = config_url+"nurse/view_objective_findings/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("objective_findings");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}
function save_doctor_notes(visit_id){
      var config_url = $('#config_url').val();
        var data_url = config_url+"nurse/save_doctor_notes/"+visit_id;
        //window.alert(data_url);
         var doctor_notes = $('#doctor_notes_item').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
        type:'POST',
        url: data_url,
        data:{notes: doctor_notes},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });

      
}




function assessment(visit_id){
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/view_assessment/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("assessment");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function plan(visit_id){
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/view_plan/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("plan");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
         get_test_results(100, visit_id);
         //closeit(79, visit_id);
         display_prescription(visit_id, 2);
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function get_test_results(page, visit_id){
 
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  if((page == 1) || (page == 65) || (page == 85)){
  // request from the lab
    url = config_url+"laboratory/test/"+visit_id;
  }
  
  else if ((page == 75) || (page == 100)){
    // this is for the doctor and the nurse
    url = config_url+"laboratory/test2/"+visit_id;
  }
  
  if(XMLHttpRequestObject) {
    if((page == 75) || (page == 85)){
      var obj = window.opener.document.getElementById("test_results");
    }
    else{
      var obj = document.getElementById("test_results");
    }
    XMLHttpRequestObject.open("GET", url);
    
    XMLHttpRequestObject.onreadystatechange = function(){
    
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
  //window.alert(XMLHttpRequestObject.responseText);
        obj.innerHTML = XMLHttpRequestObject.responseText;
        if((page == 75) || (page == 85)){
          window.close(this);
        }
        
      }
    }
    XMLHttpRequestObject.send(null);
  }
}

function display_prescription(visit_id, page){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
   var config_url = $('#config_url').val();
  var url = config_url+"pharmacy/display_prescription/"+visit_id;
  
  if(page == 1){
    var obj = window.opener.document.getElementById("prescription");
  }
  
  else{
    var obj = document.getElementById("visit_prescription");
  }
  if(XMLHttpRequestObject) {
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
        
        if(page == 1){
          window.close(this);
        
        }
        //plan(visit_id);
      }
    }
    
    XMLHttpRequestObject.send(null);
  }
}

function open_window_lab(test, visit_id){
	
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"laboratory/laboratory_list/"+test+"/"+visit_id,"Popup","height=1200, width=600, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
}

function open_window_xray(test, visit_id){
	
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"radiology/xray/xray_list/"+test+"/"+visit_id,"Popup","height=1200, width=600, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
}

function open_window_ultrasound(test, visit_id){
	
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"radiology/ultrasound/ultrasound_list/"+test+"/"+visit_id,"Popup","height=1200, width=600, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
}

function open_window_surgery(test, visit_id){
	
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"theatre/surgery_list/"+test+"/"+visit_id,"Popup","height=1200, width=600, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
}

function open_symptoms(visit_id){
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"nurse/symptoms_list/"+visit_id,"Popup","height=1000,width=600,,scrollbars=yes,"+ 
                        "directories=yes,location=yes,menubar=yes," + 
                         "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
    
  
}

function open_objective_findings(visit_id){
  var config_url = $('#config_url').val();
  var win = window.open(config_url+"nurse/objective_finding/"+visit_id,"Popup","height=600,width=1000,,scrollbars=yes,"+ 
                        "directories=yes,location=yes,menubar=yes," + 
                         "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
  
}


function save_assessment(visit_id){
 
  var config_url = $('#config_url').val();
  var data_url = config_url+"nurse/save_assessment/"+visit_id;
  //window.alert(data_url);
  //var assessment = $('#visit_assessment').val();//document.getElementById("vital"+vital_id).value;
   var assessment = tinymce.get('visit_assessment').getContent();
  $.ajax({
  type:'POST',
  url: data_url,
  data:{notes: assessment},
  dataType: 'text',
  success:function(data){
    window.alert("You have successfully updated the assessment");
  //obj.innerHTML = XMLHttpRequestObject.responseText;
  },
  error: function(xhr, status, error) {
  //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
  alert(error);
  }

  });
}

function open_window(plan, visit_id){
    var config_url = $('#config_url').val();
  if(plan == 6){
  
    var win = window.open(config_url+"nurse/disease/"+visit_id,"Popup","height=1000,width=600,,scrollbars=yes,"+ 
                        "directories=yes,location=yes,menubar=yes," + 
                         "resizable=no status=no,history=no top = 50 left = 100");
  	win.focus();
  }
  else if (plan == 1){
    
    var win = window.open(config_url+"pharmacy/prescription/"+visit_id,"Popup","height=1200,width=1300,,scrollbars=yes,"+ 
                        "directories=yes,location=yes,menubar=yes," + 
                         "resizable=yes status=yes,history=yes top = 50 left = 100");
  	win.focus();
  }
}



function doctor_notes(visit_id){
    var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/doctor_notes/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("doctor_notes");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
        //symptoms3(visit_id);
        
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function nurse_notes(visit_id){
    var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/nurse_notes/"+visit_id;
  
  if(XMLHttpRequestObject) {
    
    var obj = document.getElementById("nurse_notes");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function save_symptoms(visit_id){
  
	console.debug(tinymce.activeEditor.getContent());
	//alert(tinymce.activeEditor.getContent());
  var config_url = $('#config_url').val();
  var data_url = config_url+"nurse/save_symptoms/"+visit_id;
  //window.alert(data_url);
  // var symptoms = tinymce.activeEditor.getContent();
   var symptoms = tinymce.get('visit_symptoms').getContent();
   //$('#visit_symptoms').val();//document.getElementById("vital"+vital_id).value;
   //alert(symptoms);
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

function save_objective_findings(visit_id){
 
	var config_url = $('#config_url').val();
	var data_url = config_url+"nurse/save_objective_findings/"+visit_id;
	//window.alert(data_url);
   var objective_findings = tinymce.get('visit_objective_findings').getContent();
	//var objective_findings = $('#visit_objective_findings').val();//document.getElementById("vital"+vital_id).value;
	//alert(objective_findings);
	$.ajax({
		type:'POST',
		url: data_url,
		data:{notes: objective_findings},
		dataType: 'text',
		success:function(data){
			window.alert("You have successfully updated the objective findings");
			//obj.innerHTML = XMLHttpRequestObject.responseText;
		},
		error: function(xhr, status, error) {
			//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
			alert(error);
		}
	});
}
function save_plan(visit_id){
 
  var config_url = $('#config_url').val();
  var data_url = config_url+"nurse/save_plan/"+visit_id;
  //window.alert(data_url);
  // var plan = $('#visit_plan').val();//document.getElementById("vital"+vital_id).value;
  
   var plan = tinymce.get('visit_plan').getContent();
  $.ajax({
  type:'POST',
  url: data_url,
  data:{notes: plan},
  dataType: 'text',
  success:function(data){
    window.alert("You have successfully updated the visit plan");
  //obj.innerHTML = XMLHttpRequestObject.responseText;
  },
  error: function(xhr, status, error) {
  //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
  alert(error);
  }

  });
}

function get_disease(visit_id){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/get_diagnosis/"+visit_id;

      
  if(XMLHttpRequestObject) {
    var obj = document.getElementById("visit_diagnosis_original");
    //var obj2 = document.getElementById("visit_diagnosis");
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
        //obj2.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
    
    XMLHttpRequestObject.send(null);
  }
}

$(document).on("click","a.delete_diagnosis",function() 
{
    var diagnosis_id = $(this).attr('href');
    var visit_id = $(this).attr('id');
	var config_url = $('#config_url').val();
	var url = config_url+"nurse/delete_diagnosis/"+diagnosis_id;
	
	$.get(url, function( data ) {
		get_disease(visit_id);
	});
	
	return false;
});

function print_previous_test(visit_id, patient_id){
	var config_url = $('#config_url').val();
	var win = window.open(config_url+"laboratory/print_test/"+visit_id+"/"+patient_id,"Popup","height=900,width=1200,,scrollbars=yes,"+
					"directories=yes,location=yes,menubar=yes," +
					 "resizable=no status=no,history=no top = 50 left = 100");
  win.focus();
}
</script>
<script type="text/javascript">

  function parse_lab_test(visit_id)
  {
    var lab_test_id = document.getElementById("lab_test_id").value;
     lab(lab_test_id, visit_id);
    
  }
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
  
   function lab(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>laboratory/test_lab/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("lab_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_lab_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}
function parse_xray(visit_id)
{
  var xray_id = document.getElementById("xray_id").value;
  xray(xray_id, visit_id);

}

function xray(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>radiology/xray/test_xray/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("xray_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_xray_table(visit_id);
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
function parse_ultrasound(visit_id)
{
  var ultrasound_id = document.getElementById("ultrasound_id").value;
  ultrasound(ultrasound_id, visit_id);

}
function ultrasound(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>radiology/ultrasound/test_ultrasound/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("ultrasound_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_ultrasound_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}

function parse_orthopaedic_surgery(visit_id)
{
  var orthopaedic_surgery_id = document.getElementById("orthopaedic_surgery_id").value;
  orthopaedic(orthopaedic_surgery_id, visit_id);

}

function orthopaedic(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>theatre/test_orthopaedic_surgery/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("orthopaedic_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_surgery_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}
function parse_opthamology_surgery(visit_id)
{
  var opthamology_surgery_id = document.getElementById("opthamology_surgery_id").value;
  // alert(opthamology_surgery_id);
  opthamology(opthamology_surgery_id, visit_id);

}

function opthamology(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>theatre/test_opthamology_surgery/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("opthamology_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_surgery_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}

function parse_obstetrics_surgery(visit_id)
{
  var obstetrics_surgery_id = document.getElementById("obstetrics_surgery_id").value;
  obsterics(obstetrics_surgery_id, visit_id);

}

function obsterics(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>theatre/test_obstetrics_surgery/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("obstetrics_surgery_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_surgery_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}

function parse_theatre_procedures(visit_id)
{
  var theatre_procedure_id = document.getElementById("theatre_procedure_id").value;
  theatre_procedure(theatre_procedure_id, visit_id);

}

function theatre_procedure(id, visit_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>theatre/test_theatre_procedures/"+visit_id+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("theatre_procedures_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_surgery_table(visit_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}

function delete_inpatient_surgery_cost(visit_charge_id, visit_id,surgery_type)
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
    var url = config_url+"theatre/delete_inpatient_cost/"+visit_charge_id+"/"+visit_id;
    
    if(XMLHttpRequestObject) {
      
      XMLHttpRequestObject.open("GET", url);
      
      XMLHttpRequestObject.onreadystatechange = function(){
        
        if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
          
          
          if(surgery_type == 25)
          {
              // orthopaedic procedures
              get_orthopaedic_surgery_table(visit_id);
                

          }
          else if(surgery_type == 29)
          {
              // opthamology procedures
              get_opthamology_surgery_table(visit_id);
                
          }
          else if(surgery_type == 30)
          {
              // obstetrics procedures
              get_obstetrics_surgery_table(visit_id);
                
          }
          else if(surgery_type == 27)
          {
              // theatre procedures
              get_theatre_procedures_table(visit_id);

          }
        }
      }
      XMLHttpRequestObject.send(null);
    }
  }
}

function pass_prescription()
{
  var quantity = document.getElementById("quantity_value").value;
  var x = document.getElementById("x_value").value;
  var duration = document.getElementById("duration_value").value;
  var consumption = document.getElementById("consumption_value").value;
  var number_of_days = document.getElementById("number_of_days_value").value;
  var service_charge_id = document.getElementById("drug_id").value;
  var visit_id = document.getElementById("visit_id").value;
  var module = document.getElementById("module").value;
  var passed_value = document.getElementById("passed_value").value;

  var url = "<?php echo base_url();?>pharmacy/prescribe_prescription";


  $.ajax({
  type:'POST',
  url: url,
  data:{quantity: quantity, x: x, duration: duration,consumption: consumption, service_charge_id : service_charge_id, visit_id : visit_id, number_of_days: number_of_days,module: module,passed_value:passed_value},
  dataType: 'text',
  success:function(data){

  var prescription_view = document.getElementById("prescription_view");
  prescription_view.style.display = 'none';
  display_inpatient_prescription(visit_id,0);
  
  },
  error: function(xhr, status, error) {
  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

  }
  });
 
  return false;
}

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

function button_update_prescription(visit_id,visit_charge_id,prescription_id,module)
{
  var quantity = $('#quantity'+prescription_id).val();
  var x = $('#x'+prescription_id).val();
  var duration = $('#duration'+prescription_id).val();
  var consumption = $('#consumption'+prescription_id).val();
  var url = "<?php echo base_url();?>pharmacy/update_inpatient_prescription/"+visit_id+"/"+visit_charge_id+"/"+prescription_id+"/"+module;


  $.ajax({
  type:'POST',
  url: url,
  data:{quantity: quantity, x: x, duration: duration,consumption: consumption},
  dataType: 'text',
  success:function(data){

  
  },
  error: function(xhr, status, error) {
  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

  }
  });
  display_inpatient_prescription(visit_id,0);
  return false;
}

function dispense_prescription(visit_id,visit_charge_id,prescription_id,module)
{
  var quantity = $('#quantity'+prescription_id).val();
  var x = $('#x'+prescription_id).val();
  var duration = $('#duration'+prescription_id).val();
  var consumption = $('#consumption'+prescription_id).val();
  var charge = $('#charge'+prescription_id).val();
  var units_given = $('#units_given'+prescription_id).val();

  var url = "<?php echo base_url();?>pharmacy/dispense_inpatient_prescription/"+visit_id+"/"+visit_charge_id+"/"+prescription_id+"/"+module;

  $.ajax({
  type:'POST',
  url: url,
  data:{quantity: quantity, x: x, duration: duration,consumption: consumption,charge: charge, units_given: units_given},
  dataType: 'text',
  success:function(data){
    window.alert(data.result);
  
  },
  error: function(xhr, status, error) {
  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

  }
  });
  display_inpatient_prescription(visit_id,0);
  return false;
}


function delete_prescription(prescription_id, visit_id,visit_charge_id,module)
{
  var res = confirm('Are you sure you want to delete this prescription ?');
  
  if(res)
  {
    var XMLHttpRequestObject = false;
    
    if (window.XMLHttpRequest) {
      XMLHttpRequestObject = new XMLHttpRequest();
    } 
    
    else if (window.ActiveXObject) {
      XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = config_url+"pharmacy/delete_inpatient_prescription/"+prescription_id+"/"+visit_id+"/"+visit_charge_id+"/"+module;
    
    if(XMLHttpRequestObject) {
      
      XMLHttpRequestObject.open("GET", url);
      
      XMLHttpRequestObject.onreadystatechange = function(){
        
        if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
          
           display_inpatient_prescription(visit_id,0);
         
        }
      }
      XMLHttpRequestObject.send(null);
    }
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
function get_drug_to_prescribe(visit_id)
{
  var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var drug_id = document.getElementById("drug_id").value;

    var url = "<?php echo site_url();?>pharmacy/inpatient_prescription/"+visit_id+"/"+drug_id+"/2";

     if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
              var prescription_view = document.getElementById("prescription_view");
             
              document.getElementById("prescription_view").innerHTML=XMLHttpRequestObject.responseText;
               prescription_view.style.display = 'block';
            }
        }
                
        XMLHttpRequestObject.send(null);
    }


}

function pass_diagnosis(visit_id)
{
  var diseases_id = document.getElementById("diseases_id").value;
  save_disease(diseases_id, visit_id);

}

function save_disease(val, visit_id){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  } 
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/save_diagnosis/"+val+"/"+visit_id;
  if(XMLHttpRequestObject) {
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        get_disease(visit_id);
      }
    }
    
    XMLHttpRequestObject.send(null);
  }
}

function get_disease(visit_id){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
   var config_url = $('#config_url').val();
  var url = "<?php echo site_url();?>nurse/get_diagnosis/"+visit_id;
  

      
  if(XMLHttpRequestObject) {
      var obj = document.getElementById("patient_diagnosis");
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
    
    XMLHttpRequestObject.send(null);
  }
}

</script>
