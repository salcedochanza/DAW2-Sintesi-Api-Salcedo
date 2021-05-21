<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tags_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
 

    public function get_tag($id)
    {
        $query = $this->db->get_where('tags', array('id' => $id));
        return $query;
    }
    public function get_tags()
    {
        $query = $this->db->get_where('tags');
        return $query;
    }
    public function insert($data)
    {
        $this->db->insert('tags', $data);
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tags');
    }
    public function put($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tags', $data);
    }
}

/* End of file tags_model.php */