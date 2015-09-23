<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Categories extends MX_Controller {
	var $categories_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('inventory/categories_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		 $this->load->model('site/site_model');
		
		//path to image directory
	}
    
	/*
	*
	*	Default action is to show all the categories
	*
	*/

	public function index() 
	{
		//$where = 'created_by IN (0, '.$this->session->userdata('vendor_id').')';
		$where = 'branch_code = "'.$this->session->userdata('branch_code').'"';
		$table = 'category';

		$category_search = $this->session->userdata('category_search');
		
		if(!empty($category_search))
		{
			$where .= $category_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory-categories';
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
		$query = $this->categories_model->get_all_categories($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			//$v_data['child_categories'] = $this->categories_model->all_child_categories();
			$data['content'] = $this->load->view('categories/all_categories', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'inventory-categories/add-category class="btn btn-success pull-right">Add Category</a>There are no categories';
		}
		$data['title'] = 'All Categories';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new category
	*
	*/
	public function add_category() 
	{
		//form validation rules
		$this->form_validation->set_rules('category_name', 'Category Name', 'required|xss_clean');
		$this->form_validation->set_rules('category_status', 'Category Status', 'required|xss_clean');
		$this->form_validation->set_rules('category_preffix', 'Category Preffix', 'required|is_unique[category.category_preffix]|xss_clean');
		$this->form_validation->set_rules('category_parent', 'Category Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['category_image']['tmp_name']))
			{
				$this->load->library('image_lib');
				
				$categories_path = $this->categories_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
		
				$response = $this->file_model->upload_file($categories_path, 'category_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Add New Category';
					$v_data['all_categories'] = $this->categories_model->all_categories();
					$data['content'] = $this->load->view('categories/add_category', $v_data, true);
					$this->load->view('admin/templates/general_page', $data);
					break;
				}
			}
			
			else{
				$file_name = '';
				$thumb_name = '';
			}
			
			if($this->categories_model->add_category($file_name))
			{
				$this->session->set_userdata('success_message', 'Category added successfully');
				redirect('vendor/all-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add category. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Category';
		$v_data['all_categories'] = $this->categories_model->all_parent_categories();
		$data['content'] = $this->load->view('categories/add_category', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing category
	*	@param int $category_id
	*
	*/
	public function edit_category($category_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('category_name', 'Category Name', 'required|xss_clean');
		$this->form_validation->set_rules('category_status', 'Category Status', 'required|xss_clean');
		$this->form_validation->set_rules('category_preffix', 'Category Preffix', 'required|xss_clean');
		$this->form_validation->set_rules('category_parent', 'Category Parent', 'required|xss_clean');
		$this->form_validation->set_message("is_unique", "A unique preffix is requred.");
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['category_image']['tmp_name']))
			{
				$categories_path = $this->categories_path;
				
				//delete original image
				$this->file_model->delete_file($categories_path."\\".$this->input->post('current_image'), $categories_path);
				
				//delete original thumbnail
				$this->file_model->delete_file($categories_path."\\thumbnail_".$this->input->post('current_image'), $categories_path);
				/*
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($categories_path, 'category_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Edit Category';
					$query = $this->categories_model->get_category($category_id);
					if ($query->num_rows() > 0)
					{
						$v_data['category'] = $query->result();
						$v_data['all_categories'] = $this->categories_model->all_categories();
						$data['content'] = $this->load->view('categories/edit_category', $v_data, true);
					}
					
					else
					{
						$data['content'] = 'category does not exist';
					}
					
					$this->load->view('admin/templates/general_page', $data);
					break;
				}
			}
			
			else{
				$file_name = $this->input->post('current_image');
			}
			//update category
			if($this->categories_model->update_category($file_name, $category_id))
			{
				$this->session->set_userdata('success_message', 'Category updated successfully');
				redirect('vendor/all-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update category. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Edit Category';
		
		//select the category from the database
		$query = $this->categories_model->get_category($category_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['category'] = $query->result();
			$v_data['all_categories'] = $this->categories_model->all_parent_categories();
			
			$data['content'] = $this->load->view('categories/edit_category', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Category does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing category
	*	@param int $category_id
	*
	*/
	public function delete_category($category_id)
	{
		//delete category image
		$query = $this->categories_model->get_category($category_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->category_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->categories_path."/images/".$image, $this->categories_path);
			//delete thumbnail
			$this->file_model->delete_file($this->categories_path."/thumbs/".$image, $this->categories_path);
		}
		$this->categories_model->delete_category($category_id);
		$this->session->set_userdata('success_message', 'Category has been deleted');
		redirect('vendor/all-categories');
	}
    
	/*
	*
	*	Activate an existing category
	*	@param int $category_id
	*
	*/
	public function activate_category($category_id)
	{
		$this->categories_model->activate_category($category_id);
		$this->session->set_userdata('success_message', 'Category activated successfully');
		redirect('vendor/all-categories');
	}
    
	/*
	*
	*	Deactivate an existing category
	*	@param int $category_id
	*
	*/
	public function deactivate_category($category_id)
	{
		$this->categories_model->deactivate_category($category_id);
		$this->session->set_userdata('success_message', 'Category disabled successfully');
		redirect('vendor/all-categories');
	}
	public function search_categories()
	{

		$category_name = $this->input->post('category_name');


		if(!empty($category_name))
		{
			$category_name = ' AND category.category_name LIKE \'%'.mysql_real_escape_string($category_name).'%\' ';
		}
		
		
		$search = $category_name;
		$this->session->set_userdata('category_search', $search);
		
		$this->index();
		
	}
	public function close_categories_search()
	{
		$this->session->unset_userdata('category_search');
		redirect('vendor/all-categories');
	}
}
?>