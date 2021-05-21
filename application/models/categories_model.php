<?php defined('BASEPATH') OR exit('No direct script access allowed');

class categories_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
 

    public function get_category($id)
    {
        $query = $this->db->get_where('categories', array('id' => $id));
        return $query;
    }

    public function get_categories()
    {
        $query = $this->db->get_where('categories');
        return $query;
    }
    public function insert($data)
    {
        $this->db->insert('categories', $data);
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('categories');
    }
    public function put($id)
    {
        $this->db->where('id', $id);
        $this->db->update('categories', $data);
    }
    public function get_fills($parent)
    {
        $query = $this->db->get_where('categories', array('parent_id' => $parent));
        return $query;
    }
}

/* End of file categories_model.php */