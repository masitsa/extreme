<!-- search -->
<?php echo $this->load->view('search/test_search', '', TRUE);?>
<!-- end search -->
<div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>lab_charges/add_lab_test" class="btn btn-success btn-sm pull-right">Add a lab test</a>
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
		$search = $this->session->userdata('lab_tests');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'lab_charges/close_test_search" class="btn btn-warning">Close Search</a>';
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
						  <th>Class</th>
						  <th>Test</th>
						  <th>Units</th>
						  <th>Price</th>
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
				
				$lab_test_class_id = $row->lab_test_class_id;
				$lab_test_class = $row->lab_test_class_name;
				$lab_test_name = $row->lab_test_name;
				$lab_test_units = $row->lab_test_units;
				$lab_test_price = $row->lab_test_price;
				$lab_test_malelowerlimit = $row->lab_test_malelowerlimit;
				$lab_test_malelupperlimit = $row->lab_test_malelupperlimit;
				$lab_test_femalelowerlimit = $row->lab_test_femalelowerlimit;
				$lab_test_femaleupperlimit = $row->lab_test_femaleupperlimit;
				$lab_test_delete = $row->lab_test_delete;
				$lab_test_id = $row->lab_test_id;
				$count++;
				
				if($lab_test_delete == 1)
				{
					$test_button = '<td><a href="'.site_url().'lab_charges/activation/activate/test_lab/'.$lab_test_id.'" class="btn btn-sm btn-default">Activate</a></td>';

				}
				else
				{
					$test_button = '<td><a href="'.site_url().'lab_charges/activation/deactivate/test_lab/'.$lab_test_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to deactivate this test?\');">Deactivate</a></td>';
				}
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$lab_test_class.'</td>
							<td>'.$lab_test_name.'</td>
							<td>'.$lab_test_units.'</td>
							<td>'.$lab_test_price.'</td>
							<td>'.$lab_test_malelowerlimit.'</td>
							<td>'.$lab_test_malelupperlimit.'</td>
							<td>'.$lab_test_femalelowerlimit.'</td>
							<td>'.$lab_test_femaleupperlimit.'</td>
							<td><a href="'.site_url().'lab_charges/test_format/'.$lab_test_id.'" class="btn btn-sm btn-info">Formats</a></td>
							<td><a href="'.site_url().'lab_charges/add_lab_test/'.$lab_test_id.'" class="btn btn-sm btn-success">Edit</a></td>
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
			$result .= "There are no patients";
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