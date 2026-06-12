<?php include_once("../config/auto_loader.php");



checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');



//$hotelId='1';



/////////////////////////////////////////////////////////////////////////////////////



/*echo "Sorry for the inconvenience <br> Please wait we are working on DSR ...";



echo "<pre>";



print_r($_SESSION);



echo "<br>";



print_r($_REQUEST);



echo "</pre>";



exit;*/







if($_SESSION['userLevel']!=1){



$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";



$resPer = mysqli_query($connNew,$perSql);







if($resPer){



  	$perData	=	mysqli_fetch_object($resPer);



    if($perData->calendar_user_list_approved == 0){



	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	



    }







}



}



if($_SESSION['teamMembers'] !=""){



  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";



}



else{



  $teamMembers ="";



}







if($_REQUEST['eId'] !="" && $_REQUEST['action']=='delete'){



	



	checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'delete');



	$dsrNoDays	=selectColumn(TBL_USERS,'dsr_num_days'," WHERE `id` = '".$_SESSION['userId']."'");



	//echo "<br>";







	$validDate = abs(date('d',$_REQUEST['dated'])-date('d',strtotime(date('Y-m-d'))));







	//exit;







	//if($validDate<=$dsrNoDays || $_SESSION['userLevel']==1){



		$deleteCal = "DELETE FROM `".TBL_DAILY_CALENDER."` WHERE id_shop='".$_SESSION['shop']."' AND visit_id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";



		if(executeSQl($deleteCal)){



			$deleteSql = "DELETE FROM `".TBL_DAILYVISIT."` WHERE id_shop='".$_SESSION['shop']."' AND id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";



			



			if(executeSQl($deleteSql)){



				$deleteFeed = "DELETE FROM `".TBL_FEEDBACK_DETAILS."` WHERE id_shop='".$_SESSION['shop']."' AND visit_id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";







				if(executeSQl($deleteFeed)){



					$deleteFollow = "DELETE FROM `".TBL_FOLLOWUP_DETAILS."` WHERE id_shop='".$_SESSION['shop']."' AND visit_id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";



					



					if(executeSQl($deleteFollow)){



						$_SESSION['successMsg'] = "Selected Visit Deleted successfully";



					}



					else{



						$_SESSION['errorMsg'] = "Error While Deleting FollowUp";



					}







				}



				else{



					$_SESSION['errorMsg'] = "Error While Deleting Feedback";



				}



			}



			else{



				$_SESSION['errorMsg'] = "Error While Deleting";



			}



		}



		else{



			$_SESSION['errorMsg'] = "Error While Deleting";



		}



	//}else{



	//	$_SESSION['errorMsg'] = "You can't delete DSR created ".$dsrNoDays." days ago.";



	//}	











}







if($_REQUEST['state'] != ''){







	$sql = "SELECT  `".TBL_DAILYVISIT."`.* FROM `".TBL_DAILYVISIT."` LEFT JOIN ".TBL_USERS." ON `".TBL_DAILYVISIT."`.id_user=".TBL_USERS.".id   WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND ".TBL_USERS.".location='".$_REQUEST['state']."' ";



}					  



else{



	$sql = " SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";



}					  















if($_REQUEST['searchFormSubmit'] =='1'){



if($_REQUEST['report_date'] != ''){



	$_REQUEST['report_date'];







	list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);



	



	 $sql .= " AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";





	



	//$sql .= " AND `".TBL_DAILYVISIT."`.`dated` = '".stripslashes(date('Y-m-d',strtotime($_REQUEST['report_date'])))."' ";



}



if($_REQUEST['usernameid'] != ''){



	$sql .= " AND `".TBL_DAILYVISIT."`.`id_user` = '".addslashes($_REQUEST['usernameid'])."'";



	



		



}



if($_REQUEST['companyId'] != ''){



	$sql .= " AND `".TBL_DAILYVISIT."`.`id_company` = '".addslashes($_REQUEST['companyId'])."'";



}



/*if($_REQUEST['hotelId'] != ''){



	$sql .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` = '".addslashes($_REQUEST['hotelId'])."'";



}*/







	







}



 if($_SESSION['userLevel']==1){



				 	



	$sql .= "";



	}else if($_REQUEST['usernameid']==""){



	$sql .=  "  AND `".TBL_DAILYVISIT."`.`id_user` = '".addslashes($_SESSION['userId'])."'";



	}



		if($_REQUEST['reportDate'] == '' && $_REQUEST['location'] ==''){



		$sql .= " order by dated asc";



		}



		$datewise_array = array();







		 $checkinDate = date('Y-m-d',strtotime($checkin));







		  $checkoutDate = date('Y-m-d',strtotime($checkout));







		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	







				$datewise_array[] = $checkinDate;







				$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));



			}

			

?>







<?php



if($_REQUEST['Download'] == 'Download'){





	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");







	$rowShop = $db->fetch_object2($resShop);



	



	



/*echo $sql;







exit;*/

	

	$db->query($sql);







	 $numRows= $db->num_rows();









	//$pagging = new pagingClass($sql,$setpage);







	//$db->query($pagging->getQuery());







	$total = $db->num_rows();

	

	







$datawisearrayFinal = array();			







	if($total > 0){		







		$cntr_order= 0;







		while($row = $db->fetch_object()){





			

				



			foreach($datewise_array as $checkinDatearr){			







				if(strtotime($checkinDatearr)==strtotime($row->dated)){





					



					



					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["id"]=$row->id;







					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["company"]=$row->id_company;







					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["customer"]=$row->id_contacts;



					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["id_user"]=$row->id_user;







					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["business_potential"]=$row->business_potential;



					



			$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["discussion_summary"]=$row->discussion_summary;



					



					$datawisearrayFinal2[$checkinDatearr][$row->id_user][$row->id]["dated"]=$row->dated;



					







				}







				







			}







		}







		



	}



	





$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");



 $rowShop = $db->fetch_object2($resShop);



 



$availableData .= '<style>



.table-bordered {



    border: 1px solid #000;



}



.table {



    margin-bottom: 20px;



    max-width: 80%;



    width:100%;



}







table {



    background-color: transparent;







}



table {



    border-collapse: collapse;



    border-spacing: 0;







}



.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	



    border: 1px solid #000;



}



.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {



    color: #000;



    font-size: 0.85em;



    padding: 2px !important;



}</style>';















 /*$availableData .= '<table class="table">



						<tr>



						  <td width="100%">



							<address>



						   <img src="../../uploaded_files/shop/'.$rowShop->image.'" class="img-responsive" alt="logo" title="logo" />



							</address>



						 	</td>



						 



						 </td>



						<tr>



					</table>'; */



	 



 



 



		







				



					



				



				if($total > 0){$counter = 1;







				







				foreach($datawisearrayFinal2 as $dateCheckin=>$dateData){



					



					



						$DayVisitcount=1;



						foreach($dateData as $hotelcheckarr=>$order_data1){



//echo "<pre>";print_r($order_data1);



						



					



						//$availableData .='<img style="margin-left:40%;" src="images/'.$rowShop->image.'">';



					



						$availableData .= '<div style="page-break-after: always;"><table class="table"  style="margin:0px !important;text-align:Left;" width="600">



						<tr>



						<td  width="35%">';



						$availableData .= '<table class="table" border="1">



						<tr align="middle" style="background-color:#c2d69a;color:#fff;font-color:#fff;border:1px;">



						   <th width="5%" ><b>Name of Executive </b></th>



						   <th width="15%" ><b>'.ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($hotelcheckarr)."'")).'</b></th>						   						



						</tr>



						<tr align="middle" style="color:#fff;font-color:#fff;border:1px">



						   <th width="5%" ><b>Date</b></th>



						   <th width="15%" ><b>'.dateformat_date($dateCheckin).'</b></th>



						</tr>



						



						<!--<tr align="middle" style="color:#fff;font-color:#fff;border:1px">



						   <th width="15%" ><b>Target for the Day</b></th>



						   <th width="10%" ><b>---</b></th>



						</tr>-->







						</table>';



						



						



						$availableData .='</td>







						<td  width="35%">



							



							



									



						</td>







						<td  width="35%">';



							



$sqlDailyvisit = executeSql(" SELECT  count(id) as total,sum(Total) sumTotal,sum(entertainment) AS enTotal ,sum(lunch) as enLunch FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."'");



$rowDailyvisite = $db->fetch_object2($sqlDailyvisit);



					



//-----------------------------Add Other 



$sqlDailyvisit_AddOther = executeSql(" SELECT  count(id) as total,sum(Total) sumTotal,sum(entertainment) AS enTotal ,sum(lunch) as enLunch FROM `".TBL_OTHER."`  WHERE `".TBL_OTHER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_OTHER."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."'");



$rowDailyvisite_AddOther = $db->fetch_object2($sqlDailyvisit_AddOther);







//-----------------------------Add Other 















					



$dt = date('Y-m-d',strtotime($dateCheckin));



$MonthStartDate 	=	date("Y-m-01", strtotime($dt));



$MonthEndDate = date("Y-m-d", strtotime($dt)); 







if(date('m',strtotime($dt))<=3){



	$year = date('Y') - 1;



	$Finalcialyear	=	date('Y-04-01',strtotime('-1 years',strtotime($dt)));



}else{



	$Finalcialyear	=	date("Y-04-01");



}



		



		







$TillDate	=	date('Y-m-d',strtotime($checkoutDate));







/*if ( date('m') > 6 ) {



    echo  $year = date('Y') + 1;



}



else {



    echo $year = date('Y');



}



echo $year = ( date('m') > 6) ? date('Y') + 1 : date('Y');







*/











//echo "SELECT  count(id) as Yeartotal,sum(Total) as YearsumTotal ,sum(entertainment) as enYearTotal FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."'AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($Finalcialyear))."' And '".date('Y-m-d',strtotime($TillDate))."' and id_user	= '".addslashes($hotelcheckarr)."'";







$sqlFinalcialyear = executeSql("SELECT  count(id) as Yeartotal,sum(Total) as YearsumTotal ,sum(entertainment) as enYearTotal,sum(lunch) as enYearLunch FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."'AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($Finalcialyear))."' And '".date('Y-m-d',strtotime($MonthEndDate))."' and id_user	= '".addslashes($hotelcheckarr)."'");



$sqlFinalcialyear = $db->fetch_object2($sqlFinalcialyear);















$sqlCurrentMonth = executeSql(" SELECT  count(id) as Monthtotal,sum(Total) MonthsumTotal ,sum(entertainment) as enTotalMonth,sum(lunch) as enLunchMonth FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."'AND `".TBL_DAILYVISIT."`.`dated` BETWEEN '".date('Y-m-d',strtotime($MonthStartDate))."' And '".date('Y-m-d',strtotime($MonthEndDate))."' and id_user	= '".addslashes($hotelcheckarr)."'"); 



$sqlCurrentMonth = $db->fetch_object2($sqlCurrentMonth);



 







//-----------------------------Add Other 







$sqlFinalcialyear_Add_Other = executeSql("SELECT  count(id) as Yeartotal,sum(Total) as YearsumTotal ,sum(entertainment) as enYearTotal,sum(lunch) as enYearLunch FROM `".TBL_OTHER."`  WHERE `".TBL_OTHER."`.`id_shop` = '".addslashes($_SESSION['shop'])."'AND `".TBL_OTHER."`.`dated` BETWEEN '".date('Y-m-d',strtotime($Finalcialyear))."' And '".date('Y-m-d',strtotime($MonthEndDate))."' and id_user	= '".addslashes($hotelcheckarr)."'");



$sqlFinalcialyear_Add_Other = $db->fetch_object2($sqlFinalcialyear_Add_Other);











$sqlCurrentMonth_Add_other = executeSql(" SELECT  count(id) as Monthtotal,sum(Total) MonthsumTotal ,sum(entertainment) as enTotalMonth,sum(lunch) as enLunchMonth FROM `".TBL_OTHER."`  WHERE `".TBL_OTHER."`.`id_shop` = '".addslashes($_SESSION['shop'])."'AND `".TBL_OTHER."`.`dated` BETWEEN '".date('Y-m-d',strtotime($MonthStartDate))."' And '".date('Y-m-d',strtotime($MonthEndDate))."' and id_user	= '".addslashes($hotelcheckarr)."'"); 



$sqlCurrentMonth_Add_other = $db->fetch_object2($sqlCurrentMonth_Add_other);



//-----------------------------Add Other 					



 						







						



						$availableData .= '<table class="table" border="1" style="float:right;">



						







						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px;">



						   <th width="15%" style="color:#000;"><b>Particulars</b></th>



						   <th width="5%" style="color:#000;"><b>Today</b></th>	



						    <th width="5%" style="color:#000;"><b>MTD</b></th>	



						    <th width="5%" style="color:#000;"><b>YTD</b></th>						   						



						</tr>



						<tr align="middle" style="color:#000;font-color:#000;border:1px">



						   <th width="15%" ><b>Sales Calls Done</b></th>



						   <th width="5%" ><b>'.$rowDailyvisite->total.'</b></th>



						   <th width="5%" ><b>'.$sqlCurrentMonth->Monthtotal.'</b></th>



						   <th width="5%" ><b>'.$sqlFinalcialyear->Yeartotal.'</b></th>



						</tr>



						<tr align="middle" style="color:#000;font-color:#000;border:1px">



						   <th width="15%" ><b>Conveyance</b></th>



						   <th width="5%" ><b>'.($rowDailyvisite->sumTotal+$rowDailyvisite_AddOther->sumTotal).'</b></th>



						   <th width="5%" ><b>'.($sqlCurrentMonth->MonthsumTotal+$sqlCurrentMonth_Add_other->MonthsumTotal).'</b></th>



						   <th width="5%" ><b>'.($sqlFinalcialyear->YearsumTotal+$sqlFinalcialyear_Add_Other->YearsumTotal).'</b></th>



						</tr>



						<tr align="middle" style="color:#000;font-color:#000;border:1px">



						   <th width="15%" ><b>Entertainment</b></th>



						   <th width="5%" ><b>'.($rowDailyvisite->enTotal!=''?$rowDailyvisite->enTotal:0).'</b></th>



						   <th width="5%" ><b>'.$sqlCurrentMonth->enTotalMonth.'</b></th>



						   <th width="5%" ><b>'.$sqlFinalcialyear->enYearTotal.'</b></th>



						</tr>



						<tr align="middle" style="color:#000;font-color:#000;border:1px">



						   <th width="15%" ><b>Lunch</b></th>



						   <th width="5%" ><b>'.$rowDailyvisite->enLunch.'</b></th>



						   <th width="5%" ><b>'.$sqlCurrentMonth->enLunchMonth.'</b></th>



						   <th width="5%" ><b>'.$sqlFinalcialyear->enYearLunch.'</b></th>



						</tr>



						</table>';



						



						$availableData .='</td>



						   



						</tr>



					</table><br><br>



       ';						



						



						$availableData .='<table style="postion:absolute;margin-top:-130px;margin-left:300px;">



						<tr>



						<td width="30%"><img  src="../uploaded_files/shop/'.$rowShop->image.'"></td>



						</tr></table>';



						               				//print_r($order_data);



							

//style="page-break-inside:avoid;"

	$availableData .= '<div ><table class="table" style="margin:0px !important;text-align:Left;margin-top:-50px;" width="800">



						<tr>



						  <td>



							<b>Discussion Summary </b>



						 	</td>



						   



						</tr>



					</table>



       <table class="table" border="1" style="border:1px solid:red;margin-top:-10px;">



						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">



						   <th width="5%" style="color:#000;"><b>S:No</b></th>



						   <th width="20%" style="color:#000;"><b>Company Visited</b></th>



						   <th width="15%" style="color:#000;text-align:center;"><b>Contact Person</b></th>



						   <th width="15%" style="color:#000;text-align:center;"><b>Contact  No </b></th>						   



						   <th style="color:#000;text-align:center;width:40%;"><b>'.ucwords('discussion summary').'</b></th>



						   						



						</tr>';



				















			foreach($order_data1 as $room_idfromarr=>$order_data){	



					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($order_data['customer'])."' AND id_company='".$order_data['company']."' ",''); 



		  			$resultContact = $db->fetch_object2($resContact);



                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;



					$mobile	=	$resultContact->mobile;



					if($mobile	==''){



						$mobile	='-';



						}







			$marked = selectColumn(TBL_VISIT,'company_marked','WHERE id="'.$order_data['id'].'" ');		



			if($marked==1){



				$markColor = "style='color:blue !important;'";



			}



			else{



				$markColor='';



			}				



					



		$availableData .= '<tr align="middle" style="border:1px;" >



						   <td width="5%" >'.$counter++.'</td>







						   <td width="20%"  ><span '.$markColor.'>'.ucwords(selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."' ")).'</span></td>	



						   <td width="15%" style="text-align:center;">'.ucwords($NAme).'</td>



						   <td width="15%" style="text-align:center;">'.$mobile.'</td>						   



						   <td style="text-align:left;" width="40%">'.ucfirst($order_data['discussion_summary']).'</td>



						   						



						</tr>';



						



						



						}



						



						



						



						



	$availableData .= '</table></div>';



				



/*Discussion Summary -End*/











	$sqlDailyvisit = executeSql(" SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."'");



	 



	 //$rowRatePlanExisting = $db->fetch_object2($sqlDailyvisit);







	 $feedCount=1;



	 $printHead = 0;



	 while($rowDailyvisite = $db->fetch_object2($sqlDailyvisit)){



		



		$resState = executeSql("SELECT * from `".TBL_FOLLOWUP_DETAILS."` where status='1' and  Visit_id='".addslashes($rowDailyvisite->id)."'");







		$explodeCount = num_rows($resState);







		/*Follow up Summary -Start*/



	if($explodeCount > 0 && $printHead ==0){



	$availableData .= '<div style="page-break-inside:avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">



						<tr>



						  <td>



							<b>Follow Up Summary </b>



						 	</td>



						   



						</tr>



					</table>



       ';



	$availableData .= '<table class="table" border="1" style="border:1px solid:red;">



						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">



						   <th width="5%" style="color:#000;"><b>S:No</b></th>



						   <th width="35%" style="color:#000;"><b>Hotel Name</b></th>



						   <th width="10%" style="color:#000;text-align:center;"><b>Date</b></th>



						   <th width="10%" style="color:#000;text-align:center;"><b>Status </b></th>



						   



						   <th style="color:#000;text-align:center;width:40%;"><b>FollowUp Summary					</b></th>



						   						



						</tr>';



		$printHead++;



	}					







if(num_rows($resState) > 0){







		while($row = $db->fetch_object2($resState)){



			



				



		$followup_summary	=	selectColumn(TBL_FOLLOWUP_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `visit_id` = '".addslashes($rowDailyvisite->id)."'  AND `details_id` = '".$row->id."'");



					  				



	$availableData .= '



						<tr align="middle" style="border:1px">



						   <td width="5%" ><b>'.$feedCount++.'</b></td>



						   <td width="35%" >'.ucwords(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'")).' - '.ucwords(selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$row->hotel_id."'")).'</td>



						   <td width="10%" style="text-align:center;">'.date('d-M-Y',strtotime($row->dated)).'</td>



						   <td width="10%" style="text-align:center;">Open</td>



						   



						   <td style="text-align:left;" width="40%">'.ucfirst($followup_summary).'</td>



						   						



						</tr>';						



		}



}



						



	 }



						



						



	if($explodeCount > 0 && $printHead ==1){					



	$availableData .= '</table></div>';	



	$printHead++;



	}



/*Follow Up Summary -End*/	











		



	



	$sqlDailyvisit = executeSql(" SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."'");



	 



	 //$rowRatePlanExisting = $db->fetch_object2($sqlDailyvisit);



	 $feedCount=1;



	 $printHead=0;











	 while($rowDailyvisite = $db->fetch_object2($sqlDailyvisit)){



		



		$resState = executeSql("SELECT * from `".TBL_DAILYVISIT_FEEDBACK."` where status='1' and  Visit_id='".addslashes($rowDailyvisite->id)."'");



		



		$getCount = num_rows($resState);



		



		if($getCount>0 && $printHead==0){



		/*Feed Back Summary -Start*/



		$availableData .= '<div style="page-break-inside:avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">



						<tr>



						  <td>



							<b>Feed Back Summary </b>



						 	</td>



						   



						</tr>



					</table>



       ';



	$availableData .= '<table class="table" border="1" style="border:1px solid:red;">



						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">



						   <th width="5%" style="color:#000;"><b>S:No</b></th>



						   <th width="35%" style="color:#000;"><b>Hotel Name</b></th>



						   <!--<th width="10%" style="color:#000;text-align:center;"><b>Date</b></th>



						   <th width="10%" style="color:#000;text-align:center;"><b>Status </b></th>-->



						   



						   <th style="color:#000;text-align:center;width:40%;"><b>FeedBack Summary					</b></th>



						   	<th width="10%" style="color:#000;text-align:center;"><b>Status </b></th>					



						</tr>';



	$printHead++;					



	}					







if(num_rows($resState) > 0){







		while($row = $db->fetch_object2($resState)){



			



				



		$feedback_summary	=	selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `visit_id` = '".addslashes($rowDailyvisite->id)."'  AND `details_id` = '".$row->id."'");



				



				 $feedback_type	=	selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'conclusion_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `visit_id` = '".addslashes($rowDailyvisite->id)."'  AND `details_id` = '".$row->id."'");	



				  	if($feedback_type=='1'){



						$feedbackType='Positive';



						}



					if($feedback_type=='2'){



						$feedbackType='Negative';



						}



								



	$availableData .= '



						<tr align="middle" style="border:1px">



						   <td width="5%" ><b>'.$feedCount++.'</b></td>



						   <td width="35%" >'.ucwords(selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'")).' - '.ucwords(selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$row->hotel_id."'")).'</td>



						   <!--<td width="10%" style="text-align:center;">'.date('d-M-Y',strtotime($row->dated)).'</td>



						   <td width="10%" style="text-align:center;">Open</td>-->



						   



						   <td style="text-align:left;" width="40%">'.ucfirst($feedback_summary).'</td>



						   <td style="text-align:left;" width="20%">'.ucfirst($feedbackType).'</td>



						   						



						</tr>';						



		}



	}



	if($printHead!=0){					



		$availableData .= '</table></div>';



		$printHead++;



	}						



}



						



//$availableData .= '</table></div>';					



	



/*Feed Back Summary -End*/	







	



























		



		$availableData .= '<div style="page-break-inside: avoid;"><table class="table" style="margin:0px !important;text-align:Left;" width="800">



						<tr>



						  <td>



							<b>CONVEYANCE </b>



						 	</td>



						   



						</tr>



					</table>



       ';







$availableData .= '<table class="table" border="1" >



						<tr align="middle" style="background-color:#c2d69a;color:#000;font-color:#000;border:1px">



						   <th width="4%" style="color:#000;"><b>S:No</b></th>



						   <!--<th width="8%" style="color:#000;"><b>Date</b></th>-->



						   <th width="20%" style="color:#000;text-align:center;"><b>Company Visited</b></th>



						   



						   



						   <th colspan=2 width="28%" style="color:#000;text-align:center;" ><b>Area Covered</b></th>



						   <th width="10%" style="color:#000;text-align:center;"><b>Mode</b></th>



						   



						   <!--<th width="15%" style="color:#000;text-align:center;"><b>Supervisior Remarks</b></th>-->



						   



						   <th width="5%" style="color:#000;text-align:center;"><b>Kms</b></th>



						   <th width="5%" style="color:#000;text-align:center;"><b>Rate/Km</b></th>



						   <th width="5%" style="color:#000;text-align:center;"><b>Parking</b></th>



						   



						   <th width="5%" style="color:#000;text-align:center;"><b>Total</b></th>



						   <th width="12%" style="color:#000;text-align:center;"><b>Approval Status</b></th>



						   



						   						



						</tr>';	







		   								



						



	/*================================CONVEYANCE START==================================================================================*/



$sql1 = " SELECT  `".TBL_DAILYVISIT."`.*  FROM `".TBL_DAILYVISIT."`  WHERE `".TBL_DAILYVISIT."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_DAILYVISIT."`.`dated`=' ".date('Y-m-d',strtotime($dateCheckin))."' and id_user	= '".addslashes($hotelcheckarr)."' AND Total !=0 ";







	$db->query($sql1);







	 $numRows= $db->num_rows();







	//$pagging = new pagingClass($sql,$setpage);







	//$db->query($pagging->getQuery());







	$total = $db->num_rows();



if($total > 0){$counter = 1;



												



				  while($row2 = $db->fetch_object()){



					  



					  $TotalSum	 +=	$row2->Total;



					  $availableData .= '<tr>



						                       



                    <td>'.$counter++.'</td>



                    <!--<td>'.dateformat_date($row2->dated).'</td>-->';



                    



                  



					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($row2->id_contacts)."'",''); 



					$id_area = selectColumn(TBL_COMPANY,'area'," WHERE `id_company` = '".$row2->id_company."'");



					$areaName = selectColumn(TBL_AREAS,'name'," WHERE `id` = '".$id_area."'");



		  			$resultContact = $db->fetch_object2($resContact);



                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;



                    if($row2->conveyance_approved==1){



						$conApprove = "Approved";



					}



					else if($row2->conveyance_approved==2){



						$conApprove = "Not Approved";



					}



					else{



						$conApprove = "Pending";



					}



					$availableData .='<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row2->id_company."'").'</td>';







					



					







                    $availableData .= '<td colspan=2 width="28%">'.ucfirst($row2->StatFrom.($row2->StatTo!=''?'-'.$row2->StatTo:'')).'</td>';



                    $availableData .='<td style="text-align:center;">'.selectColumn(TBL_TRAVEL_MODES,'name'," WHERE `id` = '".$row2->id_travel_mode."'").'</td>';



                    //$availableData .='<td>'.$row2->supervisor_remarks.'</td>';



					



					$availableData .= '



                    <td style=" text-align:center;">'.$row2->KmsRun.'</td>



					<td style=" text-align:center;">'.$row2->RateKm.'</td>



					<td style=" text-align:center;" width="5%">'.$row2->Parking.'</td>



					



					<td style=" text-align:center;" width="5%">'.$row2->Total.'</td>



					<td style=" text-align:center;" width="12%">'.$conApprove.'</td>



					</tr>'; 



					



				  }



}					



	$availableData .= '



						<tr>



						<td  colspan="6"></td>



						<td colspan="2" style="text-align:center; border:1px solid;font-size:16px; " width="12%"><b>Grand Total :</b></td>



						  <td   style=" text-align:center;border:1px solid;font-size:16px; " width="5%" style="font-size:16px;">



							<b>'.$TotalSum.' </b>



						 	</td>



						 	<td ></td>



						   



						</tr></table></div>



					



       ';







       



	   



$availableData .= '</div>';	



							



				$TotalSum=0;	$DayVisitcount=0;	$counter=1;



				}



				



				







				}



						



						



			  	 		



				



                     }



 



 







/*================================CONVEYANCE START==================================================================================*/



/*echo $availableData;



exit;*/







//die;



$dompdf = new DOMPDF();



$dompdf->set_paper('letter', 'landscape');



$dompdf->load_html($availableData);



$dompdf->render();







if($_REQUEST['location']=='set'){



	$Filename='DSR_'.str_replace(' ','',selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ')).'_'.date('d-m-Y',strtotime($checkin));



	$gen = $dompdf->output();



	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));



	file_put_contents('mailattach/'.$Filename.'.pdf', $gen);



}



else if($_REQUEST['location']=='open'){



	//$dompdf->output();



	//$dompdf->stream();

		

	$dompdf->stream('SalesReport.pdf', array("Attachment" => false));



}



else{



	$dompdf->output();



	//$dompdf->stream();



	$dompdf->stream('SalesReport.pdf', array("Attachment" => true));



}















/*$dompdf->load_html($availableData);



//$dompdf->setPaper('A4', 'landscape');



$dompdf->render();







$dompdf->output();



$dompdf->stream();



    //file_put_contents('Brochure.pdf', array("Attachment" => false	));



//$dompdf->stream('test.pdf', array("Attachment" => false	));*/



}



 



?>



<?php include_once("includes/header.php")?>







<?php include_once("includes/left.php")?>



<div class="content-wrapper">



  <!-- Content Header (Page header) -->



  <section class="content-header">
	<?php
	 $text='';
	 if(isset($_REQUEST['link']))
	 	$text='Sales Visit';
	 else
	 	$text='Daily Sales Report';
	?>


    <h1><?php echo $text; ?><small></small> </h1>



    <ol class="breadcrumb">



      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>



      <li class="active"><?php echo $text; ?></li>



    </ol>



  </section>



  <!-- Main content -->



  <section class="content">



  <div class="row">



    <div class="col-xs-12">



      <div class="nav-tabs-custom">



        <div class="form-group has-error" align="center">



          <?php if($_SESSION['errorMsg']){?>



          <p class="help-block success"><?php echo messageError($_SESSION['errorMsg']);?></p>



          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>



          <p class="help-block dander"><?php echo messageSuc($_SESSION['successMsg']);?></p>



          <?php unset($_SESSION['successMsg']);}?>



        </div>



        <div class="box-header with-border">



          <h3 class="box-title">Search <small>Total Records: (



            <?=$numRows;?>



            ) &nbsp;</small> </h3>



            <a title="Add VISIT" class="pull-right btn btn-success" href="addreport.php" style="color:#fff;font-weight:bold;">&nbsp;ADD SALES VISIT</a>



          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>



            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->



            <ul class="dropdown-menu" role="menu">



              <?php /*?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_RATE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>



								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_RATE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>



            </ul>



          </div>



        </div>



        <!-- /.box-header -->



        <form name="searchForm" action="" method="get">



          <input type="hidden" value="1" name="searchFormSubmit" />



          <div class="box-body">



            <div class="row">



            <div class="form-group col-sm-6">







                <label for="reservation_date">From - To </label>







                <div class="input-group">







                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>







                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">







                </div>







                <!-- /.input group --> 







                <span id="reservation_dateError"></span> </div>



              <!--<div class="form-group col-sm-6">



                <label for="seasonId">Date <font color="#FF0000">*</font></label>



                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>



              </div>-->



              <!--<div class="col-md-6">



                <div class="form-group">



                  <label>Company - City</label>



                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>



										    <option value="">Select Company</option>';



											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !=''   and FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."') ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if($_REQUEST['companyId'] == $resultCat->id_company){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';



												}



											  }



											 	echo $companyDropDown .= '</select>';



											  ?>



                </div>



              </div>-->



              <?php 



			  if($_SESSION['userLevel']==1){



				 	



				  $ConditonUserLevel = "";



				  }else{



					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";



					  }



			  ?>



              <div class="col-md-6">



                <div class="form-group">



                <label>Sales Executive</label>



                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->



               <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">



						';



						  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1'   ".$teamMembers."  $UserRestriction AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resUserLevel)){



											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){



													if($_REQUEST['usernameid'] == $resultUserLevel->id){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';



												}



											  }



											 	echo $categoryDropDown .= '</select>';



											  ?>



                                              



                                              </div>



              </div>



              <!-- /.col -->







              <!--<div class="form-group col-sm-6">







                  <label for="remarks">State</label>







                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="state" id="state" >







												  <option value="">Select State</option>';



												 







												  $resCat = selectSql(TBL_STATE," where status='1' AND id_country='110' ",' ORDER BY `name`');										







												  if($db->num_rows2($resCat)){







													while($resultCat = $db->fetch_object2($resCat)){







														if($resultCat->id_state ==$_REQUEST['state']){







															$selected = 'selected="selected"';







														}else if($_REQUEST['state']== $resultCat->id_state){







															$selected = 'selected="selected"';







														}else{







															$selected = '';







														}	







														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';







													}







												  }







													echo $marketDropDown .= '</select>';







												  ?>







					 







                </div>



              



             


            </div>-->



          </div>



          <!-- /.box-body -->



          <div class="box-footer">



            <input name="Search" type="submit" class="btn btn-primary" value="Search" />



            <?php if($_REQUEST['link'] !="set"){ ?>



            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />



            </div>



            <?php } ?>



            



        </form>



      



        <div class="box">



          <div class="box-header">



            <h3 class="box-title">Sales Calls List</h3>



          </div>



          <form name="listingForm" action="" method="post">



            <input type="hidden" value="" name="act" />



            <div id="listingDiv"></div>



            <!-- /.box-header -->



            <div class="box-body table-responsive">



              <table id="example2" class="table table-bordered table-striped">



               



       <?php



 if($_REQUEST['Search'] == 'Search'){



$db->query($sql);



$numRows= $db->num_rows();



$pagging = new pagingClass($sql,$setpage);



$db->query($pagging->getQuery());



$total = $db->num_rows();



















	$datawisearrayFinal = array();			







	if($total > 0){		







		$cntr_order= 0;







		while($row = $db->fetch_object()){











				foreach($datewise_array as $checkinDatearr){			











				if(strtotime($checkinDatearr)==strtotime($row->dated)){







				//print_r($row->dated);







					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["id"]=$row->id;







					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["company"]=$row->id_company;







					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["customer"]=$row->id_contacts;



					



					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["business_potential"]=$row->business_potential;



					



					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["discussion_summary"]=$row->discussion_summary;



					



					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["customer"]=$row->id_contacts;







				



					$datawisearrayFinal[$checkinDatearr][$row->id][$row->visite]["id_user"]=$row->id_user;







					







				}







				







			}







		}







	}







}











 



?>       



                   <?php 		







				







				if($total > 0){$counter = 1;







				







				foreach($datawisearrayFinal as $dateCheckin=>$dateData){?>



<?php //print_r($datawisearrayFinal); ?>



                    <tr>







                      <th >Date: <?php echo dateformat_date($dateCheckin)?></th>







                      <td ></td>







                    </tr>







                    <tr>







                      <td><table >







                          <tr>







                            <th style="width:22%;">Company Name</th>







                            <th style="width:10%;">Person Met</th>











     						<!--<th style="width:23%">Business Potential</th>-->



                            



                            <th style="width:35%">Discussion Summary</th>



                             <th style="width:15%;">Sales Executive</th>



                             <!--<th style="width:15%;">Supervisor Remark </th>-->



                            <th style="width:15%">Action</th>



  







                          </tr>







                          <?php







					foreach($dateData as $hotelcheckarr=>$order_data1){







						



						foreach($order_data1 as $room_idfromarr=>$order_data){



//print_r($order_data);



						?>







                          <tr>



                          



 <td style="padding-bottom:10px; "><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$order_data['company']."'");?></td>



  <?php	//substr(wordwrap($order_data['business_potential'],40,"<br>\n"), 0, 100);



					$resContact = selectSql(TBL_CUSTOMER,"where type='2' and id_customer='".addslashes($order_data['customer'])."' AND id_company='".$order_data['company']."' ",''); 



		  			$resultContact = $db->fetch_object2($resContact);



                    $NAme	=	$resultContact->first_name.' '.$resultContact->last_name;



					?>



 



  <td style="padding-bottom:20px 10px; "><?php echo ucfirst($NAme) ;?></td>



 <!--<td style="padding-bottom:20px 10px; "><?php echo ucwords($order_data['business_potential']);?></td>-->



 



 <td style="padding-left:10px ;width:50%;"><?php echo ucwords($order_data['discussion_summary']);?></td>



 



 







                          



                    <td style="padding-bottom:10px;text-align:center;"><?php echo ucfirst(selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($order_data['id_user'])."'",''));   ?></td>



                    <!--<td style="padding-bottom:10px;text-align:center;"><?php// echo ucfirst($resultContact->supervisor_remarks);?></td>-->







                            <td style="padding-bottom:10px;">&nbsp;&nbsp;<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='addreport.php?eId=<?=encryptor('encrypt',$order_data['id'])?>&usernameid=<?=$_REQUEST['usernameid']?>&action=edit&page=<?=$_REQUEST['page']?>';" />



                    &nbsp;&nbsp; <a href="#" id="<?=encryptor('encrypt',$order_data['id'])?>" onClick="deleteMe(this.id,<?=strtotime($dateCheckin);?>);" title="Delete"><i class="fa fa-remove" ></i></a> </td>







                           







                           







                            







                            







                              </tr/>







                            <?php







						}







					}







				?>







                        </table></td>







                    </tr>







                    <?php







				}







				?>







                    <tr>







                      <td align="left" colspan="6">&nbsp;&nbsp;&nbsp;&nbsp; </td>







                    </tr>







                    <tr>







                      <td align="right" colspan="6"><?php  echo $pagging->getLinks();?></td>







                    </tr>







                    <?php }else {?>







                    <tr>







                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>







                    </tr>







                    <?php }?>



                    



                    



			



                </tbody>



              </table>



            </div>



          </form>



          <!-- /.box-body -->



        </div>



        <!-- /.box -->



      </div>



      <!-- /.col -->



    </div>



    <!-- /.row -->



    </section>



    <!-- /.content -->



  </div>



  



  <div id="duplicate" class="well" style="display:none;">



    



  </div>



  <?php include_once("includes/footer.php")?>



  <script type="text/javascript">



  	function deleteMe(id,dated){



  		var type=confirm("Do you want to delete this visit ! ");



  		var report_date = '<?php echo $checkin.'+to+'.$checkout;?>';



  		var user = '<?php echo $_REQUEST['usernameid'];?>';



  		console.log(report_date);



  		if(type==true){



  			window.location.href='ManagervisitReport.php?searchFormSubmit=1&report_date='+report_date+'&dated='+dated+'&Search=Search&eId='+id+'&usernameid='+user+'&action=delete&page=<?=$_REQUEST['page']?>';



  		}



  		else{



  			return;



  		}



  	}



  </script>



