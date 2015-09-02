<?php echo $this->load->view('search/search_brand', '', TRUE);?>
<!-- end search -->
 <div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>/pharmacy/add_brand" class="btn btn-success pull-right">Add new brand</a>
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
		$search = $this->session->userdata('brands_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/pharmacy/close_brand_search" class="btn btn-warning">Close Search</a>';
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
				$brand_id = $row->brand_id;
				$brand_name = $row->brand_name;
				$brand_delete = $row->brand_delete;
				
				// end of diagnosis
				
				if($brand_delete == 1)
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_brand/'.$brand_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Deactivate brand</a></td>';
				}
				else
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_brand/'.$brand_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Activate Brand</a></td>';
				}
				
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$brand_name.'</td>
							<td><a href="'.site_url().'/pharmacy/add_brand/'.$brand_id.'" class="btn btn-sm btn-success">Edit</a></td>
						
							
						
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