<?php defined('BASEPATH') OR exit('No direct script access allowed');

class recursos_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
 

    public function get_recurs($id)
    {
        $query = $this->db->get_where('recursos', array('id' => $id));
        return $query;
    }
    public function get_recursCat($id)
    {
        $query = $this->db->get_where('recursos', array('categoria' => $id));
        return $query;
    }
    public function get_recursProfe($id)
    {
        $query = $this->db->get_where('recursos', array('propietari' => $id));
        return $query;
    }
    public function get_recursos()
    {
        $query = $this->db->get_where('recursos');
        return $query;
    }
    public function insert($data)
    {
        $this->db->insert('recursos', $data);

        return $this->db->insert_id();
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('recursos');
    }
    public function put($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('recursos', $data);
    }
}

/* End of file recursos_model.php */