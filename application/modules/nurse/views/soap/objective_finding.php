<?php
	$get_objective_rs = $this->medical_admin_model->objective_findings();
	$num_rows = count($get_objective_rs);
	
	$rs2 = $this->nurse_model->get_visit_objective_findings($visit_id);
	$num_rows2 = count($rs2);

	$objective_findings_classes = $this->medical_admin_model->get_objective_findings_classes();
?>

<?php echo form_open("reception/register-other-patient", array("class" => "form-horizontal"));?>

<div class="row">
	<div class="col-md-12">
		
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">Objective findings</h2>
			</header>

			<div class="panel-body">
            	<?php echo "
				<div class='center-align'>
					<input name='close' type='button' value='Close' class='btn btn-primary' onclick='close_objective_findings(".$visit_id.")' />
				</div>";
				?>
				<?php
                $count = 0;
                $prev_name = '';
				
				if($objective_findings_classes->num_rows() > 0)
				{
					foreach($objective_findings_classes->result() as $res)
					{
						$class_name = $res->objective_findings_class_name;
						$class_id = $res->objective_findings_class_id;   
						?>
						<section class="panel panel-featured panel-featured-info">
							<header class="panel-heading">
								<h2 class="panel-title"><?php echo $class_name;?></h2>
							</header>
					
							<div class="panel-body">
						<?php 
						
						foreach ($get_objective_rs as $key2):
							$class_id2 = $key2->objective_findings_class_id;
							
							if($class_id == $class_id2)
							{
								$s = $count;
								$objective_name = $key2->objective_findings_name;
								$objective_id = $key2->objective_findings_id;
								$status = 0;
								$description= '';
							
								if($num_rows2 > 0)
								{
									foreach ($rs2 as $key)
									{
										$objective_findings_id = $key->objective_findings_id;
										
										if($objective_findings_id == $objective_id)
										{
											$status = 1;
											$objective_findings_name = $key->objective_findings_name;
											$visit_objective_findings_id = $key->visit_objective_findings_id;
											$objective_findings_class_name = $key->objective_findings_class_name;
											$description= $key->description;
											break;
										}
									}
								}
								?>
							
								<div class="col-md-3">
									<div class="checkbox">
										<label>
											<?php 
												if($status == 1)
												{
													?>
													<input name="<?php echo $objective_name?>" type="checkbox" onClick="add_objective_findings(<?php echo $objective_id;?>, <?php echo $visit_id?>, <?php echo $status;?>);toggleField('myTF<?php echo $objective_id;  ?>');" checked="checked" id="objective_check">
											<?php
												}
												
												else
												{
													?>
													<input name="<?php echo $objective_name?>" type="checkbox" onClick="add_objective_findings(<?php echo $objective_id;?>, <?php echo $visit_id?>, <?php echo $status;?>);toggleField('myTF<?php echo $objective_id;  ?>');" id="objective_check">
											<?php
												}
											?>
											
											<?php echo $objective_name?>
										</label>
									</div>
									
									<?php 
										if($status == 1)
										{
											?>
											<input name="myTF<?php echo $objective_id;?>" id="myTF<?php echo $objective_id;  ?>" type="text" style="display:block;" onKeyUp="update_visit_obj(<?php echo $objective_id;?>, <?php echo $visit_id?>, <?php echo 1;?>);" value="<?php echo $description;?>"/>
									<?php
										}
										
										else
										{
											?>
											<input name="myTF<?php echo $objective_id;?>" id="myTF<?php echo $objective_id;  ?>" type="text" style="display:none;" onKeyUp="update_visit_obj(<?php echo $objective_id;?>, <?php echo $visit_id?>, <?php echo 1;?>);" value="<?php echo $description;?>"/>
									<?php
										}
									?>
									
								</div> 
								<?php 
							}
							$count++;
						endforeach;     
						?>
							</div>
						</section>
						<?php 
					}
				}
                ?>
					    
            </div>
        </section>
		<?php echo "
        <div class='center-align'>
            <input name='close' type='button' value='Close' class='btn btn-primary' onclick='close_objective_findings(".$visit_id.")' />
        </div>";
		?>
    </div>
</div>
<?php echo form_close();?>

<script type="text/javascript">
	
	function close_objective_findings(visit_id)
	{
		window.close(this);
	}

function add_objective_findings(objective_findings_id, visit_id, status)
{
	
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"nurse/add_objective_findings/"+objective_findings_id+"/"+visit_id+"/"+status;
 
  if(XMLHttpRequestObject) {
    var obj3 = window.opener.document.getElementById("visit_objective_findings1");
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
	  {
		  	var myTarget = document.getElementById('objective_check');
			
			if(status == '1')
			{
				myTarget.value = '0';
			}
			
			else
			{
				myTarget.value = '1';
			}
        	obj3.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function toggleField(objective_findings) 
{
	var myTarget = document.getElementById(objective_findings);

	if(myTarget.style.display == 'none'){
  		myTarget.style.display = 'block';
    } 
	
	else {
	  myTarget.style.display = 'none';
	  myTarget.value = '';
	}
}


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
  	var url = config_url+"nurse/update_objective_findings/"+objective_findings_id+"/"+visit_id+"/"+update_id+"/"+description;
		if(XMLHttpRequestObject) {
				
		XMLHttpRequestObject.open("GET", url);
				
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
				var obj3 = window.opener.document.getElementById("visit_objective_findings1");
				obj3.innerHTML = XMLHttpRequestObject.responseText;
			}
		}
				
		XMLHttpRequestObject.send(null);
	}
}
</script>