<?php namespace Kaalpurush\SMS;

class AirtelSMS extends SMS{
	 
	const PROVIDER='airtel';	
	private $apikey = AIRTEL_KEY;
	private $passkey = AIRTEL_SECRET;
	
	function __construct() 
	{
        parent::__construct();
	}
    
    function sendSMS($msgdata)
    {		
		$reg_id=parent::registerSMS(self::PROVIDER,$msgdata);
		
		$api  = 'http://portals.bd.airtel.com/msdpapi?REQUESTTYPE=SMSSubmitReq&USERNAME='.$this->apikey.'&PASSWORD='.$this->passkey.'&MOBILENO=';
		$api .= $msgdata['to'].'&MESSAGE='.urlencode($msgdata['msg']);
		$api .= '&TYPE=0&ORIGIN_ADDR='.urlencode(isset($msgdata['from'])?$msgdata['from']:$this->from);
		$response = file_get_contents($api);
		
		if(preg_match('/Accepted/',$response))
			$status=1;
		else
			$status=0;
			
		parent::changeStatus($reg_id,$status);
		return $status;
	}
	
	function sendSMSFromCSV($csvfile)
    {
		$count=0;
		if ((!empty($csvfile) && $handle = fopen($csvfile, "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 1024, ",")) !== FALSE) {
			
				$msgdata['from'] = $this->from;
				$msgdata['to'] = $data[0];
				$msgdata['msg']   = $data[1];				
				
				//$this->sendSMS($msgdata);
				
				$client = new GearmanClient();
				$client->addServer();
				$result = $client->doBackground("sendSMS", json_encode($msgdata));
				if($result) $count++;
				
			}
			fclose($handle);
			 
		}
		return $count;
	}

}

?>
