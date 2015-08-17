<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Categories extends admin 
{
	var $categories_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->categories_path = realpath(APPPATH . '../assets/images/categories');
	}
    
	/*
	*
	*	Default action is to show all the categories
	*
	*/
	public function index($order = 'category_name', $order_method = 'ASC') 
	{
		$where = 'category_id > 0';
		$table = 'category';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'admin/categories/'.$order.'/'.$order_method;
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
		$query = $this->categories_model->get_all_categories($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Categories';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_categories'] = $this->categories_model->all_categories();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('categories/all_categories', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
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
					$this->load->view('templates/general_page', $data);
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
				redirect('admin/categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add category. Please try again');
			}
		}
		
		//open the add new category
		
		$data['title'] = 'Add category';
		$v_data['title'] = $data['title'];
		$v_data['all_categories'] = $this->categories_model->all_parent_categories();
		$data['content'] = $this->load->view('categories/add_category', $v_data, true);
		$this->load->view('templates/general_page', $data);
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
				$this->file_model->delete_file($categories_path."\\".$this->input->post('current_image'));
				
				//delete original thumbnail
				$this->file_model->delete_file($categories_path."\\thumbnail_".$this->input->post('current_image'));
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
					
					$this->load->view('templates/general_page', $data);
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
				redirect('admin/categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update category. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Edit category';
		$v_data['title'] = $data['title'];
		
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
		
		$this->load->view('templates/general_page', $data);
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
			$this->file_model->delete_file($this->categories_path."/images/".$image);
			//delete thumbnail
			$this->file_model->delete_file($this->categories_path."/thumbs/".$image);
		}
		$this->categories_model->delete_category($category_id);
		$this->session->set_userdata('success_message', 'Category has been deleted');
		redirect('admin/categories');
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
		redirect('admin/categories');
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
		redirect('admin/categories');
	}
}
?>