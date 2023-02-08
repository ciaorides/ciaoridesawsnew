<?php

class Usermodel extends CI_Model {
    /* ---------- Admin Details ------------ */

    function admin_status() {
        $this->db->where('id', $this->uri->segment(5));
        $this->db->set('status', $this->uri->segment(4));
        $this->db->update('admin');
        return true;
    }

    function check_unique_user_email($id = '', $username) {
        $this->db->where('username', $username);
        if ($id) {
            $this->db->where_not_in('id', $id);
        }
        return $this->db->get('admin')->num_rows();
    }

    public function AdminDetails() {
        $this->db->where('id', $this->session->userdata('admin_id'));
        $query = $this->db->get('admin');
        return $query->row();
    }

    public function UpdatePassword($data) {
        $this->db->where('id', 1);
        $this->db->update('admin', $data);
        //echo $this->db->last_query();  exit;
        return true;
    }

    /* ---------- /Admin Details ------------ */



    /* ---------- amount_calculations ------------ */

    public function all_amount_calculations($pdata, $getcount = null) {
        /* $search_1 = array
          (
          1 => 'per_km',
          );
          if(isset($pdata['search_text_1'])!="")
          {
          $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1'] );
          } */
        if ($getcount) {
            return $this->db->select('amount_calculations.id')->from('amount_calculations')->order_by('amount_calculations.id', 'asc')->get()->num_rows();
        } else {
            $this->db->select('amount_calculations.*')->from('amount_calculations')->order_by('amount_calculations.id', 'asc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /amount_calculations ------------ */


    /* ---------- chats ------------ */

    public function all_chats($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'sender_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'users.first_name',
            3 => 'chats.message',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('chats.id,users.first_name')->join('users', 'users.id=chats.sender_id', 'left')->from('chats')->order_by('chats.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('chats.*,users.first_name')->join('users', 'users.id=chats.sender_id', 'left')->from('chats')->order_by('chats.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /chats ------------ */

    /* ---------- declined_orders ------------ */

    public function all_declined_orders($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'order_id'
        );
        $search_1 = array
            (
            1 => 'order_id'
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('declined_orders.id')->from('declined_orders')->order_by('declined_orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('declined_orders.*')->from('declined_orders')->order_by('declined_orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /declined_orders ------------ */


    /* ---------- emergency_contacts ------------ */

    public function all_emergency_contacts($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'mobile'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'emergency_contacts.name',
            3 => 'emergency_contacts.mobile',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('emergency_contacts.id,users.first_name')->join('users', 'users.id=emergency_contacts.user_id')->from('emergency_contacts')->order_by('emergency_contacts.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('emergency_contacts.*,users.first_name,users.last_name,users.mobile as umobile,users.id as u_id')->join('users', 'users.id=emergency_contacts.user_id')->from('emergency_contacts')->order_by('emergency_contacts.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /emergency_contacts ------------ */



    /* ---------- favourite_locations ------------ */

    public function all_favourite_locations($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'address'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'favourite_locations.type',
            3 => 'favourite_locations.address',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('favourite_locations.id,users.first_name,users.last_name')->join('users', 'users.id = favourite_locations.user_id')->from('favourite_locations')->order_by('favourite_locations.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('favourite_locations.*,users.first_name,users.last_name,users.mobile')->join('users', 'users.id = favourite_locations.user_id')->from('favourite_locations')->order_by('favourite_locations.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /favourite_locations ------------ */



    /* ---------- notifications ------------ */

    public function all_notifications($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'notifications.title',
            3 => 'notifications.title',
        );
        $search_1 = array
            (
            1 => 'notifications.title',
            2 => 'notifications.description',
        );
        if (isset($pdata['search_text_1']) != "") {
            if ($pdata['search_on_1'] != 5 && $pdata['search_on_1'] != 6) {
                $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
            } else {

            }
        }
        if ($getcount) {
            return $this->db->select('notifications.*')->from('notifications')->order_by('notifications.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('notifications.*')->from('notifications')->order_by('notifications.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        //echo $this->db->last_query();exit;
        return $result;
    }

    public function insert_notifications($data) {
        $this->db->insert('notifications', $data);
        //echo $this->db->last_query();exit;
        return true;
    }

    /* ---------- /notifications ------------ */



    /* ---------- rider_current_location ------------ */

    public function all_rider_current_location($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'rider_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'users.mobile',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('rider_current_location.id,users.first_name,users.mobile')->join('users', 'users.id=rider_current_location.rider_id', 'left')->from('rider_current_location')->order_by('rider_current_location.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rider_current_location.*,users.first_name,users.mobile')->join('users', 'users.id=rider_current_location.rider_id', 'left')->from('rider_current_location')->order_by('rider_current_location.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }

        return $result;
    }

    /* ---------- /rider_current_location ------------ */

    /* ---------- rides ------------ */

    public function all_rides($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'first_name',
            0 => 'number_plate',
            0 => 'trip_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_vehicles.number_plate',
            3 => 'rides.trip_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->order_by('rides.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function ride_details() {
        $this->db->order_by('rides.id', 'desc');
        $this->db->where('rides.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=rides.user_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id', 'left');

//        $this->db->join('orders', 'orders.ride_id=rides.id', 'left');orders.*,
        $this->db->select('users.first_name,rides.*,user_vehicles.number_plate as vehicle_number');
        $query = $this->db->get('rides');
//        echo $this->db->last_query();
        return $query->row_array();
    }

    /* ---------- /rides ------------ */

    /* ---------- ongoing ------------ */

    public function all_ongoing($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'first_name',
            0 => 'number_plate',
            0 => 'trip_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_vehicles.number_plate',
            3 => 'rides.trip_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.status', 'ongoing')->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.status', 'ongoing')->order_by('rides.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function ongoing_details() {
        $this->db->order_by('rides.id', 'desc');
        $this->db->where('rides.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=rides.user_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id', 'left');
        $this->db->select('users.first_name,rides.*,user_vehicles.number_plate as vehicle_number');
        $query = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /ongoing ------------ */
    /* ---------- completed ------------ */

    public function all_completed($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'first_name',
            0 => 'number_plate',
            0 => 'trip_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_vehicles.number_plate',
            3 => 'rides.trip_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.status', 'completed')->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.status', 'completed')->order_by('rides.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function completed_details() {
        $this->db->order_by('rides.id', 'desc');
        $this->db->where('rides.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=rides.user_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id', 'left');
        $this->db->select('users.first_name,rides.*,user_vehicles.number_plate as vehicle_number');
        $query = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /completed ------------ */
    /* ---------- shceduled ------------ */

    public function all_shceduled($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'first_name',
            0 => 'number_plate',
            0 => 'trip_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_vehicles.number_plate',
            3 => 'rides.trip_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.ride_type', 'later')->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.ride_type', 'later')->order_by('rides.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function shceduled_details() {
        $this->db->order_by('rides.id', 'desc');
        $this->db->where('rides.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=rides.user_id', 'left');
        $this->db->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id', 'left');
        $this->db->select('users.first_name,rides.*,user_vehicles.number_plate as vehicle_number');
        $query = $this->db->get('rides');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /shceduled ------------ */
    /* ---------- ride_lat_lngs ------------ */

    public function all_ride_lat_lngs($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'ride_id'
        );
        $search_1 = array
            (
            1 => 'ride_id'
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('ride_lat_lngs.id')->from('ride_lat_lngs')->order_by('ride_lat_lngs.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('ride_lat_lngs.*')->from('ride_lat_lngs')->order_by('ride_lat_lngs.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /ride_lat_lngs ------------ */


    /* ---------- user_bank_details ------------ */

    public function all_user_bank_details($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'account_number'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'countries.name',
            3 => 'user_bank_details.bank_name',
            4 => 'user_bank_details.account_holder_name',
            5 => 'user_bank_details.account_number',
            6 => 'user_bank_details.ifsc_code',
            7 => 'users.mobile',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('user_bank_details.*,users.first_name,countries.name as country_name,users.mobile')->from('user_bank_details')->join('users', 'users.id = user_bank_details.user_id', 'left')->join('countries', 'countries.id = user_bank_details.country_id', 'left')->order_by('user_bank_details.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('user_bank_details.*,users.first_name,users.mobile,countries.name as country_name,users.id as u_id')->from('user_bank_details')->join('users', 'users.id = user_bank_details.user_id')->join('countries', 'countries.id = user_bank_details.country_id')->order_by('user_bank_details.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /user_bank_details ------------ */


    /* ---------- user_feedback ------------ */

    public function all_user_feedback($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'user_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_feedback.description'
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('user_feedback.*,users.first_name,users.last_name')->from('user_feedback')->join('users', 'users.id = user_feedback.user_id')->order_by('user_feedback.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('user_feedback.*,users.first_name,users.last_name,users.id as u_id')->from('user_feedback')->join('users', 'users.id = user_feedback.user_id')->order_by('user_feedback.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /user_feedback ------------ */



    /* ---------- user_ratings ------------ */

    public function all_user_ratings($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'ratings'
        );
        $search_1 = array
            (
            1 => 'user_ratings.order_id',
            2 => 'users.first_name',
            3 => 'user_ratings.ratings',
            4 => 'user_ratings.reviews',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('user_ratings.*,users.first_name')->from('user_ratings')->join('users', 'users.id=user_ratings.user_id', 'left')->order_by('user_ratings.id', 'desc')->get()->num_rows();
            //echo $this->db->last_query();exit;
        } else {
            $this->db->select('user_ratings.*,users.first_name,orders.booking_id,orders.created_on as ocreated_on,users.id as u_id')->join('users', 'users.id=user_ratings.user_id', 'left')->join('orders', 'orders.id=user_ratings.order_id', 'left')->from('user_ratings')->order_by('user_ratings.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        //echo $this->db->last_query();exit;
        return $result;
    }

    /* ---------- /user_ratings ------------ */


    /* ---------- user_vehicles ------------ */

    public function all_user_vehicles($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'user_id'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'user_vehicles.country',
            3 => 'user_vehicles.number_plate',
            4 => 'users.mobile',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('user_vehicles.id,users.mobile,users.first_name')->join('users', 'users.id = user_vehicles.user_id')->from('user_vehicles')->order_by('user_vehicles.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('user_vehicles.*,users.mobile,users.first_name,vehicle_makes.title as make_id,vehicle_models.title as model_id,users.id as u_id')->join('users', 'users.id = user_vehicles.user_id')->from('user_vehicles')->join('vehicle_makes', 'vehicle_makes.id = user_vehicles.make_id')->join('vehicle_models', 'vehicle_models.id = user_vehicles.model_id')->order_by('user_vehicles.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /user_vehicles ------------ */
    /* ---------- vehicle_makes ------------ */

    public function all_vehicle_makes($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'title'
        );
        $search_1 = array
            (
            1 => 'title'
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('vehicle_makes.id')->from('vehicle_makes')->order_by('vehicle_makes.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('vehicle_makes.*')->from('vehicle_makes')->order_by('vehicle_makes.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function insert_vehicle_makes($data) {
        $this->db->insert('vehicle_makes', $data);
        //echo $this->db->last_query();exit;
        return true;
    }

    public function edit_vehicle_makes() {
        $this->db->order_by('id', 'desc');
        $this->db->where('id', $this->uri->segment(4));
        $query = $this->db->get('vehicle_makes');
        return $query->row();
    }

    public function update_vehicle_makes($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('vehicle_makes', $data);
        return true;
    }

    public function delete_vehicle_makes() {
        $this->db->where('id', $this->uri->segment(4));
        //$this->db->where('id', $this->uri->segment(4));
        $this->db->delete('vehicle_makes');
        return true;
    }

    public function vehicle_makes() {
        $this->db->order_by('id', 'desc');

        $query = $this->db->get('vehicle_makes');
        return $query->result();
    }

    /* ---------- /vehicle_makes ------------ */

    /* ---------- vehicle_models ------------ */

    public function all_vehicle_models($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'make_id'
        );
        $search_1 = array
            (
            1 => 'vehicle_makes.title',
            2 => 'vehicle_models.title',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('vehicle_models.id')->from('vehicle_models')->join('vehicle_makes', 'vehicle_makes.id = vehicle_models.make_id')->order_by('vehicle_models.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('vehicle_models.*,vehicle_makes.title as vehicle_title,vehicle_makes.vehicle_type as vehicle_type')->from('vehicle_models')->join('vehicle_makes', 'vehicle_makes.id = vehicle_models.make_id')->order_by('vehicle_models.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function insert_vehicle_models($data) {
        $this->db->insert('vehicle_models', $data);
        //echo $this->db->last_query();exit;
        return true;
    }

    public function edit_vehicle_models() {
        $this->db->order_by('vehicle_models.id', 'desc');
        $this->db->where('vehicle_models.id', $this->uri->segment(4));
        $this->db->join('vehicle_makes', 'vehicle_makes.id=vehicle_models.make_id');
        $this->db->select('vehicle_makes.vehicle_type as vehicle_type,vehicle_models.*');
        $query = $this->db->get('vehicle_models');
        return $query->row();
    }

    public function update_vehicle_models($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('vehicle_models', $data);
        return true;
    }

    public function delete_vehicle_models() {
        $this->db->where('id', $this->uri->segment(4));
        //$this->db->where('id', $this->uri->segment(4));
        $this->db->delete('vehicle_models');
        return true;
    }

    public function vehicle_models() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('vehicle_models');
        return $query->result();
    }

    /* ---------- /vehicle_models ------------ */

    /* ---------- otherusers ------------ */

    public function all_inactiveusers($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'email_id'
        );
        $search_1 = array
            (
            1 => 'first_name',
            2 => 'last_name',
            3 => 'mobile',
            4 => 'email_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('users.id')->from('users')->where('users.status', '0')->order_by('users.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('users.*,cities.name as cityname,countries.name as countryname,states.name as states_name')->where('users.status', '0')->join('cities', 'cities.id = users.city_id', 'left')->join('countries', 'countries.id = users.country_id', 'left')->join('states', 'states.id = users.state_id', 'left')->from('users')->order_by('users.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function all_otherusers($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'email_id'
        );
        $search_1 = array
            (
            1 => 'first_name',
            2 => 'last_name',
            3 => 'mobile',
            4 => 'email_id',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('users.id')->from('users')->where('users.delete_status', '1')->order_by('users.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('users.*,cities.name as cityname,countries.name as countryname,states.name as states_name')->join('cities', 'cities.id = users.city_id', 'left')->join('countries', 'countries.id = users.country_id', 'left')->join('states', 'states.id = users.state_id', 'left')->from('users')->where('users.delete_status', '1')->order_by('users.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function user_details() {
        $this->db->order_by('users.id', 'desc');
        $this->db->where('users.id', $this->uri->segment(4));
        $this->db->join('countries', 'countries.id=users.country_id', 'left');
        $this->db->join('states', 'states.id=users.state_id', 'left');
        $this->db->join('cities', 'cities.id=users.city_id', 'left');
        $this->db->select('users.*, countries.name as countryname, cities.name as cityname,states.name as states_name');
        $query = $this->db->get('users');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function edit_verification() {
        $this->db->order_by('id', 'desc');
        $this->db->where('id', $this->uri->segment(4));
        //$this->db->where('sub_categories.delete_status', 1);
        $query = $this->db->get('users');
        return $query->row();
    }

    public function update_verification($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return true;
    }

    /* ---------- /otherusers ------------ */
    /* ---------- otherorders ------------ */

    public function all_otherorders($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {

            return $this->db->select('orders.id,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->
                            where('(orders.mode!="city" AND orders.ride_type!="now")')->
                            where('((orders.mode="city" AND orders.ride_type="later")OR(orders.mode="outstation" AND orders.ride_type="now")OR(orders.mode="outstation" AND orders.ride_type="later"))')
                            ->
                            where('orders.rider_id!=', '0')->
                            from('orders')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->
                    where('(orders.mode!="city" AND orders.ride_type!="now")')->
                    where('((orders.mode="city" AND orders.ride_type="later")OR(orders.mode="outstation" AND orders.ride_type="now")OR(orders.mode="outstation" AND orders.ride_type="later"))')
                    ->
                    where('orders.rider_id!=', '0')->
                    from('orders')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function order_details() {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=orders.user_id', 'left');
        $this->db->join('users as u', 'u.id=orders.rider_id', 'left');
        $this->db->select('users.first_name, users.last_name,u.first_name as ufirst_name, u.last_name as ulast_name,orders.*,

        (orders.total_amount-(orders.tax+orders.payment_gateway_commision+orders.ciao_commission)) as rider_amount

        ');
        $query = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /otherorders ------------ */



    /* ---------- instantorders ------------ */

    public function all_instantorders($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            // echo $this->db->last_query();
            // exit;

            return $this->db->select('orders.id,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id')->join('users as u', 'u.id=orders.rider_id')->
                            where('orders.mode', 'city')->where('orders.ride_type', 'now')->
                            from('orders')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id')->join('users as u', 'u.id=orders.rider_id')->
                    where('orders.mode', 'city')->where('orders.ride_type', 'now')->
                    from('orders')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function all_bookings($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            // echo $this->db->last_query();
            // exit;

            return $this->db->select('orders.id,users.first_name,u.first_name as ufirst_name')
                            ->join('users', 'users.id=orders.user_id', 'left')
                            ->join('users as u', 'u.id=orders.rider_id', 'left')->
                            from('orders')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.first_name,u.first_name as ufirst_name')
                    ->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->
                    from('orders')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
//        echo $this->db->last_query();
//        die;
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- /instantorders ------------ */

    public function payment_details() {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=orders.user_id', 'left');
        $this->db->join('users as u', 'u.id=orders.rider_id', 'left');

        $this->db->select('orders.*,users.*,u.first_name as rname,u.payment_mode as rpayment_mode,orders.created_on as ocreated_on');
        $query = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function all_payment_details($pdata, $getcount = null) {
        $date = date('Y-m-d');
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            //echo $this->db->last_query();exit;
            return $this->db->select('orders.id,users.first_name')->join('users', 'users.id=orders.user_id', 'left')->from('orders')->where('orders.refund_status', 'pending')->where('orders.mode', 'city')->
                            where('orders.ride_type !=', 'later')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select("orders.*,users.*,u.id as r_id,u.first_name as rname,u.payment_mode as rpayment_mode,orders.id as oid,orders.created_on as ocreated_on, round((orders.total_amount - orders.cancellation_charges), 2) as refund_amount,(6 - DATEDIFF('$date', orders.accepted_date )) as days_left")->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'cancelled by user')->where('orders.mode', 'city')->where('orders.ride_type !=', 'later')->where('orders.refund_status', 'pending')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $result->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function all_user_refunds($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('orders.id,users.first_name')->join('users', 'users.id=orders.user_id', 'left')->from('orders')->where('orders.status', 'cancelled by user')->where('orders.refund_status', 'paid')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.*,u.first_name as rname,u.payment_mode as rpayment_mode,users.id as u_id,u.id as r_id,orders.id as oid,orders.created_on as ocreated_on, round((orders.total_amount - orders.cancellation_charges), 2) as refund_amount')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'cancelled by user')->where('orders.refund_status', 'paid')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $result->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function all_rider_pending_payments($pdata, $getcount = null) {
        $date = date('Y-m-d H:i:s');
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('orders.id,users.first_name,users.id as u_id')->join('users', 'users.id=orders.user_id', 'left')->join('user_bank_details as ubd', 'ubd.user_id = orders.rider_id', 'left')->from('orders')->where('orders.status', 'completed')->where('orders.payment_status', 'paid')->where('orders.rider_payment_status', 'pending')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select("orders.*,users.*,u.first_name as rname, u.mobile as rmobile,u.id as r_id, u.payment_mode as rpayment_mode,orders.id as oid,orders.created_on as ocreated_on, DATE_ADD(orders.accepted_date, INTERVAL u.payment_mode DAY) as payment_date, DATEDIFF('$date', orders.accepted_date) as days, (u.payment_mode - DATEDIFF('$date', orders.accepted_date )) as days_left, ubd.*,users.id as u_id,(total_amount-(tax+ciao_commission)+payment_gateway_commision) as rider_amount")->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->join('user_bank_details as ubd', 'ubd.user_id = orders.rider_id', 'left')->from('orders')->where('orders.status', 'completed')->where('orders.payment_status', 'paid')->where('orders.rider_payment_status', 'pending')->order_by('payment_date', 'asc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $result->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function all_rider_paid_payments($pdata, $getcount = null) {
        $date = date('Y-m-d H:i:s');
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('orders.id,users.first_name,users.id as u_id')->join('users', 'users.id=orders.user_id', 'left')->from('orders')->where('orders.status', 'completed')->where('orders.rider_payment_status', 'paid')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select("orders.*,users.*,u.first_name as rname, u.mobile as rmobile,u.id as r_id, u.payment_mode as rpayment_mode,orders.id as oid,orders.created_on as ocreated_on, DATEDIFF('$date', orders.accepted_date) as days, (u.payment_mode - DATEDIFF('$date', orders.accepted_date )) as days_left,users.id as u_id, (total_amount-(tax+ciao_commission)+payment_gateway_commision) as amount")->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'completed')->where('orders.payment_status', 'paid')->where('orders.rider_payment_status', 'paid')->order_by('payment_date', 'asc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get();
        //echo $this->db->last_query();exit;
        $result = $result->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    /* ---------- cancelled_rider ------------ */

    public function all_cancelled_rider($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('orders.id,users.first_name,u.first_name as uname,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'cancelled by rider')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.first_name,u.first_name as uname,u.first_name as ufirst_name')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'cancelled by rider')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        //echo $this->db->last_query();exit;
        return $result;
    }

    public function cancelled_rider_details() {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=orders.user_id', 'left');
        $this->db->join('users as u', 'u.id=orders.rider_id', 'left');
        $this->db->select('users.first_name,u.first_name as ufirst_name,orders.*');
        $query = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /cancelled_rider ------------ */
    /* ---------- cancelled_user ------------ */

    public function all_cancelled_user($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('orders.id,users.first_name')->join('users', 'users.id=orders.user_id', 'left')->from('orders')->where('orders.status', 'cancelled by user')->order_by('orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('orders.*,users.first_name,u.first_name as uname,u.first_name as ufirst_name,u.id as r_id')->join('users', 'users.id=orders.user_id', 'left')->join('users as u', 'u.id=orders.rider_id', 'left')->from('orders')->where('orders.status', 'cancelled by user')->order_by('orders.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        //echo $this->db->last_query();exit;
        return $result;
    }

    public function cancelled_user_details() {
        $this->db->order_by('orders.id', 'desc');
        $this->db->where('orders.id', $this->uri->segment(4));
        $this->db->join('users', 'users.id=orders.user_id', 'left');
        $this->db->join('users as u', 'u.id=orders.rider_id', 'left');
        $this->db->select('users.last_name, users.first_name,u.first_name as ufirst_name, u.last_name as ulast_name,orders.*');
        $query = $this->db->get('orders');
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* ---------- /cancelled_user ------------ */

    public function change_user_status($user_id, $status) {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('status' => $status));
        if ($status == "0") {
            $message = "Your account has been put on hold. Please contact administrator.";
            //$this->send_notifications($user_id, $message);
        }
        //echo $this->db->last_query();exit;
        return true;
    }

    /* ---------- Push Notifications ------------ */

    public function all_push_notifications($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'notifications.title',
            3 => 'notifications.title',
        );
        $search_1 = array
            (
            1 => 'notifications.title',
            2 => 'notifications.description',
        );
        /* if(isset($pdata['search_text_1'])!="")
          {
          if($pdata['search_on_1'] != 5 && $pdata['search_on_1'] != 6)
          {
          $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1'] );
          }
          else
          {


          }
          } */
        if ($getcount) {
            return $this->db->select('notifications.*')->from('notifications')->order_by('notifications.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('notifications.*')->from('notifications')->order_by('notifications.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        //echo $this->db->last_query();exit;
        return $result;
    }

    public function send_push_notification($description = NULL, $title = NULL, $sent_to) {
        date_default_timezone_set("Asia/Kolkata");

        $reg_ids = array();
        $ios_reg_ids = array();
        $this->db->select("token, ios_token");
        if ($sent_to == "user") {
            $users = $this->db->get("users");
            //echo $this->db->last_query();exit();
        } else {
            $users = $this->db->get("users");
        }
        $r = array();
        if ($users->num_rows() > 0) {
            $result = $users->result();
            //print_r($result);exit();
            foreach ($result as $r) {
                if (!empty($r->token) && $r->token != "-" && $r->token != "") {
                    array_push($reg_ids, $r->token);
                }
                if (!empty($r->ios_token) && $r->ios_token != "-" && $r->ios_token != "") {
                    array_push($ios_reg_ids, $r->ios_token);
                }
            }
        }

        $this->db->insert('notifications', array('description' => $description, 'title' => $title, 'sent_to' => $sent_to, 'created_on' => date('Y-m-d H:i:s')));
        //echo $this->db->last_query();exit();
        //$rowsMsg[] = $message;
        $ios_message = $description;

        //$jsonData = json_encode(array('json' => $rowsMsg));
        $message = array('title' => $title, 'description' => $description, 'type' => 'admin');
        //var_dump($message);exit;
        //var_dump($reg_ids);
        $pushStatus = $this->sendAndroidNotification($reg_ids, $message);
        $iosPushStatus = $this->sendIosNotification($ios_reg_ids, $ios_message, $title);
        //print_r($iosPushStatus);exit;
        return true;
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

    /* ---------- /Push Notifications ------------ */
    /* ---------- support ------------ */

    public function all_support($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'first_name',
            1 => 'last_name',
            2 => 'mobile',
            3 => 'email_id'
        );
        $search_1 = array
            (
            1 => 'first_name',
            2 => 'last_name',
            3 => 'mobile',
            4 => 'email_id'
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('support.id')->from('support')->join('users', 'users.id = support.user_id')->order_by('support.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('support.*, users.first_name, users.last_name, users.mobile, users.email_id')->from('support')->join('users', 'users.id = support.user_id')->order_by('support.id', 'desc');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }

    public function insert_support($data) {
        $this->db->insert('support', $data);
        //echo $this->db->last_query();exit;
        return true;
    }

    public function edit_support() {
        $this->db->order_by('support.id', 'desc');
        $this->db->where('support.id', $this->uri->segment(4));
        $this->db->join('vehicle_makes', 'vehicle_makes.id=support.make_id');
        $this->db->select('vehicle_makes.vehicle_type as vehicle_type,support.*');
        $query = $this->db->get('support');
        return $query->row();
    }

    public function update_support($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('support', $data);
        return true;
    }

    public function delete_support() {
        $this->db->where('id', $this->uri->segment(4));
        //$this->db->where('id', $this->uri->segment(4));
        $this->db->delete('support');
        return true;
    }

    public function support() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('support');
        return $query->result();
    }

    /* ---------- /support ------------ */

    public function photo_gal($per_page, $start) {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        $this->db->limit($per_page, $start);
        $this->db->order_by('id', 'desc');
        $result_set = $this->db->get('users');
        return $result_set->result();
    }
    
    
    public function photo_gal_count() {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        $result_set = $this->db->get('users');
        

        
        return $result_set->num_rows();
    }
    
    
    
    /* -------------- taxi Users -------*/
    #rows count for pagination

    public function photo_gal_count_taxi_drivers() {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        
        $this->db->having('user_type', 'driver');

        $result_set = $this->db->get('users');
        
//       print_r($result_set->num_rows());    
//       exit;
//        
        return $result_set->num_rows();
    }
    
    
    public function photo_gal_taxi_drivers($per_page, $start) {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        $this->db->limit($per_page, $start);
        $this->db->order_by('id', 'desc');
        $this->db->having('user_type', 'driver');
        $result_set = $this->db->get('users');
        
//       print_r($this->db->last_query());    
//exit;
        
         return $result_set->result();
    }
    
    
    
    public function all_taxi_drivers_details($pdata, $getcount = null) {
        $columns = array
            (
            0 => 'account_number'
        );
        $search_1 = array
            (
            1 => 'users.first_name',
            2 => 'users.last_name',
            3 => 'users.mobile',
            4 => 'users.email_id',
            5 => 'users.created_on',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            return $this->db->select('users.*')->from('users')->order_by('users.id', 'desc')->where('users.user_type', 'driver')->get()->num_rows();
        } else {
            $this->db->select('users.*')->from('users')->order_by('users.id', 'desc')->where('users.user_type', 'driver');
        }
        if (isset($pdata['length'])) {
            $perpage = $pdata['length'];
            $limit = $pdata['start'];
            $generatesno = $limit + 1;
            $orderby_field = $columns[$pdata['order'][0]['column']];
            $orderby = $pdata['order']['0']['dir'];
            $this->db->order_by($orderby_field, $orderby);
            $this->db->limit($perpage, $limit);
        } else {
            $generatesno = 0;
        }
        $result = $this->db->get()->result_array();
        
        
        foreach ($result as $key => $values) {
            $result[$key]['sno'] = $generatesno++;
        }
        return $result;
    }
    
    
    
        /* -------------- passengers -------*/

    
    
    public function photo_gal_count_passengers() {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        
        $this->db->having('user_type', 'taxi');

        $result_set = $this->db->get('users');
        
//       print_r($result_set->num_rows());    
//       exit;
//        
        return $result_set->num_rows();
    }
    
    
    public function photo_gal_passengers($per_page, $start) {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        $this->db->limit($per_page, $start);
        $this->db->order_by('id', 'desc');
        $this->db->having('user_type', 'taxi');
        $result_set = $this->db->get('users');
        
/*       print_r($this->db->last_query());    
       exit;
        */
        
        return $result_set->result();
    }    
    
    
    
        /* -------------- all private users -------*/

    
    
    public function photo_gal_count_all_private_users() {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        
        $this->db->having('user_type', 'sharing');

        $result_set = $this->db->get('users');
        
//       print_r($result_set->num_rows());    
//       exit;
//        
        return $result_set->num_rows();
    }
    
    
    public function photo_gal_all_private_users($per_page, $start) {
        if (!empty($this->input->post())) {
            $search_string = $this->input->get_post('search_string');
            if (!empty($search_string)) {
                $this->db->like("first_name", $search_string, 'both');
                $this->db->or_like("last_name", $search_string, 'both');
                $this->db->or_like("mobile", $search_string, 'both');
            }
//            print_r($_POST);
//            die;
        }
        $this->db->limit($per_page, $start);
        $this->db->order_by('id', 'desc');
        $this->db->having('user_type', 'sharing');
        $result_set = $this->db->get('users');
        
/*       print_r($this->db->last_query());    
       exit;
        */
        
        return $result_set->result();
    }



  
}

?>