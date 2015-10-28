<div class="row">
	<div class="col-md-12">
		<!-- Widget -->
		<section class="panel">


			<!-- Widget head -->
			<header class="panel-heading">
				<h4 class="pull-left"><i class="icon-reorder"></i>Consumables list</h4>
				<div class="clearfix"></div>
			</header>             

			<!-- Widget content -->
			<div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $validation_error = validation_errors();
                        
                        if(!empty($validation_error))
                        {
                            echo '<div class="alert alert-danger">'.$validation_error.'</div>';
                        }
                        echo form_open('nurse/search_consumables/'.$visit_id, array('class'=>'form-inline'));
                        ?>
                        <div class="form-group">
                                <?php
                                $search = $this->session->userdata('consumable_search');
                                if(!empty($search))
                                {
                                ?>
                                <a href="<?php echo site_url().'nurse/close_consumable_search/'.$visit_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                <?php }?>
                                <input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                
                            <div class="input-group">
                                <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a consumable">
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
                                <th>Consumable</th>
                                <th>Cost</th>
                            </thead>
                
                            <?php 
                            //echo "current - ".$current_item."end - ".$end_item;
                            
                            $rs9 = $query->result();
                            foreach ($rs9 as $rs10) :
                            
                            
                            $vaccine_id = $rs10->service_charge_id;
                            $proced = $rs10->service_charge_name;
                            $visit_type = $rs10->visit_type_id;
                            
                            $stud = $rs10->service_charge_amount;
                            
                            ?>
                            <tr>
                                <td></td>
                                
                                <td> <?php $suck=1; ?>                
                                <a href="#" onClick="consumables_anchor(<?php echo $vaccine_id?>,<?php echo $visit_id?>,<?php echo $suck; ?>)"><?php echo $proced?> </a></td>
                    
                                <td><?php echo $stud?></td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </div>
            </div>
                        
            <div class="widget-foot">
				<?php
                	if(isset($links)){echo $links;}
                ?>
            </div>
		</section>
	</div>
</div>
<script type="text/javascript">
  
  function consumables_anchor(id, v_id, suck){
   
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
	
    var url = "<?php echo site_url();?>nurse/consumables/"+id+"/"+v_id+"/"+suck;
	
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                window.close(this);
                
                window.opener.document.getElementById("consumables_to_patients").innerHTML=XMLHttpRequestObject.responseText;
            }
        }
                
        XMLHttpRequestObject.send(null);
    }
}
</script>
