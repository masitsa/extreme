<?php

class Reports_model extends CI_Model 
{
	public function get_queue_total($date = NULL, $where = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		if($where == NULL)
		{
			$where = 'close_card = 0 AND visit_date = \''.$date.'\'';
		}
		
		else
		{
			$where .= ' AND close_card = 0 AND visit_date = \''.$date.'\' ';
		}
		
		$this->db->select('COUNT(visit_id) AS queue_total');
		$this->db->where($where);
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->queue_total;
	}
	
	public function get_daily_balance($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		//select the user by email from the database
		$this->db->select('SUM(visit_charge_units*visit_charge_amount) AS total_amount');
		$this->db->where('visit_charge_timestamp LIKE \''.$date.'%\'');
		$this->db->from('visit_charge');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_amount;
	}
	
	public function get_patients_total($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('COUNT(visit_id) AS patients_total');
		$this->db->where('visit_date = \''.$date.'\'');
		$query = $this->db->get('visit');
		
		$result = $query->row();
		
		return $result->patients_total;
	}
	
	public function get_all_payment_methods()
	{
		$this->db->select('*');
		$query = $this->db->get('payment_method');
		
		return $query;
	}
	
	public function get_payment_method_total($payment_method_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$this->db->select('SUM(amount_paid) AS total_paid');
		$this->db->where('payments.visit_id = visit.visit_id AND payment_method_id = '.$payment_method_id.' AND visit_date = \''.$date.'\'');
		$query = $this->db->get('payments, visit');
		
		$result = $query->row();
		
		return $result->total_paid;
	}
	
	public function get_all_order_types()
	{
		$this->db->select('*');
		$query = $this->db->get('order_status');
		
		return $query;
	}
	
	public function get_orders_total($order_status_id, $date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'created LIKE \''.$date.'%\' AND order_status = '.$order_status_id;
		
		$this->db->select('COUNT(order_id) AS total');
		$this->db->where($where);
		$query = $this->db->get('orders');
		
		$result = $query->row();
		
		return $result->total;
	}
	
	public function get_products_total($category_id)
	{
		$where = 'product.category_id = category.category_id AND product.product_id = order_item.product_id AND (category.category_id = '.$category_id.' OR category.category_parent = '.$category_id.') AND orders.order_status = 2 AND orders.order_id = order_item.order_id';
		
		$this->db->select('SUM(quantity*price) AS total');
		$this->db->where($where);
		$query = $this->db->get('product, category, order_item, orders');
		
		$result = $query->row();
		$total = $result->total;;
		
		if($total == NULL)
		{
			$total = 0;
		}
		
		return $total;
	}
	
	public function get_all_appointments($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'visit.visit_type = visit_type.visit_type_id AND visit.appointment_id = 1 AND visit.visit_date >= \''.$date.'\'';
		
		$this->db->select('visit_date, time_start, time_end, visit_type_name');
		$this->db->where($where);
		$query = $this->db->get('visit, visit_type');
		
		return $query;
	}
	
	public function get_all_sessions($date = NULL)
	{
		if($date == NULL)
		{
			$date = date('Y-m-d');
		}
		$where = 'personnel.personnel_id = session.personnel_id AND session.session_name_id = session_name.session_name_id AND session_time LIKE \''.$date.'%\'';
		
		$this->db->select('session_name_name, session_time, personnel_fname, personnel_onames');
		$this->db->where($where);
		$this->db->order_by('session_time', 'DESC');
		$query = $this->db->get('session, session_name, personnel');
		
		return $query;
	}
	
	public function get_usage_total()
	{
		$this->db->select('SUM(clicks) AS total');
		$query = $this->db->get('product');	
		
		$result = $query->row();
		
		return $result->total;
	}
	
	public function get_total_airlines()
	{
		$this->db->select('COUNT(airline_id) AS total_airlines');
		$this->db->where('airline_status = 1');
		$query = $this->db->get('airline');
		
		$result = $query->row();
		
		return $result->total_airlines;
	}
	
	public function get_total_visitors()
	{
		$this->db->select('COUNT(visitor_id) AS total_visitors');
		$this->db->where('visitor_status = 1');
		$query = $this->db->get('visitor');
		
		$result = $query->row();
		
		return $result->total_visitors;
	}
	
	public function get_active_flights()
	{
		$this->db->select('COUNT(flight_id) AS total_flights');
		$this->db->where('flight_status = 1');
		$query = $this->db->get('flight');
		
		$result = $query->row();
		
		return $result->total_flights;
	}
	
	public function get_total_payments()
	{
		//select the user by email from the database
		$this->db->select('SUM(payment_amount*payment_quantity) AS total_payments');
		$this->db->where('payment_status = 1');
		$this->db->from('payment');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_payments;
	}
}