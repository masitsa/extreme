<?php
$rs = $this->nurse_model->check_visit_type($visit_id);
if(count($rs)>0){
  foreach ($rs as $rs1) {
    # code...
      $visit_t = $rs1->visit_type;
  }
}



// echo $visit_t; die();
$consumable_order = 'service_charge.service_charge_name';
$consumable_table = 'service_charge,product,category';
$consumble_where = 'service_charge.product_id = product.product_id AND category.category_id = product.category_id AND category.category_name = "Consumable" AND service_charge.visit_type_id = '.$visit_t;

$consumable_query = $this->nurse_model->get_inpatient_consumable_list($consumable_table, $consumble_where, $consumable_order);
$rs8 = $consumable_query->result();
$consumables = '';
foreach ($rs8 as $consumable_rs) :


$consumable_id = $consumable_rs->service_charge_id;
$consumable_name = $consumable_rs->service_charge_name;

$consumable_name_stud = $consumable_rs->service_charge_amount;

    $consumables .="<option value='".$consumable_id."'>".$consumable_name." KES.".$consumable_name_stud."</option>";

endforeach;


$order = 'service_charge.service_charge_name';

$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "OSH" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;

$table = 'service_charge,visit_type,service, departments';
$config["per_page"] = 0;
$procedure_query = $this->nurse_model->get_other_procedures($table, $where, $order);

$rs9 = $procedure_query->result();
$procedures = '';
foreach ($rs9 as $rs10) :


$procedure_id = $rs10->service_charge_id;
$proced = $rs10->service_charge_name;
$visit_type = $rs10->visit_type_id;
$visit_type_name = $rs10->visit_type_name;

$stud = $rs10->service_charge_amount;

    $procedures .="<option value='".$procedure_id."'>".$proced." KES.".$stud."</option>";

endforeach;

$vaccine_order = 'service_charge.service_charge_name';

$vaccine_where = 'service_charge.service_id = service.service_id AND (service.service_name = "vaccine" OR service.service_name = "Vaccination") AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;
$vaccine_table = 'service_charge,visit_type,service';

$vaccine_query = $this->nurse_model->get_inpatient_vaccines_list($vaccine_table, $vaccine_where, $vaccine_order);
    

$rs10 = $vaccine_query->result();
$vaccines = '';
foreach ($rs10 as $vaccine_rs) :


  $vaccine_id = $vaccine_rs->service_charge_id;
  $vaccine_name = $vaccine_rs->service_charge_name;
  $visit_type_name = $vaccine_rs->visit_type_name;

  $vaccine_price = $vaccine_rs->service_charge_amount;

  $vaccines .="<option value='".$vaccine_id."'>".$vaccine_name." KES.".$vaccine_price."</option>";

endforeach;





// put it undet the soap 


$lab_test_order = 'service_charge_name';

//$where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
$lab_test_where = 'service_charge.service_charge_name = lab_test.lab_test_name AND lab_test_class.lab_test_class_id = lab_test.lab_test_class_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "Laboratory" OR service.service_name = "laboratory" OR service.service_name = "Laboratory test")  AND  service_charge.visit_type_id = '.$visit_t;
    
$lab_test_table = '`service_charge`, lab_test_class, lab_test, service';

$lab_test_query = $this->lab_model->get_inpatient_lab_tests($lab_test_table, $lab_test_where, $lab_test_order);

$rs11 = $lab_test_query->result();
$lab_tests = '';
foreach ($rs11 as $lab_test_rs) :


  $lab_test_id = $lab_test_rs->service_charge_id;
  $lab_test_name = $lab_test_rs->service_charge_name;

  $lab_test_price = $lab_test_rs->service_charge_amount;

  $lab_tests .="<option value='".$lab_test_id."'>".$lab_test_name." KES.".$lab_test_price."</option>";

endforeach;




$xray_order = 'service_charge_name';
$xray_where = 'service_charge.service_id = service.service_id AND (service.service_name = "X Ray" OR service.service_name = "xray" OR service.service_name = "XRay" OR service.service_name = "xray" OR service.service_name = "Xray")  AND  service_charge.visit_type_id = '.$visit_t;
$xray_table = '`service_charge`, service';
$xray_query = $this->xray_model->get_inpatient_xrays($xray_table, $xray_where, $xray_order);

$rs12 = $xray_query->result();
$xrays = '';
foreach ($rs12 as $xray_rs) :


  $xray_id = $xray_rs->service_charge_id;
  $xray_name = $xray_rs->service_charge_name;

  $xray_price = $xray_rs->service_charge_amount;

  $xrays .="<option value='".$xray_id."'>".$xray_name." KES.".$xray_price."</option>";

endforeach;



$ultra_sound_order = 'service_charge_name';
    
$ultra_sound_where = 'service_charge.service_id = service.service_id AND (service.service_name = "Ultrasound" OR service.service_name = "ultrasound") AND  service_charge.visit_type_id = '.$visit_t;


$ultra_sound_table = '`service_charge`, service';

$ultra_sound_query = $this->ultrasound_model->get_inpatient_ultrasounds($ultra_sound_table, $ultra_sound_where, $ultra_sound_order);
$rs13 = $ultra_sound_query->result();
$ultrasound = '';
foreach ($rs13 as $ultra_sound_rs) :


  $ultra_sound_id = $ultra_sound_rs->service_charge_id;
  $ultra_sound_name = $ultra_sound_rs->service_charge_name;

  $ultra_sound_price = $ultra_sound_rs->service_charge_amount;

  $ultrasound .="<option value='".$ultra_sound_id."'>".$ultra_sound_name." KES.".$ultra_sound_price."</option>";

endforeach;




// start of surgeries


$orthopaedic_order = 'service_charge_name';
    
$orthopaedic_where = 'service_charge.service_id = service.service_id AND service.service_id = 25  AND  service_charge.visit_type_id = '.$visit_t;
$orthopaedic_table = '`service_charge`, service';

$orthopaedic_query = $this->theatre_model->get_inpatient_surgeries($orthopaedic_table, $orthopaedic_where,$orthopaedic_order);

$rs14 = $orthopaedic_query->result();
$orthopaedics = '';
foreach ($rs14 as $orthopaedic_rs) :


  $orthopaedic_id = $orthopaedic_rs->service_charge_id;
  $orthopaedic_name = $orthopaedic_rs->service_charge_name;

  $orthopaedic_price = $orthopaedic_rs->service_charge_amount;

  $orthopaedics .="<option value='".$orthopaedic_id."'>".$orthopaedic_name." KES.".$orthopaedic_price."</option>";

endforeach;



$opthamology_order = 'service_charge_name';
    
$opthamology_where = 'service_charge.service_id = service.service_id AND service.service_id = 29  AND  service_charge.visit_type_id = '.$visit_t;
$opthamology_table = '`service_charge`, service';

$opthamology_query = $this->theatre_model->get_inpatient_surgeries($opthamology_table, $opthamology_where,$opthamology_order);

$rs14 = $opthamology_query->result();
$opthamology = '';
foreach ($rs14 as $opthamology_rs) :


  $opthamology_id = $opthamology_rs->service_charge_id;
  $opthamology_name = $opthamology_rs->service_charge_name;

  $opthamology_price = $opthamology_rs->service_charge_amount;

  $opthamology .="<option value='".$opthamology_id."'>".$opthamology_name." KES.".$opthamology_price."</option>";

endforeach;


$obstetrics_order = 'service_charge_name';
    
$obstetrics_where = 'service_charge.service_id = service.service_id AND service.service_id = 30  AND  service_charge.visit_type_id = '.$visit_t;
$obstetrics_table = '`service_charge`, service';

$obstetrics_query = $this->theatre_model->get_inpatient_surgeries($obstetrics_table, $obstetrics_where,$obstetrics_order);

$rs14 = $obstetrics_query->result();
$obstetrics = '';
foreach ($rs14 as $obstetrics_rs) :


  $obstetrics_id = $obstetrics_rs->service_charge_id;
  $obstetrics_name = $obstetrics_rs->service_charge_name;

  $obstetrics_price = $obstetrics_rs->service_charge_amount;

  $obstetrics .="<option value='".$obstetrics_id."'>".$obstetrics_name." KES.".$obstetrics_price."</option>";

endforeach;


$theatre_order = 'service_charge_name';
    
$theatre_where = 'service_charge.service_id = service.service_id  AND service.service_id = 27  AND  service_charge.visit_type_id = '.$visit_t;
$theatre_table = '`service_charge`, service';

$theatre_query = $this->theatre_model->get_inpatient_surgeries($theatre_table, $theatre_where,$theatre_order);

$rs14 = $theatre_query->result();
$theatre = '';
foreach ($rs14 as $theatre_rs) :


  $theatre_id = $theatre_rs->service_charge_id;
  $theatre_name = $theatre_rs->service_charge_name;

  $theatre_price = $theatre_rs->service_charge_amount;

  $theatre .="<option value='".$theatre_id."'>".$theatre_name." KES.".$theatre_price."</option>";

endforeach;

// end of surgeries



$drugs_order = 'product.product_name'; 
$drugs_where = 'product.product_id = service_charge.product_id  AND service_charge.service_id = service.service_id AND (service.service_name = "Pharmacy" OR service.service_name = "pharmacy") AND service_charge.visit_type_id = '.$visit_t;
$drugs_table = 'product, service_charge, service';
$drug_query = $this->pharmacy_model->get_inpatient_drugs($drugs_table, $drugs_where, $drugs_order);

$rs15 = $drug_query->result();
$drugs = '';
foreach ($rs15 as $drug_rs) :


  $drug_id = $drug_rs->service_charge_id;
  $drug_name = $drug_rs->service_charge_name;
  $brand_name = $drug_rs->brand_name;

  $drug_price = $drug_rs->service_charge_amount;

  $drugs .="<option value='".$drug_id."'>".$drug_name." Brand: ".$brand_name." KES.".$drug_price."</option>";

endforeach;

// end of surgeries
  

?>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Consumables</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='consumable_id' name='consumable_id' class="form-control custom-select">
                      <option value=''>None - Please Select a consumable</option>
                      <?php echo $consumables;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_consumable_charge(<?php echo $visit_id;?>,1);"> Add Consumable</button>
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
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='procedure_id' name='procedure_id' class="form-control custom-select">
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
                    <select id='vaccine_id' name='vaccine_id' class="form-control custom-select">
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
                <h2 class="panel-title">Lab Tests</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='lab_test_id' name='lab_test_id' class="form-control custom-select">
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
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">XRAY</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='xray_id' name='xray_id' class="form-control custom-select">
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
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Ultrasound</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='ultrasound_id' name='ultrasound_id' class="form-control custom-select">
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
         </section>
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
                    <select id='drug_id' name='drug_id' class="form-control custom-select">
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
              
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Orthopaedic Surgery</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='orthopaedic_surgery_id' name='orthopaedic_surgery_id' class="form-control custom-select">
                      <option value=''>None - Please Select an arthopaedic procedure</option>
                      <?php echo $orthopaedics;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_orthopaedic_surgery(<?php echo $visit_id;?>);"> Add a surgery procedure</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="orthopaedic_surgery_table"></div>
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Opthamology Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='opthamology_surgery_id' name='opthamology_surgery_id' class="form-control custom-select">
                      <option value=''>None - Please Select an opthamology procedure</option>
                      <?php echo $opthamology;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_opthamology_surgery(<?php echo $visit_id;?>);"> Add a surgery procedure</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="opthamology_surgery_table"></div>
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Obstetrics Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='obstetrics_surgery_id' name='obstetrics_surgery_id' class="form-control custom-select">
                      <option value=''>None - Please Select an opthamology procedure</option>
                      <?php echo $obstetrics;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_obstetrics_surgery(<?php echo $visit_id;?>);"> Add a surgery procedure</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="obstetrics_surgery_table"></div>
         </section>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Theatre Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='theatre_procedure_id' name='theatre_procedure_id' class="form-control custom-select">
                      <option value=''>None - Please Select an theatre procedure</option>
                      <?php echo $theatre;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_theatre_procedures(<?php echo $visit_id;?>);"> Add a surgery procedure</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="theatre_procedures_table"></div>
         </section>
    </div>
</div>

<script text="javascript">

var config_url = document.getElementById("config_url").value;
$(document).ready(function(){

	$(function() {
		$("#consumable_id").customselect();
		$("#procedure_id").customselect();
		$("#vaccine_id").customselect();
		$("#lab_test_id").customselect();
		$("#xray_id").customselect();
		$("#ultrasound_id").customselect();
		$("#drug_id").customselect();
		$("#orthopaedic_surgery_id").customselect();
		$("#opthamology_surgery_id").customselect();
		$("#obstetrics_surgery_id").customselect();
		$("#theatre_procedure_id").customselect();
	});
});
  $(document).on("change","select#ward_id",function(e)
  {
    var ward_id = $(this).val();
    
    //get rooms
    $.get( "<?php echo site_url();?>nurse/get_ward_rooms/"+ward_id, function( data ) 
    {
      $( "#room_id" ).html( data );
      
      $.get( "<?php echo site_url();?>nurse/get_room_beds/0", function( data ) 
      {
        $( "#bed_id" ).html( data );
      });
    });
  });
function parse_lab_test(visit_id,suck)
{
  var lab_test_id = document.getElementById("lab_test_id").value;

  
}
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

    var url = "<?php echo site_url();?>pharmacy/inpatient_prescription/"+visit_id+"/"+drug_id+"/1";

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
</script>





<!-- transfer to soap when done by alvaro -->

<script type="text/javascript">

  function parse_lab_test(visit_id)
  {
    var lab_test_id = document.getElementById("lab_test_id").value;
     lab(lab_test_id, visit_id);
    
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
function parse_consumable_charge(visit_id,suck)
{
  var consumable_id = document.getElementById("consumable_id").value;
  // alert(opthamology_surgery_id);
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
    // window.alert(url);
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

  
  },
  error: function(xhr, status, error) {
  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

  }
  });
  var prescription_view = document.getElementById("prescription_view");
  prescription_view.style.display = 'none';
  display_inpatient_prescription(visit_id,0);
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


</script>


<?php
// $order = 'diseases_id';
        
// $where = 'diseases_id > 0 ';
// $table = 'diseases';
// // $procedure_query = $this->nurse_model->get_procedures($table, $where, $config["per_page"], $order);
// $config["per_page"] = 0;
// $page = 0;
// $query = $this->nurse_model->get_diseases($table, $where, $config["per_page"], $page, $order);

// $rs9 = $query->result();
// $procedures = '';
// // var_dump($query->result());
// foreach ($rs9 as $rs10) :


// $diseases_name = $rs10->diseases_name;
// $diseases_name =  str_replace(","," ", $diseases_name);
// $diseases_name =  str_replace("[","", $diseases_name);
// $diseases_name =  str_replace("]","", $diseases_name);
// $diseases_name =  str_replace("'","", $diseases_name);

//     $procedures .= "'".$diseases_name."',";

// endforeach;
?>
