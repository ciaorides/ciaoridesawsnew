<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if( ! function_exists('MemberID')){
    function MemberID(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return $ci->session->userdata('member_id');
		}
		return false;	
	}
}

if( ! function_exists('isMember')){
    function isMember(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return $ci->session->userdata('member_id');
		}
		return false;	
	}
}

if( ! function_exists('isUser')){
    function isUser(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return $ci->session->userdata('member_type') == 'USER' ? $ci->session->userdata('member_id') : false;
		}
		return false;	
	}
}

if( ! function_exists('isVendor')){
    function isVendor(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return in_array($ci->session->userdata('member_type'), array('VENDOR-OWNER')) ? $ci->session->userdata('member_id') : false;
		}
		return false;	
	}
}

if( ! function_exists('isVenue')){
    function isVenue(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return in_array($ci->session->userdata('member_type'), array('VENUE-OWNER', 'VENUE-MANAGER')) ? $ci->session->userdata('member_id') : false;
		}
		return false;	
	}
}

if( ! function_exists('MemberName')){
    function MemberName(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return ucwords(strtolower($ci->session->userdata('member_name')));
		}
		return false;	
	}
}

if( ! function_exists('MemberType')){
    function MemberType(){
		
		$ci=& get_instance();
		
		if($ci->session->userdata('is_logged_in')){
			return $ci->session->userdata('member_type');
		}
		return false;	
	}
}

