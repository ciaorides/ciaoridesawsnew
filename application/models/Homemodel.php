<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Homemodel extends CI_Model
{
	public function login($username, $password)
	{
		//echo $password;exit;
		$this->db->select('username', 'password');
		$this->db->from('admin');
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));		
		$query = $this->db->get();	
		//echo $this->db->last_query();exit;
		if ($query !== FALSE)
		{	
			if($query->num_rows() == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function categories()
	{
		$this->db->order_by('id', 'asc');
		$this->db->where('delete_status', 1);
		$query = $this->db->get('categories');
		$res = $query->result();		
		if(!empty($res))
		{
			foreach($res as $row)
			{
				$events = $this->get_category_events_concat($row->id);
				$data[] = array(
					'id' => $row->id,
					'title' => $row->title,
					'icon' => $row->icon,
					'image' => $row->image,
					'capacity' => $row->capacity,
					'token_amount' => $row->token_amount,
					'from_range' => $row->from_range,
					'to_range' => $row->to_range,
					'cancel_percent' => $row->cancel_percent,
					'delete_status' => $row->delete_status,
					'created_on' => $row->created_on,
					'modified_on' => $row->modified_on,
					'events' => $events
					);
			}
			return $data;
		}
	}

	public function featured_venues($city_id)
	{
		$this->db->order_by('venues.id', 'asc');
		$this->db->where('venues.delete_status', 1);
		$this->db->where('city_id', $city_id);
		$this->db->where('featured', 'Yes');
		$this->db->join('categories', 'categories.id = venues.category_id', 'left');
		$this->db->select('venues.id, address, venues.image, people_capacity, venue_name, categories.title as category');
		$query = $this->db->get('venues');
		$res = $query->result();		
		return $res;
	}

	public function recommended_events()
	{
		$this->db->order_by('event_types.id', 'asc');
		$this->db->where('event_types.delete_status', 1);
		$this->db->where('recommended', 'Yes');
		$this->db->select('*');
		$query = $this->db->get('event_types');
		$res = $query->result();		
		return $res;
	}

	public function get_category_events_concat($category_id)
	{
		$this->db->order_by('id', 'desc');
		$this->db->limit(2);
		$this->db->where('delete_status', 1);
		$this->db->where('category_id', $category_id);
		$this->db->select('title');
		$query = $this->db->get('event_types');
		$res = $query->result();
		if(!empty($res))
		{		
			foreach($res as $row)
			{
				$data[] = $row->title;
			}
			return implode(',', $data);
		}
		return "";
	}

	public function cities()
	{
		$this->db->order_by('id', 'asc');
		$this->db->where('delete_status', 1);
		$query = $this->db->get('cities');
		return $query->result();
	}

	public function get_city_name_using_city_id($city_id)
	{
		$this->db->where('delete_status', 1);
		$this->db->where('id', $city_id);
		$this->db->select('title');
		$query = $this->db->get('cities');
		$row = $query->row();
		return $row->title;
	}

	function areas($city_id)
	{	
		$this->db->where('delete_status', 1);
		$this->db->where('city_id', $city_id);
		$areas = $this->db->get('areas');	
		//echo $this->db->last_query();exit;
		if($areas->num_rows() > 0)
		{
			return $areas->result();
		}
		return array();
	}

	function event_types()
	{	
		$url = explode('=', $_SERVER['REQUEST_URI']);
        if(isset($url[1]))
        {
        	$filters = explode(',', $url[1]);
        	if(isset($filters[4]) && $filters[4] != "")
			{
				$this->db->where('category_id', $filters[4]);
				$event_types = $this->db->get('event_types');	
				//echo $this->db->last_query();exit;
				if($event_types->num_rows() > 0)
				{
					return $event_types->result();
				}
			}
        }		
		return array();
	}

	function get_category_name()
	{	
		$url = explode('=', $_SERVER['REQUEST_URI']);
        if(isset($url[1]))
        {
        	$filters = explode(',', $url[1]);
        	if(isset($filters[4]) && $filters[4] != "")
			{
				$this->db->where('id', $filters[4]);
				$categories = $this->db->get('categories');	
				//echo $this->db->last_query();exit;
				if($categories->num_rows() > 0)
				{
					$res = $categories->row();
					return $res->title;
				}
			}
        }		
		return "";
	}

	function venues($params = array())
	{	
		$user_logged_in = $this->session->userdata('user_logged_in');
		if(isset($user_logged_in) || $user_logged_in == true)
		{
			$user_id = $this->session->userdata('user_id');
		}
		else
		{
			$user_id = 0;
		}

		$date = "";
		if(array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
            $this->db->limit($params['limit'],$params['start']);
        }
        elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
            $this->db->limit($params['limit']);
        }

        $url = explode('=', $_SERVER['REQUEST_URI']);
        if(isset($url[1]))
        {
	        $filters = explode(',', $url[1]);
	    	if(isset($filters[0]) && $filters[0] == "high")
	    	{
	    		$this->db->order_by('venues.price', 'desc');
	    	}    	
			elseif(isset($filters[0]) && $filters[0] == "low")
			{
				$this->db->order_by('venues.price', 'asc');
			}
			elseif(isset($filters[0]) && $filters[0] == "rating")
			{
				$this->db->order_by('ratings', 'desc');
			}
			if(isset($filters[1]) && $filters[1] == "veg")
			{
				$this->db->where('veg', "veg");
			}
			elseif(isset($filters[1]) && $filters[1] == "non_veg") 
			{
				$this->db->where('veg', "non_veg");
			}
			elseif(isset($filters[1]) && $filters[1] == "both") 
			{
				$where_veg = "(veg = 'veg' OR veg = 'non_veg' OR veg = 'both')";
				$this->db->where($where_veg);
			}
			if(isset($filters[2]) && $filters[2] == "ac")
			{
				$this->db->where('ac', "ac");
			}
			elseif(isset($filters[2]) && $filters[2] == "non_ac") 
			{
				$this->db->where('ac', "non_ac");
			}
			elseif(isset($filters[2]) && $filters[2] == "both") 
			{
				$where_ac = "(ac = 'ac' OR ac = 'non_ac' OR ac = 'both')";
				$this->db->where($where_ac);
			}
			if(isset($filters[3]) && $filters[3] != "")
			{
				$this->db->where('area_id', $filters[3]);
			}
			if(isset($filters[4]) && $filters[4] != "")
			{
				$category_id = $filters[4];
				$this->db->where('venues.category_id', $filters[4]);
			}			
			if(isset($filters[5]) && $filters[5] != "")
			{
				$event_types = $filters[5];
				$this->db->where("FIND_IN_SET('$event_types', event_types) ");
			}
			if(isset($filters[6]) && $filters[6] != "" && ($filters[4] == 4 || $filters[4] == 9 || $filters[4] == 10))
			{
				$capacity = $filters[6];
				$this->db->where("$capacity BETWEEN SUBSTRING_INDEX(people_capacity, '-', 1) AND SUBSTRING_INDEX(people_capacity, '-', -1)");
			}
			elseif(isset($filters[6]))
			{
				$capacity = $filters[6];
				$this->db->where('CAST(people_capacity AS SIGNED) >=', $capacity);
			}
			if(isset($filters[7]))
			{
				$date = date('Y-m-d', strtotime($filters[7]));
			}
		}
		$this->db->group_by('venues.id');
		//$this->db->where("FIND_IN_SET('$event_types', event_types) ");
		$this->db->where('city_id', $this->session->userdata('city'));
		//$this->db->where('venues.category_id', $data['category_id']);
		$this->db->group_by('venues.id');
		$this->db->where('venues.delete_status', 1);
		$this->db->join('sv_amenities', 'FIND_IN_SET(sv_amenities.id, sv_venues.amenities)', '', FALSE);
		$this->db->join('categories', 'categories.id = venues.category_id');
		$this->db->join('sv_event_types', 'FIND_IN_SET(sv_event_types.id, sv_venues.event_types)', '', FALSE);
		$this->db->join('sv_services', 'FIND_IN_SET(sv_services.id, sv_venues.services)', '', FALSE);
		$this->db->select('venues.*, categories.capacity as capacity_applicable, categories.token_amount');
		$this->db->select("GROUP_CONCAT(DISTINCT sv_amenities.icon SEPARATOR ',') as amenities_images");
		$this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM sv_venue_ratings where sv_venue_ratings.venue_id = sv_venues.id) as ratings");
		//$this->db->select("IF ((SELECT COUNT(*) FROM `sv_venue_ratings` where user_id = '$user_id' and sv_venue_ratings.venue_id = sv_venues.id) >0, 'yes', 'no') as rating_given");
		$this->db->select("IF ((SELECT COUNT(*) FROM `sv_favourites` where user_id = '$user_id' and sv_favourites.venue_id = sv_venues.id) >0, 'yes', 'no') as favourites");
		$venues = $this->db->get('venues');	
		//echo $this->db->last_query();exit;
		if($venues->num_rows() > 0)
		{
			$res = $venues->result();
			foreach($res as $row)
			{
				$slots_count = $this->check_slots_left($row->id, $date, $row->category_id);
				$capacity_booked = $this->check_capacity_booked($row->id, $date, $row->category_id);
				$total_capacity = $this->total_capacity($row->id, $date, $row->category_id);
				$final_data[] = array(
					"id" => $row->id,
					"vendor_id" => $row->vendor_id,
		            "city_id" => $row->city_id,
		            "area_id" => $row->area_id,
		            "category_id" => $row->category_id,
		            "event_types" => $row->event_types,
		            "venue_name" => $row->venue_name,
		            "address" => $row->address,
		            "lat" => $row->lat,
		            "lng" => $row->lng,
		            "people_capacity" => $row->people_capacity,
		            "price" => $row->price,
		            "discount_percentage" => $row->discount_percentage,
		            "token_amount" => $row->token_amount,
		            "description" => $row->description,
		            "booking_type" => $row->booking_type,
		            "venue_type" => $row->venue_type,
		            "amenities" => $row->amenities,
		            "services" => $row->services,
		            "ac" => $row->ac,
		            "veg" => $row->veg,
		            "contact_number" => $row->contact_number,
		            "email_id" => $row->email_id,
		            "image" => $row->image,		            
		            "slots_count" => $slots_count,
		            "total_capacity" => $total_capacity,
		            "capacity_booked" => $capacity_booked,
		            "amenities_images" => $row->amenities_images,
		            "capacity_applicable" => $row->capacity_applicable,
		            "ratings" => $row->ratings,
		            //"rating_given" => $row->rating_given,
		            "favourites" => $row->favourites,
		            "delete_status" => $row->delete_status,
		            "created_on" => $row->created_on,
		            "modified_on" => $row->modified_on
				);
			}
			return $final_data;
		}
		return array();
	}

	function check_slots_left($venue_id, $date = NULL, $category_id = NULL)
	{	
		//echo $date;
		if($date && $category_id)
		{
			if($category_id == 4 || $category_id == 9 || $category_id == 10)
			{
				$this->db->where('venue_id', $venue_id);
				$slots = $this->db->get('venue_slots');	
				//echo $this->db->last_query();exit;
				$slots_count = $slots->num_rows();

				$this->db->where('venue_id', $venue_id);
				$this->db->where('booked_for', $date);
				$orders = $this->db->get('orders');	
				//echo $this->db->last_query();exit;
				$orders_count = $orders->num_rows();

				return $slots_count - $orders_count;
			}
			else
			{
				$i = 0;
				$slot_capacity = 0;
				$this->db->where('venue_id', $venue_id);
				$this->db->select('*');
				$slots = $this->db->get('venue_slots');	
				//echo $this->db->last_query();exit;
				if($slots->num_rows() > 0)
				{
					$slots_count = $slots->num_rows();
					$f_slots = $slots_count;
					$res = $slots->result();
					foreach($res as $row)
					{	
										
						$slot_id = $row->id;
						$slot_capacity = $row->slot_capacity;
						$orders_capacity = $this->check_orders_capacity($slot_id);
						if($slot_capacity <= $orders_capacity)
						{		
							//slot left			
							$i++;
						}	
					}
					return $slots_count - $i;		
				}
			}
		}
		else
		{
			$this->db->where('venue_id', $venue_id);
			$slots = $this->db->get('venue_slots');	
			//echo $this->db->last_query();exit;
			return $slots->num_rows();	
		}		
	}

	function check_orders_capacity($slot_id)
	{
		$this->db->where('slot_id', $slot_id);
		$this->db->select('sum(capacity) as capacity');
		$orders = $this->db->get('orders');	
		//echo $this->db->last_query();exit;
		if($orders->num_rows() > 0)
		{
			$res = $orders->row();
			return $res->capacity;
		}
		return 0;
	}

	function check_capacity_booked($venue_id, $date, $category_id)
	{	
		// $this->db->where('venue_id', $venue_id);
		// $this->db->select('sum(slot_capacity) as slot_capacity');
		// $slots = $this->db->get('venue_slots');
		// //echo $this->db->last_query();exit;
		// if($slots->num_rows() > 0)
		// {
		// 	$res = $slots->row();
		// 	$slot_capacity = $res->slot_capacity;
		// }

		$this->db->where('venue_id', $venue_id);
		$this->db->where('booked_for', $date);
		$this->db->select('sum(capacity) as capacity');
		$orders = $this->db->get('orders');
		//echo $this->db->last_query();exit;
		if($orders->num_rows() > 0)
		{
			$res = $orders->row();
			return $res->capacity;
		}
		return 0;
	}

	function total_capacity($venue_id, $date, $category_id)
	{	
		$this->db->where('venue_id', $venue_id);
		$this->db->select('sum(slot_capacity) as slot_capacity');
		$slots = $this->db->get('venue_slots');
		//echo $this->db->last_query();exit;
		if($slots->num_rows() > 0)
		{
			$res = $slots->row();
			return $slot_capacity = $res->slot_capacity;
		}
		return 0;
	}

	function venue_details($venue_id, $date = NULL, $user_id = 0)
	{		
		$this->db->group_by('venues.id');
		$this->db->where('venues.delete_status', 1);
		$this->db->where('venues.id', $venue_id);
		$this->db->join('sv_event_types', 'FIND_IN_SET(sv_event_types.id, sv_venues.event_types)', '', FALSE);
		$this->db->join('sv_amenities', 'FIND_IN_SET(sv_amenities.id, sv_venues.amenities)', '', FALSE);
		$this->db->join('sv_services', 'FIND_IN_SET(sv_services.id, sv_venues.services)', '', FALSE);
		$this->db->join('categories', 'categories.id = venues.category_id');
		$this->db->select('venues.*, categories.capacity as capacity_applicable, categories.token_amount');
		$this->db->select("GROUP_CONCAT(DISTINCT sv_event_types.title SEPARATOR ', ') as event_type_titles");
		$this->db->select("GROUP_CONCAT(DISTINCT sv_amenities.icon SEPARATOR ',') as amenities_images");
		$this->db->select("GROUP_CONCAT(DISTINCT sv_amenities.title SEPARATOR ', ') as amenities_titles");
		$this->db->select("GROUP_CONCAT(DISTINCT sv_services.icon SEPARATOR ', ') as services_images");
		$this->db->select("GROUP_CONCAT(DISTINCT sv_services.title SEPARATOR ', ') as services_titles");
		$this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM sv_venue_ratings where sv_venue_ratings.venue_id = sv_venues.id) as ratings");
		$this->db->select("IF ((SELECT COUNT(*) FROM `sv_venue_ratings` where user_id = '$user_id' and sv_venue_ratings.venue_id = sv_venues.id) >0, 'yes', 'no') as rating_given");
		$this->db->select("IF ((SELECT COUNT(*) FROM `sv_favourites` where user_id = '$user_id' and sv_favourites.venue_id = sv_venues.id) >0, 'yes', 'no') as favourites");
		$venues = $this->db->get('venues');	
		//echo $this->db->last_query();exit;
		if($venues->num_rows() > 0)
		{
			$row = $venues->row();

			// if($date == NULL)
			// {
			// 	$date = date('Y-m-d');
			// }

			$slots_count = $this->check_slots_left($row->id, $date, $row->category_id);
			$check_slots_available = $this->check_slots_available($venue_id, $date, $row->category_id);
			$venue_ratings = $this->venue_ratings($venue_id);
			$capacity_booked = $this->check_capacity_booked($row->id, $date, $row->category_id);
			$total_capacity = $this->total_capacity($row->id, $date, $row->category_id);
			$venue_banners = $this->venue_banners($venue_id);

			$final_data = array(
				"id" => $row->id,
				"vendor_id" => $row->vendor_id,
	            "city_id" => $row->city_id,
	            "area_id" => $row->area_id,
	            "category_id" => $row->category_id,
	            "event_types" => $row->event_types,
	            "venue_id" => $row->id,
	            "venue_name" => $row->venue_name,
	            "address" => $row->address,
	            "lat" => $row->lat,
	            "lng" => $row->lng,
	            "people_capacity" => $row->people_capacity,
	            "price" => $row->price,
	            "discount_percentage" => $row->discount_percentage,
	            "token_amount" => $row->token_amount,
	            "description" => $row->description,
	            "booking_type" => $row->booking_type,
	            "venue_type" => $row->venue_type,
	            "amenities" => $row->amenities,
	            "services" => $row->services,
	            "ac" => $row->ac,
	            "veg" => $row->veg,
	            "contact_number" => $row->contact_number,
	            "email_id" => $row->email_id,
	            "image" => $row->image,		            
	            "slots_count" => $slots_count,
	            "total_capacity" => $total_capacity,
	            "capacity_booked" => $capacity_booked,
	            "amenities_images" => $row->amenities_images,
	            "amenities_titles" => $row->amenities_titles,
	            "services_images" => $row->services_images,
	            "services_titles" => $row->services_titles,
	            "event_type_titles" => $row->event_type_titles,
	            "capacity_applicable" => $row->capacity_applicable,
	            "ratings" => $row->ratings,
	            "rating_given" => $row->rating_given,
	            "favourites" => $row->favourites,
	            "delete_status" => $row->delete_status,
	            "created_on" => $row->created_on,
	            "modified_on" => $row->modified_on,
	            "check_slots_available" => $check_slots_available,
	            "venue_ratings" => $venue_ratings,
	            "venue_banners" => $venue_banners,
	            "date" => $date
			);
			
			return $final_data;
		}
		return array();
	}

	function check_slots_available($venue_id, $date = NULL, $category_id)
	{	
		if($date != "")
		{
			if($category_id == 4 || $category_id == 9 || $category_id == 10)
			{
				$this->db->where('venue_id', $venue_id);
				$this->db->where('date', $date);
				$this->db->select('temporary_slots.*');
				$this->db->select("IF ((SELECT COUNT(*) FROM `sv_orders` where sv_orders.venue_id = sv_temporary_slots.venue_id and sv_orders.slot_id = sv_temporary_slots.slot_id and sv_orders.booked_for = '$date') > 0, 'booked', 'available') as booking_status");
				$t_slots = $this->db->get('temporary_slots');	
				//echo $this->db->last_query();exit;
				if($t_slots->num_rows() > 0)
				{
					$res = $t_slots->result_array();
					foreach($res as $key=>$values)
			        {
			        	//echo $values['id'];exit;
			        	$users = $this->slot_booked_users($values['id']);
			            $res[$key]['users'] = $users;
			        }
					return $res;
				}
				else
				{
					$this->db->where('venue_id', $venue_id);
					$this->db->select('venue_slots.*');
				$this->db->select("IF ((SELECT COUNT(*) FROM `sv_orders` where sv_orders.venue_id = sv_venue_slots.venue_id and sv_orders.slot_id = sv_venue_slots.id and sv_orders.booked_for = '$date') > 0, 'booked', 'available') as booking_status");
					$slots = $this->db->get('venue_slots');	
					//echo $this->db->last_query();exit;
					if($slots->num_rows() > 0)
					{
						$res = $slots->result_array();
						foreach($res as $key=>$values)
				        {
				        	//echo $values['id'];exit;
				        	$users = $this->slot_booked_users($values['id']);
				            $res[$key]['users'] = $users;
				        }
						return $res;
					}
				}
			}
			else
			{
				$this->db->where('venue_id', $venue_id);
				$this->db->where('date', $date);
				$this->db->select('temporary_slots.*');
				$this->db->select("IF ((SELECT COUNT(*) FROM `sv_orders` where sv_orders.venue_id = sv_temporary_slots.venue_id and sv_orders.slot_id = sv_temporary_slots.slot_id and sv_orders.booked_for = '$date' and sv_temporary_slots.slot_capacity <= sv_orders.capacity) > 0, 'booked', 'available') as booking_status");
				$this->db->select("(SELECT sum(capacity) as capacity FROM `sv_orders` where sv_orders.venue_id = sv_temporary_slots.venue_id and sv_orders.slot_id = sv_temporary_slots.id and sv_orders.booked_for = '$date') as capacity_booked");
				$t_slots = $this->db->get('temporary_slots');	
				//echo $this->db->last_query();exit;
				if($t_slots->num_rows() > 0)
				{
					$res = $t_slots->result_array();
					foreach($res as $key=>$values)
			        {
			        	//echo $values['id'];exit;
			        	$users = $this->slot_booked_users($values['id']);
			            $res[$key]['users'] = $users;
			        }
					return $res;
				}
				else
				{
					$this->db->where('venue_id', $venue_id);
					$this->db->select('venue_slots.*');
					$this->db->select("IF ((SELECT COUNT(*) FROM `sv_orders` where sv_orders.venue_id = sv_venue_slots.venue_id and sv_orders.slot_id = sv_venue_slots.id and sv_orders.booked_for = '$date' and sv_venue_slots.slot_capacity <= sv_orders.capacity) > 0, 'booked', 'available') as booking_status");
					$this->db->select("(SELECT sum(capacity) as capacity FROM `sv_orders` where sv_orders.venue_id = sv_venue_slots.venue_id and sv_orders.slot_id = sv_venue_slots.id and sv_orders.booked_for = '$date') as capacity_booked");
					$slots = $this->db->get('venue_slots');	
					//echo $this->db->last_query();exit;
					if($slots->num_rows() > 0)
					{
						$res = $slots->result_array();
						foreach($res as $key=>$values)
				        {
				        	//echo $values['id'];exit;
				        	$users = $this->slot_booked_users($values['id']);
				            $res[$key]['users'] = $users;
				        }
						return $res;
					}
				}
			}
		}
		return array();	
	}

	function slot_booked_users($slot_id)
	{	
		$this->db->where('slot_id', $slot_id);
		$this->db->join('users', 'users.id = orders.user_id');
		$this->db->select('users.name, users.mobile, users.email_id');
		$ratings = $this->db->get('orders');	
		//echo $this->db->last_query();exit;
		if($ratings->num_rows() > 0)
		{
			$res = $ratings->result();
			return $res;
		}
		return array();
	}

	function venue_banners($venue_id)
	{	
		$this->db->where('venue_id', $venue_id);
		$this->db->select('venue_banners.*');
		$venue_banners = $this->db->get('venue_banners');	
		//echo $this->db->last_query();exit;
		if($venue_banners->num_rows() > 0)
		{
			$res = $venue_banners->result();
			return $res;
		}
		return array();
	}

	function venue_ratings($venue_id)
	{	
		$this->db->order_by('venue_ratings.id', 'desc');
		$this->db->where('venue_id', $venue_id);
		$this->db->join('users', 'users.id = venue_ratings.user_id');
		$this->db->select('venue_ratings.*, users.*');
		$ratings = $this->db->get('venue_ratings');	
		//echo $this->db->last_query();exit;
		if($ratings->num_rows() > 0)
		{
			$res = $ratings->result();
			return $res;
		}
		return array();
	}

	public function check_mobile_exists($user_id, $mobile)
    {
        if(!empty($user_id))
        {
             $this->db->where('id !=', $user_id);
        }
        if(!empty($mobile) )
        {
             $this->db->where('mobile', $mobile);               
	        $this->db->where('delete_status',  1);
	        $query = $this->db->get('users'); 
	        //echo $this->db->last_query();exit;
	        return $query->num_rows(); 
	    }
    }

    public function check_email_id_exists($user_id, $email_id)
    {
        if($user_id)
        {
             $this->db->where('id !=', $user_id);
        }
        if($email_id)
        {
             $this->db->where('email_id', $email_id);              
	        $this->db->where('delete_status',  1);
	        $query = $this->db->get('users'); 
	        //echo $this->db->last_query();exit;
	        return $query->num_rows(); 
    	}
    }

    function set_users($RecordData = NULL)
	{		
		if($RecordData)
		{				
			$this->db->insert('users', $RecordData);
			//echo $this->db->last_query();exit;	
			$insert_id = $this->db->insert_id();
			return $insert_id;			
		}
		return 0;
	}

	public function user_login($mobile, $password)
	{
		$this->db->where('delete_status', 1);
		$this->db->where('status', 'Active');
		$this->db->select('id, name, mobile, email_id');
		$this->db->from('users');
		$this->db->where('mobile', $mobile);
		$this->db->where('password', md5($password));
		$query = $this->db->get();
		//echo $this->db->last_query();exit;	
		return $query->row();	
	}

	public function get_userid_emailid($email_id)
	{
		$this->db->order_by('id', 'desc');
		$this->db->where('delete_status', 1);
		$this->db->where('email_id', $email_id);
		$this->db->select('id');
		$query = $this->db->get('users');
		return $query->row();
	}

	function place_order($RecordData = NULL)
	{		
		if($RecordData)
		{				
			$this->db->insert('orders', $RecordData);
			//echo $this->db->last_query();exit;	
			$insert_id = $this->db->insert_id();
			return $insert_id;			
		}
		return 0;
	}

	function update_payment_status($RecordData = NULL, $order_id)
	{		
		if($RecordData)
		{				
			$this->db->where('id', $order_id);
			$this->db->update('orders', $RecordData);
			//echo $this->db->last_query();exit;	
			return true;			
		}
		return false;
	}

	public function add_to_favourites($data)
	{
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('venue_id', $data['venue_id']);
		$q = $this->db->get('favourites');
		$res = $q->num_rows();
		//echo $this->db->last_query();
		$insert = "";
		$delete = "";
		//echo $res;exit;
		if($res == 0)
		{	
			$insert = $this->db->insert('favourites', $data);
		}
		else
		{
			$this->db->where('user_id', $data['user_id']);
			$this->db->where('venue_id', $data['venue_id']);
			$delete = $this->db->delete('favourites');
		}	
		//echo $this->db->last_query();
		//echo $insert;
		if($insert)
		{	
			return 1;
		}
		elseif($delete)
		{
			return 0;
		}
		else
		{
			return false;
		}
	}

	public function send_register_email($email_id)
	{
		$data = array();
		$message = $this->load->view("website/registration_email",$data,true);
		SendEmail(array($email_id), 'Registration Confirmation', $message,"","");
	}

	public function send_booking_successful_email($email_id, $order_id)
	{
		$data['details'] = $this->get_order_details($order_id);
		$message = $this->load->view("website/booking_confirmation",$data,true);
		SendEmail(array($email_id), 'Booking Confirmation', $message,"","");
	}

	public function send_booking_cancel_email($email_id, $order_id)
	{
		$data['details'] = $this->get_order_details($order_id);
		$message = $this->load->view("website/booking_cancellation",$data,true);
		SendEmail(array($email_id), 'Booking Cancellation', $message,"","");
	}

	public function get_order_details($order_id)
	{
		$this->db->where('orders.id', $order_id);
		$this->db->join('venue_slots', 'venue_slots.id = orders.slot_id');
		$this->db->join('venues', 'venues.id = orders.venue_id');
		$this->db->select('orders.*, venues.*, orders.created_on, venue_slots.*');
		$query = $this->db->get('orders');
		return $query->row();
	}

	public function user_profile($user_id)
	{
		$this->db->order_by('id', 'desc');
		$this->db->where('delete_status', 1);
		$this->db->where('id', $user_id);
		$query = $this->db->get('users');
		return $query->row();
	}

	public function my_orders($user_id)
	{
		$this->db->order_by('enrolled_courses.id', 'desc');		
		$this->db->where('payment_status', 'completed');	
		$this->db->where('user_id', $user_id);
		$this->db->join('courses', 'courses.id = enrolled_courses.course_id');
		$this->db->join('course_batches', 'course_batches.id = enrolled_courses.batch_id');
		$this->db->select('enrolled_courses.*, courses.title as course_name, course_batches.name as batch_name, course_batches.batch_date, course_batches.duration_range, course_batches.duration, course_batches.timing');
		$query = $this->db->get('enrolled_courses');
		return $query->result();
	}

	function user_bookings($user_id)
	{
		$this->db->order_by('orders.id', 'desc');
		$this->db->group_by('orders.id');		
		$this->db->where('orders.user_id', $user_id);
		$this->db->join('categories', 'categories.id = orders.category_id');
		$this->db->join('venues', 'venues.id = orders.venue_id');
		$this->db->join('sv_amenities', 'FIND_IN_SET(sv_amenities.id, sv_venues.amenities)', '', FALSE);
		$this->db->join('venue_slots', 'venue_slots.id = orders.slot_id');		
		$this->db->select('orders.*, venues.*, orders.created_on, categories.capacity as capacity_applicable, categories.token_amount, venue_slots.start_time, venue_slots.end_time, orders.id as order_id, categories.from_range');
		$this->db->select("GROUP_CONCAT(DISTINCT sv_amenities.icon SEPARATOR ', ') as amenities_images");
		$this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM sv_venue_ratings where sv_venue_ratings.venue_id = sv_venues.id) as ratings");
		$this->db->select("IF ((SELECT COUNT(*) FROM `sv_venue_ratings` where user_id = '$user_id' and sv_venue_ratings.venue_id = sv_venues.id) >0, 'yes', 'no') as rating_given");		
		$orders = $this->db->get('orders');	
		//echo $this->db->last_query();exit;
		if($orders->num_rows() > 0)
		{
			$res = $orders->result();
			foreach($res as $row)
			{
				$today = date('Y-m-d');
				$date1 = date_create($today);
				$date2 = date_create($row->booked_for);
				$diff = date_diff($date1,$date2);
				$days_left = $diff->format("%a");

				$cancel_button = "show";
				if($days_left < $row->from_range)
				{
					$cancel_button = "hide";
				}
				//echo date('d, F, Y', strtotime($row->created_on));
				$final_data[] = array(
					"id" => $row->id,
					"vendor_id" => $row->vendor_id,
		            "city_id" => $row->city_id,
		            "area_id" => $row->area_id,
		            "category_id" => $row->category_id,
		            "event_types" => $row->event_types,
		            "venue_name" => $row->venue_name,
		            "address" => $row->address,
		            "lat" => $row->lat,
		            "lng" => $row->lng,
		            "people_capacity" => $row->people_capacity,
		            "price" => $row->price,
		            "discount_percentage" => $row->discount_percentage,
		            "token_amount" => $row->token_amount,
		            "description" => $row->description,
		            //"booking_type" => $row->booking_type,
		            "venue_type" => $row->venue_type,
		            "amenities" => $row->amenities,
		            "services" => $row->services,
		            "ac" => $row->ac,
		            "veg" => $row->veg,
		            "contact_number" => $row->contact_number,
		            "email_id" => $row->email_id,
		            "image" => $row->image,		            
		            "amenities_images" => $row->amenities_images,
		            "capacity_applicable" => $row->capacity_applicable,
		            "ratings" => $row->ratings,
		            "rating_given" => $row->rating_given,
		            "delete_status" => $row->delete_status,		            
		            "order_id" => $row->order_id,
		            "booking_id" => $row->booking_id,
		            "user_id" => $row->user_id,
		            "category_id" => $row->category_id,
		            "venue_id" => $row->venue_id,
		            "slot_id" => $row->slot_id,
		            "total_capacity" => $row->total_capacity,
		            "capacity" => $row->capacity,
		            "amount_paid" => $row->amount_paid,
		            "booking_type" => $row->booking_type,
		            "booked_for" => date('d, F, Y', strtotime($row->booked_for)),
		            "start_time" => date('h:i A', strtotime($row->start_time)),
		            "end_time" => date('h:i A', strtotime($row->end_time)),
		            "payment_status" => $row->payment_status,
		            "status" => $row->status,
		            "cancellation_reason" => $row->cancellation_reason,
		            "booked_on" => date('d, F, Y', strtotime($row->created_on)),
		            "created_on" => date('Y-m-d', strtotime($row->created_on)),
		            "booked_for_date" => date('Y-m-d', strtotime($row->booked_for)),
		            "cancel_button" => $cancel_button
				);
			}
			return $final_data;
		}
		return array();
	}

	function favourites($user_id)
	{
		$this->db->group_by('favourites.id');
		$this->db->order_by('favourites.id', 'desc');
		$this->db->where('favourites.user_id', $user_id);		
		$this->db->join('venues', 'venues.id = favourites.venue_id');
		$this->db->join('categories', 'categories.id = venues.category_id');
		$this->db->join('sv_amenities', 'FIND_IN_SET(sv_amenities.id, sv_venues.amenities)', '', FALSE);
		$this->db->select('favourites.*, venues.*, categories.capacity as capacity_applicable, categories.token_amount');
		$this->db->select("GROUP_CONCAT(DISTINCT sv_amenities.icon SEPARATOR ', ') as amenities_images");
		$this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM sv_venue_ratings where sv_venue_ratings.venue_id = sv_venues.id) as ratings");
		$this->db->select("IF ((SELECT COUNT(*) FROM `sv_venue_ratings` where user_id = '$user_id' and sv_venue_ratings.venue_id = sv_venues.id) >0, 'yes', 'no') as rating_given");
		$favourites = $this->db->get('favourites');
		//echo $this->db->last_query();exit;
		if($favourites->num_rows() > 0)
		{
			$res = $favourites->result();
			foreach($res as $row)
			{
				//$slots_count = $this->check_slots_left($row->id, $data['date'], $data['category_id']);
				//$capacity_booked = $this->check_capacity_booked($row->id, $data['date'], $data['category_id']);
				$final_data[] = array(
					"id" => $row->id,
					"vendor_id" => $row->vendor_id,
		            "city_id" => $row->city_id,
		            "area_id" => $row->area_id,
		            "category_id" => $row->category_id,
		            "event_types" => $row->event_types,
		            "venue_name" => $row->venue_name,
		            "address" => $row->address,
		            "lat" => $row->lat,
		            "lng" => $row->lng,
		            "people_capacity" => $row->people_capacity,
		            "price" => $row->price,
		            "discount_percentage" => $row->discount_percentage,
		            "token_amount" => $row->token_amount,
		            "description" => $row->description,
		            //"booking_type" => $row->booking_type,
		            "venue_type" => $row->venue_type,
		            "amenities" => $row->amenities,
		            "services" => $row->services,
		            "ac" => $row->ac,
		            "veg" => $row->veg,
		            "contact_number" => $row->contact_number,
		            "email_id" => $row->email_id,
		            "image" => $row->image,		            
		            //"slots_count" => $slots_count,
		            //"capacity_booked" => $capacity_booked,
		            "amenities_images" => $row->amenities_images,
		            "capacity_applicable" => $row->capacity_applicable,
		            "ratings" => $row->ratings,
		            "rating_given" => $row->rating_given,
		            "delete_status" => $row->delete_status,
		            "created_on" => date('d, F, Y', strtotime($row->created_on)),
				);
			}
			return $final_data;
		}
		return array();
	}

	public function update_password($data, $user_id)
    {
    	$this->db->where('id', $user_id);
        $this->db->update('users', $data); 
        //echo $this->db->last_query();exit;            
        return true;
    }

    function get_category_details_using_order_id($order_id)
	{	
		$this->db->where('orders.id', $order_id);
		$this->db->join('categories', 'categories.id = orders.category_id');
		$this->db->select('categories.*, orders.amount_paid');
		$orders = $this->db->get('orders');	
		//echo $this->db->last_query();exit;
		if($orders->num_rows() > 0)
		{
			return $orders->row();
		}
		return array();
	}

	function user_cancel_booking($RecordData = NULL, $order_id)
	{		
		if($RecordData)
		{				
			$this->db->where('id', $order_id);
			$this->db->update('orders', $RecordData);
			//echo $this->db->last_query();exit;	
			return true;			
		}
		return false;
	}

	public function search_venues($keyword)
	{
		if($keyword)
		{
			$this->db->group_by('venues.id');
			$this->db->order_by('venues.id', 'desc');
			$this->db->where('venues.delete_status', 1);
			$this->db->like('venue_name', $keyword, 'after');
			$this->db->join('sv_event_types', 'FIND_IN_SET(sv_event_types.id, sv_venues.event_types)', '', FALSE);
			$this->db->join('sv_amenities', 'FIND_IN_SET(sv_amenities.id, sv_venues.amenities)', '', FALSE);
			$this->db->join('sv_services', 'FIND_IN_SET(sv_services.id, sv_venues.services)', '', FALSE);
			$this->db->join('categories', 'categories.id = venues.category_id');
			$this->db->select('venues.id, venue_name');
			$query = $this->db->get('venues');
			return $query->result();
		}
		return array();
	}
	
}
?>