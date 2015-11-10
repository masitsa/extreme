<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// require_once "./application/modules/auth/controllers/auth.php";

class Lab_charges extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('lab_charges_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('reception/reception_model');
		
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('administration/personnel_model');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function index()
	{
		echo "no patient id";
	}
	public function test_list($order = 'lab_test_name', $order_method = 'ASC', $page_name = '__')
	{
		// this is it
		/*$where = 'lab_test_class.lab_test_class_id = lab_test.lab_test_class_id AND service_charge.lab_test_id = lab_test.lab_test_id AND service.service_id = service_charge.service_id AND (service.service_name = "Lab" OR service.service_name = "lab" OR service.service_name = "laboratory" OR service.service_name = "Laboratory") AND service.branch_code = "'.$this->session->userdata('branch_code').'"';
		$table = 'lab_test,lab_test_class,service,service_charge';*/
		
		$where = 'lab_test_class.lab_test_class_id = lab_test.lab_test_class_id';
		$table = 'lab_test,lab_test_class';
		
		$lab_tests = $this->session->userdata('lab_tests');
		
		if(!empty($lab_tests))
		{
			$where .= $lab_tests;
		}
		
		$segment = 6;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'laboratory-setup/tests/'.$order.'/'.$order_method.'/'.$page_name;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 15;
		$config['num_links'] = 4;
		
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
		$query = $this->lab_charges_model->get_all_test_list($table, $where, $config["per_page"], $page, $order, $order_method);
		
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
		$search_title = $this->session->userdata('tests_search_title');
		
		if(!empty($search_title))
		{
			$title = $search_title;
		}
		
		else
		{
			$title = 'Test List';
		}
		
		$data['title'] = $title;
		$v_data['title'] = $title;
		$v_data['module'] = 0;
		
		$data['content'] = $this->load->view('test_list', $v_data, true);
		
		
		$data['sidebar'] = 'lab_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	}
	public function test_format($primary_key,$page_name = NULL)
	{
		// this is it
		$where = 'lab_test.lab_test_id = lab_test_format.lab_test_id AND lab_test_format.lab_test_id = '.$primary_key;
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		if($page_name == NULL)
		{
			$segment = 5;
		}
		
		else
		{
			$segment = 5;
		}
		$table = 'lab_test, lab_test_format';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'lab_charges/test_format/'.$primary_key.'/'.$page_name;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 15;
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
        $v_data["test_iddd"] = $primary_key;
		$query = $this->lab_charges_model->get_all_test_list($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Test Format';
		$v_data['title'] = 'Test Format';
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('test_format', $v_data, true);
		
		
		$data['sidebar'] = 'lab_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it

	}
	public function classes($class_id=NULL,$page_name = NULL)
    {
    	// this is it
		$where = 'lab_test_class_id > 0';
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		
		if($page_name == NULL)
		{
			$segment = 5;
		}
		
		else
		{
			$segment = 5;
		}
		$table = 'lab_test_class';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'lab_charges/test_format/'.$page_name;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 15;
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
		$v_data["class_id"] = $class_id;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->lab_charges_model->get_all_test_classes($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		if($class_id > 0)
		{
			$data['title'] = 'Edit Classes';
			$v_data['title'] = 'Edit Classes';
		}
		else
		{
			$data['title'] = 'Test Classes';
			$v_data['title'] = 'Test Classes';
		}
		
		$v_data['module'] = 0;
		
		
		$data['content'] = $this->load->view('test_classes', $v_data, true);
		
		
		$data['sidebar'] = 'lab_sidebar';
		
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
    }
    function create_new_class()
    {
    	$this->form_validation->set_rules('class_name', 'Class Name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->lab_charges_model->add_classes();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the class");
				redirect('lab_charges/classes');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('lab_charges/classes');
			
		}
    }
    public function search_lab_tests()
	{
		$lab_test_name = $this->input->post('lab_test_name');
		$test_class = $this->input->post('test_class');
		
		if(!empty($lab_test_name))
		{
			$lab_test_name = ' AND lab_test.lab_test_name LIKE \'%'.$lab_test_name.'%\' ';
		}
		
		if(!empty($test_class))
		{
			$test_class = ' AND lab_test_class.lab_test_class_name LIKE \''.$test_class.'%\' ';
		}
		
		
		
		$search = $lab_test_name.$test_class;
		$this->session->set_userdata('lab_tests', $search);
		
		$this->test_list();
	}	
	public function close_test_search()
	{
		$this->session->unset_userdata('lab_tests');
		$this->test_list();
	}
	function add_lab_test($test_id = NULL)
	{
		if($test_id > 0)
		{
			$v_data['title'] = "Edit laboratory test";
			$v_data['lab_test_details'] = $this->lab_charges_model->get_lab_test_details($test_id);
		}
		else
		{
			$v_data['title'] = "Add laboratory test";
			$v_data['lab_test_details'] = '';
		}
		
		$v_data['lab_test_classes'] = $this->lab_charges_model->get_lab_classes();
		$v_data['test_id'] = $test_id;
		$data['content'] = $this->load->view('add_lab_test', $v_data, true);
		
		$data['title'] = 'Add Lab test';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function add_lab_test_format($test_id,$test_format = NULL)
	{
		if($test_format > 0)
		{
			$v_data['title'] = "Edit laboratory test format";
			$v_data['lab_test_format_details'] = $this->lab_charges_model->get_lab_test_format_details($test_format);
		}
		else
		{
			$v_data['title'] = "Add laboratory test format";
			$v_data['lab_test_format_details'] = '';
		}
		
		$v_data['lab_test_details'] = $this->lab_charges_model->get_lab_test_details($test_id);
		$v_data['test_id'] = $test_id;
		$v_data['format_id'] = $test_format;
		$data['content'] = $this->load->view('add_lab_test_format', $v_data, true);
		
		$data['title'] = 'Add Lab test format';
		$data['sidebar'] = 'reception_sidebar';
		$this->load->view('admin/templates/general_page', $data);	
	}
	function create_new_lab_test_format($test_id)
    {
    	$this->form_validation->set_rules('lab_test_format', 'Lab test Format', 'required|xss_clean');

    	if ($this->form_validation->run())
		{
			$checker = $this->lab_charges_model->add_test_format($test_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test format");
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		redirect('lab_charges/add_lab_test_format/'.$test_id);	
    }
	function create_new_lab_test()
    {
    	$this->form_validation->set_rules('lab_test_class_id', 'Lab test Class', 'is_numeric|xss_clean');
    	$this->form_validation->set_rules('lab_test_name', 'Lab test name', 'is_numeric|xss_clean');
    	$this->form_validation->set_rules('price', 'Price', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->lab_charges_model->add_lab_test();

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('lab_charges/add_lab_test');	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('lab_charges/add_lab_test');				
		}
    }

    function update_lab_test($test_id)
    {
    	$this->form_validation->set_rules('lab_test_class_id', 'Lab test Class', 'is_numeric|xss_clean');
    	$this->form_validation->set_rules('lab_test_name', 'Lab test name', 'is_numeric|xss_clean');
    	$this->form_validation->set_rules('price', 'Price', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->lab_charges_model->edit_lab_test($test_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('lab_charges/add_lab_test/'.$test_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('lab_charges/add_lab_test/'.$test_id);				
		}
    }
    function update_lab_test_format($test_id,$format_id)
    {
    	$this->form_validation->set_rules('lab_test_format', 'Lab test format name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->lab_charges_model->edit_lab_test_format($test_id,$format_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully created the lab test");
				redirect('lab_charges/add_lab_test_format/'.$test_id.'/'.$format_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('lab_charges/add_lab_test_format/'.$test_id.'/'.$format_id);			
		}
    }
     function update_lab_test_class($class_id)
    {
    	$this->form_validation->set_rules('class_name', 'Class name', 'is_numeric|xss_clean');

    	if ($this->form_validation->run() == FALSE)
		{

			$checker = $this->lab_charges_model->edit_lab_test_class($class_id);

			if($checker == TRUE)
			{

				$this->session->set_userdata("success_message","You have successfully update the lab test class name");
				redirect('lab_charges/classes/'.$class_id);	
			}
			else
			{
				$this->session->set_userdata("error_message","Seems like there is a duplicate name. Please try again");
				
			}

		}
		
		else
		{
			
			$this->session->set_userdata("error_message","Please enter the class name then try again");
			redirect('lab_charges/classes/'.$class_id);			
		}
    }
    function activation($type, $page, $id)
    {
    	// the pages are test format, tests, classes
    	$date = date("Y-m-d");
    	
    	if($type == "deactivate")
    	{

    		// start of dicativation
    		if($page == "test_format")
    		{
    			$lab_test_format_details = $this->lab_charges_model->get_lab_test_format_details($id);
    			if($lab_test_format_details->num_rows() > 0)
           		{
       				$lab_test_format_details = $lab_test_format_details->result();
									
					foreach($lab_test_format_details as $format_details)
					{
						$lab_test_id = $format_details->lab_test_id;
					}
           		}
    			$insert = array(
				"lab_test_format_delete" => 1,
				"deleted_by" => $this->session->userdata("personnel_id"),
				"deleted_on" => $date
				);
				$this->db->where('lab_test_format_id', $id);
				$this->db->update('lab_test_format', $insert);
				$this->session->set_userdata("success_message","You have successfully deactivated the test format");
				redirect('lab_charges/test_format/'.$lab_test_id);	
    		}
    		else if($page == "test_lab")
    		{
    			// get all the test formats
    			$test_formats = $this->lab_charges_model->get_all_tests_formats($id);
    			if($test_formats->num_rows() > 0)
           		{
       				$test_formats = $test_formats->result();
									
					foreach($test_formats as $format_results)
					{
						$lab_test_format_id = $format_results->lab_test_format_id;

						$insert = array(
						"lab_test_format_delete" => 1,
						"deleted_by" => $this->session->userdata("personnel_id"),
						"deleted_on" => $date
						);
						$this->db->where('lab_test_format_id', $lab_test_format_id);
						$this->db->update('lab_test_format', $insert);
					}
					$insert_update = array(
					"lab_test_delete" => 1,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_id', $id);
					$this->db->update('lab_test', $insert_update);
					$this->session->set_userdata("success_message","You have successfully deactivated the test and all its test formats");
					redirect('lab_charges/test_list/');	
           		}
           		else
           		{
           			$insert_update = array(
					"lab_test_delete" => 1,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_id', $id);
					$this->db->update('lab_test', $insert_update);
					$this->session->set_userdata("success_message","You have successfully deactivated the test and all its test formats");
					redirect('lab_charges/test_list');
           		}
    			// end of getting the test formats

    		}
    		else if($page == "test_class")
    		{
    			$test_list = $this->lab_charges_model->get_all_tests($id);
    			if($test_list->num_rows() > 0)
	        	{
	        		$test_list = $test_list->result();
	        		foreach ($test_list as $test_values) 
	        		{
	        			$test_id = $test_values->lab_test_id;
	        			$test_formats = $this->lab_charges_model->get_all_tests_formats($test_id);
	        		 	# code...
	        		 	if($test_formats->num_rows() > 0)
		           		{
		       				$test_formats = $test_formats->result();
											
							foreach($test_formats as $format_results)
							{
								$lab_test_format_id = $format_results->lab_test_format_id;

								$insert = array(
								"lab_test_format_delete" => 1,
								"deleted_by" => $this->session->userdata("personnel_id"),
								"deleted_on" => $date
								);
								$this->db->where('lab_test_format_id', $lab_test_format_id);
								$this->db->update('lab_test_format', $insert);
							}
							$insert_update = array(
							"lab_test_delete" => 1,
							"deleted_by" => $this->session->userdata("personnel_id"),
							"deleted_on" => $date
							);
							$this->db->where('lab_test_id', $test_id);
							$this->db->update('lab_test', $insert_update);
						
		           		}
		           		else
		           		{
		           			$insert_update = array(
							"lab_test_delete" => 1,
							"deleted_by" => $this->session->userdata("personnel_id"),
							"deleted_on" => $date
							);
							$this->db->where('lab_test_id', $test_id);
							$this->db->update('lab_test', $insert_update);
							
		           		}
	        		} 
	        		$insert_update = array(
					"lab_test_class_delete" => 1,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_class_id', $id);
					$this->db->update('lab_test_class', $insert_update);
					$this->session->set_userdata("success_message","You have successfully deactivated the class , all test and all its test formats");
					redirect('lab_charges/classes');
	    			
	           }
	           else
	           {
	           	$insert_update = array(
				"lab_test_class_delete" => 1,
				"deleted_by" => $this->session->userdata("personnel_id"),
				"deleted_on" => $date
				);
				$this->db->where('lab_test_class_id', $id);
				$this->db->update('lab_test_class', $insert_update);
				$this->session->set_userdata("success_message","You have successfully deactivated the class , all test and all its test formats");
				redirect('lab_charges/classes');
	           }
    		}
    		


    		// end of diactivations
    	}
    	else if($type == "activate")
    	{
    		// start of dicativation
    		if($page == "test_format")
    		{
    			$lab_test_format_details = $this->lab_charges_model->get_lab_test_format_details($id);
    			if($lab_test_format_details->num_rows() > 0)
           		{
       				$lab_test_format_details = $lab_test_format_details->result();
									
					foreach($lab_test_format_details as $format_details)
					{
						$lab_test_id = $format_details->lab_test_id;
					}
           		}
    			$insert = array(
				"lab_test_format_delete" => 0,
				"deleted_by" => $this->session->userdata("personnel_id"),
				"deleted_on" => $date
				);
				$this->db->where('lab_test_format_id', $id);
				$this->db->update('lab_test_format', $insert);
				$this->session->set_userdata("success_message","You have successfully activated the test format");
				redirect('lab_charges/test_format/'.$lab_test_id);	
    		}
    		else if($page == "test_lab")
    		{
    			// get all the test formats
    			$test_formats = $this->lab_charges_model->get_all_tests_formats($id);
    			if($test_formats->num_rows() > 0)
           		{
       				$test_formats = $test_formats->result();
									
					foreach($test_formats as $format_results)
					{
						$lab_test_format_id = $format_results->lab_test_format_id;

						$insert = array(
						"lab_test_format_delete" => 0,
						"deleted_by" => $this->session->userdata("personnel_id"),
						"deleted_on" => $date
						);
						$this->db->where('lab_test_format_id', $lab_test_format_id);
						$this->db->update('lab_test_format', $insert);
					}
					$insert_update = array(
					"lab_test_delete" => 0,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_id', $id);
					$this->db->update('lab_test', $insert_update);
					$this->session->set_userdata("success_message","You have successfully activated the test and its formats");
					redirect('lab_charges/test_list');	
           		}
           		else
           		{
           			$insert_update = array(
					"lab_test_delete" => 0,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_id', $id);
					$this->db->update('lab_test', $insert_update);
					$this->session->set_userdata("success_message","You have successfully activated the test and its formats");
					redirect('lab_charges/test_list');	
           		}
    			// end of getting the test formats

    		}
    		else if($page == "test_class")
    		{
    			$test_list = $this->lab_charges_model->get_all_tests($id);
    			if($test_list->num_rows() > 0)
	        	{
	        		$test_list = $test_list->result();
	        		foreach ($test_list as $test_values) 
	        		{
	        			$test_id = $test_values->lab_test_id;
	        			$test_formats = $this->lab_charges_model->get_all_tests_formats($test_id);
	        		 	# code...
	        		 	if($test_formats->num_rows() > 0)
		           		{
		       				$test_formats = $test_formats->result();
											
							foreach($test_formats as $format_results)
							{
								$lab_test_format_id = $format_results->lab_test_format_id;

								$insert = array(
								"lab_test_format_delete" => 1,
								"deleted_by" => $this->session->userdata("personnel_id"),
								"deleted_on" => $date
								);
								$this->db->where('lab_test_format_id', $lab_test_format_id);
								$this->db->update('lab_test_format', $insert);
							}
							$insert_update = array(
							"lab_test_delete" => 0,
							"deleted_by" => $this->session->userdata("personnel_id"),
							"deleted_on" => $date
							);
							$this->db->where('lab_test_id', $test_id);
							$this->db->update('lab_test', $insert_update);
		           		}
		           		else
		           		{
		           			$insert_update = array(
							"lab_test_delete" => 0,
							"deleted_by" => $this->session->userdata("personnel_id"),
							"deleted_on" => $date
							);
							$this->db->where('lab_test_id', $test_id);
							$this->db->update('lab_test', $insert_update);
		           		}
	        		} 
	        		$insert_update = array(
					"lab_test_class_delete" => 0,
					"deleted_by" => $this->session->userdata("personnel_id"),
					"deleted_on" => $date
					);
					$this->db->where('lab_test_class_id', $id);
					$this->db->update('lab_test_class', $insert_update);
					$this->session->set_userdata("success_message","You have successfully activated the class test, all tests and its formats");
					redirect('lab_charges/classes');	
	    			
	           }
	           else
	           {
	           	$insert_update = array(
				"lab_test_class_delete" => 0,
				"deleted_by" => $this->session->userdata("personnel_id"),
				"deleted_on" => $date
				);
				$this->db->where('lab_test_class_id', $id);
				$this->db->update('lab_test_class', $insert_update);
				$this->session->set_userdata("success_message","You have successfully activated the class test, all tests and its formats");
				redirect('lab_charges/classes');
	           }
    		}
    		else
    		{

    		}
    	}
    	else
    	{

    	}
    }
	
	public function search_lab_test_results()
	{
		$visit_date_from = $this->input->post('visit_date_from');
		$visit_date_to = $this->input->post('visit_date_to');
		
		$search_title = 'Showing reports for: ';
		
		if(!empty($visit_date_from) && !empty($visit_date_to))
		{
			$visit_date = ' AND visit_lab_test.created >= \''.$visit_date_from.'\' AND visit_lab_test.created <= \''.$visit_date_to.'\'';
			$search_title .= 'Date from '.date('jS M Y', strtotime($visit_date_from)).' to '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else if(!empty($visit_date_from))
		{
			$visit_date = ' AND visit_lab_test.created = \''.$visit_date_from.'\'';
			$search_title .= 'Payments of '.date('jS M Y', strtotime($visit_date_from)).' ';
		}
		
		else if(!empty($visit_date_to))
		{
			$visit_date = ' AND visit_lab_test.created = \''.$visit_date_to.'\'';
			$search_title .= 'Payments of '.date('jS M Y', strtotime($visit_date_to)).' ';
		}
		
		else
		{
			$visit_date = '';
		}
		
		$search = $visit_date;
		$this->session->unset_userdata('tests_report_search');
		
		$this->session->set_userdata('tests_report_search', $search);
		$this->session->set_userdata('tests_search_title', $search_title);
		
		redirect('laboratory-setup/tests');
	}
	
	public function export_results()
	{
		$this->lab_charges_model->export_results();
	}
}
?>