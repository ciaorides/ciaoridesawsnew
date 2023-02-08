<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Menuitems extends REST_Controller {


	protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        error_reporting(0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('app/menuitems_model');
        $this->load->model('email_model');
        $this->load->helper('app/jwt_helper');

        $this->load->library('MathUtil');
        $this->load->library('PolyUtil');
        $this->load->library('SphericalUtil');

        $this->client_request = new stdClass();
        $this->client_request = json_decode(file_get_contents('php://input', true));
        $this->client_request = json_decode(json_encode($this->client_request), true);
    }


    /*
     *  get My Rides
     */

    function get_my_rides_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
 		
 		$rides_taken=$this->menuitems_model->get_rides_taken($user_id);
 		$rides_scheduled=$this->menuitems_model->get_rides_scheduled($user_id);

 		$data['rides_taken']=$rides_taken;
 		$data['rides_offering']=array();
 		$data['rides_scheduled']=$rides_scheduled;

        $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $data);
        $this->response($response);

    }
    
    
    //
    function get_my_vehicle_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
    
    	$vehicle_details=$this->menuitems_model->get_vehicle_details($user_id);
	
        $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $vehicle_details);
        $this->response($response);

    }
    
    
    // Delete Vehicle Details 
    function my_vehicle_details_delete_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
		
	   $required_params = array('user_id' => "User ID",'vehicle_id' => "Vehicle Id",);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
    
        $data['status'] = '2';
         
        $deleteVehicle = $this->menuitems_model->delete_vehicle($data, $user_id,$vehicle_id);
       
        if ($deleteVehicle === FALSE) {
            $response = array('status' => false, 'message' => 'Vehicle Deleted Failed!');
        } else {
            //$user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Vehicle Deleted Successful!');
        }
        
        $this->response($response);

    }
    
    
     // driver app add vehicle step 1
     
     function add_driver_vehicles_step1_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!', 'response' => array());
            TrackResponse($user_input, $response);
            $this->response($response);
        }*/
        $required_params = array('user_id' => 'User ID',  'brand_id' => 'Brand Id', 'model_id' => 'Model ID', 'vehicle_type' => 'Vehicle Type');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }


        $data = array(
            'user_id' => $user_id,
            'make_id' => $brand_id,
            'model_id' => $model_id,
            'vehicle_type' => $vehicle_type,
            'created_on' => date('Y-m-d H:i:s')
        );

        $unique_id = insert_table('user_vehicles', $data);
        //echo $this->db->last_query();exit;
        //$users = user_by_id($user_id);
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle Details adding Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details submitted Successfully!','response'=>$unique_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
      // driver app add vehicle step 2

     function add_driver_vehicles_step2_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
         
//         /'vehicle_registration_number' => 'Vehicle registration number'

        $required_params = array('vehicle_id' => 'Vehicle ID',  'vehicle_registration_image' => 'Vehicle registration image');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

       
        if ($vehicle_registration_image) {
            
        $vehicle_registration_img_path='/storage/VehicleRegistration/'.$vehicle_registration_image;
            
        }

        if ($vehicle_insurance_image) {
            
           $vehicle_insurance_img_path='/storage/VehicleInsurance/'.$vehicle_insurance_image;
            
        }

        if ($fitness_certification_image) {
            
            $fitness_certification_img_path='/storage/FitnessCertificates/'.$fitness_certification_image;
        }

        if ($vehicle_permit_image) {
            
            $vehicle_permit_img_path='/storage/VehiclePermit/'.$vehicle_permit_image;
        }
         
         
         

        $update_data=array(
            'number_plate'=>'1234',
            'fitness_certification_number'=>'1234',
            'vehicle_insurance_number'=>'1234',
            'vehicle_permit_number'=>'1234',
            'vehicle_registration_image'=>$vehicle_registration_img_path,
            'vehicle_insurance_image'=>$vehicle_insurance_img_path,
            'fitness_certification_image'=>$fitness_certification_img_path,
            'vehicle_permit_image'=>$vehicle_permit_img_path,
                          );

        $unique_id=$this->db->update('user_vehicles',$update_data,array('id'=>$vehicle_id));

        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle Details Updation Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details Updated Successfully!','response'=>$unique_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
      // driver app add vehicle step 3
      
     function add_driver_vehicles_step3_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('vehicle_id' => 'Vehicle ID',  'user_id' => 'User ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //echo '<pre>';print_r($vehicle_images);exit;
        foreach($vehicle_images as $vehicle_img){
        

            $insert=array(
                'user_id'=>$user_id,
                'vehicle_id'=>$vehicle_id,
                'vehicle_image'=>'/storage/VehicleImages/'.$vehicle_img,
                'created_date'=>date('Y-m-d H:i:s')
                        );
            $this->db->insert('vehicle_images',$insert);
            $insert_id=$this->db->insert_id();
        }

        if ($insert_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle images posted  Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle images posted Successfully!','response'=>true);
        }
        TrackResponse($user_input, $response);
        $this->response($response);


    }
    
    
    
    
    
    
    
    /* Bank Details */
    
    
    /*
     * 	Add Bank Details *
     
     */

    function add_driver_bank_details_step1_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }

        $required_params = array('user_id' => "User ID", 'country_id' => "Country ID", 'bank_name' => "Bank Name", 'account_holder_name' => "Account Holder Name", 'account_number' => "Account Number", 'ifsc_code' => "IFSC Code");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //var_dump($image_result);
        $data = array(
            'user_id' => $user_id,
            'country_id' => $country_id,
            'bank_name' => $bank_name,
            'account_holder_name' => $account_holder_name,
            'account_number' => $account_number,
            'ifsc_code' => $ifsc_code,
            'created_on' => date('Y-m-d H:i:s')
        );

      //  delete_record('user_bank_details', array('user_id' => $user_id));
        $unique_id = insert_table('user_bank_details', $data);
        //echo $this->db->last_query();
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Driver Bank Details Updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Driver Bank Details Updated Successfully!','response'=>$unique_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    
    
      // driver app add vehicle step 2

     function add_driver_bank_upi_details_step2_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => "User ID",'bank_id' => 'Bank ID','Upi_id' => 'UPI ID',);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

      

        $update_data=array(
            'Upi_id'=>$Upi_id
                          );

        $unique_id=$this->db->update('user_bank_details',$update_data,array('user_id'=>$user_id,'id'=>$bank_id));

        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'UPI Id Updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'UPI ID Updated Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }

    /*
     * 	Bank Details
     */

    function driver_bank_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }
        
        	$responseResult=$this->menuitems_model->get_user_bank_details($user_id);
        	
        	
       // $responseResult = get_table_row('user_bank_details', array('user_id' => $user_id));
        //echo $this->db->last_query();
        if (empty($responseResult)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $responseResult);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    // Delete Bank Details 
    
    function driver_bank_details_delete_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
		
	   $required_params = array('user_id' => "User ID",'bank_id' => "Bank Id",);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
    
        $data['status'] = '2';
         
        $deleteBankdetails = $this->menuitems_model->delete_bank_details($data, $user_id,$bank_id);
       
        if ($deleteBankdetails === FALSE) {
            $response = array('status' => false, 'message' => 'Bank Details Deleted Failed!');
        } else {
            //$user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Bank Details Deleted Successful!');
        }
        
        $this->response($response);

    }
    
    
    // Get Favourite List 
    
    function get_favourites_list_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
    
    	$favourites_details=$this->menuitems_model->get_favourites_details($user_id);
	
        $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $favourites_details);
        $this->response($response);

    }
    
    // Get About Us link 
    
    
    function get_about_us_post(){
        
        $response = array('status' => true, 'message' => 'About Us Page Link', 'response' => 'https://ciaorides.com/new/Menuitem/about_us');
        $this->response($response);
        
        
    }
    
    
        
    // Get Privacy Policy link 
    
    
    function get_privacy_policy_post(){
        
        $response = array('status' => true, 'message' => 'Privacy Policy Page Link', 'response' => 'https://ciaorides.com/new/Menuitem/privacy_policy');
        $this->response($response);
        
        
    }
    
    
          
    // Get Privacy Policy link 
    
    
    function get_termsandconditions_post(){
        
        $response = array('status' => true, 'message' => 'Terms and conditions Page Link', 'response' => 'https://ciaorides.com/new/Menuitem/termsandcoditions');
        $this->response($response);
        
        
    }
    
    
        // Delete Favourite Details 
    
    function driver_favourite_location_delete_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
		
	   $required_params = array('user_id' => "User ID",'favourite_id' => "Favourite Id",);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
    
        $data['status'] = '2';
         
        $deleteFavourite = $this->menuitems_model->delete_favourite_location($data, $user_id,$favourite_id);
       
        if ($deleteFavourite === FALSE) {
            $response = array('status' => false, 'message' => 'Favourite Location Deleted Failed!');
        } else {
            //$user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Favourite Location Deleted Successful!');
        }
        
        $this->response($response);

    }
    
    
    // Get emergency_contacts List 
    
    function get_emergency_contacts_list_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
    
    	$emergency_contacts_details=$this->menuitems_model->get_emergency_contacts_list($user_id);
	
        $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $emergency_contacts_details);
        $this->response($response);

    }
    
    // add emergency_contacts  

    function add_driver_emergency_contacts_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }

        $required_params = array('user_id' => "User ID", 'contact_name' => "Contact Name", 'contact_number' => "Contact Number");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //var_dump($image_result);
        $data = array(
            'user_id' => $user_id,
            'name' => $contact_name,
            'mobile' => $contact_number,
            'relation' => '-',
            'created_on' => date('Y-m-d H:i:s')
        );

     //   delete_record('emergency_contacts', array('user_id' => $user_id));
        $unique_id = insert_table('emergency_contacts', $data);
        //echo $this->db->last_query();
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Emergency Contact Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Emergency Contact added Successfully!','response'=>$unique_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    
        
    // Delete emergency_contacts Details 
    
    function driver_emergency_contacts_delete_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
		*/        
		
	   $required_params = array('user_id' => "User ID",'emergency_id' => "Emergency Id",);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        $data['status'] = '2';
        
        foreach ($emergency_id as $key => $value) {
            
             $deleteEmergencyContact=$this->db->update('emergency_contacts', $data, array('user_id' => $user_id,'id'=>$value));
                 
       }
   
        
        if ($deleteEmergencyContact === FALSE) {
            
            $response = array('status' => false, 'message' => 'Emergency Contact Deleted Failed!');
            
        } else {
            
            $response = array('status' => true, 'message' => 'Emergency Contact Deleted Successful!');
            
        }
        
        $this->response($response);

    }
    
    
  // change password 
    
    function update_change_password_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

    $required_params = array('user_id' => "User ID", 'new_password' => "New Password",'old_password' => "Old Password");
        
        
        foreach ($required_params as $key => $value) {
            
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
            
        }
        
        $user_details = json_decode(json_encode(user_by_id($user_id),TRUE),TRUE);
        
      //  $old_password
        if($user_details['password']!=md5($old_password)){
            $response = array('status' => false, 'message' => 'Old Password Not Matched !', 'response' => array());
              TrackResponse($user_input, $response);
              $this->response($response);
        }
        
        
  
        
        $data = array(
            'password' => md5($new_password),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('users', $data, array('id' => $user_id));
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Change Password Updation Failed!', 'response' => array());
        } else {
            $user_details = user_by_id($user_id);
            $response = array('status' => true, 'message' => 'Change Password Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    
       // Get User Details View 1-09-2022
    
    function user_profile_post(){
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => 'User ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        
        $user_details = $this->menuitems_model->user_details($user_id);
       
        if(empty($user_details)) {
             
            $response = array('status' => false, 'message' => 'No User Profile Details!');
        }else {
           
            $response = array('status' => true, 'message' => 'User Profile Details!', 'response' => $user_details);
        }
        
         TrackResponse($user_input, $response);
        $this->response($response);
        
        
    }
    
    
    
      // Wallet My Paymnets View 12-11-2022
    
    function my_payments_post(){
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => 'User ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        
        $user_paymnets = $this->menuitems_model->get_my_paymnets($user_id);
       
        if(empty($user_paymnets)) {
             
            $response = array('status' => false, 'message' => 'No  My Payments!');
        }else {
           
            $response = array('status' => true, 'message' => 'User My Payments!', 'response' => $user_paymnets);
        }
        
         TrackResponse($user_input, $response);
        $this->response($response);
        
        
    }
    
    
    
    
      // Wallet My Earnings View 12-11-2022
    
    function my_earnings_post(){
        
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => 'User ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        
        $user_paymnets = $this->menuitems_model->get_my_earnings($user_id);
       
        if(empty($user_paymnets)) {
             
            $response = array('status' => false, 'message' => 'No My Earnings!');
        }else {
           
            $response = array('status' => true, 'message' => 'User My Earnings!', 'response' => $user_paymnets);
        }
        
         TrackResponse($user_input, $response);
        $this->response($response);
        
        
    }
       
    
    
    // update emergency contacts  

    function update_driver_emergency_contacts_post(){
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }

        $required_params = array('id' => "ID",'user_id' => "User ID", 'contact_name' => "Contact Name", 'contact_number' => "Contact Number");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //var_dump($image_result);
        
        $data = array(
            'name' => $contact_name,
            'mobile' => $contact_number,
            'created_on' => date('Y-m-d H:i:s')
        );
        
 
        $unique_id=$this->db->where('id', $id)->update('emergency_contacts', $data);
        
          //echo $this->db->last_query();
        if ($id == 0) {
            $response = array('status' => false, 'message' => 'Emergency Contact Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Emergency Contact Updated Successfully!','response'=>$id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    

    
    
    function update_driver_bank_details_step1_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }

        $required_params = array('id' => "ID",'user_id' => "User ID", 'country_id' => "Country ID", 'bank_name' => "Bank Name", 'account_holder_name' => "Account Holder Name", 'account_number' => "Account Number", 'ifsc_code' => "IFSC Code");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //var_dump($image_result);
        $data = array(
            'user_id' => $user_id,
            'country_id' => $country_id,
            'bank_name' => $bank_name,
            'account_holder_name' => $account_holder_name,
            'account_number' => $account_number,
            'ifsc_code' => $ifsc_code,
            'created_on' => date('Y-m-d H:i:s')
        );

      //  delete_record('user_bank_details', array('user_id' => $user_id));
     //   $unique_id = insert_table('user_bank_details', $data);

        $unique_id=$this->db->where('id', $id)->update('user_bank_details', $data);
        
      //echo $this->db->last_query();
        if ($id == 0) {
            $response = array('status' => false, 'message' => 'Driver Bank Details Updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Driver Bank Details Updated Successfully!','response'=>$id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    
    
       // driver app add vehicle step 2

     function update_driver_bank_upi_details_step2_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
         
        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if ($token_status != "Success") {
        //     $response = array('status' => false, 'message' => 'Token Miss Match!');
        //     TrackResponse($user_input, $response);
        //     $this->response($response);
        // }


        $required_params = array('user_id' => "User ID",'id' => "ID",'Upi_id' => 'UPI ID',);
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

    

        $update_data=array(
            'Upi_id'=>$Upi_id
                          );

        $unique_id=$this->db->update('user_bank_details',$update_data,array('user_id'=>$user_id,'id'=>$id));

        if ($id == 0) {
            $response = array('status' => false, 'message' => 'UPI Id Updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'UPI ID Updated Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
    
      // driver app edit vehicle step 1
     
     function update_driver_vehicles_step1_post() {
         
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        /*try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!', 'response' => array());
            TrackResponse($user_input, $response);
            $this->response($response);
        }*/
        $required_params = array('id' => "ID",'user_id' => 'User ID',  'brand_id' => 'Brand Id', 'model_id' => 'Model ID', 'vehicle_type' => 'Vehicle Type');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }


        $data = array(
            'user_id' => $user_id,
            'make_id' => $brand_id,
            'model_id' => $model_id,
            'vehicle_type' => $vehicle_type,
            'created_on' => date('Y-m-d H:i:s')
        );

 
         $unique_id=$this->db->where('id', $id)->update('user_vehicles', $data);

         
       // $unique_id = insert_table('user_vehicles', $data);
        //echo $this->db->last_query();exit;
        //$users = user_by_id($user_id);
        if ($id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle Details Updating Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details Updated Successfully!','response'=>$id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
      // driver app edit vehicle step 2

     function update_driver_vehicles_step2_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
         
//         /'vehicle_registration_number' => 'Vehicle registration number'

        $required_params = array('vehicle_id' => 'Vehicle ID',  'vehicle_registration_image' => 'Vehicle registration image');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

       
        if($vehicle_registration_image!='') {
            
        $vehicle_registration_img_path='/storage/VehicleRegistration/'.$vehicle_registration_image;
            
            $update_data['vehicle_registration_image']=$vehicle_registration_img_path;
            
        }
         

        if($vehicle_insurance_image!='') {
            
           $vehicle_insurance_img_path='/storage/VehicleInsurance/'.$vehicle_insurance_image;
            
            $update_data['vehicle_insurance_image']=$vehicle_insurance_img_path;
            
        }

         
        if($fitness_certification_image!='') {
            
            $fitness_certification_img_path='/storage/FitnessCertificates/'.$fitness_certification_image;
            $update_data['fitness_certification_image']=$fitness_certification_img_path;
        }
         

        if($vehicle_permit_image!='') {
            
            $vehicle_permit_img_path='/storage/VehiclePermit/'.$vehicle_permit_image;
            
            $update_data['vehicle_permit_image']=$vehicle_permit_img_path;
        }

         
//         print_r($update_data);
//             exit;
         
//        $update_data=array(
//            'number_plate'=>'1234',
//            'fitness_certification_number'=>'1234',
//            'vehicle_insurance_number'=>'1234',
//            'vehicle_permit_number'=>'1234',
//     
//                          );

        $unique_id=$this->db->update('user_vehicles',$update_data,array('id'=>$vehicle_id));
         
         
  // echo $this->db->last_query();exit;
      
        if ($vehicle_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle Details Updation Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details Updated Successfully!','response'=>$vehicle_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
      // driver app edit vehicle step 3
      
     function update_driver_vehicles_step3_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('vehicle_id' => 'Vehicle ID',  'user_id' => 'User ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        //echo '<pre>';print_r($vehicle_images);exit;
        foreach($vehicle_images as $vehicle_img){
        

            $insert=array(
                'user_id'=>$user_id,
                'vehicle_id'=>$vehicle_id,
                'vehicle_image'=>'/storage/VehicleImages/'.$vehicle_img,
                'created_date'=>date('Y-m-d H:i:s')
                        );
            $this->db->insert('vehicle_images',$insert);
            $insert_id=$this->db->insert_id();
        }

        if ($insert_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle images Updated  Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle images Updated Successfully!','response'=>true);
        }
        TrackResponse($user_input, $response);
        $this->response($response);


    }

    
    
    
    
}