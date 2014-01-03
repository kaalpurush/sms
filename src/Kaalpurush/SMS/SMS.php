<?php namespace Kaalpurush\SMS;

abstract class SMS{

	var $from='';
	
	function __construct() 
	{
        //parent::__construct();
	}
	
	function setFrom($from)
	{
		$this->from=$from;
	}
    
    function registerSMS($provider,$msg)
    {
        return $msg['to'];
    } 

	abstract function sendSMS($msgdata);

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

?>
