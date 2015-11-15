<?php

if($query->num_rows() > 0)
{
	$notes_type_name = '';
	$result = '';
	
	foreach($query->result() as $row)
	{
		$personnel_fname = $row->personnel_fname;
		$notes_id = $row->notes_id;
		$notes_name = $row->notes_name;
		$notes_type_name = $row->notes_type_name;
		$notes_signature = $row->notes_signature;
		$created_by = $row->created_by;
		$notes_date = date('jS M Y',strtotime($row->notes_date));
		$notes_time = date('H:i a',strtotime($row->notes_time));
		
		$created_check = $mobile_personnel_id;
		$actions = '<td></td><td></td>';
		
		if($created_by == $created_check)
		{
			$actions = '
				<td>
					<button type="button" class="btn btn-small btn-success" data-toggle="modal" data-target="#edit_notes'.$notes_id.'"><i class="fa fa-pencil"></i></button>
					
					<!-- Modal -->
<div class="modal fade" id="edit_notes'.$notes_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title" id="myModalLabel">Edit notes</h4>
            </div>
            <div class="modal-body">
            	'.form_open("nurse/edit_nurse_notes/".$notes_id.'/'.$visit_id, array("class" => "form-horizontal", 'id' => 'edit_nurse_notes', 'notes_id' => $notes_id)).'
				<input type="hidden" name="notes_id" value="'.$notes_id.'"/>
                <div class="row" style="margin:10px;">
					<div class="col-sm-6" >
						<div class="form-group">
							<label class="control-label">Date</label>
							<input type="date" name="date'.$notes_id.'" class="form-control" value="'.$row->notes_date.'">
						</div>
					</div>
					
					<div class="col-sm-6" >
						<div class="form-group">
							<label class="control-label">Time</label>
							<input type="time" name="time'.$notes_id.'" class="form-control" value="'.$row->notes_time.'">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12" >
						<textarea id="nurse_notes_item" class="cleditor" rows="10" name="nurse_notes'.$notes_id.'">'.$notes_name.'</textarea>
					</div>
				</div>
				
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="center-align">
							<button type="submit" class="btn btn-large btn-primary">Update</button>
						</div>
					</div>
				</div>
                '.form_close().'
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
				</td>
				<td><a href="#" class="btn btn-danger btn-sm" onClick="delete_nurse_notes('.$visit_id.', '.$mobile_personnel_id.', '.$notes_id.')"><i class="fa fa-trash"></i></a></td>
			';
		}
		
		$result .= '
		<tr>
			<td>'.$notes_date.'</td>
			<td>'.$notes_time.'</td>
			<td>'.$notes_name.'</td>
			<td>'.$personnel_fname.'</td>
			'.$actions.'
		</tr>
		';
	}

	echo '<h4>'.$notes_type_name.'</h4>';
	
	echo '
	<table class="table table-responsive table-striped table-condensed table-bordered">
		<tr>
			<th>Date</th>
			<th>Time</th>
			<th>Notes</th>
			<th>By</th>
			<th colspan="2">Actions</th>
		</tr>
		'.$result.'
	</table>
	';
}
?>