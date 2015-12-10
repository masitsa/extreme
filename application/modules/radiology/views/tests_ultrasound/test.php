
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Ultrasound</h2>
        </header>

        <div class="panel-body">
        	
            <div class="well well-sm info">
                <h5 style="margin:0;">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <strong>First name:</strong>
                                </div>
                                <div class="col-lg-6">
                                    <?php echo $patient_surname;?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <strong>Other names:</strong>
                                </div>
                                <div class="col-lg-6">
                                    <?php echo $patient_othernames;?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <strong>Gender:</strong>
                                </div>
                                <div class="col-lg-6">
                                    <?php echo $gender;?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <strong>Age:</strong>
                                </div>
                                <div class="col-lg-6">
                                    <?php echo $age;?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <strong>Account balance:</strong>
                                </div>
                                <div class="col-lg-6">
                                    Kes <?php echo number_format($account_balance, 2);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </h5>
            </div>
            <div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#tests-pane" data-toggle="tab">Ultrasounds</a></li>
                <li ><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="tests-pane">
                	<div class="row">
						<div class="center-align">
							<?php
					        	if(($visit == 2)||(($visit == 3))||(($visit == 1))||(($visit == 4))){
									echo "<input type='button' onClick='open_window_ultrasound(8, ".$visit_id.")' value='Add Ultrasound' class='btn btn-large btn-primary'>";
								}
							
							?>
						</div>
					</div>
                	
                	<!-- ultrasound technicial should choose what test should be done to the patient -->
					 <div class="row">
					 	<div class="col-md-12">
                            <div id="ultrasound_table"></div>
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
		   //get_ultrasound_table(<?php echo $visit_id;?>);
	  });
	 function get_ultrasound_table(visit_id){
        var XMLHttpRequestObject = false;
            
        if (window.XMLHttpRequest) {
        
            XMLHttpRequestObject = new XMLHttpRequest();
        } 
            
        else if (window.ActiveXObject) {
            XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
        }
        var url = "<?php echo site_url();?>radiology/ultrasound/confirm_ultrasound_test_charge/"+visit_id;
		
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
  	function open_window_ultrasound(test, visit_id)
	{
		  var config_url = $('#config_url').val();
		  var win = window.open(config_url+"radiology/ultrasound/ultrasound_list/"+test+"/"+visit_id,"Popup","height=1200, width=800, , scrollbars=yes, "+ "directories=yes,location=yes,menubar=yes," + "resizable=no status=no,history=no top = 50 left = 100");
		  win.focus();
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
	    
	    url = config_url+"radiology/ultrasound/test/"+visit_id;
	  }
	  
	  else if ((page == 75) || (page == 100)){
	    url = config_url+"radiology/ultrasound/test1/"+visit_id;
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
			/* CL Editor */
			$(".cleditor").cleditor({
				width: "auto",
				height: "100%"
			});
	        if((page == 75) || (page == 85)){
	          window.close(this);
	        }
	        
	      }
	    }
	    XMLHttpRequestObject.send(null);
	  }
	}

	function save_ultrasound_comment(id, visit_id)
	{
		var config_url = $('#config_url').val();
		
		var res = document.getElementById("ultrasound_comment"+id).value;
		
		var data_url = config_url+"radiology/ultrasound/save_ultrasound_comment";
			
		$.ajax({
			type:'POST',
			url: data_url,
			data:{visit_charge_id: id, ultrasound_visit_format_comments: res, visit_id: visit_id},
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

	function save_result(visit_charge_id, visit_id){
		var config_url = $('#config_url').val();
		
		var result = document.getElementById("ultrasound_result"+visit_charge_id).value;
		var data_url = config_url+"radiology/ultrasound/save_result";
         	
        $.ajax({
			type:'POST',
			url: data_url,
			data:{visit_charge_id: visit_charge_id, result: result, visit_id: visit_id},
			dataType: 'text',
			success:function(data)
			{
				if(data == 'true')
				{
					alert('Comment saved successfully');
				}
				else
				{
					alert('Unable to save comment');
				}
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

		var url = config_url+"radiology/ultrasound/send_to_doctor/"+visit_id;
					
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					//window.location.href = host+"index.php/ultrasound/ultrasound_queue";
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}
	function finish_ultrasound_test(visit_id){

		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		var config_url = $('#config_url').val();
		var url = config_url+"radiology/ultrasound/finish_ultrasound_test/"+visit_id;
				
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					//window.location.href = host+"index.php/ultrasound/ultrasound_queue";
				}
			}
					
			XMLHttpRequestObject.send(null);
		}
	}

	function save_comment(visit_charge_id){
		var config_url = $('#config_url').val();
		var comment = document.getElementById("test_comment").value;
        var data_url = config_url+"radiology/ultrasound/save_comment/"+comment+"/"+visit_charge_id;
     
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
    	window.open(config_url+"radiology/ultrasound/print_test/"+visit_id+"/"+patient_id,"Popup","height=900,width=1200,,scrollbars=yes,"+
                        "directories=yes,location=yes,menubar=yes," +
                         "resizable=no status=no,history=no top = 50 left = 100");
	}

  </script>