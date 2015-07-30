<?php
	for($r = 1; $r < 5; $r++)
	{
		if($r == $step)
		{
			$class[$r] = 'active';
		}
		
		else
		{
			$class[$r] = '';
		}
	}
?>
<ul class="orderStep ">
    <li class="<?php echo $class[1];?>"> <a href="<?php echo site_url().'checkout/my-details';?>"> <i class="fa fa-user "></i> <span> customer</span> </a> </li>
    <li class="<?php echo $class[2];?>"> <a href="<?php echo site_url().'checkout/delivery';?>"><i class="fa fa-truck "> </i><span>delivery</span> </a> </li>
    <li class="<?php echo $class[3];?>"> <a href="<?php echo site_url().'checkout/payment';?>"><i class="fa fa-money  "> </i><span>Payment</span> </a> </li>
    <li class="<?php echo $class[4];?>"><a href="<?php echo site_url().'checkout/order';?>"><i class="fa fa-check-square "> </i><span>Order</span></a> </li>
</ul>
<!--/.orderStep end--> 