<?php
		
	$result = '';
	
	//if users exist display them
	if ($posts->num_rows() > 0)
	{	
		//get all administrators
		$administrators = $this->users_model->get_all_administrators();
		if ($administrators->num_rows() > 0)
		{
			$admins = $administrators->result();
		}
		
		else
		{
			$admins = NULL;
		}
		
		foreach ($posts->result() as $row)
		{
			$post_id = $row->post_id;
			$blog_category_name = $row->blog_category_name;
			$blog_category_id = $row->blog_category_id;
			$post_title = $row->post_title;
			$web_name = $this->site_model->create_web_name($post_title);
			$post_status = $row->post_status;
			$post_views = $row->post_views;
			$image = $row->post_image;
			$created_by = $row->created_by;
			$modified_by = $row->modified_by;
			$comments = $this->users_model->count_items('post_comment', 'post_id = '.$post_id);
			$categories_query = $this->blog_model->get_all_post_categories($blog_category_id);
			$description = $row->post_content;
			$mini_desc = implode(' ', array_slice(explode(' ', strip_tags($description)), 0, 30));
			$created = $row->created;
			$date = date('jS M Y',strtotime($created));
			
			$categories = '';
			$count = 0;
			$image = $this->site_model->image_display($posts_path, $posts_location, $image);
			
			foreach($categories_query->result() as $res)
			{
				$count++;
				$category_name = $res->blog_category_name;
				$category_id = $res->blog_category_id;
				$category_web_name = $this->site_model->create_web_name($category_name);
				
				$categories .= '<a href="'.site_url().'blog/category/'.$category_web_name.'" title="View all posts in '.$category_name.'">'.$category_name.'</a> ';
			}
			
			$result .= 
			'
				<li class="item">
					<div class="post-block format-standard">
						<a href="'.site_url().'blog/'.$web_name.'" class="media-box post-image"><img src="'.$image.'" class="img-responsive" alt="'.$post_title.'"></a>
						<div class="post-actions">
							<div class="post-date">'.$date.'</div>
							<div class="comment-count"><a href="'.site_url().'blog/'.$web_name.'"><i class="icon-dialogue-text"></i> '.$comments.'</a></div>
						</div>
						<h3 class="post-title"><a href="'.site_url().'blog/'.$web_name.'" title="'.$post_title.'">'.$post_title.'</a></h3>
						<div class="post-content">
							<p>'.$mini_desc.'</p>
						</div>
						<div class="post-meta">
							Posted in: '.$categories.'
						</div>
					</div>
				</li>
			';
		}
	}
	
	else
	{
		$result .= "There are no posts :-(";
	}
	
	//product reviews
	$reviews = '';
	if($product_reviews->num_rows() > 0)
	{
		foreach ($product_reviews->result() as $row)
		{
			$product_review_id = $row->product_review_id;
			$product_review_status = $row->product_review_status;
			$product_review_rating = $row->product_review_rating;
			$product_review_reviewer_email = $row->product_review_reviewer_email;
			$product_review_reviewer_name = $row->product_review_reviewer_name;
			$product_review_created = $row->product_review_created;
			$product_review_reviewer_phone = $row->product_review_reviewer_phone;
			$product_review_content = $row->product_review_content;
			$category_name = $row->category_name;
			$model = $row->brand_model_name;
			$brand = $row->brand_name;
			$product_code = $row->product_code;
			$prod_name = $brand.' '.$model.' '.$category_name;
			$product_web_name = $this->site_model->create_web_name($product_code.' '.$prod_name);
			$date = date('jS M Y',strtotime($product_review_created));
			
			$reviews .= '
				<li class="item">
					<div class="post-block post-review-block">
						<div class="review-status">
							<strong>'.$product_review_rating.'</strong>
							<span>Out of 5</span>
						</div>
						<h3 class="post-title"><a href="'.site_url().$product_web_name.'">'.$product_review_content.'</a></h3>
						<div class="post-content">
							<div class="post-actions">
								<div class="post-date">'.$date.'</div>
								<!--<div class="comment-count"><i class="fa fa-thumbs-o-up"></i> 3 <i class="fa fa-thumbs-o-down"></i> 0</div>-->
							</div>
						</div>
					</div>
				</li>
			';
		}
	}
	
	else
	{
		$reviews = 'There are no reviews yet';
	}
		
?>
					<div class="spacer-60"></div>
					<div class="row">
						<!-- Latest News -->
						<div class="col-md-12">
							<section class="listing-block latest-news">
								<div class="listing-header">
									<a href="<?php echo site_url().'blog';?>" class="btn btn-sm btn-default pull-right">All posts</a>
									<h3>Latest from the blog</h3>
								</div>
								<div class="listing-container">
									<div class="carousel-wrapper">
										<div class="row">
											<ul class="owl-carousel" id="news-slider" data-columns="4" data-autoplay="" data-pagination="yes" data-arrows="yes" data-single-item="no" data-items-desktop="4" data-items-desktop-small="3" data-items-tablet="2" data-items-mobile="1">
                                            	<?php echo $result;?>
											</ul>
										</div>
									</div>
								</div>
							</section>
						</div>
                    </div>
                    
                    <div class="row">
                    	<div class="col-md-8 col-sm-6">
                        	<!-- Latest Testimonials -->
							<section class="listing-block latest-testimonials">
								<div class="listing-header">
									<h3>Reviews</h3>
								</div>
								<div class="listing-container">
									<div class="carousel-wrapper">
										<div class="row">
											<ul class="owl-carousel carousel-fw" id="testimonials-slider" data-columns="3" data-autoplay="5000" data-pagination="no" data-arrows="no" data-single-item="no" data-items-desktop="3" data-items-desktop-small="2" data-items-tablet="2" data-items-mobile="1">
												<?php echo $reviews;?>
												
											</ul>
										</div>
									</div>
								</div>
							</section>
						
                        </div>
                        
						<!-- Latest Tweets -->
						<div class="col-md-4 col-sm-6 sidebar">
							
							<!-- Connect with us -->
							<section class="connect-with-us widget-block">
                            
								<h4><i class="fa fa-facebook"></i> Facebook feed</h4>
								<div class="fb-post" data-href="https://www.facebook.com/Autospares.co.ke/posts/1599077417023259" data-width="500"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/Autospares.co.ke/posts/1599077417023259"><p>http://www.autospares.co.ke/view-autopart/276</p>Posted by <a href="https://www.facebook.com/Autospares.co.ke">Autospares Online</a> on&nbsp;<a href="https://www.facebook.com/Autospares.co.ke/posts/1599077417023259">Saturday, 4 July 2015</a></blockquote></div></div>
							</section>
						</div>
					</div>
				