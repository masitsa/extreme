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
	public function all_unselected_items($request_id)
	{
		
		$query = $this->db->query('select * from item where item_status_id = 1 and item_id not in(select item_id from request_item where request_id='.$request_id.')');
		
		return $query;
	}
	
	//get the condition where the item is at
	public function get_condition_name($condition_id)
	{
		$this->db->select('condition_name');
		$this->db->where('condition_id ='.$condition_id);		
		$query = $this->db->get('conditions');
		
		return $query;
	}
	
	//get the location of the item
	public function get_location_name($location_id)
	{
		$this->db->select('location_name');
		$this->db->where('location_id = '.$location_id);		
		$query = $this->db->get('location');
	
		return $query;
	}
	
	// edit the inventory item selected
	public function update_inventory_item($inventory_id)
	{
		$data = array(
				'condition_id'=>$this->input->post('condition_id'),
				'location_id'=>$this->input->post('location_id'),
				'usage_status_id' =>$this->input->post('status_id'),
				'serial_number'=>$this->input->post('serial_number'),
				'barcode_name'=>$this->input->post('asset_barcode'),
				'inventory_description'=>$this->input->post('inventory_description'),
				);
		$this->db->where('inventory_id', $inventory_id);
		if($this->db->update('inventory', $data))
		{
			//update invetory item
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	//delete an item in the inventory
	public function delete_inventory_item($inventory_id)
	{
		$this->db->where('inventory_id', $inventory_id);	
			if($this->db->delete('inventory'))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
	
	//name of the item in the inventory
		public function get_item_name($item_id)
	{
		$this->db->select('item_name');
		$this->db->where('item_id = '.$item_id);		
		$query = $this->db->get('item');
	
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
				'item_category_id'=>$this->input->post('item_category_id'),
				'supplier_id'=>$this->input->post('store_id'),
				'manufacturer_id' =>$this->input->post('manufacturer_id'),
				'brand_id'=>$this->input->post('brand_id'),
				'model_id'=>$this->input->post('model_id'),
				'item_hiring_price'=>$this->input->post('item_hiring_price'),
				'minimum_hiring_price'=>$this->input->post('minimum_hiring_price'),
				'purchase_price'=>$this->input->post('purchase_price'),
				'current_value'=>$this->input->post('current_value'),
				'scrap_value'=>$this->input->post('scrap_value'),
				'item_status_id'=>$this->input->post('item_status_id'),
				'item_description'=>$this->input->post('item_description'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'product_deleted'=>0,
				
			);
			
		if($this->db->insert('item',$data))
		{
			
			return TRUE;
		}
		else
		{
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
				'item_category_id'=>$this->input->post('item_category_id'),
				'supplier_id'=>$this->input->post('store_id'),
				'manufacturer_id' =>$this->input->post('manufacturer_id'),
				'brand_id'=>$this->input->post('brand_id'),
				'model_id'=>$this->input->post('model_id'),
				'item_hiring_price'=>$this->input->post('item_hiring_price'),
				'minimum_hiring_price'=>$this->input->post('minimum_hiring_price'),
				'purchase_price'=>$this->input->post('purchase_price'),
				'current_value'=>$this->input->post('current_value'),
				'scrap_value'=>$this->input->post('scrap_value'),
				'item_status_id'=>$this->input->post('item_status_id'),
				'item_description'=>$this->input->post('item_description'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'last_modified'=>date('Y-m-d H:i:s'),
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
	
	//all models 
	public function get_all_models()
	{
		$this->db->select('models.*');
		$this->db->from('models');
		$query = $this->db->get();
		return $query;
	}
	
	//get all status
	public function get_all_status()
	{
		$this->db->select('item_status.*');
		$this->db->from('item_status');
		$query = $this->db->get();
		return $query;
	}
	//get all locations
	public function get_all_locations()
	{
		$this->db->select('location.*');
		$this->db->from('location');
		$query = $this->db->get();
		return $query;
	}
	//get all brands available
	public function get_all_brands()
	{
		$this->db->select('brand.*');
		$this->db->from('brand');
		$query = $this->db->get();
		return $query;
	}
	
	//inventory items insertion
	public function add_inventory_item($item_id)
	{
		$data = array(
				'item_id'=>$item_id,
				'condition_id'=>$this->input->post('condition_id'),
				'barcode_name'=>$this->input->post('asset_barcode'),
				'location_id' =>$this->input->post('location_id'),
				'serial_number'=>$this->input->post('serial_number'),
				'usage_status_id'=>$this->input->post('status_id'),
				'inventory_description'=>$this->input->post('inventory_description'),
				);
				if($this->db->insert('inventory',$data))
				{
			
					return TRUE;
				}
				else
				{
					return FALSE;
				}
	}
	
	//get inventory for a particular item
	public function get_item_inventory($item_id)
	{
		$this->db->select('inventory.*');
		$this->db->from('inventory');
		$this->db->where('item_id = '.$item_id);
		$query = $this->db->get();
		return $query;
	}
	//get all conditions
	public function all_conditions()
	{
		$this->db->select('conditions.*');
		$this->db->from('conditions');
		$query = $this->db->get();
		return $query;
	}
	//get all manufacturers
	public function get_all_manufacturers()
	{
		$this->db->select('manufacturer.*');
		$this->db->from('manufacturer');
		$query = $this->db->get();
		return $query;
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
		$this->db->where('item_category_id = '.$item_category_id);
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
		
	public function check_current_number_exisits($item_id)
	{
		$this->db->from('item');
		$this->db->where('item_id', $item_id);
		
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
		//echo $total_rows;
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
				$item_id = $array[$r][0];
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
				echo $item_category_id;
				$location = $array[$r][13];
				$status = $array[$r][14];
				$condition = $array[$r][15];
				$item_description = $array[$r][16];
				
				$category_name = $this->items_model->get_category_name($array[$r][12]);
				
				$items['item_name'] = $item_name;
				$items['item_id'] = $item_id;
				$items['model_id'] = $model;
				$items['purchase_price'] = $item_price;
				$items['scrap_value'] = $scrap_value;
				$items['item_description'] = $item_description;
				$items['purchase_price'] = $purchase_price;
				$items['brand_id'] = $brand;
				$items['manufacturer_id'] = $manufacturer;
				$items['item_category_id'] = $item_category_id;
				$items1['location_id'] = $location;
				$items1['usage_status_id'] = $status;
				$items1['condition_id'] = $condition;
				$items1['barcode_name'] = $asset_barcode;
				$items1['serial_number'] = $serial_number;
				$items1['item_id'] = $item_id;
				
				// check if item exist
				$comment ='';
				if(!empty($items['item_name']))
				{
					
					if(!empty($items['item_id']))
					{
						
						// check if the number already exists
						if($this->check_current_number_exisits($item_id))
						{
							//number exists
							$comment = '<br/>Duplicate item entered';
							$class = 'danger';
						}
						else
						{
							// number does not exisit
							//save product in the db								$this->db->insert('inventory', $items1);
							//var_dump($items); die();

							if($this->db->insert('item', $items))
							{
								$item_id = $this->db->insert_id();
								$this->db->insert('inventory', $items1);
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
							<td>'.$items['purchase_price'].'</td>
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