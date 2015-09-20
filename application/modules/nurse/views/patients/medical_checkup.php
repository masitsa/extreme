<style type="text/css">
	textarea.form-control{min-height: 150px;}
</style>
<div class="center-align">
    <a href="<?php echo site_url().'doctor/print_checkup/'.$visit_id;?>" class="btn btn-danger btn-sm" target="_blank">Print Checkup</a>

  
</div>
<?php
$exam_categories = $this->nurse_model->medical_exam_categories();

if($exam_categories->num_rows() > 0)
{
	$exam_results = $exam_categories->result();
	
	foreach ($exam_results as $exam_res)
	{
		$mec_name = $exam_res->mec_name;
		$mec_id = $exam_res->mec_id;
		
		$illnesses = $this->nurse_model->get_illness($visit_id, $mec_id);
		
		if($illnesses->num_rows() > 0)
		{
			$illnesses_row = $illnesses->row();
			$mec_result= $illnesses_row->infor;
		}
		
		else
		{
			$mec_result= '';
		}
		
		if($mec_name=="Family History")
		{
			?>
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-featured panel-featured-info">
                        <header class="panel-heading">
                            <h2 class="panel-title">Family history</h2>
                        </header>
                        
                        <div class="panel-body">
                        	<ol id="checkup_history"></ol>
                       </div>
                   </section>
                </div>
            </div>
   			<?php 
		}
		
		else if(($mec_name=="Present Illness")||($mec_name=="Past Illness")) 
		{
			?>
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-featured panel-featured-info">
                        <header class="panel-heading">
                            <h2 class="panel-title"><?php echo $mec_name; ?></h2>
                        </header>
                        
                        <div class="panel-body">
                        	<textarea class="form-control" name="gg<?php echo $mec_id ?>"  id="gg<?php echo $mec_id ?>" placeholder="<?php echo $mec_name ?>" onKeyUp="save_illness('<?php echo $mec_id ?>','<?php echo $visit_id ?>')" ><?php echo $mec_result; ?></textarea>
                       </div>
                   </section>
                </div>
            </div>
            <?php
		}
		
		else if(($mec_name=="Physiological History")||($mec_name=="General Physical Examination")||($mec_name=="Head Physical Examination")||($mec_name=="Neck Physical Examination")||($mec_name=="Cardiovascular System Physical Examination")||($mec_name=="Respiratory System Physical Examination")||($mec_name=="Abdomen Physical Examination")||($mec_name=="Nervous System Physical Examination")) 
		{	
			?>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title"><?php echo $mec_name; ?></h2>
            </header>
            
            <div class="panel-body">
                <table class="table table-striped table-hover table-condensed">
                <?php
                $category_items = $this->nurse_model->mec_med($mec_id);
                
                if($category_items->num_rows() > 0)
                {
                    $ab=0;
                    $category_items_result = $category_items->result();
                    
                    foreach($category_items_result as $cat_res)
                    {
                        $item_format_id = $cat_res->item_format_id;
                        $ab++;
                        
                        $cat_items = $this->nurse_model->cat_items($item_format_id, $mec_id);
                        
                        if($cat_items->num_rows() > 0)
                        {
                            $cat_items_result = $cat_items->result();
                            
                            foreach($cat_items_result as $items_res)
                            {
                                $cat_item_name = $items_res->cat_item_name;
                                $cat_items_id1 = $items_res->cat_items_id;
                                ?>
                                <tr> <td><?php echo $cat_item_name; ?> </td>
                                <?php
                                
                                $items_cat = $this->nurse_model->get_cat_items($item_format_id, $mec_id);
                                
                                if($items_cat->num_rows() > 0)
                                {
                                    $items_result = $items_cat->result();
                                    
                                    foreach($items_result as $res)
                                    {
                                        $cat_item_name = $res->cat_item_name;
                                        $cat_items_id = $res->cat_items_id;
                                        $item_format_id1 = $res->item_format_id;
                                        $format_name = $res->format_name;
                                        $format_id = $res->format_id;
                                        
                                        if($cat_items_id == $cat_items_id1)
                                        {
                                            if($item_format_id1 == $item_format_id)
                                            {
                                                $results = $this->nurse_model->cat_items2($cat_items_id, $format_id,$visit_id);
                                                if($results->num_rows() > 0)
                                                {
                                                    ?><td > <input checked type="checkbox" value="" name="" onClick="del_medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $visit_id; ?>')"><?php echo '<strong>'.$format_name.'</strong>'; ?>  </td><?php 
                                                } 
                                            
                                                else 
                                                { 
                                                ?><td > <input type="checkbox" value="" name="" onClick="medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $visit_id; ?>')"><?php echo '<strong>'.$format_name.'</strong>'; ?></td><?php
                                                }
                                            }
                                        }	
                                    }
                                }
                                
                                else
                                {
                                    echo 'There are no items';
                                }
                            }
                        }
                        
                        else
                        {
                            echo 'There are no category item results';
                        }
                    }
                }
                
                else
                {
                    echo 'There are no category items';
                }
                ?>
                </table>
           </div>
       </section>
    </div>
</div>
            <?php	
  		} 
	}
}
?>
<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title"><?php echo $mec_name; ?></h2>
            </header>
            
            <div class="panel-body">
            	<textarea class="form-control" name="gg<?php echo $mec_id ?>"  id="gg<?php echo $mec_id ?>" placeholder="<?php echo $mec_name ?>" onKeyUp="save_illness('<?php echo $mec_id ?>','<?php echo $visit_id ?>')" ><?php echo $mec_result; ?></textarea>
           </div>
       </section>
    </div>
</div>

<div class="center-align" style="margin-top:10px;">
    <a href="<?php echo site_url().'doctor/print_checkup/'.$visit_id;?>" class="btn btn-danger btn-sm" target="_blank">Print Checkup</a>
</div>

<script type="text/javascript">
var host = $('#config_url').val();

function save_illness(mec_id, visit_id)
{
	var str1 = "gg";
	var mec_id;
	var n = str1.concat(mec_id); 
	
	var patient_illness = document.getElementById(n).value;
	var data_url = host+"nurse/save_illness/"+mec_id+"/"+visit_id;//alert(url);
	
	$.ajax({
			type:'POST',
			url: data_url,
			data:{illness: patient_illness},
			dataType: 'text json',
			success:function(data){

				//obj.innerHTML = XMLHttpRequestObject.responseText;
			},
			error: function(xhr, status, error) {
				//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				alert(error);
			}
		});
}

function medical_exam(cat_items_id,format_id,visit_id){
	
	var XMLHttpRequestObject = false;
		
	if (window.XMLHttpRequest) {
	
		XMLHttpRequestObject = new XMLHttpRequest();
	} 
		
	else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var url = host+"nurse/save_medical_exam/"+cat_items_id+"/"+format_id+"/"+visit_id;
		//alert(url);
	
	if(XMLHttpRequestObject) {
		
		//var obj = document.getElementById("insurance_company");
				
		XMLHttpRequestObject.open("GET", url);
				
		XMLHttpRequestObject.onreadystatechange = function(){
			
			if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

				obj.innerHTML = XMLHttpRequestObject.responseText;
			}
		}
				
		XMLHttpRequestObject.send(null);
	}
}

function del_medical_exam(cat_items_id,format_id,visit_id){
	
	var XMLHttpRequestObject = false;
		
	if (window.XMLHttpRequest) {
	
		XMLHttpRequestObject = new XMLHttpRequest();
	} 
		
	else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	var url = host+"nurse/delete_medical_exam/"+cat_items_id+"/"+format_id+"/"+visit_id;
		//alert(url);
	
	if(XMLHttpRequestObject) {
		
		//var obj = document.getElementById("insurance_company");
				
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