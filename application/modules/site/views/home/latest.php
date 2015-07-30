<?php
	$latest_display = '';
	if($latest->num_rows() > 0)
	{
		$latest_products = $latest->result();
		
		foreach($latest_products as $cat)
		{
			$product_code = $cat->product_code;
			$product_id = $cat->product_id;
			$product_description = $cat->product_description;
			$product_selling_price = $cat->product_selling_price;
			$product_balance = $cat->product_balance;
			$product_status = $cat->product_status;
			$product_image_name = $cat->product_image_name;
			$category_name = $cat->category_name;
			$product_date = date('jS M Y',strtotime($cat->product_date));
			$product_year = $cat->product_year;
			$model = $cat->brand_model_name;
			$brand = $cat->brand_name;
			$location_name = $cat->location_name;
			$customer_name = $cat->customer_name;
			$customer_phone = $cat->customer_phone;
			$customer_email = $cat->customer_email;
			$tiny_url = $cat->tiny_url;
			$mini_desc = implode(' ', array_slice(explode(' ', $product_description), 0, 10));
			$price = number_format($product_selling_price, 0, '.', ',');
			$image = $this->site_model->image_display($products_path, $products_location, $product_image_name);
			
			$prod_name = $brand.' '.$model.' '.$category_name;
			$product_web_name = $this->site_model->create_web_name($product_code.' '.$prod_name);
			$category_web_name = $this->site_model->create_web_name($category_name);
			
			$latest_display .= 
			'
			<li class="item">
				<div class="vehicle-block format-standard">
					<a href="'.site_url().$product_web_name.'" class="media-box">
						<img src="'.$image.'" alt="'.$prod_name.'" class="img-responsive">
					</a>
					<div class="vehicle-block-content">
						<span class="label label-default vehicle-age">'.$product_date.'</span>
						<h5 class="vehicle-title"><a href="'.site_url().$product_web_name.'">'.$prod_name.'</a></h5>
						<span class="vehicle-meta">Listed by <abbr class="user-type" title="Listed by '.$customer_name.' user">'.$customer_name.'</abbr></span>
						<a href="'.site_url().'category/'.$category_web_name.'" title="View all '.$category_name.'s" class="vehicle-body-type"><i class="fa fa-plus"></i></a>
						<span class="vehicle-cost">Kes '.$price.'</span>
					</div>
				</div>
			</li>
			';
		}
	}
	
	else
	{
		$latest_display = '<li>No autoparts have been added yet :-(</li>';
	}
?>
        
        			<div class="spacer-10"></div>
					<!-- Recently Listed Vehicles -->
					<section class="listing-block recent-vehicles">
						<div class="listing-header">
                        	<a href="<?php echo site_url().'spareparts';?>" class="btn btn-sm btn-default pull-right">All spares</a>
							<h3>Recently listed parts</h3>
						</div>
						<div class="listing-container">
							<div class="carousel-wrapper">
								<div class="row">
									<ul class="owl-carousel carousel-fw" id="vehicle-slider" data-columns="4" data-autoplay="" data-pagination="yes" data-arrows="no" data-single-item="no" data-items-desktop="4" data-items-desktop-small="3" data-items-tablet="2" data-items-mobile="1">
                                    	
										<?php echo $latest_display;?>
										
									</ul>
								</div>
							</div>
						</div>
					</section>
					