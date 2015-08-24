<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->load->view('includes/header');?>
</head>

<body>
	<input type="hidden" id="config_url" value="<?php echo site_url();?>"/>
	<?php echo $this->load->view('includes/navigation');?>

    <!-- Main content starts -->
    
    <div class="content">
    
        <?php echo $content;?>
        
        <!-- Mainbar ends -->	    	
        <div class="clearfix"></div>
    
    </div>
    <!-- Content ends -->

	<?php echo $this->load->view('includes/footer');?>
</body>
</html>