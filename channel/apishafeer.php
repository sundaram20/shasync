<?php include_once("../config/fron_autoload.php"); 
/////////////////////////////////////////////////////////////

$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('testbooking.xml');

/////////////////////////////////////////////////////////




mysql_query("Insert into api_test set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."'");



	
		

		echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';


					



?>