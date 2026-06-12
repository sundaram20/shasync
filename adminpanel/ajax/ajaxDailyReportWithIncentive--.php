<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$hotelId = $_REQUEST['hotelId'];

//print_r($_REQUEST);
$Admin_user_id	=	$_REQUEST['Admin_user_id'];
$events = array();
$CompanyName= array();
$resState2 = executeSql("SELECT * from `".TBL_DAILY_LEAD_TYPE."` order by id asc");
if(num_rows($resState2) > 0){
		while($row22 = $db->fetch_assoc2($resState2)){
			
			
			$Table	=	$row22['table_name'];
			$type	=	$row22['id'];
		
			if($row22['id']	==3){
			//$Con	="AND followup_type='3'";
			$DatedLabel	=	'followup_date';
			$UseType	=	'AND type=3';
			$FileName	=	'addreport.php';
			}
			else if($row22['id']	==2){				
			$DatedLabel	=	'dated';
			$UseType	=	'AND type=2';
			$FileName	=	'addreport.php';
			
			}
			else if($row22['id']	==1){
				
			$DatedLabel	=	'dated';
			$UseType	=	'AND type!=2 AND enquiry_details=1';
			
			}
			else if($row22['id']	==4){
				
			$DatedLabel	=	'dated';
			$UseType	=	'AND type=4 and doc_id=0';
			$FileName	=	'editEnquiry.php';
			}
			else if($row22['id']	==7){
				
			$DatedLabel	=	'dated';
			$UseType	=	"AND type='7' AND status='1'";
			$FileName	=	'calls.php';
			}else if($row22['id']	==8){
			$DatedLabel	=	'dated';
			$UseType	=	"AND type='8' AND status='1'";
			$FileName	=	'ManagerDailyPickupItemWise.php';
			}
			else if($row22['id']	==5){
				
			$DatedLabel	=	'dated';
			$UseType	=	'AND type=5 and doc_id=0';
			$FileName	=	'editQuote.php';
			}
			else{
			$FileName	=	'addreport.php';
			}
			
			$UserLevel	= selectColumn(TBL_USERS,'user_level'," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($Admin_user_id)."' ");
			
			if($UserLevel =='1'){
    if($row22['id']	=='3'){
        $UserAssignId	= "  AND `id_user` = '".addslashes($Admin_user_id)."'";
    }
    if($row22['id']	=='1'){
        $UserAssignId	= "  AND `assign_user_id` = '".addslashes($Admin_user_id)."'";
    }
    if($row22['id']	=='2'){
        $UserAssignId	= "  AND `id_user` = '".addslashes($Admin_user_id)."'";
    }
    if($row22['id']	=='8'){
        $UserAssignId	= "  AND `assign_user_id` = '".addslashes($Admin_user_id)."'";
    }
}else{
    if($type==3){
        $UserAssignId	= "  AND `id_user` = '".addslashes($Admin_user_id)."'";
    }else{
        $UserAssignId	= "  AND `assign_user_id` = '".addslashes($Admin_user_id)."'";
    }
}
			
			
			
				
	 $resState = executeSql("SELECT * from `fs_daily_calender` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  $DatedLabel between '".addslashes($start)."' and '".addslashes($end)."'  $UseType  $UserAssignId group by $DatedLabel");
				
				

			if(num_rows($resState) > 0){
			while($row = $db->fetch_assoc2($resState)){
		if($Table	=='fs_visit'){ 
					$CompanyName[]	=	$row['id_company'];
							
				}else{
					$VisiteID	=encryptor('encrypt',$row['visit_id']);		
					}	
					
/*if($row['doc_id']==0 && $row['enquiry_details']==0){					
	$VisiteID	=$row['visit_id'];
}*/

	if($type ==1){	
$resSql 		= executeSql(
"select sum(TotalCount) as TotalCount from (
    
    (SELECT   count(distinct id) as TotalCount from `fs_follow_up_details`  where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."'  AND lead_status=1 $UserAssignId  group by dated )
union ALL
(SELECT distinct  count(distinct id) as TotalCount from `fs_feedback_details` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' AND lead_status=1 $UserAssignId  $UserAssignId group by dated)
union ALL
(SELECT distinct  count(distinct id) as TotalCount from `sales_quote_followup` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' AND lead_status=1 $UserAssignId  $UserAssignId group by dated)
union ALL
(SELECT distinct  count(distinct id) as TotalCount from `fs_enquiry_details` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' AND lead_status=1 $UserAssignId group by dated)

union ALL
(SELECT distinct  count(distinct id) as TotalCount from `fs_incentive_details` where status!='2'  and  dated ='".addslashes($row['dated'])."'  AND `id_forward_for_approval` = '".addslashes($Admin_user_id)."'  group by dated)

union ALL
(SELECT distinct  count(distinct id) as TotalCount from `call_details` where call_status!='0'  and  followup_date ='".addslashes($row['dated'])."'  AND `assign_user_id` = '".addslashes($Admin_user_id)."'  group by followup_date)

union ALL
(SELECT distinct  count(distinct id) as TotalCount from `support_details` where support_status!='0' and id_daily_pickup > 0  and  followup_date ='".addslashes($row['dated'])."'  AND `assign_user_id` = '".addslashes($Admin_user_id)."'  group by followup_date)



    )
    test"

);
	}if($type ==2){
$resSql 		= executeSql("SELECT count(*) as TotalCount from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' group by dated ");
	}
	
	if($type ==3){
$resSql 		= executeSql("SELECT count(*) as TotalCount from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  created_date ='".addslashes($row[$DatedLabel])."' $UserAssignId group by created_date ");
	}
	
	if($type ==4){
$resSql 		= executeSql("SELECT count(*) as TotalCount from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' $UserAssignId  group by dated ");
	}
	if($type ==5){
$resSql 		= executeSql("SELECT count(*) as TotalCount from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."' AND `id_assign_user` = '".addslashes($Admin_user_id)."'   group by dated ");
	}

if($type ==7){
$resSql 		= executeSql("SELECT count(*) as TotalCount from `$Table` where call_status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  followup_date ='".addslashes($row['dated'])."' and   `assign_user_id` = '".addslashes($Admin_user_id)."' group by followup_date ");
	}
				
				if($type ==8){
    $resSql = executeSql("SELECT count(*) as TotalCount FROM `support_details` 
        WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' 
        AND `followup_date` = '".addslashes($row['dated'])."' 
        AND `assign_user_id` = '".addslashes($Admin_user_id)."'
		AND `id_daily_pickup` > '0'
		AND `support_status` = '1'
        GROUP BY `followup_date`");
}

$TotalCountrow 	= $db->fetch_assoc2($resSql);

		if($type ==1){	
		$IncentiveSQL	=" SELECT max(id) as MaxId FROM fs_enquiry_details  group by fs_enquiry_details.enquiry_id";
		$resIncentive = mysqli_query($connNew,$IncentiveSQL);
		if($resIncentive){
			$numRows = mysqli_num_rows($resIncentive);$rowIncentiveMaxId=array();
			while($rowIncentive2	=mysqli_fetch_object($resIncentive)){
				$statusInc	= selectColumn('fs_enquiry_details','lead_status'," WHERE `id` = '".$rowIncentive2->MaxId."'");
				if( $statusInc=='1'){
					$rowMaxId[]=$rowIncentive2->MaxId;
				}
				}
			$rowImpMaxId=implode(',',$rowMaxId);
		}
		$resSql123 = executeSql("
		(SELECT id,type,dated,lead_status,visit_id,hotel_id,assign_user_id, 'fs_follow_up_details' as `TableName` from `fs_follow_up_details` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and lead_status=1 and  dated='".addslashes($row['dated'])."' $UserAssignId )
		UNION ALL
		(SELECT id,type,dated,lead_status,visit_id,hotel_id,assign_user_id, 'fs_feedback_details' as `TableName` from `fs_feedback_details` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and lead_status=1 and  dated='".addslashes($row['dated'])."' $UserAssignId )
		UNION ALL
		(SELECT id,type,dated,lead_status,id_quote,hotel_id,assign_user_id, 'sales_quote_followup' as `TableName` from `sales_quote_followup` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  lead_status=1 and dated='".addslashes($row['dated'])."' $UserAssignId )
		UNION ALL
		(SELECT id,type,dated,lead_status,enquiry_id,hotel_id,assign_user_id, 'fs_enquiry_details' as `TableName` from `fs_enquiry_details` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  lead_status=1 and dated='".addslashes($row['dated'])."' $UserAssignId  AND (fs_enquiry_details.id IN (".$rowImpMaxId.") ))" );
		
		$VisiteID	='visit_id';	
				
		}
		
		if($type ==3){
						
		$resSql123 		= executeSql("SELECT * from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  created_date ='".addslashes($row[$DatedLabel])."' AND lead_status=1 $UserAssignId ");
	
		$VisiteID	='visit_id';
	
		}	
	
		if($type ==4){
			
		$resSql123 = executeSql("SELECT * from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and   dated='".addslashes($row['dated'])."'  $UserAssignId ");
		
		$VisiteID	='id';	
		
		}
		if($type ==5){
			
		$resSql123 = executeSql("SELECT * from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and   dated='".addslashes($row['dated'])."'  $UserAssignId ");
		
		$VisiteID	='id';	
		
		}
		
		if($type ==2){
		$resSql123 = executeSql("SELECT * from `$Table` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and   dated='".addslashes($row['dated'])."'  AND `id_user` = '".addslashes($Admin_user_id)."'  ");
		
		$VisiteID	='id';	
		
		}
		if($type ==7){
    $resSql123 = executeSql("SELECT * FROM `$Table` where call_status!='0'  
        and  followup_date ='".addslashes($row['dated'])."'    
        AND `assign_user_id` = '".addslashes($Admin_user_id)."'");
    $IncentiveActive='1';
}else if($type ==8){

			
	 $visit_ids_sql = "SELECT visit_id FROM `fs_daily_calender` WHERE dated = '".addslashes($row['dated'])."' AND type='8' AND visit_id > 0";
	$visit_ids_result = executeSql($visit_ids_sql);
			
			$visit_ids_array = [];
			while ($visit_row = mysqli_fetch_assoc($visit_ids_result)) {
				$visit_ids_array[] = intval($visit_row['visit_id']); // ensure numeric values for safety
			}
			
			$visit_ids_list = implode(',', $visit_ids_array);
			
			$resSql123 = executeSql("SELECT * FROM `$Table` 
			where id_daily_pickup > 0
			AND id_daily_pickup IN ($visit_ids_list)
        AND  followup_date ='".addslashes($row['dated'])."'    
        AND `assign_user_id` = '".addslashes($Admin_user_id)."'
		AND `id_shop` = '".addslashes($_SESSION['shop'])."'
		AND id IN (
		SELECT MAX(id)
          FROM `$Table`
          WHERE id_daily_pickup > 0
		 AND id_daily_pickup IN ($visit_ids_list)
            AND followup_date = '".addslashes($row['dated'])."'
            AND assign_user_id = '".addslashes($Admin_user_id)."'
            AND id_shop = '".addslashes($_SESSION['shop'])."'
          GROUP BY id_daily_pickup
		)");
			
    $IncentiveActive='';
}else{
    $IncentiveActive='';
}	
	
		$k='<div class="box-body table-responsive" style="height:200px; overflow-x:scroll !important; overflow: scroll;;">
              <table id="example2" class="table table-bordered table-striped" >
                <thead>				
                <tr>';
				if($row22['id']	==1){
                  $k.='<th>Source</th>
				  <th>Hotel Name</th>
				  <th>Company</th>
				  <th>Assign To</th><th>Contact Person</th><th>Lead Source</th>';
				  $FileName	='addreport.php';
				}
				 if($row22['id']	==3){
                  $k.='
				  <th>Hotel Name</th>
				  <th>Company</th>
				 ';
				 $FileName	='addreport.php';
				}                  
				if($row22['id']	==2){
                  $k.='<th>Company Name</th>';
				  $k.='<th>Person Met</th>';
				  $FileName	='addreport.php';
				}	
				if($row22['id']	==4){
                  $k.='<th>Enquiry</th>';
				  $k.='<th>Hotel Name</th>';
				  $FileName	='editEnquiry.php';
				}
				if($row22['id']	==7){
                  $k.='<th>Source</th>';
					$k.= '<th>Call Type</th>';
					$k.='<th>Serial</th>';
				  $k.='<th>Customer Name</th>';
					$k.='<th>Remark</th>';
				  $FileName	='editEnquiry.php';
				}
				if($row22['id']	==8){
					$k.='<th>Bill No</th>';
					$k.='<th>Company</th>';
					$k.='<th>Contact</th>';
					$k.='<th>Last Remark</th>';
					$k.='<th>status</th>';
					$FileName	='ManagerDailyPickupItemWise.php';
				}
				if($row22['id']	==5){
                  $k.='<th>Company Name</th>';
				  $k.='<th>Hotel Name</th>';
				  $FileName	='editQuote.php';
				}
					

			 
               $k.='</tr>
                </thead>
                <tbody>';
	while($row123 = $db->fetch_assoc2($resSql123)){	
		//echo $type;
		//print_r($row123);
		
	if($row123['lead_status'] == 1){
		$StatusEs		=	'btn-success';
		$ActiveINactive	=	"Open";
		$row123['lead_status']	=	1;		
	}if($row123['lead_status'] == 0){
		$StatusEs				=	'btn-danger';
		$ActiveINactive			=	"Close";
		$NextFollowUpDisable	= 	"disabled";  
		$row123['lead_status']	=	0;
	}
  
	if($type == '2'){
		$id_company	=  $row123['id_company'];
		
	}
	else if($type == '3'){
		
	}
	else if($type == '1'){	
	
	if($row123['TableName']=='fs_enquiry_details'){
		$id_company	= selectColumn(TBL_DAILY_ENQUERY,'id_company'," WHERE `id` = '".$row123['visit_id']."'");
		}else{
		$id_company	= selectColumn(TBL_VISIT,'id_company'," WHERE `id` = '".$row123['visit_id']."'");
		}
	}
	else if($type == '4'){
		
		$id_company	= selectColumn(TBL_DAILY_ENQUERY,'id_company'," WHERE `id` = '".$row123['visit_id']."'");
		$withOutComTxt = 'Enquiry';
	
		
	}
	else if($type == '5'){
		
		$id_company	= selectColumn(TBL_SALES_QUOTE,'id_company'," WHERE `id` = '".$row123['visit_id']."' ");	
		$withOutComTxt = 'Direct Guest';
		
	}else if($type == '7'){
		$id_enquiry	= $row123['call_id'];
		$id_company	= 'afsal';
		$hotel_id	= selectColumn(TBL_DAILY_ENQUERY,'hotel_id'," WHERE `id` = '".$id_enquiry."' ");
		$row123[$VisiteID]=	$id_enquiry;
		$row123['hotel_id']=$hotel_id;
		//$withOutComTxt = 'Direct Guest';
		
	}
	
		
		
		
	
					
	
	
  
  $k.='<tr>';
    
if($row22['id']	==1){
	// set followup file name
	if($row123['type']==4){
		$FileName='editEnquiry.php';
		
		
	}
	elseif($row123['type']==5){
		$FileName='editQuote.php';
		$id_company	= selectColumn(TBL_SALES_QUOTE,'id_company'," WHERE `id` = '".$row123['visit_id']."' ");
		
	}
	else{
		$FileName='addreport.php';
	}

	$k .= '<td><a href="'.$FileName.'?eId='.encryptor('encrypt',$row123[$VisiteID]).'&action=edit&page=1" >'.selectColumn(TBL_DAILY_LEAD_TYPE,'name'," WHERE `id` = '".$row123['type']."'").'</a></td>';
	
}
if($row22['id']	==7){
	// set followup file name
	//print_r($row123);
		$FileName='calls.php';
	$format_req = selectColumn('`call_details`','format_type',"WHERE id = '".$row123['id']."' and call_status!='0' ORDER BY id DESC");
	$list_id = selectColumn('`call_details`','id_list_name',"WHERE id = '".$row123['id']."' and call_status!='0' ORDER BY id DESC");
	//$k .= '<td><a href="'.$FileName.'" >'.selectColumn(TBL_DAILY_LEAD_TYPE,'name'," WHERE `id` = '7'").'</a></td>';
	
$k .= '<td><a href="calls.php?format_req='.$format_req.'&call_type='.$list_id.'&view_id=' . $row123['call_id'] . '">View</a></td>';

	
}
		if($row22['id'] == 8){
    $first_name = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".
        selectColumn('daily_pickup','id_contacts'," WHERE `id` = '".$row123['id_daily_pickup']."'")."'");
    $last_name  = selectColumn(TBL_CUSTOMER,'last_name'," WHERE `id_customer` = '".
        selectColumn('daily_pickup','id_contacts'," WHERE `id` = '".$row123['id_daily_pickup']."'")."'");
    $contact    = trim($first_name.' '.$last_name);
    $company    = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row123['id_company']."'");
			$bill_no = selectColumn('daily_pickup','bill_no'," WHERE `id` = '".$row123['id_daily_pickup']."'");
			/*if($row123['support_status']=='0'){ 
				$support_statuss = 'closed' 
				}else{
				$support_statuss = 'open'
				} */

    $k .= '<td><a href="ManagerDailyPickupItemwise.php?search_name='.urlencode($bill_no).'&searchFormSubmit=1">'.
        $bill_no.'</a></td>';
    $k .= '<td>'.$company.'</td>';
    $k .= '<td>'.$contact.'</td>';
    $k .= '<td>'.$row123['support_remark'].'</td>';
		$k .= '<td>'.($row123['support_status'] == 0 ? 'Close' : 'Open').'</td>';
}
if($row22['id']	==1 || $row22['id']	==3 ){	

	$k .= '<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row123['hotel_id']."'").' - '.selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$row123['hotel_id']."'").'</td>';  
	
  
}
		
		if($row22['id']==7){
			$k .= '<td>'.selectColumn('`call_list_name`','name'," WHERE `id` = '".$row123['id_list_name']."'").'</td>';
		$k .= '<td>'.selectColumn('`call_details`','JSON_UNQUOTE(JSON_EXTRACT(extra_data, "$.serial"))'," WHERE `call_id` = '".$row123['call_id']."' and call_status!='0' ORDER BY id DESC").'</td>'; 
			
			//$list_id = selectColumn('`call_details`','id_list_name',"WHERE id = '".$row123['id']."'");
			
			$k .= '<td>'.selectColumn('`call`','name'," WHERE `id` = '".$row123['call_id']."'").'</td>';
			
			$k .= '<td>'.selectColumn('`call_details`','call_remark'," WHERE `id` = '".$row123['id']."' and call_status!='0' ORDER BY id DESC").'</td>';
		}

$comTxt=(selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$id_company."' AND id_shop='".$_SESSION['shop']."' ")!=''?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$id_company."' AND id_shop='".$_SESSION['shop']."' "):$withOutComTxt);

$k .= '<td ><a href="'.$FileName.'?eId='.encryptor('encrypt',$row123[$VisiteID]).'&action=edit&page=1" >'.$comTxt.'</a>
<input type="hidden" name="followup_id" id="followup_id" value="'.$row123['id'].'">
<input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="'.$row123['visit_id'].'">
<input type="hidden" name="hotel_id" id="hotel_id" value="'.$row123['hotel_id'].'">
<input type="hidden" name="followup_status" id="followup_status" value="'.$row123['followup_status'].'">
</td>'; 

if($row22['id']	==4 ){	
	$k .= '<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row123['hotel_id']."'").'</td>';     
}
if($row22['id']	==5 ){	
	$k .= '<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row123['hotel_id']."'").'</td>';     
}
if($row22['id']	==2){
	$first_name	= selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row123['id_contacts']."'");
	$last_name	= selectColumn(TBL_CUSTOMER,'last_name'," WHERE `id_customer` = '".$row123['id_contacts']."'");
	$name 	=	$first_name.' '.$last_name;
$k .= '<td>'.$name.'</td>';  	
	
}

if($row22['id']	==1){
	
/*$k .= '<td id="ChangeButton_'.$row123['id'].'"><button data="'.$row123['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$row123['lead_status'].','.$row123['id'].','.$row123['visit_id'].','.$row123['hotel_id'].','.$row123['type'].');"    >Action</button>
</td>'; */
$k	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$row123['assign_user_id']."'").'</td>'; 
	
	$enquiry_id = $row123['TableName'] == 'fs_enquiry_details' ? $row123['visit_id'] : $row123['enquiry_id'];
    $id_contact = selectColumn('fs_enquiry_details', 'id_contact', " WHERE `enquiry_id` = '".addslashes($enquiry_id)."'");
	
	if (empty($id_contact)) {
        $id_contact = selectColumn('fs_enquiry_details', 'id_contact', " WHERE `enquiry_id` = '".addslashes($enquiry_id)."'");
    }
	
	$first_name = selectColumn('fs_customer', 'first_name', " WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_customer` = '".addslashes($id_contact)."'");
    $last_name = selectColumn('fs_customer', 'last_name', " WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_customer` = '".addslashes($id_contact)."'");
    $mobile = selectColumn('fs_customer', 'mobile', " WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_customer` = '".addslashes($id_contact)."'");
    $customer_name = trim($first_name . ' ' . $last_name);
    $customer_details = $customer_name ? $customer_name . ' (' . $mobile . ')' : 'N/A';
	
	$k .= '<td>' .$customer_details. '</td>';
	

    $id_mst_lead_source = selectColumn('fs_enquiry', 'id_mst_lead_source', " WHERE `id` = '".addslashes($enquiry_id)."'");
    $k .= '<td>'.selectColumn('mst_lead_source', 'name', " WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($id_mst_lead_source)."'").'</td>';
	
	//print_r($row123);
}
     
}	               
			    $k .='</tr>
				</tbody>                
              </table>			  
            </div>';

				
if(num_rows($resSql123)>0){
			$e = array();
			
			$e['id'] = $row22['id'];
			$e['title'] = $row22['name'].' | '.num_rows($resSql123);
					
			$e['description']=$k;

			$e['room_id'] = $row22['id'];
			$e['CompanyName']=$CompanyName;
			
			$e['start'] = $row[$DatedLabel];
			$e['end'] = $row[$DatedLabel];
			
			
			if( $row22['color']!=''){
			$e['backgroundColor'] = $row22['color'];
			$e['borderColor'] = $row22['color'];
			}else{
			$e['backgroundColor'] = '#00a65a';
			$e['borderColor'] = '#00a65a';
			}
			$e['allDay'] = true;
			
			array_push($events, $e);
			}
			
			}
}
		}
	}
echo json_encode($events);
?>