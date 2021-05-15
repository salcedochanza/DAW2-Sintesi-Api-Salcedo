<?php defined('BASEPATH') OR exit('No direct script access allowed');

class perfil_controller  extends JwtAPI_Controller { 

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
    
    public function profile_edit_put()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");


        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $id = $this->put("id");
            $data = array(
                'user' => $this->put("user"),
                'firstName' => $this->put("firstName"),
                'lastName' => $this->put("lastName"),
                'email' => $this->put("email"),
                'phone' => $this->put("phone"),
            );

            if ($this->ion_auth->update($id, $data)){
                $user = $this->ion_auth->user($id)->row();
                $message = [
                    'status' => true,
                    'user' => $user,
                    'token' => $jwt,
                    'message' => 'Perfil Editat'
                ];
                $this->set_response($message, 200);
            } else {
                $message = [
                    'status' => true,
                    'token' => "",
                    'message' => 'Perfil Editat'
                ];
                $this->set_response($message, 200);
            }
        } else {
            $message = [
                'status' => $this->auth_code,
                'token' => "",
                'message' => 'Bad auth information. ' . $this->error_message
            ];
            $this->set_response($message, $this->auth_code); // 400 / 401 / 419 / 500
        }
        
    }

    public function profile_edit_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }

}

/* End of file login_controller.php */