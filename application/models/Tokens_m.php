<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tokens_m extends CI_Model { 

    public function __construct () 
    { 
        parent::__construct();
        $this->load->database();
    } 

    public function get($token_data)
    {
        $data = array(
            'tokenid' => $token_data->jti,
            'subject' => $token_data->sub
        );
        $query = $this->db->get_where($this->config->item("jwt_table"), $data);
        return $query->row_array();
    }

    public function revoked($token_data)
    {
        /*$data = array(
            'tokenid' => $token_data->jti,
            'subject' => $token_data->sub
        );
        $query = $this->db->get_where($this->config->item("jwt_table"), $data);
        return $query->row_array()!=null;*/

        return $this->get($token_data)!=null;
    }

    // get all JWT revoked already expired 
    public function expired($time=null)
    {
        if ($time===null) $time=time();
        $data = array('expiration <=' => $time);
        $query = $this->db->get_where($this->config->item("jwt_table"), $data);
        return $query->result_array();
    }

    public function purge($time=null)
    {
        if ($time===null) $time=time();
        $data = array('expiration <=' => $time);
        $query = $this->db->delete($this->config->item("jwt_table"), $data);
        
        return $this->db->affected_rows();
    }

    public function revoke($token_data)
    {
        $data = array(
            'tokenid' => $token_data->jti,
            'subject' => $token_data->sub,
            'expiration' => $token_data->exp
        );

        return $this->db->insert($this->config->item("jwt_table"), $data);
    } 

}