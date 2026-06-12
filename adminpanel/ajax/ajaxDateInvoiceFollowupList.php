<?php  include_once("../../config/auto_loader.php");

/*echo "<pre>";
print_r($_SESSION);
echo "-------------request--------<br>";
print_r($_REQUEST);
echo "</pre>";
exit;*/

$SelectedDate	= date('Y-m-d',strtotime($_REQUEST['reservation_date']));


if($_REQUEST['FollowupCoditionType']=='addfollowup'){
	
	$followupCode	=	$_REQUEST['followupCode'];

	foreach( $followupCode as $FupCode){
		$_SESSION['followup_hotel_id'][$FupCode]	=	date('d-M-Y');
		$_SESSION['followup_description'][$FupCode]	=	$_REQUEST['followup_description'][$FupCode];
		$_SESSION['followup_date'][$FupCode]	=	$_REQUEST['followup_date'][$FupCode];
		$_SESSION['followupstatus'][$FupCode]	=	$_REQUEST['followupstatus'][$FupCode];
		$_SESSION['assign_followup_user_id'][$FupCode]=$_REQUEST['assign_followup_user_id'][$FupCode];
		$_SESSION['user_created_date'][$FupCode]	=	date('Y-m-d');
		$_SESSION['username'][$FupCode]	=	ucwords(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$_SESSION['userId']."' "));
		
	}

	$DateVisitList	='

<div class="box">

          

          <form id="listingForm" name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

        	

			<div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr>

                                   

                    <!--<th>Added On</th>-->

					<th>Action By</th>
                                    <th>Action Date</th>

                    <th>Follow Up Summary</th>

                    <th>Follow Up Date</th>

					<th>Status</th>

                    <th>Action</th>

					

                    

                  </tr>

                </thead>

                <tbody>';

	foreach($_SESSION['followup_hotel_id'] as $Followuphotel => $k){

	

	

	$DateVisitList	.='<tr>';

			   

			  // $DateVisitList	.='<td>'.$_SESSION['followup_hotel_id'][$Followuphotel].'</td>';

			  

			   $DateVisitList	.='<td>'.$_SESSION['username'][$Followuphotel].'</td>';
$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['user_created_date'][$Followuphotel])).'</td>';

			   $DateVisitList	.='<td>'.$_SESSION['followup_description'][$Followuphotel].'</td>';

			   

$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['followup_date'][$Followuphotel])).'</td>';

 if($_SESSION['followupstatus'][$Followuphotel]==2){
	 $followupstatusid='2';
	 $followupstatus	=	'Parcially Received';
	 
	 }elseif($_SESSION['followupstatus'][$Followuphotel]==1){
		  $followupstatus	=	'Pending ';
		   $followupstatusid='1';
		 }else{
			 $followupstatus	=	'Received ';
			  $followupstatusid='0';
			 }

 $DateVisitList	.='<td>'.($followupstatus).' </td>';





		  $DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$Followuphotel.'" onclick="ajaxFollowupRemove($(this).attr(\'id\'));");">

				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';

		

		$DateVisitList	.='</tr>';

	

   

			   }

			   

	

	echo $DateVisitList;

	

} else if($_REQUEST['FollowupCoditionType']=='addfeedback'){ //FEED BACK Start





	$followupCode	=	$_REQUEST['feedbackCode'];

	

	foreach( $followupCode as $FupCode){

	

	$_SESSION['feedback_hotel_id'][$FupCode]	=	$_REQUEST['feedback_hotel_id'][$FupCode];

	$_SESSION['feedback_description'][$FupCode]	=	$_REQUEST['feedback_description'][$FupCode];

	$_SESSION['feedback_date'][$FupCode]	=	$_REQUEST['feedback_date'][$FupCode];

	

		

	}

	$DateVisitList	='

<div class="box">

          

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

        	

			<div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr>

                                   

                    <th>Added on</th>					

                     <th>FeedBack Summary</th>

	                    <th> Date</th>					

                    <th>Action</th>

					

                    

                  </tr>

                </thead>

                <tbody>';

	foreach($_SESSION['feedback_hotel_id'] as $Followuphotel => $k){

	

	

	$DateVisitList	.='<tr>';

			   

			   $DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_SESSION['feedback_hotel_id'][$Followuphotel]."'").'</td>';

			  

			   

			   $DateVisitList	.='<td>'.$_SESSION['feedback_description'][$Followuphotel].'</td>';

			   

$DateVisitList	.='<td>'.date('d M Y',strtotime($_SESSION['feedback_date'][$Followuphotel])).'</td>';

 



		  $DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$Followuphotel.'" onclick="ajaxFeedBAckRemove($(this).attr(\'id\'));");">

				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';

		

		$DateVisitList	.='</tr>';

	

   

			   }

			   

	

	echo $DateVisitList;

	

	

	

	//FEED BACK END

	}else{

$DateVisitList	='

<div class="box">

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

               

							 				

$sql = " SELECT  `".TBL_DAILYVISIT_FOLLOWUP."`.*  FROM `".TBL_DAILYVISIT_FOLLOWUP."` WHERE `".TBL_DAILYVISIT_FOLLOWUP."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `dated` = '".date('Y-m-d',strtotime($SelectedDate))."'  ";



$db->query($sql);

$numRows= $db->num_rows();

$pagging = new pagingClass($sql,$setpage);

$db->query($pagging->getQuery());

$total = $db->num_rows();



if($total > 0){$counter = 1;

				

				$Expand = 0;

				  while($row = $db->fetch_object()){

					  

					

					  $Expand++;	  

	if($row->followup_status == 1){

   $StatusEs	=	'btn-success';

   $ActiveINactive	=	"Open";

   

  }if($row->followup_status == 0){

   $StatusEs	=	   'btn-danger';

   $ActiveINactive	=	"Close";

    $NextFollowUpDisable	= "disabled";  

  }

  

  

  $id_company	= selectColumn(TBL_DAILYVISIT,'id_company'," WHERE `id` = '".$row->daily_Visit_id ."'");

  $id_contacts	= selectColumn(TBL_DAILYVISIT,'id_contacts'," WHERE `id` = '".$row->daily_Visit_id ."'");

  

  

			   $DateVisitList	.='<tr>';

			   $DateVisitList	.='<td>'.$counter++.'</td>';

			   $DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'").'</td>';

			   $DateVisitList	.='<td>'.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$id_company."'").'</td>';

			   

			   $DateVisitList	.='<td id="ChangeFollowUpSummary_'.$row->id.'">'.$row->followup_close_summary.'</td>';

			   

$DateVisitList	.='<td id="ChangeButton_'.$row->id.'"><i data="'.$row->id.'" class="status_checks btn '.$StatusEs.'" "'.$NextFollowUpDisable.'">'.$ActiveINactive.'

  

 

 </td>';

 

 

$DateVisitList	.='<td id="ChangeFollowupButton_'.$row->id.'">';

if($ActiveINactive	==	'Open'){

$DateVisitList	.='<button data="'.$row->hotel_id.'" class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddNextFollowup('.$row->id.','.$row->daily_Visit_id.','.$row->hotel_id.');"   > 

<i data="'.$row->id.'" class="btn">Next</button>';

}else{

	$DateVisitList	.='<button data="'.$row->hotel_id.'" class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddNextFollowup('.$row->id.','.$row->daily_Visit_id.','.$row->hotel_id.');"  disabled > 

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

				  

				  

		$NextFollowUpSql = executeSql(" SELECT  `".TBL_DAILYVISIT_NEXTFOLLOWUP."`.*  FROM `".TBL_DAILYVISIT_NEXTFOLLOWUP."`  WHERE `".TBL_DAILYVISIT_NEXTFOLLOWUP."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `fs_daily_visit_followup` = '".$row->id."' AND `daily_Visit_id` = '".$row->daily_Visit_id."'  ");

		

		if(num_rows($NextFollowUpSql) > 0){

		$FollwupNext=1;

		while($NextFollowRow = $db->fetch_assoc2($NextFollowUpSql)){

				

				$DateVisitList	.='<tr style="background-color:#3C8DBC; color:#fff;">

                    <th>'.$FollwupNext++.'</th>                  

                    <th>'.$NextFollowRow['dated'].'</th>

                     <th>'.$NextFollowRow['followup_summary'].'</th>

                                        

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