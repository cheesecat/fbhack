<?php
// Include the Twilio PHP library
require dirname(__FILE__) . '/SDK/Services/Twilio.php';



//get phone numbers from api
//        $friendlist = array('48509988585','48605965576','48889708906');
/*
$friendlist = array('48509988585');


//get name from fb
$name = array('name' => 'Simon', 'surname' => 'Says');

//get location by gps coords
$location = array('name' => 'Facebook Hack for developers', 'street' => 'Marii Konopnickiej 6', 'city' => 'Warsaw'); // places_get_by_coords
*/
function notify_by_twilio($friendlist, $name, $location)
{
    // Twilio REST API version
    $version = "2010-04-01";

// Set our Account SID and AuthToken
    $sid = 'AC916b36d84c99c72c01d4b77b1ef8171b';
    $token = 'c328becf3e4c7d3823713ec1a2a0c751';

// A phone number you have previously validated with Twilio
    $phonenumber = '48128810857';

// Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($sid, $token, $version);

    foreach ($friendlist as $friend) {
        try {
            // Initiate a new outbound call
            $call = $client->account->calls->create(
                $phonenumber, // The number of the phone initiating the call
                $friend, // The number of the phone receiving call
                'http://vilq.net/twilio/message.php?name=' . urlencode(implode(" ", $name)) . '&location=' . urlencode(implode(" ", $location)) // The URL Twilio will request when the call is answered
            );
            echo 'Started call: ' . $call->sid . "<br/>";
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

//notify_by_twilio($friendlist, $client, $phonenumber, $name, $location);
