<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo "<pre>";print_r($_REQUEST);echo "</pre>";
//
$Array_count_roomeid=	sizeof($_REQUEST['room_type_id']);
for($i=0;$i<$Array_count_roomeid;$i++){

	$data_id[]	=	$_POST['room_type_id'][$i].'|'.$_POST['RatePlanID'][$i].'|0';
	}
$data_id;

$Array_count_roomeid=	sizeof($_REQUEST['rate_level_id']);

$rate_level_id	=	array($_POST['rate_level_id']);
$rate_level_id = array_pad($rate_level_id, $Array_count_roomeid, $_POST['rate_level_id']);


/*foreach($data_id as $data =>$value){
	
	//echo "<br>".$data.$value;
	
	echo "<br>".$_POST['room_type_id'][$data];
	}
die;*/
//echo "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where a.company_id='".addslashes($_POST['company_id'])."' and a.rate_level_id='".addslashes($_POST['rate_level_id'])."' and a.seasonId='".addslashes($_POST['seasonId'])."' and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id AND b.rate_plan_id='".addslashes($_POST['rate_plan_id'][$i])."' ";
//die;
if($_POST['Save']){		
	$err = 0;	
	if($_POST['id']==''){
		if(empty($_POST['start_date'])){
			$err++;
			echo '<font style="color:red;font-weight:normal;" ><br>Please enter start date.</font>';
		}
		if(empty($_POST['end_date'])){
			$err++;
			echo '<font style="color:red;font-weight:normal;" ><br>Please enter end date.</font>';
		}
		if(empty($_POST['rate_level_id'])){
			$err++;
			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';
		}
		if(empty($_POST['seasonId'])){
			$err++;
			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';
		}
				
	}	
	if(empty($data_id)){
			$err++;
			echo '<font style="color:red;font-weight:normal;" ><br>Please assign room to this hotel.<br></font>';
		}	
		
		
	if($err == 0){//No error	
	$inclusionDetail = json_encode(array_combine($_POST['inclusion_id'],$_POST['inclusion_detail']));
	
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			
			for($i=0;$i<$Array_count_roomeid;$i++){
			
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'add');
			
			$checkExisting = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where a.company_id='".addslashes($_POST['company_id'])."' and a.rate_level_id='".addslashes($_POST['rate_level_id'])."' and a.seasonId='".addslashes($_POST['seasonId'])."' and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id AND b.rate_plan_id='".addslashes($_POST['rate_plan_id'][$i])."'");
				if(num_rows($checkExisting)>0){
					$err++;
					echo '<p>Rate details has been already added for this data. Automatically redirecting...</p>';/*<script>window.setTimeout(function() {window.location.href = "manageRateMaster.php?page=1";}, 2000);</script>*/ 				
				}else{
			
			
$checkRatePlanExisting = executeSql("SELECT * FROM `".TBL_RATE."` as a where a.company_id='".addslashes($_POST['company_id'])."' and a.rate_level_id='".addslashes($_POST['rate_level_id'])."' and a.seasonId='".addslashes($_POST['seasonId'])."' and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' ");
if(num_rows($checkRatePlanExisting)>0){
	$rowRatePlanExisting = $db->fetch_object2($checkRatePlanExisting);
	 $rate_id	=	$rowRatePlanExisting->id;
	
	
	
	}else{

			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
			
			
			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE id=(SELECT MAX(id) FROM `fs_rate` WHERE id_shop='".addslashes($_SESSION['shop'])."' ) AND  `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode.$newId;
			
			
				
				
				$start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
				$end_date	=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
				
						$addRate = "    INSERT INTO `".TBL_RATE."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`sub_code` = '01',
										`company_id` = '".addslashes($_POST['company_id'])."',
										`rate_level_id` = '".addslashes($_POST['rate_level_id'])."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',							
										`id_contacts` = '".addslashes($_POST['id_contacts'])."',							
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										`additional_points` = '".addslashes($_POST['additional_points'])."',
										`rate_points` = '".addslashes(implode(',',$_POST['rate_points']))."',
										`generalterms` = '".addslashes($_POST['generalterms'])."',	
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						$addRate .= "	,`date_created` = '".currenDateTime()."'
										,`created_by` = '".$_SESSION['userId']."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`allow_booking`='".addslashes($_POST['allow_booking'])."'
										,`status` = '".addslashes($_POST['status'])."'";						
						executeSql($addRate);
								$rate_id= $db->insert_id();								
								
						}
							
						$checkRateAssingExisting = executeSql("SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes(encryptor('decrypt',$_POST['id']))."' and hotel_id='".addslashes($_POST['hotelId'])."'");
				
			if(num_rows($checkRateAssingExisting)>0){
				$rowRateAssingExisting = $db->fetch_object2($checkRateAssingExisting);
				$rateAssignId		   = $rowRateAssingExisting->id;
				
				}else{	
						
						$addRateAssign = "  INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SET 
													`rate_id` = '".addslashes($rate_id)."',
													`hotel_id` = '".addslashes($_POST['hotelId'])."',					
													`remarks` = '".addslashes($_POST['remarks'])."',
													`inclusion_detail` = '".$inclusionDetail."'
													,`date_created` = '".currenDateTime()."'
													,`last_modified` = '".currenDateTime()."'
													,`last_modified_by` = '".$_SESSION['userId']."'";
								executeSql($addRateAssign);
								$rateAssignId = $db->insert_id();
				}
								
				$start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
				$end_date	=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");	
								
								foreach($data_id as $data =>$value){
								$status = isset($_POST['status'][$data]) ? 1 : 0;
								
								$addRateDetail   = "INSERT INTO `".TBL_RATE_DETAILS."` SET 
													`rate_id` = '".addslashes($rate_id)."',
													`rate_assign_id` = '".addslashes($rateAssignId)."',
													`room_id` = '".addslashes($_POST['room_type_id'][$data])."',
													`rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."',
													`hotel_id` = '".addslashes($_POST['hotelId'])."',	
													`rack_rate` = '".addslashes($_POST['rack_rate'][$data])."',
													`discount_type` = '".addslashes($_POST['discount_type'.$value])."'";
								if($_POST['discount_type'.$value]==2){
								 $addRateDetail .=	",`discount` = '".addslashes($_POST['discountPercent'.$value])."'";	
									}else{					
								 $addRateDetail .=	",`discount` = '".addslashes($_POST['discountFlat'.$value])."'";			
									}					
								 $addRateDetail .=	",`room_price` = '".addslashes($_POST['room_price'][$data])."',
													`extra_bed_price` = '".addslashes($_POST['extra_bed'][$data])."',
													
													`hotel_remarks`='".addslashes($_POST['hotel_remarks'])."',
													`single_pax_price` = '".addslashes($_POST['single_pax_price'][$data])."',
													`double_pax_price` = '".addslashes($_POST['double_pax_price'][$data])."',	
													`weekend_single_pax_price` = '".addslashes($_POST['weekend_single_pax_price'][$data])."',
													`weekend_double_pax_price` = '".addslashes($_POST['weekend_double_pax_price'][$data])."',													
													`breakfast_price` = '".addslashes($_POST['breakfast_price'][$data])."',
													`lunch_price` = '".addslashes($_POST['lunch_price'][$data])."',
													`dinner_price` = '".addslashes($_POST['dinner_price'][$data])."',
													`tax_room` = '".addslashes($_POST['tax_room'][$data])."',							
													`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
													`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."',
													`min_nights` = '".addslashes($_POST['min_nights'][$data])."',
													`min_pax` = '".addslashes($_POST['min_pax'][$data])."',
													`pkg_price` = '".addslashes($_POST['pkg_price'][$data])."',
													`pkg_tarrif_price` = '".addslashes($_POST['pkg_tarrif_price'.$value])."',
													`pkg_discount` = '".addslashes($_POST['pkg_discount'.$value])."',
													`pkg_extra_price` = '".addslashes($_POST['pkg_extra_price'.$value])."',
													`pkg_min_nights` = '".addslashes($_POST['pkg_min_nights'.$value])."',
													`pkg_min_pax` = '".addslashes($_POST['pkg_min_pax'.$value])."',
													`pkg_title` = '".addslashes($_POST['pkg_title'.$value])."',
													`pkg_description` = '".addslashes($_POST['pkg_description'.$value])."',
													`pkg_status` = '".addslashes($_POST['pkg_status'.$value])."',
													`plan_type` = '".addslashes($_POST['plan_type'.$value])."'";												
								 $addRateDetail .= "	,`detail_status` = '1'";	
													
										executeSql($addRateDetail);	
									}	
									
							
							echo '<p class="help-block">New Rate details has been added sucessfully.</p><script>window.setTimeout(function() { window.location.href = "editRateMaster.php?hotelId='.encryptor('encrypt',($_POST['hotelId'])).'&id='.encryptor('encrypt',($rate_id)).'&action=edit&page=1";}, 2000); </script>';
							unset($_POST);
			/*	}else{
					$err++;
					echo '<p class="help-block">Rate details has not been saved. Please make corrections.</p>';
				
				}*/
			}
			
		}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
			

							
			
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'update');
			
			$lastRecordRes = executeSql("Select sub_code as maxId from `".TBL_RATE."` where `id` = '".addslashes($_POST['id'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$subCode = sprintf("%'02d", ($lastRecordRow->maxId+1));
				
			$editRate = " UPDATE `".TBL_RATE."` SET 
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`sub_code` = '".addslashes($subCode)."',							
							`id_contacts` = '".addslashes($_POST['id_contacts'])."',		
							`rate_points` = '".addslashes(implode(',',$_POST['rate_points']))."',
							`generalterms` = '".addslashes($_POST['generalterms'])."',
							`additional_points` = '".addslashes($_POST['additional_points'])."',		
							`remarks` = '".addslashes($_POST['remarks'])."'";
			$editRate .= "	,`last_modified` = '".currenDateTime()."'
			,`allow_booking`='".addslashes($_POST['allow_booking'])."'
							,`status` = '".addslashes($_POST['status'])."'	
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";
			executeSql($editRate);	
			
			
			
			$checkRateAssingExisting = executeSql("SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes(encryptor('decrypt',$_POST['id']))."' and hotel_id='".addslashes($_POST['hotelId'])."'");
				
			if(num_rows($checkRateAssingExisting)>0){
				$rowRateAssingExisting = $db->fetch_object2($checkRateAssingExisting);
				$rateAssignId		   = $rowRateAssingExisting->id;
				
				}else{	
						
						$addRateAssign = "  INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SET 
													`rate_id` = '".addslashes(encryptor('decrypt',$_POST['id']))."',
													`hotel_id` = '".addslashes($_POST['hotelId'])."',					
													`remarks` = '".addslashes($_POST['remarks'])."',
													`inclusion_detail` = '".$inclusionDetail."'
													,`date_created` = '".currenDateTime()."'
													,`last_modified` = '".currenDateTime()."'
													,`last_modified_by` = '".$_SESSION['userId']."'";
								executeSql($addRateAssign);
								$rateAssignId = $db->insert_id();
				}
					
//if(executeSql($editRate)){			
/*$checkExisting = executeSql("SELECT * FROM `".TBL_RATE_DETAILS."` where rate_id='".addslashes(encryptor('decrypt',$_POST['id']))."' and hotel_id='".addslashes($_POST['hotelId'])."'");



if(num_rows($checkExisting)>0){	*/
			
				
																									
				foreach($data_id as $data =>$value){
				
				
				$status = isset($_POST['status'][$data]) ? 1 : 0;
				
				$checkExistingRateDetailRoomWise = executeSql("SELECT * FROM `".TBL_RATE_DETAILS."` where id='".$_POST['data_id'][$data]."' ");
				
				if(num_rows($checkExistingRateDetailRoomWise) >0){
				

				
				$rate_id=addslashes(encryptor('decrypt',$_REQUEST['id']));
//echo "SELECT * FROM `".TBL_RATE_DETAILS."` where id!='".$_POST['data_id'][$data]."'  AND `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' ";



$checkExistingRateDetailRoomWise1 = executeSql("SELECT * FROM `".TBL_RATE_DETAILS."` where id!='".$_POST['data_id'][$data]."'  AND `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' and rate_id='".$rate_id."' ");

$people = $_REQUEST['detail_status'];
if (is_array($_REQUEST['detail_status']) ){
   
if (in_array($_POST['data_id'][$data], $people))
  {  
  $detail_status	=1;
  }
else
  {
  $detail_status	=0;
  }	
  
}

if(num_rows($checkExistingRateDetailRoomWise1) == 0){
				


			
								$editRateDetail = "UPDATE `".TBL_RATE_DETAILS."` SET 
													
													`extra_bed_price` = '".addslashes($_POST['extra_bed'][$data])."',													
													`single_pax_price` = '".addslashes($_POST['single_pax_price'][$data])."',
													`double_pax_price` = '".addslashes($_POST['double_pax_price'][$data])."',
													`hotel_remarks`='".addslashes($_POST['hotel_remarks'])."',
													`weekend_single_pax_price` = '".addslashes($_POST['weekend_single_pax_price'][$data])."',
													`weekend_double_pax_price` = '".addslashes($_POST['weekend_double_pax_price'][$data])."',													
													`rate_assign_id` = '".addslashes($rateAssignId)."',
													`room_id` = '".addslashes($_POST['room_type_id'][$data])."',
													`breakfast_price` = '".addslashes($_POST['breakfast_price'][$data])."',
													`lunch_price` = '".addslashes($_POST['lunch_price'][$data])."',													
													`dinner_price` = '".addslashes($_POST['dinner_price'][$data])."',
													`rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."',
													`tax_room` = '".addslashes($_POST['tax_room'][$data])."',							
													`start_date` = '".$_POST['start_date'][$data]."',
													`end_date` = '".$_POST['end_date'][$data]."',													
													`pkg_price` = '".addslashes($_POST['pkg_price'][$data])."'";
																										
						   		  $editRateDetail .= ",`detail_status` = '".$detail_status."'
													 WHERE `id` = '".$_POST['data_id'][$data]."' and 
													  
													`hotel_id` = '".addslashes($_POST['hotelId'])."'";	
											
								executeSql($editRateDetail);							
									}
							}else{
								
								$checkExistingRateDetailRoomWise1 = executeSql("SELECT * FROM `".TBL_RATE_DETAILS."` where `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' and rate_id='".$rate_id."' ");


if(num_rows($checkExistingRateDetailRoomWise1) == 0){
								
								
								$rate_id=addslashes(encryptor('decrypt',$_REQUEST['id']));
								$status = isset($_POST['status'][$data]) ? 1 : 0;
									
								$addRateDetail   = "INSERT INTO `".TBL_RATE_DETAILS."` SET 
													`rate_id` = '".$rate_id."',
													`rate_assign_id` = '".addslashes($rateAssignId)."',
													`room_id` = '".addslashes($_POST['room_type_id'][$data])."',
													`rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."',
													`hotel_id` = '".addslashes($_POST['hotelId'])."',	
													`rack_rate` = '".addslashes($_POST['rack_rate'][$data])."',
													`discount_type` = '".addslashes($_POST['discount_type'.$value])."'";
								if($_POST['discount_type'.$value]==2){
								 $addRateDetail .=	",`discount` = '".addslashes($_POST['discountPercent'.$value])."'";	
									}else{					
								 $addRateDetail .=	",`discount` = '".addslashes($_POST['discountFlat'.$value])."'";			
									}					
								 $addRateDetail .=	",`room_price` = '".addslashes($_POST['room_price'][$data])."',
													`extra_bed_price` = '".addslashes($_POST['extra_bed'][$data])."',
													`pkg_price` = '".addslashes($_POST['pkg_price'][$data])."',
													`inclusion_extra` = '".addslashes($_POST['inclusion_extra'])."',
													`hotel_remarks`='".addslashes($_POST['hotel_remarks'])."',
													`single_pax_price` = '".addslashes($_POST['single_pax_price'][$data])."',
													`double_pax_price` = '".addslashes($_POST['double_pax_price'][$data])."',
													`weekend_single_pax_price` = '".addslashes($_POST['weekend_single_pax_price'][$data])."',
													`weekend_double_pax_price` = '".addslashes($_POST['weekend_double_pax_price'][$data])."',													
													`breakfast_price` = '".addslashes($_POST['breakfast_price'][$data])."',
													`lunch_price` = '".addslashes($_POST['lunch_price'][$data])."',
													`dinner_price` = '".addslashes($_POST['dinner_price'][$data])."',
													`tax_room` = '".addslashes($_POST['tax_room'][$data])."',							
													`start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
													`end_date` = '".addslashes(date('Y-m-d',strtotime($_POST['end_date'])))."'";
																									
								  $addRateDetail .= "	,`detail_status` = '1'";	
													
										executeSql($addRateDetail);	
							
							
							
								}
							
							
							
								}
							}		
			/*			}else{	

						
																					
												
				}*/
				echo '<p class="help-block">'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").' rate has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "editRateMaster.php?id='.$_POST[id].'&hotelId='.addslashes(encryptor('encrypt',$_POST[hotelId])).'&action=edit&page=1";}, 2000);</script>';
				
				exit;
			/*}else{
				$err++;
				echo '<p class="help-block">'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").' rate has not been saved.Please make corrections.</p>';
			}*/
		}
	}else{//Error
		$err++;
		echo 'Rate details has not been saved. Please make corrections.';
	}
}

?>