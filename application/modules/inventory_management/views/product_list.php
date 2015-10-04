
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
                        echo form_open('inventory/make_order_search/'.$store_id, array('class'=>'form-inline'));
                        ?>
                        <div class="form-group">
                                <?php
                                $search = $this->session->userdata('make_order_search');
                                if(!empty($search))
                                {
                                ?>
                                <a href="<?php echo site_url().'laboratory/close_lab_test_search/'.$store_id;?>" class="btn btn-warning pull-right">Close Search</a>
                                <?php }?>
                                <input type="submit" class="btn btn-info pull-right" value="Search" name="search"/>
                                
                            <div class="input-group">
                                <input type="text" class="form-control col-md-6" name="search_item" placeholder="Search for a test">
                            </div>
                        </div>
                            
                            <input type="hidden" value="<?php echo $store_id?>" name="store_id">
                            
                        <?php echo form_close();?>
                    </div>
                </div>
              
                <div class="row">
                    <div class="col-md-12">
                        <table border="0" class="table table-hover table-condensed">
                            <thead> 
                                <th></th>
                                <th> Product Name </th>
                                <th> Category</th>
                            </thead>
                
                            <?php 
                            //echo "current - ".$current_item."end - ".$end_item;
                            
                            $rs9 = $query->result();
                            foreach ($rs9 as $rs10) :
                                $product_name = $rs10->product_name;
                                $category_name = $rs10->category_name;
                                $product_id = $rs10->product_id;
                            
                            
                            ?>
                            <tr>
                                <td><input type="checkbox" name="product_id" value=""  onclick="save_product(<?php echo $product_id?>,<?php echo $store_id?>)"/></td>
                                <td><?php echo $product_name?></td>
                                <td><?php echo $category_name?></td>
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
                    <h2 class="panel-title">Selected Products</h2>
                </header>
    
                <div class="panel-body">
                    <div id="current_requests"></div>
                </div>
            </section>
        </div>
    </div>

        
<script type="text/javascript">
   var config_url = '<?php echo site_url();?>';
    $(document).ready(function(){
      get_requested_items_check(<?php echo $store_id;?>);
    });

    function get_requested_items_check(store_id)
    {
         var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = config_url+"inventory/selected-items/"+store_id;
        
        if(XMLHttpRequestObject) {
                    
            XMLHttpRequestObject.open("GET", url);
                    
            XMLHttpRequestObject.onreadystatechange = function(){
                
                if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                    
                    document.getElementById("current_requests").innerHTML = XMLHttpRequestObject.responseText;
                }
            }
            
            XMLHttpRequestObject.send(null);
        }
    }
   function save_product(product_id, store_id){
    
    var XMLHttpRequestObject = false;
        
    if (window.XMLHttpRequest) {
    
        XMLHttpRequestObject = new XMLHttpRequest();
    } 
        
    else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>inventory/save-product-request/"+product_id+"/"+store_id;
    // window.alert(url);
    if(XMLHttpRequestObject) {
                
        XMLHttpRequestObject.open("GET", url);
                
        XMLHttpRequestObject.onreadystatechange = function(){
            
            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
                
               document.getElementById("current_requests").innerHTML = XMLHttpRequestObject.responseText;
               get_requested_items_check(store_id);
            }
        }
        
        XMLHttpRequestObject.send(null);
    }
}
//Add to meeting data
      function change_quantity(product_deductions_id,store_id)
    {
      
       //var product_deductions_id = $(this).attr('href');
       var quantity = $('#quantity'+product_deductions_id).val();
       var url = "<?php echo base_url();?>inventory/update-store-order/"+product_deductions_id+'/'+quantity;
        $.ajax({
           type:'POST',
           url: url,
           data:{quantity: quantity},
           cache:false,
           contentType: false,
           processData: false,
           dataType: 'json',
           success:function(data){
            
            window.alert(data.result);

            get_requested_items();
            get_requested_items_check(store_id);
           },
           error: function(xhr, status, error) {
            alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
           
           }
        });
        return false;
     }

     function finish_making_request(){

        window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
        }
        window.close();
    }

  
</script>

