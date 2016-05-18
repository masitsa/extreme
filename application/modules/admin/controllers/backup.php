<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		//$this->load->model('backup_model');

	}
	function db_backup()
	{
       date_default_timezone_set('Africa/Nairobi');
      // Load the DB utility class 
      $this->load->dbutil(); 
      $prefs = array( 'format' => 'zip', // gzip, zip, txt 
                               'filename' => 'backup_'.date('d_m_Y_H_i_s').'.sql', 
                                                      // File name - NEEDED ONLY WITH ZIP FILES 
                                'add_drop' => TRUE,
                                                     // Whether to add DROP TABLE statements to backup file
                               'add_insert'=> TRUE,
                                                    // Whether to add INSERT data to backup file 
                               'newline' => "\n"
                                                   // Newline character used in backup file 
                              ); 
         // Backup your entire database and assign it to a variable 
         $backup =& $this->dbutil->backup($prefs); 
         // Load the file helper and write the file to your server 
         $this->load->helper('file'); 
         write_file('C:\xampp\htdocs\extreme\backup/'.'dbbackup_'.date('d_m_Y_H_i_s').'.zip', $backup); 
         // Load the download helper and send the file to your desktop 
         $this->load->helper('download'); 
         force_download('dbbackup_'.date('d_m_Y_H_i_s').'.zip', $backup);
}
}