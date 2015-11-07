<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// require_once "./application/modules/auth/controllers/auth.php";
class Cloud  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('cloud_model');
	}
	
	public function save_cloud_data()
	{
		$json = file_get_contents('php://input');
	   	$response = $this->cloud_model->save_visit_data($json);

		echo json_encode($response);
	}
	public function sync_up_petty_cash()
	{
		$json = file_get_contents('php://input');
	   	$response = $this->cloud_model->save_petty_cash_data($json);

		echo json_encode($response);

	}
	
	public function cron_sync_up()
	{
		//get all unsynced visits
		$unsynced_visits = $this->cloud_model->get_unsynced_visits();
		
		$patients = array();
		
		if($unsynced_visits->num_rows() > 0)
		{
			//delete all unsynced visits
			$this->db->where('sync_status', 0);
			if($this->db->delete('sync'))
			{
				foreach($unsynced_visits->result() as $res)
				{
					$sync_data = $res->sync_data;
					$sync_table_name = $res->sync_table_name;
					$branch_code = $res->branch_code;
					array_push($patients[$sync_table_name], $value);
					
					/*$response = $this->sync_model->syn_up_on_closing_visit($visit_id);
		
					if($response)
					{
					}*/
				}
				$patients['branch_code'] = $branch_code;
			}
		}
		
		//sync data
		$response = $this->cloud_model->send_unsynced_visits($patients);
		var_dump($response);
		if($response)
		{
		}
		
		else
		{
		}
	}
}
?>