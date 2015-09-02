<?php echo $this->load->view('search/search_types', '', TRUE);?>
<!-- end search -->
 <div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>/pharmacy/add_type" class="btn btn-success pull-right">Add new type</a>
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
		$search = $this->session->userdata('types_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/pharmacy/close_type_search" class="btn btn-warning">Close Search</a>';
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
						  <th>type</th>
						  <th colspan=3>Action</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$count = 1;
			foreach ($query->result() as $row)
			{
				$drug_type_id = $row->drug_type_id;
				$drug_type_name = $row->drug_type_name;
				$drug_type_delete = $row->drug_type_delete;
				
				// end of diagnosis
				
				if($drug_type_delete == 1)
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_type/'.$drug_type_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Deactivate type</a></td>';
				}
				else
				{
					$buttons = '<td><a href="'.site_url().'/pharmacy/delete_type/'.$drug_type_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Send to accounts?\');">Activate type</a></td>';
				}
				
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$drug_type_name.'</td>
							<td><a href="'.site_url().'/pharmacy/add_type/'.$drug_type_id.'" class="btn btn-sm btn-success">Edit</a></td>
							
							
						
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