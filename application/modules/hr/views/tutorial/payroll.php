
	<section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Payroll Tutorials</h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <div class="owl-carousel">
                            <?php

		   if($tutorial_id->num_rows() > 0)
		   {
			   foreach ($tutorial_id->result() as $row)
			   {
			   $tutorial_id = $row->tutorial_id;
			   $section_name = $this->tutorial_model->get_section($tutorial_id);
			   }
		   }
			$query = $this->tutorial_model->get_selected_section($tutorial_id);
			if ($query->num_rows()> 0)
			{
				foreach ($query->result() as $row)
				{
				$tutorial_name = $row->tutorial_name;
				$tutorial_description = $row->tutorial_desription;
				$tutorial_image = $row->tutorial_image;
				$section_id = $row->section_id;
					if ($section_id == 135)
					{
					?> 
					<div class="item"><h4><?php echo $tutorial_name;?></h4><?php echo $tutorial_description;										 					?>
                    <img src="<?php echo base_url();?>assets/tutorials/<?php echo $tutorial_image;?>" class="img-responsive" />
                	</div>
				<?php
					}
				}
				echo "There are no tutorials for this section";
				
			}
			else
			{
			echo "There are no tutorials for this section";
			}
					   ?>
						</div>
                     </div>
                 </div>
        
		   
                    