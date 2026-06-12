<?php
include_once("../../config/auto_loader.php");
error_reporting(E_ALL);
$from = date('Y-m-d',strtotime($_POST['period']));
//print_r($_SESSION);

$mtdLastValues = array();
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

$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
	$cond = ' AND id="'.$_SESSION['userId'].'" ';
}

//echo $_SESSION['teamNewMembers'];
 if($_POST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$_POST['id_team'];
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";

//$sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." where id_shop=6 ".$cond." order by name";

$resExe = mysqli_query($connNew,$sqlExe);

while($rowExe = mysqli_fetch_object($resExe)){

	
		
		$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
		LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowExe->id.'" ');

	
	
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

		$achieved =selectColumn($achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime($from))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$prevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

		$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

		$totalExpenseMtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');
		

		

		$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

		
		if(date('m',strtotime($from))<=3){

			$reportPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));

			$budget = selectColumn($budgetTable,'sum(room_nights)'," WHERE `id_user` = '".$rowExe->id."' AND `from`='".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' AND `to`='".date('Y-03-31',strtotime($from))."'   ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-2 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-2 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

			

		}
		else{

			$reportPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($from));

			$budget = selectColumn($budgetTable,'sum(room_nights)'," WHERE `id_user` = '".$rowExe->id."' AND `from`='".date('Y-04-01',strtotime($from))."' AND `to`='".date('Y-03-31',strtotime('+1 years',strtotime($from)))."'   ");

			$ytdPrevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$ytdAchieved =selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime($from))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

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
		array_push($mtdLastValues, ($prevYear==''?0:$prevYear));
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
			
	}	
}

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
$returnData['reportPeriod']=$reportPeriod;
$returnData['datePeriod']=$datePeriod;
echo json_encode($returnData);



