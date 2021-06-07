<?php defined('BASEPATH') OR exit('No direct script access allowed');

class files_controller  extends CI_Controller { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->helper('download');
    }
 

    public function getFile ($path)
    {
        force_download("./uploads/" . $path, NULL);
    }
}

/* End of file files_controller.php */