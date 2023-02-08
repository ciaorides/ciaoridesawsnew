<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sharing_model extends CI_Model {
    

    
    function search_universities($keyword) {
        $this->db->order_by('title', 'asc');
        $this->db->where('delete_status', 1);
        $this->db->like('title', $keyword, 'after');
        $universities = $this->db->get('universities');
        //echo $this->db->last_query();exit;
        if ($universities->num_rows() > 0) {
            return $universities->result_array();
        }
        return array();
    }

    function set_users($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('users', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return 0;
    }

    function check_user_deleted_using_userid($user_id) {
        $this->db->where('delete_status', 1);
        $this->db->where('id', $user_id);
        $user_status = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_status->num_rows() > 0) {
            return $user_status->row_array();
        }
        return array();
    }

    function check_user_status_using_userid($user_id) {
        $this->db->where('status', 1);
        $this->db->where('id', $user_id);
        $user_status = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_status->num_rows() > 0) {
            return $user_status->row_array();
        }
        return array();
    }

    function check_for_user_token($user_id, $ios_token = NULL, $android_token = NULL) {
        $this->db->where('status', 1);
        $this->db->where('id', $user_id);
        if ($ios_token) {
            $this->db->where('ios_token', $ios_token);
        } elseif ($android_token) {
            $this->db->where('token', $android_token);
        }
        $user_status = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_status->num_rows() > 0) {
            return $user_status->row_array();
        }
        return array();
    }

    function user_login($mobile, $password) {
        $this->db->where('status', 1);
        $this->db->where('delete_status', 1);
        $this->db->where('mobile', $mobile);
        $this->db->where('password', md5($password));
        $this->db->select('*');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = users.id), 0) as ratings");
        $user_login = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_login->num_rows() > 0) {
            $user_details = $user_login->row_array();
            $percentage = 0;
            if ($row['aadhar_card_verified'] == 'yes') {
                $percentage += 40;
            }
            if ($row['address_verified'] == 'yes') {
                $percentage += 20;
            }
            if ($row['mobile_verified'] == 'yes') {
                $percentage += 10;
            }
            if ($row['photo_verified'] == 'yes') {
                $percentage += 10;
            }

            if ($row['email_id_verified'] == 'yes') {
                $percentage += 10;
            }

            if ($row['office_email_id_verified'] == 'yes') {
                $percentage += 10;
            }


            $user_details['profile_percentage'] = $percentage;
            return $user_details;
        }
        return new ArrayObject();
    }

    function check_user_status($mobile) {
        $this->db->where('status', 1);
        $this->db->where('mobile', $mobile);
        $user_status = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_status->num_rows() > 0) {
            return $user_status->row_array();
        }
        return array();
    }

    function check_user_deleted($mobile) {
        $this->db->where('delete_status', 1);
        $this->db->where('mobile', $mobile);
        $user_status = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($user_status->num_rows() > 0) {
            return $user_status->row();
        }
        return array();
    }

    function update_user_device_token($user_id = NULL, $token = NULL, $ios_token = NULL) {
        $RecordData = array(
            'token' => $token,
            'ios_token' => $ios_token
        );
        $this->db->update('users', $RecordData, array('id' => $user_id));

        $this->db->where('id', $user_id);
        $users = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            return $users->row();
        }
    }

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

    function discussions($user_id, $class_id, $university_id, $count) {
        $this->db->order_by('discussions.id', 'desc');
        $this->db->where('discussions.delete_status', 1);
        $this->db->where('users.delete_status', 1);
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->where('discussions.class_id', $class_id);
        $this->db->where('discussions.university_id', $university_id);
        $this->db->join('users', 'users.id = discussions.user_id');
        $this->db->select('discussions.*, users.first_name, users.last_name, users.email_id, TIMESTAMPDIFF(MINUTE, discussions.created_on ,NOW()) as time');
        $this->db->select("(CASE WHEN TIMESTAMPDIFF(MINUTE, discussions.created_on ,NOW()) <= 1 THEN 'Just Now' WHEN TIMESTAMPDIFF(MINUTE, discussions.created_on ,NOW()) < 60 THEN concat(TIMESTAMPDIFF(MINUTE, discussions.created_on ,NOW()),' Minutes Ago') ELSE DATE_FORMAT(discussions.created_on, '%d %b %Y') END) as posted_on");
        $this->db->select("(SELECT COUNT(*) FROM `discussion_comments` where user_id = '$user_id' and class_id = '$class_id' and university_id = '$university_id' and discussion_id = discussions.id) as comments");
        $this->db->select("(SELECT COUNT(*) FROM `discussion_likes` where user_id = '$user_id' and class_id = '$class_id' and university_id = '$university_id' and discussion_id = discussions.id) as likes");
        $this->db->select("IF ((SELECT COUNT(*) FROM `discussion_comments` where user_id = '$user_id' and class_id = '$class_id' and university_id = '$university_id' and discussion_id = discussions.id) >0, 'yes', 'no') as commented");
        $this->db->select("IF ((SELECT COUNT(*) FROM `discussion_likes` where user_id = '$user_id' and class_id = '$class_id' and university_id = '$university_id' and discussion_id = discussions.id) >0, 'yes', 'no') as liked");
        $discussions = $this->db->get('discussions');
        //echo $this->db->last_query();exit;
        if ($discussions->num_rows() > 0) {
            return $discussions->result_array();
        }
        return array();
    }

    function discussion_likes_count($discussion_id) {
        $this->db->where('discussion_id', $discussion_id);
        $promo_codes = $this->db->get('discussion_likes');
        //echo $this->db->last_query();exit;
        return $promo_codes->num_rows();
    }

    function discussion_comments_count($discussion_id) {
        $this->db->where('discussion_id', $discussion_id);
        $promo_codes = $this->db->get('discussion_comments');
        //echo $this->db->last_query();exit;
        return $promo_codes->num_rows();
    }

    // function submit_like($RecordData = NULL)
    // {
    // 	if($RecordData)
    // 	{
    // 		$this->db->update('users', $RecordData, array('id' => $user_id));
    // 		if($this->db->affected_rows() > 0)
    // 		{
    // 			return true;
    // 		}
    // 		else
    // 		{
    // 			return false;
    // 		}
    // 	}
    // 	return false;
    // }

    function submit_like($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('discussion_likes', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            if ($insert_id > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function submit_comment($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('discussion_comments', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            if ($insert_id > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function discussion_comments($discussion_id, $count) {
        $this->db->order_by('discussion_comments.id', 'desc');
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->where('discussion_comments.discussion_id', $discussion_id);
        $this->db->join('users', 'users.id = discussion_comments.user_id');
        $this->db->select('discussion_comments.*, users.first_name, users.last_name, users.profile_pic');
        $discussion_comments = $this->db->get('discussion_comments');
        //echo $this->db->last_query();exit;
        if ($discussion_comments->num_rows() > 0) {
            return $discussion_comments->result_array();
        }
        return array();
    }

    function promo_codes($keyword = NULL) {
        $this->db->where('delete_status', 1);
        $this->db->order_by('id', 'desc');
        if ($keyword) {
            $this->db->like('code', $keyword, 'after');
        }
        $promo_codes = $this->db->get('promo_codes');
        //echo $this->db->last_query();exit;
        if ($promo_codes->num_rows() > 0) {
            return $promo_codes->result();
        }
        return array();
    }

    function community($user_id, $class_id, $count) {
        $this->db->order_by('users.id', 'desc');
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->where('users.delete_status', 1);
        $this->db->where('user_classes.user_id !=', $user_id);
        $this->db->where('user_classes.class_id', $class_id);
        $this->db->join('users', 'users.id = user_classes.user_id');
        $this->db->select('users.*');
        $this->db->select("IF ((SELECT count(`friends`.id) FROM `friends` JOIN `users` ON `users`.`id` = `friends`.`friend_id` JOIN `users` as `u` ON `u`.`id` = `friends`.`user_id` WHERE `friends`.`status` = 'accepted' AND (`friends`.`user_id` = users.id OR `friends`.`friend_id` = users.id) ORDER BY `friends`.`id` DESC) >0, 'yes', 'no') as friend");
        $this->db->select("IF ((SELECT count(`friends`.id) FROM `friends` JOIN `users` ON `users`.`id` = `friends`.`friend_id` JOIN `users` as `u` ON `u`.`id` = `friends`.`user_id` WHERE `friends`.`status` = 'pending' AND (`friends`.`user_id` = users.id OR `friends`.`friend_id` = users.id) ORDER BY `friends`.`id` DESC) >0, 'yes', 'no') as friend_request_sent");
        $this->db->select("IF ((SELECT count(`friends`.id) FROM `friends` JOIN `users` ON `users`.`id` = `friends`.`friend_id` JOIN `users` as `u` ON `u`.`id` = `friends`.`user_id` WHERE `friends`.`status` = 'ignored' AND (`friends`.`user_id` = users.id OR `friends`.`friend_id` = users.id) ORDER BY `friends`.`id` DESC) >0, 'yes', 'no') as friend_request_ignored");
        $this->db->distinct();
        $users = $this->db->get('user_classes');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            return $users->result_array();
        }
        return array();
    }

    function get_events($user_id, $university_id, $class_id, $event_type, $section, $count) {
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        if ($section == "rating") {
            $this->db->order_by('ratings', 'desc');
        } elseif ($section == "price") {
            $this->db->order_by('total_price', 'asc');
        } elseif ($section == "time") {
            $this->db->order_by('date_time', 'asc');
        } else {
            $this->db->order_by('events.id', 'desc');
        }
        $this->db->having('booked_count < events.max_group_size');
        $this->db->where('events.event_type', $event_type);
        //$this->db->where('events.user_id', $user_id);
        $this->db->where('events.university_id', $university_id);
        $this->db->where('events.class_id', $class_id);
        $this->db->join('users', 'users.id = events.user_id');
        $this->db->select('events.*, users.profile_pic, users.first_name, users.last_name');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = events.user_id), 0) as ratings");
        $this->db->select('(CASE WHEN per_session IS NULL THEN per_head * max_group_size ELSE per_session END) as total_price');
        $this->db->select('CONCAT(events.event_date, " ", events.start_time) AS date_time');
        $this->db->select('DATE_FORMAT(events.event_date, "%d %M %Y") AS date');
        $this->db->select('DATE_FORMAT(events.start_time, "%h %p") AS start_time');
        $this->db->select('DATE_FORMAT(events.end_time, "%h %p") AS end_time');
        $this->db->select("IFNULL((SELECT COUNT(id) FROM event_members where event_members.event_id = events.id and event_members.request_status = 'accepted'), 0) as booked_count");
        $events = $this->db->get('events');
        //echo $this->db->last_query();exit;
        if ($events->num_rows() > 0) {
            return $events->result_array();
        }
        return array();
    }

    function my_events($user_id, $event_type, $count) {
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->order_by('events.id', 'desc');
        $this->db->where('events.event_type', $event_type);
        $this->db->where('events.user_id', $user_id);
        $this->db->join('users', 'users.id = events.user_id');
        $this->db->select('events.*, users.profile_pic, users.first_name, users.last_name');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = events.user_id), 0) as ratings");
        $this->db->select('(CASE WHEN per_session IS NULL THEN per_head * max_group_size ELSE per_session END) as total_price');
        $this->db->select('CONCAT(events.event_date, " ", events.start_time) AS date_time');
        $this->db->select('DATE_FORMAT(events.event_date, "%d %M %Y") AS date');
        $this->db->select('DATE_FORMAT(events.start_time, "%h %p") AS start_time');
        $this->db->select('DATE_FORMAT(events.end_time, "%h %p") AS end_time');
        $this->db->select("IFNULL((SELECT COUNT(id) FROM event_members where event_members.event_id = events.id and event_members.request_status = 'accepted'), 0) as booked_count");
        $events = $this->db->get('events');
        //echo $this->db->last_query();exit;
        if ($events->num_rows() > 0) {
            return $events->result_array();
        }
        return array();
    }

    function my_pending_approvals($user_id, $event_type, $count) {
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->order_by('events.id', 'desc');
        $this->db->where('event_members.request_status', 'pending');
        $this->db->where('events.event_type', $event_type);
        $this->db->where('events.user_id', $user_id);
        $this->db->join('users', 'users.id = events.user_id');
        $this->db->join('event_members', 'event_members.event_id = events.id');
        $this->db->select('events.*, users.profile_pic, users.first_name, users.last_name, event_members.id as event_member_id');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = events.user_id), 0) as ratings");
        $this->db->select('(CASE WHEN per_session IS NULL THEN per_head * max_group_size ELSE per_session END) as total_price');
        $this->db->select('CONCAT(events.event_date, " ", events.start_time) AS date_time');
        $this->db->select('DATE_FORMAT(events.event_date, "%d %M %Y") AS date');
        $this->db->select('DATE_FORMAT(events.start_time, "%h %p") AS start_time');
        $this->db->select('DATE_FORMAT(events.end_time, "%h %p") AS end_time');
        $events = $this->db->get('events');
        //echo $this->db->last_query();exit;
        if ($events->num_rows() > 0) {
            return $events->result_array();
        }
        return array();
    }

    function event_details($user_id, $event_id, $count) {
        $this->db->where('events.id', $event_id);
        //$this->db->where('events.user_id', $user_id);
        $this->db->join('users', 'users.id = events.user_id');
        $this->db->select('events.*, users.profile_pic, users.first_name, users.last_name');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = events.user_id), 0) as ratings");
        $this->db->select('(CASE WHEN per_session IS NULL THEN per_head * max_group_size ELSE per_session END) as total_price');
        $this->db->select('CONCAT(events.event_date, " ", events.start_time) AS date_time');
        $this->db->select('DATE_FORMAT(events.event_date, "%d %M %Y") AS date');
        $this->db->select('DATE_FORMAT(events.start_time, "%h %p") AS start_time');
        $this->db->select('DATE_FORMAT(events.end_time, "%h %p") AS end_time');
        $this->db->select("(SELECT request_status FROM event_members where event_members.event_id = events.id and event_members.user_id = '$user_id') as my_book_status");
        $this->db->select("(CASE user_id WHEN $user_id THEN 'my_event' ELSE 'not_my_event' END) as event_created_by");
        $events = $this->db->get('events');
        //echo $this->db->last_query();exit;
        if ($events->num_rows() > 0) {
            $res = $events->row_array();
            $res['event_members'] = $this->get_event_members($res['id']);
            $res['event_comments'] = $this->get_event_comments($res['id'], $count);
            return $res;
        }
        return array();
    }

    function get_event_members($event_id) {
        $this->db->order_by('event_members.id', 'desc');
        $this->db->where('event_members.event_id', $event_id);
        $this->db->where('event_members.request_status', 'accepted');
        $this->db->join('users', 'users.id = event_members.user_id');
        $this->db->select('users.id, users.first_name, users.last_name, users.profile_pic');
        $event_members = $this->db->get('event_members');
        //echo $this->db->last_query();exit;
        if ($event_members->num_rows() > 0) {
            return $event_members->result_array();
        }
        return array();
    }

    function get_event_comments($event_id, $count) {
        $this->db->order_by('event_comments.id', 'desc');
        if ($count > 0) {
            $this->db->limit(10, $count);
        } else {
            $this->db->limit(10);
        }
        $this->db->where('event_comments.event_id', $event_id);
        $this->db->join('users', 'users.id = event_comments.user_id');
        $this->db->select('users.id as user_id, users.first_name, users.last_name, users.profile_pic, event_comments.comment, DATE_FORMAT(event_comments.created_on, "%d %M %Y %h:%i %p") AS created_on');
        $event_comments = $this->db->get('event_comments');
        //echo $this->db->last_query();exit;
        if ($event_comments->num_rows() > 0) {
            return $event_comments->result_array();
        }
        return array();
    }

    function check_event_availabilty($event_id) {
        $this->db->where('events.id', $event_id);
        $this->db->select('events.max_group_size');
        $this->db->select("IFNULL((SELECT COUNT(id) FROM event_members where event_members.event_id = events.id and event_members.request_status = 'accepted'), 0) as booked_count");
        $events = $this->db->get('events');
        //echo $this->db->last_query();exit;
        if ($events->num_rows() > 0) {
            $res = $events->row_array();
            if ($res['booked_count'] >= $res['max_group_size']) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    function check_for_ongoing_ride($user_id) {
        $date = date('Y-m-d H:i:s');
        $this->db->order_by('orders.ride_time', 'asc');
        $this->db->where("(((`orders`.`driver_status` = 'Not Started' AND `orders`.`ride_time` > ('$date' - INTERVAL 30 MINUTE) AND (`orders`.`ride_type` = 'later' OR orders.mode = 'outstation')) OR (`orders`.`driver_status` = 'Started')) OR (`orders`.`ride_type` = 'now'))");
        $this->db->where('orders.status !=', 'cancelled by user');
        $this->db->where('orders.status !=', 'cancelled by rider');
        $this->db->where('orders.status !=', 'completed');
        $this->db->where('orders.user_id', $user_id);
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        //$this->db->join('users r', 'r.id = orders.rider_id', 'left');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('orders.*');
        $query = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    function check_for_ongoing_ride_of_rider($user_id) {
        $this->db->order_by('rides.ride_time', 'asc');
        $this->db->where('rides.status !=', 'cancelled');
        $this->db->where('rides.status !=', 'completed');
        $this->db->where('rides.user_id', $user_id);
        $this->db->join('user_vehicles', 'user_vehicles.id = rides.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->select('rides.*');
        $query = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    function notifications($type) {
        $this->db->order_by('id', 'desc');
        $this->db->where('sent_to', $type);
        $notifications = $this->db->get('notifications');
        //echo $this->db->last_query();exit;
        if ($notifications->num_rows() > 0) {
            return $notifications->result();
        }
        return array();
    }

    function order_support($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('order_support', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return 0;
    }

    function search_classes($keyword) {
        $this->db->order_by('title', 'asc');
        $this->db->where('delete_status', 1);
        $this->db->like('title', $keyword, 'after');
        $classes = $this->db->get('classes');
        //echo $this->db->last_query();exit;
        if ($classes->num_rows() > 0) {
            return $classes->result_array();
        }
        return array();
    }

    function user_classes($user_id) {
        $this->db->order_by('classes.title', 'asc');
        $this->db->where('classes.delete_status', 1);
        $this->db->where('user_classes.user_id', $user_id);
        $this->db->join('classes', 'classes.id = user_classes.class_id');
        $classes = $this->db->get('user_classes');
        //echo $this->db->last_query();exit;
        if ($classes->num_rows() > 0) {
            return $classes->result_array();
        }
        return array();
    }

    // function payment_pending_order($user_id)
    // {
    // 	$this->db->limit(1);
    // 	$this->db->order_by('orders.id', 'desc');
    // 	$this->db->where('orders.status', 'Delivered');
    // 	$this->db->where('orders.payment_status', 'Pending');
    // 	$this->db->where('orders.user_id', $user_id);
    // 	$this->db->join('vehicle_types', 'vehicle_types.id = orders.vehicle_type_id', 'left');
    // 	$this->db->join('drivers', 'drivers.id = orders.driver_id', 'left');
    // 	$this->db->join('users', 'users.id = orders.user_id');
    // 	$this->db->select('orders.*, vehicle_types.*, drivers.name as driver_name, drivers.mobile as driver_mobile, drivers.email_id as driver_email_id, users.name as user_name, users.mobile as user_mobile, users.email_id as user_email_id, drivers.vehicle_name, drivers.profile_pic as driver_profile_pic, drivers.vehicle_image, orders.id as order_id');
    // 	$this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM driver_ratings where driver_ratings.driver_id = orders.driver_id) as ratings");
    // 	$order_details = $this->db->get('orders');
    // 	//echo $this->db->last_query();exit;
    // 	if($order_details->num_rows() > 0)
    // 	{
    // 		$res = $order_details->row();
    // 		if($res->driver_id != 0)
    // 		{
    // 			$travel_data = $this->driver_travel_data($res->driver_id);
    // 			$driver_current_location = $this->driver_current_location($res->driver_id);
    // 			if($travel_data->driving == "Later")
    // 			{
    // 				$get_distance_time = get_distance_time($driver_current_location->lat, $travel_data->pickup_lat, $driver_current_location->lng, $travel_data->pickup_lng);
    // 				$minutes = explode(" ", $get_distance_time['time']);
    // 				$seconds = $minutes[0] * 60;
    // 				$today = date('d F Y h:i:s A');
    // 				$date = date('d F Y h:i A', strtotime($today) + $seconds);
    // 				$res->driving = "Later";
    // 				$res->driving_time = $date;
    // 			}
    // 			else
    // 			{
    // 				$res->driving = "Now";
    // 				$res->driving_time = "";
    // 			}
    // 		}
    // 		$res->pickup_waiting_charge = 0;
    // 		$res->dropoff_waiting_charge = 0;
    // 		if($res->pickup_waiting > 0)
    // 		{
    // 			if($res->pickup_waiting > $res->free_time)
    // 			{
    // 				$res->pickup_waiting_charge = ($res->pickup_waiting - $res->free_time) * $res->waiting_charge;
    // 			}
    // 		}
    // 		if($res->dropoff_waiting > 0)
    // 		{
    // 			if($res->dropoff_waiting > $res->free_time)
    // 			{
    // 				$res->dropoff_waiting_charge = ($res->dropoff_waiting - $res->free_time) * $res->waiting_charge;
    // 			}
    // 		}
    // 		return $res;
    // 	}
    // 	return array();
    // }

    function payment_pending_order($user_id) {
        $this->db->limit(1);
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status !=', 'Cancelled by user');
        $this->db->where('orders.status !=', 'Cancelled by user');
        $this->db->where('orders.payment_status', 'Pending');
        $this->db->where('orders.user_id', $user_id);
        $this->db->select('orders.id, orders.status');
        $order_details = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($order_details->num_rows() > 0) {
            return $order_details->row();
        }
        return array();
    }

    function payment_history_credit($user_id) {
        $this->db->order_by('rides.id', 'desc');
        $this->db->where('rides.user_id', $user_id);
        $this->db->where('rides.status', 'completed');
        $this->db->select('rides.*');
        $this->db->select('IFNULL((SELECT SUM(amount) FROM orders where orders.ride_id = rides.id), 0) as amount');
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($rides->num_rows() > 0) {
            return $rides->result_array();
        }
        return array();
    }

    function payment_history_debit($user_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status', 'completed');
        $this->db->where('orders.user_id', $user_id);
        $this->db->select('orders.*');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function update_driver_device_token($driver_id = NULL, $token = NULL, $ios_token = NULL) {
        $RecordData = array(
            'token' => $token,
            'ios_token' => $ios_token
        );
        $this->db->update('drivers', $RecordData, array('id' => $driver_id));

        $this->db->where('id', $driver_id);
        $users = $this->db->get('drivers');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            return $users->row();
        }
    }

    function update_driver($RecordData = NULL, $driver_id = NULL) {
        if ($RecordData) {
            $this->db->update('drivers', $RecordData, array('id' => $driver_id));
            return true;
        }
        return false;
    }

    function send_friend_request($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('friends', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            //return $insert_id;
            return $this->friend_requests_sent($RecordData['user_id']);
        }
        return array();
    }

    function search_users($keyword, $user_id) {
        $where = "(`first_name` LIKE '$keyword%' OR ";
        $where .= "`last_name` LIKE '$keyword%' OR";
        $where .= "`email_id` LIKE '$keyword%')";

        $this->db->order_by('id', 'desc');
        $this->db->where('id !=', $user_id);
        $this->db->where($where);
        $users = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            return $users->result_array();
        }
        return array();
    }

    function search_friends($keyword, $user_id) {
        $where = "(`users`.`first_name` LIKE '$keyword%' OR ";
        $where .= "`users`.`email_id` LIKE '$keyword%' OR";
        $where .= "`users`.`last_name` LIKE '$keyword%' OR ";

        $where .= "`u`.`first_name` LIKE '$keyword%' OR ";
        $where .= "`u`.`email_id` LIKE '$keyword%' OR";
        $where .= "`u`.`last_name` LIKE '$keyword%')";

        $where2 = "(friends.user_id = '$user_id' OR ";
        $where2 .= "friends.friend_id = '$user_id')";

        $this->db->order_by('friends.id', 'desc');
        //$this->db->where('friends.user_id', $user_id);
        $this->db->where('friends.status', 'accepted');
        $this->db->where($where);
        //$this->db->where($where3);
        $this->db->where($where2);
        $this->db->join('users', 'users.id = friends.friend_id');
        $this->db->join('users as u', 'u.id = friends.user_id');
        $this->db->select('friends.*');
        $users = $this->db->get('friends');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            $res = $users->result();
            $array = json_decode(json_encode($res), True);
            //echo $user_id;
            //echo "<pre>";print_r($array);exit;
            $result1 = $this->myfunction($array, 'user_id', $user_id);
            $result2 = $this->myfunction($array, 'friend_id', $user_id);
            $final_result = array_map("unserialize", array_unique(array_map("serialize", array_merge($result1, $result2))));
            return $final_result;
        }
        return array();
    }

    function myfunction($products, $field, $value) {
        $user_details = array();
        foreach ($products as $key => $product) {
            //echo $product[$field];
            if ($product[$field] != $value) {
                //echo $value;
                $user_details[] = user_by_id($product[$field]);
            }
        }
        //echo "<pre>";print_r(array_unique(json_decode(json_encode($user_details), true)));
        return array_unique(json_decode(json_encode($user_details), true));
    }

    function friend_requests($user_id) {
        $this->db->order_by('friends.id', 'desc');
        $this->db->where('friend_id', $user_id);
        $this->db->where('friends.status', 'pending');
        $this->db->join('users', 'users.id = friends.user_id');
        $this->db->distinct();
        $this->db->select('users.*, friends.*');
        $users = $this->db->get('friends');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            return $users->result();
        }
        return array();
    }

    function friend_requests_sent($user_id) {
        $this->db->order_by('friends.id', 'desc');
        $this->db->where('friends.user_id', $user_id);
        $this->db->where('friends.status', 'pending');
        $this->db->join('users', 'users.id = friends.friend_id');
        $this->db->distinct();
        $this->db->select('users.*, friends.*');
        $friends = $this->db->get('friends');
        //echo $this->db->last_query();exit;
        if ($friends->num_rows() > 0) {
            return $friends->result();
        }
        return array();
    }

    function my_friends($user_id) {
        $where2 = "(friends.user_id = '$user_id' OR ";
        $where2 .= "friends.friend_id = '$user_id')";

        $this->db->order_by('friends.id', 'desc');
        //$this->db->where('friends.user_id', $user_id);
        $this->db->where('friends.status', 'accepted');
        //$this->db->where($where);
        //$this->db->where($where3);
        $this->db->where($where2);
        $this->db->join('users', 'users.id = friends.friend_id');
        $this->db->join('users as u', 'u.id = friends.user_id');
        $this->db->select('friends.*');
        $users = $this->db->get('friends');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            $res = $users->result();
            $array = json_decode(json_encode($res), True);
            //echo $user_id;
            //echo "<pre>";print_r($array);exit;
            $result1 = $this->myfunction($array, 'user_id', $user_id);
            $result2 = $this->myfunction($array, 'friend_id', $user_id);
            $final_result = array_map("unserialize", array_unique(array_map("serialize", array_merge($result1, $result2))));
            // echo "<pre>";print_r($result1));
            // echo "<pre>";print_r($result2);exit;
            return array_merge($result1, $result2);
        }
        return array();
    }

    function accept_friend_request($RecordData = NULL, $user_id, $friend_id) {
        if ($RecordData) {
            $this->db->where('user_id', $user_id);
            $this->db->where('friend_id', $friend_id);
            $this->db->update('friends', $RecordData);
            //echo $this->db->last_query();exit;

            return $this->my_friends($user_id);
        }
        return array();
    }

    function add_story($RecordData = NULL) {
        if ($RecordData) {
            $this->db->insert('stories', $RecordData);
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            //return $insert_id;

            return $this->stories($RecordData['user_id']);
        }
        return array();
    }

    function stories($user_id) {
        $this->db->order_by('stories.id', 'desc');
        //$this->db->where('users.id', $user_id);
        $this->db->where("TIMESTAMPDIFF(MINUTE, stories.created_on ,NOW()) <= 1440");
        $this->db->join('users', 'users.id = stories.user_id');
        //$this->db->distinct();
        $this->db->select('users.*, stories.*, stories.id as story_id, TIMESTAMPDIFF(MINUTE, stories.created_on ,NOW()) as time');
        $stories = $this->db->get('stories');
        //echo $this->db->last_query();exit;
        if ($stories->num_rows() > 0) {
            $res = $stories->result();
            //echo "<pre>";print_r($res);
            foreach ($res as $key => $row) {
                //echo $row->user_id;
                $posted_time = "";
                if ($row->time <= 1) {
                    $posted_time = "Just Now";
                } elseif ($row->time < 60) {
                    $posted_time = $row->time . " Minutes Ago";
                } else {
                    $day = relative_date(strtotime($row->created_on));
                    $posted_time = $day . ', ' . date('h:i A', strtotime($row->created_on));
                }
                $res[$key]->posted_time = $posted_time;
                $in_contacts = $this->my_friends($row->user_id);
                //echo $this->db->last_query();
                $array = json_decode(json_encode($in_contacts), True);
                //echo "<pre>";print_r($array);
                if ($row->privacy == "public") {
                    return $res;
                } elseif (in_array($user_id, array_column($array, 'friend_id'))) {
                    return $res;
                }
            }
        }
        return array();
    }

    function check_user_in_friends($user_id) {
        //$this->db->join('friends', 'friends.id = stories.user_id');
        //$this->db->distinct();
        $this->db->select('stories.id');
        $stories = $this->db->get('stories');
        //echo $this->db->last_query();exit;
        if ($stories->num_rows() > 0) {
            return true;
        }
        return false;
    }

    function user_ratings($user_id) {
        $this->db->where('users.id', $user_id);
        $this->db->select('users.*');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = '$user_id') as ratings");
        $users = $this->db->get('users');
        //echo $this->db->last_query();exit;
        if ($users->num_rows() > 0) {
            $res = $users->row_array();
            $res['credentials'] = $this->get_credentials($user_id);
            $res['reviews'] = $this->get_reviews($user_id);
            $res['rating_details'] = $this->get_rating_details($user_id);
            return $res;
        }
        return array();
    }

    function get_credentials($user_id) {
        $this->db->where('user_credentials.user_id', $user_id);
        $this->db->select('user_credentials.*');
        $user_credentials = $this->db->get('user_credentials');
        //echo $this->db->last_query();exit;
        if ($user_credentials->num_rows() > 0) {
            return $user_credentials->result_array();
        }
        return array();
    }

    function get_reviews($user_id) {
        $this->db->where('user_ratings.user_id', $user_id);
        $this->db->select('users.first_name, users.last_name, users.email_id, users.major, users.profile_pic, user_ratings.*');
        $this->db->join('users', 'users.id = user_ratings.user_id');
        $user_ratings = $this->db->get('user_ratings');
        //echo $this->db->last_query();exit;
        if ($user_ratings->num_rows() > 0) {
            return $user_ratings->result_array();
        }
        return array();
    }

    function get_rating_details($user_id) {
        $this->db->select('COALESCE(SUM(CASE WHEN rat.ratings=1 THEN 1 ELSE 0 END),0) AS rate1, COALESCE(SUM(CASE WHEN rat.ratings=2 THEN 1 ELSE 0 END),0) AS rate2, COALESCE(SUM(CASE WHEN rat.ratings=3 THEN 1 ELSE 0 END),0) AS rate3,COALESCE(SUM(CASE WHEN rat.ratings=4 THEN 1 ELSE 0 END),0) AS rate4,COALESCE(SUM(CASE WHEN rat.ratings=5 THEN 1 ELSE 0 END),0) AS rate5');

        $this->db->select('(COALESCE(SUM(CASE WHEN rat.ratings=1 THEN 1 ELSE 0 END),0)/COUNT(id))*100 AS rate1_ps');
        $this->db->select('(COALESCE(SUM(CASE WHEN rat.ratings=2 THEN 1 ELSE 0 END),0)/COUNT(id))*100 AS rate2_ps');
        $this->db->select('(COALESCE(SUM(CASE WHEN rat.ratings=3 THEN 1 ELSE 0 END),0)/COUNT(id))*100 AS rate3_ps');
        $this->db->select('(COALESCE(SUM(CASE WHEN rat.ratings=4 THEN 1 ELSE 0 END),0)/COUNT(id))*100 AS rate4_ps');
        $this->db->select('(COALESCE(SUM(CASE WHEN rat.ratings=5 THEN 1 ELSE 0 END),0)/COUNT(id))*100 AS rate5_ps');

        $this->db->where('rat.user_id', $user_id);
        $rating_details = $this->db->get('user_ratings as rat');
        if ($rating_details->num_rows() > 0) {
            return $rating_details->result_array();
        }
        return array();
    }

    function chats($user_id) {
        return $this->db->query("SELECT t1.*, DATE_FORMAT(t1.created_on, '%d %M %Y %h:%i %p') AS created_on, users.first_name, users.last_name, users.profile_pic, users.bio, users.email_id, users.mobile FROM chats AS t1 join users on users.id=(CASE WHEN t1.sender_id = '$user_id' THEN t1.receiver_id WHEN t1.receiver_id = '$user_id' THEN t1.sender_id END)
        INNER JOIN
        (
            SELECT
                LEAST(sender_id, receiver_id) AS sender_id,
                GREATEST(sender_id, receiver_id) AS receiver_id,
                MAX(id) AS max_id
            FROM chats
            GROUP BY
                LEAST(sender_id, receiver_id),
                GREATEST(sender_id, receiver_id)
        ) AS t2
            ON LEAST(t1.sender_id, t1.receiver_id) = t2.sender_id AND
               GREATEST(t1.sender_id, t1.receiver_id) = t2.receiver_id AND
               t1.id = t2.max_id
            WHERE t1.sender_id = $user_id OR t1.receiver_id = $user_id")->result_array();
    }

    function search_chats($user_id, $keyword) {
        return $this->db->query("SELECT t1.*, DATE_FORMAT(t1.created_on, '%d %M %Y %h:%i %p') AS created_on, users.first_name, users.last_name, users.profile_pic, users.major, users.email_id, users.mobile FROM chats AS t1 join users on users.id=t1.sender_id
        INNER JOIN
        (
            SELECT
                LEAST(sender_id, receiver_id) AS sender_id,
                GREATEST(sender_id, receiver_id) AS receiver_id,
                MAX(id) AS max_id
            FROM chats
            GROUP BY
                LEAST(sender_id, receiver_id),
                GREATEST(sender_id, receiver_id)
        ) AS t2
            ON LEAST(t1.sender_id, t1.receiver_id) = t2.sender_id AND
               GREATEST(t1.sender_id, t1.receiver_id) = t2.receiver_id AND
               t1.id = t2.max_id
            WHERE t1.sender_id = 1 OR t1.receiver_id = 1 AND (`users`.`first_name` LIKE '%$keyword%' OR `users`.`last_name` LIKE '%$keyword%')")->result_array();
    }

    public function chat_scroll_data($order_id, $booking_id, $sender_id, $receiver_id, $count) {
        if ($count > 0) {
            $this->db->limit(500, $count * 500);
        } else {
            $this->db->limit(500);
        }
        $where = "((`chats`.`sender_id` = '$sender_id' AND `chats`.`receiver_id` = '$receiver_id') OR (`chats`.`sender_id` = '$receiver_id' AND `chats`.`receiver_id` = '$sender_id'))";
        $this->db->order_by('id', 'desc');
        $this->db->where('chats.order_id', $order_id);
        $this->db->where('chats.booking_id', $booking_id);
        $this->db->where($where);
        // $this->db->group_start();
        // $this->db->where('chats.sender_id',$sender_id);
        // $this->db->where('chats.receiver_id',$receiver_id);
        // $this->db->group_end();
        // $this->db->or_group_start();
        // $this->db->where('chats.sender_id',$receiver_id);
        // $this->db->where("chats.receiver_id",$sender_id);
        // $this->db->group_end();
        $this->db->join('users', 'users.id = chats.sender_id');
        $this->db->select('chats.*, users.profile_pic, users.first_name, users.last_name');
        $this->db->select("(CASE sender_id WHEN $sender_id THEN 'sender' ELSE 'receiver' END) as user_type");
        $chats = $this->db->get('chats');
        //echo $this->db->last_query();exit;
        if ($chats->num_rows() > 0) {
            return $chats->result_array();
        }
        return array();
    }

    function get_chat_data($sender_id, $receiver_id) {
        $this->db->order_by('id', 'asc');
        $this->db->group_start();
        $this->db->where('chats.sender_id', $sender_id);
        $this->db->where('chats.receiver_id', $receiver_id);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('chats.sender_id', $receiver_id);
        $this->db->where("chats.receiver_id", $sender_id);
        $this->db->group_end();
        $this->db->join('users', 'users.id = chats.sender_id');
        $this->db->select('chats.*, users.profile_pic, users.first_name, users.last_name');
        $this->db->select("(CASE sender_id WHEN $sender_id THEN 'sender' ELSE 'receiver' END) as user_type");
        $chats = $this->db->get('chats');
        //echo $this->db->last_query();exit;
        return $chats->num_rows();
    }

    function get_estimation($data) {
        $get_distance_time = get_distance_time($data['pickup_lat'], $data['dropoff_lat'], $data['pickup_lng'], $data['dropoff_lng']);
        $minutes = explode(" ", $get_distance_time['time']);
        //var_dump($minutes);
        if (in_array("hours", $minutes) && in_array("mins", $minutes)) {
            $seconds = ($minutes[0] * 3600) + ($minutes[2] * 60);
        } else {
            $seconds = $minutes[0] * 60;
        }

        $this->db->where('id', $data['vehicle_type_id']);
        $vehicle_types = $this->db->get('vehicle_types');
        //echo $this->db->last_query();exit;
        $vtypes = $vehicle_types->row();

        $distance = explode(" ", $get_distance_time['distance']);
        //var_dump($distance);
        if ($distance[1] == "m") {
            $km = $distance[0] / 1000;
        } else {
            $km = $distance[0];
        }
        //echo $km;
        $a = $km;
        $b = str_replace(',', '', $a);

        if (is_numeric($b)) {
            $km = $b;
        }
        //echo $km;
        $miles = round($km * 0.621371, 2);
        $firstmilecost = 0;
        if ($miles >= 10) {
            $firstmilecost = $vtypes->upto_10_miles * 10;
        }
        $secondmilecost = 0;
        if ($miles >= 30) {
            $secondmilecost = $vtypes->second_20_miles * 20;
        } else {
            $secondmilecost = $vtypes->second_20_miles * ($miles - 10);
        }
        $thirdmilecost = 0;
        if ($miles >= 50) {
            $thirdmilecost = $vtypes->third_20_miles * 20;
        } else {
            $thirdmilecost = $vtypes->third_20_miles * ($miles - 30);
        }
        $restmilecost = 0;
        if ($miles > 50) {
            $rest_miles = $miles - 50;
            $restmilecost = $vtypes->after_50_miles * $rest_miles;
        }
        //echo $restmilecost;
        $mile_fare = $firstmilecost + $secondmilecost + $thirdmilecost + $restmilecost;

        $final_cost = $mile_fare + $vtypes->base_fare + $vtypes->operation_fees + $vtypes->minimum_fare;

        $today = date("Y-m-d H:i:s");
        if ($miles >= 35) {
            $mode = "Travel Mode";
            $today = date("Y-m-d H:i:s", strtotime('+12 hours'));
            //echo $today = date('d F Y h:i:s A', time() + 10800);exit;
        } else {
            $mode = "City Mode";
            $today = date("Y-m-d H:i:s", strtotime('+4 hours'));
            //echo $today = date('d F Y h:i:s A', time() + 10800);exit;
        }
        $date = date('d F Y h:i A', strtotime($today) + $seconds);

        $result = array(
            'estimated_time' => $date,
            'estimated_cost' => round($final_cost),
            'mode' => $mode,
        );
        if ($miles < 10) {
            return array();
        }
        return $result;
    }

    function check_order_status($order_id, $user_id) {
        //echo "test";
        $this->db->where('orders.id', $order_id);
        $this->db->where('orders.user_id', $user_id);
        $this->db->where('orders.rider_id !=', 0);
        $this->db->join('users r', 'r.id = orders.rider_id');
        $this->db->select('orders.*, r.first_name as r_first_name, r.last_name as r_last_name, r.mobile as r_mobile, r.profile_pic as r_profile_pic, r.gender as rider_gender');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.rider_id) as r_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return array();
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

    function find_near_by_riders($data, $order_id, $ride_type, $mode, $filter) {
        //echo "test";exit;
        //$where  = "(orders.status != 'accepted' AND orders.status = 'started')";
        $this->db->where('orders.status', 'pending');
        $this->db->where('orders.mode', $mode);
        $this->db->where('orders.id', $order_id);
        $this->db->where('orders.user_id', $data['user_id']);
        $this->db->where('orders.rider_id', 0);
        $this->db->select('orders.*');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            $res = $orders->row_array();
           //echo '<pre>'; print_r($res);
            return $first_scenario = $this->check_within_source_dest($res['from_lat'], $res['from_lng'], $res['to_lat'], $res['to_lng'], $ride_type, $mode, $res['ride_time'], $order_id, $filter, $res['gender'], $res['seats_required'], $res['vehicle_type']);

             //echo '<pre>';print_r($first_scenario);exit;
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
          // echo '<pre>';print_r($res);exit;
            if (!empty($res)) {
                $data = array();
                foreach ($res as $key => $row) {
                    $res[$key]['order_id'] = $order_id;
                    $this->db->where('ride_id', $row['id']);
                    $this->db->select('ride_lat_lngs.*');
                    $orders = $this->db->get('ride_lat_lngs');
                    //echo $this->db->last_query();
                    //echo $orders->num_rows();exit;
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
                        //echo $rider_current_location->num_rows();
                        if ($rider_current_location->num_rows() > 0) {
                            $rcl = $rider_current_location->row_array();
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
                    //echo '<pre>';print_r($distance1);exit;
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
                    //echo "km1 ".$km1."<br>"."km2 ".$km2."<br>"."km3 ".$km3."<br>"."km4 ".$km4."<br>";exit;
                    if ($km1 <= $km2) {
                        $response = PolyUtil::isLocationOnPath($first, $second, $tolerance);
                        $response_other = PolyUtil::isLocationOnPath($first_other, $second, $tolerance);
                        $first_scenario = preg_replace('/\s+/', '', $response);
                        $second_scenario = preg_replace('/\s+/', '', $response_other);
                        //echo $row['user_id'];
                        if ($first_scenario == true && $second_scenario == true) {
                            if ($ride_type == "now" && $mode == "city") {
                                //echo "string";exit;
                                //echo $row['user_id'];
                                $this->send_push_notification('You have a new booking!', 'driver', $row['user_id'], 'new_order', $order_id);
                            }

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

                            $user_details = $this->user_details($row['user_id']);
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
                return $data;
            }
            return array();
        }
    }

    function rider_users_list($ride_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.ride_id', $ride_id);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('users.first_name, users.last_name, users.mobile, users.profile_pic, users.gender, orders.seats_required');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            $res = $orders->result_array();
            return $res;
        }
    }

    function rider_orders($user_id) {
        $now = date('Y-m-d H:i:s');
        $this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->where('rides.user_id', $user_id);
        $this->db->where('rides.ride_time >', $now);
        //$this->db->having('time_diff <=', 30);
        //$this->db->where('ride_type', 'now');
        $this->db->where('seats_available >', 'capacity_booked');
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->select('rides.*');
        $this->db->select("TIMESTAMPDIFF(MINUTE, rides.ride_time, '$now') as time_diff");
        $this->db->select("(SELECT IFNULL(SUM(seats_required), 0) FROM `orders` where orders.ride_id = rides.id) as capacity_booked");
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($rides->num_rows() > 0) {
            $res = $rides->row_array();
            //print_r($res);exit;
            $order_details = $this->get_rider_orders($res['id'], $res['from_lat'], $res['from_lng'], $res['to_lat'], $res['to_lng'], $user_id, $res['vehicle_id'], $res['mode'], $res['ride_type'], $res['ride_time']);
            return $order_details;
        }
    }

    function get_rider_orders($id, $from_lat, $from_lng, $to_lat, $to_lng, $user_id, $vehicle_id, $mode, $ride_type, $ride_time) {
        //echo "test";
        $this->db->limit(1);
        $this->db->order_by('rand()');
        $this->db->having('declined_count', 0);
        $this->db->where('orders.mode', $mode);
        $this->db->where('orders.ride_type', $ride_type);
        if ($ride_type == "later") {
            $this->db->where('orders.request_id', $user_id);
        }
        $this->db->where('orders.ride_time >', $ride_time);
        $this->db->where('orders.rider_id', 0);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('users.*, orders.*, orders.id as order_id');
        $this->db->select("(SELECT count(id) FROM declined_orders where declined_orders.user_id = '$user_id' and declined_orders.order_id = orders.id) as declined_count");
        //$this->db->select("(SELECT count(id) FROM declined_orders where declined_orders.user_id = '$user_id' and declined_orders.order_id = orders.id) as declined_count");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            $res = $orders->row_array();
            $res['ride_id'] = $id;
            $res['rider_id'] = $user_id;
            $res['vehicle_id'] = $vehicle_id;
            //print_r($res);exit;

            $this->db->where('ride_id', $id);
            $this->db->select('ride_lat_lngs.*');
            $ride_lat_lngs = $this->db->get('ride_lat_lngs');
            //echo $this->db->last_query();
            //echo $ride_lat_lngs->num_rows();
            $result = array();
            if ($ride_lat_lngs->num_rows() > 0) {
                $result = $ride_lat_lngs->result_array();
            }
            $first = array('lat' => $from_lat, 'lng' => $from_lng);
            $first_other = array('lat' => $to_lat, 'lng' => $to_lng);

            $second = array();
            if (!empty($result)) {
                foreach ($result as $row) {
                    $second[] = array(
                        'lat' => $row['lat'],
                        'lng' => $row['lng']
                    );
                }
            }
            //print_r($first);
            //echo $order_lat;
            $response = PolyUtil::isLocationOnPath($first, $second);
            $response_other = PolyUtil::isLocationOnPath($first_other, $second);
            $first_scenario = preg_replace('/\s+/', '', $response);
            $second_scenario = preg_replace('/\s+/', '', $response_other);
            if ($first_scenario == true && $second_scenario == true) {
                return $res;
            }
        }
        //echo "outside";exit;
        return array();
    }

    function users_accepted_order($user_id) {
        //echo "test";
        $where = "(orders.status = 'accepted' OR orders.status = 'started')";
        $this->db->limit(1);
        $this->db->order_by('orders.id', 'desc');
        $this->db->where($where);
        $this->db->where('orders.ride_id !=', 0);
        $this->db->where('orders.user_id', $user_id);
        $this->db->join('users', 'users.id = orders.rider_id');
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->select('users.*, user_vehicles.*, vehicle_makes.*, vehicle_makes.title as vehicle_make, vehicle_models.*, vehicle_models.title as vehicle_model, orders.*, orders.id as order_id');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.rider_id) as r_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            $user_details = $orders->row_array();
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
    }

    function users_payment_pending_order($user_id) {
        //echo "test";
        $this->db->limit(1);
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status', 'completed');
        $this->db->where('orders.user_id', $user_id);
        $this->db->where('orders.payment_status', 'pending');
        $this->db->join('users', 'users.id = orders.rider_id');
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->select('users.*, user_vehicles.*, vehicle_makes.*, vehicle_models.*, orders.*, orders.id as order_id');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.rider_id) as r_ratings");
        $this->db->select("IF ((SELECT COUNT(*) FROM `user_ratings` where user_ratings.user_id = orders.rider_id and user_ratings.order_id = orders.id) >0, 'yes', 'no') as rating_given");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return array();
    }

    function users_last_completed_order($user_id) {
        //echo "test";
        $this->db->limit(1);
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status', 'completed');
        $this->db->where('orders.payment_status', 'paid');
        $this->db->join('users', 'users.id = orders.rider_id');
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->select('users.*, user_vehicles.*, vehicle_makes.*, vehicle_models.*, orders.*, orders.id as order_id');
        $this->db->select("(SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.rider_id) as r_ratings");
        $this->db->select("IF ((SELECT COUNT(*) FROM `user_ratings` where user_ratings.user_id = orders.rider_id and user_ratings.order_id = orders.id) >0, 'yes', 'no') as rating_given");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
    }

    function user_cancelled_orders($user_id) {
        //echo "test";
        //$this->db->limit(1);
        $where2 = "(orders.status = 'cancelled by user' OR ";
        $where2 .= "orders.status = 'cancelled by vendor')";

        $this->db->order_by('orders.id', 'desc');
        $this->db->where($where2);
        $this->db->where('orders.user_id', $user_id);
        $this->db->join('users', 'users.id = orders.rider_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->select('users.first_name, users.last_name, users.profile_pic, orders.*, orders.id as order_id, vehicle_models.title as model, vehicle_makes.title as vehicle_make');
        $orders = $this->db->get('orders');
      //  echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function rides_offered($user_id) {
        $now = date('Y-m-d H:i:s');
        //$this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->where('rides.user_id', $user_id);
        //$this->db->where('rides.ride_time >', $now);
        //$this->db->having('time_diff <=', 30);
        //$this->db->where('ride_type', 'now');
        //$this->db->where('seats_available >', 'capacity_booked');
        $this->db->join('user_vehicles', 'user_vehicles.id = rides.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->select('users.first_name, users.last_name, users.profile_pic, rides.*, vehicle_models.title as model, vehicle_makes.title as vehicle_make');
        $this->db->select("TIMESTAMPDIFF(MINUTE, rides.ride_time, '$now') as time_diff");
        $this->db->select("(SELECT IFNULL(SUM(seats_required), 0) FROM `orders` where orders.ride_id = rides.id) as capacity_booked");
        $this->db->select("IF ((SELECT COUNT(*) FROM `orders` where orders.ride_id = rides.id) >0, 'yes', 'no') as got_orders");
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($rides->num_rows() > 0) {
            $res = $rides->result_array();
            foreach ($res as $key => $row) {
                $orders = $this->get_all_ride_orders($row['id']);
                $res[$key]['ride_amount_calculations'] = $this->get_ride_amount_calculations($row['id']);
                $res[$key]['orders'] = $orders;
            }
            //print_r($res);exit;
            return $res;
        } else {
            return array();
        }
    }

    function rides_offered_later($user_id) {
        $now = date('Y-m-d H:i:s');
        $where = "(rides.ride_type = 'later' OR rides.mode = 'outstation')";
        $where_status = "(rides.status = 'ongoing' OR rides.status = 'completed')";
        //$this->db->limit(1);
        $this->db->order_by('id', 'desc');
        $this->db->where('rides.user_id', $user_id);
        //$this->db->having('time_diff <=', 30);
        $this->db->where($where);
        //$this->db->where('rides.status', 'ongoing');
        $this->db->where($where_status);
        $this->db->join('user_vehicles', 'user_vehicles.id = rides.vehicle_id');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id');
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->select('users.first_name, users.last_name, users.profile_pic, rides.*, vehicle_models.title as model, vehicle_makes.title as vehicle_make');
        $this->db->select("TIMESTAMPDIFF(MINUTE, rides.ride_time, '$now') as time_diff");
        $this->db->select("(SELECT IFNULL(SUM(seats_required), 0) FROM `orders` where orders.ride_id = rides.id) as capacity_booked");
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        if ($rides->num_rows() > 0) {
            $res = $rides->result_array();
            foreach ($res as $key => $row) {
                $orders = $this->get_all_ride_orders($row['id']);
                $res[$key]['ride_amount_calculations'] = $this->get_ride_amount_calculations($row['id']);
                $res[$key]['orders'] = $orders;
            }
            //print_r($res);exit;
            return $res;
        } else {
            return array();
        }
    }

    function get_ride_orders($order_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status', "accepted");
        $this->db->where('orders.ride_id', $order_id);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('users.*, users.gender as user_gender, orders.*');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.user_id), 0) as u_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function get_all_ride_orders($order_id) {
        $this->db->order_by('orders.id', 'desc');
        //$this->db->where('orders.status', "accepted");
        $this->db->where('orders.ride_id', $order_id);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('users.*, users.gender as user_gender, orders.*');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.user_id), 0) as u_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function get_ride_amount_calculations($order_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.ride_id', $order_id);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('orders.amount, orders.base_fare, orders.seats_required');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.user_id), 0) as u_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            $res = $orders->result_array();
            $amount = 0;
            foreach ($res as $row) {
                $amount += ($row['amount'] + $row['base_fare']) * $row['seats_required'];
            }
            return $amount;
        }
        return 0;
    }

    // function get_all_ride_orders($ride_id)
    // {
    // 	$this->db->order_by('orders.id', 'desc');
    // 	$this->db->where('orders.ride_id', $order_id);
    // 	$this->db->join('users', 'users.id = orders.user_id');
    // 	$this->db->select('users.*, orders.*');
    // 	$this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.user_id), 0) as u_ratings");
    // 	$orders = $this->db->get('orders');
    // 	//echo $this->db->last_query();exit;
    // 	if($orders->num_rows() > 0)
    // 	{
    // 		return $orders->result_array();
    // 	}
    // 	return array();
    // }

    function rides_requested($user_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status !=', 'pending');
        $this->db->where('orders.user_id', $user_id);
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        //$this->db->join('users r', 'r.id = orders.rider_id', 'left');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->select('user_vehicles.*, users.*, orders.*, vehicle_models.title as vehicle_model, vehicle_makes.title as vehicle_make');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function check_order_started($order_id) {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.status !=', 'pending');
        $this->db->where('orders.status !=', 'accepted');
        //$this->db->where('orders.status !=', 'cancelled by user');
        //$this->db->where('orders.status !=', 'cancelled by rider');
        $this->db->where('orders.id', $order_id);
        $this->db->select('orders.id');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return new ArrayObject();
    }

    function rides_requested_later($user_id) {
        $where = "(orders.ride_type = 'later' OR orders.mode = 'outstation')";
        $where_status = "(orders.status = 'pending' OR orders.status = 'accepted' OR orders.status = 'completed' OR orders.status = 'started')";
        $this->db->order_by('orders.id', 'desc');
        $this->db->where($where);
        $this->db->where($where_status);
        $this->db->where('orders.ride_id !=', 0);
        $this->db->where('orders.user_id', $user_id);
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id', 'left');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->join('rides', 'rides.id = orders.ride_id', 'left');
        $this->db->join('users r', 'r.id = orders.rider_id', 'left');
        $this->db->select('user_vehicles.*, users.*, orders.*, vehicle_models.title as vehicle_model, vehicle_makes.title as vehicle_make, rides.ride_time as rider_start_time, r.first_name as rider_first_name, r.last_name as rider_last_name, r.mobile as rider_mobile');
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function order_details($order_id) {
        //echo "test";
        //$where  = "(orders.status = 'accepted' OR orders.status = 'started')";
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.id', $order_id);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->join('users r', 'r.id = orders.rider_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id = orders.vehicle_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id', 'left');
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        $this->db->select('users.*, users.gender as user_gender, orders.*, orders.id as order_id, vehicle_makes.title as vehicle_make, vehicle_models.title as vehicle_model, user_vehicles.car_type, user_vehicles.color, user_vehicles.year, user_vehicles.number_plate, r.first_name as r_first_name, r.last_name as r_last_name, r.userid as r_userid');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.user_id), 0) as ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit; r_first_name
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->row_array();
        }
        return new ArrayObject();
    }

    function ride_details($ride_id) {
        //echo "test";
        $this->db->where('rides.id', $ride_id);
        //$this->db->where('rides.user_id', $user_id);
        //$this->db->where('rides.rider_id !=', 0);
        $this->db->join('users', 'users.id = rides.user_id');
        $this->db->select('users.*, rides.*, rides.id as ride_id');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = rides.user_id), 0) as r_ratings");
        $rides = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        //echo $rides->num_rows();
        if ($rides->num_rows() > 0) {
            $row = $rides->row_array();
            $orders = $this->get_all_ride_orders($row['id']);
            $row['orders'] = $orders;
            return $row;
        }
        return new ArrayObject();
    }

    function rides_started($rider_id, $status) {
        //echo "test";
        $this->db->group_by('orders.id');
        $this->db->where('orders.rider_id', $rider_id);
        $this->db->where('orders.status', $status);
        $this->db->join('users', 'users.id = orders.user_id');
        $this->db->join('rides', 'rides.user_id = orders.rider_id');
        $this->db->select('users.*, orders.*, orders.id as order_id, rides.from_lat as ride_from_lat, rides.from_lng as ride_from_lng, rides.to_lat as ride_to_lat, rides.to_lng as ride_to_lng');
        $this->db->select("IFNULL((SELECT cast(AVG(ratings) as decimal(6,1)) FROM user_ratings where user_ratings.user_id = orders.rider_id), 0) as r_ratings");
        $orders = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        //echo $orders->num_rows();
        if ($orders->num_rows() > 0) {
            return $orders->result_array();
        }
        return array();
    }

    function user_vehicles($user_id) {
        $this->db->order_by('user_vehicles.id', 'desc');
        $this->db->where('user_vehicles.user_id', $user_id);
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id', 'left');
        $this->db->select('user_vehicles.*, vehicle_models.title as vehicle_model, vehicle_makes.title as vehicle_make');
        $user_vehicles = $this->db->get('user_vehicles');
        //echo $this->db->last_query();exit;
        if ($user_vehicles->num_rows() > 0) {
            return $user_vehicles->result_array();
        }
        return array();
    }

    function user_vehicles_count($user_id, $vehicle_type) {
        $this->db->order_by('user_vehicles.id', 'desc');
        $this->db->where('user_vehicles.user_id', $user_id);
        $this->db->where('user_vehicles.vehicle_type', $vehicle_type);
        $this->db->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id', 'left');
        $this->db->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id', 'left');
        $this->db->select('user_vehicles.*, vehicle_models.title as vehicle_model, vehicle_makes.title as vehicle_make');
        $user_vehicles = $this->db->get('user_vehicles');
        //echo $this->db->last_query();exit;
        return $user_vehicles->num_rows();
    }

    function ratings($user_id) {
        $this->db->order_by('user_ratings.id', 'desc');
        $this->db->where('user_id', $user_id);
        $this->db->join('users', 'users.id = user_ratings.from_user_id');
        $this->db->select('user_ratings.*, users.first_name, users.last_name, users.mobile, users.email_id');
        $user_ratings = $this->db->get('user_ratings');
        //echo $this->db->last_query();exit;
        if ($user_ratings->num_rows() > 0) {
            return $user_ratings->result_array();
        }
        return array();
    }

    public function send_push_notification($message = NULL, $sent_to, $id, $type, $order_id = 0, $amount = 0, $rider_id = 0, $vehicle_id = 0, $ride_id = 0, $mode = NULL, $ride_type = NULL, $data = []) {
        date_default_timezone_set("Asia/Kolkata");
        $reg_ids = array();
        $ios_reg_ids = array();
        $this->db->where('delete_status', 1);
        $this->db->where('status', 1);
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->select("id, first_name, last_name, token, ios_token");
        if ($sent_to == "user") {
            $users = $this->db->get("users");
        } else {
            $users = $this->db->get("users");
        }
        $r = array();
        //echo $this->db->last_query();
        if ($users->num_rows() > 0) {
            $result = $users->result();
            foreach ($result as $r) {
                //echo $r->id;
                if (!empty($r->ios_token) && $r->ios_token != "-" && $r->ios_token != "") {
                    array_push($ios_reg_ids, $r->ios_token);
                }
                if (!empty($r->token) && $r->token != "-" && $r->token != "") {
                    array_push($reg_ids, $r->token);
                }
            }
        }
        $title = "CIAO Rides Notification";
        $this->db->insert('notifications', array('title' => $title, 'message' => $message, 'sent_to' => $sent_to, 'created_on' => date('Y-m-d H:i:s')));
        //$rowsMsg[] = $message;
        $ios_message = $message;
        //$jsonData = json_encode(array('json' => $rowsMsg));
        $message = array('title' => $title, 'message' => $message, 'user_id' => $id, 'type' => $type, 'order_id' => $order_id, 'amount' => $amount, 'rider_id' => $rider_id, 'vehicle_id' => $vehicle_id, 'ride_id' => $ride_id, 'mode' => $mode, 'ride_type' => $ride_type, 'data' => $data);
        //var_dump($message);exit;
        //var_dump($reg_ids);
        $pushStatus = $this->sendAndroidNotification($reg_ids, $message, $sent_to);
        // echo $sent_to;
        //echo "<pre>";print_r($pushStatus);
        $iosPushStatus = $this->sendIosNotification($ios_reg_ids, $ios_message, $message);
        return true;
    }

    public function sendAndroidNotification($tokens, $message, $sent_to) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );
        if ($sent_to == "driver") {
            $headers = array(
                'Authorization:key = AAAAmBD-fyw:APA91bHGsQQY7wssRco0juiYDrEkHK1csIe2R6lMQOBYsIfpIcKCMGVKndMr_W2Kib3nS0qL03ws7lrq_1NSd2eC6cW9gfYadC5B8zA7M6Gi9ty3BN6ETsRLW49LLbDF411G7fyWZyTa',
                'Content-Type: application/json'
            );
        } else {
            $headers = array(
                'Authorization:key = AAAAmBD-fyw:APA91bHGsQQY7wssRco0juiYDrEkHK1csIe2R6lMQOBYsIfpIcKCMGVKndMr_W2Kib3nS0qL03ws7lrq_1NSd2eC6cW9gfYadC5B8zA7M6Gi9ty3BN6ETsRLW49LLbDF411G7fyWZyTa',
                'Content-Type: application/json'
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        //var_dump($result);
        return $result;
    }

    public function sendIosNotification($tokens, $message, $data) {
        // API access key from Google FCM App Console
        define('API_ACCESS_KEY', 'AAAAmBD-fyw:APA91bHGsQQY7wssRco0juiYDrEkHK1csIe2R6lMQOBYsIfpIcKCMGVKndMr_W2Kib3nS0qL03ws7lrq_1NSd2eC6cW9gfYadC5B8zA7M6Gi9ty3BN6ETsRLW49LLbDF411G7fyWZyTa');
        $fcmMsg = array(
            'body' => $message,
            'title' => 'CIAO Rides Notification',
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'order_id' => $data['order_id'],
            'amount' => $data['amount'],
            'rider_id' => $data['rider_id'],
            'vehicle_id' => $data['vehicle_id'],
            'ride_id' => $data['ride_id'],
            'mode' => $data['mode'],
            'ride_type' => $data['ride_type'],
            'data' => $data['date_add()'],
            'sound' => "default",
            'color' => "#203E78"
        );
        // 'to' => $singleID ;  // expecting a single ID
        // 'registration_ids' => $registrationIDs ;  // expects an array of ids
        // 'priority' => 'high' ; // options are normal and high, if not set, defaults to high.
        $fcmFields = array(
            'registration_ids' => $tokens,
            'priority' => 'high',
            'notification' => $fcmMsg
        );

        $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result . "\n\n";//exit;
        return true;
    }

    function get_driving_distance($lat1, $lat2, $long1, $long2) {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyBSaSzEI417kuNqInU-19QL1JhIr3nNX3M&origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&language=en";
        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        //echo "<pre>";print_r($response_a);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
        $dist_value = $response_a['rows'][0]['elements'][0]['distance']['value'];

        return array('distance' => $dist, 'time' => $time, 'dist_value' => $dist_value, 'lat2' => $long2);
    }

    

    public function getUserVehicles($user_id){

        $query="select uv.*,vm.title as making_type,vmm.title as model_type from user_vehicles uv left join vehicle_makes vm on vm.id=uv.make_id left join vehicle_models vmm on vmm.id=uv.model_id where uv.user_id='$user_id' ";
        $result=$this->db->query($query)->result_array();
        return $result;

    }



}?>