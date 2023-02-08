<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Track extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        echo 'Poprebates';

        $this->db->limit(100);
        $this->db->order_by('TrackID', 'desc');
        $res = $this->db->get('mobile_tracking');

        echo '<pre>';
        if ($res->num_rows() > 0)
            foreach ($res->result() as $r) {
                echo 'Track ID: ' . $r->TrackID . '<br>';
                echo 'Track Method: ' . $r->Method . '<br>';
                echo 'Track Date: ' . $r->Date . '<br>';

                echo 'Request: <br>';
                print_r(unserialize($r->Request));

                echo 'Response: <br>';
                print_r(unserialize($r->Response));
                echo '<br><br><hr><br>';
            }
    }

}
