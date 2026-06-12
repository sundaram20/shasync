<?php  include_once("../../config/auto_loader.php");

$SelectedDate	= date('Y-m-d',strtotime($_REQUEST['reservation_date']));

//echo "<pre>";
//print_r($_SESSION);
//print_r($_REQUEST);
echo "</pre>";
//die;
if($_REQUEST['FollowupCoditionType']=='addfollowup'){
	
	
	 $followupCode	=	$_REQUEST['followupCode'];
	
	foreach( $followupCode as $FupCode){
		
		$_SESSION['followup_hotel_id'][$FupCode]					=	$_REQUEST['followup_hotel_id'][$FupCode];
		$_SESSION['followup_description'][$FupCode]					=	$_REQUEST['followup_description'][$FupCode];
		$_SESSION['followup_date'][$FupCode]						=	$_REQUEST['followup_date'][$FupCode];
		
		$_SESSION['followupstatus'][$FupCode]						=	$_REQUEST['followupstatus'][$FupCode];
		$FolloupNewDate												=	$_REQUEST['followup_date'][$FupCode];
		//$_SESSION['feedback_Explode_Date'][$FupCode][$NewDate]			=	$_REQUEST['feedback_date'][$FupCode];
		 $_SESSION['assign_followup_user_id'][$FupCode]				=	$_REQUEST['assign_followup_user_id'][$FupCode];
		$_SESSION['followup_date_created'][$FupCode]				=	$_REQUEST['followup_date_created'][$FupCode];
		
		$_SESSION['followup_Explode_Description'][$FupCode][$FolloupNewDate]	=	$_REQUEST['followup_description'][$FupCode];
	
		
		
	}
	
	$DateVisitList	='
<div class="box" id="removeForNew2">
          
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
        	
			<div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr style="background-color:#3C8DBC; color:#fff;">
                                   
                  <th>Hotel Name</th>					
                     <th>Follow Up Summary</th>
                     <!--<th>Created  Date</th>-->
                    <th>Follow Up Date</th>
                    <th>Assign To</th>
                    <!--<th>Status</th>-->
                    <th>Action</th>
					
                    
                  </tr>
                </thead>
                <tbody>';
				$FollowupExpand = 0;
				
	foreach($_SESSION['followup_hotel_id'] as $Followuphotel => $k){
		
		
		if($_SESSION['followupstatus'][$Followuphotel] == 1){
		$StatusEs	=	'btn-success';
		$ActiveINactive	=	"Open";
		
		}if($_SESSION['followupstatus'][$Followuphotel] == 0){
		$StatusEs	=	   'btn-danger';
		$ActiveINactive	=	"Close";
		$NextFollowUpDisable	= "disabled";  
		}
		
		
	
	$FollowupExpand++;
	$DateVisitList	.='<tr style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;">';
			   
			   //$DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['followup_hotel_id'][$Followuphotel]."'").'</td>';
			  
$DateVisitList	.='<td >';
$DateVisitList	.='<a href="javascript://" onClick="showContent('.$FollowupExpand.','.$CountFollowup.')" style="color:#fff;"><i class="fa  fa-plus-square">&nbsp;';
$DateVisitList	.='</i>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['followup_hotel_id'][$Followuphotel]."'").'
<input type="hidden" id="section_'.$FollowupExpand.'_img"  border="0">';

$DateVisitList	.='</td>';

	
	   
			   $DateVisitList	.='<td>'.$_SESSION['followup_description'][$Followuphotel].'</td>';
			   
			  //$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date_created'][$Followuphotel])).'</td>';
			   
$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date'][$Followuphotel])).'</td>';

$DateVisitList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_followup_user_id'][$Followuphotel]."'").'</td>';
 

 
 
 /*$DateVisitList	.='<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['visit_id'].','.$_SESSION['followup_hotel_id'][$Followuphotel].','.$FollowupSqlRow['type'].');"    >Action</button>
</td>';*/


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
	
} else if($_REQUEST['FollowupCoditionType']=='addfeedback'){ //FEED BACK Start


	$followupCode	=	$_REQUEST['feedbackCode'];

	foreach( $followupCode as $FupCode){
	
	$_SESSION['feedback_hotel_id'][$FupCode]			=	$_REQUEST['feedback_hotel_id'][$FupCode];
	$_SESSION['feedback_description'][$FupCode]			=	$_REQUEST['feedback_description'][$FupCode];
	$_SESSION['feedback_date_created'][$FupCode]		=	$_REQUEST['feedback_date_created'][$FupCode];
	
	$_SESSION['assign_feedback_user_id'][$FupCode]		=	$_REQUEST['assign_feedback_user_id'][$FupCode];
	$_SESSION['feedbackstatus'][$FupCode]				=	$_REQUEST['feedbackstatus'][$FupCode];
	
	$NewDate											=	$_REQUEST['feedback_date'][$FupCode];
	$_SESSION['feedback_date'][$FupCode]											=	$_REQUEST['feedback_date'][$FupCode];
	
	
	//$_SESSION['feedback_Explode_Date'][$FupCode][$NewDate]			=	$_REQUEST['feedback_date'][$FupCode];
	$_SESSION['feedback_Explode_Description'][$FupCode][$NewDate]	=	$_REQUEST['feedback_description'][$FupCode];
	
	$_SESSION['conclusion_type'][$FupCode] =$_REQUEST['conclusion_type'][$FupCode];
		
	}
	
	$DateVisitList	='
<div class="box" id="removeForNew">
          
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
        	
			<div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                                   
                   <th>Hotel Name</th>					
                   <th>FeedBack Summary</th>
                   <!--<th> Created Date</th>-->
                    <th> FeedBack Date</th>
                   <th>Assign To</th>
                   <!--<th>Status</th>-->					
                    <th>Action</th>
					
                    
                  </tr>
                </thead>
                <tbody>';
				$Expand = 0;
				
	foreach($_SESSION['feedback_hotel_id'] as $Followuphotel => $k){
		
	
	// if($_SESSION['feedbackstatus'][$Followuphotel] == 1){
		$StatusEs	=	'btn-success';
		$ActiveINactive	=	"Open";
		
		/*}if($_SESSION['feedbackstatus'][$Followuphotel] == 0){
		$StatusEs	=	   'btn-danger';
		$ActiveINactive	=	"Close";
		$NextFollowUpDisable	= "disabled";  
		}*/
		
		$Expand++;
	
	//$DateVisitList	.='<tr>';
			   
//$DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['feedback_hotel_id'][$Followuphotel]."'").'</td>';

$DateVisitList	.='<tr  style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;   ">';
		
$DateVisitList	.='<td>';
$DateVisitList	.='  <i class="fa fa-plus-square"> &nbsp;<a href="javascript://" onClick="showFeedBack('.$Expand.','.$CountFeedBack.')" style="color:#fff;">';
$DateVisitList	.='<input type="hidden" id="FeedBack_'.$Expand.'_img"  border="0">';
 
 
 $DateVisitList	.=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['feedback_hotel_id'][$Followuphotel]."'").'</a></td>';
			  
			   
			   $DateVisitList	.='<td>'.$_SESSION['feedback_description'][$Followuphotel].'</td>';
//$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['feedback_date_created'][$Followuphotel])).'</td>';
$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['feedback_date'][$Followuphotel])).'</td>';			   


 $DateVisitList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_feedback_user_id'][$Followuphotel]."'").'</td>';
 
 
  /*$DateVisitList	.='<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['visit_id'].','.$_SESSION['feedback_hotel_id'][$Followuphotel].','.$FollowupSqlRow['type'].');"    >Action</button>
</td>';*/

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
	
	//echo "<pre>";
	//print_r($_SESSION);
	//echo "</pre>";
	//FEED BACK END
	}else{
$DateVisitList	='
<div class="box" id="removeForNew2">
          <div class="box-header">
            <h3 class="box-title">Follow Up List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
        	
			<div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>S.No</th>                  
                    <th>Hotel Name</th>
					 <th>Company Name</th>
                     <th>Follow Up Close Summary</th>
                    <th>Status</th>
                    <th>Action</th>
					<th>List</th>
                    
                  </tr>
                </thead>
                <tbody>';
               
							 				
$sql = " SELECT  `".TBL_FOLLOWUP_DETAILS."`.*  FROM `".TBL_FOLLOWUP_DETAILS."` WHERE `".TBL_FOLLOWUP_DETAILS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `dated` = '".date('Y-m-d',strtotime($SelectedDate))."'  ";

$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();

if($total > 0){$counter = 1;
				
				$Expand = 0;
				  while($row = $db->fetch_object()){
					  
					
					  $Expand++;	  
	if($row->lead_status == 1){
   $StatusEs	=	'btn-success';
   $ActiveINactive	=	"Open";
   
  }if($row->lead_status == 0){
   $StatusEs	=	   'btn-danger';
   $ActiveINactive	=	"Close";
    $NextFollowUpDisable	= "disabled";  
  }
  
  
  $id_company	= selectColumn(TBL_VISIT,'id_company'," WHERE `id` = '".$row->visit_id ."'");
  $id_contacts	= selectColumn(TBL_VISIT,'id_contacts'," WHERE `id` = '".$row->visit_id ."'");
  
  
			   $DateVisitList	.='<tr>';
			   $DateVisitList	.='<td>'.$counter++.'</td>';
			   $DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'").'</td>';
			   $DateVisitList	.='<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$id_company."'").'</td>';
			   
			   $DateVisitList	.='<td id="ChangeFollowUpSummary_'.$row->id.'">'.$row->followup_close_summary.'</td>';
			   
$DateVisitList	.='<td id="ChangeButton_'.$row->id.'"><i data="'.$row->id.'" class="status_checks btn '.$StatusEs.'" "'.$NextFollowUpDisable.'">'.$ActiveINactive.'
  
 
 </td>';
 
 
$DateVisitList	.='<td id="ChangeFollowupButton_'.$row->id.'">';
if($ActiveINactive	==	'Open'){
$DateVisitList	.='<button data="'.$row->hotel_id.'" class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddNextFollowup('.$row->id.','.$row->visit_id.','.$row->hotel_id.');"   > 
<i data="'.$row->id.'" class="btn">Next</button>';
}else{
	$DateVisitList	.='<button data="'.$row->hotel_id.'" class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddNextFollowup('.$row->id.','.$row->visit_id.','.$row->hotel_id.');"  disabled > 
<i data="'.$row->id.'" class="btn">Next</button>';
	}
$DateVisitList	.='</td>';
$DateVisitList	.='<td>';
$DateVisitList	.='<a href="javascript://" onClick="showContent('.$Expand.','.$numRows.')">';
					
					
					
					
$DateVisitList	.='<i class="fa fa-th-list"></i>
 <input type="hidden" id="section_'.$Expand.'_img"  border="0">';
 
 $DateVisitList	.='</td>';

		
		
		
	
$DateVisitList	.='<tr>             <td colspan="9"> ';                 
                 $DateVisitList	.='<div id="div'.$Expand.'"></div>                 
                  
                  <div id="section_'.$Expand.'" style="display:none;">				  
				  
                <table id="example2" class="table table-bordered table-striped" style="background-color:#3C8DBC;">
                  <tr style="background-color:#3C8DBC; color:#fff;">
                    <th>S.No</th>                  
                    <th>Date</th>
                     <th>Follow Summary</th>
                                       
                  </tr>';
				  
				  
		$NextFollowUpSql = executeSql("SELECT  `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.*  FROM `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`  WHERE `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$row->id."' AND `visit_id` = '".$row->visit_id."'  ");
		
		if(num_rows($NextFollowUpSql) > 0){
		$FollwupNext=1;
		while($NextFollowRow = $db->fetch_assoc2($NextFollowUpSql)){
				
				$DateVisitList	.='<tr style="background-color:#3C8DBC; color:#fff;">
                    <th>'.$FollwupNext++.'</th>                  
                    <th>'.$NextFollowRow['dated'].'</th>
                     <th>'.$NextFollowRow['summary'].'</th>
                                        
                  </tr>';
				  
				  
					}
			
			}
               $DateVisitList	.='</table>
               
				  
				  
				  </div>';
				  
$DateVisitList	.='</td></tr>';				  
				  
				  
/*================Add Next Follow UP=================================*/	
$DateVisitList	.='<tr><td colspan="6"><div id="AddNextFollowup'.$row->id.'"></div></td></tr>';					
/*================Add Next Follow UP=================================*/		










				  }
				  
		}
               
			   
			    $DateVisitList	.=' </tr>
				
				
				
				</tbody>
              </table>
            </div>
          </form>';
         
      echo  $DateVisitList	.='</div>';
	  
	  }
        
?>
<script language="javascript">
 function showContent(content,numRows)
 {
	 
	 var content='section_'+content;
	 sections = new Array("section_1","section_2","section_3","section_4","section_5","section_6","section_7","section_8","section_9","section_10","section_11","section_12","section_13","section_14","section_15","section_16","section_17","section_18","section_19","section_20","section_21","section_22","section_23","section_24","section_25","section_26","section_27","section_28","section_29","section_30","section_31","section_32","section_33","section_34","section_35","section_36","section_37","section_38","section_39","section_40","section_41","section_42","section_43","section_44","section_45","section_46","section_47","section_48","section_49","section_50","section_51","section_52","section_53","section_54","section_55","section_56","section_57","section_58","section_59","section_60","section_61","section_62","section_63","section_64","section_65","section_66","section_67","section_68","section_69","section_70","section_71","section_72","section_73","section_74","section_75","section_76","section_77","section_78","section_79","section_80","section_81","section_82","section_83","section_84","section_85","section_86","section_87","section_88","section_89","section_90","section_91","section_92","section_93","section_94","section_95","section_96","section_97","section_98","section_99","section_100");
 
			 for(i=0; i<sections.length; i++){
				
						 if(document.getElementById(sections[i]).style.display == "none" && sections[i] == content){
						 document.getElementById(sections[i]).style.display = "block";
						 document.getElementById(sections[i]+"_img").src = "fa-minus";
						 }else{
						 document.getElementById(sections[i]).style.display = "none";
						 document.getElementById(sections[i]+"_img").src = "fa-plus";
						 }
			
			 }
			 
			
 }
 </script>

 
 <style type="text/css">
 a,a:active,a:focus{
 text-decoration: none;
 outline: none;
 color: #000000;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 }
 .heading{
 font-size: 13px;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 font-weight:bold;
 color: #000000;
 background-color: #EDEDED;
 border: 1px #D1D1D1 solid;
 list-style-type: disc;
 padding: 5px;
 margin-bottom:5px;
 margin-top:5px;
 height:15px;
 }
 </style>