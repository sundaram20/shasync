<?php 
/*echo '{"totalDaysGoneMtd":10,"totalDaysGoneYtd":248,
"stacked":[{"label":"Executive45","backgroundColor":"rgba(86, 4, 78,0.7)","data":[0]},
{"label":"Executive46","backgroundColor":"rgba(195, 10, 78,0.7)","data":[0]},{"label":"Executive49","backgroundColor":"rgba(21, 40, 100,0.7)","data":[0]},{"label":"Executive63","backgroundColor":"rgba(83, 7, 2,0.7)","data":[0]},{"label":"Executive87","backgroundColor":"rgba(222, 24, 117,0.7)","data":[0]}],
"mtdThisVal":[0,0,0,0,0],


"yearToDayLastVal":[3658,3296,4404,3724,3428],
"budgetRoomNightsValues":[0,0,0,0,0],
"budgetVal":[0,0,0,0,0],

"ytdLastVal":["6.1999999433755875","7.732200015336275","8.259999990463257","6.3699999414384365","5.819999944418669"],
"ytdThisVal":[0,0,0,0,0],
"mtdVisits":[0,0,0,0,0],
"mtdRateLetters":["0","0","0","0","0"],
"mtdTotalExpense":[0,0,0,0,0],
"ytdVisits":[0,0,0,0,0],
"ytdRateLetters":["0","0","0","0","0"],
"ytdTotalExpense":[0,0,0,0,0],
"achievedRoomNightsThisMonth":[0,0,0,0,0],

"hotelNameVal":["Demo Hotel 64","Demo Hotel 63","Demo Hotel 62","Demo Hotel 59","Demo Hotel 61","Demo Hotel 60","Demo Hotel 58","Demo Hotel 57","Demo Hotel 56","Demo Hotel 55","Demo Hotel 54","Demo Hotel 53","Demo Hotel 52","Demo Hotel 51","Demo Hotel 50","Demo Hotel 49","Demo Hotel 48","Demo Hotel 44","Demo Hotel 43","Demo Hotel 47","Demo Hotel 45","Demo Hotel 42","Demo Hotel 41","Demo Hotel 40","Demo Hotel 39","Demo Hotel 38","Demo Hotel 37","Demo Hotel 36","Demo Hotel 35","Demo Hotel 34","Demo Hotel 32","Demo Hotel 31","Demo Hotel 30","Demo Hotel 29","Demo Hotel 28","Demo Hotel 27","Demo Hotel 26","Demo Hotel 25","Demo Hotel 24","Demo Hotel 23","Demo Hotel 22","Demo Hotel 21","Demo Hotel 20","Demo Hotel 19","Demo Hotel 18","Demo Hotel 17","Demo Hotel 16","Demo Hotel 15","Demo Hotel 14","Demo Hotel 13","Demo Hotel 12","Demo Hotel 10","Demo Hotel 9","Demo Hotel 8","Demo Hotel 6","Demo Hotel 5","Demo Hotel 4","Demo Hotel 3","Demo Hotel 2"],
"yearToDayHotelPrevYearVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,19,0,0,0,0,0,0,0,0,0,0,0,0,0,148,447,594,735,800,766,690,740,736,755,779,761,764,750,733,711,787,772,730,800,729,747,786,730,0,0,0,0,0,751,750],
"budgetHotelRoomNightsVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"achievedHotelVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"mtdHotelPrevYearVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,11,0,0,0,0,0,0,0,0,0,0,0,0,0,9,42,45,41,56,55,43,43,46,55,54,61,54,54,51,53,53,47,47,56,51,53,54,55,0,0,0,0,0,100,47],
"budgetHotelRoomNightsThisMonthVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"mtdHotelThisMonthVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"achievedHotelValuePrveYEARVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.56,0,0,0,0,0,0,0,0,0,0,0,0,0,3.54,11.33,12.27,15.64,16.76,16.38,14.73,16.58,15.82,15.47,17.75,17.18,17.43,16.39,15.01,18.58,17.76,16.04,29.77,17.75,14.69,17.32,16.82,28.23,0,0,0,0,0,15.45,15.66],
"achievedHotelValueVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"achievedHotelValuePrveYEARMonthVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0.3,0,0,0,0,0,0,0,0,0,0,0,0,0,0.25,0.95,0.91,0.88,1.05,1.1,0.88,0.87,1.35,1.14,1.06,1.37,1.01,0.98,1.05,1.61,1.23,1.05,1.13,1.06,1.04,1.22,1.13,1.27,0,0,0,0,0,0.79,1.01],
"achievedHotelValueThisMonthVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"budgetHotelValueCurrentYEARVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"budgetHotelValueThisMonthVal":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
"budgetValueCurrentYEARVal":[0,0,0,0,0],"budgetValueThisMonthVal":[0,0,0,0,0],
"achievedValueYEARMonthVal":[5.54,6.55,5.52,4.86,5.22],"achievedValueThisMonthVal":[0,0,0,0,0],"achievedValuePrveYEARVal":[80.86,86.04,91.81,86.73,85.47],
"achievedValueCurrentYearVal":[0,0,0,0,0],"reportPeriod":"01-04-2020 To 12-03-2021","reportPeriodMonth":"March 2021","datePeriod":"01-04-2020 to 01-04-2020",

"mtdLastVal":[254,262,322,240,258], 
"executives":["Executive45","Executive46","Executive49","Executive63","Executive87"],  
"mtdThisMonthVal":[0,0,0,0,0], 
"budgetRoomNightsThisMonthVal":[0,0,0,0,0]
}';*/


/*echo '{"totalDaysGoneMtd":10,"totalDaysGoneYtd":248,
"stacked":[{"label":"Atanu Banerjee","backgroundColor":"rgba(230, 12, 22,0.7)","data":[0]},{"label":"Mithu Mukherjee","backgroundColor":"rgba(49, 51, 149,0.7)","data":[0]}],"mtdThisVal":[0,0],



"yearToDayLastVal":[0,0],
"budgetRoomNightsValues":[0,0],"budgetVal":[0,0],
"ytdLastVal":[0,0],"ytdThisVal":[0,0],
"mtdVisits":[54,83],"mtdRateLetters":["1","0"],
"mtdTotalExpense":[0,1570],
"ytdVisits":[1130,1588],"ytdRateLetters":["323","71"],
"ytdTotalExpense":[0,1570],
"achievedRoomNightsThisMonth":[0,0],

"hotelNameVal":["Demo Hotel 64","Demo Hotel 63"],
"yearToDayHotelPrevYearVal":[13,10],
"budgetHotelRoomNightsVal":[13,10],
"achievedHotelVal":[13,10],
"mtdHotelPrevYearVal":[13,10],
"budgetHotelRoomNightsThisMonthVal":[13,10],
"mtdHotelThisMonthVal":[13,10],
"achievedHotelValuePrveYEARVal":[13,10],
"achievedHotelValueVal":[13,10],
"achievedHotelValuePrveYEARMonthVal":[0,0],
"achievedHotelValueThisMonthVal":[0,0],
"budgetHotelValueCurrentYEARVal":[0,0],
"budgetHotelValueThisMonthVal":[0,0],
"budgetValueCurrentYEARVal":[0,0],
"budgetValueThisMonthVal":[0,0]
"achievedValueYEARMonthVal":[0,0],
"achievedValueThisMonthVal":[0,0],
"achievedValuePrveYEARVal":[0,0],
"achievedValueCurrentYearVal":[0,0],
"reportPeriod":"01-04-2020 To 12-03-2021","reportPeriodMonth":"March 2021","datePeriod":"01-04-2020 to 01-04-2020",

"mtdLastVal":[10,60],
"executives":["Atanu Banerjee","Mithu Mukherjee"],  
"mtdThisMonthVal":[60,30],
"budgetRoomNightsThisMonthVal":[13,10]
}';*/

/*echo '{"totalDaysGoneMtd":11,"totalDaysGoneYtd":249,
"stacked":[{"label":"Atanu Banerjee","backgroundColor":"rgba(151, 7, 142,0.7)","data":[0]},{"label":"Mithu Mukherjee","backgroundColor":"rgba(145, 47, 115,0.7)","data":[0]}],
"mtdThisVal":[0,0],

"mtdLastVal":[0,0],"executives":["Atanu Banerjee","Mithu Mukherjee"],"mtdThisMonthVal":[0,0],

"budgetRoomNightsThisMonthVal":[0,0],

"yearToDayLastVal":[0,0],
"budgetRoomNightsValues":[0,0],"budgetVal":[0,0],"ytdLastVal":[0,0],"ytdThisVal":[0,0],"mtdVisits":[60,83],
"mtdRateLetters":["1","4"],
"mtdTotalExpense":[1016,1570],
"ytdVisits":[1136,1588],"ytdRateLetters":["323","71"],
"ytdTotalExpense":[1016,1570],
"achievedRoomNightsThisMonth":[0,0],
"hotelNameVal":[],
"yearToDayHotelPrevYearVal":[0],
"budgetHotelRoomNightsVal":[0],"achievedHotelVal":[0],"mtdHotelPrevYearVal":[0],
"budgetHotelRoomNightsThisMonthVal":[0],
"mtdHotelThisMonthVal":[0],
"achievedHotelValuePrveYEARVal":[0],
"achievedHotelValueVal":[0],
"achievedHotelValuePrveYEARMonthVal":[0],
"achievedHotelValueThisMonthVal":[0],
"budgetHotelValueCurrentYEARVal":[0],
"budgetHotelValueThisMonthVal":[0],
"budgetValueCurrentYEARVal":[0,0],"budgetValueThisMonthVal":[0,0],
"achievedValueYEARMonthVal":[0,0],
"achievedValueThisMonthVal":[0,0],"achievedValuePrveYEARVal":[0,0],
"achievedValueCurrentYearVal":[0,0],
"reportPeriod":"01-04-2020 To 15-03-2021","reportPeriodMonth":"March 2021","datePeriod":"01-04-2020 to 01-04-2020"}';*/
//die;

include_once("../../config/auto_loader.php");
//error_reporting(E_ALL);
$from = date('Y-m-d',strtotime($_POST['period']));
//print_r($_SESSION);

$mtdLastValues = array();
$mtdThisMonthValues= array();
$yearToDayLastValues= array();
$budgetRoomNightsThisMonthValues= array();
$mtdThisValues = array();

$mtdVisits = array();
$mtdRateLetters = array();
$mtdTotalExpense = array();

$budgetValues = array();


$ytdLastValues = array();
$ytdThisValues = array();

$ytdVisits = array();
$ytdRateLetters = array();
$ytdTotalExpense = array();

$exeNameArr = array();
$returnData = array();

$stackedArr = array();
$stackedDataSet = array();
$budgetRoomNightsValues=array();
$achievedRoomNightsThisMonthValue=array();

$yearToDayHotelPrevYearValues=array();
$budgetHotelRoomNightsValues=array();
$achievedHotelValues=array();

$mtdHotelPrevYearValues=array();
$budgetHotelRoomNightsThisMonthValues=array();
$mtdHotelThisMonthValues=array();

$achievedHotelValuePrveYEARValues=array();
$achievedHotelValueValues=array();
$achievedHotelValuePrveYEARMonthValues=array();
$achievedHotelValueThisMonthValues=array();
$budgetHotelValueCurrentYEARValue=array();
$budgetHotelValueThisMonthValue=array();

$budgetValueCurrentYEARValues=array();
$budgetValueThisMonthValues=array();
$achievedValueYEARMonthValues=array();
$achievedValueThisMonthValues=array();
$achievedValuePrveYEARValues=array();
$achievedValueCurrentYearValues=array();
$hotelNameValue=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';


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

$MonthFrom=date('Y-m-01',strtotime($period));
$UserInActive	=	"  AND ( ".TBL_USERS.".status_inactive_date>='".$to."' ||  ".TBL_USERS.".status_inactive_date='0000-00-00') ";


if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
	//$cond = ' AND id="'.$_SESSION['userId'].'" ';
	$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');
	if($team_data_access_approved=='1'){
		$cond = '';
		}else{
			$cond = ' AND id="'.$_SESSION['userId'].'" ';
			}
}

//echo $_SESSION['teamNewMembers'];
 if($_POST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	$chartGrapType ='horizontalBar';
	}else{
		$id_teams=$_POST['id_team'];
		$chartGrapType ='bar';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


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
		$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"  ');// ".$UserInActive." 
		}
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		
		$allUser= " AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
	    
	    
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$_POST['id_team'].", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$_POST['id_team']."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser =" AND id IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if($team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    $cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
	}
//========================================================================================================	

//SELECT id,name,user_type FROM fs_users WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('', ',', '|'), ')(,|$)') AND id IN ()  order by name
//echo $cond;
            $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE id!='' ".$cond." ".$allUser." order by name";
		 //echo $sqlExe;
		
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){

	
		
		
		$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
		LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowExe->id.'" ');

	//QTY Room Nights
	//Budget value month_value 
	
	//if($assignedCompany >0){


		if($rowExe->user_type!=2){
			$rateTable = TBL_RATE;
			$budgetTable = TBL_BUDGET_MASTER;//TBL_AGENT_BUDGET;
			$achievedTable = TBL_AGENT_ACHIEVED;
		}
		else{
			$rateTable = TBL_RATE_UNIT;
			$budgetTable = TBL_UNIT_AGENT_BUDGET;
			$achievedTable = TBL_UNIT_AGENT_ACHIEVED;
		}
		
		//	$budgetTable = TBL_BUDGET_MASTER;
		//	$achievedTable = TBL_BUDGET_MASTER;
			
			
			
		array_push($userIdArray, $rowExe->id);	
		
	//	echo TBL_DAILYVISIT.'count(id)'.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
			
//echo $achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ";
		//$achieved = selectColumn($achievedTable,'sum(month_value)'," WHERE month='".date('Y-m-01',strtotime($from))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$prevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');
		
		//$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');


		$totalExpenseMtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');
		

		
//echo ' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
		$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime('+1 days',strtotime($to))).'" ');

	
	//echo TBL_DAILYVISIT.'count(id)'.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
	
	
	 $day	=	date('d',strtotime($_POST['period']));
	 $month	=	date('m',strtotime($_POST['period']));
	 $Year	=	date('Y',strtotime($_POST['period']));
	
	
if (date($month) > 6) {
    $yearStart = date($Year);
	$yearEnd	=(date($Year) +1);
}
else {
    $yearStart = (date($Year)-1);
	$yearEnd=date($Year);
}

	$reportPeriodMonth= date('F',strtotime($_POST['period'])).' '.$Year;
	$LYMONTH	=	date('Y-'.$month.'-01',strtotime('-1 years',strtotime($from)));
	$CYMONTH	=	date('Y-m-01',strtotime($from));
	$LYPEROD	= 	date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));
	$CYPERIOD   =	date('01-04-Y',strtotime('01-04-'.$yearStart)).' to '.date('d-m-Y',strtotime($yearEnd));
	
	
	 $date = date("".$yearStart."-04-01");
     $end =  date("".$Year."-".$month."-".$day);	
	
	
		$prevYear = strtotime($date);
		$prevYear = strtotime("-1 year",$prevYear);
		$prevYear = date('Y-m-d',$prevYear);
		$prevYearEnd=strtotime("-1 year",strtotime($end));
		$prevYearEnd = date('Y-m-d',$prevYearEnd);
		
		$prevYear2 = strtotime($date);
		$prevYear2 = strtotime("-2 year",$prevYear2);
		$prevYear2 = date('Y-m-d',$prevYear2);
		$prevYear2End = strtotime("-2 year",strtotime($end));
		$prevYear2End = date('Y-m-d',$prevYear2End);
	
   $budgetSQL= "SELECT 
				SUM(CASE WHEN DATE(`from`) between '".$prevYear."'  and '".$prevYearEnd."' THEN room_nights  ELSE 0 END) AS budgetRoomNightsPrveYEAR,
				SUM(CASE WHEN DATE(`from`) between '".$date."'  and '".$end."' THEN room_nights  ELSE 0 END) AS budgetRoomNightsCurrentYEAR,				
				SUM(CASE WHEN DATE(`from`)='".$CYMONTH."' THEN room_nights  ELSE 0 END) AS budgetRoomNightsThisMonth,
				
				
				SUM(CASE WHEN DATE(`from`) between '".$date."'  and '".$end."' THEN value  ELSE 0 END) AS budgetValueCurrentYEAR,				
				SUM(CASE WHEN DATE(`from`)='".$CYMONTH."' THEN value  ELSE 0 END) AS budgetValueThisMonth
				
				FROM ".TBL_AGENT_BUDGET."
				WHERE 
				id_user='".$rowExe->id."'  
				GROUP BY id_user order by room_nights desc";
//	echo $budgetSQL;					
//die;
	$resBudgetSQL 	= mysqli_query($connNew,$budgetSQL);
 	$numberOfRow	 = mysqli_num_rows($resBudgetSQL);
	$rowBudgetSQL 	= mysqli_fetch_object($resBudgetSQL);

	$budgetRoomNights=  round($rowBudgetSQL->budgetRoomNightsCurrentYEAR,2);
	$budgetRoomNightsThisMonth= round($rowBudgetSQL->budgetRoomNightsThisMonth,2);
	
	$budgetValueCurrentYEAR =   round($rowBudgetSQL->budgetValueCurrentYEAR/100000,2);
	$budgetValueThisMonth	=round($rowBudgetSQL->budgetValueThisMonth/100000,2);
	
	
      $achievedSQL = "SELECT 
  				
				SUM(CASE WHEN month between '".$prevYear2."'  and '".$prevYear2End."' THEN qty  ELSE 0 END) AS achievedRoomNightsPrveYEAR2,
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN qty  ELSE 0 END) AS achievedRoomNightsPrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN qty  ELSE 0 END) AS achieved,
				SUM(CASE WHEN month='".$LYMONTH."' THEN qty  ELSE 0 END) AS achievedRoomNightsPrveYEARMonth,
				SUM(CASE WHEN month='".$CYMONTH."' THEN qty  ELSE 0 END) AS achievedRoomNightsThisMonth,
				
				
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN month_value  ELSE 0 END) AS achievedValuePrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN month_value  ELSE 0 END) AS achievedValueCurrentYear,
				SUM(CASE WHEN month='".$LYMONTH."' THEN month_value  ELSE 0 END) AS achievedValueYEARMonth,
				SUM(CASE WHEN month='".$CYMONTH."' THEN month_value  ELSE 0 END) AS achievedValueThisMonth
				
				
				
				FROM ".$achievedTable."
				WHERE 
				id_user='".$rowExe->id."'  
				GROUP BY id_user order by qty desc";
			//	echo $achievedSQL; die;
				
				
	$resAchievedSQL = mysqli_query($connNew,$achievedSQL);
	$rowAchievedSQL = mysqli_fetch_object($resAchievedSQL);
	
	$mtdPrevYear	=round($rowAchievedSQL->achievedRoomNightsPrveYEARMonth,2);
	$mtdThisMonth	=round($rowAchievedSQL->achievedRoomNightsThisMonth,2);
		
	$yearToDayPrevYear =round($rowAchievedSQL->achievedRoomNightsPrveYEAR,2);		
	$achieved 		  =round($rowAchievedSQL->achieved,2);
	

	$achievedValueYEARMonth	=round($rowAchievedSQL->achievedValueYEARMonth/100000,2);
	$achievedValueThisMonth	=round($rowAchievedSQL->achievedValueThisMonth/100000,2);
		
	$achievedValuePrveYEAR 	=round($rowAchievedSQL->achievedValuePrveYEAR/100000,2);		
	$achievedValueCurrentYear =round($rowAchievedSQL->achievedValueCurrentYear/100000,2);
	
	
	if(date('m',strtotime($from))<=3){
			//echo $budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'   ";
			$reportDisplayPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($to));
			
			$reportPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));

			 $budget = selectColumn($budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'   ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-2 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-2 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			//echo TBL_DAILYVISIT.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ';

		}
		else{
            $reportDisplayPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($to));
			$reportPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($from));

			//$budget = selectColumn($budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND  month between '".date('Y-04-01',strtotime($from))."' and '".date('Y-03-31',strtotime('+1 years',strtotime($to)))."'    ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime($from))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			//$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ');

$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');

			$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ');

			$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ');

	//echo $budgetTable.'sum(qty)'." WHERE `id_user` = '".$rowExe->id."' AND  month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'    ";		
//echo TBL_DAILYVISIT.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-03-31',strtotime('+1 years',strtotime($to))).'" ';
	//	echo TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
		}

		
		 $stackedDataSet['label']=$rowExe->name;
		
		$stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
		//$stackedDataSet['borderColor']='rgba('.rand(0,255).', '.rand(0,255).', '.rand(0,255).',1)';
		$stackedDataSet['data'][0]=($budget==''?0:$budget);

		array_push($stackedArr,$stackedDataSet);


		array_push($exeNameArr,ucwords(strtolower($rowExe->name)));
		array_push($mtdLastValues, ($mtdPrevYear==''?0:$mtdPrevYear));
		
		
		array_push($mtdThisMonthValues, ($mtdThisMonth==''?0:$mtdThisMonth));
				
		
		array_push($yearToDayLastValues, ($yearToDayPrevYear==''?0:$yearToDayPrevYear));
		
		array_push($mtdThisValues, ($achieved==''?0:$achieved));

		array_push($budgetValues, ($budget==''?0:$budget));
		

		array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
		array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

		array_push($mtdVisits,$visitMtd);
		array_push($mtdRateLetters,$rateLetterMtd);

		array_push($ytdVisits,$visitYtd);
		array_push($ytdRateLetters,$rateLetterYtd);

		array_push($mtdTotalExpense, $totalExpenseMtd);
		array_push($ytdTotalExpense, $totalExpenseYtd);
		
		array_push($budgetRoomNightsValues, ($budgetRoomNights==''?0:$budgetRoomNights));
		array_push($achievedRoomNightsThisMonthValue, ($achievedRoomNightsThisMonth==''?0:$achievedRoomNightsThisMonth));
		array_push($budgetRoomNightsThisMonthValues, ($budgetRoomNightsThisMonth==''?0:$budgetRoomNightsThisMonth));
		
		//array_push($yearToDayHotelPrevYearValues, ($yearToDayHotelPrevYear==''?0:$yearToDayHotelPrevYear));
		//array_push($budgetHotelRoomNightsValues, ($budgetHotelRoomNights==''?0:$budgetHotelRoomNights));
		//array_push($achievedHotelValues, ($achievedHotel==''?0:$achievedHotel));
		
		//array_push($mtdHotelPrevYearValues, ($mtdHotelPrevYear==''?0:$mtdHotelPrevYear));
		//array_push($budgetHotelRoomNightsThisMonthValues, ($budgetHotelRoomNightsThisMonth==''?0:$budgetHotelRoomNightsThisMonth));
		//array_push($mtdHotelThisMonthValues, ($mtdHotelThisMonth==''?0:$mtdHotelThisMonth));
		
		array_push($budgetValueCurrentYEARValues, ($budgetValueCurrentYEAR==''?0:$budgetValueCurrentYEAR));
		array_push($budgetValueThisMonthValues, ($budgetValueThisMonth==''?0:$budgetValueThisMonth));
		
		array_push($achievedValueYEARMonthValues, ($achievedValueYEARMonth==''?0:$achievedValueYEARMonth));
		array_push($achievedValueThisMonthValues, ($achievedValueThisMonth==''?0:$achievedValueThisMonth));
		array_push($achievedValuePrveYEARValues, ($achievedValuePrveYEAR==''?0:$achievedValuePrveYEAR));
		array_push($achievedValueCurrentYearValues, ($achievedValueCurrentYear==''?0:$achievedValueCurrentYear));

	
			
	//}	 
}
 

$userIdArray;



$userIdArray=implode(',',$userIdArray);
$budgetTable = TBL_BUDGET_MASTER;
$achievedTable = TBL_BUDGET_MASTER;
//==========================HOTEL WISE SQL==START====================================================================
	
	
	  $hotelWiseBudgetSQL= "
	select * from (
				SELECT id_hotel,
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN qty  ELSE 0 END) AS budgetHotelRoomNightsPrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN qty  ELSE 0 END) AS budgetHotelRoomNightsCurrentYEAR,				
				SUM(CASE WHEN month='".$CYMONTH."' THEN qty  ELSE 0 END) AS budgetHotelRoomNightsThisMonth,
				
				
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN month_value  ELSE 0 END) AS budgetHotelValuePrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN month_value  ELSE 0 END) AS budgetHotelValueCurrentYEAR,
				SUM(CASE WHEN month='".$CYMONTH."' THEN month_value  ELSE 0 END) AS budgetHotelValueThisMonth
				FROM ".TBL_BUDGET_MASTER."
				WHERE 
				FIND_IN_SET(id_user,'".$userIdArray."')  AND type='1'
				GROUP BY id_hotel order by id_hotel desc
				)as hotelbudget 
				
				";
			//where  (budgetHotelRoomNightsPrveYEAR>0 OR budgetHotelRoomNightsCurrentYEAR>0 OR budgetHotelRoomNightsThisMonth>0 OR budgetHotelValuePrveYEAR>0 OR budgetHotelValueThisMonth>0)
		
//where  (budgetHotelRoomNightsPrveYEAR>0 OR budgetHotelRoomNightsCurrentYEAR>0 OR budgetHotelRoomNightsThisMonth>0 OR budgetHotelValuePrveYEAR>0 OR budgetHotelValueThisMonth>0)
				
	$resHotelWiseBudget 			    = mysqli_query($connNew,$hotelWiseBudgetSQL);
 	$numberOfRowreshotelWiseBudget	 = mysqli_num_rows($resHotelWiseBudget);
	
	
	while($rowHotelWiseBudget				= mysqli_fetch_object($resHotelWiseBudget)){
	
	//if($rowHotelWiseBudget->budgetHotelRoomNightsThisMonth>0 || $rowHotelWiseBudget->budgetHotelRoomNightsCurrentYEAR){
	$budgetHotelRoomNights				= round($rowHotelWiseBudget->budgetHotelRoomNightsCurrentYEAR,2);
	$budgetHotelRoomNightsThisMonth	   =round($rowHotelWiseBudget->budgetHotelRoomNightsThisMonth,2);
	
	$hotelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rowHotelWiseBudget->id_hotel."'");
	
	
	$budgetHotelValueCurrentYEAR	   =round($rowHotelWiseBudget->budgetHotelValueCurrentYEAR,2);
	$budgetHotelValueThisMonth	     =round($rowHotelWiseBudget->budgetHotelValueThisMonth,2);
	
	array_push($budgetHotelValueCurrentYEARValue, ($budgetHotelValueCurrentYEAR==''?0:$budgetHotelValueCurrentYEAR));
	array_push($budgetHotelValueThisMonthValue, ($budgetHotelValueThisMonth==''?0:$budgetHotelValueThisMonth));
	
	
	array_push($hotelNameValue, ($hotelName==''?0:$hotelName));
	array_push($budgetHotelRoomNightsValues, ($budgetHotelRoomNights==''?0:$budgetHotelRoomNights));
	array_push($budgetHotelRoomNightsThisMonthValues, ($budgetHotelRoomNightsThisMonth==''?0:$budgetHotelRoomNightsThisMonth));
	
	//}
	
	}
	//}
	if(empty($budgetHotelValueCurrentYEARValue)) {
	    array_push($budgetHotelValueCurrentYEARValue,0);
	    }
	  	if(empty($budgetHotelValueThisMonthValue)) {
	    array_push($budgetHotelValueThisMonthValue,0);
	    }  
	   	if(empty($budgetHotelRoomNightsValues)) {
	    array_push($budgetHotelRoomNightsValues,0);
	    }
	  	if(empty($budgetHotelRoomNightsThisMonthValues)) {
	    array_push($budgetHotelRoomNightsThisMonthValues,0);
	    }   
	
	 $hotelWiseAchievedSQL = "
	select * from (
				SELECT id_hotel,				
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN qty  ELSE 0 END) AS achievedHotelRoomNightsPrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN qty  ELSE 0 END) AS achievedHotel,
				SUM(CASE WHEN month='".$LYMONTH."' THEN qty  ELSE 0 END) AS achievedHotelRoomNightsPrveYEARMonth,
				SUM(CASE WHEN month='".$CYMONTH."' THEN qty  ELSE 0 END) AS achievedHotelRoomNightsThisMonth,
				
				
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN month_value  ELSE 0 END) AS achievedHotelValuePrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN month_value  ELSE 0 END) AS achievedHotelValue,
				SUM(CASE WHEN month='".$LYMONTH."' THEN month_value  ELSE 0 END) AS achievedHotelValuePrveYEARMonth,
				SUM(CASE WHEN month='".$CYMONTH."' THEN month_value  ELSE 0 END) AS achievedHotelValueThisMonth
				
				
				FROM ".TBL_BUDGET_MASTER."
				WHERE 
				FIND_IN_SET(id_user,'".$userIdArray."')   AND type='2'
				GROUP BY id_hotel order by id_hotel desc
				)as hotelbudget 
				
				";
				//where  (achievedHotelRoomNightsPrveYEAR>0 OR
				//achievedHotel>0 OR achievedHotelRoomNightsPrveYEARMonth>0 OR achievedHotelRoomNightsPrveYEARMonth>0 OR achievedHotelRoomNightsThisMonth>0)
				
	$resAchievedHotel 		= mysqli_query($connNew,$hotelWiseAchievedSQL);
	
	while($rowAchievedHotel 		= mysqli_fetch_object($resAchievedHotel)){
	//if($rowAchievedHotel->achievedHotelRoomNightsPrveYEAR>0 || $rowAchievedHotel->achievedHotelRoomNightsThisMonth  || $rowAchievedHotel->achievedHotelRoomNightsPrveYEARMonth){
	
	
		$yearToDayHotelPrevYear 			   =round($rowAchievedHotel->achievedHotelRoomNightsPrveYEAR,2);
		$achievedHotel 						=round($rowAchievedHotel->achievedHotel,2);	
		$mtdHotelPrevYear					 =round($rowAchievedHotel->achievedHotelRoomNightsPrveYEARMonth,2);
		$mtdHotelThisMonth					=round($rowAchievedHotel->achievedHotelRoomNightsThisMonth,2);
		
		array_push($yearToDayHotelPrevYearValues, ($yearToDayHotelPrevYear==''?0:$yearToDayHotelPrevYear));		
		array_push($achievedHotelValues, ($achievedHotel==''?0:$achievedHotel));
		array_push($mtdHotelPrevYearValues, ($mtdHotelPrevYear==''?0:$mtdHotelPrevYear));
		array_push($mtdHotelThisMonthValues, ($mtdHotelThisMonth==''?0:$mtdHotelThisMonth));
		
		
		$achievedHotelValuePrveYEAR 			   =round($rowAchievedHotel->achievedHotelValuePrveYEAR,2);
		$achievedHotelValue 						=round($rowAchievedHotel->achievedHotelValue,2);	
		$achievedHotelValuePrveYEARMonth					 =round($rowAchievedHotel->achievedHotelValuePrveYEARMonth,2);
		$achievedHotelValueThisMonth					=round($rowAchievedHotel->achievedHotelValueThisMonth,2);
		
		array_push($achievedHotelValuePrveYEARValues, ($achievedHotelValuePrveYEAR==''?0:$achievedHotelValuePrveYEAR));		
		array_push($achievedHotelValueValues, ($achievedHotelValue==''?0:$achievedHotelValue));
		array_push($achievedHotelValuePrveYEARMonthValues, ($achievedHotelValuePrveYEARMonth==''?0:$achievedHotelValuePrveYEARMonth));
		array_push($achievedHotelValueThisMonthValues, ($achievedHotelValueThisMonth==''?0:$achievedHotelValueThisMonth));
		
	//}
	}
		if(empty($yearToDayHotelPrevYearValues)) {
	    array_push($yearToDayHotelPrevYearValues,0);
	    }
	    
	    	if(empty($achievedHotelValues)) {
	    array_push($achievedHotelValues,0);
	    }
	    
	    	if(empty($mtdHotelPrevYearValues)) {
	    array_push($mtdHotelPrevYearValues,0);
	    }
	    
	    	if(empty($mtdHotelThisMonthValues)) {
	    array_push($mtdHotelThisMonthValues,0);
	    }
	    
	    
	    	if(empty($achievedHotelValuePrveYEARValues)) {
	    array_push($achievedHotelValuePrveYEARValues,0);
	    }
	    
	    	if(empty($achievedHotelValueValues)) {
	    array_push($achievedHotelValueValues,0);
	    }
	    
	    	if(empty($achievedHotelValuePrveYEARMonthValues)) {
	    array_push($achievedHotelValuePrveYEARMonthValues,0);
	    }
	    
	    	if(empty($achievedHotelValueThisMonthValues)) {
	    array_push($achievedHotelValueThisMonthValues,0);
	    }
	
	/*$yearToDayHotelPrevYear 			   =$rowAchievedHotel->achievedHotelRoomNightsPrveYEAR;	
	$achievedHotel 						=$rowAchievedHotel->achievedHotel;
	$budgetHotelRoomNights				=$rowHotelWiseBudget->budgetHotelRoomNightsCurrentYEAR;
	
	$mtdHotelPrevYear					 =$rowAchievedHotel->achievedHotelRoomNightsPrveYEARMonth;
	$budgetHotelRoomNightsThisMonth	   =$rowHotelWiseBudget->budgetHotelRoomNightsThisMonth;
	$mtdHotelThisMonth					=$rowAchievedHotel->achievedHotelRoomNightsThisMonth;*/
	
	
	//==========================HOTEL WISE SQL END======================================================================
	
	
/***** Total Gone Days Calculatiing Days ****/
$days=1;
$weekends=1;

$totalDaysGoneMtd=1;
$totalDaysGoneYtd=1;

//YTD
if(date('m',strtotime($period))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($period)));
	$lastDate = date('Y-m-d',strtotime($period));
}
else{
	$startDate =date('Y-04-01',strtotime($period));
	$lastDate = date('Y-m-d',strtotime($period));
}

while($startDate <= $lastDate){

	$day = date("N",strtotime($startDate));
	if($day == 6 || $day == 7) {
	  $weekends++;
	} 

	$days++;
	$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
}
$totalDaysGoneYtd = $days-$weekends;


$startDate=date('Y-m-01',strtotime($period));


$days=1;
$weekends=1;
// MTD
while($startDate <= $to){

	$day = date("N",strtotime($startDate));
	if($day == 6 || $day == 7) {
	  $weekends++;
	} 

	$days++;
	$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
}
$totalDaysGoneMtd = $days-$weekends;

/**************** END ***********************/ 
//print_r($exeNameArr);
//print_r($hotelNameValue);

$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;


$returnData['totalDaysGoneYtd']=$totalDaysGoneYtd;

$returnData['stacked']=$stackedArr;
print_r($returnDat);
$returnData['mtdThisVal']=$mtdThisValues;

$returnData['mtdLastVal']=$mtdLastValues;
$returnData['executives']=$exeNameArr;
$returnData['mtdThisMonthVal']=$mtdThisMonthValues;
$returnData['budgetRoomNightsThisMonthVal']=$budgetRoomNightsThisMonthValues;

$returnData['yearToDayLastVal']=$yearToDayLastValues;


$returnData['budgetRoomNightsValues']=$budgetRoomNightsValues;

$returnData['budgetVal']=$budgetValues;



$returnData['ytdLastVal']=$ytdLastValues;
$returnData['ytdThisVal']=$ytdThisValues;


$returnData['mtdVisits']=$mtdVisits;
$returnData['mtdRateLetters']=$mtdRateLetters;
$returnData['mtdTotalExpense']=$mtdTotalExpense;

$returnData['ytdVisits']=$ytdVisits;
$returnData['ytdRateLetters']=$ytdRateLetters;
$returnData['ytdTotalExpense']=$ytdTotalExpense;

$returnData['achievedRoomNightsThisMonth']=$achievedRoomNightsThisMonthValue;

 $returnData['hotelNameVal']=$hotelNameValue;

$returnData['yearToDayHotelPrevYearVal']=$yearToDayHotelPrevYearValues;
$returnData['budgetHotelRoomNightsVal']=$budgetHotelRoomNightsValues;
$returnData['achievedHotelVal']=$achievedHotelValues;

$returnData['mtdHotelPrevYearVal']=$mtdHotelPrevYearValues;
$returnData['budgetHotelRoomNightsThisMonthVal']=$budgetHotelRoomNightsThisMonthValues;
$returnData['mtdHotelThisMonthVal']=$mtdHotelThisMonthValues;






$returnData['achievedHotelValuePrveYEARVal']=		$achievedHotelValuePrveYEARValues;
$returnData['achievedHotelValueVal']=		$achievedHotelValueValues;
$returnData['achievedHotelValuePrveYEARMonthVal']=		$achievedHotelValuePrveYEARMonthValues;
$returnData['achievedHotelValueThisMonthVal']=		$achievedHotelValueThisMonthValues;
$returnData['budgetHotelValueCurrentYEARVal']=		$budgetHotelValueCurrentYEARValue;
$returnData['budgetHotelValueThisMonthVal']=	$budgetHotelValueThisMonthValue;
	
	
	
$returnData['budgetValueCurrentYEARVal']=$budgetValueCurrentYEARValues;
$returnData['budgetValueThisMonthVal']=	$budgetValueThisMonthValues;
$returnData['achievedValueYEARMonthVal']=	$achievedValueYEARMonthValues;
$returnData['achievedValueThisMonthVal']=	$achievedValueThisMonthValues;	
$returnData['achievedValuePrveYEARVal']=	$achievedValuePrveYEARValues;
$returnData['achievedValueCurrentYearVal']=	$achievedValueCurrentYearValues;

$returnData['chartGrapType']=	$chartGrapType;
	
		



 $returnData['reportPeriod']=$reportDisplayPeriod;
$returnData['reportPeriodMonth']=$reportPeriodMonth;
$returnData['datePeriod']=$datePeriod;


echo json_encode($returnData);



