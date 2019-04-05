<?php
return array(
/** set your paypal credential **/
'client_id' =>'AQ5NkStJB84M-r5ZMPOqt9qgAu-3yDo5oTGyDyNg16hBZejFBk2ppOKZ_pT53Frzqe1UstuH87epHSKj',
'secret' => 'EKfZcMY81uDTSv5a8vSecNZgBsQ6FELMgZtItxe3J62oLxm-ISjMjtmT42qZIhZvXceL_rHxSazeE0Rq',
/**
* SDK configuration 
*/
'settings' => array(
/**
* Available option 'sandbox' or 'live'
*/
// 'mode' => 'sandbox',
/**
* Specify the max request time in seconds
*/
'http.ConnectionTimeOut' => 1000,
/**
* Whether want to log to a file
*/
'log.LogEnabled' => true,
/**
* Specify the file that want to write on
*/
'log.FileName' => storage_path() . '/logs/paypal.log',
/**
* Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
*
* Logging is most verbose in the 'FINE' level and decreases as you
* proceed towards ERROR
*/
'log.LogLevel' => 'FINE'
),
);

?>