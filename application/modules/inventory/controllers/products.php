<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Products extends MX_Controller  
{

	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('products_model');
		$this->load->model('categories_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		
		//path to image directory
	}
    
	/*
	*
	*	Default action is to show all the products
	*
	*/
	public function index() 
	{
		$where = 'product.category_id = category.category_id  AND product.created_by = '.$this->session->userdata('personnel_id');
		$table = 'product, category';

		$product_search = $this->session->userdata('product_search');
		
		if(!empty($product_search))
		{
			$where .= $product_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory/products';
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
		$query = $this->products_model->get_all_products($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['title'] = 'All Products';
			$v_data['all_categories'] = $this->categories_model->all_categories();

			$data['content'] = $this->load->view('products/all_products', $v_data, true);
		}
		
		else
		{
			$search = $this->session->userdata('product_search');
			$search_result = '';
			if(!empty($search))
			{
				$search_result = '<a href="'.site_url().'inventory/close-product-search" class="btn btn-success">Close Search</a>';
			}
			$v_data['title'] = 'All Products';
			$v_data['query'] = $query;
			$v_data['all_categories'] = $this->categories_model->all_categories();
			$data['content'] = $this->load->view('products/all_products', $v_data, true);
		}
		$data['title'] = 'All Products';
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	
	
	public function add_product_details()
	{
		//form validation rules
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
		$this->form_validation->set_rules('product_status', 'Product Status', 'xss_clean');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'Product Category', 'required|xss_clean');
		$this->form_validation->set_message('exists', 'You have entered a post code that does not exist');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$product_id = $this->products_model->add_product($product_id);
			$this->session->set_userdata('success_message', 'Product updated successfully');
			redirect('inventory/add-product/'.$product_id);
		}
		
		else
		{
			$this->add_product();
		}
	}
   
	/*
	*
	*	Add a new product
	*
	*/
	public function add_product($product_id = NULL) 
	{
		//form validation rules
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
		$this->form_validation->set_rules('product_status', 'Product Status', 'xss_clean');
		$this->form_validation->set_rules('product_description', 'Product Description', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'Product Category', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->products_model->add_product())
			{
				$this->session->set_userdata('success_message', 'Product updated successfully');
				redirect('inventory/products');
			}
			else
			{
				$this->session->set_userdata('error_message', 'Product not created. Please try again');
				redirect('inventory/add-product');
			}
			
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Ensure you have all the fields filled');
			// redirect('inventory/add-product');
		}
		//open the add new product
		$data['title'] = 'Add New product';
		$v_data['product_id'] = $product_id;
		$v_data['all_categories'] = $this->categories_model->all_categories();
		$data['content'] = $this->load->view('products/add_product', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing product
	*	@param int $product_id
	*
	*/
	public function edit_product($product_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('product_name', 'Product Name', 'required|xss_clean');
		$this->form_validation->set_rules('product_status', 'Product Status', 'xss_clean');
		$this->form_validation->set_rules('category_id', 'Product Category', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			//update product
			if($this->products_model->update_product($product_id))
			{
				$this->session->set_userdata('success_message', 'Could not update product. Please try again');
				redirect('inventory/products');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update product. Please try again');
				redirect('inventory/products');
			}
			
		}
		
		//open the add new product
		$data['title'] = 'Edit product';
		
		//select the product from the database
		$query = $this->products_model->get_product($product_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['all_categories'] = $this->categories_model->all_categories();
			$v_data['product'] = $query->result();
			$data['content'] = $this->load->view('products/edit_product', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'product does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	
	/*
	*
	*	Delete an existing product
	*	@param int $product_id
	*
	*/
	public function delete_product($product_id)
	{
		//delete product image
		$query = $this->products_model->get_product($product_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->product_image_name;
			
			//delete image
			$this->file_model->delete_file($this->products_path."\\".$image, $this->products_path);
			//delete thumbnail
			$this->file_model->delete_file($this->products_path."\\thumbnail_".$image, $this->products_path);
		}
		
		//delete gallery images
		$query = $this->products_model->get_gallery_images($product_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			foreach($result as $res)
			{
				$image = $res->product_image_name;
				$thumb = $res->product_image_thumb;
				
				//delete image
				$this->file_model->delete_file($this->gallery_path."\\".$image, $this->gallery_path);
				//delete thumbnail
				$this->file_model->delete_file($this->gallery_path."\\".$thumb, $this->gallery_path);
			}
			
			$this->products_model->delete_gallery_images($product_id);
		}
		
		//delete features
		$query = $this->products_model->get_features($product_id);
		
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
			
			$this->products_model->delete_features($product_id);
		}
		
		$this->products_model->delete_product($product_id);
		$this->session->set_userdata('success_message', 'Product has been deleted');
		redirect('inventory/products');
	}
    
	/*
	*
	*	Delete an existing product feature
	*	@param int $feature_id
	*
	*/
	public function delete_product_feature($product_id, $product_feature_id, $image = 'None', $thumb = 'None')
	{
		if ($image != 'None')
		{
			//delete image
			$this->file_model->delete_file($this->features_path."\\".$image, $this->features_path);
			//delete thumbnail
			$this->file_model->delete_file($this->features_path."\\".$thumb, $this->features_path);
		}
		
		if($this->products_model->delete_product_features($product_feature_id))
		{
			$this->session->set_userdata('success_message', 'The feature has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete the feature. Please try again.');
		}
		redirect('inventory/add-product/'.$product_id);
	}
    
	/*
	*
	*	Activate an existing product
	*	@param int $product_id
	*
	*/
	public function activate_product($product_id)
	{
		$this->products_model->activate_product($product_id);
		$this->session->set_userdata('success_message', 'Product activated successfully');
		redirect('inventory/products');
	}
    
	/*
	*
	*	Deactivate an existing product
	*	@param int $product_id
	*
	*/
	public function deactivate_product($product_id)
	{
		$this->products_model->deactivate_product($product_id);
		$this->session->set_userdata('success_message', 'Product disabled successfully');
		redirect('inventory/products');
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
	 * Called when adding a new product
	 *
	 * @param int category_id
	 *
	 * @return object
	 *
	 */
	function get_category_features($category_id)
	{
		$data['features'] = $this->features_model->all_features_by_category($category_id);
		
		echo $this->load->view('products/features', $data, TRUE);
	}
    
	/*
	*
	*	Update an existing product feature
	*	@param int $feature_id
	*
	*/
	public function update_feature($product_id, $product_feature_id, $image = 'None', $thumb = 'None')
	{
		$feature_name = $this->input->post('feature_value'.$product_feature_id);
		$feature_quantity = $this->input->post('quantity'.$product_feature_id);
		$feature_price = $this->input->post('price'.$product_feature_id);
		
		//upload product's gallery images
		$resize['width'] = 600;
		$resize['height'] = 800;
		
		if(is_uploaded_file($_FILES['feature_image'.$product_feature_id]['tmp_name']))
		{
			$this->load->library('image_lib');
			
			$features_path = $this->features_path;
			/*
				-----------------------------------------------------------------------------------------
				Upload image
				-----------------------------------------------------------------------------------------
			*/
			$response = $this->file_model->upload_single_dir_file($features_path, 'feature_image'.$product_feature_id, $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
			}
		
			else
			{
				$this->session->set_userdata('error_message', $response['error']);
				redirect('inventory/add-product/'.$product_id);
			}
		}
		
		else
		{
			$file_name = $image;
			$thumb_name = $thumb;
		}
				
		if($this->products_model->update_features($product_feature_id, $product_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been updated successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to update the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-product/'.$product_id);
	}
	
	function add_new_feature($category_feature_id, $product_id)
	{
		$feature_name = $this->input->post('sub_feature_name'.$category_feature_id);
		$feature_quantity = $this->input->post('sub_feature_qty'.$category_feature_id);
		$feature_price = $this->input->post('sub_feature_price'.$category_feature_id);
		
		//upload product's gallery images
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
				redirect('inventory/add-product/'.$product_id);
			}
		}
		
		else
		{
			$file_name = 'None';
			$thumb_name = 'None';
		}
				
		if($this->products_model->add_new_features($category_feature_id, $product_id, $feature_name, $feature_quantity, $feature_price, $file_name, $thumb_name))
		{
			$this->session->set_userdata('success_message', 'The feature has been added successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to add the feature at this time. Please try again.');
		}
		
		redirect('inventory/add-product/'.$product_id);
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
		
		$feature_values = $this->products_model->fetch_new_category_features($category_feature_id);
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
	
	public function delete_gallery_image($product_image_id, $product_id, $image_name, $thumb_name)
	{
		if($this->products_model->delete_gallery_image($product_image_id, $image_name, $thumb_name, $this->gallery_path))
		{
			$this->session->set_userdata('success_message', 'Image deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Unable to delete image please try again');
		}
		redirect('inventory/add-product/'.$product_id);
	}
	
	function view_features()
	{
		//session_unset();
		$res = $this->products_model->fetch_new_category_features(1);
		var_dump($_SESSION['image1']);
	}
	
	function export_products()
	{
		//export products in excel 
		 $this->products_model->export_products();
	}
	
	function import_template()
	{
		//export products template in excel 
		 $this->products_model->import_template();
	}
	
	function import_categories()
	{
		//export product categories in excel 
		$this->products_model->import_categories();
	}
	
	function import_products()
	{
		//open the add new product
		$v_data['title'] = 'Import Products';
		$data['title'] = 'Import Products';
		$data['content'] = $this->load->view('products/import_product', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function do_products_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->products_model->import_csv_products($this->csv_path);
				
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
		$v_data['title'] = 'Import Products';
		$data['title'] = 'Import Products';
		$data['content'] = $this->load->view('products/import_product', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function check_export()
	{
		$this->load->dbutil();
				
		$query = $this->db->query("SELECT product.clicks, product.minimum_order_quantity, product.maximum_purchase_quantity, product.sale_price, product.featured, product.product_id, product.product_name, product.product_buying_price, product.product_selling_price, product.product_status, product.product_description, product.product_code, product.product_balance, product.brand_id, product.category_id, product.created, product.created_by, product.last_modified, product.modified_by, category.category_name FROM product, category WHERE product.category_id = category.category_id AND product.created_by = ".$this->session->userdata('personnel_id_id'));
		
		echo $this->dbutil->csv_from_result($query);
	}
	public function search_products()
	{
		$product_name = $this->input->post('product_name');
		$product_code = $this->input->post('product_code');
		$category_id = $this->input->post('category_id');


		if(!empty($product_name))
		{
			$product_name = ' AND product.product_name LIKE \'%'.mysql_real_escape_string($product_name).'%\' ';
		}
		
		if(!empty($product_code))
		{
			$product_code = ' AND product.product_code LIKE \'%'.mysql_real_escape_string($product_code).'%\' ';
		}

		
		if(!empty($category_id))
		{
			$category_id = ' AND product.category_id = '.$category_id.'';
		}
		else
		{
			$category_id = '';
		}
		$search = $product_name.$product_code.$category_id;
		$this->session->set_userdata('product_search', $search);
		
		$this->index();
	}
	public function close_product_search($page = NULL)
	{
		$this->session->unset_userdata('product_search');
		redirect('inventory/products');
	}


}
?>