<?php
$rs = $this->nurse_model->check_visit_type($visit_id);
if(count($rs)>0){
  foreach ($rs as $rs1) {
    # code...
      $visit_t = $rs1->visit_type;
  }
}
$order = 'service_charge.service_charge_name';

$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "OSH" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;

$procedure_search = $this->session->userdata('procedure_search');

if(!empty($procedure_search))
{
    $where .= $procedure_search;
}

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
    $procedures .= "'".$proced." KES. ".$stud."',";

endforeach;




?>

 <script>
  $(function() {
    var availableTags = [
      <?php echo $procedures;?>
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });
  </script>
<div class="row">
    <div class="col-md-12"> 

        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-6">
                  <input type="search" class="form-control" id="tags" placeholder="Search For Procedure..." >
                </div>
                <div class="col-lg-6">
                 <a href="#" onClick="procedures(<?php echo $procedure_id?>,<?php echo $visit_id?>,<?php echo $suck; ?>)"><?php echo $proced?> </a></td>
                </div>

            </div>
                     
           <!-- visit Procedures from java script -->
            <div id="procedures"></div>
            <!-- end of visit procedures -->
            </div>
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
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Vaccine' onclick='myPopup4(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="vaccines_to_patients"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>

<script type="text/javascript">

    
     $(function() {    
        $('#input-search').on('keyup', function() {
          var rex = new RegExp($(this).val(), 'i');
            $('.searchable-container .items').hide();
            $('.searchable-container .items').filter(function() {
                return rex.test($(this).text());
            }).show();
        });
    });

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
                window.close(this);
                
                window.opener.document.getElementById("procedures").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
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
