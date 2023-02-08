<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menuitems_model extends CI_Model {


function get_rides_taken($user_id){

	$query="SELECT tor.id AS order_id, tor.user_id, tor.vehicle_id, u.first_name, u.last_name, tor.from_address, tor.to_address, tor.total_amount, tor.trip_distance, tor.ride_time,uv.number_plate,uv.car_type FROM taxi_orders tor INNER JOIN user_vehicles uv ON uv.id = tor.vehicle_id INNER JOIN users u ON u.id = uv.user_id WHERE tor.user_id = '$user_id' ";
	//echo $query;exit;
	$result=$this->db->query($query)->result_array();

	foreach($result as $key=>$value){
	$rating_query="SELECT AVG(ur.ratings) as rating FROM user_ratings as ur WHERE ur.user_id='$user_id' GROUP BY ur.user_id";
	$rating=$this->db->query($rating_query)->row_array();
	$result[$key]['rating']= number_format((float)$rating['rating'], 1, '.', '');
		}

	return $result;
}


function get_rides_scheduled($user_id){

	$query="SELECT tor.id AS order_id, tor.user_id, tor.vehicle_id, u.first_name, u.last_name, tor.from_address, tor.to_address, tor.total_amount, tor.trip_distance, tor.ride_time,uv.number_plate,uv.car_type FROM taxi_orders tor INNER JOIN user_vehicles uv ON uv.id = tor.vehicle_id INNER JOIN users u ON u.id = uv.user_id WHERE tor.user_id = '$user_id' and tor.ride_type='later' ";
	//echo $query;exit;
	$result=$this->db->query($query)->result_array();

	foreach($result as $key=>$value){
	$rating_query="SELECT AVG(ur.ratings) as rating FROM user_ratings as ur WHERE ur.user_id='$user_id' GROUP BY ur.user_id";
	$rating=$this->db->query($rating_query)->row_array();
	$result[$key]['rating']= number_format((float)$rating['rating'], 1, '.', '');
	$result[$key]['ride_type']= "Taking";
		}

	return $result;
}


// get vehicle details 

function get_vehicle_details($user_id){

    
   $query="SELECT * FROM `user_vehicles` WHERE `user_id`='$user_id' AND status='1'";

	$resultMain=$this->db->query($query)->result_array();

  //$result = array();
  
	foreach($resultMain as $key=>$value){
	    
	    $vehicle_id=$value['id'];
	    
	    $make_id=$value['make_id'];
	    
	    $model_id=$value['model_id'];
	     
	    
	$vehicle_images_query="SELECT * FROM `vehicle_images` WHERE  `user_id`='$user_id' AND `vehicle_id`='$vehicle_id'";
	$vehicle_images=$this->db->query($vehicle_images_query)->result_array();
	
	
    $vehicle_makes_query="SELECT * FROM `vehicle_makes` WHERE  `id`='$make_id'";
	$vehicle_makes=$this->db->query($vehicle_makes_query)->result_array();
	
	
	
	$vehicle_model_query="SELECT * FROM `vehicle_models` WHERE  make_id='$make_id' AND `id`='$user_id' ";
    $vehicle_model=$this->db->query($vehicle_model_query)->result_array();


     // print_r($this->checknull($value));
      


    $result[$key] = $this->checknull($value);
        
        
	
 	$result[$key]['vehicle_images']= empty($vehicle_images) ? array() : $vehicle_images;
 	
 	$result[$key]['vehicle_makes']= empty($vehicle_makes) ? array() : $vehicle_makes;
 	
 	
 	$result[$key]['vehicle_models']= empty($vehicle_model) ? array() : $vehicle_model;
 	
 	}
	
    return $result;
}



   // delete vehicle details 

    function delete_vehicle($RecordData = NULL, $user_id = NULL, $id = NULL) {
        
        if ($RecordData) {
            $this->db->update('user_vehicles', $RecordData, array('user_id' => $user_id,'id'=>$id));
            return true;
        }
        return false;
    }
    
    
       // delete vehicle details 

    function delete_bank_details($RecordData = NULL, $user_id = NULL, $id = NULL) {
        
        if ($RecordData) {
            $deleted = $this->db->delete('user_bank_details', array('user_id' => $user_id,'id'=>$id));
            
         //   $this->db->update('user_bank_details', $RecordData, array('user_id' => $user_id,'id'=>$id));
            return true;
        }
        return false;
    }
    
    
    // get Favourites 
    
    
function get_favourites_details($user_id){

    
        $query="SELECT * FROM `favourite_locations` WHERE `user_id`='$user_id' AND status=1";

    	$resultMain=$this->db->query($query)->result_array();
    	
    	 	foreach($resultMain as $key=>$value){
    
           $result[$key] = $this->checknull($value);
  
    		}


	return $result;
}
    
    
    // delete favourite locations 

    function delete_favourite_location($RecordData = NULL, $user_id = NULL, $id = NULL) {
        
        if ($RecordData) {
            $this->db->update('favourite_locations', $RecordData, array('user_id' => $user_id,'id'=>$id));
            return true;
        }
        return false;
    }
    
    
    
        //  get_emergency_contacts_list 
    
    
function get_emergency_contacts_list($user_id){

    
        $query="SELECT * FROM `emergency_contacts` WHERE `user_id`='$user_id' and status='1'";

    	$result=$this->db->query($query)->result_array();


	return $result;
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





function get_my_paymnets($user_id){

	$query="SELECT * FROM `orders` WHERE `driver_status`='Started' AND `payment_status`='paid' AND `user_id`='$user_id'";
	$result=$this->db->query($query)->result_array();

	foreach($result as $key=>$value){
	    
	   // print_r($value['amount']);
	   
   	$result[$key]['Booking_Date']= date('d M Y', strtotime($value['ride_time']));
   	 
   	$result[$key]['Booking_Time']= date('H:m:s', strtotime($value['ride_time']));

	// $result[$key]['Final_Amout']=  $value['amount']+$value['base_fare']+$value['tax']+$value['payment_gateway_commision']+$value['ciao_commission'];
	
	 $result[$key]['Final_Amout']= $value['total_amount'];
	 
	 
		}

	return $result;
}



function get_my_earnings($user_id){

	$query="SELECT * FROM `rides` WHERE `status`='completed' AND user_id='$user_id'";
	$result=$this->db->query($query)->result_array();

	foreach($result as $key=>$value){
	    
	   // print_r($value['amount']);
	   
   	$result[$key]['Ride_Date']= date('d M Y', strtotime($value['ride_end_time']));
   	 
   	$result[$key]['Ride_Time']= date('H:m:s', strtotime($value['ride_end_time']));

	 $result[$key]['Final_Amout']=  $value['seats_available']*$value['amount_per_head'];
		}

	return $result;
}



function get_user_bank_details($user_id){

    
   $query="SELECT * FROM `user_bank_details` WHERE `user_id`='$user_id' ";

	$resultMain=$this->db->query($query)->result_array();

  //$result = array();
  
	foreach($resultMain as $key=>$value){
	    
	  

    $result[$key] = $this->checknull($value);
       
 	
 	}
	
    return $result;
}





public function checknull($array=array()){
    
    foreach ($array as $key => $value) {
    if (is_null($value)) {
         $array[$key] = "";
    }
    
  
    
}
  return $array;
  
  

}

    

}?>