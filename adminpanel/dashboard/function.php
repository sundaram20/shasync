<?php //include_once("../../config/auto_loader.php"); ?>
<?php 


function tableViewfunction($Report_period,$Report_id_hotel,$Report_id_group_master,$Report_reportType,$Report_viewMonthwise,$Report_summaryReportType,$CronSet){
global $connNew;
	
$PeriodDateArray	=	explode('to',$Report_period);
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
if($Report_reportType==1){	
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
 if($Report_id_hotel>0){
	
	$cond = ' AND id="'.$Report_id_hotel.'"   ';
	//$graphotelName='All Hotel';
	}else{
		//$cond = ' AND id="'.$Report_id_hotel.'" order by name LIMIT 0,5';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       
	$reservationTable =TBL_BE_RESERVATION_QUERY;
if($Report_id_hotel>0){
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
		$id_teams=$Report_id_hotel;
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($Report_id_hotel==0){ 
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
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$Report_id_hotel.", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$Report_id_hotel."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
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
if($Report_reportType==1){
    $ReportTypeMainTitle ='PICKUP ';
}
if($Report_reportType==2){
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
	if($_REQUEST['search_name'] != ''){
		$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
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
	if($_REQUEST['lunch_booking'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`type` = 'L'";
	}

	if($_REQUEST['id_executive'] != ''){
		//$id_executive = implode(',',$_REQUEST['id_executive']);
			//$cond .= " AND ".TBL_USERS.".`id` in (".$id_executive.")";
	}
	if($Report_id_group_master != '' && $Report_id_group_master != '0' && $Report_id_group_master != '10000' ){
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
	}elseif($Report_id_group_master == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status=1 ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
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
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
	
$reportArray=array();	


  
  
 if(($_REQUEST['pdf']==1 && $Report_reportType==1) || ($Report_summaryReportType == '7' && $Report_reportType==1)){//Team Wise  Summary PDF reportType Pickup Report
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       
      
       ".$cond." ".$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
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
	    
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    array_push($mtdThisCancelled,($rowList->newCancelled==''?0:round($rowList->newCancelled)));
	    
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['CancelledRoomNights']=$rowList->newCancelled==''?0:round($rowList->newCancelled);
	    }
	}  
  } 
  
 if(($_REQUEST['pdf']==1 && $Report_reportType==2) || ($Report_summaryReportType == '7' && $Report_reportType==2)){//Team Wise  Summary PDF reportType BOB Report
      
     $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newCancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."'))".$condhotelAccess." ".$condBOB." 
GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
       
         
     
      //echo $sql;
      //die;
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['roomnights']       = $newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['confimed_revenue'] = $rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['CancelledRoomNights'] = $rowList->newCancelled==''?0:round($rowList->newCancelled);
	    }
	}  
  }  
  
  
  
 // echo '<pre>';print_r($reportArray);
  
//die;
   
if(($_REQUEST['pdf']==1 && $Report_reportType==1) || ($Report_summaryReportType == '1' && $Report_reportType==1)){//Executivewise  Summary PDF
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       
       
       ".$cond." ".$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_ORDERS."`.id_company Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	     $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	  
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    }
	}  
  } 
if(($_REQUEST['pdf']==1 && $Report_reportType==2) || ($Report_summaryReportType == '1' && $Report_reportType==2)){//Executivewise  Summary PDF Bob
      
    $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newCancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id  and `mst_team`.id_shop='".$_SESSION['shop']."'
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group

INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$condhotelAccess." ".$condBOB."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_ORDERS."`.id_company Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
       //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	     $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	  
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  } 


 if(($_REQUEST['pdf']==1 && $Report_reportType==1) || ($Report_summaryReportType == '2' && $Report_reportType==1)){//Hotel Wise  Summary PDF PICKUP Report
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       
       
       ".$cond." ".$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY `".TBL_GROUP_MASTER."`.display_order, `mst_team`.id_group,`fs_users`.myownteam_id";
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    }
	}  
  }    
    
 if(($_REQUEST['pdf']==1 && $Report_reportType==2) || ($Report_summaryReportType == '2' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
     $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newCancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$condhotelAccess." ".$condBOB."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY `".TBL_GROUP_MASTER."`.display_order,`fs_users`.myownteam_id";
       
       
      
     // echo $sql;
      //die;
     
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  } 
    
      
 
  
 
 
 
  
		$stackedDataSet['label']=$rowExe->name;
		
		$stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
		
		//array_push($exeNameArr,ucwords(strtolower($rowExe->name)));
		array_push($stackedArr,$stackedDataSet);

		
		$budget='';
		
		$visitMtd='';
		array_push($mtdLastValues, ($prevYearRoomNightsMtd==''?0:$prevYearRoomNightsMtd));
		array_push($mtdThisValues, ($ThisMonthRoomNightsMtd==''?0:$ThisMonthRoomNightsMtd));
		
		array_push($budgetValues, ($budget==''?0:$budget));
		

		array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
		array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

		array_push($mtdVisits,$visitMtd);
		//array_push($mtdRoomRevenue,($RevenueMtd==''?0:round($RevenueMtd)));
		array_push($ytdRoomRevenue,($ytdRevenue==''?0:round($ytdRevenue)));

		

	
//===========================Segment Wise Chart END==================================	

	if(empty($mtdRoomRevenue)) {
	    array_push($mtdRoomRevenue,'null');
	}
	if(empty($mtdThisValues)) {
	    array_push($mtdThisValues,'0');
	}

	
//======================ExecutiveWise  Summary Sorting Start
//======================ExecutiveWise  Summary Sorting Start
$HotelwisePerformanceSummary=array();
foreach($exeNameArr as $key=>$value){
	
	$HotelwisePerformanceSummary[$key]['Hotel']=$value;
	$HotelwisePerformanceSummary[$key]['RoomNights']=$mtdThisValues[$key];
	$HotelwisePerformanceSummary[$key]['RoomRevenue']=$mtdRoomRevenue[$key];
	}

$sort = array();
foreach($HotelwisePerformanceSummary as $k=>$v) {
$sort['Hotel'][$k] = $v['Hotel'];
$sort['RoomNights'][$k] = $v['RoomNights'];
}
# sort by event_type desc and then title asc
array_multisort($sort['RoomNights'], SORT_DESC, $sort['Hotel'], SORT_ASC,$HotelwisePerformanceSummary);
$mtdRoomRevenue='';
$mtdRoomRevenue=array();

foreach($HotelwisePerformanceSummary as $fkey=>$fvalue){	
	$exeNameArr[$fkey]=$HotelwisePerformanceSummary[$fkey]['Hotel'];
	$mtdThisValues[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomNights'];
	$mtdRoomRevenue[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomRevenue'];	
	}
//======================ExecutiveWise  Summary Sorting EnD
//======================ExecutiveWise  Summary Sorting EnD

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
$returnData['SummaryHedding']=$SummaryHedding;
$returnData['TaleName']=$TaleName;

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

$returnData['CompanyGroupNameArray']=$CompanyGroupNameArray;
$returnData['CompanyGroupListArray']=$CompanyGroupListArray;
$returnData['CompanyGroupListLastYearArray']=$CompanyGroupListLastYearArray;
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
 
$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = mysqli_fetch_object($resShop);
$logo	=	$rowShop->image;
//$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
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

foreach($reportArray as $maintitle=>$mainDatalist){
    
    $contentTeam ='<table class="table table-striped text-center">';
	$contentTeam .='<tr><th colspan="4" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle.' Breakup For Period '.$reportPeriod.'</b></th></tr>';
    $contentTeam .='<tr><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue(Lacs)</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	 $GroupArray=array(); 
    foreach($mainDatalist as $teamGroup=>$subDataList1){
        
//echo $teamGroup;
if($teamGroup!='name'){
 $contentTeam .='<tr style="vertical-align:central;"><th colspan="4" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Group '.$teamGroup.'</b></th></tr>';
}
 foreach($subDataList1 as $TeamName=> $subDataList){
     $contentTeamBody='';
     
     
     //Sorting Order Number Height to Low        
foreach($subDataList as   $key=> $Data){ 
$roomnights[$key] =$Data['roomnights'];
$confimed_revenue[$key] = $Data['confimed_revenue'];

}
$roomnights  = array_column($subDataList, 'roomnights');
$confimed_revenue = array_column($subDataList, 'confimed_revenue');
array_multisort($roomnights, SORT_DESC, $confimed_revenue, SORT_ASC, $subDataList);
//Sorting Order Number Height to Low


      foreach($subDataList as $list=> $DataList){
           if($DataList['roomnights']>0){
                 if($DataList['confimed_revenue']>0){
						$mtrArr =($DataList['confimed_revenue']/$DataList['roomnights']);
						}else{
							$mtrArr =0;
							}
                        
                        $TotalRoomNight+=$DataList['roomnights'];
                        $TotalConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        $TotalTeamRoomNight+=$DataList['roomnights'];
                        $TotalTeamConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        $TotalCancelledRoomNight+=$DataList['CancelledRoomNights'];
                        
                        if (strpos($list, 'empty_') !== false) {
                        $name= '';
                        }else{$name=$list;}
                        
                        $contentTeamBody .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td><td style="text-align:center;">'.$DataList['roomnights'].'</td><td style="text-align:center;">'.round($DataList['confimed_revenue']/100000,2).'</td><td style="text-align:center;">'.round($mtrArr).'</td></tr>';
                    }
            
            }
            //Team Total
            $SumTeamTotalArray= round($TotalTeamConfimedRevenue/$TotalTeamRoomNight);
            //$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#5CB4E8;">'.ucwords($TeamName).' Total</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamConfimedRevenue.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$SumTeamTotalArray.'</td></tr>';
            if($TotalTeamRoomNight>0){
                $contentTeam .='<tr><th  style="vertical-align:central;text-align:Left;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.strtoupper($TeamName).'</b></th><th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .$TotalTeamRoomNight.'</b></th><th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($TotalTeamConfimedRevenue/100000,2).'</b></th><th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$SumTeamTotalArray.'</b></th></tr>';
            }
            $contentTeam .=$contentTeamBody;
            $TotalTeamRoomNight='';
            $TotalTeamConfimedRevenue='';
            $SumTeamTotalArray='';
        
        
        }  
    //Group Total
    $SumTotalArray= round($TotalConfimedRevenue/$TotalRoomNight);
    if($teamGroup!='name'){$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">'.ucwords($teamGroup).' Total</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenue/100000,2).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalArray.'</td></tr>';
    }
    $GroupArray[$teamGroup]['RoomNights']=$TotalRoomNight;
    $GroupArray[$teamGroup]['CancelledRoomNights']=$TotalCancelledRoomNight;
    
    $GroupArray[$teamGroup]['RoomRevenue']=$TotalConfimedRevenue;
    $GroupArray[$teamGroup]['Arr']=$SumTotalArray;
    
    $SumTotalArray='';
    $TotalConfimedRevenue='';
    $TotalRoomNight='';
    $TotalCancelledRoomNight='';
    }
    
   
     
     $contentTeam .= '</table><br/><br/>';
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
         if($maintitle=='Team Wise'){
             if($UnitValueIsWithout==1){
        	  //Office Team Wise For Period Start  
        	 $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="6" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .=    '<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Cancelled Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">% of Contribution</th></tr>';
                 $TotalTeamWiseRoomNightContribution='';
                 
                 
foreach($GroupArray as   $key=> $Data){ 
$RoomNights[$key] =$Data['RoomNights'];
$RoomRevenue[$key] = $Data['RoomRevenue'];
$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($GroupArray, 'RoomNights');
$RoomRevenue = array_column($GroupArray, 'RoomRevenue');
array_multisort($RoomNights, SORT_DESC, $RoomRevenue, SORT_ASC, $GroupArray);       
                     
      
      
      
       foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)!='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['RoomNights'];
                     }
                 }
                 
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)!='UNIT'){
                    $contentGroup .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td><td style="text-align:center;">'.$GroupNameArray['CancelledRoomNights'].'</td><td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td><td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td><td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td><td style="text-align:center;">'.round(($GroupNameArray['RoomNights']/$TotalTeamWiseRoomNightContribution)*100,2).'</td></tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    $TotalTeamWisCancelledRoomNights+=$GroupNameArray['CancelledRoomNights'];
                    
                    $TotalTeamWiseRoomNight1+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue1+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWisCancelledRoomNights1+=$GroupNameArray['CancelledRoomNights'];
                     }
                 }
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">Total </td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWisCancelledRoomNights.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.(($TotalTeamWiseRoomNight/$TotalTeamWiseRoomNightContribution)*100).'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             $TotalTeamWisCancelledRoomNights='';
             
             $contentGroup .= '</table>';
         }
             
             if($UnitValueIs==1){
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="5" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>UNIT '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .=    '<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Cancelled Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th></tr>';
                 $TotalTeamWiseRoomNightContribution='';
                 
                 
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['RoomNights'];
                    
                     }
                 }
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                    $contentGroup .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td><td style="text-align:center;">'.$GroupNameArray['CancelledRoomNights'].'</td><td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td><td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td><td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td></tr>';
                    $TotalTeamWiseRoomNight2+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue2+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
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
         $content .=$contentTeam;
         
         
         $contentGroup='';
         $contentTeam='';
     //Office Team Wise For Period End
     
}

if($_REQUEST['pdf']==1 && $CronSet==0){

    $dompdf = new DOMPDF();


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
}elseif($CronSet==1){
    $Filename='PickupReport_'.date("Y-m-d");
   // echo $content;die;
   pdfGeneratorAttach($content, $Filename);
    
}else{
echo $content;
//echo json_encode($returnData);
}
}







function CompareViewfunction($Report_period,$Report_id_hotel,$Report_id_group_master,$Report_reportType,$Report_viewMonthwise,$Report_summaryReportType){
global $connNew;
$PeriodDateArray	=	explode('to',$Report_period);
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
if($Report_reportType==1){	
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
 if($Report_id_hotel>0){
	
	$cond = ' AND id="'.$Report_id_hotel.'"   ';
	//$graphotelName='All Hotel';
	}else{
		//$cond = ' AND id="'.$Report_id_hotel.'" order by name LIMIT 0,5';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       
	$reservationTable =TBL_BE_RESERVATION_QUERY;
if($Report_id_hotel>0){
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
		$id_teams=$Report_id_hotel;
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($Report_id_hotel==0){ 
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
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$Report_id_hotel.", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$Report_id_hotel."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser ="  AND ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if( $team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    //$cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
	}
if($Report_reportType==1){
    $ReportTypeMainTitle ='PICKUP ';
}
if($Report_reportType==2){
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
	if($_REQUEST['search_name'] != ''){
		$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
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
	if($_REQUEST['lunch_booking'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`type` = 'L'";
	}
	if($_REQUEST['id_executive'] != ''){
		//$id_executive = implode(',',$_REQUEST['id_executive']);
			//$cond .= " AND ".TBL_USERS.".`id` in (".$id_executive.")";
	}
    	if($Report_id_group_master != '' && $Report_id_group_master != '0' && $Report_id_group_master != '10000' ){
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
	}elseif($Report_id_group_master == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status=1 ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
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
		    	$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
$reportArray=array();
//LASTYEAR SQL START >>================================================================================================================	
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' )){//Team Wise  Summary PDF reportType Pickup Report
$GroupOrder =  "  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";

}
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' )){//Executivewise  Summary PDF PICK ASE
$GroupOrder = "  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_ORDERS."`.id_hotel Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
}
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' )){//Hotel Wise  Summary PDF PICKUP Report
$GroupOrder = "  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
}


$FromLastYearDate   =   date('Y-m-d',strtotime('-1 years',strtotime($from_book)));
$ToLastYearDate   =   date('Y-m-d',strtotime('-1 years',strtotime($to_book)));

if($Report_reportType==1){
$lastYearPickUpsql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
            LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
            LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
            
            LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
            LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
             
            LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       
            ".$cond.$allUser.$GroupOrder ." Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
}            
            
 if($Report_reportType==2){           
   $lastYearPickUpsql ="SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 

 ".$cond.$allUser1.$GroupOrder;
//where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".$ToLastYearDate."' And '".$ToLastYearDate."')) $condhotelAccess 
//GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";

//$condhotelAccess";
          
 }       
            
            
            
       
      //echo $lastYearPickUpsql;
      //die;
       
       $resultlastYearList = mysqli_query($connNew,$lastYearPickUpsql);
       $empty7=0;
	while($rowlastYearList = mysqli_fetch_object($resultlastYearList)){
	   // print_r($rowlastYearList);
	   // echo '<br><br><br>';
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' )){   //Team Wise  Summary PDF reportType Pickup Report

    $companyname= strtolower(selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowlastYearList->ids_team."'"));
    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowlastYearList->id_default_group."'");
    $arrayNameSet   =   'Team Wise';
    $SummaryHedding='Team Wise ';
    $TaleName='Team Wise Source';
}
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' )){   //Executivewise  Summary PDF PICK ASE

    $companyname =$rowlastYearList->name_executive;
    $BusinessSourceName= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowlastYearList->id_hotel.' ');
    //$BusinessSourceName= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowlastYearList->id_company."'");
    $arrayNameSet   =   'Executivewise';
    	
	$SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       
}
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' )){   //Hotel Wise  Summary PDF PICKUP Report

    $companyname= strtolower(selectColumn(TBL_HOTELS,'name','WHERE id='.$rowlastYearList->id_hotel.' '));
    $BusinessSourceName=  $rowlastYearList->name_executive;
    $arrayNameSet   =   'Hotelwise';
    $SummaryHedding='Hotel Wise ';
    $TaleName='Hotel Wise Source';
}	    
	  
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowlastYearList->id_group."'");
	   
	    
	     
	     if($Report_reportType==1){
            //$ReportTypeMainTitle ='PICKUP ';
             $newConfirmednewTentative=($rowlastYearList->newConfirmed+$rowlastYearList->newTentative);
            }
        if($Report_reportType==2){
            //$ReportTypeMainTitle ='BOB ';
            $newConfirmednewTentative=($rowlastYearList->newConfirmed);
            }
	        if($newConfirmednewTentative>0){
        	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
        	    array_push($mtdRoomRevenue,($rowlastYearList->confimed_revenue==''?0:round($rowlastYearList->confimed_revenue)));
        	    
        	    $emptytext7 ='empty_'.$empty7++;
        	    $reportArray[$arrayNameSet][$GroupName][($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['lastYearroomnights']    =   $newConfirmednewTentative==''?'0':$newConfirmednewTentative;
        	    $reportArray[$arrayNameSet][$GroupName][($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['lastYearconfimed_revenue']     =   $rowlastYearList->confimed_revenue==''?0:round($rowlastYearList->confimed_revenue);
    	   }
	} 
	
	
	
	

	
	

 
	
	
	
	//echo '<pre>';print_r($reportArray);
    //die;

//LASTYEAR SQL END >>  ================================================================================================================
	
	
	

	
	
	
	
	
	
	

 if(($_REQUEST['pdf']==1 && $Report_reportType==1 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' && $Report_reportType==1)){//Team Wise  Summary PDF reportType Pickup Report
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       ".$cond.$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    
	    
	   // print_r($rowList);
	    //echo '<br><br><br>';
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	    
	    
	    
	   
             $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
         $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    
	    
	        if($newConfirmednewTentative>0){
        	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
        	     array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
        	    
        	    $emptytext7 ='empty_'.$empty7++;
        	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
        	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
    	   }
	}  
  } 
  //	echo '<pre>';print_r($reportArray);
 if(($_REQUEST['pdf']==1 && $Report_reportType==2 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' && $Report_reportType==2)){//Team Wise  Summary PDF reportType BOB Report
      
     $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$condhotelAccess." ".$condBOB."   GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
       
         
     
      //echo $sql;
      //die;
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	   
           $newConfirmednewTentative=($rowList->newConfirmed);
           
	    
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['roomnights']       = $newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['confimed_revenue'] = $rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  }  
  
  
  
 //echo '<pre>';print_r($reportArray);
  

  
  
   
if(($_REQUEST['pdf']==1 && $Report_reportType==1 && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' && $Report_reportType==1)){//Executivewise  Summary PDF PICK ASE
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       
       
       ".$cond.$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_ORDERS."`.id_hotel Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	     //$companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	  
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	     array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    }
	}  
  } 
if(($_REQUEST['pdf']==1 && $Report_reportType==2 && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' && $Report_reportType==2)){//Executivewise  Summary PDF Bob
      
    $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id  and `mst_team`.id_shop='".$_SESSION['shop']."'
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$condhotelAccess." ".$condBOB."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_ORDERS."`.id_hotel Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
       //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    // $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	  $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");;
	    
	   
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Executivewise'][$GroupName][$rowList->name_executive][$companyname]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  } 

if(($_REQUEST['pdf']==1 && $Report_reportType==1 && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' && $Report_reportType==1)){//Hotel Wise  Summary PDF PICKUP Report
      
    $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
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
       
       
       ".$cond.$allUser."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
      //echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue+$rowList->tentative_revenue);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    }
	}  
  }    
    
 if(($_REQUEST['pdf']==1 && $Report_reportType==2 && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
     $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$condhotelAccess." ".$condBOB."  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY `fs_users`.myownteam_id";
       
       
      
     // echo $sql;
      //die;
     
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->ids_team."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Hotelwise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  } 
    
      

  
 
 
 
  
		 $stackedDataSet['label']=$rowExe->name;
		
		$stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
		
		//array_push($exeNameArr,ucwords(strtolower($rowExe->name)));
		array_push($stackedArr,$stackedDataSet);

		
		$budget='';
		
		$visitMtd='';
		array_push($mtdLastValues, ($prevYearRoomNightsMtd==''?0:$prevYearRoomNightsMtd));
		array_push($mtdThisValues, ($ThisMonthRoomNightsMtd==''?0:$ThisMonthRoomNightsMtd));
		
		array_push($budgetValues, ($budget==''?0:$budget));
		

		array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
		array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

		array_push($mtdVisits,$visitMtd);
		//array_push($mtdRoomRevenue,($RevenueMtd==''?0:round($RevenueMtd)));
		array_push($ytdRoomRevenue,($ytdRevenue==''?0:round($ytdRevenue)));

		

	
//===========================Segment Wise Chart END==================================	

	if(empty($mtdRoomRevenue)) {
	    array_push($mtdRoomRevenue,'null');
	}
	if(empty($mtdThisValues)) {
	    array_push($mtdThisValues,'0');
	}

	
//======================ExecutiveWise  Summary Sorting Start
//======================ExecutiveWise  Summary Sorting Start
$HotelwisePerformanceSummary=array();
foreach($exeNameArr as $key=>$value){
	
	$HotelwisePerformanceSummary[$key]['Hotel']=$value;
	$HotelwisePerformanceSummary[$key]['RoomNights']=$mtdThisValues[$key];
	$HotelwisePerformanceSummary[$key]['RoomRevenue']=$mtdRoomRevenue[$key];
	}

$sort = array();
foreach($HotelwisePerformanceSummary as $k=>$v) {
$sort['Hotel'][$k] = $v['Hotel'];
$sort['RoomNights'][$k] = $v['RoomNights'];
}
# sort by event_type desc and then title asc
array_multisort($sort['RoomNights'], SORT_DESC, $sort['Hotel'], SORT_ASC,$HotelwisePerformanceSummary);
$mtdRoomRevenue='';
$mtdRoomRevenue=array();

foreach($HotelwisePerformanceSummary as $fkey=>$fvalue){	
	$exeNameArr[$fkey]=$HotelwisePerformanceSummary[$fkey]['Hotel'];
	$mtdThisValues[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomNights'];
	$mtdRoomRevenue[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomRevenue'];	
	}
//======================ExecutiveWise  Summary Sorting EnD
//======================ExecutiveWise  Summary Sorting EnD

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
$returnData['SummaryHedding']=$SummaryHedding;
$returnData['TaleName']=$TaleName;

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

$returnData['CompanyGroupNameArray']=$CompanyGroupNameArray;
$returnData['CompanyGroupListArray']=$CompanyGroupListArray;
$returnData['CompanyGroupListLastYearArray']=$CompanyGroupListLastYearArray;
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
 
$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = mysqli_fetch_object($resShop);
$logo	=	$rowShop->image;
//$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
if($_REQUEST['pdf']==1){
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="./../../../uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	   $content .= '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="7" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>COMPARE '.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; 
}
foreach($reportArray as $maintitle=>$mainDatalist){
    
    $contentTeam ='<table class="table table-striped text-center">';
	$contentTeam .='<tr><th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' Compare '.$maintitle.'  Breakup For Period '.$reportPeriod.'</b></th></tr>';
    $contentTeam .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	 $GroupArray=array(); 
    foreach($mainDatalist as $teamGroup=>$subDataList1){
        
//echo $teamGroup;
if($teamGroup!='name'){
 $contentTeam .='<tr style="vertical-align:central;"><th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Group '.$teamGroup.'</b></th></tr>';
  $contentTeam .='<tr style="vertical-align:central;">
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
  
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Last Year </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Current Year </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Last Year (Lacs)</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Current Year (Lacs) </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
  
  </tr>';
}
 foreach($subDataList1 as $TeamName=> $subDataList){
     $contentTeamBody='';
     
     
     //Sorting Order Number Height to Low        
foreach($subDataList as   $key=> $Data){ 
    //print_r($Data);
$roomnights[$key] =$Data['roomnights'];
$confimed_revenue[$key] = $Data['confimed_revenue'];
//echo '<br>'.$confimed_revenue[$key] = $Data['lastYearroomnights'];
//echo $confimed_revenue[$key] = $Data['lastYearconfimed_revenue'];

}
$roomnights  = array_column($subDataList, 'roomnights');
$confimed_revenue = array_column($subDataList, 'confimed_revenue');
array_multisort($roomnights, SORT_DESC, $confimed_revenue, SORT_ASC, $subDataList);
//Sorting Order Number Height to Low


      foreach($subDataList as $list=> $DataList){
           if($DataList['roomnights']>0 || $DataList['lastYearroomnights']>0){
                 if($DataList['confimed_revenue']>0 || $DataList['lastYearconfimed_revenue']>0){
						$mtrArr =($DataList['confimed_revenue']/$DataList['roomnights']);
						}else{
							$mtrArr =0;
							}
                        
                        $TotalRoomNight+=$DataList['roomnights'];
                        $TotalConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        $TotalTeamRoomNight+=$DataList['roomnights'];
                        $TotalTeamConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        
                        $TotalRoomNightlastYear+=$DataList['lastYearroomnights'];
                        $TotalConfimedRevenuelastYear+=$DataList['lastYearconfimed_revenue'];
                        
                        $TotalTeamRoomNightlastYear+=$DataList['lastYearroomnights'];
                        $TotalTeamConfimedRevenuelastYear+=$DataList['lastYearconfimed_revenue'];
                        
                        
                        
                        if (strpos($list, 'empty_') !== false) {
                                $name= $list;
                        }else{
                            $name=$list;}
                        //echo '<br>'.$DataList['roomnights'].'-'.$DataList['lastYearroomnights'];
                        
                        
                        $golyRowWiseRoomNights    = round((($DataList['roomnights']-$DataList['lastYearroomnights'])/$DataList['roomnights']) *100,2);
                        $golyRowWiseConfimedRevenue    = round((($DataList['confimed_revenue']-$DataList['lastYearconfimed_revenue'])/$DataList['confimed_revenue']) *100,2);
                        $golyRowWiseConfimedRevenuevColor = $golyRowWiseConfimedRevenue>0?"":"color:red;";
                        
                        $golyRowWiseRoomNightsColor = $golyRowWiseRoomNights>0?"":"color:red;";
                        $contentTeamBody .='<tr >';
                        $contentTeamBody .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                        
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['lastYearroomnights']>0 ? $DataList['lastYearroomnights']:'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['roomnights']>0 ?$DataList['roomnights']:'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;'.$golyRowWiseRoomNightsColor1.'">'.$golyRowWiseRoomNights.'</td>';
                        
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['lastYearconfimed_revenue']>0?round($DataList['lastYearconfimed_revenue']/100000,2):'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['confimed_revenue']>0?round($DataList['confimed_revenue']/100000,2):'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;'.$golyRowWiseConfimedRevenuevColor1.'">'.$golyRowWiseConfimedRevenue.'</td>';
                      
                        $contentTeamBody .='<td style="text-align:center;">'.round($mtrArr).'</td>';
                        
                        $contentTeamBody .='</tr>';
                    }
            
            }
            //Team Total
            $SumTeamTotalArray= round($TotalTeamConfimedRevenue/$TotalTeamRoomNight);
            $SumTeamTotallastYearArray= round($TotalTeamConfimedRevenuelastYear/$TotalTeamRoomNightlastYear);
            
            
            $SumTeamTotalgolyRoomNights=round((($TotalTeamRoomNight-$TotalTeamRoomNightlastYear)/$TotalTeamRoomNight) *100,2);
            $SumTeamTotalgolyConfimedRevenue=round((($TotalTeamConfimedRevenue-$TotalTeamConfimedRevenuelastYear)/$TotalTeamConfimedRevenue) *100,2);
            
            $SumTeamTotalgolyConfimedRevenueColor=$SumTeamTotalgolyConfimedRevenue>=0?"":"color:red;";
           $SumTeamTotalgolyRoomNightsColor=$SumTeamTotalgolyRoomNights>=0?"":"color:red;";
            //$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#5CB4E8;">'.ucwords($TeamName).' Total</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamConfimedRevenue.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$SumTeamTotalArray.'</td></tr>';
            if($TotalTeamRoomNight>0){
                $contentTeam .='<tr>
                <th  style="vertical-align:central;text-align:Left;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.strtoupper($TeamName).'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.$TotalTeamRoomNightlastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .$TotalTeamRoomNight.'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; font-size:12px !important;'.$SumTeamTotalgolyRoomNightsColor.'"><b>'.$SumTeamTotalgolyRoomNights.'</b></th>
                
               
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.round($TotalTeamConfimedRevenuelastYear/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($TotalTeamConfimedRevenue/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; font-size:12px !important;'.$SumTeamTotalgolyConfimedRevenueColor.'"><b>'.$SumTeamTotalgolyConfimedRevenue.'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$SumTeamTotalArray.'</b></th>
                
                
                </tr>';
            }
            $contentTeam .=$contentTeamBody;
            $TotalTeamRoomNight='';
            $TotalTeamConfimedRevenue='';
            $SumTeamTotalArray='';
            $SumTeamTotallastYearArray='';
            $TotalTeamRoomNightlastYear='';
            $TotalTeamConfimedRevenuelastYear='';
            
        
        
        }  
    //Group Total
    $SumTotalArray= round($TotalConfimedRevenue/$TotalRoomNight);
    $SumTotallastYearArray= round($TotalConfimedRevenuelastYear/$TotalRoomNightlastYear);
    
    $SumTotalGolyRoomNights=round((($TotalRoomNight-$TotalRoomNightlastYear)/$TotalRoomNight) *100,2);
    $SumTotalGolyConfimedRevenue=round((($TotalConfimedRevenue-$TotalConfimedRevenuelastYear)/$TotalConfimedRevenue) *100,2);
    $SumTotalGolyConfimedRevenueColor=$SumTotalGolyConfimedRevenue>=0?"":"color:red;";
     $SumTotalGolyRoomNightsColor=$SumTotalGolyRoomNights>=0?"":"color:red;";
    if($teamGroup!='name'){$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
    <td style="text-align:center;background-color:#c2d69a;">'.ucwords($teamGroup).' Total</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNightlastYear.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNight.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyRoomNightsColor1.'">'.$SumTotalGolyRoomNights.'</td>
    
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenuelastYear/100000,2).'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenue/100000,2).'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyConfimedRevenueColor1.'">'.$SumTotalGolyConfimedRevenue.'</td>
    
    
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalArray.'</td>
    
    </tr>';
    }
    $GroupArray[$teamGroup]['RoomNights']=$TotalRoomNight;
    $GroupArray[$teamGroup]['RoomRevenue']=$TotalConfimedRevenue;
    
     $GroupArray[$teamGroup]['lastYearroomnights']=$TotalRoomNightlastYear;
    $GroupArray[$teamGroup]['lastYearconfimed_revenue']=$TotalConfimedRevenuelastYear;
    
    
    $GroupArray[$teamGroup]['Arr']=$SumTotalArray;
    
    $SumTotalArray='';
    $TotalConfimedRevenue='';
    $TotalRoomNight='';
    $TotalRoomNightlastYear='';
    $TotalConfimedRevenuelastYear='';
    $SumTotallastYearArray='';
    $TotalConfimedRevenuelastYear='';
    
    }
    
   
     
     $contentTeam .= '</table><br/><br/>';
     
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
         if($maintitle=='Team Wise'){
        	    if($UnitValueIsWithout==1){
        	        //Office Team Wise For Period Start  
        	 $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
    
    $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp</th>
             
             
             
             
             
             </tr>';
foreach($GroupArray as   $key=> $Data){ 
$RoomNights[$key] =$Data['RoomNights'];
$RoomRevenue[$key] = $Data['RoomRevenue'];
$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($GroupArray, 'RoomNights');
$RoomRevenue = array_column($GroupArray, 'RoomRevenue');
array_multisort($RoomNights, SORT_DESC, $RoomRevenue, SORT_ASC, $GroupArray);       
   
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)!='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['lastYearroomnights'];
                     }
                 }
   
   
   
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)!='UNIT'){
                     $GolyGroupRoomNights=round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['RoomNights']) *100,2);
                     $GolyGroupRoomRevenue=round((($GroupNameArray['RoomRevenue']-$GroupNameArray['lastYearconfimed_revenue'])/$GroupNameArray['RoomRevenue']) *100,2);
                     
                     $GolyGroupRoomRevenueColor=$GolyGroupRoomRevenue>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GolyGroupRoomNights.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['lastYearconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColor1.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td>';
                    
                    
                    $contentGroup .='</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearconfimed_revenue'];
                    
                  }
                 }
                 
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights=round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNight) *100,2);
              $SumTotalGolyTeamConfimedRevenue=round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenue) *100,2);
              
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalGolyTeamRoomNights.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalGolyTeamConfimedRevenue.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             //$TotalTeamWiseConfimedRevenue='';
             //$TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
        	    }
             
             if($UnitValueIs==1){
             //===================================================
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
    
    $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp</th>
             
             
             
             
             
             </tr>';
             
 
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['lastYearroomnights'];
                     }
                 }
   
   
   
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                     $GolyGroupRoomNights=round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['RoomNights']) *100,2);
                     $GolyGroupRoomRevenue=round((($GroupNameArray['RoomRevenue']-$GroupNameArray['lastYearconfimed_revenue'])/$GroupNameArray['RoomRevenue']) *100,2);
                     
                     $GolyGroupRoomRevenueColor=$GolyGroupRoomRevenue>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GolyGroupRoomNights.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['lastYearconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColor1.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td>';
                    
                    
                    $contentGroup .='</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearconfimed_revenue'];
                    
                  }
                 }
                 
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights=round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNight) *100,2);
              $SumTotalGolyTeamConfimedRevenue=round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenue) *100,2);
              
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalGolyTeamRoomNights.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalGolyTeamConfimedRevenue.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
             
             }
             
             
         }
         
         $content .=$contentGroup;
         $content .=$contentTeam;
         $contentGroup='';
         $contentTeam='';
     //Office Team Wise For Period End
     
}

if($_REQUEST['pdf']==1){
//echo $content;
//die;
    $dompdf = new DOMPDF();


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

}


?>