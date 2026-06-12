<?php

include_once("../../config/auto_loader.php");
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}

//echo '1222';
error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_POST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));

$ComparePeriodDateArray	=	explode('to',$_POST['ComparePeriod']);
$ComparePeriod_from = date('Y-m-d',strtotime($ComparePeriodDateArray[0]));
$ComparePeriod_to = date('Y-m-d',strtotime($ComparePeriodDateArray[1]));

$dateCalcultion = dateCalcultion($from,$to);

$dateFromForm = DateTime::createFromFormat("Y-m-d", $from);
$FinacialYearFrom   =    $dateFromForm->format("Y");

$dateToYear = DateTime::createFromFormat("Y-m-d", $to);
$FinacialYearTo  =    $dateToYear->format("Y");

$CompareFromForm = DateTime::createFromFormat("Y-m-d", $ComparePeriod_from);
$FinacialCompareYearFrom   =    $CompareFromForm->format("Y");

$CompareToYear = DateTime::createFromFormat("Y-m-d", $ComparePeriod_to);
$FinacialCompareYearTo  =    $CompareToYear->format("Y");

//============================================================

  $Diffrence='';
  $CompareFinancialYear	=	explode('-',$_POST['CompareFinancialYear']);
  $CurrentFinancialYear	=	explode('-',$_POST['CurrentFinancialYear']);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);

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
// $to = date('31-03-'.$FinanceEndYear);
 
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
if($_REQUEST['id_group_sub_master']>0 && $_REQUEST['id_group_sub_master']!='undefined'){
    
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
	
	if($_REQUEST['id_group_master'] != '' && $_REQUEST['id_group_master'] != '0' && $_REQUEST['id_group_master'] != '10000' ){
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($_REQUEST['id_group_master'])."'";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($_REQUEST['id_group_master'])."'";
	}elseif($_REQUEST['id_group_master'] == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
	}else{
	
	    
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
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

if($_REQUEST['viewMonthwise']=='1'){
            if (date('m') > 6) {
                $year = date('Y')."-".(date('Y') +1);
                $FinanceEndYear=(date('Y') +1);
            }
            else {
                $year = (date('Y')-1)."-".date('Y');
            }
            $year2 =   explode('-',$year);
            $yearend2   =   $year2[1].'-03-31';
            
}
if($_REQUEST['viewMonthwise']=='1'){
   $dateCalcultion['To_LY_Finacial_Year'];
}else{
    $dateCalcultion['To_LY_Finacial_Year']=$dateCalcultion['To_LY_Date'];
}

    //Custom Report Start==============================================================================
     $sqlCustomeReport = "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group

,sum(case when  ( (DATE( `fs_agent_achieved`.month) BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`



,sum(case when ( (DATE( `fs_agent_achieved`.month) BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`


FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group

LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group



where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND  `fs_agent_achieved`.`month` BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."' ".$allUser." ".$condBOB.$condTeamGroup." ";
       
       
     // echo $sqlCustomeReport;
   //  die;
     //  
       
       $resultListCustomeReport = mysqli_query($connNew,$sqlCustomeReport);
	while($rowListCustomeReport = mysqli_fetch_object($resultListCustomeReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeReportValues2+=($rowListCustomeReport->newConfirmed);
	    $mtdRoomCustomeReportRevenue2+=round(($rowListCustomeReport->confimed_revenue)/100000,2);

	}
		
    $mtdThisCustomeReportValues=array();
    $mtdRoomCustomeReportRevenue=array();
    
    array_push($mtdThisCustomeReportValues,$mtdThisCustomeReportValues2);
    array_push($mtdRoomCustomeReportRevenue,$mtdRoomCustomeReportRevenue2);
    //booking _date end
    
    //print_r($dateCalcultion);
    
    $sqlCustomeLastYearReport ="SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group,`".TBL_COMPANY."`.id_default_group
      

,sum(case when  DATE(`fs_agent_achieved`.month ) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`


,sum(case when  DATE(`fs_agent_achieved`.month ) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group

LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND DATE(`fs_agent_achieved`.month ) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' ".$allUser." ".$condBOB.$condTeamGroup." ";

  
       
     // echo $sqlCustomeLastYearReport;
     //die;
     //  
       
       $resultListCustomeLastYearReport = mysqli_query($connNew,$sqlCustomeLastYearReport);
	while($rowListCustomeLastYearReport = mysqli_fetch_object($resultListCustomeLastYearReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeLastYearReportValues2+=($rowListCustomeLastYearReport->newConfirmed);
	    $mtdRoomCustomeLastYearReportRevenue2+=round(($rowListCustomeLastYearReport->confimed_revenue)/100000,2);
//round($ytdPrevYearRevenue/100000,2)
	}
		
    $mtdThisCustomeLastYearReportValues=array();
    $mtdRoomCustomeLastYearReportRevenue=array();
    
    array_push($mtdThisCustomeLastYearReportValues,$mtdThisCustomeLastYearReportValues2);
    array_push($mtdRoomCustomeLastYearReportRevenue,$mtdRoomCustomeLastYearReportRevenue2);
    //booking _date end
    
    
    
    //Custom Report End==============================================================================
   
   
    
    
    $sql = "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
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
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND  `fs_order_detail`.`dated` BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($toDate))."' ".$allUser." ".$condBOB.$condTeamGroup."  group by `fs_users`.id";
       
       
     // echo $sql;
    //  die;
     //  
       
       $resultList = mysqli_query($connNew,$sql);
	while($rowList = mysqli_fetch_object($resultList)){
	  
	    $exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    array_push($mtdThisValues,($rowList->newConfirmed));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
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
 
 if($_REQUEST['viewMonthwise']==1){ 
     //Yearly Graph Conditions
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
	$sqlCurrentYearMonthWise ="SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group
     

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.qty else 0 end) as `newTentative`


,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.qty else 0 end) as `Waitlisted`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' then `fs_agent_achieved`.qty else 0 end) as `Cancelled`



FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group

LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$startYr."' ".$allUser." ".$condBOB.$condTeamGroup."  ";
       
       
    	
       //echo  $allUser;
     //  echo '<br><br><br><br><br>'.$sqlCurrentYearMonthWise;
      // die;
       
       
        $resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
        $rowListCurrentYearMonthWise = mysqli_fetch_object($resultCurrentYearMonthWise);
        
        $MonthWiseRoomNightsCurrentYear=$rowListCurrentYearMonthWise->newConfirmed;
        $MonthWiseRevenueCurrentYear =$rowListCurrentYearMonthWise->confimed_revenue;

	 	$sqlPrevYearMonthWise =  "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$PrevstartYr."' then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$PrevstartYr."' then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$PrevstartYr."' then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$PrevstartYr."' then `fs_agent_achieved`.qty else 0 end) as `newTentative`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group

LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND MONTH(`fs_agent_achieved`.month) = '".$monthNUmers."' AND YEAR(`fs_agent_achieved`.month) = '".$PrevstartYr."' ".$allUser.$condTeamGroup." ".$condBOB."  
";
       
       
    
       
     //  echo $sqlPrevYearMonthWise;
       //die;
       
     // $graphotelName[]='Hotels';  
    $resultPrevYearMonthWise = mysqli_query($connNew,$sqlPrevYearMonthWise);
    $rowListPrevYearMonthWise = mysqli_fetch_object($resultPrevYearMonthWise);
    $ytdPrevYearRoomNights=$rowListPrevYearMonthWise->newConfirmed;
    $ytdPrevYearRevenue =$rowListPrevYearMonthWise->confimed_revenue;
    
   
 	$mtdThisAllHotelValues[]=($mtdThisAllHotelValuesResult==''?0:$mtdThisAllHotelValuesResult);
	$ytdAllHotelValues[]=($ytdAllHotelValuesResult==''?0:$ytdAllHotelValuesResult);
	$montharrayval=$monthNUmers;
	$monthName =  DateTime::createFromFormat('!m', $startMo);
    $monthName = $monthName->format('F');
   
   array_push($monthNameData,$monthName);
   array_push($MonthWiseRoomNightsData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
   array_push($MonthWiseRoomNightsLastYearData,$ytdPrevYearRoomNights==''?0:$ytdPrevYearRoomNights);
   
   
   
   
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
   
   
   //ARR===============================
     if($MonthWiseRoomNightsCurrentYear>0 && $MonthWiseRevenueCurrentYear>0){
	$mtdRoomRevenueArr2  =round($MonthWiseRevenueCurrentYear/$MonthWiseRoomNightsCurrentYear);
	array_push($mtdRoomRevenueArr,$mtdRoomRevenueArr2);
	}else{
		array_push($mtdRoomRevenueArr,'null');
		}
		if($MonthWiseRoomNightsLastYearData>0  && $ytdPrevYearRevenue>0){
	$mtdRoomRevenueArrLastYear2  =round($ytdPrevYearRevenue/$ytdPrevYearRoomNights);
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
 } //Yearly Graph Condition End 
 
 
  $MonthWiseRoomNightsCurrentYearQuarterly=array($MonthWiseRoomNightsCurrentYearQuarterlyQ1,$MonthWiseRoomNightsCurrentYearQuarterlyQ2,$MonthWiseRoomNightsCurrentYearQuarterlyQ3,$MonthWiseRoomNightsCurrentYearQuarterlyQ4);
 $MonthWiseRevenueCurrentYearQuarterly=array($MonthWiseRevenueCurrentYearQuarterlyQ1,$MonthWiseRevenueCurrentYearQuarterlyQ2,$MonthWiseRevenueCurrentYearQuarterlyQ3,$MonthWiseRevenueCurrentYearQuarterlyQ4);
 
 
 $ytdPrevYearRoomNightsQuarterly=array($ytdPrevYearRoomNightsQuarterlyQ1,$ytdPrevYearRoomNightsQuarterlyQ2,$ytdPrevYearRoomNightsQuarterlyQ3,$ytdPrevYearRoomNightsQuarterlyQ4);
 $ytdPrevYearRevenueQuarterly=array($ytdPrevYearRevenueQuarterlyQ1,$ytdPrevYearRevenueQuarterlyQ2,$ytdPrevYearRevenueQuarterlyQ3,$ytdPrevYearRevenueQuarterlyQ4);
 
 
$MonthWiseRoomNightsCurrentYearHalfYear=array($MonthWiseRoomNightsCurrentYearHalfYearH1,$MonthWiseRoomNightsCurrentYearHalfYearH2);
 $MonthWiseRevenueCurrentYearHalfYear=array($MonthWiseRevenueCurrentYearHalfYearH1,$MonthWiseRevenueCurrentYearHalfYearH2);
 
 
 $ytdPrevYearRoomNightsHalfYear=array($ytdPrevYearRoomNightsHalfYearH1,$ytdPrevYearRoomNightsHalfYearH2);
 $ytdPrevYearRevenueHalfYear=array($ytdPrevYearRevenueHalfYearH1,$ytdPrevYearRevenueHalfYearH2);
 
 
   //===========================Segment Wise Chart START==================================
 
	$OfferNameArray=array();
	$rowOfferListArray=array();
  $SegmentWiseListLastYearArray=array();
       
       $sqlOfferList = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`


,sum(case when `fs_orders`.`booking_status` = '3' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.room_quantity else 0 end) as `Waitlisted`

,sum(case when `fs_orders`.`booking_status` = '4' and ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($dateCalcultion['From_CY_Date']))."' And '".date('Y-m-d',strtotime($dateCalcultion['To_CY_Date']))."')) then `fs_order_detail`.room_quantity else 0 end) as `Cancelled`
,sum(case when (`fs_orders`.`booking_status` = '1' ) and DATE(`fs_order_detail`.dated ) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' then `fs_order_detail`.room_quantity else 0 end) as `LastYearnewConfirmed`


FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."'   ".$allUser." ".$condBOB.$condTeamGroup."   GROUP BY `".TBL_ORDERS."`.segment_id";


       
$resOfferList = mysqli_query($connNew,$sqlOfferList);
	while($rowOfferList = mysqli_fetch_object($resOfferList)){

	$OfferName 	= selectColumn('fs_segment_master','name'," WHERE   id='".$rowOfferList->segment_id."' and id_shop='".$_SESSION['shop']."'   ");
	$rowOfferListnewConfirmed = $rowOfferList->newConfirmed;
	    $OfferNameList=strtoupper($OfferName);
	    
	    //.'('.$rowOfferList.')';
	array_push($OfferNameArray,$OfferNameList==''?'0':$OfferNameList);
	array_push($rowOfferListArray,$rowOfferListnewConfirmed==''?'0':$rowOfferListnewConfirmed);
	
	
		$rowSegmentWiseLastYearValue = $rowOfferList->LastYearnewConfirmed;
	
	
	array_push($SegmentWiseListLastYearArray,$rowSegmentWiseLastYearValue==''?'0':$rowSegmentWiseLastYearValue);
	
	}
	
	$sqlHotelList = "SELECT *
FROM `fs_hotels` 
 


where `fs_hotels`.`id_shop` = '".addslashes($_SESSION['shop'])."'  ";
$resHotelList = mysqli_query($connNew,$sqlHotelList);
	while($rowHotelList = mysqli_fetch_object($resHotelList)){
	    	array_push($OfferNameArray,$rowHotelList->name==''?'0':$rowHotelList->name);
	}
	
	if(empty($OfferNameArray)) {
	    array_push($OfferNameArray,'0');
	}
		if(empty($rowOfferListArray)) {
	    array_push($rowOfferListArray,'0');
	}
		if(empty($SegmentWiseListLastYearArray)) {
	    array_push($SegmentWiseListLastYearArray,'0');
	}
	
	
	
		
	
	
//===========================Segment Wise Chart END==================================	

































//========================================================================================================	
/*$cond= '';
$budgetRoomNightsValues= array();
$mtdThisExecutiveValues= array();
$yearToDayLastValues= array();
$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($_POST['id_team']==0){ 
		//echo 'All';
		
		
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
		    $cond = ' AND `fs_users`.id="'.$_SESSION['userId'].'" ';
		}
		
	}
//SELECT id,name,user_type FROM fs_users WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('', ',', '|'), ')(,|$)') AND id IN ()  order by name
//echo $cond;
$condBOB='';
	if($_REQUEST['id_group_master'] != '' && $_REQUEST['id_group_master'] != '0' && $_REQUEST['id_group_master'] != '10000' ){
		//$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($_REQUEST['id_group_master'])."'";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($_REQUEST['id_group_master'])."'";
	}elseif($_REQUEST['id_group_master'] == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		//$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
	}else{
	
	    
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  if(strtoupper($objGroup->name)!='UNIT'){  
						$GroupArrayList[] = 	$objGroup->id;
						}
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		//$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
 $sqlExe = "SELECT `".TBL_USERS."`.id,`".TBL_USERS."`.name,`".TBL_USERS."`.user_type 
 
 
 FROM ".TBL_USERS."
 
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group

WHERE `".TBL_USERS."`.id!='' ".$condBOB." ".$cond." ".$allUser." order by name";
		 echo $sqlExe;
		die;
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
		

		

		$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');

	
	//echo TBL_DAILYVISIT.'count(id)'.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
	
	$_POST['period'] =date('Y-m-d',strtotime($PeriodDateArray[1]));
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
				//echo $achievedSQL; die;
				
				
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
$rowExe->name;

		array_push($exeNameArr,ucwords(strtolower($rowExe->name)));
		array_push($mtdLastValues, ($mtdPrevYear==''?0:$mtdPrevYear));
		
		
		array_push($mtdThisMonthValues, ($mtdThisMonth==''?0:$mtdThisMonth));
				
		
		array_push($yearToDayLastValues, ($yearToDayPrevYear==''?0:$yearToDayPrevYear));
		
		array_push($mtdThisExecutiveValues, ($achieved==''?0:$achieved));

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
*/





	
	
	
	
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
$SalesSyncLable=array();
$SalesSyncNumbers=array();
$BusinessSourceLable=array();
        $BusinessSourceNumbers=array();
$sql_team_user = "SELECT count(user_level) as count, fs_user_levels.name,fs_user_levels.id FROM `fs_users` left join `fs_user_levels` ON  fs_user_levels.id=fs_users.user_level where fs_users.id_shop='".$_SESSION['shop']."' AND fs_users.status='1' and fs_users.user_level!=1   GROUP by fs_users.user_level
							";
            				$res_teamuser = mysqli_query($connNew,$sql_team_user);
            	while($resUserActionsTeam = mysqli_fetch_object($res_teamuser)){
            	    	array_push($SalesSyncLable,$resUserActionsTeam->name.'('.$resUserActionsTeam->count.')');
            	    	array_push($SalesSyncNumbers,$resUserActionsTeam->count);
            	    
            	}
	//$SalesSyncLable=array('Sales Team', 'Sales Heads','Reservation Team','Management','Admin Team','Unit Sales Team','Unit Sales Head','Total Company','Total Contacts');
    //$SalesSyncNumbers=array('25','7','1','2','8','66','54','18068','22686');
      $sql = "SELECT count(`fs_company`.`id_company`) as count,`fs_company_group`.name
FROM `fs_company_group` 
LEFT JOIN `fs_company`  ON fs_company.id_default_group = fs_company_group.id_group

where `fs_company_group`.`status` = '1' and `fs_company`.`status` = '1' and `fs_company_group`.`id_shop` = '".addslashes($_SESSION['shop'])."'
GROUP BY `".TBL_COMPANY."`.id_default_group ";
       	$res_businessSource = mysqli_query($connNew,$sql);
            	while($resbusinessSource = mysqli_fetch_object($res_businessSource)){
            	    array_push($BusinessSourceLable,$resbusinessSource->name.'('.$resbusinessSource->count.')');
                    array_push($BusinessSourceNumbers,$resbusinessSource->count);
        
            	}
        $TotalCompanyCount = selectColumn(TBL_COMPANY,'count(id_company)'," WHERE   status='1' and id_shop='".$_SESSION['shop']."'   ");
    	array_push($BusinessSourceLable,'Total Company('.$TotalCompanyCount.')');
        array_push($BusinessSourceNumbers,$TotalCompanyCount);
        
        
                $sql_Customeruser = "SELECT count(id_customer) as customer  FROM `fs_company` left join fs_customer On fs_company.id_company=fs_customer.id_company WHERE fs_company.`id_shop` = '".$_SESSION['shop']."' AND fs_company.`status` = 1 and fs_customer.type=2";
            	$res_Customeruser = mysqli_query($connNew,$sql_Customeruser);
            	$resUserCustomeruser = mysqli_fetch_object($res_Customeruser);
            	
        	array_push($BusinessSourceLable,'Total Contacts('.$resUserCustomeruser->customer.')');
            array_push($BusinessSourceNumbers,$resUserCustomeruser->customer);


$returnData['BusinessSourceLable']=$BusinessSourceLable;
$returnData['BusinessSourceNumbers']=$BusinessSourceNumbers;


$returnData['SalesSyncLable']=$SalesSyncLable;
$returnData['SalesSyncNumbers']=$SalesSyncNumbers;
    
$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;
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
$returnData['SegmentWiseListLastYearArray']=$SegmentWiseListLastYearArray;

$returnData['BookingThroughNameArray']=	$BookingThroughNameArray;
$returnData['BookingThroughCurrentYearValue']=	$BookingSourceListArray;
$returnData['rowBookingThroughLastYearValue']=	   $BookingSourceListLastYearArray;

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

$returnData['yearToDayLastVal']=$yearToDayLastValues;
$returnData['budgetRoomNightsValues']=$budgetRoomNightsValues;
$returnData['mtdThisExecutiveValues']=$mtdThisExecutiveValues;




$returnData['CYLable']= $FinacialYearFrom.'-'.$FinacialYearTo;
$returnData['LYLable']= $FinacialCompareYearFrom.'-'.$FinacialCompareYearTo;
	
$mtdThisValuesAll=array();
$lable='All';
array_push($mtdThisValuesAll,$lable);
$returnData['CustomeReportValuesName']=$mtdThisValuesAll;

echo json_encode($returnData);


 ?>