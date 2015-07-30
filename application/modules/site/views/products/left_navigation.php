<?php
	$categories = '';
	if($parent_categories->num_rows() > 0)
	{
		$count = 0;
		$parents = $parent_categories->result();
		
		foreach($parents as $par)
		{
			$count++;
			$category_id = $par->category_id;
			$category_name = $par->category_name;
			$total_products = $this->users_model->count_items('product, category', 'product.category_id = category.category_id AND (category.category_id = '.$category_id.' OR category.category_parent = '.$category_id.')');
			$categories .= '<li > <a href="'.site_url().'products/category/'.$category_id.'"> '.$category_name.' ('.$total_products.') </a> </li>';
		}
	}
?>
<style type="text/css">
	div.brand-checkbox input[type="checkbox"] {
		display: none;
		margin: 4px 0 0 -20px;
	}
</style>
    
    <div class="col-md-3">
        <aside class="main-sidebar right-sidebar">
            <div class="widget-item">
                <form action="#" class="row no-padding">
                    <p class="col-md-12">
                        <i class="icon-335 text-dark-blue to-right"></i>
                        <input type="text" class="round-corners" name="demo_input" placeholder="Search">
                    </p>
                </form>
            </div> <!-- /.widget-item -->

            <div class="widget-item">
                <h4>Categories</h4>

                <ul class="clean-list">
                    <?php echo $categories;?>
                </ul>
            </div> <!-- /.widget-item -->

            <div class="widget-item">
                <h4>Filter price</h4>

                <ul class="clean-list">
                    <form action="<?php echo site_url().'products/price_range';?>" id="filter_price">
						<?php echo $price_range;?>
                        <button type="submit" class="button-sm blue text-dark-blue hover-dark-blue col-md-12">Filter Price</button>
                    </form>
                    <hr>
                    <p>Enter a Price range </p>
                    <form class="form-inline price_range" role="form" action="<?php echo site_url().'products/price_range';?>" id="filter_custom_price">
                        <div class="form-group">
                        	<input type="text" class="form-control" name="start_price" placeholder="2000">
                        </div>
                        <div style="margin:0 auto; text-align:center;"> - </div>
                        <div class="form-group" style="margin-bottom:10px;">
                        	<input type="text" class="form-control" name="end_price" placeholder="3000">
                        </div>
                        <button type="submit" class="button-sm blue text-dark-blue hover-dark-blue col-md-12">Filter</button>
                    </form>
                </ul>
            </div> <!-- /.widget-item -->
            
            <div class="widget-item">
                <ul class="row no-padding social-widget">
                    <li class="col-md-4 col-xs-4">
                        <a href="http://www.facebook.com/InchesToStyle" class="shape-square social-facebook"><i class="icon-161 font-2x"></i></a>
                        <span>476</span>
                    </li>
                    <li class="col-md-4 col-xs-4">
                        <a href="https://twitter.com/InchesToStyle" class="shape-square social-twitter"><i class="icon-157 font-2x"></i></a>
                        <span>137</span>
                    </li>
                    <li class="col-md-4 col-xs-4">
                        <a href="http://pinterest.com/audreym23" class="shape-square social-pinterest"><i class="icon-107 font-2x"></i></a>
                        <span>19</span>
                    </li>									
                </ul>
            </div>
            
        </aside>
    </div>
    
<script type="text/javascript">

//Sort by price range
$(document).on("submit","form#filter_price",function(e)
{
	e.preventDefault();
	
	var range = $('input[name="agree"]:checked').val();
	
	window.location.href = '<?php echo site_url();?>products/price-range/'+range;
});

//Sort by custom price range
$(document).on("submit","form#filter_custom_price",function(e)
{
	e.preventDefault();
	
	var start = $('input[name="start_price"]').val();
	var end = $('input[name="end_price"]').val();
	
	window.location.href = '<?php echo site_url();?>products/price-range/'+start+'-'+end;
});
</script>