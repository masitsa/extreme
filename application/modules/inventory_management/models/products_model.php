<?php

class Products_model extends CI_Model 
{	
	/*
	*	Retrieve all products
	*
	*/
	public function all_products()
	{
		$this->db->where('product_status = 1');
		$query = $this->db->get('product');
		
		return $query;
	}
	
	public function get_all_products($table, $where, $per_page, $page, $limit = NULL, $order_by = 'product.created', $order_method = 'DESC')
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order_by, $order_method);
		
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

	
	public function add_product()
	{
		
		$code = $this->create_product_code($this->input->post('category_id'));
		
		$data = array(
				'product_name'=>ucwords(strtolower($this->input->post('product_name'))),
				'product_status'=>$this->input->post('product_status'),
				'product_description'=>$this->input->post('product_description'),
				'category_id'=>$this->input->post('category_id'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
			);
			
		if($this->db->insert('product', $data))
		{
			
			return TRUE;
		}
		else{
			return FALSE;
		}
		
	}
	/*
	*	Update an existing product
	*	@param string $image_name
	*	@param int $product_id
	*
	*/
	public function update_product($product_id)
	{
		$data = array(
				'product_name'=>ucwords(strtolower($this->input->post('product_name'))),
				'product_status'=>$this->input->post('product_status'),
				'product_description'=>$this->input->post('product_description'),
				'category_id'=>$this->input->post('category_id'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
			);
			
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $data))
		{
			//save locations
			
			
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	
	/*
	*	get a single product's details
	*	@param int $product_id
	*
	*/
	public function get_product($product_id, $personnel_id = NULL)
	{
		//retrieve all users
		$this->db->from('product, category');
		$this->db->select('product.*, category.category_name');
		
		if($personnel_id == NULL)
		{
			$this->db->where('product.category_id = category.category_id AND product_id = '.$product_id);
		}
		
		else
		{
			$this->db->where('product.category_id = category.category_id AND product_id = '.$product_id.' AND product.created_by = '.$personnel_id);
		}
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single product's details
	*	@param int $product_id
	*
	*/
	public function get_product_shipping($product_id, $personnel_id = NULL)
	{
		//retrieve all users
		$this->db->from('product');
		
		$this->db->where('product_id = '.$product_id.' AND product.created_by = '.$personnel_id);
		$query = $this->db->get();
		
		return $query;
	}
	public function recently_viewed_products()
	{
		//retrieve all users
		$this->db->from('product, category');
		$this->db->select('product.*, category.category_name');
		$this->db->where('product.category_id = category.category_id  AND product.product_status = 1');
		$this->db->order_by('product.last_viewed_date','desc');
		$query = $this->db->get('', 10);
		 
		return $query;
	}
	
	/*
	*	Delete an existing product
	*	@param int $product_id
	*
	*/
	public function delete_product($product_id)
	{
		if($this->db->delete('product', array('product_id' => $product_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated product
	*	@param int $product_id
	*
	*/
	public function activate_product($product_id)
	{
		$data = array(
				'product_status' => 1
			);
		$this->db->where('product_id', $product_id);
		
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated product
	*	@param int $product_id
	*
	*/
	public function deactivate_product($product_id)
	{
		$data = array(
				'product_status' => 0
			);
		$this->db->where('product_id', $product_id);
		
		if($this->db->update('product', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function create_product_code($category_id)
	{
		//get category_details
		$query = $this->categories_model->get_category($category_id);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$category_preffix =  $result[0]->category_preffix;
			
			//select product code
			$this->db->from('product');
			$this->db->select('MAX(product_code) AS number');
			$this->db->where("product_code LIKE '".$category_preffix."%'");
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
	
	public function import_csv_products($upload_path)
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