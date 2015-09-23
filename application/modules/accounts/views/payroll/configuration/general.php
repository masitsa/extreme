<div class="row">
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit payments</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                    <form action="<?php echo site_url("accounts/payroll/add_new_payment");?>" method="post">
                    <tr>
                        <td><input type='text' name='payment_name' class="form-control"></td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add payment</button></td>
                    </tr>
                    </form>
                <?php
                
                if($payments->num_rows() > 0)
                {
                    foreach ($payments->result() as $row2)
                    {
                        $payment_id = $row2->payment_id;
                        $payment_name = $row2->payment_name;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-payment/".$payment_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='payment_name<?php echo $payment_id;?>' value='<?php echo $payment_name;?>' class="form-control"></td>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-payment/".$payment_id);?>" onclick="return confirm('Do you want to delete <?php echo $payment_name;?>?');" title="Delete <?php echo $payment_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div>
    
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit NSSF amount</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                <?php
                
                if($nssf->num_rows() > 0)
                {
                    foreach ($nssf->result() as $row2)
                    {
                        $nssf_id = $row2->nssf_id;
                        $amount = $row2->amount;
                        $percentage = $row2->percentage;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-nssf/".$nssf_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='amount' value='<?php echo $amount;?>' class="form-control"></td>
                            
                            <?php
                            if($percentage == 1)
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="percentage" id="optionsRadios1" value="1" checked="checked">
                                        Percentage
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="percentage" id="optionsRadios2" value="0">
                                        Fixed amount
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							else
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="percentage" id="optionsRadios1" value="1">
                                        Percentage
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="percentage" id="optionsRadios2" value="0" checked="checked">
                                        Fixed amount
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div>  
	<div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit Relief</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                <?php
                
                if($relief->num_rows() > 0)
                {
                    foreach ($relief->result() as $row2)
                    {
                        $relief_id = $row2->relief_id;
                        $relief_name = $row2->relief_name;
                        $relief_amount = $row2->relief_amount;
                        $relief_type = $row2->relief_type;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-relief/".$relief_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='relief_name<?php echo $relief_id;?>' value='<?php echo $relief_name;?>' class="form-control"></td>
                            <td><input type='text' name='relief_amount<?php echo $relief_id;?>' value='<?php echo $relief_amount;?>' class="form-control"></td>
                            <?php if($relief_type == 1){?>
                            <td>
                            	<div class="radio">
                                    <label>
                                        <input id="optionsRadios1" type="radio" checked value="1" name="relief_type<?php echo $relief_id;?>" checked="checked">
                                        Fixed amount
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input id="optionsRadios2" type="radio" value="0" name="relief_type<?php echo $relief_id;?>">
                                        Percentage
                                    </label>
                                </div>
                            </td>
                            <?php } else{?>
                            <td>
                            	<div class="radio">
                                    <label>
                                        <input id="optionsRadios1" type="radio" checked value="1" name="relief_type<?php echo $relief_id;?>">
                                        Fixed amount
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input id="optionsRadios2" type="radio" value="0" name="relief_type<?php echo $relief_id;?>" checked="checked">
                                        Percentage
                                    </label>
                                </div>
                            </td>
                            <?php }?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="row">
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit NHIF amounts</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>From (Ksh)</th>
                    	<th>To (Ksh)</th>
                    	<th>Amount (Ksh)</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_nhif");?>" method="post">
                    <tr>
                        <td><input type='text' name='nhif_from' class="form-control" value="<?php echo set_value('nhif_from');?>"></td>
                        <td><input type='text' name='nhif_to' class="form-control" value="<?php echo set_value('nhif_to');?>"></td>
                        <td><input type='text' name='nhif_amount' class="form-control" value="<?php echo set_value('nhif_amount');?>"></td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add</button></td>
                    </tr>
                    </form>
                <?php
                
                if($nhif->num_rows() > 0)
                {
                    foreach ($nhif->result() as $row2)
                    {
                        $nhif_id = $row2->nhif_id;
                        $nhif_from = $row2->nhif_from;
                        $nhif_to = $row2->nhif_to;
                        $nhif_amount = $row2->nhif_amount;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-nhif/".$nhif_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='nhif_from<?php echo $nhif_id;?>' value='<?php echo $nhif_from;?>' class="form-control"></td>
                            <td><input type='text' name='nhif_to<?php echo $nhif_id;?>' value='<?php echo $nhif_to;?>' class="form-control"></td>
                            <td><input type='text' name='nhif_amount<?php echo $nhif_id;?>' value='<?php echo $nhif_amount;?>' class="form-control"></td>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-nhif/".$nhif_id);?>" onclick="return confirm('Do you want to delete <?php echo $nhif_amount;?>?');" title="Delete <?php echo $nhif_amount;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div>
    
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit PAYE amounts</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>From (Ksh)</th>
                    	<th>To (Ksh)</th>
                    	<th>Amount (Ksh)</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_paye");?>" method="post">
                    <tr>
                        <td><input type='text' name='paye_from' class="form-control" value="<?php echo set_value('paye_from');?>"></td>
                        <td><input type='text' name='paye_to' class="form-control" value="<?php echo set_value('paye_to');?>"></td>
                        <td><input type='text' name='paye_amount' class="form-control" value="<?php echo set_value('paye_amount');?>"></td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add</button></td>
                    </tr>
                    </form>
                <?php
                
                if($paye->num_rows() > 0)
                {
                    foreach ($paye->result() as $row2)
                    {
                        $paye_id = $row2->paye_id;
                        $paye_from = $row2->paye_from;
                        $paye_to = $row2->paye_to;
                        $paye_amount = $row2->paye_amount;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-paye/".$paye_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='paye_from<?php echo $paye_id;?>' value='<?php echo $paye_from;?>' class="form-control"></td>
                            <td><input type='text' name='paye_to<?php echo $paye_id;?>' value='<?php echo $paye_to;?>' class="form-control"></td>
                            <td><input type='text' name='paye_amount<?php echo $paye_id;?>' value='<?php echo $paye_amount;?>' class="form-control"></td>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-paye/".$paye_id);?>" onclick="return confirm('Do you want to delete <?php echo $paye_amount;?>?');" title="Delete <?php echo $paye_amount;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div>
</div>