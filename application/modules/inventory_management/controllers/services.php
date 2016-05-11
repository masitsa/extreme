<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class services extends MX_Controller  
{
	var $csv_path;

	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/auth_model');
		$this->load->model('admin/users_model');
		$this->load->model('services_model');
		$this->load->model('inventory/suppliers_model');
		//$this->load->model('inventory/services_categories_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		//path to image directory
		$this->csv_path = realpath(APPPATH . '../assets/csv');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
    
	/*
	*
	*	Default action is to show all the services
	*
	*/
	public function index($order = 'service_name', $order_method = 'ASC') 
	{
		$where = 'service_deleted = 0';
		$table = 'services';

		$service_search = $this->session->userdata('service_search');
		
		if(!empty($service_search))
		{
			$where .= $service_search;
		}
		$segment = 5;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory/services'.$order.'/'.$order_method;
		$config['total_rows'] = $this->users_model->count_services($table, $where);
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
        $data["links"] = $this->pagination->create_links();
		$query = $this->services_model->get_all_services($table, $where, $config["per_page"], $page,$order,$order_method);
		
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
		$v_data['title'] = 'All Services';
		//$v_data['all_categories'] = $this->services_categories_model->all_categories();

		$data['content'] = $this->load->view('services/all_services', $v_data, true);
		$data['title'] = 'All Services';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	
	
	public function add_service_details()
	{
		//form validation rules
		$this->form_validation->set_rules('service_name', 'service Name', 'required|xss_clean');
		$this->form_validation->set_rules('service_status', 'service Status', 'xss_clean');
		$this->form_validation->set_rules('service_description', 'service Description', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'service Category', 'required|xss_clean');
		$this->form_validation->set_message('exists', 'You have entered a post code that does not exist');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$service_id = $this->services_model->add_service($service_id);
			$this->session->set_userdata('success_message', 'service updated successfully');
			redirect('inventory/add-service/'.$service_id);
		}
		
		else
		{
			$this->add_service();
		}
	}
   
	/*
	*
	*	Add a new service
	*
	*/
	public function add_service($service_id = NULL) 
	{
		//form validation rules
		$this->form_validation->set_rules('service_name', 'service Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->services_model->add_service())
			{
				$this->session->set_userdata('success_message', 'service updated successfully');
				redirect('inventory/services');
			}
			else
			{
				$this->session->set_userdata('error_message', 'service not created. Please try again');
				redirect('inventory/add-service');
			}
			
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Ensure you have all the fields filled');
			// redirect('inventory/add-service');
		}
		//open the add new service
		$data['title'] = 'Add New Service';
		$v_data['service_id'] = $service_id;
		$v_data['all_suppliers'] = $this->suppliers_model->all_suppliers();
		//$v_data['all_categories'] = $this->services_categories_model->all_categories();
		$data['content'] = $this->load->view('services/add_service', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	
	/*
	*
	*	Edit an existing service
	*	@param int $service_id
	*
	*/
	public function edit_service($service_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('service_name', 'service Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			//update service
			if($this->services_model->update_service($service_id))
			{
				$this->session->set_userdata('success_message', 'Updated Successfully');
				redirect('inventory/services');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update service. Please try again');
				redirect('inventory/services');
			}
			
		}
		
		//open the editw service
		$data['title'] = 'Edit Service';
		$title =$data['title'];
		//select the service from the database
		$query = $this->services_model->get_service($service_id);
		$v_data['service_id'] = $this->services_model->update_service($service_id);
		$v_data['all_suppliers'] = $this->suppliers_model->all_suppliers();
		$v_data['service'] = $query->result();
		$data['content'] = $this->load->view('services/edit_service', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	//search
	
	public function service_search()
	{
		$service_name = $this->input->post('service_name');
		$search_title ='';
		
		
		if(!empty($service_name))
		{
			$search_title .= ' Service Name <strong>'.$service_name.'</strong>';
			$service_name= ' AND services.service_name LIKE \'%'.$service_name.'%\'';
		}
		
		$search = $service_name;
		$this->session->set_userdata('service_search', $search);
		$this->session->set_userdata('search_title', $search_title);
		
		$this->index();
	}
	public function close_service_search()
	{
			$this->session->unset_userdata('service_search');
			redirect('inventory/services');
	
	}
	
	//impoting services
	function import_template()
	{
		//export products template in excel 
		 $this->services_model->import_template();
	}
	
	function import_services()
	{
		//open the add new product
		$v_data['title'] = 'Import Servics';
		$data['title'] = 'Import sercices';
		$data['content'] = $this->load->view('services/import_services', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function do_drugs_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->services_model->import_csv_services($this->csv_path);
				
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
		$v_data['title'] = 'Import drugs';
		$data['title'] = 'Import drugs';
		$data['content'] = $this->load->view('services/import_services', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	/*
	*
	*	Delete an existing service
	*	@param int $service_id
	*
	*/
	public function delete_service($service_id)
	{
		$this->services_model->delete_service($service_id);
		$this->session->set_userdata('success_message', 'Service Has Been Deleted');
		redirect('inventory/services');
	}
    
	/*
	*
	*	Delete an existing service feature
	*	@param int $feature_id
	*
	*/
	public function delete_service_feature($service_id, $service_feature_id, $image = 'None', $thumb = 'None')
	{
		if ($image != 'None')
		{
			//delete image
			$this->file_model->delete_file($this->features_path."\\".$image, $this->features_path);
			//delete thumbnail
			$this->file_model->delete_file($this->features_path."\\".$thumb, $this->features_path);
		}
		
		if($this->services_model->delete_service_features($service_feature_id))
		{
			$this->session->set_userdata('success_message', 'The feature has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete the feature. Please try again.');
		}
		redirect('inventory/add-service/'.$service_id);
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
		$this->session->set_userdata('success_message', 'service activated successfully');
		redirect('inventory/services');
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
		$this->session->set_userdata('success_message', 'service disabled successfully');
		redirect('inventory/services');
	}
	
	public function upload_images() 
	{
		$this->load->view('upload');
	}
	
	// Upload & Resize in action
    public function do_upload()
    {
		$this->load->library('upload');
		$this->load->library('image_lib');
		
		$resize['width'] = 600;
		$resize['height'] = 800;
		
		$response = $this->file_model->upload_gallery(1, $this->gallery_path, $resize);
        
		if($response)
		{
		   $this->load->view('upload');
		}
		
		else
		{
		   var_dump($response);
		}
    }
	
	/**
	 * Get all the features of a category
	 * Called when adding a new service
	 *
	 * @param int category_id
	 *
	 * @return object
	 *
	 */
	function get_category_features($category_id)
	{
		$data['features'] = $this->features_model->all_features_by_category($category_id);
		
		echo $this->load->view('services/features', $data, TRUE);
	}
    
	/*
	*
	*	Update an existing service feature
	*	@param int $feature_id
	*
	*/
	public function update_feature($service_id, $service_feature_id, $image = 'None', $thumb = 'None')
	{
		$feature_name = $this->input->post('feature_value'.$service_feature_id);
		$feature_quantity = $this->input->post('quantity'.$service_feature_id);
		$feature_price = $this->input->post('price'.$service_feature_id);
		
		//upload service's gallery images
		$resize['width'] = 600;
		$resize['height'] = 800;
		
		if(is_uploaded_file($_FILES['feature_image'.$service_feature_id]['tmp_name']))
		{
			$this->load->library('image_lib');
			
			$features_path = $this->features_path;
			/*
				-----------------------------------------------------------------------------------------
				Upload image
				-----------------------------------------------------------------------------------------
			*/
			$response = $this->file_model->upload_single_dir_file($features_path, 'feature_image'.$service_feature_id, $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
			}
		
			else
			{
				$this->session->set_userdata('error_message', $response['error']);
				redirect('inventory/add-service/'.$service_id);
			}
		}
		
		else
		{
			$file_name = $image;
			$thumb_name = $thumb;
		}
				
		if($this->services_model->update_features($service_feature_id, $service_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-service/'.$service_id);
	}
	
	function add_new_feature($category_feature_id, $service_id)
	{
		$feature_name = $this->input->post('sub_feature_name'.$category_feature_id);
		$feature_quantity = $this->input->post('sub_feature_qty'.$category_feature_id);
		$feature_price = $this->input->post('sub_feature_price'.$category_feature_id);
		
		//upload service's gallery images
		$resize['width'] = 600;
		$resize['height'] = 800;
		
		if(is_uploaded_file($_FILES['feature_image'.$category_feature_id]['tmp_name']))
		{
			$this->load->library('image_lib');
			
			$features_path = $this->features_path;
			/*
				-----------------------------------------------------------------------------------------
				Upload image
				-----------------------------------------------------------------------------------------
			*/
			$response = $this->file_model->upload_single_dir_file($features_path, 'feature_image'.$category_feature_id, $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
			}
		
			else
			{
				$this->session->set_userdata('error_message', $response['error']);
				redirect('inventory/add-service/'.$service_id);
			}
		}
		
		else
		{
			$file_name = 'None';
			$thumb_name = 'None';
		}
				
		if($this->services_model->add_new_features($category_feature_id, $service_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been added successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to add the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-service/'.$service_id);
	}
	
	function delete_new_feature($category_feature_id, $row)
	{
		$_SESSION['name'.$category_feature_id][$row] = NULL;
		$_SESSION['quantity'.$category_feature_id][$row] = NULL;
		$_SESSION['price'.$category_feature_id][$row] = NULL;
		
		//delete images
		if($_SESSION['image'.$category_feature_id][$row] != 'None')
		{
			$this->file_model->delete_file($this->features_path."\\".$_SESSION['image'.$category_feature_id][$row], $this->features_path);
			$this->file_model->delete_file($this->features_path."\\".$_SESSION['thumb'.$category_feature_id][$row], $this->features_path);
		}
		$_SESSION['image'.$category_feature_id][$row] = NULL;
		$_SESSION['thumb'.$category_feature_id][$row] = NULL;
		
		$feature_values = $this->services_model->fetch_new_category_features($category_feature_id);
		$options = '';
		
		if(isset($feature_values))
		{
			$options .= '
				<table class="table table-condensed table-responsive table-hover table-striped">
					<tr>
						<th></th>
						<th>Sub Feature</th>
						<th>Quantity</th>
						<th>Additional Price</th>
						<th>Image</th>
					</tr>
			'.$feature_values.'</table>
			';
		}
		
		else
		{
			$options .= '<p>You have not added any features</p>';
		}
		echo $options;
	}
	
	public function delete_gallery_image($service_image_id, $service_id, $image_name, $thumb_name)
	{
		if($this->services_model->delete_gallery_image($service_image_id, $image_name, $thumb_name, $this->gallery_path))
		{
			$this->session->set_userdata('success_message', 'Image deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete image please try again');
		}
		redirect('inventory/add-service/'.$service_id);
	}
	
	function view_features()
	{
		//session_unset();
		$res = $this->services_model->fetch_new_category_features(1);
		var_dump($_SESSION['image1']);
	}
	
	function export_services()
	{
		//export services in excel 
		 $this->services_model->export_services();
	}
	
	function import_categories()
	{
		//export service categories in excel 
		$this->services_model->import_categories();
	}
	
	
	function do_services_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import services from excel 
				$response = $this->services_model->import_csv_services($this->csv_path);
				
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
		
		//open the add new service
		$v_data['title'] = 'Import services';
		$data['title'] = 'Import services';
		$data['content'] = $this->load->view('services/import_services', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*public function check_export()
	{
		$this->load->dbutil();
				
		$query = $this->db->query("SELECT service.clicks, service.minimum_order_quantity, service.maximum_purchase_quantity, service.sale_price, service.featured, service.service_id, service.service_name, service.service_buying_price, service.service_selling_price, service.service_status, service.service_description, service.service_code, service.service_balance, service.brand_id, service.category_id, service.created, service.created_by, service.last_modified, service.modified_by, category.category_name FROM service, category WHERE service.category_id = category.category_id AND service.created_by = ".$this->session->userdata('personnel_id_id'));
		
		echo $this->dbutil->csv_from_result($query);
	}*/
	public function search_services()
	{
		$service_name = $this->input->post('service_name');

		if(!empty($service_name))
		{
			$service_name = ' AND services.service_name LIKE \'%'.mysql_real_escape_string($service_name).'%\' ';
		}
		$search = $service_name;
		$this->session->set_userdata('service_search', $search);
		
		$this->index();
	}
	


}
?>s