<?php
	$product = $product_details->result();
	//the product details
	$sale_price = $product[0]->sale_price;
	$featured = $product[0]->featured;
	$category_id = $product[0]->category_id;
	$brand_id = $product[0]->brand_id;
	$product_id = $product[0]->product_id;
	$product_name = $product[0]->product_name;
	$product_code = $product[0]->product_code;
	$product_buying_price = $product[0]->product_buying_price;
	$product_status = $product[0]->product_status;
	$product_selling_price = $product[0]->product_selling_price;
	$image = $product[0]->product_image_name;
	$thumb = $product[0]->product_thumb_name;
	$product_description = $product[0]->product_description;
	$product_balance = $product[0]->product_balance;
	$brand_name = $product[0]->brand_name;
	$category_name = $product[0]->category_name;
	$mini_desc = implode(' ', array_slice(explode(' ', $product_description), 0, 10));
	
	if($sale_price > 0)
	{
		$selling_price = $product_selling_price - ($product_selling_price * ($sale_price/100));
		$price = '<span class="price-sales">KES '. number_format($selling_price, 0, '.', ',').'</span> <span class="price-standard">KES '. number_format($product_selling_price, 0, '.', ',').'</span> ';
	}
	
	else
	{
		$price = '<span class="price-sales">KES '. number_format($product_selling_price, 0, '.', ',').'</span>';
	}
?>
		<section class="breadcrumbs-box">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h4>Shop</h4>
					</div> <!-- /.md-col-6 -->
					<div class="col-md-6">
						<ul class="clean-list to-right breadcrumb-items">
							<li><a href="<?php echo site_url();?>">Home</a></li>
							<li><a href="<?php echo site_url();?>shop">Shop</a></li>
							<li><span>Product</span></li>
						</ul> <!-- /.breadcrumb-items -->
					</div> <!-- /.md-col-6 -->
				</div> <!-- /.row -->
			</div> <!-- /.container -->
		</section> <!-- /.breadcrumbs-box -->
		
        
		<section class="box white">
			<div class="container">
            	<div class="row">
                	<div class="col-md-12">
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="blog-slider-container">
                                    <div class="blog-slider">
                                        <ul>
                                        	<li>
                                                <figure>
                                                	<a href="#"><img src="<?php echo base_url()."assets/images/products/images/".$image;?>" alt="img"></a>
                                                    <figcaption class="light-blue">
                                                        <a href="<?php echo base_url()."assets/images/products/images/".$image;?>" class="zoom-image" title="Product"></a>
                                                    </figcaption>
                                                </figure>
                                            </li>
                                            
											<?php
                                                if($product_images->num_rows() > 0)
                                                {
                                                    $galleries = $product_images->result();
                                                    
                                                    foreach($galleries as $gal)
                                                    {
                                                        $thumb = $gal->product_image_thumb;
                                                        $image = $gal->product_image_name;
                                                        ?>
                                                        <li>
                                                            <figure>
                                                                <a href="#"><img src="<?php echo base_url()."assets/images/products/gallery/".$image;?>" alt="img"></a>
                                                                <figcaption class="light-blue">
                                                                    <a href="<?php echo base_url()."assets/images/products/gallery/".$image;?>" class="zoom-image" title="Product"></a>
                                                                </figcaption>
                                                            </figure>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div> <!-- /.blog-slider-container -->
                            </div>
                            
                            <div class="col-md-6">
                               	<h4 class="text-red"><?php echo $product_name;?></h4>
								<hr class="text-red">
                                <p><a href="<?php echo site_url().'products/category/'.$category_id?>"><?php echo $category_name;?></a></p>
                                <p><?php echo $price;?></p>
                                
                                <ul class="toggle-horizontal dark-view clean-list">
                                    <li class="row dark-blue">
                                        <input type="checkbox" name="toggle-helper" id="toggle-22">
                                        <label class="uppercase col-md-4 " for="toggle-22"><i class="icon-325"></i>Description</label>
                                        <div class="toggle-content col-md-8">
                                            <?php echo $product_description;?>
                                        </div>
                                    </li>
                                    <li class="row dark-blue">
                                        <input type="checkbox" name="toggle-helper" id="toggle-23">
                                        <label class="uppercase col-md-4 " for="toggle-23"><i class="icon-267"></i>How to order</label>
                                        <div class="toggle-content col-md-8">
                                            <p>
                                                Order by text, call or Whatsup to 0705 925 498<br/>
                                                Email: inchestostyle@gmail.com<br/>
                                                * We offer free delivery for your order
                                            </p>
                                            <p class="text-right">
                                                <a class="button-md dark-red" href="tel:0705925498"><i class="icon-92"></i></a>
                                                <a class="button-md blue" href="mailto:inches2style@gmail.com "><i class="icon-42"></i></a>
                                            </p>
                                        </div>
                                    </li>
                                    <li class="row dark-blue">
                                        <input type="checkbox" name="toggle-helper" id="toggle-24">
                                        <label class="uppercase dark-blue col-md-4 " for="toggle-24"><i class="icon-179"></i>Share</label>
                                        <div class="toggle-content col-md-8">
                                            <p>
                                                Follow us on various social media platforms
                                            </p>
                                            <p class="text-right">
                                                <a class="button-md  blue" href="http://www.facebook.com/InchesToStyle" target="_blank"><i class="icon-161 font-2x"></i></a>
                                                <a class="button-md  blue" href="https://twitter.com/InchesToStyle" target="_blank"><i class="icon-157 font-2x"></i></a>
                                                <a class="button-md  blue" href="http://pinterest.com/audreym23" target="_blank"><i class="icon-107 font-2x"></i></a>
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> <!--/.productFilter-->
                        
                        <h2 class="text-center fancy-heading text-red"><span>You may also like</span></h2>
                        <div id="portfolio-slider">
                            <ul class="clean-list portfolio-items row">
                            	<?php
									if($similar_products->num_rows() > 0)
									{
										$product = $similar_products->result();
										
										foreach($product as $prods)
										{
											$sale_price = $prods->sale_price;
											$thumb = $prods->product_image_name;
											$product_id = $prods->product_id;
											$product_name = $prods->product_name;
											$product_price = $prods->product_selling_price;
											$description = $prods->product_description;
											$mini_desc = implode(' ', array_slice(explode(' ', $description), 0, 10));
											$price = number_format($product_price, 2, '.', ',');
											$sale = '';
											
											if($sale_price > 0)
											{
												$sale = '<div class="promotion"> <span class="discount">'.$sale_price.'% OFF</span> </div>';
											}
											
											echo
											'
                                            <li>
                                                <div>
                                                    <figure>
                                                        <a href="'.site_url().'products/view-product/'.$product_id.'"><img src="'.base_url().'assets/images/products/images/'.$thumb.'" alt="'.$product_name.'"></a>
                                                        <figcaption class="light-blue">
                                                            <a href="'.base_url().'assets/images/products/images/'.$thumb.'" class="zoom-image" title="'.$product_name.'"></a>
                                                        </figcaption>
                                                    </figure>
                                                    <div class="portfolio-title">
                                                        <h5 class="uppercase"><a href="'.site_url().'products/view-product/'.$product_id.'" class="transition-short">'.$product_name.'</a></h5>
                                                    <p>
                                                        <a class="button-sm red text-white hover-dark-red" href="#">Kes '.$price.'</a>
                                                        <a href="'.site_url().'products/view-product/'.$product_id.'" class="read-more button-sm dark-blue hover-blue">View</a>
                                                    </p>
                                                    </div>
                                                </div>
                                            </li>
											';
										}
									}
								?>
                            </ul>
                        </div>
                        
                	</div><!-- /. col-md-12 -->
                    
                </div><!-- /. row -->
			</div> <!-- /.container -->
		</section> <!-- /.components-box -->