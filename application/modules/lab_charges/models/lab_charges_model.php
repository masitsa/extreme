<?php

class Lab_charges_model extends CI_Model 
{


	public function get_all_test_list($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('lab_test.lab_test_class_id','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_all_test_classes($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('lab_test_class.lab_test_class_id','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function add_classes()
	{
		
		$class_name = $this->input->post('class_name');
		
		//  check if this class name exisit
		$check_rs = $this->check_class($class_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"lab_test_class_name" => $class_name
				);
			$this->database->insert_entry('lab_test_class', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function edit_lab_test_class($class_id)
	{
		$class_name = $this->input->post('class_name');
		$insert = array(
				"lab_test_class_name" => $class_name
			);
		$this->db->where('lab_test_class_id',$class_id);
		$this->db->update('lab_test_class', $insert);

		return TRUE;
		
		// end of checking
	}

	public function check_class($class_name)
	{
		$table = "lab_test_class";
		$where = "lab_test_class_name = '$class_name'";
		$items = "*";
		$order = "lab_test_class_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_lab_classes()
	{
		$this->db->order_by('lab_test_class_name');
		$query = $this->db->get('lab_test_class');
		
		return $query;
	}
	public function add_lab_test()
	{
		
		$lab_test_class_id = $this->input->post('lab_test_class_id');
		$lab_test_name = $this->input->post('lab_test_name');
		$units = $this->input->post('units');
		$price = $this->input->post('price');
		$male_upper_limit = $this->input->post('male_upper_limit');
		$male_lower_limit = $this->input->post('male_lower_limit');
		$female_upper_limit = $this->input->post('female_upper_limit');
		$female_lower_limit = $this->input->post('female_lower_limit');
		
		//  check if this class name exisit
		$check_rs = $this->check_lab_test($lab_test_class_id,$lab_test_name);
		if(count($check_rs) > 0)
		{
			return FALSE;

		}
		else
		{

			$insert = array(
					"lab_test_name" => $lab_test_name,
					"lab_test_class_id" => $lab_test_class_id,
					"lab_test_price" => $price,
					"lab_test_units" => $units,
					"lab_test_malelowerlimit" => $male_lower_limit,
					"lab_test_malelupperlimit" => $male_upper_limit,
					"lab_test_femalelowerlimit" => $female_lower_limit,
					"lab_test_femaleupperlimit" => $female_upper_limit
				);
			$this->database->insert_entry('lab_test', $insert);

			return TRUE;
		}
		// end of checking
		
	}
	public function add_test_format($test_id)
	{
		$lab_test_format = $this->input->post('lab_test_format');
		$units = $this->input->post('units');
		$male_upper_limit = $this->input->post('male_upper_limit');
		$male_lower_limit = $this->input->post('male_lower_limit');
		$female_upper_limit = $this->input->post('female_upper_limit');
		$female_lower_limit = $this->input->post('female_lower_limit');
		
		//  check if this class name exisit
		$check_rs = $this->check_lab_testformat($test_id,$lab_test_format);
		if(count($check_rs) > 0)
		{
			return FALSE;
		}
		else
		{
			$insert = array(
					"lab_test_formatname" => $lab_test_format,
					"lab_test_id" => $test_id,
					"lab_test_format_units" => $units,
					"lab_test_format_malelowerlimit" => $male_lower_limit,
					"lab_test_format_maleupperlimit" => $male_upper_limit,
					"lab_test_format_femalelowerlimit" => $female_lower_limit,
					"lab_test_format_femaleupperlimit" => $female_upper_limit
				);
			$this->database->insert_entry('lab_test_format', $insert);

			return TRUE;
		}
		// end of checking
	}
	public function check_lab_testformat($test_id,$lab_test_format)
	{
		$table = "lab_test_format";
		$where = "lab_test_id = '$test_id' AND lab_test_formatname = '$lab_test_format'";
		$items = "*";
		$order = "lab_test_format_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function check_lab_test($lab_test_class_id,$lab_test_name)
	{
		$table = "lab_test";
		$where = "lab_test_class_id = '$lab_test_class_id' AND lab_test_name = '$lab_test_name'";
		$items = "*";
		$order = "lab_test_class_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_lab_test_details($test_id)
	{
		$this->db->from('lab_test');
		$this->db->select('*');
		$this->db->where('lab_test_id = \''.$test_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function get_lab_test_format_details($format_id)
	{
		$this->db->from('lab_test_format');
		$this->db->select('*');
		$this->db->where('lab_test_format_id = \''.$format_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	public function edit_lab_test($test_id)
	{
		$lab_test_class_id = $this->input->post('lab_test_class_id');
		$lab_test_name = $this->input->post('lab_test_name');
		$units = $this->input->post('units');
		$price = $this->input->post('price');
		$male_upper_limit = $this->input->post('male_upper_limit');
		$male_lower_limit = $this->input->post('male_lower_limit');
		$female_upper_limit = $this->input->post('female_upper_limit');
		$female_lower_limit = $this->input->post('female_lower_limit');

		$insert = array(
				"lab_test_name" => $lab_test_name,
				"lab_test_class_id" => $lab_test_class_id,
				"lab_test_price" => $price,
				"lab_test_units" => $units,
				"lab_test_malelowerlimit" => $male_lower_limit,
				"lab_test_malelupperlimit" => $male_upper_limit,
				"lab_test_femalelowerlimit" => $female_lower_limit,
				"lab_test_femaleupperlimit" => $female_upper_limit
			);
		$this->db->where('lab_test_id', $test_id);
		$this->db->update('lab_test', $insert);

		return TRUE;
		
		// end of checking
	}

	public function edit_lab_test_format($test_id,$format_id)
	{
		$lab_test_format = $this->input->post('lab_test_format');
		$units = $this->input->post('units');
		$male_upper_limit = $this->input->post('male_upper_limit');
		$male_lower_limit = $this->input->post('male_lower_limit');
		$female_upper_limit = $this->input->post('female_upper_limit');
		$female_lower_limit = $this->input->post('female_lower_limit');

		$insert = array(
				"lab_test_formatname" => $lab_test_format,
				"lab_test_format_units" => $units,
				"lab_test_format_malelowerlimit" => $male_lower_limit,
				"lab_test_format_maleupperlimit" => $male_upper_limit,
				"lab_test_format_femalelowerlimit" => $female_lower_limit,
				"lab_test_format_femaleupperlimit" => $female_upper_limit
			);
		$this->db->where('lab_test_format_id', $format_id);
		$this->db->update('lab_test_format', $insert);

		return TRUE;
	}
	public function get_all_tests_formats($test_id)
	{
		$this->db->from('lab_test_format');
		$this->db->select('*');
		$this->db->where('lab_test_id = \''.$test_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	function get_all_tests($class_id)
	{
		$this->db->from('lab_test');
		$this->db->select('*');
		$this->db->where('lab_test_class_id = \''.$class_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	function get_class_details($class_id)
	{
		$this->db->from('lab_test_class');
		$this->db->select('*');
		$this->db->where('lab_test_class_id = \''.$class_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
}
?>