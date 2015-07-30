<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Checkout extends site {
	
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Open the checkout page
	*
	*/
	public function checkout_user()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			redirect('checkout/my-details');
		}
		
		else
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$data['content'] = $this->load->view('checkout/checkout', $v_data, true);
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
	}
    
	/*
	*
	*	Register a front end user
	*
	*/
	public function register()
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('other_names', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean');
		$this->form_validation->set_message('is_unique', 'That email has already been registered. Are you trying to login?');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
			
			$this->checkout_user();
		}
		
		else
		{
			//check if user has valid login credentials
			if($this->users_model->add_frontend_user())
			{
				//check if user has valid login credentials
				$this->login_user(1);
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', 'Could not add a new user. Please try again.');
				
				$this->checkout_user();
			}
		}
	}
    
	/*
	*
	*	Login a front end user
	*
	*/
	public function login_user($page)
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|exists[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_message('exists', 'That email does not exist. Are you trying to sign up?');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
				
			$this->checkout_user();
		}
		
		else
		{
			//check if user has valid login credentials
			if($this->login_model->validate_user())
			{
				if($page == 1)
				{
					redirect('checkout/my-details');
				}
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', 'Could not create a login session. Please login again.');
				$this->checkout_user();
			}
		}
	}
    
	/*
	*
	*	Checkout page user's details
	*
	*/
	public function my_details()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to checkout if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 1;
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('checkout/my_details', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Action of a forgotten password
	*
	*/
	public function forgot_password()
	{
		//form validation rules
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|exists[users.email]');
		$this->form_validation->set_message('exists', 'That email does not exist. Are you trying to sign up?');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$this->load->model('email_model');
			
			//reset password
			if($this->users_model->reset_password($this->input->post('email')))
			{
				$this->session->set_userdata('front_success_message', 'Your password has been reset and mailed to '.$this->input->post('email').'. Please use that password to sign in here');
				
				redirect('checkout');
			}
			
			else
			{
				$this->session->set_userdata('front_error_message', 'Could not add a new user. Please try again.');
			}
		}
		
		else
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		//page datea
		$data['content'] = $this->load->view('checkout/reset_password', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Checkout page delivery method
	*
	*/
	public function delivery()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 2;
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('checkout/delivery', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Checkout page payment options
	*
	*/
	public function payment_options()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 3;
				$data['content'] = $this->load->view('checkout/payment', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Checkout page prder details
	*
	*/
	public function order_details()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$cart_items = $this->cart->total_items();
			
			//go to delivery page if there are items in cart
			if($cart_items > 0)
			{
				//page datea
				$v_data['step'] = 4;
				$data['content'] = $this->load->view('checkout/order', $v_data, true);
			}
			
			//go to account if there are no items in cart
			else
			{
				//page datea
				$v_data['user_details'] = $this->users_model->get_user($this->session->userdata('user_id'));
				$data['content'] = $this->load->view('user/my_account', $v_data, true);
			}
			
			$data['title'] = $this->site_model->display_page_title();
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Confirm order
	*
	*/
	public function confirm_order()
	{
		//user has logged in
		if($this->login_model->check_login())
		{
			$this->load->model('admin/orders_model');
			
			//Required general page data
			$v_data['all_children'] = $this->categories_model->all_child_categories();
			$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
			$v_data['crumbs'] = $this->site_model->get_crumbs();
			
			$data['title'] = $this->site_model->display_page_title();
			
			if($this->cart_model->save_order())
			{
				$data['content'] = $this->load->view('checkout/confirm_message', $v_data, true);
			}
			
			else
			{
				$data['content'] = $this->load->view('checkout/error_message', $v_data, true);
			}
			
			$this->load->view('templates/general_page', $data);
		}
		
		//user has not logged in
		else
		{
			$this->session->set_userdata('front_error_message', 'Please sign up/in to continue');
				
			redirect('checkout');
		}
	}
    
	/*
	*
	*	Add delivery instructions
	*
	*/
	public function add_delivery_instructions()
	{
		//form validation rules
		$this->form_validation->set_rules('delivery_instructions', 'Delivery Instructions', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
		}
		
		else
		{
			$this->session->set_userdata('front_success_message', 'Instructions added successfully');
			$this->session->set_userdata('delivery_instructions', $this->input->post('delivery_instructions'));
		}
		redirect('checkout/delivery');
	}
    
	/*
	*
	*	Add payment options
	*
	*/
	public function add_payment_options()
	{
		//form validation rules
		$this->form_validation->set_rules('radios', 'Payment Method', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('front_error_message', validation_errors());
			redirect('checkout/payment-options');
		}
		
		else
		{
			$this->session->set_userdata('payment_option', $this->input->post('radios'));
			redirect('checkout/order');
		}
	}
}
?>