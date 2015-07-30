<?php 
	
	if(!isset($contacts))
	{
		$contacts = $this->site_model->get_contacts();
	}
	$data['contacts'] = $contacts; 

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <?php echo $this->load->view('site/includes/header', '', TRUE); ?>
    </head>

	<body>
    	<!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->
    	<div class="body">
            <!-- Top Navigation -->
            <?php echo $this->load->view('site/includes/top_navigation', $data, TRUE); ?>
            
            <?php echo $content;?>
            
            <?php echo $this->load->view('site/includes/footer', $data, TRUE); ?>
        </div>
        
        <!-- Vendor -->
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.appear/jquery.appear.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.easing/jquery.easing.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery-cookie/jquery-cookie.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/bootstrap/bootstrap.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/common/common.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.validation/jquery.validation.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.stellar/jquery.stellar.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jquery.gmap/jquery.gmap.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/isotope/jquery.isotope.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/owlcarousel/owl.carousel.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/jflickrfeed/jflickrfeed.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/vide/vide.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url()."assets/themes/porto/";?>js/theme.js"></script>
		
		<!-- Specific Page Vendor and Views -->
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>vendor/circle-flip-slideshow/js/jquery.flipshow.js"></script>
		<script src="<?php echo base_url()."assets/themes/porto/";?>js/views/view.home.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url()."assets/themes/porto/";?>js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url()."assets/themes/porto/";?>js/theme.init.js"></script>
        
        <script type="text/javascript">
	     
			window.fbAsyncInit = function() {
			FB.init({
				 appId:'<?php echo $this->config->item('appID'); ?>',
				 status     : true, 
				 cookie     : true, 
				 xfbml      : true 
			});   
			};
		
			(function(d){
				 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				 if (d.getElementById(id)) {return;}
						 js = d.createElement('script'); js.id = id; js.async = true;
						 js.src = "//connect.facebook.net/en_US/all.js";
						 ref.parentNode.insertBefore(js, ref);
				 }(document));
		
			/**/
			 
			function fb_auth() 
			{
				 parent.location ='<?php echo site_url(); ?>facebook-registration';
			}
		
			function facebook_auth(){
				 FB.getLoginStatus(function(response) 
				 {
					if (response.status === 'connected') 
					{
						fb_auth();
					} 
					
					else 
					{
						FB.login(function(response) 
						{
							// handle the response
							if(response.authResponse) {
								fb_auth();
							}
						}, {scope: 'email, publish_stream'});/*{scope: 'email,publish_stream'});*/
					}
				 });
			}
		</script>
	</body>
</html>
