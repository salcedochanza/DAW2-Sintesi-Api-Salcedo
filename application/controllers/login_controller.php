<?php defined('BASEPATH') OR exit('No direct script access allowed');

class login_controller  extends JwtAPI_Controller { 

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
    
    public function login_post()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");
        
        $user = $this->post("user");
        $pass = $this->post("pass");
        if ($this->login($user, $pass)){
            $jwt = $this->renewJWT();
        }
    }

    public function login_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        $this->response(null,200); // OK (200) being the HTTP response code
    }

}

/* End of file login_controller.php */