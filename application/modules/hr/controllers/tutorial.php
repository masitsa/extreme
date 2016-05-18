<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hr/controllers/hr.php";

class Tutorial extends hr 
{
	var $document_upload_path;
	var $document_upload_location;
	
	function __construct()
	{
		parent:: __construct();
		
	}
	
	public function index() 
	{
		$where = 'tutorial.status = 1';
		$table = 'tutorial';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'tutorial/human-resource';
		$config['total_rows'] = $this->tutorial_model->count_tutorials($table, $where);
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
        $v_data["links"] = $this->pagination->create_links();
		$tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$query = $this->tutorial_model->get_selected_section($tutorial_id);
		$section_id = $this->tutorial_model->get_all_tutorial($table, $where, $config["per_page"], $page);
		$selected_tutorial = $this->tutorial_model->get_selected_sections($tutorial_id);
		$data['title'] = 'Tutorials';
		$v_data['title'] = $data['title'];
		$v_data['query'] = $query;
		$v_data['tutorial_id']=$tutorial_id;
		$v_data['section_id']=$section_id;
		$v_data['page'] = $page;
		$v_data['table'] = $table;
		$v_data['per_page'] = $per_page;
		$data['content'] = $this->load->view('tutorial/human_resource', $v_data, true);
		$this->load->view('admin/templates/general_page',$data);
	}
	
   public function add_human_resource_tutorial()
   {
	   	//form validation rules
		$this->form_validation->set_rules('section_id', 'Section', 'xss_clean');
		$this->form_validation->set_rules('tutorial_name', 'Tutorial Name', 'required|xss_clean');
		$this->form_validation->set_rules('tutorial_desription', 'Turorial Description', 'required|xss_clean');
			//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$hr_tutorial_id = $this->tutorial->add_hr_tutorial();
			if($hr_tutorial_id > 0)
			{
				$this->session->set_userdata("success_message", "Tutorial added successfully");
				redirect('human-resource/edit-personnel/'.$hr_tutorial_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add tutorial. Please try again ".$personnel_id);
			}
		}
		$v_data['sections'] = $this->tutorial_model->get_all_sections();
		$data['title'] = 'Add Human Resource Tutorial';
		$v_data['title'] = $data['title'];
		
	   $data['content'] = $this->load->view('hr/tutorial/human_resource',$v_data,TRUE);
	   $this->load->view('admin/templates/general_page',$data);
	   
   }

	
   public function view_tutorials()
   {
	   $where = 'tutorial.status = 1';
		$table = 'tutorial';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'tutorial/human-resource';
		$config['total_rows'] = $this->tutorial_model->count_tutorials($table, $where);
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
        $v_data["links"] = $this->pagination->create_links();
		$section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$query = $this->tutorial_model->get_selected_section($tutorial_id);
		$selected_tutorial = $this->tutorial_model->get_selected_section($section_id);
		$data['title'] = 'Tutorials';
		$v_data['title'] = $data['title'];
		$v_data['query'] = $query;
		$v_data['tutorial_id']=$tutorial_id;
		$v_data['page'] = $page;
		$v_data['table'] = $table;
		$v_data['where'] = $where;
		//$v_data['per_page'] = $per_page;
	   $tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if($tutorial_id->num_rows() > 0)
	   {
		   foreach ($tutorial_id->result() as $row)
		   {
		   $tutorial_id = $row->tutorial_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   }
	   }
	   $section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if ($section_id ->num_rows() > 0)
	   {
		   foreach ($section_id->result() as $row)
		   {
		   $section_id = $row->section_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   } 
	   }
	   $data['title'] = 'Tutorials';
	   $v_data['title'] = $data['title'];
	   $query = $this->tutorial_model->get_selected_section($tutorial_id);
	   $data['content'] = $this->load->view('hr/tutorial/human_resource',$v_data,TRUE);
	   $this->load->view('admin/templates/general_page',$data);
   }
   
   public function leave()
   {
	  $where = 'tutorial.status = 1';
		$table = 'tutorial';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'tutorial/human-resource';
		$config['total_rows'] = $this->tutorial_model->count_tutorials($table, $where);
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
        $v_data["links"] = $this->pagination->create_links();
		$section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$query = $this->tutorial_model->get_selected_section($tutorial_id);
		$selected_tutorial = $this->tutorial_model->get_selected_section($section_id);
		$data['title'] = 'Tutorials';
		$v_data['title'] = $data['title'];
		$v_data['query'] = $query;
		$v_data['tutorial_id']=$tutorial_id;
		$v_data['page'] = $page;
		$v_data['table'] = $table;
		$v_data['where'] = $where;
		//$v_data['per_page'] = $per_page;
	   $tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if($tutorial_id->num_rows() > 0)
	   {
		   foreach ($tutorial_id->result() as $row)
		   {
		   $tutorial_id = $row->tutorial_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   }
	   }
	   $section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if ($section_id ->num_rows() > 0)
	   {
		   foreach ($section_id->result() as $row)
		   {
		   $section_id = $row->section_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   } 
	   }
	   $data['title'] = 'Tutorials';
	   $v_data['title'] = $data['title'];
	   $query = $this->tutorial_model->get_selected_section($tutorial_id);
	   $data['content'] = $this->load->view('hr/tutorial/leave',$v_data,TRUE);
	   $this->load->view('admin/templates/general_page',$data);  
   }
   
   public function payroll()
   {
	   $where = 'tutorial.status = 1';
		$table = 'tutorial';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'tutorial/human-resource';
		$config['total_rows'] = $this->tutorial_model->count_tutorials($table, $where);
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
        $v_data["links"] = $this->pagination->create_links();
		$section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
		$query = $this->tutorial_model->get_selected_section($tutorial_id);
		$selected_tutorial = $this->tutorial_model->get_selected_section($section_id);
		$data['title'] = 'Tutorials';
		$v_data['title'] = $data['title'];
		$v_data['query'] = $query;
		$v_data['tutorial_id']=$tutorial_id;
		$v_data['page'] = $page;
		$v_data['table'] = $table;
		$v_data['where'] = $where;
		//$v_data['per_page'] = $per_page;
	   $tutorial_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if($tutorial_id->num_rows() > 0)
	   {
		   foreach ($tutorial_id->result() as $row)
		   {
		   $tutorial_id = $row->tutorial_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   }
	   }
	   $section_id = $this->tutorial_model->get_all_tutorials($table, $where, $config["per_page"], $page);
	   if ($section_id ->num_rows() > 0)
	   {
		   foreach ($section_id->result() as $row)
		   {
		   $section_id = $row->section_id;
	   	   $section_name = $this->tutorial_model->get_section($tutorial_id);
		   } 
	   }
	   $data['title'] = 'Tutorials';
	   $v_data['title'] = $data['title'];
	   $query = $this->tutorial_model->get_selected_section($tutorial_id);
	   $data['content'] = $this->load->view('hr/tutorial/payroll',$v_data,TRUE);
	   $this->load->view('admin/templates/general_page',$data); 
   }
}