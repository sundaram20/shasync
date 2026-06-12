<?php include_once("../../config/auto_loader.php");


/************ Check for Mobile Number *************/
	$mob_no = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$_REQUEST['id_contacts'].'" ');
	
	if($mob_no==''){
		//echo '<span style="color:red;"> Mobile Number Is Mandatory.Please Edit The Details of Contact Person.</span>';
		//exit;
	}
/************ Check End *************/

	$err = 0;

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add	
			// fetching follow_up_summary
			if(!empty($_SESSION['followup_description'])){
				foreach($_SESSION['followup_description'] as $dataCode =>$value){
					$follow_up_summary=$value;
					$follow_up_date=$_SESSION['followup_date'][$dataCode];
					$assign_user = $_SESSION['assign_followup_user_id'][$dataCode];
				}
			}
			
			if(!empty($_SESSION['followup_description'])){
			
	$addEnq ="  			INSERT INTO `".INVOICE."` SET 							
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',	
							`hotel_id` = '".addslashes($_POST['id_hotel_md'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							 `id_user` = '".addslashes($_SESSION['userId'])."',	
							`status` = '1',
							`assign_user_id`='".$assign_user."',
							`type` = '6',
							`details` = '".addslashes($_POST['discussion_summary'])."',
							`amount`= '".addslashes($_POST['amount'])."',
							`id_company`= '".addslashes($_POST['Source'])."',
							`invoice_no`= '".addslashes($_POST['invoice_no'])."',
										`advance`= '".addslashes($_POST['advance'])."',
										`balance`= '".addslashes($_POST['balance'])."',
										`received`='".addslashes($_POST['received'])."',
										`checkin`= '".addslashes(date('Y-m-d',strtotime($_POST['check_in_date'])))."',
										`checkout`= '".addslashes(date('Y-m-d',strtotime($_POST['check_out_date'])))."',
										`due_date`= '".addslashes(date('Y-m-d',strtotime($_POST['duedate'])))."',
										`guest_name`= '".addslashes($_POST['guest_name'])."',
										`sales_manager`= '".addslashes($_POST['sales_manager'])."',
										`contact_person`= '".addslashes($_POST['contact_person'])."',  
										`contact_email`= '".addslashes($_POST['contact_email'])."',   
										`contact_mobile`= '".addslashes($_POST['contact_mobile'])."',
										`account_no`= '".addslashes($_POST['Account NO'])."', 
										`invoice_date`= '".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
										`mobile`= '".addslashes($_POST['mobile'])."',
										
										`account_email_id`= '".addslashes($_POST['account_email_id'])."',
										 
										
							`created_date`='".date('Y-m-d')."',	
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
							
							`expected_check_in_date`='".addslashes(date('Y-m-d',strtotime($_POST['expected_check_in_date'])))."',
							`expected_revenue` = '".addslashes($_POST['expected_revenue'])."',
							`expected_room_nights` = '".addslashes($_POST['expected_room_nights'])."',
							`percentage_of_conversion` = '".addslashes($_POST['percentage_of_conversion'])."',
							
							
							`follow_up_summary`='".$follow_up_summary."',
							`follow_up_date`='".date('Y-m-d',strtotime($follow_up_date))."',
							`created_by`='".addslashes($_SESSION['userId'])."',
							`date_created`='".currenDateTime()."',
							`modified_by`='".addslashes($_SESSION['userId'])."',
							`date_modified`='".currenDateTime()."',
							`lead_status` = '".addslashes($_POST['lead_followup_status'])."'
							";
						

			if(executeSql($addEnq)){				

				$VisiteReportInsertId= $db->insert_id();
				$count =1;
				$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='6',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
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
				 	$insertfollowup = "INSERT INTO `".INVOICE_DETAILS."` SET 
				 		
				 		`id_company` = '".addslashes($_POST['id_company'])."',
						`enquiry_id`='".addslashes($VisiteReportInsertId)."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`hotel_id`='".addslashes($_REQUEST['id_hotel_md'])."',	
						`id_contact`='".$_REQUEST['id_contacts']."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						
						`created_date`='".date('Y-m-d')."',
						`details` = '".$_SESSION['followup_description'][$dataCode]."',
						`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',
						`created_by`='".addslashes($_SESSION['userId'])."',
						`modified_by`='".addslashes($_SESSION['userId'])."',
						`type` = '6',
						`dated`  = '".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
						
						`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						$count++;
						executeSql($insertfollowup);
						$EnquiryInsertId= $db->insert_id();

						$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='6',
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
										`source`='Enquiry Follow Up',
										`id_user_assigned_to`='".addslashes($_SESSION['assign_followup_user_id'][$dataCode])."',
										`id_user_assigned_by`='".$_SESSION['userId']."',
										`dated` ='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
										`message`='".$_SESSION['followup_description'][$dataCode]."',";

							
							$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
										`date_created`='".date('Y-m-d H:i:s')."',
										`id_mst_user_created_by`='".$_SESSION['userId']."',
										`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

							$assingToEmail = $_SESSION['assign_followup_user_id'][$dataCode];
							executeSql($sqlNotify);	
							}	
						}
				}
			}	
							//Do you want to add more?	
				echo '<p class="help-block">Enquiry has been added 	Successfully!<br>
				<input type="hidden" id="recentEnqId" value="'.$VisiteReportInsertId.'"/> 
					 .</p>';
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Enquiry Not Added!';
				echo "<script>window.location='editDailyReport.php';</script>";
			}


			}else{
				$err++;
				echo  '7';
				
				
			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){ //update
			
			/*echo "<pre>";
			print_r($_SESSION);
			echo "-------------request--------<br>";
			print_r($_REQUEST);
			echo "</pre>";
			exit;*/
				

		if(!empty($_SESSION['followup_description'])){

			// fetching follow_up_summary
			if($_SESSION['followup_description']!=""){
				foreach($_SESSION['followup_description'] as $dataCode =>$value){
					$follow_up_summary=$value;
					$follow_up_date=$_SESSION['followup_date'][$dataCode];
				}
			}	

			$editSql1 = "    UPDATE `".INVOICE."` SET 
`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',	
							`hotel_id` = '".addslashes($_POST['id_hotel_md'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							`status` = '".addslashes($_POST['status'])."',
							`advance`= '".addslashes($_POST['advance'])."',
							`invoice_no`= '".addslashes($_POST['invoice_no'])."',
							`amount`= '".addslashes($_POST['amount'])."',
										`balance`= '".addslashes($_POST['balance'])."',
										`received`='".addslashes($_POST['received'])."',
										`checkin`= '".addslashes(date('Y-m-d',strtotime($_POST['check_in_date'])))."',
										`checkout`= '".addslashes(date('Y-m-d',strtotime($_POST['check_out_date'])))."',
										`due_date`= '".addslashes(date('Y-m-d',strtotime($_POST['duedate'])))."',
										`guest_name`= '".addslashes($_POST['guest_name'])."',
										`sales_manager`= '".addslashes($_POST['sales_manager'])."',
										`contact_person`= '".addslashes($_POST['contact_person'])."',  
										`contact_email`= '".addslashes($_POST['contact_email'])."',   
										`contact_mobile`= '".addslashes($_POST['contact_mobile'])."',
										`account_no`= '".addslashes($_POST['Account NO'])."', 
										`invoice_date`= '".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
										`mobile`= '".addslashes($_POST['mobile'])."',
										
										`account_email_id`= '".addslashes($_POST['account_email_id'])."',
										
							`type` = '6',
							`follow_up_summary`='".$follow_up_summary."',
							`follow_up_date`='".date('Y-m-d',strtotime($follow_up_date))."',
							
							`expected_check_in_date`='".addslashes(date('Y-m-d',strtotime($_POST['expected_check_in_date'])))."',
							`expected_revenue` = '".addslashes($_POST['expected_revenue'])."',
							`expected_room_nights` = '".addslashes($_POST['expected_room_nights'])."',
							`percentage_of_conversion` = '".addslashes($_POST['percentage_of_conversion'])."',
							
							`details` = '".addslashes($_POST['discussion_summary'])."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
							`modified_by`='".addslashes($_SESSION['userId'])."',
							`date_modified`='".currenDateTime()."',
							`lead_status` = '".addslashes($_POST['lead_followup_status'])."'";

			 $editSql1 .= "	
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
//echo $editSql1;
						//die;	

			if(executeSql($editSql1)){

				

				

				//executeSql("DELETE from `".TBL_DAILYVISIT_FOLLOWUP."` where daily_Visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				//executeSql("DELETE from `".INVOICE."` where id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				$enquiry_close_sum=selectColumn(INVOICE_DETAILS,'enquiry_close_summary','where enquiry_id="'.addslashes(encryptor('decrypt',$_POST['eId'])).'" ');
				$close_up_id=selectColumn(INVOICE_DETAILS,'followup_close_type_id','where enquiry_id="'.addslashes(encryptor('decrypt',$_POST['eId'])).'" ');

				executeSql("DELETE from `".INVOICE_DETAILS."` where type='6' and enquiry_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				
				executeSql("DELETE from `".TBL_DAILY_CALENDER."` where type='6' and visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");


				//  `followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',

				//
				

				$VisiteReportInsertId= addslashes(encryptor('decrypt',$_POST[eId]));
				$count =1;
				 $insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='6',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						
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
					 	$insertfollowup = "INSERT INTO `".INVOICE_DETAILS."` SET 
					 		
					 		`id_company` = '".addslashes($_POST['id_company'])."',
							`enquiry_id`='".addslashes($VisiteReportInsertId)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`hotel_id`='".addslashes($_REQUEST['id_hotel_md'])."',	
							`followup_close_type_id`='".$close_up_id."',
							`enquiry_close_summary`='".$enquiry_close_sum."',					
							`id_user` = '".$_SESSION['userId']."',
							`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',							
							`type`='6',
							`id_contact`='".$_REQUEST['id_contacts']."',
							`details` = '".$_SESSION['followup_description'][$dataCode]."',
							`created_by`='".addslashes($_SESSION['userId'])."',	
							`modified_by`='".addslashes($_SESSION['userId'])."',						
							`created_date`  = '".date('Y-m-d')."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
							`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
				  
							$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
							$count++;
							executeSql($insertfollowup);
							$EnquiryInsertId= $db->insert_id();
							
							$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
					 		`enquiry_details`='1',
					 		`type`='6',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_user`='".addslashes($_SESSION['userId'])."',
							`doc_id` ='".addslashes($EnquiryInsertId)."',
							`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',
							`dated`='".date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]))."',
							`visit_id` ='".addslashes($VisiteReportInsertId)."',
							`status` = '".$_SESSION['followupstatus'][$dataCode]."'";

							executeSql($insertCalendar);

							if(executeSql($insertCalendar)){
							if(addslashes($_SESSION['assign_followup_user_id'][$dataCode]) != $_SESSION['userId']){
							$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
										`id_shop`='".$_SESSION['shop']."',
										`source`='Enquiry Follow Up',
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
				


		
						
					//$_SESSION['successMsg'] = 'Enquiry Added Successfully!';		
				//	Do you want to add more
				echo '<p class="help-block">Enquiry has been added Successfully!<br>
				
					<input type="hidden" id="recentEnqId" value="'.addslashes(encryptor('decrypt',$_POST[eId])).'"/>
					 .</p>';
						

				

				

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
			
		}else{
				$err++;
				echo  '7';
				
				
			}

		}

	}else{ //Error

		$err++;

		$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections.';

	}





?>

                









