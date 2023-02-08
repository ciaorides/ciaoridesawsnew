<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if( ! function_exists('CallZohoAPI')){
    function CallZohoAPI($Method = NULL, $params = array(), $type = "GET"){
		
		if($Method){
			$url = "https://crm.zoho.com/crm/private/json/".$Method;

			$config = array("authtoken" => "933a4693c6c6d66c7fa02eadffd37488", "scope" => "crmapi");

			$params = array_merge($config, $params);
			
			if($type == "POST"){			
				$ch = curl_init($url);

				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			} else {
				$url = sprintf("%s?%s", $url, http_build_query($params));				
				$ch = curl_init($url);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			if( ($result = curl_exec($ch) ) === false){
				//$result = 'NO RESPONSE!';
				die('Curl failed ' . curl_error($ch));
			}
			curl_close($ch);
			return $result;
		}
		return false;
	}
}

