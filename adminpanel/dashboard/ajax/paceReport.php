<?php
include_once("../../../config/auto_loader.php");
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}
error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_REQUEST['period']);
//print_r($PeriodDateArray);
$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
///$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//$to = date('Y-m-d',strtotime($PeriodDateArray[1]. ' +1 day'));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//strtotime('+1 day', $stop_date)
//print_r($_SESSION);
//print_r($_REQUEST);
$mtdLastValues = array();
$mtdThisValues = array();

$mtdVisits = array();
$mtdRoomRevenue = array();
$ytdRoomRevenue= array();
$mtdTotalExpense = array();
$mtdThisAllHotelValues= array();
$ytdAllHotelValues= array();

$mtdThisAllHotelValuesMAT= array();
$ytdAllHotelValuesMAT= array();
$MonthWiseRevenueCurrentYearDataMAT=array();
   $ytdPrevYearRevenueDataMAT=array();

$budgetValues = array();
$graphotelName=array();

$ytdLastValues = array();
$ytdThisValues = array();

$ytdVisits = array();
$ytdRateLetters = array();
$ytdTotalExpense = array();

$exeNameArr = array();
$returnData = array();

$stackedArr = array();
$stackedDataSet = array();
 $monthNameData=array();
 $mtdRoomRevenueArr=array();
  $MonthWiseRoomNightsData=array();
  $MonthWiseRoomNightsLastYearData=array();
  $MonthWiseRevenueCurrentYearData=array();
      $ytdPrevYearRevenueData=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if($_REQUEST['reportType']==1){	
	$reportfieldVarible=	'created_at';
}else{
	$reportfieldVarible=	'checkin_date';
	
	
if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceEndYear=(date('Y') +1);
}
else {
    $year = (date('Y')-1)."-".date('Y');
}
 //$to = date('31-03-'.$FinanceEndYear);
 
	}
if(date('m',strtotime($from))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($from)));
	$lastDate = date('Y-m-d',strtotime($from));
}
else{
	$startDate =date('Y-04-01',strtotime($from));
	$lastDate = date('Y-m-d',strtotime($from));
}
  $from_book=$from;
  $to_book=$to;
  
  
$reportPeriod = date('d-m-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($PeriodDateArray[1]));
$datePeriod = date('d-m-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($PeriodDateArray[1]));

  
		
  	$reportPeriodMonth= date('F',strtotime($_POST['period'])).' '.$Year;
	$LYMONTH	=	date('Y-'.$month.'-01',strtotime('-1 years',strtotime($yearStart)));
	$CYMONTH	=	date('Y-m-01',strtotime($yearStart));
	$LYPEROD	= 	date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[0]))).' to '.date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[1])));
	$CYPERIOD   =	date('Y-m-d',strtotime($PeriodDateArray[0])).' to '.date('Y-m-d',strtotime($PeriodDateArray[1]));
  
  
  
  
  
  
  
 $From_CY_Date   =   date('Y-m-d',strtotime($PeriodDateArray[0]));
 $To_CY_Date     =   date('Y-m-d',strtotime($PeriodDateArray[1]));

$From_LY_Date   =date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[0])));
$To_LY_Date     =date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[1])));

// $From_CY_Finacial_Year=date('01-04-Y',strtotime('01-04-'.$yearStart));
// $To_CY_Finacial_Year=date('d-m-Y',strtotime($yearEnd));

//$From_LY_Finacial_Year=$PeriodDateArray[1];
//$To_LY_Finacial_Year=$PeriodDateArray[1];


//echo $_SESSION['teamNewMembers'];
 if($_REQUEST['id_hotel']>0){
	
	$cond = ' AND id="'.$_REQUEST['id_hotel'].'"   ';
	//$graphotelName='All Hotel';
	}else{
		//$cond = ' AND id="'.$_REQUEST['id_hotel'].'" order by name LIMIT 0,5';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       
	$reservationTable =TBL_BE_RESERVATION_QUERY;
if($_REQUEST['id_hotel']>0){
	//$hname=$rowExe->name;
	}else{
		//$hname='Hotels';
		
		}
	//=========================================================================================
//	$UserInActive	=	"  AND ( ".TBL_USERS.".status_inactive_date>='".$to."' ||  ".TBL_USERS.".status_inactive_date='0000-00-00') ";

//print_r($_SESSION);
//print_r($_REQUEST);
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
 if($_REQUEST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$_REQUEST['id_hotel'];
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($_REQUEST['id_hotel']==0){ 
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
		
		//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		//MOdify 02-02-2021
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		$allUser =" AND  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		//$allUser= " AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$_REQUEST['id_hotel'].", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$_REQUEST['id_hotel']."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser =" AND ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if( $team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    //$cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
	}
if($_REQUEST['reportType']==1){
    $ReportTypeMainTitle ='PICKUP ';
}
if($_REQUEST['reportType']==2){
    $ReportTypeMainTitle ='BOB ';
}

/*	echo '================='.$cond;
	echo '<br><pre>'.print_r($_REQUEST);$teamMembers;
	echo '<br>'.print_r($teamArray);
	echo $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE id!='' ".$cond." ".$allUser." order by name";
		 //echo $sqlExe;
		
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){
    
}
echo  ".$allUser.";	die;*/
	//=========================================================================================	
		$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
	
	if($_REQUEST['hotelId'] != ''){
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
	}
	if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
	}
	
	if($_REQUEST['booking_status'] != ''){
		$booking_status_arr = implode(',',$_REQUEST['booking_status']);
		$cond .= " AND `".TBL_ORDERS."`.`booking_status` in (".$booking_status_arr.") ";
	}
	
	if($_REQUEST['company_id'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
	}
	if($_REQUEST['guest'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
	}
	if($_REQUEST['payment_status'] != ''){
		$payment_status_arr = implode(',',$_REQUEST['payment_status']);
		$cond .= " AND `".TBL_ORDERS."`.`payment_status` in (".$payment_status_arr.") ";
	}
	
	if($_REQUEST['id_executive'] != ''){
		//$id_executive = implode(',',$_REQUEST['id_executive']);
			//$cond .= " AND ".TBL_USERS.".`id` in (".$id_executive.")";
	}
		if($_REQUEST['id_group_master'] != '' && $_REQUEST['id_group_master'] != '0' && $_REQUEST['id_group_master'] != '10000' ){
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($_REQUEST['id_group_master'])."'";
	}elseif($_REQUEST['id_group_master'] == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status=1 ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
	}else{
	
	    
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status=1 ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  if(strtoupper($objGroup->name)!='UNIT'){  
						$GroupArrayList[] = 	$objGroup->id;
						}
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
$reportArray=array();
$reportArrayNext=array();


  
  
 if(($_REQUEST['pdf']==1 && $_REQUEST['reportType']==1) || ($_REQUEST['summaryReportType'] == '9' && $_REQUEST['reportType']==1)){//Team Wise  Summary PDF reportType Pickup Report
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,

	`".TBL_USERS."`.name as name_executive 
	   FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       
       ".$cond." ".$allUser."  GROUP BY `mst_team`.id_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	  $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    array_push($mtdThisCancelled,($rowList->newCancelled==''?0:round($rowList->newCancelled)));
	    
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Team Wise'][$GroupName]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Team Wise'][$GroupName]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    $reportArray['Team Wise'][$GroupName]['CancelledRoomNights']=$rowList->newCancelled==''?0:round($rowList->newCancelled);
	    $reportArray['Team Wise'][$GroupName]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    
	        
	    }
	}  
  } 
  
 
  
//echo '<pre>===========================================';  
  
 // echo '<pre>';print_r($reportArray);
// echo '</pre>===========================================';
//die;

 $GroupArray=array();
  
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
   $MonthGroupArray=array();
    $yr = date("Y");
    $start = date("".$yr."-04-01");
    $end =  date("".($yr+1)."-03-31");
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $reserve = array(date("d-m-Y",strtotime($start)),date("d-m-Y",strtotime($end)));
	$dataRN = array();
    $finalData = array();
   // $CurrentMonth = date("m");
   // date('d/m/Y', strtotime('+2 months'));
    // $StartNewMonth = date('m',strtotime($PeriodDateArray[1]));
   // echo 'start'.$startMo= date($StartNewMonth) + 1;//date($StartNewMonth, strtotime('+1 months'));
  
   
    $FromDate =date('Y-m-d',strtotime($PeriodDateArray[0]));
     $ToDate =date('Y-m-d',strtotime($PeriodDateArray[1]));
     $dts =strtotime($PeriodDateArray[1]);
     //echo  $startMo =date("m", strtotime($dts));
    $date = date('Y-m-d',strtotime($PeriodDateArray[0]));
    $d = date_parse_from_format("Y-m-d", $date);
 $startMo = $d["month"];
//print_r($d);
//echo '---'.
$startYear = $d["year"];


   $endMo =date("m", strtotime("+4 month", $startMo));
     
     
     $diff = '2'; //abs($endMo-$startMo);
    
 
 
 
 
 
 if($_REQUEST['summaryReportType'] == '9'){  //Yearly Graph Conditions
 
 
 
for($i = 0 ; $i <= $diff ; $i++){
	   $monthNUmers =  DateTime::createFromFormat('!m', $startMo);
	    $monthNUmers = $monthNUmers->format('m');
		if (date('m') > $monthNUmers ) {
		//$PrevstartYr = (date('Y') -1);
		
		}
		else {
		//$PrevstartYr = (date('Y')+1);	
		}
/*if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
     $startYr = date('Y') + 1;
	 $PrevstartYr = (date('Y'));
}
else {
    $startYr = date('Y');
	$PrevstartYr = (date('Y')-1);
}	
		
if($_POST['id_hotel']>0){
	$ConnHotels=" and id_hotel='".$_POST['id_hotel']."'";
}else{
	$ConnHotels="";
	}
if ( date('m') > 6 ) {
    //echo  'first'.$year = date('Y') + 1;
}else {*/	
        //$checkYear=(date('Y')-1);
        $checkYear=$startYear;//date('Y',strtotime($PeriodDateArray[1]));
        
        //$checkYear=($checkYear-1);
        if ( date($monthNUmers) >= 12 ) {
            $startYr = date($checkYear) + 1;
            $PrevstartYr = (date($checkYear));
        
        }else{
        $startYr = date($checkYear);
        $PrevstartYr = (date($checkYear)-1);
        }
        
      
    
//}

       //$startYr = date('Y') ;
//echo '<br>'.$monthNUmers.'END='.$diff.'=='.$i.'YEAR='.$startYr;

if($diff==$i){
    $ConSql ="AND MONTH(`fs_order_detail`.dated) >= '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) >= '".$startYr."' ";
    $DataMonthname	=	'Future';
}else{
	$ConSql ="AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' ";
	$DataMonthname	=	date('F', mktime(0, 0, 0, $startMo, 10));
	}

  $sqlCurrentYearMonthWise ="SELECT `fs_orders`.*,`fs_users`.name as name_executive,`mst_team`.id_group,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 


,sum(case when (`fs_orders`.`booking_status` = '1' OR `fs_orders`.`booking_status` = '2' ) 
".$ConSql."   AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($FromDate))."' AND '".date('Y-m-d',strtotime($ToDate))."' then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when (`fs_orders`.`booking_status` = '1' OR `fs_orders`.`booking_status` = '2' ) 
".$ConSql."   AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($FromDate))."' AND '".date('Y-m-d',strtotime($ToDate))."' then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


".$cond." ".$ConSql.$condhotelAccess." group by `mst_team`.id_group";
       
       
    	//echo TBL_GROUP_MASTER;
       //echo  $allUser;
    // echo '<br><br>'.$sqlCurrentYearMonthWise;
      // die;
       
       
        $resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
       
        
       
	   $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       
       $empty7=0;
       $newConfirmednewTentative='';
       //$resultrowExit = mysqli_num_rows($sqlCurrentYearMonthWise);
	while($rowListMonth = mysqli_fetch_object($resultCurrentYearMonthWise)){
	   // echo '<br><br><br>'.$GroupName.'=='.date('F', mktime(0, 0, 0, $startMo, 10)).'====>';
	    //print_r($rowListMonth);
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowListMonth->ids_team."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowListMonth->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowListMonth->id_default_group."'");
	    
	    //echo '<br>'.$GroupName.'=='.date('F', mktime(0, 0, 0, $startMo, 10)).'====>'.$newConfirmednewTentative =$rowListMonth->newConfirmed;
	    
	    //echo '<br>'.$GroupName.'=='.date('F', mktime(0, 0, 0, $startMo, 10)).'====>'.$newConfirmednewTentative =($rowList->newConfirmed);
	    
	    //if($newConfirmednewTentative>0){
	       // $mtdThisValues[$GroupName][date('F', mktime(0, 0, 0, $startMo, 10))]['Newroomnights'][]=($newConfirmednewTentative);
	    //array_push($mtdThisValues[$GroupName][date('F', mktime(0, 0, 0, $startMo, 10))]['Newroomnights'][],$newConfirmednewTentative);
	    //array_push($mtdRoomRevenue,($rowListMonth->newConfirmed==''?0:round($rowListMonth->newConfirmed)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    //$reportArray['Team Wise'][$GroupName]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    
	    $reportArray['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Newroomnights']      = $rowListMonth->newConfirmed==''?'0':$rowListMonth->newConfirmed;
	    $reportArray['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['NewconfimedRevenue'] = $rowListMonth->confimed_revenue==''?0:round($rowListMonth->confimed_revenue);
	    $reportArray['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['CancelledRoomNights'] = $rowListMonth->newCancelled==''?0:round($rowListMonth->newCancelled);
	    
	    
	    $reportArray['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['MonthName'] = $DataMonthname.'-'.$startYr;
	    //}
	}
   
   

   
   
     $startMo++;  
  }
 } //Yearly Graph Condition End    
   //===========================Segment Wise Chart START==================================
  
// echo '<pre>===========================================';
    //print_r($reportArray);
  //print_r($mtdThisValues);
  
 //echo '</pre>==========================================='; 
  
  
  
  
 
 
//print_r($mtdThisValues);
//echo '<pre>';
//print_r($reportArray);
//die;
$content ='';
//if($_REQUEST['pdf']==1){
    $content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';
 
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
if($_REQUEST['pdf']==1){
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="./../../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	   $content .=    '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="4" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; 
}
foreach($reportArray as $maintitle=>$GroupArray){
    
       
   
  // echo '<pre>===========================================';
  
 //print_r($GroupArray); 
 //print_r($reportArrayNext);
  
// echo '</pre>==========================================='; 
      
    
     
      $UnitValueIs='';
     $UnitValueIsWithout='';
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)!='UNIT'){
                         $UnitValueIsWithout='1';
                     }else{
                         
                         $UnitValueIs='1';
                     }
     }         
     //=======================================================================================
     //=======================================================================================
         if($maintitle=='Team Wise'){
              if($UnitValueIsWithout==1){
        	  //Office Team Wise For Period Start  
        	 $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="12" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$maintitle0ch.' Pace Report For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .='<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Cancelled Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue(Lacs)</th>';
             
             $contentGroupHeader .='</tr>';
             $contentGroupHeader .='<tr ><th colspan="4" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">&nbsp</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;"> Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue(Lacs)</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;"> Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue(Lacs)</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue(Lacs)</th>';
             
             $contentGroupHeader .='</tr>';
                 $TotalTeamWiseRoomNightContribution='';
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)!='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['roomnights'];
                    
                     }
                 }
                  $RoomNightsTotal='';
                 foreach($GroupArray as $name => $GroupNameArray){
                    // print_r($GroupNameArray);
                     if(strtoupper($name)!='UNIT'){
                        $SubData='';
                        $dynamicHeader='';
                        $SubDataTotal='';
                       
                        foreach($GroupNameArray  as $k1=>  $monthdata){
                            
                            if($monthdata['MonthName']!=''){
                            
                            $SubData .= '<td style="text-align:center;" >'.$monthdata['Newroomnights'].'</td><td style="text-align:center;" >'.round($monthdata['NewconfimedRevenue']/100000,2).'</td>';
                            
                            $RoomNightsTotal[$k1]['Newroomnights']    +=         $monthdata['Newroomnights']==''?'0':$monthdata['Newroomnights'];
                            $RoomNightsTotal[$k1]['NewconfimedRevenue']   +=   $monthdata['NewconfimedRevenue']==''?'0':$monthdata['NewconfimedRevenue'];
                            $dynamicHeader .= '<th colspan="2" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$monthdata['MonthName'].'</th>';
                            //print_r($monthdata);
                            //echo '<pre>';print_r($RoomNightsTotal);
                                
                            }
                        
                           
                         }
                        //print_r($RoomNightsTotal);
                         //$SubData .= '<td style="text-align:center;">000'.$monthdata[$monthName]['Newroomnights'].'</td>';
                    $contentGroupHeader .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td><td style="text-align:center;">'.($GroupNameArray['CancelledRoomNights']==''?'0':$GroupNameArray['CancelledRoomNights']).'</td><td style="text-align:center;">'.($GroupNameArray['roomnights']==''?'0':$GroupNameArray['roomnights']).'</td><td style="text-align:center;">'.round($GroupNameArray['confimed_revenue']/100000,2).'</td>';
                    $contentGroupHeader.=$SubData.'</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['roomnights'];
                    
                    $TotalTeamWiseConfirmRoomNight+=$GroupNameArray['confimed_revenue'];
                    
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    $TotalTeamWisCancelledRoomNights+=$GroupNameArray['CancelledRoomNights'];
                    
                    $TotalTeamWiseRoomNight1+=$GroupNameArray['roomnights'];
                    $TotalTeamWiseConfimedRevenue1+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWisCancelledRoomNights1+=$GroupNameArray['CancelledRoomNights'];
                     }
                 }
                 
                         
                      
                         foreach($RoomNightsTotal as $keyval=> $totalValueMonth){
                             
                             $RoomNightsTotaldata += ($totalValueMonth['Newroomnights']);
                            $RoomNightsTotaldata2 += round($totalValueMonth['NewconfimedRevenue']/100000,2);
                            $SubDataTotal .= '<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$RoomNightsTotaldata.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$RoomNightsTotaldata2.'</td>';
                            $RoomNightsTotaldata='';$RoomNightsTotaldata2='';
                             }
                        
            
            $contentGroup .=$dynamicHeader.$contentGroupHeader;
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">Total </td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWisCancelledRoomNights.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfirmRoomNight/100000,2).'</td>'.$SubDataTotal.'</tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             $TotalTeamWisCancelledRoomNights='';
             $RoomNightsTotaldata='';
             $RoomNightsTotaldata2='';
             
             $contentGroup .= '</table>';
             }
             
             
             
             
             
             
              if($UnitValueIs==1){
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="5" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>UNIT '.$maintitle0ch.' Pace Report  For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .=    '<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Cancelled Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>';
             $contentGroup .=    '</tr>';
                 $TotalTeamWiseRoomNightContribution='';
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['roomnights'];
                    
                     }
                 }
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                    $contentGroup .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td><td style="text-align:center;">'.$GroupNameArray['CancelledRoomNights'].'</td><td style="text-align:center;">'.$GroupNameArray['roomnights'].'</td><td style="text-align:center;">'.round($GroupNameArray['confimed_revenue']/100000,2).'</td><td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td></tr>';
                    $TotalTeamWiseRoomNight2+=$GroupNameArray['roomnights'];
                    $TotalTeamWiseConfimedRevenue2+=$GroupNameArray['confimed_revenue'];
                    
                    $TotalTeamWiseRoomNight+=$GroupNameArray['roomnights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['confimed_revenue'];
                    
                    $TotalTeamWisCancelledRoomNights2+=$GroupNameArray['CancelledRoomNights'];
                     }
                 }
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             
             
             $SumTotalTeamWiseArrayArr =round(($TotalTeamWiseConfimedRevenue2+$TotalTeamWiseConfimedRevenue1)/($TotalTeamWiseRoomNight2+$TotalTeamWiseRoomNight1));
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">Total </td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWisCancelledRoomNights2.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;font-size:13px;"><td style="text-align:center;background-color:#c2d69a;">Grand Total </td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.($TotalTeamWisCancelledRoomNights2+$TotalTeamWisCancelledRoomNights1).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.($TotalTeamWiseRoomNight2+$TotalTeamWiseRoomNight1).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round(($TotalTeamWiseConfimedRevenue2+$TotalTeamWiseConfimedRevenue1)/100000,2).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArrayArr.'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             
             $contentGroup .= '</table>';
              }
             
             
         }
         $content .=$contentGroup;
         //$content .=$contentTeam;
         
         
         //$contentGroup='';
        // $contentTeam='';
     //Office Team Wise For Period End
     
}
//echo $content;
//die;
if($_REQUEST['pdf']==1){

    $dompdf = new DOMPDF();

echo $content;
die;
//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('portable', 'portable');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));




$Filename=$ReportTypeMainTitle.'PickupReport_'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}else{
echo $content;
//echo json_encode($returnData);
}
 