<?php defined('BASEPATH') OR exit('No direct script access allowed');

class favoritos_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
    
    public function insert($data)
    {
        $this->db->insert('users_favoritos_recursos', $data);
    }
}

/* End of file favoritos_model.php */