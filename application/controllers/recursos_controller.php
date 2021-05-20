<?php defined('BASEPATH') OR exit('No direct script access allowed');

class recursos_controller  extends JwtAPI_Controller { 

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
    
    public function recurs_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();
            $id = $this->get("id");
            $query = $this->db->get_where('recursos', array('id' => $id));
            $message = [
                'status' => true,
                'recurs' => $query->result_array(),
                'token' => $jwt,
                'message' => 'Dades recurs'
            ];
            $this->set_response($message, 200);
        } else {
            $jwt = $this->renewJWT();
            $message = [
                'status' => false,
                'token' => $jwt,
                'message' => 'Error al agafar les dades'
            ];
            $this->set_response($message, 401);
        }
    }
    public function recursosCat_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        
        $id = $this->get("id");
        $query = $this->db->get_where('recursos', array('categoria' => $id));
        $message = [
            'status' => true,
            'recurs' => $query->result_array(),
            'message' => 'Dades recurs'
        ];
        $this->set_response($message, 200);
    }

    public function recursos_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $query = $this->db->get_where('recursos');
        $this->response($query->result_array(), 200);
    }

    public function recursos_post()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        
        if ($this->auth_request()){
            $jwt = $this->renewJWT();
            
            
            $file = $this->post("file");
            var_dump($file);
    
            if ( ! $this->upload->do_upload($file))
            {
                    $message = [
                        'status' => true,
                        'errorData' => array('error' => $this->upload->display_errors()),
                        'token' => $jwt,
                        'message' => 'Recurs creat'
                    ];
                    $this->set_response($message, 400);
            }
            else
            {
                    $data = array(
                        'titol' => $this->post("titol"),
                        'descripcio' => $this->post("descripcio"),
                        'disponibilitat' => $this->post("disponibilitat"),
                        'categoria' => $this->post("categoria"),
                        'explicacio' => $this->post("explicacio"),
                    );
                    $this->db->insert('recursos', $data);
        
                    $message = [
                        'status' => true,
                        'token' => $jwt,
                        'message' => 'Recurs creat'
                    ];
                    $this->set_response($message, 200);
            }
        }
    }

    public function recursos_delete($id = null){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $this->db->where('id', $id);
            $this->db->delete('recursos');
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Recurs eliminat'
            ];
            $this->set_response($message, 200);
        }
    }

    public function recursos_put()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");


        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $id = $this->put("id");
            
            $data = array(
                'titol' => $this->put("titol"),
                'descripcio' => $this->put("descripcio"),
                'disponibilitat' => $this->put("disponibilitat"),
                'categoria' => $this->put("categoria"),
                'explicacio' => $this->put("explicacio"),
            );
        
            $this->db->where('id', $id);
            $this->db->update('recursos', $data);
        
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Recurs Editat'
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

    public function recurs_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    public function recursos_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    public function recursosCat_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
}

/* End of file recursos_controller.php */