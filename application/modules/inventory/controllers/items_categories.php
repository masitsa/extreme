<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Items_categories extends MX_Controller {
	var $categories_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('items_categories_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('site/site_model');
		$this->load->model('administration/personnel_model');
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
		$table = 'item_category';

		$category_search = $this->session->userdata('category_search');
		
		if(!empty($category_search))
		{
			$where .= $category_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'item-categories';
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
		$query = $this->items_categories_model->get_all_categories($table, $where, $config["per_page"], $page);
	
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['title'] = 'All Item Categories';
		$data['content'] = $this->load->view('itemcategories/all_item_categories', $v_data, true);
		
		$data['title'] = 'All Item Categories';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new category
	*
	*/
	public function add_item_category() 
	{
		//form validation rules
		$this->form_validation->set_rules('category_name', 'Category Name', 'required|xss_clean');
		$this->form_validation->set_rules('category_parent', 'Category Parent', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->items_categories_model->add_category())
			{
				$this->session->set_userdata('success_message', 'Category added successfully');
				redirect('inventory-setup/item-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add category. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Add New Item Category';
		$v_data['title'] = 'Add New Item Category';
		$v_data['all_categories'] = $this->items_categories_model->all_parent_categories();
		$data['content'] = $this->load->view('itemcategories/add_item_category', $v_data, true);
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
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			if($this->items_categories_model->update_category($category_id))
			{
				$this->session->set_userdata('success_message', 'Category updated successfully');
				redirect('inventory-setup/item-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update category. Please try again');
			}
		}
		
		//open the add new category
		$data['title'] = 'Edit Category';
		$v_data['title'] = 'Edit Category';
		
		//select the category from the database
		$query = $this->items_categories_model->get_category($category_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['category'] = $query->result();
			$v_data['all_categories'] = $this->items_categories_model->all_parent_categories();
			
			$data['content'] = $this->load->view('itemcategories/edit_item_category', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Item Category does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing category
	*	@param int $category_id
	*
	*/
	public function delete_category($item_category_id)
	{
		//delete category image
		$query = $this->items_categories_model->get_category($item_category_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			//$image = $result[0]->category_image_name;
			
		}
		$this->items_categories_model->delete_category($item_category_id);
		$this->session->set_userdata('success_message', 'Item Category has been deleted');
		redirect('inventory-setup/item-categories');
	}
    
	/*
	*
	*	Activate an existing category
	*	@param int $category_id
	*
	*/
	public function activate_category($category_id)
	{
		$this->items_categories_model->activate_category($category_id);
		$this->session->set_userdata('success_message', 'Category activated successfully');
		redirect('inventory-setup/item-categories');
	}
    
	/*
	*
	*	Deactivate an existing category
	*	@param int $category_id
	*
	*/
	public function deactivate_category($item_category_id)
	{
		$this->items_categories_model->deactivate_category($item_category_id);
		$this->session->set_userdata('success_message', 'Item Category disabled successfully');
		redirect('inventory-setup/item-categories');
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
		redirect('inventory-setup/item-categories');
	}
}
?>