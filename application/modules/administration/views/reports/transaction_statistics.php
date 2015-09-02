<div class="row statistics">
    <div class="col-md-2 col-sm-12">
        <ul class="today-datas">
            <!-- List #1 -->
            <li class="overall-datas">
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
                            <th>Staff</th>
                            <td><?php echo $staff;?></td>
                        </tr>
                        <tr>
                            <th>Students</th>
                            <td><?php echo $students;?></td>
                        </tr>
                        <tr>
                            <th>Insurance</th>
                            <td><?php echo $insurance;?></td>
                        </tr>
                        <tr>
                            <th>Other</th>
                            <td><?php echo $other;?></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Text -->
                <div class="datas-text">
                	Total Visits <span class="bold"><?php echo number_format($total_patients, 0);?></span>
                </div>
                
                <div class="clearfix"></div>
            </li>
        </ul>
    </div>
    
    <div class="col-md-10 col-sm-12">
        <ul class="today-datas">
            <li class="overall-datas">
                <div class="row">
                    <div class="col-md-3">
						<?php
                        	$total_services_revenue = ($total_services_revenue + $debit_notes) - $credit_notes;
                            $total_searched_revenue = ($total_payments + $debit_notes) - $credit_notes;

                        ?>
                        <h5>Revenue Type</h5>
                        <table class="table table-striped table-hover table-condensed">
                            <tbody>
                                <tr>
                                    <th>Cash</th>
                                    <td><?php echo number_format($total_cash_collection, 2);?></td>
                                </tr>
                                <tr>
                                    <th>Creditors</th>
                                    <td><?php echo number_format(($total_services_revenue - $total_cash_collection), 2);?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><?php echo number_format((($total_services_revenue - $total_cash_collection) + $total_cash_collection), 2);?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="clearfix"></div>
            		</div>
                    <!-- End Transaction Breakdown -->
                    
                    <?php 
					
					if($title != 'Debtors Report')
					{
					?>
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
                    <!-- End Cash Breakdown -->
                    
                    <?php 
					}
					if($title != 'Cash Report')
					{
					?>
                    <div class="col-md-3">
                        <h5>Debt Breakdown</h5>
                        <table class="table table-striped table-hover table-condensed">
                            <tbody>
                                <tr>
                                    <th>Student Debt</th>
                                    <td><?php echo number_format($total_students_debt, 2);?></td>
                                </tr>
                                <tr>
                                    <th>Staff Debt</th>
                                    <td><?php echo number_format(($total_staff_debt), 2);?></td>
                                </tr>
                                <tr>
                                    <th>Insurance Debt</th>
                                    <td><?php echo number_format(($total_insurance_debt), 2);?></td>
                                </tr>
                                <tr>
                                    <th>Other Debt</th>
                                    <td><?php echo number_format(($total_other_debt - $total_cash_collection), 2);?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><?php echo number_format((($total_other_debt - $total_cash_collection) + $total_insurance_debt + $total_staff_debt + $total_students_debt), 2);?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="clearfix"></div>
            		</div>
                    <!-- End debt Breakdown -->
                    <?php
					}
					?>
                    <div class="col-md-3">
                        <div class="datas-text pull-right">Total Revenue <span class="bold">
                            KSH <?php echo number_format($total_services_revenue, 2);?></span>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>