<?php
if($products->num_rows() > 0)
{
	$result = '';
	
    foreach($products->result() as $cat)
	{
        $product_code = $cat->product_code;
        $product_year = $cat->product_year;
        $product_id = $cat->product_id;
        $product_description = $cat->product_description;
		$mini_desc = implode(' ', array_slice(explode(' ', $product_description), 0, 10));
        $product_selling_price = number_format($cat->product_selling_price, 0, '.', ',');
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
		$prod_name = $brand.' '.$model.' '.$category_name;
		$product_web_name = $this->site_model->create_web_name($product_code.' '.$prod_name);
		$image = $this->site_model->image_display($products_path, $products_location, $product_image_name);
		
		if($product_year == '0000')
		{
			$product_year = '';
		}
		
		$result .= '
								<div class="result-item format-standard">
                                	<div class="result-item-image">
                                		<a href="'.site_url().$product_web_name.'" class="media-box" title="'.$prod_name.'"><img src="'.$image.'" alt="'.$prod_name.'"></a>
                                        <span class="label label-default vehicle-age">'.$product_year.'</span>
                                        <div class="result-item-view-buttons">
                                        	<a href="'.site_url().$product_web_name.'"style="background-color:#4E69A2; color:#fff;" onclick="facebook_share(\''.$product_image_name.'\', \''.$brand.'\', \''.$model.'\', \''.$category_name.'\', \''.$product_selling_price.'\', \''.$tiny_url.'\')"><i class="fa fa-facebook-square"></i> Like</a>
                                        	<a href="'.site_url().$product_web_name.'"><i class="fa fa-plus"></i> View details</a>
                                        </div>
                                    </div>
                                	<div class="result-item-in">
                                     	<h4 class="result-item-title"><a href="'.site_url().$product_web_name.'" title="'.$prod_name.'">'.$prod_name.'</a></h4>
                                		<div class="result-item-cont">
                                            <div class="result-item-block col1">
                                                <p>'.$mini_desc.'</p>
                                            </div>
                                            <div class="result-item-block col2">
                                                <div class="result-item-pricing">
                                                    <div class="price">Kes '.$product_selling_price.'</div>
                                                </div>
                                                <div class="result-item-action-buttons">
                                                    <a href="#" class="btn btn-default btn-sm"><i class="fa fa-star-o"></i> Save</a>
                                                    <a href="'.site_url().$product_web_name.'" class="btn btn-default btn-sm">Enquire</a><br>
                                                    <a href="#" class="distance-calc"><i class="fa fa-map-marker"></i> Distance from me?</a>
                                                </div>
                                            </div>
                                       	</div>
                                        <div class="result-item-features">
                                            <ul class="inline">
                                                <li>'.$customer_name.'</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
	';
	}
}

else
{
	$result = 'There are no products :-(';
}
?>
	<!-- Start Page header -->
    <div class="page-header parallax" style="background-image:url(<?php echo base_url();?>assets/images/rims.jpg);">
    	<div class="container">
        	<h1 class="page-title"><?php echo $title;?></h1>
       	</div>
    </div>
    
    <!-- Utiity Bar -->
    <div class="utility-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-8 col-sm-6 col-xs-8">
                    <ol class="breadcrumb">
                    	<?php echo $this->site_model->get_breadcrumbs();?>
                    </ol>
            	</div>
                <div class="col-md-4 col-sm-6 col-xs-4">
                </div>
            </div>
      	</div>
    </div>
    
    <!-- Actions bar -->
    <div class="actions-bar tsticky">
     	<div class="container">
        	<div class="row">
            	<div class="col-md-3 col-sm-3 search-actions">
                	<ul class="utility-icons tools-bar">
                    	<li><a href="#"><i class="fa fa-star-o"></i></a>
                        	<div class="tool-box">
                            	<div class="tool-box-head">
                            		<a href="#" class="basic-link pull-right">View all</a>
                            		<h5>Saved spareparts</h5>
                                </div>
                                <div class="tool-box-in">
                                	<ul class="listing tool-car-listing">
                                    	<li>
                                        	<div class="checkb"><input type="checkbox"></div>
                                            <div class="imageb"><a href="#"><img src="http://placehold.it/500?text=saved+part/E67558/ffffff" alt=""></a></div>
                                            <div class="textb">
                                            	<a href="#">Nissan Terrano gear box</a>
                                                <span class="price">Kes 25, 000</span>
                                           	</div>
                                            <div class="delete"><a href="#"><i class="icon-delete"></i></a></div>
                                        </li>
                                    	<li>
                                        	<div class="checkb"><input type="checkbox"></div>
                                            <div class="imageb"><a href="#"><img src="http://placehold.it/500?text=saved+part/E67558/ffffff" alt=""></a></div>
                                            <div class="textb">
                                            	<a href="#">Mercedes Benz E class rims</a>
                                                <span class="price">Kes 76, 000</span>
                                           	</div>
                                            <div class="delete"><a href="#"><i class="icon-delete"></i></a></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tool-box-foot">
                                	<a href="#" class="btn btn-xs btn-primary pull-right">Join</a>
                                	<a href="#" class="pull-right tool-signin" data-toggle="modal" data-target="#loginModal">Sign in</a>
                                	<a href="#" class="btn btn-xs btn-info">Compare(2)</a>
                                </div>
                            </div>
                        </li>
                    	<li><a href="#"><i class="fa fa-folder-o"></i></a>
                        	<div class="tool-box">
                            	<div class="tool-box-head">
                            		<a href="user-dashboard-saved-searches.html" class="basic-link pull-right">View all</a>
                            		<h5>Saved searches</h5>
                                </div>
                                <div class="tool-box-in">
                                	<ul class="listing tool-search-listing">
                                    	<li>
                                        	<div class="link"><a href="#">Search name</a></div>
                                            <div class="delete"><a href="#"><i class="icon-delete"></i></a></div>
                                        </li>
                                    	<li>
                                        	<div class="link"><a href="#">Search name</a></div>
                                            <div class="delete"><a href="#"><i class="icon-delete"></i></a></div>
                                        </li>
                                    	<li>
                                        	<div class="link"><a href="#">Search name</a></div>
                                            <div class="delete"><a href="#"><i class="icon-delete"></i></a></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tool-box-foot">
                                	<a href="#" class="btn btn-xs btn-primary pull-right">Join</a>
                                	<a href="#" class="pull-right tool-signin" data-toggle="modal" data-target="#loginModal">Sign in</a>
                                </div>
                            </div>
                        </li>
                    	<li><a href="#"><i class="fa fa-clock-o"></i></a>
                        	<div class="tool-box">
                            	<div class="tool-box-head">
                            		<h5>Recently viewed spareparts</h5>
                                </div>
                                <div class="tool-box-in">
                                	<ul class="listing tool-view-listing">
                                    	<li>
                                            <div class="imageb"><a href="#"><img src="http://placehold.it/500?text=recent+part/E67558/ffffff" alt=""></a></div>
                                            <div class="textb">
                                            	<a href="#">Nissan Terrano first hand brake</a>
                                                <span class="price">Kes 28, 000</span>
                                           	</div>
                                            <div class="save"><a href="#"><i class="fa fa-star-o"></i></a></div>
                                        </li>
                                    	<li>
                                            <div class="imageb"><a href="#"><img src="http://placehold.it/500?text=recent+part/E67558/ffffff" alt=""></a></div>
                                            <div class="textb">
                                            	<a href="#">Mercedes Benz E class rims</a>
                                                <span class="price">Kes 76, 000</span>
                                           	</div>
                                            <div class="save"><a href="#"><i class="fa fa-star-o"></i></a></div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tool-box-foot">
                                	<a href="#" class="btn btn-xs btn-primary pull-right">Join</a>
                                	<a href="#" class="pull-right tool-signin" data-toggle="modal" data-target="#loginModal">Sign in</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="btn-group pull-right results-sorter">
                        <button type="button" class="btn btn-default listing-sort-btn">Sort by</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                          	<li><a href="#">Price (High to Low)</a></li>
                          	<li><a href="#">Price (Low to High)</a></li>
                          	<li><a href="#">Newness (Low to High)</a></li>
                          	<li><a href="#">Newness (High to Low)</a></li>
                        </ul>
                  	</div>
                    
                    <div class="toggle-view view-count-choice pull-right">
                        <label>Show</label>
                        <div class="btn-group">
                            <a href="#" class="btn btn-default">10</a>
                            <a href="#" class="btn btn-default active">20</a>
                            <a href="#" class="btn btn-default">50</a>
                        </div>
                    </div>
                    
                    <div class="toggle-view view-format-choice pull-right">
                        <label>View</label>
                        <div class="btn-group">
                            <a href="#" class="btn btn-default active" id="results-list-view"><i class="fa fa-th-list"></i></a>
                            <a href="#" class="btn btn-default" id="results-grid-view"><i class="fa fa-th"></i></a>
                        </div>
                    </div>
                    <!-- Small Screens Filters Toggle Button -->
                    <button class="btn btn-default visible-xs" id="Show-Filters">Search Filters</button> 
                </div>
            </div>
      	</div>
    </div>
    
    <!-- Start Body Content -->
  	<div class="main" role="main">
    	<div id="content" class="content full">
        	<div class="container">
            	<div class="row">
                    <!-- Search Filters -->
                    <div class="col-md-3 search-filters" id="Search-Filters">
                    	<div class="tbsticky filters-sidebar">
                            <h3>Refine Search</h3>
                            <div class="accordion" id="toggleArea">
                                <!-- Filter by category -->
                                <div class="accordion-group">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_category">Category <i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_category" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <ul class="filter-options-list list-group">
                                                <?php echo $categories;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter by sub category -->
                                <div class="accordion-group">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_sub_category">Sub category <i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_sub_category" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <ul class="filter-options-list list-group" id="category_children_display">
                                                <?php echo $children;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Filter by Make -->
                                <div class="accordion-group panel">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_brand">Make<i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_brand" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <ul class="filter-options-list list-group">
                                                <?php echo $brands;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Filter by Model -->
                                <div class="accordion-group">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_model">Model <i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_model" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <ul class="filter-options-list list-group" id="brand_model_display">
                                                <?php echo $models;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Filter by Year -->
                                <!--<div class="accordion-group panel">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_year">Year<i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_year" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <div class="form-inline">
  												<div class="form-group">
                                                    <label>Min year</label>
                                                    <select class="form-control selectpicker" name="year_from">
                                                        <option value="0">------From------</option>
                                                        <?php echo $year_from;?>
                                                    </select>
                                                </div>
                                                <div class="form-group last-child">
                                                    <label>Max year</label>
                                                    <select class="form-control selectpicker" name="year_to">
                                                        <option value="0">------To------</option>
                                                        <?php echo $year_to;?>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-default btn-sm pull-right">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                                
                                <!-- Filter by location -->
                                <!--<div class="accordion-group">
                                    <div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#collapse_location">Location <i class="fa fa-angle-down"></i> </a> </div>
                                    <div id="collapse_location" class="accordion-body collapse">
                                        <div class="accordion-inner">
                                            <ul class="filter-options-list list-group">
                                                <?php echo $locations;?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                            <!-- End Toggle -->
                            <a href="<?php echo site_url().'spareparts';?>" class="btn-default btn-sm btn"><i class="fa fa-refresh"></i> Reset search</a>
                            <a href="#" class="btn-primary btn-sm btn"><i class="fa fa-folder-o"></i> Save search</a>
                        </div>
                    </div>
                    
                    <!-- Listing Results -->
                    <div class="col-md-9 results-container">
                        <div class="results-container-in">
                        	<div class="waiting" style="display:none;">
                            	<div class="spinner">
                                  	<div class="rect1"></div>
                                  	<div class="rect2"></div>
                                  	<div class="rect3"></div>
                                  	<div class="rect4"></div>
                                  	<div class="rect5"></div>
                                </div>
                            </div>
                        	<div id="results-holder" class="results-list-view">
                            	<?php echo $result;?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="pagination no-margin-top">
                                    <?php if(isset($links)){echo $links;}?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="pull-right">Showing <?php echo $first;?> to <?php echo $last;?> of <?php echo $total;?> results</p>
                            </div>
                        </div> <!--/.Pagination-->
                    </div>
               	</div>
            </div>
        </div>
   	</div>
    <!-- End Body Content -->
    <input type="hidden" id="filter_category_id" value="<?php echo $filter_category_id;?>">
    <input type="hidden" id="filter_brand_id" value="<?php echo $filter_brand_id;?>">
    <input type="hidden" id="category_filter" value="<?php echo $category_filter;?>">

<script type="text/javascript">
//Sort Products
$(document).on("change","select#sort_products",function()
{
	var order_by = $('#sort_products :selected').val();
	
	window.location.href = '<?php echo site_url();?>products/sort-by/'+order_by;
});

$(document).ready(function()
{
	//get sub categories
	var category_id = $('#filter_category_id').val();
	
	if(category_id != '')
	{
		$.ajax({
			url: '<?php echo site_url();?>site/get_sub_categories/'+category_id,
			processData: false,
			contentType: false,
			type: 'GET',
			dataType: "json",
			success: function(data)
			{
				$('#category_children_display').html(data.children);
			}
		});
	}
	
	//get models
	var brand_id = $('#filter_brand_id').val();
	var category = $('#category_filter').val();
	
	if(brand_id != '')
	{
		$.ajax({
			url: '<?php echo site_url();?>site/get_brand_models/'+brand_id+'/'+category,
			processData: false,
			contentType: false,
			type: 'GET',
			dataType: "json",
			success: function(data)
			{
				$('#brand_model_display').html(data.models);
			}
		});
	}
});
</script>
    
    