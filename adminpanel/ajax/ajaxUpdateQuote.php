<?php include_once("../../config/auto_loader.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////

/*echo "<pre>";
print_r($_SESSION);
echo "-------------request--------<br>";
print_r($_REQUEST);
echo "</pre>";
exit;*/


//include_once("../includes/header.php");
$followup_id	= $_REQUEST['followup_id'];
	
	if($_REQUEST['reservation_date'] !=""){
		$dateArray = explode(' to ',$_REQUEST['reservation_date']);
		$checkin = $dateArray[0];
		$checkout = $dateArray[1];
	}


	$err = 0;

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add	

			// fetching follow_up_summary
			if($_SESSION['followup_description']!=""){
				foreach($_SESSION['followup_description'] as $dataCode =>$value){
					$follow_up_summary=$value;
					$follow_up_date=$_SESSION['followup_date'][$dataCode];
					$assign_user = $_SESSION['assign_followup_user_id'][$dataCode];
				}
			}
	$addEnq ="  
							INSERT INTO `".TBL_SALES_QUOTE."` SET 							
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',	
							`hotel_id` = '".addslashes($_POST['hotel_id'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							 `id_user` = '".addslashes($_SESSION['userId'])."',
							 `assign_user_id` = '".$assign_user."',	
							`status` = '1',
							`follow_up_summary`='".$follow_up_summary."',
							`follow_up_date`='".date('Y-m-d',strtotime($follow_up_date))."',
							`type` = '5',
							`details` = '".addslashes($_POST['discussion_summary'])."',
							`created_date`='".date('Y-m-d')."',	
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
							`checkin`='".date('Y-m-d',strtotime($checkin))."',
							`checkout`='".date('Y-m-d',strtotime($checkout))."',
							`id_rate`='".$_REQUEST['rate_id']."',
							`id_mst_user_created_by`='".addslashes($_SESSION['userId'])."',
							`date_created`='".currenDateTime()."',
							`id_mst_user_modified_by`='".addslashes($_SESSION['userId'])."',
							`date_modified`='".currenDateTime()."',
							`lead_status` = '".addslashes($_POST['status'])."'
							";
			$addEnq;
						

			if(executeSql($addEnq)){				

				$VisiteReportInsertId= $db->insert_id();
				$count =1;
				$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='5',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id`='".addslashes($_SESSION['userId'])."',
						`doc_id` ='0',
						`visit_id` ='".addslashes($VisiteReportInsertId)."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
						
						`status` = '".addslashes($_POST['status'])."'";

				executeSql($insertCalendar);
	

				 //`followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',
				
				$reminderDate=0;
			if($_SESSION['followup_hotel_id']!=""){
				foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){
					$count =0;
				 	$insertfollowup = "INSERT INTO `".TBL_SALES_QUOTE_FOLLOWUP."` SET 
				 		
				 		`id_company` = '".addslashes($_POST['id_company'])."',
						`id_quote`='".addslashes($VisiteReportInsertId)."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`hotel_id`='".addslashes($_REQUEST['hotel_id'])."',
						`id_contact`='".$_POST['id_contacts']."',	
						`id_user`='".addslashes($_SESSION['userId'])."',
						`created_date`='".date('Y-m-d')."',
						`details` = '".$_SESSION['followup_description'][$dataCode]."',
						`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',
						`id_mst_user_created_by`='".addslashes($_SESSION['userId'])."',
						`id_mst_user_modified_by`='".addslashes($_SESSION['userId'])."',
						`type` = '5',
						`dated`  = '".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
						
						`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						$count++;

						executeSql($insertfollowup);
						$EnquiryInsertId= $db->insert_id();

						$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='5',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',
						`doc_id` ='".addslashes($EnquiryInsertId)."',
						`visit_id` ='".addslashes($VisiteReportInsertId)."',
						`dated`='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
						
						`status` = '".$_SESSION['followupstatus'][$dataCode]."'";

						if(executeSql($insertCalendar)){
							if(addslashes($_SESSION['assign_followup_user_id'][$dataCode]) != $_SESSION['userId']){
							$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
										`id_shop`='".$_SESSION['shop']."',
										`source`='Quote Follow Up',
										`id_user_assigned_to`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',
										`id_user_assigned_by`='".$_SESSION['userId']."',
										`dated` ='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
										`message`='".$_SESSION['followup_description'][$dataCode]."',";

							
							$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
										`date_created`='".date('Y-m-d H:i:s')."',
										`id_mst_user_created_by`='".$_SESSION['userId']."',
										`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

							
							executeSql($sqlNotify);	
							}	
						}
				}
			}	
				echo '<p class="help-block">Quotation has been added 	Successfully!<br>Please Wait...
				<script>window.setTimeout(function() {window.location.href = "manageQuote.php";}, 1000);</script>
				<input type="hidden" id="recentEnqId" value="'.$VisiteReportInsertId.'"/> 
					 </p>';
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Quote Not Added!';
				echo "<script>window.location='manageQuote.php';</script>";
			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){ //update
			
			/*echo "<pre>";
			print_r($_SESSION);
			echo "-------------request--------<br>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;*/
				

			//checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILY_ENQUERY,'update');

			// fetching follow_up_summary
			if($_SESSION['followup_description']){
				foreach($_SESSION['followup_description'] as $dataCode =>$value){
					$follow_up_summary=$value;
					$follow_up_date=$_SESSION['followup_date'][$dataCode];
					//$assign_user = $_SESSION['assign_followup_user_id'][$dataCode];
				}
			}

			$editSql1 = "    UPDATE `".TBL_SALES_QUOTE."` SET 
`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',	
							`hotel_id` = '".addslashes($_POST['hotel_id'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							`status` = '".addslashes($_POST['status'])."',
							`follow_up_summary`='".$follow_up_summary."',
							`follow_up_date`='".date('Y-m-d',strtotime($follow_up_date))."'	,						
							`details` = '".addslashes($_POST['discussion_summary'])."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
							`checkin`='".date('Y-m-d',strtotime($checkin))."',
							`checkout`='".date('Y-m-d',strtotime($checkout))."',
							`id_rate`='".$_REQUEST['rate_id']."',
							`id_mst_user_modified_by`='".addslashes($_SESSION['userId'])."',
							`date_modified`='".currenDateTime()."',
							`lead_status` = '".addslashes($_POST['status'])."'";

			 $editSql1 .= "	
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";

							

			if(executeSql($editSql1)){

				

				

				//executeSql("DELETE from `".TBL_DAILYVISIT_FOLLOWUP."` where daily_Visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				//executeSql("DELETE from `".TBL_DAILY_ENQUERY."` where id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				executeSql("DELETE from `".TBL_SALES_QUOTE_FOLLOWUP."` where type='5' and id_quote='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				
				executeSql("DELETE from `".TBL_DAILY_CALENDER."` where type='5' and visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");


				//  `followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',

				//
				

				$VisiteReportInsertId= addslashes(encryptor('decrypt',$_POST[eId]));
				$count =1;
				 $insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='5',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`assign_user_id`='".addslashes($_SESSION['userId'])."',
						`visit_id` ='".addslashes($VisiteReportInsertId)."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
						`enquiry_details`='0',
						`status` = '".addslashes($_POST['status'])."'";

				executeSql($insertCalendar);
	

				 //`followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',
				
				$reminderDate=0;
				if($_SESSION['followup_hotel_id']!=""){
					foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){
						$count =0;
					 	$insertfollowup = "INSERT INTO `".TBL_SALES_QUOTE_FOLLOWUP."` SET 
					 		
					 		`id_company` = '".addslashes($_POST['id_company'])."',
							`id_quote`='".addslashes($VisiteReportInsertId)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`hotel_id`='".addslashes($_REQUEST['hotel_id'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							`id_user` = '".$_SESSION['userId']."',
							`assign_user_id` = '".$_SESSION['assign_user_id'][$dataCode]."',
							`type`='5',
							`details` = '".$_SESSION['followup_description'][$dataCode]."',
							`id_mst_user_created_by`='".addslashes($_SESSION['userId'])."',						
							`created_date`  = '".date('Y-m-d')."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
							`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
				  
							$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
							$count++;
							executeSql($insertfollowup);
							$EnquiryInsertId= $db->insert_id();
							
							$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
					 		`enquiry_details`='1',
					 		`type`='5',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_user`='".addslashes($_SESSION['userId'])."',
							`doc_id` ='".addslashes($EnquiryInsertId)."',
							`assign_user_id` = '".$_SESSION['assign_user_id'][$dataCode]."',
							`dated`='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
							`visit_id` ='".addslashes($VisiteReportInsertId)."',
							`status` = '".$_SESSION['followupstatus'][$dataCode]."'";

							executeSql($insertCalendar);

							if(executeSql($insertCalendar)){
							if(addslashes($_SESSION['assign_user_id'][$dataCode]) != $_SESSION['userId']){
							$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
										`id_shop`='".$_SESSION['shop']."',
										`source`='Quote Follow Up',
										`id_user_assigned_to`='".addslashes($_SESSION['assign_user_id'][$dataCode])."',
										`id_user_assigned_by`='".$_SESSION['userId']."',
										`dated` ='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
										`message`='".$_SESSION['followup_description'][$dataCode]."',";

							
							$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
										`date_created`='".date('Y-m-d H:i:s')."',
										`id_mst_user_created_by`='".$_SESSION['userId']."',
										`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

							
							executeSql($sqlNotify);	
							}	
						}

					}
				}
				


		
						
					//$_SESSION['successMsg'] = 'Enquiry Added Successfully!';		
				
				echo '<p class="help-block">Quotation has been added Successfully!<br>Please Wait...
					<script>window.setTimeout(function() {window.location.href = "manageQuote.php";}, 1000);</script>
					<input type="hidden" id="recentEnqId" value="'.addslashes(encryptor('decrypt',$_POST[eId])).'"/>
					 </p>';
				//FeedBack============================Start
				

				    $resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($_POST['id_contacts'])."'",''); 

		  			$resultContact = $db->fetch_object2($resContact);

                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;

				

				

				$_SESSION['successMsg'] = 'Updated sucessfully.';

				//header("location:addreport.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);

				/*echo '<p class="help-block">Follow Up has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "editDailyReport.php";}, 2000);</script>';*/

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved. Please make corrections below.';

			}

		}

	}else{ //Error

		$err++;

		$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections.';

	}





?>

                









