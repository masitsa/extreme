<?php
//repopulate data if validation errors occur
$validation_errors = validation_errors();
				
if(!empty($validation_errors))
{
	$old_personnel_id = set_value('personnel_id');
	$start_date = set_value('start_date');
	$end_date = set_value('end_date');
	$old_leave_type_id = set_value('leave_type_id');
}

else
{
	$old_personnel_id = '';
	$start_date = '';
	$end_date = '';
	$old_leave_type_id = '';
}
?>      	
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-12">
		                    <h2 class="panel-title"><?php echo $title;?></h2>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                    
                    <div class="row">
                    	<div class="col-md-12">
                        	<?php
                            	$success = $this->session->userdata('success_message');
                            	$error = $this->session->userdata('error_message');
								
								if(!empty($success))
								{
									echo '
										<div class="alert alert-success">'.$success.'</div>
									';
									
									$this->session->unset_userdata('success_message');
								}
								
								if(!empty($error))
								{
									echo '
										<div class="alert alert-danger">'.$error.'</div>
									';
									
									$this->session->unset_userdata('error_message');
								}
								
							?>
                            <?php
							if(!empty($validation_errors))
							{
								echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
							}
							
							?>
                        	<?php echo form_open("human-resource/add-calender-leave");?>
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control" name="personnel_id">
                                        <option value="">--Select personnel--</option>
                                        <?php echo $personnel;?>
                                        <?php
                                            if($personnel->num_rows() > 0)
                                            {
                                                foreach($personnel->result() as $res)
                                                {
                                                    $personnel_id = $res->personnel_id;
                                                    $personnel_fname = $res->personnel_fname;
                                                    $personnel_onames = $res->personnel_onames;
                                                    
                                                    if($old_personnel_id == $personnel_id)
                                                    {
                                                        echo "<option value='".$personnel_id."' selected>".$personnel_fname." ".$personnel_onames."</option>";
                                                    }
                                                    
                                                    else
                                                    {
                                                        echo "<option value='".$personnel_id."'>".$personnel_fname." ".$personnel_onames."</option>";
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start date" value="<?php echo $start_date;?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End date" value="<?php echo $end_date;?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="leave_type_id">
                                        <option value="">--Select leave type--</option>
                                        <?php
                                            if($leave_types->num_rows() > 0)
                                            {
                                                foreach($leave_types->result() as $res)
                                                {
                                                    $leave_type_id = $res->leave_type_id;
                                                    $leave_type_name = $res->leave_type_name;
                                                    
                                                    if($old_leave_type_id == $leave_type_id)
                                                    {
                                                        echo '<option value="'.$leave_type_id.'" selected>'.$leave_type_name.'</option>';
                                                    }
                                                    
                                                    else
                                                    {
                                                        echo '<option value="'.$leave_type_id.'" >'.$leave_type_name.'</option>';
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-3 col-md-offset-5">
                                    <button type="submit" class="btn btn-primary">Add leave</button>
                                </div>
                            </div>
                            <?php echo form_close();?> 
                            
                        </div>
                    </div>
                </div>
            </section>
            
            
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Leave Schedule</h2>
        </header>            

        <!-- Widget content -->
        <div class="panel-body">
            <!-- Below line produces calendar. I am using FullCalendar plugin. -->
            <div id="leave_schedule"></div>
        </div>
    
    </section>

<script type="text/javascript">
$(document).ready(function() {
	var config_url = $('#config_url').val();
	var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
  $.ajax({
	type:'POST',
	url: config_url+"hr/leave/leave_schedule",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var appointments = [];
		var total_events = parseInt(data.total_events, 10);

		for(i = 0; i < total_events; i++)
		{
			var data_array = [];
			
			data_title = data.title[i];
			data_start = data.start[i];
			data_end = data.end[i];
			data_backgroundColor = data.backgroundColor[i];
			data_borderColor = data.borderColor[i];
			data_allDay = data.allDay[i];
			data_url = data.url[i];
			
			//add the items to an array
			data_array.title = data_title;
			data_array.start = data_start;
			data_array.end = data_end;
			data_array.backgroundColor = data_backgroundColor;
			data_array.borderColor = data_borderColor;
			data_array.allDay = data_allDay;
			data_array.url = data_url;
			//console.log(data_array);
			appointments.push(data_array);
		}
		/*console.log(appointments);
		for(var i in data){
			appointments.push([i, data [i]]);alert(data[i]);
		}*/
		
		$('#leave_schedule').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: true,
			events: appointments
		});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

});
</script>