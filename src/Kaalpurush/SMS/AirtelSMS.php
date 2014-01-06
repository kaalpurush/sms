<?php namespace Kaalpurush\SMS;

class AirtelSMS extends SMS{

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
		
		$api  = 'http://portals.bd.airtel.com/msdpapi?REQUESTTYPE=SMSSubmitReq&USERNAME='.$this->api_key.'&PASSWORD='.$this->api_secret.'&MOBILENO=';
		$api .= $msgdata['to'].'&MESSAGE='.urlencode($msgdata['msg']);
		$api .= '&TYPE=0&ORIGIN_ADDR='.urlencode(isset($msgdata['from'])?$msgdata['from']:$this->from);
		$response = file_get_contents($api);
		
		if($this->debug) echo 'DEBUG:['.$response.']';
		
		if(preg_match('/Accepted/',$response))
			$status=1;
		else
			$status=0;
			
		parent::changeStatus($reg_id,$status);
		return $status;
	}

}