
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Tests</h2>
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
                <li class="active"><a href="#tests-pane" data-toggle="tab">Tests</a></li>
                <li ><a href="#visit_trail" data-toggle="tab">Visit Trail</a></li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="tests-pane">
                	  <div class="row">
						  <div class="col-md-12">
						        <section class="panel panel-featured panel-featured-info">
						            <header class="panel-heading">
						                <h2 class="panel-title">Lab Tests</h2>
						            </header>
						            <div class="panel-body">
						                <div class="col-lg-8 col-md-8 col-sm-8">
						                  <div class="form-group">
						                    <select id='lab_test_id' name='lab_test_id' class='form-control custom-select '>
						                      <option value=''>None - Please Select a Lab test</option>
						                      <?php echo $lab_tests;?>
						                    </select>
						                  </div>
						                
						                </div>
						                <div class="col-lg-4 col-md-4 col-sm-4">
						                  <div class="form-group">
						                      <button type='submit' class="btn btn-sm btn-success"  onclick="parse_lab_test(<?php echo $visit_id;?>);"> Add Lab Test</button>
						                  </div>
						                </div>
						                 <!-- visit Procedures from java script -->
						                
						                <!-- end of visit procedures -->
						            </div>
						            <div id="lab_table"></div>
						         </section>
						    </div>
						</div>
                	
                	<!-- laboratory technicial should choose what test should be done to the patient -->
					<!--  <div class="row">
					 	<div class="col-md-12">
                            <div id="lab_table"></div>
                        </div>
					 </div> -->
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
  	$(function() {
	    $("#lab_test_id").customselect();
	});
	  $(document).ready(function(){
	       get_test_results(100, <?php echo $visit_id?>);
		   get_lab_table(<?php echo $visit_id;?>);

			$(function() {
				$("#lab_test_id").customselect();
			});
	  });
	   function parse_lab_test(visit_id)
	  {
	    var lab_test_id = document.getElementById("lab_test_id").value;
	     lab(lab_test_id, visit_id);
	    
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
	               get_lab_table(visit_id);
	               get_test_results(100, visit_id)
	            }
	        }
	        
	        XMLHttpRequestObject.send(null);
	    }
	}
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
	//alert(url);
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

	function save_result_format(visit_lab_test_id, lab_test_format_id, visit_id)
	{
		var config_url = $('#config_url').val();
		
		var res = document.getElementById("laboratory_result2"+lab_test_format_id).value;
		var data_url = config_url+"laboratory/save_result_lab";
         	
        $.ajax({
			type:'POST',
			url: data_url,
			data:{res: res, format: lab_test_format_id, visit_id: visit_id, visit_lab_test_id: visit_lab_test_id},
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
	function update_lab_test_charge(visit_lab_test_id,visit_id){
		var config_url = $('#config_url').val();
		var lab_test_amount = document.getElementById("lab_test_price"+visit_lab_test_id).value;
        var data_url = "<?php echo site_url();?>laboratory/update_lab_charge_amount/"+visit_lab_test_id+"/"+visit_id;

		  $.ajax({
		  type:'POST',
		  url: data_url,
		  data:{amount: lab_test_amount},
		  dataType: 'text',
		  success:function(data){
		    window.alert("You have successfully updated the lab test amount");
		  //obj.innerHTML = XMLHttpRequestObject.responseText;
		  },
		  error: function(xhr, status, error) {
		  //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		  alert(error);
		  }

		  });
		   get_lab_table(visit_id);
		
	}

	function print_previous_test(visit_id, patient_id){
		var config_url = $('#config_url').val();
    	window.open(config_url+"laboratory/print_test/"+visit_id+"/"+patient_id,"Popup","height=900,width=1200,,scrollbars=yes,"+
                        "directories=yes,location=yes,menubar=yes," +
                         "resizable=no status=no,history=no top = 50 left = 100");
	}

  </script>