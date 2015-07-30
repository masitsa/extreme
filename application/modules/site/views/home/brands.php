<?php
	$brands = '';
	
	if($active_brands->num_rows() > 0)
	{
		foreach($active_brands->result() as $brand)
		{
			$brand_id = $brand->brand_id;
			$brand_name = $brand->brand_name;
			$brand_image = $brand->brand_image_name;
			$brand_web_name = $this->site_model->create_web_name($brand_name);
			$image = $this->site_model->image_display($brands_path, $brands_location, $brand_image);
			
			if($brand_id > 0)
			{
				$brands .= '<li class="item"> <a href="'.site_url().'products/brand/'.$brand_web_name.'"><img src="'.$image.'" alt="'.$brand_name.'" class="img-responsive"></a></li>';
			}
		}
	}
	else
	{
		$brands =  '<li>There are no brands :-(</li>';
	}
?>
  				<div class="spacer-50"></div>
				<div class="lgray-bg make-slider">
					<div class="container">
						<!-- Search by make -->
						<div class="row">
							<div class="col-md-3 col-sm-4">
								<h3>Search by make </h3>
								<a href="<?php echo site_url().'spareparts';?>" class="btn btn-default btn-lg">All make &amp; models</a>
							</div>
							<div class="col-md-9 col-sm-8">
								<div class="row">
									<ul class="owl-carousel" id="make-carousel" data-columns="5" data-autoplay="6000" data-pagination="no" data-arrows="no" data-single-item="no" data-items-desktop="5" data-items-desktop-small="4" data-items-tablet="3" data-items-mobile="3">
										
										<?php echo $brands;?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			