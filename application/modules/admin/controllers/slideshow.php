<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Slideshow extends admin {
	var $slideshow_path;
	var $slideshow_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('slideshow_model');
		$this->load->model('file_model');
		
		//path to image directory
		$this->slideshow_path = realpath(APPPATH . '../assets/slideshow');
		$this->slideshow_location = base_url().'assets/slideshow/';
	}
    
	/*
	*
	*	Default action is to show all the registered slideshow
	*
	*/
	public function index() 
	{
		$where = 'slideshow_id > 0';
		$table = 'slideshow';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'administration/all-slides';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 5;
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
		$query = $this->slideshow_model->get_all_slides($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['slideshow_location'] = $this->slideshow_location;
			$data['content'] = $this->load->view('slideshow/all_slides', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'administration/add-slide" class="btn btn-success pull-right">Add Slide</a>There are no slides';
		}
		$data['title'] = 'Slideshow';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function add_slide()
	{
		$v_data['slideshow_location'] = 'http://placehold.it/300x300';
		
		$this->session->unset_userdata('slideshow_error_message');
		
		//upload image if it has been selected
		$response = $this->slideshow_model->upload_slideshow_image($this->slideshow_path);
		if($response)
		{
			$v_data['slideshow_location'] = $this->slideshow_location.$this->session->userdata('slideshow_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['slideshow_error'] = $this->session->userdata('slideshow_error_message');
		}
		
		$slideshow_error = $this->session->userdata('slideshow_error_message');
		
		$this->form_validation->set_rules('check', 'check', 'trim|xss_clean');
		$this->form_validation->set_rules('slideshow_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('slideshow_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($slideshow_error))
			{
				$data2 = array(
					'slideshow_name'=>$this->input->post("slideshow_name"),
					'slideshow_description'=>$this->input->post("slideshow_description"),
					'slideshow_image_name'=>$this->session->userdata('slideshow_file_name')
				);
				
				$table = "slideshow";
				$this->db->insert($table, $data2);
				$this->session->unset_userdata('slideshow_file_name');
				$this->session->unset_userdata('slideshow_thumb_name');
				$this->session->unset_userdata('slideshow_error_message');
				$this->session->set_userdata('success_message', 'Slide has been added');
				
				redirect('administration/all-slides');
			}
		}
		
		$table = "slideshow";
		$where = "slideshow_id > 0";
		
		$this->db->where($where);
		$v_data['slides'] = $this->db->get($table);
		
		$slideshow = $this->session->userdata('slideshow_file_name');
		
		if(!empty($slideshow))
		{
			$v_data['slideshow_location'] = $this->slideshow_location.$this->session->userdata('slideshow_file_name');
		}
		$v_data['error'] = $slideshow_error;
		
		$data['content'] = $this->load->view("slideshow/add_slide", $v_data, TRUE);
		$data['title'] = 'Add Slide';
		
		$this->load->view('templates/general_admin', $data);
	}
	
	function edit_slide($slideshow_id, $page)
	{
		//get slideshow data
		$table = "slideshow";
		$where = "slideshow_id = ".$slideshow_id;
		
		$this->db->where($where);
		$slides_query = $this->db->get($table);
		$slide_row = $slides_query->row();
		$v_data['slide_row'] = $slide_row;
		$v_data['slideshow_location'] = $this->slideshow_location.$slide_row->slideshow_image_name;
		
		$this->session->unset_userdata('slideshow_error_message');
		
		//upload image if it has been selected
		$response = $this->slideshow_model->upload_slideshow_image($this->slideshow_path, $edit = $slide_row->slideshow_image_name);
		if($response)
		{
			$v_data['slideshow_location'] = $this->slideshow_location.$this->session->userdata('slideshow_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['slideshow_error'] = $this->session->userdata('slideshow_error_message');
		}
		
		$slideshow_error = $this->session->userdata('slideshow_error_message');
		
		$this->form_validation->set_rules('check', 'check', 'trim|xss_clean');
		$this->form_validation->set_rules('slideshow_name', 'Title', 'trim|xss_clean');
		$this->form_validation->set_rules('slideshow_description', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($slideshow_error))
			{
		
				$slideshow = $this->session->userdata('slideshow_file_name');
				
				if($slideshow == FALSE)
				{
					$slideshow = $slide_row->slideshow_image_name;
				}
				$data2 = array(
					'slideshow_name'=>$this->input->post("slideshow_name"),
					'slideshow_description'=>$this->input->post("slideshow_description"),
					'slideshow_image_name'=>$slideshow
				);
				
				$table = "slideshow";
				$this->db->where('slideshow_id', $slideshow_id);
				$this->db->update($table, $data2);
				$this->session->unset_userdata('slideshow_file_name');
				$this->session->unset_userdata('slideshow_thumb_name');
				$this->session->unset_userdata('slideshow_error_message');
				$this->session->set_userdata('success_message', 'Slide has been edited');
				
				redirect('administration/all-slides/'.$page);
			}
		}
		
		$slideshow = $this->session->userdata('slideshow_file_name');
		
		if(!empty($slideshow))
		{
			$v_data['slideshow_location'] = $this->slideshow_location.$this->session->userdata('slideshow_file_name');
		}
		$v_data['error'] = $slideshow_error;
		
		$data['content'] = $this->load->view("slideshow/edit_slide", $v_data, TRUE);
		$data['title'] = 'Edit Slide';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing slideshow
	*	@param int $slideshow_id
	*
	*/
	function delete_slide($slideshow_id, $page)
	{
		//get slideshow data
		$table = "slideshow";
		$where = "slideshow_id = ".$slideshow_id;
		
		$this->db->where($where);
		$slides_query = $this->db->get($table);
		$slide_row = $slides_query->row();
		$slideshow_path = $this->slideshow_path;
		
		$image_name = $slide_row->slideshow_image_name;
		
		//delete any other uploaded image
		$this->file_model->delete_file($slideshow_path."\\".$image_name, $this->slideshow_path);
		
		//delete any other uploaded thumbnail
		$this->file_model->delete_file($slideshow_path."\\thumbnail_".$image_name, $this->slideshow_path);
		
		if($this->slideshow_model->delete_slideshow($slideshow_id))
		{
			$this->session->set_userdata('success_message', 'Slide has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Slide could not be deleted');
		}
		redirect('administration/all-slides/'.$page);
	}
    
	/*
	*
	*	Activate an existing slideshow
	*	@param int $slideshow_id
	*
	*/
	public function activate_slide($slideshow_id, $page)
	{
		if($this->slideshow_model->activate_slideshow($slideshow_id))
		{
			$this->session->set_userdata('success_message', 'Slide has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Slide could not be activated');
		}
		redirect('administration/all-slides/'.$page);
	}
    
	/*
	*
	*	Deactivate an existing slideshow
	*	@param int $slideshow_id
	*
	*/
	public function deactivate_slide($slideshow_id, $page)
	{
		if($this->slideshow_model->deactivate_slideshow($slideshow_id))
		{
			$this->session->set_userdata('success_message', 'Slide has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Slide could not be disabled');
		}
		redirect('administration/all-slides/'.$page);
	}
}
?>