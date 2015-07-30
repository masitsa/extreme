<?php

class Service_model extends CI_Model 
{	
	public function upload_service_image($service_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 500;
		$resize['height'] = 500;
		
		if(!empty($_FILES['service_image']['tmp_name']))
		{
			$image = $this->session->userdata('service_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($service_path."\\".$image, $service_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($service_path."\\thumbnail_".$image, $service_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($service_path, 'service_image', $resize, 'height');
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//crop file to 1920 by 1010
				$response_crop = $this->file_model->crop_file($service_path."\\".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop)
				{
					$this->session->set_userdata('service_error_message', $response_crop);
				
					return FALSE;
				}
				
				else
				{
					//Set sessions for the image details
					$this->session->set_userdata('service_file_name', $file_name);
					$this->session->set_userdata('service_thumb_name', $thumb_name);
				
					return TRUE;
				}
			}
		
			else
			{
				$this->session->set_userdata('service_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('service_error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_services($table, $where, $per_page, $page)
	{
		//retrieve all services
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('department.department_name, service.service_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Delete an existing service
	*	@param int $service_id
	*
	*/
	public function delete_service($service_id)
	{
		if($this->db->delete('service', array('service_id' => $service_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated service
	*	@param int $service_id
	*
	*/
	public function activate_service($service_id)
	{
		$data = array(
				'service_status' => 1
			);
		$this->db->where('service_id', $service_id);
		
		if($this->db->update('service', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated service
	*	@param int $service_id
	*
	*/
	public function deactivate_service($service_id)
	{
		$data = array(
				'service_status' => 0
			);
		$this->db->where('service_id', $service_id);
		
		if($this->db->update('service', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_active_services()
	{
  		$table = "service";
		$where = "service_status = 1";
		
		$this->db->where($where);
		$query = $this->db->get($table);
		
		return $query;
	}
}
