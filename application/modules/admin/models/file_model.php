<?php

class File_model extends CI_Model 
{	
	/*
	*	Upload file
	*	@param string $upload_path
	* 	@param string $field_name
	*
	*/
	public function upload_single_dir_file($upload_path, $field_name, $resize)
	{
		$config = array(
				'allowed_types' => 'JPG|JPEG|jpg|jpeg|gif|png',
				'upload_path' => $upload_path,
				'quality' => "100%",
				'file_name' => md5(date('Y-m-d H:i:s')),
				'max_size' => '30000'
			);
			
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload($field_name))
		{
			// if upload fail, grab error
			$response['check'] = FALSE;
			$response['error'] =  $this->upload->display_errors();
		}
		
		else
		{
			// otherwise, put the upload datas here.
			// if you want to use database, put insert query in this loop
			$image_upload_data = $this->upload->data();
			
			$file_name = $image_upload_data['file_name'];
			
			// set the resize config
			$resize_conf = array(
					'source_image'  => $image_upload_data['full_path'], 
					'width' => $resize['width'],
					'height' => $resize['height'],
					'maintain_ratio' => true,
				);

			// initializing
			$this->image_lib->initialize($resize_conf);

			// do it!
			if ( ! $this->image_lib->resize())
			{
				$response['check'] = FALSE;
				$response['error'] =  $this->image_lib->display_errors();
			}
			else
			{
				//Create thumbnail
				$create = $this->resize_image($image_upload_data['full_path'], $image_upload_data['file_path'].'thumbnail_'.$file_name, 80, 80);
				
				if($create)
				{
					$response['check'] = TRUE;
					$response['file_name'] =  $file_name;
					$response['thumb_name'] =  'thumbnail_'.$file_name;
					$response['upload_data'] =  $image_upload_data;
				}
				
				else
				{
					$response['check'] = FALSE;
					$response['error'] =  $this->image_lib->display_errors();
				}
			}
		}
		
        unset($_FILES[$field_name]);
		return $response;
	}
	
	/*
	*	Upload file
	*	@param string $upload_path
	* 	@param string $field_name
	*
	*/
	public function upload_file($upload_path, $field_name, $resize, $master_dim = 'width')
	{
		$config = array(
				'allowed_types'	=> 'JPG|JPEG|jpg|jpeg|gif|png',
				'upload_path' 	=> $upload_path,
				'quality' 		=> "100%",
				'max_size'      => '0',
				'file_name' 	=> md5(date('Y-m-d H:i:s'))
			);
			
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload($field_name))
		{
			// if upload fail, grab error
			$response['check'] = FALSE;
			$response['error'] =  $this->upload->display_errors();
		}
		
		else
		{
			// otherwise, put the upload datas here.
			// if you want to use database, put insert query in this loop
			$image_upload_data = $this->upload->data();
			
			$file_name = $image_upload_data['file_name'];
			
			// set the resize config
			$resize_conf = array(
					'source_image'  => $image_upload_data['full_path'], 
					'width' => $resize['width'],
					'height' => $resize['height'],
					'master_dim' => $master_dim,
					'maintain_ratio' => TRUE
				);
			$resize_conf['image_library'] = 'gd2';

			// initializing
			$this->image_lib->initialize($resize_conf);

			// do it!
			if ( ! $this->image_lib->resize())
			{
				$response['check'] = FALSE;
				$response['error'] =  $this->image_lib->display_errors();
			}
			else
			{
				//Create thumbnail
				$create = $this->resize_image($image_upload_data['full_path'], $image_upload_data['file_path'].'thumbnail_'.$file_name, 100, 100);
				
				if($create)
				{
					$response['check'] = TRUE;
					$response['file_name'] =  $file_name;
					$response['thumb_name'] =  'thumbnail_'.$file_name;
					$response['upload_data'] =  $image_upload_data;
				}
				
				else
				{
					$response['check'] = FALSE;
					$response['error'] =  $this->image_lib->display_errors();
				}
			}
		}
		
        unset($_FILES[$field_name]);
		return $response;
	}
	
	/*
	*	Resize an image
	*	@param string $file_path
	* 	@param string $new_path
	*	@param string $width
	* 	@param string $height
	*
	*/
	public function resize_image($file_path, $new_path, $width, $height)
	{
		$resize_conf = array(
			'source_image'  => $file_path,
			'new_image'     => $new_path,
			'create_thumb'  => FALSE,
			'width' => $width,
			'height' => $height,
			'maintain_ratio' => true,
		);
		
		$this->image_lib->initialize($resize_conf);
		 
		 if ( ! $this->image_lib->resize())
		{
		 	return $this->image_lib->display_errors();
		}
		
		else
		{
			return TRUE;
		}
	}
	
	/*
	*	Delete a file
	*	@param string $file_path
	*
	*/
	public function delete_file($file_path, $base_path)
	{
		if((!empty($file_path)) &&(file_exists($file_path)) && ($file_path != $base_path.'\\'))
		{
			unlink($file_path);
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	// Upload & Resize in action
    function upload_gallery($product_id, $gallery_path, $resize)
    {
		$this->load->library('upload');
		
        $upload_conf = array(
				'upload_path'   => $gallery_path,
				'allowed_types' => 'JPG|JPEG|jpg|jpeg|gif|png',
				'max_size'      => '300000',
				'quality' => "100%",
				'file_name' => md5(date('Y-m-d H:i:s'))
            );
    
        $this->upload->initialize( $upload_conf );
    	
        // Change $_FILES to new vars and loop them
        foreach($_FILES['gallery'] as $key=>$val)
        {
            $i = 1;
            foreach($val as $v)
            {
                $field_name = "file_".$i;
                $_FILES[$field_name][$key] = $v;
                $i++;   
            }
        }
        // Unset the useless one ;)
        unset($_FILES['gallery']);
    
        // Put each errors and upload data to an array
        $error = array();
        $success = array();
		$count = 0;
		
        // main action to upload each file
        foreach($_FILES as $image_field_name => $file)
        {
            if ( ! $this->upload->do_upload($image_field_name))
            {
                // if upload fail, grab error 
                $error['upload'][] = $this->upload->display_errors();
            }
            else
            {
                // otherwise, put the upload datas here.
                // if you want to use database, put insert query in this loop
                $upload_data = $this->upload->data();
				
				$file_name = $upload_data['file_name'];
				
				$thumbs['full_path'][$count] = $upload_data['full_path'];
				$thumbs['file_path'][$count] = $upload_data['file_path'].'thumb_'.$file_name;
				$count++;
                
                // set the resize config
                $resize_conf = array(
						// it's something like "/full/path/to/the/image.jpg" maybe
						'source_image'  => $upload_data['full_path'], 
						// and it's "/full/path/to/the/" + "thumb_" + "image.jpg
						// or you can use 'create_thumbs' => true option instead
						'new_image'     => $upload_data['file_path'].$file_name,
						'width' => $resize['width'],
						'height' => $resize['height'],
						'maintain_ratio' => true
                    );

                // initializing
                $this->image_lib->initialize($resize_conf);

                // do it!
                if ( ! $this->image_lib->resize())
                {
                    // if got fail.
                    $error['resize'][] = $this->image_lib->display_errors();
                }
                else
                {
					if($this->products_model->save_gallery_file($product_id, $file_name, 'thumb_'.$file_name))
					{
						// otherwise, put each upload data to an array.
						$success[] = $upload_data;
					}
                }
            }
        }
		
		for($r = 0; $r < $count; $r++)
		{
			// create thumbnails
			$resize_conf2 = array(
				// it's something like "/full/path/to/the/image.jpg" maybe
				'source_image'  => $thumbs['full_path'][$r], 
				// and it's "/full/path/to/the/" + "thumb_" + "image.jpg
				// or you can use 'create_thumbs' => true option instead
				'new_image'     => $thumbs['file_path'][$r],
				'width' => 80,
				'height' => 80,
				'maintain_ratio' => true,
				);

			// initializing
			$this->image_lib->initialize($resize_conf2);

			// do it!
			if ( ! $this->image_lib->resize())
			{
				// if got fail.
				$error['resize'][] = $this->image_lib->display_errors();
			}
		}

        // see what we get
        if(count($error) > 0)
        {
            return $error;
        }
        else
        {
            return TRUE;
        }
    }
	
	public function crop_file($file, $x, $y)
	{
		$image_config['image_library'] = 'gd2';
		$image_config['source_image'] = $file;
		$image_config['quality'] = "100%";
		$image_config['maintain_ratio'] = FALSE;
		$image_config['width'] = $x;
		$image_config['height'] = $y;
		$image_config['x_axis'] = '0';
		$image_config['y_axis'] = '0';
		 
		$this->image_lib->clear();
		$this->image_lib->initialize($image_config);
		
		if ( ! $this->image_lib->crop())
		{
			$error = $this->image_lib->display_errors();
			$return['response'] = FALSE;
			$return['message'] = $error;
			return $return;
		}
		
		else
		{
			$return['response'] = TRUE;
			return $return;
		}
	}
}
?>