<?php

class Gallery_model extends CI_Model 
{	
	public function upload_gallery_image($gallery_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 800;
		$resize['height'] = 800;
		
		if(!empty($_FILES['gallery_image']['tmp_name']))
		{
			$image = $this->session->userdata('gallery_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($gallery_path."\\".$image, $gallery_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($gallery_path."\\thumbnail_".$image, $gallery_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($gallery_path, 'gallery_image', $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//Set sessions for the image details
				$this->session->set_userdata('gallery_file_name', $file_name);
				$this->session->set_userdata('gallery_thumb_name', $thumb_name);
			}
		
			else
			{
				$this->session->set_userdata('gallery_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('gallery_error_message', '');
			return FALSE;
		}
	}
	
	public function get_all_gallerys($table, $where, $per_page, $page)
	{
		//retrieve all gallerys
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('department.department_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Delete an existing gallery
	*	@param int $gallery_id
	*
	*/
	public function delete_gallery($gallery_id)
	{
		if($this->db->delete('gallery', array('gallery_id' => $gallery_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated gallery
	*	@param int $gallery_id
	*
	*/
	public function activate_gallery($gallery_id)
	{
		$data = array(
				'gallery_status' => 1
			);
		$this->db->where('gallery_id', $gallery_id);
		
		if($this->db->update('gallery', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated gallery
	*	@param int $gallery_id
	*
	*/
	public function deactivate_gallery($gallery_id)
	{
		$data = array(
				'gallery_status' => 0
			);
		$this->db->where('gallery_id', $gallery_id);
		
		if($this->db->update('gallery', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
