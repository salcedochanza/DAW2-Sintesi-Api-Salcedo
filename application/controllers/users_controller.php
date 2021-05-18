<?php defined('BASEPATH') OR exit('No direct script access allowed');

class users_controller  extends JwtAPI_Controller { 

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
    
    public function user_get(){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        
        if ($this->auth_request()){
            $jwt = $this->renewJWT();
            $id = $this->get("id");
            $user = $this->ion_auth->user($id)->row();

            $message = [
                'status' => true,
                'user' => $user,
                'token' => $jwt,
                'message' => 'Dades usuari'
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

    public function users_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");
        
        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $users = $this->ion_auth->users()->result();

            $message = [
                'status' => true,
                'users' => $users,
                'token' => $jwt,
                'message' => 'Llistat usuaris'
            ];
            $this->set_response($message, 200);
        } else {
            $message = [
                'status' => false,
                'token' => $jwt,
                'message' => 'Error llistat'
            ];
            $this->set_response($message, 401);
        }
    }

    public function user_put()
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
                    'message' => 'Usuari Editat'
                ];
                $this->set_response($message, 200);
            } else {
                $message = [
                    'status' => false,
                    'token' => $jwt,
                    'message' => 'El usuari no s\'ha pogut editar'
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

    public function users_delete($id = null){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            //$id = $this->delete("id");
            // $id=6;

            if ($this->ion_auth->delete_user($id)){
                $message = [
                    'status' => true,
                    'token' => $jwt,
                    'message' => 'Usuari eliminat'
                ];
                $this->set_response($message, 200);
            } else {
                $message = [
                    'status' => false,
                    'token' => '',
                    'message' => 'Error al eliminar'
                ];
                $this->set_response($message, 400);
            }
        }
    }

    public function users_post(){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        if ($this->auth_request()){
            $jwt = $this->renewJWT();

            $username = $this->post("username");
            $password = $this->post("password");
            $password2 = $this->post("password2");
            $email = $this->post("email");
            $additional_data = array(
                'first_name' => $this->post("firstName"),
                'last_name' => $this->post("lastName"),
                'phone' => $this->post("phone"),
            );
            $group = array('2');

            if ($password == $password2){
                if ($this->ion_auth->register($username, $password, $email, $additional_data, $group) != null){
                    $message = [
                        'status' => true,
                        'token' => $jwt,
                        'message' => 'Usuari creat'
                    ];
                    $this->set_response($message, 200);
                } else {
                    $message = [
                        'status' => false,
                        'token' => $jwt,
                        'message' => 'Error al crear'
                    ];
                    $this->set_response($message, 401);
                }
            } else {
                $message = [
                    'status' => false,
                    'token' => $jwt,
                    'message' => 'Les contrasenyes no coincideixen'
                ];
                $this->set_response($message, 401);
            }
        }
    }

    public function user_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    public function users_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
}

/* End of file users_controller.php */