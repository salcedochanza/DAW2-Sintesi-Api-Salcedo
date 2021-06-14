<?php defined('BASEPATH') OR exit('No direct script access allowed');

class recursos_controller  extends JwtAPI_Controller { 

    public function __construct () 
    { 
        parent::__construct ();
        $this->load->model('recursos_model');

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
            $query = $this->recursos_model->get_recurs($id);
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
        $query = $this->recursos_model->get_recursCat($id);
        $message = [
            'status' => true,
            'recurs' => $query->result_array(),
            'message' => 'Dades recurs'
        ];
        $this->set_response($message, 200);
    }
    public function recursosProfe_get()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        
        $id = $this->get("id");
        $query = $this->recursos_model->get_recursProfe($id);
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

        $query = $this->recursos_model->get_recursos();
        $this->response($query->result_array(), 200);
    }

    public function recursos_post()
    {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|txt';
        $this->load->library('upload', $config);
        
        $group = array('admin', 'profe');
        if ($this->auth_request($group)){
            $jwt = $this->renewJWT();

            $data = array(
                'titol' => $this->post("titol"),
                'descripcio' => $this->post("descripcio"),
                'explicacio' => $this->post("explicacio"),
                'categoria' => $this->post("categoria"),
                'tipus_disponibilitat' => $this->post("selDisponibilitat"),
                'tipus' => $this->post("selVideorecurs"),
                'propietari' => $this->post("propietari"),
            );

            if ($this->post("selDisponibilitat") != 4){
                $data['disponibilitat'] = $this->post("disponibilitat");
            }

            if ($this->post("selVideorecurs") == 1 || $this->post("selVideorecurs") == 2){
                if ( ! $this->upload->do_upload('file'))
                {
                        $message = [
                            'status' => true,
                            'errorData' => array('error' => $this->upload->display_errors()),
                            'token' => $jwt,
                            'message' => 'Error al crear recurs'
                        ];
                        $this->set_response($message, 400);
                }
                else
                {
                    $data['videorecurs'] = $_FILES['file']['name'];
                }
            } elseif ($this->post("selVideorecurs") == 3) {
                $data['videorecurs'] = $this->post("videorecurs");
            } elseif ($this->post("selVideorecurs") == 4) {
                $data['canvas'] = $this->post("videorecurs");
            }
            
            $recursId = $this->recursos_model->insert($data);

            $this->load->model('tags_model');
            $arrayEtiquetes = explode(",", $this->post("etiquetes"));
            foreach ($arrayEtiquetes as $etiqueta) {
                $dataEtiquetes = array (
                    'recurs_id' => $recursId,
                    'etiqueta_id' => $etiqueta,
                );
                $this->tags_model->insertRecursEtiqueta($dataEtiquetes);
            }

            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Recurs creat'
            ];
            $this->set_response($message, 200);
        }
    }

    public function recursos_delete($id = null){
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Authorization: Bearer");

        $group = array('admin', 'profe');
        if ($this->auth_request($group)){
            $jwt = $this->renewJWT();

            $this->recursos_model->delete($id);
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


        $group = array('admin', 'profe');
        if ($this->auth_request($group)){
            $jwt = $this->renewJWT();

            $id = $this->put("id");
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|txt';
            $this->load->library('upload', $config);

            $data = array(
                'titol' => $this->put("titol"),
                'descripcio' => $this->put("descripcio"),
                'explicacio' => $this->put("explicacio"),
                'categoria' => $this->put("categoria"),
                'tipus_disponibilitat' => $this->put("selDisponibilitat"),
                'tipus' => $this->put("selVideorecurs"),
                'propietari' => $this->put("propietari"),
            );

            if ($this->put("selDisponibilitat") != 4){
                $data['disponibilitat'] = $this->put("disponibilitat");
            }

            if ($this->put("selVideorecurs") == 1 || $this->put("selVideorecurs") == 2){
                if ( ! $this->upload->do_upload('file'))
                {
                        $message = [
                            'status' => true,
                            'errorData' => array('error' => $this->upload->display_errors()),
                            'token' => $jwt,
                            'message' => 'Error al modificar recurs'
                        ];
                        $this->set_response($message, 400);
                }
                else
                {
                    $data['videorecurs'] = $_FILES['file']['name'];
                }
            } elseif ($this->put("selVideorecurs") == 3) {
                $data['videorecurs'] = $this->put("videorecurs");
            } elseif ($this->put("selVideorecurs") == 4) {
                $data['canvas'] = $this->put("videorecurs");
            }
            
            $this->recursos_model->put($id, $data);

            $this->load->model('tags_model');
            $arrayEtiquetes = explode(",", $this->put("etiquetes"));
            foreach ($arrayEtiquetes as $etiqueta) {
                $dataEtiquetes = array (
                    'etiqueta_id' => $etiqueta,
                );
                $this->tags_model->putRecursEtiqueta($id, $dataEtiquetes);
            }

            $message = [
                'status' => true,
                'token' => $jwt,
                'message' => 'Recurs modificat'
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
    public function recursosProfe_options() {
        $this->output->set_header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        $this->output->set_header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $this->output->set_header("Access-Control-Allow-Origin: *");

        $this->response(null,200); // OK (200) being the HTTP response code
    }
}

/* End of file recursos_controller.php */