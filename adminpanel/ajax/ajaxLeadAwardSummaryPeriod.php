<?php 

include_once("../../config/auto_loader.php");

//print_r($_REQUEST);
$per_report_date =$_REQUEST['per_report_date'];

$leadSummaryType =$_REQUEST['leadSummaryType'];

if($_REQUEST['date_period']!=''){
	$financial_year=explode('to',$_REQUEST['date_period']);
	$from=$financial_year[0];
	$to=$financial_year[1];
	$sqlConn .= "  AND DATE(`eq`.created_date) BETWEEN '".date('Y-m-d',strtotime($from))."' AND '".date('Y-m-d',strtotime($to))."'";	
	
	}else{
		
		$sqlConn .= "  AND DATE(`eq`.created_date) BETWEEN '".date('Y-04-01')."' AND '".date('Y-m-d',strtotime($_REQUEST['per_report_date']))."'";
		$from=date('Y-04-01');
	$to=date('Y-m-d',strtotime($_REQUEST['per_report_date']));
		}

if($_REQUEST['id_team']>0){
	
	//$sqlConn .= " AND usr.myownteam_id IN (".$_REQUEST['id_team'].")";	
	
	}

$LeadSql = "SELECT eq.id ,

 	case when eqd.lead_status='1' then 'OPEN' when eqd.lead_status='0' then 'CLOSE' end as leadStatus, 
 
 
 hotel.name as hotelName,
hotel.city as city,eq.hotel_id,eq.id_mst_lead_source as leadSource, eqd.created_by,eqd.commission as commission,eq.created_by as leadCreatedBy, eqd.assign_user_id,


case when inc.no_room!='' and IFNULL(inc.no_room,0)>0 then no_room
    else 0 end
    as no_room,
    
   case when inc.no_pax!='' and IFNULL(inc.no_pax,0)>0 then no_pax
    else 0 end
    as no_pax,
    case when inc.revenue!='' and IFNULL(inc.revenue,0)>0 then inc.revenue
    else 0 end
    as revenue_inc,
    
    case when inc.approved_amount!='' and IFNULL(inc.approved_amount,0)>0 then approved_amount
    else 0 end
    as approved_amount,
	inc.checkin,
	inc.checkout,
    eqd.followup_close_type_id,
	usr.myownteam_id,
	inc.current_status as incentive_current_status

FROM fs_enquiry_details as eqd 
INNER JOIN fs_enquiry as eq ON eq.id=eqd.enquiry_id AND eqd.id IN(SELECT max(id) from `fs_enquiry_details` group by enquiry_id ) 
INNER JOIN fs_hotels as hotel ON hotel.id=eq.hotel_id 
INNER JOIN fs_users usr on usr.id=eqd.created_by 
LEFT JOIN fs_incentive as inc ON inc.id_enquiry=eqd.enquiry_id WHERE 1=1 ".$sqlConn."

 ";
 //echo $LeadSql;
 $LeadSqlQuery= mysqli_query($connNew,$LeadSql);
$LeadAwardArray=array();
while($rowLeadSummary=mysqli_fetch_object($LeadSqlQuery)){
	
	$leadCreated	=selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowLeadSummary->leadCreatedBy."'");
	
	//$teamName	=selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowLeadSummary->myownteam_id."'");
	$myownteam_id=selectColumn(TBL_USERS,'myownteam_id'," WHERE `id` = '".$rowLeadSummary->leadCreatedBy."'");
	$teamName	=selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$myownteam_id."'");
	$LeadSource	=selectColumn(TBL_LEAD_SOURCE_MASTER,'name'," WHERE `id` = '".$rowLeadSummary->leadSource."'");

	$daysNew =  abs((strtotime($rowLeadSummary->checkin) - strtotime($rowLeadSummary->checkout))/ 86400 );
		if($daysNew == '0'){
			$no_of_days = '1';
		}else {
			$no_of_days = $daysNew;
		}
		
	
	$HotelName = $rowLeadSummary->hotelName.'-'.$rowLeadSummary->city;
	$leadStatus	=	$rowLeadSummary->leadStatus=='OPEN'?1:0;
	$Materialised2=0;
	
	$Commission = $rowLeadSummary->commission;

	if($leadStatus=='1'){ //Lead Status OPEN
	
		$Materialised2	= '0';
		$RoomNights	='0';
		$approved_amount='0';
		$Declined		 ='0';
		$Pending		 ='0';
		$leadStatusCount= '1';
		$NotApproved	='0';
	}else{  //Lead Status Close
		
		$LeadCloseTypeId	=	$rowLeadSummary->followup_close_type_id;
		
			if($LeadCloseTypeId=='6'){
						if($rowLeadSummary->incentive_current_status=='2' ){ //INcentive Not Approved
							$Materialised2	= '0';
							$RoomNights	='0';
							$approved_amount='0';
							$Declined		 ='0';
							$Pending		 ='0';
							$leadStatusCount= '0';
							$NotApproved	='1';
						
						}elseif($rowLeadSummary->incentive_current_status=='0' || $rowLeadSummary->incentive_current_status=='1'){
		
							$Materialised2	= '0';
							$RoomNights	='0';
							$approved_amount='0';
							$Declined		 ='0';
							$Pending		 ='1';
							$leadStatusCount= '0';
							$NotApproved	='0';
		
						}elseif($rowLeadSummary->incentive_current_status=='3'){
							$Materialised2	= '1';
							$RoomNights	= $no_of_days*$rowLeadSummary->no_room;
							$approved_amount=$rowLeadSummary->approved_amount;
							$Declined		 ='0';
							$Pending		 ='0';
							$leadStatusCount= '0';
							$NotApproved	='0';
						}
			}else{
					$Materialised2	= '0';
					$RoomNights	='0';
					$approved_amount='0';
					$Declined		 ='1';
					$Pending		 ='0';
					$leadStatusCount= '0';
					$NotApproved	='0';
				
				
				}
		
		
		
		
		}
	
	
	
	/*
	0 For Pending
	1 For Verifyed By Corperate
	2 For Not Approved
	3 For verified By Hotel
	
	Row Null Declined BY Lead
	*/
	
/*if($leadStatus=='1'){
			
			
	
	$Materialised2	= '1';
	$RoomNights	= '0';
	$approved_amount=0;
	$Declined		 ='0';
	$Pending		 ='0';
}else{*/
/*if($rowLeadSummary->incentive_current_status=='3'){	
	
	$Materialised2	= '1';
	$RoomNights	= $no_of_days*$rowLeadSummary->no_room;
	$approved_amount=$rowLeadSummary->approved_amount;
	$Declined		 ='0';
	$Pending		 ='0';	
	
}elseif($rowLeadSummary->incentive_current_status=='0' || $rowLeadSummary->incentive_current_status=='1'){
		
		$Materialised2=0;
		$RoomNights	= '0';
		$approved_amount=0;
		$Declined		 ='0';
		$Pending		 ='0';
		
}elseif($rowLeadSummary->incentive_current_status=='2' ){
	$Materialised2=0;
		$RoomNights	= '0';
		$approved_amount=0;
		$Declined		 ='0';
		$Pending		 ='1';
		
}elseif($rowLeadSummary->incentive_current_status=='' || $rowLeadSummary->incentive_current_status=='NULL' ){
		
		$Materialised2=0;
		$RoomNights	= '0';
		$approved_amount=0;
		$Declined		 ='1';
		$Pending		 ='0';
}*/

	
	
//}
		
		
		
		//$Materialised2=0;
		//$RoomNights	= '0';//$no_of_days*$rowLeadSummary->no_room;
		//$approved_amount=0;
		//}
		
	//$Declined		 = $rowLeadSummary->followup_close_type_id!='6'?'1':'0';
	
	//echo '==='.$rowLeadSummary->followup_close_type_id;
	
	$user_type	=selectColumn(TBL_USERS,'user_type'," WHERE `id` = '".$rowLeadSummary->leadCreatedBy."'");
if($user_type=='2')	{
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['leadCreated']=$leadCreated;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['HotelName']=$HotelName;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['LeadSource']=$LeadSource;

	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Generated']+=1;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Materialised'] +=($Materialised2);
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['NotApproved'] +=$NotApproved;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Pending'] +=$Pending;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Declined'] +=$Declined;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Open'] +=$leadStatusCount;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Team']= $teamName;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['RoomNights'] +=$RoomNights;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['Revenue'] +=$approved_amount;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['id_header'] ='Header-'.$rowLeadSummary->leadCreatedBy;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['leadCreatedBy'] =$rowLeadSummary->leadCreatedBy;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['leadCreatedBy'] +=$rowLeadSummary->commission;
	
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['leadCreated']=$leadCreated;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['HotelName']=$HotelName;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['LeadSource']=$LeadSource;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Team']=$teamName;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Generated']+=1;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Materialised'] +=($Materialised2);
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Pending'] +=$Pending;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['NotApproved'] +=$NotApproved;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Declined'] +=$Declined;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Open']+=$leadStatusCount;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['RoomNights'] +=$RoomNights;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Commission'] +=$Commission;

	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Revenue'] +=$approved_amount;
	$LeadAwardArray['Header-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['id_header'] ='';
	//echo "<br><br>";
	//print_r($LeadAwardArray); 

	if($_SESSION['hotel_access']!=''){
	$userHotelAccess	=		explode(',',$_SESSION['hotel_access']);
	}else{$userHotelAccess[]=$rowLeadSummary->hotel_id;}
	if(in_array($rowLeadSummary->hotel_id,$userHotelAccess)){
	//Hotel Wise Array
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['leadCreated']=$leadCreated;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['HotelName']=$HotelName;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['LeadSource']=$LeadSource;

	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Team']=$teamName;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Generated']+=1;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Materialised'] +=$Materialised2;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['NotApproved'] +=$NotApproved;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Pending'] +=$Pending;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Declined'] +=$Declined;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Open'] +=$leadStatusCount;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['RoomNights'] +=$RoomNights;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['Revenue'] +=$approved_amount;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['id_header'] ='User-'.$rowLeadSummary->leadCreatedBy;
	
	
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['leadCreated']=$leadCreated;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['HotelName']=$HotelName;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['LeadSource']=$LeadSource;

	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Team']=$teamName;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Generated']+=1;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Materialised'] +=($Materialised2);
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Pending'] +=$Pending;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['NotApproved'] +=$NotApproved;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Declined'] +=$Declined;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Open']+=$leadStatusCount;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['RoomNights'] +=$RoomNights;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Revenue'] +=$approved_amount;
	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['Commission'] +=$Commission;

	$LeadUserWiseAwardArray['User-'.$rowLeadSummary->leadCreatedBy]['SPLIT'][$rowLeadSummary->hotel_id]['id_header'] ='';
	}
}
}
//debugData($LeadAwardArray);

if($leadSummaryType=='1'){
	 if($_REQUEST['status']!='download'){
		   $callSpan	='11';
	   }else{$callSpan	='10';
		   }
$dataConn .='<div id="blk-leadaward" class=""  style="display:block!important;">
            <div class="row">
                   <div class="col-md-12">
                       <table id="accordion" cellpadding="1" border="0"  cellspacing="0" class="card table table-striped text-center" style="font-size:12px;  ">
                          
                              <thead>
                                    <tr style="color:white;">
                                       <th colspan="'.$callSpan.'" style="background-color:#548235;vertical-align: middle;font-size:20px;padding:10px;">Sales Lead Award Summary Period '.date('d-m-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($to)).' </th>     
                                     </tr>
                                     <tr style="color:black;">
                                        <th rowspan="3" style="font-size:13px;width:5%;border:1px solid #000;background-color:#fff;color:#000;vertical-align: middle;">S NO</th>
                                        <th  rowspan="3" style="font-size:13px;width:25%;border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Unit Sales Associate  <div style="font-weight:500;">Team</div></th>';
	if($_REQUEST['status']!='download'){
                                       $dataConn .=' <th rowspan="3" style="font-size:13px;width:25%;border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Product Name</th>';
										}
										
                                       $dataConn .='<th colspan="6" style="font-size:13px;padding:0;border:1px solid #000;background-color:#FFD966;color:#000;vertical-align: middle;">Leads</th>                     
                                      <th colspan="2" style="font-size:13px;border:1px solid #000;background-color:#C6E0B4;color:#000;vertical-align: middle;">Business Generated</th>
                                    </tr> 
                                       <tr style="color:black;">
                                       <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;"> Generated</th>                     
                                      <th rowspan="2"  style="font-size:13px;width:6%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Materialised</th>';
									   $dataConn .='<th colspan="2" style="font-size:13px;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Confirmed</th>';
									
                                      $dataConn .='<th rowspan="2" style="font-size:13px;width:4%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;"> Lost</th>
                                      <th rowspan="2" style="font-size:13px;width:4%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Open</th>
                                      <th  rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Room Nights</th>
                                      <th rowspan="2" style="font-size:13px;width:7%;border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Revenue</th>
                                      
                                    </tr> 

                                     <tr> 
                                        
                                          
                                         <th style="font-size:13px;width:7%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Pending For Incentive Approval</th>
                                         <th style="font-size:13px;width:7%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Incentive Not Approved</th>
                                      

                                       </tr> 
                                </thead>'; 
								$uc=1;
								
								 foreach($LeadAwardArray as   $key=> $Data){ 

$confimed_revenue[$key] = $Data['Materialised'];
$roomnights[$key] =$Data['Generated'];
}
$confimed_revenue = array_column($LeadAwardArray, 'Materialised');
$roomnights  = array_column($LeadAwardArray, 'Generated');
array_multisort($confimed_revenue, SORT_DESC, $roomnights, SORT_ASC, $LeadAwardArray);

								//debugData($LeadAwardArray);
								foreach($LeadAwardArray  as $user=>$leadData1){
									$random = rand(0000,9999);
									
									$dataConn .='<tbody style="border-top:none!important;">
                                    <tr style="color:white;cursor:pointer;" class="card-header"  data-toggle="collapse" data-target="#collapseUser'.$leadData1['leadCreatedBy'].$random.'" aria-expanded="true" aria-acontrols="collapse2" >
                                       <td  style="font-size:13px;text-align:center;border:1px solid #000;background-color:#FFF;color:#000;">'.$uc++.'</td>
                                        <td style="font-size:13px;padding:5px;padding-left:8px;text-align:center;border:1px solid #000;background-color:#FCE4D6;color:#000;font-weight:600;text-align:left;">'.$leadData1['leadCreated'].'  <div style="font-weight:500;">'.$leadData1['Team'].'</div></td>';
									if($_REQUEST['status']!='download'){
                                         $dataConn.='<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>';
									}
							     $dataConn.='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Generated'].'</td>
								<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Materialised'].'</td>';
								$dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Pending'].'</td>';
								$dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['NotApproved'].'</td>';
								$dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Declined'].'</td>
								<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Open'].'</td>
								<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData1['RoomNights'].'</td>
								<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData1['Revenue'].'</td>
								<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData1['Commission'].'</td>

							</tr>
                                 </tbody>';
								 $dataConn .='<tbody id="collapseUser'.$leadData1['leadCreatedBy'].$random.'" class="collapse" data-parent="#accordion" style="border-top:none!important;">';
				 
									
	foreach($leadData1  as $user2=>$mainDatalist){
		
		//echo '======'.$user2;
		 foreach($mainDatalist as   $key=> $Data){ 

$confimed_revenue[$key] = $Data['Materialised'];
$roomnights[$key] =$Data['Generated'];


}
$confimed_revenue = array_column($mainDatalist, 'Materialised');
$roomnights  = array_column($mainDatalist, 'Generated');


array_multisort($confimed_revenue, SORT_DESC, $roomnights, SORT_ASC, $mainDatalist);


		foreach($mainDatalist  as $user3=>$leadData3){//id_header
			//echo '<br>'.$user2.'=='.$leadData3['id_header'];
			if($_REQUEST['status']!='download'){
                                    $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                        <td style="font-size:13px;padding:5px;padding-left:8px;border:1px solid #000;font-weight:500;background-color:#FCE4D6;;color:#000;text-align:left;">'.$leadData3['HotelName'].'</td>
                                       <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Generated'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Materialised'].'</td>';
										$dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Pending'].'</td>';
										$dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['NotApproved'].'</td>';
                                        $dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Declined'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Open'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['RoomNights'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Revenue'].'</td>
										<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Commission'].'</td>

                                     </tr>'; 
                                    

			}
                    $Generated	+= $leadData3['Generated'];
					$Materialised	+= $leadData3['Materialised'];
					$Pending	+= $leadData3['Pending'];
					$NotApproved	+= $leadData3['NotApproved'];
					$Declined	+= $leadData3['Declined'];
					$Open	+= $leadData3['Open'];
					
					$RoomNights	+= $leadData3['RoomNights'];  
					$Revenue 	+=	$leadData3['Revenue'];   
					$Commission 	+=	$leadData3['Commission'];          
       
        
		}
		 
	
	}
								 
                                    
	
	$dataConn .='</tbody>';
	}
	if($_REQUEST['status']!='download'){
		   $callSpan2	='3';
	   }else{$callSpan2	='2';
		   }
          $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFD966;color:#000;font-weight:bold;text-align:right;text-align:center;" colspan="'.$callSpan2.'">Grand Total1</td>
                                         <td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Generated.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Materialised.'</td>';
										$dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Pending.'</td>';
										$dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$NotApproved.'</td>';
                                        $dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Declined.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Open.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$RoomNights.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Revenue.'</td>
										<td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;font-weight:bold;text-align:center;">'.$Commission.'</td>

                                     </tr>';                            

                                     

                                    


                                   

                                 


                                    

                                               
                              
                      $dataConn .='</table> <!--end of table-->             
                   </div><!--end of col-->
            </div><!--end of row-->
       </div>';
	  } 
	   
	   
	if($leadSummaryType=='2'){   
	  
									 
									 
	   if($_REQUEST['status']!='download'){
		   $callSpan	='11';
	   }else{$callSpan	='10';
		   }
	   $dataConn .='<div id="blk-leadaward" class=""  style="display:block!important;">
            <div class="row">
                   <div class="col-md-12">
                       <table id="accordion" cellpadding="1" border="0"  cellspacing="0" class="card table table-striped text-center" style=" font-size:12px; border: 1px solid :#3C8DBC;">
                          
                              <thead>
                                    <tr style="color:white;">
                                       <th colspan="'.$callSpan.'" style="background-color:#548235;vertical-align: middle;font-size:20px;padding:10px;">Sales Lead  Summary  Period '.date('d-m-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($to)).' </th>     
                                     </tr>
                                     <tr style="color:white;">
                                        <th rowspan="3" style="font-size:13px;width:5%;border:1px solid #000;background-color:#fff;color:#000;vertical-align: middle;">S NO</th>';
										 if($_REQUEST['status']!='download'){
											 $dataConn .='
                                        <th  rowspan="3" style="font-size:13px;width:25%;border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Executive Name  <div style="font-weight:600; "></div></th>';
										 }
                                        $dataConn .='<th rowspan="3" style="font-size:13px;width:25%;border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Product Name</th>
                                       <th colspan="5" style="font-size:13px;padding:0;border:1px solid #000;background-color:#FFD966;color:#000;vertical-align: middle;">Leads</th>                     
                                      <th colspan="3" style="font-size:13px;border:1px solid #000;background-color:#C6E0B4;color:#000;vertical-align: middle;">Business Generated</th>
                                    </tr> 

                                       <tr style="color:white;">
                                        <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;"> Source</th>       
                                       <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;"> Received</th>                     
                                      <th rowspan="2"  style="font-size:13px;width:6%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Materialised</th>';
									
									  
									   $dataConn .='<th rowspan="2" style="font-size:13px;width:4%;border-left:1px solid #000;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Lost</th>
                                      <th rowspan="2" style="font-size:13px;width:4%;border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Open</th>
                                      <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Nos</th>
                                      <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Revenue</th>
                                        <th rowspan="2" style="font-size:13px;width:6%;border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Commission</th>
                                      
                                    </tr> 
                                      
                                </thead>'; 
								$ic=1;
								
								
				 foreach($LeadUserWiseAwardArray as   $key=> $Data){ 

$confimed_revenue[$key] = $Data['Materialised'];
$roomnights[$key] =$Data['Generated'];
}
$confimed_revenue = array_column($LeadUserWiseAwardArray, 'Materialised');
$roomnights  = array_column($LeadUserWiseAwardArray, 'Generated');
array_multisort($confimed_revenue, SORT_DESC, $roomnights, SORT_ASC, $LeadUserWiseAwardArray);	


			
								
								foreach($LeadUserWiseAwardArray  as $user=>$leadData1){
									
									$random2 = rand(0000,9999);
								$dataConn .='<tbody style="border-top:none!important;">
                                    <tr style="color:white;cursor:pointer;" class="card-header"  data-toggle="collapse" data-target="#collapse'.$user.$random2.'" aria-expanded="true" aria-acontrols="collapse2" >
                                       <td  style="font-size:13px;text-align:center;border:1px solid #000;background-color:#FFF;color:#000;">'.$ic++.'</td>
                                        <td style="font-size:13px;padding:5px;padding-left:8px;border:1px solid #000;background-color:#FCE4D6;color:#000;font-weight:500;text-align:left;">'.$leadData1['leadCreated'].'  </td>';
										
									 if($_REQUEST['status']!='download'){	
                                        $dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>';
									 }
                                       $dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;"></td>
                                       <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Generated'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px 
										
										aadsolid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Materialised'].'</td>';
										
                                        $dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Declined'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData1['Open'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData1['RoomNights'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData1['Revenue'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">tt -'.$leadData1['Commission'].'</td>
                                    </tr>
                                 </tbody>';
								 $dataConn .='<tbody id="collapse'.$user.$random2.'" class="collapse" data-parent="#accordion" style="border-top:none!important;">';
				 
									
	foreach($leadData1  as $user2=>$leadData2){	
		
		//echo '======'.$user2;
		
		foreach($leadData2  as $user3=>$leadData3){//id_header
			 //debugData($leadData3);
       if($_REQUEST['status']!='download'){
                                    $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                        <td style="font-size:13px;padding:5px; padding-left:8px;border:1px solid #000;background-color:#FCE4D6;;color:#000;text-align:left;"><b>'.$leadData3['HotelName'].'</b><div style="font-weight:500;text-transform:capitalize;">'.$leadData3['Team'].'</div></td>
                                         <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['LeadSource'].'</td>
                                       <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Generated'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Materialised'].'</td>';
										
                                        $dataConn .='<td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Declined'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Open'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['RoomNights'].'</td>
                                        <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Revenue'].'</td>
                                         <td style="font-size:13px;text-align:center;border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Commission'].'</td>
                                     </tr>'; 
                                    
	   }
                                  
                     $GeneratedSummary	+= $leadData3['Generated'];
					$MaterialisedSummary	+= $leadData3['Materialised'];
					$PendingSummary	+= $leadData3['Pending'];
					$NotApprovedSummary	+= $leadData3['NotApproved'];
					$DeclinedSummary	+= $leadData3['Declined'];
					$OpenSummary	+= $leadData3['Open'];            
                       
                   $RoomNightsSummary	+= $leadData3['RoomNights'];  
					$RevenueSummary 	+=	$leadData3['Revenue'] ;   
					$CommissionSummary 	+=	$leadData3['Commission'] ;          
       
        
		}
		 
	
	}
								 
                                    
	
	$dataConn .='</tbody>';
	}
		if($_REQUEST['status']!='download'){
		   $callSpan1	='3';
	   }else{$callSpan1	='2';
		   }
          $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFD966;text-align:center;color:#000;font-weight:bold;" colspan="'.$callSpan1.'">Grand Total2</td>
									   <td style="font-size:13px;border:1px solid #000;background:#FFD966;text-align:center;color:#000;font-weight:bold"></td>
                                       <td style="font-size:13px;border:1px solid #000;background:#FFD966;text-align:center;color:#000;font-weight:bold">'.$GeneratedSummary.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;text-align:center;font-weight:bold">'.$MaterialisedSummary.'</td>';
										//$dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;text-align:center;font-weight:bold">'.$PendingSummary.'</td>';
									    //$dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;text-align:center;color:#000;font-weight:bold">'.$NotApprovedSummary.'</td>';
                                        $dataConn .='<td style="font-size:13px;border:1px solid #000;background:#FFD966;text-align:center;color:#000;font-weight:bold">'.$DeclinedSummary.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background:#FFD966;color:#000;text-align:center;font-weight:bold">'.$OpenSummary.'</td>  
                                        <td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;text-align:center;font-weight:bold;">'.$RoomNightsSummary.'</td>
                                        <td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;text-align:center;font-weight:bold;">'.$RevenueSummary.'</td>
										<td style="font-size:13px;border:1px solid #000;background-color:#FFD966;color:#000;text-align:center;font-weight:bold;">'.$CommissionSummary.'</td>

                                     </tr>';    
                                      

                                     

                                    


                                   

                                 


                                    

                                               
                              
                      $dataConn .='</table> <!--end of table-->             
                   </div><!--end of col-->
            </div><!--end of row-->
       </div>';
      // echo $dataConn;
	}
	  if($_REQUEST['pdf']==1 && $_REQUEST['excel']==0){

 //echo $dataConn;die;
$dompdf = new DOMPDF();
//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');
$dompdf->load_html($dataConn);
//debugData($dompdf);
$dompdf->render();
//debugData($dompdf);

//$font = Font_Metrics::get_font("helvetica", "bold");
//$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
$Filename=$ReportTypeMainTitle.'SalesLeadAwardSummary_'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}elseif($_REQUEST['pdf']==0 && $_REQUEST['excel']==1){
           
                        $Filename='SalesLeadAwardSummary_'.date("Y-m-d").'.xls';
                   
                    
        $test=$dataConn;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename");
        echo $test;die;
            
    
    
}else{
       echo $dataConn;
}?>