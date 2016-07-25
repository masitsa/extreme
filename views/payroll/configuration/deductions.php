<div class="row">
    
	<div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit deductions</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>Deduction</th>
                    	<th colspan="2">Taxable</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_deduction");?>" method="post">
                    <tr>
                        <td><input type='text' name='deduction_name' class="form-control"></td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="deduction_taxable" id="optionsRadios1" value="1" checked="checked">
                                    Yes
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="deduction_taxable" id="optionsRadios2" value="2">
                                    No
                                </label>
                            </div>
                        </td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Deduction</button></td>
                    </tr>
                    </form>
                <?php
                
                if($deductions->num_rows() > 0)
                {

                    foreach ($deductions->result() as $row2)
                    {
                        $deduction_id = $row2->deduction_id;
                        $deduction_name = $row2->deduction_name;
                        $deduction_taxable = $row2->deduction_taxable;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-deduction/".$deduction_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='deduction_name<?php echo $deduction_id;?>' value='<?php echo $deduction_name;?>' class="form-control"></td>
                            <?php
                            if($deduction_taxable == 1)
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="deduction_taxable<?php echo $deduction_id;?>" id="optionsRadios1" value="1" checked="checked">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="deduction_taxable<?php echo $deduction_id;?>" id="optionsRadios2" value="2">
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
                                        <input type="radio" name="deduction_taxable<?php echo $deduction_id;?>" id="optionsRadios1" value="1">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="deduction_taxable<?php echo $deduction_id;?>" id="optionsRadios2" value="2" checked="checked">
                                        No
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-deduction/".$deduction_id);?>" onclick="return confirm('Do you want to delete <?php echo $deduction_name;?>?');" title="Delete <?php echo $deduction_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                        </tr>
                        </form>
                        <?php
                    }
                }
                ?>
                </table>
            </div>
        </section>
    </div><div class="col-md-6">
    	<section class="panel">
            <header class="panel-heading">						
                <h2 class="panel-title">Edit other deductions</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                	<tr>
                    	<th>Other deduction</th>
                    	<th colspan="2">Taxable</th>
                    	<th colspan="2"></th>
                    </tr>
                    <form action="<?php echo site_url("accounts/payroll/add_new_other_deduction");?>" method="post">
                    <tr>
                        <td><input type='text' name='other_deduction_name' class="form-control"></td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="other_deduction_taxable" id="optionsRadios1" value="1" checked="checked">
                                    Yes
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="other_deduction_taxable" id="optionsRadios2" value="2">
                                    No
                                </label>
                            </div>
                        </td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Deduction</button></td>
                    </tr>
                    </form>
                <?php
                
                if($other_deductions->num_rows() > 0)
                {

                    foreach ($other_deductions->result() as $row2)
                    {
                        $other_deduction_id = $row2->other_deduction_id;
                        $other_deduction_name = $row2->other_deduction_name;
                        $other_deduction_taxable = $row2->other_deduction_taxable;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-other-deduction/".$other_deduction_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='other_deduction_name<?php echo $other_deduction_id;?>' value='<?php echo $other_deduction_name;?>' class="form-control"></td>
                            <?php
                            if($other_deduction_taxable == 1)
							{
							?>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="other_deduction_taxable<?php echo $other_deduction_id;?>" id="optionsRadios1" value="1" checked="checked">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="other_deduction_taxable<?php echo $other_deduction_id;?>" id="optionsRadios2" value="2">
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
                                        <input type="radio" name="other_deduction_taxable<?php echo $other_deduction_id;?>" id="optionsRadios1" value="1">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="other_deduction_taxable<?php echo $other_deduction_id;?>" id="optionsRadios2" value="2" checked="checked">
                                        No
                                    </label>
                                </div>
                            </td>
                            <?php
							}
							?>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-other-deduction/".$other_deduction_id);?>" onclick="return confirm('Do you want to delete <?php echo $other_deduction_name;?>?');" title="Delete <?php echo $other_deduction_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
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
                <h2 class="panel-title">Edit loan schemes</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                    <form action="<?php echo site_url("accounts/payroll/add_new_loan_scheme");?>" method="post">
                    <tr>
                        <td><input type='text' name='loan_scheme_name' class="form-control"></td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Loan Scheme</button></td>
                    </tr>
                    </form>
                <?php
                
                if($loan_schemes->num_rows() > 0)
                {
                    foreach ($loan_schemes->result() as $row2)
                    {
                        $loan_scheme_id = $row2->loan_scheme_id;
                        $loan_scheme_name = $row2->loan_scheme_name;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-loan-scheme/".$loan_scheme_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='loan_scheme_name<?php echo $loan_scheme_id;?>' value='<?php echo $loan_scheme_name;?>' class="form-control"></td>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-loan-scheme/".$loan_scheme_id);?>" onclick="return confirm('Do you want to delete <?php echo $loan_scheme_name;?>?');" title="Delete <?php echo $loan_scheme_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
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
                <h2 class="panel-title">Edit Savings</h2>
            </header>
            <div class="panel-body">
            	<table class='table table-striped table-hover table-condensed'>
                    <form action="<?php echo site_url("accounts/payroll/add_new_saving");?>" method="post">
                    <tr>
                        <td><input type='text' name='saving_name' class="form-control"></td>
                        <td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Saving</button></td>
                    </tr>
                    </form>
                <?php
                
                if($savings->num_rows() > 0)
                {
                    foreach ($savings->result() as $row2)
                    {
                        $saving_id = $row2->savings_id;
                        $saving_name = $row2->savings_name;
                        ?>
                        <form action="<?php echo site_url("accounts/payroll/edit-saving/".$saving_id);?>" method="post">
                        <tr>
                            <td><input type='text' name='saving_name<?php echo $saving_id;?>' value='<?php echo $saving_name;?>' class="form-control"></td>
                            <td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                            <td><a href="<?php echo site_url("accounts/payroll/delete-saving/".$saving_id);?>" onclick="return confirm('Do you want to delete <?php echo $saving_name;?>?');" title="Delete <?php echo $saving_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
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