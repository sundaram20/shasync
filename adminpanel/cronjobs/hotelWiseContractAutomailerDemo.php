<?php
//CRON URL
//error_reporting(E_ALL);
set_time_limit(150000);
/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/hotelWiseContractAutomailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");*/


///////////////// CRON JOB PATH ///////////////////
 $path='/var/www/vhosts/roomstatushub.in/httpdocs/sales';
//include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
//include_once($path."/phplib/roomstatus.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");




//Local File Path 
//include_once('../includes/reportFunctions_new.php');
//error_reporting(E_ALL);
//Cron Path
include_once($path."/adminpanel/includes/reportFunctions_newDemo.php");
$DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
//$DB_HOST                        = "ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306"; 
$DB_NAME						='inroomhu_fern';
$DB_USERNAME                    = "inroomhu_fern";              // Database Username
$DB_PASSWORD                    = "Welcom@123";
$DB_REPORT_ERROR                = true;                        // To Report Error
$DB_PERSISTENT_CONN             = false;

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$id_shop=6;
$summer = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-04-01').'" AND id_shop="6" ');
$winter = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-10-01').'" AND id_shop="6" ');


$season =array();


 $seasonSql = "SELECT id FROM ".TBL_RATE_SEASON." WHERE id_shop=".$id_shop."  AND status=1  and id IN('85','86','87')";


$resSeason = mysqli_query($connNew,$seasonSql);

while($rowSeason = mysqli_fetch_object($resSeason)){
	array_push($season,$rowSeason->id);
}

 $attachPath='/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/';
//$season =array();
//array_push($season,$summer);
//array_push($season,$winter);
//print_r($season);
 $hotelSql = "SELECT id FROM ".TBL_HOTELS." WHERE id_shop=".$id_shop."  AND status=1 AND id IN ('97','98','99','100','101','102','103','104','105','107','109','110') ORDER BY display_order ";

	
//'110','111','114','115','196','197','117','119','198','199','200'
//'113',
$resHotel = mysqli_query($connNew,$hotelSql);
//$hotelID = mysqli_fetch_object($resHotel)->id;
//$hotelName = selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotelID.'" ');



while($rowHotel = mysqli_fetch_object($resHotel)){
	
	$hotelID=$rowHotel->id;
	$hotelName = selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotelID.'" ');
	$hotelCity = selectColumn(TBL_HOTELS,'city','WHERE id="'.$hotelID.'" ');
	$hotelEmail = selectColumn(TBL_HOTELS,'email','WHERE id="'.$hotelID.'" ');

	//mail('support@roomstatushub.com','HOTEL CRON START',$hotelName.'_'.$hotelCity.'_'.date('d-M-Y H:i:s'));

	if($hotelEmail!=''){
		for($i=0;$i<count($season);$i++){

			


			  $seasonName = selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$season[$i].'" ');

			if($seasonName!=''){
			

			$fileName = $hotelName.'_'.$hotelCity.' Contracted Rate Report for '.ucwords(strtolower($seasonName));
			$attachBooking=$hotelName.'_'.$hotelCity.' Contracted Rate Report for '.ucwords(strtolower($seasonName)).'.xls';
			$objPHPExcel = new PHPExcel();
			hotelWiseContractReportNEW('set',$id_shop,$hotelID,$season[$i],$db,$objPHPExcel);
			unset($objPHPExcel);
			 //server
			 $attach = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls';

			  //local
			 //$attach = '../mailattach/'.$fileName.'.xls';
			//$sendMail = new sendMail;
			$mailto = 'test';
			$mailCc = 'test';
			
			   $content = 'Dear Team,<br><br>
			 				
			 				Kindly find attached the Hotel Contracted Rates Report – '.$seasonName.' which supercedes the last sent report. Kindly check the Remarks Column for Inclusions. 

						<br><br> 
						 Thanks & Regards<br><br>
						<b>RoomStatusHUB Team</b><br>
						
						';
						
						
 $mailto="roomstatushublogs@gmail.com";
if($mailto !=''){// && file_exists($attach)){
		 
$msg = $content;
$msg = wordwrap($msg,70);
$From="support@roomstatushub.com";
$sub= $fileName;

 $AddAddress=explode(',',str_replace(';',',',$hotelEmail));
	
//$recipients =explode(",",$_POST['ccId']);
 $cc = array();
 $cc[]='mohit@fernhotels.com';
$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "room9876#";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $msg;
$mail->AddBCC('roomstatushublogs@gmail.com');

	//$mail->AddCC($vendorEmailCC);
	foreach($AddAddress as $AddAddressmail)
	{	
		
   		$mail->AddAddress($AddAddressmail);
	}		
	foreach($cc as $ccmail)
		{	
		//echo '<br>=='.$name;
   		$mail->AddCC($ccmail);
		}
		 $attachname1=$attachBooking;
   		$mail->addAttachment($attachPath.$attachname1,$attachBooking,"base64","application/Excel");
			  
	
$sendMail = $mail->Send(); 
		
		 	
		}
		 unset($sendMail);







	
						
					}	
				
			 
		}
		//$hotelEmail='';
		//unset($hotelEmail);
		
	} 
}

?>