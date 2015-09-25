
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Laboratory test list</h2>
			</header>

			<div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $validation_error = validation_errors();
                        
                        if(!empty($validation_error))
                        {
                            echo '<div class="alert alert-danger">'.$validation_error.'</div>';
                        }
                        echo form_open('laboratory/search_laboratory_tests/'.$visit_id, array('class'=>'form-inline'));
                        ?>
                        <div class="form-group">
                                <?php
                                $search = $this->session->userdata('lab_test_search');
                                if(!empty($search))
                                {
                                ?>
                                <a href="<?php echo site_url().'laboratory/close_lab_test_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                <?php }?>
                                <input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                
                            <div class="input-group">
                                <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a test">
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
                                <td><input type="checkbox" name="laboratory_id" value=""  onclick="lab(<?php echo $id?>,<?php echo $visit_id?>)"/></td>
                                <td><?php echo $name?></td>
                                <td><?php echo $class?></td>
                                <td><?php echo $cost?></td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </div>
				<?php
                if(isset($links)){echo $links;}
                ?>
            
            </div>

     </section>
                 

    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-info">
                <header class="panel-heading">
                    <h2 class="panel-title">Selected tests</h2>
                </header>
    
                <div class="panel-body">
                    <div id="lab_table"></div>
                </div>
            </section>
        </div>
    </div>
        
<script type="text/javascript">
var config_url = '<?php echo site_url();?>';
    $(document).ready(function(){
      get_lab_table(<?php echo $visit_id;?>);
    });
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
  
function send_to_lab3(visit_id){
    get_test_results(85, visit_id);
}

function send_to_lab2(visit_id){
    get_test_results(75, visit_id);
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
    
    url = config_url+"laboratory/test/"+visit_id;
  }
  
  else if ((page == 75) || (page == 100)){
    url = config_url+"laboratory/test1/"+visit_id;
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
				window.location.href = host+"data/doctor/laboratory.php?visit_id="+visit_id;
			}
		}
		XMLHttpRequestObject.send(null);
	}
}
</script>
