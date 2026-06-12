<?php include_once("../config/fron_autoload.php"); 
/////////////////////////////////////////////////////////////

//$postData = file_get_contents('php://input');
//or below one//
//$postData = file_get_contents('testbooking.xml');

/////////////////////////////////////////////////////////

echo "1";



echo $in_last_id=360;
		$idres=	encryptor(encrypt,$in_last_id);
	



		
		
		echo "<script type='text/javascript'>window.location.href='../adminpanel/mail-template/apiRequestSendOrderMail.php?id=".$idres."';</script>";



?>