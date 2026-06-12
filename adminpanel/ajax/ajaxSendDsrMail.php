<?php
include_once("../../config/auto_loader.php");

//if($_REQUEST['fileName'] !=''){
	//$SITE_URL."/adminpanel/mailattach/".$_REQUEST['fileName'];

	//$file=$_REQUEST['fileName'];

	$content = "Dear All,<br><br>

				Kindly find attach your Daily Sales Report. <br><br> 

				For any technical Support please call 09810164525 or mail us at support@roomstatushub.com <br><br>

				Thanks & Regards,<br>

				RoomStatusHUB <br>
				(Support Team)

				";


	$file = 'DSR_'.str_replace(' ','',selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ')).'_'.date('d-m-Y',strtotime($_REQUEST['report_date'])).'.pdf';

	$sub = 'DSR '.str_replace(' ','',selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ')).' '.date('d-m-Y',strtotime($_REQUEST['report_date']));

	$myownteam_id	=selectColumn(TBL_USERS,'myownteam_id','WHERE id="'.$_SESSION['userId'].'" ');
	//$idsTo = selectColumn(TBL_TEAM,'ids_user_dsr_reporting','WHERE FIND_IN_SET(id,"'.$_SESSION['teamId'].'") ');
	$idsTo = selectColumn(TBL_TEAM,'ids_user_dsr_reporting','WHERE  id="'.$myownteam_id.'"');

	$idsArray =  explode(',', $idsTo);
	$to = array();
	for($i=0 ;$i<count($idsArray);$i++){
		$email = selectColumn(TBL_USERS,'email','WHERE id="'.$idsArray[$i].'" ');
		if(!is_null($email)){
		    array_push($to, $email);
		    
		}
	}
	
//	print_r($to);
	//die;

	$cc = selectColumn(TBL_USERS,'email','WHERE id="'.$_SESSION['userId'].'" ');
    if (empty($to)) {
       // echo $message = "Kindly Check your Email Id Or Update Team Email Id";
    }else{
	if(!is_null($to) && $cc !="" && file_exists("../mailattach/".$file)){
	
		$sendMail->sendDsrMail('support@roomstatushub.com',$to,$sub,$content,$cc,"../mailattach/".$file);
		$sent =1;
		//Email Log ==============================
		
		
		$sqlDailyvisit = executeSql(" SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($_REQUEST['report_date']))."' and id_user	= '".addslashes($_SESSION['userId'])."'");
	 
	 //$rowRatePlanExisting = $db->fetch_object2($sqlDailyvisit);
	 $feedCount=1;
	 $printHead = 0;$visiteArray=array();
	 while($rowDailyvisite = $db->fetch_object2($sqlDailyvisit)){
		 $visiteArray[]=$rowDailyvisite->id;
		//$reqSql = "INSERT INTO `email_log` (table_name,id_user,id_reference,dated,date_created) VALUES('fs_visit','".$rowDailyvisite->id."','".$_SESSION['userId']."','".date('Y-m-d',strtotime($_REQUEST['report_date']))."','".date('Y-m-d h:i:s')."') ";
		//mysqli_query($connNew,$reqSql);
	 }
	 $visitData	=	implode(',',$visiteArray);
	 $reqSql = "INSERT INTO `email_log` (table_name,id_reference,id_user,dated,date_created) VALUES('fs_visit','".$visitData."','".$_SESSION['userId']."','".date('Y-m-d',strtotime($_REQUEST['report_date']))."','".date('Y-m-d h:i:s')."') ";
		mysqli_query($connNew,$reqSql);
		
		
		//Email LOg ======================================
	}
	else{
		$sent =0;
	}
    }
    if($sent==1){
		//$to=implode(',',$to);
	  echo $message = "Mail Sent Successfully";
	  if (file_exists("../mailattach/".$file))
	  	unlink("../mailattach/".$file);
	}
	else{
	  echo $message = "Failed to send mail. Kindly Check your Email Id !";
	   if (file_exists("../mailattach/".$file))
	  	unlink("../mailattach/".$file);
	}
	exit;

?>