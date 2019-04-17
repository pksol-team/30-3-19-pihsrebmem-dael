<?php
// Get cURL resource
$curl = curl_init();


curl_setopt($curl, CURLOPT_VERBOSE, true);

// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://xpromailer.test/cronjob',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,	
    CURLOPT_POSTFIELDS => array(    	
        'Auth' => 'true'

    )
));

curl_setopt($curl, CURLOPT_VERBOSE, 1);
  // curl_setopt($curl, CURLOPT_STDERR, $fp);
   
   
// Send the request & save response to $resp
echo $resp = curl_exec($curl);


// Close request to clear up some resources
curl_close($curl);


?>