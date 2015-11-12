<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// require_once "./application/modules/administration/controllers/administration.php";

class Reports extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
	
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
		}

		$this->load->model('reception/reception_model');
		$this->load->model('reports_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('accounts/petty_cash_model');
		$this->load->model('reception/database');
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
		$branch_code = $this->session->userdata('search_branch_code');
		
		if(empty($branch_code))
		{
			$branch_code = "OSH";
		}
		
		$this->db->where('branch_code', $branch_code);
		$query = $this->db->get('branch');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$branch_name = $row->branch_name;
		}
		
		else
		{
			$branch_name = '';
		}
		
		$search_title = $branch_code.': ';
		$v_data['branch_name'] = $branch_name;
		
		$where = 'visit.patient_id = patients.patient_id AND visit_type.visit_type_id = visit.visit_type AND visit.visit_delete = 0 AND visit.branch_code = \''.$branch_code.'\'';
		$table = 'visit, patients, visit_type';
		$visit_search = $this->session->userdata('all_transactions_search');
		$table_search = $this->session->userdata('all_transactions_tables');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
			$title = $this->session->userdata('search_title');
			$search_title .= $title;
		
			if(!empty($table_search))
			{
				$table .= $table_search;
			}
		}
		
		else
		{
			$where .= ' AND visit.visit_date = "'.date('Y-m-d').'"';
			$search_title .= date('jS M Y');
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
		$config['base_url'] = site_url().'administration/reports/all_transactions/'.$module;
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
		
		//total outpatients debt
		$where2 = $where.' AND patients.inpatient = 0';
		$total_outpatient_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//outpatient debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND patients.inpatient = 0';
		$outpatient_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//outpatient credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND patients.inpatient = 0';
		$outpatient_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_outpatient_debt'] = ($total_outpatient_debt + $outpatient_debit_notes) - $outpatient_credit_notes;
		
		//total inpatient debt
		$where2 = $where.' AND patients.inpatient = 1';
		$total_inpatient_debt = $this->reports_model->get_total_services_revenue($where2, $table);
		//inpatient debit notes
		$where2 = $where.' AND payments.payment_type = 2 AND patients.inpatient = 1';
		$inpatient_debit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		//inpatient credit notes
		$where2 = $where.' AND payments.payment_type = 3 AND patients.inpatient = 1';
		$inpatient_credit_notes = $this->reports_model->get_total_cash_collection($where2, $table);
		$v_data['total_inpatient_debt'] = ($total_inpatient_debt + $inpatient_debit_notes) - $inpatient_credit_notes;
		
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
		
		//count outpatient visits
		$where2 = $where.' AND patients.inpatient = 0';
		$v_data['outpatients'] = $this->reception_model->count_items($table, $where2);
		
		//count inpatient visits
		$where2 = $where.' AND patients.inpatient = 1';
		$v_data['inpatients'] = $this->reception_model->count_items($table, $where2);
		
		$page_title = $this->session->userdata('page_title');
		if(empty($page_title))
		{
			$page_title = 'All transactions';
		}
		$data['title'] = $v_data['title'] = $search_title;
		$v_data['debtors'] = $this->session->userdata('debtors');
		
		$v_data['branches'] = $this->reports_model->get_all_active_branches();
		$v_data['services_query'] = $this->reports_model->get_all_active_services();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		$v_data['module'] = $module;
		
		$newdata = $this->load->view('reports/all_transactions', $v_data, true);
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
	}
	
	public function search_transactions($visit_type_id = NULL,$personnel_id = NULL, $visit_date_from = NULL, $visit_date_to = NULL,  $branch_code = NULL, $module = NULL)
	{
		if(!empty($branch_code) AND $branch_code != "_")
		{
			$this->session->set_userdata('search_branch_code', $branch_code);
		}
		
		$search_title = $branch_code.': ';
		$visit_type_id2 = "";
		if(!empty($visit_type_id) && $visit_type_id != "_")
		{
			$visit_type_id2 = ' AND visit.visit_type = '.$visit_type_id.' ';
			
			$this->db->where('visit_type_id', $visit_type_id);
			$query = $this->db->get('visit_type');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->visit_type_name.' ';
			}
		}
		
		/*if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\' ';
			
			$search_title .= 'Patient number. '.$patient_number;
		}*/
		$personnel_id2 = "";
		if(!empty($personnel_id) && $personnel_id != "_")
		{
			$personnel_id2 = ' AND visit.personnel_id = '.$personnel_id.' ';
			
			$this->db->where('personnel_id', $personnel_id);
			$query = $this->db->get('personnel');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->personnel_fname.' '.$row->personnel_onames.' ';
			}
		}
		
		//date filter for cash report
		$prev_search = '';
		$prev_table = '';
		
		$debtors = $this->session->userdata('debtors');
		
		if($debtors == 'false')
		{
			$prev_search = ' AND payments.visit_id = visit.visit_id AND payments.payment_type = 1';
			$prev_table = ', payments';
			
			if(!empty($visit_date_from) && !empty($visit_date_to) AND $visit_date_from != "_" AND $visit_date_to != "_")
			{
				$visit_date = ' AND payments.payment_created BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
				$search_title .= 'Payments from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
			}
			
			else if(!empty($visit_date_from) AND $visit_date_from != "_" )
			{
				$visit_date = ' AND payments.payment_created = \''.$visit_date_from.'\'';
				$search_title .= 'Payments of '.date('jS M Y', strtotime($visit_date_from)).' ';
			}
			
			else if(!empty($visit_date_to) AND $visit_date_to != "_")
			{
				$visit_date = ' AND payments.payment_created = \''.$visit_date_to.'\'';
				$search_title .= 'Payments of '.date('jS M Y', strtotime($visit_date_to)).' ';
			}
			
			else
			{
				$visit_date = '';
			}
		}
		
		else
		{
			if(!empty($visit_date_from) && !empty($visit_date_to) AND $visit_date_from != "_" AND $visit_date_to != "_")
			{
				$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
				$search_title .= 'Visit date from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
			}
			
			else if(!empty($visit_date_from) AND $visit_date_from != "_")
			{
				$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
				$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_from)).' ';
			}
			
			else if(!empty($visit_date_to) AND $visit_date_to != "_")
			{
				$visit_date = ' AND visit.visit_date = \''.$visit_date_to.'\'';
				$search_title .= 'Visit date of '.date('jS M Y', strtotime($visit_date_to)).' ';
			}
			
			else
			{
				$visit_date = '';
			}
		}

		$search = $visit_type_id2.$visit_date.$personnel_id2;

		
		$visit_search = $this->session->userdata('all_transactions_search');
		$this->session->set_userdata('all_transactions_search', $search);
		$this->session->set_userdata('search_title', $search_title);
		
		$this->all_transactions($module);
	}
	public function petty_cash($date_from = NULL, $date_to = NULL)
	{
		$branch_code = $this->session->userdata('search_branch_code');
		
		if(empty($branch_code))
		{
			$branch_code = "OSH";
		}
		$where = 'petty_cash.transaction_type_id = transaction_type.transaction_type_id AND petty_cash.branch_code = \''.$branch_code.'\'';
		$table = 'petty_cash, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (petty_cash.petty_cash_date >= \''.$date_from.'\' AND petty_cash.petty_cash_date <= \''.$date_to.'\')';
			//$where .= ' AND petty_cash.petty_cash_date BETWEEN \''.$date_from.'\' AND \'petty_cash.petty_cash_date <= '.$date_to.'\')';
			$search_title = $branch_code.' Petty cash from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_from.'\'';
			$search_title = $branch_code.' Petty cash of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_to.'\'';
			$search_title = $branch_code.' Petty cash of '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else
		{
			$date_from = date('Y-m-01');
			$where .= ' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = $branch_code.' Petty cash for the month of '.date('M Y').' ';
		}
		
		$v_data['balance_brought_forward'] = $this->petty_cash_model->calculate_balance_brought_forward($date_from);
		
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['branches'] = $this->reports_model->get_all_active_branches();
		$v_data['accounts'] = $this->petty_cash_model->get_accounts();
		$v_data['query'] = $this->petty_cash_model->get_petty_cash($where, $table);
		$v_data['title'] = $search_title;
		$data['title'] = 'Petty cash';
		$newdata = $this->load->view('petty_cash/statement', $v_data, TRUE);
		
		$response['result'] = $newdata;
		$response['title'] = 'Petty cash';
		
		echo json_encode($newdata);
	}

	public function search_petty_cash($date_from = NULL, $date_to = NULL, $branch_code = NULL)
	{
		if(!empty($branch_code) AND ($branch_code != "_"))
		{
			$this->session->set_userdata('search_branch_code', $branch_code);
		}
		if(!empty($date_from) && !empty($date_to) AND $date_from != "_" AND $date_to != "_")
		{
			redirect('mobile/reports/petty_cash/'.$date_from.'/'.$date_to);
		}
		
		else if(!empty($date_from) AND $date_from != "_")
		{
			redirect('mobile/reports/petty_cash/'.$date_from);
		}
		
		else if(!empty($date_to) AND $date_to != "_")
		{
			redirect('mobile/reports/petty_cash/'.$date_to);
		}
		
		else
		{
			redirect('mobile/reports/petty_cash');
		}
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
		
		$newdata = $this->load->view('reports/department_reports', $v_data, true);
		
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
	}
	
	public function search_departments($visit_date_from = NULL, $visit_date_to = NULL)
	{
		
		
		if(!empty($visit_date_from) && !empty($visit_date_to) AND $visit_date_from != "_" AND $visit_date_to != "_")
		{
			$visit_date = ' AND visit.visit_date BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from) AND $visit_date_from != "_")
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date_from.'\'';
		}
		
		else if(!empty($visit_date_to) AND $visit_date_to != "_")
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
		
		$newdata = $this->load->view('reports/time_reports', $v_data, true);
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
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
		
		$newdata = $this->load->view('reports/doctor_reports', $v_data, true);
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
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
	
	public function debtors_report_invoices($visit_type_id, $order = 'debtor_invoice_created', $order_method = 'DESC')
	{
		//get bill to
		$v_data['visit_type_query'] = $this->reports_model->get_visit_type();
		
		//select first debtor from query
		if($visit_type_id == 0)
		{
			if($v_data['visit_type_query']->num_rows() > 0)
			{
				$res = $v_data['visit_type_query']->result();
				$visit_type_id = $res[0]->visit_type_id;
				$visit_type_name = $res[0]->visit_type_name;
			}
		}
		
		else
		{
			if($v_data['visit_type_query']->num_rows() > 0)
			{
				$res = $v_data['visit_type_query']->result();
				
				foreach($res as $r)
				{
					$visit_type_id2 = $r->visit_type_id;
					
					if($visit_type_id == $visit_type_id2)
					{
						$visit_type_name = $r->visit_type_name;
						break;
					}
				}
			}
		}
		
		if($visit_type_id > 0)
		{
			$where = 'debtor_invoice.visit_type_id = '.$visit_type_id;
			$table = 'debtor_invoice';
			
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
			
			$segment = 7;
			
			//pagination
			$this->load->library('pagination');
			$config['base_url'] = site_url().'administration/reports/debtors_report_data/'.$visit_type_id.'/'.$order.'/'.$order_method;
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
			$query = $this->reports_model->get_all_debtors_invoices($table, $where, $config["per_page"], $page, $order, $order_method);
			
			$where .= ' AND debtor_invoice.debtor_invoice_id = debtor_invoice_item.debtor_invoice_id AND visit.visit_id = debtor_invoice_item.visit_id ';
			$table .= ', visit, debtor_invoice_item';
			$v_data['where'] = $where;
			$v_data['table'] = $table;
			
			if($order_method == 'DESC')
			{
				$order_method = 'ASC';
			}
			else
			{
				$order_method = 'DESC';
			}
			$v_data['total_patients'] = $this->reports_model->get_total_visits($where, $table);
			$v_data['total_services_revenue'] = $this->reports_model->get_total_services_revenue($where, $table);
			$v_data['total_payments'] = $this->reports_model->get_total_cash_collection($where, $table);
			
			$v_data['order'] = $order;
			$v_data['order_method'] = $order_method;
			$v_data['visit_type_name'] = $visit_type_name;
			$v_data['visit_type_id'] = $visit_type_id;
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['search'] = $visit_search;
			
			$data['title'] = $this->session->userdata('page_title');
			$v_data['title'] = $this->session->userdata('page_title');
			$v_data['debtors'] = $this->session->userdata('debtors');
			
			$v_data['services_query'] = $this->reports_model->get_all_active_services();
			$v_data['type'] = $this->reception_model->get_types();
			$v_data['doctors'] = $this->reception_model->get_doctor();
			//$v_data['module'] = $module;
			
			$data['content'] = $this->load->view('reports/debtors_report_invoices', $v_data, true);
		}
		
		else
		{
			$data['title'] = $this->session->userdata('page_title');
			$data['content'] = 'Please add debtors first';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function create_new_batch($visit_type_id)
	{
		$this->form_validation->set_rules('invoice_date_from', 'Invoice date from', 'required|xss_clean');
		$this->form_validation->set_rules('invoice_date_to', 'Invoice date to', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->reports_model->add_debtor_invoice($visit_type_id))
			{
				
			}
			
			else
			{
				
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		//echo 'done '.$visit_type_id;
		redirect('accounts/insurance-invoices/'.$visit_type_id);
	}
	
	public function view_invoices($debtor_invoice_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$search = 'debtor_invoice_item.visit_id = visit.visit_id AND debtor_invoice_item.debtor_invoice_id = '.$debtor_invoice_id;
		$table = ', debtor_invoice_item';
		
		//create title
		$this->db->where('visit_type.visit_type = debtor_invoice.visit_type_id AND debtor_invoice.debtor_invoice_id = '.$debtor_invoice_id);
		$this->db->select('visit_type_name, date_from, date_to');
		$query = $this->db->get('debtor_invoice, visit_type');
		
		$row = $query->row();
		
		$visit_type_name = $row->visit_type_name;
		$date_from = date('jS M Y',strtotime($row->date_from));
		$date_to = date('jS M Y',strtotime($row->date_to));
		
		$search_title = 'Invoices for '.$visit_type_name.' between '.$date_from.' and '.$date_to;
		
		$_SESSION['all_transactions_search'] = $search;
		$_SESSION['all_transactions_tables'] = $table;
		
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('administration/reports/all_transactions');
	}
	
	public function export_debt_transactions($debtor_invoice_id)
	{
		$this->reports_model->export_debt_transactions($debtor_invoice_id);
	}
	
	public function invoice($debtor_invoice_id)
	{
		$where = 'debtor_invoice.debtor_invoice_id = '.$debtor_invoice_id.' AND debtor_invoice.visit_type_id = visit_type.visit_type_id';
		$table = 'debtor_invoice, visit_type';
		
		$data = array(
			'debtor_invoice_id'=>$debtor_invoice_id,
			'query' => $this->reports_model->get_debtor_invoice($where, $table),
			'personnel_query' => $this->personnel_model->get_all_personnel()
		);
			
		$where .= ' AND debtor_invoice.debtor_invoice_id = debtor_invoice_item.debtor_invoice_id AND visit.visit_id = debtor_invoice_item.visit_id ';
		$table .= ', visit, debtor_invoice_item';
		
		$data['where'] = $where;
		$data['table'] = $table;
		$data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('reports/invoice', $data);
	}
	
	public function search_debtors($visit_type_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		
		$date_from = $this->input->post('batch_date_from');
		$date_to = $this->input->post('batch_date_to');
		$batch_no = $this->input->post('batch_no');
		
		if(!empty($batch_no) && !empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created >= \''.$date_from.'\' AND debtor_invoice.debtor_invoice_created <= \''.$date_to.'\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created between '.date('jS M Y',strtotime($date_from)).' and '.date('jS M Y',strtotime($date_to));
		}
		
		else if(!empty($batch_no) && !empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_from.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created on '.date('jS M Y',strtotime($date_from));
		}
		
		else if(!empty($batch_no) && empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_to.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no.' created on '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($batch_no) && !empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created >= \''.$date_from.'\' AND debtor_invoice.debtor_invoice_created <= \''.$date_to.'\'';
			$search_title = 'Showing invoices created between '.date('jS M Y',strtotime($date_from)).' and '.date('jS M Y',strtotime($date_to));
		}
		
		else if(empty($batch_no) && !empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_from.'%\'';
			$search_title = 'Showing invoices created created on '.date('jS M Y',strtotime($date_from));
		}
		
		else if(empty($batch_no) && empty($date_from) && !empty($date_to))
		{
			$search = ' AND debtor_invoice.debtor_invoice_created LIKE \''.$date_to.'%\'';
			$search_title = 'Showing invoices created created on '.date('jS M Y',strtotime($date_to));
		}
		else if(!empty($batch_no) && empty($date_from) && empty($date_to))
		{
			$search = ' AND debtor_invoice.batch_no LIKE \'%'.$batch_no.'%\'';
			$search_title = 'Showing invoices for batch no. '.$batch_no;
		}
		
		else
		{
			$search = '';
			$search_title = '';
		}
		
		
		$_SESSION['all_transactions_search'] = $search;
		
		$this->session->set_userdata('search_title', $search_title);
		
		redirect('administration/reports/debtors_report_data/'.$visit_type_id);
	}
	
	public function close_debtors_search($visit_type_id)
	{
		$_SESSION['all_transactions_search'] = NULL;
		$_SESSION['all_transactions_tables'] = NULL;
		
		$this->session->unset_userdata('search_title');
		redirect('administration/reports/debtors_report_data/'.$visit_type_id);
	}
	
	public function cash_report()
	{
		$branch_code = $this->session->userdata('search_branch_code');
		
		if(empty($branch_code))
		{
			$branch_code = 'OSH';
		}
		
		$search_title = $branch_code.': ';
		
		$this->db->where('branch_code', $branch_code);
		$query = $this->db->get('branch');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$branch_name = $row->branch_name;
		}
		
		else
		{
			$branch_name = '';
		}
		$v_data['branch_name'] = $branch_name;
		
		$where = 'payments.payment_method_id = payment_method.payment_method_id AND payments.visit_id = visit.visit_id AND payments.payment_type = 1 AND visit.visit_delete = 0 AND visit.branch_code = \''.$branch_code.'\' AND visit.patient_id = patients.patient_id AND visit_type.visit_type_id = visit.visit_type AND payments.cancel = 0';
		
		$table = 'payments, visit, patients, visit_type, payment_method';
		$visit_search = $this->session->userdata('cash_report_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
			$title = $this->session->userdata('cash_search_title');
			$search_title .= $title;
		}
		
		else
		{
			$where .= ' AND payments.payment_created = "'.date('Y-m-d').'"';
			$search_title .= date('jS M Y');
		}
		
		$segment = 3;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-reports/cash-report';
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
		$query = $this->reports_model->get_all_payments($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		
		$v_data['total_payments'] = $this->reports_model->get_total_cash_collection($where, $table, 'cash');
		
		//all normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['normal_payments'] = $this->reports_model->get_normal_payments($where2, $table, 'cash');
		$v_data['payment_methods'] = $this->reports_model->get_payment_methods($where2, $table, 'cash');
		
		//normal payments
		$where2 = $where.' AND payments.payment_type = 1';
		$v_data['total_cash_collection'] = $this->reports_model->get_total_cash_collection($where2, $table, 'cash');
		
		//count outpatient visits
		$cash_report_patients = $this->session->userdata('cash_report_patients');
		
		if(!empty($cash_report_patients))
		{
			$where2 = $cash_report_patients.' AND visit.branch_code = \''.$branch_code.'\'';
		}
		
		else
		{
			$where2 = 'visit.visit_date = "'.date('Y-m-d').'" AND visit.branch_code = \''.$branch_code.'\'';
		}
		$where3 = $where2.' AND visit.inpatient = 0';
		$table2 = 'visit';
		$v_data['outpatients'] = $this->reports_model->count_items($table2, $where3);
		
		//count inpatient visits
		$where3 = $where2.' AND visit.inpatient = 1';
		$v_data['inpatients'] = $this->reports_model->count_items($table2, $where3);

		$v_data['total_patients'] = $v_data['inpatients'] + $v_data['outpatients'];
		
		$page_title = $this->session->userdata('cash_search_title');
		
		if(empty($page_title))
		{
			$page_title = 'Cash report';
		}
		
		$data['title'] = $v_data['title'] = $search_title;
		$v_data['debtors'] = $this->session->userdata('debtors');
		
		$v_data['branches'] = $this->reports_model->get_all_active_branches();
		$v_data['services_query'] = $this->reports_model->get_all_active_services();
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$newdata = $this->load->view('reports/cash_report', $v_data, true);
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
	}
	
	public function search_cash_reports($visit_type_id = '_',$visit_date_from = '_', $visit_date_to = '_',  $branch_code = '_', $module = '_')
	{
		if(!empty($branch_code) AND $branch_code != "_")
		{
			$this->session->set_userdata('search_branch_code', $branch_code);
		}
		
		$search_title = '';
		
		$visit_type_id2 = "";
		if(!empty($visit_type_id) && $visit_type_id != "_")
		{
			$visit_type_id2 = ' AND visit.visit_type = '.$visit_type_id.' ';
			
			$this->db->where('visit_type_id', $visit_type_id);
			$query = $this->db->get('visit_type');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->visit_type_name.' ';
			}
		}
		
		/*if(!empty($patient_number))
		{
			$patient_number = ' AND patients.patient_number LIKE \'%'.$patient_number.'%\' ';
			
			$search_title .= 'Patient number. '.$patient_number;
		}*/
		$personnel_id2 = "";
		if(!empty($personnel_id) && $personnel_id != "_")
		{
			$personnel_id2 = ' AND visit.personnel_id = '.$personnel_id.' ';
			
			$this->db->where('personnel_id', $personnel_id);
			$query = $this->db->get('personnel');
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$search_title .= $row->personnel_fname.' '.$row->personnel_onames.' ';
			}
		}
		
		//date filter for cash report
		$prev_search = '';
		$prev_table = '';
		
		if(!empty($visit_date_from) && !empty($visit_date_to) AND $visit_date_from != "_" AND $visit_date_to != "_")
		{
			$visit_date = ' AND payments.payment_created BETWEEN \''.$visit_date_from.'\' AND \''.$visit_date_to.'\'';
			$search_title .= ' from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
			$patients_date = 'visit.visit_date >= \''.$visit_date_from.'\' AND visit.visit_date <= \''.$visit_date_to.'\'';
		}
		
		else if(!empty($visit_date_from) AND $visit_date_from != "_")
		{
			$visit_date = ' AND payments.payment_created = \''.$visit_date_from.'\'';
			$patients_date = 'visit.visit_date = \''.$visit_date_from.'\'';
			$search_title .= ''.date('jS M Y', strtotime($visit_date_from)).' ';
		}
		
		else if(!empty($visit_date_to) AND $visit_date_to != "_")
		{
			$visit_date = ' AND payments.payment_created = \''.$visit_date_to.'\'';
			$patients_date = 'visit.visit_date = \''.$visit_date_to.'\'';
			$search_title .= ''.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else
		{
			$visit_date = '';
			$patients_date = '';
		}

		$search = $visit_type_id2.$visit_date.$personnel_id2;

		$this->session->unset_userdata('cash_report_search');
		$this->session->unset_userdata('cash_report_patients');
		
		$this->session->set_userdata('cash_report_search', $search);
		$this->session->set_userdata('cash_report_patients', $patients_date);
		$this->session->set_userdata('cash_search_title', $search_title);
		
		$this->cash_report();
	}
	
	public function close_cash_search()
	{
		$this->session->unset_userdata('cash_report_search');
		$this->session->unset_userdata('cash_search_title');
		
		redirect('hospital-reports/cash-report');
	}
	
	public function export_cash_report()
	{
		$this->reports_model->export_cash_report();
	}
}
?>