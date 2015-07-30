<?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$facebook = $contacts['facebook'];
		$twitter = $contacts['twitter'];
		$logo = $contacts['logo'];
		$company_name = $contacts['company_name'];
		$phone = $contacts['phone'];
	}
?>

			<footer id="footer">
				<div class="container">
					<div class="row">
						<div class="footer-ribbon">
							<span>Get in Touch</span>
						</div>
						<div class="col-md-6">
							<div class="contact-details">
								<h4 class="heading-primary">Contact Us</h4>
								<ul class="contact">
									<li><p><i class="fa fa-phone"></i> <strong>Phone:</strong> <a href="tel:<?php echo $phone;?>"><?php echo $phone;?></p></li>
									<li><p><i class="fa fa-envelope"></i> <strong>Email:</strong> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></p></li>
								</ul>
							</div>
						</div>
						<div class="col-md-6">
							<h4 class="heading-primary">Follow Us</h4>
							<div class="social-icons">
								<ul class="social-icons">
									<li class="facebook"><a href="<?php echo $facebook;?>" target="_blank" data-placement="bottom" data-tooltip title="Facebook">Facebook</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-copyright">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
								<a href="<?php echo site_url();?>" class="logo">
                                	<img src="<?php echo base_url().'assets/logo/'.$logo;?>" class="img-responsive" alt="<?php echo $company_name;?>"
								</a>
							</div>
							<div class="col-md-7">
								<p>© Copyright <?php echo date('Y');?>. All Rights Reserved.</p>
							</div>
							<div class="col-md-4">
								<nav id="sub-menu">
									<ul>
										<li><a href="<?php echo site_url().'terms-and-conditions'?>">Terms &amp; conditions</a></li>
                                        <li><a href="<?php echo site_url().'privacy-policy'?>">Privacy policy</a></li>
                                        <li><a href="<?php echo site_url().'about'?>">About</a></li>
									</ul>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</footer>