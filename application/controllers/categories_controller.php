<?php defined('BASEPATH') OR exit('No direct script access allowed');

class categories_controller  extends JwtAPI_Controller { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->database();

        $config=[
            "sub" => "secure.jwt.dwtube", // subject of token
            "jti" => $this->uuid->v5('secure.jwt.dwtube')// Json Token Id
        ];
        $this->init($config,300); // configuration + auth timeout
    }

    public function category_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $jwt = $this->renewJWT();
        $id = $this->get("id");
        $query = $this->db->get_where('categories', array('id' => $id));
        $message = [
            'status' => true,
            'group' => $query->result_array(),
            'token' => $jwt,
            'message' => 'Dades categoria'
        ];
        $this->set_response($message, 200);
    }

    public function categories_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $query = $this->db->get_where('categories');
        $this->response($query->result_array(), 200);
    }

    public function categories_post()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $data = array(
                'name' => $this->post("name"),
                'parent_id' => $this->post("parentId"),
            );
            $this->db->insert('categories', $data);

            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Group creat'
            ];
            $this->set_response($message, 200);
        }
    }

    public function categories_delete($id = null){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $this->db->where('id', $id);
            $this->db->delete('categories');
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Group eliminat'
            ];
            $this->set_response($message, 200);
        }
    }

    public function categories_put()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");


        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $id = $this->put("id");
            
            $data = array(
                'name' => $this->put("name"),
                'parent_id' => $this->put("parentId"),
            );
        
            $this->db->where('id', $id);
            $this->db->update('categories', $data);
        
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Categoria Editada'
            ];
            $this->set_response($message, 200);
        } else {
            $jwt = $this->renewJWT();
            $message = [
                'status' => $this->auth_code,
                'token' => $jwt,
                'message' => 'Bad auth information. ' . $this->error_message
            ];
            $this->set_response($message, $this->auth_code); // 400 / 401 / 419 / 500
        }
        
    }

    public function category_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    public function categories_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }





    
    public function fills_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $parent = $this->get("parent");

        $query = $this->db->get_where('categories', array('parent_id' => $parent));
        $this->response($query->result_array(), 200);
    }

    public function fills_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }

}


/* End of file categories_controller.php */