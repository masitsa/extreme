
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Tests for <?php echo $patient;?></h2>
        </header>

        <div class="panel-body">
            <div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#tests-pane" data-toggle="tab">Tests</a></li>
                <li ><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="tests-pane">
                	<div class="row">
						<div class="center-align">
							<?php
					        	if(($visit == 2)||(($visit == 3))||(($visit == 1))||(($visit == 4))){
									echo "<input type='button' onClick='open_window_lab(8, ".$visit_id.")' value='Add Test' class='btn btn-large btn-primary'>";
								}
							
							?>
						</div>
					</div>
                	
                	<!-- laboratory technicial should choose what test should be done to the patient -->
					 <div class="row">
					 	<div class="col-md-12">
                            <div id="lab_table"></div>
                        </div>
					 </div>
					<!-- bill for thr test asap -->

                  	<div id="test_results"></div>
                    
                </div>
                 <div class="tab-pane" id="visit_trail">
                  <?php echo $this->load->view("nurse/patients/visit_trail", '', TRUE);?>
                </div>
              </div>
           </div>
			
		</div>
	</div>
</section>
   

  <script type="text/javascript">
	  $(document).ready(function(){
	       get_test_results(100, <?php echo $visit_id?>);
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
        var url = "<?php echo site_url();?>laboratory/confirm_lab_test_charge/"+visit_id;
		
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
  	function open_window_lab(test, visit_id){
	  var config_url = $('#config_url').val();
	  window.open(config_url+"laboratory/laboratory_list/"+test+"/"+visit_id,"Popup","height=1200, width=800, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
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
	// alert(url);
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

	function save_result_format(id, format, visit_id){
		var config_url = $('#config_url').val();
		
		var res = document.getElementById("laboratory_result2"+format).value;
		var data_url = config_url+"laboratory/save_result_lab";
         	
        $.ajax({
			type:'POST',
			url: data_url,
			data:{id: id, res: res, format: format, visit_id: visit_id},
			dataType: 'text',
			success:function(data){
				//$("#result_space"+format).val(data);
			},
			error: function(xhr, status, error) {
				//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				alert(error);
			}

        });
	}

	function save_lab_comment(id, visit_id)
	{
		var config_url = $('#config_url').val();
		
		var res = document.getElementById("laboratory_comment"+id).value;
		
		var data_url = config_url+"laboratory/save_lab_comment";
			
		$.ajax({
			type:'POST',
			url: data_url,
			data:{visit_charge_id: id, lab_visit_format_comments: res, visit_id: visit_id},
			dataType: 'text',
			success:function(data){
				//$("#result_space"+format).val(data);
			},
			error: function(xhr, status, error) {
				//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				alert(error);
			}
	
		});
	}

	function save_result(id, visit_id){
		
		var config_url = $('#config_url').val();
		var res = document.getElementById("laboratory_result"+id).value;
        var data_url = config_url+"laboratory/save_result/"+id+"/"+res+"/"+visit_id;
   	 // window.alert(data_url);
         var result_space = $('#result_space'+id).val();//document.getElementById("vital"+vital_id).value;
         	
        $.ajax({
			type:'POST',
			url: data_url,
			data:{result: result_space},
			dataType: 'text',
			success:function(data){
				//$("#result_space"+id).val(data);
			},
			error: function(xhr, status, error) {
				//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				alert(error);
			}

        });
	
		
	}
	function send_to_doc(visit_id){
	

		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var config_url = $('#config_url').val();

		var url = config_url+"laboratory/send_to_doctor/"+visit_id;
					
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					//window.location.href = host+"index.php/laboratory/lab_queue";
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}
	function finish_lab_test(visit_id){

		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var config_url = $('#config_url').val();
		var url = config_url+"laboratory/finish_lab_test/"+visit_id;
				
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					//window.location.href = host+"index.php/laboratory/lab_queue";
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}

	function save_comment(visit_charge_id){
		var config_url = $('#config_url').val();
		var comment = document.getElementById("test_comment").value;
        var data_url = config_url+"laboratory/save_comment/"+comment+"/"+visit_charge_id;
     
        // var comment_tab = $('#comment').val();//document.getElementById("vital"+vital_id).value;
         	
        $.ajax({
        type:'POST',
        url: data_url,
       // data:{comment: comment_tab},
        dataType: 'text',
        success:function(data){
        //obj.innerHTML = XMLHttpRequestObject.responseText;
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
       // alert(error);
        }

        });
	

		
	}
	function print_previous_test(visit_id, patient_id){
		var config_url = $('#config_url').val();
    	window.open(config_url+"laboratory/print_test/"+visit_id+"/"+patient_id,"Popup","height=900,width=1200,,scrollbars=yes,"+
                        "directories=yes,location=yes,menubar=yes," +
                         "resizable=no status=no,history=no top = 50 left = 100");
	}

  </script>