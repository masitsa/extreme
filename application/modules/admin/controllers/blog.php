<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Blog extends admin {
	var $posts_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('blog_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->posts_path = realpath(APPPATH . '../assets/images/posts');
	}
    
	/*
	*
	*	Default action is to show all the posts
	*
	*/
	public function index() 
	{
		$where = 'post.blog_category_id = blog_category.blog_category_id';
		$table = 'post, blog_category';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'all-posts';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = 2;
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
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->blog_model->get_all_posts($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$data['content'] = $this->load->view('blog/all_posts', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'add-post" class="btn btn-success pull-right">Add post</a>There are no posts';
		}
		$data['title'] = 'All posts';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new post
	*
	*/
	public function add_post() 
	{
		//form validation rules
		$this->form_validation->set_rules('blog_category_id', 'Post Category', 'required|xss_clean');
		$this->form_validation->set_rules('created', 'Post Date', 'required|xss_clean');
		$this->form_validation->set_rules('post_title', 'Post Name', 'required|xss_clean');
		$this->form_validation->set_rules('post_status', 'Post Status', 'required|xss_clean');
		$this->form_validation->set_rules('post_content', 'Post Description', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['post_image']['tmp_name']))
			{
				$posts_path = $this->posts_path;
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($posts_path, 'post_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Add New Post';
					$data['content'] = $this->load->view('posts/add_post', '', true);
					$this->load->view('templates/general_admin', $data);
					break;
				}
			}
			
			else{
				$file_name = '';
			}
			
			if($this->blog_model->add_post($file_name, $thumb_name))
			{
				$this->session->set_userdata('success_message', 'Post added successfully');
				redirect('all-posts');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add post. Please try again');
			}
		}
		
		//open the add new post
		$data['title'] = 'Add New post';
		$categories_query = $this->blog_model->get_all_active_categories();
		if($categories_query->num_rows > 0)
		{
			$categories = '<select class="form-control" name="blog_category_id">';
			
			foreach($categories_query->result() as $res)
			{
				$categories .= '<option value="'.$res->blog_category_id.'">'.$res->blog_category_name.'</option>';
			}
			$categories .= '</select>';
			
			$v_data['categories'] = $categories;
			$data['content'] = $this->load->view('blog/add_post', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Please add blog categories to continue. Add categories <a href="'.site_url().'add-blog-category">here</a>';
		}
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Edit an existing post
	*	@param int $post_id
	*
	*/
	public function edit_post($post_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('blog_category_id', 'Post Category', 'required|xss_clean');
		$this->form_validation->set_rules('created', 'Post Date', 'required|xss_clean');
		$this->form_validation->set_rules('post_title', 'Post Name', 'required|xss_clean');
		$this->form_validation->set_rules('post_status', 'Post Status', 'required|xss_clean');
		$this->form_validation->set_rules('post_content', 'Post Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			$resize['width'] = 600;
			$resize['height'] = 800;
			
			if(is_uploaded_file($_FILES['post_image']['tmp_name']))
			{
				$posts_path = $this->posts_path;
				
				//delete original image
				$this->file_model->delete_file($posts_path."\\".$this->input->post('current_image'), $posts_path);
				
				//delete original thumbnail
				$this->file_model->delete_file($posts_path."\\thumbnail_".$this->input->post('current_image'), $posts_path);
				/*
				/*
					-----------------------------------------------------------------------------------------
					Upload image
					-----------------------------------------------------------------------------------------
				*/
				$response = $this->file_model->upload_file($posts_path, 'post_image', $resize);
				if($response['check'])
				{
					$file_name = $response['file_name'];
					$thumb_name = $response['thumb_name'];
				}
			
				else
				{
					$this->session->set_userdata('error_message', $response['error']);
					
					$data['title'] = 'Edit post';
					$query = $this->blog_model->get_post($post_id);
					if ($query->num_rows() > 0)
					{
						$v_data['post'] = $query->result();
						$v_data['all_posts'] = $this->blog_model->all_posts();
						$data['content'] = $this->load->view('posts/edit_post', $v_data, true);
					}
					
					else
					{
						$data['content'] = 'post does not exist';
					}
					
					$this->load->view('templates/general_admin', $data);
					break;
				}
			}
			
			else{
				$file_name = $this->input->post('current_image');
				$thumb_name = 'thumbnail_'.$this->input->post('current_image');
			}
			//update post
			if($this->blog_model->update_post($file_name, $thumb_name, $post_id))
			{
				$this->session->set_userdata('success_message', 'Post updated successfully');
				redirect('all-posts');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update post. Please try again');
			}
		}
		
		//open the add new post
		$data['title'] = 'Edit Post';
		
		//select the post from the database
		$query = $this->blog_model->get_post($post_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['post'] = $query->result();
			
			$categories_query = $this->blog_model->get_all_active_categories();
			if($categories_query->num_rows > 0)
			{
				$categories = '<select class="form-control" name="blog_category_id">';
				
				foreach($categories_query->result() as $res)
				{
					if($v_data['post'][0]->blog_category_id == $res->blog_category_id)
					{
						$categories .= '<option value="'.$res->blog_category_id.'" selected="selected">'.$res->blog_category_name.'</option>';
					}
					else
					{
						$categories .= '<option value="'.$res->blog_category_id.'">'.$res->blog_category_name.'</option>';
					}
				}
				$categories .= '</select>';
				
				$v_data['categories'] = $categories;
			
				$data['content'] = $this->load->view('blog/edit_post', $v_data, true);
			}
			
			else
			{
				$data['content'] = 'Please add blog categories to continue. Add categories <a href="'.site_url().'add-blog-category">here</a>';
			}
		}
		
		else
		{
			$data['content'] = 'post does not exist';
		}
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing post
	*	@param int $post_id
	*
	*/
	public function delete_post($post_id)
	{
		//delete post image
		$query = $this->blog_model->get_post($post_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->post_image;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->posts_path."\\".$image, $this->posts_path);
			//delete thumbnail
			$this->file_model->delete_file($this->posts_path."\\thumbnail_".$image, $this->posts_path);
		}
		//delete posts of that category
		$this->blog_model->delete_post_comments($post_id);
		$this->blog_model->delete_post($post_id);
		$this->session->set_userdata('success_message', 'Post has been deleted');
		redirect('all-posts');
	}
    
	/*
	*
	*	Activate an existing post
	*	@param int $post_id
	*
	*/
	public function activate_post($post_id)
	{
		$this->blog_model->activate_post($post_id);
		$this->session->set_userdata('success_message', 'Post activated successfully');
		redirect('all-posts');
	}
    
	/*
	*
	*	Deactivate an existing post
	*	@param int $post_id
	*
	*/
	public function deactivate_post($post_id)
	{
		$this->blog_model->deactivate_post($post_id);
		$this->session->set_userdata('success_message', 'Post disabled successfully');
		redirect('all-posts');
	}
    
	/*
	*
	*	Post Comments
	*
	*/
	public function comments($post_id = NULL) 
	{
		$where = 'post.post_id = post_comment.post_id';
		if($post_id == NULL)
		{
			$segment = 2;
		}
		
		else
		{
			$where .= ' AND post_comment.post_id = '.$post_id;
			$segment = 3;
		}
		$table = 'post_comment, post';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'comments/'.$post_id;
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
		$query = $this->blog_model->get_comments($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['post_id'] = $post_id;
			$v_data['title'] = $this->blog_model->get_comment_title($post_id);
			$data['content'] = $this->load->view('blog/comments', $v_data, true);
		}
		
		else
		{
			if($post_id != NULL)
			{
				$data['content'] = '<a href="'.site_url().'add-comment/'.$post_id.'" class="btn btn-success pull-right">Add Comment</a>There are no comments';
			}
			
			else
			{
				$data['content'] = 'There are no comments';
			}
		}
		$data['title'] = 'Comments';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new comment
	*
	*/
	public function add_comment($post_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('post_comment_description', 'Description', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{	
			if($this->blog_model->add_comment_admin($post_id))
			{
				$this->session->set_userdata('success_message', 'Comment added successfully');
				redirect('comments/'.$post_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add comment. Please try again');
			}
		}
		
		//open the add new post
		$data['title'] = 'Add Comment';
		$v_data['post_id'] = $post_id;
		$v_data['title'] = $this->blog_model->get_comment_title($post_id);
		$data['content'] = $this->load->view('blog/add_comment', $v_data, true);
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing comment
	*	@param int $post_comment_id
	*	@param int $post_id
	*
	*/
	public function delete_comment($post_comment_id, $post_id = NULL)
	{
		$this->blog_model->delete_comment($post_comment_id);
		$this->session->set_userdata('success_message', 'Comment has been deleted');
		redirect('comments/'.$post_id);
	}
    
	/*
	*
	*	Activate an existing comment
	*	@param int $post_comment_id
	*	@param int $post_id
	*
	*/
	public function activate_comment($post_comment_id, $post_id = NULL)
	{
		$this->blog_model->activate_comment($post_comment_id);
		$this->session->set_userdata('success_message', 'Comment activated successfully');
		redirect('comments/'.$post_id);
	}
    
	/*
	*
	*	Deactivate an existing comment
	*	@param int $post_comment_id
	*	@param int $post_id
	*
	*/
	public function deactivate_comment($post_comment_id, $post_id = NULL)
	{
		$this->blog_model->deactivate_comment($post_comment_id);
		$this->session->set_userdata('success_message', 'Comment disabled successfully');
		redirect('comments/'.$post_id);
	}
    
	/*
	*
	*	Blog Categories
	*
	*/
	public function categories() 
	{
		$where = 'blog_category_id > 0';
		$table = 'blog_category';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'blog-categories';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = 2;
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
		
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->blog_model->get_all_categories($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['categories_query'] = $this->blog_model->get_all_active_categories();
			$data['content'] = $this->load->view('blog/all_categories', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'add-blog-category" class="btn btn-success pull-right">Add Category</a>There are no categories';
		}
		$data['title'] = 'All Categories';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	public function add_blog_category()
	{
		//form validation rules
		$this->form_validation->set_rules('blog_category_parent', 'Parent Category', 'required|xss_clean');
		$this->form_validation->set_rules('blog_category_name', 'Category Name', 'required|xss_clean');
		$this->form_validation->set_rules('blog_category_status', 'Category Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{	
			if($this->blog_model->add_blog_category())
			{
				$this->session->set_userdata('success_message', 'Category added successfully');
				redirect('blog-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add category. Please try again');
			}
		}
		
		//open the add new post
		$data['title'] = 'Add Category';
		$categories_query = $this->blog_model->get_all_active_categories();
		$categories = '<select class="form-control" name="blog_category_parent"><option value="0">No Parent</option>';
		if($categories_query->num_rows > 0)
		{
			
			foreach($categories_query->result() as $res)
			{
				$categories .= '<option value="'.$res->blog_category_id.'">'.$res->blog_category_name.'</option>';
			}
		}
		$categories .= '</select>';
		
		$v_data['categories'] = $categories;
		$data['content'] = $this->load->view('blog/add_category', $v_data, true);
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Edit an existing blog category
	*	@param int $blog_category_id
	*
	*/
	public function edit_blog_category($blog_category_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('blog_category_parent', 'Parent Category', 'required|xss_clean');
		$this->form_validation->set_rules('blog_category_name', 'Category Name', 'required|xss_clean');
		$this->form_validation->set_rules('blog_category_status', 'Category Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update post
			if($this->blog_model->update_blog_category($blog_category_id))
			{
				$this->session->set_userdata('success_message', 'Category updated successfully');
				redirect('blog-categories');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update category. Please try again');
			}
		}
		
		//open the add new post
		$data['title'] = 'Edit Category';
		
		//select the post from the database
		$query = $this->blog_model->get_blog_category($blog_category_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['category'] = $query->result();
			$categories_query = $this->blog_model->get_all_active_categories();
			$categories = '<select class="form-control" name="blog_category_parent"><option value="0">No Parent</option>';
			if($categories_query->num_rows > 0)
			{
				
				foreach($categories_query->result() as $res)
				{
					if($v_data['category'][0]->blog_category_parent == $res->blog_category_id)
					{
						$categories .= '<option value="'.$res->blog_category_id.'" selected="selected">'.$res->blog_category_name.'</option>';
					}
					
					else
					{
						$categories .= '<option value="'.$res->blog_category_id.'">'.$res->blog_category_name.'</option>';
					}
				}
			}
			$categories .= '</select>';
			
			$v_data['categories'] = $categories;
			
			$data['content'] = $this->load->view('blog/edit_blog_category', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'post does not exist';
		}
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing category
	*	@param int $blog_category_id
	*
	*/
	public function delete_blog_category($blog_category_id)
	{
		//delete posts of that category
		$this->blog_model->delete_category_post_comments($blog_category_id);
		$this->blog_model->delete_category_posts($blog_category_id);
		$this->blog_model->delete_blog_category($blog_category_id);
		$this->session->set_userdata('success_message', 'Category has been deleted');
		redirect('blog-categories');
	}
    
	/*
	*
	*	Activate an existing category
	*	@param int $blog_category_id
	*
	*/
	public function activate_blog_category($blog_category_id)
	{
		$this->blog_model->activate_blog_category($blog_category_id);
		$this->session->set_userdata('success_message', 'Category activated successfully');
		redirect('blog-categories');
	}
    
	/*
	*
	*	Deactivate an existing category
	*	@param int $blog_category_id
	*
	*/
	public function deactivate_blog_category($blog_category_id)
	{
		$this->blog_model->deactivate_blog_category($blog_category_id);
		$this->session->set_userdata('success_message', 'Category disabled successfully');
		redirect('blog-categories');
	}
}
?>