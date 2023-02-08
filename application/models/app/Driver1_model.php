<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Driver1_model extends CI_Model {

      public function register_email_verify(){

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
      }

      public function previous_booking_data($driver_id){

            $query="select trip_distance,total_amount,ride_time from taxi_orders where rider_id='$driver_id' and payment_status='paid' and status='completed' and driver_status='Started' and rider_payment_status='paid' order by ride_time desc";
            //echo $query;exit;
            $res=$this->db->query($query)->row_array();
            if(!empty($res)){
                  $result=array(
                              'trip_distance'=>$res['trip_distance'],
                              'total_amount'=>$res['total_amount'],
                              'ride_time'=>$res['ride_time'],
                              );
            }else{
                  $result=array(
                              'trip_distance'=>0,
                              'total_amount'=>0,
                              'ride_time'=>00.00,
                              );
            }

        return $result;
      }

      public function total_bookings($driver_id){

            $query="select count(id) as booking_count from taxi_orders where rider_id='$driver_id' and payment_status='paid' and status='completed' and driver_status='Started' and rider_payment_status='paid' order by ride_time desc";
            $res=$this->db->query($query)->row_array();
            return $res['booking_count'];
      }

      public function total_earnings($driver_id){
            
             $query="select SUM(total_amount) as final_amount from taxi_orders where rider_id='$driver_id' and payment_status='paid' and status='completed' and driver_status='Started' and rider_payment_status='paid' order by ride_time desc";
            $res=$this->db->query($query)->row_array();
            return $res['final_amount'];
      }

      public function save_rider_current_lat_lngs($driver_id,$from_lat,$from_lng){

            $insert_array=array(
                                    'rider_id'=>$driver_id,
                                    'lat'=>$from_lat,
                                    'lng'=>$from_lng,
                                    'created_on'=>date('Y-m-d H:i:s')
                               );
            $result=$this->db->insert('rider_current_location', $insert_array);
            return $result;
      }

      public function search_rides($driver_id,$travel_type,$vehicle_type,$sub_vehicle_type,$from_lat,$from_lng){

            /* getting rides from taxi_orders status pending and time ridetime between now to 10 min */
            /* apply filter from, to lat lngs with in the 10 km range get user details from taxi_orders */ 

            $to_time=date('Y-m-d H:i:s');
            $from_time = date('Y-m-d H:i:s', strtotime(' -240 minutes'));

            $query="select tr.id as order_id,tr.booking_id,tr.user_id,tr.ride_time,u.first_name,u.last_name,u.mobile,u.average_rating,tr.from_lat,tr.from_lng,tr.from_address,tr.to_lat,tr.to_lng,tr.to_address,tr.driver_status,tr.vehicle_type,tr.sub_vehicle_type,tr.rider_id from taxi_orders tr inner join users u on u.id=tr.user_id where tr.vehicle_type='$vehicle_type' and tr.sub_vehicle_type='$sub_vehicle_type' and tr.status='pending' and tr.driver_status='not_started' and tr.rider_id = 0 and tr.ride_time between '$from_time' and '$to_time' ";

            //echo $query;echo '<pre>';
            $orders=$this->db->query($query)->result_array();

            $new_result=array();
            if(!empty($orders)){
            foreach($orders as $key=>$value){
                  $rider_current_location=$this->db->query("select * from rider_current_location where rider_id='$driver_id' order by id desc")->row_array();
//echo $this->db->last_query();
                  $from_lat=$value['from_lat'];
                  $from_lng=$value['from_lng'];
                  $to_lat=$rider_current_location['lat'];
                  $to_lng=$rider_current_location['lng'];

                  $traval_to_lat=$value['to_lat'];;
                  $traval_to_lng=$value['to_lng'];;
                  $trip_distance = get_distance_time($from_lat, $to_lat, $from_lng, $to_lng);
                  //echo $trip_distance;exit;
                 // $orders[$key]['distance']=$trip_distance['distance'];
                 // $orders[$key]['time_to_reach']=$trip_distance['time'];

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

                  $orders[$key]['distance']=$dis;
                  $orders[$key]['apporx_time_to_reach']=$trip_distance['time'];

                  $travel_distance=$this->get_travel_distance($from_lat, $traval_to_lat, $from_lng, $traval_to_lng);

                  $orders[$key]['travel_distance']=$travel_distance;
                  $orders[$key]['amount_calulation']=$this->amount_calulations($travel_distance,$vehicle_type,$sub_vehicle_type,$travel_type);
                  $radius=10;
                  if($orders[$key]['distance'] <= $radius){
                      $new_result[]=$orders[$key];
                  }


               }
            }
           // echo '<pre>';print_r($new_result);exit;
           return $new_result;

      }

      public function get_travel_distance($from_lat, $traval_to_lat, $from_lng, $traval_to_lng){


                  $trip_distance = get_distance_time($from_lat, $traval_to_lat, $from_lng, $traval_to_lng);
                  //echo $trip_distance;exit;
                 // $orders[$key]['distance']=$trip_distance['distance'];
                 // $orders[$key]['time_to_reach']=$trip_distance['time'];

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

                  return $dis;
      }

      public function amount_calulations($km,$vehicle_type,$sub_vehicle_type,$travel_type){

            $calculations= get_table_row('taxi_amount_calculations', array('travel_type' => $travel_type, 'vehicle_type' => $vehicle_type,'sub_vehicle_slug'=>$sub_vehicle_type));
            //echo $this->db->last_query();exit;
           // echo '<pre>';print_r($calculations);
            $km=round($km);
            //echo '<pre>';print_r($km);
            if ($km > 0 && $km <= 1) {
                $fare_per_km = $calculations['0to1'];
            } elseif ($km > 1 && $km <= 10) {
                $fare_per_km = $calculations['2to10'];
            } elseif ($km > 10 && $km <= 30) {
                $fare_per_km = $calculations['11to30'];
            }
            //echo '<pre>';print_r($fare_per_km);

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

            $subCalculations=array(
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
             // echo '<pre>';print_r($subCalculations);exit;
            $final_amount= round($subCalculations['amount']*$subCalculations['max_seat_capacity']);
            return $final_amount;
      }

      function check_order_started($order_id) {
        $this->db->order_by('taxi_orders.id', 'desc');
        $this->db->where('taxi_orders.status !=', 'pending');
        $this->db->where('taxi_orders.status !=', 'accepted');
        //$this->db->where('orders.status !=', 'cancelled by user');
        //$this->db->where('orders.status !=', 'cancelled by rider');
        $this->db->where('taxi_orders.id', $order_id);
        $this->db->select('taxi_orders.id');
        $orders = $this->db->get('taxi_orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return new ArrayObject();
    }

    function getMyRides($rider_id){

      $query="select u.first_name,u.last_name,ROUND((r.amount_per_head*r.seats),1) as total_amount,r.ride_time,r.from_address,r.to_address from rides r inner join users u on u.id=r.user_id where r.rider_id='$rider_id' ";
      $result=$this->db->query($query)->result_array();
      return $result;
    }



}?>