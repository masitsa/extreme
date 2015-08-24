      <div class="row">
        <div class="col-md-12">
              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Symptoms List</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                            <div class="row">
                                <div class="col-md-12">
                                	<?php 
									$validation_error = validation_errors();
									
									if(!empty($validation_error))
									{
										echo '<div class="alert alert-danger">'.$validation_error.'</div>';
									}
									echo form_open('nurse/search_procedures/'.$visit_id, array('class'=>'form-inline'));
									?>
                                    <div class="form-group">
                                            <?php
											$search = $this->session->userdata('procedure_search');
                                            if(!empty($search))
											{
											?>
                                            <a href="<?php echo site_url().'/nurse/close_procedure_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                            <?php }?>
                                        	<input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                            
                                        <div class="input-group">
                                            <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a symptom">
                                        </div>
                                    </div>
                                        
                                        <input type="hidden" value="<?php echo $visit_id?>" name="visit_id">
                                        
                                    <?php echo form_close();?>
                                </div>
                            </div>
                          
                            <div class="row">
                                <div class="col-md-12">
                                    <table border="0" class="table table-hover table-condensed">
                                        <thead> 
                                           <th>Symptom</th>
                                            <th>Yes</th>  
                                            <th>No</th> 
                                            <th>Description</th>
                                        </thead>
                            
                                        <?php 
                                        
                                        $rs9 = $query->result();
                                        foreach ($rs9 as $rs10) :
                                            $symptoms_id = $rs10->symptoms_id;
                                            $symptoms_name = $rs10->symptoms_name;
                                        ?>
                                        <tr>  
                                            <td align='left'><?php  echo $symptoms_name  ?> </td>
                                            <td ><input name='yes' type='checkbox' value='<?php echo $symptoms_id ?>' onclick='toggleField("myTF<?php echo $symptoms_id; ?>");add_symptoms("<?php echo $symptoms_id ?>","<?php echo 1 ?>","<?php echo $visit_id ?>" );' /></td>
                                            
                                            <td align='right'><input name='no' type='checkbox' value= '<?php echo  $symptoms_id ;?>' onclick='toggleField("myTF<?php echo $symptoms_id; ?>");add_symptoms("<?php echo $symptoms_id ?>","<?php echo 2 ?>","<?php echo $visit_id ?>" );'  /></td>
                                            
                                            <td> <textarea cols='20' name="myTF<?php echo $symptoms_id;?>" id="myTF<?php echo $symptoms_id;?>" rows='4' cols='1' style='display:none;' onKeyUp='update_visit_symptoms("<?php echo $symptoms_id ?>","<?php echo 0; ?>","<?php echo $visit_id ?>" );' required placeholder='Describe <?php echo $symptoms_name; ?>'></textarea>

                                             </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </table>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                        
                    <div class="widget-foot">
                    <?php
                    if(isset($links)){echo $links;}
                    ?>
                    </div>
                 </div>
            </div>
        </div>
<script type="text/javascript">
  
 function toggleField(field1) {

  var myTarget = document.getElementById(field1);

  if(myTarget.style.display == 'none'){
    myTarget.style.display = 'block';
      } else {
    myTarget.style.display = 'none';
    myTarget.value = '';
  }
}

function add_symptoms(symptoms_id, status, visit_id){

  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  var url = config_url+"/nurse/symptoms/"+symptoms_id+"/"+status+"/"+visit_id;


  if(XMLHttpRequestObject) {
    var obj3 = window.opener.document.getElementById("symptoms");
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        
        obj3.innerHTML = XMLHttpRequestObject.responseText;
      }
    }
        
    XMLHttpRequestObject.send(null);
  }
}

function update_visit_symptoms(symptoms_id,status,visit_id){
  
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    var id= "myTF".concat(symptoms_id);
    var description = document.getElementById(id).value;
     var config_url = $('#config_url').val();
    var url = config_url+"/nurse/symptoms/"+symptoms_id+"/"+status+"/"+visit_id+"/"+description;

    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                symptoms2(visit_id);
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}

function symptoms2(visit_id){
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var config_url = $('#config_url').val();
    var url = config_url+"/nurse/view_symptoms/"+visit_id;
   
    if(XMLHttpRequestObject) {
        
        var obj = window.opener.document.getElementById("symptoms");
            
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
