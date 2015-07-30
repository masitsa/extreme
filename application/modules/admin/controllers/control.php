<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Control extends admin 
{
	/*
	*
	*	Show the admin template header
	*
	*/
	public function admin_header() 
	{
		$this->load->view("includes/header");
	}
    
	/*
	*
	*	Show the top navigation
	*
	*/
	public function top_navigation() 
	{
		$this->load->view("includes/top_navigation");
	}
    
	/*
	*
	*	Show the left navigation
	*
	*/
	public function left_navigation() 
	{
		$this->load->view("includes/left_navigation");
	}
    
	/*
	*
	*	Show the bread crumbs
	*
	*/
	public function crumbs() 
	{
		$this->load->view("includes/crumbs");
	}
    
	/*
	*
	*	Load the welcome lightbox
	*
	*/
	public function welcome() 
	{
		$this->load->view("includes/welcome");
	}
    
	/*
	*
	*	Show the notifications menu
	*
	*/
	public function notifications() 
	{
		$this->load->view("includes/notifications");
	}
    
	/*
	*
	*	Load the admin footer
	*
	*/
	public function admin_footer() 
	{
		$this->load->view("includes/footer");
	}
}
?>