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
            <section class="panel panel-featured panel-featured-info">
                <header class="panel-heading">
                    <h2 class="panel-title">ICD-10</h2>
                </header>
    
                <div class="panel-body">
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
                            <a href="<?php echo site_url().'nurse/close_disease_search/'.$visit_id;?>" class="btn btn-warning pull-right btn-sm">Close Search</a>
                            <?php }?>
                            <input type="submit" class="btn btn-info pull-right btn-sm" value="Search" name="search"/>
                            
                        <div class="input-group">
                            <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a disease">
                        </div>
                    </div>
                        
                        <input type="hidden" value="<?php echo $visit_id?>" name="visit_id">
                        
                    <?php echo form_close();?>
                    
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
                    
                    <?php
                    if(isset($links)){echo $links;}
                    ?>
                    </div>
                 </section>
                 
        <div class="row">
        	<div class="col-md-12">
        		<div id="disease_list"></div>
            </div>
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
  var url = config_url+"nurse/save_diagnosis/"+val+"/"+visit_id;
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
  var url = config_url+"nurse/get_diagnosis/"+visit_id;
  
  var obj = document.getElementById("disease_list");
      
  if(XMLHttpRequestObject) {
        
    XMLHttpRequestObject.open("GET", url);
        
    XMLHttpRequestObject.onreadystatechange = function(){
      
      if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
        obj.innerHTML = XMLHttpRequestObject.responseText;
		$("#visit_diagnosis_original",opener.document).html(XMLHttpRequestObject.responseText);
		/*var obj2 = window.opener.document.getElementById("visit_diagnosis_original");
        obj.innerHTML = XMLHttpRequestObject.responseText;*/
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
	var url = config_url+"nurse/delete_diagnosis/"+diagnosis_id;
	
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
  var url = config_url+"nurse/diagnose/"+visit_id;
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
