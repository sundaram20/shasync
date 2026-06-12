<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

//include_once("../includes/header.php");
/*echo "<pre>";
print_r($_SESSION);
echo "-------------request--------<br>";
print_r($_REQUEST);
echo "</pre>";
exit;*/

/*debugData($_SESSION);
exit;*/
$jsonArray=array();
$followup_id	= $_REQUEST['followup_id'];

function myCompany($connNew){
	$chkSQl = 'SELECT id_company FROM  '.TBL_COMPANY.' WHERE id_company="'.$_POST['id_company'].'" AND area IN ('.$_SESSION['teamMyAreas'].') ';

	$resChk = mysqli_query($connNew,$chkSQl);
		
	if(mysqli_num_rows($resChk)==0){
		$markedCompany = 1;
		
	}
	else{
		$markedCompany = 0;
	}

	return $markedCompany;
}

//Check Company Contact is exist or Not
function myContact($connNew){
	
	$chkSQlmyContact = 'SELECT id_customer FROM '.TBL_CUSTOMER.' WHERE type="2" AND  id_company="'.$_POST['id_company'].'" AND  id_customer="'.addslashes($_POST['id_contacts']).'" ';
	$resChkmyContact = mysqli_query($connNew,$chkSQlmyContact);		
	if(mysqli_num_rows($resChkmyContact)==0){
		$myContactRow = 1;	 //Company Contact NOT Exist error =1	
	}
	else{
		$myContactRow = 0;  //Company Contact Exist error =0
	}
	return $myContactRow;
}
//Check Company Contact is exist or Not

//echo "<pre>";
	//print_r($_REQUEST);
	//print_r($_SESSION);
	//print_r($_SESSION['feedback_hotel_id']);
	//print_r($_SESSION['feedback_Explode_Description']);
	//echo "</pre>";
	//die;


$err = 0;
	
$checkConatError =myContact($connNew);
if($checkConatError==1){
	$err = 0;
	$errMeassage=1;
	/*$_SESSION['successMsg'] = 'Mismatch found in contact person please reselect.';
				
				echo '<p class="help-block">Mismatch found in contact person please reselect. <br><br>
						 </p>
						';*/
						
						$jsonArray['Message']='<p class="help-block">Mismatch found in contact person please reselect<br><br>';						
						$jsonArray['status']='1'; //Faild
						echo json_encode($jsonArray);
				exit;
	}
	
$convayence_total	=	($_POST['KmsRun']*$_POST['RateKm'])+$_POST['Total']+$_POST['Parking']+$_POST['entertainment'];

	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add	


			
			$addSql = "   	INSERT INTO `".TBL_VISIT."` SET 							
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',					
							`company_marked`='".myCompany($connNew)."',		
							`id_contacts` = '".addslashes($_POST['id_contacts'])."',
							`id_user` = '".addslashes($_SESSION['userId'])."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',
							`business_potential` = '".addslashes($_POST['business_potential'])."',							
							`discussion_summary` = '".addslashes($_POST['discussion_summary'])."',
							`conveyance_remarks` = '".addslashes($_POST['conveyance_remarks'])."',
							`StatFrom` = '".addslashes($_POST['StatFrom'])."',
							`id_travel_mode` = '".addslashes($_POST['travelMode'])."',
							`StatTo` = '".addslashes($_POST['StatTo'])."',
							`KmsRun` = '".$_POST['KmsRun']."',
							`RateKm` = '".$_POST['RateKm']."',
							`Total` = '".$_POST['Total']."',
							`Parking` = '".$_POST['Parking']."',
							`convayence_total` = '".$convayence_total."',
							`entertainment` = '".$_POST['entertainment']."',
							`lunch`='".$_POST['lunch']."',

							`supervisor_remarks` = '".addslashes($_POST['supervisor_remarks'])."'";					
							
     
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'";
			if(executeSql($addSql)){				
				$VisiteReportInsertId= $db->insert_id();
				
					
			
			/****-------------Daily Calender---------------------------------------------*/
				$insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
						  `visit_id`='".addslashes($VisiteReportInsertId)."',						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `type` = '2',	
										 
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						  `assign_user_id`='".addslashes($_SESSION['userId'])."',	
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."'			  
						  ";
						 
				$insertfollowup .= "							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				
			/****-------------Daily Calender---------------------------------------------*/
			
			
				
			
			if(!empty($_SESSION['followup_hotel_id'])){	 
				foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){
				 	$insertfollowup = "INSERT INTO `".TBL_FOLLOWUP_DETAILS."` SET 
						  `visit_id`='".addslashes($VisiteReportInsertId)."',						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						   `type` = '1',	
						  `hotel_id`='".addslashes($_SESSION['followup_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						  `follow_up_summary`='".addslashes($_SESSION['followup_description'][$dataCode])."',
						  `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',		
						  `dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',						  
						  `lead_status`='".addslashes($_SESSION['followupstatus'][$dataCode])."'";
						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				/*Next Follow up Start*/
foreach( $_SESSION['followup_Explode_Description'] as $billdate => $date) { 
foreach( $date as $NewDated => $Description) {         

if($billdate == $dataCode){
				$insertfollowup = "INSERT INTO `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($fs_daily_visit_followup)."',
						  `visit_id`='".addslashes($VisiteReportInsertId)."',
						   `type` = '2',	
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['followup_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',						  
						  `summary`='".addslashes($Description)."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($NewDated)))."',
						  `lead_status`='".addslashes($_SESSION['followupstatus'][$dataCode])."'";
						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				if(executeSql($insertfollowup)){
					if(addslashes($_SESSION['assign_followup_user_id'][$dataCode]) != $_SESSION['userId']){
					$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
								`id_shop`='".$_SESSION['shop']."',
								`source`='Follow Up',
								`id_user_assigned_to`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',
								`id_user_assigned_by`='".$_SESSION['userId']."',
								`dated` ='".addslashes(date('Y-m-d',strtotime($NewDated)))."',
								`message`='".addslashes($Description)."',";

					
					$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
								`date_created`='".date('Y-m-d H:i:s')."',
								`id_mst_user_created_by`='".$_SESSION['userId']."',
								`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

					
					executeSql($sqlNotify);	
					}			
				}
				/*Next Follow up END*/
}}}
				
				/****-------------Daily Calender---------------------------------------------*/
				$insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
						  `visit_id`='".addslashes($VisiteReportInsertId)."',
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `type` = '1',	
  				 		`enquiry_details`='1',
						   `doc_id`='".addslashes($fs_daily_visit_followup)."',					 
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',		
						    `dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."'		  
						  ";
						 
				$insertfollowup .= "							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				
			/****-------------Daily Calender---------------------------------------------*/
				}
			}
				
				 
				 //FeedBack============================Start
				 if(!empty($_SESSION['feedback_hotel_id'])){	
				foreach($_SESSION['feedback_hotel_id'] as $dataCode =>$key){
				 	$insertfollowup = "INSERT INTO `".TBL_FEEDBACK_DETAILS."` SET 					 	
						  `visit_id`='".addslashes($VisiteReportInsertId)."',
						  
						  `type` = '3',							  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['feedback_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',		
						   `created_date`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',	
						 `lead_status`='".addslashes($_SESSION['feedbackstatus'][$dataCode])."',
						 `conclusion_type`='".addslashes($_SESSION['conclusion_type'][$dataCode])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."'
						  ";
						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				/*Next Follow up Start*/
		foreach( $_SESSION['feedback_Explode_Description'] as $billdate => $date) { 
    foreach( $date as $NewDated => $Description) {         
       // echo "<br>".$billdate.'=='.$NewDated.'=='.$Description;
		 // Prints something like Nov 18, 2011
		if($billdate == $dataCode){
		$insertfollowup = "INSERT INTO `".TBL_FEEDBACK_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($fs_daily_visit_followup)."',
						  `visit_id`='".addslashes($VisiteReportInsertId)."',	
						  `type` = '3',						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['feedback_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',	
						   `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',					  
						  `summary`='".addslashes($Description)."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',
						  `conclusion_type`='".addslashes($_SESSION['conclusion_type'][$dataCode])."',
						  `lead_status`='".addslashes($_SESSION['feedbackstatus'][$dataCode])."'";						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				if(executeSql($insertfollowup)){
					if(addslashes($_SESSION['assign_feedback_user_id'][$dataCode])!=$_SESSION['userId']){

				$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
								`id_shop`='".$_SESSION['shop']."',
								`source`='Feedback',
								`id_user_assigned_to`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',
								`id_user_assigned_by`='".$_SESSION['userId']."',
								`dated` ='".date('Y-m-d',strtotime($NewDated))."',
								`message`='".addslashes($Description)."',";

					
					$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
								`date_created`='".date('Y-m-d H:i:s')."',
								`id_mst_user_created_by`='".$_SESSION['userId']."',
								`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

					
					executeSql($sqlNotify);
					}
				}	
		}
    }            
} 

			
				
				
				 //FeedBack============================
				 
				 /****-------------Daily Calender---------------------------------------------*/
				/*$insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
						  `visit_id`='".addslashes($VisiteReportInsertId)."',
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `doc_id`='".addslashes($fs_daily_visit_followup)."',		
						  `type` = '3',	
  				 		`enquiry_details`='1',
						  `followup_date`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',					 
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',		
						     `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."' 
						  ";
						 
				$insertfollowup .= "						
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);*/
				$fs_daily_visit_followup= $db->insert_id();
				
				
			/****-------------Daily Calender---------------------------------------------*/
				}
				 }
				
				$jsonArray['Message']='<p class="help-block">Visit Inserted sucessfully. <br><br>
						<b>Do You Want To Add More Visit ?</b> </p>
						<input id="recentVisitId" type="hidden" value="'.$VisiteReportInsertId.'" />';						
						$jsonArray['status']='0'; //Faild
						echo json_encode($jsonArray);


				/*echo '<p class="help-block">Visit Inserted sucessfully. <br><br>
						<b>Do You Want To Add More Visit ?</b> </p>
						<input id="recentVisitId" type="hidden" value="'.$VisiteReportInsertId.'" />
				';*/
				exit;
			}else{
				$err++;
				$jsonArray['Message']='<p class="help-block">Company details has not been saved. Please make corrections below<br><br>';						
				$jsonArray['status']='1';
				echo json_encode($jsonArray);
				//$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){ //update
			/*echo "<pre>";
			print_r($_SESSION);
			echo "-------------request--------<br>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;*/

				
			checkUserLevelPermission($_SESSION['userLevel'],TBL_VISIT,'update');
			 $editSql1 = "    UPDATE `".TBL_VISIT."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',
							`company_marked`='".myCompany($connNew)."',					
							`id_contacts` = '".addslashes($_POST['id_contacts'])."',
							`id_user` = '".addslashes($_REQUEST['user_id'])."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',
							`business_potential` = '".addslashes($_POST['business_potential'])."',							
							`discussion_summary` = '".addslashes($_POST['discussion_summary'])."',
							`conveyance_remarks` = '".addslashes($_POST['conveyance_remarks'])."',
							`StatFrom` = '".addslashes($_POST['StatFrom'])."',
							`StatTo` = '".addslashes($_POST['StatTo'])."',
							`id_travel_mode` = '".addslashes($_POST['travelMode'])."',
							`KmsRun` = '".$_POST['KmsRun']."',
							`RateKm` = '".$_POST['RateKm']."',
							`Total` = '".$_POST['Total']."',
							`Parking` = '".$_POST['Parking']."',
							`convayence_total` = '".$convayence_total."',
							`entertainment` = '".$_POST['entertainment']."',
							`lunch`='".$_POST['lunch']."'"
							;
			 $editSql1 .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '1'
							
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
						
			if(executeSql($editSql1)){
				
				
				executeSql("DELETE from `".TBL_FOLLOWUP_DETAILS."` where visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
				executeSql("DELETE from `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` where visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
				
				executeSql("DELETE from `".TBL_FEEDBACK_DETAILS."` where visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
				executeSql("DELETE from `".TBL_FEEDBACK_DETAILS_EXPLOAD."` where visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
				
				executeSql("DELETE from `".TBL_DAILY_CALENDER."` where visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");
				

				 
				 
					/****-------------Daily Calender---------------------------------------------*/
				$insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
						  `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',					  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						   
						  `type` = '2',						 
						  `id_user` = '".addslashes($_SESSION['userId'])."',	
						   `assign_user_id`='".addslashes($_SESSION['userId'])."',	
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."'			  
						  ";
						 
				$insertfollowup .= "							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				
			/****-------------Daily Calender---------------------------------------------*/
			
				 
				 
			 if(!empty($_SESSION['followup_hotel_id'])){		 
				foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){
					if($_SESSION['followup_date_created'][$dataCode] == ''){
						$CreadtedDate	= currenDateTime();
						}else{
						$CreadtedDate	= addslashes(date('Y-m-d',strtotime($_SESSION['followup_date_created'][$dataCode])));	
							}
					
				 	$insertfollowup = "INSERT INTO `".TBL_FOLLOWUP_DETAILS."` SET 
						  `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',
						  `type` = '1',			  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['followup_hotel_id'][$dataCode])."',
						  `follow_up_summary`='".addslashes($_SESSION['followup_description'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',	
						   `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',	
						  `dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',						  
						  `lead_status`='".addslashes($_SESSION['followupstatus'][$dataCode])."'";
						 
				$insertfollowup .= ",`date_created` = '".$CreadtedDate."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				foreach( $_SESSION['followup_Explode_Description'] as $billdate => $date) { 
    foreach( $date as $NewDated => $Description) {         
       // echo "<br>".$billdate.'=='.$NewDated.'=='.$Description;
		 // Prints something like Nov 18, 2011
		if($billdate == $dataCode){
				
				/*Next Follow up Start*/
				$insertfollowup = "INSERT INTO `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($fs_daily_visit_followup)."',
						  `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',
						  `type` = '1',	
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['followup_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',						  
						  `summary`='".addslashes($Description)."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($NewDated)))."',
						  `lead_status`='".addslashes($_SESSION['followupstatus'][$dataCode])."'";
						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				if(executeSql($insertfollowup)){
					if(addslashes($_SESSION['assign_followup_user_id'][$dataCode])!=$_SESSION['userId']){
					$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
									`id_shop`='".$_SESSION['shop']."',
									`source`='Follow Up',
									`id_user_assigned_to`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',
									`id_user_assigned_by`='".$_SESSION['userId']."',
									`dated` ='".addslashes(date('Y-m-d',strtotime($NewDated)))."',
									`message`='".addslashes($Description)."',";

						
						$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
									`date_created`='".date('Y-m-d H:i:s')."',
									`id_mst_user_created_by`='".$_SESSION['userId']."',
									`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

						
						executeSql($sqlNotify);
					}	
				}
				/*Next Follow up END*/
		
	}
	}
	}
				
				/****-------------Daily Calender---------------------------------------------*/
				$insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
						  `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',
						   `doc_id`='".addslashes($fs_daily_visit_followup)."',
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `type` = '1',		
							`enquiry_details`='1',				 
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						  	 `assign_user_id`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',	
						    `dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."'		  
						  ";
						 
				$insertfollowup .= "						
							,`status` = '".addslashes($_SESSION['followupstatus'][$dataCode])."'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();
				
				
			/****-------------Daily Calender---------------------------------------------*/
				}
				}
				
				
				 
				 //FeedBack============================Start
				  if(!empty($_SESSION['feedback_hotel_id'])){
				foreach($_SESSION['feedback_hotel_id'] as $dataCode =>$key){
					
					if($_SESSION['feedback_date_created'][$dataCode] == ''){
						$CreadtedDate	= currenDateTime();
						}else{
						$CreadtedDate	= addslashes(date('Y-m-d',strtotime($_SESSION['feedback_date_created'][$dataCode])));	
							}
							
				 	$insertfollowup = "INSERT INTO `".TBL_FEEDBACK_DETAILS."` SET 					 	
						  `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',	
						  `type` = '3',					  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['feedback_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',	
						   `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',	
						  `created_date`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',	
						 `lead_status`='".addslashes($_SESSION['feedbackstatus'][$dataCode])."',
						 
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."'
						  ";
						 
				$insertfollowup .= ",`date_created` = '".$CreadtedDate."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);
				$fs_daily_FeedBack_followup= $db->insert_id();
				
				/*Next Follow up Start*/
foreach( $_SESSION['feedback_Explode_Description'] as $billdate => $date) { 
    foreach( $date as $NewDated => $Description) {         
       // echo "<br>".$billdate.'=='.$NewDated.'=='.$Description;
		 // Prints something like Nov 18, 2011
		if($billdate == $dataCode){
		 $insertfollowup = "INSERT INTO `".TBL_FEEDBACK_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($fs_daily_FeedBack_followup)."',
						   `visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',
						  `type` = '3',						  
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($_SESSION['feedback_hotel_id'][$dataCode])."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',		
						   `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',				
						    
						  `summary`='".addslashes($Description)."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',
						  `lead_status`='".addslashes($_SESSION['feedbackstatus'][$dataCode])."' ";						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
				executeSql($insertfollowup);

				if(executeSql($insertfollowup)){
					if(addslashes($_SESSION['assign_feedback_user_id'][$dataCode])!=$_SESSION['userId']){
					$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
									`id_shop`='".$_SESSION['shop']."',
									`source`='Feedback',
									`id_user_assigned_to`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',
									`id_user_assigned_by`='".$_SESSION['userId']."',
									`dated` ='".addslashes(date('Y-m-d',strtotime($NewDated)))."',
									`message`='".addslashes($Description)."',";

						
						$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
									`date_created`='".date('Y-m-d H:i:s')."',
									`id_mst_user_created_by`='".$_SESSION['userId']."',
									`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

						
						executeSql($sqlNotify);
					}	
				}
		}
    }            
} 
				
				 //FeedBack============================
				 
				 /****-------------Daily Calender---------------------------------------------*/
					/* $insertfollowup = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
								`visit_id`='".addslashes(encryptor('decrypt',$_POST[eId]))."',
								`doc_id`='".addslashes($fs_daily_FeedBack_followup)."',
								`id_shop` = '".addslashes($_SESSION['shop'])."',
								`type` = '3',	
									`enquiry_details`='1',
								`followup_date`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."',				 
								`id_user` = '".addslashes($_SESSION['userId'])."',	
								 `assign_user_id`='".addslashes($_SESSION['assign_feedback_user_id'][$dataCode])."',	
								`dated`='".addslashes(date('Y-m-d',strtotime($_POST['report_date'])))."', 						  							
								`status` = '".addslashes($_SESSION['feedbackstatus'][$dataCode])."'"; 
					
					executeSql($insertfollowup);
				$fs_daily_visit_followup= $db->insert_id();*/
				
				
			/****-------------Daily Calender---------------------------------------------*/
				}
			}
				
				    $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($_POST['id_contacts'])."'",''); 
		  			$resultContact = $db->fetch_object2($resContact);
                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;
				
				
				$_SESSION['successMsg'] = 'Updated sucessfully.';
				
				//header("location:addreport.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				echo '<p class="help-block">Visit Updated sucessfully. <br><br>
						 </p>
						<input id="recentVisitId" type="hidden" value="'.$VisiteReportInsertId.'" />';
				exit;
			}else{
				$err++;
				
				$errorMsg = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved. Please make corrections below.';
				$jsonArray['Message']='<p class="help-block">'.$errorMsg.'<br><br>';						
				$jsonArray['status']='1';
				echo json_encode($jsonArray);
			}
		}
	}else{ //Error
		$err++;
		$jsonArray['Message']='<p class="help-block">Company details has not been saved. Please make corrections.<br><br>';						
		$jsonArray['status']='1';
		echo json_encode($jsonArray);
		//$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections.';
	}


?>
                




