<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if( ! function_exists('CCAvenue_Payment')){
    function CCAvenue_Payment($OrderData = NULL, $ReturnPath = 'payment_return'){
		$ci=& get_instance();
		
		$ci->load->helper('crypto');
		
		if($OrderData){ 
			
			$merchant_id 	= '114583';
			$working_key	= 'AE48F8E10F4E073A5E4715E1C9A004D6';//Shared by CCAVENUES
			$access_code	= 'AVCQ67DK75AS72QCSA';//Shared by CCAVENUES
			
			$OrderData['merchant_id'] 	= $merchant_id;
			$OrderData['currency'] 		= 'INR';
			$OrderData['language'] 		= 'EN';
			$OrderData['redirect_url'] 	= base_url('response/'.$ReturnPath);
			$OrderData['cancel_url'] 	= base_url('response/'.$ReturnPath);
			
			//echo '<pre>'; print_r($OrderData); exit;

			foreach($OrderData as $key => $value){
				$merchant_data .= $key.'='.$value.'&';
			}
			
			$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

  			$action = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
			
			$FormData = array(
				'encRequest' 	=> $encrypted_data,
				'access_code' 	=> $access_code
			);
				
			$html = form_open($action, 'name="paymentGatewayForm"', $FormData);
			$html .= form_close();
            
            return $html;
		}
		
	}
}



if( ! function_exists('CCAvenuePaymentOptionCode')){
    function CCAvenuePaymentOptionCode($Type = NULL){
		
		switch($Type){
			case 'NB': return 'OPTNBK'; break;
			case 'CC': return 'OPTCRDC'; break;
			case 'DC': return 'OPTDBCRD'; break;
			default: return '';
		}
		return false;
	}
}



if( ! function_exists('CCAvenueCardTypeCode')){
    function CCAvenueCardTypeCode($Type = NULL){
		
		switch($Type){
			case 'NB': return 'NBK'; break;
			case 'CC': return 'CRDC'; break;
			case 'DC': return 'DBCRD'; break;
			default: return '';
		}
		return false;
	}
}
