<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administration  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception/reception_model');
		$this->load->model('reception/strathmore_population');
		$this->load->model('accounts/accounts_model');
		$this->load->model('reports_model');
		$this->load->model('administration_model');
		
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('administration/sync_model');
		$this->load->model('administration/personnel_model');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function textarea()
	{
		$data['title'] = 'Textarea';
		$data['content'] = $this->load->view('textarea', '', TRUE);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function index()
	{
		$this->session->unset_userdata('all_transactions_search');
		
		$data['content'] = $this->load->view('dashboard', '', TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}
	public function check($visit_id)
	{
		// $this->sync_model->syn_up_on_closing_visit(673);

		 $response = $this->sync_model->syn_up_on_closing_visit($visit_id);
		
		// echo $response;
	}
	public function sync_app_petty_cash()
	{
		// $this->sync_model->syn_up_on_closing_visit(673);

		$response = $this->sync_model->syn_up_petty_cash();
		
		$this->session->set_userdata('Sync completed successfully');
		redirect('accounts/petty-cash');
	}



	public function pass_patient_bookings($visit_id)
	{
		// $this->sync_model->syn_up_on_closing_visit(673);

		 $response = $this->sync_model->sync_patient_bookings($visit_id);
		
		// echo $response;
	}
	public function sync_down_request()
	{
		$this->sync_model->sync_data_down();	
	}
	
	public function services($page_name = NULL)
	{
		// this is it
		$where = 'service_delete = 0';
		$service_search = $this->session->userdata('service_search');
		
		if(!empty($service_search))
		{
			$where .= $service_search;
		}
		
		if($page_name == NULL)
		{
			$segment = 3;
		}
		
		else
		{
			$segment = 4;
		}
		$table = 'service';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/services/'.$page_name;
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
		$query = $this->administration_model->get_all_services($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Services';
		$v_data['title'] = 'Services';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('services', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	public function service_charges($service_id,$page_name = 'page')
	{
		// this is it
		$where = 'service_charge_delete = 0 AND service.service_id = service_charge.service_id AND service_charge.visit_type_id = visit_type.visit_type_id AND service_charge.service_id = '.$service_id;
		$service_charge_search = $this->session->userdata('service_charge_search');
		
		if(!empty($service_charge_search))
		{
			$where .= $service_charge_search;
		}
		
		if($page_name == 'page')
		{
			$segment = 5;
		}
		
		else
		{
			$segment = 4;
		}
		$table = 'service,service_charge,visit_type';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/service_charges/'.$service_id.'/'.$page_name;
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
		$query = $this->administration_model->get_all_service_charges($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Services Charges';
		$v_data['title'] = 'Services Charges';
		$v_data['module'] = 0;
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $this->administration_model->get_service_names($service_id);
		$data['content'] = $this->load->view('service_charges', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function import_data()
	{
		$data['content'] = $this->load->view('import_data', '', true);
		$data['sidebar'] = 'admin_sidebar';
		$data['title'] = 'Import';
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function bulk_add_all_staff()
	{
		if($this->strathmore_population->get_hr_staff())
		{
			$this->session->set_userdata("success_message", "Staff imported successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Staff could not be imported. Please try again");
		}
		
		redirect('administration/import_data');
	}
	
	public function bulk_add_all_students()
	{
		if($this->strathmore_population->get_ams_student())
		{
			$this->session->set_userdata("success_message", "Students imported successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Students could not be imported. Please try again");
		}
		
		redirect('administration/import_data');
	}
	
	public function add_service_charge($service_id, $service_charge_id = NULL)
	{
		$v_data = array('service_id'=>$service_id);
		$v_data['service_id'] = $service_id;

		$data['title'] = 'Add Service Charge';
		$v_data['title'] = 'Add Service Charge';
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['service_charge_id'] = $service_charge_id;
		$v_data['service_name'] = $this->administration_model->get_service_names($service_id);
		$data['content'] = $this->load->view('add_service_charge',$v_data,TRUE);
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}

	public function service_charge_add($service_id)
	{
		$this->form_validation->set_rules('service_charge_name', 'Service Charge name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('charge', 'charge', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_userdata("error_message","Fill in the fields");
				$this->add_service_charge($service_id);
		}
		else
		{
			$result = $this->administration_model->submit_service_charges($service_id);
			if($result == FALSE)
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate of this service charge. Please try again");
				$this->add_service_charge($service_id);
			}
			else
			{
				$this->session->set_userdata("success_message","Successfully created a service charge");
				$this->add_service_charge($service_id);
			}
		}

	}
	public function new_service()
	{

		$data['title'] = 'Add Service ';
		$v_data['title'] = 'Add Service ';
		$v_data['service_id'] = 0;
		$data['content'] = $this->load->view('add_service',$v_data,TRUE);
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}

	public function service_add()
	{
		$this->form_validation->set_rules('service_name', 'Service Charge name', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_userdata("error_message","Fill in the fields");
				$this->new_service();
		}
		else
		{
				$result = $this->administration_model->submit_service();
				if($result == FALSE)
				{
					$this->session->set_userdata("error_message","Seems like there is a duplicate of this service . Please try again");
					$this->new_service();
				}
				else
				{
					$this->session->set_userdata("success_message","Successfully created a service ");
					$this->new_service();
				}
		}

	}
	public function edit_service($service_id)
	{
		$data['title'] = 'Edit  Service ';
		$v_data['title'] = 'Edit Service ';
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $this->administration_model->get_service_names($service_id);
		$data['content'] = $this->load->view('add_service',$v_data,TRUE);
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}

	public function update_service($service_id)
	{
		$this->form_validation->set_rules('service_name', 'Service Charge name', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata("error_message","Fill in the fields");
			$this->edit_service($service_id);
		}
		else
		{
			$service_name = $this->input->post('service_name');
			$visit_data = array('service_name'=>$service_name);
			$this->db->where('service_id',$service_id);
			$this->db->update('service', $visit_data);
			$this->edit_service($service_id);
		}
		
	}
	public function edit_service_charge($service_id,$service_charge_id)
	{
		$v_data = array('service_id'=>$service_id);
		$v_data['service_id'] = $service_id;

		$data['title'] = 'Add Service Charge';
		$v_data['title'] = 'Add Service Charge';
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['service_charge_id'] = $service_charge_id;
		$v_data['service_name'] = $this->administration_model->get_service_names($service_id);
		$data['content'] = $this->load->view('add_service_charge',$v_data,TRUE);
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	public function update_service_charge($service_id,$service_charge_id)
	{
		$this->form_validation->set_rules('service_charge_name', 'Service Charge name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('charge', 'Charge', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata("error_message","Fill in the fields");
			$this->edit_service_charge($service_id,$service_charge_id);
		}
		else
		{
			$service_charge_name = $this->input->post('service_charge_name');
			$patient_type = $this->input->post('patient_type');
			$charge = $this->input->post('charge');

			$visit_data = array('service_charge_name'=>$service_charge_name,'visit_type_id'=>$patient_type,'service_charge_amount'=>$charge);
			$this->db->where('service_charge_id',$service_charge_id);
			$this->db->update('service_charge', $visit_data);
			$this->edit_service_charge($service_id,$service_charge_id);
		}
		
	}
	public function delete_visit_charge($visit_charge_id)
	{

		$visit_data = array('visit_charge_delete'=>1);
		$this->db->where(array("visit_charge_id"=>$visit_charge_id));
		$this->db->update('visit_charge', $visit_data);
		redirect('reception/general_queue/administration');
	}
	public function update_visit_charge($visit_charge_id)
	{
		
		$consultation_id = $this->input->post('consultation');

		$table = "visit_charge";
		$where = "visit_charge_delete = 0 AND visit_charge_id = ".$visit_charge_id;
		$items = "*";
		$order = "visit_charge_id";
		
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		if(count($result) > 0)
		{
			foreach ($result as $key):
				# code...
				$visit_id = $key->visit_id;
				$visit_charge_units = $key->visit_charge_units;

			endforeach;
			$date = date('Y-m-d');
			//  need to update this to one then
			$visit_data = array('visit_charge_delete'=>1,'deleted_by'=>$this->session->userdata("personnel_id"),'deleted_on'=>$date);
		
			$this->db->where(array("visit_charge_id"=>$visit_charge_id));
			$this->db->update('visit_charge', $visit_data);
			// end of updating the charge to 1

			// start to insert a new charge 
			$service_charge = $this->reception_model->get_service_charge($consultation_id);		
		
			$visit_charge_data = array(
				"visit_id" => $visit_id,
				"service_charge_id" => $consultation_id,
				"date" => $date,
				"visit_charge_units" => $visit_charge_units,
				"created_by" => $this->session->userdata("personnel_id"),
				"visit_charge_amount" => $service_charge
			);
			if($this->db->insert('visit_charge', $visit_charge_data))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
			// end of inserting a new charge
		}
		else
		{
			return FALSE;
		}
		
	}
	public function reduce_receipt($payment_id)
	{
		$personnel_id = $this->accounts_model->get_payment_peronnel($payment_id);

		if($this->session->userdata("personnel_id") == $personnel_id)
		{
		   $this->session->set_userdata("error_message","Seems you are not allowed to do this action. Please ask an administrator for assistance");
			redirect('reception/general_queue/administration');
		}
		else
		{

			$data = array('payment_status' => 0, 'modified_by'=> $this->session->userdata("personnel_id"),'modified_on'=>date("Y-m-d"));
			
			$this->db->where('payment_id', $payment_id);
			$this->db->update('payments', $data);
			$this->session->set_userdata("success_message","You have successfully removed the receipt value");
			redirect('reception/general_queue/administration');

		}
	}
	public function increase_receipt($payment_id)
	{
		$personnel_id = $this->accounts_model->get_payment_peronnel($payment_id);

		if($this->session->userdata("personnel_id") == $personnel_id)
		{
		   $this->session->set_userdata("error_message","Seems you are not allowed to do this action. Please ask an administrator for assistance");
			redirect('reception/general_queue/administration');
		}
		else
		{

			$data = array('payment_status' => 1, 'modified_by'=> $this->session->userdata("personnel_id"),'modified_on'=>date("Y-m-d"));
			
			$this->db->where('payment_id', $payment_id);
			$this->db->update('payments', $data);
			$this->session->set_userdata("success_message","You have successfully restored the receipt value ");
			redirect('reception/general_queue/administration');

		}
	}

	public function patient_statement()
	{
		$segment = 3;
		$patient_statement_search = $this->session->userdata('patient_statement_search');
		// $where = '(visit_type_id <> 2 OR visit_type_id <> 1) AND patient_delete = '.$delete;
		$where = 'patient_delete = 0';
		if(!empty($patient_statement_search))
		{
			$where .= $patient_statement_search;
		}
		
		$table = 'patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/patient_statement';
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
		$query = $this->reception_model->get_all_patients($table, $where, $config["per_page"], $page);
	
		$data['title'] = 'Patients Statements';
		$v_data['title'] = ' Patients Statements';
	
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['delete'] = 1;
		$v_data['type'] = $this->reception_model->get_types();
		$data['content'] = $this->load->view('patients', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	public function search_patient_statement()
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$patient_national_id = $this->input->post('patient_national_id');
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND patients.visit_type_id = '.$visit_type_id.' ';
		}
		
		if(!empty($patient_national_id))
		{
			$visit_type_id = ' AND patients.patient_national_id = \''.$patient_national_id.'\'';
		}
		
		//search surname
		if(!empty($_POST['surname']))
		{
			$surnames = explode(" ",$_POST['surname']);
			$total = count($surnames);
			
			$count = 1;
			$surname = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
				}
				$count++;
			}
			$surname .= ') ';
			}
			else
			{

				$surname = '';
			}
		
			//search other_names
			if(!empty($_POST['othernames']))
			{
				$other_names = explode(" ",$_POST['othernames']);
				$total = count($other_names);
				
				$count = 1;
				$other_name = ' AND (';
				for($r = 0; $r < $total; $r++)
				{
					if($count == $total)
					{
						$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
					}
					
					else
					{
						$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
					}
					$count++;
				}
				$other_name .= ') ';
			}
			else
			{
				$other_name = '';
			}
		
			$search = $visit_type_id.$patient_national_id.$surname.$other_name;
			$this->session->set_userdata('patient_statement_search', $search);
			
			$this->patient_statement();
	}
	public function service_search()
	{
		$service_name = $this->input->post('service_name');
		
		if(!empty($service_name))
		{
			$service_name = ' AND service.service_name LIKE \'%'.$service_name.'%\' ';
		}
		else
		{
			$service_name = '';
		}
		
		$search = $service_name;
		$this->session->set_userdata('service_search', $search);
		
		$this->services();
	}
	public function service_charge_search($service_id)
	{
		$service_charge_name = $this->input->post('service_charge_name');
		
		if(!empty($service_charge_name))
		{
			$service_charge_name = ' AND service_charge.service_charge_name LIKE \'%'.$service_charge_name.'%\' ';
		}
		else
		{
			$service_charge_name = '';
		}
		
		$search = $service_charge_name;
		$this->session->set_userdata('service_charge_search', $search);
		
		$this->service_charges($service_id);
	}


	
	public function delete_service($service_id)
	{
		if($this->administration_model->delete_service($service_id))
		{
			$this->session->set_userdata('service_success_message', 'The service has been deleted successfully');

		}
		else
		{
			$this->session->set_userdata('service_error_message', 'The service could not be deleted');
		}
		
			redirect('administration/services');
	}
	
	public function delete_service_charge($service_id, $service_charge_id)
	{
		if($this->administration_model->delete_service_charge($service_charge_id))
		{
			$this->session->set_userdata('service_charge_success_message', 'The charge has been deleted successfully');

		}
		else
		{

			$this->session->set_userdata('service_charge_error_message', 'The charge could not be deleted');
		}
		redirect('administration/service_charges/'.$service_id);
		
		
	}
	public function close_patient_search($page = NULL)
	{
		$this->session->unset_userdata('patient_statement_search');
		redirect('administration/patient_statement');
		
	}
	public function individual_statement($patient_id,$module = NULL)
	{
		if($module == NULL)
		{
			$segment = 3;
			$config['base_url'] = site_url().'administration/patient_statement/'.$patient_id;
		}
		
		else
		{
			$segment = 4;
			$config['base_url'] = site_url().'administration/patient_statement/'.$patient_id.'/'.$module;
		}
		$where = 'visit.patient_id = '.$patient_id;
		
		$table = 'visit';
		//pagination
		$this->load->library('pagination');
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
		$query = $this->administration_model->get_all_patient_visit($table, $where, $config["per_page"], $page);
	
		$data['title'] = 'Patients Statements';
		$v_data['title'] = ' Patients Statements';
	
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['delete'] = 1;
		$v_data['module'] = $module;
		$v_data['type'] = $this->administration_model->get_visit_types();
		$v_data['patient'] = $this->administration_model->get_patient($patient_id);
		$data['content'] = $this->load->view('individual_statement', $v_data, true);
		if($module == NULL)
		{
			$data['sidebar'] = 'admin_sidebar';	
		}
		else if($module == 2)
		{
			$data['sidebar'] = 'reception_sidebar';
		}
		else if($module == 3)
		{
			$data['sidebar'] = 'accounts_sidebar';
		}
		else
		{
			$data['sidebar'] = 'reception_sidebar';
		}
		
		
		$this->load->view('admin/templates/general_page', $data);

	}
	
		
	
}

?>