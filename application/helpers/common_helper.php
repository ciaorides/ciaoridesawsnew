<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('DateFormat')) {

    function DateFormat($Date) {
        return date('M d, Y', strtotime($Date));
    }

}

if (!function_exists('DateTimeFormat')) {

    function DateTimeFormat($Date = '', $Style = '') {

        if ($Style == 'break')
            return date('d M Y', strtotime($Date)) . '<br />' . date('h:i A', strtotime($Date));

        return date('d M Y | h:i A', strtotime($Date));
    }

}

if (!function_exists('TimeFormat')) {

    function TimeFormat($Date = '', $Style = '') {

        if ($Style == 'break')
            return date('d M Y', strtotime($Date)) . '<br />' . date('h:i A', strtotime($Date));

        return date('h:i A', strtotime($Date));
    }

}

if (!function_exists('Ymd_to_dmY')) {

    function Ymd_to_dmY($date = NULL) {
        if ($date)
            return date('d/m/Y', strtotime($date));
        return false;
    }

}

if (!function_exists('Ymd_to_mdY')) {

    function Ymd_to_mdY($date = NULL) {
        if ($date)
            return date('m/d/Y', strtotime($date));
        return false;
    }

}

if (!function_exists('dmY_to_Ymd')) {

    function dmY_to_Ymd($date = NULL) {
        if ($date) {
            $dateInput = explode('/', $date);
            $date = $dateInput[2] . '-' . $dateInput[1] . '-' . $dateInput[0];
            return $date;
        }
        return false;
    }

}

if (!function_exists('mdY_to_Ymd')) {

    function mdY_to_Ymd($date = NULL) {
        if ($date) {
            $dateInput = explode('/', $date);
            $date = $dateInput[2] . '-' . $dateInput[0] . '-' . $dateInput[1];
            return $date;
        }
        return false;
    }

}

if (!function_exists('DeliveryTimeFormat')) {

    function DeliveryTimeFormat($Time) {
        $Minutes = round((strtotime($Time) - strtotime('TODAY')) / 60);
        return $Minutes < 60 ? $Minutes : date('h:i', strtotime($Time));
    }

}

if (!function_exists('BootstrapCreatePagination')) {

    function BootstrapCreatePagination($URL = '', $TotalRows = 0, $PerPage = 0, $Class = NULL) {
        $ci = & get_instance();

        $ci->load->library('pagination');
        $config['base_url'] = $URL;
        $config['total_rows'] = $TotalRows;
        $config['per_page'] = $PerPage;
        $config['num_links'] = 5;
        $config['page_query_string'] = TRUE;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo; prev';
        $config['next_link'] = 'next &raquo;';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $ci->pagination->initialize($config);
        return $ci->pagination->create_links();
    }

}

if (!function_exists('SendEmail')) {

    function SendEmail($to = '', $subject = '', $message = '', $attachs = NULL, $replyTo = NULL) {
        if ($to && $subject && $message) {
            $ci = & get_instance();
            $ci->load->model('email_model');
            $ci->email_model->SendEmail($to, $subject, $message, $attachs, $replyTo);
        }
        return false;
    }

}

if (!function_exists('SendSMS')) {

    function SendSMS($phone = NULL, $message = NULL) {
        if ($phone && $message) {
            $username = urlencode("u48200");
            $msg_token = urlencode("rUwY2M");
            $sender_id = urlencode("CIAORD"); // optional (compulsory in transactional sms)
            $message = urlencode($message);
            $contacts = urlencode($phone);
            $api = "http://manage.sarvsms.com/api/send_transactional_sms.php?username=" . $username . "&msg_token=" . $msg_token . "&sender_id=" . $sender_id . "&message=" . $message . "&mobile=" . $contacts . "";
            //echo $api;
            // $response = file_get_contents($api);
            // //var_dump($response);exit;
            // if (strpos($response, 'SUCCESS') !== false)
            // {
            // 	return true;
            // }
            // else
            // {
            // 	return false;
            // }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $api);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
            $str = curl_exec($curl);
            return true;
            //var_dump($str);
        }
        return false;
    }

}

if (!function_exists('check_user_username_exists')) {

    function check_user_username_exists($username = NULL) {
        if ($username) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('username' => $username, 'delete_status' => 1));
            //echo $ci->db->last_query();exit;
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

}

if (!function_exists('check_user_username_status')) {

    function check_user_username_status($username = NULL) {
        if ($username) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('username' => $username, 'delete_status' => 1, 'status' => 1));
            //echo $ci->db->last_query();exit;
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

}

if (!function_exists('check_user_mobile_exists')) {

    function check_user_mobile_exists($mobile = NULL) {
        if ($mobile) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('mobile' => $mobile, 'delete_status' => 1));
            //echo $ci->db->last_query();exit;
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

}

if (!function_exists('check_user_mobile_status')) {

    function check_user_mobile_status($mobile = NULL) {
        if ($mobile) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('mobile' => $mobile, 'delete_status' => 1, 'status' => 1));
            //echo $ci->db->last_query();exit;
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

}

if (!function_exists('check_user_email_id_status')) {

    function check_user_email_id_status($email_id = NULL) {
        if ($email_id) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('email_id' => $email_id, 'delete_status' => 1, 'status' => 1));
            //echo $ci->db->last_query();exit;
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

}

if (!function_exists('check_user_email_id_exists')) {

    function check_user_email_id_exists($email_id = NULL) {
        if ($email_id) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('users', array('email_id' => $email_id, 'delete_status' => 1));
            //echo $ci->db->last_query();
            if ($Result->num_rows() > 0) {
                return $Result->row();
            } else {
                return array();
            }
        }
        return array();
    }

    if (!function_exists('user_by_id')) {

        function user_by_id($user_id = NULL) {
            if ($user_id) {
                $ci = & get_instance();
                $Result = $ci->db->get_where('users', array('id' => $user_id, 'delete_status' => 1));
                if ($Result->num_rows() > 0)
                    return $Result->row();
            }
            return array();
        }

    }

    if (!function_exists('user_by_email_id')) {

        function user_by_email_id($email_id = NULL) {
            if ($email_id) {
                $ci = & get_instance();
                $Result = $ci->db->get_where('users', array('email_id' => $email_id, 'delete_status' => 1));
                if ($Result->num_rows() > 0)
                    return $Result->row();
            }
            return array();
        }

    }

    if (!function_exists('check_course_name_exists')) {

        function check_course_name_exists($title = NULL) {
            if ($title) {
                $ci = & get_instance();
                $Result = $ci->db->get_where('classes', array('title' => $title, 'delete_status' => 1));
                if ($Result->num_rows() > 0)
                    return $Result->row();
            }
            return array();
        }

    }

    if (!function_exists('get_table_row')) {

        function get_table_row($table_name = '', $where = '', $columns = '', $order_column = '', $order_by = 'asc', $limit = '') {
            $ci = & get_instance();
            if (!empty($columns)) {
                $tbl_columns = implode(',', $columns);
                $ci->db->select($tbl_columns);
            }
            if (!empty($where))
                $ci->db->where($where);
            if (!empty($order_column))
                $ci->db->order_by($order_column, $order_by);
            if (!empty($limit))
                $ci->db->limit($limit);
            $query = $ci->db->get($table_name);
            if ($columns == 'test') {
                echo $ci->db->last_query();
                exit;
            }
            //echo $ci->db->last_query();
            return $query->row_array();
        }

    }

    if (!function_exists('get_table')) {

        function get_table($table_name = '', $where = '', $columns = '', $order_column = '', $order_by = 'asc', $limit = '', $offset = '') {
            $ci = & get_instance();
            if (!empty($columns)) {
                $tbl_columns = implode(',', $columns);
                $ci->db->select($tbl_columns);
            }
            if (!empty($where))
                $ci->db->where($where);
            if (!empty($order_column))
                $ci->db->order_by($order_column, $order_by);
            if (!empty($limit) && !empty($offset))
                $ci->db->limit($limit, $offset);
            else if (!empty($limit))
                $ci->db->limit($limit);
            $query = $ci->db->get($table_name);
            //echo $ci->db->last_query(); exit;
            //if($columns=='test') { echo $ci->db->last_query(); exit; }
            //echo $ci->db->last_query();
            return $query->result_array();
        }

    }

    if (!function_exists('insert_table')) {

        function insert_table($table_name = '', $array = '', $insert_id = '', $batch = false) {
            $ci = & get_instance();
            if (!empty($array) && !empty($table_name)) {
                if ($batch) {
                    $ci->db->insert_batch($table_name, $array);
                } else {
                    $ci->db->insert($table_name, $array);
                }
                //echo $ci->db->last_query(); exit;
                //if(!empty($insert_id)) return $ci->db->insert_id();
                return $ci->db->insert_id();
            }
            return 0;
        }

    }

    if (!function_exists('update_table')) {

        function update_table($table_name = '', $array = '', $where = '') {
            $ci = & get_instance();
            if (!empty($array) && !empty($table_name)) {
                $ci->db->where($where);
                $ci->db->update($table_name, $array);
                if ($ci->db->affected_rows() > 0) {
                    return true;
                }
            }
            return false;
        }

    }

    if (!function_exists('delete_record')) {

        function delete_record($table_name = '', $array = '') {
            $ci = & get_instance();
            if (!empty($array) && !empty($table_name)) {
                $ci->db->delete($table_name, $array);
                return true;
            }
            return false;
        }

    }

    if (!function_exists('upload_image')) {

        function upload_image($image_data = array()) {
            $encoded_string = $image_data['image'];
            $imgdata = base64_decode($encoded_string);
            $data = getimagesizefromstring($imgdata);
            $extension = explode('/', $data['mime']);
            define('UPLOAD_DIR', $image_data['upload_path']);
            $img = str_replace('data:' . $data['mime'] . ';base64,', '', $image_data['image']);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = $image_data['file_path'] . uniqid() . '.' . $extension[1];
            $success = file_put_contents($file, $data);
            //var_dump($success);
            if ($success) {
                $status = true;
                $result = $file;
            } else {
                $status = false;
                $result = '';
            }
            $response = array('status' => $status, 'result' => $result);
            return $response;
        }

    }

    //Function that resizes image.
    function resizeImage($SrcImage, $DestImage, $MaxWidth, $MaxHeight, $Quality) {
        list($iWidth, $iHeight, $type) = @getimagesize($SrcImage);
        $ImageScale = min($MaxWidth / $iWidth, $MaxHeight / $iHeight);
        $NewWidth = ceil($ImageScale * $iWidth);
        $NewHeight = ceil($ImageScale * $iHeight);
        $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

        //echo image_type_to_mime_type($type);

        switch (strtolower(image_type_to_mime_type($type))) {
            case 'image/jpeg':
                $NewImage = @imagecreatefromjpeg($SrcImage);
                break;
            case 'image/png':
                $NewImage = @imagecreatefrompng($SrcImage);
                break;
            case 'image/gif':
                $NewImage = @imagecreatefromgif($SrcImage);
                break;
            default:
                return false;
        }
        // Resize Image
        if (@imagecopyresampled($NewCanves, $NewImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight)) {
            // copy file
            if (imagejpeg($NewCanves, $DestImage, $Quality)) {
                imagedestroy($NewCanves);
                return true;
            }
        }
    }

    function relative_date($time) {

        $today = strtotime(date('M j, Y'));

        $reldays = ($time - $today) / 86400;

        if ($reldays >= 0 && $reldays < 1) {

            return 'Today';
        } else if ($reldays >= 1 && $reldays < 2) {

            return 'Tomorrow';
        } else if ($reldays >= -1 && $reldays < 0) {

            return 'Yesterday';
        }

        if (abs($reldays) < 7) {

            if ($reldays > 0) {

                $reldays = floor($reldays);

                return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
            } else {

                $reldays = abs(floor($reldays));

                return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
            }
        }

        if (abs($reldays) < 182) {

            return date('l, j F', $time ? $time : time());
        } else {

            return date('l, j F, Y', $time ? $time : time());
        }
    }

    if (!function_exists('get_distance_time')) {

        function get_distance_time($lat1, $lat2, $long1, $long2) {
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
            // echo "<pre>";print_r($response_a);exit;
            $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
            $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
            return array('distance' => $dist, 'time' => $time);
        }

    }

    function all_waypoints_fromLatLng_toLatLng($From_lat = NULL, $From_Long = NULL, $To_lat = NULL, $To_long = NULL) {

        if (!$From_lat OR ! $From_Long) {
            return false;
        }
        if (!$To_lat OR ! $To_long) {
            return false;
        }
        $travelMode = "driving";

        $url = "https://maps.googleapis.com/maps/api/directions/json?key=AIzaSyBSaSzEI417kuNqInU-19QL1JhIr3nNX3M&origin=" . $From_lat . "," . $From_Long . "&destination=" . $To_lat . "," . $To_long . "&mode=" . $travelMode . "&sensor=false";

        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);
        //var_dump($data);
        if (isset($data["status"]) && $data["status"] == "OK") {
            $meta = array(
                "status" => 200,
                "message" => "Succeed."
            );
            $res = $data["routes"][0]["legs"][0];
            $startAddress = $res["start_address"];
            $endAddress = $res["end_address"];
            $startLocation = $res["start_location"];
            $endLocation = $res["end_location"];
            $duration = $res["duration"];
            $distance = $res["distance"];
            $steps = $res["steps"];
            $len = count($steps);
            $i = 0;
            $row = array();
            while ($i < $len) {
                $text = $steps[$i]["html_instructions"];
                $start = $steps[$i]["start_location"];
                $end = $steps[$i]["end_location"];
                $dist = $steps[$i]["distance"];
                $dur = $steps[$i]["duration"];
                array_push($row, array(
                    //"text"=>strip_tags($text),
                    "start" => $start,
                    "end" => $end,
                        //"distance"=>$dist,
                        //"duration"=>$dur
                ));
                $i++;
            }
            //var_dump($row);exit;
            $data_arr = array();
            if (!empty($row)) {
                foreach ($row as $rs) {
                    $data_arr[] = $rs['start'];
                    $data_arr[] = $rs['end'];
                }
                return $data_arr;
            }
            return array();
            // return array("meta"=>$meta, "response"=>array(
            // 	//"start_address"=>$startAddress,
            // 	//"end_address"=>$endAddress,
            // 	//"start_location"=>$startLocation,
            // 	//"end_location"=>$endLocation,
            // 	//"duration"=>$duration,
            // 	//"distance"=>$distance,
            // 	"steps"=>$row
            // ));
        } else {
            return array();
        }
    }

}

function getAddress($latitude, $longitude)
{
        //google map api url
        $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyBSaSzEI417kuNqInU-19QL1JhIr3nNX3M&latlng=$latitude,$longitude";
        //echo $url;exit;
        // send http request
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);
        $address = $json->results[0]->formatted_address;
        return $address;
}

function get_age($dob) {
    // $dob  date format
    $diff = (date('Y') - date('Y', strtotime($dob)));
    return $diff;
}

function convert_to_inr_format($amount) {
    setlocale(LC_MONETARY, 'en_IN');
    $amount = money_format('%!i', $amount);
    return $amount;
}

if (!function_exists('GetCityName')) {

    function GetCityName($lat, $long) {
        if ($lat && $lat) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $long . "&sensor=true&key=AIzaSyBSaSzEI417kuNqInU-19QL1JhIr3nNX3M");
            //curl_setopt($ch, CURLOPT_URL, "http://freegeoip.net/json/$ip");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            $response = json_decode($contents);
//            echo '<pre>';
//            print_r($response->results[0]->formatted_address);
//            die;
            curl_close($ch);
            return $response->results[0]->formatted_address;
        }
        return false;
    }

}

function decodeEmoticons($src) {
    if (mb_detect_encoding($src) === 'ASCII') {
        $replaced = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", $src);
        $result = mb_convert_encoding($replaced, "UTF-16", "HTML-ENTITIES");
        $result = mb_convert_encoding($result, 'utf-8', 'utf-16');
    } else {
        $result = $src;
    }
    return $result;
}




 function getDynamicId($column_name,$dynamic_test){

        $ci = &get_instance();
        /* Reference No */
        $reference_id='';
        $ci->db->select("*");
        $ci->db->from('tbl_dynamic_nos');
        $query = $ci->db->get();
        $row_count = $query->num_rows();
        if($row_count > 0){

            $refers_no = $query->row_array();
            $ref_no=$refers_no[$column_name]+1;
            $refernce_data = array($column_name => $ref_no,
                                   'update_date_time'    => date('Y-m-d H:i:s')
                                   );
            $ci->db->where('id',1);
            $update = $ci->db->update('tbl_dynamic_nos', $refernce_data);
        }else{

            $ref_no=1;
            $refernce_data =   array($column_name => $ref_no,
                                    'update_date_time'   => date('Y-m-d H:i:s')
                                    );
            $update = $ci->db->insert('tbl_dynamic_nos', $refernce_data); 
        }
        
        
        $reference_id =  $dynamic_test.$ref_no;
        
        //$reference_id="BBM".$ref_no;
        /* Reference No */
        return $reference_id;
}
