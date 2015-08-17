<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/accounts/controllers/accounts.php";

class Payroll extends accounts 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the personnel
	*
	*/
	public function salaries($order = 'personnel_onames', $order_method = 'ASC') 
	{
		$where = 'personnel_status != 0';
		$table = 'personnel';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'accounts/salaries/'.$order.'/'.$order_method;
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
		$query = $this->personnel_model->get_all_personnel($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = $v_data['title'] = 'Personnel salaries';
		
		$v_data['order'] = $order;
		$v_data['month'] = $this->payroll_model->get_months();
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('payroll/salaries', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function payment_details($personnel_id)
	{
		$result = $this->personnel_model->get_personnel($personnel_id);
		
		if($result->num_rows() > 0)
		{
			$row2 = $result->row();
			
			$onames = $row2->personnel_onames;
			$fname = $row2->personnel_fname;
			$basic_pay = $row2->basic_pay;
			
			$v_data['personnel_name'] = $fname." ".$onames;
			$v_data['personnel_id'] = $personnel_id;
			$v_data['allowances'] = $this->payroll_model->get_all_allowances();
			$v_data['personel_allowances'] = $this->payroll_model->get_personel_allowances($personnel_id);
			$v_data['deductions'] = $this->payroll_model->get_all_deductions();
			$v_data['personel_deductions'] = $this->payroll_model->get_personel_deductions($personnel_id);
			$v_data['savings'] = $this->payroll_model->get_all_savings();
			$v_data['savings_opening'] = $this->payroll_model->get_savings_opening($personnel_id);
			$v_data['personel_savings'] = $this->payroll_model->get_personnel_savings($personnel_id);
			$v_data['loan_schemes'] = $this->payroll_model->get_all_loan_schemes();
			$v_data['personel_schemes'] = $this->payroll_model->get_personnel_scheme($personnel_id);
			
			$v_data['basic_pay'] = $basic_pay;
			
			$data['title'] = $v_data['title'] = 'Payment details for '.$v_data['personnel_name'];
			
			$data['content'] = $this->load->view("payroll/payment_details", $v_data, TRUE);
		}
		
		else
		{
			$data['title'] = 'Error';
			$data['content'] = '<h3 class="center-align">Unable to find personnel.</h3>';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function save_payment_details($personnel_id)
	{
		//Add new basic pay
		$pay = $this->input->post("basic_pay");
		$items = array(
			"basic_pay" => $pay
		);
		$this->db->where("personnel_id", $personnel_id);
		$this->db->update('personnel', $items);
		
		//Update Allowances
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_allowance");
		
		//Add allowances
		$allowances = $this->payroll_model->get_all_allowances();
		if($allowances->num_rows() > 0)
		{
			foreach($allowances->result() as $allow)
			{
				$allowance_id = $allow->allowance_id;
				$amount = $this->input->post("allowance".$allowance_id);
		
				$items = array(
					"allowance_id" => $allowance_id,
					"personnel_id" => $personnel_id,
					"personnel_allowance_amount" => $amount
				);
				$this->db->insert($table, $items);
			}
		}
		
		//Update deductions
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_deduction");
		
		//Add deductions
		$deductions = $this->payroll_model->get_all_deductions();
		if($deductions->num_rows() > 0)
		{
			foreach($deductions->result() as $allow)
			{
				$deduction_id = $allow->deduction_id;
				$amount = $this->input->post("deduction".$deduction_id);
		
				$items = array(
					"deduction_id" => $deduction_id,
					"personnel_id" => $personnel_id,
					"personnel_deduction_amount" => $amount
				);
				$this->db->insert($table, $items);
			}
		}
		$this->session->set_userdata("success_message", "Payment details updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function update_savings($personnel_id)
	{
		//delete savings
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("savings_opening");
		
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_savings");
		
		$savings = $this->payroll_model->get_all_savings();
		
		if($savings->num_rows() > 0)
		{
			
			foreach ($savings->result() as $row2)
			{
				//save savings opening
				$savings_id = $row2->savings_id;
				$savings_opening = $this->input->post("savings_opening".$savings_id);
				
				$items = array(
					"savings_opening_amount" => $savings_opening,
					"savings_id" => $savings_id,
					"personnel_id" => $personnel_id
				);
				
				$this->db->insert("savings_opening", $items);
				
				//save personnel savings
				$savings = $this->input->post("savings".$savings_id);
				
				$items = array(
					"personnel_savings_amount" => $savings,
					"savings_id" => $savings_id,
					"personnel_id" => $personnel_id
				);
				
				$this->db->insert("personnel_savings", $items);
			}
		}
		$this->session->set_userdata("success_message", "Savings updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function update_loan_schemes($personnel_id)
	{
		//delete savings
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_scheme");
		
		$schemes = $this->payroll_model->get_all_loan_schemes();
		
		if($schemes->num_rows() > 0){
			
			foreach ($schemes->result() as $row2)
			{
				//save savings opening
				$scheme_id = $row2->loan_scheme_id;
				$amount = $this->input->post("borrowings".$scheme_id);
				$monthly = $this->input->post("payments".$scheme_id);
				$interest = $this->input->post("interest".$scheme_id);
				$int = $this->input->post("interest2".$scheme_id);
				$sdate = $this->input->post("datepicker".$scheme_id);
				$edate = $this->input->post("2datepicker".$scheme_id);
				
				$items = array(
					"loan_scheme_id" => $scheme_id,
					"personnel_scheme_repayment_sdate" => $sdate,
					"personnel_scheme_repayment_edate" => $edate,
					"personnel_scheme_monthly" => $monthly,
					"personnel_scheme_interest" => $interest,
					"personnel_scheme_amount" => $amount,
					"personnel_scheme_int" => $int,
					"personnel_id" => $personnel_id
				);
				
				$this->db->insert("personnel_scheme", $items);
			}
		}
		$this->session->set_userdata("success_message", "Loan schemes updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	public function payroll_configuration()
	{
		$v_data['allowances'] = $this->payroll_model->get_all_allowances();
		$v_data['deductions'] = $this->payroll_model->get_all_deductions();
		$v_data['savings'] = $this->payroll_model->get_all_savings();
		$v_data['loan_schemes'] = $this->payroll_model->get_all_loan_schemes();
		$data['title'] = $v_data['title'] = 'Payroll configuration';
		
		$data['content'] = $this->load->view("payroll/configuration", $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function new_allowance()
	{
		$allowance = $this->input->post("allowance");
		$table = "allowance";
		$items = array(
			"allowance_name" => $allowance
		);
		if(!empty($allowance)){
			$this->db->insert($table, $items);
		}
		
		$this->session->set_userdata("success_message", "Allowance added successfully");
		redirect("payroll/configuration");
	}
	
	function new_deduction()
	{
		$deduction = $this->input->post("deduction");
		$table = "deduction";
		$items = array(
			"deduction_name" => $deduction
		);
		if(!empty($allowance)){
			$this->db->insert($table, $items);
		}
		
		$this->session->set_userdata("success_message", "Deductions added successfully");
		redirect("payroll/configuration");
	}
	
	function new_saving()
	{
		$saving = $this->input->post("saving");
		$table = "savings";
		$items = array(
			"savings_name" => $saving
		);
		if(!empty($allowance)){
			$this->db->insert($table, $items);
		}
		
		$this->session->set_userdata("success_message", "Saving added successfully");
		redirect("payroll/configuration");
	}
	
	function new_loan_scheme()
	{
		$loan_scheme = $this->input->post("loan_scheme");
		$table = "loan_scheme";
		$items = array(
			"loan_scheme_name" => $loan_scheme
		);
		if(!empty($allowance)){
			$this->db->insert($table, $items);
		}
		
		$this->session->set_userdata("success_message", "Loan scheme added successfully");
		redirect("payroll/configuration");
	}
	
	function edit_allowance($allowance_id)
	{
		$allowance = $this->input->post("allowance".$allowance_id);
		$table = "allowance";
		$where = array(
			"allowance_id" => $allowance_id
		);
		$items = array(
			"allowance_name" => $allowance
		);
		$this->db->where($where);
		$this->db->update($table, $items);
		
		$this->session->set_userdata("success_message", "Allowance editted successfully");
		redirect("payroll/configuration");
	}
	
	function edit_deduction($deduction_id)
	{
		$deduction = $this->input->post("deduction".$deduction_id);
		$table = "deduction";
		$where = array(
			"deduction_id" => $deduction_id
		);
		$items = array(
			"deduction_name" => $deduction
		);
		$this->db->where($where);
		$this->db->update($table, $items);
		
		$this->session->set_userdata("success_message", "Deduction editted successfully");
		redirect("payroll/configuration");
	}
	
	function edit_saving($saving_id)
	{
		$saving = $this->input->post("saving".$saving_id);
		$table = "savings";
		$where = array(
			"savings_id" => $saving_id
		);
		$items = array(
			"savings_name" => $saving
		);
		$this->db->where($where);
		$this->db->update($table, $items);
		
		$this->session->set_userdata("success_message", "Saving editted successfully");
		redirect("payroll/configuration");
	}
	
	function edit_loan_scheme($loan_scheme_id)
	{
		$loan_scheme = $this->input->post("loan_scheme".$loan_scheme_id);
		$table = "loan_scheme";
		$where = array(
			"loan_scheme_id" => $loan_scheme_id
		);
		$items = array(
			"loan_scheme_name" => $loan_scheme
		);
		$this->db->where($where);
		$this->db->update($table, $items);
		
		$this->session->set_userdata("success_message", "Loan scheme editted successfully");
		redirect("payroll/configuration");
	}
	
	function delete_allowance($allowance_id)
	{
		$table = "allowance";
		$where = array(
			"allowance_id" => $allowance_id
		);
		$this->db->where($where);
		$this->db->delete($table);
		
		$this->session->set_userdata("success_message", "Allowance deleted successfully");
		redirect("payroll/configuration");
	}
	
	function delete_deduction($deduction_id)
	{
		$table = "deduction";
		$where = array(
			"deduction_id" => $deduction_id
		);
		$this->db->where($where);
		$this->db->delete($table);
		
		$this->session->set_userdata("success_message", "Deduction deleted successfully");
		redirect("payroll/configuration");
	}
	
	function delete_saving($saving_id)
	{
		$table = "savings";
		$where = array(
			"savings_id" => $saving_id
		);
		$this->db->where($where);
		$this->db->delete($table);
		
		$this->session->set_userdata("success_message", "Savings deleted successfully");
		redirect("payroll/configuration");
	}
	
	function delete_loan_scheme($loan_scheme_id)
	{
		$table = "loan_scheme";
		$where = array(
			"loan_scheme_id" => $loan_scheme_id
		);
		$this->db->where($where);
		$this->db->delete($table);
		
		$this->session->set_userdata("success_message", "Loan scheme deleted successfully");
		redirect("payroll/configuration");
	}
	
	public function print_payslips()
	{
		$this->payroll_model->save_salary();
		$year = $this->input->post("year");
		$month = $this->input->post("month");
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/payroll_list.php?year=".$year."&month=".$month?>","Popup","height=500,width=1000,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		$this->payslips($_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}

	public function print_payroll()
	{
		$this->payroll_model->save_salary();
		$data['year'] = $this->input->post("year");
		$data['month'] = $this->input->post("month");
		$data['query'] = $this->personnel_model->retrieve_personnel();
		$data['allowances'] = $this->payroll_model->get_all_allowances();
		$data['deductions'] = $this->payroll_model->get_all_deductions();
		$data['contacts'] = $this->site_model->get_contacts();
	
		$this->load->view('payroll/payroll', $data);
		/*$this->load->library('pdf');
		$this->pdf->load_view('payroll/payroll', $data);
		$this->pdf->render();
		$this->pdf->stream("Payroll for ".$data['month']." ".$data['year'].".pdf");*/
	}
}
?>