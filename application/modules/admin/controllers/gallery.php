<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Gallery extends admin {
	var $gallery_path;
	var $gallery_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('gallery_model');
		$this->load->model('department_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->gallery_path = realpath(APPPATH . '../assets/gallery');
		$this->gallery_location = base_url().'assets/gallery/';
	}
    
	/*
	*
	*	Default action is to show all the registered gallery
	*
	*/
	public function index() 
	{
		$where = 'gallery.department_id = department.department_id';
		$table = 'gallery, department';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-gallery-images';
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
		$query = $this->gallery_model->get_all_gallerys($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['gallery_location'] = $this->gallery_location;
			$v_data['active_departments'] = $this->department_model->get_active_departments();
			$data['content'] = $this->load->view('gallery/all_images', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-gallery" class="btn btn-success pull-right">Add image</a>There are no gallery images';
		}
		$data['title'] = 'All gallery images';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function add_gallery()
	{
		$v_data['gallery_location'] = 'http://placehold.it/500x500';
		
		$this->session->unset_userdata('gallery_error_message');
		
		//upload image if it has been selected
		$response = $this->gallery_model->upload_gallery_image($this->gallery_path);
		if($response)
		{
			$v_data['gallery_location'] = $this->gallery_location.$this->session->userdata('gallery_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['gallery_error'] = $this->session->userdata('gallery_error_message');
		}
		
		$gallery_error = $this->session->userdata('gallery_error_message');
		
		$this->form_validation->set_rules('gallery_name', 'Title', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($gallery_error))
			{
				$data2 = array(
					'gallery_name'=>$this->input->post("gallery_name"),
					'department_id'=>$this->input->post("department_id"),
					'gallery_status'=>1,
					'gallery_image_name'=>$this->session->userdata('gallery_file_name')
				);
				
				$table = "gallery";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('gallery_file_name');
				$this->session->unset_userdata('gallery_thumb_name');
				$this->session->unset_userdata('gallery_error_message');
				$this->session->set_userdata('success_message', 'Gallery has been added');
				
				redirect('administration/all-gallery-images');
			}
		}
		
		$gallery = $this->session->userdata('gallery_file_name');
		
		if(!empty($gallery))
		{
			$v_data['gallery_location'] = $this->gallery_location.$this->session->userdata('gallery_file_name');
		}
		$v_data['error'] = $gallery_error;
		$v_data['active_departments'] = $this->department_model->get_active_departments();
		
		$data['content'] = $this->load->view("gallery/add_gallery", $v_data, TRUE);
		$data['title'] = 'Add Gallery';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function edit_gallery($gallery_id, $page)
	{
		//get gallery data
		$table = "gallery";
		$where = "gallery_id = ".$gallery_id;
		
		$this->db->where($where);
		$gallerys_query = $this->db->get($table);
		$gallery_row = $gallerys_query->row();
		$v_data['gallery_row'] = $gallery_row;
		$v_data['gallery_location'] = $this->gallery_location.$gallery_row->gallery_image_name;
		
		$this->session->unset_userdata('gallery_error_message');
		
		//upload image if it has been selected
		$response = $this->gallery_model->upload_gallery_image($this->gallery_path, $edit = $gallery_row->gallery_image_name);
		if($response)
		{
			$v_data['gallery_location'] = $this->gallery_location.$this->session->userdata('gallery_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['gallery_error'] = $this->session->userdata('gallery_error_message');
		}
		
		$gallery_error = $this->session->userdata('gallery_error_message');
		
		$this->form_validation->set_rules('gallery_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('gallery_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($gallery_error))
			{
		
				$gallery = $this->session->userdata('gallery_file_name');
				
				if($gallery == FALSE)
				{
					$gallery = $gallery_row->gallery_image_name;
				}
				$data2 = array(
					'gallery_name'=>$this->input->post("gallery_name"),
					'department_id'=>$this->input->post("department_id"),
					'gallery_image_name'=>$gallery
				);
				
				$table = "gallery";
				$this->db->where('gallery_id', $gallery_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('gallery_file_name');
				$this->session->unset_userdata('gallery_thumb_name');
				$this->session->unset_userdata('gallery_error_message');
				$this->session->set_userdata('success_message', 'Gallery has been edited');
				
				redirect('administration/all-gallery-images/'.$page);
			}
		}
		
		$gallery = $this->session->userdata('gallery_file_name');
		
		if(!empty($gallery))
		{
			$v_data['gallery_location'] = $this->gallery_location.$this->session->userdata('gallery_file_name');
		}
		$v_data['error'] = $gallery_error;
		$v_data['services'] = $this->service_model->get_active_services();
		
		$data['content'] = $this->load->view("gallery/edit_gallery", $v_data, TRUE);
		$data['title'] = 'Edit Gallery';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing gallery
	*	@param int $gallery_id
	*
	*/
	function delete_gallery($gallery_id, $page)
	{
		//get gallery data
		$table = "gallery";
		$where = "gallery_id = ".$gallery_id;
		
		$this->db->where($where);
		$gallerys_query = $this->db->get($table);
		$gallery_row = $gallerys_query->row();
		$gallery_path = $this->gallery_path;
		
		$image_name = $gallery_row->gallery_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($gallery_path."\\".$image_name);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($gallery_path."\\thumbnail_".$image_name);
		
		if($this->gallery_model->delete_gallery($gallery_id))
		{
			$this->session->set_userdata('success_message', 'Gallery has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Gallery could not be deleted');
		}
		redirect('administration/all-gallery-images/'.$page);
	}
    
	/*
	*
	*	Activate an existing gallery
	*	@param int $gallery_id
	*
	*/
	public function activate_gallery($gallery_id, $page)
	{
		if($this->gallery_model->activate_gallery($gallery_id))
		{
			$this->session->set_userdata('success_message', 'Gallery has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Gallery could not be activated');
		}
		redirect('administration/all-gallery-images/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing gallery
	*	@param int $gallery_id
	*
	*/
	public function deactivate_gallery($gallery_id, $page)
	{
		if($this->gallery_model->deactivate_gallery($gallery_id))
		{
			$this->session->set_userdata('success_message', 'Gallery has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Gallery could not be disabled');
		}
		redirect('administration/all-gallery-images/'.$page);
	}
}
?>