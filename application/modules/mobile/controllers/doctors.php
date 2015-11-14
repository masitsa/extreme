<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctors extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
		
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
		}

		$this->load->model('mobile/doctors_model.php');
		$this->load->model('nurse/nurse_model.php');
	}
    public function get_doctors_bokings()
    {
    	# code...

    	$where = 'booking_date = "'.date('Y-m-d').'" AND personnel_id ='.$this->session->userdata('personnel_id');
		$table = 'bookings';
		$booking_search = $this->session->userdata('booking_search');
		
		if(!empty($booking_search))
		{
			$where .= $booking_search;
		
			
		}
		$segment = 4;
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/mobile/doctors/get_doctors_bokings';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		$query = $this->doctors_model->get_all_bookings($table, $where, $config["per_page"], $page, 'ASC');
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $visit_search;
		$v_data['total_patients'] = $config['total_rows'];
		$v_data['total_due'] = $this->doctors_model->get_total_collections_due();
		//$v_data['visit_departments'] = $this->reports_model->get_visit_departments($where, $table);
		
		
		
		$data['title'] = $this->session->userdata('page_title');
		$v_data['title'] = $this->session->userdata('page_title');
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$newdata = $this->load->view('doctor/patient_bookings', $v_data, true);
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
    }

	public function save_nurse_notes($visit_id)
	{
		$signature_name = '';
		if(isset($_POST['signature']))
		{
			$this->load->library('signature/signature');
			//require_once 'signature-to-image.php';
	
			$json = $_POST['signature']; // From Signature Pad
			//var_dump($json); die();
			$img = $this->signature->sigJsonToImage($json);
			$signature_name = $this->session->userdata('username').'_signature_'.date('Y-m-d-H-i-s').'.png';
			imagepng($img, $this->signature_path.'\\'.$image_name);
			//imagedestroy($img);
		}
		
		if($this->nurse_model->add_notes($visit_id, $signature_name))
		{
			$v_data['signature_location'] = $this->signature_location;
			$v_data['query'] = $this->nurse_model->get_notes(1);
			$return['result'] = 'success';
			$return['message'] = $this->load->view('nurse/patients/notes', $v_data, TRUE);
			echo 'success';
		}
		
		else
		{
			echo 'fail';
		}
		// end of things to do with the trail
	}
}