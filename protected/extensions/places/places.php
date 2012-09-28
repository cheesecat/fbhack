<?php

    function get($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );  
        $output = curl_exec($ch);
        curl_close ($ch);     
        return json_decode($output);
    }

	$lat = '52.228391';
	$lon = '21.025815';
	$token = 'AAAAAAITEghMBALZA7rzKtrYKAJMJpSVMEn6s616Q653kFaTNTKxZBFLWsqfmbgaHHggmnZCvnYaIsktG7zeFzbrgDStKLtidS969eAuV2Niz4cAXjJU';

function get_location($lat, $lon, $token)
{
    $base = 'https://graph.facebook.com';
    $uri = '/search?type=place&center=' . $lat . ',' . $lon . '&distance=100&access_token=' . $token;

    $output = get($base . $uri);
    $loc = array();
    $loc['name'] = $output->data[0]->name;
    $loc['street'] = str_replace("ul. ", "", $output->data[0]->location->street);
    $loc['city'] = $output->data[0]->location->city;
    return $loc;
}

//$loc = get_location($lat, $lon, $token);
	
//	print_r($loc);
?>	