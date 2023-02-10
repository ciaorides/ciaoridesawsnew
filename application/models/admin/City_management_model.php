<?php

class City_management_model extends CI_Model {
    /* ---------- Admin Details ------------ */

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
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.type','taxi')->where('rides.mode','city')->where('rides.vehicle_type',$pdata['uri_seg'])->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.type','taxi')->where('rides.mode','city')->where('rides.vehicle_type',$pdata['uri_seg'])->order_by('rides.id', 'desc');
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
            1 => 'taxi_orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {
            // echo $this->db->last_query();
            // exit;

            return $this->db->select('taxi_orders.id,users.first_name,u.first_name as ufirst_name')
                            ->join('users', 'users.id=taxi_orders.user_id', 'left')
                            ->join('users as u', 'u.id=taxi_orders.rider_id', 'left')->
                            from('taxi_orders')->where('mode','city')->where('vehicle_type',$pdata['uri_seg'])->order_by('taxi_orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('taxi_orders.*,users.first_name,u.first_name as ufirst_name')
                    ->join('users', 'users.id=taxi_orders.user_id', 'left')->join('users as u', 'u.id=taxi_orders.rider_id', 'left')->
                    from('taxi_orders')->where('mode','city')->where('vehicle_type',$pdata['uri_seg'])->order_by('taxi_orders.id', 'desc');
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


    /* ---------- otherorders ------------ */

    public function all_otherorders($pdata,$type, $getcount = null) {
        $columns = array
            (
            0 => 'booking_id'
        );
        $search_1 = array
            (
            1 => 'taxi_orders.booking_id',
            2 => 'users.first_name',
        );
        if (isset($pdata['search_text_1']) != "") {
            $this->db->like($search_1[$pdata['search_on_1']], $pdata['search_text_1'], $pdata['search_at_1']);
        }
        if ($getcount) {

            return $this->db->select('taxi_orders.id,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=taxi_orders.user_id', 'left')->join('users as u', 'u.id=taxi_orders.rider_id', 'left')->
                            where('(taxi_orders.mode!="city" AND taxi_orders.ride_type!="now")')->
                            where('((taxi_orders.mode="city" AND taxi_orders.ride_type="later")OR(taxi_orders.mode="outstation" AND taxi_orders.ride_type="now")OR(taxi_orders.mode="outstation" AND taxi_orders.ride_type="later"))')
                            ->
                            where('taxi_orders.rider_id!=', '0')->
                            from('taxi_orders')->order_by('taxi_orders.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('taxi_orders.*,users.first_name,u.first_name as ufirst_name')->join('users', 'users.id=taxi_orders.user_id', 'left')->join('users as u', 'u.id=taxi_orders.rider_id', 'left')->
                    where('(taxi_orders.mode!="city" AND taxi_orders.ride_type!="now")')->
                    where('((taxi_orders.mode="city" AND taxi_orders.ride_type="later")OR(taxi_orders.mode="outstation" AND taxi_orders.ride_type="now")OR(taxi_orders.mode="outstation" AND taxi_orders.ride_type="later"))')
                    ->
                    where('taxi_orders.rider_id!=', '0')->
                    from('taxi_orders')->order_by('taxi_orders.id', 'desc');
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



    /* ---------- all_city_cancellation ------------ */

    public function all_city_cancellation($pdata, $getcount = null) {
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
            return $this->db->select('rides.id,users.first_name')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.type','taxi')->where('rides.mode','city')->where('rides.status','cancelled')->where('rides.vehicle_type',$pdata['uri_seg'])->order_by('rides.id', 'desc')->get()->num_rows();
        } else {
            $this->db->select('rides.*,users.first_name,user_vehicles.number_plate as vehicle_number')->join('users', 'users.id=rides.user_id', 'left')->join('user_vehicles', 'user_vehicles.id=rides.vehicle_id')->from('rides')->where('rides.type','taxi')->where('rides.mode','city')->where('rides.status','cancelled')->where('rides.vehicle_type',$pdata['uri_seg'])->order_by('rides.id', 'desc');
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


}