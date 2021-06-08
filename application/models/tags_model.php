<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tags_model  extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();
    }
 

    public function get_tag($id)
    {
        $query = $this->db->get_where('etiquetes', array('id' => $id));
        return $query;
    }
    public function get_tags()
    {
        $query = $this->db->get_where('etiquetes');
        return $query;
    }
    public function insert($data)
    {
        $this->db->insert('etiquetes', $data);
    }
    public function insertRecursEtiqueta($data)
    {
        $this->db->insert('recursos_etiquetes', $data);
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('etiquetes');
    }
    public function put($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('etiquetes', $data);
    }
}

/* End of file tags_model.php */