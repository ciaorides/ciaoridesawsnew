<?php

class Vehiclemodel extends CI_Model {
    
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

}