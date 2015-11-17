<!-- end #header -->
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Prescribe drugs</h2>
    </header>

    <div class="panel-body">
		
		<div class="well well-sm info">
			<h5 style="margin:0;">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>First name:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $patient_surname;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-5">
								<strong>Other names:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $patient_othernames;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>Gender:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $gender;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="row">
							<div class="col-lg-5">
								<strong>Age:</strong>
							</div>
							<div class="col-lg-5">
								<?php echo $age;?>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<div class="col-lg-5">
								<strong>Account balance:</strong>
							</div>
							<div class="col-lg-5">
								Kes <?php echo number_format($account_balance, 2);?>
							</div>
						</div>
					</div>
				</div>
			</h5>
		</div>
<div class="row">
  <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Prescription</h2>
            </header>
            <div class="panel-body">
                <div class="col-lg-8 col-md-8 col-sm-8">
                  <div class="form-group">
                    <select id='drug_id' name='drug_id' class='form-control custom-select '>
                      <option value=''>None - Please Select an drug</option>
                      <?php echo $drugs;?>
                    </select>
                  </div>
                
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <button type='submit' class="btn btn-sm btn-success"  onclick="get_drug_to_prescribe(<?php echo $visit_id;?>);"> Prescribe drug</button>
                  </div>
                </div>
                 <!-- visit Procedures from java script -->
                
                <!-- end of visit procedures -->
            </div>
             <div id="prescription_view"></div>
             <div id="visit_prescription"></div>
              
			
			<div class="center-align">
			<?php echo '<a href="'.site_url().'pharmacy/send_to_accounts/'.$visit_id.'" onclick="return confirm(\'Send to accounts?\');" class="btn btn-sm btn-success">Send to Accounts</a>';?>
			<?php echo '<a href="'.site_url().'pharmacy/print-prescription/'.$visit_id.'" class="btn btn-sm btn-warning print_prescription" target="_blank"><i class="fa fa-print"></i> Print Prescription</a>';?>

		 	</div>
         </section>
    </div>
</div>

  
 </div>
 </section>

  
<script type="text/javascript">
	$(document).ready(function(){
	  display_inpatient_prescription(<?php echo $visit_id;?>,1);
	});

	$(function() {
	    $("#drug_id").customselect();

	  });

	function myPopup2(visit_id,module) {
		var config_url = $('#config_url').val();
		var win_drugs = window.open(config_url+"pharmacy/drugs/"+visit_id+"/"+module,"Popup3","height=1200,width=1000,,scrollbars=yes,"+ 
							"directories=yes,location=yes,menubar=yes," + 
							 "resizable=no status=no,history=no top = 50 left = 100"); 
  		win_drugs.focus();
	}
	
	function myPopup2_soap(visit_id) {
		var config_url = $('#config_url').val();
		var win_drugs = window.open(config_url+"pharmacy/drugs/"+visit_id,"Popup2","height=1200,width=1000,,scrollbars=yes,"+ 
							"directories=yes,location=yes,menubar=yes," + 
							 "resizable=no status=no,history=no top = 50 left = 100"); 
  		win_drugs.focus();
	}
	
	function send_to_pharmacy2(visit_id)
	{
		var config_url = $('#config_url').val();
		var url = config_url+"pharmacy/display_prescription/"+visit_id;
	
		$.get(url, function( data ) {
			var obj = window.opener.document.getElementById("prescription");
			obj.innerHTML = data;
			window.close(this);
		});
	}
</script>



<script type="text/javascript">

	function get_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = '';
		button.style.display = 'none';
		button2.style.display = '';
	}
	function close_visit_trail(visit_id){

		var myTarget2 = document.getElementById("visit_trail"+visit_id);
		var button = document.getElementById("open_visit"+visit_id);
		var button2 = document.getElementById("close_visit"+visit_id);

		myTarget2.style.display = 'none';
		button.style.display = '';
		button2.style.display = 'none';
	}

	function update_prescription_values(visit_id,visit_charge_id,prescription_id,module)
    {
      
       //var product_deductions_id = $(this).attr('href');
       var quantity = $('#quantity'+prescription_id).val();
       var x = $('#x'+prescription_id).val();
       var duration = $('#duration'+prescription_id).val();
       var consumption = $('#consumption'+prescription_id).val();


       var url = "<?php echo base_url();?>pharmacy/update_prescription/"+visit_id+'/'+visit_charge_id+'/'+prescription_id+'/'+module;
  
        //window.alert(data_url);
		  $.ajax({
		  type:'POST',
		  url: data_url,
		  data:{quantity: quantity, x: x, duration: duration,consumption: consumption},
		  dataType: 'text',
           success:function(data){
            
            window.alert(data.result);
            if(module == 1){
				window.location.href = "<?php echo base_url();?>pharmacy/prescription1/"+visit_id+"/1'";
			
			}else{
				window.location.href = "<?php echo base_url();?>pharmacy/prescription1/"+visit_id+"";
			}
           },
           error: function(xhr, status, error) {
            alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
           
           }
        });
        return false;
     }
  </script>

<script type="text/javascript">
	function get_drug_to_prescribe(visit_id)
	{
	  var XMLHttpRequestObject = false;
	        
	    if (window.XMLHttpRequest) {
	    
	        XMLHttpRequestObject = new XMLHttpRequest();
	    } 
	        
	    else if (window.ActiveXObject) {
	        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    var drug_id = document.getElementById("drug_id").value;

	    var url = "<?php echo site_url();?>pharmacy/inpatient_prescription/"+visit_id+"/"+drug_id+"/1";

	     if(XMLHttpRequestObject) {
	                
	        XMLHttpRequestObject.open("GET", url);
	                
	        XMLHttpRequestObject.onreadystatechange = function(){
	            
	            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
	              var prescription_view = document.getElementById("prescription_view");
	             
	              document.getElementById("prescription_view").innerHTML=XMLHttpRequestObject.responseText;
	               prescription_view.style.display = 'block';
	            }
	        }
	                
	        XMLHttpRequestObject.send(null);
	    }

	}
	function pass_prescription()
	{
	  var quantity = document.getElementById("quantity_value").value;
	  var x = document.getElementById("x_value").value;
	  var duration = document.getElementById("duration_value").value;
	  var consumption = document.getElementById("consumption_value").value;
	  var number_of_days = document.getElementById("number_of_days_value").value;
	  var service_charge_id = document.getElementById("drug_id").value;
	  var visit_id = document.getElementById("visit_id").value;
	  var module = document.getElementById("module").value;
	  var passed_value = document.getElementById("passed_value").value;

	  var url = "<?php echo base_url();?>pharmacy/prescribe_prescription";

	  $.ajax({
	  type:'POST',
	  url: url,
	  data:{quantity: quantity, x: x, duration: duration,consumption: consumption, service_charge_id : service_charge_id, visit_id : visit_id, number_of_days: number_of_days,module: module,passed_value:passed_value},
	  dataType: 'text',
	  success:function(data){

	  var prescription_view = document.getElementById("prescription_view");
	  prescription_view.style.display = 'none';
	  display_inpatient_prescription(visit_id,1);
	  	
	  },
	  error: function(xhr, status, error) {
	  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

	  }
	  });
	  
	  return false;
	}
	function display_inpatient_prescription(visit_id,module){

	    var XMLHttpRequestObject = false;
	        
	    if (window.XMLHttpRequest) {
	    
	        XMLHttpRequestObject = new XMLHttpRequest();
	    } 
	        
	    else if (window.ActiveXObject) {
	        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    
	    var config_url = document.getElementById("config_url").value;
	    var url = config_url+"pharmacy/display_inpatient_prescription/"+visit_id+"/"+module;
	    
	    if(XMLHttpRequestObject) {
	                
	        XMLHttpRequestObject.open("GET", url);
	                
	        XMLHttpRequestObject.onreadystatechange = function(){
	            
	            if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {

	                document.getElementById("visit_prescription").innerHTML=XMLHttpRequestObject.responseText;
	            }
	        }
	                
	        XMLHttpRequestObject.send(null);
	    }
	}
	function button_update_prescription(visit_id,visit_charge_id,prescription_id,module)
	{
	  var quantity = $('#quantity'+prescription_id).val();
	  var x = $('#x'+prescription_id).val();
	  var duration = $('#duration'+prescription_id).val();
	  var consumption = $('#consumption'+prescription_id).val();
	
	  var url = "<?php echo site_url();?>pharmacy/update_inpatient_prescription/"+visit_id+"/"+visit_charge_id+"/"+prescription_id+"/"+module;


	  $.ajax({
	  type:'POST',
	  url: url,
	  data:{quantity: quantity, x: x, duration: duration,consumption: consumption},
	  dataType: 'text',
	  success:function(data){

	  
	  },
	  error: function(xhr, status, error) {
	  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

	  }
	  });
	  display_inpatient_prescription(visit_id,1);
	  return false;
	}

	function dispense_prescription(visit_id,visit_charge_id,prescription_id,module)
	{

	  var quantity =  $('#quantity'+prescription_id).val();
	  var x = $('#x'+prescription_id).val();
	  var duration = $('#duration'+prescription_id).val();
	  var consumption = $('#consumption'+prescription_id).val();
	  var charge = $('#charge'+prescription_id).val();
	  var units_given = $('#units_given'+prescription_id).val();
	  
	  var url = "<?php echo site_url();?>pharmacy/dispense_inpatient_prescription/"+visit_id+"/"+visit_charge_id+"/"+prescription_id+"/"+module+"/"+quantity;
	 
	  $.ajax({
	  type:'POST',
	  url: url,
	  data:{quantity: quantity, x: x, duration: duration,consumption: consumption,charge: charge, units_given: units_given},
	  dataType: 'json',
	  success:function(data){
	    window.alert(data.result);
	  },
	  error: function(xhr, status, error) {
	  alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);

	  }
	  });
	  display_inpatient_prescription(visit_id,1);
	  return false;
	}

function delete_prescription(prescription_id, visit_id,visit_charge_id,module)
{
  var res = confirm('Are you sure you want to delete this prescription ?');
  
  if(res)
  {
    var XMLHttpRequestObject = false;
    
    if (window.XMLHttpRequest) {
      XMLHttpRequestObject = new XMLHttpRequest();
    } 
    
    else if (window.ActiveXObject) {
      XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var url = "<?php echo site_url();?>pharmacy/delete_inpatient_prescription/"+prescription_id+"/"+visit_id+"/"+visit_charge_id+"/"+module;
    // alert(url);
    if(XMLHttpRequestObject) {
      
      XMLHttpRequestObject.open("GET", url);
      
      XMLHttpRequestObject.onreadystatechange = function(){
        
        if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
          
           display_inpatient_prescription(visit_id,1);
        }
      }
      XMLHttpRequestObject.send(null);
    }
  }
}

$(document).on("click","a.print_prescription",function(e)
{
	e.preventDefault();
	
	//get checkbox values
	var val = [];
	$(':checkbox:checked').each(function(i){
	  	val[i] = $(this).val();
	});
	var request = '<?php echo site_url();?>pharmacy/print_selected_drugs/<?php echo $visit_id;?>';
	$.ajax({
		type:'POST',
		url: request,
		data:{prescription_id: val},
		dataType: 'text',
		success:function(data)
		{
			var win = window.open("<?php echo site_url();?>pharmacy/print-prescription/<?php echo $visit_id;?>", '_blank');
			if(win){
				//Browser has allowed it to be opened
				win.focus();
			}else{
				//Broswer has blocked it
				alert('Please allow popups for this site');
			}
		},
		error: function(xhr, status, error) 
		{
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	
	return false;
});
</script>                        