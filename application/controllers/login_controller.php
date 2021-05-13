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
        $user = $this->post("user");
        $pass = $this->post("pass");
        if ($this->login($user, $pass)){
            $jwt = $this->renewJWT();
        } else {
            $this->auth_code=401;
            $message = [
                'token' => "",
                'message' => 'Bad username/password'
            ];
            $this->set_response($message, $this->auth_code); // 401
        }
    }
}

/* End of file login_controller.php */