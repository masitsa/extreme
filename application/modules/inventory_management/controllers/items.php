<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class items extends MX_Controller  
{
	var $csv_path;

	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/auth_model');
		$this->load->model('admin/users_model');
		$this->load->model('items_model');
		$this->load->model('inventory/suppliers_model');
		$this->load->model('inventory/items_categories_model');
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
	*	Default action is to show all the items
	*
	*/
	public function index($order = 'item_name', $order_method = 'ASC') 
	{
		$where = 'item.item_category_id = item_category.item_category_id AND item.product_deleted=0';
		$table = 'item, item_category';

		$item_search = $this->session->userdata('item_search');
		
		if(!empty($item_search))
		{
			$where .= $item_search;
		}
		$segment = 5;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory/items'.$order.'/'.$order_method;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->items_model->get_all_items($table, $where, $config["per_page"], $page,$order,$order_method);
		
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
		$v_data['title'] = 'All Items';
		$v_data['all_categories'] = $this->items_categories_model->all_categories();

		$data['content'] = $this->load->view('items/all_items', $v_data, true);
		$data['title'] = 'All Items';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	
	
	public function add_item_details()
	{
		//form validation rules
		$this->form_validation->set_rules('item_name', 'item Name', 'required|xss_clean');
		$this->form_validation->set_rules('item_status', 'item Status', 'xss_clean');
		$this->form_validation->set_rules('item_description', 'item Description', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'item Category', 'required|xss_clean');
		$this->form_validation->set_message('exists', 'You have entered a post code that does not exist');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$item_id = $this->items_model->add_item($item_id);
			$this->session->set_userdata('success_message', 'item updated successfully');
			redirect('inventory/add-item/'.$item_id);
		}
		
		else
		{
			$this->add_item();
		}
	}
   
	/*
	*
	*	Add a new item
	*
	*/
	public function add_item($item_id = NULL) 
	{
		//var_dump($_POST); die();
		//form validation rules
		$this->form_validation->set_rules('item_name', 'item Name', 'required|xss_clean');
		$this->form_validation->set_rules('item_status', 'item Status', 'xss_clean');
		$this->form_validation->set_rules('item_description', 'item Description', 'required|xss_clean');
		$this->form_validation->set_rules('item_category_id', 'item Category', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->items_model->add_item())
			{
				$this->session->set_userdata('success_message', 'item updated successfully');
				redirect('inventory/item');
			}
			else
			{
				$this->session->set_userdata('error_message', 'item not created. Please try again');
				redirect('inventory/add-item');
			}
			
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Ensure you have all the fields filled');
			// redirect('inventory/add-item');
		}
		//open the add new item
		$data['title'] = 'Add New Item';
		$v_data['item_id'] = $item_id;
		$v_data['all_suppliers'] = $this->suppliers_model->all_suppliers();
		$v_data['all_categories'] = $this->items_categories_model->all_categories();
		$data['content'] = $this->load->view('items/add_item', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing item
	*	@param int $item_id
	*
	*/
	public function edit_item($item_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('item_name', 'item Name', 'required|xss_clean');
		$this->form_validation->set_rules('item_status', 'item Status', 'xss_clean');
		$this->form_validation->set_rules('item_category_id', 'item Category', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			//update item
			if($this->items_model->update_item($item_id))
			{
				$this->session->set_userdata('success_message', 'Updated Successfully');
				redirect('inventory/item');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update item. Please try again');
				redirect('inventory/item');
			}
			
		}
		
		//open the editw item
		$data['title'] = 'Edit Item';
		
		//select the item from the database
		$query = $this->items_model->get_item($item_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['all_categories'] = $this->items_categories_model->all_categories();
			$v_data['all_suppliers'] = $this->suppliers_model->all_suppliers();
			$v_data['item'] = $query->result();
			$data['content'] = $this->load->view('items/edit_item', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'item does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	//search
	
	public function item_search()
	{
		$item_name = $this->input->post('item_name');
		$item_category_id=$this->input->post('item_category_id');
		$search_title ='';
		
		
		if(!empty($item_name))
		{
			$search_title .= ' Item Name <strong>'.$item_name.'</strong>';
			$item_name= ' AND item.item_name LIKE \'%'.$item_name.'%\'';
		}
		if(!empty($item_category_id))
			{
				$item_category_id= ' AND item.item_category_id LIKE \'%'.$item_category_id.'%\'';
				$search_title .= 'Item Category ';
			}
			else 
			{
				$item_category_id = '';
				
				}
				
		$search = $item_name.$item_category_id;
		$this->session->set_userdata('item_search', $search);
		$this->session->set_userdata('search_title', $search_title);
		
		$this->index();
	}
	public function close_item_search()
	{
			$this->session->unset_userdata('item_search');
			redirect('inventory/item');
	
	}
	
	//impoting items
	function import_template()
	{
		//export products template in excel 
		 $this->items_model->import_template();
	}
	
	function import_items()
	{
		//open the add new product
		$v_data['title'] = 'Import Assets';
		$data['title'] = 'Import assets';
		$data['content'] = $this->load->view('items/import_items', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function do_drugs_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->items_model->import_csv_items($this->csv_path);
				
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
		$data['content'] = $this->load->view('items/import_items', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	/*
	*
	*	Delete an existing item
	*	@param int $item_id
	*
	*/
	public function delete_item($item_id)
	{
		//delete item image
		$query = $this->items_model->get_item($item_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			//$image = $result[0]->item_image_name;
			
			//delete image
			//$this->file_model->delete_file($this->items_path."\\".$image, $this->items_path);
			//delete thumbnail
			//$this->file_model->delete_file($this->items_path."\\thumbnail_".$image, $this->items_path);
		}
		
		//delete gallery images
		/*$query = $this->items_model->get_gallery_images($item_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $res)
			{
				//$image = $res->item_image_name;
				$thumb = $res->item_image_thumb;
				
				//delete image
				$this->file_model->delete_file($this->gallery_path."\\".$image, $this->gallery_path);
				//delete thumbnail
				$this->file_model->delete_file($this->gallery_path."\\".$thumb, $this->gallery_path);
			}
			
			$this->items_model->delete_gallery_images($item_id);
		}*/
		
		//delete features
		/*$query = $this->items_model->get_features($item_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $res)
			{
				$image = $res->image;
				$thumb = $res->thumb;
				
				//delete image
				$this->file_model->delete_file($this->features_path."\\".$image, $this->features_path);
				//delete thumbnail
				$this->file_model->delete_file($this->features_path."\\".$thumb, $this->features_path);
			}
			
			$this->items_model->delete_features($item_id);
		}*/
		
		$this->items_model->delete_item($item_id);
		$this->session->set_userdata('success_message', 'Item Has Been Deleted');
		redirect('inventory/item');
	}
    
	/*
	*
	*	Delete an existing item feature
	*	@param int $feature_id
	*
	*/
	public function delete_item_feature($item_id, $item_feature_id, $image = 'None', $thumb = 'None')
	{
		if ($image != 'None')
		{
			//delete image
			$this->file_model->delete_file($this->features_path."\\".$image, $this->features_path);
			//delete thumbnail
			$this->file_model->delete_file($this->features_path."\\".$thumb, $this->features_path);
		}
		
		if($this->items_model->delete_item_features($item_feature_id))
		{
			$this->session->set_userdata('success_message', 'The feature has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete the feature. Please try again.');
		}
		redirect('inventory/add-item/'.$item_id);
	}
    
	/*
	*
	*	Activate an existing item
	*	@param int $item_id
	*
	*/
	public function activate_item($item_id)
	{
		$this->items_model->activate_item($item_id);
		$this->session->set_userdata('success_message', 'item activated successfully');
		redirect('inventory/item');
	}
    
	/*
	*
	*	Deactivate an existing item
	*	@param int $item_id
	*
	*/
	public function deactivate_item($item_id)
	{
		$this->items_model->deactivate_item($item_id);
		$this->session->set_userdata('success_message', 'item disabled successfully');
		redirect('inventory/item');
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
	 * Called when adding a new item
	 *
	 * @param int category_id
	 *
	 * @return object
	 *
	 */
	function get_category_features($category_id)
	{
		$data['features'] = $this->features_model->all_features_by_category($category_id);
		
		echo $this->load->view('items/features', $data, TRUE);
	}
    
	/*
	*
	*	Update an existing item feature
	*	@param int $feature_id
	*
	*/
	public function update_feature($item_id, $item_feature_id, $image = 'None', $thumb = 'None')
	{
		$feature_name = $this->input->post('feature_value'.$item_feature_id);
		$feature_quantity = $this->input->post('quantity'.$item_feature_id);
		$feature_price = $this->input->post('price'.$item_feature_id);
		
		//upload item's gallery images
		$resize['width'] = 600;
		$resize['height'] = 800;
		
		if(is_uploaded_file($_FILES['feature_image'.$item_feature_id]['tmp_name']))
		{
			$this->load->library('image_lib');
			
			$features_path = $this->features_path;
			/*
				-----------------------------------------------------------------------------------------
				Upload image
				-----------------------------------------------------------------------------------------
			*/
			$response = $this->file_model->upload_single_dir_file($features_path, 'feature_image'.$item_feature_id, $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
			}
		
			else
			{
				$this->session->set_userdata('error_message', $response['error']);
				redirect('inventory/add-item/'.$item_id);
			}
		}
		
		else
		{
			$file_name = $image;
			$thumb_name = $thumb;
		}
				
		if($this->items_model->update_features($item_feature_id, $item_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-item/'.$item_id);
	}
	
	function add_new_feature($category_feature_id, $item_id)
	{
		$feature_name = $this->input->post('sub_feature_name'.$category_feature_id);
		$feature_quantity = $this->input->post('sub_feature_qty'.$category_feature_id);
		$feature_price = $this->input->post('sub_feature_price'.$category_feature_id);
		
		//upload item's gallery images
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
				redirect('inventory/add-item/'.$item_id);
			}
		}
		
		else
		{
			$file_name = 'None';
			$thumb_name = 'None';
		}
				
		if($this->items_model->add_new_features($category_feature_id, $item_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been added successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to add the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-item/'.$item_id);
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
		
		$feature_values = $this->items_model->fetch_new_category_features($category_feature_id);
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
	
	public function delete_gallery_image($item_image_id, $item_id, $image_name, $thumb_name)
	{
		if($this->items_model->delete_gallery_image($item_image_id, $image_name, $thumb_name, $this->gallery_path))
		{
			$this->session->set_userdata('success_message', 'Image deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete image please try again');
		}
		redirect('inventory/add-item/'.$item_id);
	}
	
	function view_features()
	{
		//session_unset();
		$res = $this->items_model->fetch_new_category_features(1);
		var_dump($_SESSION['image1']);
	}
	
	function export_items()
	{
		//export items in excel 
		 $this->items_model->export_items();
	}
	
	function import_categories()
	{
		//export item categories in excel 
		$this->items_model->import_categories();
	}
	
	
	function do_items_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import items from excel 
				$response = $this->items_model->import_csv_items($this->csv_path);
				
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
		
		//open the add new item
		$v_data['title'] = 'Import items';
		$data['title'] = 'Import items';
		$data['content'] = $this->load->view('items/import_items', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function check_export()
	{
		$this->load->dbutil();
				
		$query = $this->db->query("SELECT item.clicks, item.minimum_order_quantity, item.maximum_purchase_quantity, item.sale_price, item.featured, item.item_id, item.item_name, item.item_buying_price, item.item_selling_price, item.item_status, item.item_description, item.item_code, item.item_balance, item.brand_id, item.category_id, item.created, item.created_by, item.last_modified, item.modified_by, category.category_name FROM item, category WHERE item.category_id = category.category_id AND item.created_by = ".$this->session->userdata('personnel_id_id'));
		
		echo $this->dbutil->csv_from_result($query);
	}
	public function search_items()
	{
		$item_name = $this->input->post('item_name');
		$item_code = $this->input->post('item_code');
		$category_id = $this->input->post('category_id');


		if(!empty($item_name))
		{
			$item_name = ' AND item.item_name LIKE \'%'.mysql_real_escape_string($item_name).'%\' ';
		}
		
		if(!empty($item_code))
		{
			$item_code = ' AND item.item_code LIKE \'%'.mysql_real_escape_string($item_code).'%\' ';
		}

		
		if(!empty($category_id))
		{
			$category_id = ' AND item.category_id = '.$category_id.'';
		}
		else
		{
			$category_id = '';
		}
		$search = $item_name.$item_code.$category_id;
		$this->session->set_userdata('item_search', $search);
		
		$this->index();
	}
	


}
?>