<?php defined('BASEPATH') OR exit('No direct script access allowed');

class favoritos_controller  extends JwtAPI_Controller { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->model('favoritos_model');

        $config=[
            "sub" => "secure.jwt.dwtube", // subject of token
            "jti" => $this->uuid->v5('secure.jwt.dwtube')// Json Token Id
        ];
        $this->init($config,300); // configuration + auth timeout
    }
 
    public function favoritos_post(){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $data = array(
                'user_id' => $this->post("user_id"),
                'recurs_id' => $this->post("recurs_id"),
            );

            $this->favoritos_model->insert($data);

            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Favorito creat'
            ];
            $this->set_response($message, 200);
        }

    }
    public function favoritos_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    
}

/* End of file favoritos_controller.php */