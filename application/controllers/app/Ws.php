<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Ws extends REST_Controller {

    protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        error_reporting(0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('email_model');
        $this->load->helper('app/jwt_helper');

        $this->load->library('MathUtil');
        $this->load->library('PolyUtil');
        $this->load->library('SphericalUtil');

        $this->client_request = new stdClass();
        $this->client_request = json_decode(file_get_contents('php://input', true));
        $this->client_request = json_decode(json_encode($this->client_request), true);
    }

    /* -------------------- User ----------------------- */

    /*
     * 	Send OTP
     */

    function send_otp_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('mobile' => 'Mobile Number');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        // $user_details = check_user_username_exists($mobile);
        // if(!empty($user_details))
        // {
        // 	$response = array('status' => false, 'message' => 'This Username is already registered!');
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        // $user_details = check_user_username_status($mobile);
        // if(!empty($user_details))
        // {
        // 	$response = array('status' => false, 'message' => 'User With this mobile number is put on hold by Administrator!', 'response' => array());
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        // $user_details = check_user_mobile_exists($mobile);
        // if(empty($user_details))
        // {
        // 	$response = array('status' => false, 'message' => 'This mobile number is not registered with us!');
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        // $user_details = check_user_mobile_status($mobile);
        // if(!empty($user_details))
        // {
        // 	$response = array('status' => false, 'message' => 'User With this mobile number is put on hold by Administrator!', 'response' => array());
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        $otp = mt_rand(100000, 999999);
        $message = "Dear User, $otp is One time password (OTP) for CIAO Rides. Thank You.";
        SendSMS($mobile, $message);
        $response = array('status' => true, 'message' => 'Otp sent successfully!', 'response' => "$otp");
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Register User Using Mobile Number
     */

    function register_user_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('first_name' => "First Name", 'last_name' => "Last Name", 'mobile' => 'Mobile', 'dob' => 'Date of Birth', 'gender' => 'Gender', 'password' => 'Password');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        if (strlen($password) < 6) {
            $response = array('status' => false, 'message' => 'Password should have minimum of 6 characters!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        // $user_details = check_user_email_id_exists($email_id);
        // if(!empty($user_details))
        // {
        // 	$response = array('status' => false, 'message' => 'This Email ID is already registered!');
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        $user_details = get_table_row('users', array('mobile' => $mobile, 'delete_status' => 1));
        if (!empty($user_details)) {
            $response = array('status' => false, 'message' => 'This Mobile Number is already registered!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        if ($otp_confirmed == "No") {
            $otp = mt_rand(100000, 999999);
            $message = "Dear User, $otp is One time password (OTP) for CIAO Rides. Thank You.";
            SendSMS($mobile, $message);
            $response = array('status' => true, 'message' => 'Otp sent successfully!', 'response' => "$otp");
            TrackResponse($user_input, $response);
            $this->response($response);
            exit;
        }
        //'lattitude' => $lat,'longitude' => $long,
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'mobile' => $mobile,
            'alternate_number' => $alternate_number,
            'email_id' => $email_id,
            'dob' => $dob,
            'gender' => $gender,
            'lattitude' => $lat,
            'longitude' => $long,
            'password' => md5($password),
            'mobile_verified' => 'yes',
            'status' => 1,
            'created_on' => date('Y-m-d H:i:s')
        );
        $user_id = insert_table('users', $data);
        $users = get_table_row('users', array('id' => $user_id));
        if (empty($users)) {
            $response = array('status' => false, 'message' => 'User Registration Failed!', 'response' => array());
        } else {
            $message = "Dear User, Thanks for registering with CIAO Rides. Thank You.";
            SendSMS($mobile, $message);


            $salt = "CIAO_SECRET_STUFF";
            $encrypted_id = base64_encode($user_id . $salt);
            $message = "
			<html>
			<body>
			Dear User, please click on the below link to verify your email id.<br><br>

			<a href='https://www.ciaorides.com/verify_email/$encrypted_id'>Click here to verify</a>
			</body>
			</html>
			";
            $subject = "Email Verification For CIAO Rides";
            $this->session->set_tempdata('otp', $rand, 900);
            //SendSMS($email_id, $message);
            SendEmail($email_id, $subject, $message);
            $response = array('status' => true, 'message' => 'User Registration Successful!', 'response' => $users);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Login
     */

    function user_login_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('mobile' => "Mobile", 'password' => 'Password');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $user_deleted = $this->ws_model->check_user_deleted($mobile);
        if (empty($user_deleted)) {
            $response = array('status' => false, 'message' => 'This Mobile Number is not registered with us!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $user_status = $this->ws_model->check_user_status($mobile);
        //echo '<pre>';print_r($user_status);exit;
        if (empty($user_status)) {
            $response = array('status' => false, 'message' => 'Your account has been put on hold. Please contact Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        // $user_data = get_table_row('users', array('mobile' => $mobile));
        // if($android_token && $user_data['token'] == "" && $user_data['ios_token'] == "")
        // {
        // 	update_table('users', array('token' => $android_token), array('mobile' => $mobile));
        // }
        // elseif($ios_token && $user_data['ios_token'] == "" && $user_data['token'] == "")
        // {
        // 	update_table('users', array('ios_token' => $ios_token), array('mobile' => $mobile));
        // }
        // elseif(($android_token && $user_data['token'] != "" && $user_data['token'] != $android_token) || ($ios_token && $user_data['ios_token'] != "" && $user_data['ios_token'] != $ios_token) || ($android_token && $user_data['ios_token'] != "" && $user_data['token'] == "" && $user_data['token'] != $android_token) || ($ios_token && $user_data['token'] != "" && $user_data['ios_token'] == "" && $user_data['ios_token'] != $ios_token))
        // {
        // 	$response = array('status' => false, 'message' => 'Please logout from another device and please login again!');
        // 	$this->response($response);
        // }
        $user_login = $this->ws_model->user_login($mobile, $password);
        //echo $this->db->last_query();exit;
        //echo '<pre>';print_r($user_login);exit;
        if (empty((array) $user_login)) {
            $response = array('status' => false, 'message' => 'Login Failed. Please check your login credentails and try again!');
        } else {
            $user_id = $user_login->id;
            $static_str = 'CIAORIDES';
            $currenttimeseconds = date("mdY_His");
            $token_id = $static_str . $user_id . $currenttimeseconds;

            $token = array();
            $token['id'] = md5($token_id);

            $Token = JWT::encode($token, 'secret_server_key');

            delete_record('active_users', array('id' => $user_id));
            insert_table('active_users', array('id' => $user_id, 'modified_on' => date('Y-m-d H:i:s')));

            $response = array('status' => true, 'message' => 'Login Successful!', 'response' => $user_login, 'token' => $Token);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Social Login
     */

    function social_login_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('email_id' => "Email ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $user_id = $user_status['id'];
        $user_deleted = $this->ws_model->check_user_deleted($email_id);
        if (empty($user_deleted)) {
            $data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email_id' => $email_id,
                'university_id' => $university_id,
                'registered_through' => $registered_through,
                'created_on' => date('Y-m-d H:i:s')
            );
            $user_id = $this->ws_model->set_users($data);
            if ($user_id == 0) {
                $response = array('status' => false, 'message' => 'Login Failed. Please try again!');
            }
        } else {
            $user_status = $this->ws_model->check_user_status($email_id);
            if (empty($user_status)) {
                $response = array('status' => false, 'message' => 'Your account has been put on hold. Please contact Administrator!');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $users = user_by_email_id($email_id);
        $user_id = $users->id;
        $static_str = 'CIAORIDES';
        $currenttimeseconds = date("mdY_His");
        $token_id = $static_str . $user_id . $currenttimeseconds;

        $token = array();
        $token['id'] = md5($token_id);
        $Token = JWT::encode($token, 'secret_server_key');

        delete_record('active_users', array('user_id' => $user_id));
        insert_table('active_users', array('user_id' => $user_id, 'modified_on' => date('Y-m-d H:i:s')));
        $response = array('status' => true, 'message' => 'Login Successful!', 'response' => $users, 'token' => $Token);
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Device Token
     */

    function update_device_token_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $update_token = $this->ws_model->update_user_device_token($user_id, $android_token, $ios_token);
        //echo $this->db->last_query();exit;
        if (empty($update_token)) {
            $response = array('status' => false, 'message' => 'Device Token updation failed!', 'response' => array());
        } else {
            $response = array('status' => true, 'message' => 'Device Token Updated Successfully!', 'response' => $update_token);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Device Token
     */

    function user_active_status_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        delete_record('active_users', array('user_id' => $user_id));
        insert_table('active_users', array('user_id' => $user_id, 'modified_on' => date('Y-m-d H:i:s')));

        $response = array('status' => true, 'message' => 'User status is set to active!');
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Forgot Password
     */

    function forgot_password_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('mobile' => "Email ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $user_deleted = get_table_row('users', array('mobile' => $mobile, 'delete_status' => 1));
        if (empty($user_deleted)) {
            $response = array('status' => false, 'message' => 'Not a registered Mobile Number!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }

        $user_status = get_table_row('users', array('mobile' => $mobile, 'delete_status' => 1, 'status' => 1));
        if (empty($user_status)) {
            $response = array('status' => false, 'message' => 'Your account has been put on hold. Please contact Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }

        $user_details = get_table_row('users', array('mobile' => $mobile, 'delete_status' => 1));
        //echo $this->db->last_query();
        $rand = mt_rand(100000, 999999);
        if (empty($user_details)) {
            $response = array('status' => false, 'message' => 'Mobile Number not registered!');
        } else {
            $message = 'Dear Customer ' . $rand . ' is your One Time Password for CIAO Rides. Thank You.';
            SendSMS($mobile, $message);
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $user_details, 'otp' => "$rand");
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Change Password
     */

    function update_password_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => "User ID", 'password' => "Password");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'password' => md5($password),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('users', $data, array('id' => $user_id));
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Password Updation Failed!', 'response' => array());
        } else {
            $user_details = user_by_id($user_id);
            $response = array('status' => true, 'message' => 'Password Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Profile
     */

    function user_profile_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $user_details = $this->ws_model->user_details($user_id);
        $user_vehicles = $this->ws_model->user_vehicles($user_id);
        //echo $this->db->last_query();exit;
        if (empty($user_details)) {
            $response = array('status' => false, 'message' => 'No Data Found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $user_details, 'user_vehicles' => $user_vehicles);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Profile
     */

    function update_profile_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID", 'first_name' => "First Name", 'last_name' => "Last Name", 'dob' => "Date of birth", 'gender' => "Gender");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        if ($profile_pic) {
            $image_data = array(
                'image' => $profile_pic,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);

            $image = $image_result['result'];
            if (file_exists($image)) {
                $base_names = basename($image);
                $imagePath = $image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
            //echo '<pre>';print_r($image_result);exit;
        }
        if ($driver_license_front) {
            $image_data = array(
                'image' => $driver_license_front,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $driver_license_front_image = $image_result['result'];
            if (file_exists($driver_license_front_image)) {
                $base_names = basename($driver_license_front_image);
                $imagePath = $driver_license_front_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($driver_license_back) {
            $image_data = array(
                'image' => $driver_license_back,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $driver_license_back_image = $image_result['result'];
            if (file_exists($driver_license_back_image)) {
                $base_names = basename($driver_license_back_image);
                $imagePath = $driver_license_back_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($government_id_front) {
            $image_data = array(
                'image' => $government_id_front,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $government_id_front_image = $image_result['result'];
            if (file_exists($government_id_front_image)) {
                $base_names = basename($government_id_front_image);
                $imagePath = $government_id_front_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($government_id_back) {
            $image_data = array(
                'image' => $government_id_back,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $government_id_back_image = $image_result['result'];
            if (file_exists($government_id_back_image)) {
                $base_names = basename($government_id_back_image);
                $imagePath = $government_id_back_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($pan_card_front) {
            $image_data = array(
                'image' => $pan_card_front,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $pan_card_front_image = $image_result['result'];
            if (file_exists($pan_card_front_image)) {
                $base_names = basename($pan_card_front_image);
                $imagePath = $pan_card_front_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($pan_card_back) {
            $image_data = array(
                'image' => $pan_card_back,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $pan_card_back_image = $image_result['result'];
            if (file_exists($pan_card_back_image)) {
                $base_names = basename($pan_card_back_image);
                $imagePath = $pan_card_back_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($aadhar_card_front) {
            $image_data = array(
                'image' => $aadhar_card_front,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $aadhar_card_front_image = $image_result['result'];
            if (file_exists($aadhar_card_front_image)) {
                $base_names = basename($aadhar_card_front_image);
                $imagePath = $aadhar_card_front_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($aadhar_card_back) {
            $image_data = array(
                'image' => $aadhar_card_back,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $aadhar_card_back_image = $image_result['result'];
            if (file_exists($aadhar_card_back_image)) {
                $base_names = basename($aadhar_card_back_image);
                $imagePath = $aadhar_card_back_image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        $check_driver_license_id = get_table_row('users', array('driver_license_id' => $driver_license_id, 'id !=' => $user_id));
        if ($driver_license_id != "" && !empty((array) $check_driver_license_id)) {
            $response = array('status' => false, 'message' => 'Driver License ID already exists!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $check_government_id = get_table_row('users', array('government_id' => $government_id, 'id !=' => $user_id));
        if ($government_id != "" && !empty((array) $check_government_id)) {
            $response = array('status' => false, 'message' => 'Government ID already exists!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $check_pan_card_id = get_table_row('users', array('pan_card_id' => $pan_card_id, 'id !=' => $user_id));
        if ($pan_card_id != "" && !empty((array) $check_pan_card_id)) {
            $response = array('status' => false, 'message' => 'Pan Card ID already exists!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $check_aadhar_card_id = get_table_row('users', array('aadhar_card_id' => $aadhar_card_id, 'id !=' => $user_id));
        if ($aadhar_card_id != "" && !empty((array) $check_aadhar_card_id)) {
            $response = array('status' => false, 'message' => 'Aadhar Card ID already exists!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        //var_dump($image_result);
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'bio' => $bio,
            'dob' => date('Y-m-d', strtotime($dob)),
            'gender' => $gender,
            'office_email_id' => $office_email_id,
            'facebook' => $facebook,
            'instagram' => $instagram,
            'twitter' => $twitter,
            'linkedin' => $linkedin,
            //'driver_license_id' => $driver_license_id,
            //'government_id' => $government_id,
            //'pan_card_id' => $pan_card_id,
            //'aadhar_card_id' => $aadhar_card_id,
            'modified_on' => date('Y-m-d H:i:s')
        );
        $check_office_email_id = get_table_row('users', array('id' => $user_id, 'office_email_id' => $office_email_id));
        if (empty((array) $check_office_email_id)) {
            $data['office_email_id_verified'] = "no";
        }
        if ($email_id) {
            $data['email_id'] = $email_id;
            $data['email_id_verified'] = "no";
        }
        if ($alternate_number) {
            $data['alternate_number'] = $alternate_number;
        }
        if ($profile_pic) {
            $data['profile_pic'] = $image;
        }
        if ($driver_license_front) {
            $data['driver_license_front'] = $driver_license_front_image;
            $data['driver_license_id'] = $driver_license_id;
        }
        if ($driver_license_back) {
            $data['driver_license_back'] = $driver_license_back_image;
        }
        if ($government_id_front) {
            $data['government_id_front'] = $government_id_front_image;
            $data['government_id'] = $government_id;
        }
        if ($government_id_back) {
            $data['government_id_back'] = $government_id_back_image;
        }
        if ($pan_card_front) {
            $data['pan_card_front'] = $pan_card_front_image;
            $data['pan_card_id'] = $pan_card_id;
        }
        if ($pan_card_back) {
            $data['pan_card_back'] = $pan_card_back_image;
        }
        if ($aadhar_card_front) {
            $data['aadhar_card_front'] = $aadhar_card_front_image;
            $data['aadhar_card_id'] = $aadhar_card_id;
            $data['aadhar_card_verified'] = "no";
        }
        if ($aadhar_card_back) {
            $data['aadhar_card_back'] = $aadhar_card_back_image;
        }
        //var_dump($data);
        $update_user = $this->ws_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'User Updation Failed!');
        } else {
            $user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'User Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Profile Picture
     */

    function update_profile_picture_post() {
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID", 'profile_pic' => 'Profile Picture');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        if ($profile_pic) {
            $image_data = array(
                'image' => $profile_pic,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $image = $image_result['result'];
            if (file_exists($image)) {
                $base_names = basename($image);
                $imagePath = $image;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //	resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }
        if ($profile_pic) {
            $data['profile_pic'] = $image;
        }
        //var_dump($data);
        $update_user = $this->ws_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Profile Picture Updation Failed!');
        } else {
            $user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Profile Picture Updated Successfully!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Address
     */

    function update_address_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID", 'address1' => "Address 1", 'address2' => "Address 2", 'postcode' => "Postcode", 'city_id' => "City ID", 'state_id' => "State ID", 'country_id' => "Country ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //var_dump($image_result);
        $data = array(
            'address1' => $address1,
            'address2' => $address2,
            'postcode' => $postcode,
            'city_id' => $city_id,
            'state_id' => $state_id,
            'country_id' => $country_id,
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('users', $data, array('id' => $user_id));
        //echo $this->db->last_query();
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Address Updation Failed!');
        } else {
            $user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Address Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Add Bank Details *
     
     */

    function add_bank_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        delete_record('user_bank_details', array('user_id' => $user_id));
        $response = insert_table('user_bank_details', $data);
        //echo $this->db->last_query();
        if ($response == 0) {
            $response = array('status' => false, 'message' => 'Bank Details Updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Bank Details Updated Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Bank Details
     */

    function bank_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $response = get_table_row('user_bank_details', array('user_id' => $user_id));
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Payment Mode
     */

    function update_payment_mode_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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

        $required_params = array('user_id' => "User ID", 'payment_mode' => "Payment Mode");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //var_dump($image_result);
        $data = array(
            'payment_mode' => $payment_mode,
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('users', $data, array('id' => $user_id));
        //echo $this->db->last_query();
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Payment Mode Updation Failed!');
        } else {
            $user_details = $this->ws_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Payment Mode Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Driver Start Ride
     */

    function driver_start_ride_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'ride_id' => "Ride ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //var_dump($image_result);
        // $order_details = get_table_row('orders', array('ride_id' => $ride_id, 'rider_id' => $user_id, 'status' => "accepted"), '', 'id', 'desc');
        // if(empty((array)$order_details))
        // {
        // 	$response = array('status' => false, 'message' => 'Problem in starting the trip!');
        // 	$this->response($response);
        // }
        $data = array(
            'driver_status' => 'started',
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('orders', $data, array('ride_id' => $ride_id, 'rider_id' => $user_id));
        $update_rider = update_table('rides', $data, array('id' => $ride_id, 'user_id' => $user_id));
        //echo $this->db->last_query();
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Trip not started!');
        } else {
            $orders = $this->ws_model->get_ride_orders($ride_id);
            if (!empty($orders)) {
                foreach ($orders as $order) {
                    $this->ws_model->send_push_notification('Trip started sucessfully!', 'user', $order['user_id'], 'ride_started', $order['order_id'], '', '', $order['vehicle_id'], $ride_id, $order['mode'], $order['ride_type']);
                }
            }
            $response = array('status' => true, 'message' => 'Trip started sucessfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Get Distance
     */

    function get_distance_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'from_lat' => "From Latitude", 'from_lng' => "From Longitude", 'to_lat' => "To Latitude", 'to_lng' => "To Longitude", 'travel_type' => "Travel Type");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
        //var_dump($trip_distance);
        $minutes = explode(" ", $trip_distance['time']);
        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }
        $distance = explode(" ", $trip_distance['distance']);
        //var_dump($distance);
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }
        $calculations = get_table_row('amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => $vehicle_type));
        //print_r($calculations);
        $fare_per_km = 0;
        if ($travel_type == "city") {
            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 10) {
                $fare_per_km = $calculations['2to10'];
            } elseif ($km > 10 && $km <= 30) {
                $fare_per_km = $calculations['11to30'];
            }
        } elseif ($travel_type == "outstation") {
            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 25) {
                $fare_per_km = $calculations['2to25'];
            } elseif ($km > 25) {
                $fare_per_km = $calculations['>25'];
            }
        }
        $amount = (($fare_per_km * $km) + $calculations['base_fare']);
        $amount_according_to_seats = (($fare_per_km * $km) + $calculations['base_fare']) * $seats_required;
        $amount_per_head = (($fare_per_km * $km));

        $ciao_commission = ($calculations['ciao_commission'] / 100) * $amount_according_to_seats;

        //$tax = ($calculations['service_tax'] / 100) * $amount_according_to_seats;
        $tax = ($calculations['service_tax'] / 100) * $ciao_commission;
        $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount_according_to_seats;

        $total_amount = $amount_according_to_seats + $tax + $payment_gateway_commision + $ciao_commission;

        /* Driver per seat calculation */

        $per_seat_ciao_commission = ($calculations['ciao_commission'] / 100) * $amount;
        $per_seat_tax = ($calculations['service_tax'] / 100) * $per_seat_ciao_commission;
        $per_seat_payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount;

        $per_seat_amount = $amount - $tax - $payment_gateway_commision - $ciao_commission;


        $cancellation_charges = get_table_row('orders', array('cancellation_charges_status' => 'unpaid', 'status' => 'cancelled by user', 'user_id' => $user_id), '', 'id', 'desc');
        //echo $this->db->last_query();

        $response = array('status' => true, 'message' => 'Distance Calculated Successfully!', 'response' => $km, 'amount' => round($amount), 'tax' => round($tax), 'payment_gateway_commision' => round($payment_gateway_commision), 'ciao_commission' => round($ciao_commission), 'total_amount' => round($total_amount), 'cancellation_charges' => (int) $cancellation_charges['cancellation_charges'], 'amount_per_head' => round($amount_per_head), 'base_fare' => (int) $calculations['base_fare'], 'per_seat_amount' => round($per_seat_amount));
        $this->response($response);
    }

    /*
     * 	Get Gogone Distance
     */

    function get_gogone_distance_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'from_lat' => "From Latitude", 'from_lng' => "From Longitude", 'to_lat' => "To Latitude", 'to_lng' => "To Longitude");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
        //var_dump($trip_distance);
        $minutes = explode(" ", $trip_distance['time']);
        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }
        $distance = explode(" ", $trip_distance['distance']);
        //var_dump($distance);
        $km = 0;
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }
        $calculations = get_table_row('amount_calculations', array('travel_type' => 'city', 'vehicle_type' => $vehicle_type));
        $service_tax = get_table_row('service_tax');
        //print_r($calculations);
        $fare_per_km = 0;
        if ($km > 0 && $km < 10) {
            $fare_per_km = $calculations['0_to_10'];
        } elseif ($km >= 10 && $km < 25) {
            $fare_per_km = $calculations['10_to_25'];
        } elseif ($km >= 25 && $km < 75) {
            $fare_per_km = $calculations['25_to_75'];
        } elseif ($km >= 75 && $km < 200) {
            $fare_per_km = $calculations['75_to_200'];
        } elseif ($km >= 200) {
            $fare_per_km = $calculations['greater_than_200'];
        }
        //echo $fare_per_km;
        if ($vehicle_type == "bike") {
            $percentage = $calculations['bike_discount'];
        } else {
            if ($seats_required == 2) {
                $percentage = $calculations['for_two_discount'];
            } elseif ($seats_required == 3) {
                $percentage = $calculations['for_three_discount'];
            } elseif ($seats_required == 4) {
                $percentage = $calculations['for_four_discount'];
            } elseif ($seats_required == 5) {
                $percentage = $calculations['for_five_discount'];
            }
        }
        $amount_according_to_seats = (($fare_per_km * $km) * $seats_required) + $calculations['base_fare'];
        $percentage_amount = ($percentage / 100) * $amount_according_to_seats;
        $percentage_amount_final = $amount_according_to_seats - $percentage_amount;

        /* ----- GST will be calculated with the amount before discount ------- */
        $gst = ($service_tax['tax'] / 100) * $amount_according_to_seats;
        //$rider_fare = $percentage_amount_final + $service_tax['tax'];
        $gogone_fare = ($seats_required * 2) + $gst + $service_tax['payment_gateway_charges'];
        $total_fare = $percentage_amount_final + $gogone_fare;
        $amount = $km * $calculations['per_km'] * $seats_required;

        $response = array('status' => true, 'message' => 'Distance Calculated Successfully!', 'response' => $km, 'amount' => round($total_fare), 'gogone_fare' => round($gogone_fare), 'gst_for_test' => round($gst));
        $this->response($response);
    }

    /*
     * 	Offer A Ride
     */

    function offer_a_ride_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID", 'vehicle_id' => "Vehicle ID", 'from_lat' => "From Latitude", 'from_lng' => "From Longitude", 'from_address' => "From Address", 'to_lat' => "To Latitude", 'to_lng' => "To Longitude", 'to_address' => "To Address", 'mode' => "Mode", 'vehicle_type' => "Vehicle Type", 'gender' => "Gender", 'seats_available' => "Seats Available", 'ride_type' => "Ride Type");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        delete_record('rides', array('user_id' => $user_id, 'status' => 'ongoing', 'ride_type' => 'now'));
        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
        $minutes = explode(" ", $trip_distance['time']);
        //var_dump($minutes);
        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }
        $calculations = get_table_row('amount_calculations', array('travel_type' => 'city'));
        $distance = explode(" ", $trip_distance['distance']);
        //var_dump($distance);
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }
        $trip_id = mt_rand(100000, 999999);
        $data = array(
            'trip_id' => $trip_id,
            'user_id' => $user_id,
            'vehicle_id' => $vehicle_id,
            'from_lat' => $from_lat,
            'from_lng' => $from_lng,
            'from_address' => $from_address,
            'to_lat' => $to_lat,
            'to_lng' => $to_lng,
            'to_address' => $to_address,
            'mode' => $mode,
            'vehicle_type' => $vehicle_type,
            'gender' => $gender,
            'seats_available' => $seats_available,
            'seats' => $seats,
            'note' => $note,
            'trip_distance' => $km,
            //'amount_per_head' => $km * $calculations['per_km'],
            'amount_per_head' => $amount_per_head,
            'ride_type' => $ride_type,
            'ride_time' => date('Y-m-d H:i:s', strtotime($ride_time)),
            'middle_seat_empty' => $middle_seat_empty,
            'message' => $message,
            'created_on' => date('Y-m-d H:i:s')
        );
        $ride_id = insert_table('rides', $data);
        $poly_lat_lngs = all_waypoints_fromLatLng_toLatLng($from_lat, $from_lng, $to_lat, $to_lng);
        if (!empty($poly_lat_lngs)) {
            delete_record('ride_lat_lngs', array('ride_id' => $ride_id));
            //echo $this->db->last_query();exit;
            $polys_data = array();
            foreach ($poly_lat_lngs as $polys) {
                //echo "test";
                $polys_data[] = array(
                    'lat' => $polys['lat'],
                    'lng' => $polys['lng'],
                    'ride_id' => $ride_id,
                    'created_on' => date('Y-m-d H:i:s')
                );
            }
            //var_dump($polys_data);
            if (!empty($polys_data)) {
                //echo "test";
                insert_table('ride_lat_lngs', $polys_data, '', true);
            }
        } else {
            delete_record('rides', array('id' => $ride_id));
            $response = array('status' => false, 'message' => 'Poly Latitudes not generated!');
            $this->response($response);
        }
        //echo $this->db->last_query();exit;
        if ($ride_id <= 0) {
            $response = array('status' => false, 'message' => 'Ride not posted!');
        } else {
            $ride_details = get_table_row('rides', array('id' => $ride_id));
            $response = array('status' => true, 'message' => 'Data Posted Successful!', 'response' => $ride_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Vehicle Makes
     */

    function vehicle_makes_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $response = get_table('vehicle_makes', array('vehicle_type' => $vehicle_type), '', 'title', 'asc');
        //echo $this->db->last_query();

        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Vehicle Models
     */

    function vehicle_models_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('make_id' => "Make ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $response = get_table('vehicle_models', array('make_id' => $make_id));
        //echo $this->db->last_query();

        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Add User Vehicles
     */

    function add_user_vehicles_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!', 'response' => array());
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $required_params = array('user_id' => 'User ID', 'country' => 'Country', 'number_plate' => 'Number Plate', 'make_id' => 'Make Id', 'model_id' => 'Model ID', 'color' => 'Color', 'year' => 'Year', 'vehicle_picture' => 'Vehicle Picture');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $image_data = array(
            'upload_path' => './storage/',
            'file_path' => 'storage/'
        );
        if ($vehicle_picture) {
            $image_data['image'] = $vehicle_picture;
            $image_result = upload_image($image_data);
            if (!$image_result['status']) {
                $response = array('status' => false, 'message' => 'Image saving is unsuccessful!');
                TrackResponse($user_input, $response);
                $this->response($response);
            } else {
                $image = $image_result['result'];
                if (file_exists($image)) {
                    $base_names = basename($image);
                    $imagePath = $image;
                    $destPath = 'storage/' . $base_names;
                    $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                    if ($sizes_in_kb > 50) {
                        //	resizeImage($imagePath,$destPath,300,300,100);
                    }
                }
            }
        }
        $data = array(
            'user_id' => $user_id,
            'country' => $country,
            'number_plate' => $number_plate,
            'make_id' => $make_id,
            'model_id' => $model_id,
            'car_type' => $car_type,
            'color' => $color,
            'year' => $year,
            'vehicle_type' => $vehicle_type,
            'created_on' => date('Y-m-d H:i:s')
        );

        if ($vehicle_picture) {
            $data['vehicle_picture'] = $destPath;
        }
        $unique_id = insert_table('user_vehicles', $data);
        //$users = user_by_id($user_id);
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle Details updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details submitted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Vehicles
     */

    function user_vehicles_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->user_vehicles($user_id);
        $bikes_count = $this->ws_model->user_vehicles_count($user_id, 'bike');
        $cars_count = $this->ws_model->user_vehicles_count($user_id, 'car');
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response, 'bikes_count' => $bikes_count, 'cars_count' => $cars_count);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Vehicles Details
     */

    function user_vehicles_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_vehicle_id' => "User Vehicle ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $response = get_table_row('user_vehicles', array('id' => $user_vehicle_id));
        //echo $this->db->last_query();

        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update User Vehicles
     */

    function update_user_vehicles_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        if ($token_status != "Success") {
            $response = array('status' => false, 'message' => 'Token Miss Match!', 'response' => array());
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $required_params = array('user_vehicle_id' => "User Vehicle ID", 'country' => 'Country', 'number_plate' => 'Number Plate', 'make_id' => 'Make Id', 'model_id' => 'Model ID', 'color' => 'Color', 'year' => 'Year');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $image_data = array(
            'upload_path' => './storage/',
            'file_path' => 'storage/'
        );

        if ($vehicle_picture) {
            $image_data['image'] = $vehicle_picture;
            $image_result = upload_image($image_data);
            if (!$image_result['status']) {
                $response = array('status' => false, 'message' => 'Image saving is unsuccessful!');
                TrackResponse($user_input, $response);
                $this->response($response);
            } else {
                $image = $image_result['result'];
                if (file_exists($image)) {
                    $base_names = basename($image);
                    $imagePath = $image;
                    $destPath = 'storage/' . $base_names;
                    $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                    if ($sizes_in_kb > 50) {
                        //	resizeImage($imagePath,$destPath,300,300,100);
                    }
                }
            }
        }
        $data = array(
            'country' => $country,
            'number_plate' => $number_plate,
            'make_id' => $make_id,
            'model_id' => $model_id,
            'car_type' => $car_type,
            'color' => $color,
            'year' => $year,
            'vehicle_type' => $vehicle_type,
            'created_on' => date('Y-m-d H:i:s')
        );

        if ($vehicle_picture) {
            $data['vehicle_picture'] = $destPath;
        }
        $unique_id = update_table('user_vehicles', $data, array('id' => $user_vehicle_id));
        //$users = user_by_id($user_id);
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Details updation Failed!');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle Details updated Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Delete User Vehicles
     */

    function delete_user_vehicles_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_vehicle_id' => "User Vehicke ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = delete_record('user_vehicles', array('id' => $user_vehicle_id));
        //echo $this->db->last_query();exit;
        if ($response == false) {
            $response = array('status' => false, 'message' => 'Record not deleted!');
        } else {
            $response = array('status' => true, 'message' => 'Record deleted successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Add Location to favourites
     */

    function add_location_favourites_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID", 'type' => "Type", 'lat' => "Latitude", 'lng' => "Longitude", 'address' => "Address");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'user_id' => $user_id,
            'type' => $type,
            'lat' => $lat,
            'lng' => $lng,
            'address' => $address,
            'created_on' => date('Y-m-d H:i:s')
        );

        $insert_table = insert_table('favourite_locations', $data);
        //echo $this->db->last_query();exit;
        if ($insert_table <= 0) {
            $response = array('status' => false, 'message' => 'Not added to Favourite!');
        } else {
            $response = array('status' => true, 'message' => 'Added to favourite!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Favourite Location
     */

    function favourite_locations_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table('favourite_locations', array('user_id' => $user_id));
        //echo $this->db->last_query();exit;
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Favourites not found!');
        } else {
            $response = array('status' => true, 'message' => 'Favourites fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Delete Favourite Location
     */

    function delete_favourite_location_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('favourite_id' => "Favourite ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = delete_record('favourite_locations', array('id' => $favourite_id));
        //echo $this->db->last_query();exit;
        if ($response == false) {
            $response = array('status' => false, 'message' => 'Record not deleted!');
        } else {
            $response = array('status' => true, 'message' => 'Record deleted successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Add Emergency Contacts
     */

    function add_emergency_contacts_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'name' => "Name", 'mobile' => "Mobile");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $contact_details = get_table_row('emergency_contacts', array('user_id' => $user_id, 'mobile' => $mobile));
        if (!empty((array) $contact_details)) {
            $response = array('status' => false, 'message' => 'Contact Number already exists!');
            $this->response($response);
        }
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'mobile' => $mobile,
            'relation' => $relation,
            'created_on' => date('Y-m-d H:i:s')
        );
        $insert_table = insert_table('emergency_contacts', $data);
        //echo $this->db->last_query();exit;
        if ($insert_table <= 0) {
            $response = array('status' => false, 'message' => 'Emergency Contact not added!');
        } else {
            $response = array('status' => true, 'message' => 'Emergency Contact added successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Emergency Contacts
     */

    function emergency_contacts_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table('emergency_contacts', array('user_id' => $user_id));
        //echo $this->db->last_query();exit;
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No Data Found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Delete Emergency Contacts
     */

    function delete_emergency_contacts_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('contact_id' => "Emergency Contact ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = delete_record('emergency_contacts', array('id' => $contact_id));
        //echo $this->db->last_query();exit;
        if ($response == false) {
            $response = array('status' => false, 'message' => 'Record not deleted!');
        } else {
            $response = array('status' => true, 'message' => 'Record deleted successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Edit Emergency Contacts
     */

    function edit_emergency_contacts_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('contact_id' => "Contact ID", 'name' => "Name", 'mobile' => "Mobile");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $contact_details = get_table_row('emergency_contacts', array('user_id' => $user_id, 'mobile' => $mobile, 'id !=' => $contact_id));
        //echo $this->db->last_query();exit;
        if (!empty((array) $contact_details)) {
            $response = array('status' => false, 'message' => 'Contact Number already exists!');
            $this->response($response);
        }
        $data = array(
            'name' => $name,
            'mobile' => $mobile,
            'relation' => $relation,
            'created_on' => date('Y-m-d H:i:s')
        );
        $update_table = update_table('emergency_contacts', $data, array('id' => $contact_id));
        //echo $this->db->last_query();exit;
        if ($update_table == false) {
            $response = array('status' => false, 'message' => 'Emergency Contact not updated!');
        } else {
            $response = array('status' => true, 'message' => 'Emergency contact updated successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rides Started
     */

    function rides_started_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('rider_id' => "Rider ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //$response = get_table('orders', array('rider_id' => $rider_id, 'status' => 'started'), '', 'id', 'desc', 1);

        $response = $this->ws_model->rides_started($rider_id, 'started');

        $rides = get_table_row('rides', array('user_id' => $rider_id, 'status' => 'ongoing'), '', '', 'desc');
        //echo $this->db->last_query();
        $ride_id = $rides['id'];

        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!', 'ride_id' => $ride_id);
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response, 'rides' => $rides, 'ride_id' => $ride_id);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Notifications
     */

    function notifications_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $user_details = get_table_row('users', array('id' => $user_id));
        $response = get_table('notifications', array('created_on >=' => $user_details['created_on']), '', 'id', 'desc');
        //echo $this->db->last_query();
        $rand = mt_rand(100000, 999999);

        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Feedback
     */

    function user_feedback_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID", 'description' => "Description");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $insert_id = insert_table('user_feedback', array('user_id' => $user_id, 'description' => $description, 'created_on' => date('Y-m-d H:i:s')));
        //echo $this->db->last_query();exit;
        if ($insert_id <= 0) {
            $response = array('status' => false, 'message' => 'Feedback not submitted!');
        } else {
            $response = array('status' => true, 'message' => 'Feedback submitted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Place Order
     */

    function place_order_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID", 'from_lat' => "From Latitude", 'from_lng' => "From Longitude", 'from_address' => "From Address", 'to_lat' => "To Latitude", 'to_lng' => "To Longitude", 'to_address' => "To Address", 'mode' => "Mode", 'vehicle_type' => "Vehicle Type", 'gender' => "Gender", 'seats_required' => "Seats Required", 'ride_type' => "Ride Type");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        delete_record('orders', array('user_id' => $user_id, 'status' => 'pending', 'ride_type' => 'now'));
        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
        $minutes = explode(" ", $trip_distance['time']);
        //var_dump($minutes);
        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }
        $distance = explode(" ", $trip_distance['distance']);
        //var_dump($distance);
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }
        //echo $km;
        $calculations = get_table_row('amount_calculations', array('travel_type' => $mode, 'vehicle_type' => $vehicle_type));
        //print_r($calculations);
        $fare_per_km = 0;
        if ($mode == "city") {
            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 10) {
                $fare_per_km = $calculations['2to10'];
            } elseif ($km > 10 && $km <= 30) {
                $fare_per_km = $calculations['11to30'];
            }
        } elseif ($mode == "outstation") {
            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 25) {
                $fare_per_km = $calculations['2to25'];
            } elseif ($km > 25) {
                $fare_per_km = $calculations['>25'];
            }
        }
        // $amount = (($fare_per_km * $km));
        // $amount_according_to_seats = (($fare_per_km * $km)  + $calculations['base_fare'])  * $seats_required;
        // $tax = ($calculations['service_tax'] / 100) * $amount_according_to_seats;
        // $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount_according_to_seats;
        // $ciao_commission = ($calculations['ciao_commission'] / 100) * $amount_according_to_seats;
        // $total_amount = $amount_according_to_seats + $tax + $payment_gateway_commision + $ciao_commission;


        $amount = (($fare_per_km * $km) + $calculations['base_fare']);
        $amount_according_to_seats = (($fare_per_km * $km) + $calculations['base_fare']) * $seats_required;
        $amount_per_head = (($fare_per_km * $km));

        $ciao_commission = ($calculations['ciao_commission'] / 100) * $amount_according_to_seats;

        //$tax = ($calculations['service_tax'] / 100) * $amount_according_to_seats;
        $tax = ($calculations['service_tax'] / 100) * $ciao_commission;
        $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount_according_to_seats;

        $total_amount = $amount_according_to_seats + $tax + $payment_gateway_commision + $ciao_commission;


        $booking_id = mt_rand(100000, 999999);
        $data = array(
            'booking_id' => $booking_id,
            'user_id' => $user_id,
            'from_lat' => $from_lat,
            'from_lng' => $from_lng,
            'from_address' => $from_address,
            'to_lat' => $to_lat,
            'to_lng' => $to_lng,
            'to_address' => $to_address,
            'mode' => $mode,
            'vehicle_type' => $vehicle_type,
            'gender' => $gender,
            'seats_required' => $seats_required,
            'trip_distance' => $km,
            'amount' => round($amount),
            'base_fare' => round($calculations['base_fare']),
            'tax' => round($tax),
            'payment_gateway_commision' => round($payment_gateway_commision),
            'ciao_commission' => round($ciao_commission),
            'total_amount' => round($total_amount),
            'ride_type' => $ride_type,
            'ride_time' => date('Y-m-d H:i:s', strtotime($ride_time)),
            'created_on' => date('Y-m-d H:i:s')
        );
        $order_id = insert_table('orders', $data);
        //echo $this->db->last_query();exit;
        // if($order_id <= 0)
        // {
        // 	$response = array('status' => false, 'message' => 'Order not placed!');
        // }
        // else
        // {
        // 	if($ride_type == "now")
        // 	{
        // 		$this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode);
        // 	}
        // 	$wait_count = 5;
        // 	SearchAgain:
        // 	if($ride_type == "now")
        // 	{
        // 		$order_details = $this->ws_model->check_order_status($order_id, $user_id, $mode);
        // 	}
        // 	else
        // 	{
        // 		$order_details = $this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode);
        // 	}
        // 	if(empty($order_details))
        // 	{
        // 		if($wait_count>0){ sleep(5); $wait_count--; goto SearchAgain; }
        // 		$response = array('status' => false, 'message' => 'No riders found!', 'response' => array());
        // 	}
        // 	else
        // 	{
        // 		$response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $order_details);
        // 	}
        // }
        if ($order_id <= 0) {
            $response = array('status' => false, 'message' => 'Ride not placed!');
        } else {
            if ($ride_type == "now" && $mode == "city") {
                $this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode, $filter);
                $wait_count = 10;
                SearchAgain:
                $order_details = $this->ws_model->check_order_status($order_id, $user_id);
            } elseif ($ride_type == "later" && $mode == "city") {
                $order_details = $this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode, $filter);
            } elseif ($ride_type == "now" && $mode == "outstation") {
                $order_details = $this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode, $filter);
            } elseif ($ride_type == "later" && $mode == "outstation") {
                $order_details = $this->ws_model->find_near_by_riders($data, $order_id, $ride_type, $mode, $filter);
            }
            //print_r($order_details);
            if (empty($order_details)) {
                if ($wait_count > 0) {
                    sleep(5);
                    $wait_count--;
                    goto SearchAgain;
                }
                $response = array('status' => false, 'message' => 'No riders found!', 'response' => array());
            } else {
                $distance_time = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
                $minutes = explode(" ", $distance_time['time']);
                //var_dump($minutes);
                if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
                    $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
                } else {
                    $seconds = $minutes[0] * 60;
                }
                $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $order_details, 'time' => $distance_time['time']);
            }
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Send rider request
     */

    function send_rider_request_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('rider_id' => "Rider ID", 'order_id' => "Order ID", 'user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'amount' => $amount,
            'base_fare' => $base_fare,
            'tax' => $tax,
            'payment_gateway_commision' => $payment_gateway_commision,
            'ciao_commission' => $ciao_commission,
            'total_amount' => $total_amount,
            //'payment_status' => 'paid',
            'modified_on' => date('Y-m-d H:i:s')
        );
        update_table('orders', $data, array('id' => $order_id));

        $cancellation_charges = get_table_row('orders', array('cancellation_charges_status' => 'unpaid', 'status' => 'cancelled by user', 'user_id' => $user_id, 'mode' => 'city', 'ride_type' => 'now'), '', 'id', 'desc');
        update_table('orders', array('cancellation_charges_status' => 'paid'), array('id' => $cancellation_charges['id']));

        $this->ws_model->send_push_notification('You have a new booking!', 'rider', $rider_id, 'new_order_schedule', $order_id, $vehicle_id, $ride_id);
        //echo $this->db->last_query();exit;
        $response = array('status' => true, 'message' => 'Request sent Successfully!');
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Chats
     */

    function chats_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $chats = $this->ws_model->chats($user_id);
        //echo $this->db->last_query();exit;
        if (empty($chats)) {
            $response = array('status' => false, 'message' => 'No data found!', 'response' => array());
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $chats);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Search Chats
     */

    function search_chats_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $chats = $this->ws_model->search_chats($user_id, $keyword);
        //echo $this->db->last_query();exit;
        if (empty($chats)) {
            $response = array('status' => false, 'message' => 'No data found!', 'response' => array());
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $chats);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    function submit_chat_message_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        $required_params = array('order_id' => "Order ID", 'booking_id' => "Booking ID", 'sender_id' => "Sender ID", 'receiver_id' => "Receiver ID", 'message' => "Message");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'order_id' => $order_id,
            'booking_id' => $booking_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'created_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = insert_table('chats', $data);
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Unable to send message', 'response' => array());
            $this->response($response);
        } else {
            $this->ws_model->send_push_notification($message, 'user', $receiver_id, 'chat_message', '', '', $sender_id, '', '', '', '', $data);
            $chat_array = $this->ws_model->chat_scroll_data($order_id, $booking_id, $sender_id, $receiver_id, $count);
            if (empty($chat_array)) {
                $response = array('status' => false, 'message' => 'Unable to fetch messages', 'response' => array());
            } else {
                $response = array('status' => true, 'message' => 'Messages fetched Successfully!', 'response' => $chat_array);
            }
            $this->response($response);
        }
        $this->response($response);
    }

    /*
     * 	Chat Details
     */

    function chat_details_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        // try {
        //     JWT::decode($token, 'secret_server_key');
        //     $token_status = "Success";
        // } catch (Exception $e) {
        //     $token_status = $e->getmessage();
        // }
        // if($token_status != "Success")
        // {
        // 	$response = array('status' => false, 'message' => 'Token Miss Match!');
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }

        $required_params = array('order_id' => "Order ID", 'booking_id' => "Booking ID", 'sender_id' => "Sender ID", 'receiver_id' => "Receiver ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $chat_array = $this->ws_model->chat_scroll_data($order_id, $booking_id, $sender_id, $receiver_id, $count);
        //echo $this->db->last_query();exit;
        if (empty($chat_array)) {
            $response = array('status' => false, 'message' => 'No data found!', 'response' => array());
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $chat_array);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rider Orders
     */

    function rider_orders_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $wait_count = 5;

        SearchAgain:
        $rider_orders = $this->ws_model->rider_orders($user_id);

        if (empty($rider_orders)) {
            if ($wait_count > 0) {
                sleep(3);
                $wait_count--;
                goto SearchAgain;
            }

            $response = array('status' => false, 'message' => 'No data available!', 'response' => array());
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $rider_orders);
        }
        //TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Accept Order
     */

    function accept_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('order_id' => "Order ID", 'ride_id' => "Ride ID", 'rider_id' => "Rider ID", 'vehicle_id' => "Vehicle ID", 'booking_id' => "Booking ID", 'user_id' => "User ID", 'rider_name' => "Rider Name");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $tdate = date('Y-m-d H:i:s');
        $res = get_table_row('orders', array('id' => $order_id));
        $diff = strtotime($tdate) - strtotime($res['created_on']);
        if ($diff >= 35 && $mode == "city" && $ride_type == "now") {
            $response = array('status' => false, 'message' => 'This ride is no more available!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $res = get_table_row('orders', array('id' => $order_id, 'status' => 'accepted'));
        if (!empty($res)) {
            $response = array('status' => false, 'message' => 'Ride accepted by some other rider!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $res = get_table_row('orders', array('id' => $order_id, 'status' => 'started'));
        if (!empty($res)) {
            $response = array('status' => false, 'message' => 'Ride started by some other rider!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }

        $ride_details = get_table_row('rides', array('id' => $ride_id));
        $ride_seats = $ride_details['seats_available'];

        $order_details = get_table_row('orders', array('ride_id' => $ride_id, 'status' => 'started', 'status' => 'accepted'), array('sum(seats_required) as seats_required'));
        $order_booked_seats = $order_details['seats_required'];

        $order_details = get_table_row('orders', array('id' => $order_id), array('seats_required'));
        $order_required_seats = $order_details['seats_required'];
        if ($ride_seats < ($order_booked_seats + $order_required_seats)) {
            $response = array('status' => false, 'message' => 'No seats available for this ride!');
            $this->response($response);
        }

        $data = array(
            'ride_id' => $ride_id,
            'rider_id' => $rider_id,
            'vehicle_id' => $vehicle_id,
            'status' => 'accepted',
            'accepted_date' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while accepting the Ride!');
        } else {
            $rider_details = get_table_row('users', array('id' => $rider_id));
            $user_details = get_table_row('users', array('id' => $user_id));
            $first_name = $rider_details['first_name'];
            $last_name = $rider_details['last_name'];
            $message = "Your ride with Booking ID: " . $booking_id . " has been accepted by $first_name $last_name";
            //echo $message;
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'accepted_request', $mode, $ride_type);
            SendSMS($user_details['mobile'], $message);
            $response = array('status' => true, 'message' => 'Ride accepted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Decline Order
     */

    function decline_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID", 'order_id' => "Order ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'created_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = insert_table('declined_orders', $data);

        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Booking not declined!');
        } else {
            $order_details = get_table_row('orders', array('id' => $order_id));
            if ($order_details['ride_type'] == "later" || $order_details['mode'] == "outstation") {
                $booking_id = $order_details['order_id'];
                $user_id = $order_details['user_id'];
                $message = "Driver Rejected Your Request!";
                $this->ws_model->send_push_notification($message, 'user', $user_id, 'decline_driver');
            }
            $response = array('status' => true, 'message' => 'Booking declined Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	User Cancel Order
     */

    function user_cancel_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('order_id' => "Order ID", 'rider_id' => "Rider ID", 'booking_id' => "Booking ID", 'user_name' => "User Name", 'user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //$order_started = get_table_row('orders', array('id' => $order_id, 'status !=' => 'pending', 'status !=' => 'accepted', 'status !=' => 'cancelled by user', 'status !=' => 'cancelled by rider'));
        $order_started = $this->ws_model->check_order_started($order_id);
        //echo $this->db->last_query();
        if (!empty((array) $order_started)) {
            $response = array('status' => false, 'type' => 'reload', 'message' => 'Your ride already started!', 'response' => $order_started);
            $this->response($response);
        }
        $data = array(
            'status' => 'cancelled by user',
            'modified_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $datetime = date('Y-m-d H:i:s');
        $order_details = get_table_row('orders', array('id' => $order_id));
        if ($order_details['ride_type'] == "now" && ($order_details['mode'] == "city" || $order_details['mode'] == "outstation")) {
            //echo "test";
            $minutes = (strtotime($order_details['ride_time']) - $datetime) / 60;
            $calculations = get_table_row('amount_calculations', array('travel_type' => 'city', 'vehicle_type' => $order_details['vehicle_type']));
            if ($minutes > $calculations['instant_after_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['instant_after_percentage']) / 100));
            }
        } elseif ($order_details['ride_type'] == "later" && ($order_details['mode'] == "city" || $order_details['mode'] == "outstation")) {
            $minutes = (strtotime($order_details['ride_time']) - $datetime) / 60;
            $calculations = get_table_row('amount_calculations', array('travel_type' => 'city', 'vehicle_type' => 'car'));
            if ($minutes > $calculations['schedule_before_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['schedule_before_percentage']) / 100));
            } elseif ($minutes < $calculations['schedule_lessthan_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['schedule_lessthan_percentage']) / 100));
            }
        }
        $data['cancellation_charges'] = $cancel_amount;
        //echo $cancel_amount;exit;
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while cancelling the Order!');
        } else {
            $message = "Your ride with Booking ID: " . $booking_id . " has been cancelled Successfully!";
            //echo $message;
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'cancel_user');

            $user_details = get_table_row('users', array('id' => $user_id));
            SendSMS($user_details['mobile'], $message);

            $order_details = get_table_row('orders', array('id' => $order_id));

            $driver_message = "Your ride with Booking ID: " . $booking_id . " has been cancelled by $user_name!";
            //echo $message;
            $this->ws_model->send_push_notification($driver_message, 'rider', $rider_id, 'user_cancel_ride', $order_id, $order_details['total_amount'], $order_details['rider_id'], $order_details['vehicle_id'], $order_details['ride_id'], $order_details['mode'], $order_details['ride_type']);

            $rider_details = get_table_row('users', array('id' => $rider_id));
            SendSMS($rider_details['mobile'], $driver_message);

            $response = array('status' => true, 'message' => 'Booking cancelled Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rider Cancel Order
     */

    function rider_cancel_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('order_id' => "Order ID", 'booking_id' => "Booking ID", 'user_id' => "User ID", 'rider_name' => "Rider Name");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'status' => 'cancelled by rider',
            'modified_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while cancelling the Booking!');
        } else {
            $message = "Your Booking with Booking ID: " . $booking_id . " has been cancelled by $rider_name!";
            //echo $message;
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'cancel_user');

            $user_details = get_table_row('users', array('id' => $user_id));
            SendSMS($user_details['mobile'], $message);

            $response = array('status' => true, 'message' => 'Booking cancelled Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Complete Trip
     */

    function complete_trip_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('ride_id' => "Ride ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'purpose' => 'refresh', 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table_row('rides', array('id' => $ride_id, 'status' => 'completed'), '', 'id', 'desc', 1);
        if (!empty($response)) {
            $response = array('status' => true, 'message' => 'Trip Completed Successfully!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $data = array(
            'status' => 'completed',
            'ride_end_time' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('rides', $data, array('id' => $ride_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while completing the Trip!');
        } else {
            $response = array('status' => true, 'message' => 'Trip Completed Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Payment Status
     */

    function update_payment_status_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
            JWT::decode($token, 'secret_server_key');
            $token_status = "Success";
        } catch (Exception $e) {
            $token_status = $e->getmessage();
        }
        // if($token_status != "Success")
        // {
        // 	$response = array('status' => false, 'message' => 'Token Miss Match!');
        // 	TrackResponse($user_input, $response);
        // 	$this->response($response);
        // }
        $required_params = array('order_id' => "Order ID", 'user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'payment_status' => 'paid',
            //'cancellation_charges_status' => 'paid',
            'modified_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem occured while updating status!');
        } else {
            $cancellation_charges = get_table_row('orders', array('cancellation_charges_status' => 'unpaid', 'status' => 'cancelled by user', 'user_id' => $user_id), '*', 'id', 'desc');
            update_table('orders', array('cancellation_charges_status' => 'paid'), array('id' => $cancellation_charges['id']));
            $order_details = get_table_row('orders', array('id' => $order_id), array('rider_id, mode, ride_type, booking_id'), 'id', 'desc');
            $message = "Payment done Successfully!";

            $rider_details = get_table_row('users', array('id' => $order_details['rider_id']));
            $user_details = get_table_row('users', array('id' => $user_id));
            $ride_name = $user_details['first_name'] . " " . $user_details['last_name'];
            $booking_id = $order_details['booking_id'];
            $mobile = $rider_details['mobile'];

            $rider_message = "Dear Rider, $ride_name has made a payment for Booking ID: $booking_id. Thank You.";

            if ($cancellation_charges['rider_id']) {
                $this->ws_model->send_push_notification($rider_message, 'rider', $cancellation_charges['rider_id'], 'payment_done', '', 0, 0);

                $this->ws_model->send_push_notification($message, 'user', $cancellation_charges['user_id'], 'payment_done', '', 0, 0);
            }

            //print_r($order_details);
            //if($order_details['rider_id'] && $order_details['mode'] == "city" && $order_details['ride_type'] == "now")
            if ($order_details['rider_id']) {
                $this->ws_model->send_push_notification($rider_message, 'rider', $order_details['rider_id'], 'payment_done', '', 0, 0);
                //echo $message;exit;
                SendSMS($mobile, $rider_message);
            }
            //if($order_details['user_id'] && $order_details['mode'] == "city" && $order_details['ride_type'] == "now")
            if ($order_details['user_id']) {
                $this->ws_model->send_push_notification($message, 'user', $order_details['user_id'], 'payment_done', '', 0, 0);
            }
            $response = array('status' => true, 'message' => 'Payment done Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Check for Ongoing Ride
     */

    function check_for_ongoing_ride_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $user_deleted = $this->ws_model->check_user_deleted_using_userid($user_id);
        if (empty((array) $user_deleted)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'Your account is deleted by Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $user_status = $this->ws_model->check_user_status_using_userid($user_id);
        if (empty((array) $user_status)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'Your account has been put on hold. Please contact Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $user_token = $this->ws_model->check_for_user_token($user_id, $ios_token, $android_token);
        if (empty((array) $user_token)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'You are logged in a another device. Please Login again!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $order_details = $this->ws_model->check_for_ongoing_ride_of_rider($user_id);
        //echo $this->db->last_query();
        //code1 = take to enter otp screen
        //code2 = take to trips screen
        if (!empty($order_details)) {
            $response = array('status' => false, 'pupose' => '', 'message' => 'You cannot post a ride when ride is already progressing!', 'response' => $order_details, 'post_type' => 'ride');
        } else {
            $order_details = $this->ws_model->check_for_ongoing_ride($user_id);
            if (!empty($order_details)) {
                $response = array('status' => false, 'pupose' => '', 'message' => 'You cannot post a ride when ride is already progressing!', 'response' => $order_details, 'post_type' => 'order');
            } else {
                $response = array('status' => true, 'pupose' => '', 'message' => 'Ride can be created!');
            }
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Start Ride
     */

    function start_ride_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('order_id' => "Order ID", 'ride_id' => "Ride ID", 'user_id' => "User ID", 'booking_id' => "Booking ID", 'amount' => "Amount", 'rider_id' => "Rider ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'status' => 'started',
            'ride_start_time' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while Starting the Ride!');
        } else {
            update_table('rides', array('status' => 'ongoing'), array('id' => $ride_id));
            $order_details = get_table_row('orders', array('id' => $order_id));
            $message = "Your ride with Booking ID: " . $booking_id . " has started. Enjoy your ride!";
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'start_ride', $order_id, $amount, $rider_id, $order_details['vehicle_id'], $order_details['rider_id'], $order_details['mode'], $order_details['ride_type']);
            $response = array('status' => true, 'message' => 'Ride started Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Complete Ride
     */

    function complete_ride_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('order_id' => "Order ID", 'user_id' => "User ID", 'booking_id' => "Booking ID", 'amount' => "Amount", 'rider_id' => "Rider ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'status' => 'completed',
            'ride_end_time' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while Completing the Ride!');
        } else {
            $order_details = get_table_row('orders', array('id' => $order_id));
            $message = "Your Booking with Booking ID: " . $booking_id . " has been completed successfully!";
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'complete_ride', $order_id, $amount, $rider_id, $order_details['vehicle_id'], $order_details['ride_id'], $order_details['mode'], $order_details['ride_type']);
            $response = array('status' => true, 'message' => 'Ride completed Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Give Ratings
     */

    function give_ratings_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID", 'ratings' => "Ratings");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'from_user_id' => $from_user_id,
            'user_id' => $user_id,
            'ratings' => $ratings,
            'reviews' => $reviews,
            'created_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = insert_table('user_ratings', $data);
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Unable to submit Ratings!');
        } else {
            $response = array('status' => true, 'message' => 'Rating submitted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Submit Rider Lat Lng
     */

    function submit_rider_lat_lng_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('rider_id' => "Rider ID", 'lat' => "Latitude", 'lng' => "Longitude");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'rider_id' => $rider_id,
            'lat' => $lat,
            'lng' => $lng,
            'created_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = insert_table('rider_current_location', $data);
        if ($unique_id == 0) {
            $response = array('status' => false, 'message' => 'Data posting failed!');
        } else {
            $response = array('status' => true, 'message' => 'Data submitted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rider Lat Lng
     */

    function rider_lat_lng_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('rider_id' => "Rider ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table_row('rider_current_location', array('rider_id' => $rider_id), '', 'id', 'desc', 1);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Order Tracking
     */

    function order_tracking_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('rider_id' => "Rider ID", 'order_id' => "Order ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table_row('rider_current_location', array('rider_id' => $rider_id), '', 'id', 'desc', 1);
        $order_response = get_table_row('orders', array('id' => $order_id), '', 'id', 'desc', 1);
        $distance = get_distance_time($response['lat'], $order_response['from_lat'], $response['lng'], $order_response['from_lng']);
        $final_data = array(
            'order_id' => $order_id,
            'rider_id' => $rider_id,
            'rider_lat' => $response['lat'],
            'rider_lng' => $response['lng'],
            'from_lat' => $order_response['from_lat'],
            'from_lng' => $order_response['from_lng'],
            'distance' => $distance['distance'],
            'time' => $distance['time']
        );
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $final_data);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rider accepted order of user
     */

    function users_accepted_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->users_accepted_order($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Users payment pending order
     */

    function users_payment_pending_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->users_payment_pending_order($user_id);
        $cancellation_charges = get_table_row('orders', array('cancellation_charges_status' => 'unpaid', 'status' => 'cancelled by user', 'user_id' => $user_id), '', 'id', 'desc');
        //echo $this->db->last_query();
        //print_r($cancellation_charges);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!', 'cancellation_charges' => $cancellation_charges['cancellation_charges']);
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response, 'cancellation_charges' => $cancellation_charges['cancellation_charges']);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Users last completed order
     */

    function users_last_completed_order_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->users_last_completed_order($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Users cancelled orders
     */

    function user_cancelled_orders_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->user_cancelled_orders($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rides Offered
     */

    function rides_offered_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->rides_offered($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rides Offered Later
     */

    function rides_offered_later_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->rides_offered_later($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rides Requested
     */

    function rides_requested_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->rides_requested($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Rides Requested
     */

    function rides_requested_later_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->rides_requested_later($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Order Details
     */

    function order_details_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('order_id' => "Order ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $rider_rides = array();
        $response = $this->ws_model->order_details($order_id);
        if ($ride_id) {
            $rider_rides = $this->ws_model->rider_users_list($ride_id);
            if ($rider_rides == NULL) {
                $rider_rides = array();
            }
        }
        if (empty((array) $response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response, 'rider_rides' => $rider_rides);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Ride Details
     */

    function ride_details_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('ride_id' => "Ride ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //var_dump($data);
        $response = $this->ws_model->ride_details($ride_id);
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No Data Found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Payment History
     */

    function payment_history_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $payment_history_credit = $this->ws_model->payment_history_credit($user_id);
        $payment_history_debit = $this->ws_model->payment_history_debit($user_id);
        if (empty($payment_history_credit) && empty($payment_history_debit)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'payment_history_credit' => $payment_history_credit, 'payment_history_debit' => $payment_history_debit);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Countries
     */

    function countries_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $response = get_table('countries', '', '*', 'name', 'asc');
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	States
     */

    function states_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('country_id' => "Country ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table('states', array('country_id' => $country_id), '*', 'name', 'asc');
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Cities
     */

    function cities_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        try {
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
        $required_params = array('state_id' => "State ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = get_table('cities', array('state_id' => $state_id), '*', 'name', 'asc');
        //echo $this->db->last_query();
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'Data not found!');
        } else {
            $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Ratings
     */

    function ratings_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);

        try {
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->ws_model->ratings($user_id);
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Submit Support
     */

    function submit_support_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'order_id' => "Order ID", 'message' => "Message");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $data = array(
            'user_id' => $user_id,
            'order_id' => $order_id,
            'message' => $message,
            'created_on' => date('Y-m-d H:i:s')
        );
        $insert_id = insert_table('support', $data);
        //echo $this->db->last_query();exit;
        if ($insert_id <= 0) {
            $response = array('status' => false, 'message' => 'Support not submitted!');
        } else {
            $response = array('status' => true, 'message' => 'Message for support submitted Successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Send Invoice
     */

    function send_invoice_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        try {
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
        $required_params = array('user_id' => "User ID", 'order_id' => "Order ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $details = $this->ws_model->order_details($order_id);
        $data['details'] = $details;
        $message = $this->load->view('website/invoice-test', $data, true);
        SendEmail(array($details['email_id']), 'CIAO Rides - Invoice(' . $details['booking_id'] . ')', $message, "", "");
        //echo $this->db->last_query();exit;
        $response = array('status' => true, 'message' => 'Invoice sent Successfully!');
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     *  Get Taxi rider details
     */

    function get_taxi_rider_details_post() {
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
        }*/
//$response = get_table_row('rider_current_location', array('rider_id' => $rider_id), '', 'id', 'desc', 1);
$result =$this->db->query("select `id`, `userid`, `first_name`, `last_name`, `mobile`, `lattitude`, `longitude`, `alternate_number`, `email_id`, `dob`, `gender`, `profile_pic` from users where userid='".$rider_id."' ")->row_array();

        if (empty($result)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $result);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
   
    }

    public function rider_current_lat_longs_post(){
         $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $result = get_table_row('rider_current_location', array('rider_id' => $rider_id), '', 'id', 'desc', 1);


        if (empty($result)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $result);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
  

    }

}

?>