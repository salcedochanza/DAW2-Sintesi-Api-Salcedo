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
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");


        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $id = $this->put("id");
            $password = $this->put("password");
            if ($password != null){
                $data = array(
                    'username' => $this->put("user"),
                    'password' => $this->put("password"),
                    'first_name' => $this->put("firstName"),
                    'last_name' => $this->put("lastName"),
                    'email' => $this->put("email"),
                    'phone' => $this->put("phone"),
                );
            } else {
                $data = array(
                    'username' => $this->put("user"),
                    'first_name' => $this->put("firstName"),
                    'last_name' => $this->put("lastName"),
                    'email' => $this->put("email"),
                    'phone' => $this->put("phone"),
                );
            }

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
                    'status' => false,
                    'token' => "",
                    'message' => 'Error al editar perfil'
                ];
                $this->set_response($message, 401);
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
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");

        $this->response(null,200); // OK (200) being the HTTP response code
    }

}

/* End of file perfil_controller.php */