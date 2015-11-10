<!-- search -->
<?php echo $this->load->view('search/test_search', '', TRUE);?>
<!-- end search -->
<div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>lab_charges/add_lab_test" class="btn btn-info btn-sm pull-right">Add a lab test</a>
		<a href="<?php echo site_url();?>lab_charges/export_results" class="btn btn-success btn-sm pull-right" style="margin-right:10px;">Export</a>
	</div>
</div>
 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
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
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test_class.lab_test_class_name/'.$order_method.'/'.$page.'">Class</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_name/'.$order_method.'/'.$page.'">Test</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_units/'.$order_method.'/'.$page.'">Units</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_price/'.$order_method.'/'.$page.'">Price</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_malelowerlimit/'.$order_method.'/'.$page.'">Male Lower</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_malelupperlimit/'.$order_method.'/'.$page.'">Male Upper</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_femalelowerlimit/'.$order_method.'/'.$page.'">Female Lower</a></th>
						  <th><a href="'.site_url().'laboratory-setup/tests/lab_test.lab_test_femaleupperlimit/'.$order_method.'/'.$page.'">Female Upper</a></th>
						  <th>No Done</th>
						  <th>Revenue</th>
						  <th colspan="4">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
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
				$no_done = $this->lab_charges_model->get_tests_done($lab_test_id);
				$revenue = $this->lab_charges_model->get_tests_revenue($lab_test_id);
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
							<td>'.number_format($no_done, 0).'</td>
							<td>'.number_format($revenue, 2).'</td>
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
			$result .= "There are no tests";
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