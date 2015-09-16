 <section class="panel">
    <header class="panel-heading">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Lab Test List</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        <a href="#" class="wclose"><i class="icon-remove"></i></a>
                      </div>
                      <div class="clearfix"></div>
                    </header>             

                <!-- Widget content -->
                    <div class="panel-body">
                        <div class="padd">
                            <div class="row">
                                <div class="col-md-12">
                                	<?php 
									$validation_error = validation_errors();
									
									if(!empty($validation_error))
									{
										echo '<div class="alert alert-danger">'.$validation_error.'</div>';
									}
									echo form_open('laboratory/search_laboratory_tests/'.$visit_number, array('class'=>'form-inline'));
									?>
                                    <div class="form-group">
                                            <?php
											$search = $this->session->userdata('lab_test_search');
                                            if(!empty($search))
											{
											?>
                                            <a href="<?php echo site_url().'/laboratory/close_lab_test_search/'.$visit_number;?>" class="btn btn-warning pull-right">Close Search</a>
                                            <?php }?>
                                        	<input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                            
                                        <div class="input-group">
                                            <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a test">
                                        </div>
                                    </div>
                                        
                                        <input type="hidden" value="<?php echo $visit_number?>" name="visit_number">
                                        
                                    <?php echo form_close();?>
                                </div>
                            </div>
                          
                            <div class="row">
                                <div class="col-md-12">
                                    <table border="0" class="table table-hover table-condensed">
                                        <thead> 
                                            <th></th>
                                            <th> Name </th>
                                            <th> Class</th>
                                            <th> Cost</th>
                                        </thead>
                            
                                        <?php 
                                        //echo "current - ".$current_item."end - ".$end_item;
                                        
                                        $rs9 = $query->result();
                                        foreach ($rs9 as $rs10) :
                                            $name = $rs10->service_charge_name;
                                            $class = $rs10->lab_test_class_name;
                                            $cost = $rs10->service_charge_amount;
                                            $id = $rs10->service_charge_id;
                                        
                                        
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" name="laboratory_id" value=""  onclick="lab(<?php echo $id?>,<?php echo $visit_number?>)"/></td>
                                            <td><?php echo $name?></td>
                                            <td><?php echo $class?></td>
                                            <td><?php echo $cost?></td>
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

                 </section>
<div class="row">
<div class="col-md-12">
      <!-- Widget -->
      <div class="widget boxed">
            <!-- Widget head -->
            <div class="widget-head">
              <h4 class="pull-left"><i class="icon-reorder"></i>Lab Test List</h4>
              <div class="widget-icons pull-right">
                <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                <a href="#" class="wclose"><i class="icon-remove"></i></a>
              </div>
              <div class="clearfix"></div>
            </div>             
			<hr />
        <!-- Widget content -->
            <div class="widget-content">
                <div class="padd">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="lab_table"></div>
                        </div>
                    </div>
                </div>
            </div>
   </div>
   </div>
   </div>
<script type="text/javascript">
var config_url = '<?php echo site_url();?>';
    $(document).ready(function(){
      get_lab_table(<?php echo $visit_number;?>);
    });
    function get_lab_table(visit_number){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>laboratory/test_lab/"+visit_number;
		
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
   function lab(id, visit_number){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>laboratory/test_lab/"+visit_number+"/"+id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("lab_table").innerHTML = XMLHttpRequestObject.responseText;
               //get_lab_table(visit_number);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}
  
function send_to_lab3(visit_number){
    get_test_results(85, visit_number);
}

function send_to_lab2(visit_number){
    get_test_results(75, visit_number);
}
function get_test_results(page, visit_number){

  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  var config_url = $('#config_url').val();
  if((page == 1) || (page == 65) || (page == 85)){
    
    url = config_url+"laboratory/test/"+visit_number;
  }
  
  else if ((page == 75) || (page == 100)){
    url = config_url+"laboratory/test1/"+visit_number;
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

function delete_cost(visit_charge_id, visit_number){
	
	var XMLHttpRequestObject = false;
	
	if (window.XMLHttpRequest) {
		XMLHttpRequestObject = new XMLHttpRequest();
	} 
	
	else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = config_url+"laboratory/delete_cost/"+visit_charge_id+"/"+visit_number;
	
	if(XMLHttpRequestObject) {
		var obj = document.getElementById("lab_table");
		
		XMLHttpRequestObject.open("GET", url);
		
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
				
				obj.innerHTML = XMLHttpRequestObject.responseText;
				window.location.href = host+"data/doctor/laboratory.php?visit_number="+visit_number;
			}
		}
		XMLHttpRequestObject.send(null);
	}
}
</script>
