<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];
$FollowupRemove = $_REQUEST['FollowupRemove'];

 

if(($remove == 'removeOne') && ($uniqueCode!='') ){

	unset($_SESSION['feedback_hotel_id'][$uniqueCode]);
	unset($_SESSION['feedback_description'][$uniqueCode]);
	unset($_SESSION['feedback_date'][$uniqueCode]);
	unset($_SESSION['feedback_date_created'][$uniqueCode]);
	unset($_SESSION['assign_feedback_user_id'][$uniqueCode]);
	unset($_SESSION['lead_status'][$uniqueCode]);


	
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
                   <th>FeedBack Summary</th>
                   <th> Created Date</th>
                    <th> FeedBack Date</th>
                   <th>Assign To</th>
                    <th>Status</th>					
                    <th>Action</th>
					
                    
                  </tr>
                </thead>
                <tbody>';
				$Expand=0;
	foreach($_SESSION['feedback_hotel_id'] as $Followuphotel => $k){
		
	$Expand++;
	
	$StatusEs	=	'btn-success';
		$ActiveINactive	=	"Open";
	$DateVisitList	.='<tr style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;   ">';
			   
			  // $DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['feedback_hotel_id'][$Followuphotel]."'").'</td>';
			  
	$DateVisitList	.='<td>';
$DateVisitList	.='  <i class="fa fa-plus-square"> &nbsp;<a href="javascript://" onClick="showFeedBack('.$Expand.','.$CountFeedBack.')" style="color:#fff;">';
$DateVisitList	.='<input type="hidden" id="FeedBack_'.$Expand.'_img"  border="0">';
 
 
 $DateVisitList	.=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['feedback_hotel_id'][$Followuphotel]."'").'</a></td>';
			  
				   
			   $DateVisitList	.='<td>'.$_SESSION['feedback_description'][$Followuphotel].'</td>';
			   
			   
$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['feedback_date_created'][$Followuphotel])).'</td>';

$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['feedback_date'][$Followuphotel])).'</td>';
 


 $DateVisitList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_feedback_user_id'][$Followuphotel]."'").'</td>';
 
 
  $DateVisitList	.='<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['visit_id'].','.$_SESSION['feedback_hotel_id'][$Followuphotel].','.$FollowupSqlRow['type'].');"    >Action</button>
</td>';




$DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$Followuphotel.'" onclick="ajaxFeedBAckRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';
		
		$DateVisitList	.='<tr><td colspan="9" style="padding-bottom:0px;padding-top:0px;"> ';                 
$DateVisitList	.='<div id="div'.$Expand.'"></div>                 
                  
                  <div id="FeedBack_'.$Expand.'" style="display:none;">				  
				  
                <table id="example2" class="table table-bordered table-striped">
                  <tr style="background-color:#3C8DBC; color:#fff;">
                    <th>S.No</th>                  
                    <th>Date</th>
                     <th>FeedBack Summary</th>
                                       
                  </tr>';
 
 $NextFeedBAckUpSql = executeSql("SELECT  `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.*  FROM `".TBL_FEEDBACK_DETAILS_EXPLOAD."`  WHERE `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$_SESSION['feedback_Explode_id'][$Followuphotel]."' AND `visit_id` = '".$_SESSION['feedback_Explode_visit_id'][$Followuphotel]."'  ");
		
		if(num_rows($NextFeedBAckUpSql) > 0){
		$FeedBackupNext=1;
		while($NextFeedBackRow = $db->fetch_assoc2($NextFeedBAckUpSql)){
			$feedBackExplodeDated	=	$NextFeedBackRow['dated'];
			
		$_SESSION['feedback_Explode_Description'][$FeedBackuniqueCode][$feedBackExplodeDated]	=	$NextFeedBackRow['summary'];	
		
			
		
				$DateVisitList	.='<tr >
                    <th>'.$FeedBackupNext++.'</th>                  
                    <th>'.$NextFeedBackRow['dated'].'</th>
                     <th>'.$NextFeedBackRow['summary'].'</th>
                                        
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