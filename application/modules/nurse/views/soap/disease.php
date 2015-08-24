<?php 
	/*$diseases = '';
	$total_diseases = $all_diseases->num_rows();
	
	if($total_diseases > 0)
	{
		$count = 0;
		foreach($all_diseases->result() as $res)
		{
			$name = $res->diseases_name;
			$code = $res->diseases_code;
			$id = $res->diseases_id;
			$count++;
			
			if($total_diseases == $count)
			{
				$diseases .= $code.' '.$name;
			}
			
			else
			{
				$diseases .= $code.' '.$name.',';
			}
		}
	}*/
?>
      <div class="row">
        <div class="col-md-12">
              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Auto Add Diseases</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                      </div>
                      <div class="clearfix"></div>
                    </div>             

                <!-- Widget content -->
                    <div class="widget-content">
                        <div class="padd">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"/>
                                        <link rel="stylesheet" href="<?php echo base_url();?>assets/autocomplete/style.css">
                                        <input id="myAutocomplete" type="text" class="form-control" />
										<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script> 
                                        <script src="<?php echo base_url();?>js/jquery.autocomplete.multiselect.js"></script>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        
      <div class="row">
        <div class="col-md-12">
              <!-- Widget -->
              <div class="widget boxed">
                    <!-- Widget head -->
                    <div class="widget-head">
                      <h4 class="pull-left"><i class="icon-reorder"></i>Diseases List</h4>
                      <div class="widget-icons pull-right">
                        <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                        
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
									echo form_open('nurse/search_diseases/'.$visit_id, array('class'=>'form-inline'));
									?>
                                    <div class="form-group">
                                            <?php
                  											$search = $this->session->userdata('disease_search');
                                                              if(!empty($search))
                  											{
                  											?>
                                            <a href="<?php echo site_url().'/nurse/close_disease_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                            <?php }?>
                                        	<input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                            
                                        <div class="input-group">
                                            <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a disease">
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
                                            <th> </th>
                                            <th>Code </th>
                                            <th>Name </th>
                                        </thead>
                            
                                        <?php 
                                        //echo "current - ".$current_item."end - ".$end_item;
                                        
                                        $rs9 = $query->result();
                                        foreach ($rs9 as $rs10) :
                                        
                                        $name = $rs10->diseases_name;
										$code = $rs10->diseases_code;
										$id = $rs10->diseases_id;
									
									?>
							        <tr>
							        	<td><input type="checkbox" name="disease_id" value="" onClick="save_disease(<?php echo $id?>, <?php echo $visit_id?>)"/></td>
							        	<td><?php echo $code?></td>
							        	<td><?php echo $name?></td>
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
        <div class="row">
        	<div id="disease_list"></div>
        </div>
<script type="text/javascript">

$(document).ready(function(){
      get_disease(<?php echo $visit_id;?>);
  });

function save_disease(val, visit_id){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  } 
  var config_url = $('#config_url').val();
  var url = config_url+"/nurse/save_diagnosis/"+val+"/"+visit_id;
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
  var url = config_url+"/nurse/get_diagnosis/"+visit_id;
  
  var obj = document.getElementById("disease_list");
      
  if(XMLHttpRequestObject) {
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
		$("#visit_diagnosis_original",opener.document).html(XMLHttpRequestObject.responseText);
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
	var url = config_url+"/nurse/delete_diagnosis/"+diagnosis_id;
	
	$.get(url, function( data ) {
		get_disease(visit_id);
	});
	
	return false;
});

function closeit(page, visit_id){
  
  var XMLHttpRequestObject = false;
    
  if (window.XMLHttpRequest) {
  
    XMLHttpRequestObject = new XMLHttpRequest();
  } 
    
  else if (window.ActiveXObject) {
    XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
   var config_url = $('#config_url').val();
  var url = config_url+"/nurse/diagnose/"+visit_id;
  if(XMLHttpRequestObject) {
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        
        if(page == 1){
          window.opener.document.getElementById("diagnosis").innerHTML = XMLHttpRequestObject.responseText;
          window.close(this);
        }
        
        else{
          document.getElementById("diagnosis").innerHTML = XMLHttpRequestObject.responseText;
        }
      }
    }
    
    XMLHttpRequestObject.send(null);
  }
}

/*$(function(){
	var diseases = '<?php echo $diseases;?>';
	var result = diseases.split(',').map(function(item) {
			return item;
		});
	var total_diseases = parseInt('<?php echo $total_diseases;?>');
	var availableTags = 
	    result
	;
	$('#myAutocomplete').autocomplete({
        source: availableTags,
        multiselect: true
    });
})*/

</script>
