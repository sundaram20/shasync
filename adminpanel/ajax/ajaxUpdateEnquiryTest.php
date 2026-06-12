<?php include_once("../../config/auto_loader.php");


/************ Check for Mobile Number *************/
	$mob_no = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$_REQUEST['id_contacts'].'" ');
	
	if($mob_no==''){
		echo '<span style="color:red;"> Mobile Number Is Mandatory.Please Edit The Details of Contact Person.</span>';
		exit;
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
			
	$addEnq ="  			INSERT INTO `".TBL_DAILY_ENQUERY."` SET 							
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',
						   `id_mst_lead_source` = '".addslashes($_POST['id_mst_lead_source'])."',
						   `id_enquiry_item_details` = '".$enquiryItemDetailsInsertId."',	
	
							`hotel_id` = '".addslashes($_POST['id_hotel_md'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							 `id_user` = '".addslashes($_SESSION['userId'])."',	
							`status` = '1',
							`assign_user_id`='".$assign_user."',
							`type` = '4',
							`details` = '".addslashes($_POST['discussion_summary'])."',
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
							`lead_status` = '".addslashes($_POST['status'])."'
							";
						

			if(executeSql($addEnq)){				

				$VisiteReportInsertId= $db->insert_id();
				$count =1;
				$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='4',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_user`='".addslashes($_SESSION['userId'])."',
						`doc_id` ='0',
						`visit_id` ='".addslashes($VisiteReportInsertId)."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_POST['enquiryDate'])))."',
						
						`status` = '".addslashes($_POST['status'])."'";

				executeSql($insertCalendar);

				$insertEnquiryItemDetails = " INSERT INTO `enquiry_item_details` SET

							
							`id_inv_indent` = '".explode('-',$id_inv_indent)[1]."', 
							`id_inv_indent_details` = '".addslashes($_POST["id_inv_indent_details"])."',
							`base_currency_code` = '".addslashes($_POST['base_currency_code1'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code1'])."',
							`exchange_rate` = '".addslashes($_POST['exchange_rate'])."', 
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."', 
							`id_mst_charges_sgst` = '".addslashes($_POST['id_mst_charges_sgst'])."', 
							`id_mst_charges_cgst` = '".addslashes($_POST['id_mst_charges_cgst'])."', 
							`id_mst_charges_igst` = '".addslashes($_POST['id_mst_charges_igst'])."', 
							`transaction_unit` = '".addslashes($_POST["transaction_unit"])."', 
							`qty` = '".addslashes($qty_total)."', 
							`bal_qty` = '".addslashes($qty_total)."', 
							`main_unit` = '".addslashes($_POST["main_unit"])."', 
							`per_unit` = '".addslashes($_POST["per_unit"])."', 
							`alt_unit` = '".addslashes($_POST["alt_unit"])."', 
							`alt_qty` = '".addslashes($alt_qty)."', 
							`conver_rate_per_unit` = '".addslashes($_POST['conver_rate_per_unit'])."', 
							`id_mst_charges_purchase_local` = '".addslashes($_POST['id_mst_charges_purchase_local'])."', 
							`id_mst_charges_purchase_interstate` = '".addslashes($_POST['id_mst_charges_purchase_interstate'])."', 
							`rate_per_main_unit` = '".addslashes($rate_per_main_unit)."', 
							`rate_per_alt_unit` = '".addslashes($rate_per_alt_unit)."', 
							`item_amount_before_discount` = '".addslashes($_POST['item_amount_before_discount'])."', 
							`discount_percent` = '".addslashes($_POST['discount_percent'])."', 
							`discount_amount` = '".addslashes($_POST['discount_amount'])."', 
							`item_sgst_percent` = '".addslashes($_POST['item_sgst_percent'])."', 
							`item_sgst_amount` = '".addslashes($_POST['item_sgst_amount'])."', 
							`item_cgst_percent` = '".addslashes($_POST['item_cgst_percent'])."', 
							`item_cgst_amount` = '".addslashes($_POST['item_cgst_amount'])."', 
							`item_igst_percent` = '".addslashes($_POST['item_igst_percent'])."', 
							`item_igst_amount` = '".addslashes($_POST['item_igst_amount'])."', 
							`item_amount` = '".addslashes($_POST['item_amount'])."', 
							`item_remarks` = '".addslashes($_POST['item_remarks'])."',  
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$insertEnquiryItemDetails .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							if(executeSql($insertEnquiryItemDetails)){
								$enquiryItemDetailsInsertId= $db->insert_id();

							}


				 //`followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',
				
				$reminderDate=0;
			if($_SESSION['followup_hotel_id']!=""){
				foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){
					$count =0;
				 	$insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
				 		
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
						`type` = '4',
						`dated`  = '".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
						
						`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						$count++;
						executeSql($insertfollowup);
						$EnquiryInsertId= $db->insert_id();

						$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		`enquiry_details`='1',
				 		`type`='4',
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

			$editSql1 = "    UPDATE `".TBL_DAILY_ENQUERY."` SET 
`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company` = '".addslashes($_POST['id_company'])."',	
							`hotel_id` = '".addslashes($_POST['id_hotel_md'])."',	
						    `id_mst_lead_source` = '".addslashes($_POST['id_mst_lead_source'])."',	
							`id_contact`='".$_POST['id_contacts']."',
							`status` = '".addslashes($_POST['status'])."',
							`type` = '4',
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
							`lead_status` = '".addslashes($_POST['status'])."'";

			 $editSql1 .= "	
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";

							

			if(executeSql($editSql1)){

				

				

				//executeSql("DELETE from `".TBL_DAILYVISIT_FOLLOWUP."` where daily_Visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				//executeSql("DELETE from `".TBL_DAILY_ENQUERY."` where id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				$enquiry_close_sum=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary','where enquiry_id="'.addslashes(encryptor('decrypt',$_POST['eId'])).'" ');
				$close_up_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'followup_close_type_id','where enquiry_id="'.addslashes(encryptor('decrypt',$_POST['eId'])).'" ');

				executeSql("DELETE from `".TBL_DAILY_ENQUERY_DETAILS."` where type='4' and enquiry_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");

				
				executeSql("DELETE from `".TBL_DAILY_CALENDER."` where type='4' and visit_id='".addslashes(encryptor('decrypt',$_POST['eId']))."' ");


				//  `followup_summary`='".addslashes($_REQUEST['followup_description'][$dataCode])."',

				//
				

				$VisiteReportInsertId= addslashes(encryptor('decrypt',$_POST[eId]));
				$count =1;
				 $insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
				 		
				 		`type`='4',
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
					 	$insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
					 		
					 		`id_company` = '".addslashes($_POST['id_company'])."',
							`enquiry_id`='".addslashes($VisiteReportInsertId)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`hotel_id`='".addslashes($_REQUEST['id_hotel_md'])."',	
							`followup_close_type_id`='".$close_up_id."',
							`enquiry_close_summary`='".$enquiry_close_sum."',					
							`id_user` = '".$_SESSION['userId']."',
							`assign_user_id` = '".$_SESSION['assign_followup_user_id'][$dataCode]."',							
							`type`='4',
							`id_contact`='".$_REQUEST['id_contacts']."',
							`details` = '".$_SESSION['followup_description'][$dataCode]."',
							`created_by`='".addslashes($_SESSION['followup_created_by'][$dataCode])."',	
							`modified_by`='".addslashes($_SESSION['followup_modified_by'][$dataCode])."',						
							`created_date`  = '".date('Y-m-d')."',
							`dated`='".addslashes(date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode])))."',
							`lead_status` = '".$_SESSION['followupstatus'][$dataCode]."'";
				  
							$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
							$count++;
							executeSql($insertfollowup);
							$EnquiryInsertId= $db->insert_id();
							
							$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
					 		`enquiry_details`='1',
					 		`type`='4',
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

                









