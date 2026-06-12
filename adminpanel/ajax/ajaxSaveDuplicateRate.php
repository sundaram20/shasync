<?php include_once("../../config/auto_loader.php");



/*echo "<pre>";print_r($_REQUEST);echo "</pre>";

die;*/

		if(empty($_POST['seasonId']) && $_POST['seasonId']==""){

			echo '<font style="color:red;font-weight:normal;" ><br>Please select Season.</font>';

			exit;

		}

		if(empty($_POST['start_date'])){

			echo '<font style="color:red;font-weight:normal;" ><br>Please enter start date.</font>';

			exit;

		}

		if(empty($_POST['end_date'])){

			echo '<font style="color:red;font-weight:normal;" ><br>Please enter end date.</font>';

			exit;

		}

		if(empty($_POST['rate_level_id'])){

			echo '<font style="color:red;font-weight:normal;" ><br>Please select Rate Letter Master.</font>';

			exit;

		}

		if(empty($_POST['new_rate_level_id'])){

			echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';

			exit;

		}

		if(empty($_POST['id_contacts']) || $_POST['id_contacts']==0 ){

			echo '<font style="color:red;font-weight:normal;" ><br>Error : Contact Person can\'t be blank.</font>';

			exit;

		}

			





$_POST['hotel_remarks']=nl2br($_POST['hotel_remarks']);

$id_state	=	selectColumn(TBL_COMPANY,'id_state'," WHERE `id_company` = '".addslashes($_POST['company_id'])."'");

$start_date	=	$_REQUEST['start_date'];

$end_date	=	$_REQUEST['end_date'];



$DuphotelId = explode(",", $_REQUEST['DuphotelId']);





$DuplicateID	=	explode('|',$_REQUEST['rate_level_id']);



$dupId	=	$DuplicateID['0'];



$rate_category_id	=	$DuplicateID['0'];



$RateSQL = executeSql("SELECT * FROM `".TBL_RATE."` where id='".addslashes($dupId)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");



$RateRecordRow = $db->fetch_object2($RateSQL);



	  $addSql  = "  INSERT INTO `".TBL_CUSTOMER."` SET

				`id_default_group` = '1',

				`id_company` = '".addslashes($_POST['company_id'])."',

				`first_name` = '".addslashes($_POST['first_name'])."',

				`last_name` = '".addslashes($_POST['last_name'])."',

				`email` = '".addslashes($_POST['email'])."',

				`mobile` = '".addslashes($_POST['mobile'])."',

				`type` = '2'";

	  $addSql .= "	,`date_created` = '".currenDateTime()."'

				,`last_modified` = '".currenDateTime()."'

				,`last_modified_by` = '".$_SESSION['userId']."'

				,`status` = '1'";

		

	//executeSql($addSql);

		//$id_contacts= $db->insert_id(); 

		

		

		

 	 checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'add');



	 $start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");				

	 $end_date		=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");					

	 	

	 	$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['company_id'].'" ');



	 	//$chkSql = "SELECT * FROM `".TBL_RATE."` LEFT JOIN `".TBL_COMPANY."` ON `".TBL_RATE."`.company_id=`".TBL_COMPANY."`.id_company where company_id='".addslashes($_POST['company_id'])."' and rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and seasonId='".addslashes($_POST['seasonId'])."' and market='".addslashes($_POST['market'])."' AND `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.`status` = '1' AND `".TBL_COMPANY."`.name='".$companyName."' ";



	 	$seasonStartDate=selectColumn(TBL_RATE_SEASON,'start_date','WHERE id="'.$_POST['seasonId'].'" ');

	 	// season type :  1 for calendar year | 2 for summer | 3 for winter | 4 for custom  | 5 for financial year

		$sessonType =  selectColumn(TBL_RATE_SEASON,'season_type','WHERE id="'.$_POST['seasonId'].'" ');



	 	$seaCond='';



	 	if(date('01-01')==date('d-m',strtotime($seasonStartDate)) && $sessonType=='1'){

	 		$seaCond='OR DATE(a.start_date)="'.date('Y-04-01',strtotime($seasonStartDate)).'" OR DATE(a.start_date)="'.date('Y-10-01',strtotime($seasonStartDate)).'" ';
	 	}
	 	else if($sessonType!='4' && $sessonType!='5' && $sessonType!='1'){

	 		$seaCond='OR DATE(a.start_date)="'.date('Y-01-01',strtotime($seasonStartDate)).'" ';
	 	}
	 	else{
			$seaCond='';
		}

 $companyName = str_replace("'", "\'", $companyName);
	 	 $chkSql = "SELECT * FROM `".TBL_RATE."` AS a LEFT JOIN `".TBL_COMPANY."` ON a.company_id=`".TBL_COMPANY."`.id_company where  rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and (seasonId='".addslashes($_POST['seasonId'])."' ".$seaCond.") and market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."' AND a.status=1  AND a.`status` = '1' AND `".TBL_COMPANY."`.name='".$companyName."' ";

	 	

	 	$chkSqlUnit = "SELECT DISTINCT * FROM `".TBL_RATE_UNIT."` AS a 

	 					LEFT JOIN `".TBL_COMPANY."` ON a.company_id=`".TBL_COMPANY."`.id_company where  rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and (seasonId='".addslashes($_POST['seasonId'])."' ".$seaCond.") and market='".addslashes($_POST['market'])."' AND a.status=1 AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'   AND a.`status` = '1' AND `".TBL_COMPANY."`.name='".$companyName."' ";

	 		



		$checkExisting = executeSql($chkSql);



		$rateNoUnit = mysqli_query($connNew,$chkSqlUnit);

			$rateNameUnit = '';

			while($rowUnit = mysqli_fetch_object($rateNoUnit)){

				$hotel_id = selectColumn(TBL_RATE_DETAILS_UNIT,'hotel_id','WHERE rate_id="'.$rowUnit->id.'" ');

				$rateNameUnit.=$rowUnit->rate_name.'-'.selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$hotel_id.'" AND id_shop="'.$_SESSION['shop'].'" ').'<br>';

			}

			$rateNo = mysqli_fetch_object(mysqli_query($connNew,$chkSql));

	

			

if(num_rows($checkExisting)==0 && mysqli_num_rows($rateNoUnit)>0){	

$warning= '<p>!! Duplicate Found !!<br>

'.($rateNameUnit!=""?"-----Rate Letter By Unit -----<br>".$rateNameUnit."<br><button class='btn btn-primary' onclick='SaveDuplicateRate();'>Countinue</button>&nbsp; <button class='btn btn-danger' onclick='location.replace(\"manageRateLetters.php\");'>Cancel</button>":"").'

	</p>';	

	if($_REQUEST['chkFlag']!="0"){

		$chkFlag = 0;

		$warning='';

	}else{

		echo $warning;

		exit;

	}	

}

elseif(num_rows($checkExisting)> 0){


if($_REQUEST['HotelDuplicateInsert']==2){ //SELECTED HOTEL INSERT

while($rowRateID = mysqli_fetch_object($checkExisting)){
$rowRate_ID[]= $rowRateID->id;
}

$rowRate_ID	=	implode(',',$rowRate_ID);



$sqlHotelExistOr = mysqli_query($connNew,"SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id IN (".addslashes($rowRate_ID).") and hotel_id IN (".$_REQUEST['DuphotelId'].")");

if( mysqli_num_rows($sqlHotelExistOr)>0){
	$rowRateIDName = mysqli_fetch_object($sqlHotelExistOr);
	$RateNameTo = selectColumn(TBL_RATE,'rate_name','WHERE id="'.$rowRateIDName->rate_id.'" ');
$warning= '<p>!! Duplicate Found Exit !!<br>

	'.($rateNameUnit!=""?"-----Rate Letter By Unit -----<br>".$rateNameUnit:"").($rateNo->rate_name!=""?"<br>

	-----Rate Letter By Corporate -------<br>".$RateNameTo."<br><button class='btn btn-primary' onclick='location.replace(\"manageRateLetters.php\")'>Ok, Redirect me to rate letters lists</button>":"").'



		</p>';	

	$chkFlag = 2;
}

}else{

	$warning= '<p>!! Duplicate Found !!<br>

	'.($rateNameUnit!=""?"-----Rate Letter By Unit -----<br>".$rateNameUnit:"").($rateNo->rate_name!=""?"<br>

	-----Rate Letter By Corporate -------<br>".$rateNo->rate_name."<br><button class='btn btn-primary' onclick='location.replace(\"manageRateLetters.php\")'>Ok, Redirect me to rate letters lists</button>":"").'



		</p>';	

	$chkFlag = 2;

	
}


}





if($chkFlag==2){

	echo $warning;

		$warning='';

		exit;

				

}else{

	

				

			if($_REQUEST['HotelDuplicateInsert']==1){  //ALL HOTEL INSERT

				

//echo "ALL HOTEL INSERT";

				

		

			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				

			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");

			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE id=(SELECT MAX(id) FROM ".TBL_RATE." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ) AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

			$lastRecordRow = $db->fetch_object2($lastRecordRes);

			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));

			//echo $rateName 	= 'WH'.$newId;

			$mystring 	   = $lastRecordRow->maxId;			

			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			

			$newId 		   = sprintf("%'03d", ($newId+1));

			$rateName 	   = $GetShopShortCode.$newId;

			

$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");

			$resultCat 	= $db->fetch_object2($resCat);

		

				$ratePointDetail = json_encode($resultCat);

						$addRate = "    INSERT INTO `".TBL_RATE."` SET 

										`id_shop` = '".addslashes($_SESSION['shop'])."',

										`id_shop_group` = '1',

										`rate_name` = '".addslashes($rateName)."',

										`id_state` = '".addslashes($id_state)."',

										`sub_code` = '0',

										`company_id` = '".addslashes($_POST['company_id'])."',

										`id_contacts` = '".addslashes($_POST['id_contacts'])."',

										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',

										`rate_category_id` = '".addslashes($rate_category_id)."',

										`seasonId` = '".addslashes($_POST['seasonId'])."',							

										`remarks` = '".addslashes($_POST['remarks'])."',

										`market` = '".addslashes($_POST['market'])."',

										`generalterms` = '".addslashes($_POST['generalterms'])."',

										`rate_points` = '".addslashes($resultCat->rate_points)."',							

										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',

										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";

						$addRate .= "	,`date_created` = '".currenDateTime()."'

										,`created_by` = '".$_SESSION['userId']."'

										,`last_modified` = '".currenDateTime()."'

										,`last_modified_by` = '".$_SESSION['userId']."'

										,`status` = '1'";					

			executeSql($addRate);

			$rate_id= $db->insert_id();				

		

		

		

		

			executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");

			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");

			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");

			$rateAssignId = $db->insert_id();	

			

			

			executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");

			

			

			$resCat = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".$rate_id."'");

			

			

			while($resultCat = $db->fetch_object2($resCat)){

				

				

						executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,rate_assign_id='".$resultCat->id."' where rate_assign_id=(select id from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes($dupId)."' and hotel_id='".$resultCat->hotel_id."')");

					

					

					}

			

			

			

			

			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."'");

					

			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");

			

			executeSql("DROP TEMPORARY TABLE temp_rate_detail");

			executeSql("DROP TEMPORARY TABLE temp_rate_assign");

			

			//echo '<p class="help-block">All Hotel Rate details has been updated sucessfully.</p>';

			echo '<p class="help-block">All Hotel Rate details has been updated sucessfully<br>Redirecting Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 1);</script>';

			//header("location:manageRateLetters.php");

			}

			

			

	if($_REQUEST['HotelDuplicateInsert']==2){ //SELECTED HOTEL INSERT

				

			//echo "SELECTED HOTEL INSERT";		

			//die;	

			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				

			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");

			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE id=(SELECT MAX(id) FROM ".TBL_RATE." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ) AND  `id_shop` = '".addslashes($_SESSION['shop'])."'");

			$lastRecordRow = $db->fetch_object2($lastRecordRes);

			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));

			//echo $rateName 	= 'WH'.$newId;

			$mystring 	   = $lastRecordRow->maxId;			

			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			

			$newId 		   = sprintf("%'03d", ($newId+1));

			$rateName 	   = $GetShopShortCode.$newId;

			

$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");

			$resultCat 	= $db->fetch_object2($resCat);

		

				$ratePointDetail = json_encode($resultCat);

						 $addRate = "    INSERT INTO `".TBL_RATE."` SET 

										`id_shop` = '".addslashes($_SESSION['shop'])."',

										`id_shop_group` = '1',

										`rate_name` = '".addslashes($rateName)."',

										`sub_code` = '0',

										`company_id` = '".addslashes($_POST['company_id'])."',

										`id_state` = '".addslashes($id_state)."',

										`id_contacts` = '".addslashes($_POST['id_contacts'])."',

										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',

										`rate_category_id` = '".addslashes($rate_category_id)."',

										`seasonId` = '".addslashes($_POST['seasonId'])."',							

										`remarks` = '".addslashes($_POST['remarks'])."',

										`market` = '".addslashes($_POST['market'])."',

										`generalterms` = '".addslashes($_POST['generalterms'])."',

										

										`rate_points` = '".addslashes($resultCat->rate_points)."',							

										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',

										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";

						 $addRate .= "	,`date_created` = '".currenDateTime()."'

						 				,`created_by` = '".$_SESSION['userId']."'

										,`last_modified` = '".currenDateTime()."'

										,`last_modified_by` = '".$_SESSION['userId']."'

										,`status` = '1'";					

			executeSql($addRate);

			$rate_id= $db->insert_id();



			foreach($DuphotelId as $value){

					

						

			$value;

				

					executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");

			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");

			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");

			$rateAssignId = $db->insert_id();	

				

					

					

					

				executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");

			

			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,  rate_assign_id='".$rateAssignId."'");

					

			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");

		

			executeSql("DROP TEMPORARY TABLE temp_rate_detail");

			executeSql("DROP TEMPORARY TABLE temp_rate_assign");

				

				

				

				}	

			echo '<p class="help-block">Selected Hotel Rate details has been updated sucessfully.<br>Redirecting Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 1);</script>'; 

			}

			

			

			

			

			

			

			

		}

?>