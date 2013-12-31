<?php
require_once __DIR__ . '/vendor/autoload.php';
use Kaalpurush\SMS\AirtelSMS;

if($_FILES){
	$file = $_FILES['file']['tmp_name'];
	$sms= new AirtelSMS();
	$sms->setFrom('E.HAQUE');
	$count=$sms->sendSMSFromCSV($file);
	die('Successfully queued: '.$count);
}

$sms= new AirtelSMS();
$sms->setFrom('E.HAQUE');
$msgdata['to'] = '01673911244';
$msgdata['msg']   = 'howar you?';
echo $sms->sendSMS($msgdata);
?>

<form action="" method="post" enctype="multipart/form-data">
Upload CSV <input type="file" name="file" />
<input type="submit" value="Send" />
</form>