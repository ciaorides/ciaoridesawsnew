<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Taxiflow_model extends CI_Model {


function find_near_by_taxi_riders($data, $order_id, $ride_type, $mode, $filter) {
        //echo "test";exit;
        //$where  = "(orders.status != 'accepted' AND orders.status = 'started')";
        $this->db->where('taxi_orders.status', 'pending');
        $this->db->where('taxi_orders.mode', $mode);
        $this->db->where('taxi_orders.id', $order_id);
        $this->db->where('taxi_orders.user_id', $data['user_id']);
        $this->db->where('taxi_orders.rider_id', 0);
        $this->db->select('taxi_orders.*');
        $orders = $this->db->get('taxi_orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            $res = $orders->row_array();
            //print_r($res);
            return $first_scenario = $this->check_within_source_dest($res['from_lat'], $res['from_lng'], $res['to_lat'], $res['to_lng'], $ride_type, $mode, $res['ride_time'], $order_id, $filter, $res['gender'], $res['seats_required'], $res['vehicle_type']);
        }
        return array();
    }

    function get_locations_taxi($user_id,$mode,$type){

        $query="select * from favourite_locations where user_id='".$user_id."' and type='".$type."' and mode='".$mode."' order by id desc limit 0,4 ";
        $result=$this->db->query($query)->result_array();
        return $result;
    }

    function check_taxi_order_status($order_id, $user_id) {
        //echo "test";
        $this->db->where('taxi_orders.id', $order_id);
        $this->db->where('taxi_orders.user_id', $user_id);
        $this->db->where('taxi_orders.rider_id !=', 0);
        $this->db->join('users r', 'r.id = taxi_orders.rider_id');
        $this->db->select('taxi_orders.*, r.first_name as r_first_name, r.last_name as r_last_name, r.mobile as r_mobile, r.profile_pic as r_profile_pic, r.gender as rider_gender');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = taxi_orders.rider_id) as r_ratings");
        $orders = $this->db->get('taxi_orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return array();
    }


     function check_within_source_dest($order_lat, $order_lng, $order_to_lat, $order_to_lng, $ride_type, $mode, $order_ride_time, $order_id, $filter, $gender, $seats_required, $vehicle_type) {
        $now = date('Y-m-d H:i:s');
        $having = "seats_available >= seats_booked";
        if (isset($filter) && $filter == "high") {
            $this->db->order_by('amount_per_head', 'desc');
        } elseif (isset($filter) && $filter == "low") {
            $this->db->order_by('amount_per_head', 'asc');
        } elseif (isset($filter) && $filter == "ratings") {
            $this->db->order_by('r_ratings', 'desc');
        }
        // if($gender == "men")
        // {
        // 	$this->db->where("(rides.gender = 'men' or rides.gender = 'any')");
        // }
        // elseif($gender == "women")
        // {
        // 	$this->db->where("(rides.gender = 'women' or rides.gender = 'any')");
        // }
        // elseif($gender == "any")
        // {
        // 	$this->db->where("(rides.gender = 'men' or rides.gender = 'women' or rides.gender = 'any')");
        // }
        if ($gender == "any") {
            $this->db->where("(rides.gender = 'any')");
        } elseif ($gender == "men") {
            $this->db->where("(rides.gender = 'men'  or rides.gender = 'any')");
        } elseif ($gender == "women") {
            $this->db->where("(rides.gender = 'women' or rides.gender = 'any')");
        }
        if ($vehicle_type) {
            $this->db->where('rides.vehicle_type', $vehicle_type);
        }
        if ($ride_type == "now") {
            //$this->db->having('time_diff <=', 60);
            $this->db->where('ride_type', 'now');
        } else {
            $this->db->where('ride_type', 'later');
        }
        $this->db->having($having);
        $this->db->where('rides.status', 'ongoing');
        $this->db->where('rides.mode', $mode);
        //$this->db->where('rides.vehicle_type', $vehicle_type);
        $this->db->where('seats_available >=', $seats_required);
        if ($ride_type == "now") {
            //$this->db->where('rides.ride_time >', $now);
        } else {
            $this->db->where('rides.ride_time >=', $order_ride_time);
        }
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->join('user_vehicles', 'user_vehicles.id = rides.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->select('users.*, users.gender as rider_gender, user_vehicles.*, vehicle_makes.*, vehicle_makes.title as vehicle_make, vehicle_models.*, vehicle_models.title as vehicle_model, rides.*');
        $this->db->select("(IFNULL((SELECT SUM(seats_required) FROM orders where orders.ride_id = rides.id and ride_type = '$ride_type' and (status = 'pending' or status = 'started' or status = 'accepted')), 0) + $seats_required) as seats_booked");
        $this->db->select("TIMESTAMPDIFF(MINUTE, rides.ride_time, '$now') as time_diff");
        //$this->db->select("(SELECT IFNULL(SUM(seats_required), 0) FROM `orders` where orders.ride_id = rides.id and ride_type = '$ride_type') as capacity_booked");
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = rides.user_id) as r_ratings");
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($rides->num_rows() > 0) {
            $res = $rides->result_array();
           //echo '<pre>';print_r($res);exit;
            if (!empty($res)) {
                $data = array();
                foreach ($res as $key => $row) {
                    $res[$key]['order_id'] = $order_id;
                    $this->db->where('ride_id', $row['id']);
                    $this->db->select('ride_lat_lngs.*');
                    $orders = $this->db->get('ride_lat_lngs');
                    //echo $this->db->last_query();
                    //echo $orders->num_rows();
                    $ress = array();
                    if ($orders->num_rows() > 0) {
                        $ress = $orders->result_array();
                    }

                    $rider_current_lat = "";
                    $rider_current_lng = "";
                    if ($row['ride_time'] <= date('Y-m-d H:i:s')) {
                        $this->db->limit(1);
                        $this->db->order_by('id', 'desc');
                        $this->db->where('rider_id', $row['user_id']);
                        $this->db->select('rider_current_location.*');
                        $rider_current_location = $this->db->get('rider_current_location');
                        //echo $this->db->last_query();
                         /*if($row['id'] == 2117){
                         	echo $this->db->last_query();
                         }*/
                        //echo $rider_current_location->num_rows();
                        if ($rider_current_location->num_rows() > 0) {
                            $rcl = $rider_current_location->row_array();
                           /* if($row['id'] == 2117){
                         	echo '<pre>';print_r($rcl);exit;
                         }*/
                            $rider_current_lat = $rcl['lat'];
                            $rider_current_lng = $rcl['lng'];
                        }
                    } else {
                        $rider_current_lat = $order_lat;
                        $rider_current_lng = $order_lng;
                    }
                    $first = array('lat' => $order_lat, 'lng' => $order_lng);
                    $first_other = array('lat' => $order_to_lat, 'lng' => $order_to_lng);
                    $second = array();
                    if (!empty($ress)) {
                        foreach ($ress as $row1) {
                            $second[] = array(
                                'lat' => $row1['lat'],
                                'lng' => $row1['lng']
                            );
                        }
                    }
                    //print_r($first);
                    //echo $order_lat;
                    if ($mode == "city") {
                        $tolerance = 1000;
                    } else {
                        $tolerance = 12000;
                    }

                    
                    $d1 = get_distance_time($order_lat, $rider_current_lat, $order_lng, $rider_current_lng);
                    $distance1 = explode(" ", $d1['distance']);
                    //var_dump($distance);
                    
                    if ($distance1[1] == "m") {
                        $km1 = $distance1[0] / 1000;
                    } else {
                        $km1 = $distance1[0];
                    }
                    $d2 = get_distance_time($order_lat, $row['to_lat'], $order_lng, $row['to_lng']);
                    $distance2 = explode(" ", $d2['distance']);
                    //var_dump($distance);
                   // echo '<pre>';print_r($distance2);exit;
                    if ($distance2[1] == "m") {
                        $km2 = $distance2[0] / 1000;
                    } else {
                        $km2 = $distance2[0];
                    }

                    /*if($row['id'] == 2117){
                    echo '<pre>';print_r($distance1);
                    echo '<pre>';print_r($distance2);
                    echo '<pre>';print_r($km1);
                    echo '<pre>';print_r($km2);exit;
                		}*/

                    //echo "km1 ".$km1."<br>"."km2 ".$km2."<br>"."km3 ".$km3."<br>"."km4 ".$km4."<br>";exit;
                    if ($km1 <= $km2) {
                        $response = PolyUtil::isLocationOnPath($first, $second, $tolerance);
                        $response_other = PolyUtil::isLocationOnPath($first_other, $second, $tolerance);
                        $first_scenario = preg_replace('/\s+/', '', $response);
                        $second_scenario = preg_replace('/\s+/', '', $response_other);
                        if($row['id'] == 2117){
                        	//echo '<pre>';print_r($response);exit;
                        	$first_scenario = 1;
                        	$second_scenario = 1;
						}
                        
                        if ($first_scenario == true && $second_scenario == true) {
                        	//echo $ride_type;exit;
                            if ($ride_type == "now" && $mode == "city") {
                                //echo "string";exit;
                                //echo $row['user_id'];
                                $this->ws_model->send_push_notification('You have a new booking!', 'driver', $row['user_id'], 'new_order', $order_id);
                            }
                            //echo $ride_type;exit;

                            $calculations = get_table_row('amount_calculations', array('travel_type' => $mode, 'vehicle_type' => $vehicle_type));
						
                            $amount_per_head = $row['amount_per_head'];
                            $amountwith_base_fare = ($row['amount_per_head'] + $calculations['base_fare']) * $seats_required;
                            $amount = ($row['amount_per_head'] * $seats_required);

                            //$ciao_commission = ($calculations['ciao_commission'] / 100) * $amount;
                            $ciao_commission = ($calculations['ciao_commission'] / 100) * $amount;

                            // $tax = ($calculations['service_tax'] / 100) * $amount;
                            // $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount;

                            $tax = ($calculations['service_tax'] / 100) * $ciao_commission;
                            $payment_gateway_commision = ($calculations['payment_gateway_commision'] / 100) * $amount;

                            $total_amount = $amount + $tax + $payment_gateway_commision + $ciao_commission;

                            // $data[$key]['amount'] = $amount;
                            // $data[$key]['base_fare'] = $calculations['base_fare'];
                            // $data[$key]['tax'] = $tax;
                            // $data[$key]['payment_gateway_commision'] = $payment_gateway_commision;
                            // $data[$key]['ciao_commission'] = $ciao_commission;
                            // $data[$key]['total_amount'] = $total_amount;

                            $user_details = $this->ws_model->user_details($row['user_id']);

                            //$data[$key]['profile_percentage'] = $user_details['profile_percentage'];

                            $data[] = array(
                                'id' => $row['id'],
                                'first_name' => $row['first_name'],
                                'last_name' => $row['last_name'],
                                'mobile' => $row['mobile'],
                                'alternate_number' => $row['alternate_number'],
                                'email_id' => $row['email_id'],
                                'dob' => $row['dob'],
                                'gender' => $row['gender'],
                                'bio' => $row['bio'],
                                'profile_pic' => $row['profile_pic'],
                                'user_id' => $row['user_id'],
                                'rider_gender' => $row['rider_gender'],
                                'status' => $row['status'],
                                'country' => $row['country'],
                                'number_plate' => $row['number_plate'],
                                'car_type' => $row['car_type'],
                                'color' => $row['color'],
                                'year' => $row['year'],
                                'vehicle_picture' => $row['vehicle_picture'],
                                'vehicle_type' => $row['vehicle_type'],
                                'title' => $row['title'],
                                'vehicle_make' => $row['vehicle_make'],
                                'vehicle_model' => $row['vehicle_model'],
                                'trip_id' => $row['trip_id'],
                                'mode' => $row['mode'],
                                'seats_available' => $row['seats_available'],
                                'seats' => $row['seats'],
                                'note' => $row['note'],
                                'trip_distance' => $row['trip_distance'],
                                'amount_per_head' => $row['amount_per_head'],
                                'ride_type' => $row['ride_type'],
                                'ride_time' => $row['ride_time'],
                                'middle_seat_empty' => $row['middle_seat_empty'],
                                'message' => $row['message'],
                                'driver_status' => $row['driver_status'],
                                'seats_booked' => $row['seats_booked'],
                                'time_diff' => $row['time_diff'],
                                'r_ratings' => $row['r_ratings'],
                                'order_id' => $order_id,
                                'from_lat' => $row['from_lat'],
                                'from_lng' => $row['from_lng'],
                                'from_address' => $row['from_address'],
                                'to_lat' => $row['to_lat'],
                                'to_lng' => $row['to_lng'],
                                'to_address' => $row['to_address'],
                                'vehicle_id' => $row['vehicle_id'],
                                'profile_percentage' => $user_details['profile_percentage'],
                                'amount' => $amount_per_head - $calculations['base_fare'],
                                'base_fare' => $calculations['base_fare'],
                                'tax' => $tax,
                                'payment_gateway_commision' => $payment_gateway_commision,
                                'ciao_commission' => $ciao_commission,
                                'total_amount' => $total_amount
                            );
                        }
                    }
                }
                //echo '<pre>';print_r($data);exit;
                return $data;
            }
            return array();
        }
    }

   function banners_sorting($type,$sub_type){

    $banners=get_table('banners', array('type' =>$type,'sub_type' =>$sub_type,'status'=>'Active' ));

    foreach($banners as $key=>$banner){
            $banners[$key]['banner_image']=base_url().$banner['banner_image'];
        }
      return $banners;  
   }

    
    // Update User Profile 
    
    function update_user($RecordData = NULL, $user_id = NULL) {
        if ($RecordData) {
            $this->db->update('users', $RecordData, array('id' => $user_id));
            return true;
        }
        return false;
    }
    
    
    
    function user_details($user_id) {
        $this->db->where('users.status', 1);
        $this->db->where('users.delete_status', 1);
        $this->db->where('users.id', $user_id);
        $this->db->select('users.*');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = users.id), 0) as ratings");
        $this->db->select("IFNULL((SELECT COUNT(id) FROM rides where rides.user_id = users.id), 0) as rides_posted");
        $user_login = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_login->num_rows() > 0) {
            
            
            $user_detailsMain = $user_login->row_array();
            
            
                  $user_details = array();
          foreach($user_detailsMain as $key => $value)
        {
            $user_details[$key] = $value === null ? '' : $value;
        }
            
            
            
            
            $one = 0;
            if ($user_details['aadhar_card_verified'] == "yes") {
                $one = 40;
            }
            $two = 0;
            if ($user_details['address_verified'] == "yes") {
                $two = 20;
            }
            $three = 0;
            if ($user_details['mobile_verified'] == "yes") {
                $three = 10;
            }
            $four = 0;
            if ($user_details['photo_verified'] == "yes") {
                $four = 10;
            }
            $five = 0;
            if ($user_details['email_id_verified'] == "yes") {
                $five = 10;
            }
            $six = 0;
            if ($user_details['office_email_id_verified'] == "yes") {
                $six = 10;
            }
            $total = $one + $two + $three + $four + $five + $six;
            $user_details['profile_percentage'] = $total;
            return $user_details;
        }
        return new ArrayObject();
    }


     // Update User Profile 
    
    function cancel_taxi_order($booking_id, $user_id) {
            $upData=array('status'=>'cancelled by user');
            $result=$this->db->update('taxi_orders', $upData, array('user_id' => $user_id,'booking_id'=>$booking_id));
            return $result;
      
    }
   

}