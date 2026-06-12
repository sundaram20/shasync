<?php

function followUpNotificationsReport($conn,$shop_id,$cron,$checkin,$checkout,$UserId){
	
	
	$Content='';
$Content = '<style>





.table-bordered {

    border: 1px solid #000;

}

.table {

    margin-bottom: 18px;

    max-width: 100%;

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

    font-size: 0.90em;

    padding: 7px !important;

}

.tdrightalign{

float:right;

margin-right:8px;

}</style>';
	
	
			if(isset($cron)){
			$cond='';$condEnq='';
			if($checkin != '' && $checkout != ''){

		$sql_Followup_Details .= " AND `".TBL_FOLLOWUP_DETAILS."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";

		$sql_Daily_Enqury .= " AND `".TBL_DAILY_ENQUERY."`.`follow_up_date` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime('-1 day',strtotime($checkout)))."'";
				
		$sql_Daily_Incentive .= " AND `fs_incentive_details`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime('-1 day',strtotime($checkout)))."'";		

		$sql_sales_Quote .= " AND `".TBL_SALES_QUOTE."`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		
		}
			//$cond .= " AND `dated` <= '".date('Y-m-d')."'";
			$cond .= " AND `lead_status` = '1' ";
			//if($RsoUserChecked=='' && $UserHotelAccesid==''){
			$cond .= " AND (assign_user_id IN (".$UserId.") )";


			//$cond .= " AND `dated` <= '".date('Y-m-d')."'";
			$condEnq .= " AND ".TBL_DAILY_ENQUERY_DETAILS.".`lead_status` = '1' ";
			//if($RsoUserChecked=='' && $UserHotelAccesid==''){
			$condEnq .= " AND (".TBL_DAILY_ENQUERY.".assign_user_id IN (".$UserId.") )";



			//}
			if($RsoUserChecked!='' && $RsoUserChecked=='2'){
		    if($UserHotelAccesid!=''){
		        	$sql_Followup_Details .= " AND `".TBL_FOLLOWUP_DETAILS."`.`hotel_id` IN (".$UserHotelAccesid.") ";
		        	$sql_Daily_Enqury .= " AND `".TBL_DAILY_ENQUERY."`.`hotel_id` IN (".$UserHotelAccesid.") ";
		        	$sql_sales_Quote .= " AND `".TBL_SALES_QUOTE."`.`hotel_id` IN (".$UserHotelAccesid.") ";
		        	$sql_sales_Incentive .= " AND `fs_incentive`.`hotel_id` IN (".$UserHotelAccesid.") ";
		    }
		    
		}
		}	
	
	$IncentiveSQL	=" SELECT max(id) as MaxId FROM fs_incentive_details group by fs_incentive_details.id_incentive";
		$resIncentive = mysqli_query($conn,$IncentiveSQL);
		if($resIncentive){
			$numRows = mysqli_num_rows($resIncentive);
			while($rowIncentive	=mysqli_fetch_object($resIncentive)){
				
				$rowIncentiveMaxId[]=$rowIncentive->MaxId;
				}
			$rowIncentiveMaxId=implode(',',$rowIncentiveMaxId);
		}
	$sql = "SELECT DISTINCT 4 AS display_order,`fs_incentive`.id,fs_incentive.current_status as lead_status,'Incentive' AS 'Source',`fs_incentive`.hotel_id,`fs_incentive_details`.id_user,`fs_incentive_details`.id_forward_for_approval as assign_user_id,`fs_incentive_details`.dated AS dated,`fs_incentive`.`date_created` AS date_created,`fs_incentive`.follow_up_close_summary AS summary,'' AS id_close_type,'' AS close_summary, '' AS id_company, '' AS visit_id 

FROM `fs_incentive` 
LEFT JOIN fs_incentive_details ON `fs_incentive`.id = fs_incentive_details.id_incentive 
WHERE `fs_incentive`.follow_up_close_summary !='' AND `fs_incentive`.`id_shop` = '6' AND (fs_incentive.`current_status` = '0' || fs_incentive.`current_status` = '1')  AND (`fs_incentive_details`.id_forward_for_approval IN (".$UserId.") ) AND (`fs_incentive_details`.id IN (".$rowIncentiveMaxId.") ) ".$sql_sales_Incentive." ".$sql_Daily_Incentive." 

UNION ALL";
		if($RsoUserChecked!='2'){	
		$sql .= " SELECT  3 AS display_order,id,lead_status,'Sales Report' AS 'Source',hotel_id,id_user,assign_user_id,dated,date_created,follow_up_summary AS summary,followup_close_type_id AS id_close_type,followup_close_summary AS close_summary, '' AS id_company,visit_id AS visit_id  FROM `".TBL_FOLLOWUP_DETAILS."`  WHERE follow_up_summary !='' AND   `".TBL_FOLLOWUP_DETAILS."`.`id_shop` = '".$shop_id."'  ".$cond.$sql_Followup_Details."
			
			UNION ALL";
            }
			$sql .= "	SELECT DISTINCT  2 AS display_order,`".TBL_DAILY_ENQUERY."`.id,".TBL_DAILY_ENQUERY_DETAILS.".lead_status,'Enquiry' AS 'Source',`".TBL_DAILY_ENQUERY."`.hotel_id,`".TBL_DAILY_ENQUERY."`.id_user,`".TBL_DAILY_ENQUERY."`.assign_user_id,`".TBL_DAILY_ENQUERY."`.follow_up_date AS dated,`".TBL_DAILY_ENQUERY."`.created_date AS date_created,`".TBL_DAILY_ENQUERY."`.follow_up_summary AS summary,'' AS id_close_type,'' AS close_summary, `".TBL_DAILY_ENQUERY."`.id_company AS id_company, '' AS visit_id  
			FROM `".TBL_DAILY_ENQUERY."` LEFT JOIN ".TBL_DAILY_ENQUERY_DETAILS."   ON `".TBL_DAILY_ENQUERY."`.id = ".TBL_DAILY_ENQUERY_DETAILS.".enquiry_id
			WHERE `".TBL_DAILY_ENQUERY."`.follow_up_summary !='' AND   `".TBL_DAILY_ENQUERY."`.`id_shop` = '".$shop_id."'
			 ".str_replace('dated', 'follow_up_date', $condEnq).$sql_Daily_Enqury."

			UNION ALL

			SELECT  1 AS display_order,id,lead_status,'Quote' AS 'Source',hotel_id,id_user,assign_user_id,follow_up_date As dated,created_date AS last_created,details AS summary,'' AS id_close_type,'' AS close_summary,id_company AS id_company,'' AS visit_id  FROM `".TBL_SALES_QUOTE."`  WHERE follow_up_summary !='' AND   `".TBL_SALES_QUOTE."`.`id_shop` = '".$shop_id."' ".str_replace('dated', 'follow_up_date',$cond).$sql_sales_Quote."
		";
			

		$sql .=  " ORDER BY display_order,dated DESC";
		//echo $sql ;	
		//print_r($_SESSION);	
		//echo $sql;die;
		$res = mysqli_query($conn,$sql);
		if($res){
			$numRows = mysqli_num_rows($res);
		}	
			

			

			




		if($numRows > 0){
			$counter = 1;
	

			

			$head_hotel_row = 8;
			$head_cntr_column = "A";$head_hotel_column = "A";
				$Content.='<table  class="table"  width="100%" style="border:1px solid black;background-color:#75923c;">
							<tr style="border:1px solid black;">';
		
		
					$Content.='<th style="border:1px solid black;">&nbsp;&nbsp;ID.&nbsp;&nbsp;</th>';
					
					$Content.='<th style="border:1px solid black;">Hotel</th>
					<th style="border:1px solid black;">Company</th>
					<th style="border:1px solid black;">Description</th>
					<th style="border:1px solid black;">Assigned By</th>
					<th style="border:1px solid black;">Assigned On</th>
					<th style="border:1px solid black;">Assigned To</th>
					<th style="border:1px solid black;">Follow up Date</th>
					<th style="border:1px solid black;">Follow Up Summary</th>
					
					<th style="border:1px solid black;">Source</th>
					<th style="border:1px solid black;">Status</th>
		
	</tr>
';			
			$Serialno=1;
while($row = mysqli_fetch_object($res)){
				//print_r($row);
				//die;
				$ExecutiveName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$shop_id." ");

				$ExecutiveAssignToName	=	selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->assign_user_id." and id_shop=".$shop_id." ");

				$head_order_data1 = "A";

				$head_order_data = "A";       

				
				if(!$row->id_company){
					
					}
				
			if($row->display_order==3){
				$id_close_type = $row->id_close_type;
				$closeSummary=$row->close_summary;
				$Company_id	=	selectColumn(TBL_VISIT,'id_company'," WHERE `id` =".$row->visit_id." and id_shop=".$shop_id." ");		
				$Description	=	selectColumn(TBL_VISIT,'discussion_summary'," WHERE `id` =".$row->visit_id." and id_shop=".$shop_id." ");	
					
				$sqlFollowUpExplode1 = "SELECT * FROM `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`  WHERE `visit_id` =".$row->visit_id." and id_shop=".$shop_id." ORDER BY id DESC";

			$resQue = mysqli_query($conn,$sqlFollowUpExplode1);
			 $numRows= mysqli_num_rows($resQue);
		
			$RowFollowUpExplode=mysqli_fetch_array($resQue);
			$summary	=	$RowFollowUpExplode['summary'];
			
				}
			else{
				$Company_id	=$row->id_company;
			}

				if($row->display_order==2){
					$id_close_type = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'followup_close_type_id','WHERE enquiry_id="'.$row->id.'" AND dated="'.$row->dated.'" ');
					$closeSummary=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary','WHERE enquiry_id="'.$row->id.'" AND dated="'.$row->dated.'" ');
				$Description	=	selectColumn(TBL_DAILY_ENQUERY,'details'," WHERE `id` =".$row->id." and id_shop=".$shop_id." ");		
			$summary	=	$row->summary;
			}else{
				//$Description='';
				}

				if($row->display_order==1){
					$id_close_type = selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'followup_close_type_id','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');

					$closeSummary=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'quote_close_summary','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');
					$Description	=	$row->summary;
						
					$summary	=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'details','WHERE id_quote="'.$row->id.'" AND dated="'.$row->dated.'" ');

				}
$lead_status =$row->lead_status;
	
$dated=	$row->dated;	
			if($row->display_order==4){
				
			$id_enquiry	=	selectColumn('fs_incentive','id_enquiry'," WHERE `id` =".$row->id." ");
	$Company_id	=	selectColumn(TBL_DAILY_ENQUERY,'id_company'," WHERE `id` =".$id_enquiry." ");
	
		$row->hotel_id	=	selectColumn(TBL_DAILY_ENQUERY,'hotel_id'," WHERE `id` =".$id_enquiry." ");
	$Description	=	selectColumn(TBL_DAILY_ENQUERY,'details'," WHERE `id` =".$id_enquiry." and id_shop=".$shop_id." ");	
				
	$max_id=selectColumn('fs_incentive_details','MAX(id)',"WHERE  id_incentive = '".$row->id."' ");			
	
	$summary	=selectColumn('fs_incentive_details','remarks','WHERE id="'.$max_id.'"');
				$dated=	selectColumn('fs_incentive_details','dated','WHERE id="'.$max_id.'"');
	$lead_status =1;			
}

				if($lead_status==1){
					$Content.='<tr style="border:1px solid black;background-color:#ffffff;">';
				//if($row->display_order==4){
				$Content.='<td style="border:1px solid black;">'.$row->id.'</td>';
				//}else{
					//$Content.='<td style="border:1px solid black;">'.$Serialno++.'</td>';
					//}
					$Content.='<td style="border:1px solid black;">'.selectColumn(TBL_HOTELS,'CONCAT(name,",",city)','WHERE id="'.$row->hotel_id.'" ').'</td>
					<td style="border:1px solid black;">'.selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$Company_id.'" ').'</td>
					<td style="border:1px solid black;">'.$Description.'</td>
					<td style="border:1px solid black;">'.ucwords($ExecutiveName).'</td>
					<td style="border:1px solid black;">'.date('d-M-Y',strtotime($row->date_created)).'</td>
					<td style="border:1px solid black;">'.ucwords($ExecutiveAssignToName).'</td>

					<td style="border:1px solid black;">'.date('d-M-Y',strtotime($dated)).'</td>
					<td style="border:1px solid black;">'.$summary.'</td>
				
					<td style="border:1px solid black;">'.$row->Source.'</td>
					

					<td style="border:1px solid black;">Open</td>
</tr>';
					
					$connew++;
				}

			}
			$Content.='</table>';
			return $Content;

			
		}
		
		
		
		
	


//echo $Content;die;

	}
	?>