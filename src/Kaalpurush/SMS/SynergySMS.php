<?php namespace Kaalpurush\SMS;

class SynergySMS extends SMS{

	private $api_key;
	private $api_secret;

	function __construct($api_key,$api_secret) 
	{
		parent::__construct();
        $this->api_key=$api_key;
		$this->api_secret=$api_secret;
	}
    
    function sendSMS($msgdata)
    {
		$reg_id=parent::registerSMS($msgdata);
		
		$data=array( "key" =>  $this->api_key , "countrycode" => "BD" , "numbers" => $msgdata['to'] , "message" => urlencode($msgdata['msg']) ); 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "http://bulksms.synergyinterface.com/sms_db/bulk_send_api.php" ); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: multipart/form-data' )); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1); 
		$response  = curl_exec($ch); 
		curl_close($ch);
		
		if($this->debug) echo 'DEBUG:['.$response.']';
		
		if(preg_match('/true/',$response))
			$status=1;
		else
			$status=0;
			
		parent::changeStatus($reg_id,$status);
		return $status;
	}

}