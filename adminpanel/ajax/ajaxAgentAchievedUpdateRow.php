<?php  include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*echo "<pre>";print_r($_REQUEST);echo "</pre>";*/

$value		 =	$_REQUEST['value'];
$EditId		=	$_REQUEST['EditId'];
$id_company	=	$_REQUEST['id_company'];
$seasonId	  =	$_REQUEST['seasonId'];
$userId	    =	$_REQUEST['userId'];
$DateValue	 =	date('Y-m-d', $_REQUEST['DateValue']);

if($id_company!='' && $seasonId!='' && $userId!=''){
	
$EditId	=selectColumn(TBL_AGENT_ACHIEVED,'id',"WHERE `id_company`='".addslashes($id_company)."' AND  seasonId = '".addslashes($seasonId)."' and  `id_user` = '".addslashes($userId)."' and  `month` = '".addslashes($DateValue)."'  ");

if($EditId>0){
			$editAchieved = " UPDATE `".TBL_AGENT_ACHIEVED."` SET 						
						`qty` = '".addslashes($value)."'";
			$editAchieved .= "
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$EditId."'";	
			  $editAchieved;
			executeSql($editAchieved);	
}else{
	

	$dateStart  =	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	$dateEnd    =	selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	$month 	  = 	strtotime($dateStart);
			

	while (strtotime($dateStart) <= strtotime($dateEnd)) {
                //echo "$dateStart\n";
                if($dateStart==$DateValue){
						$recordvalue = $value;
					}else{
						$recordvalue = 0;
						}
				$addAchieved = "INSERT INTO `".TBL_AGENT_ACHIEVED."` SET 
									`id_shop` = '".addslashes($_SESSION['shop'])."',
									`id_company` = '".addslashes($id_company)."',
									`id_user` = '".addslashes($userId)."',
									`qty` = '".addslashes($recordvalue)."',
									`seasonId`	= '".addslashes($seasonId)."',
									`month` = '".$dateStart."'																
								";
				$addAchieved .= ",`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`last_modified_by` = '".$_SESSION['userId']."'
								,`status` = '1'";								
				executeSql($addAchieved);
			    $dateStart = date ("Y-m-d", strtotime("+1 month", strtotime($dateStart)));
	}
					

					

	
	 				
	}



    $userTypeTable	= TBL_AGENT_ACHIEVED;			
	$SqlConn		  = "and C.`user_id`='".$_REQUEST['userId']."'";
	$start_date  =	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	$end_date    =	selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
	
	
	$sqlTotalVer = "SELECT sum(qty)AS qty FROM `".$userTypeTable."` as a
	LEFT JOIN ".TBL_COMPANY." B ON a.id_company=B.id_company
	LEFT JOIN ".TBL_AREAS." C ON B.area=C.id
	where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and FIND_IN_SET('".$_REQUEST['userId']."',C.ids_unit_user)  and a.`seasonId`='".$_REQUEST['seasonId']."' and month='".$DateValue."'  $SqlConn ";
	$resVer= mysqli_query($connNew,$sqlTotalVer);
	$objTot = mysqli_fetch_object($resVer);
	
			
			
$DateValueMonth	 =	date('m', $_REQUEST['DateValue']);
 $rowTotal=	selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)',"WHERE `id_company`='".addslashes($id_company)."' AND  seasonId = '".addslashes($seasonId)."' and  `id_user` = '".addslashes($userId)."'");

	echo '<p class="help-block">Achievement has been updated sucessfully.</p>####'.$rowTotal.'####'.$objTot->qty;	
}else{
	echo 'Achievement has not been saved. Please make corrections.####';
	}

?>