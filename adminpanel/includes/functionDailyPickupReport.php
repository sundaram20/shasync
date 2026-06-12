<?php 

function functionDailyReport($useris,$connNew,$db,$id_shop,$CronSet,$Report_period){ 
						
	
	//echo $Report_period;
if($Report_period==$_REQUEST['period']){
	 $_REQUEST['period']=date('Y-m-d');
}	
		$_REQUEST['period'] =$Report_period;
$from = date('Y-m-d',strtotime($_REQUEST['period']));
//print_r($_SESSION);
$PeriodDateArray	=	$_REQUEST['period'];

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));

$to_TodaysDate=$_REQUEST['period'];

$period=$_REQUEST['period'];


	
if(date('m',strtotime($period))<=3){
	$YTDfrom = date('Y-04-01',strtotime('-1 years',strtotime($period)));
	$YTDto = date('Y-m-d',strtotime($period));
}
else{
	$YTDfrom = date('Y-04-01',strtotime($period));
	$YTDto = date('Y-m-d',strtotime($period));
}

  $MonthFrom=date('Y-m-01',strtotime($period));
  $MonthTo=date('Y-m-d',strtotime($period));
  
  
  
  
  
  
    $sqlBobBased = "SELECT `daily_pickup`.*,`fs_users`.name as name_executive
      

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.sales_revenue else 0 end) as `Today_sales_revenue`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.sales_revenue else 0 end) as `MTD_sales_revenue`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.sales_revenue else 0 end) as `YTD_sales_revenue`


,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.points else 0 end) as `Today_points`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.points else 0 end) as `MTD_points`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.points else 0 end) as `YTD_points`


,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.points else 0 end) as `Today_Relized_points`

,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.points else 0 end) as `MTD_Relized_points`

,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.points else 0 end) as `YTD_Relized_points`


,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `Today_comission`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `MTD_comission`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `YTD_comission`





FROM `daily_pickup`
LEFT JOIN `fs_users` ON daily_pickup.id_executive = fs_users.id   
INNER join `daily_pickup_details` on daily_pickup.id=daily_pickup_details.id_daily_pickup 


where `daily_pickup`.`id_shop` = '".addslashes($id_shop)."'  
GROUP BY `daily_pickup`.id_executive

Order BY daily_pickup.id_executive";
//echo $sqlBobBased;
  
  $resTeam =  mysqli_query($connNew,$sqlBobBased);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			$teamArray['executive'][$rowTeam->name_executive]['name_executive']=$rowTeam->name_executive;
			
			$teamArray['executive'][$rowTeam->name_executive]['Today_sales_revenue']=($rowTeam->Today_sales_revenue-$rowTeam->Today_comission);
			$teamArray['executive'][$rowTeam->name_executive]['MTD_sales_revenue']=($rowTeam->MTD_sales_revenue-$rowTeam->MTD_comission);
			$teamArray['executive'][$rowTeam->name_executive]['YTD_sales_revenue']=($rowTeam->YTD_sales_revenue-$rowTeam->YTD_comission);
			
			$teamArray['executive'][$rowTeam->name_executive]['Today_Relized_points']=$rowTeam->Today_Relized_points;
			$teamArray['executive'][$rowTeam->name_executive]['MTD_Relized_points']=$rowTeam->MTD_Relized_points;
			$teamArray['executive'][$rowTeam->name_executive]['YTD_Relized_points']=$rowTeam->YTD_Relized_points;
			
			$teamArray['executive'][$rowTeam->name_executive]['Today_points']=$rowTeam->Today_points;
			$teamArray['executive'][$rowTeam->name_executive]['MTD_points']=$rowTeam->MTD_points;
			$teamArray['executive'][$rowTeam->name_executive]['YTD_points']=$rowTeam->YTD_points;
			
			$teamArray['executive'][$rowTeam->name_executive]['MTD_Variable_Points']=$rowTeam->MTD_Relized_points-600;
			$teamArray['executive'][$rowTeam->name_executive]['YTD_Variable_Points']=$rowTeam->YTD_Relized_points-7200;
			
		}
		
	//	debugData($teamArray);
		
 
  
 $DataContent	= '<table class="table table-striped text-center" style="border-spacing:0;">
  <tbody>
    <tr style="color:white;">
      <th colspan="13" style="background-color:#3C8DBC;vertical-align: middle;">Daily Pickup Report</th>
    </tr>
    <tr style="color:white;">
      <th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive</th>
      <th style="background-color:#3C8DBC;" colspan="3">Today</th>
       <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="4">Month To Date</th>
      <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="4">Year To Date</th>
    </tr>
    <tr style="background-color:#5cb4e8;">
      <th style="border:1px solid #252525;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      
      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
      			

      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
    </tr>';
	
	foreach($teamArray   as $exec=>$execList){
		
		 
	 foreach($execList as   $key=> $Data){ 
$roomnights[$key] =$Data['YTD_sales_revenue'];

$lastYearroomnights[$key] = $Data['YTD_points'];

}
$roomnights  = array_column($execList, 'YTD_sales_revenue');
$lastYearroomnights = array_column($execList, 'YTD_points');
		
array_multisort($roomnights, SORT_DESC, $lastYearroomnights, SORT_ASC, $execList);
		
		
		
		
		
		
	foreach($execList   as $execList2){	
		$DataContent	.='<tr>
      <td style="text-align:left;border:1px solid #252525;">'.$execList2['name_executive'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_Relized_points'].'</td>
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['MTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0).'</td>
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['YTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0).'</td>
    </tr>';
		
		
	    $exeTotalToday_sales_revenue+=$execList2['Today_sales_revenue'];
		$exeTotalToday_points+=$execList2['Today_points'];
		$exeTotalToday_Relized_points+=$execList2['Today_Relized_points'];
		
		$exeTotalMTD_sales_revenue+=$execList2['MTD_sales_revenue'];
		$exeTotalMTD_points+=$execList2['MTD_points'];
		$exeTotalMTD_Relized_points+=$execList2['MTD_Relized_points'];
		$exeTotalMTD_Variable_Points+=($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0);
		
		$exeTotalYTD_sales_revenue+=$execList2['YTD_sales_revenue'];
		$exeTotalYTD_points+=$execList2['YTD_points'];
		$exeTotalYTD_Relized_points+=$execList2['YTD_Relized_points'];
		$exeTotalYTD_Variable_Points+=($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0);
	}	
		}
   
	
	$DataContent	.='<tr>
      <td style="text-align:left;border:1px solid #252525;font-weight:bold;">Total</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalToday_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalToday_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalToday_Relized_points.'</td>
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$exeTotalMTD_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalMTD_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalMTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalMTD_Variable_Points.'</td>
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$exeTotalYTD_sales_revenue.'</td>
	  
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalYTD_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalYTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$exeTotalYTD_Variable_Points.'</td>
    </tr>';
	
    $DataContent	.='
  </tbody>
</table>';

  
	
	//Data=================================
	
	
	
	$sqlBobBased2 = "SELECT `daily_pickup`.*,`fs_users`.name as name_executive,daily_pickup_details.id_product
      

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.sales_revenue else 0 end) as `Today_sales_revenue`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.sales_revenue else 0 end) as `MTD_sales_revenue`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.sales_revenue else 0 end) as `YTD_sales_revenue`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.points else 0 end) as `Today_points`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.points else 0 end) as `MTD_points`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.points else 0 end) as `YTD_points`


,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then `daily_pickup_details`.points else 0 end) as `Today_Relized_points`

,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then `daily_pickup_details`.points else 0 end) as `MTD_Relized_points`

,sum(case when  ( `daily_pickup` .payment_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then `daily_pickup_details`.points else 0 end) as `YTD_Relized_points`



,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `Today_comission`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `MTD_comission`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then (`daily_pickup_details`.comission+`daily_pickup_details`.discount+`daily_pickup_details`.other_expenses) else 0 end) as `YTD_comission`


,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' And '".date('Y-m-d',strtotime($to_TodaysDate))."') then (`daily_pickup_details`.qty) else 0 end) as `Today_qty`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($MonthFrom))."' And '".date('Y-m-d',strtotime($MonthTo))."') then (`daily_pickup_details`.qty) else 0 end) as `MTD_qty`

,sum(case when  ( `daily_pickup` .doc_date BETWEEN '".date('Y-m-d',strtotime($YTDfrom))."' And '".date('Y-m-d',strtotime($YTDto))."') then (`daily_pickup_details`.qty) else 0 end) as `YTD_qty`




FROM `daily_pickup`
LEFT JOIN `fs_users` ON daily_pickup.id_executive = fs_users.id   
INNER join `daily_pickup_details` on daily_pickup.id=daily_pickup_details.id_daily_pickup 


where `daily_pickup`.`id_shop` = '".addslashes($id_shop)."'  
GROUP BY daily_pickup.id_executive,`daily_pickup_details`.id_product

Order BY daily_pickup_details.id_product";
//echo $sqlBobBased;die;
  
  $resTeam2 =  mysqli_query($connNew,$sqlBobBased2);

		$teamArray2=array();
		$ProductArray=array();
		while($rowTeam=mysqli_fetch_object($resTeam2)){
			$product	= ucwords(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rowTeam->id_product."' "));
			//$id_executive	= ucwords(selectColumn('fs_users','name'," WHERE `id` = '".$rowTeam->id_executive."' "));
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['name_executive']=$product;
			
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['Today_sales_revenue']=($rowTeam->Today_sales_revenue-$rowTeam->Today_comission);
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_sales_revenue']=($rowTeam->MTD_sales_revenue-$rowTeam->MTD_comission);
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_sales_revenue']=($rowTeam->YTD_sales_revenue-$rowTeam->YTD_comission);
			
									
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['Today_Relized_points']=$rowTeam->Today_Relized_points;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_Relized_points']=$rowTeam->MTD_Relized_points;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_Relized_points']=$rowTeam->YTD_Relized_points;
			
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['Today_points']=$rowTeam->Today_points;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_points']=$rowTeam->MTD_points;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_points']=$rowTeam->YTD_points;
			
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_Variable_Points']=$rowTeam->MTD_Relized_points-600;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_Variable_Points']=$rowTeam->YTD_Relized_points-7200;
			
			
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['Today_qty']=$rowTeam->Today_qty;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_qty']=$rowTeam->MTD_qty;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_qty']=$rowTeam->YTD_qty;
			
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['Today_comission']=$rowTeam->Today_comission;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['MTD_comission']=$rowTeam->MTD_comission;
			$teamArray2['executive'][$rowTeam->name_executive][$rowTeam->id_product]['YTD_comission']=$rowTeam->YTD_comission;
			
			
			
			
			
			//=====================================================================================================================
			
			
			
			$product	= ucwords(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rowTeam->id_product."' "));
			//$id_executive	= ucwords(selectColumn('fs_users','name'," WHERE `id` = '".$rowTeam->id_executive."' "));
			$ProductArray['executive'][$rowTeam->id_product]['name_executive']=$product;
			
			$ProductArray['executive'][$rowTeam->id_product]['Today_sales_revenue']=($rowTeam->Today_sales_revenue-$rowTeam->Today_comission);
			$ProductArray['executive'][$rowTeam->id_product]['MTD_sales_revenue']=($rowTeam->MTD_sales_revenue-$rowTeam->MTD_comission);
			$ProductArray['executive'][$rowTeam->id_product]['YTD_sales_revenue']=($rowTeam->YTD_sales_revenue-$rowTeam->YTD_comission);
			
									
			$ProductArray['executive'][$rowTeam->id_product]['Today_Relized_points']=$rowTeam->Today_Relized_points;
			$ProductArray['executive'][$rowTeam->id_product]['MTD_Relized_points']=$rowTeam->MTD_Relized_points;
			$ProductArray['executive'][$rowTeam->id_product]['YTD_Relized_points']=$rowTeam->YTD_Relized_points;
			
			$ProductArray['executive'][$rowTeam->id_product]['Today_points']=$rowTeam->Today_points;
			$ProductArray['executive'][$rowTeam->id_product]['MTD_points']=$rowTeam->MTD_points;
			$ProductArray['executive'][$rowTeam->id_product]['YTD_points']=$rowTeam->YTD_points;
			
			$ProductArray['executive'][$rowTeam->id_product]['MTD_Variable_Points']=$rowTeam->MTD_Relized_points-600;
			$ProductArray['executive'][$rowTeam->id_product]['YTD_Variable_Points']=$rowTeam->YTD_Relized_points-7200;
			
			
			$ProductArray['executive'][$rowTeam->id_product]['Today_qty']=$rowTeam->Today_qty;
			$ProductArray['executive'][$rowTeam->id_product]['MTD_qty']=$rowTeam->MTD_qty;
			$ProductArray['executive'][$rowTeam->id_product]['YTD_qty']=$rowTeam->YTD_qty;
			
			$ProductArray['executive'][$rowTeam->id_product]['Today_comission']=$rowTeam->Today_comission;
			$ProductArray['executive'][$rowTeam->id_product]['MTD_comission']=$rowTeam->MTD_comission;
			$ProductArray['executive'][$rowTeam->id_product]['YTD_comission']=$rowTeam->YTD_comission;
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
		
	//debugData($teamArray2);
		
 
  
 $DataContentDetails	= '<br/><br/><br/><br/><br/><table class="table table-striped text-center" style="border-spacing:0;">
  <tbody>
    <tr style="color:white;">
      <th colspan="19" style="background-color:#3C8DBC;vertical-align: middle;">Item Wise Daily Pickup Report</th>
    </tr>
    <tr style="color:white;">
      <th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Item</th>
      <th style="background-color:#3C8DBC;" colspan="5">Today</th>
       <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="6">Month To Date</th>
      <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="6">Year To Date</th>
    </tr>
    <tr style="background-color:#5cb4e8;">
      <th style="border:1px solid #252525;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      
      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
      			

      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
    </tr>';
	
	foreach($teamArray2   as $execList2){
		foreach($execList2 as   $exec=>$execList){
			
			
			//echo $exec;
			 $DataContentDetails.='<tr style="color:white;">
      <td colspan="19" style="background-color:#3C8DBC;vertical-align: middle;"><b>'.$exec.'</b></td>
    </tr>';
			 foreach($execList as   $key=> $Data){ 
$roomnights[$key] =$Data['YTD_sales_revenue'];

$lastYearroomnights[$key] = $Data['YTD_points'];

}
$roomnights  = array_column($execList, 'YTD_sales_revenue');
$lastYearroomnights = array_column($execList, 'YTD_points');
		
array_multisort($roomnights, SORT_DESC, $lastYearroomnights, SORT_ASC, $execList);
		
	
		
	foreach($execList   as $execList2){	
		$DataContentDetails	.='<tr>
      <td style="text-align:left;border:1px solid #252525;">'.$execList2['name_executive'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_points'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_comission'].'</td>	  
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_Relized_points'].'</td>
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['MTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_points'].'</td>
	   <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_comission'].'</td>	 
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0).'</td>
	  
	  
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['YTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_points'].'</td>
	   <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_comission'].'</td>	 
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0).'</td>
    </tr>';
		
		$TotalToday_sales_revenue+=$execList2['Today_sales_revenue'];
		$TotalToday_points+=$execList2['Today_points'];
		$TotalToday_qty+=$execList2['Today_qty'];
		$TotalToday_comission+=$execList2['Today_comission'];
		$TotalToday_Relized_points+=$execList2['Today_Relized_points'];
		
		$TotalMTD_sales_revenue+=$execList2['MTD_sales_revenue'];
		$TotalMTD_points+=$execList2['MTD_points'];
		$TotalMTD_qty+=$execList2['MTD_qty'];
		$TotalMTD_comission+=$execList2['MTD_comission'];
		
		$TotalMTD_Relized_points+=$execList2['MTD_Relized_points'];
		$TotalMTD_Variable_Points+=($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0);
		
		$TotalYTD_sales_revenue+=$execList2['YTD_sales_revenue'];
		$TotalYTD_points+=$execList2['YTD_points'];
		$TotalYTD_qty+=$execList2['YTD_qty'];
		$TotalYTD_comission+=$execList2['YTD_comission'];		
		$TotalYTD_Relized_points+=$execList2['YTD_Relized_points'];
		$TotalYTD_Variable_Points+=($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0);
		
		
	}
	$DataContentDetails	.='<tr>
      <td style="text-align:left;border:1px solid #252525;font-weight:bold;">Total</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_comission.'</td>
	  
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_Relized_points.'</td>
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$TotalMTD_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_comission.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_Variable_Points.'</td>
	  
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$TotalYTD_sales_revenue.'</td>	  
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_comission.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_Variable_Points.'</td>
    </tr>';
	
	
	
	$TotalToday_sales_revenue='0';
		$TotalToday_points='0';
		$TotalToday_qty='0';
		$TotalToday_comission='0';
		$TotalToday_Relized_points='0';
		
		$TotalMTD_sales_revenue='0';
		$TotalMTD_points='0';
		$TotalMTD_qty='0';
		$TotalMTD_comission='0';
		
		$TotalMTD_Relized_points='0';
		$TotalMTD_Variable_Points='0';
		
		$TotalYTD_sales_revenue='0';
		$TotalYTD_points='0';
		$TotalYTD_qty='0';
		$TotalYTD_comission='0';	
		$TotalYTD_Relized_points='0';
		$TotalYTD_Variable_Points='0';	
	}	
		}
   
	
	
	
    $DataContentDetails	.='
  </tbody>
</table>';
	
	
	
//==============================================================================



$DataContentProductDetails	= '<br/><br/><br/><br/><br/><table class="table table-striped text-center" style="border-spacing:0;">
  <tbody>
    <tr style="color:white;">
      <th colspan="19" style="background-color:#3C8DBC;vertical-align: middle;">Item Wise Daily Pickup Report</th>
    </tr>
    <tr style="color:white;">
      <th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Item</th>
      <th style="background-color:#3C8DBC;" colspan="5">Today</th>
       <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="6">Month To Date</th>
      <th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="6">Year To Date</th>
    </tr>
    <tr style="background-color:#5cb4e8;">
      <th style="border:1px solid #252525;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      
      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
      			


      <th style="border:1px solid #252525;border-left:2px solid red;">Value</th>
      <th style="border:1px solid #252525;">Points</th>
	  <th style="border:1px solid #252525;">Count</th>
	  <th style="border:1px solid #252525;">Comission</th>
      <th style="border:1px solid #252525;">Relized Points</th>
      <th style="border:1px solid #252525;">Variable Points</th>
    </tr>';
	
	foreach($ProductArray   as $exec=>$execList){
		
			 foreach($execList as   $key=> $Data){ 
$roomnights[$key] =$Data['YTD_sales_revenue'];

$lastYearroomnights[$key] = $Data['YTD_points'];

}
$roomnights  = array_column($execList, 'YTD_sales_revenue');
$lastYearroomnights = array_column($execList, 'YTD_points');
		
array_multisort($roomnights, SORT_DESC, $lastYearroomnights, SORT_ASC, $execList);
		
	
		
	foreach($execList   as $execList2){	
		$DataContentProductDetails	.='<tr>
      <td style="text-align:left;border:1px solid #252525;">'.$execList2['name_executive'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_points'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_comission'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['Today_Relized_points'].'</td>
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['MTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_points'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_comission'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['MTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0).'</td>
	  
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;">'.$execList2['YTD_sales_revenue'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_points'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_qty'].'</td>
	  <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_comission'].'</td>	 
      <td style="border:1px solid #252525;text-align:right;">'.$execList2['YTD_Relized_points'].'</td>
      <td style="border:1px solid #252525;text-align:right;">'.($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0).'</td>
    </tr>';
		
		$TotalToday_sales_revenue+=$execList2['Today_sales_revenue'];
		$TotalToday_points+=$execList2['Today_points'];
		$TotalToday_qty+=$execList2['Today_qty'];
		$TotalToday_comission+=$execList2['Today_comission'];
		$TotalToday_Relized_points+=$execList2['Today_Relized_points'];
		
		$TotalMTD_sales_revenue+=$execList2['MTD_sales_revenue'];
		$TotalMTD_points+=$execList2['MTD_points'];
		$TotalMTD_qty+=$execList2['MTD_qty'];
		$TotalMTD_comission+=$execList2['MTD_comission'];
		
		$TotalMTD_Relized_points+=$execList2['MTD_Relized_points'];
		$TotalMTD_Variable_Points+=($execList2['MTD_Variable_Points']>0?$execList2['MTD_Variable_Points']:0);
		
		$TotalYTD_sales_revenue+=$execList2['YTD_sales_revenue'];
		$TotalYTD_points+=$execList2['YTD_points'];
		$TotalYTD_qty+=$execList2['YTD_qty'];
		$TotalYTD_comission+=$execList2['YTD_comission'];		
		$TotalYTD_Relized_points+=$execList2['YTD_Relized_points'];
		$TotalYTD_Variable_Points+=($execList2['YTD_Variable_Points']>0?$execList2['YTD_Variable_Points']:0);
		
		
		
		
	}	
		}
   
	$DataContentProductDetails	.='<tr>
      <td style="text-align:left;border:1px solid #252525;font-weight:bold;">Total</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_comission.'</td>
	  
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalToday_Relized_points.'</td>
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$TotalMTD_sales_revenue.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_comission.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalMTD_Variable_Points.'</td>
	  
	  
      <td style="border:1px solid #252525;border-left:2px solid red;text-align:right;font-weight:bold;">'.$TotalYTD_sales_revenue.'</td>	  
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_points.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_qty.'</td>
	  <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_comission.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_Relized_points.'</td>
      <td style="border:1px solid #252525;text-align:right;font-weight:bold;">'.$TotalYTD_Variable_Points.'</td>
    </tr>';
	
	
    $DataContentProductDetails	.='
  </tbody>
</table>';	
	
 
  if($CronSet=='0'){
  
 echo $DataContent.$DataContentDetails.$DataContentProductDetails;
  }else{
  
  return $DataContent.$DataContentDetails.$DataContentProductDetails;
  }
  
  
}
?>