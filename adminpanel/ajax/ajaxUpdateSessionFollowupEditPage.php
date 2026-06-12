<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];
$FollowupRemove = $_REQUEST['FollowupRemove'];

 if(($remove == 'removeOne') && ($uniqueCode!='')  ){

	unset($_SESSION['followup_hotel_id'][$uniqueCode]);
	unset($_SESSION['followup_description'][$uniqueCode]);
	unset($_SESSION['followup_date'][$uniqueCode]);
	unset($_SESSION['followupstatus'][$uniqueCode]);

	
	$DateVisitList	='
<div class="box">
          
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
        	
			<div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr style="background-color:#3C8DBC; color:#fff;">
                                   
                    <th>Hotel Name</th>					
                     <th>Follow Up Summary</th>
                     <th>Created  Date</th>
                    <th>Follow Up Date</th>
                    <th>Assign To</th>
                    <th>Status</th>
                    <th>Action</th>
					
                    
                  </tr>
                </thead>
                <tbody>';
		$FollowupExpand=0;		
	foreach($_SESSION['followup_hotel_id'] as $Followuphotel => $k){
	$FollowupExpand++;
	if($_SESSION['followupstatus'][$Followuphotel] == 1){
		$StatusEs	=	'btn-success';
		$ActiveINactive	=	"Open";
		
		}if($_SESSION['followupstatus'][$Followuphotel] == 0){
		$StatusEs	=	   'btn-danger';
		$ActiveINactive	=	"Close";
		$NextFollowUpDisable	= "disabled";  
		}
		
		
	$DateVisitList	.='<tr style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;   ">';
			   
	//$DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['followup_hotel_id'][$Followuphotel]."'").'</td>';
			  
	$DateVisitList	.='<td >';
$DateVisitList	.='<a href="javascript://" onClick="showContent('.$FollowupExpand.','.$CountFollowup.')" style="color:#fff;"><i class="fa  fa-plus-square">&nbsp;';
$DateVisitList	.='</i>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['followup_hotel_id'][$Followuphotel]."'").'
<input type="hidden" id="section_'.$FollowupExpand.'_img"  border="0">';

$DateVisitList	.='</td>';


		   
			   $DateVisitList	.='<td>'.$_SESSION['followup_description'][$Followuphotel].'</td>';
			   
$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date'][$Followuphotel])).'</td>';

 $DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date_created'][$Followuphotel])).'</td>';

 $DateVisitList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_followup_user_id'][$Followuphotel]."'").'</td>';
 
 
  $DateVisitList	.='<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['visit_id'].','.$_SESSION['feedback_hotel_id'][$Followuphotel].','.$FollowupSqlRow['type'].');"    >Action</button>
  </td>';
 //$DateVisitList	.='<td>'.$_SESSION['followupstatus'][$Followuphotel].'</td>';


		  $DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$Followuphotel.'" onclick="ajaxFollowupRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';
		
		$DateVisitList	.='<tr><td colspan="9" style="padding-bottom:0px;padding-top:0px;"> ';                 
$DateVisitList	.='<div id="div'.$FollowupExpand.'"></div>                 
                  
                  <div id="section_'.$FollowupExpand.'" style="display:none;">				  
				  
                <table id="example2" class="table table-bordered table-striped">
                  <tr style="background-color:#3C8DBC; color:#fff;">
                    <th>S.No</th>                  
                    <th>Date</th>
                     <th>Follow Summary</th>
                                       
                  </tr>';
 
 $NextFollowUpSql = executeSql("SELECT  `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.*  FROM `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`  WHERE `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$_SESSION['followup_Explode_id'][$Followuphotel]."' AND `visit_id` = '".$_SESSION['followup_Explode_visit_id'][$Followuphotel]."'  ");
		
		if(num_rows($NextFollowUpSql) > 0){
		$FollwupNext=1;
		while($NextFollowRow = $db->fetch_assoc2($NextFollowUpSql)){
			$FollowExplodeDated	=	$NextFollowRow['dated'];
			
		$_SESSION['followup_Explode_Description'][$OtherChargesuniqueCode][$FollowExplodeDated]	=	$NextFollowRow['summary'];	
		
			
		
				$DateVisitList	.='<tr >
                    <th>'.$FollwupNext++.'</th>                  
                    <th>'.$NextFollowRow['dated'].'</th>
                     <th>'.$NextFollowRow['summary'].'</th>
                                        
                  </tr>';
				  
				  
					}
			
			}
  $DateVisitList	.='</table>
               
				  
				  
				  </div>';
				  
				  
				  $DateVisitList	.='</tr>';
	
   
			   }
			   
	
	echo $DateVisitList;
			
			
	
}


?>