<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('administration/sync_model');
	}
	
	public function sync_visits()
	{
		
		$date = date('Y-m-d');
		//Sync KDP
		$this->session->set_userdata('branch_code', 'KDP');
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'" AND visit_date = "'.$date.'"');
		$query = $this->db->get('visit');
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$visit_id = $res->visit_id;
				
				if($this->sync_model->syn_up_on_closing_visit($visit_id))
				{
				}
			}
		}
		
		//Sync KDPH
		$this->session->set_userdata('branch_code', 'KDPH');

		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'" AND visit_date = "'.$date.'"');
		$query = $this->db->get('visit');
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$visit_id = $res->visit_id;
				
				if($this->sync_model->syn_up_on_closing_visit($visit_id))
				{
				}
			}
		}
	}
}
?>