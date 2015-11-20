<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Services extends Hospital_administration 
{
	var $csv_path;
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('reception/reception_model');
		
		$this->load->model('services_model');
		
		$this->csv_path = realpath(APPPATH . '../assets/csv');
	}

	public function index($order = 'service_name', $order_method = 'ASC')
	{
		//check if branch has parent
		$this->db->where('branch_code', $this->session->userdata('branch_code'));
		$query = $this->db->get('branch');
		$branch_code = $this->session->userdata('branch_code');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$branch_parent = $row->branch_parent;
			
			if(!empty($branch_parent))
			{
				$branch_code = $branch_parent;
			}
		}
		// this is it
		$where = 'service_delete = 0 AND service.branch_code = "'.$branch_code.'"';
		$service_search = $this->session->userdata('service_search');
		
		if(!empty($service_search))
		{
			$where .= $service_search;
		}
		
		$segment = 5;
		$table = 'service';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/services/'.$order.'/'.$order_method;
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
		$query = $this->services_model->get_all_services($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
        $v_data["departments"] = $this->departments_model->all_departments();
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Services';
		$v_data['title'] = 'Services';
		$v_data['module'] = 0;
		
		$data['content'] = $this->load->view('services/services', $v_data, true);
		
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function service_charges($service_id, $order = 'service_charge_name', $order_method = 'ASC')
	{
		// this is it
		$where = 'service_charge_delete = 0 AND service.service_id = service_charge.service_id AND service_charge.visit_type_id = visit_type.visit_type_id AND service_charge.service_id = '.$service_id;
		$service_charge_search = $this->session->userdata('service_charge_search');
		
		if(!empty($service_charge_search))
		{
			$where .= $service_charge_search;
		}
		
		$segment = 6;
		$table = 'service,service_charge,visit_type';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/service-charges/'.$service_id.'/'.$order.'/'.$order_method;
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
		$query = $this->services_model->get_all_service_charges($table, $where, $config["per_page"], $page, $order, $order_method);
		$v_data["department_id"] = $this->services_model->get_department_id($service_id);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$service_name = $this->services_model->get_service_names($service_id);
		$data['title'] = $v_data['title'] = $service_name.' charges';
		$v_data['visit_types'] = $this->services_model->get_visit_types();
		
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $service_name;
		$data['content'] = $this->load->view('services/service_charges', $v_data, true);
		
		$data['sidebar'] = 'admin_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
	}
	
	public function add_service()
	{
		$this->form_validation->set_rules('department_id', 'Department', 'trim|required|xss_clean');
		$this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean');

		if ($this->form_validation->run())
		{
			$result = $this->services_model->submit_service();
			if($result == FALSE)
			{
				$this->session->set_userdata("error_message", "Unable to add this service. Please try again");
			}
			else
			{
				$this->session->set_userdata("success_message", "Successfully created a service ");
			}
			redirect('hospital-administration/services');
		}
		
        $v_data["departments"] = $this->departments_model->all_departments();
		
		$data['title'] = 'Add Service';
		$v_data['title'] = 'Add Service';
		$v_data['service_id'] = 0;
		$data['content'] = $this->load->view('services/add_service',$v_data,TRUE);
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function edit_service($service_id)
	{
		$this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique name is requred.");

		if ($this->form_validation->run())
		{
			$service_name = $this->input->post('service_name');
			$department_id = $this->input->post('department_id');
			$visit_data = array('service_name'=>$service_name, 'department_id'=>$department_id, 'modified_by'=>$this->session->userdata('personnel_id'));
			$this->db->where('service_id',$service_id);
			$this->db->update('service', $visit_data);
			
			$this->session->set_userdata("success_message", "Service updated successfully");
			redirect('hospital-administration/services');
		}
		
        $v_data["departments"] = $this->departments_model->all_departments();
		
		$service_name = $this->services_model->get_service_names($service_id);
		$v_data['dept_id'] = $this->services_model->get_service_department($service_id);
		$data['title'] = $v_data['title'] = 'Edit '.$service_name;
		
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $service_name;
		$data['content'] = $this->load->view('services/edit_service',$v_data,TRUE);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function add_service_charge($service_id)
	{
		$this->form_validation->set_rules('service_charge_name', 'Service Charge name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('charge', 'charge', 'trim|required|xss_clean');

		if ($this->form_validation->run())
		{
			$result = $this->services_model->submit_service_charges($service_id);
			if($result == FALSE)
			{
				$this->session->set_userdata("error_message", "Unable to add service charge. Please try again");
			}
			else
			{
				$this->session->set_userdata("success_message","Successfully created a service charge");
			}
			redirect('hospital-administration/service-charges/'.$service_id);
		}
		
		$service_name = $this->services_model->get_service_names($service_id);
		$data['title'] = $v_data['title'] = 'Add '.$service_name.' charge';
		
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $service_name;
		$v_data['type'] = $this->reception_model->get_types();
		
		$data['content'] = $this->load->view('services/add_service_charge',$v_data,TRUE);
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function edit_service_charge($service_id, $service_charge_id)
	{
		$this->form_validation->set_rules('service_charge_name', 'Service Charge name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patient_type', 'Patient Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('charge', 'charge', 'trim|required|xss_clean');

		if ($this->form_validation->run())
		{
			$service_charge_name = $this->input->post('service_charge_name');
			$patient_type = $this->input->post('patient_type');
			$charge = $this->input->post('charge');
			if($patient_type != 1)
			{
				// select * insurance service charge 

				$this->db->where('patient_type <> 1 AND  service_charge_name = "'.$service_charge_name.'"');
				$service_charges = $this->db->get('service_charge');

				if($service_charges->num_rows() > 0)
				{
					foreach ($service_charges->result() as $key) {
						$service_charge_id_id = $key->service_charge_id;
						$visit_type_idd = $key->visit_type_id;

						$visit_data = array('service_charge_name'=>$service_charge_name,'visit_type_id'=>$visit_type_idd,'service_charge_amount'=>$charge, 'modified_by'=>$this->session->userdata('personnel_id'));
						$this->db->where('service_charge_id',$service_charge_id_id);
						$this->db->update('service_charge', $visit_data);

					}			
				}


			}
			else
			{
				$visit_data = array('service_charge_name'=>$service_charge_name,'visit_type_id'=>$patient_type,'service_charge_amount'=>$charge, 'modified_by'=>$this->session->userdata('personnel_id'));
				$this->db->where('service_charge_id',$service_charge_id);
				$this->db->update('service_charge', $visit_data);
			}
			
			
			$this->session->set_userdata("success_message","Successfully updated service charge");
				
			redirect('hospital-administration/service-charges/'.$service_id);
		}
		
		$service_name = $this->services_model->get_service_names($service_id);
		$data['title'] = $v_data['title'] = 'Edit '.$service_name.' charge';
		
		$v_data['service_id'] = $service_id;
		$v_data['service_name'] = $service_name;
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['service_charge_id'] = $service_charge_id;
		$data['content'] = $this->load->view('services/edit_service_charge',$v_data,TRUE);
		$this->load->view('admin/templates/general_page', $data);	
	}
	
	public function delete_visit_charge($visit_charge_id)
	{
		$visit_data = array('visit_charge_delete'=>1);
		$this->db->where(array("visit_charge_id"=>$visit_charge_id));
		$this->db->update('visit_charge', $visit_data);
		redirect('reception/general_queue/administration');
	}
	
	public function service_search()
	{
		$service_name = $this->input->post('service_name');
		$department_id = $this->input->post('department_id');
		
		if(!empty($service_name))
		{
			$service_name = ' AND service.service_name LIKE \'%'.$service_name.'%\' ';
		}
		else
		{
			$service_name = '';
		}
		
		if(!empty($department_id))
		{
			$department_id = ' AND service.department_id = \''.$department_id.'\' ';
		}
		else
		{
			$department_id = '';
		}
		
		$search = $service_name.$department_id;
		$this->session->set_userdata('service_search', $search);
		
		redirect('hospital-administration/services');
	}
	
	public function close_service_search()
	{
		$this->session->unset_userdata('service_search');
		
		redirect('hospital-administration/services');
	}
	
	public function service_charge_search($service_id)
	{
		$service_charge_name = $this->input->post('service_charge_name');
		$visit_type_id = $this->input->post('visit_type_id');
		
		if(!empty($service_charge_name))
		{
			$service_charge_name = ' AND service_charge.service_charge_name LIKE \'%'.$service_charge_name.'%\' ';
		}
		else
		{
			$service_charge_name = '';
		}
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND service_charge.visit_type_id = '.$visit_type_id.' ';
		}
		else
		{
			$visit_type_id = '';
		}
		
		$search = $service_charge_name.$visit_type_id;
		$this->session->set_userdata('service_charge_search', $search);
		
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	public function close_service_charge_search($service_id)
	{
		$this->session->unset_userdata('service_charge_search');
		
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	public function delete_service($service_id)
	{
		if($this->services_model->delete_service($service_id))
		{
			$this->session->set_userdata('service_success_message', 'The service has been deleted successfully');

		}
		else
		{
			$this->session->set_userdata('service_error_message', 'The service could not be deleted');
		}
		
			redirect('hospital-administration/services');
	}
	
	public function delete_service_charge($service_id, $service_charge_id)
	{
		if($this->services_model->delete_service_charge($service_charge_id))
		{
			$this->session->set_userdata('success_message', 'The charge has been deleted successfully');

		}
		else
		{

			$this->session->set_userdata('error_message', 'The charge could not be deleted');
		}
		redirect('hospital-administration/service-charges/'.$service_id);
	}
    
	/*
	*
	*	Activate an existing service
	*	@param int $service_id
	*
	*/
	public function activate_service($service_id)
	{
		$this->services_model->activate_service($service_id);
		$this->session->set_userdata('success_message', 'Service activated successfully');
		redirect('hospital-administration/services');
	}
    
	/*
	*
	*	Deactivate an existing service
	*	@param int $service_id
	*
	*/
	public function deactivate_service($service_id)
	{
		$this->services_model->deactivate_service($service_id);
		$this->session->set_userdata('success_message', 'Service disabled successfully');
		redirect('hospital-administration/services');
	}
    
	/*
	*
	*	Activate an existing service_charge
	*	@param int $service_charge_id
	*
	*/
	public function activate_service_charge($service_id, $service_charge_id)
	{
		$this->services_model->activate_service_charge($service_charge_id);
		$this->session->set_userdata('success_message', 'Charge activated successfully');
		redirect('hospital-administration/service-charges/'.$service_id);
	}
    
	/*
	*
	*	Deactivate an existing service_charge
	*	@param int $service_charge_id
	*
	*/
	public function deactivate_service_charge($service_id, $service_charge_id)
	{
		$this->services_model->deactivate_service_charge($service_charge_id);
		$this->session->set_userdata('success_message', 'Charge disabled successfully');
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	public function import_lab_charges($service_id)
	{
		if($this->services_model->import_lab_charges($service_id))
		{
		}
		
		else
		{
		}
		
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	public function import_bed_charges($service_id)
	{
		if($this->services_model->import_bed_charges($service_id))
		{
		}
		
		else
		{
		}
		
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	public function import_pharmacy_charges($service_id)
	{
		if($this->services_model->import_pharmacy_charges($service_id))
		{
		}
		
		else
		{
		}
		
		redirect('hospital-administration/service-charges/'.$service_id);
	}
	
	function import_charges_template()
	{
		//export products template in excel 
		 $this->services_model->import_charges_template();
	}
	
	function import_charges($service_id)
	{
		//open the add new product
		$v_data['service_id'] = $service_id;
		$v_data['title'] = 'Import Charges';
		$data['title'] = 'Import Charges';
		$data['content'] = $this->load->view('services/import_charges', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function do_charges_import($service_id)
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->services_model->import_csv_charges($this->csv_path, $service_id);
				
				if($response == FALSE)
				{
				}
				
				else
				{
					if($response['check'])
					{
						$v_data['import_response'] = $response['response'];
					}
					
					else
					{
						$v_data['import_response_error'] = $response['response'];
					}
				}
			}
			
			else
			{
				$v_data['import_response_error'] = 'Please select a file to import.';
			}
		}
		
		else
		{
			$v_data['import_response_error'] = 'Please select a file to import.';
		}
		
		//open the add new product
		$v_data['service_id'] = $service_id;
		$v_data['title'] = 'Import Charges';
		$data['title'] = 'Import Charges';
		$data['content'] = $this->load->view('services/import_charges', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
}

?>