<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/microfinance/controllers/microfinance.php";

class Savings_plan extends microfinance 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the savings_plan
	*
	*/
	public function index($order = 'savings_plan_name', $order_method = 'ASC') 
	{
		$where = 'savings_plan.compounding_period_id = compounding_period.compounding_period_id';
		$table = 'savings_plan, compounding_period';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'microfinance/'.$order.'/'.$order_method;
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->savings_plan_model->get_all_savings_plan($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Savings plan';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('savings_plan/all_savings_plan', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new savings_plan
	*
	*/
	public function add_savings_plan() 
	{
		//form validation rules
		$this->form_validation->set_rules('savings_plan_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('savings_plan_min_opening_balance', 'Minimum opening balance', 'required|xss_clean');
		$this->form_validation->set_rules('savings_plan_min_account_balance', 'Minimum_account_balance', 'required|xss_clean');
		$this->form_validation->set_rules('charge_withdrawal', 'Charge withdrawal', 'xss_clean');
		$this->form_validation->set_rules('compounding_period_name', 'Compounding period', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$savings_plan_id = $this->savings_plan_model->add_savings_plan();
			if($savings_plan_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Individual added successfully");
				redirect('microfinance/savings_plan');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add savings_plan. Please try again");
			}
		}
		
		$v_data['compounding_period'] = $this->savings_plan_model->get_compounding_periods();
		$data['title'] = 'Add savings plan';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('savings_plan/add_savings_plan', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function edit_savings_plan($savings_plan_id) 
	{	
		//form validation rules
		$this->form_validation->set_rules('savings_plan_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('savings_plan_min_opening_balance', 'Minimum opening balance', 'required|xss_clean');
		$this->form_validation->set_rules('savings_plan_min_account_balance', 'Minimum_account_balance', 'required|xss_clean');
		$this->form_validation->set_rules('charge_withdrawal', 'Charge withdrawal', 'xss_clean');
		$this->form_validation->set_rules('compounding_period_name', 'Compounding period', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			//update savings_plan
			if($this->savings_plan_model->update_savings_plan($savings_plan_id))
			{
				$this->session->set_userdata('success_message', 'Individual updated successfully');
				redirect('microfinance/savings_plan');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update savings_plan. Please try again');
			}
		}
		
		//open the add new savings_plan
		$data['title'] = 'Edit savings_plan';
		$v_data['title'] = $data['title'];
		
		$v_data['savings_plan_id'] = $savings_plan_id;
		$v_data['savings_plan'] = $this->savings_plan_model->get_savings_plan($savings_plan_id);
		$data['content'] = $this->load->view('savings_plan/edit_savings_plan', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function delete_savings_plan($savings_plan_id)
	{
		if($this->savings_plan_model->delete_savings_plan($savings_plan_id))
		{
			$this->session->set_userdata('success_message', 'Individual has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Individual could not deleted');
		}
		redirect('microfinance/savings_plan');
	}
    
	/*
	*
	*	Activate an existing savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function activate_savings_plan($savings_plan_id)
	{
		$this->savings_plan_model->activate_savings_plan($savings_plan_id);
		$this->session->set_userdata('success_message', 'Individual activated successfully');
		redirect('microfinance/savings_plan');
	}
    
	/*
	*
	*	Deactivate an existing savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function deactivate_savings_plan($savings_plan_id)
	{
		$this->savings_plan_model->deactivate_savings_plan($savings_plan_id);
		$this->session->set_userdata('success_message', 'Individual disabled successfully');
		redirect('microfinance/savings_plan');
	}
	
	function add_leave()
	{
		$this->form_validation->set_rules('savings_plan_id', 'Individual', 'trim|numeric|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');

		if ($this->form_validation->run() == FALSE)//if there is an invalid item
		{
			$this->calender($_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
		}
		
		else//if the input is valid
		{
			$items = array(
						'savings_plan_id' => $this->input->post("savings_plan_id"),
						'start_date' => $this->input->post("start_date"),
						'end_date' => $this->input->post("end_date"),
						'leave_type_id' => $this->input->post("leave_type_id")
					);
			$result = $this->db->insert("leave_duration", $items);
			
			redirect("administration/calender/".$_SESSION['navigation_id']."/".$_SESSION['sub_navigation_id']);
		}
	}
	
	public function get_section_children($section_id)
	{
		$sub_sections = $this->sections_model->get_sub_sections($section_id);
		
		$children = '';
		
		if($sub_sections->num_rows() > 0)
		{
			foreach($sub_sections->result() as $res)
			{
				$section_id = $res->section_id;
				$section_name = $res->section_name;
				
				$children .= '<option value="'.$section_id.'" >'.$section_name.'</option>';
			}
		}
		
		else
		{
			$children = '<option value="" >--No sub sections--</option>';
		}
		
		echo $children;
	}
}
?>