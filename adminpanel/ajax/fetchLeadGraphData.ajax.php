<?php
include_once("../../config/auto_loader.php");

$period = $_REQUEST['period'];
$from = '';
$to='';
if(date('m',strtotime($period))<=3){
	$from = date('Y-04-01',strtotime('-1 years',strtotime($period)));
	$to = date('Y-m-d',strtotime($period));
}
else{
	$from = date('Y-04-01',strtotime($period));
	$to = date('Y-m-d',strtotime($period));
}

$returnData = array();
$membersArr = array();
$reasonsArr = array();
$reasonsValArr = array();
$reasonValRec =  array();
$revenueGen = 0;
$bgColor = array();
$enquiryIdArr = array();
$enquiryIdRecArr = array();


if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
//	$cond = ' AND id="'.$_SESSION['userId'].'" ';
}

$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($_POST['id_team']==0){
		//echo 'All';
		
		/*$teamIds = "SELECT id FROM ".TBL_TEAM." WHERE id_shop=".$_SESSION['shop']." ";
		$resTeamIds =  mysqli_query($connNew,$teamIds);

		$teamIdsArray=array();

		while($rowTeamIds=mysqli_fetch_object($resTeamIds)){
			array_push($teamIdsArray,$rowTeamIds->id);
		}

		$teamId=implode(',',$teamIdsArray);*/
		if($_SESSION['userLevel']==1){ //Super ADMIN
					$teamIds = "SELECT id FROM ".TBL_TEAM." WHERE id_shop=".$_SESSION['shop']." ";
					$resTeamIds =  mysqli_query($connNew,$teamIds);
					
					$teamIdsArray=array();
					
					while($rowTeamIds=mysqli_fetch_object($resTeamIds)){
					array_push($teamIdsArray,$rowTeamIds->id);
					}
					
					$id_teams=implode(',',$teamIdsArray);
		}else{
		$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'" ');
		}
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		
		$allUser= "ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$_POST['id_team'].", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$_POST['id_team']."'   AND id_shop= '".$_SESSION['shop']."'";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser ="  id IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	
		$cond = '  id="'.$_SESSION['userId'].'" ';
		
	}
//========================================================================================================	

//SELECT id,name,user_type FROM fs_users WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('', ',', '|'), ')(,|$)') AND id IN ()  order by name

            $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ".$cond." ".$allUser." order by name";
//$sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE FIND_IN_SET('".$_POST['id_team']."',ids_team) AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";

$resExe = mysqli_query($connNew,$sqlExe);

while($rowMembers = mysqli_fetch_object($resExe)){
	
	$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
		LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowMembers->id.'" ');
	
	if($assignedCompany>0)
		array_push($membersArr,$rowMembers->id);
}


$sqlMain = "SELECT  DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A WHERE A.`id_shop` = '".$_SESSION['shop']."' AND A.id_user IN (".implode(',',$membersArr).") AND A.created_date BETWEEN '".$from."' AND '".$to."' ";
$resMain = mysqli_query($connNew,$sqlMain);

while($rowMain = mysqli_fetch_object($resMain)){
	array_push($enquiryIdArr,$rowMain->id);
}


$totalLeadsGen = mysqli_num_rows($resMain);

if($_SESSION['unit_user']==2){
	$unitCond = " AND A.hotel_id IN (".$_SESSION['hotel_access'].") ";
}
else{
	$unitCond = "AND A.assign_user_id IN (".implode(',',$membersArr).")";
}

$sqlMainRec = "SELECT  DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A WHERE A.`id_shop` = '".$_SESSION['shop']."' ".$unitCond." AND A.created_date BETWEEN '".$from."' AND '".$to."' ";

$resMainRec = mysqli_query($connNew,$sqlMainRec);

while($rowMainRec = mysqli_fetch_object($resMainRec)){
	array_push($enquiryIdRecArr,$rowMainRec->id);
}

$totalLeadsRec = mysqli_num_rows($resMainRec);


$sqlOpen = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE FIND_IN_SET(enquiry_id,'".implode(',',$enquiryIdArr)."') AND lead_status=1 GROUP BY enquiry_id";

$totalOpenLeads = 	mysqli_num_rows(mysqli_query($connNew,$sqlOpen));

$sqlOpenRec = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE FIND_IN_SET(enquiry_id,'".implode(',',$enquiryIdRecArr)."') AND lead_status=1  GROUP BY enquiry_id";

$totalOpenRecLeads = mysqli_num_rows(mysqli_query($connNew,$sqlOpenRec));

$revenueGen = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'sum(revenue)','WHERE FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdArr).'")  ');

$revenueRec = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'sum(revenue)','WHERE FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdRecArr).'")  ');

$sqlReasons = "SELECT id,name FROM ".TBL_CLOSING_MASTER." WHERE id_shop='".$_SESSION['shop']."' ";

$resReasons = mysqli_query($connNew, $sqlReasons);

array_push($reasonsArr,'Open');
array_push($reasonsValArr,($totalOpenLeads==''?0:$totalOpenLeads));
array_push($reasonValRec,($totalOpenRecLeads==''?0:$totalOpenRecLeads));
while($rowReasons = mysqli_fetch_object($resReasons)){

	$sqlOpen = 'SELECT * FROM '.TBL_DAILY_ENQUERY_DETAILS.' where FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdArr).'")   AND followup_close_type_id="'.$rowReasons->id.'" GROUP BY enquiry_id';

	$val = mysqli_num_rows(mysqli_query($connNew,$sqlOpen));

	$sqlOpenRec = 'SELECT * FROM '.TBL_DAILY_ENQUERY_DETAILS.' where FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdRecArr).'")   AND followup_close_type_id="'.$rowReasons->id.'" GROUP BY enquiry_id';
	$valRec =  mysqli_num_rows(mysqli_query($connNew,$sqlOpenRec));

	array_push($reasonsArr, $rowReasons->name);
	array_push($reasonsValArr,($val==''?0:$val));
	array_push($reasonValRec,($valRec==''?0:$valRec));

}
array_push($bgColor,'rgb(255, 205, 86)');
array_push($bgColor,'rgb(255, 99, 132)');
array_push($bgColor,'rgb(54, 162, 235)');
array_push($bgColor,'rgb(64, 192, 235)');
array_push($bgColor,'rgb(74, 182, 245)');
array_push($bgColor,'rgb(84, 172, 255)');
array_push($bgColor,'rgb(94, 152, 265)');
array_push($bgColor,'rgb(34, 142, 275)');
array_push($bgColor,'rgb(24, 132, 285)');
array_push($bgColor,'rgb(14, 122, 295)');


$returnData['totalLeadsGen'] = $totalLeadsGen;
$returnData['totalOpenLeads'] = ($totalOpenLeads==''?0:$totalOpenLeads);
$returnData['reasons'] = $reasonsArr;
$returnData['reasonval'] = $reasonsValArr;
$returnData['bgColor'] = $bgColor;
$returnData['revenueGen'] = ($revenueGen==''?0:$revenueGen);
$returnData['revenueRec'] = ($revenueRec==''?0:$revenueRec);
$returnData['totalLeadsRec']=$totalLeadsRec;
$returnData['totalOpenRecLeads']=$totalOpenRecLeads;
$returnData['reasonValRec']=$reasonValRec;
$returnData['exeIdArr']=$membersArr;
echo json_encode($returnData);


