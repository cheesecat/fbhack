<?php

    $lat = '52.228391';
    $lon = '21.025815';
    $token = 'AAACEdEose0cBANHdoTZBnwxuTcvlbpuGDCdUK1bVdRNsf67PKuTbnyQ5JFkEWTR8mscfZAIvn9DEfZCW5CScMRVlo0ROUjGNSo2kMLN75KT4OjeZAeMg';

    function get_location($lat, $lon, $token)
    {
        include_once('/extensions/facebook/sdk/facebook.php');
        $uri = '/search?type=place&center=' . $lat . ',' . $lon . '&distance=100&access_token=' . $token;

        $fb = new Facebook(array(
                        'appId' => Yii::app()->params['fb']['appId'],
                        'secret' => Yii::app()->params['fb']['secret'],
                 ));

        $output = $fb->api($uri);
        $loc = array();
        $loc['street'] = '';
        $i = 0;
        while ($loc['street']==''){
            $loc['name'] = $output->data[$i]->name;
            $loc['street'] = str_replace("ul. ", "", $output->data[$i]->location->street);
            $loc['city'] = $output->data[$i]->location->city;
            $i++;
            if ($i >= count($output->data)) exit;
        }
        return $loc;
    }

//$loc = get_location($lat, $lon, $token);
	
//	print_r($loc);
?>	