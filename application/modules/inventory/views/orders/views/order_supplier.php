<?php
// get order details

// get supplier  and supplier details
$supplier_order_details = $this->orders_model->get_supplier_order_details($supplier_order_id);

if($supplier_order_details->num_rows() > 0)
{
	foreach ($supplier_order_details->result() as $key_supplier) {
		# code...

		$order_number = $key_supplier->order_number;
		$supplier_name = $key_supplier->supplier_name;
		$supplier_contact_person = $key_supplier->supplier_contact_person;
		$supplier_phone = $key_supplier->supplier_phone;
		$supplier_id = $key_supplier->supplier_id;
		$supplier_email = $key_supplier->supplier_email;
		$supplier_status = $key_supplier->supplier_status;

		$total_qoute_amount = $key_supplier->total_qoute_amount;
		$qoutation_attachment = $key_supplier->qoutation_attachment;
		$request_form_attachment = $key_supplier->request_form_attachment;
		$lpo_form_attachment = $key_supplier->lpo_form_attachment;
	}
}

?>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title pull-left"><?php echo $supplier_name;?></h2>
        <div class="widget-icons pull-right">
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="col-md-6">
    				<h4>Supplier Details</h4>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"><strong> Supplier Name :</strong> </label>
    					<div class="col-lg-7">
			            	<?php echo $supplier_name;?>
			            </div>
    				</div>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"> <strong> Contact Person :</strong> </label>
    					<div class="col-lg-7">
			            	<?php echo $supplier_contact_person;?>
			            </div>
    				</div>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"> <strong> Supplier Email :</strong> </label>
    					<div class="col-lg-7">
			            	<?php echo $supplier_email;?>
			            </div>
    				</div>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"><strong>Supplier Phone :</strong> </label>
    					<div class="col-lg-7">
			            	<?php echo $supplier_phone;?>
			            </div>
    				</div>

    			</div>
    			<div class="col-md-6">
    				<h4>Order Details</h4>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"><strong>Order Number :</strong> </label>
    					<div class="col-lg-7">
			            	<strong> <?php echo $order_number;?></strong>
			            </div>
    				</div>
    				
    				<div class="form-group">
    					<label class="col-lg-5 control-label"><strong>Order Items :</strong> </label>
    					<div class="col-lg-7">
			            	<strong> 45 Items</strong>
			            </div>
    				</div>
    				<div class="form-group">
    					<label class="col-lg-5 control-label"><strong>Order Items :</strong> </label>
    					<div class="col-lg-7">
			            	<a class="btn btn-primary btn-sm col-md-12 fa fa-print" href="<?php echo base_url();?>inventory/generate-rfq/<?php echo $order_id;?>/<?php echo $supplier_id;?>/<?php echo $order_number;?>" target="_blank"> Generate Request for quotation </a>
			            </div>
    				</div>
    				
    				
    				
    			</div>
    		</div>
    	</div>
    	<hr>
    	<div class="row">
    		<div class="col-md-4 ">
    		</div>
    		<div class="col-md-4 ">
    			<h4 class="center-align">Quotation Attachment</h4>
    			<br>
    			<div class="form-group">
					<label class="col-lg-5 control-label"> Quatation Attachment:</label>
					<div class="col-lg-7">
		            	<input type="file" name="quotation_attachment">
		            </div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"> Total Quote Amount (KES) :</label>
					<div class="col-lg-7">
		            	<input type="text" class="form-control" name="total_qoute_amount" placeholder="Total Amount" value="<?php echo $total_qoute_amount;?>">
		            </div>
				</div>
				<div class="form-group">
					<label class="col-lg-5 control-label"> Discount (KES) :</label>
					<div class="col-lg-7">
		            	<input type="text" class="form-control" name="discount_amount" placeholder="Total Amount" value="<?php echo $total_qoute_amount;?>">
		            </div>
				</div>
				<br>
				<div class="row col-md-12 center-align">
					<button class="btn btn-success btn-sm col-md-12" type="submit">Upload Quotation </button>
				</div>

    		</div>
    		<div class="col-md-4 ">
    		</div>
    		
    	</div>
    	
    	<!-- <hr>
    	<div class="row">
    		<div class="col-md-6 ">
    			<h4>LPO Details</h4>

				<div class="row center-align">
					<a class="btn btn-primary btn-sm col-md-12 fa fa-print" href="<?php echo base_url();?>inventory/generate-lpo/<?php echo $supplier_order_id;?>" target="_blank"> Generate LPO </a>
				</div>
				<br>
				<div class="form-group">
					<label class="col-lg-5 control-label"> LPO Attachment:</label>
					<div class="col-lg-7">
		            	<input type="file" name="quotation_attachment">
		            </div>
				</div>
				<br>
				<div class="row col-md-12 center-align">
					<button class="btn btn-success btn-sm " type="submit">Upload LPO </button>
				</div>
			</div>
    		<div class="col-md-6">
    			<h4 class="center-align"><strong>Complete Transaction</strong></h4>
    			<br>
    			<div class="col-md-2 ">
    			</div>
    			<div class="col-md-8 ">
    				<div class="form-group">
						<label class="col-lg-5 control-label"> Invoice Attachment:</label>
						<div class="col-lg-7">
			            	<input type="file" name="invoice_attachment">
			            </div>
					</div>
    				<div class="row center-align">
    					<a class="btn btn-success btn-sm col-md-12" href="<?php echo base_url();?>inventory/send-for-approval/" > Mark Transaction AS Completed </a>
    				</div>
    				
				</div>
				<div class="col-md-2 ">
				</div>
			</div>
    	</div> -->
    </div>
</section>