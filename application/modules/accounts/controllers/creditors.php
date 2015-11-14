<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/accounts/controllers/accounts.php";

class Creditors extends accounts 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('creditors_model');
	}
	
	public function index()
	{
		$branch_code = $this->session->userdata('search_branch_code');
		
		if(empty($branch_code))
		{
			$branch_code = $this->session->userdata('branch_code');
		}
		
		$this->db->where('branch_code', $branch_code);
		$query = $this->db->get('creditor');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$creditor_name = $row->creditor_name;
		}
		
		else
		{
			$creditor_name = '';
		}
		$where = 'creditor.branch_code = \''.$branch_code.'\'';
		$search = $this->session->userdata('search_hospital_creditors');
		
		$where .= $search;
		
		$table = 'creditor';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'creditors/creditors';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 40;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["page"] = $page;
        $v_data["links"] = $this->pagination->create_links();
		$v_data['query'] = $this->creditors_model->get_all_creditors($table, $where, $config["per_page"], $page);
		$data['title'] = $v_data['title'] = 'Creditors';
		$data['content'] = $this->load->view('creditors/creditors', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_hospital_creditors()
	{
		$creditor_name = $this->input->post('creditor_name');
		
		if(!empty($creditor_name))
		{
			$this->session->set_userdata('search_hospital_creditors', ' AND creditor.creditor_name LIKE \'%'.$creditor_name.'%\'');
		}
		
		redirect('creditors/hospital-creditors');
	}
	
	public function close_search_hospital_creditors()
	{
		$this->session->unset_userdata('search_hospital_creditors');
		
		redirect('creditors/hospital-creditors');
	}
    
	/*
	*
	*	Add a new creditor
	*
	*/
	public function add_creditor() 
	{
		//form validation rules
		$this->form_validation->set_rules('creditor_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('creditor_email', 'Email', 'xss_clean');
		$this->form_validation->set_rules('creditor_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('creditor_location', 'Location', 'xss_clean');
		$this->form_validation->set_rules('creditor_building', 'Building', 'xss_clean');
		$this->form_validation->set_rules('creditor_floor', 'Floor', 'xss_clean');
		$this->form_validation->set_rules('creditor_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('creditor_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('creditor_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_name', 'Contact Name', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_onames', 'Contact Other Names', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_phone1', 'Contact Phone 1', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_phone2', 'Contact Phone 2', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_email', 'Contact Email', 'valid_email|xss_clean');
		$this->form_validation->set_rules('creditor_description', 'Description', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$creditor_id = $this->creditors_model->add_creditor();
			if($creditor_id > 0)
			{
				$this->session->set_userdata("success_message", "Creditor added successfully");
				redirect('accounts/creditors');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add creditor. Please try again");
				redirect('accounts/creditors/add_creditor');
			}
		}
		$data['title'] = 'Add creditor';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('creditors/add_creditor', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new creditor
	*
	*/
	public function edit_creditor($creditor_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('creditor_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('creditor_email', 'Email', 'xss_clean');
		$this->form_validation->set_rules('creditor_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('creditor_location', 'Location', 'xss_clean');
		$this->form_validation->set_rules('creditor_building', 'Building', 'xss_clean');
		$this->form_validation->set_rules('creditor_floor', 'Floor', 'xss_clean');
		$this->form_validation->set_rules('creditor_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('creditor_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('creditor_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_name', 'Contact Name', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_onames', 'Contact Other Names', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_phone1', 'Contact Phone 1', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_phone2', 'Contact Phone 2', 'xss_clean');
		$this->form_validation->set_rules('creditor_contact_person_email', 'Contact Email', 'valid_email|xss_clean');
		$this->form_validation->set_rules('creditor_description', 'Description', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$creditor_id = $this->creditors_model->edit_creditor($creditor_id);
			if($creditor_id > 0)
			{
				$this->session->set_userdata("success_message", "Creditor updated successfully");
				redirect('accounts/creditors');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add creditor. Please try again");
				redirect('accounts/creditors/add_creditor');
			}
		}
		$data['title'] = 'Add creditor';
		$v_data['title'] = $data['title'];
		$v_data['creditor'] = $this->creditors_model->get_creditor($creditor_id);
		$data['content'] = $this->load->view('creditors/edit_creditor', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function add_amount()
	{
		//form validation rules
		$this->form_validation->set_rules('creditor_name', 'Account', 'required|xss_clean');
		$this->form_validation->set_rules('creditor_status', 'Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->creditors_model->add_creditor())
			{
				$this->session->set_userdata('success_message', 'Account added successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add creditor. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('creditors/hospital-creditors');
	}
	
	public function statement($creditor_id, $date_from = NULL, $date_to = NULL)
	{
		$where = 'creditor_account.transaction_type_id = transaction_type.transaction_type_id AND creditor_account.creditor_id = '.$creditor_id;
		$table = 'creditor_account, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (creditor_account.creditor_account_date >= \''.$date_from.'\' AND creditor_account.creditor_account_date <= \''.$date_to.'\')';
			$search_title = 'Statement from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$where .= ' AND creditor_account.creditor_account_date = \''.$date_from.'\'';
			$search_title = 'Statement of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$where .= ' AND creditor_account.creditor_account_date = \''.$date_to.'\'';
			$search_title = 'Statement of '.date('jS M Y', strtotime($date_to)).' ';
			$date_from = $date_to;
		}
		
		else
		{
			$where .= ' AND DATE_FORMAT(creditor_account.creditor_account_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(creditor_account.creditor_account_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Statement for the month of '.date('M Y').' ';
			$date_from = date('Y-m-d');
		}
		//echo $where;die();
		$v_data['balance_brought_forward'] = $this->creditors_model->calculate_balance_brought_forward($date_from);
		$creditor = $this->creditors_model->get_creditor($creditor_id);
		$row = $creditor->row();
		$creditor_name = $row->creditor_name;
		$v_data['module'] = 1;
		$v_data['creditor_name'] = $creditor_name;
		$v_data['creditor_id'] = $creditor_id;
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['query'] = $this->creditors_model->get_creditor_account($where, $table);
		$v_data['title'] = $creditor_name.' '.$search_title;
		$data['title'] = $creditor_name.' Statement';
		$data['content'] = $this->load->view('creditors/statement', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function record_creditor_account($creditor_id)
	{
		$this->form_validation->set_rules('transaction_type_id', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('creditor_account_description', 'Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('creditor_account_amount', 'Amount', 'trim|required|xss_clean');
		
		if ($this->form_validation->run())
		{
			if($this->creditors_model->record_creditor_account($creditor_id))
			{
				$this->session->set_userdata('success_message', 'Record saved successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to save. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('accounts/creditors/statement/'.$creditor_id.'');
	}
	
	public function search_creditor_account($creditor_id)
	{
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(!empty($date_from) && !empty($date_to))
		{
			redirect('accounts/creditors/statement/'.$creditor_id.'/'.$date_from.'/'.$date_to);
		}
		
		else if(!empty($date_from))
		{
			redirect('accounts/creditors/statement/'.$creditor_id.'/'.$date_from);
		}
		
		else if(!empty($date_to))
		{
			redirect('accounts/creditors/statement/'.$creditor_id.'/'.$date_to);
		}
		
		else
		{
			redirect('accounts/creditors/statement/'.$creditor_id);
		}
	}
	
	public function print_creditor_account($creditor_id, $date_from = NULL, $date_to = NULL)
	{
		$where = 'creditor_account.transaction_type_id = transaction_type.transaction_type_id AND creditor_account.creditor_id = '.$creditor_id;
		$table = 'creditor_account, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (creditor_account.creditor_account_date >= \''.$date_from.'\' AND creditor_account.creditor_account_date <= \''.$date_to.'\')';
			$search_title = 'Statement from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$where .= ' AND creditor_account.creditor_account_date = \''.$date_from.'\'';
			$search_title = 'Statement of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$where .= ' AND creditor_account.creditor_account_date = \''.$date_to.'\'';
			$search_title = 'Statement of '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else
		{
			$where .= ' AND DATE_FORMAT(creditor_account.creditor_account_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(creditor_account.creditor_account_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Statement for the month of '.date('M Y').' ';
		}
		$creditor = $this->creditors_model->get_creditor($creditor_id);
		$row = $creditor->row();
		$creditor_name = $row->creditor_name;
		
		$v_data['contacts'] = $this->site_model->get_contacts();
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['query'] = $this->creditors_model->get_creditor_account($where, $table);
		$v_data['title'] = $creditor_name.' '.$search_title;
		$this->load->view('creditors/print_creditor_account', $v_data);
	}
}
?>