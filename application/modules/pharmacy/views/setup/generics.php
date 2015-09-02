<?php echo $this->load->view('search/search_generic', '', TRUE);?>
<!-- end search -->
 <div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>/pharmacy/add_generic" class="btn btn-success pull-right">Add new generic</a>
	</div>
</div>
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
          
<?php
		$search = $this->session->userdata('generics_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/pharmacy/close_generic_search" class="btn btn-warning">Close Search</a>';
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
						  <th>Brand</th>
						  <th colspan=3>Action</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$count = 1;
			foreach ($query->result() as $row)
			{
				$generic_id = $row->generic_id;
				$generic_name = $row->generic_name;
				$generic_delete = $row->delete_generic;
				
				// end of diagnosis
				
				if($generic_delete == 1)
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_generic/'.$generic_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Deactivate generic</a></td>';
				}
				else
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_generic/'.$generic_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Activate generic</a></td>';
				}
				
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$generic_name.'</td>
							<td><a href="'.site_url().'/pharmacy/add_generic/'.$generic_id.'" class="btn btn-sm btn-success">Edit</a></td>
							
							
						
						</tr> 
					';
				$count++;
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no previous prescriptions";
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
    </div>
  </div>