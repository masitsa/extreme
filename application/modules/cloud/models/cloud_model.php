<?php
class Cloud_model extends CI_Model 
{
	public function insert_into_test($text)
	{
		//  instert data into the patients table
		$date = date("Y-m-d H:i:s");
		$patient_data = array('name'=>$text);
		$this->db->insert('test', $patient_data);
		
	}
}
?>