<!-- search -->
<?php //echo $this->load->view('patients/search_visit', '', TRUE);?>
<!-- end search -->
<!-- end search -->
<div class="row">
	<div class="col-md-12">
	<a href="<?php echo site_url();?>lab_charges/add_lab_test_format/<?php echo $test_iddd;?>" class="btn btn-primary pull-left">Add lab test format</a>
		<a href="<?php echo site_url();?>lab_charges/test_list" class="btn btn-success pull-right">Back to lab tests</a>
	</div>
</div>
 <section class="panel">
    <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </header>             

           <!-- Widget content -->
        <div class="panel-body">
          <div class="padd">
          
<?php
			$error = $this->session->userdata('error_message');
			$success = $this->session->userdata('success_message');
			
			if(!empty($error))
			{
				echo '<div class="alert alert-danger">'.$error.'</div>';
				$this->session->unset_userdata('error_message');
			}
			
			if(!empty($success))
			{
				echo '<div class="alert alert-success">'.$success.'</div>';
				$this->session->unset_userdata('success_message');
			}
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/nurse/close_queue_search" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Lab test</th>
						  <th>Format</th>
						  <th>Units</th>
						  <th>Male Lower Limit</th>
						  <th>Male Upper Limit</th>
						  <th>Female Lower Limit</th>
						  <th>Female Upper Limit</th>
						  <th colspan="4">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			$count = 0;
			
			foreach ($query->result() as $row)
			{
				
				$lab_test_id = $row->lab_test_id;
				$lab_test_formatname = $row->lab_test_formatname;
				$lab_test_name = $row->lab_test_name;
				$lab_test_format_id = $row->lab_test_format_id;
				$lab_test_format_delete = $row->lab_test_format_delete;
				$lab_test_format_units = $row->lab_test_format_units;
				$lab_test_format_malelowerlimit = $row->lab_test_format_malelowerlimit;
				$lab_test_format_maleupperlimit = $row->lab_test_format_maleupperlimit;
				$lab_test_format_femalelowerlimit = $row->lab_test_format_femalelowerlimit;
				$lab_test_format_femaleupperlimit = $row->lab_test_format_femaleupperlimit;
				$count++;
				
				if($lab_test_format_delete == 1)
				{
					$test_button = '<td><a href="'.site_url().'/lab_charges/activation/activate/test_format/'.$lab_test_format_id.'" class="btn btn-sm btn-default">Activate</a></td>';

				}
				else
				{
					$test_button = '<td><a href="'.site_url().'/lab_charges/activation/deactivate/test_format/'.$lab_test_format_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to deactivate this format?\');">Deactivate</a></td>';
				}
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$lab_test_name.'</td>
							<td>'.$lab_test_formatname.'</td>
							<td>'.$lab_test_format_units.'</td>
							<td>'.$lab_test_format_malelowerlimit.'</td>
							<td>'.$lab_test_format_maleupperlimit.'</td>
							<td>'.$lab_test_format_femalelowerlimit.'</td>
							<td>'.$lab_test_format_femaleupperlimit.'</td>
							<td><a href="'.site_url().'lab_charges/add_lab_test_format/'.$lab_test_id.'/'.$lab_test_format_id.'" class="btn btn-sm btn-success">Edit</a></td>
							'.$test_button.'
							
						</tr> 
					';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are lab test formats";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
      </section>
