<?php

if($query->num_rows() > 0)
{
	$notes_type_name = '';
	$result = '';
	
	foreach($query->result() as $row)
	{
		$notes_id = $row->notes_id;
		$notes_name = $row->notes_name;
		$notes_type_name = $row->notes_type_name;
		$notes_signature = $row->notes_signature;
		$created_by = $row->created_by;
		$notes_date = date('jS M Y',strtotime($row->notes_date));
		$notes_time = date('H:i a',strtotime($row->notes_time));
		
		$created_check = $this->session->userdata('personel_id');
		$actions = '';
		
		if($created_by == $created_check)
		{
			$actions = '
				<td><a href="'.$visit_id.'" class="btn btn-success btn-sm edit_notes"><i class="fa fa-pencil"></i></a></td>
				<td><a href="'.$visit_id.'" class="btn btn-danger btn-sm delete_notes"><i class="fa fa-trash"></i></a></td>
			';
		}
		
		$result .= '
		<tr>
			<td>'.$notes_date.'</td>
			<td>'.$notes_time.'</td>
			<td>'.$notes_name.'</td>
			<td><img src="'.$signature_location.$notes_signature.'" class="img-responsive"></td>
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
			<th>Signature</th>
			<th colspan="2">Actions</th>
		</tr>
		'.$result.'
	</table>
	';
}
?>