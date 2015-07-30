<?php 
	
	if(!isset($contacts))
	{
		$contacts = $this->site_model->get_contacts();
	}
	$data['contacts'] = $contacts; 

?>
<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
        <?php echo $this->load->view('admin/includes/header', $contacts, TRUE); ?>
    </head>

	<body>
    	<input type="hidden" id="base_url" value="<?php echo site_url()?>">
    	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
    	<section class="body">
            <!-- Top Navigation -->
            <?php echo $this->load->view('admin/includes/top_navigation', $data, TRUE); ?>
            
            <div class="inner-wrapper">
            	<?php echo $this->load->view('admin/includes/sidebar', '', TRUE); ?>
                
                <section role="main" class="content-body">
                	
            		<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<?php echo $this->admin_model->create_breadcrumbs($title);?>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
                    
					<?php echo $content;?>
                
                </section>
            </div>
            
        </section>
        
        <!-- Vendor -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery/jquery.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-cookie/jquery.cookie.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/js/bootstrap.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/nanoscroller/nanoscroller.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/magnific-popup/magnific-popup.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-appear/jquery.appear.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/flot/jquery.flot.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/flot/jquery.flot.pie.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/flot/jquery.flot.categories.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/raphael/raphael.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/morris/morris.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/gauge/gauge.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/snap-svg/snap.svg.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/liquid-meter/liquid.meter.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/jquery.vmap.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>		
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>	
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>			
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/select2/select2.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.js"></script>
		<script src="<?php echo base_url()."assets/themes/jasny/js/jasny-bootstrap.js";?>"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/theme.init.js"></script>

		<!-- Examples -->
		<script src="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/javascripts/dashboard/examples.dashboard.js"></script>
	</body>
</html>
