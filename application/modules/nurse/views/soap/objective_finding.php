<?php


$get_objective_rs = $this->medical_admin_model->objective_findings();
$num_rows = count($get_objective_rs);

$function = $this->medical_admin_model->visit_objective($visit_id);
$num_function = count($function);

if($num_function > 0){
	foreach ($function as $key):
		$function_name = $key->visit_objective_findings;
	endforeach;
	
}


?>

<?php echo form_open("reception/register-other-patient", array("class" => "form-horizontal"));?>

<div class="row">
	<div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Objective Findings</h4>
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
                       $count = 0;
                       $prev_name = '';
						foreach ($get_objective_rs as $key2):
							$s = $count;
						$objective_name = $key2->objective_findings_name;
						$class_name = $key2->objective_findings_class_name;
						$objective_id = $key2->objective_findings_id;
						
						
						if (($count == 0)){           
							?>

					    	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><?php echo $class_name;?></p>
					    	<?php 
						} 
						
						else if($class_name != $prev_name){         
							?>
					        </div>
					    	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><?php echo $prev_name;?></p>
					    	<?php 
						}

						$prev_name = $class_name;
						?>

					    <div class="col-md-3"><input name="<?php echo $objective_name?>" type="checkbox" onClick="add_objective_findings(<?php echo $objective_id;?>, <?php echo $visit_id?>, 0);toggleField('myTF<?php echo $objective_id;  ?>');"/><?php echo $objective_name?><input name="myTF<?php echo $objective_id;?>" id="myTF<?php echo $objective_id;  ?>" type="text" style="display:none;" onKeyUp="update_visit_obj(<?php echo $objective_id;?>, <?php echo $visit_id?>, <?php echo 1;?>);"/> 
					  </div> 
						<?php 
						$count++;
						endforeach;
						 ?>
					    </div>
					    <?php echo "
						<table border='0' align='center'>
							<tr align='center'>
								<td><input name='close' type='button' value='Close' class='btn btn-large' onclick='close_objective_findings(".$visit_id.")' /></td>
							</tr>
						</table>";?>
                        </div>

                     </div>
                
                </div>
            </div>
        </div>
    </div>
   
</div>
<?php echo form_close();?>

<script type="text/javascript">
	
	function close_objective_findings(visit_id){
  window.close(this);
}

function add_objective_findings(objective_findings_id, visit_id,status){
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"/nurse/add_objective_findings/"+objective_findings_id+"/"+visit_id+"/"+status;
 
  if(XMLHttpRequestObject) {
    var obj3 = window.opener.document.getElementById("objective_findings");
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        
        obj3.innerHTML = XMLHttpRequestObject.responseText;
        
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function toggleField(objective_findings) {
var myTarget = document.getElementById(objective_findings);

if(myTarget.style.display == 'none'){
  myTarget.style.display = 'block';
    } else {
  myTarget.style.display = 'none';
  myTarget.value = '';
}}


function update_visit_obj(objective_findings_id,visit_id,update_id){
	
	var XMLHttpRequestObject = false;
		
	if (window.XMLHttpRequest) {
	
		XMLHttpRequestObject = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var id= "myTF".concat(objective_findings_id);
	var description = document.getElementById(id).value;
	var config_url = $('#config_url').val();
  	var url = config_url+"/nurse/add_objective_findings/"+objective_findings_id+"/"+visit_id+"/"+update_id+"/"+description;
		if(XMLHttpRequestObject) {
				
		XMLHttpRequestObject.open("GET", url);
				
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
			//	window.alert(objective_findings1(visit_id));
				symptoms(visit_id);
			}
		}
				
		XMLHttpRequestObject.send(null);
	}
}
</script>