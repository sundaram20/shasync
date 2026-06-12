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
$mtdnewTentative=array();
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
		$allUserBudget =" AND   `".TBL_BUDGET_MASTER."`.`id_user` IN (".$teamMembers.") ";
		$allUserLead =" AND   `".TBL_DAILY_ENQUERY."`.`id_user` IN (".$teamMembers.") ";
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
		$allUserBudget =" AND   `".TBL_BUDGET_MASTER."`.`id_user` IN (".$teamMembers.") ";
	    $allUserLead =" AND   `".TBL_DAILY_ENQUERY."`.`id_user` IN (".$teamMembers.") ";
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


 
  

  
 //debugData($reportArray,'Report');
  $monthArray=array();
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
$startYear = $yr;$d["year"];


   $endMo =date("m", strtotime("+4 month", $startMo));
     
     
     $diff = '2'; //abs($endMo-$startMo);
    
 
 
 
 
 
 if($_REQUEST['summaryReportType'] == '20'){  //Yearly Graph Conditions
 
 
 
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
$ConbudgetSql ="AND MONTH(`".TBL_BUDGET_MASTER."`.month) = '".$monthNUmers."' AND YEAR(`".TBL_BUDGET_MASTER."`.month) = '".$startYr."' ";
  $sqlCurrentYearMonthWise ="SELECT `fs_orders`.*,`fs_users`.name as name_executive,`mst_team`.id_group,fs_company.id_default_group,`fs_users`.myownteam_id as MyOwnteam,
      `fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.adults) as adults,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.infants) as infants,
      sum(`fs_order_detail`.room_quantity * `fs_order_detail`.child) as child ,
      sum(`fs_order_detail`.room_quantity) as room_quantity 

,sum(case when (`fs_orders`.`booking_status` = '1' ) ".$ConSql."  then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') ".$ConSql."   then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) ".$ConSql."   then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') ".$ConSql."   then `fs_order_detail`.room_quantity else 0 end) as `newTentative`








FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id 


LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


".$cond." ".$ConSql.$condhotelAccess."GROUP BY `".TBL_USERS."`.myownteam_id,`mst_team`.id_group ";
       
       
    	//echo TBL_GROUP_MASTER;
       //echo  $allUser;
   // echo '<br><br>'.$sqlCurrentYearMonthWise;
     //  die;
       
       
        $resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
       
        
       
	   $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $Totalroomnights='';
       $TotalnewTentative='';
        $TotalLead='';
	    $TotalConvertion_Ratio='';
	    $TotalForcasting='';
	    $TotalBudget='';
	    $Totalvariance_to_budget ='';
       $empty7=0;
       $newConfirmednewTentative='';
       //Budget SQL===================================================================================================
	   
	   $ConbudgetSql ="AND MONTH(`".TBL_BUDGET_MASTER."`.month) = '".$monthNUmers."' AND YEAR(`".TBL_BUDGET_MASTER."`.month) = '".$startYr."' ";
	
	    $sqlBudget = "SELECT  
	    
        sum(`".TBL_BUDGET_MASTER."`.qty) As budget_qty,
        sum(`".TBL_BUDGET_MASTER."`.month_value) As budget_month_value,
        sum(`".TBL_BUDGET_MASTER."`.forecast_qty) As forecast_qty,
        sum(`".TBL_BUDGET_MASTER."`.forecast_month_value) As forecast_month_value,
        `".TBL_BUDGET_MASTER."`.month,`".TBL_BUDGET_MASTER."`.id_company,
        `".TBL_BUDGET_MASTER."`.id_user,`mst_team`.id_group,
        fs_company.id_default_group,
        `fs_users`.myownteam_id as MyOwnteam

	    FROM `".TBL_BUDGET_MASTER."`   
LEFT JOIN `fs_company`  ON `".TBL_BUDGET_MASTER."`.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id 


LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
	    
	    
	    
	    where  `".TBL_BUDGET_MASTER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ".$ConbudgetSql." ".$allUserBudget."  
	    AND `".TBL_BUDGET_MASTER."`.type=1     GROUP BY `".TBL_USERS."`.myownteam_id,`mst_team`.id_group  ORDER BY `".TBL_BUDGET_MASTER."`.month";
  // echo '<br> <br> <br> '.$sqlBudget;
   $resultBudget = mysqli_query($connNew,$sqlBudget);
   
   while($rowBudget = mysqli_fetch_object($resultBudget)){
       
       
        //debugdata($rowBudget);
        $companynameBudget= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowBudget->MyOwnteam."'");
	    $GroupNameBudget= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowBudget->id_group."'");
	    $reportArray['Team Wise'][$GroupNameBudget][strtolower($companynameBudget==''?$emptytext7:$companynameBudget)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['budget_roomnights']=$rowBudget->budget_qty;
	    $reportArray['Team Wise'][$GroupNameBudget][strtolower($companynameBudget==''?$emptytext7:$companynameBudget)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['budget_revenue']=$rowBudget->budget_month_value;
	    $reportArray['Team Wise'][$GroupNameBudget][strtolower($companynameBudget==''?$emptytext7:$companynameBudget)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['forecast_roomnights']=$rowBudget->forecast_qty;
	    $reportArray['Team Wise'][$GroupNameBudget][strtolower($companynameBudget==''?$emptytext7:$companynameBudget)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['forecast_revenue']=$rowBudget->forecast_month_value;
    
       
       
       
        $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalbudget_roomnights']            +=$rowBudget->budget_qty;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalbudget_revenue']            +=$rowBudget->budget_month_value;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalforecast_roomnights']                   += $rowBudget->forecast_qty;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalforecast_revenue']       += $rowBudget->forecast_month_value;
	   
	    
	    $reportArraySubTotal['Team Wise'][$GroupNameBudget][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalbudget_roomnights']                 +=$rowBudget->budget_qty;
	    $reportArraySubTotal['Team Wise'][$GroupNameBudget][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalbudget_revenue']             +=$rowBudget->budget_month_value;
	    $reportArraySubTotal['Team Wise'][$GroupNameBudget][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalforecast_roomnights']                  += $rowBudget->forecast_qty;
	    $reportArraySubTotal['Team Wise'][$GroupNameBudget][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalforecast_revenue']       +=$rowBudget->forecast_month_value;
	    
       
       
   }
	
	   //Budget SQL==END=================================================================================================
	   
	   
	   
	   
	   
	   //Lead SQL=START==================================================================================================
	    $ConbudgetSql ="AND MONTH(`".TBL_DAILY_ENQUERY."`.expected_check_in_date) = '".$monthNUmers."' AND YEAR(`".TBL_DAILY_ENQUERY."`.expected_check_in_date) = '".$startYr."' ";
	
	    $sqlLead = "SELECT  
	    
        sum(`".TBL_DAILY_ENQUERY."`.expected_room_nights) As expected_room_nights,
        sum(`".TBL_DAILY_ENQUERY."`.expected_revenue) As expected_revenue,
        
        `".TBL_DAILY_ENQUERY."`.expected_check_in_date,`".TBL_DAILY_ENQUERY."`.id_company,
        `".TBL_DAILY_ENQUERY."`.id_user,`mst_team`.id_group,
        fs_company.id_default_group,
        `fs_users`.myownteam_id as MyOwnteam

	    FROM `".TBL_DAILY_ENQUERY."`   
            LEFT JOIN `fs_company`  ON `".TBL_DAILY_ENQUERY."`.id_company = fs_company.id_company
            LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
            LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id 
            
            
            LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
            LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
            LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
	    
	    
	    
	    where  `".TBL_DAILY_ENQUERY."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ".$ConbudgetSql." ".$allUserLead."  
	        GROUP BY `".TBL_USERS."`.myownteam_id,`mst_team`.id_group  ORDER BY `".TBL_DAILY_ENQUERY."`.expected_check_in_date";
  // echo '<br> <br> <br> '.$sqlLead;
   $resultLead = mysqli_query($connNew,$sqlLead);
   
   while($rowLead = mysqli_fetch_object($resultLead)){
      // debugData($rowLead);
       $companynameLead= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowLead->MyOwnteam."'");
	    $GroupNameLead= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowLead->id_group."'");
       $reportArray['Team Wise'][$GroupNameLead][strtolower($companynameLead==''?$emptytext7:$companynameLead)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['lead_roomnights']=$rowLead->expected_room_nights;
	    $reportArray['Team Wise'][$GroupNameLead][strtolower($companynameLead==''?$emptytext7:$companynameLead)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['lead_revenue']=$rowLead->expected_revenue;
	   
        $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totallead_roomnights']            +=$rowLead->expected_room_nights;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totallead_revenue']            +=$rowLead->expected_revenue;
        
        $reportArraySubTotal['Team Wise'][$GroupNameLead][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totallead_roomnights']                 +=$rowLead->expected_room_nights;
	    $reportArraySubTotal['Team Wise'][$GroupNameLead][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totallead_revenue']             +=$rowLead->expected_revenue;
	    
       
   }
	   
	   //Lead SQL=END==================================================================================================
	    
	while($rowListMonth = mysqli_fetch_object($resultCurrentYearMonthWise)){
	    
	  
	    
	    
	    
	    
	    
	    
	    
	    
	    
	  
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowListMonth->MyOwnteam."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowListMonth->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowListMonth->id_default_group."'");
	    
	    
	    $emptytext7 ='empty_'.$empty7++;
	    //$reportArray['Team Wise'][$GroupName]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	   
	    
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['roomnights']      = $rowListMonth->newConfirmed==''?'0':$rowListMonth->newConfirmed;
	    
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['newTentative']      = $rowListMonth->newTentative==''?'0':$rowListMonth->newTentative;
	    
	    
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Lead']=0;//$newConfirmed==''?'0':$newConfirmed;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Convertion_Ratio']=0;//$newConfirmed==''?'0':$newConfirmed;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Forcasting']=($rowListMonth->newConfirmed+$rowListMonth->newTentative);//$newConfirmed==''?'0':$newConfirmed;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Budget']=0;//$newConfirmed==''?'0':$newConfirmed;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['variance_to_budget']=($rowListMonth->newConfirmed+$rowListMonth->newTentative)-$Budget;//$newConfirmed==''?'0':$newConfirmed;
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['MonthName'] = $DataMonthname.'-'.$startYr;
	    
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['confimed_revenue'] = $rowListMonth->confimed_revenue==''?0:round($rowListMonth->confimed_revenue);
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['tentative_revenue'] = $rowListMonth->tentative_revenue==''?0:round($rowListMonth->tentative_revenue);
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['RevenueForcasting']=($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue);
	    $reportArray['Team Wise'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Revenuevariance_to_budget']=($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue)-$Budget;//$newConfirmed==''?'0':$newConfirmed;
	    //}
	    
	    $monthArray['MonthName'][date('F', mktime(0, 0, 0, $startMo, 10))] =   date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr;
	    
	    $Totalroomnights    +=$rowListMonth->newConfirmed;
	    $TotalnewTentative       +=$rowListMonth->newTentative;
	    $TotalLead=0;
	    $TotalConvertion_Ratio=0;
	    $TotalForcasting +=($rowListMonth->newConfirmed+$rowListMonth->newTentative);
	    $TotalBudget=0;
	     $Budget=0;
	    $Totalvariance_to_budget +=($rowListMonth->newConfirmed+$rowListMonth->newTentative)-$Budget;
	    
	    
	    
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalroomnights']             = $Totalroomnights;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalnewTentative']           = $TotalnewTentative;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalLead']                   = $TotalLead;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalConvertion_Ratio']       = $TotalConvertion_Ratio;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalForcasting']              = $TotalForcasting;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalBudget']                  = $TotalBudget;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalvariance_to_budget']      = $Totalvariance_to_budget;
	    
	    
	    
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalconfimed_revenue']            +=$rowListMonth->confimed_revenue;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totaltentative_revenue']            +=$rowListMonth->tentative_revenue;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueLead']                   = $TotalLead;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueConvertion_Ratio']       = $TotalConvertion_Ratio;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueForcasting']               +=($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue);
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueBudget']                  = $TotalBudget;
	    $reportArrayTotal['Team Wise'][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenuevariance_to_budget']      += ($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue)-$Budget;
	    
	    
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalconfimed_revenue']                 +=$rowListMonth->confimed_revenue;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totaltentative_revenue']             +=$rowListMonth->tentative_revenue;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueLead']                   = 0;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueConvertion_Ratio']       = 0;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueForcasting']              += ($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue);;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenueBudget']                  = 0;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalRevenuevariance_to_budget']      += ($rowListMonth->confimed_revenue+$rowListMonth->tentative_revenue)-$Budget;
	    
	    
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalroomnights']            +=$rowListMonth->newConfirmed;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalnewTentative']           +=$rowListMonth->newTentative;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalLead']                   = 0;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalConvertion_Ratio']       = $TotalConvertion_Ratio;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalForcasting']              +=($rowListMonth->newConfirmed+$rowListMonth->newTentative);
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['TotalBudget']                  = 0;
	    $reportArraySubTotal['Team Wise'][$GroupName][date('F', mktime(0, 0, 0, $startMo, 10)).'-'.$startYr]['Totalvariance_to_budget']     +=($rowListMonth->newConfirmed+$rowListMonth->newTentative)-$Budget;
	    
	  
	    
	}
   
   

   
   
     $startMo++;  
  }
 } //Yearly Graph Condition End    
  
//debugData($monthArray,'Report');
  
  //debugData($reportArray,'Report');
  //debugData($reportArraySubTotal,'Report');
  
 
 
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
    min-width:80%;
    overflow-y:scroll;
    height: 150px;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
     overflow-y:scroll;
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
 
/*$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
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
}*/
if($_REQUEST['pdf']==1 || $CronSet==1){
  //  if($CronSet==1){
    $resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '2'");
    $rowShop = mysqli_fetch_object($resShop);
    $logo	=	$rowShop->image;
    $pathImg='/home/inroomhub/public_html/crs';
   // }
  $pathImg = $DOCUMENT_ROOT;  
 /*$content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.$MAP_VROOT_PATH.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	   $content .=    '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="19" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; */
}
$mainTotalroomnights ='';
$mainTotalnewTentative ='';
$mainTotalLead ='';
$mainTotalConvertion_Ratio ='';
$mainTotalForcasting ='';
$mainTotalBudget ='';
$mainTotalvariance_to_budget ='';

foreach($reportArray as $maintitle=>$mainDatalist){
    $contentGroup .='<table class="table table-striped text-center" border="1">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="43" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$maintitle0ch.' Forecast Report For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .='<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>';
             foreach($monthArray as $ked=>$monthlist){
                 foreach($monthlist as $monthName){
                 $contentGroup .='<th colspan="14" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$monthName.'</th>';
                 }
                 
             }
             $contentGroup .='</tr>';
              $contentGroup .='<tr >
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "></th>
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Room Nights</th>
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red ">Revenue</th>
              
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Room Nights</th>
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red ">Revenue</th>
              
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Room Nights</th>
              <th  colspan="7" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red ">Revenue</th>
              
              ';
              $contentGroup .='</tr>';
             
             $contentGroup .='<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">&nbsp</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed(RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>
            ';
             /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Forecast</th>';
             
             
             //Revenue=======================================================
             $contentGroup .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>
              ';
            /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red">Forecast</th>';
              //Revenue=======================================================
             
             
             
             
              $contentGroup .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed(RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>
              ';
            /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Forecast</th>';
             
             //Revenue=======================================================
             $contentGroup .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>
              ';
            /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red">Forecast</th>';
              //Revenue=======================================================
             
             
              $contentGroup .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed(RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (RN)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>';
             /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Forecast</th>';
             
             
             //Revenue=======================================================
             $contentGroup .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Confimed (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Tentative (Revenue)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Lead</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Total Funnel</th>
              ';
            /*
             $contentGroup .='
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Convertion Ratio</th>';*/
             $contentGroup .='
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance To Budget</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red">Forecast</th>';
              //Revenue=======================================================
             
             $contentGroup .='</tr>';
    foreach($mainDatalist as $teamGroup=>$subDataList1){
       
        //echo  '<br>===='.$teamGroup;
    $contentGroup .='<tr ><td style="text-align:left;color:#000;background-color:#c2d69a;">'.strtoupper($teamGroup).'</td>';
    //Tema SubTotal=========================================================================================
    
    //debugdata($subDataList1);
    foreach($reportArraySubTotal as $temaname=>$Temlistdate){
        
        foreach($Temlistdate as $temasubname=>$Temdate){
        if(strtoupper($teamGroup) ==strtoupper($temasubname)){
        
     
        foreach($Temdate as $listsubtotaldata){
            //foreach($listsubtotaldata as $listsubtotaldata1){
               //debugdata($listsubtotaldata);
        //RoomNights=============================       
         $contentGroup .='<td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['Totalroomnights'].'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['TotalnewTentative'].'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['Totallead_roomnights'].'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.($listsubtotaldata['Totalroomnights']+$listsubtotaldata['TotalnewTentative']+$listsubtotaldata['Totallead_roomnights']).'</td>
         ';
         $TotalSubFunnelroomnights=($listsubtotaldata['Totalroomnights']+$listsubtotaldata['TotalnewTentative']+$listsubtotaldata['Totallead_roomnights']);
         $TotalSubvariance_to_roomnights=$TotalSubFunnelroomnights-$listsubtotaldata['Totalbudget_roomnights'];
        /* $contentGroup .='
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['TotalConvertion_Ratio'].'</td>';*/
         $contentGroup .='
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['Totalbudget_roomnights'].'</td>
          <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$TotalSubvariance_to_roomnights.'</td>
          <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['Totalforecast_roomnights'].'</td>
         ';
        //RoomNights============================= 
        //Revenue=================================
               
         $contentGroup .='<td style="text-align:center;color:#000;background-color:#c2d69a;">'.round($listsubtotaldata['Totalconfimed_revenue']/100000,2).'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.round($listsubtotaldata['Totaltentative_revenue']/100000,2).'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['Totallead_revenue'].'</td>
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.(round(($listsubtotaldata['Totalconfimed_revenue']+$listsubtotaldata['Totaltentative_revenue'])/100000,2)+$listsubtotaldata['Totallead_revenue']).'</td>
         ';
         $TotalSubFunnelrevenue=(round(($listsubtotaldata['Totalconfimed_revenue']+$listsubtotaldata['Totaltentative_revenue'])/100000,2)+$listsubtotaldata['Totallead_revenue']);
         $TotalSubvariance_to_revenue=$TotalSubFunnelrevenue-round($listsubtotaldata['Totalbudget_revenue']/100000,2);
        /* $contentGroup .='
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$listsubtotaldata['TotalConvertion_Ratio'].'</td>';*/
         $contentGroup .='
         <td style="text-align:center;color:#000;background-color:#c2d69a;">'.round($listsubtotaldata['Totalbudget_revenue']/100000,2).'</td>
          <td style="text-align:center;color:#000;background-color:#c2d69a;">'.$TotalSubvariance_to_revenue.'</td>
          <td style="text-align:center;color:#000;background-color:#c2d69a;border-right: 1px solid red">'.round($listsubtotaldata['Totalforecast_revenue']/100000,2).'</td>
         ';
         
         //Revenue=================================
           // }
        }
        }
        
        }$contentGroup .='</tr>';
    }
     
    
    //Tema SubTotal=========================================================================================
 foreach($subDataList1 as $TeamName=> $subDataList){
       $contentSubGroup='';
     $contentGroup .='<tr><td style="text-align:left;">'.strtoupper($TeamName).'</td>';
     $is=0;
      //$k='<br>';
     foreach($subDataList as  $subData){
         $arraylength   =   count($subDataList);
      //debugData($subData);
     // echo $k.$is++.count($subDataList);
      $k='';
     // $sumroomnights     +=$subData['roomnights'];
      $contentSubGroup .='
      <td style="text-align:center;">'.($subData['roomnights']).'</td>
      <td style="text-align:center;">'.($subData['newTentative']).'</td>';
      /*$contentSubGroup .='<td style="text-align:center;">'.($subData['Convertion_Ratio']).'</td>
      ';*/
      $contentSubGroup .='
      <td style="text-align:center;">'.($subData['lead_roomnights']).'</td>
      <td style="text-align:center;">'.($subData['roomnights']+$subData['newTentative']+$subData['lead_roomnights']).'</td>
      
      <td style="text-align:center;">'.($subData['budget_roomnights']).'</td>
      <td style="text-align:center;">'.($subData['variance_to_budget']).'</td>
      <td style="text-align:center;">'.($subData['forecast_roomnights']).'</td>';
      
      //Revenue=================================
       $contentSubGroup .='
      <td style="text-align:center;">'.round(($subData['confimed_revenue'])/100000,2).'</td>
      <td style="text-align:center;">'.round(($subData['tentative_revenue'])/100000,2).'</td>';
      /*$contentSubGroup .='<td style="text-align:center;">'.($subData['Convertion_Ratio']).'</td>
      ';*/
      $ToFunnelrevenue=round(($subData['confimed_revenue']+$subData['tentative_revenue'])/100000,2)+$subData['lead_revenue'];
      $Tovariance_to_revenue=$ToFunnelrevenue-$subData['budget_revenue'];
      $totalFannel   =   (round(($subData['confimed_revenue']+$subData['tentative_revenue'])/100000,2));
      $contentSubGroup .='
      <td style="text-align:center;">'.($subData['lead_revenue']).'</td>
      <td style="text-align:center;">'.($totalFannel+$subData['lead_revenue']).'</td>
      
      <td style="text-align:center;">'.($subData['budget_revenue']).'</td>
      <td style="text-align:center;">'.($Tovariance_to_revenue).'</td>
      <td style="text-align:center;border-right: 1px solid red">'.($subData['forecast_revenue']).'</td>';
      //Revenue=================================
      
  
     }
     //echo '<br>'.$arraylength;
     if($arraylength<3){
     for($is=1;$is<$arraylength;$is++){
         $contentSubGroup .='
      <td style="text-align:center;">0</td>';
      //$contentSubGroup .='<td style="text-align:center;">0</td>   ';
      $contentSubGroup .='
       <td style="text-align:center;">0</td>
       <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>';
       $contentSubGroup .='
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>';
     }
     }
     if($arraylength<2){
     for($is=0;$is<=$arraylength;$is++){
         $contentSubGroup .='
      <td style="text-align:center;">0</td>';
      /*$contentSubGroup .='<td style="text-align:center;">0</td>';'
      ';*/
      $contentSubGroup .='
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>';
     
          $contentSubGroup .='
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>
      <td style="text-align:center;">0</td>';
     }
     }
     $contentGroup .=$contentSubGroup.'</tr>';
     
     
 }

}
$contentGroup .='<tr><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Total</th>';
foreach($reportArrayTotal as $ReTotal){
    
foreach($ReTotal as $ReTotal1){    
  $contentGroup .='
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totalroomnights'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['TotalnewTentative'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totallead_roomnights'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.($ReTotal1['Totalroomnights']+$ReTotal1['TotalnewTentative']+$ReTotal1['Totallead_roomnights']).'</td>';
     // $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.$ReTotal1['TotalConvertion_Ratio'].'</td>';
      $TotalTotalFunnelroomnights=($ReTotal1['Totalroomnights']+$ReTotal1['TotalnewTentative']+$ReTotal1['Totallead_roomnights']);
      $Totalvariance_to_roomnights=$TotalTotalFunnelroomnights-$ReTotal1['Totalbudget_roomnights'];
      $contentGroup .='
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totalbudget_roomnights'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$Totalvariance_to_roomnights.'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totalforecast_roomnights'].'</td>
      ';
      
      //Revenue===================================================
      $contentGroup .='
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.round($ReTotal1['Totalconfimed_revenue']/100000,2).'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.round($ReTotal1['Totaltentative_revenue']/100000,2).'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totallead_revenue'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.(round(($ReTotal1['Totalconfimed_revenue']+$ReTotal1['Totaltentative_revenue'])/100000,2)+$ReTotal1['Totallead_revenue']).'</td>';
     // $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.$ReTotal1['TotalRevenueConvertion_Ratio'].'</td>';
      
      $TotalTotalFunnelrevenue=(round(($ReTotal1['Totalconfimed_revenue']+$ReTotal1['Totaltentative_revenue'])/100000,2)+$ReTotal1['Totallead_revenue']);
      $Totalvariance_to_revenue=$TotalTotalFunnelrevenue-$ReTotal1['Totalbudget_revenue'];
      $contentGroup .='
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$ReTotal1['Totalbudget_revenue'].'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;">'.$Totalvariance_to_revenue.'</td>
      <td style="text-align:center;background-color:#C2D69A;font-weight: bold;border-right: 1px solid red">'.$ReTotal1['Totalforecast_revenue'].'</td>
      ';
      //Revenue==================================================
}
}
      $contentGroup .='</tr>';
    // $mainTotalroomnights ='';
$mainTotalnewTentative ='';
$mainTotalLead ='';
$mainTotalConvertion_Ratio ='';
$mainTotalForcasting ='';
$mainTotalBudget ='';
$mainTotalvariance_to_budget ='';
    // echo '<br>'.$TeamName;
$contentGroup .= '</table>';
}

 $content .=$contentGroup;
        
//echo $content;
//die;
if($_REQUEST['pdf']==1){

    $dompdf = new DOMPDF();


//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));




$Filename=$ReportTypeMainTitle.'PickupReport_'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}elseif($_REQUEST['pdf']==0 && $CronSet==0 && $_REQUEST['excel']==1){
            if($_REQUEST['reportType']==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='ForecastView-PickupRepor_'.date("Y-m-d H:i:s").'.xls';
                    }
                    if($_REQUEST['reportType']==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='ForecastView-BobReport_'.date("Y-m-d H:i:s").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$Filename);
        echo $test;die;
            
    
}else{
echo $content;
//echo json_encode($returnData);
}
 