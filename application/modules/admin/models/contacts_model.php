<?php

class Contacts_model extends CI_Model 
{	
	public function upload_company_logo($logo_path, $edit = NULL)
	{
		//upload product's gallery images
		$resize['width'] = 300;
		$resize['height'] = 100;
		
		if(!empty($_FILES['logo']['tmp_name']))
		{
			$image = $this->session->userdata('contact_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				//delete any other uploaded image
				$this->file_model->delete_file($logo_path."\\".$image, $logo_path);
				
				//delete any other uploaded thumbnail
				$this->file_model->delete_file($logo_path."\\thumbnail_".$image, $logo_path);
			}
			//Upload image
			$response = $this->file_model->upload_file($logo_path, 'logo', $resize, 'height');
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				//Set sessions for the image details
				$this->session->set_userdata('logo_file_name', $file_name);
				$this->session->set_userdata('logo_thumb_name', $thumb_name);
				
				//crop file to 1920 by 1010
				/*$response_crop = $this->file_model->crop_file($logo_path."\\".$file_name, $resize['width'], $resize['height']);
				
				if(!$response_crop)
				{
					$this->session->set_userdata('error_message', $response_crop);
				
					return FALSE;
				}
				
				else
				{
					
				
					return TRUE;
				}*/
			}
		
			else
			{
				$this->session->set_userdata('error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', '');
			return FALSE;
		}
	}
	
	/*
	*	Update company details
	*	@param int $contact_id
	*
	*/
	public function update_company_details($logo, $thumb)
	{
		$data = array(
			'email' => $this->input->post("email"),
			'phone' => $this->input->post("phone"),
			'address' => $this->input->post("address"),
			'city' => $this->input->post("city"),
			'post_code' => $this->input->post("post_code"),
			'building' => $this->input->post("building"),
			'floor' => $this->input->post("floor"),
			'location' => $this->input->post("location"),
			'working_weekend' => $this->input->post("working_weekend"),
			'working_weekday' => $this->input->post("working_weekday"),
			'company_name' => $this->input->post("company_name"),
			'mission' => $this->input->post("mission"),
			'vision' => $this->input->post("vision"),
			'motto' => $this->input->post("motto"),
			'facebook' => $this->input->post("facebook"),
			'twitter' => $this->input->post("twitter"),
			'pintrest' => $this->input->post("pintrest"),
			'about' => $this->input->post("about"),
			'objectives' => $this->input->post("objectives"),
			'core_values' => $this->input->post("core_values"),
			'logo' => $logo,
			'thumb' => $thumb
		);
		if($this->db->update('contacts', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_company_details()
	{
  		$table = "contacts";
		
		$query = $this->db->get($table);
		
		return $query;
	}
}
