<?php
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/conveyanceReportAutomailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include("../../phplib/dompdf/dompdf_config.inc.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once("../../adminpanel/includes/reportFunctions.php");*/



///////////////// CRON JOB PATH ///////////////////
$path = getcwd().'/public_html/crs';
include_once($path."/config/data.config.php");
include_once(str_replace('crs','sales',$path)."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once(str_replace('crs','sales',$path)."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include($path."/phplib/dompdf/dompdf_config.inc.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once(str_replace('crs','sales',$path)."/adminpanel/includes/reportFunctions.php");

$DB_NAME='inroomhu_fern';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlTeam  = "SELECT id,name,id_user_level_2,id_user_level_1,ids_user_monthly_reporting FROM ".TBL_TEAM." WHERE id_shop=6  ";
$resTeam  = mysqli_query($conn,$sqlTeam);
$cron='set';
$shop_id=6;

$_REQUEST['Download']='Download';
while($rowTeam = mysqli_fetch_object($resTeam)){
	
	$sqlUser = "SELECT id,name FROM ".TBL_USERS." WHERE FIND_IN_SET ('".$rowTeam->id."',".TBL_USERS.".ids_team) AND NOT FIND_IN_SET(id,'".$rowTeam->id_user_level_1.",".$rowTeam->id_user_level_2."') AND id_shop=6";
	$resUsers = mysqli_query($conn,$sqlUser);
	$numUser = mysqli_num_rows($resUsers);
	
	if($numUser>0){
		$memberArray = array();
		while($rowUser = mysqli_fetch_object($resUsers)){

			$teamId = $rowTeam->id;
			$id_user=$rowUser->id;

			mail('support@roomstatushub.com','Conveyance Mail Start FOR '.selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ').' '.date('d-m-Y H:i:s'),'Automailer');

			$fileName = 'ConveyanceReport'.'_'.selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ');
			$mailCc = selectColumn(TBL_USERS,'email','where id="'.$id_user.'" ');
			$setUserArr=array();
			$setUserArr = explode(',',$rowTeam->ids_user_monthly_reporting);
			$i=0;
			$setEmail = array();
			while($i<count($setUserArr)){
				array_push($setEmail,selectColumn(TBL_USERS,'email','WHERE id="'.$setUserArr[$i].'" '));
				$i++;
			} 
			$toEmails = implode(',',$setEmail);
			$dateStr = date('d-m-Y');
			$checkin =date('01-m-Y',strtotime('-1 month',strtotime($dateStr)));
			$checkout = date('t-m-Y',strtotime('-1 month',strtotime($dateStr)));
			$id_user = $rowUser->id;
			
			 conveyanceReport($cron,$shop_id,$id_user,$checkin.' to '.$checkout,'');
			 	 
			 //server
			$attach = '/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.pdf';

			  //local
			// $attach = '../mailattach/'.$fileName.'.pdf';
			 $sendMail = new sendMail;
			 $mailto = $toEmails;
			 $testcc='sundaram@globalcomputersolutions.in';
			 if($mailCc !='' && $mailto !='' && file_exists($attach)){

			 	$sendMail->sendRateMail('donotreply@roomstatushub.com ',$mailto,$fileName,' PFA Conveyance Report ',$mailCc,$attach);

			 	//---without test  CC--//
			 	//$sendMail->sendRateMail('donotreply@roomstatushub.com',$mailto,$fileName,' PFA Conveyance Report',$mailCc,$attach);

			 	mail('support@roomstatushub.com','Conveyance Mail Sent To '.selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ').' '.date('d-m-Y H:i:s'),'Head : '.$mailto.'<br> Exe : '.$mailCc);
		 	
			}

			$setUserArr='';
			$setEmail ='';
			$mailCc='';	
			$mailto='';
			$fileName='';

			unset($setUserArr);
			unset($setEmail);
			unset($mailCc);
			unset($fileName);
			unset($sendMail);

			if(file_exists($attach)){
				unlink($attach);
			}	

		}
		
		
	}
	
}



exit;
?>