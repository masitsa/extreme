<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/accounts/controllers/accounts.php";

class Petty_cash extends accounts 
{
	function __construct()
	{
		parent:: __construct();
	}
	
	public function index()
	{
		$data['title'] = $v_data['title'] = 'Petty cash';
		$data['content'] = $this->load->view('petty_cash/statement', '', TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
}
?>