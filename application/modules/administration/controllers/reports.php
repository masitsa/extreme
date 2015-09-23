<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/administration/controllers/administration.php";

class Reports extends administration
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception/reception_model');
		$this->load->model('reports_model');
		$this->load->model('accounts/accounts_model');
	}
	
	public function cash_report($module = NULL)
	{
		$search = ' AND payments.visit_id = visit.visit_id AND payments.payment_type = 1';
		$table = ', payments';
		$this->session->set_userdata('all_transactions_search', $search);
		$this->session->set_userdata('all_transactions_tables', $table);
		
		$this->session->set_userdata('debtors', 'false');
		$this->session->set_userdata('page_title', 'Cash Report');
		
		$this->all_transactions($module);
	}
	
	public function all_reports($module = NULL)
	{
		$this->session->unset_userdata('all_transactions_search');
		$this->session->unset_userdata('all_transactions_tables');
		
		$this->session->set_userdata('debtors', 'false2');
		$this->session->set_userdata('page_title', 'All Transactions');
		
		$this->all_transactions($module);
	}
	
	public function time_reports()
	{
		$this->session->unset_userdata('time_reports_search');
		$this->session->unset_userdata('time_reports_tables');
		
		$this->session->set_userdata('page_title', 'Time Reports');
		
		$this->all_time_reports();
	}
	
	public function debtors_report($module = NULL)
	{
		$this->session->unset_userdata('all_transactions_search');
		$this->session->unset_userdata('all_transactions_tables');
		$this->session->set_userdata('page_title', 'Debtors Report');
		
		$this->session->set_userdata('debtors', 'true');
		
		$this->all_transactions($module);
	}
	
	public function all_transactions($module = NULL)
	{
		$where = 'visit.patient_id = patients.patient_id ';
		$table = 'visit, patients';
		$visit_search = $this->session->userdata('all_transactions_search');
		$table_search = $this->session->userdata('all_transactions_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		if($module == NULL)
		{
			$segment = 4;
		}
		else
		{
			$segment = 5;	
		}
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/reports/all_transactions/'.$module;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reports_model->get_all_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		$v_data['total_patients'] = $config['total_rows'];
		$v_data['total_services_revenue'] = $this->reports_model->get_total_services_revenue($where, $table);
		$v_data['total_payments'] = $this->reports_model->get_total_cash_collection($where, $table);
		
		//total other debt
		$where2 = $where.' AND visit.visit_type = 3';
		$total_other_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 3';
		$other_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 3';
		$other_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_other_debt'] = ($total_other_debt + $other_debit_notes) - $other_credit_notes;
		
		//total insurance debt
		$where2 = $where.' AND visit.visit_type = 4';
		$total_insurance_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//students debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND visit.visit_type = 4';
		$insurance_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//students credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND visit.visit_type = 4';
		$insurance_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_insurance_debt'] = ($total_insurance_debt + $insurance_debit_notes) - $insurance_credit_notes;
		
		//all normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['normal_payments'] = $this->reports_model->get_normal_payments($where2, $table);
		$v_data['payment_methods'] = $this->reports_model->get_payment_methods($where2, $table);
		
		//normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['total_cash_collection'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//debit notes
		$where2 = $where.' AND payments.payment_type = 2';
		$v_data['debit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//credit notes
		$where2 = $where.' AND payments.payment_type = 3';
		$v_data['credit_notes'] = $this->reports_model->get_total_cash_collection($where2, $table);
		
		//count other visits
		$where2 = $where.' AND patients.inpatient = 0';
		$v_data['outpatients'] = $this->reception_model->count_items($table, $where2);
		
		//count insurance visits
		$where2 = $where.' AND patients.inpatient = 1';
		$v_data['inpatients'] = $this->reception_model->count_items($table, $where2);
		
		$data['title'] = $this->session->userdata('page_title');
		$v_data['title'] = $this->session->userdata('page_title');
		$v_data['debtors'] = $this->session->userdata('debtors');
		
		$v_data['services_query'] = $this->reports_model->get_all_active_services();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		$v_data['module'] = $module;
		
		$data['content'] = $this->load->view('reports/all_transactions', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_transactions($module = NULL)
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$patient_number = $this->input->post('patient_number');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		$search_title = 'Showing reports for: ';
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
			
			$this->db->where('visit_type_id', $visit_type_id);
			$query = $this->db->get('visit_type');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->visit_type_name.' ';
			}
		}
		
		if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\' ';
			
			$search_title .= 'Patient number. '.$patient_number;
		}
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
			
			$this->db->where('personnel_id', $personnel_id);
			$query = $this->db->get('personnel');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->personnel_fname.' '.$row->personnel_onames.' ';
			}
		}
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
			$search_title .= 'Visit date from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
			$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_from)).' ';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
			$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_type_id.$patient_number.$visit_date.$personnel_id;
		$visit_search = $this->session->userdata('all_transactions_search');
		
		if(!empty($visit_search))
		{
			$search .= $visit_search;
		}
		$this->session->set_userdata('all_transactions_search', $search);
		$this->session->set_userdata('search_title', $search_title);
		
		$this->all_transactions($module);
	}
	
	public function export_transactions()
	{
		$this->reports_model->export_transactions();
	}
	public function export_time_report()
	{
		$this->reports_model->export_time_report();
	}
	
	public function close_search()
	{
		$this->session->unset_userdata('all_transactions_search');
		$this->session->unset_userdata('all_transactions_tables');
		$this->session->unset_userdata('search_title');
		
		$debtors = $this->session->userdata('debtors');
		
		if($debtors == 'true')
		{
			$this->debtors_report();
		}
		
		else if($debtors == 'false')
		{
			$this->cash_report();
		}
		
		else
		{
			$this->all_reports();
		}
	}
	
	public function department_reports()
	{
		//get all service types
		$v_data['services_result'] = $this->reports_model->get_all_service_types();
		$v_data['type'] = $this->reception_model->get_types();
		
		$data['title'] = 'Department Reports';
		$v_data['title'] = 'Department Reports';
		
		$data['content'] = $this->load->view('reports/department_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_departments()
	{
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_date;
		
		$this->session->set_userdata('all_departments_search', $search);
		
		$this->department_reports();
	}
	
	public function all_time_reports()
	{
		$where = 'visit.patient_id = patients.patient_id AND visit.close_card = 1';
		$table = 'visit, patients';
		$visit_search = $this->session->userdata('time_reports_search');
		$table_search = $this->session->userdata('time_reports_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/reports/all_time_reports';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reports_model->get_all_visits($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		$v_data['total_patients'] = $config['total_rows'];
		//$v_data['visit_departments'] = $this->reports_model->get_visit_departments($where, $table);
		
		//count student visits
		$where2 = $where.' AND visit.visit_type = 1';
		$v_data['students'] = $this->reception_model->count_items($table, $where2);
		
		//count staff visits
		$where2 = $where.' AND visit.visit_type = 2';
		$v_data['staff'] = $this->reception_model->count_items($table, $where2);
		
		//count other visits
		$where2 = $where.' AND visit.visit_type = 3';
		$v_data['other'] = $this->reception_model->count_items($table, $where2);
		
		//count insurance visits
		$where2 = $where.' AND visit.visit_type = 4';
		$v_data['insurance'] = $this->reception_model->count_items($table, $where2);
		
		$data['title'] = $this->session->userdata('page_title');
		$v_data['title'] = $this->session->userdata('page_title');
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('reports/time_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_time()
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$strath_no = $this->input->post('strath_no');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
		}
		
		if(!empty($strath_no))
		{
			$strath_no = ' AND patients.strath_no LIKE \'%'.$strath_no.'%\' ';
		}
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
		}
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_type_id.$strath_no.$visit_date.$personnel_id;
		$visit_search = $this->session->userdata('time_reports_search');
		
		if(!empty($visit_search))
		{
			//$search .= $visit_search;
		}
		$this->session->set_userdata('time_reports_search', $search);
		
		$this->all_time_reports();
	}
	
	public function close_time_reports_search()
	{
		$this->session->unset_userdata('time_reports_search');
		$this->session->unset_userdata('time_reports_tables');
		
		$this->all_time_reports();
	}
	public function doctor_reports($date_from = NULL, $date_to = NULL)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		//get all service types
		$v_data['doctor_results'] = $this->reports_model->get_all_doctors();
		
		if(!empty($date_from) && !empty($date_to))
		{
			$title = 'Doctors report from '.date('jS M Y',strtotime($date_from)).' to '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($date_from) && !empty($date_to))
		{
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_to));
		}
		
		else if(!empty($date_from) && empty($date_to))
		{
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_from));
		}
		
		else
		{
			$date_from = date('Y-m-d');
			$title = 'Doctors report for '.date('jS M Y',strtotime($date_from));
		}
		
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		
		$v_data['title'] = $title;
		$data['title'] = 'Doctor Reports';
		
		$data['content'] = $this->load->view('reports/doctor_reports', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function search_doctors()
	{
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		redirect('administration/reports/doctor_reports/'.$visit_date_from.'/'.$visit_date_to);
	}
	
	public function doctor_reports_export($date_from = NULL, $date_to = NULL)
	{
		$this->reports_model->doctor_reports_export($date_from, $date_to);
	}
	
	public function doctor_patients_export($personnel_id, $date_from = NULL, $date_to = NULL)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->reports_model->doctor_patients_export($personnel_id, $date_from, $date_to);
	}
}
?>