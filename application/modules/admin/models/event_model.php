<?php

class Event_model extends CI_Model 
{	
	public function upload_event_image($event_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 500;
		$resize['height'] = 500;
		
		if(!empty($_FILES['event_image']['tmp_name']))
		{
			$image = $this->session->userdata('event_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($event_path."\\".$image, $event_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($event_path."\\thumbnail_".$image, $event_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($event_path, 'event_image', $resize, 'height');
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//crop file to 1920 by 1010
				$response_crop = $this->file_model->crop_file($event_path."\\".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop)
				{
					$this->session->set_userdata('event_error_message', $response_crop);
				
					return FALSE;
				}
				
				else
				{
					//Set sessions for the image details
					$this->session->set_userdata('event_file_name', $file_name);
					$this->session->set_userdata('event_thumb_name', $thumb_name);
				
					return TRUE;
				}
			}
		
			else
			{
				$this->session->set_userdata('event_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('event_error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_events($table, $where, $per_page, $page)
	{
		//retrieve all events
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('event_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Delete an existing event
	*	@param int $event_id
	*
	*/
	public function delete_event($event_id)
	{
		if($this->db->delete('event', array('event_id' => $event_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated event
	*	@param int $event_id
	*
	*/
	public function activate_event($event_id)
	{
		$data = array(
				'event_status' => 1
			);
		$this->db->where('event_id', $event_id);
		
		if($this->db->update('event', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated event
	*	@param int $event_id
	*
	*/
	public function deactivate_event($event_id)
	{
		$data = array(
				'event_status' => 0
			);
		$this->db->where('event_id', $event_id);
		
		if($this->db->update('event', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_active_events($limit = NULL)
	{
  		$table = "event";
		$where = "event_status = 1";
		
		$this->db->where($where);
		
		if($limit == NULL)
		{
			$query = $this->db->get($table);
		}
		else
		{
			$query = $this->db->get($table, $limit);
		}
		
		return $query;
	}
	
	public function get_event_id($event_name)
	{
		//retrieve all users
		$this->db->from('event');
		$this->db->select('event_id');
		$this->db->where('event_name', $event_name);
		$query = $this->db->get();
		$event_id = FALSE;
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$event_id = $row->event_id;
		}
		
		return $event_id;
	}
	
	public function get_event($event_id)
	{
		//retrieve all users
		$this->db->from('event');
		$this->db->where('event_id', $event_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve recent events
	*
	*/
	public function get_recent_events($num = 6)
	{
		$this->db->select('event.*');
		$this->db->where('event_status = 1');
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('event', $num);
		
		return $query;
	}
	
	/*
	*	Retrieve upcoming events
	*
	*/
	public function get_upcoming_events($num = 6)
	{
		$this->db->select('event.*');
		$this->db->where('event_status = 1 AND event_start_time >= NOW()');
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('event', $num);
		
		return $query;
	}
	
	/*
	*	Retrieve past events
	*
	*/
	public function get_past_events($num = 6)
	{
		$this->db->select('event.*');
		$this->db->where('event_status = 1 AND event_start_time < NOW()');
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('event', $num);
		
		return $query;
	}
}