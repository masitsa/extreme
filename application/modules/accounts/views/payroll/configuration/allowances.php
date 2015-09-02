<div class="row">
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit non cash benefits</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>Benefit</th>
                    	<th colspan="2">Taxable</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_benefit");?>" method="post">
                    <tr>
                        <td><input type='text' name='benefit_name' class="form-control"></td>
                        <td>
                        	<div class="radio">
                                <label>
                                    <input type="radio" name="benefit_taxable" id="optionsRadios1" value="1" checked="checked">
                                    Yes
                                </label>
                            </div>
                        </td>
                        <td>
                        	<div class="radio">
                                <label>
                                    <input type="radio" name="benefit_taxable" id="optionsRadios2" value="2">
                                    No
                                </label>
                            </div>
                        </td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add benefit</button></td>
                    </tr>
                    </form>
                <?php
                
                if($benefits->num_rows() > 0)
                {
                    foreach ($benefits->result() as $row2)
                    {
                        $benefit_id = $row2->benefit_id;
                        $benefit_name = $row2->benefit_name;
                        $benefit_taxable = $row2->benefit_taxable;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-benefit/".$benefit_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='benefit_name<?php echo $benefit_id;?>' value='<?php echo $benefit_name;?>' class="form-control"></td>
                            <?php
                            if($benefit_taxable == 1)
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="benefit_taxable<?php echo $benefit_id;?>" id="optionsRadios1" value="1" checked="checked">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="benefit_taxable<?php echo $benefit_id;?>" id="optionsRadios2" value="2">
                                        No
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
                                        <input type="radio" name="benefit_taxable<?php echo $benefit_id;?>" id="optionsRadios1" value="1">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="benefit_taxable<?php echo $benefit_id;?>" id="optionsRadios2" value="2" checked="checked">
                                        No
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-benefit/".$benefit_id);?>" onclick="return confirm('Do you want to delete <?php echo $benefit_name;?>?');" title="Delete <?php echo $benefit_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
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
                <h2 class="panel-title">Edit cash benefits</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>Allowance</th>
                    	<th colspan="2">Taxable</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_allowance");?>" method="post">
                    <tr>
                        <td><input type='text' name='allowance_name' class="form-control"></td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="allowance_taxable" id="optionsRadios1" value="1" checked="checked">
                                    Yes
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="allowance_taxable" id="optionsRadios2" value="2">
                                    No
                                </label>
                            </div>
                        </td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add benefit</button></td>
                    </tr>
                    </form>
                <?php
                
                if($allowances->num_rows() > 0)
                {
                    foreach ($allowances->result() as $row2)
                    {
                        $allowance_id = $row2->allowance_id;
                        $allowance_name = $row2->allowance_name;
                        $allowance_taxable = $row2->allowance_taxable;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit_allowance/".$allowance_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='allowance_name<?php echo $allowance_id;?>' value='<?php echo $allowance_name;?>' class="form-control"></td>
                            <?php
                            if($allowance_taxable == 1)
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="allowance_taxable<?php echo $allowance_id;?>" id="optionsRadios1" value="1" checked="checked">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="allowance_taxable<?php echo $allowance_id;?>" id="optionsRadios2" value="2">
                                        No
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
                                        <input type="radio" name="allowance_taxable<?php echo $allowance_id;?>" id="optionsRadios1" value="1">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="allowance_taxable<?php echo $allowance_id;?>" id="optionsRadios2" value="2" checked="checked">
                                        No
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete_allowance/".$allowance_id);?>" onclick="return confirm('Do you want to delete <?php echo $allowance_name;?>?');" title="Delete <?php echo $allowance_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
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