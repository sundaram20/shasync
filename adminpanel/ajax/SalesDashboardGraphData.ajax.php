<?php


include_once("../../config/auto_loader.php");
//print_r($_SESSION);
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}
error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_POST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));


$ComparePeriodDateArray	=	explode('to',$_POST['ComparePeriod']);
$ComparePeriod_from = date('Y-m-d',strtotime($ComparePeriodDateArray[0]));
$ComparePeriod_to = date('Y-m-d',strtotime($ComparePeriodDateArray[1]));







$dateFromForm = DateTime::createFromFormat("Y-m-d", $from);
$FinacialYearFrom   =    $dateFromForm->format("Y");
$FinacialDayMonthFrom   =    $dateFromForm->format("d-m");

$dateToYear = DateTime::createFromFormat("Y-m-d", $to);
$FinacialYearTo  =    $dateToYear->format("Y");
$FinacialDayMonthTo   =    $dateToYear->format("d-m");

$CompareFromForm = DateTime::createFromFormat("Y-m-d", $ComparePeriod_from);
$FinacialCompareYearFrom   =    $CompareFromForm->format("Y");

$CompareToYear = DateTime::createFromFormat("Y-m-d", $ComparePeriod_to);
$FinacialCompareYearTo  =    $CompareToYear->format("Y");

//============================================================
//$Diffrence  =($FinacialYearTo-$FinacialCompareYearTo);
  $Diffrence='';
  $CompareFinancialYear	=	explode('-',$_POST['CompareFinancialYear']);
  $CurrentFinancialYear	=	explode('-',$_POST['CurrentFinancialYear']);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);

 

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
// $monthNameDataQuarterly=array();
 $mtdRoomRevenueArr=array();
  $MonthWiseRoomNightsData=array();
  $MonthWiseRoomNightsLastYearData=array();
  $MonthWiseRevenueCurrentYearData=array();
      $ytdPrevYearRevenueData=array();
      $mtdRoomRevenueLastYearArr=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if($_POST['reportType']==1){	
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
 $to = date('31-03-'.$FinanceEndYear);
 
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


//echo $_SESSION['teamNewMembers'];
 if($_POST['id_hotel']>0){
	
	$cond = ' AND id="'.$_POST['id_hotel'].'"   ';
	//$graphotelName='All Hotel';
	}else{
		//$cond = ' AND id="'.$_POST['id_hotel'].'" order by name LIMIT 0,5';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       
	$reservationTable =TBL_BE_RESERVATION_QUERY;
if($_POST['id_hotel']>0){
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
 if($_POST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$_POST['id_hotel'];
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($_POST['id_hotel']==0){ 
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
		$allUser =" AND  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		//$allUser= " AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$_POST['id_hotel'].", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$_POST['id_hotel']."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser =" AND  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if( $team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    //$cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
	}
if($_REQUEST['id_group_sub_master']>0){
    
    $condTeamGroup.= " AND `".TBL_TEAM."`.`id` =".$_REQUEST['id_group_sub_master']." ";
}else{
    
    $condTeamGroup=' ';
}	
/*	echo '================='.$cond;
	echo '<br>'.$teamMembers;
	echo '<br>'.print_r($teamArray);
	echo $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE id!='' ".$cond." ".$allUser." order by name";
		 //echo $sqlExe;
		
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){
    
}*/
//echo  ".$allUser.";	die;
	//=========================================================================================	
	
	
	
		$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";

	if($_REQUEST['hotelId'] != ''){
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
	}
	if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
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

    //Custom Report Start==============================================================================
     $sqlCustomeReport = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       
       ".$cond." ".$allUser.$condTeamGroup ;
       
       
      //echo $sqlCustomeReport;
     // die;
     //  
       
       $resultListCustomeReport = mysqli_query($connNew,$sqlCustomeReport);
	while($rowListCustomeReport = mysqli_fetch_object($resultListCustomeReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeReportValues2+=($rowListCustomeReport->newConfirmed+$rowListCustomeReport->newTentative);
	    $mtdRoomCustomeReportRevenue2+=round(($rowListCustomeReport->confimed_revenue+$rowListCustomeReport->tentative_revenue)/100000,2);

	}
		
    $mtdThisCustomeReportValues=array();
    $mtdRoomCustomeReportRevenue=array();
    
    array_push($mtdThisCustomeReportValues,$mtdThisCustomeReportValues2);
    array_push($mtdRoomCustomeReportRevenue,$mtdRoomCustomeReportRevenue2);
    //booking _date end
    
    
    
    $sqlCustomeLastYearReport = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond." ".$allUser.$condTeamGroup ;
       
       
    //  echo 'LAST Year ='.$sqlCustomeLastYearReport;
    // die;
     //  
       
       $resultListCustomeLastYearReport = mysqli_query($connNew,$sqlCustomeLastYearReport);
	while($rowListCustomeLastYearReport = mysqli_fetch_object($resultListCustomeLastYearReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeLastYearReportValues2+=($rowListCustomeLastYearReport->newConfirmed+$rowListCustomeLastYearReport->newTentative);
	    $mtdRoomCustomeLastYearReportRevenue2+=round(($rowListCustomeLastYearReport->confimed_revenue+$rowListCustomeLastYearReport->tentative_revenue)/100000,2);
//round($ytdPrevYearRevenue/100000,2)
	}
		
    $mtdThisCustomeLastYearReportValues=array();
    $mtdRoomCustomeLastYearReportRevenue=array();
    
    array_push($mtdThisCustomeLastYearReportValues,$mtdThisCustomeLastYearReportValues2);
    array_push($mtdRoomCustomeLastYearReportRevenue,$mtdRoomCustomeLastYearReportRevenue2);
    //booking _date end
    
    
    
    //Custom Report End==============================================================================
    
  

	
		

   
   
   
   
   
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
   
    
 if($_REQUEST['viewMonthwise']==1){
 $yr = $FinacialYearFrom;//date("Y");
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
  if($diffYr > 0){
    $diff = abs(($endMo+12-$startMo));
  }
 
 //$PrevstartYr = date('Y',strtotime('-1 years',strtotime($startYr)));
 $sqlSumConnCY='';
 $sqlSumConnLY='';
   //Yearly Graph Conditions
   $listmonthArray=array();
for($i = 0 ; $i <= $diff ; $i++){
	  $monthNUmers =  DateTime::createFromFormat('!m', $startMo);
	  $monthNUmers = $monthNUmers->format('m');
		if (date('m') > $monthNUmers ) {
		//$PrevstartYr = (date('Y') -1);
		
		}
		else {
		//$PrevstartYr = (date('Y')+1);	
		}
if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
     $startYr = $FinacialYearFrom + 1;
     $PrevstartYr = $ComparePeriod_from + 1;
	 ///$PrevstartYr = ($FinacialYearFrom);
}
else {
    $startYr = $FinacialYearFrom;
    $PrevstartYr= $ComparePeriod_from;
	///$PrevstartYr = ($FinacialYearFrom-1);
}		
		
if($_POST['id_hotel']>0){
	$ConnHotels=" and id_hotel='".$_POST['id_hotel']."'";
}else{
	$ConnHotels="";
	}
if ( date('m') > 6 ) {
    //echo  'first'.$year = $FinacialYearFrom + 1;
}else {
        //$checkYear=($FinacialYearFrom-1);
         $checkYear=$FinacialYearFrom;
        
        if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
        $startYr = date($checkYear) + 1;
        $PrevstartYr= date($ComparePeriod_from) + 1;
        ///$PrevstartYr = (date($checkYear));
        }
        else {
        $startYr = date($checkYear);
        $PrevstartYr= date($ComparePeriod_from);
        ///$PrevstartYr = (date($checkYear)-1);
        }
    
}
//echo '<br>'.$startYr.'====='.$PrevstartYr;
		$sqlSumConnCY	.="	SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND MONTH(`".TBL_ORDERS."`.last_modified) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.last_modified) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND MONTH(`".TBL_ORDERS."`.invoice_date)  = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.invoice_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative_".$monthNUmers.",   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue_".$monthNUmers.", 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND MONTH(`".TBL_ORDERS."`.booking_confirm_date)   = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue_".$monthNUmers." ,
    		";
	
	
	$sqlSumConnLY	.="SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND MONTH(`".TBL_ORDERS."`.last_modified) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.last_modified) = '".$PrevstartYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND MONTH(`".TBL_ORDERS."`.invoice_date)  = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.invoice_date) = '".$PrevstartYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$PrevstartYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed_".$monthNUmers.",
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$PrevstartYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative_".$monthNUmers.",   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$PrevstartYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue_".$monthNUmers.", 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND MONTH(`".TBL_ORDERS."`.booking_confirm_date)   = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$PrevstartYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue_".$monthNUmers." ,";
			
	
	
	
   
   $listmonthArray[]=$monthNUmers;
   $listYearArray[]=$startYr;
   
     $startMo++;  
  }
 
    $sqlCurrentYearMonthWise = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		".$sqlSumConnCY."
			
			`".TBL_USERS."`.name as name_executive 
    		
    		FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup ;
       
       
    	
       //echo  $allUser;
       //echo $sqlCurrentYearMonthWise;
       //die;
       
       
        $resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
        $rowListCurrentYearMonthWise = mysqli_fetch_object($resultCurrentYearMonthWise);
        
        
        foreach($listmonthArray   as $monthkey=>$montharrayval){
            
            $ConnectVal = '_'.$montharrayval;
            $ConnectValnewConfirmed = 'newConfirmed_'.$montharrayval;
            $ConnectValnewTentative = 'newTentative_'.$montharrayval;
            
            $ConnectValconfimed_revenue = 'confimed_revenue_'.$montharrayval;
            
            $ConnectValtentative_revenue = 'tentative_revenue_'.$montharrayval;
            
           // echo '<br>'.$rowListCurrentYearMonthWise->$ConnectValnewConfirmed;
            $MonthWiseRoomNightsCurrentYear=$rowListCurrentYearMonthWise->$ConnectValnewConfirmed+$rowListCurrentYearMonthWise->$ConnectValnewTentative;
            $MonthWiseRevenueCurrentYear =$rowListCurrentYearMonthWise->$ConnectValconfimed_revenue+$rowListCurrentYearMonthWise->$ConnectValtentative_revenue;
    $monthName =  DateTime::createFromFormat('!m', $montharrayval);
    $monthName = $monthName->format('F');
   
   array_push($monthNameData,$monthName);
   array_push($MonthWiseRoomNightsData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
   
            if($montharrayval==4 || $montharrayval==5 || $montharrayval==6){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ1+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ1+=round($MonthWiseRevenueCurrentYear/100000,2);
				} 
			if($montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ2+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ2+=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ3+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ3+=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
			if($montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ4+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ4+=round($MonthWiseRevenueCurrentYear/100000,2);;
				}
				//half year======================
			 if($montharrayval==4 || $montharrayval==5 || $montharrayval==6 || $montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$MonthWiseRoomNightsCurrentYearHalfYearH1 +=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearHalfYearH1 +=round($MonthWiseRevenueCurrentYear/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12 || $montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$MonthWiseRoomNightsCurrentYearHalfYearH2 +=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearHalfYearH2 +=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
		$monthNameDataQuarterly=array('Q1','Q2','Q3','Q4');
   //array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear/100000,2));
  
  
   $MonthWiseRoomNightsCurrentYear2  += $MonthWiseRoomNightsCurrentYear;
   $MonthWiseRevenueCurrentYear2  += $MonthWiseRevenueCurrentYear;
   array_push($mtdThisAllHotelValuesMAT,$MonthWiseRoomNightsCurrentYear==''?'null':$MonthWiseRoomNightsCurrentYear2);
   array_push($MonthWiseRevenueCurrentYearDataMAT,$MonthWiseRevenueCurrentYear==''?'null':round($MonthWiseRevenueCurrentYear2/100000,2));
   array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear/100000,2));
            
       if($MonthWiseRoomNightsCurrentYear>0 && $MonthWiseRevenueCurrentYear>0){
	$mtdRoomRevenueArr2  =round($MonthWiseRevenueCurrentYear/$MonthWiseRoomNightsCurrentYear);
	array_push($mtdRoomRevenueArr,$mtdRoomRevenueArr2);
	}else{
		array_push($mtdRoomRevenueArr,'null');
		} }
        //die;
        
        //print_r($monthNameData);
	 	////print_r($MonthWiseRoomNightsData);
	 	//die;
	 	$sqlPrevYearMonthWise = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		
		".$sqlSumConnLY."	
			
			`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
           LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
           LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
           LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
           LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group".$cond."  ".$allUser.$condTeamGroup ;
       
       
    
       
      // echo $sqlPrevYearMonthWise;
       //die;
       
     // $graphotelName[]='Hotels';  
    $resultPrevYearMonthWise = mysqli_query($connNew,$sqlPrevYearMonthWise);
    $rowListPrevYearMonthWise = mysqli_fetch_object($resultPrevYearMonthWise);
    
    
     foreach($listmonthArray   as $monthkey=>$montharrayval){
    
     $ConnectVal = '_'.$montharrayval;
     
      $ConnectVal = '_'.$montharrayval;
            $ConnectVallastYearnewConfirmed = 'newConfirmed_'.$montharrayval;
            $ConnectVallastYearnewTentative = 'newTentative_'.$montharrayval;
            
            $ConnectVallastYearconfimed_revenue = 'confimed_revenue_'.$montharrayval;
            
            $ConnectVallastYeartentative_revenue = 'tentative_revenue_'.$montharrayval;
     
    $ytdPrevYearRoomNights=$rowListPrevYearMonthWise->$ConnectVallastYearnewConfirmed+$rowListPrevYearMonthWise->$ConnectVallastYearnewTentative;
    $ytdPrevYearRevenue =$rowListPrevYearMonthWise->$ConnectVallastYearconfimed_revenue+$rowListPrevYearMonthWise->$ConnectVallastYeartentative_revenue;
    
    
     if($montharrayval==4 || $montharrayval==5 || $montharrayval==6){
				$ytdPrevYearRoomNightsQuarterlyQ1+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ1+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$ytdPrevYearRoomNightsQuarterlyQ2+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ2+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12){
				$ytdPrevYearRoomNightsQuarterlyQ3+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ3+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$ytdPrevYearRoomNightsQuarterlyQ4+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ4+=round($ytdPrevYearRevenue/100000,2);
				} 
			 if($montharrayval==4 || $montharrayval==5 || $montharrayval==6 || $montharrayval==7 || $montharrayval==8 || $montharrayval==9){
					$ytdPrevYearRoomNightsHalfYearH1+=$ytdPrevYearRoomNights;
				    $ytdPrevYearRevenueHalfYearH1+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12 || $montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$ytdPrevYearRoomNightsHalfYearH2+=$ytdPrevYearRoomNights;
				    $ytdPrevYearRevenueHalfYearH2+=round($ytdPrevYearRevenue/100000,2);
				} 
			
			
			
				
				
   array_push($MonthWiseRoomNightsLastYearData,$ytdPrevYearRoomNights==''?0:$ytdPrevYearRoomNights);
     
    $ytdPrevYearRoomNights2  += $ytdPrevYearRoomNights;
     $ytdPrevYearRevenue2  += $ytdPrevYearRevenue;
    array_push($ytdPrevYearRevenueDataMAT,$ytdPrevYearRevenue==''?'null':round($ytdPrevYearRevenue2/100000,2));
   array_push($ytdAllHotelValuesMAT,$ytdPrevYearRoomNights==''?'null':$ytdPrevYearRoomNights2);
   
   array_push($ytdPrevYearRevenueData,$ytdPrevYearRevenue==''?0:round($ytdPrevYearRevenue/100000,2));      
     
   
 	//$mtdThisAllHotelValues[]=($mtdThisAllHotelValuesResult==''?0:$mtdThisAllHotelValuesResult);
	//$ytdAllHotelValues[]=($ytdAllHotelValuesResult==''?0:$ytdAllHotelValuesResult);
	
	 //ARR===============================
     
		
		
		if($MonthWiseRoomNightsLastYearData>0  && $ytdPrevYearRevenue>0){
	$mtdRoomRevenueArrLastYear2  =round($ytdPrevYearRevenue/$ytdPrevYearRoomNights);
	array_push($mtdRoomRevenueLastYearArr,$mtdRoomRevenueArrLastYear2);
	}else{
		array_push($mtdRoomRevenueLastYearArr,'null');
		}	
		
     }	
	//ARR===============================
   
     
 } //Yearly Graph Condition End    
 
 $MonthWiseRoomNightsCurrentYearQuarterly=array($MonthWiseRoomNightsCurrentYearQuarterlyQ1,$MonthWiseRoomNightsCurrentYearQuarterlyQ2,$MonthWiseRoomNightsCurrentYearQuarterlyQ3,$MonthWiseRoomNightsCurrentYearQuarterlyQ4);
 $MonthWiseRevenueCurrentYearQuarterly=array($MonthWiseRevenueCurrentYearQuarterlyQ1,$MonthWiseRevenueCurrentYearQuarterlyQ2,$MonthWiseRevenueCurrentYearQuarterlyQ3,$MonthWiseRevenueCurrentYearQuarterlyQ4);
 
 
 $ytdPrevYearRoomNightsQuarterly=array($ytdPrevYearRoomNightsQuarterlyQ1,$ytdPrevYearRoomNightsQuarterlyQ2,$ytdPrevYearRoomNightsQuarterlyQ3,$ytdPrevYearRoomNightsQuarterlyQ4);
 $ytdPrevYearRevenueQuarterly=array($ytdPrevYearRevenueQuarterlyQ1,$ytdPrevYearRevenueQuarterlyQ2,$ytdPrevYearRevenueQuarterlyQ3,$ytdPrevYearRevenueQuarterlyQ4);
 
 
$MonthWiseRoomNightsCurrentYearHalfYear=array($MonthWiseRoomNightsCurrentYearHalfYearH1,$MonthWiseRoomNightsCurrentYearHalfYearH2);
 $MonthWiseRevenueCurrentYearHalfYear=array($MonthWiseRevenueCurrentYearHalfYearH1,$MonthWiseRevenueCurrentYearHalfYearH2);
 
 
 $ytdPrevYearRoomNightsHalfYear=array($ytdPrevYearRoomNightsHalfYearH1,$ytdPrevYearRoomNightsHalfYearH2);
 $ytdPrevYearRevenueHalfYear=array($ytdPrevYearRevenueHalfYearH1,$ytdPrevYearRevenueHalfYearH2);
 
			
    //	echo '<pre>';
			//	array_sum($MonthWiseRoomNightsCurrentYearQuarterly);
			//	print_r($MonthWiseRoomNightsCurrentYearQuarterly);
			//	echo '</pre>';
 
 
  

	
	 //===========================Segment Wise Chart START==================================
 
	$OfferNameArray=array();
	$rowOfferListArray=array();
  $sqlOfferList = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id 
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_ORDERS."`.segment_id";
       
      
$resOfferList = mysqli_query($connNew,$sqlOfferList);
	while($rowOfferList = mysqli_fetch_object($resOfferList)){

	$OfferName 	= selectColumn('fs_segment_master','name'," WHERE   id='".$rowOfferList->segment_id."' and id_shop='".$_SESSION['shop']."'   ");
	$rowOfferList = $rowOfferList->newConfirmed+$rowOfferList->newTentative;
	    $OfferNameList=strtoupper($OfferName);//.'('.$rowOfferList.')';
	array_push($OfferNameArray,$OfferNameList==''?'0':$OfferNameList);
	array_push($rowOfferListArray,$rowOfferList==''?'0':$rowOfferList);
	}
	if(empty($OfferNameArray)) {
	    array_push($OfferNameArray,'0');
	}
		if(empty($rowOfferListArray)) {
	    array_push($rowOfferListArray,'0');
	}
	
	$SegmentWiseListLastYearArray=array();
	$sqlSegmentWiseLastYearGroup = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.segment_id,`".TBL_COMPANY."`.id_default_group,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_ORDERS."`.segment_id";
       
      //echo 	$sqlSegmentWiseLastYearGroup;die;
    $resSegmentWiseLastYearGroup = mysqli_query($connNew,$sqlSegmentWiseLastYearGroup);
	while($rowSegmentWiseLastYearGroup  = mysqli_fetch_object($resSegmentWiseLastYearGroup)){

	//$segment_masterName 	= selectColumn(TBL_GROUP,'name'," WHERE   id_group='".$rowCompanyGroup->id_default_group."' and id_shop='".$_SESSION['shop']."'   ");
	$rowSegmentWiseLastYearValue = $rowSegmentWiseLastYearGroup->newConfirmed+$rowSegmentWiseLastYearGroup->newTentative;
	
	//array_push($CompanyGroupNameArray,strtoupper($segment_masterName));
	array_push($SegmentWiseListLastYearArray,$rowSegmentWiseLastYearValue==''?'0':$rowSegmentWiseLastYearValue);
	//array_push($CompanyGroupListLastYearArray,0);
	
	}
		if(empty($SegmentWiseListLastYearArray)) {
	    array_push($SegmentWiseListLastYearArray,'0');
	}
	
	
	
//===========================Segment Wise Chart END==================================
	
//===========================COMPANY SOURSE Wise Chart START==================================
 
	$CompanyGroupNameArray=array();
	$CompanyGroupListArray=array();
	$CompanyGroupListLastYearArray=array();
   $sqlCompanyGroup = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_COMPANY."`.id_default_group,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_COMPANY."`.id_default_group";
       
     // die;
    $resCompanyGroup = mysqli_query($connNew,$sqlCompanyGroup);
	while($rowCompanyGroup  = mysqli_fetch_object($resCompanyGroup)){

	$segment_masterName 	= selectColumn(TBL_GROUP,'name'," WHERE   id_group='".$rowCompanyGroup->id_default_group."' AND status='1'  ");
	$rowCompanyGroupValue = $rowCompanyGroup->newConfirmed+$rowCompanyGroup->newTentative;
	
	array_push($CompanyGroupNameArray,$segment_masterName==''?'0':strtoupper($segment_masterName));
	array_push($CompanyGroupListArray,$rowCompanyGroupValue==''?'0':$rowCompanyGroupValue);
	//array_push($CompanyGroupListLastYearArray,0);
	
	}
	if(empty($CompanyGroupNameArray)) {
	    array_push($CompanyGroupNameArray,'0');
	}
		if(empty($CompanyGroupListArray)) {
	    array_push($CompanyGroupListArray,'0');
	}
//'".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."'
//'".date('Y-m-d',strtotime($LastyearStart))."' AND '".date('Y-m-d',strtotime($LastyearEnd))."'
$CompanyGroupListLastYearArray=array();
	$sqlCompanyLastYearGroup = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_COMPANY."`.id_default_group,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_COMPANY."`.id_default_group";
       
      
    $resCompanyLastYearGroup = mysqli_query($connNew,$sqlCompanyLastYearGroup);
	while($rowCompanyLastYearGroup  = mysqli_fetch_object($resCompanyLastYearGroup)){

	//$segment_masterName 	= selectColumn(TBL_GROUP,'name'," WHERE   id_group='".$rowCompanyGroup->id_default_group."' and id_shop='".$_SESSION['shop']."'   ");
	$rowCompanyGroupLastYearValue = $rowCompanyLastYearGroup->newConfirmed+$rowCompanyLastYearGroup->newTentative;
	
	//array_push($CompanyGroupNameArray,strtoupper($segment_masterName));
	array_push($CompanyGroupListLastYearArray,$rowCompanyGroupLastYearValue==''?'0':$rowCompanyGroupLastYearValue);
	//array_push($CompanyGroupListLastYearArray,0);
	
	}
		if(empty($CompanyGroupListLastYearArray)) {
	    array_push($CompanyGroupListLastYearArray,'0');
	}
//===========================COMPANY SOURSE Wise Chart END==================================
//===========================Booking CompleteChar horizontalBar===============================================================
$setFormat = date_create( date('Y-m-d'));
$current_date = $setFormat->format('Y-m-d');
$last_year_current_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));

//MTD
$from = date_create($current_date);
$from_month_to_date = date_create($from->format('Y-m-01'));
$from_month_to_date = $from_month_to_date->format('Y-m-d');
$to_month_to_date = $current_date;
$last_year_to_month_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_month_date);

$last_year_from_month_date = date_create($from->format('Y-m-01'));
$last_year_from_month_date = $last_year_from_month_date->format('Y-m-d');

//YTD
$to_year_to_date = $current_date;
$from = date_create($current_date);

if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
	$from_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($from_year_to_date)));
}
else{
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
}

$last_year_to_year_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_year_date);
$last_year_from_year_date = date_create($from->format('Y-04-01'));
if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
    $last_year_from_year_date = date('Y-m-d',strtotime('-1 year',strtotime($last_year_from_year_date)));
  }
  else{
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
  }
  $current_quarter = ceil(date('n') / 3);
$QuarterThisYearstart_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
$QuarterThisYearlast_date = date('Y-m-t', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));

$Quarterlast_yearstart_date = date('Y-m-d',strtotime('-1 year',strtotime($QuarterThisYearstart_date)));
$Quarterlast_yeartart_date = date('Y-m-d',strtotime('-1 year',strtotime($QuarterThisYearlast_date)));

if($_REQUEST['viewMonthwise']=='1'){
    
    $QuarterThisYearlast_date=$to_year_to_date;
    $Quarterlast_yeartart_date=$last_year_to_year_date;
}

 $sqlHor = " SELECT 
    
     
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_month_to_date."' and '".$to_month_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `MTDThisYearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_month_date."' and '".$last_year_to_month_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `MTDLastYearRoomNights`,


sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `YTDThisYearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `YTDLastYearRoomNights`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2')  and (  `".TBL_ORDERS."`.booking_confirm_date = '".$current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `ThisYearConfirmandTend`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date = '".$last_year_current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearConfirmandTend`,
    	
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$QuarterThisYearstart_date."' and '".$QuarterThisYearlast_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `QTYThisYearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$Quarterlast_yearstart_date."' and '".$Quarterlast_yeartart_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `QTYLastYearRoomNights`,




sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_month_to_date."' and '".$to_month_to_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `MTDThisYearRevenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_month_date."' and '".$last_year_to_month_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `MTDLastYearRevenue`,


sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `YTDThisYearRevenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `YTDLastYearRevenue`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2')  and (  `".TBL_ORDERS."`.booking_confirm_date = '".$current_date."') then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `ThisYearConfirmandTendRevenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date = '".$last_year_current_date."') then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `LastYearConfirmandTendRevenue`,
    	
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$QuarterThisYearstart_date."' and '".$QuarterThisYearlast_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `QTYThisYearRevenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$Quarterlast_yearstart_date."' and '".$Quarterlast_yeartart_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `QTYLastYearRevenue`
   	   		
    FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       
       
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
        LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond." ".$allUser.$condTeamGroup." 
      Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id
       ";
      // echo $sqlHor;
    // die;
    $resultListHor = mysqli_query($connNew,$sqlHor);
	$rowListHor = mysqli_fetch_object($resultListHor);
$rowListHorThisYear =	array($rowListHor->ThisYearConfirmandTend,$rowListHor->MTDThisYearRoomNights,$rowListHor->QTYThisYearRoomNights,	$rowListHor->YTDThisYearRoomNights);

$rowListHorLastYear =	array($rowListHor->LastYearConfirmandTend,$rowListHor->MTDLastYearRoomNights,$rowListHor->QTYLastYearRoomNights,$rowListHor->YTDLastYearRoomNights);

$rowListHorThisYearRevenue =	array(round($rowListHor->ThisYearConfirmandTendRevenue/100000,2),round($rowListHor->MTDThisYearRevenue/100000,2),round($rowListHor->QTYThisYearRevenue/100000,2),round($rowListHor->YTDThisYearRevenue/100000,2));

$rowListHorLastYearRevenue =	array(round($rowListHor->LastYearConfirmandTendRevenue/100000,2),round($rowListHor->MTDLastYearRevenue/100000,2),round($rowListHor->QTYLastYearRevenue/100000,2),round($rowListHor->YTDLastYearRevenue/100000,2));

$rowListHorName =	array('Today','MTD','QTD','YTD');

//	array($rowListHor->MTDThisYearRoomNights,);

//==============================Booking CompleteChar==horizontalBar end ====================================================

//===========================BOOKING Through  Chart START==================================
 $BookingThroughNameArray=array();
	$BookingThroughListArray=array();
	$BookingThroughListLastYearArray=array();
   $sqlBookingThrough = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.booking_hrough,`".TBL_COMPANY."`.id_default_group,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_ORDERS."`.booking_hrough";
       
    
    $resBookingThrough = mysqli_query($connNew,$sqlBookingThrough);
	while($rowBookingThrough  = mysqli_fetch_object($resBookingThrough)){

	//$BookingThroughName 	= selectColumn(TBL_BOOKINGTHROUGH_MASTER,'name'," WHERE   id='".$rowBookingThrough->booking_hrough."'  AND id_shop= '".$_SESSION['shop']."' AND status='1'  ");
	$rowBookingThroughValue = $rowBookingThrough->newConfirmed+$rowBookingThrough->newTentative;
	
	array_push($BookingThroughNameArray,$BookingThroughName==''?'0':strtoupper($BookingThroughName));
	array_push($BookingThroughListArray,$rowBookingThroughValue==''?'0':$rowBookingThroughValue);
	
	
	}
	if(empty($BookingThroughNameArray)) {
	    array_push($BookingThroughNameArray,'0');
	}
		if(empty($BookingThroughListArray)) {
	    array_push($BookingThroughListArray,'0');
	}
$BookingThroughListLastYearArray=array();
	$sqlBookingThroughLastYear = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.booking_hrough,`".TBL_COMPANY."`.id_default_group,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       ".$cond."  ".$allUser.$condTeamGroup." GROUP BY `".TBL_ORDERS."`.booking_hrough";
       
      
    $resBookingThroughLastYear = mysqli_query($connNew,$sqlBookingThroughLastYear);
	while($rowBookingThroughLastYear  = mysqli_fetch_object($resBookingThroughLastYear)){


	$rowBookingThroughLastYearValue = $rowBookingThroughLastYear->newConfirmed+$rowBookingThroughLastYear->newTentative;
	
	
	array_push($BookingThroughListLastYearArray,$rowBookingThroughLastYearValue==''?'0':$rowBookingThroughLastYearValue);
	
	
	}
		if(empty($BookingThroughListLastYearArray)) {
	    array_push($BookingThroughListLastYearArray,'0');
	}

//===========================BOOKING Through  Chart END====================================	


//=============================
$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;
$returnData['totalDaysGoneYtd']=$totalDaysGoneYtd;

$returnData['stacked']=$stackedArr;
$returnData['mtdThisVal']=$mtdThisValues;
$returnData['mtdLastVal']=$mtdLastValues;
$returnData['mtdThisAllHotelValues']=$mtdThisAllHotelValues;
$returnData['ytdAllHotelValues']=$ytdAllHotelValues;




$returnData['graphotelName']=$graphotelName;

$returnData['ytdPrevYearAllHotelValue']=$ytdPrevYearAllHotelValue;
$returnData['ytdAchievedAllHotelValue']=$ytdAchievedAllHotelValue;

$returnData['MtdRevenueAllHotelValue']=$MtdRevenueAllHotelValue;
$returnData['ytdRevenueAllHotelValue']=$ytdRevenueAllHotelValue;

$returnData['ytdPrevYearRevenuAllHotelLastYearValue']=$ytdPrevYearRevenuAllHotelLastYearValue;
$returnData['ytdRevenueAllHotelThisYearValue']=$ytdRevenueAllHotelThisYearValue;

$returnData['budgetVal']=$budgetValues;

$returnData['executives']=$exeNameArr;

$returnData['ytdLastVal']=$ytdLastValues;
$returnData['ytdThisVal']=$ytdThisValues;

$returnData['mtdVisits']=$mtdVisits;
$returnData['mtdRoomRevenue']=$mtdRoomRevenue;
$returnData['ytdRoomRevenue']=$ytdRoomRevenue;

$returnData['mtdTotalExpense']=$mtdTotalExpense;

$returnData['ytdVisits']=$ytdVisits;
$returnData['ytdRateLetters']=$ytdRateLetters;
$returnData['ytdTotalExpense']=$ytdTotalExpense;
$returnData['reportPeriod']=$reportPeriod;
$returnData['datePeriod']=$datePeriod;

$returnData['monthNameData']=$monthNameData;

$returnData['MonthWiseRoomNightsData']=$MonthWiseRoomNightsData;
$returnData['MonthWiseRoomNightsLastYearData']=$MonthWiseRoomNightsLastYearData;
$returnData['MonthWiseRevenueCurrentYearData']=$MonthWiseRevenueCurrentYearData;
$returnData['ytdPrevYearRevenueData']=$ytdPrevYearRevenueData;

$returnData['mtdThisAllHotelValuesMat']=$mtdThisAllHotelValuesMAT;
$returnData['ytdAllHotelValuesMat']=$ytdAllHotelValuesMAT; 

$returnData['MonthWiseRevenueCurrentYearDataMat']=$MonthWiseRevenueCurrentYearDataMAT;
$returnData['ytdPrevYearRevenueDataMat']=$ytdPrevYearRevenueDataMAT;
$returnData['mtdRoomRevenueArr']=$mtdRoomRevenueArr;

$returnData['OfferNameArray']=$OfferNameArray;
$returnData['rowOfferListArray']=$rowOfferListArray;
$returnData['mtdRoomRevenueLastYearArr']=$mtdRoomRevenueLastYearArr;
$returnData['CompanyGroupNameArray']=$CompanyGroupNameArray;
$returnData['CompanyGroupListArray']=$CompanyGroupListArray;
$returnData['CompanyGroupListLastYearArray']=$CompanyGroupListLastYearArray;
$returnData['SegmentWiseListLastYearArray']=$SegmentWiseListLastYearArray;


$returnData['mtdThisCustomeReportValues']=$mtdThisCustomeReportValues;
$returnData['mtdRoomCustomeReportRevenue']=	$mtdRoomCustomeReportRevenue;
$returnData['mtdThisCustomeLastYearReportValues']=	$mtdThisCustomeLastYearReportValues;
$returnData['mtdRoomCustomeLastYearReportRevenue']=	   $mtdRoomCustomeLastYearReportRevenue;


$returnData['BookingThroughNameArray']=	$BookingThroughNameArray;
$returnData['BookingThroughCurrentYearValue']=	$BookingThroughListArray;
$returnData['rowBookingThroughLastYearValue']=	   $BookingThroughListLastYearArray;
$returnData['horizontalBarThisYear']=	   $rowListHorThisYear;
$returnData['horizontalBarLastYear']=	   $rowListHorLastYear;

$returnData['horizontalBarThisYearRevenue']=	   $rowListHorThisYearRevenue;
$returnData['horizontalBarLastYearRevenue']=	   $rowListHorLastYearRevenue;

$returnData['horizontalBarName']=	   $rowListHorName;
$returnData['monthNameDataQuarterly']=$monthNameDataQuarterly;

$returnData['MonthWiseRoomNightsCurrentYearQuarterly']=$MonthWiseRoomNightsCurrentYearQuarterly;
$returnData['MonthWiseRevenueCurrentYearQuarterly']=$MonthWiseRevenueCurrentYearQuarterly;
$returnData['ytdPrevYearRoomNightsQuarterly']=$ytdPrevYearRoomNightsQuarterly;
$returnData['ytdPrevYearRevenueQuarterly']= $ytdPrevYearRevenueQuarterly;

$returnData['MonthWiseRoomNightsCurrentYearHalfYear']=$MonthWiseRoomNightsCurrentYearHalfYear;
$returnData['MonthWiseRevenueCurrentYearHalfYear']= $MonthWiseRevenueCurrentYearHalfYear;
$returnData['ytdPrevYearRoomNightsHalfYear']=  $ytdPrevYearRoomNightsHalfYear;
$returnData['ytdPrevYearRevenueHalfYear']= $ytdPrevYearRevenueHalfYear;
$returnData['monthNameDataHalfYear']=$monthNameDataHalfYear =array('H1','H2');
$returnData['CYLable']= $_POST['CurrentFinancialYear'];$FinacialYearFrom.'-'.$FinacialYearTo;
$returnData['LYLable']= $_POST['CompareFinancialYear'];$FinacialCompareYearFrom.'-'.$FinacialCompareYearTo;
$mtdThisValuesAll=array();
$lable='All';
array_push($mtdThisValuesAll,$lable);
$returnData['CustomeReportValuesName']=$mtdThisValuesAll;

echo json_encode($returnData);


 