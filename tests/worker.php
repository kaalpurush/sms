<?php
require_once __DIR__ . '../vendor/autoload.php';

$worker = new GearmanWorker();
$worker->addServer();

$worker->addFunction("sendSMS", function(GearmanJob $job) {
    $workload = json_decode($job->workload(),true
	$provider=$workload['provider'];
	$api_key=$workload['api_key'];
	$api_secret=$workload['api_secret'];
	$sms= new Kaalpurush\SMS\$provider($api_key,$api_secret);
	$sms->setFrom('E.HAQUE');
	$sms->sendSMS($workload['msgdata']);
});

while ($worker->work());