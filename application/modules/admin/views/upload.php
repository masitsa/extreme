
            <?php echo form_open_multipart('admin/products/do_upload');?>
            
            <!-- Gallery Images -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Gallery Images</label>
                <div class="col-lg-4">
                	<input type="hidden" name="gallery_images" value="1"/>
                	<?php echo form_upload(array( 'name'=>'gallery[]', 'multiple'=>true, 'class'=>'btn'));?>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Add product
                </button>
            </div>
            <br />
            <?php echo form_close();?>