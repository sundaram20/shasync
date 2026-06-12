<?php  include_once("../../config/auto_loader.php");

error_reporting(0);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//echo "<pre>";print_r($_REQUEST);echo "</pre>";

//die;





// condition put on 21/02/2019

if($_POST['new_rate_level_id']==""){

	$_POST['new_rate_level_id'] = $_POST['new_rate_level_id_change'];

}

else{

	$_POST['new_rate_level_id'] = $_POST['new_rate_level_id'];

}

///////////// end////////////////////



$id_state	=	selectColumn(TBL_COMPANY,'id_state'," WHERE `id_company` = '".addslashes($_POST['company_id'])."'");

				



$Splite_Master_type_id	=	explode('|',$_REQUEST['rate_level_id']);

$Array_count_roomeid=	sizeof($_REQUEST['room_type_id']);

for($i=0;$i<$Array_count_roomeid;$i++){



	$data_id[]	=	$_POST['room_type_id'][$i].'|'.$_POST['RatePlanID'][$i].'|0';

	}

$data_id;



$Array_count_roomeid	=	sizeof($_REQUEST['rate_level_id']);



$rate_level_id		=	array($_POST['rate_level_id']);

$rate_level_id 		=	array_pad($rate_level_id, $Array_count_roomeid, $_POST['rate_level_id']);



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

		if(empty($_POST['new_rate_level_id'])){

			$err++;

			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';

		}

		if($_POST['rate_level_id'] ==""){

			$err++;

			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate letter master.</font>';

		}

		if(empty($_POST['seasonId'])){

			$err++;

			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';

		}

		if(empty($_POST['id_contacts']) || $_POST['id_contacts']==0){

			$err++;

			echo '<font style="color:red;font-weight:normal;" ><br>Contact Person Can\'t be blank.</font>';

		}

				

	}	

	if(empty($data_id)){

			$err++;

			echo '<font style="color:red;font-weight:normal;" ><br>Please assign room to this hotel.<br></font>';

		}	

		
$_POST['hotel_remarks']=nl2br($_POST['hotel_remarks']);
		

	if($err == 0){//No error	

	$inclusionDetail = json_encode(array_combine($_POST['inclusion_id'],$_POST['inclusion_detail']));

	

		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add

			

			for($i=0;$i<$Array_count_roomeid;$i++){

			

			

			$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['company_id'].'" ');



			$seasonStartDate=selectColumn(TBL_RATE_SEASON,'start_date','WHERE id="'.$_POST['seasonId'].'" ');



			$seaCond='';



			if(date('01-01')==date('d-m',strtotime($seasonStartDate))){

				$seaCond='OR DATE(a.start_date)="'.date('Y-04-01',strtotime($seasonStartDate)).'" OR DATE(a.start_date)="'.date('Y-10-01',strtotime($seasonStartDate)).'" ';

				



			}

			else{

				$seaCond='OR DATE(a.start_date)="'.date('Y-01-01',strtotime($seasonStartDate)).'" ';

			}



			$chkSql = "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b LEFT JOIN 

			".TBL_COMPANY." c ON a.company_id=c.id_company  where UPPER(c.name)='".strtoupper($companyName)."' and a.rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and ( a.seasonId='".addslashes($_POST['seasonId'])."' ".$seaCond." )  and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id AND b.rate_plan_id='".addslashes($_POST['rate_plan_id'][$i])."'";



			$chkSqlUnit = "SELECT DISTINCT a.id,a.rate_name FROM `".TBL_RATE_UNIT."` as a join `".TBL_RATE_DETAILS_UNIT."` as b LEFT JOIN 

						".TBL_COMPANY." c ON a.company_id=c.id_company  where UPPER(c.name)='".strtoupper($companyName)."' and a.rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and (a.seasonId='".addslashes($_POST['seasonId'])."' ".$seaCond."  ) and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id AND b.hotel_id IN (".$_SESSION['hotel_access'].") AND b.rate_plan_id='".addslashes($_POST['rate_plan_id'][$i])."' AND  a.status=1 ";



			$rateNoUnit = mysqli_query($connNew,$chkSqlUnit);

						$rateNameUnit = '';



			while($rowUnit = mysqli_fetch_object($rateNoUnit)){

				$rateNameUnit.=$rowUnit->rate_name.'<br>';

			}

			

			$rateNo = mysqli_fetch_object(mysqli_query($connNew,$chkSql));

			$checkExisting = executeSql($chkSql);

			$chkFlag = 0;



			if(num_rows($checkExisting)>0 && mysqli_num_rows($rateNoUnit )==0){	

			/*<button class='btn btn-primary' onclick='submitRateLetterUnitForm();'>Countinue</button>*/

			$warning= '<p>!! Duplicate Found !!<br>

			'.($rateNo->rate_name!=""?"-----Rate Letter By Corporate -----<br>".$rateNo->rate_name."<br> &nbsp; <button class='btn btn-danger' onclick='location.replace(\"manageRateLettersUnit.php\");'>Cancel</button> ":"").'

				</p>';	

				if($_REQUEST['chkFlag']!="0"){

					$chkFlag = 2;

					//$warning='';

				}

				else{

					echo $warning;

					exit;

				}	

			}

			elseif(mysqli_num_rows($rateNoUnit )> 0){

				$warning='<p>!! Duplicate Found !!<br>

				'.($rateNo->rate_name!=""?"-----Rate Letter By Corporate -----<br>".$rateNo->rate_name:"").($rateNameUnit!=""?"<br>

				-----Rate Letter By Unit -------<br>".$rateNameUnit."<br><button class='btn btn-primary' onclick='location.replace(\"manageRateLettersUnit.php\")'>Ok, Redirect me to rate letters lists</button>":"").'



					</p>';	

				$chkFlag = 2;

				

			}

			

			if($chkFlag==2){

				echo $warning;

				$warning='';

				exit;			

			}

			else{

						

$checkRatePlanExisting = executeSql("SELECT * FROM `".TBL_RATE_UNIT."` as a where a.company_id='".addslashes($_POST['company_id'])."' and a.rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and a.seasonId='".addslashes($_POST['seasonId'])."' and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' AND status=1 ");

if(num_rows($checkRatePlanExisting)>0){

	$rowRatePlanExisting = $db->fetch_object2($checkRatePlanExisting);

	 $rate_id	=	$rowRatePlanExisting->id;

	

	

	

	}else{



			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".$_SESSION['shop']."'");

			

			

			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");

			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate_unit` WHERE id = (SELECT MAX(id) FROM ".TBL_RATE_UNIT." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ) AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

			$lastRecordRow = $db->fetch_object2($lastRecordRes);

			$mystring 	   = $lastRecordRow->maxId;			

			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			

			$newId 		   = sprintf("%'03d", ($newId+1));

			$rateName 	   = $GetShopShortCode.'U'.$newId;

			

			

								

				$start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

				$end_date	=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

				

						$addRate = "    INSERT INTO `".TBL_RATE_UNIT."` SET 

										`id_shop` = '".addslashes($_SESSION['shop'])."',

										`id_shop_group` = '1',

										`rate_name` = '".addslashes($rateName)."',

										`sub_code` = '0',

										`company_id` = '".addslashes($_POST['company_id'])."',

										`id_state` = '".addslashes($id_state)."',

										`rate_category_id` = '".addslashes($Splite_Master_type_id[0])."',

										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',										

										`seasonId` = '".addslashes($_POST['seasonId'])."',							

										`id_contacts` = '".addslashes($_POST['id_contacts'])."',							

										`remarks` = '".addslashes($_POST['remarks'])."',

										`market` = '".addslashes($_POST['market'])."',

										`rate_points` = '".addslashes(implode(',',$_POST['rate_points']))."',

										`generalterms` = '".addslashes($_POST['generalterms'])."',

										`additional_points` = '".addslashes($_POST['additional_points'])."',		

										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',

										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";

						$addRate .= "	,`date_created` = '".currenDateTime()."'

										,`created_by` = '".$_SESSION['userId']."'

										,`last_modified` = '".currenDateTime()."'

										,`last_modified_by` = '".$_SESSION['userId']."'

										,`allow_booking`='".addslashes($_POST['allow_booking'])."'

										,`status` = '1'";



						executeSql($addRate);

								$rate_id= $db->insert_id();								

								

						}

							

						$checkRateAssingExisting = executeSql("SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS_UNIT."` where rate_id='".addslashes(encryptor('decrypt',$_POST['id']))."' and hotel_id='".addslashes($_POST['hotelId'])."'");

				

			if(num_rows($checkRateAssingExisting)>0){

				$rowRateAssingExisting = $db->fetch_object2($checkRateAssingExisting);

				$rateAssignId		   = $rowRateAssingExisting->id;

				

				}else{	

						

						$addRateAssign = "  INSERT INTO `".TBL_RATE_ASSIGN_DETAILS_UNIT."` SET 

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

								

								$addRateDetail   = "INSERT INTO `".TBL_RATE_DETAILS_UNIT."` SET 

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

									

							

							echo '<p class="help-block">New Rate details has been added sucessfully.<br><br><span style="color:red;">Do you want to add/edit more Rates ? </span>

								<input type="hidden" id="rateInsertId" value="'.addslashes(encryptor('encrypt',$rate_id)).'"/>

							</p>

							';

							unset($_POST);

			/*	}else{

					$err++;

					echo '<p class="help-block">Rate details has not been saved. Please make corrections.</p>';

				

				}*/

			}

			

		}



}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update

	

		



//							`rate_category_id` = '".addslashes($Splite_Master_type_id[0])."',

//`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',						

			

			//checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_UNIT,'update');

			

			$lastRecordRes = executeSql("Select sub_code as maxId from `".TBL_RATE_UNIT."` where `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'");

			$lastRecordRow = $db->fetch_object2($lastRecordRes);			

			$subCode = sprintf("%'02d", ($lastRecordRow->maxId+1));

			

			if($_POST['revise_code']	==0){

				$subCode	=0;

				}

			

				

			$editRate = " UPDATE `".TBL_RATE_UNIT."` SET 

							`sub_code` = '".addslashes($_POST['sub_code'])."',	

							`market` = '".addslashes($_POST['market'])."',

							`id_contacts` = '".addslashes($_POST['id_contacts'])."',

							`id_state` = '".$id_state."',		

							`rate_points` = '".addslashes(implode(',',$_POST['rate_points']))."',

							`generalterms` = '".addslashes($_POST['generalterms'])."',	

							`additional_points` = '".addslashes($_POST['additional_points'])."',	

																		

							`remarks` = '".addslashes($_POST['remarks'])."'";

			$editRate .= "	,`last_modified` = '".currenDateTime()."'

			,`allow_booking`='".addslashes($_POST['allow_booking'])."'

							,`status` = '1'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";

					

			executeSql($editRate);	

			

			$chkSql2="SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS_UNIT."` where rate_id='".addslashes(encryptor('decrypt',$_POST['id']))."' and hotel_id='".addslashes($_POST['hotelId'])."'";



			

			$checkRateAssingExisting = executeSql($chkSql2);

				

			if(num_rows($checkRateAssingExisting)>0){

				$rowRateAssingExisting = $db->fetch_object2($checkRateAssingExisting);

				$rateAssignId		   = $rowRateAssingExisting->id;

				

			}else{	

						

				$addRateAssign = "  INSERT INTO `".TBL_RATE_ASSIGN_DETAILS_UNIT."` SET 

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

				/*debugData($_POST['data_id']);

				exit;*/

				$status = isset($_POST['status'][$data]) ? 1 : 0;

				$chkSql3="SELECT * FROM `".TBL_RATE_DETAILS_UNIT."` where id='".$_POST['data_id'][$data]."' ";

				

				$checkExistingRateDetailRoomWise = executeSql($chkSql3);

				

				if(num_rows($checkExistingRateDetailRoomWise) >0){

				

				$rate_id=addslashes(encryptor('decrypt',$_REQUEST['id']));

//echo "SELECT * FROM `".TBL_RATE_DETAILS."` where id!='".$_POST['data_id'][$data]."'  AND `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' ";





$chkSQL="SELECT * FROM `".TBL_RATE_DETAILS_UNIT."` where id!='".$_POST['data_id'][$data]."'  AND `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' and rate_id='".$rate_id."' ";



$checkExistingRateDetailRoomWise1 = executeSql($chkSQL);



$people = $_REQUEST['detail_status'];



if (in_array($_POST['data_id'][$data], $people))

  {  

  $detail_status	=1;

  }

else

  {

  $detail_status	=0;

  }	

if(num_rows($checkExistingRateDetailRoomWise1) == 0){

				

								

								$editRateDetail = "UPDATE `".TBL_RATE_DETAILS_UNIT."` SET 

													

													`extra_bed_price` = '".addslashes($_POST['extra_bed'][$data])."',													

													`single_pax_price` = '".addslashes($_POST['single_pax_price'][$data])."',

													`double_pax_price` = '".addslashes($_POST['double_pax_price'][$data])."',	

													`weekend_single_pax_price` = '".addslashes($_POST['weekend_single_pax_price'][$data])."',

													`weekend_double_pax_price` = '".addslashes($_POST['weekend_double_pax_price'][$data])."',												

													`rate_assign_id` = '".addslashes($rateAssignId)."',

													`details_sub_code` = '".addslashes($subCode)."',

													`room_id` = '".addslashes($_POST['room_type_id'][$data])."',

													`hotel_remarks`='".addslashes($_POST['hotel_remarks'])."',

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

								$rate_id=addslashes(encryptor('decrypt',$_REQUEST['id']));

								$chkSQL="SELECT * FROM `".TBL_RATE_DETAILS_UNIT."` where `room_id` = '".addslashes($_POST['room_type_id'][$data])."' AND  `rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."' and hotel_id='".addslashes($_POST['hotelId'])."' and rate_id='".$rate_id."' ";

								

								$checkExistingRateDetailRoomWise1 = executeSql($chkSQL);





	if(num_rows($checkExistingRateDetailRoomWise1) == 0){

									

									

									$rate_id=addslashes(encryptor('decrypt',$_REQUEST['id']));

									$status = isset($_POST['status'][$data]) ? 1 : 0;

										

									$addRateDetail   = "INSERT INTO `".TBL_RATE_DETAILS_UNIT."` SET 

														`rate_id` = '".$rate_id."',

														`details_sub_code` = '".addslashes($subCode)."',

														`rate_assign_id` = '".addslashes($rateAssignId)."',

														`room_id` = '".addslashes($_POST['room_type_id'][$data])."',

														`rate_plan_id` = '".addslashes($_POST['rate_plan_id'][$data])."',

														`hotel_id` = '".addslashes($_POST['hotelId'])."',	

														`rack_rate` = '".addslashes($_POST['rack_rate'][$data])."',

														`hotel_remarks`='".addslashes($_POST['hotel_remarks'])."',

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

			if($_REQUEST['sub_code']== 0){

			

			$HOtelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'");

				 	   

	   $seasonId=selectColumn(TBL_RATE_UNIT,'seasonId'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'");

	   $company_id=selectColumn(TBL_RATE_UNIT,'company_id'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'");

	   $revise_code=$_REQUEST['revise_code'];

	   $sub_code=$_REQUEST['sub_code'];

	   

	   

	   echo '<p class="help-block">'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").' rate has been updated sucessfully.<br><br><span style="color:red;">Do you want to add/edit more Rates ? </span><input type="hidden" id="rateInsertId" value="'.addslashes(encryptor('encrypt',$rate_id)).'"/></p>

	   			';

  echo $msg;

			

			

			

			

		}else{

				

				

				 $HOtelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'");

				 $msg	=	'<p class="help-block">'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").' rate has been updated sucessfully.'; 

    $msg	.='<form id="FeedBackpopupForm" data-parsley-validate autocomplete="off">';

         $msg	.='<div class="form-group">';

         $msg	.='<label for="room_name">You want to Send Revise Rate Letter From <span style="color:red;">'.$HOtelName.'</span></label>';

      

       $msg	.='</div>';

	   

	   $seasonId=selectColumn(TBL_RATE_UNIT,'seasonId'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'");

	   $company_id=selectColumn(TBL_RATE_UNIT,'company_id'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'");

	   $revise_code=$_REQUEST['revise_code'];

	   $sub_code=$_REQUEST['sub_code'];

	   ?>

         

         

         

    <?php  $msg	.='<a onClick="ReviseRateLetterAutoMail('.$_REQUEST['hotelId'].','.$seasonId.','.$company_id.','.$revise_code.','.$sub_code.');" href="javascript://" title="Download" style="float:left; margin-left:5px;" title="Edit"   class="btn btn-default">Yes</a> </form>';

  $msg	.='</p>';

  echo $msg;

		}	

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