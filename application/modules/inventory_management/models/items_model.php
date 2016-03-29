<?php

class Items_model extends CI_Model 
{	
	/*
	*	Retrieve all items
	*
	*/
	public function all_items()
	{
		$this->db->where('item_status_id = 1');
		$query = $this->db->get('item');
		
		return $query;
	}
	
	public function get_all_items($table, $where, $per_page, $page)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		//$this->db->order_by('item_id');
		
		if(isset($limit))
		{
			$query = $this->db->get('', $limit);
		}
		
		else
		{
			$query = $this->db->get('', $per_page, $page);
		}
		
		return $query;
	}

	
	public function add_item()
	{
		
		//$code = $this->create_item_code($this->input->post('category_id'));
		
		$data = array(
				'item_name'=>ucwords(strtolower($this->input->post('item_name'))),
				'item_status_id'=>$this->input->post('item_status_id'),
				'supplier_id'=>$this->input->post('store_id'),
				'quantity'=>$this->input->post('quantity'),
				'item_unit_price'=>$this->input->post('item_unit_price'),
				'item_description'=>$this->input->post('item_description'),
				'item_category_id'=>$this->input->post('item_category_id'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
			);
			
		if($this->db->insert('item', $data))
		{
			
			return TRUE;
		}
		else{
			return FALSE;
		}
		
	}
	/*
	*	Update an existing item
	*	@param string $image_name
	*	@param int $item_id
	*
	*/
	public function update_item($item_id)
	{
		$data = array(
				'item_name'=>ucwords(strtolower($this->input->post('item_name'))),
				'item_status_id'=>$this->input->post('item_status_id'),
				'item_description'=>$this->input->post('item_description'),
				'category_id'=>$this->input->post('category_id'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
			);
			
		$this->db->where('item_id', $item_id);
		if($this->db->update('item', $data))
		{
			//save locations
			
			
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	
	/*
	*	get a single item's details
	*	@param int $item_id
	*
	*/
	public function get_item($item_id, $personnel_id = NULL)
	{
		//retrieve all users
		$this->db->from('item, item_category');
		$this->db->select('item.*, item_category.item_category_name');
		
		if($personnel_id == NULL)
		{
			$this->db->where('item.item_category_id = item_category.item_category_id AND item_id = '.$item_id);
		}
		
		else
		{
			$this->db->where('item.category_id = category.category_id AND item_id = '.$item_id.' AND item.created_by = '.$personnel_id);
		}
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single item's details
	*	@param int $item_id
	*
	*/
	public function get_item_shipping($item_id, $personnel_id = NULL)
	{
		//retrieve all users
		$this->db->from('item');
		
		$this->db->where('item_id = '.$item_id.' AND item.created_by = '.$personnel_id);
		$query = $this->db->get();
		
		return $query;
	}
	public function recently_viewed_items()
	{
		//retrieve all users
		$this->db->from('item, item_category');
		$this->db->select('item.*, category.category_name');
		$this->db->where('item.category_id = item_category.citem_ategory_id  AND item.item_status_id = 1');
		$this->db->order_by('item.last_viewed_date','desc');
		$query = $this->db->get('', 10);
		 
		return $query;
	}
	
	/*
	*	Delete an existing item
	*	@param int $item_id
	*
	*/
	public function delete_item($item_id)
	{
		if($this->db->delete('item', array('item_id' => $item_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated item
	*	@param int $item_id
	*
	*/
	public function activate_item($item_id)
	{
		$data = array(
				'item_status_id' => 1
			);
		$this->db->where('item_id', $item_id);
		
		if($this->db->update('item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated item
	*	@param int $item_id
	*
	*/
	public function deactivate_item($item_id)
	{
		$data = array(
				'item_status_id' => 0
			);
		$this->db->where('item_id', $item_id);
		
		if($this->db->update('item', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function create_item_code($category_id)
	{
		//get category_details
		$query = $this->items_categories_model->get_category($category_id);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$category_preffix =  $result[0]->category_preffix;
			
			//select item code
			$this->db->from('item');
			$this->db->select('MAX(item_code) AS number');
			$this->db->where("item_code LIKE '".$category_preffix."%'");
			$query = $this->db->get();
			
			if($query->num_rows() > 0)
			{
				$result = $query->result();
				$number =  $result[0]->number;
				$number++;//go to the next number
				
				if($number == 1){
					$number = $category_preffix."001";
				}
			}
			else{//start generating receipt numbers
				$number = $category_preffix."001";
			}
		}
		
		else
		{
			$number = '001';
		}
		
		return $number;
	}
	
	public function import_csv_items($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	
	public function get_category_id($category_name)
	{
		$this->db->where('category_name = \''.$category_name.'\'');
		$query = $this->db->get('category');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$category_id = $row->category_id;
		}
		
		else
		{
			$category_id = '';
		}
		
		return $category_id;
	}
	
	
}
?>