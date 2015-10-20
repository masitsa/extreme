<div class="row">
    <div class="col-md-4">

        <section class="panel panel-featured">
            <header class="panel-heading">
            	 <h2 class="panel-title">Select debtor</h2>
            </header>             

			<!-- Widget content -->
			<div class="panel-body">
				<?php
				echo form_open("administration/reports/select_debtor", array("class" => "form-horizontal"));
				?>
				<div class="form-group">
					<label class="col-lg-12 control-label">Debtor: </label>
					
					<div class="col-lg-12">
						<select class="form-control" name="visit_type_id">
							<?php
								if($visit_type_query->num_rows() > 0)
								{
									foreach($visit_type_query->result() as $row):
										$visit_type_name2 = $row->visit_type_name;
										$visit_type_id2= $row->visit_type_id;
										?><option value="<?php echo $visit_type_id2; ?>" ><?php echo $visit_type_name2; ?></option><?php	
									endforeach;
								}
							?>
						</select>
					</div>
				</div>        
				
				<div class="center-align">
					<button type="submit" class="btn btn-info">Search</button>
				</div>

				<?php echo form_close();?>
			</div>
		</section>
	</div>
    
    <div class="col-md-8">
        
        <section class="panel panel-featured">
            <header class="panel-heading">
            	 <h2 class="panel-title">Search debtor</h2>
            </header>             

			<!-- Widget content -->
			<div class="panel-body">
				<?php
				echo form_open("administration/reports/search_debtors/".$visit_type_id, array("class" => "form-horizontal"));
				?>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-lg-12 control-label">Batch no.: </label>
							
							<div class="col-lg-12">
								<input type="text" class="form-control" name="batch_no" placeholder="Batch no.">
							</div>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-lg-12 control-label">Batch date from: </label>
							
							<div class="col-lg-12">
                            	<div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="batch_date_from" placeholder="Batch date from">
                                </div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-lg-12 control-label">Batch date to: </label>
							
							<div class="col-lg-12">
                            	<div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="batch_date_to" placeholder="Batch date to">
                                </div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="center-align" style="margin-top:14px;">
					<button type="submit" class="btn btn-info">Search</button>
				</div>
				<?php
				echo form_close();
				?>
			</div>
		</section>
    </div>
</div>