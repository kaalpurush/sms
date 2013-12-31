<?php
require_once __DIR__ . '/vendor/autoload.php';
use Kaalpurush\SMS\AirtelSMS;

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("sendSMS", function(GearmanJob $job) {
    $msgdata = json_decode($job->workload(),true);
    $sms= new AirtelSMS();
	$sms->setFrom('E.HAQUE');
	$sms->sendSMS($msgdata);
});

while ($worker->work());