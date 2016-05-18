	<?php 
      
	$item_id = $row->item_id;
	$condition_id = $row->condition_id;
	$inventory_id = $row->inventory_id;
	$usage_status_id = $row->usage_status_id;
	$inventory_description = $row->inventory_description;
	$location_id = $row->location_id;
	$serial_number= $row->serial_number;
	$barcode= $row->barcode_name;      
    echo form_open('inventory/inventory-edit-item/'.$inventory_id.'', array("class" => "form-horizontal", "role" => "form"));
    ?>
    <div class="row">
    
    <div class="col-md-6">
       <div class="form-group">
             <label class="col-lg-4 control-label">Asset Barcode:</label>
                
                <div class="col-lg-8">
                   <input type="text" class="form-control" name="asset_barcode" placeholder="Asset Barcode" value="<?php echo $barcode;?>">
                </div>
         </div>
         <div class="form-group">
             <label class="col-lg-4 control-label">Serial Number:</label>
                
            <div class="col-lg-8">
                   <input type="text" class="form-control" name="serial_number" placeholder="Serial Number" value="<?php echo $serial_number;?>">
            </div>
          </div>
          <div class="form-group">
             <label class="col-lg-4 control-label">Location: </label>
                
                <div class="col-lg-8">
                   <select name="location_id" id="location_id" class="form-control">
                        <?php
                        echo '<option value="0">No Location </option>';
                        if($all_locations->num_rows() > 0)
                        {
                            $result = $all_locations->result();
                            
                            foreach($result as $res)
                            {
                                if($res->location_id == $location_id)
                                {
                                    echo '<option value="'.$res->location_id.'" selected>'.$res->location_name.' '.$res->location_id.'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$res->location_id.'">'.$res->location_name.' </option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
             <label class="col-lg-4 control-label">Condition:</label>
                
                <div class="col-lg-8">
                 <select name="condition_id" id="condition_id" class="form-control">
                        <?php
                        echo '<option value="0">No Condition </option>';
                        if($all_conditions->num_rows() > 0)
                        {
                            $result = $all_conditions->result();
                            
                            foreach($result as $res)
                            {
                                if($res->condition_id == $condition_id)
                                {
                                    echo '<option value="'.$res->condition_id.'" selected>'.$res->condition_name.'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$res->condition_id.'">'.$res->condition_name.' </option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                </div>
               <div class="form-group">
             <label class="col-lg-4 control-label">Status:</label>
             <div class="col-lg-8">
                   <select name="status_id" id="status_id" class="form-control">
                        <?php
                        echo '<option value="0">No Status </option>';
                        if($all_status->num_rows() > 0)
                        {
                            $result = $all_status->result();
                            
                            foreach($result as $res)
                            {
                                if($res->item_status_id == $usage_status_id)
                                {
                                    echo '<option value="'.$res->item_status_id.'" selected>'.$res->item_status_name.' '.$res->item_status_id.'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$res->item_status_id.'">'.$res->item_status_name.' </option>';
                                }
                            }
                        }
                        ?>
                    </select>
                    </div>
            </div>
        </div>
    </div>
    <br/>
     <div class="form-group">
              <label class="col-lg-2 control-label">Inventory Description</label>
              <div class="col-lg-10">
                <textarea name="inventory_description" class="form-control"><?php echo $inventory_description;?></textarea>
              </div>
            </div>
    <br>
     <div class="row">   
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Edit Item Inventory
            </button>
        </div>
    </div>
        <?php echo form_close();?>