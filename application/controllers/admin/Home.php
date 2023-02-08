<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->is_logged_in();
        $this->load->model('admin/loginmodel');
        date_default_timezone_set("Asia/Kolkata");
         $this->load->model('common_model');
         if($this->session->userdata('user_id') != 'superadmin'){
         $this->data['roleResponsible'] = $this->common_model->get_responsibilities();
         }else{
         $this->data['roleResponsible'] = $this->common_model->get_default_responsibilities();
         }

        //echo '<pre>';print_r($this->data['roleResponsible']);exit;
    }

    public function index() {
        $this->data['title'] = "Komodeo";
        $this->data['active_users_count'] = $this->loginmodel->active_users_count();
        $this->data['inactive_users_count'] = $this->loginmodel->in_active_users_count();
        $this->data['orders_count'] = $this->loginmodel->orders_count();
        $this->data['orders_count_completed'] = $this->loginmodel->orders_count('completed');
        $this->data['orders_count_accepted'] = $this->loginmodel->orders_count('accepted');
        $this->data['orders_count_can_us'] = $this->loginmodel->orders_count('cancelled by user');
        $this->data['orders_count_can_rider'] = $this->loginmodel->orders_count('cancelled by rider');
        $this->data['rides_count'] = $this->loginmodel->rides_count();
        $this->data['chats_count'] = $this->loginmodel->chats_count();
        $this->data['recent_bookings'] = $this->loginmodel->recent_bookings();
        if (!empty($_GET['from_date'])) {
            $from_date = $this->input->get_post('from_date') . ' 00:00:00';
            $to_date = $this->input->get_post('to_date') . ' 23:59:59';
            $this->data['stat_data'] = $this->get_totals($from_date, $to_date);
        } else {
            $from_date = date('Y-m-d') . ' 00:00:00';
            $to_date = date('Y-m-d') . ' 23:59:59';
            $this->data['stat_data'] = $this->get_totals($from_date, $to_date);
        }
        if (!empty($_GET['search_string'])) {
            $search = $this->input->get_post('search_string');
            $this->data['user_data'] = $this->get_userdetails($search);
            //print_r($this->data['user_data']);
        }
       // echo '<pre>';print_r($this->data);exit;
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/includes/leftside_menu', $this->data);
        $this->load->view('admin/homeview', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    function get_userdetails($search) {
        $this->data['info'] = $this->db->where('mobile', $search)->get('users')->row();
        $user_id = $this->data['info']->id;
        $this->data['bookings'] = $this->db->where('user_id', $user_id)->get('orders')->result();
        $this->data['rides'] = $this->db->where('user_id', $user_id)->get('rides')->result();
        return $this->data;
    }

    function get_totals($from_date, $to_date) {
        $res = $this->db->where('created_on >=', $from_date)->where('created_on <=', $to_date)->where('status', 'completed')->get('orders')->result();
        $res_rides = $this->db->where('created_on >=', $from_date)->where('created_on <=', $to_date)->where('status', 'completed')->get('rides')->result();
        $total_amount = 0;
        $total_ciao_commission = 0;
        $total_rider_commission = 0;
        $total_tax = 0;
        $total_payment_gateway = 0;
        $stat_arr = (object) array();
        foreach ($res as $item) {

            $total_amount += $item->total_amount;
            $total_ciao_commission += $item->ciao_commission;
            $total_rider_commission += $item->amount;
            $total_tax += $item->tax;
            $total_payment_gateway += $item->payment_gateway_commision;
        }
        $stat_arr->total_bookings = count($res);
        $stat_arr->total_rides = count($res_rides);
        $stat_arr->total_amount = $total_amount;
        $stat_arr->total_ciao_commission = $total_ciao_commission;
        $stat_arr->total_rider_commission = $total_rider_commission;
        $stat_arr->total_tax = $total_tax;
        $stat_arr->total_payment_gateway = $total_payment_gateway;
        return $stat_arr;
    }

    public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('admin/login', 'refresh');
        }
    }

    function is_logged_out() {
        $this->session->unset_userdata('is_logged_in');
        redirect('admin/login', 'refresh');
    }

    function ciao_view($userid) {
        $this->data = array();
        $this->data['user_id'] = $userid;
        $this->data['chat_list'] = $this->db->where('user_id', $userid)->get('co_chat')->result();
        $update = $this->db->set('is_scean', 'yes')->where(['user_id' => $userid, 'type' => "user"])->update('co_chat');
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/ciao_chats', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    function admin_chat() {
        $this->data = array();
        $this->data['chat_list'] = $this->db->set("SET character_set_connection=utf8mb4")->where(['type' => 'user', 'is_scean' => 'no'])->order_by('id', 'desc')->get('co_chat')->result();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/ciao_chats_list', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    function get_chat_list_api() {
        header('Content-type: application/json');
        $user_id = $this->input->get_post('user_id');
        $this->data = $this->db->where('user_id', $user_id)->get('co_chat')->result();
        foreach ($this->data as $d) {
            $d->message = decodeEmoticons($d->message);
        }
        echo json_encode($this->data);
        die;
    }

    function post_chat_list_api() {

        $ins = array(
            'user_id' => $this->input->get_post('user_id'),
            'message' => str_replace('"', "", json_encode($this->input->get_post('message'))),
            'type' => $this->input->get_post('type'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $ins_data = $this->db->set($ins)->insert("co_chat");
        if ($ins_data) {
            //send push notification to user
            $user_push = $this->db->select('token,ios_token')->where('id', $this->input->get_post('user_id'))->get('users')->row();
            $title = "Admin Message";
            if (!empty($user_push->token)) {
                $pushStatus = $this->sendAndroidNotification([$user_push->token], array('title' => $title, 'description' => $this->input->get_post('message'), 'type' => 'admin_chat'));
            } else if (!empty($user_push->ios_token)) {
                $iosPushStatus = $this->sendIosNotification([$user_push->ios_token], $this->input->get_post('message'), $title);
            }
            if (!empty($fire)) {

//                $this->registermodel->send_push_notification($this->input->get_post('message'), "Admin Notification", $fire);
            }
            $this->data = $this->db->where('user_id', $this->input->get_post('user_id'))->get('co_chat')->result();
            echo json_encode($this->data);
            die;
        }
    }

    public function sendAndroidNotification($tokens, $message) {
        //print_r($tokens);exit();
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );
        //echo "string";exit;
        $headers = array(
            'Authorization:key = AAAAmBD-fyw:APA91bHGsQQY7wssRco0juiYDrEkHK1csIe2R6lMQOBYsIfpIcKCMGVKndMr_W2Kib3nS0qL03ws7lrq_1NSd2eC6cW9gfYadC5B8zA7M6Gi9ty3BN6ETsRLW49LLbDF411G7fyWZyTa',
            'Content-Type: application/json'
        );
        //print_r($headers) ;exit;


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
        //print_r($result);exit();
        return $result;
    }

    public function sendIosNotification($tokens, $message, $title) {
        // API access key from Google FCM App Console
        define('API_ACCESS_KEY', 'AAAAmBD-fyw:APA91bHGsQQY7wssRco0juiYDrEkHK1csIe2R6lMQOBYsIfpIcKCMGVKndMr_W2Kib3nS0qL03ws7lrq_1NSd2eC6cW9gfYadC5B8zA7M6Gi9ty3BN6ETsRLW49LLbDF411G7fyWZyTa');
        $fcmMsg = array(
            'body' => $message,
            'title' => $title,
            'type' => 'admin_chat',
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

}

?>