<?php
	// Include the Twilio PHP library
	require 'SDK/Services/Twilio.php';

	// Twilio REST API version
	$version = "2010-04-01";

	// Set our Account SID and AuthToken
	$sid = 'AC916b36d84c99c72c01d4b77b1ef8171b';
	$token = 'c328becf3e4c7d3823713ec1a2a0c751';
	
	// A phone number you have previously validated with Twilio
	$phonenumber = '48128810857';
	
	// Instantiate a new Twilio Rest Client
	$client = new Services_Twilio($sid, $token, $version);

//        $friendlist = array('48509988585','48605965576','48889708906');
        $friendlist = array('48509988585');

        $name = 'Simon';
        $location = 'Facebook Hack for developers';
        
        foreach ($friendlist as $friend)
	try {
		// Initiate a new outbound call
		$call = $client->account->calls->create(
			$phonenumber, // The number of the phone initiating the call
    			$friend, // The number of the phone receiving call
			'http://vilq.net/twilio/message.php?name='.urlencode($name).'&location='.urlencode($location) // The URL Twilio will request when the call is answered
		);
		echo 'Started call: ' . $call->sid."<br/>";
	} catch (Exception $e) {
		echo 'Error: ' . $e->getMessage();
	}