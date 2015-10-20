<div class="row statistics">
    <div class="col-md-2 col-sm-12">
    	 <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Visits</h2>
              </header>             
        
              <!-- Widget content -->
              <div class="panel-body">
                <h5>Visit Breakdown</h5>
                <table class="table table-striped table-hover table-condensed">
                	<thead>
                    	<tr>
                        	<th>Type</th>
                            <th>Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Outpatients</th>
                            <td><?php echo $outpatients;?></td>
                        </tr>
                        <tr>
                            <th>Inpatients</th>
                            <td><?php echo $inpatients;?></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Text -->
                <h5>Total Visits</h5>
                <h4><?php echo $total_patients;?></h4>
                
                <div class="clearfix"></div>
          	</div>
		</section>
    </div>
    
    <div class="col-md-10 col-sm-12">
    	 <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
            	<h2 class="panel-title">Transaction breakdown</h2>
            </header>             
        
              <!-- Widget content -->
              <div class="panel-body">
                <div class="row">
                    <!-- End Transaction Breakdown -->
                    
                    <div class="col-md-3">
                        <h5>Cash Breakdown</h5>
                        <table class="table table-striped table-hover table-condensed">
                            <tbody>
								<?php
								$total_cash_breakdown = 0;
                                if($payment_methods->num_rows() > 0)
                                {
                                    foreach($payment_methods->result() as $res)
                                    {
                                        $method_name = $res->payment_method;
                                        $payment_method_id = $res->payment_method_id;
                                        $total = 0;
                                        
                                        if($normal_payments->num_rows() > 0)
                                        {
                                            foreach($normal_payments->result() as $res2)
                                            {
                                                $payment_method_id2 = $res2->payment_method_id;
                                            
                                                if($payment_method_id == $payment_method_id2)
                                                {
                                                    $total += $res2->amount_paid;
                                                }
                                            }
                                        }
										
										$total_cash_breakdown += $total;
                                    
                                        echo 
										'
										<tr>
											<th>'.$method_name.'</th>
											<td>'.number_format($total, 2).'</td>
										</tr>
										';
                                    }
                                    
									echo 
									'
									<tr>
										<th>Total</th>
										<td>'.number_format($total_cash_breakdown, 2).'</td>
									</tr>
									';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-3">
                        <h4>Total Cash</h4>	
                        <h3>Ksh <?php echo number_format($total_cash_breakdown, 2);?></h3>
                    </div>
                </div>
          	</div>
		</section>
    </div>
</div>