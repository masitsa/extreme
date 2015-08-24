<?php 
	$visit_trail = $this->reception_model->get_visit_trail($visit_id);
	
	if($visit_trail->num_rows() > 0)
	{
		$trail = 
		'
			<table class="table table-responsive table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Department</th>
						<th>Sent At</th>
						<th>Sent By</th>
						<th>Released At</th>
						<th>Released By</th>
					</tr>
				</thead>
		';
		$count = 0;
		foreach($visit_trail->result() as $res)
		{
			$count++;
			$department_name = $res->departments_name;
			$created = date('H:i a',strtotime($res->created));
			$created_by = $res->personnel_fname;
			$status = $res->visit_department_status;
			
			if($status == 0)
			{
				$last_modified = date('H:i a',strtotime($res->last_modified));
				$modified_by = $res->modified_by;
			}
			
			else
			{
				$last_modified = '-';
				$modified_by = '-';
			}
			$personnel_query = $this->personnel_model->get_all_personnel();
			if($personnel_query->num_rows() > 0)
			{
				$personnel_result = $personnel_query->result();
				
				foreach($personnel_result as $adm)
				{
					$personnel_id = $adm->personnel_id;
					
					if($personnel_id == $modified_by)
					{
						$modified_by = $adm->personnel_fname;
					}
				}
			}
			
			else
			{
				$modified_by = '-';
			}
			
			$trail .=
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$department_name.'</td>
					<td>'.$created.'</td>
					<td>'.$created_by.'</td>
					<td>'.$last_modified.'</td>
					<td>'.$modified_by.'</td>
				</tr>
			';
		}
		
		$trail .= '</table>';
	}
	
	else
	{
		$trail = 'Trail not found';
	}
?>
<div class="row">
	<div class="col-md-12">
		<!-- Widget -->
		<div class="widget">
			<!-- Widget head -->
			<div class="widget-head">
				<h4 class="pull-left"><i class="icon-reorder"></i>Visit Trail</h4>
				<div class="widget-icons pull-right">
					<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
					<a href="#" class="wclose"><i class="icon-remove"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>             
			
			<!-- Widget content -->
			<div class="widget-content">
				<div class="padd">
					<?php echo $trail;?>
					
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-12">
		<!-- Widget -->
		<div class="widget">
			<!-- Widget head -->
			<div class="widget-head">
				<h4 class="pull-left"><i class="icon-reorder"></i>Visit Charges</h4>
				<div class="widget-icons pull-right">
					<a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
					<a href="#" class="wclose"><i class="icon-remove"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>             
			
			<!-- Widget content -->
			<div class="widget-content">
				<div class="padd">
				<table class="table table-hover table-bordered col-md-12">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>Item Name</th>
                        <th>Charge</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                        $item_invoiced_rs = $this->accounts_model->get_patient_visit_charge_items($visit_id);
                        $total = 0;
                        if(count($item_invoiced_rs) > 0){
                          $s=0;
                          
                          foreach ($item_invoiced_rs as $key_items):
                            $s++;
                            $service_charge_name = $key_items->service_charge_name;
                            $visit_charge_amount = $key_items->visit_charge_amount;
                            $service_name = $key_items->service_name;
                             $units = $key_items->visit_charge_units;

                             $visit_total = $visit_charge_amount * $units;

                            ?>
                              <tr>
                                <td><?php echo $s;?></td>
                                <td><?php echo $service_name;?></td>
                                <td><?php echo $service_charge_name;?></td>
                                <td><?php echo number_format($visit_total,2);?></td>
                              </tr>
                            <?php
                              $total = $total + $visit_total;
                          endforeach;
                            ?>
                            <tr>
                              <td></td>
                              <td></td>
                              <td>Total :</td>
                              <td> <?php echo number_format($total,2);?></td>
                            </tr>
                            <?php
                        }else{
                           ?>
                            <tr>
                              <td colspan="4"> No Charges</td>
                            </tr>
                            <?php
                        }

                        ?>
                          
                      </tbody>
                    </table>

				</div>
			</div>
		</div>
	</div>
</div>
