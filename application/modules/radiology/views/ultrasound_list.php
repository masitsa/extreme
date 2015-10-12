
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
            	<h2 class="panel-title">Ultrasound list</h2>
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
                        echo form_open('radiology/ultrasound/search_ultrasounds/'.$visit_id, array('class'=>'form-inline'));
                        ?>
                        <div class="form-group">
                                <?php
                                $search = $this->session->userdata('ultrasound_search');
                                if(!empty($search))
                                {
                                ?>
                                <a href="<?php echo site_url().'radiology/ultrasound/close_ultrasound_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                <?php }?>
                                <input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                
                            <div class="input-group">
                                <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for an ultrasound">
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
                                <th> Cost</th>
                            </thead>
                
                            <?php 
                            //echo "current - ".$current_item."end - ".$end_item;
                            
                            $rs9 = $query->result();
                            foreach ($rs9 as $rs10) :
                                $name = $rs10->service_charge_name;
                                $cost = $rs10->service_charge_amount;
                                $id = $rs10->service_charge_id;
                            
                            
                            ?>
                            <tr>
                                <td><input type="checkbox" name="ultrasound_id" value=""  onclick="ultrasound(<?php echo $id?>,<?php echo $visit_id?>)"/></td>
                                <td><?php echo $name?></td>
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
                    <h2 class="panel-title">Selected ultrasounds</h2>
                </header>
    
                <div class="panel-body">
                    <div id="ultrasound_table"></div>
                </div>
            </section>
        </div>
    </div>

<script type="text/javascript">
var config_url = '<?php echo site_url();?>';
    $(document).ready(function(){
      get_ultrasound_table(<?php echo $visit_id;?>);
    });
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

function send_to_ultrasound3(visit_id)
{
    get_test_results(85, visit_id);
}

function send_to_ultrasound2(visit_id){
	var ultrasound = '<?php echo $ultrasound;?>';
	
	if(ultrasound == '8')
	{
		get_test_results(85, visit_id);
	}
	
	else
	{
    	get_test_results(75, visit_id);
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
  if((page == 1) || (page == 65)){
    
    url = config_url+"radiology/ultrasound/test/"+visit_id;
  }
  else if((page == 85)){
    
    url = config_url+"radiology/ultrasound/test1/"+visit_id;
  }
  
  else if ((page == 75)){
    url = config_url+"radiology/ultrasound/test2/"+visit_id;
  }
  
  else if ((page == 100)){
    url = config_url+"radiology/ultrasound/test2/"+visit_id;
  }
  if(XMLHttpRequestObject) {
    if((page == 75)){
      var obj = window.opener.document.getElementById("ultrasound_results");
    }
    else if(page == 85){
      var obj = window.opener.document.getElementById("test_results");
	}
    else{
      var obj = document.getElementById("ultrasound_results");
    }
    XMLHttpRequestObject.open("GET", url);
    
    XMLHttpRequestObject.onreadystatechange = function(){
    
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
	  {
        obj.innerHTML = XMLHttpRequestObject.responseText;
        if(page == 75){
          	window.close(this);
        }
        if(page == 85){
			/* CL Editor */
			$(".cleditor", opener.document).cleditor({
				width: "auto",
				height: "100%"
			});
          	window.close(this);
        }
        
      }
    }
    XMLHttpRequestObject.send(null);
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
					window.location.href = host+"data/doctor/ultrasound.php?visit_id="+visit_id;
				}
			}
			XMLHttpRequestObject.send(null);
		}
	}
}

function reload_opener_confirmation(visit_id){
	var XMLHttpRequestObject = false;
		
	if (window.XMLHttpRequest) {
	
		XMLHttpRequestObject = new XMLHttpRequest();
	} 
		
	else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "<?php echo site_url();?>radiology/ultrasound/confirm_ultrasound_charge/"+visit_id;
	
	if(XMLHttpRequestObject) {
				
		XMLHttpRequestObject.open("GET", url);
				
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
				var obj = window.opener.document.getElementById("ultrasound_table");
				obj.innerHTML = XMLHttpRequestObject.responseText;
			}
		}
		
		XMLHttpRequestObject.send(null);
	}
}
</script>
