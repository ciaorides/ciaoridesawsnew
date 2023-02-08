<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Loginmodel extends CI_Model {

    public function login($username, $password) {
        //echo $password;exit;
        $this->db->select('*');
        $this->db->from('employees');
        $this->db->where('user_id', $username);
        $this->db->where('password', md5($password));
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if ($query !== FALSE) {
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function active_users_count() {
        $this->db->where('users.delete_status', 1);
        $this->db->where('users.status', 1);
        $query = $this->db->get('users');
        if ($query !== FALSE) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function in_active_users_count() {
        $this->db->where('users.delete_status', 1);
        $this->db->where('users.status', 0);
        $query = $this->db->get('users');
        if ($query !== FALSE) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function orders_count($status = false) {
        if ($status) {
            $this->db->where('status', $status);
        }
        $query = $this->db->get('orders');
        if ($query !== FALSE) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function rides_count() {

        $query = $this->db->get('rides');
        if ($query !== FALSE) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function chats_count() {

        $query = $this->db->get('chats');
        if ($query !== FALSE) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function recent_bookings() {
        $this->db->limit(10);
        $this->db->select('orders.*,users.first_name, users.last_name,u.first_name as ufirst_name,(orders.total_amount-(orders.tax+orders.payment_gateway_commision+orders.ciao_commission)) as rider_amount')->join('users', 'users.id=orders.user_id')->join('users as u', 'u.id=orders.rider_id')->where('orders.mode', 'city')->where('orders.ride_type', 'now')->from('orders')->order_by('orders.id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

?>