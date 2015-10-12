
 <section class="panel">            

    <!-- Widget content -->
    <div class="panel-body">
      <div class="padd">
      	<div class="row">
      		<div class="col-md-12">
      			 <div class="col-md-3">
                    <div class="form-group">
                        
                        	<?php echo $cons_list2;?>
                      
                    </div>
                 </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="quantity<?php echo $prescription_id?>" class='form-control' autocomplete="off" value="<?php echo $quantity?>" />
                    </div>
                 </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        	<?php echo $time_list2;?>
                        
                    </div>
                 </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <?php echo $duration_list2;?>
                    </div>
                 </div>
      		</div>
      	</div>
      	 <input type="hidden" name="hidden_id" value="<?php echo $prescription_id?>" />
         <input type="hidden" name="v_id" value="<?php echo $visit_id;?>"/>
      	<br>
      	<div class="row">
      		<div class="col-md-12 center-align">
      			<button type="submit" name="update" class="btn btn-sm btn-warning">Update Prescription</button>	<a class="btn btn-sm btn-success" href="#">Print Label</a>
      		</div>
      	</div>
      </div>
    </div>
</section>