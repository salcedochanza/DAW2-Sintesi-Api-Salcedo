<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tags_controller  extends JwtAPI_Controller { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->model('tags_model');

        $config=[
            "sub" => "secure.jwt.dwtube", // subject of token
            "jti" => $this->uuid->v5('secure.jwt.dwtube')// Json Token Id
        ];
        $this->init($config,300); // configuration + auth timeout
    }
    
    public function tag_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        if ($this->auth_request('admin')){
            $jwt = $this->renewJWT();
            $id = $this->get("id");
            $query = $this->tags_model->get_tag($id);
            $message = [
                'status' => true,
                'tag' => $query->result_array(),
                'token' => $jwt,
                'message' => 'Dades etiqueta'
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

    public function tags_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $query = $this->tags_model->get_tags();
        $this->response($query->result_array(), 200);
    }

    public function tags_post()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        if ($this->auth_request('admin')){
            $jwt = $this->renewJWT();

            $data = array(
                'name' => $this->post("name"),
            );
            $this->tags_model->insert($data);

            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Etiqueta creada'
            ];
            $this->set_response($message, 200);
        }
    }

    public function tags_delete($id = null){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        if ($this->auth_request('admin')){
            $jwt = $this->renewJWT();

            $this->tags_model->delete($id);
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Etiqueta eliminada'
            ];
            $this->set_response($message, 200);
        }
    }

    public function tags_put()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");
        
        if ($this->auth_request('admin')){
            $jwt = $this->renewJWT();
            
            $id = $this->put("id");
            
            $data = array(
                'name' => $this->put("name"),
            );
        
            $this->tags_model->put($id, $data);
        
            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Etiqueta Editada'
            ];
            $this->set_response($message, 200);

        } else {

            $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer ");

            $jwt = $this->renewJWT();
            $message = [
                'status' => $this->auth_code,
                'token' => $jwt,
                'message' => 'Bad auth information. ' . $this->error_message
            ];
            error_log($this->output->get_header("Access-Control-Allow-Origin"));

            $this->set_response($message, $this->auth_code); // 400 / 401 / 419 / 500
        }
        
    }

    public function tag_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
    public function tags_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
}

/* End of file tags_controller.php */