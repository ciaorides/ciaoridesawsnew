<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Driver1 extends REST_Controller {


	protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        error_reporting(0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('app/driver1_model');
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
     *  Driver Register Using Mobile Number
     */

    function driver_register_post() {
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
        
        // $user_details = check_user_email_id_exists($email_id);
        // if(!empty($user_details))
        // {
        //  $response = array('status' => false, 'message' => 'This Email ID is already registered!');
        //  TrackResponse($user_input, $response);
        //  $this->response($response);
        // }
        $user_details = get_table_row('users', array('mobile' => $mobile,'user_type'=>'driver', 'delete_status' => 1),array('id'));
        
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
            'user_type' => 'driver',
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
            
            //$this->driver1_model->register_email_verify();

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

    /* driver home page data */

     public function home_page_data_post(){


        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('driver_id' => 'Drier Id');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $previous_booking_data=$this->driver1_model->previous_booking_data($driver_id);
        $total_bookings=$this->driver1_model->total_bookings($driver_id);
        $total_earnings=$this->driver1_model->total_earnings($driver_id);

        $result=array(
                        'previous_booking_data'=>$previous_booking_data,
                        'total_bookings'=>$total_bookings,
                        'total_earnings'=>$total_earnings
                     );

        $response = array('status' => true, 'message' => 'Data Fetched successfully!','response' => $result);
        TrackResponse($user_input, $response);
        $this->response($response);
     }


     /* Fetching rides */

     public function fetching_rides_post(){


        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);

        $required_params = array('driver_id' => 'Drier Id','from_lat'=> 'From lat','from_lng'=>'From lng','vehicle_type'=>'vehicle type','sub_vehicle_type'=>'sub vehicle type','travel_type'=> 'Travel type');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $save=$this->driver1_model->save_rider_current_lat_lngs($driver_id,$from_lat,$from_lng);

        $result=$this->driver1_model->search_rides($driver_id,$travel_type,$vehicle_type,$sub_vehicle_type,$from_lat,$from_lng);

        $response = array('status' => true, 'message' => 'Data Fetched successfully!','response' => $result);
        TrackResponse($user_input, $response);
        $this->response($response);

    }

    /*
     *  Accept Ride
     */

    public function accept_ride_post(){

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

        $required_params = array('booking_id'=>'Booking ID','driver_id' => 'Drier Id','vehicle_id'=>'Vehicle ID','user_id'=>'User ID','order_id'=>'Order ID');
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $data = array(
            'rider_id' => $driver_id,
            'vehicle_id' => $vehicle_id,
            'status' => 'accepted',
            'accepted_date' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = update_table('taxi_orders', $data, array('id' => $order_id));


        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while accepting the Ride!');
        } else {
            $rider_details = get_table_row('users', array('id' => $driver_id));
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
     *  Reject Ride
     */

    function reject_ride_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
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

        $required_params = array('user_id' => "User ID", 'order_id' => "Order ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $data = array(
            'rider_id' => 0,
            'vehicle_id' => 0,
            'status' => 'pending',
            'accepted_date' => ''
        );
        //var_dump($data);
        $unique_id = update_table('taxi_orders', $data, array('id' => $order_id));

        $data = array(
            'order_id' => $order_id,
            'user_id' => $user_id,
            'driver_id' => $driver_id,
            'created_on' => date('Y-m-d H:i:s')
        );
        //var_dump($data);
        $unique_id = insert_table('declined_taxi_orders', $data);

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
     *  User Cancel Ride
     */

    function user_cancel_ride_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
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
        $required_params = array('order_id' => "Order ID", 'rider_id' => "Rider ID", 'booking_id' => "Booking ID", 'user_name' => "User Name", 'user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        //$order_started = get_table_row('orders', array('id' => $order_id, 'status !=' => 'pending', 'status !=' => 'accepted', 'status !=' => 'cancelled by user', 'status !=' => 'cancelled by rider'));
        $order_started = $this->driver1_model->check_order_started($order_id);
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
        $order_details = get_table_row('taxi_orders', array('id' => $order_id));
        if ($order_details['ride_type'] == "now" && ($order_details['mode'] == "city" || $order_details['mode'] == "outstation")) {
            //echo "test";
            $minutes = (strtotime($order_details['ride_time']) - $datetime) / 60;
            $calculations = get_table_row('taxi_amount_calculations', array('travel_type' => 'city', 'vehicle_type' => $order_details['vehicle_type']));
            if ($minutes > $calculations['instant_after_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['instant_after_percentage']) / 100));
            }
        } elseif ($order_details['ride_type'] == "later" && ($order_details['mode'] == "city" || $order_details['mode'] == "outstation")) {
            $minutes = (strtotime($order_details['ride_time']) - $datetime) / 60;
            $calculations = get_table_row('taxi_amount_calculations', array('travel_type' => 'city', 'vehicle_type' => 'car'));
            if ($minutes > $calculations['schedule_before_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['schedule_before_percentage']) / 100));
            } elseif ($minutes < $calculations['schedule_lessthan_time']) {
                $cancel_amount = $order_details['amount'] - ($order_details['amount'] * ((100 - $calculations['schedule_lessthan_percentage']) / 100));
            }
        }
        $data['cancellation_charges'] = $cancel_amount;
        //echo $cancel_amount;exit;
        $unique_id = update_table('taxi_orders', $data, array('id' => $order_id));
        //echo $this->db->last_query();
        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while cancelling the Order!');
        } else {
            $message = "Your ride with Booking ID: " . $booking_id . " has been cancelled Successfully!";
            //echo $message;
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'cancel_user');

            $user_details = get_table_row('users', array('id' => $user_id));
            SendSMS($user_details['mobile'], $message);

            $order_details = get_table_row('taxi_orders', array('id' => $order_id));

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


    function pickup_ride_post() {
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

        $required_params = array('order_id' => "Order ID", 'rider_id' => "Rider ID", 'vehicle_id' => "Vehicle ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        $data = array(
            'rider_id'=>$rider_id,
            'vehicle_id'=>$vehicle_id,
            'status'=>'started',
            'driver_status' => 'started',
            'modified_on' => date('Y-m-d H:i:s')
        );

        $update_user = update_table('taxi_orders', $data, array('id' => $order_id));

        $order=get_table_row('taxi_orders',array('id'=>$order_id));

        $trip_id = Trip_ID(); 
       
        $amount_per_head=round(($order['amount']/$order['seats_required']),1);

        $insert_ride=array(
                        
                        'trip_id'=>$trip_id,
                        'type'=>'taxi',
                        'user_id'=>$order['user_id'],
                        'rider_id'=>$rider_id,
                        'vehicle_id'=>$order['vehicle_id'],
                        'from_lat'=>$order['from_lat'],
                        'from_lng'=>$order['from_lng'],
                        'from_address'=>$order['from_address'],
                        'to_lat'=>$order['to_lat'],
                        'to_lng'=>$order['to_lng'],
                        'to_address'=>$order['to_address'],
                        'mode'=>$order['mode'],
                        'vehicle_type'=>$order['vehicle_type'],
                        'gender'=>$order['gender'],
                        'seats'=>$order['seats_required'],
                        'seats_available'=>$seats_available,
                        'trip_distance'=>$order['trip_distance'],
                        'gender'=>$order['gender'],
                        'ride_type'=>'now',
                        'amount_per_head'=>$amount_per_head,
                        'ride_start_time'=>date('Y-m-d H:i:s'),
                        'ride_time'=>date('Y-m-d H:i:s'),
                        'middle_seat_empty'=>$middle_seat_empty,
                        'status'=>'ongoing',
                        'driver_status'=>'Started',
                        'created_on'=>date('Y-m-d H:i:s')
                          );
        $this->db->insert('rides',$insert_ride);
        $ride_id=$this->db->insert_id();

        $up_data=array('ride_id'=>$ride_id);
        $update_rider = update_table('taxi_orders', $up_data, array('id' => $order_id));
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
     *  Complete Ride
     */

    public function complete_ride_post(){

        $response = array('status' => false, 'message' => '', 'response' => array());
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
        $unique_id = update_table('taxi_orders', $data, array('id' => $order_id));

        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while Completing the Ride!');
        } else {
            $order_details = get_table_row('taxi_orders', array('id' => $order_id));
            $message = "Your Booking with Booking ID: " . $booking_id . " has been completed successfully!";
            $this->ws_model->send_push_notification($message, 'user', $user_id, 'complete_ride', $order_id, $amount, $rider_id, $order_details['vehicle_id'], $order_details['ride_id'], $order_details['mode'], $order_details['ride_type']);
            $response = array('status' => true, 'message' => 'Ride completed Successfully!');
        }


        TrackResponse($user_input, $response);
        $this->response($response);

    }


    /*
     *  Submit Review
     */

    public function submit_review_post(){

        $response = array('status' => false, 'message' => '', 'response' => array());
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
        $required_params = array('user_id' => "User ID",'rider_id'=>"Rider id",'order_id'=>"Order id",'rating'=> "Rating");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $insert_rating=array(
                                'order_id'=>$order_id,
                                'from_user_id'=>$user_id,
                                'user_id'=>$rider_id,
                                'ratings'=>$rating,
                                'reviews'=>$review,
                                'created_on'=>date('Y-m-d H:i:s')
                            );
        $last_id=insert_table('user_ratings',$insert_rating);

        $query="select ROUND(AVG(ratings),1) as avg_rating from user_ratings where user_id='$rider_id' GROUP BY user_id";
        $rat=$this->db->query($query)->row_array();

        $update_avg=array('average_rating'=>$rat['avg_rating']);

        $unique_id = update_table('users', $update_avg, array('id' => $rider_id));

        if ($unique_id == false) {
            $response = array('status' => false, 'message' => 'Some Problem found while Submit review!');
        } else {
           
            $response = array('status' => true, 'message' => 'Review submitted Successfully!');
        }


        TrackResponse($user_input, $response);
        $this->response($response);

    }


    /*
     *  My Rides
     */

    public function my_rides_post(){

        $response = array('status' => false, 'message' => '', 'response' => array());
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
        $required_params = array('rider_id' => "Rider ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }

        $result=$this->driver1_model->getMyRides($rider_id);

        if (empty($result)) {
            $response = array('status' => false, 'message' => 'No rides found!','reponse'=> $result);
        } else {
           
            $response = array('status' => true, 'message' => 'Data fetched Successfully!','reponse'=> $result);
        }


        TrackResponse($user_input, $response);
        $this->response($response);

    }


}



?>