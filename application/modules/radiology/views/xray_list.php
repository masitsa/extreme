
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
            	<h2 class="panel-title">X Ray list</h2>
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
                        echo form_open('radiology/xray/search_xrays/'.$visit_id, array('class'=>'form-inline'));
                        ?>
                        <div class="form-group">
                                <?php
                                $search = $this->session->userdata('xray_search');
                                if(!empty($search))
                                {
                                ?>
                                <a href="<?php echo site_url().'radiology/xray/close_xray_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                <?php }?>
                                <input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                
                            <div class="input-group">
                                <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for an xray">
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
                                <td><input type="checkbox" name="xray_id" value=""  onclick="xray(<?php echo $id?>,<?php echo $visit_id?>)"/></td>
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
                    <h2 class="panel-title">Selected xrays</h2>
                </header>
    
                <div class="panel-body">
                    <div id="xray_table"></div>
                </div>
            </section>
        </div>
    </div>
        
<script type="text/javascript">
var config_url = '<?php echo site_url();?>';
    $(document).ready(function(){
      get_xray_table(<?php echo $visit_id;?>);
    });
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

function send_to_xray3(visit_id)
{
    get_test_results(85, visit_id);
}

function send_to_xray2(visit_id){
	var xray = '<?php echo $xray;?>';
	
	if(xray == '8')
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
    
    url = config_url+"radiology/xray/test/"+visit_id;
  }
  else if((page == 85)){
    
    url = config_url+"radiology/xray/test1/"+visit_id;
  }
  
  else if ((page == 75)){
    url = config_url+"radiology/xray/test2/"+visit_id;
  }
  
  else if ((page == 100)){
    url = config_url+"radiology/xray/test1/"+visit_id;
  }
  if(XMLHttpRequestObject) {
    if((page == 75)){
      var obj = window.opener.document.getElementById("xray_results");
    }
    else if(page == 85){
      var obj = window.opener.document.getElementById("test_results");
	}
    else{
      var obj = document.getElementById("xray_results");
    }
    XMLHttpRequestObject.open("GET", url);
    
    XMLHttpRequestObject.onreadystatechange = function(){
    
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
  //window.alert(XMLHttpRequestObject.responseText);
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
					window.location.href = host+"data/doctor/xray.php?visit_id="+visit_id;
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
	var url = "<?php echo site_url();?>radiology/xray/confirm_xray_charge/"+visit_id;
	
	if(XMLHttpRequestObject) {
				
		XMLHttpRequestObject.open("GET", url);
				
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) 
			{
				var obj = window.opener.document.getElementById("xray_table");
				obj.innerHTML = XMLHttpRequestObject.responseText;
			}
		}
		
		XMLHttpRequestObject.send(null);
	}
}
</script>
