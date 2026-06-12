<?php 
unset($_SESSION);
set_time_limit(50000);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/feedbackReportAutoMailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include_once("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
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
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once(str_replace('crs', 'sales',$path)."/adminpanel/includes/reportFunctions.php");
//echo $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/includes/reportFunctions.php";
 $DB_NAME='inroomhu_fern';

 
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());



$link = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


$resTeam  = mysqli_query($link,"SELECT id,name,id_user_level_1,ids_user_monthly_reporting FROM ".TBL_TEAM." WHERE id_shop=6 ");

$cron='set';
$shop_id=6;
$_REQUEST['Download'] = 'Download';
while($rowTeam = mysqli_fetch_object($resTeam)){
    
    	mail('support@roomstatushub.com','FEEDBACK CRON START',$rowTeam->name.'_FeedBack_Report_'.date('d-M-Y H:i:s'));
	
	$resUsers = mysqli_query($link,"SELECT id FROM ".TBL_USERS." WHERE FIND_IN_SET (".TBL_USERS.".ids_team,'".$rowTeam->id."') AND id_shop=6");
	$numUser = mysqli_num_rows($resUsers);
	
	if($numUser>0){
		
		$memberArray = array();
		while($rowUser = mysqli_fetch_object($resUsers)){
			array_push($memberArray,$rowUser->id);
		}
		 $idsTeamMember = implode(',',$memberArray);
		 $teamId = $rowTeam->id;
				
	    $SqlRsoUsers = mysqli_query($link,"SELECT id,hotel_access,user_type FROM ".TBL_USERS." WHERE id=".$rowTeam->id_user_level_1." AND  id_shop=6");
		$numRsoUser = mysqli_num_rows($SqlRsoUsers);
		$UnitUserHotelAccess    =   mysqli_fetch_object($SqlRsoUsers);
		if($UnitUserHotelAccess->user_type!=2){
			$RsoUserChecked=1; // RSO USER
		}else{
		    $RsoUserChecked=2; // UNIT USER
		    $UserHotelAccesid  =   $UnitUserHotelAccess->hotel_access;
		}
		
		
		$fileName = $rowTeam->name.' Feedback';
		$setUserArr=array();
		  $setUserArr = explode(',',$rowTeam->ids_user_monthly_reporting);
		//print_r($setUserArr);
		$i=0;
		$setEmail = array();
		while($i<count($setUserArr)){
			array_push($setEmail,selectColumn(TBL_USERS,'email','WHERE id="'.$setUserArr[$i].'" '));
			$i++;
		} 
		 $toEmails = implode(',',$setEmail);
		//print_r($toEmails);
		 $objPHPExcel = new PHPExcel();
		 $dateStr = date('d-m-Y');
		 $checkin =date('01-04-Y',strtotime('-1 month',strtotime($dateStr)));
		 $checkout = date('d-m-Y');


		 feedbackReport($link,$shop_id,$checkin,$checkout,'','','',$objPHPExcel,$idsTeamMember,$cron,$fileName,$RsoUserChecked,$UserHotelAccesid);
		 unset($objPHPExcel);
		 //server
		 $attach = '/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls';
		
		  //local
		 //$attach = '../mailattach/'.$fileName.'.xls';
		 
		 $sendMail = new sendMail;
		            
		 $mailto = $toEmails;
		 
		 if($mailto !='' && file_exists($attach)){
		     
		     $content='Dear All,<br><br>

				Kindly find attach Feedback Report. <br><br>

				For any technical Support please mail us at support@roomstatushub.com <br><br>

				Thanks & Regards,<br>
				RoomStatusHUB <br>
				(Support Team) ';


		 	$sendMail->sendRateMail('noreply@roomstatushub.com',$mailto,$fileName .' Report',$content,'support1@roomstatushub.com',$attach);
		 	mail('support@roomstatushub.com','FEEDBACK REPORT SENT',$rowTeam->name.'_FeedBack_Report_'.date('d-M-Y H:i:s').'___'.str_replace(';',',',$mailto));
		 	
			}
		 unset($sendMail);
		 unset($mailto);
		 if(file_exists($attach)){
		 	unlink($attach);
		 }	
	}
	
}



mysqli_close($conn);
exit;
?>