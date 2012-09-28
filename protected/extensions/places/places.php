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
	$token = 'AAACEdEose0cBANHdoTZBnwxuTcvlbpuGDCdUK1bVdRNsf67PKuTbnyQ5JFkEWTR8mscfZAIvn9DEfZCW5CScMRVlo0ROUjGNSo2kMLN75KT4OjeZAeMg';

function get_location($lat, $lon, $token)
{
    $base = 'https://graph.facebook.com';
    $uri = '/search?type=place&center=' . $lat . ',' . $lon . '&distance=100&access_token=' . $token;

    $output = get($base . $uri);
    $loc = array();
    $loc['street'] = '';
    $i = 0;
    while ($loc['street']==''){
        $loc['name'] = $output->data[$i]->name;
        $loc['street'] = str_replace("ul. ", "", $output->data[$i]->location->street);
        $loc['city'] = $output->data[$i]->location->city;
        $i++;
    }
    return $loc;
}

//$loc = get_location($lat, $lon, $token);
	
//	print_r($loc);
?>	