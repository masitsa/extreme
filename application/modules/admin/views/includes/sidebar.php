<?php
	$parents = $this->sections_model->all_parent_sections('section_position');
	$children = $this->sections_model->all_child_sections();
	$sections = '';
	
	if($parents->num_rows() > 0)
	{
		foreach($parents->result() as $res)
		{
			$section_id = $res->section_id;
			$section_name = $res->section_name;
			$section_parent = $res->section_parent;
			$section_icon = $res->section_icon;
			$web_name = strtolower($this->site_model->create_web_name($section_name));
			$link = site_url().$web_name;
			$section_children = $this->admin_model->check_children($children, $section_id, $web_name);
			$total_children = count($section_children);
			
			if($total_children == 0)
			{
				if($title == $section_name)
				{
					$sections .= '<li class="nav-active">';
				}
				
				else
				{
					$sections .= '<li>';
				}
				$sections .= '
					<a href="'.$link.'">
						<!--<span class="pull-right label label-primary">182</span>-->
						<i class="fa fa-'.$section_icon.'" aria-hidden="true"></i>
						<span>'.$section_name.'</span>
					</a>
				</li>
				';
			}
			
			else
			{
				if($title == $section_name)
				{
					$sections .= '<li class="nav-active nav-parent">';
				}
				
				else
				{
					$sections .= '<li class="nav-parent">';
				}
				$sections .= '
					<a>
						<i class="fa fa-'.$section_icon.'" aria-hidden="true"></i>
						<span>'.$section_name.'</span>
					</a>
					<ul class="nav nav-children">';
				
				//children
				for($r = 0; $r < $total_children; $r++)
				{
					$name = $section_children[$r]['section_name'];
					$link = $section_children[$r]['link'];
					
					$sections .= '
						<li>
							<a href="'.$link.'">
								 '.$name.'
							</a>
						</li>
					';
				}
				
				$sections .= '
				</ul></li>
				';
			}
		}
	}
	
?>				
                <!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Navigation
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<?php echo $sections;?>
								</ul>
							</nav>
						</div>
				
					</div>
				
				</aside>
				<!-- end: sidebar -->