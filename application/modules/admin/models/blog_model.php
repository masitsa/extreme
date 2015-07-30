<?php

class Blog_model extends CI_Model 
{	
	/*
	*	Retrieve all active categories
	*
	*/
	public function get_all_active_categories($limit = NULL, $order = 'blog_category_name', $order_method = 'ASC', $where ='blog_category_status = 1' )
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('blog_category');
		
		return $query;
	}
	
	public function get_all_post_categories($blog_category_id)
	{
		$this->db->where('blog_category.blog_category_id = '.$blog_category_id.' OR blog_category.blog_category_parent = '.$blog_category_id);
		$this->db->order_by('blog_category_parent, blog_category_name');
		$query = $this->db->get('blog_category');
		
		return $query;
	}
	
	public function count_posts($blog_category_id)
	{
		$this->db->where('blog_category.blog_category_id = post.blog_category_id AND blog_category.blog_category_id = '.$blog_category_id.' OR blog_category.blog_category_parent = '.$blog_category_id);
		$total = $this->db->count_all_results('blog_category, post');
		
		return $total;
	}
	
	/*
	*	Retrieve all active categories
	*
	*/
	public function get_all_active_category_parents()
	{
		$this->db->where('blog_category_status = 1 AND blog_category_parent = 0');
		$this->db->order_by('blog_category_name');
		$query = $this->db->get('blog_category');
		
		return $query;
	}
	
	/*
	*	Retrieve all active children
	*
	*/
	public function get_all_active_category_children($blog_category_id)
	{
		$this->db->where('blog_category_status = 1 AND blog_category_parent = '.$blog_category_id);
		$this->db->order_by('blog_category_name');
		$query = $this->db->get('blog_category');
		
		return $query;
	}
	/*
	*	Retrieve all active posts
	*
	*/
	public function all_active_posts()
	{
		$this->db->where('post_status = 1');
		$query = $this->db->get('post');
		
		return $query;
	}
	
	/*
	*	Retrieve latest post
	*
	*/
	public function latest_post()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('post');
		
		return $query;
	}
	
	/*
	*	Retrieve all posts
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_posts($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('post.*, blog_category.blog_category_name');
		$this->db->where($where);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new post
	*	@param string $image_name
	*
	*/
	public function add_post($image_name, $thumb_name)
	{
		$data = array(
				'post_title'=>ucwords(strtolower($this->input->post('post_title'))),
				'post_status'=>$this->input->post('post_status'),
				'post_content'=>$this->input->post('post_content'),
				'blog_category_id'=>$this->input->post('blog_category_id'),
				'created'=>$this->input->post('created'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'post_thumb'=>$thumb_name,
				'post_image'=>$image_name
			);
			
		if($this->db->insert('post', $data))
		{
			$post_id = $this->db->insert_id();
			$tiny_url = $this->getTinyUrl(site_url()."blog/post/".$post_id);
			
			$url_data['tiny_url'] = $tiny_url;
			
			$this->db->where('post_id', $post_id);
			if($this->db->update('post', $url_data))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	
	function getTinyUrl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://tinyurl.com/api-create.php?url=".$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tinyurl = curl_exec($ch);
		curl_close($ch);
		//$tinyurl = file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
		return $tinyurl;
	}
	
	/*
	*	Update an existing post
	*	@param string $image_name
	*	@param int $post_id
	*
	*/
	public function update_post($image_name, $thumb_name, $post_id)
	{
		$data = array(
				'post_title'=>ucwords(strtolower($this->input->post('post_title'))),
				'post_status'=>$this->input->post('post_status'),
				'post_content'=>$this->input->post('post_content'),
				'blog_category_id'=>$this->input->post('blog_category_id'),
				'created'=>$this->input->post('created'),
				'modified_by'=>$this->session->userdata('user_id'),
				'post_thumb'=>$thumb_name,
				'post_image'=>$image_name,
				'tiny_url'=>$this->getTinyUrl(site_url()."blog/post/".$post_id)
			);
			
		$this->db->where('post_id', $post_id);
		if($this->db->update('post', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single post's details
	*	@param int $post_id
	*
	*/
	public function get_post($post_id)
	{
		//retrieve all users
		$this->db->from('post,blog_category');
		$this->db->select('*');
		$this->db->where('post.blog_category_id = blog_category.blog_category_id AND post_id = '.$post_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing post's comments
	*	@param int $post_id
	*
	*/
	public function delete_post_comments($post_id)
	{
		if($this->db->delete('post_comment', array('post_id' => $post_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing post
	*	@param int $post_id
	*
	*/
	public function delete_post($post_id)
	{
		if($this->db->delete('post', array('post_id' => $post_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated post
	*	@param int $post_id
	*
	*/
	public function activate_post($post_id)
	{
		$data = array(
				'post_status' => 1
			);
		$this->db->where('post_id', $post_id);
		
		if($this->db->update('post', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated post
	*	@param int $post_id
	*
	*/
	public function deactivate_post($post_id)
	{
		$data = array(
				'post_status' => 0
			);
		$this->db->where('post_id', $post_id);
		
		if($this->db->update('post', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve comments
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_comments($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('post.post_title, post.created, post.post_image, post_comment.*');
		$this->db->where($where);
		$this->db->order_by('comment_created', 'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new comment
	*	@param int $post_id
	*
	*/
	public function add_comment_admin($post_id)
	{
		$data = array(
				'post_comment_description'=>$this->input->post('post_comment_description'),
				'comment_created'=>date('Y-m-d H:i:s'),
				'post_comment_user'=>$this->session->userdata('first_name'),
				'post_comment_email'=>$this->session->userdata('email'),
				'post_id'=>$post_id
			);
			
		if($this->db->insert('post_comment', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new comment
	*	@param int $post_id
	*
	*/
	public function add_comment_user($post_id)
	{
		$data = array(
				'post_comment_description'=>$this->input->post('post_comment_description'),
				'comment_created'=>date('Y-m-d H:i:s'),
				'post_comment_user'=>$this->input->post('name'),
				'post_comment_email'=>$this->input->post('email'),
				'post_comment_status'=>0,
				'post_id'=>$post_id
			);
			
		if($this->db->insert('post_comment', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_comment_title($post_id)
	{
		if($post_id > 0)
		{
			$query = $this->get_post($post_id);
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$title = $row->post_title;
			}
			
			else
			{
				$title = '';
			}
		}
			
		else
		{
			$title = '';
		}
		
		return $title;	
	}
	
	/*
	*	Delete an existing comment
	*	@param int $post_comment_id
	*
	*/
	public function delete_comment($post_comment_id)
	{
		if($this->db->delete('post_comment', array('post_comment_id' => $post_comment_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated comment
	*	@param int $post_comment_id
	*
	*/
	public function activate_comment($post_comment_id)
	{
		$data = array(
				'post_comment_status' => 1
			);
		$this->db->where('post_comment_id', $post_comment_id);
		
		if($this->db->update('post_comment', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated comment
	*	@param int $post_comment_id
	*
	*/
	public function deactivate_comment($post_comment_id)
	{
		$data = array(
				'post_comment_status' => 0
			);
		$this->db->where('post_comment_id', $post_comment_id);
		
		if($this->db->update('post_comment', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function update_views_count($post_id)
	{
		//get count of views
		$this->db->where('post_id', $post_id);
		$query = $this->db->get('post');
		$row = $query->row();
		$total = $row->post_views;
		
		//increment comments
		$total++;
		
		//update
		$data = array(
				'post_views' => $total
			);
		$this->db->where('post_id', $post_id);
		
		if($this->db->update('post', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve all categories
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_categories($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('blog_category_name', 'ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new category
	*	@param int $post_id
	*
	*/
	public function add_blog_category()
	{
		$data = array(
				'blog_category_name'=>ucwords(strtolower($this->input->post('blog_category_name'))),
				'blog_category_status'=>$this->input->post('blog_category_status'),
				'blog_category_parent'=>$this->input->post('blog_category_parent'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('blog_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param int $blog_category_id
	*
	*/
	public function update_blog_category($blog_category_id)
	{
		$data = array(
				'blog_category_name'=>ucwords(strtolower($this->input->post('blog_category_name'))),
				'blog_category_status'=>$this->input->post('blog_category_status'),
				'blog_category_parent'=>$this->input->post('blog_category_parent'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		$this->db->where('blog_category_id', $blog_category_id);
		if($this->db->update('blog_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single category's details
	*	@param int $blog_category_id
	*
	*/
	public function get_blog_category($blog_category_id)
	{
		//retrieve all users
		$this->db->from('blog_category');
		$this->db->select('*');
		$this->db->where('blog_category_id = '.$blog_category_id);
		$query = $this->db->get();
		
		return $query;
	}

	public function check_previous_post($post_id)
	{
		//retrieve all users
		$this->db->from('post');
		$this->db->select('*');
		$this->db->where('post_status = 1 AND post_id < '.$post_id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function check_post_post($post_id)
	{
		//retrieve all users
		$this->db->from('post');
		$this->db->select('*');
		$this->db->where('post_status = 1 AND post_id > '.$post_id);
		$query = $this->db->get();
				
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing post's comments by category id
	*	@param int $blog_category_id
	*
	*/
	public function delete_category_post_comments($blog_category_id)
	{
		$this->db->where(array('blog_category_id' => $blog_category_id));
		$this->db->select('post_id');
		$query = $this->db->get('post');
		$row = $query->row();
		$post_id = $row->post_id;
		
		if($this->db->delete('post_comment', array('post_id' => $post_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing post
	*	@param int $blog_category_id
	*
	*/
	public function delete_category_posts($blog_category_id)
	{
		if($this->db->delete('post', array('blog_category_id' => $blog_category_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing category
	*	@param int $blog_category_id
	*
	*/
	public function delete_blog_category($blog_category_id)
	{
		if($this->db->delete('blog_category', array('blog_category_id' => $blog_category_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated category
	*	@param int $blog_category_id
	*
	*/
	public function activate_blog_category($blog_category_id)
	{
		$data = array(
				'blog_category_status' => 1
			);
		$this->db->where('blog_category_id', $blog_category_id);
		
		if($this->db->update('blog_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated category
	*	@param int $blog_category_id
	*
	*/
	public function deactivate_blog_category($blog_category_id)
	{
		$data = array(
				'blog_category_status' => 0
			);
		$this->db->where('blog_category_id', $blog_category_id);
		
		if($this->db->update('blog_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve recent posts
	*
	*/
	public function get_recent_posts($num = 6)
	{
		$this->db->select('post.*');
		$this->db->where('post_status = 1');
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('post', $num);
		
		return $query;
	}
	
	/*
	*	Retrieve popular posts
	*
	*/
	public function get_popular_posts()
	{
		$this->db->from('post');
		$this->db->select('post.*');
		$this->db->where('post_status = 1');
		$this->db->order_by('post_views', 'DESC');
		$query = $this->db->get('', 3);
		
		return $query;
	}
	
	/*
	*	Retrieve related posts
	*
	*/
	public function get_related_posts($blog_category_id, $post_id)
	{
		$this->db->from('post, blog_category');
		$this->db->select('post.*');
		$this->db->where('post.post_id <> '.$post_id.' AND post.post_status = 1 AND post.blog_category_id = blog_category.blog_category_id AND (blog_category.blog_category_id = '.$blog_category_id.' OR blog_category.blog_category_parent = '.$blog_category_id.')');
		$this->db->order_by('post.created', 'DESC');
		$query = $this->db->get('', 4);
		
		return $query;
	}
	
	/*
	*	Retrieve comments
	* 	@param int $post_id
	*
	*/
	public function get_post_comments($post_id)
	{
		//retrieve all users
		$this->db->from('post_comment');
		$this->db->select('post_comment.*');
		$this->db->where('post_comment_status = 1 AND post_id = '.$post_id);
		$this->db->order_by('comment_created', 'DESC');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_post_id($post_title)
	{
		//retrieve all users
		$this->db->from('post');
		$this->db->select('post_id');
		$this->db->where('post_title', $post_title);
		$query = $this->db->get();
		$post_id = FALSE;
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$post_id = $row->post_id;
		}
		
		return $post_id;
	}
	
	/*
	*	Retrieve all posts per given category
	*	@param int $blog_category_id
	*	@param int $limit
	*
	*/
	public function get_category_posts($blog_category_id, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from('post');
		$this->db->select('post.*');
		$this->db->where('post.post_status = 1 AND post.blog_category_id = '.$blog_category_id);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get();
		
		return $query;
	}
}
?>