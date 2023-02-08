<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function upload_file()
	{
		if (is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) 
	    {
	    	$uploads_dir = 'storage/';
            $tmp_name = $_FILES['uploadedfile']['tmp_name'];
            $pic_name = $_FILES['uploadedfile']['name'];
            move_uploaded_file($tmp_name, $uploads_dir.$pic_name);
            $file_path = $uploads_dir.$pic_name;
            $user_id = $this->input->post('user_id');

            $this->db->insert('files', array('file_path' => $file_path, 'user_id' => $user_id));
            echo "success";
        }
	    else
	    {
	        echo "failed";
	    }
	}
}
