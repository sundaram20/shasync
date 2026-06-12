<?php
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
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
	$cond = ' AND id="'.$_SESSION['userId'].'" ';
}

//echo $_SESSION['teamNewMembers'];
 /*if($_POST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$_POST['id_team'];
		}*/
		
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
	//	 echo $sqlExe;
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){

	
		
		
		$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
		LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowExe->id.'" ');

	//QTY Room Nights
	//Budget value month_value 
	
	if($assignedCompany >0){


		if($rowExe->user_type!=2){
			$rateTable = TBL_RATE;
			$budgetTable = TBL_AGENT_BUDGET;
			$achievedTable = TBL_AGENT_ACHIEVED;
		}
		else{
			$rateTable = TBL_RATE_UNIT;
			$budgetTable = TBL_UNIT_AGENT_BUDGET;
			$achievedTable = TBL_UNIT_AGENT_ACHIEVED;
		}
		
			$budgetTable = TBL_BUDGET_MASTER;
			$achievedTable = TBL_BUDGET_MASTER;
			
			
			
		array_push($userIdArray, $rowExe->id);	
			
//echo $achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ";
		//$achieved = selectColumn($achievedTable,'sum(month_value)'," WHERE month='".date('Y-m-01',strtotime($from))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$prevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

		$totalExpenseMtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');
		

		

		$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

	
	
	
	
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
				SUM(CASE WHEN month between '".$prevYear."'  and '".$prevYearEnd."' THEN qty  ELSE 0 END) AS budgetRoomNightsPrveYEAR,
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN qty  ELSE 0 END) AS budgetRoomNightsCurrentYEAR,				
				SUM(CASE WHEN month='".$CYMONTH."' THEN qty  ELSE 0 END) AS budgetRoomNightsThisMonth,
				
				
				SUM(CASE WHEN month between '".$date."'  and '".$end."' THEN month_value  ELSE 0 END) AS budgetValueCurrentYEAR,				
				SUM(CASE WHEN month='".$CYMONTH."' THEN month_value  ELSE 0 END) AS budgetValueThisMonth
				
				FROM ".TBL_BUDGET_MASTER."
				WHERE 
				id_user='".$rowExe->id."'   AND type='1'
				GROUP BY id_user order by qty desc";
						

	$resBudgetSQL 	= mysqli_query($connNew,$budgetSQL);
 	$numberOfRow	 = mysqli_num_rows($resBudgetSQL);
	$rowBudgetSQL 	= mysqli_fetch_object($resBudgetSQL);

	$budgetRoomNights=$rowBudgetSQL->budgetRoomNightsCurrentYEAR;
	$budgetRoomNightsThisMonth=$rowBudgetSQL->budgetRoomNightsThisMonth;
	
	$budgetValueCurrentYEAR =$rowBudgetSQL->budgetValueCurrentYEAR;
	$budgetValueThisMonth	=$rowBudgetSQL->budgetValueThisMonth;
	
	
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
				
				
				
				FROM ".TBL_BUDGET_MASTER."
				WHERE 
				id_user='".$rowExe->id."'   AND type='2'
				GROUP BY id_user order by qty desc";
				
	$resAchievedSQL = mysqli_query($connNew,$achievedSQL);
	$rowAchievedSQL = mysqli_fetch_object($resAchievedSQL);
	
	$mtdPrevYear	=$rowAchievedSQL->achievedRoomNightsPrveYEARMonth;
	$mtdThisMonth	=$rowAchievedSQL->achievedRoomNightsThisMonth;
		
	$yearToDayPrevYear =$rowAchievedSQL->achievedRoomNightsPrveYEAR;		
	$achieved 		  =$rowAchievedSQL->achieved;	
	

	$achievedValueYEARMonth	=$rowAchievedSQL->achievedValueYEARMonth;
	$achievedValueThisMonth	=$rowAchievedSQL->achievedValueThisMonth;
		
	$achievedValuePrveYEAR 	=$rowAchievedSQL->achievedValuePrveYEAR;		
	$achievedValueCurrentYear =$rowAchievedSQL->achievedValueCurrentYear;
	
	
	if(date('m',strtotime($from))<=3){
			//echo $budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'   ";
			$reportPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));

			 $budget = selectColumn($budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'   ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-2 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-2 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			

		}
		else{

			$reportPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($from));

			$budget = selectColumn($budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND  month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'    ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime($from))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			

		
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

	
			
	}	
}


$userIdArray;



$userIdArray=implode(',',$userIdArray);
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
				GROUP BY id_hotel order by qty desc
				)as hotelbudget 
				
				";
			//where  (budgetHotelRoomNightsPrveYEAR>0 OR budgetHotelRoomNightsCurrentYEAR>0 OR budgetHotelRoomNightsThisMonth>0 OR budgetHotelValuePrveYEAR>0 OR budgetHotelValueThisMonth>0)
		
//where  (budgetHotelRoomNightsPrveYEAR>0 OR budgetHotelRoomNightsCurrentYEAR>0 OR budgetHotelRoomNightsThisMonth>0 OR budgetHotelValuePrveYEAR>0 OR budgetHotelValueThisMonth>0)
				
	$resHotelWiseBudget 			    = mysqli_query($connNew,$hotelWiseBudgetSQL);
 	$numberOfRowreshotelWiseBudget	 = mysqli_num_rows($resHotelWiseBudget);
	$hotelNameValue=array();
	
	while($rowHotelWiseBudget				= mysqli_fetch_object($resHotelWiseBudget)){
	
	//if($rowHotelWiseBudget->budgetHotelRoomNightsThisMonth>0 || $rowHotelWiseBudget->budgetHotelRoomNightsCurrentYEAR){
	$budgetHotelRoomNights				= $rowHotelWiseBudget->budgetHotelRoomNightsCurrentYEAR;
	$budgetHotelRoomNightsThisMonth	   =$rowHotelWiseBudget->budgetHotelRoomNightsThisMonth;
	
	$hotelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rowHotelWiseBudget->id_hotel."'");
	
	
	$budgetHotelValueCurrentYEAR	   =$rowHotelWiseBudget->budgetHotelValueCurrentYEAR;
	$budgetHotelValueThisMonth	     =$rowHotelWiseBudget->budgetHotelValueThisMonth;
	
	array_push($budgetHotelValueCurrentYEARValue, ($budgetHotelValueCurrentYEAR==''?0:$budgetHotelValueCurrentYEAR));
	array_push($budgetHotelValueThisMonthValue, ($budgetHotelValueThisMonth==''?0:$budgetHotelValueThisMonth));
	
	
	array_push($hotelNameValue, ($hotelName==''?0:$hotelName));
	array_push($budgetHotelRoomNightsValues, ($budgetHotelRoomNights==''?0:$budgetHotelRoomNights));
	array_push($budgetHotelRoomNightsThisMonthValues, ($budgetHotelRoomNightsThisMonth==''?0:$budgetHotelRoomNightsThisMonth));
	
	//}
	
	}
	//}
	
	
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
				GROUP BY id_hotel order by qty desc
				)as hotelbudget 
				
				";
				//where  (achievedHotelRoomNightsPrveYEAR>0 OR
				//achievedHotel>0 OR achievedHotelRoomNightsPrveYEARMonth>0 OR achievedHotelRoomNightsPrveYEARMonth>0 OR achievedHotelRoomNightsThisMonth>0)
				
	$resAchievedHotel 		= mysqli_query($connNew,$hotelWiseAchievedSQL);
	
	while($rowAchievedHotel 		= mysqli_fetch_object($resAchievedHotel)){
	//if($rowAchievedHotel->achievedHotelRoomNightsPrveYEAR>0 || $rowAchievedHotel->achievedHotelRoomNightsThisMonth  || $rowAchievedHotel->achievedHotelRoomNightsPrveYEARMonth){
	
	
		$yearToDayHotelPrevYear 			   =$rowAchievedHotel->achievedHotelRoomNightsPrveYEAR;
		$achievedHotel 						=$rowAchievedHotel->achievedHotel;	
		$mtdHotelPrevYear					 =$rowAchievedHotel->achievedHotelRoomNightsPrveYEARMonth;
		$mtdHotelThisMonth					=$rowAchievedHotel->achievedHotelRoomNightsThisMonth;
		
		array_push($yearToDayHotelPrevYearValues, ($yearToDayHotelPrevYear==''?0:$yearToDayHotelPrevYear));		
		array_push($achievedHotelValues, ($achievedHotel==''?0:$achievedHotel));
		array_push($mtdHotelPrevYearValues, ($mtdHotelPrevYear==''?0:$mtdHotelPrevYear));
		array_push($mtdHotelThisMonthValues, ($mtdHotelThisMonth==''?0:$mtdHotelThisMonth));
		
		
		$achievedHotelValuePrveYEAR 			   =$rowAchievedHotel->achievedHotelValuePrveYEAR;
		$achievedHotelValue 						=$rowAchievedHotel->achievedHotelValue;	
		$achievedHotelValuePrveYEARMonth					 =$rowAchievedHotel->achievedHotelValuePrveYEARMonth;
		$achievedHotelValueThisMonth					=$rowAchievedHotel->achievedHotelValueThisMonth;
		
		array_push($achievedHotelValuePrveYEARValues, ($achievedHotelValuePrveYEAR==''?0:$achievedHotelValuePrveYEAR));		
		array_push($achievedHotelValueValues, ($achievedHotelValue==''?0:$achievedHotelValue));
		array_push($achievedHotelValuePrveYEARMonthValues, ($achievedHotelValuePrveYEARMonth==''?0:$achievedHotelValuePrveYEARMonth));
		array_push($achievedHotelValueThisMonthValues, ($achievedHotelValueThisMonth==''?0:$achievedHotelValueThisMonth));
		
	//}
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
if(date('m',strtotime($from))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($from)));
	$lastDate = date('Y-m-d',strtotime($from));
}
else{
	$startDate =date('Y-04-01',strtotime($from));
	$lastDate = date('Y-m-d',strtotime($from));
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
$startDate=date('Y-m-01',strtotime($from));

$days=1;
$weekends=1;
// MTD
while($startDate <= $from){

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
$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;
$returnData['totalDaysGoneYtd']=$totalDaysGoneYtd;

$returnData['stacked']=$stackedArr;

$returnData['mtdThisVal']=$mtdThisValues;
$returnData['mtdLastVal']=$mtdLastValues;

$returnData['mtdThisMonthVal']=$mtdThisMonthValues;


$returnData['yearToDayLastVal']=$yearToDayLastValues;


$returnData['budgetRoomNightsValues']=$budgetRoomNightsValues;
$returnData['budgetRoomNightsThisMonthVal']=$budgetRoomNightsThisMonthValues;
$returnData['budgetVal']=$budgetValues;

$returnData['executives']=$exeNameArr;

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
	
		



$returnData['reportPeriod']=$reportPeriod;
$returnData['reportPeriodMonth']=$reportPeriodMonth;
$returnData['datePeriod']=$datePeriod;



echo json_encode($returnData);



