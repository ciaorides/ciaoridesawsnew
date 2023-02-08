<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Taxi_flow extends REST_Controller {

    protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
       // error_reporting(0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('app/taxiflow_model');
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
     *  Register User Using Mobile Number
     */

    function register_user_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
//
//        print_r( $this->client_request);
//        exit;
        $required_params = array('mobile' => 'Mobile');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        // $user_details = check_user_email_id_exists($email_id);
        // if(!empty($user_details))
        // {
        //  $response = array('status' => false, 'message' => 'This Email ID is already registered!');
        //  TrackResponse($user_input, $response);
        //  $this->response($response);
        // }
        $user_details = get_table_row('users', array('mobile' => $mobile, 'delete_status' => 1),array('id'));
        
    if (!empty($user_details)) {
        $otp = mt_rand(1000, 9999);
        //$message = "Dear User, $otp is One time password (OTP) for CIAO Rides. Thank You.";
        $message = 'Dear Customer ' . $otp . ' is your One Time Password for CIAO Rides. Thank You.';
        SendSMS($mobile, $message);
        $response = array('status' => true, 'message' => 'Registered user','otp'=>$otp,'response'=>$user_details);
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    if ($otp_confirmed == "No") {
        $otp = mt_rand(1000, 9999);
        //$message = "Dear User, $otp is One time password (OTP) for CIAO Rides. Thank You.";
       $message = 'Dear Customer ' . $otp . ' is your One Time Password for CIAO Rides. Thank You.';
        SendSMS($mobile, $message);
        $response = array('status' => true, 'message' => 'Otp sent successfully!', 'otp'=>$otp,'response' => new stdClass());
        TrackResponse($user_input, $response);
        $this->response($response);
        exit;
    }
        //'lattitude' => $lat,'longitude' => $long,
        $data = array(
            'mobile' => $mobile,
            'lattitude' => $lat,
            'longitude' => $long,
            'mobile_verified' => 'yes',
            'user_type' => 'taxi',
            'status' => 1,
            'created_on' => date('Y-m-d H:i:s')
        );
        $user_id = insert_table('users', $data);

        $dy_user_id=str_pad($user_id, 6, '0', STR_PAD_LEFT);
        //echo $dy_user_id;exit;
        $new_dy_user_id='CIAO-'.$dy_user_id;
        //echo $new_dy_user_id;exit;
        $update_user=array('userid'=>$new_dy_user_id);
        $this->db->update('users',$update_user,array('id'=>$user_id));

        $users = get_table_row('users', array('id' => $user_id));
        if (empty($users)) {
            $response = array('status' => false, 'message' => 'User Registration Failed!', 'response' => new stdClass());
        } else {
            /*$message = "Dear User, Thanks for registering with CIAO Rides. Thank You.";
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
            */
            $response = array('status' => true, 'message' => 'User Registration Successful!', 'response' => $users);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    public function resend_otp_post(){

        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('mobile' => 'Mobile');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $otp = mt_rand(1000, 9999);
        //$message = "Dear User, $otp is One time password (OTP) for CIAO Rides. Thank You.";
        $message = 'Dear Customer ' . $otp . ' is your One Time Password for CIAO Rides. Thank You.';
        SendSMS($mobile, $message);
        $response = array('status' => true, 'message' => 'Otp sent successfully!', 'otp'=>$otp,'response' => new stdClass());
        TrackResponse($user_input, $response);
        $this->response($response);

    } 

    public function banners_post(){

        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

         $required_params = array('type' => 'Type');
        
         // $type=$_GET['type'];
        
//        print_r($type);
//        exit;
        
        
//        foreach ($required_params as $key => $value) {
//            if (!$user_input[$key]) {
//                $response = array('status' => false, 'message' => $value . ' is required');
//                TrackResponse($user_input, $response);
//                $this->response($response);
//            }
//        }

    $top_banners = $this->taxiflow_model->banners_sorting($type,'top');
    $middle_banners = $this->taxiflow_model->banners_sorting($type,'middle');
    $bottom_banners =$this->taxiflow_model->banners_sorting($type,'bottom');

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
      //     TrackResponse($user_input, $response);
        //   $this->response($response);
    //    print_r(json_encode($response));
        
        //	$ret_val ['responsecode'] = 1;
		//	$ret_val ['result_arr'] = $response;
		//	$ret_val ['responsemsg'] = "success";
            $this->response($response,200);
        
        

    } 


    public function add_location_taxi_post(){

        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => "User ID", 'type' => "Type", 'lat' => "Latitude", 'lng' => "Longitude", 'address' => "Address",'mode'=> "Mode");
//echo '<pre>';print_r($required_params);exit;
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $ins_array=array(
                            'user_id'=>$user_id,
                            'type'=>$type,
                            'lat' => $lat,
                            'lng' => $lng,
                            'address' => $address,
                            'mode'=> $mode,
                            'created_on' => date('Y-m-d H:i:s')
                        );

       // echo '<pre>';print_r($ins_array);exit;
        $this->db->insert('favourite_locations',$ins_array);
        $insert_id=$this->db->insert_id();
        if ($insert_id <= 0) {
            $response = array('status' => false, 'message' => 'Data Adding Failed !');
        } else {
            $response = array('status' => true, 'message' => 'Data Added to successfully!');
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }

  public function get_locations_taxi_post(){

        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('user_id' => "User ID",'mode'=>"Mode",'from_lat'=>'From lat','from_lng'=>'From lng');

        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

       
       
        $recent=$this->taxiflow_model->get_locations_taxi($user_id,$mode,'recent');
        $favorite=$this->taxiflow_model->get_locations_taxi($user_id,$mode,'favorite');
        $schedule=$this->taxiflow_model->get_locations_taxi($user_id,$mode,'schedule');

        /*start near by location*/

        $current_time=date('Y:m:d H:i:s');
        $endTime = strtotime($current_time);
        $startTime = date("Y:m:d H:i:s", strtotime('-10 minutes', $endTime));
       //echo $endTime; echo '<br>';
        //echo $startTime ;exit;
        
        $query="select DISTINCT(ul.user_id),ul.vehicle_id,uv.vehicle_type,ul.lat,ul.lng from user_vehicle_lat_lngs ul inner join user_vehicles uv on uv.user_id=ul.user_id and uv.id=ul.vehicle_id where ul.created_on BETWEEN '$startTime' AND '$current_time' ";
        //echo $query;exit;
        $result=$this->db->query($query)->result_array();
        $new_result=array();
        foreach($result as $key=>$val){
            $to_lat=$val['lat'];$to_lng=$val['lng'];
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

            $dis=number_format((float)$km, 3, '.', '');

            $result[$key]['distance']=$dis;
            $radius=3;
            if($result[$key]['distance'] <= $radius){
                $new_result[]=$result[$key];
            }
        }
        /*stop near by locations*/

        $res_ary=array(
                       'recent'=>$recent,
                       'favorite'=>$favorite,
                       'schedule'=>$schedule,
                       'near_by_riders'=>$new_result
                      );
        
        if (!empty($res_ary)) {
            $response = array('status' => true, 'message' => 'Data Fetched successfully!','response'=>$res_ary);
        } else {
           $response = array('status' => false, 'message' => 'Data Not Found !','response'=>array());
        }

        TrackResponse($user_input, $response);
        $this->response($response);

    }

    /*
     *  Get Distance
     */

    function get_distance_post() {
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
       // $calculations = get_table_row('amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => $vehicle_type));
        //print_r($calculations);
        

        $fare_per_km = 0; $main_result=array();
        if ($travel_type == "city") {
            
       
        $subTypes= get_table('taxi_amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => 'car'));
        $subType_calculations=$this->taxi_amount_calculations($km,$subTypes);

        $auto=$this->taxi_typeWise_amount_calculations($km,'city','auto');
        $bike=$this->taxi_typeWise_amount_calculations($km,'city','bike');
        if($km <= 30){
        $main_result=array(
                           'car'=>$subType_calculations,
                           'auto'=>$auto,
                           'bike'=>$bike
                          );
            }else{
        $main_result=array();    
            }
   
        } elseif ($travel_type == "outstation") {
            /*if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 25) {
                $fare_per_km = $calculations['2to25'];
            } elseif ($km > 25) {
                $fare_per_km = $calculations['>25'];
            }*/
         $subTypes= get_table('taxi_amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => 'car'));
         $subType_calculations=$this->taxi_amount_calculations($km,$subTypes);
         //$auto=$this->taxi_typeWise_amount_calculations($km,'outstation','auto');
         //$bike=$this->taxi_typeWise_amount_calculations($km,'outstation','bike');
         $main_result=array(
                           'car'=>$subType_calculations,
                          // 'auto'=>$auto,
                          // 'bike'=>$bike
                          );
        }
        

        if(empty($main_result)){
            
             if ($travel_type == "city") {
                 
                   $response = array('status' => false, 'message' => "Sorry, we don't serve this
location yet.!", 'distance' => number_format($km, 2),  'response' => NULL);
                 
                 
             }else{
                 
                   $response = array('status' => true, 'message' => 'Distance Calculated Successfully!', 'distance' => number_format($km, 2),  'response' => NULL);
                 
             }

      
        }else{

          $response = array('status' => true, 'message' => 'Distance Calculated Successfully!', 'distance' => number_format($km, 2),  'response' => $main_result);  
        }

        

        $this->response($response);
    }

    public function taxi_typeWise_amount_calculations($km,$travel_type,$vehicle_type){

      $calculations = get_table_row('taxi_amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => $vehicle_type));

      if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 10) {
                $fare_per_km = $calculations['2to10'];
            } elseif ($km > 10 && $km <= 30) {
                $fare_per_km = $calculations['11to30'];
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

            $finalCalculations=array(
                  'vehicle_type'=>$vehicle_type,
                  'sub_vehicle_type'=>$calculations['sub_vehicle_type'],
                  'max_seat_capacity'=>$calculations['seat_capacity'],
                  'distance' => $km,
                  'amount' => round($amount), 
                  'tax' => round($tax), 
                  'payment_gateway_commision' => round($payment_gateway_commision), 
                  'ciao_commission' => round($ciao_commission), 
                  'total_amount' => round($total_amount), 
                  //'cancellation_charges' => (int) $cancellation_charges['cancellation_charges'],
                  'cancellation_charges'=>0,
                  'amount_per_head' => round($amount_per_head), 
                  'base_fare' => (int) $calculations['base_fare'], 
                  'per_seat_amount' => round($per_seat_amount)
                 );
            return $finalCalculations;


    }
    public function taxi_amount_calculations($km,$subTypes){
        //echo '<pre>';print_r($subTypes);exit;

        $subCalculations=array();
        $seats_required=1;
        $amount=0;$tax=0;$fare_per_km=0;
        foreach($subTypes as $key=>$calculations){

            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 10) {
                $fare_per_km = $calculations['2to10'];
            } elseif ($km > 10 && $km <= 30) {
                $fare_per_km = $calculations['11to30'];
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

            $subCalculations[]=array(
                  'travel_type'=>'car',
                  'sub_vehicle_type'=>$calculations['sub_vehicle_type'],
                  'max_seat_capacity'=>$calculations['seat_capacity'],
                  'distance' => $km,
                  'amount' => round($amount), 
                  'tax' => round($tax), 
                  'payment_gateway_commision' => round($payment_gateway_commision), 
                  'ciao_commission' => round($ciao_commission), 
                  'total_amount' => round($total_amount), 
                  //'cancellation_charges' => (int) $cancellation_charges['cancellation_charges'],
                  'cancellation_charges'=>0,
                  'amount_per_head' => round($amount_per_head), 
                  'base_fare' => (int) $calculations['base_fare'], 
                  'per_seat_amount' => round($per_seat_amount)
                 );
        
        }
        //echo '<pre>';print_r($subCalculations);exit;
        return $subCalculations;

    }

   function book_now_post(){

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
        $required_params = array('user_id' => "User ID", 'from_lat' => "From Latitude", 'from_lng' => "From Longitude", 'from_address' => "From Address", 'to_lat' => "To Latitude", 'to_lng' => "To Longitude", 'to_address' => "To Address", 'mode' => "Mode", 'vehicle_type' => "Vehicle Type", 'gender' => "Gender", 'seats_required' => "Seats Required", 'ride_type' => "Ride Type");

        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        delete_record('taxi_orders', array('user_id' => $user_id, 'status' => 'pending', 'ride_type' => 'now'));
        $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
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



        if($sub_vehicle_type != ''){

          $calculations = get_table_row('taxi_amount_calculations', array('travel_type' => $mode, 'vehicle_type' => $vehicle_type,'sub_vehicle_type'=>$sub_vehicle_type));
        }else{
           $calculations = get_table_row('taxi_amount_calculations', array('travel_type' => $mode, 'vehicle_type' => $vehicle_type));
           $sub_vehicle_type=$vehicle_type;
        }
       //echo '<pre>';print_r($calculations);exit;
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

        $amount = (($fare_per_km * $km) + $calculations['base_fare']);
        $amount_according_to_seats = (($fare_per_km * $km) + $calculations['base_fare']) * $seats_required;
        $amount_per_head = (($fare_per_km * $km));

        $ciao_commission = ($calculations['ciao_commission'] / 100) * $amount_according_to_seats;

        //$tax = ($calculations['service_tax'] / 100) * $amount_according_to_seats;
        $tax = ($calculations['service_tax'] / 100) * $ciao_commission;
        $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount_according_to_seats;

        $total_amount = $amount_according_to_seats + $tax + $payment_gateway_commision + $ciao_commission;


        $booking_id = mt_rand(10000000, 99999999);

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
            'sub_vehicle_type'=>$sub_vehicle_type,
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
       // echo '<pre>';print_r($data);exit;
        $order_id = insert_table('taxi_orders', $data);
        //echo $this->db->last_query();exit;
        //echo '<pre>';print_r($order_id);exit;
        
        if ($order_id <= 0) {
            $response = array('status' => false, 'message' => 'Ride not placed!');
        } else {
            if ($ride_type == "now" && $mode == "city") {
                $order_details =$this->taxiflow_model->find_near_by_taxi_riders($data, $order_id, $ride_type, $mode, $filter);
                $wait_count = 10;
                SearchAgain:
               $this->taxiflow_model->check_taxi_order_status($order_id, $user_id);
            } elseif ($ride_type == "later" && $mode == "city") {
                $order_details = $this->taxiflow_model->find_near_by_taxi_riders($data, $order_id, $ride_type, $mode, $filter);
            } elseif ($ride_type == "now" && $mode == "outstation") {
                $order_details = $this->taxiflow_model->find_near_by_taxi_riders($data, $order_id, $ride_type, $mode, $filter);
            } elseif ($ride_type == "later" && $mode == "outstation") {
                $order_details = $this->taxiflow_model->find_near_by_taxi_riders($data, $order_id, $ride_type, $mode, $filter);
            }

            if (empty($order_details)) {
                if ($wait_count > 0) {
                    sleep(5);
                    $wait_count--;
                    goto SearchAgain;
                }
                $order_details=$this->book_now_response_temp();
               // $response = array('status' => false, 'message' => 'No riders found!', 'response' => array());
                $distance_time['time'] = "31 mins";

            // echo '<pre>';print_r($order_details);exit;
            $otp = mt_rand(1000, 9999);
            $mobile=$order_details[0]['mobile'];
            $message = 'Dear Customer ' . $otp . ' is your One Time Password for CIAO Rides. Thank You.';
            //SendSMS($mobile, $message);

                $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $order_details, 'time' => $distance_time['time'],'otp'=>$otp);
                //echo '<pre>';print_r($this->book_now_response());exit;
            } else {
                $distance_time = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
                $minutes = explode(" ", $distance_time['time']);
                //var_dump($minutes);
                if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
                    $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
                } else {
                    $seconds = $minutes[0] * 60;
                }

            $order_details=$this->book_now_response_temp();
            $distance_time['time'] = "31 mins";

            //echo '<pre>';print_r($order_details);exit;
            $otp = mt_rand(1000, 9999);
            $mobile=$order_details[0]['mobile'];
            $message = 'Dear Customer ' . $otp . ' is your One Time Password for CIAO Rides. Thank You.';
            //SendSMS($mobile, $message);

            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $order_details, 'time' => $distance_time['time'],'otp'=>$otp);
           
          
            }
        }
        
        TrackResponse($user_input, $response);
       $this->response($response);
    }
   
   public function book_now_response_temp(){
    /*$response='{"id":"2118","first_name":"satish","last_name":"kuravi","mobile":8093668622","alternate_number":"","email_id":"asatish4112@gmail.com","dob":"1992-04-04","gender":"men","bio":null,"profile_pic":null,"user_id":"2058","rider_gender":"male","status":"ongoing","country":"india","number_plate":"567999","car_type":"luxury car","color":"black","year":"2015","vehicle_picture":"storage\/5d958d2aaa650.jpeg","vehicle_type":"car","title":"A4","vehicle_make":"Audi","vehicle_model":"A4","trip_id":"480222","mode":"city","seats_available":"2","seats":"","note":"","trip_distance":"9.7","amount_per_head":"61","ride_type":"now","ride_time":"2022-08-07 11:00:00","middle_seat_empty":"yes","message":"","driver_status":"Started","seats_booked":"2","time_diff":"1810","r_ratings":null,"order_id":44,"from_lat":"17.386673","from_lng":"78.3810183","from_address":"Shiva Teja Nilayam H.No.6-2-656,Secretariat Hills,Dr YSR Enclave, Secretariat Employees Colony,Neknampur Village,Manikonda (PO),Gandipet Mandal, Neknampur, Ibrahim Bagh, Hyderabad, Telangana 500089, India","to_lat":"17.4347287","to_lng":"78.3867669","to_address":"Survey No. 64, Mind Space, Madhapur, Hyderabad, Telangana 500081, India","vehicle_id":"607","profile_percentage":10,"amount":41,"base_fare":"20","tax":2.1,"payment_gateway_commision":2.43,"ciao_commission":12.2,"total_amount":138.83}';*/

    $response=array(
                 "id"=>"2118",
                 "first_name"=>"satish",
                 "last_name"=>"kuravi",
                  "mobile"=>"7093668623",
                 "alternate_number"=>null,
                 "email_id"=>"asatish4112@gmail.com",
                 "dob"=>"1992-04-04",
                 "gender"=>"men",
                 "bio"=>null,
                "profile_pic"=>null,
                 "user_id"=>"2058",
                 "rider_gender"=>"male",
                 "status"=>"ongoing",
                 "country"=>"india",
                 "number_plate"=>"567999",
                  "car_type"=>"luxury car",
                 "color"=>"black",
                 "year"=>"2015",
                "vehicle_picture"=>"storage/5d958d2aaa650.jpeg",
                 "vehicle_type"=>"car",
                 "title"=>"A4",
                 "vehicle_make"=>"Audi",
                 "vehicle_model"=>"A4",
                 "trip_id"=>"480222",
                 "mode"=>"city",
                 "seats_available"=>"2",
                 "seats"=>null,
                 "note"=>null,
                 "trip_distance"=>"9.7",
                 "amount_per_head"=>"61",
                 "ride_type"=>"now",
                 "ride_time"=>"2022-08-07 11:00:00",
                 "middle_seat_empty"=>"yes",
                 "message"=>null,
                 "driver_status"=>"Started",
                 "seats_booked"=>"2",
                 "time_diff"=>"1810",
                 "r_ratings"=>null,
                 "order_id"=>"44",
                 "from_lat"=>"17.386673",
                 "from_lng"=>"78.3810183",
                 "from_address"=>"Shiva Teja Nilayam H.No.6-2-656,Secretariat Hills,Dr YSR Enclave, Secretariat Employees Colony,Neknampur Village,Manikonda (PO),Gandipet Mandal, Neknampur, Ibrahim Bagh, Hyderabad, Telangana 500089, India",
                 "to_lat"=>"17.4347287",
                 "to_lng"=>"78.3867669",
                 "to_address"=>"Survey No. 64, Mind Space, Madhapur, Hyderabad, Telangana 500081, India",
                 "vehicle_id"=>"607",
                 "profile_percentage"=>10,
                 "amount"=>41,
                 "base_fare"=>"20",
                 "tax"=> 23,
                 "payment_gateway_commision"=>35,
                 "ciao_commission"=>120,
                 "total_amount"=>138,
                 "ratting"=>4.5,
                 "driver_image"=>base_url('assets/images/driver.png'),
    );
   //echo '<pre>';print_r($response);exit;
    $new_array=array();
    array_push($new_array,$response);
    return $new_array;
   }
    /*
     *  Offer A Ride
     */

    function offer_a_ride_post() {
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
        //echo '<pre>';print_r($distance);exit;
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
        //echo '<pre>';print_r($data);exit;
        $ride_id = insert_table('rides', $data);
        //echo $this->db->last_query();exit;
        $poly_lat_lngs = all_waypoints_fromLatLng_toLatLng($from_lat, $from_lng, $to_lat, $to_lng);
        //echo '<pre>';print_r($poly_lat_lngs);exit;
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
     *  Get Vehicle Brands
     */

    function get_vehicle_brands_post() {
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
        $required_params = array('vehicle_type' => "Vehicle Type");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

       $vehicle_brands = get_table('vehicle_makes', array('vehicle_type' => $vehicle_type));

       if (empty($vehicle_brands)) {
            $response = array('status' => false, 'message' => 'Data not posted!');
        } else {
           
            $response = array('status' => true, 'message' => 'Data Fetched Successful!', 'response' => $vehicle_brands);
        }
        TrackResponse($user_input, $response);
        $this->response($response);


    }
    

    /*
     *  Get Vehicle Models
     */

    function get_vehicle_models_post() {
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
        $required_params = array('brand_id' => "Brand ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

       $vehicle_models = get_table('vehicle_models', array('make_id' => $brand_id));

       if (empty($vehicle_models)) {
            $response = array('status' => false, 'message' => 'Data not posted!');
        } else {
           
            $response = array('status' => true, 'message' => 'Data Fetched Successful!', 'response' => $vehicle_models);
        }
        TrackResponse($user_input, $response);
        $this->response($response);


    }
    
    function add_sharing_user_vehicles_step1_post() {
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

     function add_sharing_user_vehicles_step2Old14102022_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('vehicle_id' => 'Vehicle ID',  'vehicle_registration_image' => 'Vehicle registration image', 'vehicle_registration_number' => 'Vehicle registration number');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

         
                 
    
    
        if ($vehicle_registration_image) {
            $image_data = array(
                'image' => $vehicle_registration_image,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $vehicle_registration_img_path = $image_result['result'];
            if (file_exists($vehicle_registration_img_path)) {
                $base_names = basename($vehicle_registration_img_path);
                $imagePath = $vehicle_registration_img_path;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //  resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }

        if ($vehicle_insurance_image) {
            $image_data = array(
                'image' => $vehicle_insurance_image,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $vehicle_insurance_img_path = $image_result['result'];
            if (file_exists($vehicle_insurance_img_path)) {
                $base_names = basename($vehicle_insurance_img_path);
                $imagePath = $vehicle_insurance_img_path;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //  resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }

        if ($fitness_certification_image) {
            $image_data = array(
                'image' => $fitness_certification_image,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $fitness_certification_img_path = $image_result['result'];
            if (file_exists($fitness_certification_img_path)) {
                $base_names = basename($fitness_certification_img_path);
                $imagePath = $fitness_certification_img_path;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //  resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }

        if ($vehicle_permit_image) {
            $image_data = array(
                'image' => $vehicle_permit_image,
                'upload_path' => './storage/profile_pics/',
                'file_path' => 'storage/profile_pics/'
            );
            $image_result = upload_image($image_data);
            $vehicle_permit_img_path = $image_result['result'];
            if (file_exists($vehicle_permit_img_path)) {
                $base_names = basename($vehicle_permit_img_path);
                $imagePath = $vehicle_permit_img_path;
                $destPath = 'storage/profile_pics/' . $base_names;
                $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                if ($sizes_in_kb > 50) {
                    //  resizeImage($imagePath,$destPath,300,300,100);
                }
            }
        }

        $update_data=array(
            'number_plate'=>$vehicle_registration_number,
            'fitness_certification_number'=>$fitness_certification_number,
            'vehicle_insurance_number'=>$vehicle_insurance_number,
            'vehicle_permit_number'=>$vehicle_permit_number,
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

    function add_sharing_user_vehicles_step3Old141022_post() {
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
            $vehicle_image=$vehicle_img['image'];
            if ($vehicle_image) {
                $image_data = array(
                    'image' => $vehicle_image,
                    'upload_path' => './storage/profile_pics/',
                    'file_path' => 'storage/profile_pics/'
                );
                $image_result = upload_image($image_data);
                $vehicle_image_img_path = $image_result['result'];
                if (file_exists($vehicle_image_img_path)) {
                    $base_names = basename($vehicle_image_img_path);
                    $imagePath = $vehicle_image_img_path;
                    $destPath = 'storage/profile_pics/' . $base_names;
                    $sizes_in_kb = round(filesize($imagePath) / 1024, 2);
                    if ($sizes_in_kb > 50) {
                        //  resizeImage($imagePath,$destPath,300,300,100);
                    }
                }
            }

            $insert=array(
                'user_id'=>$user_id,
                'vehicle_id'=>$vehicle_id,
                'vehicle_image'=>$vehicle_image_img_path,
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

    
    
    
     function add_sharing_user_vehicles_step2_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('vehicle_id' => 'Vehicle ID',  'vehicle_registration_image' => 'Vehicle registration image', 'vehicle_registration_number' => 'Vehicle registration number');
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
            'number_plate'=>$vehicle_registration_number,
            'fitness_certification_number'=>$fitness_certification_number,
            'vehicle_insurance_number'=>$vehicle_insurance_number,
            'vehicle_permit_number'=>$vehicle_permit_number,
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
    
    
    

    function add_sharing_user_vehicles_step3_post() {
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

    public function get_nearby_rider_lat_longs_post(){

        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('from_lat' => 'From Lat','from_lng' => 'From Lng',  'user_id' => 'User ID','radius'=> 'Radius');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $current_time=date('Y:m:d H:i:s');
        $endTime = strtotime($current_time);
        $startTime = date("Y:m:d H:i:s", strtotime('-10 minutes', $endTime));
       //echo $endTime; echo '<br>';
        //echo $startTime ;exit;
        
        $query="select DISTINCT(ul.user_id),ul.vehicle_id,uv.vehicle_type,ul.lat,ul.lng from user_vehicle_lat_lngs ul inner join user_vehicles uv on uv.user_id=ul.user_id and uv.id=ul.vehicle_id where ul.created_on BETWEEN '$startTime' AND '$current_time' ";
        //echo $query;exit;
        $result=$this->db->query($query)->result_array();
        $new_result=array();
        foreach($result as $key=>$val){
            $to_lat=$val['lat'];$to_lng=$val['lng'];
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

            $dis=number_format((float)$km, 3, '.', '');

            $result[$key]['distance']=$dis;

            if($result[$key]['distance'] <= $radius){
                $new_result[]=$result[$key];
            }
        }

        if (empty($new_result)) {
            $response = array('status' => false, 'message' => 'No Vehicles Found!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle details Fetched Successfully!','response'=>$new_result);
        }
        TrackResponse($user_input, $response);
        $this->response($response);

    }
    
    
      /*
     * 	Update Profile 08-10-2022
     */

    function update_profile08102022_post() {
        
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
        $update_user = $this->taxiflow_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'User Updation Failed!');
        } else {
            $user_details = $this->taxiflow_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'User Updation Successful!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     * 	Update Profile Picture Old 08-10-2022
     */

    function update_profile_picture08102022_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

//        try {
//            JWT::decode($token, 'secret_server_key');
//            $token_status = "Success";
//        } catch (Exception $e) {
//            $token_status = $e->getmessage();
//        }
//        
//        if ($token_status != "Success") {
//            $response = array('status' => false, 'message' => 'Token Miss Match!');
//            TrackResponse($user_input, $response);
//            $this->response($response);
//        }

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
        $update_user = $this->taxiflow_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Profile Picture Updation Failed!');
        } else {
            $user_details = $this->taxiflow_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Profile Picture Updated Successfully!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    
    // Get User Details View 1-09-2022
    
    function user_details_post(){
        
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
        
        
        $user_details = $this->taxiflow_model->user_details($user_id);
        if($user_details['profile_pic'] == ''){
            $defalut_img="https://ciaorides.com/testingnew/ciaorides/assets/images/no_image.png";
            $user_details['profile_pic']= $defalut_img;
        }
       
        if(empty($user_details)) {
             
            $response = array('status' => false, 'message' => 'No Details!');
        }else {
           
            $response = array('status' => true, 'message' => 'User Details!', 'response' => $user_details);
        }
        
         TrackResponse($user_input, $response);
        $this->response($response);
        
        
    }
        


    // Update Profile 


    function update_profile_post() {
        
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

//        try {
//            JWT::decode($token, 'secret_server_key');
//            $token_status = "Success";
//        } catch (Exception $e) {
//            $token_status = $e->getmessage();
//        }
//        if ($token_status != "Success") {
//            $response = array('status' => false, 'message' => 'Token Miss Match!');
//            TrackResponse($user_input, $response);
//            $this->response($response);
//        }
        
        
        $required_params = array('user_id' => "User ID", 'first_name' => "First Name", 'last_name' => "Last Name", 'dob' => "Date of birth", 'gender' => "Gender");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
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
            'driver_license_id' => $driver_license_id,
            'government_id' => $government_id,
            'pan_card_id' => $pan_card_id,
            'aadhar_card_id' => $aadhar_card_id,
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
            $data['profile_pic'] = '/storage/profile_pics/'.$profile_pic;
        }
        
    
            /*
            
        upload_type - 1 -driving licence 2- adhar card 3- pan card 4- Profile Pic 5- Government 6- VehicleRegistration 7 - VehicleInsurance 8- FitnessCertificates 9- VehiclePermit 10- VehicleImages
            
            */
            
        if($driver_license!=''){
            
                            
           $RecordData['status']='0';
                
        //   $this->db->update('user_documents', $RecordData, array('user_id' => $user_id,'document_type'=>'1'));
         $this->db->delete('user_documents', array('user_id' => $user_id,'document_type'=>'1'));
        
   //      $driver_license = explode(',', $driver_license);
            
            
//         foreach($driver_license as $driver_license_img){
//                 print_r($driver_license_img);
//            }
            
//            
//            
         foreach($driver_license as $driver_license_img){

           $insert=array(
               
                'user_id'=>$user_id,
                'path'=>'/storage/DrivingLicence/',
                'date'=>date('Y-m-d'),
                'document_type'=>'1',
                'status'=>'1',
                'created_by'=>'1',
                'created_on'=>date('Y-m-d H:i:s'),
                'updated_by'=>'1',
                'file'=>'/storage/DrivingLicence/'.$driver_license_img,
               
                        );
                
            $this->db->insert('user_documents',$insert);      
       }   
            
            
           $data['driver_license_id'] = $driver_license_id;
            
        }
        
 
        // Government
        if($government_file!=''){
            
                            
           $RecordData['status']='0';
                
           $this->db->delete('user_documents', array('user_id' => $user_id,'document_type'=>'5'));
                
       //    $government_file = explode(',', $government_file);
            
            
           foreach($government_file as $government_id_img){

           $insert=array(
               
                'user_id'=>$user_id,
                'path'=>'/storage/Government/',
                'date'=>date('Y-m-d'),
                'document_type'=>'5',
                'status'=>'1',
                'created_by'=>'1',
                'created_on'=>date('Y-m-d H:i:s'),
                'updated_by'=>'1',
                'file'=>'/storage/Government/'.$government_id_img,
               
                        );
                 
               $this->db->insert('user_documents',$insert);      
             }    
           $data['government_id'] = $government_id;
            
        }
    
         
        // PanCard
        if($pan_card!=''){
            
                            
           $RecordData['status']='0';
                
          // $this->db->update('user_documents', $RecordData, array('user_id' => $user_id,'document_type'=>'3'));
            
        $this->db->delete('user_documents', array('user_id' => $user_id,'document_type'=>'3'));

            
            
       //    $pan_card = explode(',', $pan_card);
            
           
           foreach($pan_card as $pan_card_img){

           $insert=array(
               
                'user_id'=>$user_id,
                'path'=>'/storage/PanCard/',
                'date'=>date('Y-m-d'),
                'document_type'=>'3',
                'status'=>'1',
                'created_by'=>'1',
                'created_on'=>date('Y-m-d H:i:s'),
                'updated_by'=>'1',
                'file'=>'/storage/PanCard/'.$pan_card_img,
               
             );
                 
               $this->db->insert('user_documents',$insert);      
             }    
          $data['pan_card_id'] = $pan_card_id;
            
        }
        
         // Adhar 
        if($aadhar_card!=''){
            
                            
           $RecordData['status']='0';
                
         //  $this->db->update('user_documents', $RecordData, array('user_id' => $user_id,'document_type'=>'2'));
           
         $this->db->delete('user_documents', array('user_id' => $user_id,'document_type'=>'2'));

        //   $aadhar_card = explode(',', $aadhar_card);

           foreach($aadhar_card as $aadhar_card_img){

           $insert=array(
               
                'user_id'=>$user_id,
                'path'=>'/storage/AdharCard/',
                'date'=>date('Y-m-d'),
                'document_type'=>'2',
                'status'=>'1',
                'created_by'=>'1',
                'created_on'=>date('Y-m-d H:i:s'),
                'updated_by'=>'1',
                'file'=>'/storage/AdharCard/'.$aadhar_card_img,
               
             );
                 
               $this->db->insert('user_documents',$insert);      
             }    
            
            $data['aadhar_card_id'] = $aadhar_card_id;
            $data['aadhar_card_verified'] = "no";
            
        }
        
        
        
        
        /*
        
        if ($driver_license_front) {
            $data['driver_license_front'] = '/storage/DrivingLicence/'.$driver_license_front;
            $data['driver_license_id'] = $driver_license_id;
        }
        if ($driver_license_back) {
            $data['driver_license_back'] = '/storage/DrivingLicence/'.$driver_license_back;
        }
        if ($government_id_front) {
            $data['government_id_front'] = '/storage/Government/'.$government_id_front;
            $data['government_id'] = $government_id;
        }
        if ($government_id_back) {
            $data['government_id_back'] = '/storage/Government/'.$government_id_back;
        }
        if ($pan_card_front) {
            $data['pan_card_front'] = '/storage/PanCard/'.$pan_card_front;
            $data['pan_card_id'] = $pan_card_id;
        }
        if ($pan_card_back) {
            $data['pan_card_back'] = '/storage/PanCard/'.$pan_card_back;
        }
        if ($aadhar_card_front) {
            $data['aadhar_card_front'] = '/storage/AdharCard/'.$aadhar_card_front;
            $data['aadhar_card_id'] = $aadhar_card_id;
            $data['aadhar_card_verified'] = "no";
        }
        if ($aadhar_card_back) {
            $data['aadhar_card_back'] = '/storage/AdharCard/'.$aadhar_card_back;
        }
        
        */
        
        
        
        
        //var_dump($data);
        
    
        $update_user = $this->taxiflow_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'User Updation Failed!');
        } else {
            $user_details = $this->taxiflow_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'User Updation Successful00!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    /*
     * 	Update Profile Picture New Mohammad
     */

    function update_profile_picture_post() {
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

        $required_params = array('user_id' => "User ID", 'profile_pic' => 'Profile Picture');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        if ($profile_pic) {
            $data['profile_pic'] = '/storage/profile_pics/'.$profile_pic;
        }
        //var_dump($data);
        $update_user = $this->taxiflow_model->update_user($data, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_user === FALSE) {
            $response = array('status' => false, 'message' => 'Profile Picture Updation Failed!');
        } else {
            $user_details = $this->taxiflow_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Profile Picture Updated Successfully!', 'response' => $user_details);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }


        // Upload API 
    
    
        
    public function uploadOldSIngleImage_post()

	{
	    
	    
   // upload_type - 1 -driving licence 2- adhar card 3- pan card 4- Profile Pic 5- Government 6- VehicleRegistration 7 - VehicleInsurance 8- FitnessCertificates 9- VehiclePermit 10- VehicleImages
	    
	    
   $upload_type=$this->input->post('upload_type');
        
      if($upload_type!=0 && $upload_type<11){
      
      if($upload_type==1){
          
          $config['upload_path']   =  './storage/DrivingLicence/';
          
      }else if($upload_type==2){
          
           $config['upload_path']   =  './storage/AdharCard/';
          
      }else if($upload_type==3){
          
           $config['upload_path']   =  './storage/PanCard/';
          
      }else if($upload_type==4){
          
           $config['upload_path']   =  './storage/profile_pics/';
      }
      else if($upload_type==5){
          
           $config['upload_path']   =  './storage/Government/';
          
      }else if($upload_type==6){
          
           $config['upload_path']   =  './storage/VehicleRegistration/';
          
      }else if($upload_type==7){
          
           $config['upload_path']   =  './storage/VehicleInsurance/';
          
      }else if($upload_type==8){
          
           $config['upload_path']   =  './storage/FitnessCertificates/';
          
      }else if($upload_type==9){
          
           $config['upload_path']   =  './storage/VehiclePermit/';
          
      }else if($upload_type==10){
          
           $config['upload_path']   =  './storage/VehicleImages/';
          
      }
        
    
		$this->load->library('upload');

        //$config['allowed_types'] =  'jpg|jpeg|PNG|png|pdf|doc|docx|xlsx|csv';

        $config['allowed_types'] =  '*';

        $config['file_name'] = date("Ymd_His") . '-' . $_FILES['image']['name'];
         
        $this->upload->initialize($config);

        if ( $this->upload->do_upload('image')){

			$data = array('upload_data' => $this->upload->data());

			$ret_val ['responsecode'] = 1;

			$ret_val ['result_arr']=$data;

			$ret_val ['responsemsg'] = "Success";

			$this->response($ret_val,200);

		}else{

			$ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = $this->upload->display_errors();

			$this->response($ret_val, 400);

		}
		
      }else{
          
          
          		$ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = 'User Type Not Valid!';

			$this->response($ret_val, 400);
			
			
      }
        
        
        

	}
    
    
    // testing 
    
    
    

    function imageloop_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

  

        //echo '<pre>';print_r($vehicle_images);exit;
        foreach($vehicle_images as $vehicle_img){
          

           print_r($vehicle_img);
        
        }

        if ($insert_id == 0) {
            $response = array('status' => false, 'message' => 'Vehicle images posted  Failed!','response'=>'');
        } else {
            $response = array('status' => true, 'message' => 'Vehicle images posted Successfully!','response'=>true);
        }
        TrackResponse($user_input, $response);
        $this->response($response);


    }
    
    
        
        
    public function upload_post()

	{
	    
	    
   // upload_type - 1 -driving licence 2- adhar card 3- pan card 4- Profile Pic 5- Government 6- VehicleRegistration 7 - VehicleInsurance 8- FitnessCertificates 9- VehiclePermit 10- VehicleImages
	    
	    
   $upload_type=$this->input->post('upload_type');
        
      if($upload_type!=0 && $upload_type<12){
      
      if($upload_type==1){
          
          $config['upload_path']   =  './storage/DrivingLicence/';
          
      }else if($upload_type==2){
          
           $config['upload_path']   =  './storage/AdharCard/';
          
      }else if($upload_type==3){
          
           $config['upload_path']   =  './storage/PanCard/';
          
      }else if($upload_type==4){
          
           $config['upload_path']   =  './storage/profile_pics/';
      }
      else if($upload_type==5){
          
           $config['upload_path']   =  './storage/Government/';
          
      }else if($upload_type==6){
          
           $config['upload_path']   =  './storage/VehicleRegistration/';
          
      }else if($upload_type==7){
          
           $config['upload_path']   =  './storage/VehicleInsurance/';
          
      }else if($upload_type==8){
          
           $config['upload_path']   =  './storage/FitnessCertificates/';
          
      }else if($upload_type==9){
          
           $config['upload_path']   =  './storage/VehiclePermit/';
          
      }else if($upload_type==10){
          
           $config['upload_path']   =  './storage/VehicleImages/';
          
      }else if($upload_type==11){
          
           $config['upload_path']   =  './storage/testing/';
          
      }
        
    
		$this->load->library('upload');


        $config['allowed_types'] =  '*';

       
       $data = [];
   
         
      $count = count($_FILES['image']['name']);
     if($count>0){
      // print_r($count);
          
      for($i=0;$i<$count;$i++){
    
        if(!empty($_FILES['image']['name'][$i])){
            
            
          $config['file_name'] = date("Ymd_His").$_FILES['image']['name'][$i];
            
          $_FILES['file']['name'] = $_FILES['image']['name'][$i];
          $_FILES['file']['type'] = $_FILES['image']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['image']['error'][$i];
          $_FILES['file']['size'] = $_FILES['image']['size'][$i];
  
    
    
        $this->upload->initialize($config);

          $config['allowed_types'] = 'gif|jpg|png|jpeg';
         $this->load->library('upload',$config);
         if($this->upload->do_upload('file'))
        {
           $filedata = $this->upload->data();
           $this->resize_image($filedata['full_path']);
             
             $data['totalFiles'][] =$filedata;
        }else{
             
              print_r($this->upload->display_errors());
           //  echo 'ki';
         }
            
            
            
            
        }
   
      }
          
          
	        $ret_val ['responsecode'] = 1;

			$ret_val ['result_arr']=$data;

			$ret_val ['responsemsg'] = "Success";

			$this->response($ret_val,200);
      }else{
              
              $ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = 'Please Selete At Least One Image!';

			$this->response($ret_val, 400);
              
          }
      }else{
          
          
          		$ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = 'User Type Not Valid!';

			$this->response($ret_val, 400);
			
			
      }
        
        
        

	}
    
    
    
        function loopexplode_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

  

      //  echo '<pre>';print_r($vehicle_images);exit;
            
            $vehicle_images = explode(',', $vehicle_images);
           foreach($vehicle_images as $vehicle_img){
          

           print_r($vehicle_img);
        
        }

     
            $response = array('status' => true, 'message' => 'Vehicle images posted Successfully!','response'=>true);
        
        TrackResponse($user_input, $response);
        $this->response($response);


    }


    /*
     *  Cancel ride API details
     */

    function cancel_taxi_order_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        
        $required_params = array('user_id' => "User ID", 'booking_id' => 'Booking Id');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        
        //var_dump($data);
        $update_order = $this->taxiflow_model->cancel_taxi_order($booking_id, $user_id);
        //echo $this->db->last_query();exit;
        if ($update_order === FALSE) {
            $response = array('status' => false, 'message' => 'Cancel Order Failed!');
        } else {
            $user_details = $this->taxiflow_model->user_details($user_id);
            $response = array('status' => true, 'message' => 'Order Cancelled Successfully!', 'response' => true);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    

    
    
    public function submitd_post()
    {
        
        $files = $_FILES;
        $cpt = count($_FILES ['image'] ['name']);
        print_r($cpt);
        
    
        for ($i = 0; $i < $cpt; $i ++) {
            
          $files = $_FILES;
        
            echo '<pre>';
            print_r($_FILES);
        
            $name = time().$files ['image'] ['name'] [$i];
            $_FILES ['multipleUpload'] ['name'] = $name;
            $_FILES ['multipleUpload'] ['type'] = $files ['image'] ['type'] [$i];
            $_FILES ['multipleUpload'] ['tmp_name'] = $files ['image'] ['tmp_name'] [$i];
            $_FILES ['multipleUpload'] ['error'] = $files ['image'] ['error'] [$i];
            $_FILES ['multipleUpload'] ['size'] = $files ['image'] ['size'] [$i];
        
        
         $config['upload_path'] = FCPATH.'/storage/Resize';
         $config['allowed_types'] = 'gif|jpg|png|jpeg';
         $this->load->library('upload',$config);
         if($this->upload->do_upload('multipleUpload'))
        {
           $filedata = $this->upload->data();
           $this->resize_image($filedata['full_path']);
             
             print_r($filedata);
        }else{
             
              print_r($this->upload->display_errors());
           //  echo 'ki';
         }
            
        }
        
        echo 'Hi';
        
    }
    
    function resize_image($file_path) {
  
        $CI =& get_instance();
    // Set your config up
    $config['image_library']    = "gd2";      
    $config['source_image']     = $file_path;      
    $config['create_thumb']     = TRUE;      
    $config['maintain_ratio']   = TRUE;     
    $config['new_image'] = $file_path; 
    $config['width'] = "750";      
    $config['height'] = "750";  
    $config['thumb_marker']=FALSE;
    $CI->load->library('image_lib');

    $CI->image_lib->initialize($config);
    // Do your manipulation

    if(!$CI->image_lib->resize())
    {
      return $CI->image_lib->display_errors();  
    } 
        
    $CI->image_lib->clear(); 

}
    
    
    
        
    public function uploadfile_post()

	{
	    
	    
   // upload_type - 1 -driving licence 2- adhar card 3- pan card 4- Profile Pic 5- Government 6- VehicleRegistration 7 - VehicleInsurance 8- FitnessCertificates 9- VehiclePermit 10- VehicleImages
	    
	    
   $upload_type=$this->input->post('upload_type');
        
      if($upload_type!=0 && $upload_type<12){
      
      if($upload_type==1){
          
          $config['upload_path']   =  './storage/DrivingLicence/';
          
      }else if($upload_type==2){
          
           $config['upload_path']   =  './storage/AdharCard/';
          
      }else if($upload_type==3){
          
           $config['upload_path']   =  './storage/PanCard/';
          
      }else if($upload_type==4){
          
           $config['upload_path']   =  './storage/profile_pics/';
      }
      else if($upload_type==5){
          
           $config['upload_path']   =  './storage/Government/';
          
      }else if($upload_type==6){
          
           $config['upload_path']   =  './storage/VehicleRegistration/';
          
      }else if($upload_type==7){
          
           $config['upload_path']   =  './storage/VehicleInsurance/';
          
      }else if($upload_type==8){
          
           $config['upload_path']   =  './storage/FitnessCertificates/';
          
      }else if($upload_type==9){
          
           $config['upload_path']   =  './storage/VehiclePermit/';
          
      }else if($upload_type==10){
          
           $config['upload_path']   =  './storage/VehicleImages/';
          
      }else if($upload_type==11){
          
           $config['upload_path']   =  './storage/testing/';
          
      }
        
    
		$this->load->library('upload');


        $config['allowed_types'] =  '*';

       
       $data = [];
   
       $imagedata=array();
         
      $count = count($_FILES['image']['name']);
     if($count>0){
      // print_r($count);
          
     // for($i=0;$i<$count;$i++){
    
        if(!empty($_FILES['image']['name'])){
            
            
          $config['file_name'] = date("Ymd_His").$_FILES['image']['name'];
            
          $_FILES['file']['name'] = $_FILES['image']['name'];
          $_FILES['file']['type'] = $_FILES['image']['type'];
          $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'];
          $_FILES['file']['error'] = $_FILES['image']['error'];
          $_FILES['file']['size'] = $_FILES['image']['size'];
  
    
    
        $this->upload->initialize($config);

          $config['allowed_types'] = 'gif|jpg|png|jpeg';
         $this->load->library('upload',$config);
         if($this->upload->do_upload('file'))
        {
           $filedata = $this->upload->data();
           $this->resize_image($filedata['full_path']);
             
             $imagedata =$filedata;
        }else{
             
             $imagedata =$this->upload->display_errors();
           //  echo 'ki';
         }
            
            
            
            
        }
   
     // }
          
          
	        $ret_val ['responsecode'] = 1;

			$ret_val ['result_arr']=array($imagedata);

			$ret_val ['responsemsg'] = "Success";

			$this->response($ret_val,200);
      }else{
              
              $ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = 'Please Selete At Least One Image!';

			$this->response($ret_val, 400);
              
          }
      }else{
          
          
          		$ret_val ['responsecode'] = 2;

			$ret_val ['responsemsg'] = 'User Type Not Valid!';

			$this->response($ret_val, 400);
			
			
      }
        
        
        

	}

    /*
     *  Payment Start API details
     */

    function payment_start_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        
        $required_params = array('user_id' => "User ID", 'trip_id' => 'Trip Id');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $query="select * from rides where user_id='$user_id' and trip_id='$trip_id' and driver_status='Started' ";

        $ride_data = $this->db->query($query)->row_array();
        //echo $this->db->last_query();exit;
        $new_response=array();
        if (empty($ride_data)) {

            $response = array('status' => false, 'message' => 'Data fetching Failed!','response'=>$new_response);
        } else {
            $new_response['payment_id']=$trip_id;
            $persons=$ride_data['seats'];
            $amount=$ride_data['amount_per_head'];
            $final_amount=$persons*$amount;
            $new_response['final_amount']=round($final_amount,2);

            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $new_response);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     *  Payment Submit API details
     */

    function payment_submit_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        
        $required_params = array('user_id' => "User ID", 'trip_id' => 'Trip Id');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        
        $up_data=array( 
                        'status'=>'completed',
                        'modified_on'=>date('Y-m-d H:i:s')
                      );
        $result = $this->db->update('rides',$up_data,array('user_id'=>$user_id,'trip_id'=>$trip_id));
        //echo $this->db->last_query();exit;
        //$new_response=array();
        if (empty($result)) {

            $response = array('status' => false, 'message' => 'Data updatation Failed!','response'=>$$result);
        } else {
            

            $response = array('status' => true, 'message' => 'Data update Successfully!', 'response' => $result);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    
    /*
     *  Verify OTP API details
     */

    function verify_otp_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        
        $required_params = array('user_id' => "User ID", 'booking_id' => 'Booking Id','otp'=>'OTP');

        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        
        $query="select * from taxi_orders where user_id='$user_id' and booking_id='$booking_id' and otp='$otp' ";

        $result = $this->db->query($query)->row_array();
        //echo $this->db->last_query();exit;
        //$new_response=array();
        if (empty($result)) {

            $response = array('status' => false, 'message' => 'Invalid  OTP..!','response'=>false);
        } else {
            

            $response = array('status' => true, 'message' => 'OTP validate Successfully!', 'response' => ture);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }


    /*
     *  Control Locations API details
     */

    function controll_locations_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        
        $required_params = array('lat' => "Latitude", 'lng' => 'Longitude','zipcode'=>'Zip code');

        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $address_string= getAddress($lat,$lng);
        //echo '<pre>';print_r($res);exit;
        $query="select * from control_locations";

        $result = $this->db->query($query)->result_array();
        $new_res=array();
        foreach($result as $res){
            if(strpos($address_string, $res['location']) !== false){
                //echo $res['location'];
                $new_res=$res;
            } else{
                //echo "Word Not Found!";
            }
        }
        //echo '<pre>';print_r($new_res);exit;
        
        //$new_response=array();
        if (empty($new_res)) {

        $response = array('status' => false, 'message' => 'Data not found..!','response'=>false);
        } else {
            
        $response = array('status' => true, 'message' => 'Data Fetched Successfully!', 'response' => $new_res);
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

}