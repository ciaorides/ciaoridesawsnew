<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('TrackResponse')) {

    function TrackResponse($Req = '', $Res = '') {
        $ci = & get_instance();

        $TrackData = array(
            'Method' => $ci->router->fetch_method(),
            'Request' => serialize($Req),
            'Response' => serialize($Res),
            'Date' => date('Y-m-d H:i:s')
        );
//        $ci->db->insert('mobile_tracking', $TrackData);
        return true;
    }

}


if (!function_exists('IsVenueExists_By_Email_Phone')) {

    function IsVenueExists_By_Email_Phone($Email = NULL, $Phone = NULL) {

        if ($Email) {
            $ci = & get_instance();
            $ci->db->where(" (Email = '$Email' OR Phone = '$Phone' ) ");
            $Result = $ci->db->get('venue_admins');
            if ($Result->num_rows() > 0)
                return true;
        }
        return false;
    }

}

if (!function_exists('IsVendorExists_By_Email_Phone')) {

    function IsVendorExists_By_Email_Phone($Email = NULL, $Phone = NULL) {

        if ($Email) {
            $ci = & get_instance();
            $ci->db->where(" (Email = '$Email' OR Phone = '$Phone' ) ");
            $Result = $ci->db->get('vendor_admins');
            if ($Result->num_rows() > 0)
                return true;
        }
        return false;
    }

}


if (!function_exists('VenueTypeName_By_ID')) {

    function VenueTypeName_By_ID($VenueTypeID = NULL) {

        if ($VenueTypeID) {
            $ci = & get_instance();
            $Result = $ci->db->get_where('venue_types', array('VenueTypeID' => $VenueTypeID));
            if ($Result->num_rows() > 0)
                return $Result->row()->Name;
        }
        return false;
    }

}


if (!function_exists('Trip_ID')) {

    function Trip_ID() {

        $result= mt_rand(100000, 999999);
        return $result;
    }

}


