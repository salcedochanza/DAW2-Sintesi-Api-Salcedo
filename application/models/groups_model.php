<?php defined('BASEPATH') OR exit('No direct script access allowed');

class groups_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
 

    public function get_group($id)
    {
        $query = $this->db->get_where('groups', array('id' => $id));
        return $query;
    }
    public function get_groups()
    {
        $query = $this->db->get_where('groups');
        return $query;
    }
    public function insert($data)
    {
        $this->db->insert('groups', $data);
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('groups');
    }
    public function put($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('groups', $data);
    }
}

/* End of file groups_model.php */