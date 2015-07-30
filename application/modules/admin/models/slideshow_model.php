<?php

class Slideshow_model extends CI_Model 
{	
	public function upload_slideshow_image($slideshow_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 1920;
		$resize['height'] = 755;
		
		if(!empty($_FILES['slideshow_image']['tmp_name']))
		{
			$image = $this->session->userdata('slideshow_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($slideshow_path."\\".$image, $slideshow_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($slideshow_path."\\thumbnail_".$image, $slideshow_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($slideshow_path, 'slideshow_image', $resize);//var_dump($response);die();
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//crop file to 1920 by 1010
				$response_crop = $this->file_model->crop_file($slideshow_path."/".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop['response'])
				{
					$this->session->set_userdata('error_message', 'Cannot crop image. '.$response_crop['message']);
				
					return FALSE;
				}
				
				else
				{
					//Set sessions for the image details
					$this->session->set_userdata('slideshow_file_name', $file_name);
					$this->session->set_userdata('slideshow_thumb_name', $thumb_name);
				
					return TRUE;
				}
			}
		
			else
			{
				$this->session->set_userdata('error_message', 'Cannot upload image. '.$response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_slides($table, $where, $per_page, $page)
	{
		//retrieve all slideshows
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('slideshow_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Delete an existing slideshow
	*	@param int $slideshow_id
	*
	*/
	public function delete_slideshow($slideshow_id)
	{
		if($this->db->delete('slideshow', array('slideshow_id' => $slideshow_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated slideshow
	*	@param int $slideshow_id
	*
	*/
	public function activate_slideshow($slideshow_id)
	{
		$data = array(
				'slideshow_status' => 1
			);
		$this->db->where('slideshow_id', $slideshow_id);
		
		if($this->db->update('slideshow', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated slideshow
	*	@param int $slideshow_id
	*
	*/
	public function deactivate_slideshow($slideshow_id)
	{
		$data = array(
				'slideshow_status' => 0
			);
		$this->db->where('slideshow_id', $slideshow_id);
		
		if($this->db->update('slideshow', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
