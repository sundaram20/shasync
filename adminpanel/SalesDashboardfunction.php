<?php //include_once("../../config/auto_loader.php"); ?>
<?php 
//include_once("../../config/auto_loader.php");

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
	
$reportArray=array();	

if($_REQUEST['id_group_sub_master']>0 && $_REQUEST['id_group_sub_master']!='undefined'){
    
    $condTeamGroup.= " AND `".TBL_TEAM."`.`id` =".$_REQUEST['id_group_sub_master']." ";
}else{
    
    $condTeamGroup=' ';
}
  
  
 //================Team View Start ======================================================
 if((($_REQUEST['pdf']=='1' || $_REQUEST['excel']=='1')  && $Report_summaryReportType == '7' && $Report_reportType=='2') ||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '7' && $Report_reportType==2)){//Team Wise  Summary PDF reportType BOB Report
      
     $sql = "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
     
,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`


,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `Waitlisted`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newCancelled`



FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group





where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_agent_achieved`.month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."'))".$allUser." ".$condBOB." ".$condTeamGroup."
GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
       
         
     
     // echo $sql;
     //die;
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
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
  //================Team View END ======================================================
  

  
  
  
 // echo '<pre>';print_r($reportArray);
  
//die;
   
 //================Executivewise View Start ======================================================
if((($_REQUEST['pdf']=='1' || $_REQUEST['excel']=='1')  && $Report_summaryReportType == '1' && $Report_reportType=='2')||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '1' && $Report_reportType==2)){//Executivewise  Summary PDF Bob
      
    $sql = "SELECT `fs_agent_achieved`.id_company,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
      
       

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`


,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `Waitlisted`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newCancelled`



FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id  and `mst_team`.id_shop='".$_SESSION['shop']."'
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group




where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_agent_achieved`.month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) 
".$allUser." ".$condBOB." ".$condTeamGroup."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`fs_agent_achieved`.id_company Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
       
      // echo $sql;
      //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	     $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
	  
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
 // debugData($reportArray);

////Hotel Wise Start=====================================================================================================================
  
    
 if((($_REQUEST['pdf']=='1' || $_REQUEST['excel']=='1')  && $Report_summaryReportType == '2' && $Report_reportType=='2') ||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '2' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
     $sql = "SELECT `fs_hotels`.id as id_hotel,`fs_users`.name as name_executive,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team
      

,sum(case when ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.month_value else 0 end) as `confimed_revenue`

,sum(case when ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.qty else 0 end) as `newTentative`



FROM `fs_budget_master` 

LEFT JOIN `fs_hotels` ON fs_budget_master.id_hotel = fs_hotels.id
LEFT JOIN `fs_users` ON fs_budget_master.id_user = fs_users.id
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group



where `fs_budget_master`.type ='2' AND `fs_budget_master`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) 
".$allUser." ".$condBOB." ".$condTeamGroup."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_hotels`.id,`".TBL_USERS."`.name Order BY `".TBL_GROUP_MASTER."`.display_order,`fs_users`.myownteam_id";
       
       
      //`fs_budget_master`.type ='1'  Budget
      //`fs_budget_master`.type ='2'  Achived
      //echo $sql;
      //die;
     
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
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
    
      
 
  ////Hotel Wise END=====================================================================================================================
 
 // Booking Through======================================================================================================================
  
    
 if((($_REQUEST['pdf']=='1' || $_REQUEST['excel']=='1')  && $Report_summaryReportType == '5' && $Report_reportType=='2') ||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '5' && $Report_reportType==2)){//Booking Through  Summary PDF BOP
      
     $sql = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group,`".TBL_ORDERS."`.booking_hrough, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group,
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


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) 
".$allUser." ".$condBOB."  ".$condTeamGroup."
GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.booking_hrough,`".TBL_USERS."`.name Order BY `".TBL_GROUP_MASTER."`.display_order,`fs_users`.myownteam_id";
       
       
      
     // echo $sql;
      //die;
     
       $SummaryHedding='Booking Through ';
       $TaleName='Booking Through';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
	 $companyname= selectColumn(TBL_BOOKINGTHROUGH_MASTER,'name'," WHERE   id='".$rowList->booking_hrough."' AND status='1'  ");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    
	    
	    $newConfirmednewTentative=($rowList->newConfirmed);
	    if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	    
	    $emptytext7 ='empty_'.$empty7++;
	    $reportArray['Booking Through'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    $reportArray['Booking Through'][$GroupName][strtolower($companyname==''?$emptytext7:$companyname)][$rowList->name_executive]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	    }
	}  
  } 
 //Booking Throught END==================================================================================================================
 
  
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
$Recpath =explode('/',getcwd());
if (in_array("crs", $Recpath)) {
    $foldername =    "/crs";
}

if (in_array("sales", $Recpath)) {
    $foldername =    "/sales";
}
$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;


//$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
if($_REQUEST['pdf']==1 || $CronSet==1){
    if($CronSet==1){
    $resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '2'");
    $rowShop = mysqli_fetch_object($resShop);
    $logo	=	$rowShop->image;
    $pathImg='/home/inroomhub/public_html/crs';
    }
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
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
    $contentTeam .='<tr>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue(Lacs)</th>
    </tr>';
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
                         $CancelledRoomNight+=$DataList['CancelledRoomNights'];
                        
                        if (strpos($list, 'empty_') !== false) {
                        $name= '';
                        }else{$name=$list;}
                        
                        $contentTeamBody .='<tr ><td style="text-align:left;">'.strtoupper($name).'</td>
                        <td style="text-align:center;">'.$DataList['roomnights'].'</td>
                        <td style="text-align:center;">'.round($mtrArr).'</td><td style="text-align:center;">'.round($DataList['confimed_revenue']/100000,2).'</td></tr>';
                    }
            
            }
            //Team Total
            $SumTeamTotalArray= round($TotalTeamConfimedRevenue/$TotalTeamRoomNight);
            //$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#5CB4E8;">'.ucwords($TeamName).' Total</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamConfimedRevenue.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$SumTeamTotalArray.'</td></tr>';
            if($TotalTeamRoomNight>0){
                $contentTeam .='<tr><th  style="vertical-align:central;text-align:Left;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.strtoupper($TeamName).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .$TotalTeamRoomNight.'</b></th>
                 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$SumTeamTotalArray.'</b></th>
                 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($TotalTeamConfimedRevenue/100000,2).'</b></th>
               
                </tr>';
$GroupArray[$teamGroup][$TeamName]['RoomNights']=$TotalTeamRoomNight;
$GroupArray[$teamGroup][$TeamName]['CancelledRoomNights']=$CancelledRoomNight;

$GroupArray[$teamGroup][$TeamName]['RoomRevenue']=round($TotalTeamConfimedRevenue/100000,2);
$GroupArray[$teamGroup][$TeamName]['Arr']=$SumTeamTotalArray;
                
            }
            $contentTeam .=$contentTeamBody;
            $TotalTeamRoomNight='';
            $TotalTeamConfimedRevenue='';
            $SumTeamTotalArray='';$CancelledRoomNight='';
       
        
        }  
    //Group Total
    $SumTotalArray= round($TotalConfimedRevenue/$TotalRoomNight);
    if($teamGroup!='name'){
        $contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
        <td style="text-align:center;background-color:#c2d69a;">'.ucwords($teamGroup).' Total</td>
        <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNight.'</td>
        <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalArray.'</td>
        <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenue/100000,2).'</td>
        </tr>';
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
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="7" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .=    '<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
            
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">% of Room Nights Contribution</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">% of Revenue Contribution</th>
             </tr>';
                 $TotalTeamWiseRoomNightContribution='';
                // echo '<pre>';
            //print_r($GroupArray);     
            //echo '</pre>';
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
                    $TotalTeamWiseRoomRevenueContribution+=round($GroupNameArray['RoomRevenue']/100000,2);
                    $TotalTeamWiseRoomRevenueContributio2n+=($GroupNameArray['RoomRevenue']);
                     }
                 }
                 //
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)!='UNIT'){
                    $contentGroup .='<tr ><td style="text-align:left;background-color:#c2d69a;">'.strtoupper($name).'</td>
                    
                    <td style="text-align:center;background-color:#c2d69a;">'.$GroupNameArray['RoomNights'].'</td>
                    <td style="background-color:#c2d69a;text-align:center;">'.round($GroupNameArray['Arr']).'</td>
                    <td style="text-align:center;background-color:#c2d69a;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>
                    
                    <td style="text-align:center;background-color:#c2d69a;">'.round(($GroupNameArray['RoomNights']/$TotalTeamWiseRoomNightContribution)*100,2).'</td>
                    <td style="text-align:center;background-color:#c2d69a;">'.round(($GroupNameArray['RoomRevenue']/$TotalTeamWiseRoomRevenueContributio2n)*100,2).'</td>
                    </tr>';
                    foreach($GroupNameArray as $name2 => $GroupNameArray2){
                        if((strtoupper($name2) != 'ROOMNIGHTS') && (strtoupper($name2) != 'CANCELLEDROOMNIGHTS') && (strtoupper($name2) != 'ROOMREVENUE')  && (strtoupper($name2) != 'ARR')  ){
                         $contentGroup .='<tr ><td style="text-align:left;">'.strtoupper($name2).'</td>
                        
                         <td style="text-align:center;">'.$GroupNameArray2['RoomNights'].'</td>
                         <td style="text-align:center;">'.round($GroupNameArray2['Arr']).'</td>
                         <td style="text-align:center;">'.$GroupNameArray2['RoomRevenue'].'</td>
                         
                         <td style="text-align:center;">'.round(($GroupNameArray2['RoomNights']/$TotalTeamWiseRoomNightContribution)*100,2).'</td>
                         <td style="text-align:center;">'.round(($GroupNameArray2['RoomRevenue']/$TotalTeamWiseRoomRevenueContribution)*100,2).'</td>
                         </tr>';
                        }
                    }    
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    $TotalTeamWisCancelledRoomNights+=$GroupNameArray['CancelledRoomNights'];
                    
                    $TotalTeamWiseRoomNight1+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue1+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWisCancelledRoomNights1+=$GroupNameArray['CancelledRoomNights'];
                     }
                 }
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             
            
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.(($TotalTeamWiseRoomNight/$TotalTeamWiseRoomNightContribution)*100).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round(($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomRevenueContributio2n)*100).'</td>
             </tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             $TotalTeamWisCancelledRoomNights='';
             
             $contentGroup .= '</table>';
         } //THIs Withour Unit Wise 
             
             if($UnitValueIs==1){
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:"><th colspan="7" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
             $contentGroup .=    '<tr ><th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">% of Room Nights Contribution</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">% of Revenue Contribution</th>
             </tr>';
                $TotalTeamWiseRoomNightContribution='';
                 $TotalTeamWiseRoomRevenueContribution='';
                 $TotalTeamWiseRoomRevenueContribution2='';
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['RoomNights'];
                    
                      $TotalTeamWiseRoomRevenueContribution+=round($GroupNameArray['RoomRevenue']/100000,2);
                      $TotalTeamWiseRoomRevenueContribution2+=$GroupNameArray['RoomRevenue'];
                     
                     
                    
                     }
                 }
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){$contentGroup .='<tr ><td style="text-align:left;background-color:#4F6228;color:#fff;">'.strtoupper($name).'</td>
                     
                     <td style="text-align:center;background-color:#4F6228;color:#fff;">'.$GroupNameArray['RoomNights'].'</td>
                     <td style="background-color:#4F6228;text-align:center;color:#fff;">'.round($GroupNameArray['Arr']).'</td>
                     <td style="text-align:center;background-color:#4F6228;color:#fff;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>
                     
                     <td style="text-align:center;background-color:#4F6228;color:#fff;">'.round(($GroupNameArray['RoomNights']/$TotalTeamWiseRoomNightContribution)*100,2).'</td>
                     <td style="text-align:center;background-color:#4F6228;color:#fff;">'.round(($GroupNameArray['RoomRevenue']/$TotalTeamWiseRoomRevenueContribution2)*100,2).'</td>
                     </tr>';
                    foreach($GroupNameArray as $name2 => $GroupNameArray2){
                        if((strtoupper($name2) != 'ROOMNIGHTS') && (strtoupper($name2) != 'CANCELLEDROOMNIGHTS') && (strtoupper($name2) != 'ROOMREVENUE')  && (strtoupper($name2) != 'ARR')  ){
                         $contentGroup .='<tr ><td style="text-align:left;">'.strtoupper($name2).'</td>
                         
                         <td style="text-align:center;">'.$GroupNameArray2['RoomNights'].'</td>
                         <td style="text-align:center;">'.round($GroupNameArray2['Arr']).'</td>
                         <td style="text-align:center;">'.$GroupNameArray2['RoomRevenue'].'</td>
                         
                         <td style="text-align:center;">'.round(($GroupNameArray2['RoomNights']/$TotalTeamWiseRoomNightContribution)*100,2).'</td>
                         <td style="text-align:center;">'.round(($GroupNameArray2['RoomRevenue']/$TotalTeamWiseRoomRevenueContribution)*100,2).'</td>
                         </tr>';
                        }
                    }    
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    $TotalTeamWisCancelledRoomNights+=$GroupNameArray['CancelledRoomNights'];
                    
                    $TotalTeamWiseRoomNight1+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue1+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWisCancelledRoomNights1+=$GroupNameArray['CancelledRoomNights'];
                         
                     }
                 }
             $SumTotalTeamWiseArray= round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight);
             
             
             $SumTotalTeamWiseArrayArr =round(($TotalTeamWiseConfimedRevenue2+$TotalTeamWiseConfimedRevenue1)/($TotalTeamWiseRoomNight2+$TotalTeamWiseRoomNight1));
              $SumTotalTeamWiseArrayArrtContribution =(($TotalTeamWiseRoomNight/$TotalTeamWiseRoomNightContribution)*100);
              
              
              $SumTotalTeamWiseArrayArrtContribution2 =(($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomRevenueContribution2)*100);
              
              
             //$contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">Total </td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWisCancelledRoomNights2.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td><td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#c2d69a;">Total </td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.(($TotalTeamWiseRoomNight/$TotalTeamWiseRoomNightContribution)*100).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.(($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomRevenueContribution2)*100).'</td>
             
             </tr>';
        	$contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;font-size:13px;">
        	<td style="text-align:center;background-color:#c2d69a;">Grand Total </td>
        	
        	<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.($TotalTeamWiseRoomNight2+$TotalTeamWiseRoomNight1).'</td>
            <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArrayArr.'</td>
            <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round(($TotalTeamWiseConfimedRevenue2+$TotalTeamWiseConfimedRevenue1)/100000,2).'</td>
            	
        	<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArrayArrtContribution.'</td>
        		<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArrayArrtContribution2.'</td>
        	</tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             
             $contentGroup .= '</table>';
             }
             
             
         } //Teamview End
         
         
         
         $content .=$contentGroup;
         $content .=$contentTeam;
         
         
         $contentGroup='';
         //$contentTeam='';
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
}elseif($_REQUEST['pdf']==0 && $CronSet==0 && $_REQUEST['excel']==1){
    if($Report_reportType==1){
               // $ReportTypeMainTitle ='PICKUP ';
                $Filename='TableView-PickupReport_'.date("Y-m-d").'.xls';
            }
            if($Report_reportType==2){
                $ReportTypeMainTitle ='BOB ';
                 $Filename='TableView-BobReport_'.date("Y-m-d").'.xls';
            }
$test=$content;
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$Filename");
echo $test;die;
    
    }elseif($CronSet==1){
    
                //mail("shashafeer@gmail.com","My subject fa1 Content-GroupName=",$content);
                if($Report_reportType==1){
               // $ReportTypeMainTitle ='PICKUP ';
                $Filename='TableView-PickupReport_'.date("Y-m-d");
            }
            if($Report_reportType==2){
                $ReportTypeMainTitle ='BOB ';
                 $Filename='TableView-BobReport_'.date("Y-m-d");
            }
            
                //$Filename='TableView-PickupReport_'.date("Y-m-d");
               // echo $content;die;
               pdfGeneratorAttach($content, $Filename);
                
}else{
echo $content;
//echo json_encode($returnData);
}
}




//===============================================================================================================================================================
function CompareViewfunction($Report_period,$Report_id_hotel,$Report_id_group_master,$Report_reportType,$Report_viewMonthwise,$Report_summaryReportType,$CronSet,$ComparePeriodDate,$CurrentFinancialYearDate){
global $connNew;
//echo $ComparePeriodDate.'=='.$CurrentFinancialYear;die;
 $Diffrence='';
  $CompareFinancialYear	=	explode('-',$ComparePeriodDate);
  $CurrentFinancialYear	=	explode('-',$CurrentFinancialYearDate);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);

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

if($_REQUEST['id_group_sub_master']>0 && $_REQUEST['id_group_sub_master']!='undefined'){
    
    $condTeamGroup.= " AND `".TBL_TEAM."`.`id` =".$_REQUEST['id_group_sub_master']." ";
}else{
    
    $condTeamGroup=' ';
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
		$cond = "  where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
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
$reportArray=array();
//LASTYEAR SQL START >>================================================================================================================	
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' )){//Team Wise  Summary PDF reportType Pickup Report
$GroupOrder =  "  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";

}
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 )&& $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' )){//Executivewise  Summary PDF PICK ASE
$GroupOrder = "  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`".TBL_COMPANY."`.id_company Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
}
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' )){//Hotel Wise  Summary PDF PICKUP Report
$GroupOrder = "  GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_ORDERS."`.id_hotel,`".TBL_USERS."`.name Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
}


$FromLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)));
$ToLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)));

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
       
            ".$cond.$allUser.$condTeamGroup.$GroupOrder ." ";// Order BY  `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
}            
            
 if($Report_reportType==2){           
     
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' )){ //Hotel Wise
     $lastYearPickUpsql = "SELECT `fs_hotels`.id as id_hotel,`fs_users`.name as name_executive,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team
,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_budget_master`.month_value else 0 end) as `confimed_revenue`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_budget_master`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_budget_master`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_budget_master`.qty else 0 end) as `newTentative`




FROM `fs_budget_master` 

LEFT JOIN `fs_hotels` ON fs_budget_master.id_hotel = fs_hotels.id
LEFT JOIN `fs_users` ON fs_budget_master.id_user = fs_users.id
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where  `fs_budget_master`.type ='2' AND `fs_budget_master`.`id_shop` = '".addslashes($_SESSION['shop'])."' ".$allUser." ".$condBOB.$condTeamGroup."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_hotels`.id,`".TBL_USERS."`.name Order BY `fs_users`.myownteam_id";
       
   
    //`fs_budget_master`.type ='1'  Budget
      //`fs_budget_master`.type ='2'  Achived
    
}else{
   $lastYearPickUpsql ="SELECT fs_agent_achieved.id_company,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
      

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group

 ".$cond.$allUser.$condTeamGroup.$GroupOrder;
//where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_order_detail` .dated BETWEEN '".$ToLastYearDate."' And '".$ToLastYearDate."')) $condhotelAccess 
//GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";

//$condhotelAccess";
}         
 }       
            
            
            
       
      //echo $lastYearPickUpsql;
      //die;
       
       $resultlastYearList = mysqli_query($connNew,$lastYearPickUpsql);
       $empty7=0;
	while($rowlastYearList = mysqli_fetch_object($resultlastYearList)){
	   // print_r($rowlastYearList);
	   // echo '<br><br><br>';
if(($_REQUEST['pdf']==1 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' )){   //Team Wise  Summary PDF reportType Pickup Report

    $companyname= strtolower(selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowlastYearList->MyOwnteam."'"));
    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowlastYearList->id_default_group."'");
    $arrayNameSet   =   'Team Wise';
    $SummaryHedding='Team Wise ';
    $TaleName='Team Wise Source';
    
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
    	   //=========================================
}
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' )){   //Executivewise  Summary PDF PICK ASE

    $companyname =$rowlastYearList->name_executive;
    //$BusinessSourceName= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowlastYearList->id_hotel.' ');
    $BusinessSourceName= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowlastYearList->id_company."'");
    $arrayNameSet   =   'Executivewise';
    	
    	
    	$companypincode= selectColumn(TBL_COMPANY,'postcode'," WHERE `id_company` = '".$rowlastYearList->id_company."'");
	  $companypincode = $companypincode!=''?'-'.$companypincode:'';
	  $companyCity = $companyCity!=''?','.ucwords(strtolower($companyCity)):'';
    	
	$SummaryHedding='Executivewise  ';
       $TaleName='Executivewise';
       
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
    	   //=========================================
       
}
if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' )){   //Hotel Wise  Summary PDF PICKUP Report

    $companyname= strtolower(selectColumn(TBL_HOTELS,'name','WHERE id='.$rowlastYearList->id_hotel.' '));
    $BusinessSourceName=  $rowlastYearList->name_executive;
    $arrayNameSet   =   'Hotelwise';
    $SummaryHedding='Hotel Wise ';
    $TaleName='Hotel Wise Source';
    
    
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
    	   //=========================================
}	    
	  
	    
	} 
	
	
	
	

	
	

 
	
	
	
	//echo '<pre>';print_r($reportArray);
    //die;

//LASTYEAR SQL END >>  ================================================================================================================
	
	
	

	
	
	
	
	
	
	


  //	echo '<pre>';print_r($reportArray);
 if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_reportType==2 && $Report_summaryReportType == '1') || ($Report_summaryReportType == '1' && $Report_reportType==2)){//Team Wise  Summary PDF reportType BOB Report
      
     $sql = "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
       

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$allUser." ".$condBOB.$condTeamGroup."   GROUP BY `".TBL_USERS."`.myownteam_id,`".TBL_COMPANY."`.id_default_group Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
       
         
     
      //echo $sql;
      //die;
       $SummaryHedding='Team Wise ';
       $TaleName='Team Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	    $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
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
  

  
  
   

if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_reportType==2 && $Report_summaryReportType == '2') || ($Report_summaryReportType == '2' && $Report_reportType==2)){//Executivewise  Summary PDF Bob
      
    $sql = "SELECT `fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
      

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$allUser." ".$condBOB.$condTeamGroup."  
GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id,`fs_company`.id_company 

Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
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
	 // $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	 
	 $companyCity= selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$rowList->id_company."'");
	  $companypincode= selectColumn(TBL_COMPANY,'postcode'," WHERE `id_company` = '".$rowList->id_company."'");
	  $companypincode = $companypincode!=''?'-'.$companypincode:'';
	  $companyCity = $companyCity!=''?','.ucwords(strtolower($companyCity)):'';
	 
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


 if((($_REQUEST['pdf']==1 || $_REQUEST['excel']==1 ) && $Report_reportType==2 && $Report_summaryReportType == '3') || ($Report_summaryReportType == '3' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
     $sql = "SELECT `fs_hotels`.id as id_hotel,`fs_users`.name as name_executive,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team
      
,sum(case when ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.month_value else 0 end) as `confimed_revenue`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.month_value else 0 end) as `tentative_revenue`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_budget_master`.qty else 0 end) as `newTentative`





FROM `fs_budget_master` 

LEFT JOIN `fs_hotels` ON fs_budget_master.id_hotel = fs_hotels.id
LEFT JOIN `fs_users` ON fs_budget_master.id_user = fs_users.id
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where  `fs_budget_master`.type ='2' AND `fs_budget_master`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND ( ( `fs_budget_master` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) ".$allUser." ".$condBOB.$condTeamGroup."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_hotels`.id,`".TBL_USERS."`.name Order BY `fs_users`.myownteam_id";
       
       // `fs_budget_master`.type ='1' AND Budget
       // `fs_budget_master`.type ='2' AND Achived
    //  echo $sql;
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
$Recpath =explode('/',getcwd());
if (in_array("crs", $Recpath)) {
    $foldername =    "/crs";
}

if (in_array("sales", $Recpath)) {
    $foldername =    "/sales";
}
$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;

//$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
if($_REQUEST['pdf']==1 || $CronSet==1){
    if($CronSet==1){
    $resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '2'");
    $rowShop = mysqli_fetch_object($resShop);
    $logo	=	$rowShop->image;
    $pathImg='/home/inroomhub/public_html/crs';
    }
    
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	   $content .=    '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="5" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; 
}
foreach($reportArray as $maintitle=>$mainDatalist){
    
    $contentTeam ='<table class="table table-striped text-center">';
	$contentTeam .='<tr><th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' Compare '.$maintitle.'  Breakup For Period '.$reportPeriod.'</b></th></tr>';
    $contentTeam .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="4" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th  colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th></tr>
    ';
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	 $GroupArray=array(); 
    foreach($mainDatalist as $teamGroup=>$subDataList1){
        
//echo $teamGroup;
if($teamGroup!='name'){
 $contentTeam .='<tr style="vertical-align:central;"><th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Group '.$teamGroup.'</b></th></tr>';
  $contentTeam .='<tr style="vertical-align:central;">
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
  
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Variance</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>
  
   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>
  
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.' Lacs </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>
 
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
                    if($DataList['roomnights']>0 ){
                    $mtrArr =($DataList['confimed_revenue']/$DataList['roomnights']);
                    }else{
                    $mtrArr =0;
                    }
                      if($DataList['lastYearroomnights'] >0 ){
                    $lastYearComparViewmtrArr =($DataList['lastYearconfimed_revenue']/$DataList['lastYearroomnights']);
                    }else{
                    $lastYearComparViewmtrArr =0;
                    }
                       
                      $GolyListData      =    $lastYearComparViewmtrArr>0?round((($mtrArr-$lastYearComparViewmtrArr)/$lastYearComparViewmtrArr)*100,2):'0';
                        $TotalRoomNight+=$DataList['roomnights'];
                        $TotalConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        $TotalTeamRoomNight+=$DataList['roomnights'];
                        $TotalTeamConfimedRevenue+=$DataList['confimed_revenue'];
                        
                        
                        $TotalRoomNightlastYear+=$DataList['lastYearroomnights'];
                        $TotalConfimedRevenuelastYear+=$DataList['lastYearconfimed_revenue'];
                        
                        $TotalTeamRoomNightlastYear+=$DataList['lastYearroomnights'];
                        $TotalTeamConfimedRevenuelastYear+=$DataList['lastYearconfimed_revenue'];
                        
                        
                        $TotalRoomNighttopsheet+=$DataList['roomnights'];
                        
                        if (strpos($list, 'empty_') !== false) {
                                $name= $list;
                        }else{
                            
                            
                            $name=$list;
                            
                            
                            
                        }
                        //echo '<br>'.$DataList['roomnights'].'-'.$DataList['lastYearroomnights'];
                        
                        
                        $golyRowWiseRoomNights    = $DataList['lastYearroomnights']>0?round((($DataList['roomnights']-$DataList['lastYearroomnights'])/$DataList['lastYearroomnights']) *100,2):'0';
                        $GLY1   =   round($DataList['lastYearconfimed_revenue']/100000,2);
                        $GCY1   =   round($DataList['confimed_revenue']/100000,2);
                         $golyRowWiseConfimedRevenue    = $GLY1>0?round((($GCY1 -$GLY1)/$GLY1) *100,2):'0';
                       // $golyRowWiseConfimedRevenue    = round((($DataList['confimed_revenue']-$DataList['lastYearconfimed_revenue'])/$DataList['lastYearconfimed_revenue']) *100,2);
                        $golyRowWiseConfimedRevenuevColor = $golyRowWiseConfimedRevenue>0?"":"color:red;";
                        
                        $golyRowWiseRoomNightsColor = $golyRowWiseRoomNights>=0?"":"color:red;";
                        $ColorGolyListData =$GolyListData>0?"":"color:red;";
                        $golyRowWiseConfimedRevenuevColor1=$golyRowWiseConfimedRevenue>=0?"":"color:red;";
                        $golyRowWiseRoomNightsColor1=$golyRowWiseRoomNights>=0?"":"color:red;";
                        $golyRowWiseRoomNightsVariantColor1= ($DataList['roomnights']-$DataList['lastYearroomnights'])>=0?"":"color:red;";
                        $contentTeamBody .='<tr >';
                        $contentTeamBody .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                        
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['lastYearroomnights']>0 ? $DataList['lastYearroomnights']:'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['roomnights']>0 ?$DataList['roomnights']:'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;'.$golyRowWiseRoomNightsVariantColor1.'">'.($DataList['roomnights']-$DataList['lastYearroomnights']).'</td>';
                        $contentTeamBody .='<td style="text-align:center;'.$golyRowWiseRoomNightsColor1.'">'.$golyRowWiseRoomNights.'</td>';
                        
                        $contentTeamBody .='<td style="text-align:center;background-color:#e9f7cd">'.round($lastYearComparViewmtrArr).'</td>';
                        $contentTeamBody .='<td style="text-align:center;background-color:#e9f7cd">'.round($mtrArr).'</td>';
                         $contentTeamBody .='<td style="text-align:center;background-color:#e9f7cd;'.$ColorGolyListData.'">'.round($GolyListData).'</td>';
                         
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['lastYearconfimed_revenue']>0?round($DataList['lastYearconfimed_revenue']/100000,2):'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;">'.($DataList['confimed_revenue']>0?round($DataList['confimed_revenue']/100000,2):'0').'</td>';
                        $contentTeamBody .='<td style="text-align:center;'.$golyRowWiseConfimedRevenuevColor1.'">'.$golyRowWiseConfimedRevenue.'</td>';
                      
                       
                        
                        $contentTeamBody .='</tr>';
                    }
            
            }
            //Team Total
            $SumTeamTotalCYArray= $TotalTeamRoomNight>0?round($TotalTeamConfimedRevenue/$TotalTeamRoomNight):'0';
            $SumTeamTotalLYArray= $TotalTeamRoomNightlastYear>0?round($TotalTeamConfimedRevenuelastYear/$TotalTeamRoomNightlastYear):'0';
            $SumTeamTotalOccpanGoly      =    $SumTeamTotalLYArray>0?round((($SumTeamTotalCYArray-$SumTeamTotalLYArray)/$SumTeamTotalLYArray)*100,2):'0';
            
            
            $SumTeamTotallastYearArray= $TotalTeamRoomNightlastYear>0?round($TotalTeamConfimedRevenuelastYear/$TotalTeamRoomNightlastYear):'0';
            
            
            $SumTeamTotalgolyRoomNights= $TotalTeamRoomNightlastYear>0?round((($TotalTeamRoomNight-$TotalTeamRoomNightlastYear)/$TotalTeamRoomNightlastYear) *100,2):'0';
            
            
            $TotalGCy1  =   round($TotalTeamConfimedRevenue/100000,2);
            $TotalGLy1  = round($TotalTeamConfimedRevenuelastYear/100000,2);
            $SumTeamTotalgolyConfimedRevenue=$TotalGLy1>0?round((($TotalGCy1-$TotalGLy1)/$TotalGLy1) *100,2):'0';
            //$SumTeamTotalgolyConfimedRevenue=round((($TotalTeamConfimedRevenue-$TotalTeamConfimedRevenuelastYear)/$TotalTeamConfimedRevenuelastYear) *100,2);
            
            $SumTeamTotalgolyConfimedRevenueColor=$SumTeamTotalgolyConfimedRevenue>=0?"":"color:red;";
           $SumTeamTotalgolyRoomNightsColor=$SumTeamTotalgolyRoomNights>=0?"":"color:red;";
           $SumTeamTotalgolyRoomNightsVariantColor2=($TotalTeamRoomNight-$TotalTeamRoomNightlastYear)>=0?"":"color:red;";
           $ColorSumTeamTotalOccpanGoly  =$SumTeamTotalOccpanGoly>=0?"":"color:red;";
            //$contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:center;background-color:#5CB4E8;">'.ucwords($TeamName).' Total</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamRoomNight.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$TotalTeamConfimedRevenue.'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;text-align:center;">'.$SumTeamTotalArray.'</td></tr>';
            if($TotalTeamRoomNight>0 || $TotalRoomNightlastYear>0){
                $contentTeam .='<tr>
                <th  style="vertical-align:central;text-align:Left;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.strtoupper($TeamName).'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.$TotalTeamRoomNightlastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .$TotalTeamRoomNight.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important;'.$SumTeamTotalgolyRoomNightsVariantColor2.'"><b>' .($TotalTeamRoomNight-$TotalTeamRoomNightlastYear).'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; font-size:12px !important;'.$SumTeamTotalgolyRoomNightsColor.'"><b>'.$SumTeamTotalgolyRoomNights.'</b></th>';
                
                $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$SumTeamTotalLYArray.'</b></th>
                 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$SumTeamTotalCYArray.'</b></th>
                 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important;'.$ColorSumTeamTotalOccpanGoly.'"><b> '.$SumTeamTotalOccpanGoly.'</b></th>';
                
                
                $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.round($TotalTeamConfimedRevenuelastYear/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($TotalTeamConfimedRevenue/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; font-size:12px !important;'.$SumTeamTotalgolyConfimedRevenueColor.'"><b>'.$SumTeamTotalgolyConfimedRevenue.'</b></th>';
                
               
                
                $contentTeam .='</tr>';
                
                
             //GroupBuilt------1
        $SumTotalCYArray= $TotalTeamRoomNight>0?round($TotalTeamConfimedRevenue/$TotalTeamRoomNight):'0';
     $SumTotalLYArray= $TotalTeamRoomNightlastYear>0?round($TotalTeamConfimedRevenuelastYear/$TotalTeamRoomNightlastYear):'0';
     
    $GroupArray[$teamGroup][$TeamName]['RoomNights']=$TotalTeamRoomNight;
    $GroupArray[$teamGroup][$TeamName]['RoomRevenue']=$TotalTeamConfimedRevenue;
    
    $GroupArray[$teamGroup][$TeamName]['lastYearroomnights']=$TotalTeamRoomNightlastYear;
    $GroupArray[$teamGroup][$TeamName]['lastYearconfimed_revenue']=$TotalTeamConfimedRevenuelastYear;
    
    
    $GroupArray[$teamGroup][$TeamName]['ArrCY']=$SumTotalCYArray;
     $GroupArray[$teamGroup][$TeamName]['ArrLY']=$SumTotalLYArray;
     $GroupArray[$teamGroup][$TeamName]['ArrGoly']      =    $SumTotalLYArray>0?round((($SumTotalCYArray-$SumTotalLYArray)/$SumTotalLYArray)*100,2):'0';
     //GroupBuilt------    
                
                
                
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
    $SumTotalLYArray= $TotalRoomNightlastYear>0?round($TotalConfimedRevenuelastYear/$TotalRoomNightlastYear,2):'0';
    $SumTotalCYArray= $TotalRoomNight>0?round($TotalConfimedRevenue/$TotalRoomNight):'0';
    $OccpanGolyMTD      =    $SumTotalLYArray>0?round((($SumTotalCYArray-$SumTotalLYArray)/$SumTotalLYArray)*100,2):'0';
    
    $SumTotallastYearArray= $TotalRoomNightlastYear>0?round($TotalConfimedRevenuelastYear/$TotalRoomNightlastYear):'0';
    
    $SumTotalGolyRoomNights= $TotalRoomNightlastYear>0?round((($TotalRoomNight-$TotalRoomNightlastYear)/$TotalRoomNightlastYear) *100,2):'0';
    
    
    
    $SumTotalGolyConfimedRevenue= $TotalConfimedRevenuelastYear>0?round((($TotalConfimedRevenue-$TotalConfimedRevenuelastYear)/$TotalConfimedRevenuelastYear) *100,2):'0';
    
     $SumTotalGolyRoomNightsColor=$SumTotalGolyRoomNights>=0?"":"color:red;";
     
     $SumTotalGolyConfimedRevenueColor1=$SumTotalGolyConfimedRevenue>=0?"":"color:red;";
     $SumTotalGolyOccpanGolyMTDColor=$OccpanGolyMTD>=0?"":"color:red;";
     
     $SumTotalGolyOccpanVariantColor = ($TotalRoomNight-$TotalRoomNightlastYear)>=0?"":"color:red;";
    if($teamGroup!='name'){
    $contentTeam .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
    <td style="text-align:center;background-color:#c2d69a;">'.ucwords($teamGroup).' Total</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNightlastYear.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalRoomNight.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyOccpanVariantColor.'">'.($TotalRoomNight-$TotalRoomNightlastYear).'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyRoomNightsColor.'">'.$SumTotalGolyRoomNights.'</td>';
    
     $contentTeam .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalLYArray.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalCYArray.'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyOccpanGolyMTDColor.'">'.$OccpanGolyMTD.'</td>';
    
    
    $contentTeam .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenuelastYear/100000,2).'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalConfimedRevenue/100000,2).'</td>
    <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyConfimedRevenueColor1.'">'.$SumTotalGolyConfimedRevenue.'</td>';
    
    
   
    
    $contentTeam .='</tr>';
    }
     //GroupBuilt------2
     $SumTotalCYArray= $TotalRoomNight>0?round($TotalConfimedRevenue/$TotalRoomNight):'0';
     $SumTotalLYArray= $TotalRoomNightlastYear>0?round($TotalConfimedRevenuelastYear/$TotalRoomNightlastYear):'0';
     
    $GroupArray[$teamGroup]['RoomNights']=$TotalRoomNight;
    $GroupArray[$teamGroup]['RoomRevenue']=$TotalConfimedRevenue;
    
    $GroupArray[$teamGroup]['lastYearroomnights']=$TotalRoomNightlastYear;
    $GroupArray[$teamGroup]['lastYearconfimed_revenue']=$TotalConfimedRevenuelastYear;
    
    
    $GroupArray[$teamGroup]['ArrCY']=$SumTotalCYArray;
     $GroupArray[$teamGroup]['ArrLY']=$SumTotalLYArray;
     $GroupArray[$teamGroup]['ArrGoly']      =    $SumTotalLYArray>0?round((($SumTotalCYArray-$SumTotalLYArray)/$SumTotalLYArray)*100,2):'0';
     //GroupBuilt------
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
        	 <th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="4" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th></tr>
    ';
    
    $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Variance </th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
             
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'</th>
               <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
               
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
             
             $contentGroup .=    '</tr>';
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
                     $GolyGroupRoomNights=$GroupNameArray['lastYearroomnights']>0?round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['lastYearroomnights']) *100,2):'0';
                     
                     
                     
                     $GlastYearconfimed_revenue =   round($GroupNameArray['lastYearconfimed_revenue']/100000,2);
                     $GRoomRevenue  =   round($GroupNameArray['RoomRevenue']/100000,2);
                     $GolyGroupRoomRevenue=$GlastYearconfimed_revenue>0?round((($GRoomRevenue-$GlastYearconfimed_revenue)/$GlastYearconfimed_revenue) *100,2):'0';
                     
                     $GolyGroupRoomRevenueColorW=$GolyGroupRoomRevenue>=0?"":"color:red;";
                     $GolyGroupRoomRevenueColorLastYear=$GolyGroupRoomNights>=0?"":"color:red;";
                     $ArrGolyColor  =   round($GroupNameArray['ArrGoly'])>=0?"":"color:red;";
                     
                     $vaColor=($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;background-color:#C2D69A;color:#000;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;'.$vaColor.'">'.($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights']).'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;'.$GolyGroupRoomRevenueColorLastYear.'">'.$GolyGroupRoomNights.'</td>';
                    
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.round($GroupNameArray['ArrLY']).'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.round($GroupNameArray['ArrCY']).'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;'.$ArrGolyColor.'">'.round($GroupNameArray['ArrGoly']).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.round($GroupNameArray['lastYearconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;background-color:#C2D69A;'.$GolyGroupRoomRevenueColorW.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                    
                    
                    
                    $contentGroup .='</tr>';
                    
                    foreach($GroupNameArray as $name2 => $GroupNameArray2){
                      $teamgrouthColor=  round((($GroupNameArray2['RoomNights']-$GroupNameArray2['lastYearroomnights'])/$GroupNameArray2['lastYearroomnights'])*100,2)>=0?"":"color:red;";
                      
                    $TeamColorlastYearconfimed_revenue   =     round((($GroupNameArray2['RoomRevenue']-$GroupNameArray2['lastYearconfimed_revenue'])/$GroupNameArray2['lastYearconfimed_revenue'])*100,2)>=0?"":"color:red;";
                    $TeamcolorArrGoly = $GroupNameArray2['ArrGoly']>=0?"":"color:red;"; 
                    $TeamvarintColor    =   ($GroupNameArray2['RoomNights']-$GroupNameArray2['lastYearroomnights'])>=0?"":"color:red;";
                    $GLYList1    =   round($GroupNameArray2['lastYearconfimed_revenue']/100000,2);
                    $GCYList1    =   round($GroupNameArray2['RoomRevenue']/100000,2);
                    $ListGolyMain   =   $GLYList1>0?round((($GCYList1-$GLYList1)/$GLYList1)*100,2):'0';
                    
                    
                    
                    if((strtoupper($name2) != 'ROOMNIGHTS') && (strtoupper($name2) != 'LASTYEARROOMNIGHTS') && (strtoupper($name2) != 'LASTYEARCONFIMED_REVENUE')  && (strtoupper($name2) != 'ARRCY') && (strtoupper($name2) != 'ARRLY') && (strtoupper($name2) != 'ARRGOLY') && (strtoupper($name2) !='ROOMREVENUE') ){
                         $contentGroup .='<tr >
                         <td style="text-align:left;">'.strtoupper($name2).'</td>
                         <td style="text-align:center;">'.$GroupNameArray2['lastYearroomnights'].'</td>
                         <td style="text-align:center;">'.$GroupNameArray2['RoomNights'].'</td>
                         <td style="text-align:center;'.$TeamvarintColor.'">'.($GroupNameArray2['RoomNights']-$GroupNameArray2['lastYearroomnights']).'</td>
                         
                         <td style="text-align:center;'.$teamgrouthColor.'">'.($GroupNameArray2['lastYearroomnights']>0?round((($GroupNameArray2['RoomNights']-$GroupNameArray2['lastYearroomnights'])/$GroupNameArray2['lastYearroomnights'])*100,2):'0').'</td>
                         
                         <td style="text-align:center;">'.round($GroupNameArray2['ArrLY']).'</td>
                         <td style="text-align:center;">'.round($GroupNameArray2['ArrCY']).'</td>
                         <td style="text-align:center;'.$TeamcolorArrGoly.'">'.$GroupNameArray2['ArrGoly'].'</td>
                         
                         
                         <td style="text-align:center;">'.round($GroupNameArray2['lastYearconfimed_revenue']/100000,2).'</td>
                         <td style="text-align:center;">'.round($GroupNameArray2['RoomRevenue']/100000,2).'</td>
                         <td style="text-align:center;'.$TeamColorlastYearconfimed_revenue.'">'.$ListGolyMain.'</td>
                         
                         
                         </tr>';
                        }
                    } 
                    
                    
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearconfimed_revenue'];
                    
                  }
                 }
                 
              $SumTotalTeamWiseLYArray= $TotalTeamWiseRoomNightlastYear>0?round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear):'0';
             $SumTotalTeamWiseCyArray= $TotalTeamWiseRoomNight>0?round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight):'0';
            $SumTotalTeamWiseGoly=  $SumTotalTeamWiseLYArray>0?round((($SumTotalTeamWiseCyArray-$SumTotalTeamWiseLYArray)/$SumTotalTeamWiseLYArray)*100,2):'0';
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights= $TotalTeamWiseRoomNightlastYear>0?round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNightlastYear) *100,2):'0';
              $SumTotalGolyTeamConfimedRevenue=$TotalTeamWiseConfimedRevenuelastYear>0?round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenuelastYear) *100,2):'0';
              
              
               $SumTotalGolyTeamRoomNightsColor=$SumTotalGolyTeamRoomNights>=0?"":"color:red;";
                     $SumTotalGolyTeamConfimedRevenueColor=$SumTotalGolyTeamConfimedRevenue>=0?"":"color:red;";
                   $varColor=  ($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)>0?"":"color:red;";
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$varColor.'">'.($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamRoomNightsColor.'">'.$SumTotalGolyTeamRoomNights.'</td>';
             
             $contentGroup .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseLYArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseCyArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseGoly.'</td>';
             
             $contentGroup .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamConfimedRevenueColor.'">'.$SumTotalGolyTeamConfimedRevenue.'</td>';
             
             
             $contentGroup .='</tr>';
        	 $SumTotalTeamWiseArray='';
        	 //===
             //$TotalTeamWiseConfimedRevenue='';
             //$TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
        	    }
             
             if($UnitValueIs==1){
             //===================================================
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="10" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
             
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
     <th  colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th>
     <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th></tr>
   ';
    
  /*  $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>';
             
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">LY</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">CY</th>
               <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>';
               
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>';
             $contentGroup .=    '</tr>';
    */         
 $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'</th>
           
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
             
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'</th>
              <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'</th>
               <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
               
             $contentGroup .=    '<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$ComparePeriodDate.'(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">'.$CurrentFinancialYearDate.'(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GROWTH %</th>';
             
             $contentGroup .=    '</tr>';
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['lastYearroomnights'];
                     }
                 }
   
   
   
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                     $GolyGroupRoomNights=$GroupNameArray['lastYearroomnights']>0?round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['lastYearroomnights']) *100,2):'0';
                     $GolyGroupRoomRevenue=$GroupNameArray['lastYearconfimed_revenue']>0?round((($GroupNameArray['RoomRevenue']-$GroupNameArray['lastYearconfimed_revenue'])/$GroupNameArray['lastYearconfimed_revenue']) *100,2):'0';
                     
                     $GolyGroupRoomRevenueColor=$GolyGroupRoomRevenue>=0?"":"color:red;";
                      $GolyGroupRoomRevenueColorLastYear=$GolyGroupRoomNights>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColorLastYear.'">'.$GolyGroupRoomNights.'</td>';
                    
                    
                     $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['ArrLY']).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['ArrCY']).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['ArrGoly']).'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['lastYearconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColor.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                  
                    
                    
                    $contentGroup .='</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearconfimed_revenue'];
                    
                  }
                 }
                 
             $SumTotalTeamWiseLYArray= $TotalTeamWiseRoomNightlastYear>0?round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear):'0';
             $SumTotalTeamWiseCyArray= $TotalTeamWiseRoomNight>0?round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight):'0';
            $SumTotalTeamWiseGoly=  $SumTotalTeamWiseLYArray>0?round((($SumTotalTeamWiseCyArray-$SumTotalTeamWiseLYArray)/$SumTotalTeamWiseLYArray)*100,2):'0';
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights=$TotalTeamWiseRoomNightlastYear>0?round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNightlastYear) *100,2):'0';
              $SumTotalGolyTeamConfimedRevenue=$TotalTeamWiseConfimedRevenuelastYear>0?round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenuelastYear) *100,2):'0';
              
              
               $SumTotalGolyTeamRoomNightsColor=$SumTotalGolyTeamRoomNights>=0?"":"color:red;";
                     $SumTotalGolyTeamConfimedRevenueColor=$SumTotalGolyTeamConfimedRevenue>=0?"":"color:red;";
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamRoomNightsColor.'">'.$SumTotalGolyTeamRoomNights.'</td>';
             
               $contentGroup .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseLYArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseCyArray.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseGoly.'</td>';
             
             $contentGroup .='<td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamConfimedRevenueColor.'">'.$SumTotalGolyTeamConfimedRevenue.'</td>';
             
             
             
              $contentGroup .='</tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
             
             }
             
             
         }
         
         $content .=$contentGroup;
         $content .=$contentTeam;
         $contentGroup='';
         //$contentTeam='';
     //Office Team Wise For Period End
     
}
//debugData($_REQUEST);die;
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
}elseif($_REQUEST['pdf']==0 && $CronSet==0 && $_REQUEST['excel']==1){
            if($Report_reportType==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='CompareView-PickupReport_'.date("Y-m-d").'.xls';
                    }
                    if($Report_reportType==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='CompareView-BobReport_'.date("Y-m-d").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename");
        echo $test;die;
            
    
    
}elseif($CronSet==1){
    if($Report_reportType==1){
   // $ReportTypeMainTitle ='PICKUP ';
    $Filename='CompareView-PickupReport_'.date("Y-m-d");
}
if($Report_reportType==2){
     $ReportTypeMainTitle ='BOB ';
     $Filename='CompareView-BobReport_'.date("Y-m-d");
     
     
}
    //$Filename='CompareView-PickupReport_'.date("Y-m-d");
   // echo $content;die;
    pdfGeneratorAttach($content, $Filename);
    
}else{
echo $content;
//echo json_encode($returnData);
}
}


?>