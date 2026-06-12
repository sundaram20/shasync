<?php  include_once("../../config/auto_loader.php");

$Sql = "SELECT * FROM `call_request` where   `id` = '".addslashes($_REQUEST['id_call_request'])."' AND status='0'  ";	
$row = mysqli_fetch_object(mysqli_query($connNew,$Sql));
$row->json_data;
$Records=array();
$Records['Call']	=json_decode($row->json_data,true);	
$format = $row->format_type;
$list_name = $row->list_id;





$listarray = array();
//$arrayList ='<tbody>';

$user_codeShortCode	=	selectColumn('fs_users','user_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `id` = '".addslashes($_SESSION['userId'])."'");

$lastRecordRes = executeSql("SELECT MAX(`reference`) as maxId FROM `call_master` WHERE id=(SELECT MAX(id) FROM call_master WHERE id_shop='".addslashes($_SESSION['shop'])."') AND  `id_user`= '".$_SESSION['userId']."'");

			$lastRecordRow = $db->fetch_object2($lastRecordRes);

			$mystring 	   = $lastRecordRow->maxId;			

			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			

			$newId 		   = sprintf("%'03d", ($newId+1));

			$reference 	   = $user_codeShortCode.$newId;






			$In=0;
				foreach($Records as $DataList){
					foreach($DataList as $Call){
					
								 
								 $CallText ='Success';
								 $CallStatus='0';
						
						if($Call['Resource Name']== 'Bipin Kumar'){
							$assign_user_id = 363;
						}else if($Call['Resource Name']== 'Bhawna'){
							$assign_user_id = 368;
						}else if($Call['Resource Name']== 'Deepika Bhardwaj'){
							$assign_user_id = 364;
						}else if($Call['Resource Name']== 'Faariya'){
							$assign_user_id = 371;
						}else if($Call['Resource Name']== ''){
							$assign_user_id = 370;
						}else if($Call['Resource Name']== 'AADIYAR INFOTECH PRIVATE LIMITE'){
							$assign_user_id = 370;
						}else{
							$assign_user_id = 370;
						}
						
						
						$extra_data=[];

							if($format == 'tss'){
								$extra_data = [
									'serial' => $Call['Serial'],
									'expiry_date' => $Call['Expiry Date'],
									'flavour' => $Call['Flavour'],
									'release' => $Call['Release'],
									'account_id' => $Call['Account ID'],
									'admin_id' => $Call['Admin ID'],
									'city'=> $Call['City'],
									'state'=> $Call['State'],
									'pin_code'=> $Call['Pin Code'],
									'contact_person'=> $Call['Contact Person'],
									'landline'=> $Call['Landline'],
									'executive'=>$Call['Resource Name'],
									'email'=>$Call['Email ID'],
									'mobile'=>$Call['Mobile']
								];
								
								$extra_data_json = addslashes(json_encode($extra_data, true));
								
								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										
										
										`name`= '".addslashes($Call['Customer Name'])."',
										`mobile`= '".addslashes($Call['Mobile'])."',
										`email`= '".addslashes($Call['Email ID'])."',
										
										
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addCall;die;

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '".$assign_user_id."',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='".$Call['Resource Name']."',
										`assign_user_id` = '".$assign_user_id."',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
								
								
							}else if($format == 'mau'){
								
								if($Call['MAU']=='true'){
								$mau = 'True';
								}else{
								$mau = 'False';
								}
								
								if($Call['Ping in this Quarter']=='true'){
								$ping = 'True';
								}else{
								$ping = 'False';
								}
								
								$extra_data = [
									'serial' => $Call['Serial Number'],
									'Product' => $Call['Product'],
									'release'=>$Call['Release'],
									'expiry_date'=>$Call['TSS Expiry Date'],
									'company_name'=>$Call['Org Name'],
									'contact_person' => $Call['Contact Person'],
									'mobile' => $Call['Mobile']??'-',
									'email'=>trim($Call['Email ID']??'-')
								];
								
								if (empty($Call['Mobile']) || empty($Call['Email'])) {
    								error_log("Missing mobile or email: " . print_r($Call, true));
								}
								
								$extra_data_json = addslashes(json_encode($extra_data, true));
								
								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										
										
										`name`= '".addslashes($Call['Org Name'])."',
										`mobile`= '".addslashes($Call['Mobile'])."',
										`email`= '".addslashes($Call['Email ID'])."',
										
										
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '364',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='Deepika Bhardwaj',
										`assign_user_id` = '364',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
								
							}else if($format == 'salesync'){
								
								if($Call['Executive']== 'Vipin Pandey'){
							$assign_user_id = 363;
						}else if($Call['Executive']== 'Bhawna'){
							$assign_user_id = 368;
						}else if($Call['Executive']== 'Deepika Bhardwaj'){
							$assign_user_id = 364;
						}else if($Call['Executive']== 'Faariya'){
							$assign_user_id = 371;
						}else if($Call['Executive']== ''){
							$assign_user_id = 370;
						}else if($Call['Executive']== 'AADIYAR INFOTECH PRIVATE LIMITED'){
							$assign_user_id = 370;
						}else{
							$assign_user_id = 370;
						}
								
								$extra_data = [
									'S_no' => $Call['S.No.']??'-',
									'bill_no' => $Call['Bill No']??'-',
									'executive'=>$Call['Executive']??'-',
									'serial' => $Call['Tally Serial No']??'-',
									'Product' => trim($Call['Item Name']??'-'),
									'contact_person'=> $Call['Contact Person']??'-',
									'Designation' => $Call['Designation']??'-',
									'mobile'=>$Call['Mobile No']??'-',
									'email'=>trim($Call['Email']??'-'),
									'company_name'=>trim($Call['Company Name']??'-'),
									'lead_source'=>trim($Call['Lead Source']??'')
	
								];
								
								$closeType = $Call['Close Type'] ?? '';
                                $lastRemark = mysqli_real_escape_string($connNew, $Call['Last Remarks'] ?? '');

                                $call_remark = trim($closeType . ' - ' . $lastRemark, '-');
								if($Call['Status']=='Close'){
								  $call_status = '0';
								}else{
								  $call_status = '1';
								}
								$int_remark = mysqli_real_escape_string($connNew, $Call['Lead Description']??'');
								
								
								$extra_data_json = addslashes(json_encode($extra_data, true));
								
								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`name`= '".addslashes($Call['Company Name'])."',
										`mobile`= '".addslashes($Call['Mobile No'])."',
										`email`= '".addslashes($Call['Email'])."',
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addCall;die;

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '".$assign_user_id."',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='".$Call['Executive']."',
										`assign_user_id` = '".$assign_user_id."',
										`call_remark` = '".$call_remark."',
										`internal_remark` = '".$int_remark."',
										`call_status` = '".$call_status."'
										";
						
						executeSql($addCallDetail);
								
							}else if($format == 'webinar'){
								
								
								if($Call['assign']== 'Vipin Pandey'){
							$assign_user_id = 363;
						}else if($Call['assign']== 'Bhawna'){
							$assign_user_id = 368;
						}else if($Call['assign']== 'Deepika Bhardwaj'){
							$assign_user_id = 364;
						}else if($Call['assign']== 'Faariya'){
							$assign_user_id = 371;
						}else if($Call['assign']== ''){
							$assign_user_id = 370;
						}else if($Call['assign']== 'AADIYAR INFOTECH PRIVATE LIMITED'){
							$assign_user_id = 370;
						}else{
							$assign_user_id = 370;
						}
								
								$contact_person = $Call['First Name'].' '.$Call['Last Name'];
								
								$extra_data = [
									'executive'=>$Call['assign']??'-',
									'serial' => $Call['Serial no.']??'-',
									'contact_person'=> $contact_person??'-',
									'mobile'=>$Call['Mobile']??'-',
									'phone'=>$Call['Phone']??'-',
									'email'=>trim($Call['Contact Email']??'-'),
									'company_name'=>trim($Call['Company Name']??'-')

									
								];
								
								$extra_data_json = addslashes(json_encode($extra_data, true));
								
								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`name`= '".addslashes($Call['Company Name'])."',
										`mobile`= '".addslashes($Call['Mobile'])."',
										`email`= '".addslashes($Call['Contact Email'])."',
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addCall;die;

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '".$assign_user_id."',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='".$Call['Executive']."',
										`assign_user_id` = '".$assign_user_id."',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
							
							}else if($format == 'aws'){

							$assign_user_id = 364;

							$executive = selectColumn('fs_users','name',"WHERE id = '".assign_user_id."'");
							
								$extra_data = [
									'serial' => $Call['Serial no.']??'-',
									'company_name'=>trim($Call['Account Name']??'-'),
									'instance_type'=>trim($Call['Instance Type']??'-'),
									'cloud'=>trim($Call['Cloud']??'-'),
									'expiry_date'=>$Call['Expiry Date'],
									'created_date'=>$Call['Created Date'],
									'mobile'=>$Call['mobile no']??'-',
									'email'=>trim($Call['email']??'-'),
									'partner'=>trim($Call['Partner Name']??'-')
									
								];

								$extra_data_json = addslashes(json_encode($extra_data, true));

								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`name`= '".addslashes($Call['Account Name'])."',
										`mobile`= '".addslashes($Call['mobile no'])."',
										`email`= '".addslashes($Call['email'])."',
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addCall;die;

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '".$assign_user_id."',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='".$executive."',
										`assign_user_id` = '".$assign_user_id."',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
							
							}else if($format == 'amc'){
								
								if($Call['MAU']=='true'){
								$mau = 'True';
								}else{
								$mau = 'False';
								}
								
								if($Call['Ping in this Quarter']=='true'){
								$ping = 'True';
								}else{
								$ping = 'False';
								}
								
								$extra_data = [
									'serial' => $Call['Serial Number'],
									'Product' => $Call['Product'],
									'release'=>$Call['Release'],
									'expiry_date'=>$Call['TSS Expiry Date'],
									'company_name'=>$Call['Org Name'],
									'contact_person' => $Call['Contact Person'],
									'mobile' => $Call['Mobile']??'-',
									'email'=>trim($Call['Email ID']??'-')
								];
								
								if (empty($Call['Mobile']) || empty($Call['Email'])) {
    								error_log("Missing mobile or email: " . print_r($Call, true));
								}
								
								$extra_data_json = addslashes(json_encode($extra_data, true));
								
								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										
										
										`name`= '".addslashes($Call['Org Name'])."',
										`mobile`= '".addslashes($Call['Mobile'])."',
										`email`= '".addslashes($Call['Email ID'])."',
										
										
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '364',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='Deepika Bhardwaj',
										`assign_user_id` = '364',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
								
							}else if($format == 'cocloud'){

							$assign_user_id = 364;

							$executive = selectColumn('fs_users','name',"WHERE id = '".assign_user_id."'");
							
								$extra_data = [
									'sub_id' => $Call['SubId']??'-',
									'customer_name'=>trim($Call['CustomerName']??'-'),
									'email'=>trim($Call['CustomerEmail']??'-'),
									'mobile'=>$Call['CustomerMobile']??'-',
									'customer_last_login'=>$Call['CustomerLastLogin']??'-',
									'stard_date'=>$Call['StartDate']??'-',
									'last_renew_date'=>$Call['LastRenewDate']??'-',
									'end_date'=>$Call['EndDate']??'-',
									'plan_name'=>trim($Call['PlanName']??'-'),
									'plan_unit_price'=>trim($Call['PlanUnitPrice']??'-'),
									'no_of_users'=>$Call['NoOfUsers']??'-',
									'sales_person_name'=>$Call['SalesPersonName'],
									'relationship_manager'=>$Call['relationshipManager'],
									'duration'=>$Call['Duration'],
									'amount'=>$Call['Amount'],
									'status'=>trim($Call['Status']??'-')
									
								];

								$extra_data_json = addslashes(json_encode($extra_data, true));

								$addCall = "  INSERT INTO `call` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',	
										`name`= '".addslashes($Call['CustomerName'])."',
										`mobile`= '".addslashes($Call['CustomerMobile'])."',
										`email`= '".addslashes($Call['CustomerEmail'])."',
										`id_user`= '".$_SESSION['userId']."',
										`date_created`= '".currenDateTime()."', 
										`created_by`= '".$_SESSION['userId']."',
										`date_modified`= '".currenDateTime()."',
										`modified_by`= '".$_SESSION['userId']."'";

//echo $addCall;die;

													

								executeSql($addCall);

								$addCallAssignId = $db->insert_id();
						$In++;
						
						
						
						$addCallDetail = "INSERT INTO `call_details` SET 
										`id_shop` = '".$_SESSION['shop']."',
										`id_user` = '".$assign_user_id."',
										`call_id`= '".$addCallAssignId."',
										`id_list_name` = '".addslashes($list_name)."',
										`format_type` = '".addslashes($format)."',
										`extra_data` = '".$extra_data_json."',
										`executive`='".$executive."',
										`assign_user_id` = '".$assign_user_id."',
										`call_status` = '1'
										";
						
						executeSql($addCallDetail);
							
							}
								 
				 
								 
								 
						  
						
						
					}
		
				}
			   
			                 
				
                
			
			
			$listarray['count']=$In;
			$listarray['status']=$CallStatus;
			echo json_encode($listarray);
?>