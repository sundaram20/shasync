<?php
echo '1';
die;
error_reporting(E_ALL);
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/followupReportAutoMailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once("../../adminpanel/includes/reportFunctionsFollowupNotification.php");*/


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
include_once(str_replace('crs', 'sales',$path)."/adminpanel/includes/reportFunctionsFollowupNotification.php");


$DB_NAME='inroomhu_fern';
//$DB_NAME='sales';

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlTeam  = "SELECT * FROM ".TBL_USERS." WHERE id_shop=6";
$resTeam  = mysqli_query($conn,$sqlTeam);
$cron='set';
$shop_id=6;




while($rowTeam = mysqli_fetch_object($resTeam)){
   $rowTeam->name.$rowTeam->id;
   
   	
   	// Head Email
   		$ids_team		= selectColumn(TBL_USERS,'ids_team','WHERE id="'.$rowTeam->id.'" ');
		$ids_team		= explode(',',$ids_team);
			
		$id_user_level_1 = selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team[0].'" ');	
		$ccHead		  = selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray 	 = explode("|",$ccHead);

		echo '<br>'.$ccHeadEmail 	   =    $ccHeadArray[0];
		 echo $ccHeadName 		=	$ccHeadArray[1];
		// Head Email
	
	//mail('support1@roomstatushub.com','NOTIFICATION START',$rowTeam->name.'_Followup_'.date('d-M-Y H:i:s'));

	
die;
		
		$UserId = $rowTeam->id;
		
		 

	}	

mysqli_close($conn);
exit;
?>