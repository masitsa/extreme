
  <!-- Main component call to action -->
  
  <div class="row">
    <div class="breadcrumbDiv col-lg-12">
      <ul class="breadcrumb">
      	<?php
        	$total_crumbs = count($crumbs);
			
			if($total_crumbs > 0)
			{
				for($r = 0; $r < $total_crumbs; $r++)
				{
					$class = '';
					if($r == ($total_crumbs - 1))
					{
						$class = 'class="active"';
					}
					echo '<li '.$class.'><a href="'.site_url().$crumbs[$r]['link'].'">'.$crumbs[$r]['name'].'</a> </li>';
				}
			}
		?>
      </ul>
    </div>
  </div>  <!-- /.row  --> 