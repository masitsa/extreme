<?php

class Items_model extends CI_Model 
{	
	/*
	*	Retrieve all items
	*
	*/
	public function all_items()
	{
		$this->db->where('item_status_id = 1 AND product_deleted=0');
		$query = $this->db->get('item');
		
		return $query;
	}
	
	public function get_item_category($item_id)
	{
		$query = $this->db->query('select category_name from item_category WHERE item_category_id in (select item_category_id from item where item_id='.$item_id.')');
		
		return $query;
		
	}
	
	public function all_unselected_items($request_id){
		
		$query = $this->db->query('select * from item where item_status_id = 1 and item_id not in(select item_id from request_item where request_id='.$request_id.')');
		
		return $query;
	}
	public function get_all_items($table, $where, $per_page, $page, $order, $order_method)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		
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
				'asset_id'=>$this->input->post('asset_id'),
				'purchase_price'=>$this->input->post('purchase_price'),
				'scrap_value'=>$this->input->post('scrap_value'),
				'condition'=>$this->input->post('condition'),
				'serial_number'=>$this->input->post('serial_number'),
				'asset_barcode'=>$this->input->post('asset_barcode'),
				'condition_id'=>$this->input->post('condition_id'),
				'status'=>$this->input->post('status'),
				'model'=>$this->input->post('model'),
				'brand'=>$this->input->post('brand'),
				'location'=>$this->input->post('location'),
				'manufacturer'=>$this->input->post('manufacturer'),
				'minimum_hiring_price'=>$this->input->post('minimum_hiring_price'),
				'item_description'=>$this->input->post('item_description'),
				'item_category_id'=>$this->input->post('item_category_id'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'created'=>date('Y-m-d H:i:s'),
				'product_deleted'=>0,
				//'deleted_on'=>date('Y-m-d H:i:s'),
				//'deleted_by'=>$this->session->userdata('personnel_id'),
				'created_by'=>$this->session->userdata('personnel_id'),
				
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
				'item_hiring_price'=>$this->input->post('item_hiring_price'),
				'minimum_hiring_price'=>$this->input->post('minimum_hiring_price'),
				'item_unit_price'=>$this->input->post('item_unit_price'),
				'item_category_id'=>$this->input->post('item_category_id'),
				'created'=>date('Y-m-d H:i:s'),
				'quantity'=>$this->input->post('quantity'),
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
		$this->db->select('item.*, item_category.category_name');
		
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
		$data = array(
			'product_deleted'=>1,
			'deleted_on'=>date('Y-m-d H:i:s'),
			'deleted_by'=>$this->session->userdata('personnel_id'),
		);
		$this->db->where('item_id', $item_id);	
			if($this->db->update('item',$data))
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
		else
		{
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
	
	public function get_category_name($item_category_id)
	{
		$this->db->from('item_category');
		$this->db->select('category_name');
		$this->db->where('item_category_id ='.$item_category_id);
		$query = $this->db->get('');
		$category = $query->result();
		if($category > 0)
		{
			foreach($category as $category_details)
			{
				$category_name = $category_details->category_name;
			}
		}
		return $category_name;
	}
		
	public function check_current_number_exisits($asset_id)
	{
		$this->db->from('item');
		$this->db->where('asset_id', $asset_id);
		
		$query = $this->db->get('');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
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
				
				if($number == 1)
				{
					$number = $category_preffix."001";
				}
			}
			else
			{//start generating receipt numbers
				$number = $category_preffix."001";
			}
		}
		
		else
		{
			$number = '001';
		}
		
		return $number;
	}
	function import_template()
	{
		$this->load->library('Excel');
		
		$title = 'Extreme Items Import Template';
		$count=1;
		$r = 0;
		
		$array[$r][0] = 'Asset ID';
		$array[$r][1] = 'Asset Barcode';
		$array[$r][2] = 'Serial Number';
		$array[$r][3] = 'Model';
		$array[$r][4] = 'Condition ID';
		$array[$r][5] = 'Scrap Value';
		$array[$r][6] = 'Current Value';
		$array[$r][7] = 'Purchase Price';
		$array[$r][8] = 'Is Checked Out';
		$array[$r][9] = 'Asset Name';
		$array[$r][10] = 'Brand';
		$array[$r][11] = 'Manufacturer';
		$array[$r][12] = 'Asset Category';
		$array[$r][13] = 'Location';
		$array[$r][14] = 'Status';
		$array[$r][15] = 'Condition';
		$array[$r][16] = 'Notes (i.e description of item)';
		
		$r++;
		
		//create the excel document
		$this->excel->addArray ($array);
		$this->excel->generateXML ($title);
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
	
	//sort the imported data
	public function sort_csv_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if($total_rows > 0)
		{
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Asset Name</th>
						  <th>Asset Category</th>
						  <th>Asset Price</th>
						  <th>Quantity</th>
						  <th>Description</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$asset_id = $array[$r][0];
				$asset_barcode = $array[$r][1];
				$serial_number = $array[$r][2];
				$model = $array[$r][3];
				$condition_id = $array[$r][4];
				$scrap_value = $array[$r][5];
				$purchase_price = $array[$r][7];
				$checked_out = $array[$r][8];
				$item_price = $array[$r][6];
				$item_name = $array[$r][9];
				$brand = $array[$r][10];
				$manufacturer = $array[$r][11];
				$item_category_id = $array[$r][12];
				$location = $array[$r][13];
				$status = $array[$r][14];
				$condition = $array[$r][15];
				$item_description = $array[$r][16];
				
				$category_name = $this->get_category_name($item_category_id);
				
				$items['item_name'] = $item_name;
				$items['asset_barcode'] = $asset_barcode;
				$items['asset_id'] = $asset_id;
				$items['model'] = $model;
				$items['condition_id'] = $condition_id;
				$items['scrap_value'] = $scrap_value;
				$items['purchase_price'] = $purchase_price;
				$items['checked_out'] = $checked_out;
				$items['brand'] = $brand;
				$items['manufacturer'] = $manufacturer;
				$items['item_category_id'] = $item_category_id;
				$items['location'] = $location;
				$items['status'] = $status;
				$items['item_description'] = $item_description;
				$items['condition'] = $condition;
				$items['serial_number'] = $serial_number;
				$items['item_unit_price'] = $item_price;
				// check if item exist
				$comment ='';
				if(!empty($items['item_name']))
				{
					if(!empty($items['asset_id']))
					{
						// check if the number already exists
						if($this->check_current_number_exisits($asset_id))
						{
							//number exists
							$comment = '<br/>Duplicate item entered';
							$class = 'danger';
						}
						else
						{
							// number does not exisit
							//save product in the db
							if($this->db->insert('item', $items))
							{
								$item_id = $this->db->insert_id();
							
								$comment = '<br/>Item successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment = '<br/>Internal error. Could not add item to the database. Please contact the site administrator. Item code '.$items['item_name'];
								$class = 'warning';
							}
						}
					}
					else{
						$comment = '<br/>Internal error. Could not add item to the database. Please contact the site administrator. Item code '.$items['item_name'];
								$class = 'warning';
					}
				}
				else
				{
					$comment = '<br/>Not saved ensure you have a item number entered'.$items['item_name'];
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$items['item_name'].'</td>
							<td>'.$category_name.'</td>
							<td>'.$items['item_unit_price'].'</td>
							<td>1</td>
							<td>'.$items['item_description'].'</td>
							<td>'.$comment.'</td>
						</tr> 
				';
				
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Item data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
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