<?php
require_once '../vendor/autoload.php';

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("sendSMS", function(GearmanJob $job) {
    $workload=json_decode($job->workload(),true);
	$provider=$workload['provider'];
	$api_key=$workload['api_key'];
	$api_secret=$workload['api_secret'];
	$class="Kaalpurush\SMS\$provider";
	$sms=new $class($api_key,$api_secret);
	$sms->setFrom('E.HAQUE');
	$sms->sendSMS($workload['msgdata']);
});

while ($worker->work());