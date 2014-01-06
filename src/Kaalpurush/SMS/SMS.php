<?php namespace Kaalpurush\SMS;

abstract class SMS{

	private $from='';
	private $debug=false;
	private $extra=null;
	
	function __construct() 
	{
        //parent::__construct();
	}
	
	function setExtra($extra)
	{
		$this->extra=$extra;
	}
	
	function setDebug($bool)
	{
		$this->debug=$bool;
	}
	
	function setFrom($from)
	{
		$this->from=$from;
	}
    
    function registerSMS($msg)
    {
        return $msg['to'];
    } 

	abstract function sendSMS($msgdata);
	
	function sendSMSFromCSV($csvfile)
    {
		$count=0;
		if ((!empty($csvfile) && $handle = fopen($csvfile, "r")) !== FALSE) {
			
			while (($data = fgetcsv($handle, 1024, ",")) !== FALSE) {
			
				$workload['msgdata']['from'] = $this->from;
				$workload['msgdata']['to'] = $data[0];
				$workload['msgdata']['msg'] = $data[1];	
				$workload['provider']=get_class($this);
				$workload['api_key']=$this->api_key;
				$workload['api_secret']=$this->api_secret;
				if($this->$extra!=null) $workload['extra']=$this->$extra;
				$client = new GearmanClient();
				$client->addServer();
				$result = $client->doBackground("sendSMS", json_encode($workload));
				if($result) $count++;
				
			}
			fclose($handle);
			 
		}
		return $count;
	}

	function changeStatus($reg_id,$status)
    {
        $this->log(date('d-m-Y H:i:s',time()).'>'.$reg_id.':'.$status);
    }  
	
	function log($data) 
	{	
		$file='log.txt';
		$fh = fopen($file, 'a') or die("can't open file");
		fwrite($fh, $data."\n");
		fclose($fh);
	}
    
}
