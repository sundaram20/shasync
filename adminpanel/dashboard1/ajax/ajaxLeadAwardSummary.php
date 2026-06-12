<?php 

include_once("../../../config/auto_loader.php");


$per_report_date =$_REQUEST['per_report_date'];



if($_REQUEST['per_report_date']>0){
	
	//$sqlConn .= " AND usr.myownteam_id IN (".$_REQUEST['id_team'].")";	
	
	}

if($_REQUEST['id_team']>0){
	
	$sqlConn .= " AND usr.myownteam_id IN (".$_REQUEST['id_team'].")";	
	
	}

$LeadSql = "SELECT eq.id , case when eqd.lead_status='1' then 'OPEN' when eqd.lead_status='0' then 'CLOSE' end as leadStatus, hotel.name as hotelName,
hotel.city as city,eq.hotel_id, eqd.created_by,eq.created_by as leadCreatedBy, eqd.assign_user_id,


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

FROM fs_enquiry_details as eqd INNER JOIN fs_enquiry as eq ON eq.id=eqd.enquiry_id AND eqd.id IN(SELECT max(id) from `fs_enquiry_details` group by enquiry_id ) INNER JOIN fs_hotels as hotel ON hotel.id=eq.hotel_id INNER JOIN fs_users usr on usr.id=eqd.created_by LEFT JOIN fs_incentive as inc ON inc.id_enquiry=eqd.enquiry_id 

 ";
 
 $LeadSqlQuery= mysqli_query($connNew,$LeadSql);
$LeadAwardArray=array();
while($rowLeadSummary=mysqli_fetch_object($LeadSqlQuery)){
	
	$leadCreated	=selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowLeadSummary->leadCreatedBy."'");
	$teamName	=selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowLeadSummary->myownteam_id."'");
	
	$daysNew =  abs((strtotime($rowLeadSummary->checkin) - strtotime($rowLeadSummary->checkout))/ 86400 );
		if($daysNew == '0'){
			$no_of_days = '1';
		}else {
			$no_of_days = $daysNew;
		}
		
	
	$HotelName = $rowLeadSummary->hotelName.'-'.$rowLeadSummary->city;
	$leadStatus	=	$rowLeadSummary->leadStatus=='OPEN'?1:0;
	$Materialised2=0;
	
	if($rowLeadSummary->incentive_current_status=='3'){
	$Materialised2	= '1';//$rowLeadSummary->followup_close_type_id=='6'?'1':'0';
	$RoomNights	= $no_of_days*$rowLeadSummary->no_room;
	$approved_amount=$rowLeadSummary->approved_amount;
	}else{
		$Materialised2=0;
		$RoomNights	= '0';//$no_of_days*$rowLeadSummary->no_room;
		$approved_amount=0;
		}
	$Declined		 = $rowLeadSummary->followup_close_type_id!='6'?'1':'0';
	
	//echo '==='.$rowLeadSummary->followup_close_type_id;
	
	$user_type	=selectColumn(TBL_USERS,'user_type'," WHERE `id` = '".$rowLeadSummary->leadCreatedBy."'");
if($user_type=='2')	{
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['leadCreated']=$leadCreated;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['HotelName']=$HotelName;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Generated']=$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Generated']+1;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Materialised'] +=($Materialised2);
	
	
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Declined'] +=$Declined;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Open'] +=$leadStatus;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Team']= $teamName;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['RoomNights'] +=$RoomNights;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['Revenue'] +=$approved_amount;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy]['Header-'.$rowLeadSummary->leadCreatedBy]['id_header'] ='Header-'.$rowLeadSummary->leadCreatedBy;
	
	
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['leadCreated']=$leadCreated;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['HotelName']=$HotelName;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Team']=$teamName;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Generated']=$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Generated']+1;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Materialised'] +=($Materialised2);
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Declined'] +=$Declined;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Open']+=$leadStatus;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['RoomNights'] +=$RoomNights;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['Revenue'] +=$approved_amount;
	$LeadAwardArray['Lead'][$rowLeadSummary->leadCreatedBy][$rowLeadSummary->hotel_id]['id_header'] ='';
	
	
	if($_SESSION['hotel_access']!=''){
	$userHotelAccess	=		explode(',',$_SESSION['hotel_access']);
	}else{$userHotelAccess[]=$rowLeadSummary->hotel_id;}
	if(in_array($rowLeadSummary->hotel_id,$userHotelAccess)){
	//Hotel Wise Array
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['leadCreated']=$leadCreated;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['HotelName']=$HotelName;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Team']=$teamName;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Generated']=$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Generated']+1;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Materialised'] +=$Materialised2;
	
	
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Declined'] +=$Declined;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Open'] +=$leadStatus;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['RoomNights'] +=$RoomNights;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['Revenue'] +=$approved_amount;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id]['Hotel-'.$rowLeadSummary->hotel_id]['id_header'] ='Hotel-'.$rowLeadSummary->hotel_id;
	
	
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['leadCreated']=$leadCreated;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['HotelName']=$HotelName;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Team']=$teamName;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Generated']=$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Generated']+1;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Materialised'] +=($Materialised2);
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Declined'] +=$Declined;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Open']+=$leadStatus;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['RoomNights'] +=$RoomNights;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['Revenue'] +=$approved_amount;
	$LeadHotelWiseAwardArray['Lead'][$rowLeadSummary->hotel_id][$rowLeadSummary->leadCreatedBy]['id_header'] ='';
	}
}
}
//debugData($LeadHotelWiseAwardArray);


$dataConn .='<div id="blk-leadaward" class=""  style="display:block!important;">
            <div class="row">
                   <div class="col-md-12">
                       <table id="accordion"  class="card table table-striped text-center" style=" border: 1px solid :#3C8DBC;">
                          
                              <thead>
                                    <tr style="color:white;">
                                       <th colspan="9" style="background-color:#548235;vertical-align: middle;font-size:18px;">Sales Lead Award Summary as on 10/10/2022 </th>     
                                     </tr>
                                     <tr style="color:white;">
                                        <th rowspan="2" style="border:1px solid #000;background-color:#fff;color:#000;vertical-align: middle;">S NO</th>
                                        <th  rowspan="2" style="border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Unit Sales Associate  <div style="font-weight:600; ">Team</div></th>
                                        <th rowspan="2" style="border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Hotel Name</th>
                                       <th colspan="4" style="border:1px solid #000;background-color:#FFD966;color:#000;vertical-align: middle;">Leads</th>                     
                                      <th colspan="4" style="border:1px solid #000;background-color:#C6E0B4;color:#000;vertical-align: middle;">Business Generated</th>
                                    </tr> 
                                       <tr style="color:white;">
                                       <th  style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Generated</th>                     
                                      <th  style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Materialised</th>
                                      <th style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Declined</th>
                                      <th style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Open</th>
                                      <th style="border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Room Nights</th>
                                      <th style="border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Revenue(Lakh)</th>
                                      
                                    </tr> 
                                </thead>'; 
								$uc=1;
								foreach($LeadAwardArray  as $user=>$leadData1){
	foreach($leadData1  as $user2=>$leadData2){
		
		//echo '======'.$user2;
		
		foreach($leadData2  as $user3=>$leadData3){//id_header
			//echo '<br>'.$user2.'=='.$leadData3['id_header'];
			if('Header-'.$user2==$leadData3['id_header']){
				$dataConn .='<tbody style="border-top:none!important;">
                                    <tr style="color:white;cursor:pointer;" class="card-header"  data-toggle="collapse" data-target="#collapseUser'.$user2.'" aria-expanded="true" aria-acontrols="collapse2" >
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;">'.$uc++.'</td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;font-weight:600;text-align:left;">'.$leadData3['leadCreated'].'  <div style="font-weight:500;">'.$leadData3['Team'].'</div></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                       <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Generated'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Materialised'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Declined'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Open'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData3['RoomNights'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData3['Revenue'].'</td>
                                    </tr>
                                 </tbody>';
								 $dataConn .='<tbody id="collapseUser'.$user2.'" class="collapse" data-parent="#accordion" style="border-top:none!important;">';
				 }else{ //debugData($leadData3);
       
                                    $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;;color:#000;text-align:left;">'.$leadData3['HotelName'].'</td>
                                       <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Generated'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Materialised'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Declined'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Open'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['RoomNights'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Revenue'].'</td>
                                     </tr>'; 
                                    

                                  
                                 
        }
		}
		 $dataConn .='</tbody>';
	
	}
	}
                                      

                                     

                                    


                                   

                                 


                                    

                                               
                              
                      $dataConn .='</table> <!--end of table-->             
                   </div><!--end of col-->
            </div><!--end of row-->
       </div>';
	   
	   $dataConn .='<div id="blk-leadaward" class=""  style="display:block!important;">
            <div class="row">
                   <div class="col-md-12">
                       <table id="accordion"  class="card table table-striped text-center" style=" border: 1px solid :#3C8DBC;">
                          
                              <thead>
                                    <tr style="color:white;">
                                       <th colspan="9" style="background-color:#548235;vertical-align: middle;font-size:18px;">Sales Lead  Summary as on 10/10/2022 </th>     
                                     </tr>
                                     <tr style="color:white;">
                                        <th rowspan="2" style="border:1px solid #000;background-color:#fff;color:#000;vertical-align: middle;">S NO</th>
                                        <th  rowspan="2" style="border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Hotel Name  <div style="font-weight:600; "></div></th>
                                        <th rowspan="2" style="border:1px solid #000;background-color:#F8CBAD;color:#000;vertical-align: middle;">Generated By</th>
                                       <th colspan="4" style="border:1px solid #000;background-color:#FFD966;color:#000;vertical-align: middle;">Leads</th>                     
                                      <th colspan="4" style="border:1px solid #000;background-color:#C6E0B4;color:#000;vertical-align: middle;">Business Generated</th>
                                    </tr> 
                                       <tr style="color:white;">
                                       <th  style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Received</th>                     
                                      <th  style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Materialised</th>
                                      <th style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Declined</th>
                                      <th style="border:1px solid #000;background-color:#FFD966;vertical-align: middle;color:#000;">Open</th>
                                      <th style="border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Room Nights</th>
                                      <th style="border:1px solid #000;background-color:#C6E0B4;vertical-align: middle;color:#000;">Revenue(Lakh)</th>
                                      
                                    </tr> 
                                </thead>'; 
								$ic=1;
								foreach($LeadHotelWiseAwardArray  as $user=>$leadData1){
									
									
								
									
	foreach($leadData1  as $user2=>$leadData2){	
		
		//echo '======'.$user2;
		
		foreach($leadData2  as $user3=>$leadData3){//id_header
			//echo '<br>'.$user2.'=='.$leadData3['id_header'];
			if('Hotel-'.$user2==$leadData3['id_header']){
				$dataConn .='<tbody style="border-top:none!important;">
                                    <tr style="color:white;cursor:pointer;" class="card-header"  data-toggle="collapse" data-target="#collapse'.$user2.'" aria-expanded="true" aria-acontrols="collapse2" >
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;">'.$ic++.'</td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;font-weight:600;text-align:left;">'.$leadData3['HotelName'].'  </td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                       <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Generated'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Materialised'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Declined'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;font-weight:700;">'.$leadData3['Open'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData3['RoomNights'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;font-weight:700;">'.$leadData3['Revenue'].'</td>
                                    </tr>
                                 </tbody>';
								 $dataConn .='<tbody id="collapse'.$user2.'" class="collapse" data-parent="#accordion" style="border-top:none!important;">';
				 }else{ //debugData($leadData3);
       
                                    $dataConn .='<tr style="color:white;">
                                       <td  style="border:1px solid #000;background-color:#FFF;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;color:#000;"></td>
                                        <td style="border:1px solid #000;background-color:#FCE4D6;;color:#000;text-align:left;">'.$leadData3['leadCreated'].'<div style="font-weight:500;">'.$leadData3['Team'].'</div></td>
                                       <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Generated'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Materialised'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Declined'].'</td>
                                        <td style="border:1px solid #000;background:#FFF2CC;color:#000;">'.$leadData3['Open'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['RoomNights'].'</td>
                                        <td style="border:1px solid #000;background-color:#E2EFDA;color:#000;">'.$leadData3['Revenue'].'</td>
                                     </tr>'; 
                                    

                                  
                                 
        }
		}
		 $dataConn .='</tbody>';
	
	}
	}
                                      

                                     

                                    


                                   

                                 


                                    

                                               
                              
                      $dataConn .='</table> <!--end of table-->             
                   </div><!--end of col-->
            </div><!--end of row-->
       </div>';
      // echo $dataConn;
	   
	   
       echo $dataConn;?>