
    	<!-- Slider -->
        <?php echo $this->load->view('home/slider', '', TRUE); ?>
        
        <!-- Start Body Content -->
		<div class="main" role="main">
			<div id="content" class="content full padding-b0">
				<div class="container">
                	
                    <!-- New Arrivals -->
                    <?php echo $this->load->view('home/latest', '', TRUE); ?>
                    
                    <!-- Featured -->
                    <?php echo $this->load->view('home/blog', '', TRUE); ?>
        
                </div>
            </div>
            
            <!-- Brands -->
            <?php echo $this->load->view('home/brands', '', TRUE); ?>
        </div>