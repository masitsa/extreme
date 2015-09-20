		<style type="text/css">
			#insured_company{display:none;}
		</style>
		<section class="panel">
		        <!-- Widget head -->
		       <header class="panel-heading">
		          <h5 class="pull-left"><i class="icon-reorder"></i>Initiate Visit</h5>
		          <div class="widget-icons pull-right">
		          	 <a href="<?php echo site_url();?>reception/patients-list" class="btn btn-primary btn-sm pull-right">  <i class="fa fa-angle-left"></i> Patients list</a>
		          </div>
		          <div class="clearfix"></div>
		        </header>             

		        <!-- Widget content -->
		        <div class="panel-body">
                	
                	<div class="well well-sm info">
                        <h5 style="margin:0;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>First name:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $patient_surname;?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Other names:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $patient_othernames;?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Account balance:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            Kes <?php echo number_format($account_balance, 2);?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="<?php echo site_url();?>administration/individual_statement/<?php echo $patient_id;?>/2" class="btn btn-sm btn-success pull-right" target="_blank" style="margin-top: 5px;">Patient Statement</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h5>
                    </div>
                    
					<div class="tabs">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a class="text-center" data-toggle="tab" href="#outpatient">Outpatient</a>
                            </li>
                            <li>
                                <a class="text-center" data-toggle="tab" href="#inpatient">Inpatient</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="outpatient">
                                <?php $this->load->view('visit/initiate_outpatient');?>
                            </div>
                            <div class="tab-pane" id="inpatient">
                                <h4 class="center-align">Initiate inpatient visit</h4>
                                <?php //$this->load->view('visit/initiate_inpatient');?>
                            </div>
                        </div>
                    </div>
                    
                </div>
        	</section>