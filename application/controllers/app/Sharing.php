<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . 'libraries/RESTful/REST_Controller.php';

class Sharing extends REST_Controller {


	protected $client_request = NULL;

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        error_reporting(0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $this->load->helper('app/ws_helper');
        $this->load->model('app/ws_model');
        $this->load->model('app/sharing_model');
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
     *  get distance API
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

        if($amount !=0){

            $response = array('status' => true, 'message' => 'Distance Calculated Successfully!', 'response' => $km, 'amount' => round($amount), 'tax' => round($tax), 'payment_gateway_commision' => round($payment_gateway_commision), 'ciao_commission' => round($ciao_commission), 'total_amount' => round($total_amount), 'cancellation_charges' => (int) $cancellation_charges['cancellation_charges'], 'amount_per_head' => round($amount_per_head), 'base_fare' => (int) $calculations['base_fare'], 'per_seat_amount' => round($per_seat_amount));
        }else{
        $response = array('status' => false, 'message' => 'Failed to Data Calculated!', 'response' => $km);
        }


        $this->response($response);
    }

    /*
     *  Check Availability API
     */

    function check_availability_post() {
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
        delete_record('orders', array('user_id' => $user_id, 'status' => 'pending', 'ride_type' => 'later'));
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

//        echo $this->db->last_query();exit;
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
          $order_details2=$this->db->query("select response from sample_responses where id=1 ")->row_array();
          //echo '<pre>';print_r($order_details2);exit;
             
            
            // dummy 
            $distance_time = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
            $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'],true)), 'time' => $distance_time['time']);
            
            // dummy end 

            //New 
//            if (empty($order_details)) {
//                if ($wait_count > 0) {
//                    sleep(5);
//                    $wait_count--;
//                    goto SearchAgain;
//                }
//        $response = array('status' => false, 'message' => 'No riders found!', 'response' => array());
//    //$response = array('status' => true, 'message' => 'Data fetched Successfully2!', 'response' => $order_details2['response'], 'time' => $distance_time['time']);
//                
//            } else {
//                $distance_time = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
//                $minutes = explode(" ", $distance_time['time']);
//                //var_dump($minutes);
//                if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
//                    $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
//                } else {
//                    $seconds = $minutes[0] * 60;
//                }
//                $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $order_details, 'time' => $distance_time['time']);
//            }
            
            // end new 
        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }


    
    /*
     *  Send rider request
     */

    function send_rider_request_post() {
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
     *  Accept Order
     */

    function accept_order_post() {
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
     *  Decline Order
     */

    function decline_order_post() {
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
     *  User Cancel Order
     */

    function user_cancel_order_post() {
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
     *  Rider Cancel Order
     */

    function rider_cancel_order_post() {
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
     *  Complete Trip
     */

    function complete_trip_post() {
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
     *  Start Ride
     */

    function start_ride_post() {
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
     *  Complete Ride
     */

    function complete_ride_post() {
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
     *  Give Ratings
     */

    function give_ratings_post() {
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
     *  Submit Rider Lat Lng
     */

    function submit_rider_lat_lng_post() {
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
     *  Rider Lat Lng
     */

    function rider_lat_lng_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
        $user_input = $this->client_request;
        extract($user_input);
       /* try {
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
     * 	Check for Ongoing Ride
     */

    function check_for_ongoing_ride_post() {
        $response = array('status' => false, 'message' => '');
        $user_input = $this->client_request;
        extract($user_input);
        
        /*
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
        
        */
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $user_deleted = $this->sharing_model->check_user_deleted_using_userid($user_id);
        if (empty((array) $user_deleted)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'Your account is deleted by Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $user_status = $this->sharing_model->check_user_status_using_userid($user_id);
        if (empty((array) $user_status)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'Your account has been put on hold. Please contact Administrator!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $user_token = $this->sharing_model->check_for_user_token($user_id, $ios_token, $android_token);
        if (empty((array) $user_token)) {
            $response = array('status' => false, 'purpose' => 'logout', 'message' => 'You are logged in a another device. Please Login again!');
            TrackResponse($user_input, $response);
            $this->response($response);
        }
        $order_details = $this->sharing_model->check_for_ongoing_ride_of_rider($user_id);
        //echo $this->db->last_query();
        //code1 = take to enter otp screen
        //code2 = take to trips screen
        if (!empty($order_details)) {
            $response = array('status' => false, 'pupose' => '', 'message' => 'You cannot post a ride when ride is already progressing!', 'response' => $order_details, 'post_type' => 'ride');
        } else {
            $order_details = $this->sharing_model->check_for_ongoing_ride($user_id);
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
     * 	Order Tracking
     */

    function order_tracking_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
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
        
        
        
        $order_details2=$this->db->query("select response from sample_responses where id=4 ")->row_array();

        
        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
           // $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $final_data);
            
            
              $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));

            
            
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->sharing_model->user_cancelled_orders($user_id);
        
        
       $order_details2=$this->db->query("select response from sample_responses where id=5 ")->row_array();


        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
            // $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
            
                $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));

            
            
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
        
        
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->sharing_model->rides_offered($user_id);
        
      $order_details2=$this->db->query("select response from sample_responses where id=6 ")->row_array();

        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
          //  $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
            
             $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));
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

        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        $response = $this->sharing_model->rides_offered_later($user_id);
        
        
              $order_details2=$this->db->query("select response from sample_responses where id=6 ")->row_array();


        
        
        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
           // $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
            
             $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));
            
            
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
      //  $response = $this->sharing_model->rides_requested($user_id);
        
      $order_details2=$this->db->query("select response from sample_responses where id=2 ")->row_array();
   
    //    echo '<pre>'; print_r($order_details2['response']); exit;
        
        
        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
        //    $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
      $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));

            
            
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
    //    $response = $this->sharing_model->rides_requested_later($user_id);
        
           $order_details2=$this->db->query("select response from sample_responses where id=3 ")->row_array();
   

        
        
        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
           // $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
            
              $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => array(json_decode($order_details2['response'])));

        }
        TrackResponse($user_input, $response);
        $this->response($response);
    }

    /*
     *  User Vehicles Fetch 
     */

    function user_vehicles_post() {
        $response = array('status' => false, 'message' => '', 'response' => array());
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
        $required_params = array('user_id' => "User ID");
        foreach ($required_params as $key => $value) {
            if (!$user_input[$key]) {
                $response = array('status' => false, 'message' => $value . ' is required');
                TrackResponse($user_input, $response);
                $this->response($response);
            }
        }
        
        
        $response=$this->sharing_model->getUserVehicles($user_id);
        
        
        if (empty($response)) {
            $response = array('status' => false, 'message' => 'No data available!');
        } else {
           $response = array('status' => true, 'message' => 'Data fetched Successfully!', 'response' => $response);
        }

        TrackResponse($user_input, $response);
        $this->response($response);
    }
    
    

}