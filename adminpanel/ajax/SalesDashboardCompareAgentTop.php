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


//error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_REQUEST['period']);
//print_r($PeriodDateArray);
$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
///$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//$to = date('Y-m-d',strtotime($PeriodDateArray[1]. ' +1 day'));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
$ComparePeriodDate= $_REQUEST['CompareFinancialYear'];
$CurrentFinancialYearDate=$_REQUEST['CurrentFinancialYear'];
  $Diffrence='';
  $CompareFinancialYear	=	explode('-',$_REQUEST['CompareFinancialYear']);
  $CurrentFinancialYear	=	explode('-',$_REQUEST['CurrentFinancialYear']);
 
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

  
		
  	$reportPeriodMonth= date('F',strtotime($_REQUEST['period'])).' '.$Year;
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
		$allUser ="  AND ".TBL_USERS.".`id` IN (".$teamMembers.") ";
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

$reportArray=array();

$FromLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)));
$ToLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)));

if(($_REQUEST['pdf']==1) || ($_REQUEST['summaryReportType'] == '4')){ //Agentwise Summary
 
   
 if($_REQUEST['reportType']==2){       
$sql = "SELECT fs_agent_achieved.id_company,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
      

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `confimed_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.month_value else 0 end) as `tentative_revenue`

,sum(case when ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_agent_achieved`.qty else 0 end) as `newTentative`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `LastYearconfimed_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `LastYeartentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `LastYearnewConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `LastYearnewTentative`





FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group



where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'   $condhotelAccess GROUP BY `fs_agent_achieved`.id_company Order BY newConfirmed desc ";
       
 } 
       
       
     //  echo $sql;
     // die;
     //  
       $SummaryHedding='Agentwise ';
       $TaleName='Agent Name';
       $resultList = mysqli_query($connNew,$sql);
       $empty3=0;
	while($rowList = mysqli_fetch_object($resultList)){
	  $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowList->id_company."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $companyCity= selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$rowList->id_company."'");
	  
	  $companyCity = $companyCity!=''?','.$companyCity:'';
	  $companypincode= selectColumn(TBL_COMPANY,'postcode'," WHERE `id_company` = '".$rowList->id_company."'");
	  $companypincode = $companypincode!=''?'-'.$companypincode:'';       
	     if($_REQUEST['reportType']==1){
            //$ReportTypeMainTitle ='PICKUP ';
           $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
            }
        if($_REQUEST['reportType']==2){
            //$ReportTypeMainTitle ='BOB ';
            $newConfirmednewTentative=($rowList->newConfirmed);
            }
	    
	    
	    
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($rowList->confimed_revenue==''?0:round($rowList->confimed_revenue)));
	     $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	     $emptytext3 ='empty_'.$empty3++;
	
	
	
	     
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['name_executive']=$rowList->name_executive;
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['CityPinCode']=$companyCity.$companypincode;

	    
	$reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']=$rowList->LastYearnewConfirmed==''?'0':$rowList->LastYearnewConfirmed;
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearconfimed_revenue']=$rowList->LastYearconfimed_revenue==''?0:round($rowList->LastYearconfimed_revenue);
    //$reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['name_executive']=$rowlastYearList->name_executive;
   // $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['CityPinCode']=$companyCity.$companypincode;
	    
	   // $reportArray['Agentwise']['name'][strtolower($companyname==''?$emptytext3:$companyname)]['roomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
	    // $reportArray['Agentwise']['name'][strtolower($companyname==''?$emptytext3:$companyname)]['confimed_revenue']=$rowList->confimed_revenue==''?0:round($rowList->confimed_revenue);
	
	}  
  }












//LASTYEAR SQL START >>================================================================================================================	

 if(($_REQUEST['pdf']==1) || ($_REQUEST['summaryReportType'] == '4')){ 


$FromLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)));
$ToLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)));
           
            
 if($_REQUEST['reportType']==2){           
   $lastYearPickUpsql =
    "SELECT `fs_agent_achieved`.id_company,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
     

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `LastYearconfimed_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.month_value else 0 end) as `LastYeartentative_revenue`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `LastYearnewConfirmed`

,sum(case when  ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_agent_achieved`.qty else 0 end) as `LastYearnewTentative`



FROM `fs_agent_achieved` 
LEFT JOIN `fs_company`  ON fs_agent_achieved.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group


where `fs_agent_achieved`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND ( ( `fs_agent_achieved` .month BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) $condhotelAccess GROUP BY `fs_agent_achieved`.id_company ";
     
     
   
 }       
            
            
            
       
      //echo $lastYearPickUpsql;
      //die;
       
       $resultlastYearList = mysqli_query($connNew,$lastYearPickUpsql);
       $empty7=0;
	while($rowlastYearList = mysqli_fetch_object($resultlastYearList)){
	   // print_r($rowlastYearList);
	   // echo '<br><br><br>';


if(($_REQUEST['pdf']==1 && $_REQUEST['summaryReportType'] == '3') || ($_REQUEST['summaryReportType'] == '3' )){   //Hotel Wise  Summary PDF PICKUP Report

    $companyname= strtolower(selectColumn(TBL_HOTELS,'name','WHERE id='.$rowlastYearList->id_hotel.' '));
    $BusinessSourceName=  $rowlastYearList->name_executive;
    $arrayNameSet   =   'Top 50 Agents';
    $SummaryHedding='Hotel Wise ';
    $TaleName='Hotel Wise Source';
}	    
	  $companyname= selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rowlastYearList->id_company."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowlastYearList->id_group."'");
	    
	    $companypincode= selectColumn(TBL_COMPANY,'postcode'," WHERE `id_company` = '".$rowList->id_company."'");
	  $companypincode = $companypincode!=''?'-'.$companypincode:''; 
	    
	    
	    $companyCity= selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$rowList->id_company."'");
	    $companyCity = $companyCity!=''?','.$companyCity:'';
	     
	     if($_REQUEST['reportType']==1){
            //$ReportTypeMainTitle ='PICKUP ';
             $newConfirmednewTentative=($rowlastYearList->newConfirmed+$rowlastYearList->newTentative);
             $newConfirmednewTentative_revenue=($rowlastYearList->confimed_revenue+$rowlastYearList->tentative_revenue);
            }
        if($_REQUEST['reportType']==2){
            //$ReportTypeMainTitle ='BOB ';
            $newConfirmednewTentative=($rowlastYearList->newConfirmed);
            $newConfirmednewTentative_revenue=($rowlastYearList->confimed_revenue);
            }
	        if($newConfirmednewTentative>0){
        	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
        	    //array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
        	    
        	    $emptytext7 ='empty_'.$empty7++;
        	    //$reportArray[$arrayNameSet][$GroupName][($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['lastYearroomnights']    =   $newConfirmednewTentative==''?'0':$newConfirmednewTentative;
        	   // $reportArray[$arrayNameSet][$GroupName][($companyname==''?$emptytext7:$companyname)][$BusinessSourceName]['lastYearconfimed_revenue']     =   $rowlastYearList->confimed_revenue==''?0:round($rowlastYearList->confimed_revenue);
    	   
  /*  $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']=$newConfirmednewTentative==''?'0':$newConfirmednewTentative;
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearconfimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['name_executive']=$rowlastYearList->name_executive;
    $reportArray['Top 100 Agents'][strtolower($companyname==''?$emptytext7:$companyname)]['CityPinCode']=$companyCity.$companypincode;*/
	            
	            
	            
	        }
	} 
	
	
 }	
	

	
	

 
	
	
	
	//echo '<pre>';print_r($reportArray);
    //die;

//LASTYEAR SQL END >>  ================================================================================================================
	
	
	

	//debugdata($reportArray);
	
//	die;
	

 //echo '<pre>';print_r($reportArray);
  

  

 
  
  
   

  
 
 
 
  
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
//array_multisort($sort['RoomNights'], SORT_DESC, $sort['Hotel'], SORT_ASC,$HotelwisePerformanceSummary);
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
 
$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = $db->fetch_object2($resShop);
$logo	=	$rowShop->image;
$Newrate_id	= addslashes(encryptor('decrypt',$_REQUEST['id']));
$pathImg = $DOCUMENT_ROOT;
if($_REQUEST['pdf']==1){
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.$MAP_VROOT_PATH.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	   $content .= '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="7" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>COMPARE '.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; 
}
foreach($reportArray as $maintitle=>$mainDatalist){
    
    $content .='<table class="table table-striped text-center">';
	$content .='<tr><th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>Compare '.$maintitle.'  Breakup For Period '.$reportPeriod.'</b></th></tr>';
    $content .='<tr>
    <th   colspan="2" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    </tr>';
    
      $content .='<tr style="vertical-align:central;">
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>Executive Name </b></th>
  
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>';
  
 $content .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>';
   
  
  $content .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$ComparePeriodDate.' (Lacs)</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>'.$CurrentFinancialYearDate.' (Lacs) </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GROWTH % </b></th>';
  
  
  
  
   $content .='</tr>';
    
    
    
    
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	
	 
	 foreach($mainDatalist as   $key=> $Data){ 
$roomnights[$key] =$Data['roomnights'];
$confimed_revenue[$key] = $Data['confimed_revenue'];
$lastYearroomnights[$key] = $Data['lastYearroomnights'];

}
$roomnights  = array_column($mainDatalist, 'roomnights');
$confimed_revenue = array_column($mainDatalist, 'confimed_revenue');
$lastYearroomnights = array_column($mainDatalist, 'lastYearroomnights');
array_multisort($roomnights, SORT_DESC, $lastYearroomnights, SORT_ASC, $mainDatalist);
//array_multisort($roomnights, SORT_DESC, $lastYearroomnights, SORT_ASC, $mainDatalist);
//array_multisort($roomnights, SORT_ASC, $lastYearroomnights, SORT_DESC);
//array_multisort( array_column( $mainDatalist, 'distance' ), SORT_ASC, SORT_NUMERIC, $mainDatalist );
//debugdata($mainDatalist);die;
	 $k=1;
    foreach($mainDatalist as $teamGroup=>$subDataList){
       if($k<=100){  
if($subDataList['roomnights']>0 || $subDataList['lastYearroomnights']>0){
    if($subDataList['roomnights']>0){
							$mtrArr =($subDataList['confimed_revenue']/$subDataList['roomnights']);
						}else{
							$mtrArr =0;
							}
                        
                        $TotalRoomNight+=$subDataList['roomnights'];
                        $TotalConfimedRevenue+=$subDataList['confimed_revenue'];
                        
                        $TotalTeamRoomNight+=$subDataList['roomnights'];
                        $TotalTeamConfimedRevenue+=$subDataList['confimed_revenue'];
                        
                        
                        $TotalRoomNightlastYear+=$subDataList['lastYearroomnights'];
                        $TotalConfimedRevenuelastYear+=$subDataList['lastYearconfimed_revenue'];
                        
                        $TotalTeamRoomNightlastYear+=$subDataList['lastYearroomnights'];
                        $TotalTeamConfimedRevenuelastYear+=$subDataList['lastYearconfimed_revenue'];
                        
                        
                        
                        if (strpos($list, 'empty_') !== false) {
                                $name= $list;
                        }else{
                            $name=$list;}
           
            
        
        
         
    //Group Total
    $SumTotalArray= $TotalRoomNight>0?round($TotalConfimedRevenue/$TotalRoomNight):'0';
    $SumTotallastYearArray= $TotalRoomNightlastYear>0?round($TotalConfimedRevenuelastYear/$TotalRoomNightlastYear):'0';
    
    $GolyListData      =    $SumTotallastYearArray>0?round((($SumTotalArray-$SumTotallastYearArray)/$SumTotallastYearArray)*100,2):'0';
    
    $SumTotalGolyRoomNights= $TotalRoomNightlastYear>0?round((($TotalRoomNight-$TotalRoomNightlastYear)/$TotalRoomNightlastYear) *100,2):'0';
    
    
    $lY1 =round($TotalConfimedRevenuelastYear/100000,2);
    $CY1= round($TotalConfimedRevenue/100000,2);
    $SumTotalGolyConfimedRevenue=$lY1>0?round((($CY1-$lY1)/$lY1) *100,2):'0';
    
    //$SumTotalGolyConfimedRevenue=round((($TotalConfimedRevenue-$TotalConfimedRevenuelastYear)/$TotalConfimedRevenuelastYear) *100,2);
    
    
    
    $SumTotalGolyConfimedRevenueColor=$SumTotalGolyConfimedRevenue>=0?"":"color:red;";
     $SumTotalGolyRoomNightsColor=$SumTotalGolyRoomNights>=0?"":"color:red;";
    if($teamGroup!='name'){
    $content .='<tr style="">
    <td style="border:1px solid #000;text-align:left;width:30%;">'.ucwords($teamGroup).$subDataList['CityPinCode'].' </td>
    <td style="border:1px solid #000;text-align:left;width:15%;">'.$subDataList['name_executive'].' </td>
    <td style="border:1px solid #000;text-align:center;">'.$TotalRoomNightlastYear.'</td>
    <td style="border:1px solid #000;text-align:center;">'.$subDataList['roomnights'].'</td>
    <td style="border:1px solid #000;text-align:center;'.$SumTotalGolyRoomNightsColor1.'">'.$SumTotalGolyRoomNights.'</td>';
    
    $content .='<td style="border:1px solid #000;text-align:center;">'.$SumTotallastYearArray.'</td>
    <td style="border:1px solid #000;text-align:center;">'.$SumTotalArray.'</td>
    <td style="border:1px solid #000;text-align:center;">'.$GolyListData.'</td>';
    
    $content .='<td style="border:1px solid #000;text-align:center;">'.round($TotalConfimedRevenuelastYear/100000,2).'</td>
    <td style="border:1px solid #000;text-align:center;">'.round($TotalConfimedRevenue/100000,2).'</td>
    <td style="border:1px solid #000;text-align:center;'.$SumTotalGolyConfimedRevenueColor1.'">'.$SumTotalGolyConfimedRevenue.'</td>';
    
    
    
    
    $content .='</tr>';
    }
   
    
    $SumTotalArray='';
    $TotalConfimedRevenue='';
    $TotalRoomNight='';
    $TotalRoomNightlastYear='';
    $TotalConfimedRevenuelastYear='';
    $SumTotallastYearArray='';
    $TotalConfimedRevenuelastYear='';
	}
	}
	$k++;
}
   
     
     $content .= '</table><br/><br/>';
     
        
         //$content .=$contentTeam;
         //$content .=$contentGroup;
         
         $contentGroup='';
         $contentTeam='';
     //Office Team Wise For Period End
     
}

if($_REQUEST['pdf']==1){
//echo $content;
//die;
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
                        $Filename='CompareView-PickupRepor_'.date("Y-m-d H:i:s").'.xls';
                    }
                    if($_REQUEST['reportType']==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='CompareView-BobReport_'.date("Y-m-d H:i:s").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$Filename);
        echo $test;die;
            
    
    
}else{
echo $content;
//echo json_encode($returnData);
}
 
