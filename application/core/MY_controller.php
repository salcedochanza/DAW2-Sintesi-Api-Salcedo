<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

class API_Controller extends chriskacerguis\RestServer\RestController { 

    public function __construct () 
    { 
        parent::__construct ();
    } 
}

class JwtAPI_Controller extends chriskacerguis\RestServer\RestController {

    protected $token_data;
    protected $error_message;
    protected $auth_code;
    protected $expiration;

    public function __construct () 
    { 
        parent::__construct ();
        $this->config->load("jwt");

        $this->load->model('tokens_m');

        $this->load->helper("jwt");
        $this->load->library('ion_auth');
        $this->load->library('uuid');

        $this->token_data=new stdClass();
        $this->auth_code=200;
        $this->error_message="";
    } 

    protected function init($token_data,$expiration=null)
    {
        $this->token_data=(object) $token_data;

        if ($expiration==null) $this->expiration=$this->config->item("jwt_timeout");
        else $this->expiration=$expiration;
    }

    protected function renewJWT()
    {
        $this->token_data->iat=time();
        $this->token_data->exp=time() + $this->expiration;
        $this->token_data->jti=$this->uuid->v5($this->token_data->sub);

        $jwt = $this->getJWT();
        $this->output->set_header("Authorization: " . $jwt);

        return $jwt;
    }

    protected function getJWT()
    {
        $jwt = JWT::encode($this->token_data, $this->config->item('jwt_key'));
        return $jwt;
    }
    protected function login ($usr,$pass)
    {
        if ($this->ion_auth->login($usr,$pass))
        {
            $user = $this->ion_auth->user()->row();

            $this->token_data->usr=$user->id;

            $jwt = $this->renewJWT(); // Get new Token and set to HTTP header

            $message = [
                //'status' => REST_Controller::HTTP_OK,
                'token' => $jwt,
                'message' => 'User logged'
            ];
            $this->set_response($message); // 200
        } else {
            $this->auth_code = 401;
            $message = [
                //'status' => REST_Controller::HTTP_UNAUTHORIZED,
                'token' => "",
                'message' => 'Bad username/password'
            ];
            $this->set_response($message, $this->auth_code); // 401
        }
    }

    protected function auth_request($memberof=null)
    {
        try {
            $token=explode(" ",$this->head ("Authorization"));
            // token + password used to sign + array of allowed algorithms

            if (count($token)!=2)
            {
                $this->auth_code=400;
                $this->error_message= "Token no present or wrong format";
                return false; 
            }
            $this->token_data = JWT::decode($token[1],$this->config->item('jwt_key'),array('HS256')); 

            if ($this->config->item("jwt_autorenew")) {
                if ($this->tokens_m->revoked($this->token_data)) {
                    $this->auth_code=401;
                    $this->error_message= "Token revoked";
                    return false;
                } else {
                    $this->tokens_m->revoke($this->token_data);
                }
            }

            $user=$this->ion_auth->user($this->token_data->usr)->row();
            if ($user->active) {     // user exists && is active
                if ($memberof!==null) {   //chek if user is member of a group or groups
                    if ($this->ion_auth->in_group($memberof,$this->token_data->usr)){
                        $this->auth_code=200;
                        return true;
                    } else {
                        $this->error_message= "User NOT MEMBER of valid groups";
                        $this->auth_code=401;
                        return false;
                    }
                } else {
                    $this->auth_code=200;
                    return true;
                }
            } else {
                $this->auth_code=401;
                $this->error_message= "User disabled";
                return false;
            }
        } catch(SignatureInvalidException $e) {     // to get exception message => $e->getMessage()
            $this->error_message= print_r($e->getMessage(),true);
            $this->auth_code=400;
            return false;        
        } catch(BeforeValidException $e) {     // to get exception message => $e->getMessage()
            $this->error_message= print_r($e->getMessage(),true);
            $this->auth_code=400;
            return false;        
        } catch(UnexpectedValueException $e) {     // to get exception message => $e->getMessage()
            $this->error_message= print_r($e->getMessage(),true);
            $this->auth_code=400;
            return false;
        } catch(ExpiredException $e) {     // to get exception message => $e->getMessage()
            $this->error_message= print_r($e->getMessage(),true);
            $this->auth_code=400;
            return false;
        } catch(Exception $e) {
            $this->error_message= print_r($e->getMessage(),true);
            $this->auth_code=400;
            return false;
        } finally {
            $this->tokens_m->purge();   
        }
    }

}