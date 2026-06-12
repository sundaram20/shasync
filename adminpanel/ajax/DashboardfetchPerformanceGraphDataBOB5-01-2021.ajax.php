<?php

include_once("../../config/auto_loader.php");
error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_POST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
///$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//$to = date('Y-m-d',strtotime($PeriodDateArray[1]. ' +1 day'));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
$toDate = date('Y-m-d',strtotime($PeriodDateArray[1]));
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
 $from_book=$from;$to_book=$toDate;


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
	$UserInActive	=	"  AND ( ".TBL_USERS.".status_inactive_date>='".$to."' ||  ".TBL_USERS.".status_inactive_date='0000-00-00') ";

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
		$allUser ="  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
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
		$allUser ="  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if( $team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    //$cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
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


    //Custom Report Start==============================================================================
     $sqlCustomeReport = " SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ".$allUser." AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."'".$condhotelAccess
 ;
       
       
     // echo $sql;
    //  die;
     //  
       
       $resultListCustomeReport = mysqli_query($connNew,$sqlCustomeReport);
	while($rowListCustomeReport = mysqli_fetch_object($resultListCustomeReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeReportValues2+=($rowListCustomeReport->newConfirmed+$rowListCustomeReport->newTentative);
	    $mtdRoomCustomeReportRevenue2+=($rowListCustomeReport->tarrif_price_confimed==''?0:round($rowListCustomeReport->tarrif_price_confimed));

	}
		
    $mtdThisCustomeReportValues=array();
    $mtdRoomCustomeReportRevenue=array();
    
    array_push($mtdThisCustomeReportValues,$mtdThisCustomeReportValues2);
    array_push($mtdRoomCustomeReportRevenue,$mtdRoomCustomeReportRevenue2);
    //booking _date end
    
    
    
     $sqlCustomeLastYearReport = " SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN ('".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' )) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ".$allUser." AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' And '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."'" ;
       
       
      //echo $sqlCustomeLastYearReport;
      //die;
     //  
       
       $resultListCustomeLastYearReport = mysqli_query($connNew,$sqlCustomeLastYearReport);
	while($rowListCustomeLastYearReport = mysqli_fetch_object($resultListCustomeLastYearReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeLastYearReportValues2+=($rowListCustomeLastYearReport->newConfirmed+$rowListCustomeLastYearReport->newTentative);
	    $mtdRoomCustomeLastYearReportRevenue2+=($rowListCustomeLastYearReport->tarrif_price_confimed==''?0:round($rowListCustomeLastYearReport->tarrif_price_confimed));

	}
		
    $mtdThisCustomeLastYearReportValues=array();
    $mtdRoomCustomeLastYearReportRevenue=array();
    
    array_push($mtdThisCustomeLastYearReportValues,$mtdThisCustomeLastYearReportValues2);
    array_push($mtdRoomCustomeLastYearReportRevenue,$mtdRoomCustomeLastYearReportRevenue2);
    //booking _date end
    
    
    
    //Custom Report End==============================================================================
    
    
   /* $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  ".$cond." AND  ".$allUser."  GROUP BY `fs_users`.id" ;
       */
       
        $sql	=	"SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ".$allUser." AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."' $condhotelAccess group by `fs_users`.id";
 
      
       
      //echo $sql;
     // die;
     //  
       
       $resultList = mysqli_query($connNew,$sql);
	while($rowList = mysqli_fetch_object($resultList)){
	  
	    $exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    array_push($mtdThisValues,($rowList->newConfirmed+$rowList->newTentative));
	    array_push($mtdRoomRevenue,($rowList->tarrif_price_confimed==''?0:round($rowList->tarrif_price_confimed)));
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

		


 
   //$ytdPrevYearRoomNights = selectColumn($reservationTable,'sum(room_nights)'," WHERE  MONTH(".$reportfieldVarible.") = '".$startMo."' AND YEAR(".$reportfieldVarible.") = '".$PrevstartYr."'  and id_shop='".$_SESSION['shop']."'  and payment_status=1 ".$ConnHotels." GROUP by id_offer");
	
   
  // print_r($monthNameData);
   //print_r($MonthWiseRoomNightsData);
   
   
   
   
   
   
   
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
  if($diffYr > 0){
    $diff = abs(($endMo+12-$startMo));
  }
 
 $PrevstartYr = date('Y',strtotime('-1 years',strtotime($startYr)));
 
 
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
}else {
        $checkYear=(date('Y')-1);
        
        if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
        $startYr = date($checkYear) + 1;
        $PrevstartYr = (date($checkYear));
        }
        else {
        $startYr = date($checkYear);
        $PrevstartYr = (date($checkYear)-1);
        }
    
}
	//echo $reservationTable.'sum(room_nights)'." WHERE  MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$PrevstartYr."'  and id_shop='".$_SESSION['shop']."'  and payment_status=1 ".$ConnHotels."";
	
	 //$MonthWiseRoomNightsCurrentYear =selectColumn($reservationTable,'sum(room_nights)'," WHERE MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$startYr."'    AND id_shop='".$_SESSION['shop']."'  and payment_status=1  ".$ConnHotels."");
	// $ytdPrevYearRoomNights = selectColumn($reservationTable,'sum(room_nights)'," WHERE  MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$PrevstartYr."'  and id_shop='".$_SESSION['shop']."'  and payment_status=1 ".$ConnHotels."");
	
	//$MonthWiseRevenueCurrentYear =selectColumn($reservationTable,'sum(paid)'," WHERE MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$startYr."'    AND id_shop='".$_SESSION['shop']."'  and payment_status=1  ".$ConnHotels."");
	// $ytdPrevYearRevenue = selectColumn($reservationTable,'sum(paid)'," WHERE  MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$PrevstartYr."'  and id_shop='".$_SESSION['shop']."'  and payment_status=1 ".$ConnHotels."");
	//echo " MONTH(".$reportfieldVarible.") = '".$monthNUmers."' AND YEAR(".$reportfieldVarible.") = '".$startYr."'";
	/*$sqlCurrentYearMonthWise = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,
    
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND MONTH(`".TBL_ORDERS."`.last_modified) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.last_modified) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND MONTH(`".TBL_ORDERS."`.invoice_date)  = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.invoice_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND MONTH(`".TBL_ORDERS."`.invoice_date) = '".$monthNUmers."'  AND YEAR(`".TBL_ORDERS."`.invoice_date) = '".$startYr."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND MONTH(`".TBL_ORDERS."`.booking_confirm_date) = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.booking_confirm_date) = '".$startYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND MONTH(`".TBL_ORDERS."`.invoice_date)   = '".$monthNUmers."' AND YEAR(`".TBL_ORDERS."`.invoice_date) = '".$startYr."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  ".$cond."  AND  ".$allUser ;
       */
       $sqlCurrentYearMonthWise = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$startYr."' $condhotelAccess ";


       
    	
       //echo  $allUser;
       //echo $sqlCurrentYearMonthWise;
       //die;
       
       
        $resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
        $rowListCurrentYearMonthWise = mysqli_fetch_object($resultCurrentYearMonthWise);
        
        $MonthWiseRoomNightsCurrentYear=$rowListCurrentYearMonthWise->newConfirmed+$rowListCurrentYearMonthWise->newTentative;
        $MonthWiseRevenueCurrentYear =$rowListCurrentYearMonthWise->tarrif_price_confimed;

	 $sqlPrevYearMonthWise = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND MONTH(`fs_order_detail`.dated) = '".$monthNUmers."' AND YEAR(`fs_order_detail`.dated) = '".$PrevstartYr."' $condhotelAccess 
";
       
       
    
       
       //echo $sqlPrevYearMonthWise;
       //die;
       
     // $graphotelName[]='Hotels';  
    $resultPrevYearMonthWise = mysqli_query($connNew,$sqlPrevYearMonthWise);
    $rowListPrevYearMonthWise = mysqli_fetch_object($resultPrevYearMonthWise);
    $ytdPrevYearRoomNights=$rowListPrevYearMonthWise->newConfirmed+$rowListPrevYearMonthWise->newTentative;
    $ytdPrevYearRevenue =$rowListPrevYearMonthWise->tarrif_price_confimed;
    
   
 	$mtdThisAllHotelValues[]=($mtdThisAllHotelValuesResult==''?0:$mtdThisAllHotelValuesResult);
	$ytdAllHotelValues[]=($ytdAllHotelValuesResult==''?0:$ytdAllHotelValuesResult);
	
	$monthName =  DateTime::createFromFormat('!m', $startMo);
    $monthName = $monthName->format('F');
   
   array_push($monthNameData,$monthName);
   array_push($MonthWiseRoomNightsData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
   array_push($MonthWiseRoomNightsLastYearData,$ytdPrevYearRoomNights==''?0:$ytdPrevYearRoomNights);
   
   //ARR===============================
     if($MonthWiseRoomNightsCurrentYear>0 && $MonthWiseRevenueCurrentYear>0){
	$mtdRoomRevenueArr2  =round($MonthWiseRevenueCurrentYear/$MonthWiseRoomNightsCurrentYear,2);
	array_push($mtdRoomRevenueArr,$mtdRoomRevenueArr2);
	}else{
		array_push($mtdRoomRevenueArr,'null');
		}
		if($MonthWiseRoomNightsLastYearData>0  && $ytdPrevYearRevenue>0){
	$mtdRoomRevenueArrLastYear2  =round($ytdPrevYearRevenue/$ytdPrevYearRoomNights,2);
	array_push($mtdRoomRevenueLastYearArr,$mtdRoomRevenueArrLastYear2);
	}else{
		array_push($mtdRoomRevenueLastYearArr,'null');
		}	
		
		
	//ARR===============================	
		
   array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear/100000,2));
   array_push($ytdPrevYearRevenueData,$ytdPrevYearRevenue==''?0:round($ytdPrevYearRevenue/100000,2));
   
   
   $MonthWiseRoomNightsCurrentYear2  += $MonthWiseRoomNightsCurrentYear;
   $ytdPrevYearRoomNights2  += $ytdPrevYearRoomNights;
   array_push($mtdThisAllHotelValuesMAT,$MonthWiseRoomNightsCurrentYear==''?'null':$MonthWiseRoomNightsCurrentYear2);
   array_push($ytdAllHotelValuesMAT,$ytdPrevYearRoomNights==''?'null':$ytdPrevYearRoomNights2);
   
   $MonthWiseRevenueCurrentYear2  += $MonthWiseRevenueCurrentYear;
   $ytdPrevYearRevenue2  += $ytdPrevYearRevenue;
   array_push($MonthWiseRevenueCurrentYearDataMAT,$MonthWiseRevenueCurrentYear==''?'null':round($MonthWiseRevenueCurrentYear2/100000,2));
   array_push($ytdPrevYearRevenueDataMAT,$ytdPrevYearRevenue==''?'null':round($ytdPrevYearRevenue2/100000,2));
   
   
   
   
     $startMo++;  
  }
    
   //===========================Segment Wise Chart START==================================
 
    $OfferNameArray=array();
    $rowOfferListArray=array();
    $sqlOfferList = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.segment_id,`".TBL_ORDERS."`.id_company,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_COMPANY."`.id_default_group,

    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '4' AND DATE(`".TBL_ORDERS."`.last_modified) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0)  ELSE 0 END) AS newCancelled,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '3' AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END) AS newWaitlisted,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
    		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  ".$cond."  AND  ".$allUser." GROUP BY `".TBL_ORDERS."`.segment_id";
       
      
$resOfferList = mysqli_query($connNew,$sqlOfferList);
	while($rowOfferList = mysqli_fetch_object($resOfferList)){

	$OfferName 	= selectColumn('fs_segment_master','name'," WHERE   id='".$rowOfferList->segment_id."' and id_shop='".$_SESSION['shop']."'   ");
	$rowOfferList = $rowOfferList->newConfirmed+$rowOfferList->newTentative;
	
	array_push($OfferNameArray,strtoupper($OfferName).'('.$rowOfferList.')');
	array_push($rowOfferListArray,$rowOfferList);
	}
	
//===========================Segment Wise Chart END==================================	

//===========================COMPANY SOURSE Wise Chart START==================================
 
	$CompanyGroupNameArray=array();
	$CompanyGroupListArray=array();
	$CompanyGroupListLastYearArray=array();
   $sqlCompanyGroup = "SELECT `fs_orders`.*,`".TBL_COMPANY."`.id_default_group,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ".$allUser." AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."' $condhotelAccess GROUP BY `".TBL_COMPANY."`.id_default_group 

";
       
     // die;
    $resCompanyGroup = mysqli_query($connNew,$sqlCompanyGroup);
	while($rowCompanyGroup  = mysqli_fetch_object($resCompanyGroup)){

	$segment_masterName 	= selectColumn(TBL_GROUP,'name'," WHERE   id_group='".$rowCompanyGroup->id_default_group."' AND status='1'  ");
	$rowCompanyGroupValue = $rowCompanyGroup->newConfirmed+$rowCompanyGroup->newTentative;
	
	array_push($CompanyGroupNameArray,strtoupper($segment_masterName));
	array_push($CompanyGroupListArray,$rowCompanyGroupValue);
	//array_push($CompanyGroupListLastYearArray,0);
	
	}
$LastyearStart='01-04-2019';
$LastyearEnd='31-03-2020';
$CompanyGroupListLastYearArray=array();
	$sqlCompanyLastYearGroup = " SELECT `fs_orders`.*,`".TBL_COMPANY."`.id_default_group,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_confimed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tarrif_price_tenditive`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`



FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ".$allUser." AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime('-1 years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime('-1 years',strtotime($to_book)))."' $condhotelAccess GROUP BY `".TBL_COMPANY."`.id_default_group 
";
       
      
    $resCompanyLastYearGroup = mysqli_query($connNew,$sqlCompanyLastYearGroup);
	while($rowCompanyLastYearGroup  = mysqli_fetch_object($resCompanyLastYearGroup)){

	//$segment_masterName 	= selectColumn(TBL_GROUP,'name'," WHERE   id_group='".$rowCompanyGroup->id_default_group."' and id_shop='".$_SESSION['shop']."'   ");
	$rowCompanyGroupLastYearValue = $rowCompanyLastYearGroup->newConfirmed+$rowCompanyLastYearGroup->newTentative;
	
	//array_push($CompanyGroupNameArray,strtoupper($segment_masterName));
	array_push($CompanyGroupListLastYearArray,$rowCompanyGroupLastYearValue);
	//array_push($CompanyGroupListLastYearArray,0);
	
	}
	
//===========================COMPANY SOURSE Wise Chart END==================================
	
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
$returnData['mtdThisCustomeReportValues']=$mtdThisCustomeReportValues;
$returnData['mtdRoomCustomeReportRevenue']=	$mtdRoomCustomeReportRevenue;
$returnData['mtdThisCustomeLastYearReportValues']=	$mtdThisCustomeLastYearReportValues;
$returnData['mtdRoomCustomeLastYearReportRevenue']=	   $mtdRoomCustomeLastYearReportRevenue;

$mtdThisValuesAll=array();
$lable='All';
array_push($mtdThisValuesAll,$lable);
$returnData['CustomeReportValuesName']=$mtdThisValuesAll;
//echo '{"totalDaysGoneMtd":12,"totalDaysGoneYtd":121,"stacked":[{"label":"Anil Kumar Sharma","backgroundColor":"rgba(22, 28, 87,0.7)","data":[0]},{"label":"Hitender Kumar","backgroundColor":"rgba(82, 13, 42,0.7)","data":[0]},{"label":"Sumit Dhiman","backgroundColor":"rgba(219, 6, 6,0.7)","data":[0]},{"label":"Varun Kanojia","backgroundColor":"rgba(134, 1, 140,0.7)","data":[0]}],"mtdThisVal":[0,0,0,0],"mtdLastVal":["105","0","0","50"],"budgetVal":[0,0,0,0],"executives":["Anil Kumar Sharma","Hitender Kumar","Sumit Dhiman","Varun Kanojia"],"ytdLastVal":["8814","1634","1600","2846"],"ytdThisVal":[0,0,0,0],"mtdVisits":[0,0,0,0],"mtdRoomRevenue":["0","0","0","0"],"mtdTotalExpense":[0,0,0,0],"ytdVisits":[0,0,0,0],"ytdRateLetters":["0","0","0","0"],"ytdTotalExpense":[0,0,0,0],"reportPeriod":"01-04-2020 To 16-09-2020","datePeriod":"01-04-2020 to 16-09-2020"}';
//echo json_encode($returnData);
//"mtdThisVal":[0,0,0,0],"mtdLastVal":["105","0","0","50"],"budgetVal":[0,0,0,0],"executives":["Anil Kumar Sharma","Hitender Kumar","Sumit Dhiman","Varun Kanojia"],"ytdLastVal":["8814","1634","1600","2846"],"ytdThisVal":[0,0,0,0],"mtdVisits":[0,0,0,0],"mtdRoomRevenue":["0","0","0","0"],"mtdTotalExpense":[0,0,0,0],"ytdVisits":[0,0,0,0],"ytdRateLetters":["0","0","0","0"],"ytdTotalExpense":[0,0,0,0],"reportPeriod":"01-04-2020 To 16-09-2020","datePeriod":"01-04-2020 to 16-09-2020"}';
?>