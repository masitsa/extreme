<?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$email2 = $contacts['email'];
		$facebook = $contacts['facebook'];
		$twitter = $contacts['twitter'];
		$linkedin = $contacts['linkedin'];
		$logo = $contacts['logo'];
		$company_name = $contacts['company_name'];
		$phone = $contacts['phone'];
		
		if(!empty($facebook))
		{
			$facebook = '<li class="facebook"><a href="'.$facebook.'" target="_blank" title="Facebook">Facebook</a></li>';
		}
		
	}
	else
	{
		$email = '';
		$facebook = '';
		$twitter = '';
		$linkedin = '';
		$logo = '';
		$company_name = '';
		$google = '';
	}
?>
			<header id="header">
				<div class="container">
					<div class="logo">
                    	<a href="<?php echo site_url();?>">
                        	<img src="<?php echo base_url().'assets/logo/'.$logo;?>" class="img-responsive" alt="<?php echo $company_name;?>" data-sticky-width="82" data-sticky-height="40">
                        </a>
					</div>
					<div class="search">
						<form id="searchForm" action="page-search-results.html" method="get">
							<div class="input-group">
								<input type="text" class="form-control search" name="q" id="q" placeholder="Search..." required>
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</form>
					</div>
					<nav class="nav-top">
						<ul class="nav nav-pills nav-top">
							<li>
								<a href="<?php echo site_url().'about-us';?>"><i class="fa fa-angle-right"></i> About Us</a>
							</li>
							<li>
								<a href="<?php echo site_url().'contact-us';?>"><i class="fa fa-angle-right"></i> Contact Us</a>
							</li>
							<li class="phone">
								<span><i class="fa fa-phone"></i> <?php echo $phone;?></span>
							</li>
						</ul>
					</nav>
					<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
						<i class="fa fa-bars"></i>
					</button>
				</div>
				<div class="navbar-collapse nav-main-collapse collapse">
					<div class="container">
						<ul class="social-icons">
							<?php echo $facebook;?>
						</ul>
						<nav class="nav-main mega-menu">
							<ul class="nav nav-pills nav-main" id="mainMenu">
								<?php echo $this->site_model->get_navigation();?>
							</ul>
						</nav>
					</div>
				</div>
			</header>