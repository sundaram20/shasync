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

$DB_HOST                        = "ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306"; 
$DB_NAME						='inroomhu_fern';
$DB_USERNAME                    = "inroomhu_fern";              // Database Username
$DB_PASSWORD                    = "Welcom@123";
$DB_REPORT_ERROR                = true;                        // To Report Error
$DB_PERSISTENT_CONN             = false;

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$id_shop=6;
echo '==============='.$summer = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-04-01').'" AND id_shop="6" ');
$winter = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-10-01').'" AND id_shop="6" ');



$season =array();
array_push($season,$summer);
array_push($season,$winter);
//print_r($season);
 $hotelSql = "SELECT id FROM ".TBL_HOTELS." WHERE id_shop=".$id_shop."  AND status=1 AND id='75' ORDER BY display_order ";

$resHotel = mysqli_query($connNew,$hotelSql);
//$hotelID = mysqli_fetch_object($resHotel)->id;
//$hotelName = selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotelID.'" ');



while($rowHotel = mysqli_fetch_object($resHotel)){
	
	$hotelID=$rowHotel->id;
	$hotelName = selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotelID.'" ');
	$hotelCity = selectColumn(TBL_HOTELS,'city','WHERE id="'.$hotelID.'" ');
	$hotelEmail = selectColumn(TBL_HOTELS,'email','WHERE id="'.$hotelID.'" ');

	mail('support@roomstatushub.com','HOTEL CRON START',$hotelName.'_'.$hotelCity.'_'.date('d-M-Y H:i:s'));

	if($hotelEmail!=''){
		for($i=0;$i<count($season);$i++){

			

			$seasonName = selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$season[$i].'" ');

			

			$fileName = $hotelName.'_'.$hotelCity.' Contracted Rate Report for '.ucwords(strtolower($seasonName));
			
			$objPHPExcel = new PHPExcel();
			hotelWiseContractReportNEW('set',$id_shop,$hotelID,$season[$i],$db,$objPHPExcel);
			unset($objPHPExcel);
			 //server
			echo $attach = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls';

			  //local
			 //$attach = '../mailattach/'.$fileName.'.xls';
			$sendMail = new sendMail;
			$mailto = 'test';
			$mailCc = 'test';
			
			 echo  $content = 'Dear Team,<br><br>
			 				
			 				Kindly find attached the Hotel Contracted Rates Report which supercedes the last sent 15 days back. Also please check the Remarks Column for Inclusions. 

						<br><br> 
						 Thanks & Regards<br><br>
						<b>RoomStatusHUB Team</b><br>
						
						';
						
						
					die;	
			if($mailCc !='' && $mailto !='' && file_exists($attach)){
				//reference purpose
				//sendRateMail($from, $to, $subject, $body, $cc,$attach="",$fromName="",$addReplyTo="")
				//str_replace(';',',',$hotelEmail)
				echo '=='.$sendMail->sendRateMail('donotreply@roomstatushub.com','support@roomstatushub.com',$fileName,$content,'support@roomstatushub.com',$attach,'Do not reply','support@roomstatushub.com');

				mail('support@roomstatushub.com','HOTEL CRON SENT',$seasonName.'_'.$hotelName.'_'.$hotelCity.'_'.date('d-M-Y H:i:s').'___'.str_replace(';',',',$hotelEmail));
				//$sendMail->sendRateMail('donotreply@roomstatushub.com','hiteshaloney75@gmail.com',$fileName,$content.'<br>'.$hotelEmail,'hiteshaloney75@gmail.com',$attach,'Do not reply','hiteshaloney75@gmail.com');
			}

			 $mailCc='';
			 $mailto='';
			 
			 
			 unset($sendMail);
			 if(file_exists($attach)){
			 	unlink($attach);
			 }	
			 
		}
		$hotelEmail='';
		unset($hotelEmail);
		
	} 
}

?>