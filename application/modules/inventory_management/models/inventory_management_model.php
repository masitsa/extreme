<?php

class Inventory_management_model extends CI_Model 
{

	public function get_product_list($table, $where, $per_page, $page, $order)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('product.*,store.store_name,store.in_service_charge_status,store.store_id');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	
	public function item_purchases($product_id)
	{
  		$table = "product_purchase, product";
		$where = "product.product_id = ".$product_id." AND product_purchase.product_id = product.product_id";
		$items = "product_purchase.purchase_pack_size, product_purchase.purchase_quantity";
		$order = "purchase_pack_size";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$purchase_pack_size = $row2->purchase_pack_size;
				$purchase_quantity = $row2->purchase_quantity;
				$total = $total + ($purchase_pack_size * $purchase_quantity);
			}
		}
		return $total;
	}
	
	public function item_deductions($product_id)
	{
  		$table = "product_deductions, product";
		$where = "product.product_id = ".$product_id." AND product_deductions.product_id = product.product_id";
		$items = "product_deductions.product_deductions_pack_size, product_deductions.product_deductions_quantity";
		$order = "product_deductions_pack_size";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		$total = 0;
		
		if(count($result) > 0){
			
			foreach ($result as $row2)
			{
				$product_deductions_pack_size = $row2->product_deductions_pack_size;
				$product_deductions_quantity = $row2->product_deductions_quantity;
				$total = $total + ($product_deductions_pack_size * $product_deductions_quantity);
			}
		}
		return $total;
	}
	
	public function get_product_units_sold($product_id)
	{
		$table = "visit_charge, service_charge";
		$where = "visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.product_id = ". $product_id;
		$items = "SUM(visit_charge.visit_charge_units) AS total_sold";
		$order = "total_sold";

		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$total_sold = 0;
		if(count($result) > 0)
		{
			foreach ($result as $key) {
				# code...
				$total_sold = $key->total_sold;
			}
		}
		return $total_sold;
	}

	public function purchase_product($product_id)
	{
		$array = array(
			'product_id'=>$product_id,
			'purchase_quantity'=>$this->input->post('purchase_quantity'),
			'purchase_pack_size'=>$this->input->post('purchase_pack_size'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'expiry_date'=>$this->input->post('expiry_date')
		);
		if($this->db->insert('product_purchase', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_product_purchase($product_purchase_id)
	{
		$array = array(
			'product_id'=>$product_id,
			'purchase_quantity'=>$this->input->post('purchase_quantity'),
			'purchase_pack_size'=>$this->input->post('purchase_pack_size'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'expiry_date'=>$this->input->post('expiry_date')
		);
		$this->db->where('product_purchase_id', $product_purchase_id);
		if($this->db->update('productpurchase', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_purchase_details($product_purchase_id)
	{
		$this->db->where('purchase_id', $product_purchase_id);
		$query = $this->db->get('product_purchase');
		
		return $query;
	}

	public function get_product_deductions($table, $where, $per_page, $page, $order)
	{
		//retrieve all purchases
		$this->db->from($table);
		// $this->db->select('store_product.*,store.store_name,product_deductions.*,product.product_name,product.product_id,product.quantity AS parent_store_qty ');
		$this->db->select('store.store_name,product_deductions.*,product.product_name,product.product_id,product.quantity AS parent_store_qty ');
		$this->db->where($where);
		$this->db->order_by($order,'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function deduct_product($product_id)
	{
		$array = array(
			'product_id'=>$product_id,
			'container_type_id'=>$this->input->post('container_type_id'),
			'product_deductions_quantity'=>$this->input->post('product_deduction_quantity'),
			'product_deductions_pack_size'=>$this->input->post('product_deduction_pack_size')
		);
		if($this->db->insert('product_deductions', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function edit_product_deduction($product_deduction_id)
	{
		$array = array(
			'container_type_id'=>$this->input->post('container_type_id'),
			'product_deductions_quantity'=>$this->input->post('product_deduction_quantity'),
			'product_deductions_pack_size'=>$this->input->post('product_deduction_pack_size')
		);
		$this->db->where('product_deductions_id', $product_deduction_id);
		if($this->db->update('product_deductions', $array))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_deduction_details($product_deduction_id)
	{
		$this->db->where('product_deductions_id', $product_deduction_id);
		$query = $this->db->get('product_deductions');
		
		return $query;
	}


	public function get_product_purchase_details($product_id)
	{
		$table = "product_purchase";
		$where = "product_id = '$product_id'";
		$items = "*";
		$order = "product_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_all_generics()
	{
		$table = "generic";
		$items = "*";
		$order = "generic_id";
		$where = 'generic_id > 0';
		$this->db->order_by('generic_name');
		$result = $this->db->get($table);
		
		return $result;
	}
	public function get_all_brands()
	{
		$table = "brand";
		$items = "*";
		$order = "brand_id";
		$where = 'brand_id > 0';
		
		$this->db->order_by('brand_name');
		$result = $this->db->get($table);
		
		return $result;
	}
	public function save_product()
	{
		$name = ucwords(strtolower($this->input->post('product_name')));
		$unit_of_measure = $this->input->post('unit_of_measure');

		if($this->input->post('category_id') == 2)
		{
			// get the administration route nam
			$this->db->where('drug_type_id = '.$this->input->post('drug_type_id'));
			$query = $this->db->get('drug_type');

			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $key) {
					# code...
					$drug_type_name = $key->drug_type_name;
				}
			}
		}
		$product_name = $name.'-'.$unit_of_measure.'('.$drug_type_name.')';
		$array = array(
			'product_name'=>$product_name,
			'product_status'=>1,
			'product_description'=>$this->input->post('product_description'),
			'category_id'=>$this->input->post('category_id'),
			'quantity'=>$this->input->post('quantity'),
			'batch_no'=>$this->input->post('batch_no'),
			'product_unitprice'=> $this->input->post('product_unitprice'),
			'store_id'=>$this->input->post('store_id'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'product_packsize'=>$this->input->post('product_pack_size'),
			'unit_of_measure'=>$this->input->post('unit_of_measure'),
			'reorder_level'=>$this->input->post('reorder_level'),
			'brand_id'=>$this->input->post('brand_id'),
			'class_id'=>$this->input->post('class_id'),
			'generic_id'=>$this->input->post('generic_id'),
			'drug_type_id'=>$this->input->post('drug_type_id'),
			'is_synced'=>0
		);
		//save product in the db
		if($this->db->insert('product', $array))
		{
			//calculate the price of the drug
			$product_id = $this->db->insert_id();
			
			
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function edit_product($product_id)
	{
		$name = ucwords(strtolower($this->input->post('product_name')));
		$unit_of_measure = $this->input->post('unit_of_measure');

		
		if($this->input->post('category_id') == 2)
		{
			// get the administration route nam
			$this->db->where('drug_type_id = '.$this->input->post('drug_type_id'));
			$query = $this->db->get('drug_type');

			if($query->num_rows() > 0)
			{
				foreach ($query->result() as $key) {
					# code...
					$drug_type_name = $key->drug_type_name;
				}
			}
		}
		$product_name = $name;
		$array = array(
			'product_name'=>$product_name,
			'product_status'=>1,
			'product_description'=>$this->input->post('product_description'),
			'category_id'=>$this->input->post('category_id'),
			'quantity'=>$this->input->post('quantity'),
			'batch_no'=>$this->input->post('batch_no'),
			'product_unitprice'=> $this->input->post('product_unitprice'),
			'store_id'=>$this->input->post('store_id'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'product_packsize'=>$this->input->post('product_pack_size'),
			'unit_of_measure'=>$this->input->post('unit_of_measure'),
			'reorder_level'=>$this->input->post('reorder_level'),
			'brand_id'=>$this->input->post('brand_id'),
			'class_id'=>$this->input->post('class_id'),
			'generic_id'=>$this->input->post('generic_id'),
			'drug_type_id'=>$this->input->post('drug_type_id'),
			'is_synced'=>0
		);
		
		$this->db->where('product_id', $product_id);
		if($this->db->update('product', $array))
		{
			//edit service charge
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function get_product_details($product_id)
	{
		//retrieve all users
		$this->db->from('product, category ,store');
		$this->db->select('product.*, category.category_name,store.store_name, store.store_name');
		$this->db->where('product.category_id = category.category_id AND store.store_id = product.store_id AND product.product_id = '.$product_id);
		$query = $this->db->get();
		
		return $query->result();
	}
	public function get_product_purchases($table, $where, $per_page, $page, $order)
	{
		//retrieve all purchases
		$this->db->from($table);
		$this->db->select('product_purchase.*');
		$this->db->where($where);
		$this->db->order_by($order,'DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}

	public function get_store_inventory_quantity($sub_store_id,$product_id)
	{
		$this->db->where('store_id = '.$sub_store_id.' AND product_id ='.$product_id);
		$this->db->select('quantity');
		$query = $this->db->get('store_product');

		if($query->num_rows() > 0)
		{
			$product_store = $query->result();
			foreach ($product_store as $key) {
				# code...
				$quantity = $key->quantity;
			}

			return $quantity;
		}
		else
		{
			return 0;
		}

	}
	public function check_if_can_access($product_id, $store_id)
	{
		$this->db->select('*');
		$this->db->where('personnel_store.store_id  = '.$store_id.' AND personnel_store.personnel_id = '.$this->session->userdata('personnel_id').'');
		$this->db->order_by('personnel_store.store_id','DESC');
		$this->db->limit(1);
		$query = $this->db->get('personnel_store');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

	}
	public function get_assigned_stores()
	{
		$this->db->select('*');
		$this->db->where('personnel_store.store_id  = store.store_id AND personnel_store.personnel_id = '.$this->session->userdata('personnel_id').'');
		$this->db->order_by('personnel_store.store_id','DESC');
		// $this->db->limit(1);
		$query = $this->db->get('personnel_store,store');

		return $query;
	}
	public function check_store_child($store_id)
	{
		$this->db->select('*');
		$this->db->where('store.store_id  = '.$store_id.' AND store.store_parent > 0 ');
		$this->db->order_by('store.store_id','DESC');
		$this->db->limit(1);
		$query = $this->db->get('store');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	
	}
	public function get_orders_on_days_requests($date)
	{
		$this->db->select('*');
		$this->db->where('product_deductions.search_date = "'.$date.'"  AND product_deductions.product_id = product.product_id AND product_deductions.store_id  = store.store_id ');
		$this->db->order_by('product_deductions.product_deductions_id','DESC');
		// $this->db->limit(1);
		// $this->db->group_by('product_deductions.search_date');
		$query = $this->db->get('product_deductions,product,store');

		return $query;

	}

	public function get_all_requests_made($table, $where, $per_page, $page, $order)
	{
		$this->db->from($table);
		$this->db->select('product_deductions.search_date');
		$this->db->where($where);
		$this->db->group_by('product_deductions.search_date');
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_parent_products($table, $where, $per_page, $page, $order)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order,'asc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_parent_store($store_id)
	{
		$this->db->select('store_parent');
		$this->db->where('store.store_id ='.$store_id);
		// $this->db->limit(1);
		$query = $this->db->get('store');

		foreach ($query->result() as $key) {
			# code...
			$parent_store = $key->store_parent;
		}
		return $parent_store;
	}
	public function get_store_request($store_id)
	{
		$this->db->select('*');
		$this->db->where('product.product_id = product_deductions.product_id AND product_deductions.store_id ='.$store_id);
		$query = $this->db->get('product_deductions,product');
		return $query;
	}
}