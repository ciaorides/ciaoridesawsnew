<?php
 error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Banner extends REST_Controller {

    protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        
        set_time_limit(0);
        //ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('app/taxiflow_model');
        $this->load->model('email_model');
        $this->load->helper('app/jwt_helper');
        
            $this->load->model('app/menuitems_model');

        $this->load->library('MathUtil');
        $this->load->library('PolyUtil');
        $this->load->library('SphericalUtil');

        $this->client_request = new stdClass();
        $this->client_request = json_decode(file_get_contents('php://input', true));
        $this->client_request = json_decode(json_encode($this->client_request), true);
        
        $this->output->set_header("HTTP/1.0 200 OK");
$this->output->set_header("HTTP/1.1 200 OK");
$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
$this->output->set_header("Pragma: no-cache");
        
        
        
        
    }
    
     public function banners_get(){

        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);


    $top_banners = $this->taxiflow_model->banners_sorting('home','top');
    $middle_banners = $this->taxiflow_model->banners_sorting('home','middle');
    $bottom_banners =$this->taxiflow_model->banners_sorting('home','bottom');

        $result=array(
                'top'=>$top_banners,
                'middle'=>$middle_banners,
                'bottom'=>$bottom_banners
                    );
        
        if (empty($result)) {
            $response = array('status' => false, 'message' => 'Data Not Found !','response'=>array());
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched successfully!','response'=>$result);
        }

            $this->response($response,200);
        
        

    } 
    
    public function get_favourites_list_get() {
          
          $userId=$_GET['user_id'];
          $favourites_details=$this->menuitems_model->get_favourites_details($userId);
        
          $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $favourites_details);
          $this->response($response);

    }
    
    
    
    

    
}