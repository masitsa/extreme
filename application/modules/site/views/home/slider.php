<?php
	
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$facebook = $contacts['facebook'];
		$twitter = $contacts['twitter'];
		
		if(!empty($email))
		{
			$email = '<li class="googleplus"><a href="mailto:'.$email.'"><i class="fa fa-envelope-o"></i></a></li>';
		}
		
		if(!empty($facebook))
		{
			$facebook = '<li class="facebook"><a href="'.$facebook.'" target="_blank"><i class="fa fa-facebook"></i></a></li>';
		}
		
		if(!empty($twitter))
		{
			$twitter = '<li class="twitter"><a href="'.$twitter.'" target="_blank"><i class="fa fa-twitter"></i></a></li>';
		}
	}
	else
	{
		$email = '';
		$facebook = '';
		$twitter = '';
	}
?>
		<div class="hero-area">
			<!-- Search Form -->
			<div class="floated">
				<div class="search-form">
					<h2>Looking for spare parts for your car?</h2>
					<p>We have a wide range of dealers and parts for you to choose from.</p>
					<div class="search-form-inner">
						<form>
							<div class="input-group input-group-lg">
								<input type="text" class="form-control" placeholder="Enter category, make, model..">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button">Search</button>
								</span>
							</div>
							<span class="label label-warning pull-right"><?php echo $total_products;?> spares</span>
							<span class="label label-success pull-right"><?php echo $total_categories;?> categories</span>
							<a href="#" class="search-advanced-trigger">Advanced Search <i class="fa fa-arrow-down"></i></a>
						</form>
                        
                        <div class="advanced-search-row">
                            <form action="<?php echo site_url()."search";?>" method="post" class="container">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Car brand</label>
                                                <select class="form-control selectpicker  search_brand_model" id="brand_id" name="brand_id">
                                                    <option value="0">---Select car brand---</option>
                                  					<?php echo $brands;?>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label>Brand model</label>
                                                <select class="form-control selectpicker" id="brand_model_id" name="brand_model_id">
                                                    <option value="0">---Select Brand Model---</option>
                                  					<?php echo $models;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Year from</label>
                                                <select class="form-control selectpicker" name="year_from">
                                                    <option value="0">------From------</option>
                                        			<?php echo $year_from;?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Year to</label>
                                                <select class="form-control selectpicker" name="year_to">
                                                    <option value="0">------To------</option>
                                        			<?php echo $year_to;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Category</label>
                                                <select class="form-control selectpicker  search_category_children" id="category_id" name="category_id">
                                                   	<option value="0">---Select category---</option>
                                  					<?php echo $categories;?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Sub category</label>
                                                <select class="form-control selectpicker" id="category_child" name="category_child">
                                                   	<option value="0">---Select sub category---</option>
                                                    <?php echo $children;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Location</label>
                                                <select class="form-control selectpicker" id="location_id" name="location_id">
                                                   	<option value="0">---Select location---</option>
                                                    <?php echo $locations;?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label></label>
                                                <input type="submit" class="btn btn-block btn-info btn-lg" value="Find my autospares">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
    
                        </div>
					</div>
				</div>
			</div>
			<!-- Start Hero Slider -->
			<div class="hero-slider heroflex flexslider clearfix" data-autoplay="yes" data-pagination="no" data-arrows="yes" data-style="fade" data-speed="7000" data-pause="yes">
				<ul class="slides">
					<li class="parallax" style="background-image:url(<?php echo base_url().'assets/images/';?>multiparts.jpg);"></li>
					<li class="parallax" style="background-image:url(<?php echo base_url().'assets/images/';?>headlights.jpg);"></li>
					<li class="parallax" style="background-image:url(<?php echo base_url().'assets/images/';?>rims.jpg);"></li>
				</ul>
			</div>
			<!-- End Hero Slider -->
		</div>
		<!-- Utiity Bar -->
		<div class="utility-bar">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="utility-icons social-icons social-icons-colored">
							<?php echo $facebook.$twitter.$email;?>
						</ul>
					</div>
				</div>
			</div>
			<div class="by-type-options">
				<div class="container">
					<div class="row">
						<ul class="owl-carousel carousel-alt" data-columns="6" data-autoplay="" data-pagination="no" data-arrows="yes" data-single-item="no" data-items-desktop="6" data-items-desktop-small="4" data-items-mobile="3" data-items-tablet="4">
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/wagon.png" alt=""> <span>Wagon</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/minivan.png" alt=""> <span>Minivan</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/coupe.png" alt=""> <span>Coupe</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/convertible.png" alt=""> <span>Convertible</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/crossover.png" alt=""> <span>Crossover</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/suv.png" alt=""> <span>SUV</span></a></li>
							<li class="item"> <a href="results-list.html#"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/minicar.png" alt=""> <span>Minicar</span></a></li>
							<li class="item"> <a href="results-list.html"><img src="<?php echo base_url()."assets/themes/autostarts/";?>images/body-types/sedan.png" alt=""> <span>Sedan</span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		